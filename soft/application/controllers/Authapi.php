<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');

class Authapi extends CI_Controller {

public function __construct()
  {
  parent::__construct();
  $this->load->model("Prime_model","pm");
  header('Content-Type: application/json;charset=utf-8');  
}


public function get_order_data()
  {
  $other = array(
    'order_by' => 'oid',
    'join' => 'left'
        );
  $field = array(
    'order' => 'order.*',
    'customers' => 'customers.customerName,customers.mobile'
        );
  $join = array(
    'customers' => 'customers.customerID = order.custid'
        );

  $order = $this->pm->get_data('order',false,$field,$join,$other);
            //var_dump(count($users));
  if(count($order)>0)
    {            
    $rsponse = array(
      "message" => "Order found",
      "data" => $order
          );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse=array(
      "message" => "Order not Found",     
          );
    echo json_encode($rsponse);
    }
}

public function get_order_product_data($oid)
  {
  $other = array(
    'join' => 'left'
        );
  $where = array('oid' => $oid);
  $field = array(
    'order_product' => 'order_product.product,order_product.oPrice,order_product.oQnt,order_product.tPrice',
    'products' => 'products.productName,products.productcode'
        );
  $join = array(
    'products' => 'products.productID = order_product.product'
        );
    
  $product = $this->pm->get_data('order_product',$where,$field,$join,$other);
            //var_dump(count($users));
  if(count($product)>0)
    {            
    $rsponse = array(
      "message" => "Order found",
      "product" => $product
          );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse=array(
      "message" => "Order not Found",     
          );
    echo json_encode($rsponse);
    }
}

public function get_inprogress_order_data() // new order
  {
  $where = array(
    'order.status' => 1
        );
  $other = array(
    'order_by' => 'oid',
    'join' => 'left'
        );
  $field = array(
    'order' => 'order.*',
    'customers' => 'customers.customerName,customers.mobile'
        );
  $join = array(
    'customers' => 'customers.customerID = order.custid'
        );

  $order = $this->pm->get_data('order',$where,$field,$join,$other);
            //var_dump(count($users));
  if(count($order)>0)
    {            
    $rsponse = array(
      "message" => "Order found",
      "data" => $order
          );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse=array(
      "message" => "Order not Found",     
          );
    echo json_encode($rsponse);
    }
}

public function get_sale_order_data() // after sale order list
  {
  $where = array(
    'order.status' => 2
        );
  $other = array(
    'order_by' => 'oid',
    'join' => 'left'
        );
  $field = array(
    'order' => 'order.*',
    'customers' => 'customers.customerName,customers.mobile'
        );
  $join = array(
    'customers' => 'customers.customerID = order.custid'
        );

  $order = $this->pm->get_data('order',$where,$field,$join,$other);
            //var_dump(count($users));
  if(count($order)>0)
    {            
    $rsponse = array(
      "message" => "Order found",
      "data" => $order
          );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse=array(
      "message" => "Order not Found",     
          );
    echo json_encode($rsponse);
    }
}

public function get_cancel_order_data() // after canceled order list
  {
  $where = array(
    'order.status' => 5
        );
  $other = array(
    'order_by' => 'oid',
    'join' => 'left'
        );
  $field = array(
    'order' => 'order.*',
    'customers' => 'customers.customerName,customers.mobile'
        );
  $join = array(
    'customers' => 'customers.customerID = order.custid'
        );

  $order = $this->pm->get_data('order',$where,$field,$join,$other);
            //var_dump(count($users));
  if(count($order)>0)
    {            
    $rsponse = array(
      "message" => "Order found",
      "data" => $order
          );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse=array(
      "message" => "Order not Found",     
          );
    echo json_encode($rsponse);
    }
}

public function get_new_order()
  {
  $product = $this->pm->get_data('products',false);
            //var_dump(count($users));
  if(count($product)>0)
    {            
    $rsponse = array(
      "message" => "Product found",
      "data" => $product
          );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse=array(
      "message" => "product not Found",     
          );
    echo json_encode($rsponse);
    }
}

public function post_save_order_data()
  {
  $info = $this->input->post();
  
  $company = $this->pm->company_profile_details(); 
   //var_dump($company); exit();
  if($info['customerID'])
    {
    $custid = $info['customerID'];
    }
  else
    {
    $custMob = $this->db->select('mobile')->from('customers')->where('mobile',$info['newcust_mobile'])->get()->row();
    
    if($custMob)
        {
        $rsponse = array(
          "message" => "This Customer Allready added"
              );
        echo json_encode($rsponse);
        }
    else{
    $cquery = $this->db->select('customerID')
                  ->from('customers')
                  //->where('compid',$_SESSION['compid'])
                  ->limit(1)
                  ->order_by('customerID','DESC')
                  ->get()
                  ->row();
    if($cquery)
        {
        $csn = $cquery->customerID+1;
        }
    else
        {
        $csn = 1;
        }
    //var_dump($sn); exit();
    $ccn = strtoupper(substr($company->com_name,0,3));
    $pcc = sprintf("%'05d",$csn);

    $cust = 'C-'.$ccn.$pcc;
    
    $custdata = array(
        'compid'       => $company->compid,
        'cus_id'       => $cust,
        'customerName' => $info['newcust_name'],
        'mobile'       => $info['newcust_mobile'],
        'address'      => $info['newcust_add'],
        'email'        => 0,
        'balance'      => 0,
        'regby'        => $info['uid']
            );

    $custid = $this->pm->insert_data('customers',$custdata);
    }
    }
   // var_dump($custid); exit();

  $query = $this->db->select('oid')
                ->from('order')
                ->limit(1)
                ->order_by('oid','DESC')
                ->get()
                ->row();
  if($query)
    {
    $sn = $query->oid+1;
    }
  else
    {
    $sn = 1;
    }

  $cn = strtoupper(substr($company->com_name,0,3));
  $pc = sprintf("%'05d",$sn);

  $cusid = $cn.'O-'.$pc;

  $order = array(
    'compid'     => $company->compid,
    'oCode'      => $cusid,
    'oDate'      => $info['date'],
    'custid'     => $custid,
    'tAmount'    => $info['totalAmount'],
    'paidAmount' => $info['paidAmount'],
    'dueAmount'  => $info['totalAmount']-$info['paidAmount'],
    'scost'      => $info['shiping_cost'],
    'dOption'    => $info['dOption'],
    //'shmethod'   => $info['shmethod'],
    'note'       => $info['note'],
    'regby'      => $info['uid']
        );

  $result = $this->pm->insert_data('order',$order);
  
  if($result)
    {
    $length = count($info['product']);
    
    for($i = 0; $i < $length; $i++)
      {
      $porder = array(
        'oid'     => $result,
        'product' => $info['product'][$i],
        'oPrice'  => $info['price'][$i],
        'oQnt'    => $info['quantity'][$i],                 
        'tPrice'  => $info['totalPrice'][$i],
        'regby'   => $info['uid']
            );

      $result2 = $this->pm->insert_data('order_product',$porder);
      }
    }
  
  if($result)
    {
    $rsponse = array(
      "message" => "Order added Success",
      "data" => $result
          );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse = array(
      "message" => "Order added Filed",      
          );
    echo json_encode($rsponse);
    }
}

public function get_view_order_data($oid)
  {
  $where = array('oid' => $oid);
  $other = array(
    'join' => 'left'
        );
  $field = array(
    'order' => 'order.*',
    'customers' => 'customers.customerName,customers.mobile'
        );
  $join = array(
    'customers' => 'customers.customerID = order.custid'
        );
    
  $order = $this->pm->get_data('order',$where,$field,$join,$other);
  
  $pfield = array(
    'order_product' => 'order_product.oPrice,order_product.oQnt,order_product.tPrice',
    'products' => 'products.productName,products.productcode'
        );
  $pjoin = array(
    'products' => 'products.productID = order_product.product'
        );
    
  $product = $this->pm->get_data('order_product',$where,$pfield,$pjoin,$other);
                
  if(count($order)>0)
    {            
    $rsponse = array(
      "message" => "Order found",
      "data" => $order[0],
      "product" => $product
        );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse = array(
      "message" => "Order not Found",      
          );
    echo json_encode($rsponse);
    }
}

public function post_order_update_data($oid)
  {
  $info = $this->input->post();

  $where = array(
    'oid' => $oid
        );
  $order = array(
    'oDate'   => $info['date'],
    'custid'  => $info['customerID'],
    'tAmount' => $info['totalPrice'],
    'scost'   => $info['shiping_cost'],
    'dOption' => $info['dOption'],
    'shmethod' => $info['shmethod'],
    'note'    => $info['note'],
    'upby'    => $info['uid']
        );

  $result = $this->pm->update_data('order',$order,$where);
  $this->pm->delete_data('order_product',$where);

  if($result)
    {
    $length = count($info['product']);
    
    for($i = 0; $i < $length; $i++)
      {
      $porder = array(
        'oid'     => $result,
        'product' => $info['product'][$i],
        'oPrice'  => $info['price'][$i],
        'oQnt'    => $info['quantity'][$i],                 
        'tPrice'  => $info['totalPrice'][$i],
        'regby'   => $info['uid']
            );

      $result2 = $this->pm->insert_data('order_product',$porder);
      }
    }
  
  if($result)
    {
    $rsponse = array(
      "message" => "Order update Success",
          );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse = array(
      "message" => "Order update Filed",      
          );
    echo json_encode($rsponse);
    }
}

public function post_order_delete_data($oid)
  {
  $where = array(
    'oid' => $oid
        );

  $result = $this->pm->delete_data('order',$where);
  $this->pm->delete_data('order_product',$where);
  
  if($result)
    {
    $rsponse = array(
      "message" => "Order delete Success",
          );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse = array(
      "message" => "Order delete Filed",      
          );
    echo json_encode($rsponse);
    }
}

public function post_order_cancel_data($oid)
  {
  $info = $this->input->post();

  $where = array(
    'oid' => $oid
        );
  $order = array(
    'status' => 5,
    'upby'   => $info['uid']
        );

  $result = $this->pm->update_data('order',$order,$where);

  if($result)
    {
    $rsponse = array(
      "message" => "Order Cancel Success",
          );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse = array(
      "message" => "Order Cancel Filed",      
          );
    echo json_encode($rsponse);
    }
}

public function get_order_sale_data($oid)
  {
  $where = array(
    'oid' => $id
        );
  $order = $this->pm->get_data('order',$where);
            //var_dump(count($users));
  if(count($order)>0)
    {            
    $rsponse = array(
      "message" => "Order found",
      "data" => $order[0]
          );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse=array(
      "message" => "Order not Found",     
          );
    echo json_encode($rsponse);
    }
}

public function post_order_sale()
  {
  $info = $this->input->post();

  $where = array(
    'oid' => $info['oid']
        );

  $order = array(
    'status' => 2,
    'upby'   => $info['uid']
        );

  $this->pm->update_data('order',$order,$where);

  $query = $this->db->select('saleID')
                  ->from('sales')
                  ->limit(1)
                  ->order_by('saleID','DESC')
                  ->get()
                  ->row();
  if($query)
    {
    $sn = $query->saleID+1;
    }
  else
    {
    $sn = 1;
    }

  $company = $this->pm->company_profile_details(); 
  $cn = strtoupper(substr($company->com_name,0,3));
  $pc = sprintf("%'05d", $sn);

  //$cusid = 'INV-'.$cn.$pc;
  $cusid = $pc;

  $orderSale = array(
    'compid'      => $company->compid,
    'invoice_no'  => $cusid,
    'saleDate'    => $info['date'],
    'customerID'  => $info['customerID'],
    'totalAmount' => $info['tPrice'],
    'paidAmount'  => 0,
    'scost'       => $info['shiping_cost'],
    'dOption'     => $info['dOption'],
    'accountType' => 'Cash',
    'accountNo'   => 1,
    'note'        => $info['note'],
    'sstatus'     => 'Online Sell',
    'regby'       => $info['uid']
        );
    
  $result = $this->pm->insert_data('sales',$orderSale);

  if($result)
    {
    $length = count($info['productID']);
    
    for($i = 0; $i < $length; $i++)
      {
      $sproduct = array(
        'saleID'     => $result,
        'productID'  => $info['productID'][$i],
        'sprice'     => $info['sprice'][$i],
        'quantity'   => $info['quantity'][$i],                 
        'totalPrice' => $info['totalPrice'][$i],
        'regby'      => $info['uid']
            );
      //var_dump($purchase_product);            
      $result2 = $this->pm->insert_data('sale_product',$sproduct);

      $pid = $info['productID'][$i];
      $aid = $company->compid;

      $swhere = array(
        'product' => $pid,
        'compid' => $aid
                );

      $stpd = $this->pm->get_data('stock',$swhere);
      $this->pm->delete_data('stock',$swhere);

      if($stpd)
        {
        $tquantity = $stpd[0]['totalPices']-($info['quantity'][$i]);
        }
      else
        {
        $tquantity = '-'.($info['quantity'][$i]);
        }

      $stock_info = array(
        'compid'     => $aid,
        'product'    => $pid,
        'totalPices' => $tquantity,
        'regby'      => $info['uid']
                );
      //var_dump($stock_info);    
      $this->pm->insert_data('stock',$stock_info);  
      }
    }

  
  if($result)
    {
    $rsponse = array(
      "message" => "Order Sale Success",
        );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse = array(
      "message" => "Order Sale Filed",      
        );
    echo json_encode($rsponse);
    }
}

public function get_order_track_data($oid = null)
  {
  if($oid)
    {
    $order = $this->pm->get_order_track_data($oid);
    }
  else
    {
    $order = '';
    }
            //var_dump(count($users));
  if($order)
    {            
    $rsponse = array(
      "message" => "Order Track found by Order Number",
      "data" => $order
          );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse=array(
      "message" => "Order Track not Found",     
          );
    echo json_encode($rsponse);
    }
}

public function get_order_mtrack_data($oid = null)
  {
  if($oid)
    {
     $morder = $this->pm->get_morder_track_data($oid);
    }
  else
    {
    $order = '';
    }
            //var_dump(count($users));
  if($morder)
    {            
    $rsponse = array(
      "message" => "Order Track found by Mobile Number",
      "data" => $morder
          );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse=array(
      "message" => "Order Track not Found",     
          );
    echo json_encode($rsponse);
    }
}

public function get_product_stock_data()
  {
  $product = $this->pm->get_product_stock_data();
  
  if(count($product)>0)
    {            
    $rsponse = array(
      "message" => "Product Stock found",
      "data" => $product
          );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse=array(
      "message" => "Product Stock not Found",     
          );
    echo json_encode($rsponse);
    }
}

public function get_fresh_product_stock_data()
  {
  $product = $this->pm->get_product_stock_data();
  
  if(count($product)>0)
    {            
    $rsponse = array(
      "message" => "Product Stock found",
      "data" => $product
          );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse=array(
      "message" => "Product Stock not Found",     
          );
    echo json_encode($rsponse);
    }
}

public function get_damage_product_stock_data()
  {
  $product = $this->pm->get_dproduct_sstock_data();
  
  if(count($product)>0)
    {            
    $rsponse = array(
      "message" => "Product Stock found",
      "data" => $product
          );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse=array(
      "message" => "Product Stock not Found",     
          );
    echo json_encode($rsponse);
    }
}

public function get_customer_data()
  {
  $customer = $this->pm->get_data('customers',false);
  
  if(count($customer)>0)
    {            
    $rsponse = array(
      "message" => "Customer found",
      "data" => $customer
          );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse=array(
      "message" => "Customer not Found",     
          );
    echo json_encode($rsponse);
    }
}

public function post_save_customer_data()
  {
  $info = $this->input->post();
  
  $where = array(
    'mobile' => $info['mobile']
        );
  $customer = $this->pm->get_data('customers',$where);
  
  if($customer)
    {
    $rsponse = array(
      "message" => "Customer All ready exit",
        );
    echo json_encode($rsponse);
    }
  else
    {
  $query = $this->db->select('customerID')
                  ->from('customers')
                  ->limit(1)
                  ->order_by('customerID','DESC')
                  ->get()
                  ->row();
  if($query)
    {
    $sn = $query->customerID+1;
    }
  else
    {
    $sn = 1;
    }

  $company = $this->pm->company_profile_details(); 
  $cn = strtoupper(substr($company->com_name,0,3));
  $pc = sprintf("%'05d",$sn);

  $cusid = 'C-'.$cn.$pc;

  $data = array(
    'compid'       => $company->compid,
    'cus_id'       => $cusid,
    'customerName' => $info['customerName'],
    'mobile'       => $info['mobile'],
    'email'        => $info['email'],
    'address'      => $info['address'],
    'balance'      => $info['balance'],         
    'regby'        => $info['uid']
        );
    
  $result = $this->pm->insert_data('customers',$data);

  if($result)
    {
    $rsponse = array(
      "message" => "Customer add Success",
        );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse = array(
      "message" => "Customer add Filed",      
        );
    echo json_encode($rsponse);
    }
    }
}

public function post_login_user_data()
  {
  $info = $this->input->post();

  $uname = $info['username'];

  if(is_numeric($uname))
    {     
    $where = array(
      'mobile'   => '+88'.$info['username'],
      'status'   => 'Active',
      'password' => $info['password']
          );
    }
  else
    {
    $where = array(
      'email'    => $info['username'],
      'status'   => 'Active',
      'password' => $info['password']
          );
    }
    // var_dump($where); //exit();
  $user_data = $this->pm->get_data('users',$where);
    
  if(count($user_data)>0)
    {            
    $rsponse = array(
      "message" => "User found",
      "data" => $user_data[0]
          );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse=array(
      "message" => "User not Found",     
          );
    echo json_encode($rsponse);
    }
}

public function get_staf_data()
  {
  $staf = $this->pm->get_data('users',false);
  
  if(count($staf)>0)
    {            
    $rsponse = array(
      "message" => "Staf found",
      "data" => $staf
          );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse=array(
      "message" => "Staf not Found",     
          );
    echo json_encode($rsponse);
    }
}

public function post_order_search_data()
  {
  $info = $this->input->post();
  
  $sdate = date("Y-m-d", strtotime($info['sdate']));
  $edate = date("Y-m-d", strtotime($info['edate']));
  $uid = $info['stafid'];
  
//   $sdate = "2021-07-11";
//   $edate = "2021-07-15";
//   $uid = 13;
      
  $order = $this->pm->user_dorder_ledger($uid,$sdate,$edate);
  $torder = $this->pm->count_total_order();
  $porder = $this->pm->count_total_porder();
  $corder = $this->pm->count_total_corder();
  $sorder = $this->pm->count_total_sorder();
  
  if(count($order)>0)
    {            
    $rsponse = array(
      "message" => "Order found",
      "data" => $order,
      "torder" => $torder,
      "porder" => $porder,
      "corder" => $corder,
      "sorder" => $sorder
          );
    echo json_encode($rsponse);
    }
  else
    {
    $rsponse=array(
      "message" => "Order not Found",     
          );
    echo json_encode($rsponse);
    }
}








}