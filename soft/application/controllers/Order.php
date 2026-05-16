<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');

class Order extends CI_Controller {

public function __construct()
  {
  parent::__construct();
  $this->load->model("prime_model","pm");
  $this->checkPermission();
}

public function index()
  {
  $data['title'] = 'Pre-Order';
  
  $total_records = $this->pm->count_all('order');

  $limit = 10;
  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  $offset = ($page - 1) * $limit;
  $total_pages = ceil($total_records / $limit);
  
  $other = array(
    'order_by' => 'oid',
    'join' => 'left',
    'limit'    => $limit,
    'offset'   => $offset
        );
  $field = array(
    'order' => 'order.*',
    'customers' => 'customers.custName,customers.custMobile'
        );
  $join = array(
    'customers' => 'customers.custid = order.custid'
        );
  $data['order'] = $this->pm->get_data('order',false,$field,$join,$other);
  //var_dump($data['purchase']); exit();
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
  
  $this->load->view('order/order_list',$data);
}

public function new_order() 
  {
  $data['title'] = 'Pre-Order';

  $where = array(
    'status' => 'Active'
        );
  $data['product'] = $this->pm->get_data('products',$where);
  $data['customer'] = $this->pm->get_data('customers',$where);
  $data['category'] = $this->pm->get_data('categories',$where);

  $this->load->view('order/new_order',$data);
}

public function getProduct($id)
  {
  $where = array(
    'stock.pid' => $id
        );
  $other = array(
    'join' => 'left'
        );
  $field = array(
    'products' => 'products.*',
    'stock' => 'stock.tquantity'
        );
  $join = array(
    'stock' => 'products.pid = stock.pid'
        );
  $productlist = $this->pm->get_data('products',$where,$field,$join,$other);

  $str = "";
  foreach ($productlist as $value)
    {
    $id = $value['pid'];
    $str .= "<tr>
    <td>".$value['pName'].' ( '.$value['pCode'].' )'."<input type='hidden' name='product[]' value='".$value['pid']."' required ></td>
    <td><input type='text' class='form-control' name='quantity[]' id='quantity_".$value['pid']."' onkeyup='getTotal(".$id.")' value='0' required ></td>
    <td><input type='text' class='form-control' name='sprice[]' onkeyup='getTotal(".$id.")' id='sprice_".$value['pid']."' value='".$value['sprice']."' required ></td>
    <td><input type='text' class='form-control tprice' name='tprice[]' id='tprice_".$value['pid']."' value='0' required readonly ></td>
    <td><span class='item_remove btn btn-danger btn-xs' onClick='$(this).parent().parent().remove();'>x</span></td></tr>";
    }
  echo json_encode($str);
}

public function save_order()
  {
  $info = $this->input->post();

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

  $cn = strtoupper(substr($_SESSION['compname'],0,3));
  $pc = sprintf("%'05d",$sn);

  $cusid = $cn.'O-'.$pc;

  $quotation = array(
    'compid'  => $_SESSION['compid'],
    'oCode'   => $cusid,
    'oDate'   => date('Y-m-d',strtotime($info['date'])),
    'custid'  => $info['customer'],
    'tAmount' => $info['tAmount'],
    'pAmount' => $info['pAmount'],
    'dAmount' => $info['dAmount'],
    'note'    => $info['note'],
    'regby'   => $_SESSION['uid']
        );
      //var_dump($quotation); exit();
  $result = $this->pm->insert_data('order',$quotation);
        //var_dump($purchase_id); exit();
  $length = count($info['product']);
    
  for($i = 0; $i < $length; $i++)
    {
    $qdata = array(
        'oid'     => $result,
        'pid'     => $info['product'][$i],
        'oPrice'  => $info['sprice'][$i],
        'oQnt'    => $info['quantity'][$i],                 
        'tPrice'  => $info['tprice'][$i],
        'regby'   => $_SESSION['uid']
            );
      //var_dump($purchase_product);            
    $result2 = $this->pm->insert_data('order_product',$qdata);
    }
    
  if($result2)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Order add Successfully !</h4>
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
  redirect('Order');
}

public function view_Order($id)
  {
  $data['title'] = 'Pre-Order';

  $where = array(
    'oid' => $id
        );
  $join = array(
    'products' => 'products.pid = order_product.pid'
        );
  $data['pquotation'] = $this->pm->get_data('order_product',$where,false,$join);
  
  $field = array(
    'order' => 'order.*',
    'customers'=>'customers.*',
    'users'=>'users.name'
        );
  $join = array(
    'customers' => 'customers.custid = order.custid',
    'users' => 'users.uid = order.regby'
        );
  $quotation = $this->pm->get_data('order',$where,$field,$join);
  $data['quotation'] = $quotation[0];    
  
  $data['company'] = $this->pm->company_details();
  
  $this->load->view('order/view_order',$data);
}

public function edit_Order($id)
  {
  $data['title'] = 'Pre-Order';

  $cwhere = array(
    'status' => 'Active'
        );
  $data['customer'] = $this->pm->get_data('customers',$cwhere);
  $data['product'] = $this->pm->get_data('products',$cwhere);
  $data['category'] = $this->pm->get_data('categories',$cwhere);

  $where = array(
    'oid' => $id
        );
  $join = array(
    'products' => 'products.pid = order_product.pid'
        );
  $data['pquotation'] = $this->pm->get_data('order_product',$where,false,$join);

  $quotation = $this->pm->get_data('order',$where);
  $data['quotation'] = $quotation[0];    
  
  $this->load->view('order/edit_order',$data);
}

public function update_Order()
  {
  $info = $this->input->post();

  $where = array(
    'oid' => $info['oid']
        );

  $quotation = array(
    'compid'  => $_SESSION['compid'],
    'oDate'   => date('Y-m-d',strtotime($info['date'])),
    'custid'  => $info['customer'],
    'tAmount' => $info['tAmount'],
    'pAmount' => $info['pAmount'],
    'dAmount' => $info['dAmount'],
    'note'    => $info['note'],
    'upby'    => $_SESSION['uid']
        );

  $result = $this->pm->update_data('order',$quotation,$where);

  $this->pm->delete_data('order_product',$where);
  
  $length = count($this->input->post('product'));

  for($i = 0; $i < $length; $i++)
    {
    $qproduct = array(
      'oid'     => $info['oid'],
      'pid'     => $info['product'][$i],
      'oPrice'  => $info['sprice'][$i],
      'oQnt'    => $info['quantity'][$i],                 
      'tPrice'  => $info['tprice'][$i],
      'regby'   => $_SESSION['uid']
          );
    //var_dump($quotation_product); exit();
    $result2 = $this->pm->insert_data('order_product',$qproduct);
    }
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Order update Successfully !</h4>
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
  redirect('Order');
}

public function sale_Order($id)
  {
  $data['title'] = 'Pre-Order';

  $cwhere = array(
    'status' => 'Active'
        );
  $data['customer'] = $this->pm->get_data('customers',$cwhere);
  $data['product'] = $this->pm->get_data('products',$cwhere);

  $where = array(
    'oid' => $id
        );
  $join = array(
    'products' => 'products.pid = order_product.pid'
        );
  $data['pquotation'] = $this->pm->get_data('order_product',$where,false,$join);

  $quotation = $this->pm->get_data('order',$where);
  $data['quotation'] = $quotation[0];    
  
  $this->load->view('order/sale_order',$data);
}

public function savle_sale_Order()
  {
  $info = $this->input->post();

  $where = array(
    'oid' => $info['oid']
        );

  $order = array(
    'status' => 2,
    'upby'   => $_SESSION['uid']
        );

  $this->pm->update_data('order',$order,$where);

  $query = $this->db->select('said')
                  ->from('sales')
                  ->limit(1)
                  ->order_by('said','DESC')
                  ->get()
                  ->row();
  if($query)
    {
    $sn = $query->said+1;
    }
  else
    {
    $sn = 1;
    }
  $cn = strtoupper(substr($_SESSION['compname'],0,3));
  $pc = sprintf("%'05d", $sn);

  $cusid = 'SA-'.$cn.$pc;

  $quotation = array(
    'compid'      => $_SESSION['compid'],
    'invoice'     => $cusid,
    'saDate'      => date('Y-m-d',strtotime($info['date'])),
    'custid'      => $info['customer'],
    'tAmount'     => $info['tAmount'],
    'pAmount'     => $info['pAmount'],
    'dAmount'     => $info['dAmount'],
    'accountType' => 'Cash',
    'accountNo'   => 1,
    'note'        => $info['note'],
    'regby'       => $_SESSION['uid']
        );
      //var_dump($quotation); exit();
  $result = $this->pm->insert_data('sales',$quotation);
        //var_dump($purchase_id); exit();
  if($result)
    {
    $length = count($info['product']);
    
    for($i = 0; $i < $length; $i++)
      {
      $qdata = array(
        'said'     => $result,
        'pid'      => $info['product'][$i],
        'sprice'   => $info['sprice'][$i],
        'quantity' => $info['quantity'][$i],                 
        'tprice'   => $info['tprice'][$i],
        'regby'    => $_SESSION['uid']
            );
      //var_dump($purchase_product);            
      $result2 = $this->pm->insert_data('sale_product',$qdata);

      $swhere = array(
        'pid' => $info['product'][$i]
                );

      $stpd = $this->pm->get_data('stock',$swhere);
      $this->pm->delete_data('stock',$swhere);

      if($stpd)
        {
        $tquantity = $stpd[0]['tquantity']-$info['quantity'][$i];
        }
      else
        {
        $tquantity = '-'.$info['quantity'][$i];
        }

      $stock_info = array(
        'compid'    => $_SESSION['compid'],
        'pid'       => $info['product'][$i],
        'tquantity' => $tquantity,
        'regby'     => $_SESSION['uid']
                );
      //var_dump($stock_info);    
      $this->pm->insert_data('stock',$stock_info);  
      }
    }
  if($result2)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Order sale add Successfully !</h4>
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
  redirect('Order');
}

public function delete_Order($id)
  {
  $where = array(
    'oid' => $id
        );

  $result = $this->pm->delete_data('order',$where);
  $result2 = $this->pm->delete_data('order_product',$where);
  
  if($result && $result2)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Order delete Successfully !</h4>
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
  redirect('Order');
}

public function cancel_Order($id)
  {
  $where = array(
    'oid' => $id
        );
  $quotation = array(
    'status' => 5,
    'upby'   => $_SESSION['uid']
        );

  $result = $this->pm->update_data('order',$quotation,$where);
  
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Cancel delete Successfully !</h4>
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
  redirect('Order');
}

public function order_ledger_report()
  {
  $data['title'] = 'Order Report';
  $data['customer'] = $this->pm->get_data('users',false);
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
      $uid = $_GET['dcustomer'];
      
      $where = array(
        'uid' => $uid
            );
      $data['users'] = $this->pm->get_data('users',$where);
      $data['sale'] = $this->pm->user_dorder_ledger($uid,$sdate,$edate);
      }
    else if ($report == 'monthlyReports')
      {
      $month = $_GET['month'];
      $data['month'] = $month;
      $year = $_GET['year'];
      $data['year'] = $year;

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
      $uid = $_GET['mcustomer'];
      
      $where = array(
        'uid' => $uid
            );
      $data['users'] = $this->pm->get_data('users',$where);
      $data['sale'] = $this->pm->user_morder_ledger($uid,$month,$year);
      }
    else if ($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $data['year'] = $year;
      $data['report'] = $report;
      $uid = $_GET['ycustomer'];
      
      $where = array(
        'uid' => $uid
            );
      $data['users'] = $this->pm->get_data('users',$where);
      $data['sale'] = $this->pm->user_yorder_ledger($uid,$year);
      }
    else if ($report == 'allReports')
      {
      $uid = $_GET['customer'];
      $data['report'] = $report;
      
      $where = array(
        'uid' => $uid
            );
      $data['users'] = $this->pm->get_data('users',$where);
      $data['sale'] = $this->pm->user_aorder_ledger($uid);
      }
    }
  else
    {
    $data['sale'] = $this->pm->user_order_ledger();
    }

  $this->load->view('order/order_report',$data);
}










}