<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');

class Category extends CI_Controller {

public function __construct()
  {
  parent::__construct();
  $this->load->model("prime_model","pm");
  $this->checkPermission();
}

public function index()
  {
  $data['title'] = 'Category';

  $data['category'] = $this->pm->get_data('categories',false);
  
  $this->load->view('category/category',$data);
}

public function save_category()
  {
  $info = $this->input->post();

  $data = array(
    'compid'  => $_SESSION['compid'],
    'catName' => $info['catName'],          
    'regby'   => $_SESSION['uid']
        );

  $result = $this->pm->insert_data('categories',$data);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Category add Successfully !</h4>
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
  redirect('Category');
}

public function get_category_data()
  {
  $section = $this->pm->get_category_data($_POST['id']);
  $someJSON = json_encode($section);
  echo $someJSON;
}

public function update_category()
  {
  $info = $this->input->post();

  $data = array(
    'compid'  => $_SESSION['compid'],
    'catName' => $info['catName'],
    'status'  => $info['status'],            
    'upby'    => $_SESSION['uid']
        );

  $where = array(
    'catid' => $info['catid']
        );

  $result = $this->pm->update_data('categories',$data,$where);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Category update Successfully !</h4>
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
  redirect('Category');
}

public function delete_category($id)
  {
  $where = array(
    'catid' => $id
        );

  $empu = $this->pm->get_data('products',$where);

  if(!$empu)
    {
    $result = $this->pm->delete_data('categories',$where);

    if($result)
      {
      $sdata = [
        'exception' =>'<div class="alert alert-danger alert-dismissible">
          <h4><i class="icon fa fa-check"></i> Category delete Successfully !</h4>
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
  else
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-ban"></i> All ready add this Category in Product !</h4>
        </div>'
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('Category');
}

public function product_units()
  {
  $data['title'] = 'Unit';

  $data['unit'] = $this->pm->get_data('sma_units',false);

  $this->load->view('category/product_units',$data);
}

public function save_units()
  {
  $info = $this->input->post();

  $data = array(
    'compid'   => $_SESSION['compid'],
    'unitName' => $info['unitName'],         
    'regby'    => $_SESSION['uid']
        );

  $result = $this->pm->insert_data('sma_units',$data);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i>Units add Successfully !</h4>
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
  redirect('Unit');
}

public function get_unit_data()
  {
  $section = $this->pm->get_unit_data($_POST['id']);
  $someJSON = json_encode($section);
  echo $someJSON;
}

public function update_units()
  {
  $info = $this->input->post();

  $data = array(
    'compid'   => $_SESSION['compid'],
    'unitName' => $info['unitName'],
    'status'   => $info['status'],            
    'upby'     => $_SESSION['uid']
        );

  $where = array(
    'untid' => $info['untid']
        );

  $result = $this->pm->update_data('sma_units',$data,$where);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i>Unit update Successfully !</h4>
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
  redirect('Unit');
}

public function delete_units($id)
  {
  $where = array(
    'untid' => $id
        );
  $empu = $this->pm->get_data('products',$where);

  if(!$empu)
    {
    $result = $this->pm->delete_data('sma_units',$where);

    if($result)
      {
      $sdata = [
        'exception' =>'<div class="alert alert-danger alert-dismissible">
          <h4><i class="icon fa fa-check"></i> Unit delete Successfully !</h4>
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
  else
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-ban"></i> All ready add this Unit in Product !</h4>
        </div>'
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('Unit');
}

public function purchase_type()
  {
  $data['title'] = 'Purchase Type';

  $data['putype'] = $this->pm->get_data('purchase_type',false);

  $this->load->view('category/purchase_type',$data);
}

public function save_purchase_type()
  {
  $info = $this->input->post();

  $data = array(
    'compid' => $_SESSION['compid'],
    'ptName' => $info['ptName'],         
    'regby'  => $_SESSION['uid']
        );

  $result = $this->pm->insert_data('purchase_type',$data);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Purchase Type add Successfully !</h4>
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
  redirect('puType');
}

public function get_purchase_type_data()
  {
  $section = $this->pm->get_purchase_type_data($_POST['id']);
  $someJSON = json_encode($section);
  echo $someJSON;
}

public function update_purchase_type()
  {
  $info = $this->input->post();

  $data = array(
    'compid' => $_SESSION['compid'],
    'ptName' => $info['ptName'],
    'status' => $info['status'],            
    'upby'   => $_SESSION['uid']
        );

  $where = array(
    'ptid' => $info['ptid']
        );

  $result = $this->pm->update_data('purchase_type',$data,$where);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Purchase Type update Successfully !</h4>
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
  redirect('puType');
}

public function delete_purchase_type($id)
  {
  $where = array(
    'ptid' => $id
        );
  $result = $this->pm->delete_data('purchase_type',$where);

    if($result)
      {
      $sdata = [
        'exception' =>'<div class="alert alert-danger alert-dismissible">
          <h4><i class="icon fa fa-check"></i> Purchase Type delete Successfully !</h4>
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
  redirect('puType');
}

public function currency()
  {
  $data['title'] = 'Currency';
  $data['currency'] = $this->pm->get_data('currency',false);
  
  $this->load->view('category/currency',$data);
}

public function save_currency()
  {
  $info = $this->input->post();

  $data = array(
    'cName' => $info['cName'],     
    'regby' => $_SESSION['uid']
        );

  $result = $this->pm->insert_data('currency',$data);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> currency add Successfully !</h4>
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
  redirect('currency');
}

public function get_currency_data()
  {
  $section = $this->pm->get_currency_data($_POST['id']);
  $someJSON = json_encode($section);
  echo $someJSON;
}

public function update_currency()
  {
  $info = $this->input->post();

  $data = array(
    'cName'  => $info['cName'],
    'status' => $info['status'],            
    'upby'   => $_SESSION['uid']
        );

  $where = array(
    'cid' => $info['cid']
        );

  $result = $this->pm->update_data('currency',$data,$where);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> currency update Successfully !</h4>
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
  redirect('currency');
}

public function delete_currency($id)
  {
  $where = array(
    'cid' => $id
        );
//   $empu = $this->pm->get_data('tender',$where);

//   if(!$empu)
//     {
    $result = $this->pm->delete_data('currency',$where);

    if($result)
      {
      $sdata = [
        'exception' =>'<div class="alert alert-danger alert-dismissible">
          <h4><i class="icon fa fa-check"></i> currency delete Successfully !</h4>
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
//     }
//   else
//     {
//     $sdata = [
//       'exception' =>'<div class="alert alert-danger alert-dismissible">
//         <h4><i class="icon fa fa-ban"></i> All ready add this currency in tender !</h4>
//         </div>'
//             ];
//     }
  $this->session->set_userdata($sdata);
  redirect('currency');
}






}