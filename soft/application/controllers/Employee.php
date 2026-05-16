<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');

class Employee extends CI_Controller {

public function __construct()
  {
  parent::__construct();       
  $this->load->model("prime_model","pm");
  $this->checkPermission();
}


public function emp_department()
  {
  $data['title'] = 'Department';

  $data['dept'] = $this->pm->get_data('department',false);

  $this->load->view('employees/department',$data);
}

public function save_department()
  {       
  $info = $this->input->post();
         
  $data = array(
    'compid'  => $_SESSION['compid'],
    'dptName' => $info['dptName'],
    'regby'   => $_SESSION['uid']
        );
  
  $result = $this->pm->insert_data('department',$data);

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
  redirect('Department');
}

public function get_dept_data()
  {
  $section = $this->pm->get_dept_data($_POST['id']);
  $someJSON = json_encode($section);
  echo $someJSON;
}

public function update_dept()
  {       
  $info = $this->input->post();
         
  $data = array(
    'compid'  => $_SESSION['compid'],
    'dptName' => $info['dptName'],
    'status'  => $info['status'],
    'upby'    => $_SESSION['uid']
          );

  $where = array(
    'dptid' => $info['dptid']
          );

  $result = $this->pm->update_data('department',$data,$where);

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
  redirect('Department');
}

public function delete_dept($id)
  {
  $where = array(
    'dptid' => $id
        );

  $empd = $this->pm->get_data('employees',$where);

  if(!$empd)
    {
    $result = $this->pm->delete_data('department',$where);

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
    }
  else
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-ban"></i> All ready add this department in employees !</h4></div>'
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('Department');
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
  
  $config['upload_path'] = './upload/users/';
  $config['allowed_types'] = '*';
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
    'empFName'   => $info['empFName'],
    'empMName'   => $info['empMName'],
    'dptid'      => $info['dptid'],
    'empAddress' => $info['empAddress'],
    'empMobile'  => $info['empMobile'],
    'empEmail'   => $info['empEmail'],
    'joinDate'   => date('Y-m-d', strtotime($info['joinDate'])),
    'salary'     => $info['salary'],
    'nid'        => $info['nid'],
    'empFiles'   => $img,
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
  
  $query = $this->db->select('empFiles')
                ->from('employees')
                ->where('empid',$info['empid'])
                ->get()
                ->row();
                
  $config['upload_path'] = './upload/users/';
  $config['allowed_types'] = '*';
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
    if($query)
      {
      $img = $query->empFiles;
      }
    else
      {
      $img = '';
      }
    }
    
  $employee = array(
    'empName'    => $info['empName'],
    'empFName'   => $info['empFName'],
    'empMName'   => $info['empMName'],
    'dptid'      => $info['dptid'],
    'empAddress' => $info['empAddress'],
    'empMobile'  => $info['empMobile'],
    'empEmail'   => $info['empEmail'],
    'joinDate'   => date('Y-m-d', strtotime($info['joinDate'])),
    'salary'     => $info['salary'],
    'nid'        => $info['nid'],
    'empFiles'   => $img,
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