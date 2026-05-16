<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');

class Lcmanagement extends CI_Controller {

public function __construct()
  {
  parent::__construct();
  $this->load->model("prime_model","pm");
  $this->checkPermission();
}

public function index()
  {
  $data['title'] = 'LC Management';

  $other = array(
    'order_by' => 'lcid',
    'join' => 'left'
        );
  $field = array(
    'lc_management' => 'lc_management.*',
    'suppliers' => 'suppliers.supName'
        );    
  $join = array(
    'suppliers' => 'suppliers.supid = lc_management.supid'
        );
  $data['purchase'] = $this->pm->get_data('lc_management',false,$field,$join,$other);

  $this->load->view('lcmanagement/lc_list',$data);
}

public function new_lc_management() 
  {
  $data['title'] = 'LC Management';

  $data['product'] = $this->pm->get_data('products',false);
  $data['supplier'] = $this->pm->get_data('suppliers',false);
  $data['currency'] = $this->pm->get_data('currency',false);

  $this->load->view('lcmanagement/new_lc',$data);
}

public function get_product($id)
  {
  $str = "";
  $where = array(
    'pid' => $id
        );
  $productlist = $this->pm->get_data('products',$where);
    //   var_dump($productlist);exit();
  foreach($productlist as $value)
    {
    $id = $value['pid'];
    $str .= "<tr>
      <td>".$value['pName'].' ( '.$value['pCode'].' )'."<input type='hidden' name='product[]' value='".$id."' required ></td>
      <td><input type='text' class='form-control' name='hscode[]' placeholder='HS Code' required ></td>
      <td><input type='text' class='form-control' name='weight[]' id='weight_".$id."' onkeyup='getTotal(".$id.")' value='0' required ></td>
      <td><input type='text' class='form-control' name='uprice[]' id='uprice_".$id."' onkeyup='getTotal(".$id.")' value='0' required ></td>
      <td><input type='text' class='form-control tprice' name='tprice[]' id='tprice_".$id."' value='0' required readonly ></td>
      <td><input type='text' class='form-control' name='quantity[]' id='quantity_".$id."' onkeyup='getTotal(".$id.")' value='1' required ></td>
      <td><input type='text' class='form-control' name='upprice[]' id='upprice_".$id."' value='0' required readonly ></td>
      <td><span class='item_remove btn btn-danger btn-xs' onclick='deleteProduct(this)'>x</span></td></tr>";
    }
  echo json_encode($str);
}

public function saved_lc_management()
  {
  $info = $this->input->post();

  $query = $this->db->select('lcid')
                  ->from('lc_management')
                  ->limit(1)
                  ->order_by('lcid','DESC')
                  ->get()
                  ->row();
  if($query)
    {
    $sn = $query->lcid+1;
    }
  else
    {
    $sn = 1;
    }
  $cn = strtoupper(substr($_SESSION['compname'],0,3));
  $pc = sprintf("%'05d",$sn);

  $cusid = 'LC-'.$cn.$pc;
    //var_dump($cusid); exit();
  $purchase = array(
    'lcCode'      => $cusid,
    'lcDate'      => date('Y-m-d', strtotime($info['date'])),
    'supid'       => $info['supplier'],
    'tAmount'     => $info['tAmount'],
    'pAmount'     => $info['pAmount'],
    'dAmount'     => $info['dAmount'],
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'notes'       => $info['note'],
    'regby'       => $_SESSION['uid']
        );
       // var_dump($purchase); exit();
  $result = $this->pm->insert_data('lc_management',$purchase);

  $length = count($info['product']);
  
  for($i = 0; $i < $length; $i++)
    {
    $pproduct = array(
      'lcid'     => $result,
      'pid'      => $info['product'][$i],
      'hscode'   => $info['hscode'][$i],
      'weight'   => $info['weight'][$i],
      'uprice'   => $info['uprice'][$i],                    
      'tprice'   => $info['tprice'][$i],
      'quantity' => $info['quantity'][$i],
      'upprice'  => $info['upprice'][$i],
      'regby'    => $_SESSION['uid']
            );
        //var_dump($purchase_product);            
    $result2 = $this->pm->insert_data('lc_product',$pproduct); 
    }
    
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> LC Management add Successfully !</h4></div>'
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
  redirect('Lcmanagement');
}

public function view_lc_management($id)
  {
  $data['title'] = 'LC Management';

  $where = array(
    'lcid' => $id
        );
  $other = array(
    'join' => 'left'
        );
  $pfield = array(
    'lc_product' => 'lc_product.*',
    'products' => 'products.pName,products.pCode'
          );
  $pjoin = array(
    'products' => 'products.pid = lc_product.pid'
          );
  
  $data['pproduct'] = $this->pm->get_data('lc_product',$where,$pfield,$pjoin,$other);

 $field = array(
    'lc_management' => 'lc_management.*',
    'suppliers' => 'suppliers.*'
        );    
  $join = array(
    'suppliers' => 'suppliers.supid = lc_management.supid'
        );

  $purchase = $this->pm->get_data('lc_management',$where,$field,$join,$other);
  $data['purchase'] = $purchase[0];
    //var_dump($cusid); exit();
  $data['company'] = $this->pm->company_details();
    
  $this->load->view('lcmanagement/view_lc',$data);
}

public function edit_lc_management($id)
  {
  $data['title'] = 'LC Management';

  $where = array(
    'lcid' => $id
        );
  $pfield = array(
    'lc_product' => 'lc_product.*',
    'products' => 'products.pName,products.pCode'
          );
  $pjoin = array(
    'products' => 'products.pid = lc_product.pid'
          );
  $other = array(
    'join' => 'left'
        );

  $data['pproduct'] = $this->pm->get_data('lc_product',$where,$pfield,$pjoin,$other);

  $purchase = $this->pm->get_data('lc_management',$where);
  $data['purchase'] = $purchase[0];

  $data['product'] = $this->pm->get_data('products',false);
  $data['supplier'] = $this->pm->get_data('suppliers',false);
    
  $this->load->view('lcmanagement/edit_lc',$data);
}

public function update_lc_management()
  {
  $info = $this->input->post();

  $purchase = array(
    'lcDate'      => date('Y-m-d', strtotime($info['date'])),
    'supid'       => $info['supplier'],
    'tAmount'     => $info['tAmount'],
    'pAmount'     => $info['pAmount'],
    'dAmount'     => $info['dAmount'],
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'notes'       => $info['note'],
    'upby'        => $_SESSION['uid']
        );

  $where = array(
    'lcid' => $info['lcid']
        );
  $result = $this->pm->update_data('lc_management',$purchase,$where);
  $this->pm->delete_data('lc_product',$where);

  $length = count($info['product']);
        
  for($i = 0; $i < $length; $i++)
    {
    $pproduct = array(
      'lcid'     => $info['lcid'],
      'pid'      => $info['product'][$i],
      'hscode'   => $info['hscode'][$i],
      'weight'   => $info['weight'][$i],
      'uprice'   => $info['uprice'][$i],                    
      'tprice'   => $info['tprice'][$i],
      'quantity' => $info['quantity'][$i],
      'upprice'  => $info['upprice'][$i],
      'regby'    => $_SESSION['uid']
            );
        //var_dump($pproduct);exit();            
    $result2 = $this->pm->insert_data('lc_product',$pproduct); 
    // $pclnth = count($info['quantity'][$i]);
    }

  if($result || $result2)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> LC Management update Successfully !</h4></div>'
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
  redirect('Lcmanagement');
}

public function delete_lc_management($id)
  {
  $where = array(
    'lcid' => $id
        );
  $result = $this->pm->delete_data('lc_management',$where);
  $result2 = $this->pm->delete_data('lc_product',$where);
 

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-check"></i> LC Management delete Successfully !</h4></div>'
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
  redirect('Lcmanagement');
}

public function get_lc_payment()
  {
  $section = $this->pm->get_lc_payment($_POST['id']);
  $someJSON = json_encode($section);
  echo $someJSON;
}

public function save_lc_payment()
  {
  $info = $this->input->post();

  $sale = [
    'lcid'        => $info['lcid'],
    'lcpDate'     => date('Y-m-d', strtotime($info['lcpDate'])),
    'pAmount'     => $info['pAmount'],
    'cid'         => $info['currency'],
    'pRate'       => $info['pRate'],
    'cAmount'     => $info['cAmount'],
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],   
    'notes'       => $info['notes'],    
    'regby'       => $_SESSION['uid']
        ];
    //var_dump($sale); exit();
  $result = $this->pm->insert_data('lc_payment',$sale);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> LC Payment add Successfully !</h4></div>'
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
  redirect('Lcmanagement');
}











public function purchases_reports()
  {
  $data['title'] = 'Purchase Reports';
  $data['supplier'] = $this->pm->get_data('suppliers',false);

  if(isset($_GET['search']))
    {
    $report = $_GET['reports'];
    
    if($report == 'dailyReports')
      {
      $sdate = date("Y-m-d", strtotime($_GET['sdate']));
      $edate = date("Y-m-d", strtotime($_GET['edate']));
      $supplier = $_GET['dsupplier'];
      $data['sdate'] = $sdate;
      $data['edate'] = $edate;
      $data['report'] = $report;
            //var_dump($employee); exit();
      $data['purchase'] = $this->pm->get_dpurchses_data($sdate,$edate,$supplier);
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
      $supplier = $_GET['msupplier'];
      $data['name'] = $name;
      $data['report'] = $report;

      $data['purchase'] = $this->pm->get_mpurchses_data($month,$year,$supplier);
      }
    else if ($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $supplier = $_GET['ysupplier'];
      $data['year'] = $year;
      $data['report'] = $report;

      $data['purchase'] = $this->pm->get_ypurchses_data($year,$supplier);
      }
    }
  else
    {
    $data['purchase'] = $this->pm->get_purchses_data();
    }

  $this->load->view('purchase/purchase_reports',$data);
}




}