<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');

class Courieraccount extends  CI_Controller {

public function __construct()
  {
  parent::__construct();
  $this->load->model("prime_model","pm");
  $this->checkPermission();
}


public function index()
  {
  $data['title'] = 'Courier Account';
  
  $data['cash'] = $this->pm->get_data('courier_account',false);

  $this->load->view('courieraccount/courier_account',$data);
}

public function save_courier_account()
  {
  $info = $this->input->post();

  $data = array(
    'compid'  => $_SESSION['compid'],
    'caName'  => $info['caName'],
    'balance' => $info['balance'],
    'regby'   => $_SESSION['uid']
        );
  
  $result = $this->pm->insert_data('courier_account',$data);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Successfully Add Courier Account !</h4>
        </div>'
            ];
    }
  else
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Something is error !</h4>
        </div>'
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('Courieraccount');
}

public function get_courier_account()
  {
  $section = $this->pm->get_courier_account(1);
  $someJSON = json_encode($section);
  echo $someJSON;
}

public function update_courier_account()
  {
  $info = $this->input->post();

  $data = array(
    'compid'  => $_SESSION['compid'],
    'caName'  => $info['caName'],
    'balance' => $info['balance'],
    'upby'    => $_SESSION['uid']
        );

  $where = array(
    'caid' => $info['caid']
        );
  
  $result = $this->pm->update_data('courier_account',$data,$where);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Successfully update Courier Account !</h4>
        </div>'
            ];
    }
  else
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Something is error !</h4>
        </div>'
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('Courieraccount');
}

public function courier_account_delete($id)
  {
  $where = array(
    'caid' => $id
        );
  
  $result = $this->pm->delete_data('courier_account',$where);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Successfully delete Courier Account !</h4>
        </div>'
            ];
    }
  else
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Something is error !</h4>
        </div>'
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('Courieraccount');
}





 
}