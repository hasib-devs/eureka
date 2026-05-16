<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');

class Supplier extends CI_Controller {

function __construct() {
  parent::__construct();
  $this->load->model("prime_model","pm");
  $this->checkPermission();
  //$this->load->library('PHPExcel');
  //$this->load->library('excel');
}

public function index()
  {
  $data['title'] = 'Supplier';
  $other = array(
      'order_by' => 'supid'
      );
  $data['supplier'] = $this->pm->get_data('suppliers',false,false,false,$other);

  $this->load->view('suppliers/suppliers',$data);
}

public function new_supplier()
  {
  $data['title'] = 'Supplier';

  $this->load->view('suppliers/new_supplier',$data);
}

public function save_supplier()
  {
  $info = $this->input->post();

  $query = $this->db->select('supid')
                ->from('suppliers')
                ->limit(1)
                ->order_by('supid','DESC')
                ->get()
                ->row();
  if($query)
    {
    $sn = $query->supid+1;
    }
  else
    {
    $sn = 1;
    }

  $cn = strtoupper(substr($_SESSION['compname'],0,3));
  $pc = sprintf("%'05d", $sn);

  $cusid = 'S-'.$cn.$pc;

  $data = array(
    'compid'     => $_SESSION['compid'],
    'supCode'    => $cusid,
    'supName'    => $info['supName'],
    // new field
    'iicN'    => $info['iicN'],
    'bingst'    => $info['bingst'],
    'pinN'    => $info['pinN'],
    'binN'    => $info['binN'],
    
    
    'supCompany' => $info['supCompany'],
    'supMobile'  => $info['supMobile'],
    'supEmail'   => $info['supEmail'],
    'supAddress' => $info['supAddress'],
    'balance'    => $info['balance'],
    'accountType'=> $info['accountType'],
    'accountNo'    => $info['accountNo'],
    'regby'      => $_SESSION['uid']
        );

  $result = $this->pm->insert_data('suppliers',$data);
  
  $bdata = array(
    'supid'      => $result,
    'supBank'    => $info['supBank'],
    'supBranch'  => $info['supBranch'],
    'supAName'   => $info['supAName'],
    'supANumber' => $info['supANumber'],
    'supRNumber' => $info['supRNumber'],
    'regby'      => $_SESSION['uid']
        );

  $this->pm->insert_data('sup_account',$bdata);
  
  if($info['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($info['accountNo']);
      
      $cdata = array(
        'current' => $cash->current-$info['balance'],
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
        'current' => $bank->current-$info['balance'],
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
        'current' => $mobile->current-$info['balance'],
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
      <h4><i class="icon fa fa-check"></i> Supplier add Successfully !</h4></div>'
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
  redirect('Supplier');
}

public function get_supplier_data()
  {
  $section = $this->pm->get_supplier_data($_POST['id']);
  $someJSON = json_encode($section);
  echo $someJSON;
}

public function update_supplier()
  {
  $info = $this->input->post();

  $data = array(
    'supName'    => $info['supName'],
    'supCompany' => $info['supCompany'],
    'supMobile'  => $info['supMobile'],
    'iicN'    => $info['iicN'],
    'bingst'    => $info['bingst'],
    'pinN'    => $info['pinN'],
    'binN'    => $info['binN'],
    'supEmail'   => $info['supEmail'],
    'supAddress' => $info['supAddress'],
    'balance'    => $info['balance'],
    'status'     => $info['status'],            
    'upby'       => $_SESSION['uid']
        );

  $where = array(
    'supid' => $info['supid']
          );

  $result = $this->pm->update_data('suppliers',$data,$where);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Supplier update Successfully !</h4></div>'
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
  redirect('Supplier');
}

public function delete_supplier($id)
  {
  $where = array(
    'supid' => $id
        );
  $purchase = $this->pm->delete_data('purchase',$where);

  if($purchase)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-ban"></i> All ready purchase from this supplier !</h4></div>'
            ];
    }
  else
    {
    $result = $this->pm->delete_data('suppliers',$where);

    if($result)
      {
      $sdata = [
        'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Supplier delete Successfully !</h4></div>'
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
  redirect('Supplier');
}



public function add_supplier()
  {
  $data = array(
    'compid'     => $_SESSION['compid'],
    'supName'    => $_POST['supName'],
    'supCompany' => $_POST['supCompany'],
    'supMobile'  => $_POST['supMobile'],
    'supEmail'   => $_POST['supEmail'],
    'supAddress' => $_POST['supAddress'],
    'balance'    => $_POST['balance'],            
    'regby'      => $_SESSION['uid']
            );

  $result = $this->pm->insert_data('suppliers',$data);

  if($result)
    {
    echo "Supplier Added Successfully !";
    }
  else
    {
    echo "Supplier Added Failed!";
    }
}



public function supplier_report()
  {
  $data['title'] = 'Supplier Reports';

  $data['supplier'] = $this->pm->get_data('suppliers',false);
  
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

  $this->load->view('suppliers/supplier_report',$data);
}

public function supplier_ledger()
  {
  $data = ['title' => 'Supplier Ledger'];

  $data['supplier'] = $this->pm->get_data('suppliers',false);
  
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

      $sid = $_GET['dsupplier'];
      $where = array('supid' => $sid);

      $data['supp'] = $this->pm->get_data('suppliers',$where);
      $data['purchase'] = $this->pm->get_dspurchase_data($sdate,$edate,$sid);
      $data['payment'] = $this->pm->get_dsp_payment_data($sdate,$edate,$sid);
      $data['voucher'] = $this->pm->get_dsvoucher_data($sdate,$edate,$sid);
      $data['return'] = $this->pm->get_dspreturn_data($sdate,$edate,$sid);
      }
    else if($report == 'monthlyReports')
      {
      $month = $_GET['month'];
      $data['month'] = $month;
      $year = $_GET['year'];
      $data['year'] = $year;

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

      $sid = $_GET['msupplier'];
      $where = array('supid' => $sid);

      $data['supp'] = $this->pm->get_data('suppliers',$where);
      $data['purchase'] = $this->pm->get_mspurchase_data($month,$year,$sid);
      $data['payment'] = $this->pm->get_msp_payment_data($month,$year,$sid);
      $data['voucher'] = $this->pm->get_msvoucher_data($month,$year,$sid);
      $data['return'] = $this->pm->get_mspreturn_data($month,$year,$sid);
      }
    else if($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $data['year'] = $year;
      $data['report'] = $report;

      $sid = $_GET['ysupplier'];
      $where = array('supid' => $sid);

      $data['supp'] = $this->pm->get_data('suppliers',$where);
      $data['purchase'] = $this->pm->get_yspurchase_data($year,$sid);
      $data['payment'] = $this->pm->get_ysp_payment_data($year,$sid);
      $data['voucher'] = $this->pm->get_ysvoucher_data($year,$sid);
      $data['return'] = $this->pm->get_yspreturn_data($year,$sid);
      }
    else if($report == 'ocust')
      {
      $data['report'] = $report;

      $sid = $_GET['supplier'];
      $where = array('supid' => $sid);

      $data['supp'] = $this->pm->get_data('suppliers',$where);
      $data['purchase'] = $this->pm->get_spurchase_data($sid);
      $data['payment'] = $this->pm->get_sp_payment_data($sid);
      $data['voucher'] = $this->pm->get_svoucher_data($sid);
      $data['return'] = $this->pm->get_sreturn_data($sid);
      }
    }
  else
    {
    $data['purchase'] = '';
    $data['payment'] = '';
    $data['voucher'] = '';
    $data['return'] = '';
    }
    //var_dump('Hello');
  $this->load->view('suppliers/supplier_ledger',$data);
}

public function save_supplier_payment()
  {
  $info = $this->input->post();

  $data = array(
    'supid'       => $info['supid'],
    'pAmount'     => $info['pAmount'],
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'regby'       => $_SESSION['uid']
        );

  $result = $this->pm->insert_data('supplier_payment',$data);
  
  if($info['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($info['accountNo']);
      
      $cdata = array(
        'current' => $cash->current - $info['pAmount'],
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
        'current' => $bank->current - $info['pAmount'],
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
        'current' => $mobile->current - $info['pAmount'],
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
      <h4><i class="icon fa fa-check"></i> Supplier Payment Successfully !</h4></div>'
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
  redirect('Supplier');
}

public function export_action()
    {
    $this->load->library("excel");
    $object = new PHPExcel();

    $object->setActiveSheetIndex(0);

    $table_columns = array("Supplier Name","Company Name","Mobile","Email","Address");

    $column = 0;

    foreach($table_columns as $field)
        {
        $object->getActiveSheet()->setCellValueByColumnAndRow($column,1,$field);
        $column++;
        }

    $supplier_data = $this->pm->supplier_fetch_data($_SESSION['compid']);

    $excel_row = 2;

    foreach($supplier_data as $row)
        {
        $object->getActiveSheet()->setCellValueByColumnAndRow(0,$excel_row,$row->supplierName);
        $object->getActiveSheet()->setCellValueByColumnAndRow(1,$excel_row,$row->compname);
        $object->getActiveSheet()->setCellValueByColumnAndRow(2,$excel_row,$row->mobile);
        $object->getActiveSheet()->setCellValueByColumnAndRow(3,$excel_row,$row->email);
        $object->getActiveSheet()->setCellValueByColumnAndRow(4,$excel_row,$row->address);
        $excel_row++;
        }

    $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Suppliers Data.xls"');
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
                $supplierName = $worksheet->getCellByColumnAndRow(0,$row)->getValue();
                $compname = $worksheet->getCellByColumnAndRow(1,$row)->getValue();
                $mobile = $worksheet->getCellByColumnAndRow(2,$row)->getValue();
                $email = $worksheet->getCellByColumnAndRow(3,$row)->getValue();
                $address = $worksheet->getCellByColumnAndRow(4,$row)->getValue();
                $balance = $worksheet->getCellByColumnAndRow(5,$row)->getValue();

                $query = $this->db->select('sup_id')
                              ->from('suppliers')
                              ->where('compid',$_SESSION['compid'])
                              ->limit(1)
                              ->order_by('sup_id','DESC')
                              ->get()
                              ->row();
                if($query)
                    {
                    $sn = substr($query->sup_id,5)+1;
                    }
                else
                    {
                    $sn = 1;
                    }

                $cn = strtoupper(substr($_SESSION['compname'],0,3));
                $pc = sprintf("%'05d", $sn);

                $cusid = 'S-'.$cn.$pc;

                $data[] = array(
                    'compid'       => $_SESSION['compid'],
                    'sup_id'       =>  $cusid,
                    'supplierName' =>  $supplierName,
                    'compname'     =>  $compname,
                    'mobile'       =>  $mobile,
                    'email'        =>  $email,
                    'address'      =>  $address,
                    'balance'      =>  0,
                    'regby'        => $_SESSION['uid']
                        );
                }
            }
        $this->pm->insert_supplier_data($data);
        echo 'Data Imported successfully';
        }   
}

public function supplier_bank_account()
  {
  $info = $this->input->post();

  $data = array(
    'supid'      => $info['supid'],
    'supBank'    => $info['supBank'],
    'supBranch'  => $info['supBranch'],
    'supAName'   => $info['supAName'],
    'supANumber' => $info['supANumber'],
    'supRNumber' => $info['supRNumber'],
    'regby'      => $_SESSION['uid']
        );

  $result = $this->pm->insert_data('sup_account',$data);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Supplier Bank account add Successfully !</h4></div>'
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
  redirect('Supplier');
}





}