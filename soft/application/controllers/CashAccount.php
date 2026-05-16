<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class CashAccount extends  CI_Controller {

public function __construct()
  {
  parent::__construct();
  $this->load->model("prime_model","pm");
  $this->checkPermission();
}


public function index()
  {
  $data['title'] = 'Cash Account';
  
  $data['cash'] = $this->pm->get_data('cash',false);

  $this->load->view('cashaccount/cash_list',$data);
}

public function save_cash_account()
  {
  $info = $this->input->post();

  $data = array(
    'compid'   => $_SESSION['compid'],
    'cashName' => $info['cashName'],
    'balance'  => $info['balance'],
    'current'  => $info['balance'],
    'regby'    => $_SESSION['uid']
        );
  
  $result = $this->pm->insert_data('cash',$data);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Successfully Add Cash Account !</h4>
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
  redirect('cashAccount');
}

public function get_cash_account()
  {
  $section = $this->pm->get_cash_account($_POST['id']);
  $someJSON = json_encode($section);
  echo $someJSON;
}

public function update_cash_account()
  {
  $info = $this->input->post();

  $data = array(
    'compid'   => $_SESSION['compid'],
    'cashName' => $info['cashName'],
    'balance'  => $info['balance'],
    'current'  => (($info['current']+$info['balance'])-$info['pbalance']),
    'upby'     => $_SESSION['uid']
        );

  $where = array(
    'ca_id' => $info['caid']
        );
  
  $result = $this->pm->update_data('cash',$data,$where);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Successfully update Cash Account !</h4>
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
  redirect('cashAccount');
}

public function cash_account_delete($id)
  {
  $where = array(
    'ca_id' => $id
        );
  
  $result = $this->pm->delete_data('cash',$where);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Successfully delete Cash Account !</h4>
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
  redirect('cashAccount');
}

public function cash_reports()
  {
  $data['title'] = 'Cash Book';
  $where = array('compid' => $_SESSION['compid']);
  $data['cash'] = $this->pm->get_data('cash',$where);
  $data['company'] = $this->pm->company_details();

  $this->load->view('cashaccount/cashreports',$data);
}

public function transfer_account_list()
  {
  $data['title'] = 'Transfer Account';
  $data['cash'] = $this->pm->get_data('transfer_account',false);
  
  $this->load->view('cashaccount/transfer_account',$data);
}

public function save_transfer_account()
  {
  $info = $this->input->post();

  $data = array(
    'facType' => $info['accountType'],
    'facAcno' => $info['accountNo'],
    'sacType' => $info['account2Type'],
    'sacAcno' => $info['account2No'],
    'amount'  => $info['amount'],
    'note'    => $info['note'],
    'regby'   => $_SESSION['uid']
        );
  
  $result = $this->pm->insert_data('transfer_account',$data);
  
    if($info['accountType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($info['accountNo']);
      
      $cdata = array(
        'current' => $cash->current+$info['amount'],
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
        'current' => $bank->current+$info['amount'],
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
        'current' => $mobile->current+$info['amount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $info['accountNo']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }
    
    if($info['account2Type'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($info['account2No']);
      
      $cdata = array(
        'current' => $cash->current-$info['amount'],
        'upby'    => $_SESSION['uid']
                );
      
      $cwhere = array(
        'ca_id' => $info['account2No']
            );
      $this->pm->update_data('cash',$cdata,$cwhere);
      }
    else if($info['account2Type'] == 'Bank')
      {
      $bank = $this->pm->get_bank_account($info['account2No']);
      
      $bdata = array(
        'current' => $bank->current-$info['amount'],
        'upby'    => $_SESSION['uid']
                );
      
      $bwhere = array(
        'ba_id' => $info['account2No']
            );
      $this->pm->update_data('bankaccount',$bdata,$bwhere);
      }
    else if($info['account2Type'] == 'Mobile')
      {
      $mobile = $this->pm->get_mobile_transaction($info['account2No']);
      
      $mdata = array(
        'current' => $mobile->current-$info['amount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $info['account2No']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Successfully Add transfer Account !</h4>
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
  redirect('transAccount');
}

public function delete_balance_transfer($id)
  {
  $where = array(
    'ta_id' => $id
        );
  $ppurchase = $this->pm->get_data('transfer_account',$where);
  $result = $this->pm->delete_data('transfer_account',$where);
  
  if($ppurchase[0]['facType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($ppurchase[0]['facAcno']);
      
      $cdata = array(
        'current' => $cash->current-$ppurchase[0]['amount'],
        'upby'    => $_SESSION['uid']
                );
      
      $cwhere = array(
        'ca_id' => $ppurchase[0]['facAcno']
            );
      $this->pm->update_data('cash',$cdata,$cwhere);
      }
    else if($ppurchase[0]['facType'] == 'Bank')
      {
      $bank = $this->pm->get_bank_account($ppurchase[0]['facAcno']);
      
      $bdata = array(
        'current' => $bank->current-$ppurchase[0]['amount'],
        'upby'    => $_SESSION['uid']
                );
      
      $bwhere = array(
        'ba_id' => $ppurchase[0]['facAcno']
            );
      $this->pm->update_data('bankaccount',$bdata,$bwhere);
      }
    else if($ppurchase[0]['facType'] == 'Mobile')
      {
      $mobile = $this->pm->get_mobile_transaction($ppurchase[0]['facAcno']);
      
      $mdata = array(
        'current' => $mobile->current-$ppurchase[0]['amount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $ppurchase[0]['facAcno']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }
    
    if($ppurchase[0]['sacType'] == 'Cash')
      {
      $cash = $this->pm->get_cash_account($ppurchase[0]['sacAcno']);
      
      $cdata = array(
        'current' => $cash->current+$ppurchase[0]['amount'],
        'upby'    => $_SESSION['uid']
                );
      
      $cwhere = array(
        'ca_id' => $ppurchase[0]['sacAcno']
            );
      $this->pm->update_data('cash',$cdata,$cwhere);
      }
    else if($ppurchase[0]['sacType'] == 'Bank')
      {
      $bank = $this->pm->get_bank_account($ppurchase[0]['sacAcno']);
      
      $bdata = array(
        'current' => $bank->current+$ppurchase[0]['amount'],
        'upby'    => $_SESSION['uid']
                );
      
      $bwhere = array(
        'ba_id' => $ppurchase[0]['sacAcno']
            );
      $this->pm->update_data('bankaccount',$bdata,$bwhere);
      }
    else if($ppurchase[0]['sacType'] == 'Mobile')
      {
      $mobile = $this->pm->get_mobile_transaction($ppurchase[0]['sacAcno']);
      
      $mdata = array(
        'current' => $mobile->current+$ppurchase[0]['amount'],
        'upby'    => $_SESSION['uid']
                );
      $mwhere = array(
        'ma_id' => $ppurchase[0]['sacAcno']
            );
      $this->pm->update_data('mobileaccount',$mdata,$mwhere);
      }

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Successfully delete transfer Account !</h4>
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
  redirect('transAccount');
}

public function transfer_account_report()
  {
  $data['title'] = 'Transfer Account';
  
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
      
      $data['cash'] = $this->pm->get_dtransfer_account_data($sdate,$edate);
      }
    else if ($report == 'monthlyReports')
      {
      $month = $_GET['month'];
      $data['month'] = $month;
      $year = $_GET['year'];
      $data['year'] = $year;

      if($month == 01)
        {
        $name = 'January';
        }
      elseif ($month == 02)
        {
        $name = 'February';
        }
      elseif ($month == 03)
        {
        $name = 'March';
        }
      elseif ($month == 04)
        {
        $name = 'April';
        }
      elseif ($month == 05)
        {
        $name = 'May';
        }
      elseif ($month == 06)
        {
        $name = 'June';
        }
      elseif ($month == 07)
        {
        $name = 'July';
        }
      elseif ($month == 8)
        {
        $name = 'August';
        }
      elseif ($month == 9)
        {
        $name = 'September';
        }
      elseif ($month == 10)
        {
        $name = 'October';
        }
      elseif ($month == 11)
        {
        $name = 'November';
        }
      else
        {
        $name = 'December';
        }
      $data['name'] = $name;
      $data['report'] = $report;
      
      $data['cash'] = $this->pm->get_mtransfer_account_data($month,$year);
      }
    else if ($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $data['year'] = $year;
      $data['report'] = $report;
      
      $data['cash'] = $this->pm->get_ytransfer_account_data($year);
      }
    }
  else
    {
    $data['cash'] = $this->pm->get_transfer_account_data();
    }
  
  $this->load->view('cashaccount/transfer_report',$data);
}




 
}