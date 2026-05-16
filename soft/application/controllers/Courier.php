<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');

class Courier extends CI_Controller {

public function __construct()
  {
  parent::__construct();       
  $this->load->model("prime_model","pm");
  $this->checkPermission();
}


public function emp_courier()
  {
  $data['title'] = 'Courier';
    $field = array(
        'employees' => 'employees.empName'
    );
    
    $join = array(
        'employees' => 'employees.empid = courier.empid'
    );
    
    $data['dept'] = $this->pm->get_data('courier', false,false, $join, false);

  $data['emp'] = $this->pm->get_data('employees',false);

  $this->load->view('courier/courier',$data);
}

public function save_courier()
  {       
  $info = $this->input->post();
         
  $data = array(
    // 'compid'  => $_SESSION['compid'],
    'empID' => $info['empID'],
    'courierName' => $info['courierName']
    // 'regby'   => $_SESSION['uid']
     
    
        );
  
  $result = $this->pm->insert_data('courier',$data);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Staff department add Successfully !</h4></div>'
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
  redirect('Courier');
}

public function get_courier_data()
  {
  $section = $this->pm->get_courier_data($_POST['id']);
  $someJSON = json_encode($section);
  echo $someJSON;
}

public function update_courier()
  {       
  $info = $this->input->post();
         
  $data = array(
    // 'compid'  => $_SESSION['compid'],
    // 'dptName' => $info['dptName'],
    'empID' => $info['empID'],
    'courierName' => $info['courierName'],
    'status'  => $info['status'],
    // 'upby'    => $_SESSION['uid']
          );

  $where = array(
    'id' => $info['id']
          );

  $result = $this->pm->update_data('courier',$data,$where);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Staff Department update Successfully !</h4></div>'
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
  redirect('Courier');
}

public function delete_courier($id)
  {
  $where = array(
    'id' => $id
        );

//   $empd = $this->pm->get_data('employees',$where);

//   if(!$empd)
//     {
    $result = $this->pm->delete_data('courier',$where);

    if($result)
      {
      $sdata = [
        'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Staff department delete Successfully !</h4></div>'
              ];  
      }
    else
      {
      $sdata = [
        'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-ban"></i> Failed !</h4></div>'
              ];
      }
//     }
//   else
//     {
//     $sdata = [
//       'exception' =>'<div class="alert alert-danger alert-dismissible">
//       <h4><i class="icon fa fa-ban"></i> All ready add this department in employees !</h4></div>'
//             ];
//     }
  $this->session->set_userdata($sdata);
  redirect('Courier');
}

public function employee_info()
  {
  $data['title'] = 'Staff / Employee';

  $where = array(
    'status' => 'Active'
        );

  $data['employee'] = $this->pm->get_data('employees',false);
  $data['dept'] = $this->pm->get_data('department',$where);
  
  $this->load->view('employees/employees',$data);
}

public function save_employee()
  {       
  $info = $this->input->post();

  $query = $this->db->select('empid')
                ->from('employees')
                ->limit(1)
                ->order_by('empid','DESC')
                ->get()
                ->row();
  if($query)
    {
    $sn = $query->empid+1;
    }
  else
    {
    $sn = 1;
    }

  $cn = strtoupper(substr($_SESSION['compname'],0,3));
  $pc = sprintf("%'05d",$sn);
  $cusid = 'E-'.$cn.$pc;

  $employee = array(
    'compid'     => $_SESSION['compid'],
    'empCode'    => $cusid,
    'empName'    => $info['empName'],
    'dptid'      => $info['dptid'],
    'empAddress' => $info['empAddress'],
    'empMobile'  => $info['empMobile'],
    'empEmail'   => $info['empEmail'],
    'joinDate'   => date('Y-m-d', strtotime($info['joinDate'])),
    'salary'     => $info['salary'],
    'nid'        => $info['nid'],
    'regby'      => $_SESSION['uid']
            );
  $result = $this->pm->insert_data('employees',$employee);
    
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Staff / Employee add Successfully !</h4></div>'
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
  redirect('Employee');
}

public function get_emp_data()
  {
  $section = $this->pm->get_emp_data($_POST['id']);
  $someJSON = json_encode($section);
  echo $someJSON;
}

public function update_Employee()
  {       
  $info = $this->input->post();

  $employee = array(
    'empName'    => $info['empName'],
    'dptid'      => $info['dptid'],
    'empAddress' => $info['empAddress'],
    'empMobile'  => $info['empMobile'],
    'empEmail'   => $info['empEmail'],
    'joinDate'   => date('Y-m-d', strtotime($info['joinDate'])),
    'salary'     => $info['salary'],
    'nid'        => $info['nid'],
    'status'     => $info['status'],
    'upby'       => $_SESSION['uid']
            );
    //var_dump($employee); exit();
  $where = array(
    'empid' => $info['empid']
        );

  $result = $this->pm->update_data('employees',$employee,$where);
    
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Staff update Successfully !</h4>
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
  redirect('Employee');
}

public function delete_Employee($id)
  {
  $where = array(
    'empid' => $id
        );

  $result = $this->pm->delete_data('employees',$where);
  $this->pm->delete_data('users',$where);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Staff / Employee delete Successfully !</h4></div>'
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
  redirect('Employee');
}









}