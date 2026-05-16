<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');

class Webhome extends CI_Controller {

public function __construct()
  {
  parent::__construct();
  $this->load->model("prime_model",'pm');
}

        ################################################
        #   /* Pages  start*/                          #
        ################################################

public function track_order()
  {
  $data['title'] = 'Home';
  if(isset($_GET['search']))
    {
    $oid = $_GET['oid'];
            //var_dump($employee); exit();
    if(is_numeric($oid))
      {
      $data['morder'] = $this->pm->get_morder_track_data($oid);
      $data['order'] = '';
      }
     else
      {
      $data['order'] = $this->pm->get_order_track_data($oid);
      $data['morder'] = '';
      }
    }
  else
    {
    }
  $data['company'] = $this->pm->company_profile_details();
  //var_dump($data['pagesetup']); exit();
    
  $this->load->view('order/track_order',$data);
}



        ################################################
        #   /* Pages  end*/                            #
        ################################################
}