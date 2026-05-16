<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');

require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

class Sale extends CI_Controller {

public function __construct() {
  parent::__construct();
  $this->load->model("prime_model","pm");
  $this->checkPermission();
    //$this->load->library('PHPExcel');
    //$this->load->library('excel');
}

public function index()
  {
  $data['title'] = 'Sale';
  
  $total_records = $this->pm->count_all('sales');

  $limit = 10;
  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  $offset = ($page - 1) * $limit;
  $total_pages = ceil($total_records / $limit);
  
  $other = array(
    'join' => 'left',
    'order_by' => 'said',
    //'limit'    => $limit,
    //'offset'   => $offset
        );
  $field = array(
    'sales' => 'sales.*',
    'customers' => 'customers.custName,customers.custMobile'
        );
  $join = array(
    'customers' => 'customers.custid = sales.custid'
        );
  $data['sales'] = $this->pm->get_data('sales',false,$field,$join,$other);
  
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
  
  $this->pm->get_sales_total_quantity();

  $this->load->view('sale/sales_list',$data);
}

public function new_sale()
  {
  $data['title'] = 'Sale';
   $where = array(
    'status' => 'Active' 
        );

  $data['category'] = $this->pm->get_data('categories',$where);
  $data['customer'] = $this->pm->get_data('customers',false);
  $data['product'] = $this->pm->get_data('products',false);
  $data['courier'] = $this->pm->get_data('courier',false);
  $data['employees'] = $this->pm->get_data('employees',false);

  $this->load->view('sale/NewSale',$data);
}

public function get_sale_customer()
  {
  $other = array(
    'order_by' => 'custid'
            );
  $section = $this->pm->get_data('customers',false,false,false,$other);
  $someJSON = json_encode($section);
  echo $someJSON;
} 

public function get_product()
   {
  $catid = $this->input->post('catid');
  $Subwhere = array(
    'status' => 'Active',
    'catid' => $catid
        );
  $products = $this->pm->get_data('products', $Subwhere);
  
  $options = '<option value="">Select One</option>';
  foreach ($products as $value) {
    $options .= '<option value="' . $value['pid'] . '">' . $value['pName'].' ( '.$value['partNo'].' - '.$value['pCode'].' )'. '</option>';
        }
  echo $options;
} 

public function get_product_details($id,$saType)
  {
  $category = '';

  $where = array(
    'products.pid' => $id,
    'stock.tquantity >' => 0
      );
  $other = array(
    'join' => 'left'
        );
  $field = array(
    'products' => 'products.*',
    'stock' => 'stock.tquantity',
        );
  $join = array(
    'stock' => 'products.pid = stock.pid'
        );

  $products = $this->pm->get_data('products',$where,$field,$join,$other);
  
  $str = '';
  foreach($products as $value)
    {
    $pid = $value['pid'];
    $tpq = $value['tquantity'];
    
    $cost = $this->db->select('pprice,sprice')
                  ->from('costing')
                  ->where('pid',$pid)
                  ->get()
                  ->row();
    if($cost)
      {
      $pprice = $cost->pprice;
      $sprice = $cost->sprice;
      }
    else
      {
      $pprice = $value['pprice'];
      $sprice = $value['sprice'];
      }
    $readonly = ($saType == 1) ? 'readonly' : '';
    
    if ($value['catid'] == '1'){
    $str.="<tr>
      <td>".$value['pName']."<input type='hidden' name='product[]' value='".$value['pid']."' required ></td>
      <td><select class='form-control' name='spType[]' onchange='productcostPrice(".$value['pid'].")' id='spType_".$value['pid']."' required ><option value='1'>Costing</option><option value='2'>Local</option></select></td>
      <td><input type='text' class='form-control' name='pChassis[]' value='".$value['partNo']."' required ></td>
      <td><input type='text' class='form-control' name='pColor[]' value='".$value['model']."' ></td>
      <td><input type='text' class='form-control' name='pEngine[]' id='pEngine_".$value['pid']."' value='".$pprice."' required readonly ></td>
      <td><input type='text' class='form-control' onkeyup='totalPrice(".$value['pid'].")' name='iRate[]' id='iRate_".$value['pid']."' value='1' required ".$readonly." ></td>
      <td>".$value['tquantity']."</td>
      <td><input type='text' class='form-control' onkeyup='totalPrice(".$value['pid'].")' name='quantity[]' id='quantity_".$value['pid']."' value='1' max='$tpq' min='1' required ></td>
      <td><input type='text' class='form-control' onkeyup='totalPrice(".$value['pid'].")' name='sprice[]' id='sprice_".$value['pid']."' value='".$sprice."' required  ></td>
      <td><input type='text' class='form-control' onkeyup='totalPrice(".$value['pid'].")' name='pdiscount[]' id='pdiscount_".$value['pid']."' value='0' ></td>
      <td><input type='text' class='form-control' onkeyup='totalPrice(".$value['pid'].")' name='iprice[]' id='iprice_".$value['pid']."' value='0' ></td>
      <td><input type='text' class='tprice form-control' name='tprice[]' id='tprice_".$value['pid']."' value='".$sprice."' required readonly ></td>
      <td><span class='item_remove btn btn-danger' onclick='deleteProduct(this)' >x</span>
      </td>
      </tr>";
    }
    else{
      $str.="<tr>
      <td>".$value['pName']."<input type='hidden' name='product[]' value='".$value['pid']."' required ></td>
      <td><select class='form-control' name='spType[]' onchange='productcostPrice(".$value['pid'].")' id='spType_".$value['pid']."' required ><option value='1'>Costing</option><option value='2'>Local</option></select></td>
      <td><input type='text' class='form-control' name='pChassis[]' value='".$value['partNo']."' required ></td>
      <td><input type='text' class='form-control' name='pColor[]' value='".$value['model']."' ></td>
      <td><input type='text' class='form-control' name='pEngine[]' id='pEngine_".$value['pid']."' value='".$pprice."' required readonly ></td>
      <td><input type='text' class='form-control' onkeyup='totalPrice(".$value['pid'].")' name='iRate[]' id='iRate_".$value['pid']."' value='1' required ".$readonly." ></td>
      <td>".$value['tquantity']."</td>
      <td><input type='text' class='form-control' onkeyup='totalPrice(".$value['pid'].")' name='quantity[]' id='quantity_".$value['pid']."' value='1' max='$tpq' min='1' required ></td>
      <td><input type='text' class='form-control' onkeyup='totalPrice(".$value['pid'].")' name='sprice[]' id='sprice_".$value['pid']."' value='".$sprice."' required  ></td>
      <td><input type='text' class='form-control' onkeyup='totalPrice(".$value['pid'].")' name='pdiscount[]' id='pdiscount_".$value['pid']."' value='0' ></td>
      <td><input type='text' class='form-control' onkeyup='totalPrice(".$value['pid'].")' name='iprice[]' id='iprice_".$value['pid']."' value='0' ></td>
      <td><input type='text' class='tprice form-control' name='tprice[]' id='tprice_".$value['pid']."' value='".$sprice."' required readonly ></td>
      <td><span class='item_remove btn btn-danger' onclick='deleteProduct(this)' >x</span>
      </td>
      </tr>";
    }
    }
  echo json_encode($str);
}

public function get_product_price_data($id,$spid)
  {
  $section = $this->pm->get_product_price_data($id,$spid);
  $someJSON = json_encode($section);
  echo $someJSON;
}

public function saved_sale()
  {
  $info = $this->input->post();

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

  $sale = array(
    'compid'      => $_SESSION['compid'],
    'invoice'     => $cusid,
    'saDate'      => date('Y-m-d', strtotime($info['date'])),
    'saType'      => $info['saType'],
    'custid'      => $info['customer'],
    'id'          => $info['id'],
    'cempid'      => $info['cempid'],
    'tAmount'     => $info['tAmount'],
    'pAmount'     => $info['pAmount'],
    'dAmount'     => $info['dAmount'],
    'disAmount'   => $info['discount'], 
    'vat'         => $info['salevat'],
    'charge'      => $info['charge'],
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'note'        => $info['note'],
    'comment'     => $info['comment'],
    'regby'       => $_SESSION['uid']
        );
        //var_dump($sale); exit();
  $result = $this->pm->insert_data('sales',$sale);
       
  $length = count($info['product']);

  for($i = 0; $i < $length; $i++)
    {
    $spdata = array(
      'said'      => $result,
      'pid'       => $info['product'][$i],
      'spType'    => $info['spType'][$i], 
      'spChassis' => $info['pChassis'][$i],                       
      'spEngine'  => $info['pEngine'][$i],
      'iRate'     => $info['iRate'][$i],
      'spColor'   => $info['pColor'][$i],
      'quantity'  => $info['quantity'][$i],
      'sprice'    => $info['sprice'][$i],
      'pdiscount' => $info['pdiscount'][$i],
      'iprice'    => $info['iprice'][$i],
      'tprice'    => $info['tprice'][$i],
      'regby'     => $_SESSION['uid']
            );

    $this->pm->insert_data('sale_product',$spdata);

    $swhere = array(
      'pid' => $info['product'][$i]
              );

    $stpd = $this->pm->get_data('stock',$swhere);
    $this->pm->delete_data('stock',$swhere);

    if($stpd)
      {
      $tqnt = $stpd[0]['tquantity']-$info['quantity'][$i];
      $dtqnt = $stpd[0]['dtquantity'];
      }
    else
      {
      $tqnt = '-'.$info['quantity'][$i];
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
    //var_dump($smsresult); exit();
    
    if($info['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($info['accountNo']);
      
      $cdata = array(
        'current' => $cash->current+$info['pAmount'],
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
        'current' => $bank->current+$info['pAmount'],
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
        'current' => $mobile->current+$info['pAmount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $info['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }
    
    
    // Start SMS
    
// Fetch customer mobile number and name
$cust = $this->db->select('custMobile, custName')
                 ->from('customers')
                 ->where('custid', $info['customer'])
                 ->get()
                 ->row();

if (!isset($cust->custMobile) || empty($cust->custMobile)) {
    log_message('error', 'Customer mobile number not found.');
    return;
}

// Extract necessary values
// $invoiceNo = $prints['invoice']; 
$customerName = $cust->custName; // Customer name
$totalAmount = $info['nAmount']-$info['charge']; // Total amount from form submission
$charge = $info['charge'];
$netAmount = $info['nAmount']; // Total amount from form submission
$paidAmount = $info['pAmount'];  // Paid amount from form submission
$dueAmount = $info['dAmount'];   // Due amount from form submission

// Construct the SMS message dynamically
$msg = "Welcome to Eureka Avenue!\n";
// $msg .= "Invoice No: {$invoiceNo}\n";
$msg .= "Customer Name: {$customerName}\n";
$msg .= "Products Price: " . number_format($totalAmount, 2) . " Taka\n";
$msg .= "Shipping Charge Amount: " . number_format($charge, 2) . " Taka\n";
$msg .= "Total Amount: " . number_format($netAmount, 2) . " Taka\n";
$msg .= "Paid Amount: " . number_format($paidAmount, 2) . " Taka\n";
$msg .= "Due Amount: " . number_format($dueAmount, 2) . " Taka\n";
$msg .= "Thank you for your order!";

// Prepare SMS API details
$token = urlencode("kzgohm6e-ibzg6yrh-potoh793-ecd5f90k-oi2qgqsm");
$message = urlencode($msg);
$to = urlencode($cust->custMobile);
$csms_id = uniqid(); // Generate unique client message ID
$url = "https://smsplus.sslwireless.com/api/v3/send-sms?api_token=$token&sid=EUREKAAVEOTP&sms=$message&msisdn=$to&csms_id=$csms_id";

// Initialize cURL and send SMS
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$smsresult = curl_exec($ch);

if (curl_errno($ch)) {
    log_message('error', 'SMS sending failed: ' . curl_error($ch));
} else {
    $response = json_decode($smsresult, true);
    if (isset($response['status']) && $response['status'] === 'SUCCESS') {
        log_message('info', 'SMS sent successfully: ' . $response['status']);
    } else {
        log_message('error', 'SMS sending failed: ' . $response['error_message']);
    }
}
curl_close($ch);

    // End Start SMS          
            
             //var_dump($smsresult); exit();  

    // Redirect with a success or failure message
    
      if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Product Sale Successfully !</h4></div>'
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
    //redirect('Sale');
  redirect('Sale');
}

public function view_invoice($id)
  {
  $data['title'] = 'Sales';

  $where = array(
    'said' => $id
        );
  $other = array(
    'join' => 'left'
        );
  $field = array(
    'sales' => 'sales.*',
    'customers' => 'customers.*',
    'users' => 'users.name, users.compname',
    'courier' => 'courier.empid',
    'employees' => 'employees.empName'
          );
  $join = array(
    'customers' => 'customers.custid = sales.custid',
    'users' => 'users.uid = sales.regby',
    'courier' => 'courier.id = sales.id',
    'employees' => 'employees.empid = sales.cempid'
          );
  $prints = $this->pm->get_data('sales',$where,$field,$join,$other);
  $data['prints'] = $prints[0];

  $pfield = array(
    'sale_product' => 'sale_product.*',
    'products' => 'products.pName, products.pCode, products.model, products.prNumber, products.pColor, products.capacity, products.uWeight, products.mkCountry',
    'categories' => 'categories.catName'
        );
  $pjoin = array(
    'products' => 'products.pid = sale_product.pid',
    'categories' => 'categories.catid = products.catid'
        );

  $data['salesp'] = $this->pm->get_data('sale_product',$where,$pfield,$pjoin,$other);
  $data['spay'] = $this->pm->get_sales_payment_data($id);
    //$cusid = $prints[0]['customerID'];
    //var_dump($cusid); exit();
    //$data['csdue'] = $this->pm->customer_sales_due_details($id,$cusid);
    //$data['cvpa'] = $this->pm->customer_vaucher_paid_details($cusid);
    //$data['cra'] = $this->pm->customer_returns_details($cusid);
  $data['company'] = $this->pm->company_details();
    
  $this->load->view('sale/print_page',$data);
}

public function view_part_invoice($id)
  {
  $data['title'] = 'Sales';

  $where = array(
    'said' => $id
        );
  $other = array(
    'join' => 'left'
        );
   $field = array(
    'sales' => 'sales.*',
    'customers' => 'customers.*',
    'users' => 'users.name, users.compname',
    'courier' => 'courier.courierName',
    'employees' => 'employees.empName'
          );
  $join = array(
    'customers' => 'customers.custid = sales.custid',
    'users' => 'users.uid = sales.regby',
    'courier' => 'courier.id = sales.id',
    'employees' => 'employees.empid = sales.cempid'
          );
  $prints = $this->pm->get_data('sales',$where,$field,$join,$other);
  $data['prints'] = $prints[0];

  $pfield = array(
    'sale_product' => 'sale_product.*',
    'products' => 'products.pName, products.pCode, products.model, products.prNumber, products.brand,  products.partNo',
    'categories' => 'categories.catName',
    'sma_units'  => 'sma_units.unitName'
    
        );
  $pjoin = array(
    'products' => 'products.pid = sale_product.pid',
    'categories' => 'categories.catid = products.catid',
    'sma_units'  => 'sma_units.untid = products.untid'
        );

  $data['salesp'] = $this->pm->get_data('sale_product',$where,$pfield,$pjoin,$other);
  $data['spay'] = $this->pm->get_sales_payment_data($id);
  
    $cusid = $prints[0]['custid'];
    // var_dump($cusid); exit();
    $data['csdue'] = $this->pm->customer_sales_due_details($id,$cusid);
    $data['cvpa'] = $this->pm->customer_vaucher_paid_details($cusid);
    $data['cra'] = $this->pm->customer_returns_details($cusid);
    $data['company'] = $this->pm->company_details();
    
  $this->load->view('sale/view_parts_sale.php',$data);
}

public function pdf_sale($id)
  {
  $data['title'] = 'Sales';

  $where = array(
    'said' => $id
        );
  $other = array(
    'join' => 'left'
        );
   $field = array(
    'sales' => 'sales.*',
    'customers' => 'customers.*',
    'users' => 'users.name, users.compname',
    'courier' => 'courier.courierName',
    'employees' => 'employees.empName'
          );
  $join = array(
    'customers' => 'customers.custid = sales.custid',
    'users' => 'users.uid = sales.regby',
    'courier' => 'courier.id = sales.id',
    'employees' => 'employees.empid = sales.cempid'
          );
  $prints = $this->pm->get_data('sales',$where,$field,$join,$other);
  $data['prints'] = $prints[0];

  $pfield = array(
    'sale_product' => 'sale_product.*',
    'products' => 'products.pName, products.pCode, products.model, products.prNumber, products.brand,  products.partNo',
    'categories' => 'categories.catName',
    'sma_units'  => 'sma_units.unitName'
    
        );
  $pjoin = array(
    'products' => 'products.pid = sale_product.pid',
    'categories' => 'categories.catid = products.catid',
    'sma_units'  => 'sma_units.untid = products.untid'
        );

  $data['salesp'] = $this->pm->get_data('sale_product',$where,$pfield,$pjoin,$other);
  $data['spay'] = $this->pm->get_sales_payment_data($id);
  
  $data['imagepath'] = 'https://soft.eurekaavenue.com/upload/company/PHOTO-2024-04-08-23-28-331.jpg';
  $cusid = $prints[0]['custid'];
    // var_dump($cusid); exit();
  $data['csdue'] = $this->pm->customer_sales_due_details($id,$cusid);
  $data['cvpa'] = $this->pm->customer_vaucher_paid_details($cusid);
  $data['cra'] = $this->pm->customer_returns_details($cusid);
  $data['company'] = $this->pm->company_details();
  
  $html = $this->load->view('sale/pdf_sale',$data,TRUE);
  
  $options = new Options();
  $options->set('isRemoteEnabled', TRUE);

  $dompdf = new Dompdf($options);
  $dompdf->loadHtml($html);
  $dompdf->setPaper('A4', 'portrait');
  $dompdf->render();

  $dompdf->stream("invoice_".$id.".pdf", array("Attachment" => 1));
}

public function view_sales_challan($id)
  {
  $data['title'] = 'Challan';

  $where = array(
    'said' => $id
        );
  $other = array(
    'join' => 'left'
        );
  $field = array(
    'sales' => 'sales.*',
    'customers' => 'customers.*',
    'users' => 'users.name, users.compname',
    'courier' => 'courier.courierName',
    'employees' => 'employees.empName'
          );
  $join = array(
    'customers' => 'customers.custid = sales.custid',
    'users' => 'users.uid = sales.regby',
    'courier' => 'courier.id = sales.id',
    'employees' => 'employees.empid = sales.cempid'
          );
  $prints = $this->pm->get_data('sales',$where,$field,$join,$other);
  $data['prints'] = $prints[0];

  $pfield = array(
    'sale_product' => 'sale_product.*',
    'products' => 'products.pName, products.pCode, products.model, products.prNumber, products.brand,  products.partNo',
    'categories' => 'categories.catName',
    'sma_units'  => 'sma_units.unitName'
    
        );
  $pjoin = array(
    'products' => 'products.pid = sale_product.pid',
    'categories' => 'categories.catid = products.catid',
    'sma_units'  => 'sma_units.untid = products.untid'
        );

  $data['salesp'] = $this->pm->get_data('sale_product',$where,$pfield,$pjoin,$other);
    //var_dump($cusid); exit();
  $data['company'] = $this->pm->company_details();
    
  $this->load->view('sale/view_challan',$data);
}

public function edit_sale($id)
  {
  $data['title'] = 'Sale';

  $where = array(
    'said' => $id
        );
  
  $whereOne = array(
    'status' => 'Active' 
        );

  $data['category'] = $this->pm->get_data('categories',$whereOne);
  
  
  $prints = $this->pm->get_data('sales',$where);
  $data['sale'] = $prints[0];
  
  $data['courier'] = $this->pm->get_data('courier',false);
  
  $other = array(
    'join' => 'left'
        );
  $pfield = array(
    'sale_product' => 'sale_product.*',
    'products' => 'products.pName,products.pCode,products.brand,products.partNo'
        );
  $pjoin = array(
    'products' => 'products.pid = sale_product.pid'
        );

  $data['salesp'] = $this->pm->get_data('sale_product',$where,$pfield,$pjoin,$other);
  $cwhere = array(
    'status' => 'Active'
        );
  $data['customer'] = $this->pm->get_data('customers',$cwhere);
  $data['product'] = $this->pm->get_data('products',$cwhere);
  $data['employees'] = $this->pm->get_data('employees',false);

  $this->load->view('sale/EditSale',$data);
}

public function update_sale()
  {
  $info = $this->input->post();
    
  $sale = array(
    'saDate'      => date('Y-m-d', strtotime($info['date'])),
    'saType'      => $info['saType'],
    'custid'      => $info['customer'],
    'tAmount'     => $info['tAmount'],
    'id'          => $info['id'],
    'cempid'      => $info['cempid'],
    'pAmount'     => $info['pAmount'],
    'dAmount'     => (($info['tAmount']+$info['salevat']+$info['charge'])-($info['pAmount']+$info['discount'])),
    'disAmount'   => $info['discount'], 
    'vat'         => $info['salevat'], 
    'charge'      => $info['charge'],
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'note'        => $info['note'],        
    'comment'     => $info['comment'],        
    'upby'        => $_SESSION['uid']
        );

  $where = array(
    'said' => $info['said']
        );
    //var_dump($sale); exit();
  $ppurchase = $this->pm->get_data('sales',$where);
  $result = $this->pm->update_data('sales',$sale,$where);
  $pp = $this->pm->get_data('sale_product',$where);
  $this->pm->delete_data('sale_product',$where);
  
  $lnth = count($pp);

  for($i = 0; $i < $lnth; $i++)
    {
    $sswhere = array(
      'pid' => $pp[$i]['pid']
            );

    $stpd = $this->pm->get_data('stock',$sswhere);
    $this->pm->delete_data('stock',$sswhere);
        
    if($stpd)
      {
      if($pp)
        {
        $tquantity = ($stpd[0]['tquantity']+$pp[$i]['quantity']);
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
      'pid'        => $pp[$i]['pid'],
      'tquantity'  => $tquantity,
      'dtquantity' => $dtqnt,
      'regby'      => $_SESSION['uid']
              );
        //var_dump($stock_info);    
    $this->pm->insert_data('stock',$stockinfo); 
    }

  $length = count($info['product']);

  for($i = 0; $i < $length; $i++)
    {
    $spdata = array(
      'said'      => $info['said'],
      'pid'       => $info['product'][$i], 
      'spType'    => $info['spType'][$i], 
      'spChassis' => $info['pChassis'][$i],                       
      'spEngine'  => $info['pEngine'][$i],
      'iRate'     => $info['iRate'][$i],
      'spColor'   => $info['pColor'][$i],
      'quantity'  => $info['quantity'][$i],
      'sprice'    => $info['sprice'][$i],
      'pdiscount' => $info['pdiscount'][$i],
      'iprice'    => $info['iprice'][$i],
      'tprice'    => $info['tprice'][$i],
      'regby'     => $_SESSION['uid']
            );

    $this->pm->insert_data('sale_product',$spdata);

    $swhere = array(
      'pid' => $info['product'][$i]
              );

    $stpd = $this->pm->get_data('stock',$swhere);
    $this->pm->delete_data('stock',$swhere);

    if($stpd)
      {
      $tqnt = $stpd[0]['tquantity']-$info['quantity'][$i];
      $dtqnt = $stpd[0]['dtquantity'];
      }
    else
      {
      $tqnt = '-'.$info['quantity'][$i];
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
        'current' => $cash->current+$info['pAmount'],
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
        'current' => $bank->current+$info['pAmount'],
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
        'current' => $mobile->current+$info['pAmount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $info['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }
  if($result || $ppurchase || $pp)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Sale product update Successfully !</h4></div>'
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
  redirect('Sale');
}

public function delete_sales($id)
  {
  $where = array(
    'said' => $id
        );
    //var_dump($sale); exit();
  $ppurchase = $this->pm->get_data('sales',$where);
  $result = $this->pm->delete_data('sales',$where);
  $pp = $this->pm->get_data('sale_product',$where);
  $result2 = $this->pm->delete_data('sale_product',$where);
  $this->pm->delete_data('sales_payment',$where);

  $lnth = count($pp);

  for($i = 0; $i < $lnth; $i++)
    {
    $sswhere = array(
      'pid' => $pp[$i]['pid']
            );

    $stpd = $this->pm->get_data('stock',$sswhere);
    $this->pm->delete_data('stock',$sswhere);
        
    if($stpd)
      {
      if($pp)
        {
        $tquantity = ($stpd[0]['tquantity']+$pp[$i]['quantity']);
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
      'pid'        => $pp[$i]['pid'],
      'tquantity'  => $tquantity,
      'dtquantity' => $dtqnt,
      'regby'      => $_SESSION['uid']
              );
        //var_dump($stock_info);    
    $this->pm->insert_data('stock',$stockinfo); 
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
      <h4><i class="icon fa fa-check"></i> Sale product delete Successfully !</h4></div>'
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
  redirect('Sale');
}

public function get_sales_payment()
  {
  $section = $this->pm->get_sales_payment($_POST['id']);
  $data = json_encode($section);
  echo $data;
}

public function save_sales_payment()
  {
  $info = $this->input->post();
    
  $sale = [
    'said'        => $info['said'],
    'tAmount'     => $info['tAmount'],
    'pAmount'     => $info['pAmount'],
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'notes'       => $info['notes'],            
    'regby'       => $_SESSION['uid']
        ];
    //var_dump($sale); exit();
  $result = $this->pm->insert_data('sales_payment',$sale);
    
    if($info['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($info['accountNo']);
      
      $cdata = array(
        'current' => $cash->current+$info['pAmount'],
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
        'current' => $bank->current+$info['pAmount'],
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
        'current' => $mobile->current+$info['pAmount'],
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
      <h4><i class="icon fa fa-check"></i> Sale Payment add Successfully !</h4></div>'
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
  redirect('Sale');
}

public function delete_sales_payment($id)
  {
  $where = array(
    'spid' => $id
        );
    //var_dump($sale); exit();
  $result = $this->pm->delete_data('sales_payment',$where);
    
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Sale Payment delete Successfully !</h4></div>'
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
  redirect('Sale');
}

public function all_sales_reports()
  {
  $data['title'] = 'Sales Report';

  $data['customer'] = $this->pm->get_data('customers',false);
  $data['employee'] = $this->pm->get_data('users',false);

  if(isset($_GET['search']))
    {
    $report = $_GET['reports'];
    
    if($report == 'dailyReports')
      {
      $sdate = date("Y-m-d", strtotime($_GET['sdate']));
      $edate = date("Y-m-d", strtotime($_GET['edate']));
      $customer = $_GET['dcustomer'];
      $employee = $_GET['demployee'];
      $data['sdate'] = $sdate;
      $data['edate'] = $edate;
      $data['report'] = $report;
      $data['customer'] = $customer;
      $data['employee'] = $employee;
            //var_dump($employee); exit();
      $data['sales'] = $this->pm->get_dsales_data($sdate,$edate,$customer,$employee);
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
      $customer = $_GET['mcustomer'];
      $employee = $_GET['memployee'];
      $data['name'] = $name;
      $data['report'] = $report;
      $data['customer'] = $customer;
      $data['employee'] = $employee;

      $data['sales'] = $this->pm->get_msales_data($month,$year,$customer,$employee);
      }
    else if ($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $customer = $_GET['mcustomer'];
      $employee = $_GET['memployee'];
      $data['year'] = $year;
      $data['report'] = $report;
      $data['customer'] = $customer;
      $data['employee'] = $employee;

      $data['sales'] = $this->pm->get_ysales_data($year,$customer,$employee);
      }
    }
  else
    {
    $data['sales'] = $this->pm->get_sales_data();
    }

  $this->load->view('sale/all_sales',$data);
}

public function edit_new_sales($id)
  {
  $data['title'] = 'BRTA Registration';

  $where = array(
    'said' => $id
        );

  $prints = $this->pm->get_data('sales',$where);
  $data['sale'] = $prints[0];

  $other = array(
    'join' => 'left'
        );
  $pfield = array(
    'sale_product' => 'sale_product.*',
    'products' => 'products.pName,products.pCode'
        );
  $pjoin = array(
    'products' => 'products.pid = sale_product.pid'
        );

  $data['salesp'] = $this->pm->get_data('sale_product',$where,$pfield,$pjoin,$other);
  $cwhere = array(
    'status' => 'Active'
        );
  $data['customer'] = $this->pm->get_data('customers',$cwhere);
  $data['product'] = $this->pm->get_data('products',$cwhere);

  $this->load->view('sale/edit_new_sales',$data);
}

public function edit_user_new_sales($id)
  {
  $data['title'] = 'BRTA Registration';

  $where = array(
    'said' => $id
        );

  $prints = $this->pm->get_data('sales_duplicate',$where);
  $data['sale'] = $prints[0];

  $other = array(
    'join' => 'left'
        );
  $pfield = array(
    'sales_dproduct' => 'sales_dproduct.*',
    'products' => 'products.pName,products.pCode'
        );
  $pjoin = array(
    'products' => 'products.pid = sales_dproduct.pid'
        );

  $data['salesp'] = $this->pm->get_data('sales_dproduct',$where,$pfield,$pjoin,$other);
  $cwhere = array(
    'status' => 'Active'
        );
  $data['customer'] = $this->pm->get_data('customers',$cwhere);
  $data['product'] = $this->pm->get_data('products',$cwhere);

  $this->load->view('sale/edit_user_new_sales',$data);
}

public function update_new_sale()
  {
  $info = $this->input->post();
    
  $sale = array(
    'compid'      => $_SESSION['compid'],
    'said'        => $info['said'],
    'invoice'     => $info['invoice'],
    'saDate'      => date('Y-m-d', strtotime($info['date'])),
    'custid'      => $info['customer'],
    'tAmount'     => $info['tAmount'],
    'pAmount'     => $info['pAmount'],
    'dAmount'     => $info['dAmount'],
    'disAmount'   => $info['discount'], 
    'vAmount'     => $info['salevat'], 
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'note'        => $info['note'],
    'psType'      => $info['psType'],   
    'regby'       => $_SESSION['uid']
        );
    
  $where = array(
    'said' => $info['said']
        );
  $dsales = $this->pm->get_data('sales_duplicate',$where);
    //var_dump($dsales); exit();
  if($dsales)
    {
    $result = $this->pm->update_data('sales_duplicate',$sale,$where);
    $this->pm->delete_data('sales_dproduct',$where);
    }
  else
    {
    $result = $this->pm->insert_data('sales_duplicate',$sale);
    }
  $length = count($info['product']);

  for($i = 0; $i < $length; $i++)
    {
    $spdata = array(
      'said'      => $info['said'],
      'pid'       => $info['product'][$i], 
      'spChassis' => $info['pChassis'][$i],                       
      'spEngine'  => $info['pEngine'][$i],                      
      'quantity'  => $info['quantity'][$i],
      'sprice'    => $info['sprice'][$i],
      'tprice'    => $info['tprice'][$i],
      'regby'     => $_SESSION['uid']
            );

    $this->pm->insert_data('sales_dproduct',$spdata);
    }
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Sale product Successfully !</h4></div>'
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
  redirect('saleDList');
}

public function sales_duplicate_list()
  {
  $data['title'] = 'BRTA Registration';
    
  $other = array(
    'join' => 'left',
    'order_by' => 'said'
        );
  $field = array(
    'sales_duplicate' => 'sales_duplicate.*',
    'customers' => 'customers.custName,customers.custMobile'
        );
  $join = array(
    'customers' => 'customers.custid = sales_duplicate.custid'
        );
  $data['sales'] = $this->pm->get_data('sales_duplicate',false,$field,$join,$other);

  $this->load->view('sale/sales_dlists',$data);
}

public function delete_duplicate_sales($id)
  {
  $where = array(
    'said' => $id
        );
    //var_dump($sale); exit();
  $result = $this->pm->delete_data('sales_duplicate',$where);
  $result2 = $this->pm->delete_data('sales_dproduct',$where);

  if($result && $result2)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Sale product delete Successfully !</h4></div>'
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
  redirect('saleDList');
}

public function view_new_sales($id)
  {
  $data['title'] = 'BRTA Registration';

  $where = array(
    'said' => $id
        );
  $other = array(
    'join' => 'left'
        );
  $field = array(
    'sales_duplicate' => 'sales_duplicate.*',
    'customers' => 'customers.*',
    'users' => 'users.name'
          );
  $join = array(
    'customers' => 'customers.custid = sales_duplicate.custid',
    'users' => 'users.uid = sales_duplicate.regby'
          );
  $prints = $this->pm->get_data('sales_duplicate',$where,$field,$join,$other);
  $data['prints'] = $prints[0];

  $pfield = array(
    'sales_dproduct' => 'sales_dproduct.*',
    'products' => 'products.pName,products.pCode,products.pColor',
    'categories' => 'categories.catName'
        );
  $pjoin = array(
    'products' => 'products.pid = sales_dproduct.pid',
    'categories' => 'categories.catid = products.catid'
        );

  $data['salesp'] = $this->pm->get_data('sales_dproduct',$where,$pfield,$pjoin,$other);
  $data['company'] = $this->pm->company_details();
    
  $this->load->view('sale/view_sales',$data);
}

public function sales_invoice_reports()
  {
  $data['title'] = 'Sales Report';

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
            //var_dump($employee); exit();
      $data['sales'] = $this->pm->sales_ddata($sdate,$edate);
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

      $data['sales'] = $this->pm->sales_mdata($month,$year);
      }
    else if ($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $data['year'] = $year;
      $data['report'] = $report;

      $data['sales'] = $this->pm->sales_ydata($year);
      }
    }
  else
    {
    $data['sales'] = $this->pm->sales_adata();
    }
    
  $data['company'] = $this->pm->company_details();

  $this->load->view('sale/sales_ireport',$data);
}

public function sales_due_reports()
  {
  $data['title'] = 'Due Report';

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
            //var_dump($employee); exit();
      $data['sales'] = $this->pm->sales_due_ddata($sdate,$edate);
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

      $data['sales'] = $this->pm->sales_due_mdata($month,$year);
      }
    else if($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $data['year'] = $year;
      $data['report'] = $report;

      $data['sales'] = $this->pm->sales_due_ydata($year);
      }
    }
  else
    {
    $data['sales'] = $this->pm->sales_due_adata();
    }
    
  $data['company'] = $this->pm->company_details();

  $this->load->view('sale/sales_dreport',$data);
}

public function sales_due_payment_reports()
  {
  $data['title'] = 'Due Payment Report';

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
            //var_dump($employee); exit();
      $data['sales'] = $this->pm->sales_due_dpayment_data($sdate,$edate);
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

      $data['sales'] = $this->pm->sales_due_mpayment_data($month,$year);
      }
    else if($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $data['year'] = $year;
      $data['report'] = $report;

      $data['sales'] = $this->pm->sales_due_ypayment_data($year);
      }
    }
  else
    {
    $data['sales'] = $this->pm->sales_due_payment_data();
    }
    
  $data['company'] = $this->pm->company_details();

  $this->load->view('sale/due_payment_report',$data);
}
########### Service Sales start ##################################

public function service_sale_list()
  {
  $data['title'] = 'Service';
    
  $other = array(
    'join' => 'left',
    'order_by' => 'ssid'
        );
  $field = array(
    'services_sale' => 'services_sale.*',
    'customers' => 'customers.custName,customers.custMobile'
        );
  $join = array(
    'customers' => 'customers.custid = services_sale.custid'
        );
  $data['sales'] = $this->pm->get_data('services_sale',false,$field,$join,$other);

  $this->load->view('sale/service_list',$data);
}

public function new_service_sale()
  {
  $data['title'] = 'Service';

  $data['customer'] = $this->pm->get_data('customers',false);
  $data['service'] = $this->pm->get_data('services',false);

  $this->load->view('sale/new_service',$data);
}

public function get_service_details($id)
  {
  $where = array(
    'sid' => $id
      );

  $products = $this->pm->get_data('services',$where);
  
  $str = '';
  foreach($products as $value)
    {
    $pid = $value['sid'];
    $str.="<tr>
      <td>".$value['sName'].' ( '.$value['sCode'].' )'."<input type='hidden' name='product[]' value='".$value['sid']."' required ></td>
      <td><input type='text' class='form-control' onkeyup='totalPrice(".$value['sid'].")' name='quantity[]' id='quantity_".$value['sid']."' value='1' min='1' required ></td>
      <td><input type='text' class='form-control' onkeyup='totalPrice(".$value['sid'].")' name='sprice[]' id='sprice_".$value['sid']."' value='".$value['sprice']."' required ></td>
      <td><input type='text' class='tprice form-control' name='tprice[]' id='tprice_".$value['sid']."' value='".$value['sprice']."' required readonly ></td>
      <td><span class='item_remove btn btn-danger' onclick='deleteProduct(this)' >x</span>
      </td>
      </tr>";
    }
  echo json_encode($str);
}

public function saved_sale_service()
  {
  $info = $this->input->post();

  $query = $this->db->select('ssid')
                ->from('services_sale')
                ->limit(1)
                ->order_by('ssid','DESC')
                ->get()
                ->row();
  if($query)
    {
    $sn = $query->ssid+1;
    }
  else
    {
    $sn = 1;
    }
  $cn = strtoupper(substr($_SESSION['compname'],0,3));
  $pc = sprintf("%'05d", $sn);
  $cusid = 'SV-'.$cn.$pc;

  $sale = array(
    'ssCode'     => $cusid,
    'ssDate'      => date('Y-m-d', strtotime($info['date'])),
    'custid'      => $info['customer'],
    'tAmount'     => $info['tAmount'],
    'disAmount'   => $info['discount'], 
    'vAmount'     => $info['salevat'], 
    'pAmount'     => $info['pAmount'],
    'dAmount'     => $info['dAmount'],
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'notes'       => $info['note'],
    'regby'       => $_SESSION['uid']
        );
        //var_dump($sale); exit();
  $result = $this->pm->insert_data('services_sale',$sale);
       
  $length = count($info['product']);

  for($i = 0; $i < $length; $i++)
    {
    $spdata = array(
      'ssid'      => $result,
      'sid'       => $info['product'][$i],                      
      'quantity'  => $info['quantity'][$i],
      'sprice'    => $info['sprice'][$i],
      'tprice'    => $info['tprice'][$i],
      'regby'     => $_SESSION['uid']
            );

    $this->pm->insert_data('services_sproduct',$spdata);
    }
    //var_dump($smsresult); exit();
    if($info['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($info['accountNo']);
      
      $cdata = array(
        'current' => $cash->current+$info['pAmount'],
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
        'current' => $bank->current+$info['pAmount'],
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
        'current' => $mobile->current+$info['pAmount'],
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
      <h4><i class="icon fa fa-check"></i> Service Sale Successfully !</h4></div>'
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
  redirect('servlist');
}

public function view_sale_service($id)
  {
  $data['title'] = 'Sale Service';

  $where = array(
    'ssid' => $id
        );
  $other = array(
    'join' => 'left'
        );
  $field = array(
    'services_sale' => 'services_sale.*',
    'customers' => 'customers.*',
    'users' => 'users.name'
          );
  $join = array(
    'customers' => 'customers.custid = services_sale.custid',
    'users' => 'users.uid = services_sale.regby'
          );
  $prints = $this->pm->get_data('services_sale',$where,$field,$join,$other);
  $data['prints'] = $prints[0];

  $pfield = array(
    'services_sproduct' => 'services_sproduct.*',
    'services' => 'services.sCode,services.sName,services.sDetails'
        );
  $pjoin = array(
    'services' => 'services.sid = services_sproduct.sid'
        );

  $data['salesp'] = $this->pm->get_data('services_sproduct',$where,$pfield,$pjoin,$other);
  $data['spay'] = $this->pm->get_service_payment_data($id);
  $data['company'] = $this->pm->company_details();
    
  $this->load->view('sale/view_service',$data);
}

public function edit_sale_service($id)
  {
  $data['title'] = 'Sale Service';

  $where = array(
    'ssid' => $id
        );
  $prints = $this->pm->get_data('services_sale',$where);
  $data['sale'] = $prints[0];
  
  $other = array(
    'join' => 'left'
        );
  $pfield = array(
    'services_sproduct' => 'services_sproduct.*',
    'services' => 'services.sCode,services.sName,services.sDetails'
        );
  $pjoin = array(
    'services' => 'services.sid = services_sproduct.sid'
        );

  $data['salesp'] = $this->pm->get_data('services_sproduct',$where,$pfield,$pjoin,$other);
    
  $this->load->view('sale/edit_service',$data);
}

public function update_sale_service()
  {
  $info = $this->input->post();
    
  $sale = array(
    'ssDate'      => date('Y-m-d', strtotime($info['date'])),
    'custid'      => $info['customer'],
    'tAmount'     => $info['tAmount'],
    'disAmount'   => $info['discount'], 
    'vAmount'     => $info['salevat'], 
    'pAmount'     => $info['pAmount'],
    'dAmount'     => $info['dAmount'],
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'note'        => $info['note'],          
    'upby'        => $_SESSION['uid']
        );

  $where = array(
    'ssid' => $info['ssid']
        );
    //var_dump($sale); exit();
  $ppurchase = $this->pm->get_data('services_sale',$where);
  $result = $this->pm->update_data('services_sale',$sale,$where);
  $this->pm->delete_data('services_sproduct',$where);
  
  $length = count($info['product']);

  for($i = 0; $i < $length; $i++)
    {
    $spdata = array(
      'ssid'      => $info['ssid'],
      'sid'       => $info['product'][$i],                     
      'quantity'  => $info['quantity'][$i],
      'sprice'    => $info['sprice'][$i],
      'tprice'    => $info['tprice'][$i],
      'regby'     => $_SESSION['uid']
            );

    $this->pm->insert_data('services_sproduct',$spdata);
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
        'current' => $cash->current+$info['pAmount'],
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
        'current' => $bank->current+$info['pAmount'],
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
        'current' => $mobile->current+$info['pAmount'],
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
      <h4><i class="icon fa fa-check"></i> Sale Service update Successfully !</h4></div>'
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
  redirect('servlist');
}

public function delete_sales_service($id)
  {
  $where = array(
    'ssid' => $id
        );
    //var_dump($sale); exit();
  $ppurchase = $this->pm->get_data('services_sale',$where);
  $result = $this->pm->delete_data('services_sale',$where);
  $result2 = $this->pm->delete_data('services_sproduct',$where);

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
      <h4><i class="icon fa fa-check"></i> Sale Service delete Successfully !</h4></div>'
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
  redirect('servlist');
}

public function get_service_payment()
  {
  $section = $this->pm->get_service_payment($_POST['id']);
  $data = json_encode($section);
  echo $data;
}

public function save_service_payment()
  {
  $info = $this->input->post();
    
  $sale = [
    'ssid'        => $info['ssid'],
    'tAmount'     => $info['tAmount'],
    'pAmount'     => $info['pAmount'],
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'notes'       => $info['notes'],            
    'regby'       => $_SESSION['uid']
        ];
    //var_dump($sale); exit();
  $result = $this->pm->insert_data('service_payment',$sale);
    
    if($info['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($info['accountNo']);
      
      $cdata = array(
        'current' => $cash->current+$info['pAmount'],
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
        'current' => $bank->current+$info['pAmount'],
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
        'current' => $mobile->current+$info['pAmount'],
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
      <h4><i class="icon fa fa-check"></i> Service Payment add Successfully !</h4></div>'
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
  redirect('servlist');
}

public function service_sale_reports()
  {
  $data['title'] = 'Service Sales Report';

  $data['customer'] = $this->pm->get_data('customers',false);
  $data['company'] = $this->pm->company_details();

  if(isset($_GET['search']))
    {
    $report = $_GET['reports'];
    
    if($report == 'dailyReports')
      {
      $sdate = $_GET['sdate'];
      $edate = $_GET['edate'];
      $custid = $_GET['dcustomer'];
      $data['sdate'] = $sdate;
      $data['edate'] = $edate;
      $data['report'] = $report;
            //var_dump($employee); exit();
      $data['sales'] = $this->pm->get_dsale_service_data($sdate,$edate,$custid);
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
      $custid = $_GET['mcustomer'];
      $data['name'] = $name;
      $data['report'] = $report;

      $data['sales'] = $this->pm->get_msale_service_data($month,$year,$custid);
      }
    else if($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $custid = $_GET['ycustomer'];
      $data['year'] = $year;
      $data['report'] = $report;

      $data['sales'] = $this->pm->get_ysale_service_data($year,$custid);
      }
    }
  else
    {
    $data['sales'] = $this->pm->get_sale_service_data();
    }

  $this->load->view('sale/service_reports',$data);
}


public function courier_sales_reports()
  {
  $data['title'] = 'Sales Report';

  $data['courier'] = $this->pm->get_data('courier_account',false);

  if(isset($_GET['search']))
    {
    $report = $_GET['reports'];
    
    if($report == 'dailyReports')
      {
      $sdate = date("Y-m-d", strtotime($_GET['sdate']));
      $edate = date("Y-m-d", strtotime($_GET['edate']));
      $caid = $_GET['dcourier'];
      $data['sdate'] = $sdate;
      $data['edate'] = $edate;
      $data['report'] = $report;
            //var_dump($employee); exit();
      $data['sales'] = $this->pm->get_courier_dsales_data($sdate,$edate,$caid);
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
      $caid = $_GET['mcourier'];
      $data['name'] = $name;
      $data['report'] = $report;

      $data['sales'] = $this->pm->get_courier_dsales_data($month,$year,$caid);
      }
    else if ($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $caid = $_GET['mcourier'];
      $data['year'] = $year;
      $data['report'] = $report;

      $data['sales'] = $this->pm->get_courier_ysales_data($year,$caid);
      }
    }
  else
    {
    $data['sales'] = $this->pm->get_courier_sales_data();
    }

  $this->load->view('sale/courier_report',$data);
}




########### Service Sales end ##################################
########### Pos Sales start ##################################

public function pos_sales_info()
  {
  $where = array(
    'catid >' => 1
        );
  $data['title'] = 'Pos Sales';
  
  $data['company'] = $this->pm->company_details();
  $data['product'] = $this->pm->get_data('products',$where);
  $data['customer'] = $this->pm->get_data('customers',false);
  
  $this->load->view('sale/pos_sales',$data);
}

public function get_pos_sale_details()
  {
  $id = $this->input->post('id');
  //$id = "P-SER00001";
 
  $where = array(
    'pCode' => $id
        );

  $products = $this->pm->get_data('products',$where);
  $str = '';
  
  foreach($products as $value)
    {
    $id = $value['pid'];
    $str.="<tr>
      <td>".$value['pName']."<input type='hidden' name='productID[]' value='".$value['pid']."' required ></td>
      <td><input type='text' class='form-control' onkeyup='getTotal(".$id.")' name='pices[]' id='quantity_".$value['pid']."' value='1' min='1' required ></td>
      <td><input type='text' class='form-control' onkeyup='getTotal(".$id.")' name='salePrice[]' id='tp_".$value['pid']."' value='".$value['sprice']."' required ></td>
      <td><input type='text' class='form-control totalPrice'  name='tPrice[]' id='totalPrice_".$value['pid']."' value='".$value['sprice']."' required readonly ></td>
      <td><span class='item_remove btn btn-danger btn-xs' onclick='deleteProduct(this)' >X</span></td>
      </tr>";
    }
  //var_dump($str); exit();
  echo json_encode($str);
}

public function save_pos_sale()
  {
  $info = $this->input->post();
  
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

  $sale = array(
    'compid'      => $_SESSION['compid'],
    'invoice'     => $cusid,
    'saDate'      => date('Y-m-d'),
    'custid'      => $info['customer'],
    'tAmount'     => $info['nAmount'],
    'pAmount'     => $info['pAmount'],
    'dAmount'     => $info['dAmount'],
    'discount'    => $info['discount'], 
    'disType'     => $info['disType'], 
    'disAmount'   => $info['disAmount'], 
    'vCost'       => $info['vCost'],
    'vType'       => $info['vType'],
    'vat'         => $info['vAmount'],
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'note'        => $info['note'],
    'regby'       => $_SESSION['uid']
        );
        //var_dump($sale); exit();
  $result = $this->pm->insert_data('sales',$sale);
       
  $length = count($info['productID']);

  for($i = 0; $i < $length; $i++)
    {
    $spdata = array(
      'said'      => $result,
      'pid'       => $info['productID'][$i], 
      'quantity'  => $info['pices'][$i],
      'sprice'    => $info['salePrice'][$i],
      'tprice'    => $info['tPrice'][$i],
      'regby'     => $_SESSION['uid']
            );

    $this->pm->insert_data('sale_product',$spdata);

    $swhere = array(
      'pid' => $info['productID'][$i]
              );

    $stpd = $this->pm->get_data('stock',$swhere);
    $this->pm->delete_data('stock',$swhere);

    if($stpd)
      {
      $tqnt = $stpd[0]['tquantity']-$info['pices'][$i];
      $dtqnt = $stpd[0]['dtquantity'];
      }
    else
      {
      $tqnt = '-'.$info['pices'][$i];
      $dtqnt = 0;
      }

    $stock = array(
      'compid'     => $_SESSION['compid'],
      'pid'        => $info['productID'][$i],
      'tquantity'  => $tqnt,
      'dtquantity' => $dtqnt,
      'regby'      => $_SESSION['uid']
              );
        //var_dump($stock_info);    
    $this->pm->insert_data('stock',$stock);  
    }
    //var_dump($smsresult); exit();
    if($info['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($info['accountNo']);
      
      $cdata = array(
        'current' => $cash->current+$info['pAmount'],
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
        'current' => $bank->current+$info['pAmount'],
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
        'current' => $mobile->current+$info['pAmount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $info['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }
      
  if($result)
    {
    redirect('printPSale/'.$result);
    }
  else
    {
    $sdata = [
      'exception' => '<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-ban"></i> something is error !</h4>
        </div>'
            ];
    
    $this->session->set_userdata($sdata);
    redirect('posSales');
    }
}

public function pos_sales_details($sid)
  {
  $pid = $sid;
  $data = [
    'title'   => 'Sales',
    'company' => $this->pm->company_details(),
    'sales'   => $this->pm->get_salesdata($pid),
    'salesp'  => $this->pm->get_sales_products_data($pid)
      ];
  
  $this->load->view('sale/view_pos_sales',$data);
}
########### Pos Sales End   ##################################





}