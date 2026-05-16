<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');

class Chartofaccounting extends CI_Controller
  {

public function __construct()
  {
  parent::__construct();
  $this->load->model("prime_model","pm");
  $this->checkPermission();
}



public function sub_account_type_list()
  {
  $data['title'] = 'Major Account Type';

  $other = array(
    'order_by' => 'satid',
    'join' => 'left',
      );
  $field = array(
    'sub_AType' => 'sub_AType.*',
    'caccount_type' => 'caccount_type.catName',
      );
  $join = array(
    'caccount_type' => 'caccount_type.catid = sub_AType.catid',
      );
  
  $data['caccount'] = $this->pm->get_data('sub_AType',false,$field,$join,$other);
  $data['atype'] = $this->pm->get_data('caccount_type',false);

  $this->load->view('accounting/sub_account', $data);
}

public function save_sub_account_type()
  {
  $info = $this->input->post();

  $data = array(
    'catid'   => $info['catid'],
    'scaType' => $info['scaType'],
    'regby'   => $_SESSION['uid'],
        );

  $result = $this->pm->insert_data('sub_AType',$data);

  if($result)
    {
    $sdata = [
      'exception' => '<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Sub Account type add Successfully !</h4></div>',
            ];
    }
  else
    {
    $sdata = [
      'exception' => '<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-ban"></i> Failed !</h4></div>',
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('subAType');
}

public function get_sub_account_type_data()
  {
  $grup = $this->pm->get_sub_account_type_data($_POST['id']);
  $someJSON = json_encode($grup);
  echo $someJSON;
}

public function update_sub_account_type()
  {
  $info = $this->input->post();

  $where = array(
    'satid' => $info['satid'],
        );
  $data = array(
    'catid'   => $info['catid'],
    'scaType' => $info['scaType'],
    'upby'    => $_SESSION['uid'],
        );

  $result = $this->pm->update_data('sub_AType', $data, $where);

  if($result)
    {
    $sdata = [
      'exception' => '<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Sub Account Type Update Successfully !</h4></div>',
            ];
    }
  else
    {
    $sdata = [
      'exception' => '<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-ban"></i> Failed !</h4></div>',
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('subAType');
}

public function delete_sub_account_type($id)
  {
  $where = array(
    'satid' => $id,
        );

  $result = $this->pm->delete_data('sub_AType', $where);

  if($result)
    {
    $sdata = [
      'exception' => '<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Sub Account type delete Successfully !</h4></div>',
            ];
    }
  else
    {
    $sdata = [
      'exception' => '<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-ban"></i> Failed !</h4></div>',
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('subAType');
}

public function chart_account_type_list()
  {
  $data['title'] = 'Accounting Type';

  $other = array(
    'order_by' => 'cat_id',
    'join' => 'left',
      );
  $field = array(
    'chart_atype' => 'chart_atype.*',
    'caccount_type' => 'caccount_type.catName',
    'sub_AType' => 'sub_AType.scaType'
      );
  $join = array(
    'caccount_type' => 'caccount_type.catid = chart_atype.catid',
    'sub_AType' => 'sub_AType.satid = chart_atype.satid'
      );
  
  $data['caccount'] = $this->pm->get_data('chart_atype',false,$field,$join,$other);
  $data['atype'] = $this->pm->get_data('caccount_type',false);

  $this->load->view('accounting/account_type', $data);
}

public function get_sub_account_data()
  {
  $grup = $this->pm->get_sub_account_data($_POST['id']);
  $someJSON = json_encode($grup);
  echo $someJSON;
}

public function save_chart_of_account_type()
  {
  $info = $this->input->post();

  $data = array(
    'catid'  => $info['atype'],
    'satid'  => $info['sAccount'],
    'caType' => $info['catype'],
    'regby'  => $_SESSION['uid'],
        );

  $result = $this->pm->insert_data('chart_atype',$data);

  if($result)
    {
    $sdata = [
      'exception' => '<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Chart of Account type add Successfully !</h4></div>',
            ];
    }
  else
    {
    $sdata = [
      'exception' => '<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-ban"></i> Failed !</h4></div>',
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('chaType');
}

public function get_chart_account_type_data()
  {
  $grup = $this->pm->get_chart_account_type_data($_POST['id']);
  $someJSON = json_encode($grup);
  echo $someJSON;
}

public function update_chart_of_account_type()
  {
  $info = $this->input->post();

  $where = array(
    'cat_id' => $info['catid'],
        );
  $data = array(
    'catid'  => $info['atype'],
    'satid'  => $info['sAccount'],
    'caType' => $info['catype'],
    'upby'   => $_SESSION['uid'],
        );

  $result = $this->pm->update_data('chart_atype', $data, $where);

  if($result)
    {
    $sdata = [
      'exception' => '<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Chart of Account Type Update Successfully !</h4></div>',
            ];
    }
  else
    {
    $sdata = [
      'exception' => '<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-ban"></i> Failed !</h4></div>',
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('chaType');
}

public function delete_chart_of_account_type($id)
  {
  $where = array(
    'cat_id' => $id,
        );

  $result = $this->pm->delete_data('chart_atype', $where);

  if($result)
    {
    $sdata = [
      'exception' => '<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Chart Of Account type delete Successfully !</h4></div>',
            ];
    }
  else
    {
    $sdata = [
      'exception' => '<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-ban"></i> Failed !</h4></div>',
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('chaType');
}

public function chart_account_list()
  {
  $data['title'] = 'Accounting';

  $other = array(
    'order_by' => 'ca_id',
    'join'     => 'left',
      );
  $field = array(
    'chart_account' => 'chart_account.*',
    'caccount_type' => 'caccount_type.catName',
    'chart_atype' => 'chart_atype.caType',
    'sub_AType' => 'sub_AType.scaType'
      );
  $join = array(
    'caccount_type' => 'caccount_type.catid = chart_account.catid',
    'chart_atype' => 'chart_atype.cat_id = chart_account.catype',
    'sub_AType' => 'sub_AType.satid = chart_account.satid'
      );
        
  $data['caccount'] = $this->pm->get_data('chart_account',false,$field,$join,$other);
  $data['atype'] = $this->pm->get_data('caccount_type',false);

  $this->load->view('accounting/chart_of_account',$data);
}

public function new_chart_account()
  {
  $data['title'] = 'Accounting';
        
  $data['caccount'] = $this->pm->get_data('chart_account',false);
  $data['atype'] = $this->pm->get_data('caccount_type',false);

  $this->load->view('accounting/new_chart_account',$data);
}

public function get_account_type()
  {
  $where = array(
    'satid' => $_POST['id'],
        );
  $grup = $this->pm->get_data('chart_atype',$where);
  $someJSON = json_encode($grup);
  echo $someJSON;
}

public function get_account_details()
  {
  $where = array(
    'catype' => $_POST['id'],
        );
  $grup = $this->pm->get_data('chart_account',$where);
  $someJSON = json_encode($grup);
  echo $someJSON;
}

public function save_chart_of_accounting()
  {
  $info = $this->input->post();
              
//   if($info['cadetails'] == "newDetalis")
//     {
//     $details = $info['newDetalis'];
//     }
//   else
//     {
    $details = $info['cadetails'];
    
    $query = $this->db->select('*')->from('chart_account')->where('cadetails',$details)->get()->row();
    
    $atype = $query->catid;
    $sAccount = $query->satid;
    $catype = $query->catype;
    //}
  //var_dump($details); exit();
  
  $data = array(
    'catid'       => $atype,
    'satid'       => $sAccount,
    'catype'      => $catype,
    'cadetails'   => $details,
    'narration'   => $info['narration'],
    'caamount'    => $info['amount'],
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'regby'       => $_SESSION['uid'],
        );
        //var_dump($data); exit();
  $result = $this->pm->insert_data('chart_account',$data);
  if($info['atype'] == 1 || $info['atype'] == 5)
    {
    if($info['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($info['accountNo']);
      
      $cdata = array(
        'current' => $cash->current-$info['amount'],
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
        'current' => $bank->current-$info['amount'],
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
        'current' => $mobile->current-$info['amount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $info['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }
    }
    
  if($info['atype'] == 2 || $info['atype'] == 4)
    {
    if($info['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($info['accountNo']);
      
      $cdata = array(
        'current' => $cash->current+$info['amount'],
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
        'current' => $bank->current+$info['amount'],
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
        'current' => $mobile->current+$info['amount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $info['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }
    }
    
  if($info['atype'] == 3)
    {
    $bank = $this->pm->company_details();
      
    $bdata = array(
      'com_balance' => $bank->com_balance+$info['amount'],
      'upby'    => $_SESSION['uid']
                );
      
    $bwhere = array(
      'com_pid' => 1
            );
    $this->pm->update_data('com_profile',$bdata,$bwhere);
    }
      
  if($result)
    {
    $sdata = [
      'exception' => '<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Chart Of Accounting add Successfully !</h4></div>',
            ];
    }
  else
    {
    $sdata = [
      'exception' => '<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-ban"></i> Failed !</h4></div>',
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('chAccount');
}

public function get_chart_accounting_data()
  {
  $grup = $this->pm->get_chart_accounting_data($_POST['id']);
  $someJSON = json_encode($grup);
  echo $someJSON;
}

public function account_type_data()
  {
  $grup = $this->pm->account_type_data($_POST['id']);
  $someJSON = json_encode($grup);
  echo $someJSON;
}

public function edit_chart_account($id)
  {
  $data['title'] = 'Accounting';
  
  $where = array(
    'ca_id' => $id
        );
  $data['account'] = $this->pm->get_data('chart_account',$where);
  $data['caccount'] = $this->pm->get_data('chart_account',false);
  $data['atype'] = $this->pm->get_data('caccount_type',false);

  $this->load->view('accounting/edit_chart_account',$data);
}

public function update_chart_of_accounting()
  {
  $info = $this->input->post();

  $where = array(
    'ca_id' => $info['catid']
        );
  $data = array(
    //'catid'       => $info['atype'],
    //'satid'       => $info['sAccount'],
    //'catype'      => $info['catype'],
    'cadetails'   => $info['cadetails'],
    'narration'   => $info['narration'],
    'caamount'    => $info['amount'],
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'upby'        => $_SESSION['uid'],
        );
  $ppurchase = $this->pm->get_data('chart_account',$where);
  $result = $this->pm->update_data('chart_account',$data,$where);
  
  if($ppurchase[0]['catid'] == 1 || $ppurchase[0]['catid'] == 5)
    {
    if($ppurchase[0]['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($ppurchase[0]['accountNo']);
      
      $cdata = array(
        'current' => $cash->current+$ppurchase[0]['caamount'],
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
        'current' => $bank->current+$ppurchase[0]['caamount'],
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
        'current' => $mobile->current+$ppurchase[0]['caamount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $ppurchase[0]['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }
    }
  if($ppurchase[0]['catid'] == 2 || $ppurchase[0]['catid'] == 4)
    {
    if($ppurchase[0]['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($ppurchase[0]['accountNo']);
      
      $cdata = array(
        'current' => $cash->current-$ppurchase[0]['caamount'],
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
        'current' => $bank->current-$ppurchase[0]['caamount'],
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
        'current' => $mobile->current-$ppurchase[0]['caamount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $ppurchase[0]['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }
    }
    
    if($ppurchase[0]['catid'] == 3)
      {
      $cash = $this->pm->company_details();
      
      $bdata = array(
        'com_balance' => $cash->com_balance-$ppurchase[0]['caamount'],
        'upby'        => $_SESSION['uid']
                );
      
      $bwhere = array(
        'com_pid' => 1
            );
      $this->pm->update_data('com_profile',$bdata,$bwhere);
      }
    
  if($info['atype'] == 1 || $info['atype'] == 5)
    {
    if($info['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($info['accountNo']);
      
      $cdata = array(
        'current' => $cash->current-$info['amount'],
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
        'current' => $bank->current-$info['amount'],
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
        'current' => $mobile->current-$info['amount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $info['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }
    }
  
  if($info['atype'] == 2 || $info['atype'] == 4)
    {
    if($info['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($info['accountNo']);
      
      $cdata = array(
        'current' => $cash->current+$info['amount'],
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
        'current' => $bank->current+$info['amount'],
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
        'current' => $mobile->current+$info['amount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $info['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }
    }
    
  if($info['atype'] == 3)
    {
    $bank = $this->pm->company_details();
      
    $bdata = array(
      'com_balance' => $bank->com_balance+$info['amount'],
      'upby'    => $_SESSION['uid']
                );
      
    $bwhere = array(
      'com_pid' => 1
            );
    $this->pm->update_data('com_profile',$bdata,$bwhere);
    }
      
  if($result)
    {
    $sdata = [
      'exception' => '<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Chart Of Accounting Update Successfully !</h4></div>',
            ];
    }
  else
    {
    $sdata = [
      'exception' => '<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-ban"></i> Failed !</h4></div>',
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('chAccount');
}

public function delete_chart_of_accounting($id)
  {
  $where = array(
    'ca_id' => $id,
        );
  $ppurchase = $this->pm->get_data('chart_account',$where);
  $result = $this->pm->delete_data('chart_account', $where);
  if($ppurchase[0]['catid'] == 1 || $ppurchase[0]['catid'] == 5)
    {
    if($ppurchase[0]['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($ppurchase[0]['accountNo']);
      
      $cdata = array(
        'current' => $cash->current+$ppurchase[0]['caamount'],
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
        'current' => $bank->current+$ppurchase[0]['caamount'],
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
        'current' => $mobile->current+$ppurchase[0]['caamount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $ppurchase[0]['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }
    }
  
  if($ppurchase[0]['catid'] == 2 || $ppurchase[0]['catid'] == 4)
    {
    if($ppurchase[0]['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($ppurchase[0]['accountNo']);
      
      $cdata = array(
        'current' => $cash->current-$ppurchase[0]['caamount'],
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
        'current' => $bank->current-$ppurchase[0]['caamount'],
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
        'current' => $mobile->current-$ppurchase[0]['caamount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $ppurchase[0]['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }
    }
    
    if($ppurchase[0]['catid'] == 3)
      {
      $cash = $this->pm->company_details();
      
      $bdata = array(
        'com_balance' => $cash->com_balance-$ppurchase[0]['caamount'],
        'upby'        => $_SESSION['uid']
                );
      
      $bwhere = array(
        'com_pid' => 1
            );
      $this->pm->update_data('com_profile',$bdata,$bwhere);
      }
      
  if($result)
    {
    $sdata = [
      'exception' => '<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Chart Of Accounting delete Successfully !</h4></div>',
            ];
    }
  else
    {
    $sdata = [
      'exception' => '<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-ban"></i> Failed !</h4></div>',
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('chAccount');
}

public function journal_entry_account_list()
  {
  $data = ['title' => 'Journal Entry'];

  $data['customer'] = $this->pm->get_data('customers',false);
  $data['supplier'] = $this->pm->get_data('suppliers',false);
  $data['company'] = $this->pm->company_details();

  if(isset($_GET['search']))
    {
    $rtype = $_GET['rtype'];

    if($rtype == 'customer')
      {
      $data['rtype'] = $rtype;
      $report = $_GET['reports'];

      if($report == 'dailyReports')
        {
        $sdate = date("Y-m-d", strtotime($_GET['sdate']));
        $edate = date("Y-m-d", strtotime($_GET['edate']));
        $custid = $_GET['dcustomer'];
        $data['sdate'] = $sdate;
        $data['edate'] = $edate;
        $data['report'] = $report;

        $cwhere = array('custid' => $custid);

        $data['cust'] = $this->pm->get_data('customers',$cwhere);
        $data['sale'] = $this->pm->sales_dcust_ledger_data($custid,$sdate,$edate);
        $data['voucher'] = $this->pm->voucher_dcust_ledger_data($custid,$sdate,$edate);
        $data['return'] = $this->pm->return_dcust_ledger_data($custid,$sdate,$edate);
        }
      else if($report == 'monthlyReports')
        {
        $month = $_GET['month'];
        $data['month'] = $month;
        $year = $_GET['year'];
        $data['year'] = $year;
        $custid = $_GET['mcustomer'];
                    //var_dump($data['month']); exit();
        if($month == 1)
          {
          $name = 'January';
          }
        else if($month == 2)
          {
          $name = 'February';
          } 
        else if($month == 3)
          {
          $name = 'March';
          }
        else if($month == 4)
          {
          $name = 'April';
          }
        else if($month == 5)
          {
          $name = 'May';
          }
        else if($month == 6)
          {
          $name = 'June';
          }
        else if($month == 7)
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

        $cwhere = array('custid' => $custid);

        $data['cust'] = $this->pm->get_data('customers',$cwhere);
        $data['sale'] = $this->pm->sales_mcust_ledger_data($custid,$month,$year);
        $data['voucher'] = $this->pm->voucher_mcust_ledger_data($custid,$month,$year);
        $data['return'] = $this->pm->return_mcust_ledger_data($custid,$month,$year);
        } 
      else if($report == 'yearlyReports')
        {
        $year = $_GET['ryear'];
        $data['year'] = $year;
        $data['report'] = $report;
        $custid = $_GET['ycustomer'];

        $cwhere = array('custid' => $custid);

        $data['cust'] = $this->pm->get_data('customers',$cwhere);
        $data['sale'] = $this->pm->sales_ycust_ledger_data($custid,$year);
        $data['voucher'] = $this->pm->voucher_ycust_ledger_data($custid,$year);
        $data['return'] = $this->pm->return_ycust_ledger_data($custid,$year);
        }
      else if($report == 'ocust')
        {
        $data['report'] = $report;
        $custid = $_GET['customer'];

        $cwhere = array('custid' => $custid);

        $data['cust'] = $this->pm->get_data('customers',$cwhere);
        $data['sale'] = $this->pm->sales_cust_ledger_data($custid);
        $data['voucher'] = $this->pm->voucher_cust_ledger_data($custid);
        $data['return'] = $this->pm->return_cust_ledger_data($custid);
        }
      }
    else if($rtype == 'supplier')
      {
      $data['rtype'] = $rtype;
      $report = $_GET['reports'];

      if($report == 'dailysReports')
        {
        $sdate = date("Y-m-d", strtotime($_GET['ssdate']));
        $edate = date("Y-m-d", strtotime($_GET['esdate']));
        $data['sdate'] = $sdate;
        $data['edate'] = $edate;
        $data['report'] = $report;

        $sid = $_GET['dsupplier'];
        $where = array('supid' => $sid);

        $data['supp'] = $this->pm->get_data('suppliers', $where);
        $data['purchase'] = $this->pm->get_dspurchase_data($sdate,$edate,$sid);
        $data['voucher'] = $this->pm->get_dsvoucher_data($sdate,$edate,$sid);
        } 
      else if($report == 'monthlysReports')
        {
        $month = $_GET['smonth'];
        $data['month'] = $month;
        $year = $_GET['syear'];
        $data['year'] = $year;

        if($month == 1)
          {
          $name = 'January';
          }
        else if($month == 2)
          {
          $name = 'February';
          } 
        else if($month == 3)
          {
          $name = 'March';
          }
        else if($month == 4)
          {
          $name = 'April';
          }
        else if($month == 5)
          {
          $name = 'May';
          }
        else if($month == 6)
          {
          $name = 'June';
          }
        else if($month == 7)
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

        $sid = $_GET['msupplier'];
        $where = array('supid' => $sid);

        $data['supp'] = $this->pm->get_data('suppliers',$where);
        $data['purchase'] = $this->pm->get_mspurchase_data($month,$year,$sid);
        $data['voucher'] = $this->pm->get_msvoucher_data($month,$year,$sid);
        }
      else if($report == 'yearlysReports')
        {
        $year = $_GET['rsyear'];
        $data['year'] = $year;
        $data['report'] = $report;

        $sid = $_GET['ysupplier'];
        $where = array('supid' => $sid);

        $data['supp'] = $this->pm->get_data('suppliers',$where);
        $data['purchase'] = $this->pm->get_yspurchase_data($year,$sid);
        $data['voucher'] = $this->pm->get_ysvoucher_data($year,$sid);
        }
      else if($report == 'asupplier')
        {
        $data['report'] = $report;

        $sid = $_GET['supplier'];
        $where = array('supid' => $sid);

        $data['supp'] = $this->pm->get_data('suppliers', $where);
        $data['purchase'] = $this->pm->get_spurchase_data($sid);
        $data['voucher'] = $this->pm->get_svoucher_data($sid);
        }
      }
    else
      {

      }
    }
  $this->load->view('accounting/journal_entry',$data);
}

public function income_statement_list()
  {
  $data = ['title' => 'Income Statement'];
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
      
      $data['sale'] = $this->pm->get_sales_dis_data($sdate,$edate);
      $data['purchase'] = $this->pm->get_purchase_dis_data($sdate,$edate);
      $data['closing_inv'] = $this->pm->get_closing_inventory_data();
      $data['dvoucher'] = $this->pm->get_dvoucher_dis_data($sdate,$edate);
      }
    else if($report == 'monthlyReports')
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
      else if($month == 2)
        {
        $name = 'February';
        } 
      else if($month == 3)
        {
        $name = 'March';
        }
      else if($month == 4)
        {
        $name = 'April';
        }
      else if($month == 5)
        {
        $name = 'May';
        }
      else if($month == 6)
        {
        $name = 'June';
        }
      else if($month == 7)
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
      
      $data['sale'] = $this->pm->get_sales_mis_data($month,$year);
      $data['purchase'] = $this->pm->get_purchase_mis_data($month,$year);
      $data['closing_inv'] = $this->pm->get_closing_inventory_data();
      $data['dvoucher'] = $this->pm->get_dvoucher_mis_data($month,$year);
      }
    else if($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $data['year'] = $year;
      $data['report'] = $report;
      
      $data['sale'] = $this->pm->get_sales_yis_data($year);
      $data['purchase'] = $this->pm->get_purchase_yis_data($year);
      $data['closing_inv'] = $this->pm->get_closing_inventory_data();
      $data['dvoucher'] = $this->pm->get_dvoucher_yis_data($year);
      }
    }
  else
    {
    $data['sale'] = $this->pm->get_sales_is_data();
    $data['purchase'] = $this->pm->get_purchase_is_data();
    $data['closing_inv'] = $this->pm->get_closing_inventory_data();
    $data['dvoucher'] = $this->pm->get_dvoucher_bs_data();
    }

  $this->load->view('accounting/income_statement', $data);
}

public function trail_balance_list()
  {
  $data = ['title' => 'Trial Balance'];
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
      
      $data['cash'] = $this->pm->get_cash_tb_data();
      $data['mobile'] = $this->pm->get_mobile_tb_data();
      $data['bank'] = $this->pm->get_bank_tb_data();
      $data['receivable'] = $this->pm->dsales_due_data($sdate,$edate);
      $data['expense'] = $this->pm->get_texpense_dtb_data($sdate,$edate);
      $data['payable'] = $this->pm->dpurchase_due_data($sdate,$edate);
      $data['sale'] = $this->pm->get_tsale_dtb_data($sdate,$edate);
      $data['return'] = $this->pm->get_treturn_dtb_data($sdate,$edate);
      $data['purchase'] = $this->pm->get_tpurchase_dtb_data($sdate,$edate);
      $data['pur_return'] = $this->pm->get_dpurchase_return_tb_data($sdate,$edate);
      $data['closing_inv'] = $this->pm->get_closing_inventory_data();
      }
    else if($report == 'monthlyReports')
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
      else if($month == 2)
        {
        $name = 'February';
        } 
      else if($month == 3)
        {
        $name = 'March';
        }
      else if($month == 4)
        {
        $name = 'April';
        }
      else if($month == 5)
        {
        $name = 'May';
        }
      else if($month == 6)
        {
        $name = 'June';
        }
      else if($month == 7)
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
      
      $data['cash'] = $this->pm->get_cash_tb_data();
      $data['mobile'] = $this->pm->get_mobile_tb_data();
      $data['bank'] = $this->pm->get_bank_tb_data();
      $data['receivable'] = $this->pm->msales_due_data($month,$year);
      $data['expense'] = $this->pm->get_texpense_mtb_data($month,$year);
      $data['payable'] = $this->pm->ypurchase_due_data($month,$year);
      $data['sale'] = $this->pm->get_tsale_mtb_data($month,$year);
      $data['return'] = $this->pm->get_treturn_mtb_data($month,$year);
      $data['purchase'] = $this->pm->get_tpurchase_mtb_data($month,$year);
      $data['pur_return'] = $this->pm->get_mpurchase_return_tb_data($month,$year);
      $data['closing_inv'] = $this->pm->get_closing_inventory_data();
      }
    else if($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $data['year'] = $year;
      $data['report'] = $report;
      
      $data['cash'] = $this->pm->get_cash_tb_data();
      $data['mobile'] = $this->pm->get_mobile_tb_data();
      $data['bank'] = $this->pm->get_bank_tb_data();
      $data['receivable'] = $this->pm->ysales_due_data($year);
      $data['expense'] = $this->pm->get_texpense_ytb_data($year);
      $data['payable'] = $this->pm->ypurchase_due_data($year);
      $data['sale'] = $this->pm->get_tsale_ytb_data($year);
      $data['return'] = $this->pm->get_treturn_ytb_data($year);
      $data['purchase'] = $this->pm->get_tpurchase_ytb_data($year);
      $data['pur_return'] = $this->pm->get_ypurchase_return_tb_data($year);
      $data['closing_inv'] = $this->pm->get_closing_inventory_data();
      }
    }
  else
    {
    $data['cash'] = $this->pm->get_cash_tb_data();
    $data['mobile'] = $this->pm->get_mobile_tb_data();
    $data['bank'] = $this->pm->get_bank_tb_data();
    $data['receivable'] = $this->pm->sales_due_data();
    $data['expense'] = $this->pm->get_texpense_tb_data();
    $data['payable'] = $this->pm->purchase_due_data();
    $data['sale'] = $this->pm->get_sale_tb_data();
    $data['return'] = $this->pm->get_treturn_tb_data();
    $data['purchase'] = $this->pm->get_tpurchase_tb_data();
    $data['pur_return'] = $this->pm->get_purchase_return_tb_data();
    $data['closing_inv'] = $this->pm->get_closing_inventory_data();
    }

  $this->load->view('accounting/trail_balance', $data);
}

public function balance_sheet_list()
  {
  $data = ['title' => 'Balance Sheet'];
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

      $data['asset'] = $this->pm->get_asset_dbs_data($sdate,$edate);
      $data['liability'] = $this->pm->get_liability_dbs_data($sdate,$edate);
      $data['equity'] = $this->pm->get_equity_dbs_data($sdate,$edate);
      $data['income'] = $this->pm->get_income_dbs_data($sdate,$edate);
      $data['expense'] = $this->pm->get_expense_dbs_data($sdate,$edate);
      $data['sale'] = $this->pm->get_sale_dbs_data($sdate,$edate);
      $data['cvoucher'] = $this->pm->get_cvoucher_dbs_data($sdate,$edate);
      $data['purchase'] = $this->pm->get_purchase_dbs_data($sdate,$edate);
      $data['payment'] = $this->pm->get_payment_dbs_data($sdate,$edate);
      $data['return'] = $this->pm->get_return_dbs_data($sdate,$edate);
      $data['dvoucher'] = $this->pm->get_dvoucher_dbs_data($sdate,$edate);
      $data['bank'] = $this->pm->get_bank_tb_data();
      $data['mobile'] = $this->pm->get_mobile_tb_data();
      }
    else if($report == 'monthlyReports')
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
      else if($month == 2)
        {
        $name = 'February';
        }
      else if($month == 3)
        {
        $name = 'March';
        }
      else if($month == 4)
        {
        $name = 'April';
        }
      else if($month == 5)
        {
        $name = 'May';
        }
      else if($month == 6)
        {
        $name = 'June';
        }
      else if($month == 7)
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

      $data['asset'] = $this->pm->get_asset_mbs_data($month,$year);
      $data['liability'] = $this->pm->get_liability_mbs_data($month,$year);
      $data['equity'] = $this->pm->get_equity_mbs_data($month,$year);
      $data['income'] = $this->pm->get_income_mbs_data($month,$year);
      $data['expense'] = $this->pm->get_expense_mbs_data($month,$year);
      $data['sale'] = $this->pm->get_sale_mbs_data($month,$year);
      $data['cvoucher'] = $this->pm->get_cvoucher_mbs_data($month,$year);
      $data['purchase'] = $this->pm->get_purchase_mbs_data($month,$year);
      $data['payment'] = $this->pm->get_payment_mbs_data($month,$year);
      $data['return'] = $this->pm->get_return_mbs_data($month,$year);
      $data['dvoucher'] = $this->pm->get_dvoucher_mbs_data($month,$year);
      $data['bank'] = $this->pm->get_bank_tb_data();
      $data['mobile'] = $this->pm->get_mobile_tb_data();
      }
    else if($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $data['year'] = $year;
      $data['report'] = $report;

      $data['asset'] = $this->pm->get_asset_ybs_data($year);
      $data['liability'] = $this->pm->get_liability_ybs_data($year);
      $data['equity'] = $this->pm->get_equity_ybs_data($year);
      $data['income'] = $this->pm->get_income_ybs_data($year);
      $data['expense'] = $this->pm->get_expense_ybs_data($year);
      $data['sale'] = $this->pm->get_sale_ybs_data($year);
      $data['cvoucher'] = $this->pm->get_cvoucher_ybs_data($year);
      $data['purchase'] = $this->pm->get_purchase_ybs_data($year);
      $data['payment'] = $this->pm->get_payment_ybs_data($year);
      $data['return'] = $this->pm->get_return_ybs_data($year);
      $data['dvoucher'] = $this->pm->get_dvoucher_ybs_data($year);
      $data['bank'] = $this->pm->get_bank_tb_data();
      $data['mobile'] = $this->pm->get_mobile_tb_data();
      }
    }
  else
    {
    $data['cash'] = $this->pm->get_cash_tb_data();
    $data['bank'] = $this->pm->get_bank_tb_data();
    $data['mobile'] = $this->pm->get_mobile_tb_data();
    $data['payable'] = $this->pm->purchase_due_data();
    $data['sale'] = $this->pm->get_sales_is_data();
    $data['purchase'] = $this->pm->get_purchase_is_data();
    $data['dvoucher'] = $this->pm->get_dvoucher_bs_data();
    $data['receivable'] = $this->pm->sales_due_data();
    $data['closing_inv'] = $this->pm->get_closing_inventory_data();
    }

  $this->load->view('accounting/balance_sheet', $data);
}

public function general_ledger_list()
  {
  $data = ['title' => 'General Ledger'];
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

      $data['sale'] = $this->pm->get_sale_dbs_data($sdate,$edate);
      $data['cvoucher'] = $this->pm->get_cvoucher_dbs_data($sdate,$edate);
      $data['purchase'] = $this->pm->get_purchase_dbs_data($sdate,$edate);
      $data['payment'] = $this->pm->get_payment_dbs_data($sdate,$edate);
      $data['return'] = $this->pm->get_return_dbs_data($sdate,$edate);
      $data['dvoucher'] = $this->pm->get_dvoucher_dbs_data($sdate,$edate);
      }
    else if($report == 'monthlyReports')
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
      else if($month == 2)
        {
        $name = 'February';
        } 
      else if($month == 3)
        {
        $name = 'March';
        }
      else if($month == 4)
        {
        $name = 'April';
        }
      else if($month == 5)
        {
        $name = 'May';
        }
      else if($month == 6)
        {
        $name = 'June';
        }
      else if($month == 7)
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

      $data['sale'] = $this->pm->get_sale_mbs_data($month,$year);
      $data['cvoucher'] = $this->pm->get_cvoucher_mbs_data($month,$year);
      $data['purchase'] = $this->pm->get_purchase_mbs_data($month,$year);
      $data['payment'] = $this->pm->get_payment_mbs_data($month,$year);
      $data['return'] = $this->pm->get_return_mbs_data($month,$year);
      $data['dvoucher'] = $this->pm->get_dvoucher_mbs_data($month,$year);
      }
    else if($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $data['year'] = $year;
      $data['report'] = $report;

      $data['sale'] = $this->pm->get_sale_ybs_data($year);
      $data['cvoucher'] = $this->pm->get_cvoucher_ybs_data($year);
      $data['purchase'] = $this->pm->get_purchase_ybs_data($year);
      $data['payment'] = $this->pm->get_payment_ybs_data($year);
      $data['return'] = $this->pm->get_return_ybs_data($year);
      $data['dvoucher'] = $this->pm->get_dvoucher_ybs_data($year);
      }
    }
  else
    {
    $data['sale'] = $this->pm->get_sale_bs_data();
    $data['cvoucher'] = $this->pm->get_cvoucher_bs_data();
    $data['purchase'] = $this->pm->get_purchase_bs_data();
    $data['payment'] = $this->pm->get_payment_bs_data();
    $data['return'] = $this->pm->get_return_bs_data();
    $data['dvoucher'] = $this->pm->get_dvoucher_gl_data();
    }
   //var_dump($data['dvoucher']); exit();
  $this->load->view('accounting/general_ledger', $data);
}

public function chart_of_account_report()
  {
  $data = ['title' => 'COA Reports'];
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
      }
    else if($report == 'monthlyReports')
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
      else if($month == 2)
        {
        $name = 'February';
        }
      else if($month == 3)
        {
        $name = 'March';
        }
      else if($month == 4)
        {
        $name = 'April';
        }
      else if($month == 5)
        {
        $name = 'May';
        }
      else if ($month == 6)
        {
        $name = 'June';
        }
      else if($month == 7)
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
      }
    else if($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $data['year'] = $year;
      $data['report'] = $report;
      }
    }
  else
    {
    $data['equity'] = $this->pm->get_equity_data();
    $data['bank'] = $this->pm->get_bank_data();
    $data['cash'] = $this->pm->get_cash_data();
    $data['mobile'] = $this->pm->get_mobile_data();
    $data['stock'] = $this->pm->get_product_stock_data();
    $data['sdue'] = $this->pm->get_sale_due_data();
    $data['assets'] = $this->pm->get_assets_data();
    $data['pdue'] = $this->pm->get_purchse_due_data();
    $data['liability'] = $this->pm->get_liability_data();
    $data['expense'] = $this->pm->get_expense_data();
    $data['sale'] = $this->pm->total_sales_amount();
    $data['purchase'] = $this->pm->total_purchases_amount();
    }

  $this->load->view('accounting/coa_reports', $data);
}

public function voucher_ledger_report()
  {
  $data = ['title' => 'Voucher Ledger'];
  $data['company'] = $this->pm->company_details();
  $data['atype'] = $this->pm->get_data('chart_account',false);

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
      $vuType = $_GET['dvuType'];
      
      $data['voucher'] = $this->pm->get_dvoucher_ledger_data($sdate,$edate,$vuType);
      }
    else if($report == 'monthlyReports')
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
      else if($month == 2)
        {
        $name = 'February';
        }
      else if($month == 3)
        {
        $name = 'March';
        }
      else if($month == 4)
        {
        $name = 'April';
        }
      else if($month == 5)
        {
        $name = 'May';
        }
      else if ($month == 6)
        {
        $name = 'June';
        }
      else if($month == 7)
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
      
      $vuType = $_GET['mvuType'];
      
      $data['voucher'] = $this->pm->get_mvoucher_ledger_data($month,$year,$vuType);
      }
    else if($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $data['year'] = $year;
      $data['report'] = $report;
      $vuType = $_GET['yvuType'];
      //var_dump($year); var_dump($vuType); exit();
      $data['voucher'] = $this->pm->get_yvoucher_ledger_data($year,$vuType);
      }
    }
  else
    {
    $data['voucher'] = $this->pm->get_voucher_ledger_data();
    }

  $this->load->view('accounting/voucher_ledger', $data);
}



}