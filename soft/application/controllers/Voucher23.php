<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');

class Voucher extends CI_Controller {

public function __construct()
  {
  parent::__construct();
  $this->load->model("prime_model","pm");
  $this->checkPermission();
}

public function index()
  {
  $data['title'] = 'Received Voucher';

  $where = array(
    'vauchertype' => 'Credit Voucher'
        );
  $other = array(
    'order_by' => 'vuid',
    'join' => 'left'
        );
  $field = array(
    'vaucher' => 'vaucher.*',
    'customers' => 'customers.custName,customers.custCode'
        );
  $join = array(
    'customers' => 'customers.custid = vaucher.custid'
        );

  $data['vaucher'] = $this->pm->get_data('vaucher',$where,$field,$join,$other);
  
  $this->load->view('vouchers/voucher',$data);
}

public function new_received_voucher()
  {
  $data['title'] = 'Received Voucher';
                
  $data['customer'] = $this->pm->get_data('customers',false);

  $this->load->view('vouchers/addvoucher',$data);
}

public function getAccountNo()
  {
  $str = '';
  $where = array(
    'status' => 'Active',
        );

  $accountType = $this->input->post('id');
  if($accountType == 'Bank')
    {
    $accounts = $this->pm->get_data('bankaccount',$where);
    if(count($accounts) == 0)
      {
      $str .= "<option value='0'>Please Add Bank account</option>";
      } 
    else 
      {
      foreach($accounts as $value)
        {
        $str .= "<option value='".$value['ba_id']."'>".$value['bankName'].' '.$value['branchName'].' '.$value['accountNo'].' '.$value['accountName']."</option>";
        }
      }
    } 
  else if($accountType == 'Mobile')
    {
    $accounts = $this->pm->get_data('mobileaccount',$where);
    if(count($accounts) == 0) 
      {
      $str .= "<option value='0'>Please Add mobile account</option>";
      } 
    else 
      {
      foreach($accounts as $value)
        {
        $str .= "<option value='".$value['ma_id']."'>".$value['accountName'].' '.$value['accountNo']."</option>";
        }
      }
    } 
  else if($accountType == 'Cash') 
    {
    $accounts = $this->pm->get_data('cash',$where);
    if(count($accounts) == 0) 
      {
      $str .= "<option value='0'>Please Add cash account</option>";
      } 
    else 
      {
      foreach($accounts as $value)
        {
        $str .= "<option value='".$value['ca_id']."'>".$value['cashName']."</option>";
        }
      }
    } 
  else 
    {
    $str .= "<option >Please account Type</option>";
    }
  echo json_encode($str);
}

public function save_voucher()
  {
  $info = $this->input->post();

  $query = $this->db->select('vuid')
                ->from('vaucher')
                //->where('compid',$_SESSION['compid'])
                ->limit(1)
                ->order_by('vuid','DESC')
                ->get()
                ->row();
  if($query)
    {
    $sn = $query->vuid+1;
    }
  else
    {
    $sn = 1;
    }

  $cn = strtoupper(substr($_SESSION['compname'],0,3));
  $pc = sprintf("%'05d", $sn);

  $cusid = 'V-'.$cn.$pc;

  $data = array(
    'compid'      => $_SESSION['compid'],
    'invoice'     => $cusid,
    'vuDate'      => date('Y-m-d',strtotime($info['date'])),
    'custid'      => $info['customerID'],
    'vauchertype' => 'Credit Voucher',
    'tAmount'     => array_sum($info['amount']),
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'notes'       => $info['note'],
    'regby'       => $_SESSION['uid']
          );
    
  $result = $this->pm->insert_data('vaucher',$data);
    //var_dump($vid); exit();
  $length = count($info['amount']);
    //var_dump($length); exit();
  for($i = 0; $i < $length; $i++)
    {
    $partdata = array(
      'vuid'        => $result,
      'particulars' => $info['particular'][$i],
      'amount'      => $info['amount'][$i],
      'regby'       => $_SESSION['uid']
          );
    //var_dump($partdata);    
    $result2 = $this->pm->insert_data('vaucher_particular',$partdata); 
    }
    
    if($info['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($info['accountNo']);
      
      $cdata = array(
        'current' => $cash->current+array_sum($info['amount']),
        'upby'    => $_SESSION['uid']
                );
      
      $cwhere = array(
        'ca_id' => $info['accountNo']
            );
      $this->pm->update_data('cash',$cdata,$cwhere);
      }
    else if($info['accountType'] == 'Bank')
      {
      $bank = $this->pm->get_bank_account($info['accountNo']);
      
      $bdata = array(
        'current' => $bank->current+array_sum($info['amount']),
        'upby'    => $_SESSION['uid']
                );
      
      $bwhere = array(
        'ba_id' => $info['accountNo']
            );
      $this->pm->update_data('bankaccount',$bdata,$bwhere);
      }
    else if($info['accountType'] == 'Mobile')
      {
      $mobile = $this->pm->get_mobile_transaction($info['accountNo']);
      
      $mdata = array(
        'current' => $mobile->current+array_sum($info['amount']),
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $info['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }

  if($result && $result2)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Received Voucher Add Successfully !</h4>
        </div>'
            ];  
    }
  else
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-ban"></i> Failed !</h4>
        </div>'
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('rVoucher');
}

public function received_voucher_details($id)
  {
  $data['title'] = 'Received Voucher';

  $where = array(
    'vuid' => $id
        );
  $other = array(
    'join' => 'left'
        );
  $field = array(
    'vaucher' => 'vaucher.*',
    'customers' => 'customers.*'
        );
  $join = array(
    'customers' => 'customers.custid = vaucher.custid'
        );

  $voucher = $this->pm->get_data('vaucher',$where,$field,$join,$other);
  $data['voucher'] = $voucher[0];

  $data['voucherp'] = $this->pm->get_data('vaucher_particular',$where);
  $data['company'] = $this->pm->company_details();

  $this->load->view('vouchers/viewvoucher',$data);
}

public function received_voucher_edit($id)
  {
  $data['title'] = 'Received Voucher';

  $data['customer'] = $this->pm->get_data('customers',false);

  $where = array(
    'vuid' => $id
        );

  $voucher = $this->pm->get_data('vaucher',$where);
  $data['voucher'] = $voucher[0];

  $data['voucherp'] = $this->pm->get_data('vaucher_particular',$where);

  $this->load->view('vouchers/editvoucher',$data);
}

public function update_received_voucher()
  {
  $info = $this->input->post();

  $where = array(
    'vuid' => $info['vuid']
        );
    
  $data = array(
    'vuDate'      => date('Y-m-d',strtotime($info['date'])),
    'compid'      => $_SESSION['compid'],
    'custid'      => $info['customerID'],
    'tAmount'     => array_sum($info['amount']),
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'notes'       => $info['note'],
    'upby'        => $_SESSION['uid']
            );
  $ppurchase = $this->pm->get_data('vaucher',$where);
  $result = $this->pm->update_data('vaucher',$data,$where);
    //var_dump($vid); exit();
  $this->pm->delete_data('vaucher_particular',$where);

  $length = count($info['amount']);
    //var_dump($length); exit();
  for($i = 0; $i < $length; $i++)
    {
    $partdata = array(
      'vuid'     => $info['vuid'],
      'particulars' => $info['particular'][$i],
      'amount'   => $info['amount'][$i],
      'upby'     => $_SESSION['uid']
            );
        //var_dump($partdata);    
    $result2 = $this->pm->insert_data('vaucher_particular',$partdata);
    }
    
    if($ppurchase[0]['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($ppurchase[0]['accountNo']);
      
      $cdata = array(
        'current' => $cash->current-$ppurchase[0]['tAmount'],
        'upby'    => $_SESSION['uid']
                );
      
      $cwhere = array(
        'ca_id' => $ppurchase[0]['accountNo']
            );
      $this->pm->update_data('cash',$cdata,$cwhere);
      }
    else if($ppurchase[0]['accountType'] == 'Bank')
      {
      $bank = $this->pm->get_bank_account($ppurchase[0]['accountNo']);
      
      $bdata = array(
        'current' => $bank->current-$ppurchase[0]['tAmount'],
        'upby'    => $_SESSION['uid']
                );
      
      $bwhere = array(
        'ba_id' => $ppurchase[0]['accountNo']
            );
      $this->pm->update_data('bankaccount',$bdata,$bwhere);
      }
    else if($ppurchase[0]['accountType'] == 'Mobile')
      {
      $mobile = $this->pm->get_mobile_transaction($ppurchase[0]['accountNo']);
      
      $mdata = array(
        'current' => $mobile->current-$ppurchase[0]['tAmount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $ppurchase[0]['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }
    
    if($info['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($info['accountNo']);
      
      $cdata = array(
        'current' => $cash->current+array_sum($info['amount']),
        'upby'    => $_SESSION['uid']
                );
      
      $cwhere = array(
        'ca_id' => $info['accountNo']
            );
      $this->pm->update_data('cash',$cdata,$cwhere);
      }
    else if($info['accountType'] == 'Bank')
      {
      $bank = $this->pm->get_bank_account($info['accountNo']);
      
      $bdata = array(
        'current' => $bank->current+array_sum($info['amount']),
        'upby'    => $_SESSION['uid']
                );
      
      $bwhere = array(
        'ba_id' => $info['accountNo']
            );
      $this->pm->update_data('bankaccount',$bdata,$bwhere);
      }
    else if($info['accountType'] == 'Mobile')
      {
      $mobile = $this->pm->get_mobile_transaction($info['accountNo']);
      
      $mdata = array(
        'current' => $mobile->current+array_sum($info['amount']),
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $info['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }

  if($result && $result2)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Received Voucher update Successfully !</h4>
        </div>'
            ];  
    }
  else
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-ban"></i> Failed !</h4>
        </div>'
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('rVoucher');
}

public function received_voucher_delete($id)
  {
  $where = array(
    'vuid' => $id
        );
  $ppurchase = $this->pm->get_data('vaucher',$where);
  $result = $this->pm->delete_data('vaucher',$where);
    //var_dump($vid); exit();
  $this->pm->delete_data('vaucher_particular',$where);
    
    if($ppurchase[0]['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($ppurchase[0]['accountNo']);
      
      $cdata = array(
        'current' => $cash->current-$ppurchase[0]['tAmount'],
        'upby'    => $_SESSION['uid']
                );
      
      $cwhere = array(
        'ca_id' => $ppurchase[0]['accountNo']
            );
      $this->pm->update_data('cash',$cdata,$cwhere);
      }
    else if($ppurchase[0]['accountType'] == 'Bank')
      {
      $bank = $this->pm->get_bank_account($ppurchase[0]['accountNo']);
      
      $bdata = array(
        'current' => $bank->current-$ppurchase[0]['tAmount'],
        'upby'    => $_SESSION['uid']
                );
      
      $bwhere = array(
        'ba_id' => $ppurchase[0]['accountNo']
            );
      $this->pm->update_data('bankaccount',$bdata,$bwhere);
      }
    else if($ppurchase[0]['accountType'] == 'Mobile')
      {
      $mobile = $this->pm->get_mobile_transaction($ppurchase[0]['accountNo']);
      
      $mdata = array(
        'current' => $mobile->current-$ppurchase[0]['tAmount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $ppurchase[0]['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }
      
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Received Voucher delete Successfully !</h4>
        </div>'
            ];  
    }
  else
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-ban"></i> Failed !</h4>
        </div>'
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('rVoucher');
}

public function payment_voucher_list()
  {
  $data['title'] = 'Payment Voucher';

  $where = array(
    'vauchertype' => 'Debit Voucher'
        );
  $other = array(
    'order_by' => 'vuid',
    'join' => 'left'
        );
  $field = array(
    'vaucher' => 'vaucher.*',
    'suppliers' => 'suppliers.supCode,suppliers.supName'
        );
  $join = array(
    'suppliers' => 'suppliers.supid = vaucher.supid'
        );

  $data['vaucher'] = $this->pm->get_data('vaucher',$where,$field,$join,$other);
  
  $this->load->view('vouchers/payment_voucher',$data);
}

public function new_payment_voucher()
  {
  $data['title'] = 'Payment Voucher';
                
  $data['supplier'] = $this->pm->get_data('suppliers',false);

  $this->load->view('vouchers/new_pvoucher',$data);
}

public function save_payment_voucher()
  {
  $info = $this->input->post();

  $query = $this->db->select('vuid')
                ->from('vaucher')
                //->where('compid',$_SESSION['compid'])
                ->limit(1)
                ->order_by('vuid','DESC')
                ->get()
                ->row();
  if($query)
    {
    $sn = $query->vuid+1;
    }
  else
    {
    $sn = 1;
    }

  $cn = strtoupper(substr($_SESSION['compname'],0,3));
  $pc = sprintf("%'05d", $sn);

  $cusid = 'V-'.$cn.$pc;

  $data = array(
    'compid'      => $_SESSION['compid'],
    'invoice'     => $cusid,
    'vuDate'      => date('Y-m-d',strtotime($info['date'])),
    'supid'       => $info['supplier'],
    'vauchertype' => 'Debit Voucher',
    'tAmount'     => array_sum($info['amount']),
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'notes'       => $info['note'],
    'regby'       => $_SESSION['uid']
          );
    
  $result = $this->pm->insert_data('vaucher',$data);
    //var_dump($vid); exit();
  $length = count($info['amount']);
    //var_dump($length); exit();
  for($i = 0; $i < $length; $i++)
    {
    $partdata = array(
      'vuid'     => $result,
      'particulars' => $info['particular'][$i],
      'amount'   => $info['amount'][$i],
      'regby'    => $_SESSION['uid']
          );
    //var_dump($partdata);    
    $result2 = $this->pm->insert_data('vaucher_particular',$partdata); 
    }
    
    if($info['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($info['accountNo']);
      
      $cdata = array(
        'current' => $cash->current-array_sum($info['amount']),
        'upby'    => $_SESSION['uid']
                );
      
      $cwhere = array(
        'ca_id' => $info['accountNo']
            );
      $this->pm->update_data('cash',$cdata,$cwhere);
      }
    else if($info['accountType'] == 'Bank')
      {
      $bank = $this->pm->get_bank_account($info['accountNo']);
      
      $bdata = array(
        'current' => $bank->current-array_sum($info['amount']),
        'upby'    => $_SESSION['uid']
                );
      
      $bwhere = array(
        'ba_id' => $info['accountNo']
            );
      $this->pm->update_data('bankaccount',$bdata,$bwhere);
      }
    else if($info['accountType'] == 'Mobile')
      {
      $mobile = $this->pm->get_mobile_transaction($info['accountNo']);
      
      $mdata = array(
        'current' => $mobile->current-array_sum($info['amount']),
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $info['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }

  if($result && $result2)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Payment Voucher Add Successfully !</h4>
        </div>'
            ];  
    }
  else
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-ban"></i> Failed !</h4>
        </div>'
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('pVoucher');
}

public function payment_voucher_details($id)
  {
  $data['title'] = 'Payment Voucher';

  $where = array(
    'vuid' => $id
        );
  $other = array(
    'join' => 'left'
        );
  $field = array(
    'vaucher' => 'vaucher.*',
    'suppliers' => 'suppliers.*'
        );
  $join = array(
    'suppliers' => 'suppliers.supid = vaucher.supid'
        );

  $voucher = $this->pm->get_data('vaucher',$where,$field,$join,$other);
  $data['voucher'] = $voucher[0];

  $data['voucherp'] = $this->pm->get_data('vaucher_particular',$where);
  $data['company'] = $this->pm->company_details();

  $this->load->view('vouchers/view_pvoucher',$data);
}

public function payment_voucher_edit($id)
  {
  $data['title'] = 'Payment Voucher';

  $data['supplier'] = $this->pm->get_data('suppliers',false);

  $where = array(
    'vuid' => $id
        );

  $voucher = $this->pm->get_data('vaucher',$where);
  $data['voucher'] = $voucher[0];

  $data['voucherp'] = $this->pm->get_data('vaucher_particular',$where);

  $this->load->view('vouchers/edit_pvoucher',$data);
}

public function update_payment_voucher()
  {
  $info = $this->input->post();

  $where = array(
    'vuid' => $info['vuid']
        );
    
  $data = array(
    'compid'      => $_SESSION['compid'],
    'vuDate'      => date('Y-m-d',strtotime($info['date'])),
    'supid'       => $info['supplier'],
    'tAmount'     => array_sum($info['amount']),
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'notes'       => $info['note'],
    'upby'        => $_SESSION['uid']
            );
  $ppurchase = $this->pm->get_data('vaucher',$where);
  $result = $this->pm->update_data('vaucher',$data,$where);
    //var_dump($vid); exit();
  $this->pm->delete_data('vaucher_particular',$where);

  $length = count($info['amount']);
    //var_dump($length); exit();
  for($i = 0; $i < $length; $i++)
    {
    $partdata = array(
      'vuid'     => $info['vuid'],
      'particulars' => $info['particular'][$i],
      'amount'   => $info['amount'][$i],
      'upby'     => $_SESSION['uid']
            );
        //var_dump($partdata);    
    $result2 = $this->pm->insert_data('vaucher_particular',$partdata);
    }
    
    if($ppurchase[0]['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($ppurchase[0]['accountNo']);
      
      $cdata = array(
        'current' => $cash->current+$ppurchase[0]['tAmount'],
        'upby'    => $_SESSION['uid']
                );
      
      $cwhere = array(
        'ca_id' => $ppurchase[0]['accountNo']
            );
      $this->pm->update_data('cash',$cdata,$cwhere);
      }
    else if($ppurchase[0]['accountType'] == 'Bank')
      {
      $bank = $this->pm->get_bank_account($ppurchase[0]['accountNo']);
      
      $bdata = array(
        'current' => $bank->current+$ppurchase[0]['tAmount'],
        'upby'    => $_SESSION['uid']
                );
      
      $bwhere = array(
        'ba_id' => $ppurchase[0]['accountNo']
            );
      $this->pm->update_data('bankaccount',$bdata,$bwhere);
      }
    else if($ppurchase[0]['accountType'] == 'Mobile')
      {
      $mobile = $this->pm->get_mobile_transaction($ppurchase[0]['accountNo']);
      
      $mdata = array(
        'current' => $mobile->current+$ppurchase[0]['tAmount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $ppurchase[0]['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }
    
    if($info['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($info['accountNo']);
      
      $cdata = array(
        'current' => $cash->current-array_sum($info['amount']),
        'upby'    => $_SESSION['uid']
                );
      
      $cwhere = array(
        'ca_id' => $info['accountNo']
            );
      $this->pm->update_data('cash',$cdata,$cwhere);
      }
    else if($info['accountType'] == 'Bank')
      {
      $bank = $this->pm->get_bank_account($info['accountNo']);
      
      $bdata = array(
        'current' => $bank->current-array_sum($info['amount']),
        'upby'    => $_SESSION['uid']
                );
      
      $bwhere = array(
        'ba_id' => $info['accountNo']
            );
      $this->pm->update_data('bankaccount',$bdata,$bwhere);
      }
    else if($info['accountType'] == 'Mobile')
      {
      $mobile = $this->pm->get_mobile_transaction($info['accountNo']);
      
      $mdata = array(
        'current' => $mobile->current-array_sum($info['amount']),
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $info['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }

  if($result && $result2)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Payment Voucher update Successfully !</h4>
        </div>'
            ];  
    }
  else
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-ban"></i> Failed !</h4>
        </div>'
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('pVoucher');
}

public function payment_voucher_delete($id)
  {
  $where = array(
    'vuid' => $id
        );
  $ppurchase = $this->pm->get_data('vaucher',$where);
  $result = $this->pm->delete_data('vaucher',$where);
    //var_dump($vid); exit();
  $this->pm->delete_data('vaucher_particular',$where);
  
    if($ppurchase[0]['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($ppurchase[0]['accountNo']);
      
      $cdata = array(
        'current' => $cash->current+$ppurchase[0]['tAmount'],
        'upby'    => $_SESSION['uid']
                );
      
      $cwhere = array(
        'ca_id' => $ppurchase[0]['accountNo']
            );
      $this->pm->update_data('cash',$cdata,$cwhere);
      }
    else if($ppurchase[0]['accountType'] == 'Bank')
      {
      $bank = $this->pm->get_bank_account($ppurchase[0]['accountNo']);
      
      $bdata = array(
        'current' => $bank->current+$ppurchase[0]['tAmount'],
        'upby'    => $_SESSION['uid']
                );
      
      $bwhere = array(
        'ba_id' => $ppurchase[0]['accountNo']
            );
      $this->pm->update_data('bankaccount',$bdata,$bwhere);
      }
    else if($ppurchase[0]['accountType'] == 'Mobile')
      {
      $mobile = $this->pm->get_mobile_transaction($ppurchase[0]['accountNo']);
      
      $mdata = array(
        'current' => $mobile->current+$ppurchase[0]['tAmount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $ppurchase[0]['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Payment Voucher delete Successfully !</h4>
        </div>'
            ];  
    }
  else
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-ban"></i> Failed !</h4>
        </div>'
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('pVoucher');
}

public function voucher_received_report()
  {
  $data = ['title' => 'Voucher Received'];

  if(isset($_GET['search']))
    {
    $report = $_GET['reports'];
        
    if($report == 'dailyReports')
      {
      $sdate = date("Y-m-d", strtotime($_GET['sdate']));
      $edate = date("Y-m-d", strtotime($_GET['edate']));
      $data['sdate'] = $sdate;
      $data['edate'] = $edate;
      $data['report'] = $report;
      $vtype = 'All';
      
      $data['voucher'] = $this->pm->get_dall_voucher_data($sdate,$edate,$vtype);
      }
    else if ($report == 'monthlyReports')
      {
      $month = $_GET['month'];
      $data['month'] = $month;
      $year = $_GET['year'];
      $data['year'] = $year;
            //var_dump($data['month']); exit();
      if($month == 1)
        {
        $name = 'January';
        }
      elseif ($month == 2)
        {
        $name = 'February';
        }
      elseif ($month == 3)
        {
        $name = 'March';
        }
      elseif ($month == 4)
        {
        $name = 'April';
        }
      elseif ($month == 5)
        {
        $name = 'May';
        }
      elseif ($month == 6)
        {
        $name = 'June';
        }
      elseif ($month == 7)
        {
        $name = 'July';
        }
      elseif ($month == 8)
        {
        $name = 'August';
        }
      elseif ($month == 9)
        {
        $name = 'September';
        }
      elseif ($month == 10)
        {
        $name = 'October';
        }
      elseif ($month == 11)
        {
        $name = 'November';
        }
      else
        {
        $name = 'December';
        }

      $data['name'] = $name;
      $data['report'] = $report;
      $vtype = 'All';

      $data['voucher'] = $this->pm->get_mall_voucher_data($month,$year,$vtype);
      }
    else if($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $data['year'] = $year;
      $data['report'] = $report;
      $vtype = 'All';
      $data['voucher'] = $this->pm->get_yall_voucher_data($year,$vtype);
      }
    }
  else
    {
    $data['voucher'] = $this->pm->get_voucher_data();
    }

  $this->load->view('vouchers/voucher_reports',$data);
}

public function voucher_payment_report()
  {
  $data = ['title' => 'Voucher Payment'];

  if(isset($_GET['search']))
    {
    $report = $_GET['reports'];
        
    if($report == 'dailyReports')
      {
      $sdate = date("Y-m-d", strtotime($_GET['sdate']));
      $edate = date("Y-m-d", strtotime($_GET['edate']));
      $data['sdate'] = $sdate;
      $data['edate'] = $edate;
      $data['report'] = $report;

      $data['voucher'] = $this->pm->get_dall_pvoucher_data($sdate,$edate);
      }
    else if ($report == 'monthlyReports')
      {
      $month = $_GET['month'];
      $data['month'] = $month;
      $year = $_GET['year'];
      $data['year'] = $year;
            //var_dump($data['month']); exit();
      if($month == 1)
        {
        $name = 'January';
        }
      elseif ($month == 2)
        {
        $name = 'February';
        }
      elseif ($month == 3)
        {
        $name = 'March';
        }
      elseif ($month == 4)
        {
        $name = 'April';
        }
      elseif ($month == 5)
        {
        $name = 'May';
        }
      elseif ($month == 6)
        {
        $name = 'June';
        }
      elseif ($month == 7)
        {
        $name = 'July';
        }
      elseif ($month == 8)
        {
        $name = 'August';
        }
      elseif ($month == 9)
        {
        $name = 'September';
        }
      elseif ($month == 10)
        {
        $name = 'October';
        }
      elseif ($month == 11)
        {
        $name = 'November';
        }
      else
        {
        $name = 'December';
        }

      $data['name'] = $name;
      $data['report'] = $report;

      $data['voucher'] = $this->pm->get_mall_pvoucher_data($month,$year);
      }
    else if($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $data['year'] = $year;
      $data['report'] = $report;

      $data['voucher'] = $this->pm->get_yall_pvoucher_data($year);
      }
    }
  else
    {
    $data['voucher'] = $this->pm->get_pvoucher_data();
    }

  $this->load->view('vouchers/voucher_preports',$data);
}

public function profit_loss_report()
  {
  $data['title'] = 'Profit Loss Report';
  $data['company'] = $this->pm->company_details();

  if(isset($_GET['search']))
    {
    $report = $_GET['reports'];
    
    if($report == 'dailyReports')
      {
      $sdate = date("Y-m-d", strtotime($_GET['sdate']));
      $edate = date("Y-m-d", strtotime($_GET['edate']));
      $data['sdate'] = $sdate;
      $data['edate'] = $edate;
      $data['report'] = $report;

      $data['sale'] = $this->pm->total_dsales_amount($sdate,$edate);
      $data['purchase'] = $this->pm->total_dpurchases_amount($sdate,$edate);
      $data['empp'] = $this->pm->total_demp_payments_amount($sdate,$edate);
      $data['return'] = $this->pm->total_dreturns_amount($sdate,$edate);
      $data['cvoucher'] = $this->pm->total_dcvoucher_amount($sdate,$edate);
      $data['dvoucher'] = $this->pm->total_ddvoucher_amount($sdate,$edate);
      $data['cusvoucher'] = $this->pm->total_dcusvoucher_amount($sdate,$edate);
      $data['svoucher'] = $this->pm->total_dsvoucher_amount($sdate,$edate);
      }
        else if ($report == 'monthlyReports')
            {
            $month = $_GET['month'];
            $data['month'] = $month;
            $year = $_GET['year'];
            $data['year'] = $year;
            //var_dump($data['month']); exit();
            if($month == 01)
                {
                $name = 'January';
                }
            elseif ($month == 02)
                {
                $name = 'February';
                }
            elseif ($month == 03)
                {
                $name = 'March';
                }
            elseif ($month == 04)
                {
                $name = 'April';
                }
            elseif ($month == 05)
                {
                $name = 'May';
                }
            elseif ($month == 06)
                {
                $name = 'June';
                }
            elseif ($month == 07)
                {
                $name = 'July';
                }
            elseif ($month == 8)
                {
                $name = 'August';
                }
            elseif ($month == 9)
                {
                $name = 'September';
                }
            elseif ($month == 10)
                {
                $name = 'October';
                }
            elseif ($month == 11)
                {
                $name = 'November';
                }
            else
                {
                $name = 'December';
                }
            $data['name'] = $name;
            $data['report'] = $report;

            $data['sale'] = $this->pm->total_msales_amount($month,$year);
            $data['purchase'] = $this->pm->total_mpurchases_amount($month,$year);
            $data['empp'] = $this->pm->total_memp_payments_amount($month,$year);
            $data['return'] = $this->pm->total_mreturns_amount($month,$year);
            $data['cvoucher'] = $this->pm->total_mcvoucher_amount($month,$year);
            $data['dvoucher'] = $this->pm->total_mdvoucher_amount($month,$year);
            $data['cusvoucher'] = $this->pm->total_mcusvoucher_amount($month,$year);
            $data['svoucher'] = $this->pm->total_msvoucher_amount($month,$year);
            }
        else if ($report == 'yearlyReports')
            {
            $year = $_GET['ryear'];
            $data['year'] = $year;
            $data['report'] = $report;

            $data['sale'] = $this->pm->total_ysales_amount($year);
            $data['purchase'] = $this->pm->total_ypurchases_amount($year);
            $data['empp'] = $this->pm->total_yemp_payments_amount($year);
            $data['return'] = $this->pm->total_yreturns_amount($year);
            $data['cvoucher'] = $this->pm->total_ycvoucher_amount($year);
            $data['dvoucher'] = $this->pm->total_ydvoucher_amount($year);
            $data['cusvoucher'] = $this->pm->total_ycusvoucher_amount($year);
            $data['svoucher'] = $this->pm->total_ysvoucher_amount($year);
            }
        }
    else
        {
        $data['sale'] = $this->pm->total_sales_amount();
        $data['purchase'] = $this->pm->total_purchases_amount();
        $data['empp'] = $this->pm->total_emp_payments_amount();
        $data['return'] = $this->pm->total_returns_amount();
        $data['cvoucher'] = $this->pm->total_cvoucher_amount();
        $data['dvoucher'] = $this->pm->total_dvoucher_amount();
        $data['svoucher'] = $this->pm->total_svoucher_amount();
        }

    $this->load->view('vouchers/profit_loss',$data);
}

public function profit_loss()
  {
  $data['title'] = 'Profit Loss';
  $data['company'] = $this->pm->company_details();

  if(isset($_GET['search']))
    {
    $report = $_GET['reports'];
    
    if($report == 'dailyReports')
      {
      $sdate = date("Y-m-d", strtotime($_GET['sdate']));
      $edate = date("Y-m-d", strtotime($_GET['edate']));
      $data['sdate'] = $sdate;
      $data['edate'] = $edate;
      $data['report'] = $report;

      $data['sale'] = $this->pm->total_dsales_amount($sdate,$edate);
      $data['purchase'] = $this->pm->total_dpurchases_amount($sdate,$edate);
      $data['empp'] = $this->pm->total_demp_payments_amount($sdate,$edate);
      $data['return'] = $this->pm->total_dreturns_amount($sdate,$edate);
      $data['cvoucher'] = $this->pm->total_dcvoucher_amount($sdate,$edate);
      $data['dvoucher'] = $this->pm->total_ddvoucher_amount($sdate,$edate);
      $data['svoucher'] = $this->pm->total_dsvoucher_amount($sdate,$edate);
      }
    else if($report == 'monthlyReports')
      {
      $month = $_GET['month'];
      $data['month'] = $month;
      $year = $_GET['year'];
      $data['year'] = $year;
            //var_dump($data['month']); exit();
      if($month == 01)
        {
        $name = 'January';
        }
      else if($month == 02)
        {
        $name = 'February';
        }
      else if($month == 03)
        {
        $name = 'March';
        }
      else if($month == 04)
        {
        $name = 'April';
        }
      else if($month == 05)
        {
        $name = 'May';
        }
      else if($month == 06)
        {
        $name = 'June';
        }
      else if($month == 07)
        {
        $name = 'July';
        }
      else if($month == 8)
        {
        $name = 'August';
        }
      else if($month == 9)
        {
        $name = 'September';
        }
      else if($month == 10)
        {
        $name = 'October';
        }
      else if($month == 11)
        {
        $name = 'November';
        }
      else
        {
        $name = 'December';
        }
      $data['name'] = $name;
      $data['report'] = $report;

      $data['sale'] = $this->pm->total_msales_amount($month,$year);
      $data['purchase'] = $this->pm->total_mpurchases_amount($month,$year);
      $data['empp'] = $this->pm->total_memp_payments_amount($month,$year);
      $data['return'] = $this->pm->total_mreturns_amount($month,$year);
      $data['cvoucher'] = $this->pm->total_mcvoucher_amount($month,$year);
      $data['dvoucher'] = $this->pm->total_mdvoucher_amount($month,$year);
      $data['svoucher'] = $this->pm->total_msvoucher_amount($month,$year);
      }
    else if ($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $data['year'] = $year;
      $data['report'] = $report;

      $data['sale'] = $this->pm->total_ysales_amount($year);
      $data['purchase'] = $this->pm->total_ypurchases_amount($year);
      $data['empp'] = $this->pm->total_yemp_payments_amount($year);
      $data['return'] = $this->pm->total_yreturns_amount($year);
      $data['cvoucher'] = $this->pm->total_ycvoucher_amount($year);
      $data['dvoucher'] = $this->pm->total_ydvoucher_amount($year);
      $data['svoucher'] = $this->pm->total_ysvoucher_amount($year);
      }
    }
  else
    {
    $data['sale'] = $this->pm->total_sales_amount();
    $data['purchase'] = $this->pm->total_purchases_amount();
    $data['empp'] = $this->pm->total_emp_payments_amount();
    $data['return'] = $this->pm->total_returns_amount();
    $data['cvoucher'] = $this->pm->total_cvoucher_amount();
    $data['dvoucher'] = $this->pm->total_dvoucher_amount();
    $data['svoucher'] = $this->pm->total_svoucher_amount();
    }

  $this->load->view('vouchers/profit_loss',$data);
}

public function sale_purchase_profit_report()
  {
  $data['title'] = 'Sale Purchase Report';

  if(isset($_GET['search']))
    {
    $report = $_GET['reports'];
    
    if($report == 'dailyReports')
      {
      $sdate = date("Y-m-d", strtotime($_GET['sdate']));
      $edate = date("Y-m-d", strtotime($_GET['edate']));
      $data['sdate'] = $sdate;
      $data['edate'] = $edate;
      $data['report'] = $report;

      $data['salep'] = $this->pm->total_dsales_product($sdate,$edate);
      }
    else if ($report == 'monthlyReports')
      {
      $month = $_GET['month'];
      $data['month'] = $month;
      $year = $_GET['year'];
      $data['year'] = $year;
            //var_dump($data['month']); exit();
      if($month == 01)
        {
        $name = 'January';
        }
      else if($month == 02)
        {
        $name = 'February';
        }
      else if($month == 03)
        {
        $name = 'March';
        }
      else if($month == 04)
        {
        $name = 'April';
        }
      else if($month == 05)
        {
        $name = 'May';
        }
      else if($month == 06)
        {
        $name = 'June';
        }
      else if($month == 07)
        {
        $name = 'July';
        }
      else if($month == 8)
        {
        $name = 'August';
        }
      else if($month == 9)
        {
        $name = 'September';
        }
      else if($month == 10)
        {
        $name = 'October';
        }
      else if($month == 11)
        {
        $name = 'November';
        }
      else
        {
        $name = 'December';
        }
      $data['name'] = $name;
      $data['report'] = $report;

      $data['salep'] = $this->pm->total_msales_product($month,$year);
      }
    else if ($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $data['year'] = $year;
      $data['report'] = $report;

      $data['salep'] = $this->pm->total_ysales_product($year);
      }
    }
  else
    {
    $data['salep'] = $this->pm->total_sales_product();
    }
    //var_dump($data['salep']); exit();
  $this->load->view('vouchers/sale_purchase_profit_report',$data);
}

public function daily_report()
  {
  $data['title'] = 'Daily Report';

  $data['psale'] = $this->pm->pre_sales_amount();
  $data['ppurchase'] = $this->pm->pre_purchases_amount();
  $data['pcvoucher'] = $this->pm->pre_cvoucher_amount();
  $data['pdvoucher'] = $this->pm->pre_dvoucher_amount();
  $data['psvoucher'] = $this->pm->pre_svoucher_amount();
  $data['pempp'] = $this->pm->pre_emp_payments_amount();
  $data['preturn'] = $this->pm->pre_returns_amount();

  $data['csale'] = $this->pm->today_sales_amount();
  $data['cpurchase'] = $this->pm->today_purchases_amount();
  $data['ccvoucher'] = $this->pm->today_cvoucher_amount();
  $data['cdvoucher'] = $this->pm->today_dvoucher_amount();
  $data['csvoucher'] = $this->pm->today_svoucher_amount();
  $data['cempp'] = $this->pm->today_emp_payments_amount();
  $data['creturn'] = $this->pm->today_returns_amount();
  $data['cduep'] = $this->pm->today_due_payment();
  $data['cbwa'] = $this->pm->today_bank_withdraw();
  $data['cbta'] = $this->pm->today_bank_transfer();

  $this->load->view('vouchers/daily_report',$data);
}

public function user_notice()
    {
    $data['title'] = 'Notice';
    $where = array(
        'userrole' => 2
            );
    $data['users'] = $this->pm->get_data('users',$where);
    $data['notice'] = $this->pm->get_data('notice',false);

    $this->load->view('vouchers/user_notice',$data);
}

public function save_user_notice()
    {
    $info = $this->input->post();

    $config['upload_path'] = './upload/';
    $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|JPG|PNG';
    $config['max_size'] = 0;
    $config['max_width'] = 0;
    $config['max_height'] = 0;

    $this->load->library('upload', $config);
    $this->upload->initialize($config);
    if ($this->upload->do_upload('user_photo'))
        {
        $img = $this->upload->data('file_name');
        }
    else
        {
        $img = '';
        }

    $data = array(
        'ntype'   => $info['user'],
        'subject' => $info['subject'],
        'message' => $info['message'],
        'image'   => $img,
        'regby'   => $_SESSION['uid']
            );
    
    $result = $this->pm->insert_data('notice',$data);
    
    if($result)
        {
        $sdata = [
          'exception' =>'<div class="alert alert-success alert-dismissible">
            <h4><i class="icon fa fa-check"></i>Notice add Successfully !</h4>
            </div>'
                ];  
        }
    else
        {
        $sdata = [
          'exception' =>'<div class="alert alert-danger alert-dismissible">
            <h4><i class="icon fa fa-ban"></i> Failed !</h4>
            </div>'
                ];
        }
    $this->session->set_userdata($sdata);
    redirect('notice');
}

public function get_user_notice_data()
  {
  $grup = $this->pm->get_user_notice_data($_POST['id']);
  $someJSON = json_encode($grup);
  echo $someJSON;
}

public function update_user_notice()
    {
    $info = $this->input->post();

    $where = array(
        'nid' => $info['nid']
            );

    $config['upload_path'] = './upload/';
    $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|JPG|PNG';
    $config['max_size'] = 0;
    $config['max_width'] = 0;
    $config['max_height'] = 0;

    $this->load->library('upload', $config);
    $this->upload->initialize($config);

    $nimg = $this->pm->get_data('notice',$where);

    if ($this->upload->do_upload('user_photo'))
        {
        $img = $this->upload->data('file_name');
        }
    else
        {
        if($nimg)
            {
            $img = $nimg[0]['image'];
            }
        else
            {
            $img = '';
            }
        
        }

    $data = array(
        'ntype'   => $info['user'],
        'subject' => $info['subject'],
        'message' => $info['message'],
        'image'   => $img,
        'status'  => $info['status'],
        'regby'   => $_SESSION['uid']
            );
    
    $result = $this->pm->update_data('notice',$data,$where);
    
    if($result)
        {
        $sdata = [
          'exception' =>'<div class="alert alert-success alert-dismissible">
            <h4><i class="icon fa fa-check"></i> Notice Update Successfully !</h4>
            </div>'
                ];  
        }
    else
        {
        $sdata = [
          'exception' =>'<div class="alert alert-danger alert-dismissible">
            <h4><i class="icon fa fa-ban"></i> Failed !</h4>
            </div>'
                ];
        }
    $this->session->set_userdata($sdata);
    redirect('notice');
}







}