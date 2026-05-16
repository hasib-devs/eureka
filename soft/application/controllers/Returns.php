<?php
if(!defined('BASEPATH')) 
  exit('No direct script access allowed');

class Returns extends CI_Controller {

public function __construct(){
  parent::__construct();
  $this->load->model("prime_model","pm");
  $this->checkPermission();
}

public function index()
  {
  $data['title'] = 'Returns';
  
  $other = array(
    'join' => 'left',
    'order_by' => 'rid'
        );
  $field = array(
    'returns' => 'returns.*',
    'customers' => 'customers.custName,customers.custCode'
          );
  $join = array(
    'customers' => 'customers.custid = returns.custid'
          );

  $data['return'] = $this->pm->get_data('returns',false,$field,$join,$other);

  $this->load->view('return/returns',$data);
}

public function new_return()
  {
  $data['title'] = 'Returns';
  
  $where = array(
    'status' => 'Active'
        );
          
  $data['customer'] = $this->pm->get_data('customers',$where);
  $data['product'] = $this->pm->get_data('products',$where);

  $this->load->view('return/newReturn',$data);
}

public function getDetails()
  {
  $id = $this->input->post('id');

  $where = array('pid' => $id);

  $products = $this->pm->get_data('products',$where);
  $str='';
  foreach($products as $value){
    $id = $value['pid'];
    $str.="<tr>
    <td>".$value['pName']." ( ".$value['pCode']." )"."<input type='hidden' name='product[]' value='".$value['pid']."' required >
    </td>
    <td><input type='text' onkeyup='totalPrice(".$id.")' name='quantity[]' id='quantity_".$value['pid']."' value='0' required >
    </td>
    <td><input type='text' onkeyup='totalPrice(".$id.")' name='sprice[]' id='sprice_".$value['pid']."' value='".$value['sprice']."' required >
    </td>
    <td><input type='text' class='tprice' name='tprice[]' id='tprice_".$value['pid']."' value='0' required readonly >
    </td>
    <td><input type='button' class='btn btn-danger' value='Remove' onClick='$(this).parent().parent().remove();''></td>
    </tr>";
    }
  echo json_encode($str);
}

public function save_returns()
  {
  $info = $this->input->post();
  
  $query = $this->db->select('rid')
                ->from('returns')
                ->limit(1)
                ->order_by('rid','DESC')
                ->get()
                ->row();
  if($query)
    {
    $sn = $query->rid+1;
    }
  else
    {
    $sn = 1;
    }

  $cn = strtoupper(substr($_SESSION['compname'],0,3));
  $pc = sprintf("%'05d", $sn);

  $cusid = 'R-'.$cn.$pc;

  $data = array(
    'compid'      => $_SESSION['compid'],
    'rCode'       => $cusid,
    'rDate'       => date('Y-m-d',strtotime($info['date'])),
    'custid'      => $info['customer'],
    'invoice'     => $info['invoice'],
    'tAmount'     => $info['tAmount'],
    'sAmount'     => $info['sAmount'],
    'pAmount'     => $info['pAmount'], 
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'], 
    'note'        => $info['note'],          
    'regby'       => $_SESSION['uid']
        );
    //var_dump($sale); exit();
  $result = $this->pm->insert_data('returns',$data);
       
  $length = count($info['product']);

  for($i = 0;$i < $length;$i++)
    {
    $rpdata = array(
      'compid'   => $_SESSION['compid'],
      'rid'      => $result,
      'pid'      => $info['product'][$i],
      'quantity' => $info['quantity'][$i],
      'sprice'   => $info['sprice'][$i],
      'tprice'   => $info['tprice'][$i],
      'regby'    => $_SESSION['uid']
            );

    $result2 = $this->pm->insert_data('returns_product',$rpdata);

    $swhere = array(
      'pid' => $info['product'][$i]
              );

    $stpd = $this->pm->get_data('stock',$swhere);
    $this->pm->delete_data('stock',$swhere);

    if($stpd)
      {
      $tqnt = $stpd[0]['tquantity']+$info['quantity'][$i];
      $dtqnt = $stpd[0]['dtquantity'];
      }
    else
      {
      $tqnt = $info['quantity'][$i];
      $dtqnt = 0;
      }

    $stock = array(
      'compid'     => $_SESSION['compid'],
      'pid'        => $info['product'][$i],
      'tquantity'  => $tqnt,
      'dtquantity' => $dtqnt,
      'regby'      => $_SESSION['uid']
              );
        //var_dump($stock_info);    
    $this->pm->insert_data('stock',$stock); 
    }
    
    if($info['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($info['accountNo']);
      
      $cdata = array(
        'current' => $cash->current-($info['tAmount']-$info['sAmount']),
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
        'current' => $bank->current-($info['tAmount']-$info['sAmount']),
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
        'current' => $mobile->current-($info['tAmount']-$info['sAmount']),
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
      <h4><i class="icon fa fa-check"></i> Products Returns add Successfully !</h4></div>'
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
  redirect('Return');
}

public function view_return($id)
  {
  $data['title'] = 'Return View';

  $other = array(
    'join' => 'left'
        );
  $where = array(
    'rid' => $id
        );
  $field = array(
    'returns' => 'returns.*',
    'customers' => 'customers.*'
        );
  $join = array(
    'customers' => 'customers.custid = returns.custid'
          );

  $returns = $this->pm->get_data('returns',$where,$field,$join,$other);
  $data['returns'] = $returns[0];

  $rfield = array(
    'returns_product' => 'returns_product.*',
    'products' => 'products.pName,products.pCode'
        );
  $rjoin = array(
    'products' => 'returns_product.pid = products.pid',
        );

  $data['rproduct']=$this->pm->get_data('returns_product',$where,$rfield,$rjoin,$other);
  $data['company'] = $this->pm->company_details();

  $this->load->view('return/viewReturns',$data);
}

public function returns_by_sales_invoice()
  {
  $data['title'] = 'Returns';

  $id = $this->input->post('returnid');
  $swhere = array(
    'invoice' => $id
        );
  $sales = $this->pm->get_data('sales',$swhere);
  if($sales)
    {
    $data['returns'] = $sales[0];
    $cwhere = array(
      'status' => 'Active'
          );  

    $data['customer'] = $this->pm->get_data('customers',$cwhere);
    $data['product'] = $this->pm->get_data('products',$cwhere);

    $where = array(
      'said' => $sales[0]['said']            
          );
    $other = array(
      'join' => 'left'
          );
    $field = array(
      'sale_product' => 'sale_product.*',
      'products' => 'products.pName,products.pCode'
          );
    $join = array(
      'products' => 'products.pid = sale_product.pid'
          );
    $data['rproduct'] = $this->pm->get_data('sale_product',$where,$field,$join,$other);

    $this->load->view('return/editReturn',$data);
    }
  else
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-ban"></i> This Invoice ID Can not exit !</h4></div>'
            ];
    $this->session->set_userdata($sdata);
    redirect('newReturn');
    }
}

public function edit_returns($id)
  {
  $data['title'] = 'Returns';

  $cwhere = array(
    'status' => 'Active'
        );

  $data['customer'] = $this->pm->get_data('customers',$cwhere);
  $data['product'] = $this->pm->get_data('products',$cwhere);

  $where = array(
    'rid' => $id
        );
  $sales = $this->pm->get_data('returns',$where);
  $data['returns'] = $sales[0];

  $field = array(
    'returns_product' => 'returns_product.*',
    'products' => 'products.pName,products.pCode'
        );
  $join = array(
    'products'=>'returns_product.pid = products.pid'
          );
  $other = array(
    'join'=>'left'
        );
  $data['rproduct'] = $this->pm->get_data('returns_product',$where,$field,$join,$other);

  $this->load->view('return/editReturn',$data);
}

public function update_returns()
  {
  $info = $this->input->post();

  $sale = array(
    'rDate'       => date('Y-m-d',strtotime($info['date'])),
    'custid'      => $info['customer'],
    'invoice'     => $info['invoice'],
    'tAmount'     => $info['tAmount'],
    'sAmount'     => $info['sAmount'],
    'pAmount'     => $info['tAmount']-$info['sAmount'], 
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'], 
    'note'        => $info['note'],            
    'upby'        => $_SESSION['uid']
        );
    //var_dump($sale); exit();
  $where = array(
    'rid' => $info['rid']
        );
  $ppurchase = $this->pm->get_data('returns',$where);
  $result = $this->pm->update_data('returns',$sale,$where);
  $pp = $this->pm->get_data('returns_product',$where);
  $this->pm->delete_data('returns_product',$rwhere);
  
  $lnth = count($pp);

  for($i = 0; $i < $lnth; $i++)
    {
    $swhere = array(
      'pid' => $pp[$i]['pid']
            );

    $stpd = $this->pm->get_data('stock',$swhere);
    $this->pm->delete_data('stock',$swhere);
        
    if($stpd)
      {
      if($pproduct)
        {
        $tqnt = $stpd[0]['tquantity']-$pp[$i]['quantity'];
        $dtqnt = $stpd[0]['dtquantity'];
        }
      else
        {
        $tqnt = $spd[0]['tquantity'];
        $dtqnt = $stpd[0]['dtquantity'];
        }
      }
    else
      {
      $tqnt = 0;
      $dtqnt = 0;
      }

    $stockinfo = array(
      'compid'     => $_SESSION['compid'],
      'pid'        => $pp[$i]['pid'],
      'tquantity'  => $tqnt,
      'dtquantity' => $dtqnt,
      'regby'      => $_SESSION['uid']
              );
        //var_dump($stock_info);    
    $this->pm->insert_data('stock',$stockinfo); 
    }
    
  $length = count($info['product']);
        //var_dump($length); exit();
  for($i = 0;$i < $length;$i++)
    {
    $rproduct = array(
      'rid'      => $info['rid'],
      'pid'      => $info['product'][$i],
      'quantity' => $info['quantity'][$i],
      'sprice'   => $info['sprice'][$i],
      'tprice'   => $info['tprice'][$i],
      'regby'    => $_SESSION['uid']
          );

    $rp_id = $this->pm->insert_data('returns_product',$rproduct);

    $swhere = array(
      'pid' => $info['product'][$i]
              );

    $stpd = $this->pm->get_data('stock',$swhere);
    $this->pm->delete_data('stock',$swhere);

    if($stpd)
      {
      $tquantity = $stpd[0]['tquantity']+$info['pices'][$i];
      $dtqnt = $stpd[0]['dtquantity'];
      }
    else
      {
      $tquantity = $info['pices'][$i];
      $dtqnt = 0;
      }

    $stock_info = array(
      'compid'     => $_SESSION['compid'],
      'pid'        => $info['product'][$i],
      'tquantity'  => $tquantity,
      'dtquantity' => $dtqnt,
      'regby'      => $_SESSION['uid']
              );
      //var_dump($stock_info);    
    $this->pm->insert_data('stock',$stock_info); 
    }
    
    if($ppurchase[0]['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($ppurchase[0]['accountNo']);
      
      $cdata = array(
        'current' => $cash->current+$ppurchase[0]['pAmount'],
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
        'current' => $bank->current+$ppurchase[0]['pAmount'],
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
        'current' => $mobile->current+$ppurchase[0]['pAmount'],
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
        'current' => $cash->current-($info['tAmount']-$info['sAmount']),
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
        'current' => $bank->current-($info['tAmount']-$info['sAmount']),
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
        'current' => $mobile->current-($info['tAmount']-$info['sAmount']),
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $info['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Products Returns update Successfully !</h4></div>'
            ];  
    }
  else
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-ban"></i> Failed !</h4></div>'
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('Return');
}

public function delete_returns($id)
  {
  $where = array(
    'rid' => $id
        );
  $result = $this->pm->delete_data('returns',$where);
  $pproduct = $this->pm->get_data('returns_product',$where);
  $result2 = $this->pm->delete_data('returns_product',$where);
    
  $length = count($pproduct);

  for($i = 0; $i < $length; $i++)
    {
    $swhere = array(
      'pid' => $pproduct[$i]['pid']
            );

    $spd = $this->pm->get_data('stock',$swhere);
    $this->pm->delete_data('stock',$swhere);
        
    if($spd)
      {
      if($pproduct)
        {
        $tqnt = ($spd[0]['tquantity']-$pproduct[$i]['quantity']);
        $dtqnt = $spd[0]['dtquantity'];
        }
      else
        {
        $tqnt = $spd[0]['tquantity'];
        $dtqnt = $spd[0]['dtquantity'];
        }
      }
    else
      {
      $tqnt = 0;
      $dtqnt = 0;
      }

    $stock = array(
      'compid'     => $_SESSION['compid'],
      'pid'        => $pproduct[$i]['pid'],
      'tquantity'  => $tqnt,
      'dtquantity' => $dtqnt,
      'regby'      => $_SESSION['uid']
              );
        //var_dump($stock_info);    
    $this->pm->insert_data('stock',$stock); 
    }
    
    if($ppurchase[0]['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($ppurchase[0]['accountNo']);
      
      $cdata = array(
        'current' => $cash->current+$ppurchase[0]['pAmount'],
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
        'current' => $bank->current+$ppurchase[0]['pAmount'],
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
        'current' => $mobile->current+$ppurchase[0]['pAmount'],
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
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Products Returns delete Successfully !</h4>
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
  redirect('Return');
}

public function purchase_return_list()
  {
  $data['title'] = 'Returns';
  
  $other = array(
    'join' => 'left',
    'order_by' => 'prid'
        );
  $field = array(
    'preturns' => 'preturns.*',
    'suppliers' => 'suppliers.supName,suppliers.supCode'
        );
  $join = array(
    'suppliers' => 'suppliers.supid = preturns.supid'
        );
  $data['return'] = $this->pm->get_data('preturns',false,$field,$join,$other);

  $this->load->view('return/purchase_returns',$data);
}

public function new_purchase_return()
  {
  $data['title'] = 'Returns';
  
  $where = array(
    'status' => 'Active'
          );
          
  $data['supplier'] = $this->pm->get_data('suppliers',$where);
  $data['product'] = $this->pm->get_data('products',$where);

  $this->load->view('return/new_preturn',$data);
}

public function returns_by_purchase_invoice()
  {
  $data['title'] = 'Returns';

  $id = $this->input->post('prid');
  
  $where = array(
    'challanNo' => $id
        );
  $sales = $this->pm->get_data('purchase',$where);
    
  if($sales)
    {
    $data['returns'] = $sales[0];
    
    $cwhere = array(
      'status' => 'Active'
          );
    $data['supplier'] = $this->pm->get_data('suppliers',$cwhere);
    $data['product'] = $this->pm->get_data('products',$cwhere);
    
    $pwhere = array(
      'puid' => $sales[0]['puid']
          );
    $field = array(
      'purchase_product' => 'purchase_product.*',
      'products' => 'products.pName,products.pCode'
            );
    $join = array(
      'products' => 'products.pid = purchase_product.pid'
            );
    $other = array(
        'join'=>'left'
            );
    $data['rproduct'] = $this->pm->get_data('purchase_product',$pwhere,$field,$join,$other);

    $this->load->view('return/edit_preturn',$data);
    }
  else
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-ban"></i> This Challan No Can not exit !</h4>
        </div>'
            ];
    $this->session->set_userdata($sdata);
    redirect('newpReturn');
    }
}

public function save_preturns()
  {
  $info = $this->input->post();

  $query = $this->db->select('prid')
                ->from('preturns')
                ->limit(1)
                ->order_by('prid','DESC')
                ->get()
                ->row();
  if($query)
    {
    $sn = $query->prid+1;
    }
  else
    {
    $sn = 1;
    }

  $cn = strtoupper(substr($_SESSION['compname'],0,3));
  $pc = sprintf("%'05d", $sn);

  $cusid = 'PR-'.$cn.$pc;

  $data = array(
    'prDate'      => date('Y-m-d',strtotime($info['date'])),
    'prCode'      => $cusid,
    'supid'       => $info['supplier'],
    'challan'     => $info['invoice'],
    'tAmount'     => $info['tAmount'],
    'pAmount'     => $info['tAmount'],
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'], 
    'note'        => $info['note'],          
    'regby'       => $_SESSION['uid']
        );
    //var_dump($sale); exit();
  $result = $this->pm->insert_data('preturns',$data);
       
  $length = count($info['product']);

  for($i = 0;$i < $length;$i++)
    {
    $rpdata = array(
      'prid'     => $result,
      'pid'      => $info['product'][$i],
      'quantity' => $info['quantity'][$i],
      'pPrice'   => $info['sprice'][$i],
      'tPrice'   => $info['tprice'][$i],
      'regby'    => $_SESSION['uid']
            );

    $result2 = $this->pm->insert_data('preturns_product',$rpdata);

    $swhere = array(
      'pid' => $info['product'][$i]
              );

    $stpd = $this->pm->get_data('stock',$swhere);
    $this->pm->delete_data('stock',$swhere);

    if($stpd)
      {
      $tquantity = $stpd[0]['tquantity']-$info['quantity'][$i];
      $dtqnt = $stpd[0]['dtquantity'];
      }
    else
      {
      $tquantity = '-'.$info['quantity'][$i];
      $dtqnt = 0;
      }

    $stock_info = array(
      'compid'     => $_SESSION['compid'],
      'pid'        => $info['product'][$i],
      'tquantity'  => $tquantity,
      'dtquantity' => $dtqnt,
      'regby'      => $_SESSION['uid']
              );
        //var_dump($stock_info);    
    $this->pm->insert_data('stock',$stock_info); 
    }
    
    if($info['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($info['accountNo']);
      
      $cdata = array(
        'current' => $cash->current+$info['tAmount'],
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
        'current' => $bank->current+$info['tAmount'],
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
        'current' => $mobile->current+$info['tAmount'],
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
      <h4><i class="icon fa fa-check"></i> Purchase Returns add Successfully !</h4>
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
  redirect('pReturn');
}

public function view_purchase_return($id)
  {
  $data['title'] = 'Return View';

  $other = array(
    'join' => 'left'
        );
  $where = array(
    'prid' => $id
        );
  $field = array(
    'preturns' => 'preturns.*',
    'suppliers' => 'suppliers.*'
        );
  $join = array(
    'suppliers' => 'suppliers.supid = preturns.supid'
          );

  $returns = $this->pm->get_data('preturns',$where,$field,$join,$other);
  $data['returns'] = $returns[0];

  $rfield = array(
    'preturns_product' => 'preturns_product.*',
    'products' => 'products.pName,products.pCode'
        );
  $rjoin = array(
    'products' => 'products.pid = preturns_product.pid',
        );

  $data['rproduct']=$this->pm->get_data('preturns_product',$where,$rfield,$rjoin,$other);
  $data['company'] = $this->pm->company_details();

  $this->load->view('return/view_preturns',$data);
}

public function edit_purchase_return($id)
  {
  $data['title'] = 'Returns';

  $cwhere = array(
    'status' => 'Active'
        );  
  $data['supplier'] = $this->pm->get_data('suppliers',$cwhere);
  $data['product'] = $this->pm->get_data('products',$cwhere);

  $where = array(
    'prid' => $id
        );
  $sales = $this->pm->get_data('preturns',$where);
  $data['returns'] = $sales[0];

  $field = array(
    'preturns_product' => 'preturns_product.*',
    'products' => 'products.pName,products.pCode'
          );
  $join = array(
    'products' => 'products.pid = preturns_product.pid',
          );
  $other = array(
    'join'=>'left'
        );
  $data['rproduct'] = $this->pm->get_data('preturns_product',$where,$field,$join,$other);

  $this->load->view('return/edit_preturn',$data);
}

public function update_preturns()
  {
  $info = $this->input->post();

  $sale = array(
    'prDate'      => date('Y-m-d',strtotime($info['date'])),
    'supid'       => $info['supplier'],
    'challanNo'   => $info['invoice'],
    'tAmount'     => $info['tAmount'],
    'pAmount'     => $info['tAmount'],
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'], 
    'note'        => $info['note'],                   
    'upby'        => $_SESSION['uid']
          );
    //var_dump($sale); exit();
  $where = array(
    'prid' => $info['prid']
        );
  $ppurchase = $this->pm->get_data('preturns',$where);
  $result = $this->pm->update_data('preturns',$sale,$where);
  $pp = $this->pm->get_data('preturns_product',$where);
  $this->pm->delete_data('preturns_product',$where);
  
  $lnth = count($pp);

  for($i = 0; $i < $lnth; $i++)
    {
    $swhere = array(
      'pid' => $pp[$i]['pid']
            );

    $spd = $this->pm->get_data('stock',$swhere);
    $this->pm->delete_data('stock',$swhere);
        
    if($spd)
      {
      if($pp)
        {
        $tquantity = ($spd[0]['tquantity']+$pp[$i]['quantity']);
        $dtqnt = $spd[0]['dtquantity'];
        }
      else
        {
        $tquantity = $spd[0]['tquantity'];
        $dtqnt = $spd[0]['dtquantity'];
        }
      }
    else
      {
      $tquantity = 0;
      $dtqnt = 0;
      }

    $stockinfo = array(
      'compid'     => $_SESSION['compid'],
      'pid'        => $pp[$i]['pid'],
      'tquantity'  => $tquantity,
      'dtquantity' => $dtqnt,
      'regby'      => $_SESSION['uid']
              );
        //var_dump($stock_info);    
    $this->pm->insert_data('stock',$stockinfo); 
    }

  $length = count($info['product']);
        //var_dump($length); exit();
  for($i = 0;$i < $length;$i++)
    {
    $rproduct = array(
      'prid'     => $info['prid'],
      'pid'      => $info['product'][$i],
      'quantity' => $info['quantity'][$i],
      'pPrice'   => $info['sprice'][$i],
      'tPrice'   => $info['tprice'][$i],
      'regby'    => $_SESSION['uid']
            );

    $rp_id = $this->pm->insert_data('preturns_product',$rproduct);

    $swhere = array(
      'pid' => $info['product'][$i]
              );

    $stpd = $this->pm->get_data('stock',$swhere);
    $this->pm->delete_data('stock',$swhere);

    if($stpd)
      {
      $tquantity = $stpd[0]['tquantity']-$info['pices'][$i];
      $dtqnt = $stpd[0]['dtquantity'];
      }
    else
      {
      $tquantity = '-'.$info['pices'][$i];
      $dtqnt = 0;
      }

    $stock_info = array(
      'compid'     => $_SESSION['compid'],
      'pid'        => $info['product'][$i],
      'tquantity'  => $tquantity,
      'dtquantity' => $dtqnt,
      'regby'      => $_SESSION['uid']
              );
        //var_dump($stock_info);    
    $this->pm->insert_data('stock',$stock_info); 
    }
    
    if($ppurchase[0]['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($ppurchase[0]['accountNo']);
      
      $cdata = array(
        'current' => $cash->current-$ppurchase[0]['pAmount'],
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
        'current' => $bank->current-$ppurchase[0]['pAmount'],
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
        'current' => $mobile->current-$ppurchase[0]['pAmount'],
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
        'current' => $cash->current+$info['tAmount'],
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
        'current' => $bank->current+$info['tAmount'],
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
        'current' => $mobile->current+$info['tAmount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $info['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }
      
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Purchase Returns update Successfully !</h4></div>'
            ];  
    }
  else
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-ban"></i> Failed !</h4></div>'
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('pReturn');
}

public function delete_preturns($id)
  {
  $where = array(
    'prid' => $id
        );
  $ppurchase = $this->pm->get_data('preturns',$where);
  $result = $this->pm->delete_data('preturns',$where);
  $pproduct = $this->pm->get_data('preturns_product',$where);
  $result2 = $this->pm->delete_data('preturns_product',$where);
  
  $length = count($pproduct);

  for($i = 0; $i < $length; $i++)
    {
    $swhere = array(
      'pid' => $pproduct[$i]['pid']
            );

    $spd = $this->pm->get_data('stock',$swhere);
    
    $this->pm->delete_data('stock',$swhere);
        
    if($spd)
      {
      if($pproduct)
        {
        $tquantity = ($spd[0]['tquantity']+$pproduct[$i]['quantity']);
        $dtqnt = $spd[0]['dtquantity'];
        }
      else
        {
        $tquantity = $spd[0]['tquantity'];
        $dtqnt = $spd[0]['dtquantity'];
        }
      }
    else
      {
      $tquantity = 0;
      $dtqnt = 0;
      }

    $stock_info = array(
      'compid'     => $_SESSION['compid'],
      'pid'        => $pproduct[$i]['pid'],
      'tquantity'  => $tquantity,
      'dtquantity' => $dtqnt,
      'regby'      => $_SESSION['uid']
              );
        //var_dump($stock_info);    
    $this->pm->insert_data('stock',$stock_info); 
    }
    
    if($ppurchase[0]['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($ppurchase[0]['accountNo']);
      
      $cdata = array(
        'current' => $cash->current-$ppurchase[0]['pAmount'],
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
        'current' => $bank->current-$ppurchase[0]['pAmount'],
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
        'current' => $mobile->current-$ppurchase[0]['pAmount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $ppurchase[0]['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }
    
  if($result && $result2)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-check"></i> purchase Returns delete Successfully !</h4>
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
  redirect('pReturn');
}





}