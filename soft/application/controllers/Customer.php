<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');

class Customer extends CI_Controller {

public function __construct()
  {
  parent::__construct();
  $this->load->model("prime_model","pm");
  $this->checkPermission();
  //$this->load->library('PHPExcel');
  //$this->load->library('excel');
}

public function index()
  {
  $data['title'] = 'Customer';
  
  $total_records = $this->pm->count_all('customers');

  $limit = 10;
  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  $offset = ($page - 1) * $limit;
  $total_pages = ceil($total_records / $limit);
  
  $other = array(
    'order_by' => 'custid',
    //'limit'    => $limit,
    //'offset'   => $offset
        );
  $data['customer'] = $this->pm->get_data('customers',false,false,false,$other);
  
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

  $this->load->view('customers/customer',$data);
}

public function new_customer()
  {
  $data['title'] = 'Customer';

  $data['customer'] = $this->pm->get_data('customers',false);
  $data['product'] = $this->pm->get_data('products',false);

  $this->load->view('customers/NewCustomer',$data);
}

public function save_customer()
  {
  $info = $this->input->post();
    
  $custMob = $this->db->select('custMobile')->from('customers')->where('custMobile',$info['custMobile'])->get()->row();
    
  if($custMob)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-ban"></i> This Customer Allready added !</h4>
        </div>'
            ];
    }
  else
    {
    $query = $this->db->select('custid')
                  ->from('customers')
                  ->limit(1)
                  ->order_by('custid','DESC')
                  ->get()
                  ->row();
    if($query)
      {
      $sn = $query->custid+1;
      }
    else
      {
      $sn = 1;
      }
    //var_dump($sn); exit();
    $cn = strtoupper(substr($_SESSION['compname'],0,3));
    $pc = sprintf("%'05d",$sn);

    $cusid = 'C-'.$cn.$pc;

    $config['upload_path'] = './upload/customer/';
    $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|JPG|PNG';
    $config['max_size'] = 0;
    $config['max_width'] = 0;
    $config['max_height'] = 0;

    $this->load->library('upload', $config);
    $this->upload->initialize($config);
    
    if($this->upload->do_upload('custNFiles'))
      {
      $nid = $this->upload->data('file_name');
      }
    else
      {
      $nid = '';
      }

    if($this->upload->do_upload('custDFiles'))
      {
      $driving = $this->upload->data('file_name');
      }
    else
      {
      $driving = '';
      }

    $data = array(
      'compid'       => $_SESSION['compid'],
      'custCode'     => $cusid,
      'custName'     => $info['custName'],
      'custfName'    => $info['custfName'],
      'custmName'    => $info['custmName'],
      'spouse'       => $info['spouse'],
      'custMobile'   => $info['custMobile'],
      'custEmail'    => $info['custEmail'],
      'custAddress'  => $info['custAddress'],
      'custpAddress' => $info['custpAddress'],
      'brtaDis'      => $info['brtaDis'],
      'custGender'   => $info['custGender'],
      'custDob'      => date('Y-m-d', strtotime($info['custDob'])),
      'custNation'   => $info['custNation'],
      'custNid'      => $info['custNid'],
      'custNFiles'   => $nid,
      'custDriving'  => $info['custDriving'],
      'custDFiles'   => $driving,
      'custBank'     => $info['custBank'],
      'custBNumber'  => $info['custBNumber'],
      'regby'        => $_SESSION['uid']
          );

    $result = $this->pm->insert_data('customers',$data);

    if($result)
      {
      $sdata = [
        'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Customer add Successfully !</h4></div>'
              ];  
      }
    else
      {
      $sdata = [
        'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-ban"></i> Failed !</h4></div>'
              ];
      }
    }
  $this->session->set_userdata($sdata);
  redirect('Customer');
}

public function add_customer()
  {
  $query = $this->db->select('custid')
              ->from('customers')
              //->where('compid',$_SESSION['compid'])
              ->limit(1)
              ->order_by('custid','DESC')
              ->get()
              ->row();
  if($query)
    {
    $sn = $query->custid+1;
    }
  else
    {
    $sn = 1;
    }

  $cn = strtoupper(substr($_SESSION['compname'],0,3));
  $pc = sprintf("%'05d",$sn);

  $cusid = 'C-'.$cn.$pc;

//   $config['upload_path'] = './upload/customer/';
//   $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|JPG|PNG';
//   $config['max_size'] = 0;
//   $config['max_width'] = 0;
//   $config['max_height'] = 0;

//   $this->load->library('upload', $config);
//   $this->upload->initialize($config);
    
//     if($this->upload->do_upload('custNFiles'))
//       {
//       $nid = $this->upload->data('file_name');
//       }
//     else
//       {
//       $nid = '';
//       }

//     if($this->upload->do_upload('custDFiles'))
//       {
//       $driving = $this->upload->data('file_name');
//       }
//     else
//       {
//       $driving = '';
//       }

  $custdata = array(
    'compid'       => $_SESSION['compid'],
    'custCode'     => $cusid,
    'custName'     => $_POST['custName'],
    'custfName'    => $_POST['custfName'],
    'custmName'    => $_POST['custmName'],
    'spouse'       => $_POST['spouse'],
    'custMobile'   => $_POST['custMobile'],
    'custEmail'    => $_POST['custEmail'],
    'custAddress'  => $_POST['custAddress'],
    'custpAddress' => $_POST['custpAddress'],
    //'brtaDis'      => $_POST['brtaDis'],
    'custGender'   => $_POST['custGender'],
    'custDob'      => date('Y-m-d', strtotime($_POST['custDob'])),
    'custNation'   => $_POST['custNation'],
    'custNid'      => $_POST['custNid'],
    //'custNFiles'   => $nid,
    'custDriving'  => $_POST['custDriving'],
    //'custDFiles'   => $driving,
    'regby'        => $_SESSION['uid']
            );

  $result = $this->pm->insert_data('customers',$custdata);

  if($result)
    {
    echo "Customer Added Successfully !";
    }
  else
    {
    echo "Customer Added Failed !";
    }
}

public function view_customer($id)
  {
  $data['title'] = 'Customer';

  $data['customer'] = $this->pm->get_customer_data($id);
  $data['company'] = $this->pm->company_details();

  $this->load->view('customers/view_customer',$data);
}

public function get_customer_data()
  {
  $section = $this->pm->get_customer_data($_POST['id']);
  $someJSON = json_encode($section);
  echo $someJSON;
}

public function update_customer()
  {
  $info = $this->input->post();

  $config['upload_path'] = './upload/customer/';
  $config['allowed_types'] = 'gif|jpg|png|jpeg|JPEG|JPG|PNG';
  $config['max_size'] = 0;
  $config['max_width'] = 0;
  $config['max_height'] = 0;

  $this->load->library('upload', $config);
  $this->upload->initialize($config);
  
  $customer = $this->pm->get_customer_data($info['custid']);

  if($this->upload->do_upload('custNFiles'))
    {
    $nid = $this->upload->data('file_name');
    }
  else
    {
    if($customer)
      {
      $nid = $customer->custNFiles;
      }
    else
      {
      $nid = '';
      }
    }

  if($this->upload->do_upload('custDFiles'))
    {
    $driving = $this->upload->data('file_name');
    }
  else
    {
    if($customer)
      {
      $driving = $customer->custDFiles;
      }
    else
      {
      $driving = '';
      }
    }

  $data = array(
    'custName'     => $info['custName'],
    'custfName'    => $info['custfName'],
    'custmName'    => $info['custmName'],
    'spouse'       => $info['spouse'],
    'custMobile'   => $info['custMobile'],
    'custEmail'    => $info['custEmail'],
    'custAddress'  => $info['custAddress'],
    'custpAddress' => $info['custpAddress'],
    'brtaDis'      => $info['brtaDis'],
    'custGender'   => $info['custGender'],
    'custDob'      => date('Y-m-d', strtotime($info['custDob'])),
    'custNation'   => $info['custNation'],
    'custNid'      => $info['custNid'],
    'custNFiles'   => $nid,
    'custDriving'  => $info['custDriving'],
    'custDFiles'   => $driving,
    'custBank'     => $info['custBank'],
    'custBNumber'  => $info['custBNumber'],
    'status'       => $info['status'],              
    'upby'         => $_SESSION['uid']
          );

  $where = array(
    'custid' => $info['custid']
        );

  $result = $this->pm->update_data('customers',$data,$where);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Customer update Successfully !</h4></div>'
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
  redirect('Customer');
}

public function delete_customer($id)
  {
  $where = array(
    'custid' => $id
        );
  $sales = $this->pm->get_data('sales',$where);

  if($sales)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-ban"></i> All ready sales on this customer !</h4></div>'
            ];
    }
  else
    {
    $result = $this->pm->delete_data('customers',$where);

    if($result)
      {
      $sdata = [
        'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Customer delete Successfully !</h4></div>'
              ];  
      }
    else
      {
      $sdata = [
        'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-ban"></i> Failed !</h4></div>'
              ];
      }
    }
  $this->session->set_userdata($sdata);
  redirect('Customer');
}

public function all_customer_reports()
  {
  $data['title'] = 'Customers Report';

  $data['customer'] = $this->pm->get_data('customers',false);

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
    }

  $this->load->view('customers/customerReport',$data);
}

public function customer_ledger_report()
  {
  $data['title'] = 'Customers Report';

  $data['customer'] = $this->pm->get_data('customers',false);
  $data['company'] = $this->pm->company_details();

  if(isset($_GET['search']))
    {
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
      $data['spay'] = $this->pm->sales_payment_dcust_ledger_data($custid,$sdate,$edate);
      $data['voucher'] = $this->pm->voucher_dcust_ledger_data($custid,$sdate,$edate);
      $data['return'] = $this->pm->return_dcust_ledger_data($custid,$sdate,$edate);
      $data['service'] = $this->pm->service_dcust_ledger_data($custid,$sdate,$edate);
      }
    else if ($report == 'monthlyReports')
      {
      $month = $_GET['month'];
      $data['month'] = $month;
      $year = $_GET['year'];
      $data['year'] = $year;
      $custid = $_GET['mcustomer'];
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

      $cwhere = array('custid' => $custid);

      $data['cust'] = $this->pm->get_data('customers',$cwhere);
      $data['sale'] = $this->pm->sales_mcust_ledger_data($custid,$month,$year);
      $data['spay'] = $this->pm->sales_payment_mcust_ledger_data($custid,$month,$year);
      $data['voucher'] = $this->pm->voucher_mcust_ledger_data($custid,$month,$year);
      $data['return'] = $this->pm->return_mcust_ledger_data($custid,$month,$year);
      $data['service'] = $this->pm->service_mcust_ledger_data($custid,$month,$year);
      }
    else if ($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $data['year'] = $year;
      $data['report'] = $report;
      $custid = $_GET['ycustomer'];

      $cwhere = array('custid' => $custid);

      $data['cust'] = $this->pm->get_data('customers',$cwhere);
      $data['sale'] = $this->pm->sales_ycust_ledger_data($custid,$year);
      $data['spay'] = $this->pm->sales_payment_ycust_ledger_data($custid,$year);
      $data['voucher'] = $this->pm->voucher_ycust_ledger_data($custid,$year);
      $data['return'] = $this->pm->return_ycust_ledger_data($custid,$year);
      $data['service'] = $this->pm->service_ycust_ledger_data($custid,$year);
      }
    else if ($report == 'ocust')
      {
      $data['report'] = $report;
      $custid = $_GET['customer'];

      $cwhere = array('custid' => $custid);

      $data['cust'] = $this->pm->get_data('customers',$cwhere);
      $data['sale'] = $this->pm->sales_cust_ledger_data($custid);
      $data['spay'] = $this->pm->sales_payment_cust_ledger_data($custid);
      $data['voucher'] = $this->pm->voucher_cust_ledger_data($custid);
      $data['return'] = $this->pm->return_cust_ledger_data($custid);
      $data['service'] = $this->pm->service_cust_ledger_data($custid);
      }
    }
  else
    {
    }
    
  $this->load->view('customers/customerLedger',$data);
}

public function export_action()
    {
    $this->load->library("excel");
    $object = new PHPExcel();

    $object->setActiveSheetIndex(0);

    $table_columns = array("Customer Name","Mobile","Address","Email");

    $column = 0;

    foreach($table_columns as $field)
        {
        $object->getActiveSheet()->setCellValueByColumnAndRow($column,1,$field);
        $column++;
        }

    $customer_data = $this->pm->customer_fetch_data($_SESSION['compid']);

    $excel_row = 2;

    foreach($customer_data as $row)
        {
        $object->getActiveSheet()->setCellValueByColumnAndRow(0,$excel_row,$row->customerName);
        $object->getActiveSheet()->setCellValueByColumnAndRow(1,$excel_row,$row->mobile);
        $object->getActiveSheet()->setCellValueByColumnAndRow(2,$excel_row,$row->address);
        $object->getActiveSheet()->setCellValueByColumnAndRow(3,$excel_row,$row->email);
        $excel_row++;
        }

    $object_writer = PHPExcel_IOFactory::createWriter($object,'Excel2007');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Customers Data.xls"');
    ob_end_clean();
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
            for($row=2; $row<=$highestRow; $row++)
                {
                $customerName = $worksheet->getCellByColumnAndRow(0,$row)->getValue();
                $mobile = $worksheet->getCellByColumnAndRow(1,$row)->getValue();
                $address = $worksheet->getCellByColumnAndRow(2,$row)->getValue();
                $email = $worksheet->getCellByColumnAndRow(3,$row)->getValue();
                
                
                $query = $this->db->select('customerID')
                              ->from('customers')
                              ->where('compid',$_SESSION['compid'])
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

                $cn = strtoupper(substr($_SESSION['compname'],0,3));
                $pc = sprintf("%'05d",$sn);

                $cusid = 'C-'.$cn.$pc;

                $data[] = array(
                    'compid'       => $_SESSION['compid'],
                    'cus_id'       => $cusid,
                    'customerName' => $customerName,
                    'mobile'       => $mobile,
                    'email'        => $email,
                    'address'      => $address,
                    'balance'      => 0,
                    'regby'        => $_SESSION['uid']
                        );
                }
            }
        $this->pm->insert_customer_data($data);
        echo 'Customer Data Imported successfully';
        }   
}




}