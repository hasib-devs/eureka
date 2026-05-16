<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');

class Product extends CI_Controller {

public function __construct(){
  parent::__construct();       
  $this->load->model("prime_model",'pm');            
  $this->checkPermission();   
  $this->load->library('PHPExcel');
  $this->load->library('excel');
  $this->load->library('zend');
  $this->zend->load('Zend/Barcode'); 
}

public function index()
  {
  $data['title'] = 'Product Information'; 
  
  $total_records = $this->pm->count_all('products');

  $limit = 10;
  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  $offset = ($page - 1) * $limit;
  $total_pages = ceil($total_records / $limit);
  
  $other = array(
    'order_by' => 'pid',
    'join'     => 'left',
    //'limit'    => $limit,
    //'offset'   => $offset
          );
  $field = array(
    'products'   => 'products.*',
    'categories' => 'categories.catName',
    'suppliers'  => 'suppliers.supName'
          );
  $join = array(
    'categories' => 'categories.catid = products.catid',
    'suppliers'  => 'suppliers.supid = products.supid'
          );

  $data['product'] = $this->pm->get_data('products',false,$field,$join,$other);
  
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

  $where = array(
    'status' => 'Active' 
        );

  $data['category'] = $this->pm->get_data('categories',$where);
  $data['unit'] = $this->pm->get_data('sma_units',$where);
  $data['supplier'] = $this->pm->get_data('suppliers',$where);
  
  $this->load->view('products/product',$data);
}

public function new_products()
  {
  $data['title'] = 'Products';

  $where = array(
    'status' => 'Active' 
        );

  $data['category'] = $this->pm->get_data('categories',$where);
  $data['unit'] = $this->pm->get_data('sma_units',$where);
  $data['supplier'] = $this->pm->get_data('suppliers',$where);

  $this->load->view('products/new_product',$data);
}

public function save_product()
  {
  $info = $this->input->post();
    //var_dump('hello'); exit();
  $config['upload_path'] = './upload/product/';
  $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|JPG|PNG';
  $config['max_size'] = 0;
  $config['max_width'] = 0;
  $config['max_height'] = 0;

  $this->load->library('upload', $config);
  $this->upload->initialize($config);
  
  if($this->upload->do_upload('userfile'))
    {
    $img = $this->upload->data('file_name');
    }
  else
    {
    $img = '';
    }
    //var_dump($img); exit();

  $data = [
    'compid'      => $_SESSION['compid'],
    'pCode'       => $info['pCode'],
    'partNo'       => $info['partNo'],
    
    'brand'       => $info['brand'],
    'model'       => $info['model'],
    'pName'       => $info['pName'],
    'pChassis'    => $info['pChassis'],
    'pEngine'     => $info['pEngine'],
    'catid'       => $info['category'],
    'untid'       => $info['unit'],
    // 'supid'       => $info['supplier'],
    'pprice'      => $info['pprice'],
    'sprice'      => $info['sprice'],
    'pColor'      => $info['pColor'],
    'warranty'    => $info['warranty'],
    'manufacture' => $info['manufacture'],
    //'power'       => $info['power'],
    
    'rpm'         => $info['rpm'],
    'hsn'         => $info['hsn'],
    
    'capacity'    => $info['capacity'],
    'wBase'       => $info['wBase'],
    'uWeight'     => $info['uWeight'],
    'sTire'       => $info['sTire'],
    // 'bLabel'      => $info['bLabel'],
    'nCylinder'   => $info['nCylinder'],
    'fUsed'       => $info['fUsed'],
    'tCapacity'   => $info['tCapacity'],
    'seats'       => $info['seats'],
    'prNumber'    => $info['prNumber'],
    'mkName'      => $info['mkName'],
    'mkCountry'   => $info['mkCountry'],
    'image'       => $img,
    'regby'       => $_SESSION['uid']
        ];
    //var_dump($productID); exit();
  $result = $this->pm->insert_data('products',$data);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Items add Successfully !</h4></div>'
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
  redirect('items');
}

public function view_product($id)
  {
  $data['title'] = 'Items'; 

  $where = array(
    'pid' => $id  
        );
  $other = array(
    'join' => 'left' 
        );
  $field = array(
    'products'  => 'products.*',
    'categories' => 'categories.catName',
    'sma_units'  => 'sma_units.unitName',
    'suppliers'  => 'suppliers.supName'
        );
  $join = array(
    'categories' => 'categories.catid = products.catid',
    'sma_units'  => 'sma_units.untid = products.untid',
    'suppliers'  => 'suppliers.supid = products.supid'
        );

  $product = $this->pm->get_data('products',$where,$field,$join,$other);
  $data['product'] = $product[0];

  $this->load->view('products/productView',$data);
}

public function edit_products($id)
  {
  $data['title'] = 'Product';

  $where = array(
    'status' => 'Active'
        );
  $data['category'] = $this->pm->get_data('categories',$where);
  $data['unit'] = $this->pm->get_data('sma_units',$where);
  $data['supplier'] = $this->pm->get_data('suppliers',$where);

  $pwhere = array(
    'pid' => $id
        );

  $product = $this->pm->get_data('products',$pwhere);
  $data['product'] = $product[0];
    //var_dump($data['unit']);
  $this->load->view('products/edit_product',$data);
}

public function update_product()
  {
  $info = $this->input->post();
  $pid = $info['pid'];
    //var_dump($pid); exit();
  $config['upload_path'] = './upload/product/';
  $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|JPG|PNG';
  $config['max_size'] = 0;
  $config['max_width'] = 0;
  $config['max_height'] = 0;

  $this->load->library('upload',$config);
  $this->upload->initialize($config);
  
  if($this->upload->do_upload('userfile'))
    {
    $img = $this->upload->data('file_name');
    }
  else
    {
    $pimg = $this->db->select('image')->from('products')->where('pid',$pid)->get()->row();
    if($pimg)
      {
      $img = $pimg->image;
      }
    else
      {
      $img = '';
      }
      }  

  $data = [
    'pCode'       => $info['pCode'],
    'partNo'       => $info['partNo'],
    'brand'       => $info['brand'],
    'model'       => $info['model'],
    'pName'       => $info['pName'],
    'pChassis'    => $info['pChassis'],
    'pEngine'     => $info['pEngine'],
    'catid'       => $info['category'],
    'untid'       => $info['unit'],
    // 'supid'       => $info['supplier'],
    'pprice'      => $info['pprice'],
    'sprice'      => $info['sprice'],
    'pColor'      => $info['pColor'],
    'warranty'    => $info['warranty'],
    'manufacture' => $info['manufacture'],
    //'power'       => $info['power'],
    'rpm'         => $info['rpm'],
    'hsn'         => $info['hsn'],
    'capacity'    => $info['capacity'],
    'wBase'       => $info['wBase'],
    'uWeight'     => $info['uWeight'],
    'sTire'       => $info['sTire'],
    // 'bLabel'      => $info['bLabel'],
    'nCylinder'   => $info['nCylinder'],
    'fUsed'       => $info['fUsed'],
    'tCapacity'   => $info['tCapacity'],
    'seats'       => $info['seats'],
    'prNumber'    => $info['prNumber'],
    'mkName'      => $info['mkName'],
    'mkCountry'   => $info['mkCountry'],
    'image'       => $img,
    'upby'        => $_SESSION['uid']
            ];
    //var_dump($info); exit();
  $where = array(
    'pid' => $pid
        );
    //var_dump($where); exit();
  $result = $this->pm->update_data('products',$data,$where);
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Items update Successfully !</h4></div>'
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
  redirect('items');
}

public function delete_products($pid)
  {
  $where = array(
    'pid' => $pid
        );
    //var_dump($where); exit();
  $result = $this->pm->delete_data('products',$where);
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-check"></i> items delete Successfully !</h4></div>'
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
  redirect('items');
}

public function save_product_store()
  {
  $info = $this->input->post();
  
  $data = [
    'compid'    => $_SESSION['compid'],
    'pid'       => $info['product'],
    'quantity'  => $info['quantity'],
    'regby'     => $_SESSION['uid']
        ];
    //var_dump($productID); exit();
  $this->pm->insert_data('store_product',$data);
  
  $swhere = array(
    'pid' => $info['product']
            );

  $stpd = $this->pm->get_data('stock',$swhere);
  $this->pm->delete_data('stock',$swhere);

  if($stpd)
    {
    $tqnt = $stpd[0]['tquantity']+$info['quantity'];
    $dtqnt = $stpd[0]['dtquantity'];
    }
  else
    {
    $tqnt = $info['quantity'];
    $dtqnt = 0;
    }

  $stock = [
    'compid'     => $_SESSION['compid'],
    'pid'        => $info['product'],
    'tquantity'  => $tqnt,
    'dtquantity' => $dtqnt,
    'regby'      => $_SESSION['uid']
        ];
    //var_dump($productID); exit();
  $result = $this->pm->insert_data('stock',$stock);

 

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> items Store Successfully !</h4></div>'
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
  redirect('items');
}

public function product_barcode($id)
  {
  $data['title'] = 'Product';

  if(isset($_GET['search']))
    {
    $nopack = $_GET['nopack'];
    $data['nopack'] = $nopack;
    $data['product'] = $id;

    $where = array(
      'pid' => $id
          );

    $data['product'] = $this->pm->get_data('products',$where);
    }
  else
    {
    $where = array(
      'pid' => $id
          );

    $data['product'] = $this->pm->get_data('products',$where);
    }
    //var_dump($data['products']); exit();
  $this->load->view('products/product_barcode',$data);
}

public function product_reports()
  {
  $data['title'] = 'Stock Report';
  
  $data['category'] = $this->pm->get_data('categories',false);
  

  if(isset($_GET['search']))
    {
    $report = $_GET['reports'];
    
    if($report == 'dailyReports')
      {
      $sdate = date("Y-m-d", strtotime($_GET['sdate']));
      $edate = date("Y-m-d", strtotime($_GET['edate']));
      $catid = $_GET['dcategory'];
      $data['sdate'] = $sdate;
      $data['edate'] = $edate;
      $data['report'] = $report;
      $data['catid'] = $catid;
      //var_dump($catid); exit();
      if($catid == 'All')
        {
        $data['product'] = $this->pm->get_data('products',false);
        //$data['product'] = $this->pm->get_product_stock_data();
        }
      else
        {
        $where = array(
          'catid' => $catid
                );
        
        $data['product'] = $this->pm->get_data('products',$where);
        }
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
      $catid = $_GET['mcategory'];
      $data['name'] = $name;
      $data['report'] = $report;
      $data['catid'] = $catid;
      
      if($catid == 'All')
        {
        $data['product'] = $this->pm->get_data('products',false);
        //$data['product'] = $this->pm->get_product_stock_data();
        }
      else
        {
        $where = array(
          'catid' => $catid
                );
        
        $data['product'] = $this->pm->get_data('products',$where);
        }
      }
    else if ($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $catid = $_GET['ycategory'];
      $data['year'] = $year;
      $data['report'] = $report;
      $data['catid'] = $catid;
      //var_dump($data['month']);  exit();
      if($catid == 'All')
        {
        $data['product'] = $this->pm->get_data('products',false);
        //$data['product'] = $this->pm->get_product_stock_data();
        }
      else
        {
        $where = array(
          'catid' => $catid
                );
        
        $data['product'] = $this->pm->get_data('products',$where);
        }
      }
    }
  else
    {
    $data['product'] = $this->pm->get_data('products',false);
    //$data['product'] = $this->pm->get_product_stock_data();
    }
    //var_dump($data['products']); exit();
  $this->load->view('products/product_report',$data);
}

public function service_list()
  {
  $data['title'] = 'Service'; 

  $other = array(
    'order_by' => 'sid'
          );

  $data['services'] = $this->pm->get_data('services',false,false,false,$other);
  
  $this->load->view('products/services',$data);
}

public function save_service()
  {
  $info = $this->input->post();
    //var_dump('hello'); exit();
    
  $query = $this->db->select('sid')
                  ->from('services')
                  ->limit(1)
                  ->order_by('sid','DESC')
                  ->get()
                  ->row();
  if($query)
    {
    $sn = $query->sid+1;
    }
  else
    {
    $sn = 1;
    }

  $cn = strtoupper(substr($_SESSION['compname'],0,3));
  $pc = sprintf("%'05d",$sn);

  $cusid = 'S'.$cn.$pc;
  $data = [
    'sCode'    => $cusid,
    'sName'    => $info['sName'],
    'sDetails' => $info['sDetails'],
    'sprice'   => $info['sprice'],
    'regby'    => $_SESSION['uid']
        ];
    //var_dump($productID); exit();
  $result = $this->pm->insert_data('services',$data);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Service add Successfully !</h4></div>'
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
  redirect('service');
}

public function get_service_data()
  {
  $section = $this->pm->get_service_data($_POST['id']);
  $someJSON = json_encode($section);
  echo $someJSON;
}

public function update_service()
  {
  $info = $this->input->post();

  $data = [
    'sName'    => $info['sName'],
    'sDetails' => $info['sDetails'],
    'sprice'   => $info['sprice'],
    'status'   => $info['status'],
    'upby'     => $_SESSION['uid']
            ];
    //var_dump($info); exit();
  $where = array(
    'sid' => $info['sid']
        );
    //var_dump($where); exit();
  $result = $this->pm->update_data('services',$data,$where);
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> service update Successfully !</h4></div>'
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
  redirect('service');
}

public function delete_service($id)
  {
  $where = array(
    'sid' => $id
        );
    //var_dump($where); exit();
  $result = $this->pm->delete_data('services',$where);
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Services delete Successfully !</h4></div>'
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
  redirect('service');
}

public function product_ledger()
  {
  $data['title'] = 'Stock Ledger';
  
  $data['product'] = $this->pm->get_data('products',false);
  $data['company'] = $this->pm->company_details();
  
  if(isset($_GET['search']))
    {
    $report = $_GET['reports'];
    
    if($report == 'dailyReports')
      {
      $sdate = date("Y-m-d", strtotime($_GET['sdate']));
      $edate = date("Y-m-d", strtotime($_GET['edate']));
      $pid = $_GET['dproduct'];
      $data['sdate'] = $sdate;
      $data['edate'] = $edate;
      $data['report'] = $report;
      //var_dump($catid); exit();
      $cwhere = array('pid' => $pid);

      $data['spdetails'] = $this->pm->get_data('products',$cwhere);
      $data['pproduct'] = $this->pm->get_purchase_dproduct_ledger_data($pid,$sdate,$edate);
      $data['sproduct'] = $this->pm->get_sale_dproduct_ledger_data($pid,$sdate,$edate);
      $data['prproduct'] = $this->pm->get_preturn_dproduct_ledger_data($pid,$sdate,$edate);
      $data['srproduct'] = $this->pm->get_sreturn_dproduct_ledger_data($pid,$sdate,$edate);
      $data['mrproduct'] = $this->pm->get_rmanufacture_dproduct_ledger_data($pid,$sdate,$edate);
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
      $pid = $_GET['mproduct'];
      $data['name'] = $name;
      $data['report'] = $report;
      
      $cwhere = array('pid' => $pid);

      $data['spdetails'] = $this->pm->get_data('products',$cwhere);
      $data['pproduct'] = $this->pm->get_purchase_mproduct_ledger_data($pid,$month,$year);
      $data['sproduct'] = $this->pm->get_sale_mproduct_ledger_data($pid,$month,$year);
      $data['prproduct'] = $this->pm->get_preturn_mproduct_ledger_data($pid,$month,$year);
      $data['srproduct'] = $this->pm->get_sreturn_mproduct_ledger_data($pid,$month,$year);
      $data['mrproduct'] = $this->pm->get_rmanufacture_mproduct_ledger_data($pid,$month,$year);
      }
    else if ($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $pid = $_GET['yproduct'];
      $data['year'] = $year;
      $data['report'] = $report;
      //var_dump($data['month']);  exit();
      $cwhere = array('pid' => $pid);

      $data['spdetails'] = $this->pm->get_data('products',$cwhere);
      $data['pproduct'] = $this->pm->get_purchase_yproduct_ledger_data($pid,$year);
      $data['sproduct'] = $this->pm->get_sale_yproduct_ledger_data($pid,$year);
      $data['prproduct'] = $this->pm->get_preturn_yproduct_ledger_data($pid,$year);
      $data['srproduct'] = $this->pm->get_sreturn_yproduct_ledger_data($pid,$year);
      $data['mrproduct'] = $this->pm->get_rmanufacture_yproduct_ledger_data($pid,$year);
      }
    else if($report == 'ocust')
      {
      $pid = $_GET['aproduct'];
      $data['report'] = $report;
      
      $cwhere = array('pid' => $pid);

      $data['spdetails'] = $this->pm->get_data('products',$cwhere);
      $data['pproduct'] = $this->pm->get_purchase_product_ledger_data($pid);
      $data['sproduct'] = $this->pm->get_sale_product_ledger_data($pid);
      $data['prproduct'] = $this->pm->get_preturn_product_ledger_data($pid);
      $data['srproduct'] = $this->pm->get_sreturn_product_ledger_data($pid);
      $data['mrproduct'] = $this->pm->get_rmanufacture_product_ledger_data($pid);
      }
    }
  else
    {
    
    }
    //var_dump($data['products']); exit();
  $this->load->view('products/product_ledger',$data);
}








public function stock_product_export()
    {
    $this->load->library("excel");
    $object = new PHPExcel();

    $object->setActiveSheetIndex(0);

    $table_columns = array("Product Name","Product Code","Category","Units","Purchase Price","Sale Price","In Quantity","Out Quantity","Stock","Damage Stock","Stock Sale Price","Stock Purchase Price");

    $column = 0;

    foreach($table_columns as $field)
        {
        $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
        $column++;
        }

    $product_data = $this->pm->get_product_stock_data();
    //print_r($product_data); exit();
    $excel_row = 2;

    foreach($product_data as $row)
        {
        $cat = $this->db->select("categoryName")->from('categories')->where('categoryID',$row->categoryID)->get()->row();
        $unt = $this->db->select("unitName")->from('sma_units')->where('id',$row->unit)->get()->row();
        
        $pp = $this->db->select("SUM(purchase_product.quantity) as tpq,purchase.compid")
                    ->from('purchase_product')
                    ->join('purchase','purchase.purchaseID = purchase_product.purchaseID','left')
                    ->where('productID',$row->product)
                    ->group_by('purchase_product.purchaseID')
                    ->get()
                    ->row();

        $spp = $this->db->select("SUM(sale_product.quantity) as tsq,sales.compid")
                    ->from('sale_product')
                    ->join('sales','sales.saleID = sale_product.saleID','left')
                    ->where('productID',$row->product)
                    ->group_by('sale_product.saleID')
                    ->get()
                    ->row();
      
        $rp = $this->db->select("SUM(returns_product.quantity) as trq,returns.compid")
                    ->from('returns_product')
                    ->join('returns','returns.returnId = returns_product.rt_id','left')
                    ->where('productID',$row->product)
                    ->group_by('returns_product.rt_id')
                    ->get()
                    ->row();
      
        $rpp = $this->db->select("SUM(quantity) as trq")
                    ->from('preturns_product')
                    ->where('product',$row->product)
                    ->get()
                    ->row();
        if($pp){ $tpq = $pp->tpq; } else{ $tpq = 0; }
        if($rpp){ $trq = $rpp->trq; } else{ $trq = 0; }
        if($spp){ $tsq = $spp->tsq; } else{ $tsq = 0; }
        if($rp){ $trpq = $rp->trq; } else{ $trpq = 0; }
        
        $tiq = $tpq-$trq;
        $toq = $tsq-$trpq;
        $ssa = $row->totalPices*$row->sprice;
        $spa = $row->totalPices*$row->pprice;
                          
        $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->productName);
        $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->productcode);
        $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $cat->categoryName);
        $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $unt->unitName);
        $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->pprice);
        $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->sprice);
        $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $tiq);
        $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $toq);
        $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->totalPices);
        $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row->dtquantity);
        $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $ssa);
        $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $spa);
        $excel_row++;
        }

    //$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
    $object->getActiveSheet()->setTitle('Products');

    //Save ke .xlsx, kalau ingin .xls, ubah 'Excel2007' menjadi 'Excel5'
    $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');

    header("Last-Modified: ".gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    // header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Products Stock.xls"');
    ob_end_clean();
    $object_writer->save('php://output');
}

public function product_export()
  {
  $this->load->library("excel");
  $object = new PHPExcel();

  $object->setActiveSheetIndex(0);

  $table_columns = array("Product Name", "Brand", "Model Number", "Category", "Product Code", "Part No ", "Origin", "CC", "Colour", "Chassis No.", "Engine No.", "Unit", "Purchase Price", "Sale Price", "Warranty", "Year of Manufacture ", "Size", "HS Code", "Wheel Base", "Weight", "Size of Tire", "Number of Cylinders", "Fuel Used", "Fuel Tank Capacity", "Seats", "Pre. Regn. No.", "Maker's Name");

  $column = 0;

  foreach($table_columns as $field)
    {
    $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
    $column++;
    }

  $product_data = $this->pm->product_fetch_data();
    //print_r($product_data); exit();
  $excel_row = 2;

  foreach($product_data as $row)
    {
    $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->pName);
    $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->pCode);
    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->pColor);
    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->mkCountry);
    $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->catid);
    $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->untid);
    $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->pprice);
    $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->sprice);
    $excel_row++;
    }

    //$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
  $object->getActiveSheet()->setTitle('Products');

    //Save ke .xlsx, kalau ingin .xls, ubah 'Excel2007' menjadi 'Excel5'
  $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');

  header("Last-Modified: ".gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    // header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="Products Data.xls"');
  if (ob_get_length()) ob_end_clean();
  $object_writer->save('php://output');
}

public function excel_import()
  {
  if(isset($_FILES["file"]["name"]))
    {
    $path = $_FILES["file"]["tmp_name"];
    $object = PHPExcel_IOFactory::load($path);
    foreach($object->getWorksheetIterator() as $worksheet)
      {
      $highestRow = $worksheet->getHighestRow();
      $highestColumn = $worksheet->getHighestColumn();
      for($row = 2; $row<=$highestRow; $row++)
        {
        $pName = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
        $brand = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
        $model = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
        $category = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
        $pCode = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
        $partNo = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
        $mkCountry = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
        $capacity = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
        $pColor = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
        $pChassis = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
        $pEngine = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
        $unit = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
        $pprice = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
        $sprice = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
        $warranty = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
        $manufacture = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
        $rpm = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
        $hsn = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
        $wBase = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
        $uWeight = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
        $sTire = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
        $nCylinder = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
        $fUsed = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
        $tCapacity = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
        $seats = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
        $prNumber = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
        $mkName = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
        //var_dump($category);
        $catid = $this->db->select('catid')->from('categories')->where('trim(catName)', trim($category))->get()->row();
        //var_dump($catid); exit();
        $untid = $this->db->select('untid')->from('sma_units')->where('trim(unitName)', trim($unit))->get()->row();
        
        if($catid)
          {
          $cat2id = $catid->catid;
          }
        else
          {
          $catdata = array(
            'compid'  => $_SESSION['compid'],
            'catName' => $category,          
            'regby'   => $_SESSION['uid']
                );
        
          $cat2id = $this->pm->insert_data('categories',$catdata);
          }
        
        if($untid)
          {
          $unt2id = $untid->untid;
          }
        else
          {
          $unt2id = 0;
        //   $untdata = array(
        //     'compid'   => $_SESSION['compid'],
        //     'unitName' => $unit,         
        //     'regby'    => $_SESSION['uid']
        //         );
        
        //   $unt2id = $this->pm->insert_data('sma_units',$untdata);
          }
        
        $query = $this->db->select('	pid')
                      ->from('products')
                      ->limit(1)
                      ->order_by('	pid','DESC')
                      ->get()
                      ->row();
        if($query)
            {
            $sn = $query->	pid+1;
            }
        else
            {
            $sn = 1;
            }

        $cn = strtoupper(substr($_SESSION['compname'],0,3));
        $pc = sprintf("%'05d",$sn);
    
        $cusid = 'P'.$cn.$pc;
    
        $pdata = array(
          'compid'      => $_SESSION['compid'],
          'pName'       => $pName,
          'brand'       => $brand,
          'model'       => $model,
          'catid'       => $cat2id,
          'pCode'       => $cusid,
          'partNo'      => $partNo,
          'mkCountry'   => $mkCountry,
          'capacity'    => $capacity,
          'pColor'      => $pColor,
          'pChassis'    => $pChassis,
          'pEngine'     => $pEngine,
          'untid'       => $untid,
          'pprice'      => $pprice,
          'sprice'      => $sprice,
          'warranty'    => $warranty,
          'manufacture' => $manufacture,
          'rpm'         => $rpm,
          'hsn'         => $hsn,
          'wBase'       => $wBase,
          'uWeight'     => $uWeight,
          'sTire'       => $sTire,
          'nCylinder'   => $nCylinder,
          'fUsed'       => $fUsed,
          'tCapacity'   => $tCapacity,
          'seats'       => $seats,
          'prNumber'    => $prNumber,
          'mkName'      => $mkName,
          'regby'       => $_SESSION['uid']
                        );
        //var_dump($pdata); exit();
        $result = $this->pm->insert_data('products',$pdata);
        }
      }
            
    //$result = $this->pm->insert_product_data($data);
        
    if($result)
      {
      $sdata = [
        'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Product import Successfully !</h4>
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
    }   
  $this->session->set_userdata($sdata);
  redirect('items');
}



}