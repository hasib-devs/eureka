<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');

class Loan extends CI_Controller{

public function __construct()
  {
  parent::__construct();       
  $this->load->model("prime_model","pm");
  $this->checkPermission();
}

public function index()
  {
  $data['title'] = 'Employee Loan';

      $field = array(
        // 'employee_payment' => 'employee_payment.*',
        'loan'     =>'loan.*',
        'employees' => 'employees.empName,employees.empCode'
            );
      $join = array(
        'employees' => 'employees.empid = loan.empid'
            );
    //   $other = array(
    //     'order_by' => 'empid',
    //     'join' => 'left'
    //         );

  $data['loan'] = $this->pm->get_data('loan',false,$field,$join,false);

  $this->load->view('loan/loan_list',$data);
}

public function AddInfo()
  {
  $data['title'] = 'Employee Payment';
  $where = array(
    'status' => 'Active'
        );
  $data['employee'] = $this->pm->get_data('employees',$where);

  $this->load->view('loan/new_loan',$data);
}

public function get_emp_salary()
    {
      // Load input values from POST
    $mid = $this->input->post('mid');
    $yid = $this->input->post('yid');
    $id = $this->input->post('id');

  $section = $this->pm->get_salary_empOne($mid,$yid,$id);
    //$someJSON = json_encode($section);
    //echo $someJSON;
    $empid = $section->empid;
    // $loan = $section->loan;
  $str='';
    

    $str.="<tr>
    <td>".$section->empName."<input type='hidden' name='empid[]' value='".$empid."'></td>
    <td><input type='text' name='loan[]' id='loan' ></td>";
    
   
    
  echo json_encode($str);

}
//         <td>".$basic."<inpu  <td>".$basic."<input type='hidden' name='basic[]' value='".$basic."'></td>
//     <td>".$convence."<input type='hidden' name='convence[]' value='".$convence."'></td>
//     <td>".$rent."<input type='hidden' name='rent[]' value='".$rent."'></td>
//     <td>".$medical."<input type='hidden' name='medical[]' value='".$medical."'></td>
//     <td><input type='text' class='form-control' onkeyup='totalPrice()' name='attday[]' id='attday' value='0' ></td>
//     <td><input type='text' class='form-control' onkeyup='totalPrice()' name='advance[]' id='advance' value='0' ></td>
//     <td><input type='text' class='form-control' onkeyup='totalPrice()' name='bonus[]' id='bonus' value='0' ></td>
//  <td><input type='text' class='totalPrice form-control' name='payment[]' id='payment' value='".$section->salary."' readonly ></td></tr>";>";

public function save_employee_payment()
  {        
  $info = $this->input->post();
  $length = count($info['empid']);

  for ($i = 0; $i < $length; $i++)
    {
    $emps = array(
        'empid'       => $info['empid'][$i],
        // 'compid'      => $_SESSION['compid'],
        'month'       => $info['month'],
        'year'        => $info['year'],
        'loan'      => $info['loan'][$i],
        // 'attday'      => $info['attday'][$i],
        // 'tSalary'     => $info['basic'][$i],
        // 'allowance'   => $info['convence'][$i],
        // 'hrAmount'    => $info['rent'][$i],
        // 'mallowance'  => $info['medical'][$i],
        // 'aAmount'     => $info['advance'][$i],
        // 'bonus'       => $info['bonus'][$i],
        // 'pAmount'     => $info['payment'][$i],
        'accountType' => $info['accountType'],
        'accountNo'   => $info['accountNo'],
        'note'        => $info['note'],
        'regby'       => $_SESSION['uid']
                );
    //var_dump($emps); exit();
    $result = $this->pm->insert_data('loan',$emps);
    }
    // if($result)
    //     {
        $sdata = [
          'exception' =>'<div class="alert alert-success alert-dismissible">
          <h4><i class="icon fa fa-check"></i> Employee Payment added Successfully !</h4></div>'
                ];  
    //     }
    //   else
    //     {
    //     $sdata = [
    //       'exception' =>'<div class="alert alert-danger alert-dismissible">
    //       <h4><i class="icon fa fa-ban"></i> Failed !</h4></div>'
    //             ];
    //     }
  $this->session->set_userdata($sdata);
  redirect('empLoan');
}

public function emp_loan_details($id)
  {
  $data['title'] = 'Employee Loan';

  $data['company'] = $this->pm->company_details();

  $where = array(
    'lid' => $id
        );
  $field = array(
    'loan' => 'loan.*',
    'employees' => 'employees.empName,employees.empAddress,employees.empMobile,employees.empEmail,employees.empCode'
        );
  $join = array(
    // 'employees' => 'employees.empid = employee_payment.empid',
    'employees'  => 'employees.empid = loan.empid'
        );
  $other = array(
    'join' => 'left'
            );
  $collection = $this->pm->get_data('loan',$where,$field,$join,$other);
  $data['empp'] = $collection[0];

  $this->load->view('loan/view_loan',$data);
}

public function employee_payment_ledger()
  {
  $data = ['title' => 'Employee Payment'];
  $data['company'] = $this->pm->company_details();
  $data['employee'] = $this->pm->get_data('employees',false);
  
  if(isset($_GET['search']))
    {
    $report = $_GET['reports'];
      
    if($report == 'monthlyReports')
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
      
      $empid = $_GET['memp'];
      $where = array('empid' => $empid);

      $data['emp'] = $this->pm->get_data('employees',$where);
      $data['payment'] = $this->pm->get_memp_payment_ledger_data($month,$year,$empid);
      }
    else if($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $data['year'] = $year;
      $data['report'] = $report;
      
      $empid = $_GET['yemp'];
      $where = array('empid' => $empid);

      $data['emp'] = $this->pm->get_data('employees',$where);
      $data['payment'] = $this->pm->get_yemp_payment_ledger_data($year,$empid);
      }
    else if($report == 'ocust')
      {
      $data['report'] = $report;
      $empid = $_GET['emp'];
      $where = array('empid' => $empid);

      $data['emp'] = $this->pm->get_data('employees',$where);
      $data['payment'] = $this->pm->get_emp_payment_ledger_data($empid);
      }
    }
  else
    {
    $data['payment'] = '';
    }
    //var_dump('Hello');
  $this->load->view('employeePayment/payment_ledger',$data);
}

public function delete_loan($id)
  {
  $where = array(
    'lid' => $id
        );

    $result = $this->pm->delete_data('loan',$where);

    if($result)
      {
      $sdata = [
        'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Loan delete Successfully !</h4>
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
  redirect('empLoan');
}

public function get_loan_payment()
  {
  $section = $this->pm->get_loan_payment($_POST['id']);
  $data = json_encode($section);
  echo $data;
}
public function getAccountNo()
  {
  $str = '';
  $where = array(
    'status' => 'Active',
    'compid' => $_SESSION['compid']
        );

  $accountType = $this->input->post('id');
  if($accountType == 'Bank')
    {
    $accounts = $this->pm->get_data('bankaccount',$where);
    if(count($accounts) == 0)
      {
      $str .= "<option value='0'>Please Add Bank account</option>";
      } 
    else 
      {
      foreach ($accounts as $value) {
        $str .= "<option value='".$value['ba_id']."'>".$value['bankName'].' '.$value['branchName'].' '.$value['accountNo'].' '.$value['accountName']."</option>";
        }
      }
    } 
  else if($accountType == 'Mobile')
    {
    $accounts = $this->pm->get_data('mobileaccount',$where);
    if(count($accounts) == 0) 
      {
      $str .= "<option value='0'>Please Add mobile account</option>";
      } 
    else 
      {
      foreach ($accounts as $value) {
        $str .= "<option value='".$value['ma_id']."'>".$value['accountName'].' '.$value['accountNo']."</option>";
        }
      }
    } 
  else if($accountType == 'Cash') 
    {
    $accounts = $this->pm->get_data('cash',$where);
    if(count($accounts) == 0) 
      {
      $str .= "<option value='0'>Please Add cash account</option>";
      } 
    else 
      {
      foreach ($accounts as $value) {
        $str .= "<option value='".$value['ca_id']."'>".$value['cashName']."</option>";
        }
      }
    } 
  else 
    {
    $str .= "<option >Please account Type</option>";
    }
  echo json_encode($str);
}
public function save_sales_payment()
  {
  $info = $this->input->post();
  
  $query = $this->db->select('spid')
              ->from('sales_payment')
              ->limit(1)
              ->order_by('spid','DESC')
              ->get()
              ->row();
  if($query)
    {
    $sn = $query->spid+1;
    }
  else
    {
    $sn = 1;
    }
  $cn = strtoupper(substr($_SESSION['compname'],0,3));
  $pc = sprintf("%'05d", $sn);

  $cusid = 'SP-'.$cn.$pc;
  
  $sale = [
    'said'        => $info['said'],
    'spCode'      => $cusid,
    'spDate'      => date('Y-m-d', strtotime($info['spDate'])),
    'pAmount'     => $info['pAmount'],
    'accountType' => $info['accountType'],
    'accountNo'   => $info['accountNo'],
    // 'notes'       => $info['notes'],          
    'regby'       => $_SESSION['uid']
        ];
    //var_dump($sale); exit();
  $result = $this->pm->insert_data('sales_payment',$sale);
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Sale Payment add Successfully !</h4>
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
  redirect('Sale');
}
}