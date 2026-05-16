<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class User extends CI_Controller {

public function __construct()
  {
  parent::__construct();
  $this->load->model("prime_model","pm");
 // $this->checkPermission();
}

public function user_notice_lists()
  {
  $data['title'] = 'Notice';
  $data['notice'] = $this->pm->get_user_notice();

  //var_dump($data['users']); exit();
  $this->load->view('users/notice_list',$data);
}

public function user_role()
  {
  $data['title'] = 'User Role';

  $where = array(
    'ax_id >' => 2
        );

  $data['role'] = $this->pm->get_data('access_lavels',$where);

  $this->load->view('users/user_role',$data);
}

public function save_accesslavel()
  {
  $info = $this->input->post();

  $urole = array(
    'compid'    => $_SESSION['compid'],
    'lavelName' => $info['lavelName'],
    'regby'     => $_SESSION['uid']
        );
 
  $result = $this->pm->insert_data('access_lavels',$urole);

  $pdata = [
    'utype'        => $result,
    'compid'       => $_SESSION['compid'],
    'regby'        => $_SESSION['uid'],
    'dashboard'    => 1
        ];

  $fdata = [
    'utype'        => $result,
    'compid'       => $_SESSION['compid'],
    'regby'        => $_SESSION['uid']
        ];

  $this->pm->insert_data('tbl_user_m_permission',$pdata);
  $this->pm->insert_data('tbl_user_p_permission',$fdata);
  $this->pm->insert_data('tbl_user_f_permission',$fdata);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> User role add Successfully !</h4>
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
  redirect('uRole');
}

public function get_user_role_data()
  {
  $section = $this->pm->get_user_role_data($_POST['id']);
  $someJSON = json_encode($section);
  echo $someJSON;
}

public function update_user_role()
  {
  $info = $this->input->post();

  $where = array(
    'ax_id' => $info['roleid']
        );

  $urole = array(
    'compid'    => $_SESSION['compid'],
    'lavelName' => $info['lavelName'],
    'status'    => $info['status'],
    'upby'      => $_SESSION['uid']
        );
  //var_dump($where,$urole); exit();
  $result = $this->pm->update_data('access_lavels',$urole,$where);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> User role update Successfully !</h4>
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
  redirect('uRole');
}

public function delete_user_role($id)
  {
  $uwhere = array(
      'userrole' => $id
          );
  $auser = $this->pm->get_data('users',$uwhere);

  if(!$auser)
    {
    $where = array(
      'ax_id' => $id
          );
   
    $result = $this->pm->delete_data('access_lavels',$where);

    if($result)
      {
      $sdata = [
        'exception' =>'<div class="alert alert-danger alert-dismissible">
          <h4><i class="icon fa fa-check"></i> User role delete Successfully !</h4>
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
        <h4><i class="icon fa fa-ban"></i> All ready add this user role in user !</h4>
        </div>'
            ];
    }
  $this->session->set_userdata($sdata);
  redirect('uRole');
}

public function user_list()
  {
  $data['title'] = 'User';
  
  $field = array(
    'users' => 'users.uid,users.name,users.email,users.mobile,users.status,users.password',
    'access_lavels' => 'lavelName'
        );
  $join = array(
    'access_lavels' => 'access_lavels.ax_id = users.userrole'
        );
  $other = array(
    'order_by' => 'uid',
    'join' => 'left'
        );

  $data['users'] = $this->pm->get_data('users',false,$field,$join,$other);

  $awhere = array(
    'status' => 'Active',
    'compid' => $_SESSION['compid']
        );
  $data['userrole'] = $this->pm->get_data('access_lavels',$awhere);
  $data['emp'] = $this->pm->get_employee();
  //var_dump($data['emp']); exit();
  $this->load->view('users/users',$data);
}

public function save_user()
  {
  $info = $this->input->post(); 

  $where = array(
    'empid' => $info['emp']
        );
  $emp = $this->pm->get_data('employees',$where);

  $data = array(
    'compid'   => $_SESSION['compid'],
    'compname' => $_SESSION['compname'],
    'empid'    => $info['emp'],
    'name'     => $emp[0]['empName'],
    'email'    => $emp[0]['empEmail'],
    'mobile'   => '+88'.$emp[0]['empMobile'],
    'password' => $info['password'],
    'userrole' => $info['utype'],      
    'regby'    => $_SESSION['uid']
        );
  //var_dump($data); exit();
  $result = $this->pm->insert_data('users',$data);
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> User add Successfully !</h4>
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
  redirect('User');
}

public function get_user_data()
  {
  $grup = $this->pm->get_user_data($_POST['id']);
  $someJSON = json_encode($grup);
  echo $someJSON;
}

public function update_User()
  {
  $info = $this->input->post(); 

  $sdata = array(
    'userrole' => $info['utype'],
    'status'   => $info['status'],      
    'upby'     => $_SESSION['uid']
        );

  $where = array(
    'uid' => $info['user_id']
        );
      
  $result = $this->pm->update_data('users',$sdata,$where);
  
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> User update Successfully !</h4>
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
  redirect('User');
}

public function delete_user($id)
  {
  $where = array(
      'uid' => $id
          );
      
  $result = $this->pm->delete_data('users',$where);
  
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> User delete Successfully !</h4>
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
  redirect('User');
}

public function help_support()
  {
  $data['title'] = 'Help & Support';

  $where = array(
    'compid' => $_SESSION['compid']
        );
  $data['help'] = $this->pm->get_data('help_support',$where);

  $this->load->view('users/help_support',$data);
}

public function save_help_support_msg()
  {
  $info = $this->input->post();

  $query = $this->db->select('hs_id')
                  ->from('help_support')
                  ->limit(1)
                  ->order_by('hs_id','DESC')
                  ->get()
                  ->row();
  if($query)
      {
      $sn = substr($query->hs_id,5)+1;
      }
  else
      {
      $sn = 1;
      }

  $cn = strtoupper(substr($_SESSION['compname'],0,3));
  $pc = sprintf("%'05d", $sn);

  $cusid = 'HS-'.$cn.$pc; 

  $data = array(
    's_id'    => $cusid,
    'compid'  => $_SESSION['compid'],
    'subject' => $info['subject'],
    'message' => $info['message'],      
    'regby'   => $_SESSION['uid']
        );
  //var_dump($data); exit();
  $result = $this->pm->insert_data('help_support',$data);
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Help & Support Send Successfully !</h4>
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
  redirect('helpSupport');
}

public function reply_help_support_msg()
  {
  $info = $this->input->post(); 

  $data = array(
    'hp_id' => $info['hs_id'],
    'reply' => $info['message'],
    'regby' => $_SESSION['uid']
        );
  //var_dump($data); exit();
  $result = $this->pm->insert_data('help_support_reply',$data);
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Help & Support Reply Successfully !</h4>
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
  redirect('helpSupport');
}

public function delete_help_message($id)
  {
  $where = array(
    'hs_id' => $id
        );

  $rwhere = array(
    'hp_id' => $id
        );
  //var_dump($data); exit();
  $result = $this->pm->delete_data('help_support',$where);
  $result2 = $this->pm->delete_data('help_support_reply',$rwhere);
  if($result && $result2)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Help & Support message delete Successfully !</h4>
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
  redirect('helpSupport');
}

// public function get_help_reply_data()
//   {
//   $grup = $this->pm->get_help_reply_data($_POST['id']);
//   $someJSON = json_encode($grup);
//   echo $someJSON;
// }

public function get_help_reply_data()
  {
  $id = $this->input->post('id');

  $where = array(
    'hp_id' => $id
        );
  $field = array(
    'help_support_reply' => 'help_support_reply.reply',
    'users' => 'users.name'
        );
  $join = array(
    'users' => 'users.uid = help_support_reply.regby'
        );
  $other = array(
    'join' => 'left'
        );
  $products = $this->pm->get_data('help_support_reply',$where,$field,$join,$other);
  $str='';
  foreach($products as $value){
    $str.="<tr><td>".$value['reply'].'<br><b>'.$value['name'].'<b>'.' '.$value['regdate']."</td></tr>";
    }
  echo json_encode($str);
}

public function all_user_lists()
  {
  $data['title'] = 'User List';
  $where = array(
    'userrole' => 2
        );
  $data['users'] = $this->pm->get_data('users',$where);

  $this->load->view('users/user_list',$data);
}

public function save_user_payment()
  {
  $info = $this->input->post(); 

  $data = array(
      'package' => $info['utype'],
      'amount'  => $info['amount'], 
      'uid'     => $info['user_id'],      
      'regby'   => $_SESSION['uid']
          );
  //var_dump($data); exit();
  $result = $this->pm->insert_data('user_payments',$data);
  if($result)
      {
      $sdata = [
        'exception' =>'<div class="alert alert-success alert-dismissible">
          <h4><i class="icon fa fa-check"></i>User payment add Successfully !</h4>
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
  redirect('userList');
}

public function inactive_users($id)
  {
  $sdata = array(
    'status' => 'Inactive',      
    'upby'   => $_SESSION['uid']
        );

  $where = array(
    'compid' => $id
        );
      
  $result = $this->pm->update_data('users',$sdata,$where);
  
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-ban"></i>User Inactive Successfully !</h4>
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
  redirect('userList');
}

public function active_users($id)
  {
  $sdata = array(
    'status' => 'Active',      
    'upby'   => $_SESSION['uid']
        );

  $where = array(
    'compid' => $id
        );
      
  $result = $this->pm->update_data('users',$sdata,$where);
  
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i>User Active Successfully !</h4>
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
  redirect('userList');
}






}