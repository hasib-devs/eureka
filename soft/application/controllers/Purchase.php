<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');

class Purchase extends CI_Controller {

public function __construct()
  {
  parent::__construct();
  $this->load->model("prime_model","pm");
  $this->checkPermission();
}

public function index()
  {
  $data['title'] = 'Purchase';
  
  $total_records = $this->pm->count_all('purchase');

  $limit = 10;
  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  $offset = ($page - 1) * $limit;
  $total_pages = ceil($total_records / $limit);
  
  $other = array(
    'order_by' => 'puid',
    'join' => 'left',
    'limit'    => $limit,
    'offset'   => $offset
        );
  $join = array(
    'suppliers' => 'suppliers.supid = purchase.supid'
        );
  $field = array(
    'purchase' => 'purchase.*',
    'suppliers' => 'suppliers.supName'
        );    
  $data['purchase'] = $this->pm->get_data('purchase',false,$field,$join,$other);
  
  $pagination_html = '<ul class="pagination">';

  if($page > 1)
    {
    $pagination_html .= '<li class="paginated"><a href="?page=' . ($page - 1) . '">Previous</a></li>';
    }

  $num_links = 5;
  $half_links = floor($num_links / 2);
  $start_page = max(1, $page - $half_links);
  $end_page = min($total_pages, $start_page + $num_links - 1);

  if($page > 1)
    {
    $pagination_html .= '<li class="paginated"><a href="?page=' . ($page - 1) . '">Prev</a></li>';
    }

  for($i = $start_page; $i <= $end_page; $i++)
    {
    $pagination_html .= '<li class="paginated';
    if($page == $i)
      {
      $pagination_html .= ' active';
      }
    $pagination_html .= '"><a href="?page=' . $i . '">' . $i . '</a></li>';
    }

  if($end_page < $total_pages)
    {
    $pagination_html .= '<li class="disabled"><span>...</span></li>';
    }
    
  if($page < $total_pages)
    {
    $pagination_html .= '<li class="paginated"><a href="?page=' . ($page + 1) . '">Next</a></li>';
    }

  $pagination_html .= '</ul>';

  $data['pagination_html'] = $pagination_html;
  
  $this->pm->get_purchase_total_quantity();

  $this->load->view('purchase/purchase_list',$data);
}

public function new_purchase() 
  {
  $data['title'] = 'Purchase';

  $where = array(
    'status' => 'Active',
        );
  $data['product'] = $this->pm->get_data('products',$where);
  $data['supplier'] = $this->pm->get_data('suppliers',$where);
  $data['purtype'] = $this->pm->get_data('purchase_type',$where);
  $data['category'] = $this->pm->get_data('categories',$where);
  
//   $other = array(
//     'join'     => 'left' 
//           );
//   $field = array(
//     'products'   => 'products.*',
//     'categories' => 'categories.catName, categories.catid',
//           );
//   $join = array(
//     'categories' => 'categories.catid = products.catid',
//           );
//   $productlist = $this->pm->get_data('products',false, $field,$join,$other);

  $this->load->view('purchase/newPurchase',$data);
}




public function get_purchase_supplier()
  {
  $other = array(
    'order_by' => 'supid'
        );
  $grup = $this->pm->get_data('suppliers',false,false,false,$other);
  $someJSON = json_encode($grup);
  echo $someJSON;
}



public function get_product($id)
  {
  $str = "";
  $where = array(
    'pid' => $id
        );
  $other = array(
    'order_by' => 'pid',
    'join'     => 'left' 
          );
  $field = array(
    'products'   => 'products.*',
    'categories' => 'categories.catName, categories.catid',
          );
  $join = array(
    'categories' => 'categories.catid = products.catid',
          );
  $productlist = $this->pm->get_data('products',$where, $field,$join,$other);
    //   var_dump($productlist);exit();
  foreach($productlist as $value)
    {
    $id = $value['pid'];
    if($value['catid'] <= '8')
      {
      $str .= "<tr>
        <td>".$value['pName'].' ( '.$value['pCode'].' )'."<input type='hidden' name='product[]' value='".$id."' required ></td>
        <td><input type='text' class='form-control' name='partNo[]' value='".$value['partNo']."' ></td>
        <td><input type='text' class='form-control' name='pChassis[]' value='".$value['hsn']."' ></td>
        <td><input type='text' class='form-control' name='pEngine[]' value='".$value['model']."' ></td>
        <td><input type='text' class='form-control' id='quantity_".$id."' onkeyup='getTotal(".$id.")' name='quantity[]' value='1' required ></td>
        <td><input type='text' class='form-control' onkeyup='getTotal(".$id.")' id='pprice_".$id."' name='pprice[]' value='".$value['pprice']."' required ></td>
        <td>
          <input type='text' class='form-control' onkeyup='getTotal(".$id.")' id='igst_".$id."' name='igst[]' required >
          <input type='hidden' class='taxamt' id='tax_".$id."' value='0' required >
        </td>
        <td><input type='text' class='form-control tprice' id='tprice_".$id."' name='tprice[]' value='".$value['pprice']."' required readonly ></td>
        <td><span class='item_remove btn btn-danger btn-xs' onclick='deleteProduct(this)'>x</span></td></tr>";
      }
    else
      {
      $str .= "<tr>
        <td>".$value['pName'].' ( '.$value['pCode'].' )'."<input type='hidden' name='product[]' value='".$id."' required ></td>
        <td><input type='text' class='form-control' name='partNo[]' value='N/A' readonly ></td>
        <td><input type='text' class='form-control' name='pChassis[]' value='N/A' readonly ></td>
        <td><input type='text' class='form-control' name='pEngine[]' value='N/A' readonly ></td>
        <td><input type='text' class='form-control' onkeyup='getTotal(".$id.")' id='quantity_".$id."' name='quantity[]' value='1' required ></td>
        <td><input type='text' class='form-control' onkeyup='getTotal(".$id.")' id='pprice_".$id."' name='pprice[]' value='".$value['pprice']."' required ></td>
        <td>
          <input type='text' class='form-control' onkeyup='getTotal(".$id.")' id='igst_".$id."' name='igst[]' required >
          <input type='hidden' class='taxamt' id='tax_".$id."' value='0' required >
        </td>
        <td><input type='text' class='form-control tprice' id='tprice_".$id."' name='tprice[]' value='".$value['pprice']."' required readonly ></td>
        <td><span class='item_remove btn btn-danger btn-xs' onclick='deleteProduct(this)'>x</span></td></tr>";
      }
    }
  echo json_encode($str);
}

public function get_supplier_amount()
  {
  $section = $this->pm->get_supplier_amount($_POST['id']);
  $someJSON = json_encode($section);
  echo $someJSON;
}

public function saved_purchase()
  {
  $info = $this->input->post();
    //   $cat = $this->db->select('purchase_product.*, products.pid, products.catid, categories.catid')
    //           ->from('purchase_product')
    //         //   ->where('pid', $info['pid'])
    //           ->join('products', 'products.pid = purchase_product.pid', 'left')
    //           ->join('categories', 'categories.catid = products.catid', 'left')
    //           ->get()
    //           ->row();
    // var_dump($cat);exit();

  $query = $this->db->select('puid')
                  ->from('purchase')
                  ->limit(1)
                  ->order_by('puid','DESC')
                  ->get()
                  ->row();
  if($query)
    {
    $sn = $query->puid+1;
    }
  else
    {
    $sn = 1;
    }
  $cn = strtoupper(substr($_SESSION['compname'],0,3));
  $pc = sprintf("%'05d",$sn);

  $cusid = 'PU-'.$cn.$pc;
  
    //   if(($info['tAmount']+$info['vAmount']) >= ($info['pAmount']))
    //     {
    //     $paid =$info['pAmount'];
    //     $due = $info['dAmount'];
    //     }
    //   else
    //     {
    //     $paid = $info['tAmount']+$info['vAmount'];
    //     $due = 0;
    //     }
    //var_dump($cusid); exit();
  $purchase = array(
    'compid'      => $_SESSION['compid'],
    'challanNo'   => $cusid,
    'puDate'      => date('Y-m-d', strtotime($info['date'])),
    'supid'       => $info['supplier'],
    // 'ptid'        => $info['purtype'],
    'tAmount'     => $info['tAmount'],
    'vAmount'     => $info['vAmount'],
    'pAmount'     => $info['pAmount'],
    'dAmount'     => $info['dAmount'],
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'note'        => $info['note'],
    'regby'       => $_SESSION['uid']
        );
       // var_dump($purchase); exit();
  $result = $this->pm->insert_data('purchase',$purchase);

  $length = count($info['product']);
  
  for($i = 0; $i < $length; $i++)
    {
    $pproduct = array(
      'puid'      => $result,
      'pid'       => $info['product'][$i],
      'ppChassis' => $info['pChassis'][$i],
      'partNo'    => $info['partNo'][$i],
      'ppEngine'  => $info['pEngine'][$i],
      //'ppColor'   => $info['pColor'][$i],
      'quantity'  => $info['quantity'][$i],
      'pprice'    => $info['pprice'][$i],
      'igst'      => $info['igst'][$i],
      'tprice'    => $info['tprice'][$i],
      'regby'     => $_SESSION['uid']
            );
        //var_dump($purchase_product);            
    $result2 = $this->pm->insert_data('purchase_product',$pproduct); 
    
    
    // $pclnth = count($info['pChassis'][$i]);
    
  
    // for($j = 0; $j < $pclnth; $j++)
    //   {
    //   $ppchassis = array(
    //     'puid'      => $result,
    //     'ppid'      => $result2,
    //     'pid'       => $info['product'][$i],
    //     'ppChassis' => $info['pChassis'][$j],
    //     'ppEngine'  => $info['pEngine'][$j],
    //     //'ppColor'   => $info['pColor'][$j],
    //     'regby'     => $_SESSION['uid']
    //           );
    //     //var_dump($purchase_product);            
    //   $this->pm->insert_data('purchase_chassis',$ppchassis); 
    //   }
      
    $swhere = array(
      'pid' => $info['product'][$i]
              );

    $stpd = $this->pm->get_data('stock',$swhere);
    //$this->pm->delete_data('stock',$swhere);

    if($stpd)
      {
      $tqnt = $info['quantity'][$i]+$stpd[0]['tquantity'];
      $dtqnt = $stpd[0]['dtquantity'];
      }
    else
      {
      $tqnt = $info['quantity'][$i];
      $dtqnt = 0;
      }

    // $stock = array(
    //   'compid'     => $_SESSION['compid'],
    //   'pid'        => $info['product'][$i],
    //   'tquantity'  => $tqnt,
    //   'dtquantity' => $dtqnt,
    //   'regby'      => $_SESSION['uid']
    //           );
    //     //var_dump($stock_info);    
    // $this->pm->insert_data('stock',$stock);                 
    }
    
    if($info['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($info['accountNo']);
      
      $cdata = array(
        'current' => $cash->current-$info['pAmount'],
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
        'current' => $bank->current-$info['pAmount'],
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
        'current' => $mobile->current-$info['pAmount'],
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
      <h4><i class="icon fa fa-check"></i> Purchase add Successfully !</h4></div>'
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
  redirect('Purchase');
}

public function view_purchase($id)
  {
  $data['title'] = 'Purchase';

  $where = array(
    'puid' => $id
        );
  $other = array(
    'join' => 'left'
        );
  $pfield = array(
    'purchase_product' => 'purchase_product.*',
    'products' => 'products.*',
    'categories' => 'categories.catName, categories.catid'
          );
  $pjoin = array(
    'products' => 'products.pid = purchase_product.pid',
    'categories' => 'categories.catid = products.catid'
          );
  
  $data['pproduct'] = $this->pm->get_data('purchase_product',$where,$pfield,$pjoin,$other);

  $join = array(
    'suppliers' => 'suppliers.supid = purchase.supid',
    'purchase_type' => 'purchase_type.ptid = purchase.ptid'
          );
  $field = array(
    'purchase' => 'purchase.*',
    'supplier' => 'suppliers.supName, suppliers.supCode, suppliers.supMobile, suppliers.supAddress',
    'purchase_type' => 'purchase_type.ptName'
        );

  $purchase = $this->pm->get_data('purchase',$where,$field,$join,$other);
  $data['purchase'] = $purchase[0];

  //$sid = $purchase[0]['supplier'];
    //var_dump($cusid); exit();
  //$data['csdue'] = $this->pm->supplier_purchases_due_details($id,$sid);
  //$data['cvpa'] = $this->pm->supplier_paid_details($sid);
  $data['company'] = $this->pm->company_details();
    
  $this->load->view('purchase/viewPurchase',$data);
}

public function edit_purchase($id)
  {
  $data['title'] = 'Purchase';

  $pwhere = array(
    'puid' => $id
        );
  $pfield = array(
    'purchase_product' => 'purchase_product.*',
    'products' => 'products.pName,products.pCode'
          );
  $pjoin = array(
    'products' => 'products.pid = purchase_product.pid'
          );
  $other = array(
    'join' => 'left'
        );

  $data['pproduct'] = $this->pm->get_data('purchase_product',$pwhere,$pfield,$pjoin,$other);

  $purchase = $this->pm->get_data('purchase',$pwhere);
  $data['purchase'] = $purchase[0];

  $where = array(
    'status' => 'Active',
        );
  $data['product'] = $this->pm->get_data('products',$where);
  $data['supplier'] = $this->pm->get_data('suppliers',$where);
  $data['purtype'] = $this->pm->get_data('purchase_type',$where);
  $data['category'] = $this->pm->get_data('categories',$where);
    
  $this->load->view('purchase/editPurchase',$data);
}

public function update_purchase()
  {
  $info = $this->input->post();

  $purchase = array(
    'puDate'      => date('Y-m-d', strtotime($info['date'])),
    'supid'       => $info['supplier'],
    'ptid'        => $info['purtype'],
    'tAmount'     => $info['tAmount'],
    'vAmount'     => $info['vAmount'],
    'pAmount'     => $info['pAmount'],
    'dAmount'     => $info['dAmount'],
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'note'        => $info['note'],
    'upby'        => $_SESSION['uid']
        );

  $where = array(
    'puid' => $info['puid']
        );
  $ppurchase = $this->pm->get_data('purchase',$where);
  $result = $this->pm->update_data('purchase',$purchase,$where);
  $pproduct = $this->pm->get_data('purchase_product',$where);
  $this->pm->delete_data('purchase_product',$where);
  $this->pm->delete_data('purchase_chassis',$where);
  $lnth = count($pproduct);

  for($i = 0; $i < $lnth; $i++)
    {
    $sswhere = array(
      'pid' => $pproduct[$i]['pid']
            );

    $stpd = $this->pm->get_data('stock',$sswhere);
    //$this->pm->delete_data('stock',$sswhere);
        
    if($stpd)
      {
      if($pproduct)
        {
        $tquantity = ($stpd[0]['tquantity']-$pproduct[$i]['quantity']);
        $dtqnt = $stpd[0]['dtquantity'];
        }
      else
        {
        $tquantity = $stpd[0]['tquantity'];
        $dtqnt = $stpd[0]['dtquantity'];
        }
      }
    else
      {
      $tquantity = 0;
      $dtqnt = 0;
      }

    $stockinfo = array(
      'compid'     => $_SESSION['compid'],
      'pid'        => $pproduct[$i]['pid'],
      'tquantity'  => $tquantity,
      'dtquantity' => $dtqnt,
      'regby'      => $_SESSION['uid']
              );
        //var_dump($stock_info);    
    //$this->pm->insert_data('stock',$stockinfo); 
    }

  $length = count($info['product']);
        
  for($i = 0; $i < $length; $i++)
    {
    $pproduct = array(
      'puid'      => $info['puid'],
      'pid'       => $info['product'][$i],
      'partNo'    => $info['partNo'][$i],
      'ppChassis' => $info['pChassis'][$i],
      'ppEngine'  => $info['pEngine'][$i],
      'quantity'  => $info['quantity'][$i],
      'pprice'    => $info['pprice'][$i],
      'igst'      => $info['igst'][$i],
      'tprice'    => $info['tprice'][$i],
      'regby'     => $_SESSION['uid']
            );
        //var_dump($pproduct);exit();            
    $result2 = $this->pm->insert_data('purchase_product',$pproduct); 
    // $pclnth = count($info['quantity'][$i]);
        
    // for($j = 0; $j < $info['quantity'][$i]; $j++)
    //   {
    //   $ppchassis = array(
    //     'puid'      => $info['puid'],
    //     'ppid'      => $result2,
    //     'pid'       => $info['product'][$i],
    //     'ppChassis' => $info['pChassis'][$j],
    //     'ppEngine'  => $info['pEngine'][$j],
    //     //'ppColor'   => $info['pColor'][$j],
    //     'regby'     => $_SESSION['uid']
    //           );
    //     //var_dump($purchase_product);            
    //   $this->pm->insert_data('purchase_chassis',$ppchassis); 
    //   }
    $swhere = array(
      'pid' => $info['product'][$i]
              );

    $stpd = $this->pm->get_data('stock',$swhere);
    //$this->pm->delete_data('stock',$swhere);

    if($stpd)
      {
      $tqnt = $info['quantity'][$i]+$stpd[0]['tquantity'];
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
    //$this->pm->insert_data('stock',$stock);                 
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
        'current' => $cash->current-$info['pAmount'],
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
        'current' => $bank->current-$info['pAmount'],
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
        'current' => $mobile->current-$info['pAmount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $info['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }

  if($result || $result2)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Purchase update Successfully !</h4></div>'
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
  redirect('Purchase');
}

public function approve_purchase($id)
  {
  $data['title'] = 'Purchase';

  $pwhere = array(
    'puid' => $id
        );
  $pfield = array(
    'purchase_product' => 'purchase_product.*',
    'products' => 'products.pName,products.pCode'
          );
  $pjoin = array(
    'products' => 'products.pid = purchase_product.pid'
          );
  $other = array(
    'join' => 'left'
        );

  $data['pproduct'] = $this->pm->get_data('purchase_product',$pwhere,$pfield,$pjoin,$other);

  $purchase = $this->pm->get_data('purchase',$pwhere);
  $data['purchase'] = $purchase[0];

  $where = array(
    'status' => 'Active',
        );
  $data['product'] = $this->pm->get_data('products',$where);
  $data['supplier'] = $this->pm->get_data('suppliers',$where);
  $data['purtype'] = $this->pm->get_data('purchase_type',$where);
  $data['category'] = $this->pm->get_data('categories',$where);
    
  $this->load->view('purchase/appPurchase',$data);
}

public function save_approve_purchase()
  {
  $info = $this->input->post();
  $purchase = array(
    'puDate'      => date('Y-m-d', strtotime($info['date'])),
    'supid'       => $info['supplier'],
    'tAmount'     => $info['tAmount'],
    'vAmount'     => $info['vAmount'],
    'pAmount'     => $info['pAmount'],
    'dAmount'     => $info['dAmount'],
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'note'        => $info['note'],
    'status'      => 1,
    'upby'        => $_SESSION['uid']
        );

  $where = array(
    'puid' => $info['puid']
        );
  $result = $this->pm->update_data('purchase',$purchase,$where);
  
  $pproduct = $this->pm->get_data('purchase_product',$where);
  $this->pm->delete_data('purchase_product',$where);
  $this->pm->delete_data('purchase_chassis',$where);
  
  $length = count($info['product']);
        
  for($i = 0; $i < $length; $i++)
    {
    $pproduct = array(
      'puid'      => $info['puid'],
      'pid'       => $info['product'][$i],
      'partNo'    => $info['partNo'][$i],
      'ppChassis' => $info['pChassis'][$i],
      'ppEngine'  => $info['pEngine'][$i],
      'quantity'  => $info['quantity'][$i],
      'pprice'    => $info['pprice'][$i],
      'igst'      => $info['igst'][$i],
      'tprice'    => $info['tprice'][$i],
      'regby'     => $_SESSION['uid']
            );
        //var_dump($pproduct);exit();            
    $result2 = $this->pm->insert_data('purchase_product',$pproduct); 
    // $pclnth = count($info['quantity'][$i]);
        
    // for($j = 0; $j < $info['quantity'][$i]; $j++)
    //   {
    //   $ppchassis = array(
    //     'puid'      => $info['puid'],
    //     'ppid'      => $result2,
    //     'pid'       => $info['product'][$i],
    //     'ppChassis' => $info['pChassis'][$i],
    //     'ppEngine'  => $info['pEngine'][$i],
    //     //'ppColor'   => $info['pColor'][$j],
    //     'regby'     => $_SESSION['uid']
    //           );
    //     //var_dump($purchase_product);            
    //   $this->pm->insert_data('purchase_chassis',$ppchassis); 
    //   }
    $swhere = array(
      'pid' => $info['product'][$i]
              );

    $stpd = $this->pm->get_data('stock',$swhere);
    $this->pm->delete_data('stock',$swhere);

    if($stpd)
      {
      $tqnt = $info['quantity'][$i]+$stpd[0]['tquantity'];
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
     $res2ult = $this->pm->insert_data('stock',$stock);                 
    }
    
  if($result || $res2ult)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Purchase product approve Successfully !</h4>
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
  redirect('Purchase');
}

public function delete_purchases($id)
  {
  $where = array(
    'puid' => $id
        );
  $ppurchase = $this->pm->get_data('purchase',$where);
  $result = $this->pm->delete_data('purchase',$where);
  $pproduct = $this->pm->get_data('purchase_product',$where);
  $result2 = $this->pm->delete_data('purchase_product',$where);
  $this->pm->delete_data('purchase_chassis',$where);
    
  $lnth = count($pproduct);

  for($i = 0; $i < $lnth; $i++)
    {
    $sswhere = array(
      'pid' => $pproduct[$i]['pid']
            );

    $stpd = $this->pm->get_data('stock',$sswhere);
    //$this->pm->delete_data('stock',$sswhere);
        
    if($stpd)
      {
      if($pproduct)
        {
        $tquantity = ($stpd[0]['tquantity']-$pproduct[$i]['quantity']);
        $dtqnt = $stpd[0]['dtquantity'];
        }
      else
        {
        $tquantity = $stpd[0]['tquantity'];
        $dtqnt = $stpd[0]['dtquantity'];
        }
      }
    else
      {
      $tquantity = 0;
      $dtqnt = 0;
      }

    $stockinfo = array(
      'compid'     => $_SESSION['compid'],
      'pid'        => $pproduct[$i]['pid'],
      'tquantity'  => $tquantity,
      'dtquantity' => $dtqnt,
      'regby'      => $_SESSION['uid']
              );
        //var_dump($stock_info);    
    //$this->pm->insert_data('stock',$stockinfo); 
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
      'exception' =>'<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Purchase delete Successfully !</h4></div>'
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
  redirect('Purchase');
}

public function get_purchase_payment()
  {
  $section = $this->pm->get_purchase_payment($_POST['id']);
  $someJSON = json_encode($section);
  echo $someJSON;
}

public function save_purchase_payment()
  {
  $info = $this->input->post();

  $sale = [
    'puid'    => $info['puid'],
    'tAmount' => $info['tAmount'],
    'pAmount' => $info['pAmount'],
    'notes'   => $info['notes'],    
    'regby'   => $_SESSION['uid']
        ];
    //var_dump($sale); exit();
  $result = $this->pm->insert_data('purchase_payment',$sale);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Purchase Payment add Successfully !</h4></div>'
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
  redirect('Purchase');
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