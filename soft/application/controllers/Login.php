<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');

class Login extends CI_Controller {

public function __construct()
  {
  parent::__construct();
  $this->load->model("prime_model",'pm');
  $this->load->library('email');
}

        ################################################
        #   /* Pages  start*/                          #
        ################################################

public function index()
  {
  $data['title'] = 'Sign In';
        
  $this->load->view('login',$data);
}

public function loginProcess()
  {
  $info = $this->input->post();

  $uname = $info['username'];
  if(is_numeric($uname))
    {     
    $where = array(
      'mobile'   => '+88'.$info['username'],
      'status'   => 'Active',
      'password' => $info['password']
          );
    }
  else
    {
    $where = array(
      'email'    => $info['username'],
      'status'   => 'Active',
      'password' => $info['password']
          );
    }
    //var_dump($where); //exit();
  $user_data = $this->pm->get_data('users',$where);
    //var_dump($user_data); exit();
  $company = $this->pm->company_details();
  if($user_data)
    {
    $udata = [
      'uid'      => $user_data[0]['uid'],
      'name'     => $user_data[0]['name'],
      'compname' => $company->com_name,
      'email'    => $user_data[0]['email'],
      'role'     => $user_data[0]['userrole'],
      'compid'   => $user_data[0]['compid'],
      'empid'    => $user_data[0]['empid']
            ];
        //var_dump($udata); exit();
    $this->session->set_userdata($udata);

    $pwhere = array(
      'utype' => $user_data[0]['userrole']
          );

    $master = $this->pm->get_data('tbl_user_m_permission',$pwhere);
    $page = $this->pm->get_data('tbl_user_p_permission',$pwhere);
    $function = $this->pm->get_data('tbl_user_f_permission',$pwhere);
        //var_dump($paccess); exit();
    if($page)
      {
      $mdata = [
        'dashboard'   => $master[0]['dashboard'],
        'inventory'   => $master[0]['inventory'],
        'brtaregstar' => $master[0]['brtaregstar'],
        'accounting'  => $master[0]['accounting'],
        'hrpayroll'   => $master[0]['hrpayroll'],
        'reports'     => $master[0]['reports'],
        'settings'    => $master[0]['settings'],
        'accesssetup' => $master[0]['accesssetup']
                ];
      
      $pdata = [
        'todaysales'       => $page[0]['todaysales'],
        'todaypurchase'    => $page[0]['todaypurchase'],
        'todayexpense'     => $page[0]['todayexpense'],
        'todayincome'      => $page[0]['todayincome'],
        'lastdSales'       => $page[0]['lastdSales'],
        'products'         => $page[0]['products'],
        'services'         => $page[0]['services'],
        'purchases'        => $page[0]['purchases'],
        'sales'            => $page[0]['sales'],
        'saleservices'     => $page[0]['saleservices'],
        'salereturns'      => $page[0]['salereturns'],
        'purreturns'       => $page[0]['purreturns'],
        'preorder'         => $page[0]['preorder'],
        'quotations'       => $page[0]['quotations'],
        'brtareglist'      => $page[0]['brtareglist'],
        'majortype'        => $page[0]['majortype'],
        'subtype'          => $page[0]['subtype'],
        'voucherentry'     => $page[0]['voucherentry'],
        'voucherreports'   => $page[0]['voucherreports'],
        'journalentry'     => $page[0]['journalentry'],
        'trailbalance'     => $page[0]['trailbalance'],
        'balancesheet'     => $page[0]['balancesheet'],
        'generalledger'    => $page[0]['generalledger'],
        'incomestatement'  => $page[0]['incomestatement'],
        'customers'        => $page[0]['customers'],
        'suppliers'        => $page[0]['suppliers'],
        'employees'        => $page[0]['employees'],
        'users'            => $page[0]['users'],
        'emppayments'      => $page[0]['emppayments'],
        'salesreports'     => $page[0]['salesreports'],
        'purchasereports'  => $page[0]['purchasereports'],
        'profitreports'    => $page[0]['profitreports'],
        'spprofit'         => $page[0]['spprofit'],
        'custreports'      => $page[0]['custreports'],
        'custledger'       => $page[0]['custledger'],
        'supreports'       => $page[0]['supreports'],
        'supledger'        => $page[0]['supledger'],
        'stockreports'     => $page[0]['stockreports'],
        'orderreports'     => $page[0]['orderreports'],
        'dailyreports'     => $page[0]['dailyreports'],
        'cashbook'         => $page[0]['cashbook'],
        'bankbook'         => $page[0]['bankbook'],
        'mobilebook'       => $page[0]['mobilebook'],
        'swpreports'       => $page[0]['swpreports'],
        'duereports'       => $page[0]['duereports'],
        'btransreports'    => $page[0]['btransreports'],
        'duepreports'      => $page[0]['duepreports'],
        'expensereports'   => $page[0]['expensereports'],
        'btransferreports' => $page[0]['btransferreports'],
        'alltransreports'  => $page[0]['alltransreports'],
        'category'         => $page[0]['category'],
        'units'            => $page[0]['units'],
        'department'       => $page[0]['department'],
        'courier'          => $page[0]['courier'],
        'cashaccount'      => $page[0]['cashaccount'],
        'bankaccount'      => $page[0]['bankaccount'],
        'mobileaccount'    => $page[0]['mobileaccount'],
        'usertype'         => $page[0]['usertype'],
        'purchasetype'     => $page[0]['purchasetype'],
        'balancetransfer'  => $page[0]['balancetransfer'],
        'accesssetuplist'  => $page[0]['accesssetuplist']
                ];
  
      $fdata = [
        'newProduct'       => $function[0]['newProduct'],
        'editProduct'      => $function[0]['editProduct'],
        'deleteProduct'    => $function[0]['deleteProduct'],
        'storeProduct'     => $function[0]['storeProduct'],
        'newService'       => $function[0]['newService'],
        'editService'      => $function[0]['editService'],
        'deleteService'    => $function[0]['deleteService'],
        'newPurchase'      => $function[0]['newPurchase'],
        'editPurchase'     => $function[0]['editPurchase'],
        'deletePurchase'   => $function[0]['deletePurchase'],
        'newSale'          => $function[0]['newSale'],
        'editSale'         => $function[0]['editSale'],
        'deleteSale'       => $function[0]['deleteSale'],
        'salesbrta'        => $function[0]['salesbrta'],
        'newSService'      => $function[0]['newSService'],
        'editSService'     => $function[0]['editSService'],
        'deleteSService'   => $function[0]['deleteSService'],
        'newSReturns'      => $function[0]['newSReturns'],
        'editSReturns'     => $function[0]['editSReturns'],
        'deleteSReturns'   => $function[0]['deleteSReturns'],
        'newPReturns'      => $function[0]['newPReturns'],
        'editPReturns'     => $function[0]['editPReturns'],
        'deletePReturns'   => $function[0]['deletePReturns'],
        'newPreorder'      => $function[0]['newPreorder'],
        'editPreorder'     => $function[0]['editPreorder'],
        'deletePreorder'   => $function[0]['deletePreorder'],
        'newQuotations'    => $function[0]['newQuotations'],
        'editQuotations'   => $function[0]['editQuotations'],
        'deleteQuotations' => $function[0]['deleteQuotations'],
        'editbrtareg'      => $function[0]['editbrtareg'],
        'newMType'         => $function[0]['newMType'],
        'editMType'        => $function[0]['editMType'],
        'deleteMType'      => $function[0]['deleteMType'],
        'newSType'         => $function[0]['newSType'],
        'editSType'        => $function[0]['editSType'],
        'deleteSType'      => $function[0]['deleteSType'],
        'newVEntry'        => $function[0]['newVEntry'],
        'editVEntry'       => $function[0]['editVEntry'],
        'deleteVEntry'     => $function[0]['deleteVEntry'],
        'newCustomer'      => $function[0]['newCustomer'],
        'editCustomer'     => $function[0]['editCustomer'],
        'deleteCustomer'   => $function[0]['deleteCustomer'],
        'newSupplier'      => $function[0]['newSupplier'],
        'editSupplier'     => $function[0]['editSupplier'],
        'deleteSupplier'   => $function[0]['deleteSupplier'],
        'newEmployee'      => $function[0]['newEmployee'],
        'editEmployee'     => $function[0]['editEmployee'],
        'deleteEmployee'   => $function[0]['deleteEmployee'],
        'newUser'          => $function[0]['newUser'],
        'editUser'         => $function[0]['editUser'],
        'deleteUser'       => $function[0]['deleteUser'],
        'newEmppay'        => $function[0]['newEmppay'],
        'newCategory'      => $function[0]['newCategory'],
        'editCategory'     => $function[0]['editCategory'],
        'deleteCategory'   => $function[0]['deleteCategory'],
        'newUnit'          => $function[0]['newUnit'],
        'editUnit'         => $function[0]['editUnit'],
        'deleteUnit'       => $function[0]['deleteUnit'],
        'newDepartment'    => $function[0]['newDepartment'],
        'editDepartment'   => $function[0]['editDepartment'],
        'deleteDepartment' => $function[0]['deleteDepartment'],
        'newCAccount'      => $function[0]['newCAccount'],
        'editCAccount'     => $function[0]['editCAccount'],
        'deleteCAccount'   => $function[0]['deleteCAccount'],
        'newBAccount'      => $function[0]['newBAccount'],
        'editBAccount'     => $function[0]['editBAccount'],
        'deleteBAccount'   => $function[0]['deleteBAccount'],
        'newMAccount'      => $function[0]['newMAccount'],
        'editMAccount'     => $function[0]['editMAccount'],
        'deleteMAccount'   => $function[0]['deleteMAccount'],
        'newUType'         => $function[0]['newUType'],
        'editUType'        => $function[0]['editUType'],
        'deleteUType'      => $function[0]['deleteUType'],
        'newPType'         => $function[0]['newPType'],
        'editPType'        => $function[0]['editPType'],
        'deletePType'      => $function[0]['deletePType'],
        'newTransfer'      => $function[0]['newTransfer'],
        'editTransfer'     => $function[0]['editTransfer'],
        'deleteTransfer'   => $function[0]['deleteTransfer']
            ];
  
      $this->session->set_userdata($mdata);
      $this->session->set_userdata($pdata);
      $this->session->set_userdata($fdata);
      }
    redirect('Dashboard');
    }
  else
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-ban"></i> Invalid Login id or Password !</h4></div>'
            ];

    $this->session->set_userdata($sdata);
    redirect('Login');
    }
}

public function forget_password()
  {
  $data['title'] = 'Forget Password';
      
  $this->load->view('forget_password',$data);
}

public function check_forget_password_email()
  {
  $this->load->library('email');

  $empemail = $this->input->post('username');
  
  if(is_numeric($empemail))
    {
    $mid = '+88'.$this->input->post('username');
    $fpe = $this->pm->check_mobile_number($mid);
      // var_dump($fpe); var_dump($mid); exit();
    if($fpe)
      {
      $prdata = [
        'useruid' => $fpe->uid
            ];
      
      $this->session->set_userdata($prdata);
      
      $digits = 6;
      $otp = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
      $msg = $otp.' is your otp code for verify. Expires in 10 minites. Sunshine it';

      $to = $mid;
      $token = "4451583824966151583824966";
      $message = $msg;
      $url = "http://sms.iglweb.com/api/v1/balance?api_key=(API";
        
      $data = array(
        'to'      => "$to",
        'message' => "$message",
        'token'   =>"$token"
              );
            //var_dump($data); exit();
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$url);
      curl_setopt($ch, CURLOPT_ENCODING, '');
      curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $smsresult = curl_exec($ch);

      $udata = array(
        'otp'  => $otp,
        'upby' => $_SESSION['useruid']
              );
        //var_dump($udata); exit();
      $uwhere = array(
        'mobile' => $mid,
        'uid'    => $_SESSION['useruid']
              );

      $result = $this->pm->update_data('users',$udata,$uwhere);
            
      if($result)
        {
        $sdata = [
          'exception' =>'<div class="alert alert-success alert-dismissible">
          <h4><i class="icon fa fa-check"></i> Enter Your OTP code !</h4></div>'
                ];

        $this->session->set_userdata($sdata);
        redirect('otpPassword');
        }
      else
        {
        $sdata = [
          'exception' =>'<div class="alert alert-danger alert-dismissible">
          <h4><i class="icon fa fa-ban"></i> Somethings is Wrong !</h4></div>'
                ];
    
        $this->session->set_userdata($sdata);
        redirect('forgetPassword');
        }
      }
    else
      {
      $sdata = [
        'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-ban"></i> This mobile is not exit !</h4></div>'
              ];
  
      $this->session->set_userdata($sdata);
      redirect('forgetPassword');
      }
    }
  else
    {
    $fpe = $this->pm->check_email($empemail);
    
    $prdata = [
      'useruid' => $fpe->uid
          ];
  
    $this->session->set_userdata($prdata);
        //var_dump($fpe); exit();
    if($fpe)
      {
      $config = Array(
        'protocol' => 'smtp',
        'smtp_host' => 'ssl://smtp.gmail.com',
        'smtp_port' => 465,
        'smtp_user' => 'sunshine@gmail.com', // change it to yours
        'smtp_pass' => '123456', // change it to yours
        'mailtype' => 'html',
        'charset' => 'iso-8859-1',
        'wordwrap' => TRUE
            );

      $to = $fpe->email;

      $message = "<p>
          <h3>Hi !</h3>
          <h4>Reset your Sunshine Account Password.</h4>
          <p>We have received a request to reset your Sunshine account password associated with this email address. If you have not placed this request, you can safely ignore this email and we assure you that your account is completely secure.</p>
          <p>If you do need to change your Sunshine Password, you can use the link given below.</p>
          <b>New Password Setup : http://maxmarketingbd.com/app/passwordSetup .</b>
          <p>Please contact <b style='color: green;'>support@sunshine.com.bd</b> for any queries regarding this.</p><br>
          <h5>Cheers</h5>
          <h6>Sunshine Team</h6>
          <h6><b style='color: green;'>www.sunshine.com.bd</b></h6>
          </p>";
      $this->load->library('email',$config);
      $this->email->set_newline("\r\n");
      $this->email->from('sajadulshogib43@gmail.com'); // change it to yours
      $this->email->to($to);// change it to yours
      $this->email->subject('Forget Password');
      $this->email->message($message);
        
      if($this->email->send())
        {
        $sdata = [
          'exception' =>'<div class="alert alert-success alert-dismissible">
          <h4><i class="icon fa fa-check"></i> Check Your email !</h4></div>'
                    ];  

        $this->session->set_userdata($sdata);
        redirect('Login');
        }
      else
        {
        $sdata = [
          'exception' =>'<div class="alert alert-danger alert-dismissible">
          <h4><i class="icon fa fa-ban"></i> Somethings is Wrong !</h4></div>'
                ];

        $this->session->set_userdata($sdata);
        redirect('forgetPassword');
        }
      }
    else
      {
      $sdata = [
        'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-ban"></i> This email id is not exit !</h4></div>'
              ];
  
      $this->session->set_userdata($sdata);
      redirect('forgetPassword');
      }
    }
}

public function otp_password()
  {
  $data['title'] = 'Forget Password';
      
  $this->load->view('otp_password',$data);
}

public function check_otp()
  {
  $info = $this->input->post();

  $where = array(
    'otp' => $info['otp'],
    'uid' => $_SESSION['useruid']
          );
  
  $result = $this->pm->get_data('users',$where);
   // var_dump($result); exit();

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Password Setup !</h4></div>'
            ];  

    $this->session->set_userdata($sdata);
    redirect('passwordSetup');
    }
  else
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-ban"></i> Failed !</h4></div>'
            ];

    $this->session->set_userdata($sdata);
    redirect('forgetPassword');
    }
}

public function password_setup()
  {
  $data['title'] = 'Password Setup';
      
  $this->load->view('pass_setup',$data);
}

public function save_passord_setup()
  {
  $info = $this->input->post();

  $npassword = $info['npassword'];
  $cpassword = $info['cpassword'];

  if($npassword == $cpassword)
    {
    $info = [
      'password' => $info['npassword'],
      'upby'     => $_SESSION['useruid']
            ];
    
    $where = array(
      'uid' => $_SESSION['useruid']
          );
        //var_dump($where); exit();
    $result = $this->pm->update_data('users',$info,$where);

    if($result)
      {
      $sdata = [
        'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> New Password Setup Successfully !</h4></div>'
              ];  

      $this->session->set_userdata($sdata);
      redirect('Login');
      }
    else
      {
      $sdata = [
        'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-ban"></i> Failed !</h4></div>'
              ];

      $this->session->set_userdata($sdata);
      redirect('passwordSetup');
      }
    }
  else
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-ban"></i> Password can not match !</h4></div>'
            ];

    $this->session->set_userdata($sdata);
    redirect('passwordSetup');
    }
}

public function logout()
  {
  $this->session->sess_destroy();
  redirect('Login');
}

public function account_verify()
  {
  $where = [
    'email' => $_SESSION['useremail']
        ];

  $info = [
    'status' => 'Active',
    'upby'   => $_SESSION['uid']
        ];
       
  $result = $this->pm->update_data('users',$info,$where);
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> User verify Successfully !</h4></div>'
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
  redirect('Login');
}


        ################################################
        #   /* Pages  end*/                            #
        ################################################
}