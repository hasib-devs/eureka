<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');

class Employee_payment extends CI_Controller{

public function __construct()
  {
  parent::__construct();       
  $this->load->model("prime_model","pm");
  $this->checkPermission();
}

public function index()
  {
  $data['title'] = 'Employee Payments';

  $field = array(
    'employee_payment' => 'employee_payment.*',
    'employees' => 'employees.empName'
        );
  $join = array(
    'employees' => 'employee_payment.empid = employees.empid'
        );
  $other = array(
    'order_by' => 'empPid',
    'join' => 'left'
        );
    
  $data['employees'] = $this->pm->get_data('employee_payment',false,$field,$join,$other);
  $this->load->view('employeePayment/infos',$data);
}

public function AddInfo()
  {
  $data['title'] = 'Employee Payment';
  
  $where = array(
    'status' => 'Active'
            );
  $data['employee'] = $this->pm->get_data('employees',$where);
  
  $this->load->view('employeePayment/new_payment',$data);
}

public function get_emp33_salary()
  {
  $section = $this->pm->get_salary_emp($_POST['id'],$_POST['id2']);
  $someJSON = json_encode($section);
  echo $someJSON;
}

public function get_emp_salary()
  {
     //$mid = 01; $yid = 2020; $id = 1;
  $section = $this->pm->get_salary_emp($_POST['mid'],$_POST['yid'],$_POST['id']);
    //$someJSON = json_encode($section);
    //echo $someJSON;
  $sstrac = $this->db->select('*')->from('salary_structure')->where('ssid',1)->get()->row();
  $str='';
    $empid = $section->empid;
    $salary = $section->salary;
    $basic = (($section->salary*$sstrac->basic)/100);
    $convence = (($section->salary*$sstrac->childAl)/100);
    $rent = (($section->salary*$sstrac->hrent)/100);
    $medical = (($section->salary*$sstrac->medical)/100);
    $str.="<tr>
    <td>".$section->empName."<input type='hidden' name='empid' value='".$empid."'></td>
    <td>".$section->salary."<input type='hidden' name='salary' id='salary' value='".$section->salary."'></td>
    <td>".$basic."<input type='hidden' name='basic' value='".$basic."'></td>
    <td>".$convence."<input type='hidden' name='convence' value='".$convence."'></td>
    <td>".$rent."<input type='hidden' name='rent' value='".$rent."'></td>
    <td>".$medical."<input type='hidden' name='medical' value='".$medical."'></td>
    <td><input type='text' class='form-control' onkeyup='totalPrice()' name='attday' id='attday' value='0' ></td>
    <td><input type='text' class='form-control' onkeyup='totalPrice()' name='advance' id='advance' value='0' ></td>
    <td><input type='text' class='form-control' onkeyup='totalPrice()' name='bonus' id='bonus' value='0' ></td>
    <td><input type='text' class='totalPrice form-control' name='payment' id='payment' value='".$section->salary."' readonly ></td></tr>";
    
  echo json_encode($str);
}

public function Saveinfo()
  {        
  $info = $this->input->post();

 $emps = array(
    'compid'      => $_SESSION['compid'],
    'empid'       => $info['empid'],
    'month'       => $info['month'],
    'year'        => $info['year'],
    'salary'      => $info['salary'],
    'attday'      => $info['attday'],
    'basic'       => $info['basic'],
    'convence'    => $info['convence'],
    'rent'        => $info['rent'],
    'medical'     => $info['medical'],
    'advance'     => $info['advance'],
    'bonus'       => $info['bonus'],
    'pAmount'     => $info['payment'],
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    'note'        => $info['note'],
    'regby'       => $_SESSION['uid']
                );
        //var_dump($emps); exit();
  $result = $this->pm->insert_data('employee_payment',$emps);
    
  if($info['accountType'] == 'Cash')
    {
    $cash = $this->pm->get_cash_account($info['accountNo']);
      
    $cdata = array(
        'current' => $cash->current-$info['salary'],
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
        'current' => $bank->current-$info['salary'],
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
        'current' => $mobile->current-$info['salary'],
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
      <h4><i class="icon fa fa-check"></i> Employee Payments add Successfully !</h4></div>'
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
  redirect('empPayment');
}

public function emp_payment_details($id)
  {
  $data['title'] = 'Employee Payment';

  $data['company'] = $this->pm->company_details();

  $where = array(
    'empPid' => $id
        );
  $field = array(
    'employee_payment' => 'employee_payment.*',
    'employees' => 'employees.empCode,employees.empName,employees.empAddress,employees.empMobile,employees.empEmail',
    'department' => 'department.dptName'
        );
  $join = array(
    'employees' => 'employees.empid = employee_payment.empid',
    'department' => 'department.dptid = employees.dptid'
        );
  $other = array(
    'join' => 'left'
        );
  $collection = $this->pm->get_data('employee_payment',$where,$field,$join,$other);
  $data['empp'] = $collection[0];

  $this->load->view('employeePayment/emppDetails',$data);
}




}