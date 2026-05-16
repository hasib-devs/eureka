<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');

class Costing extends CI_Controller {

public function __construct()
  {
  parent::__construct();
  $this->load->model("prime_model","pm");
  $this->checkPermission();
}

public function index()
  {
  $data['title'] = 'Costing';
  
  $total_records = $this->pm->count_all('costing');

  $limit = 10;
  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  $offset = ($page - 1) * $limit;
  $total_pages = ceil($total_records / $limit);
  
  $other = array(
    'order_by' => 'cstid',
    'join' => 'left',
    //'limit'    => $limit,
    //'offset'   => $offset
        );
  $field = array(
    'costing' => 'costing.*',
    'products' => 'products.pName,products.model,products.partNo',
    'categories' => 'categories.catName'
        );    
  $join = array(
    'products' => 'products.pid = costing.pid',
    'categories' => 'categories.catid = products.catid'
        );
  $data['purchase'] = $this->pm->get_data('costing',false,$field,$join,$other);
  
  $pagination_html = '<ul class="pagination">';

  if($page > 1)
    {
    $pagination_html .= '<li class="paginated"><a href="?page=' . ($page - 1) . '">Previous</a></li>';
    }

  $num_links = 5;
  $half_links = floor($num_links / 2);
  $start_page = max(1, $page - $half_links);
  $end_page = min($total_pages, $start_page + $num_links - 1);

  if($page > 1)
    {
    $pagination_html .= '<li class="paginated"><a href="?page=' . ($page - 1) . '">Prev</a></li>';
    }

  for($i = $start_page; $i <= $end_page; $i++)
    {
    $pagination_html .= '<li class="paginated';
    if($page == $i)
      {
      $pagination_html .= ' active';
      }
    $pagination_html .= '"><a href="?page=' . $i . '">' . $i . '</a></li>';
    }

  if($end_page < $total_pages)
    {
    $pagination_html .= '<li class="disabled"><span>...</span></li>';
    }
    
  if($page < $total_pages)
    {
    $pagination_html .= '<li class="paginated"><a href="?page=' . ($page + 1) . '">Next</a></li>';
    }

  $pagination_html .= '</ul>';

  $data['pagination_html'] = $pagination_html;

  $this->load->view('costing/costing_list',$data);
}

public function in22dex()
  {
  $data['title'] = 'Costing';

  $other = array(
    'order_by' => 'cstid',
    'join' => 'left'
        );
  $field = array(
    'costing' => 'costing.*',
    'products' => 'products.pName,products.model,products.partNo',
    'categories' => 'categories.catName'
        );    
  $join = array(
    'products' => 'products.pid = costing.pid',
    'categories' => 'categories.catid = products.catid'
        );
  $data['purchase'] = $this->pm->get_data('costing',false,$field,$join,$other);

  $this->load->view('costing/costing_list',$data);
}

public function new_costing() 
  {
  $data['title'] = 'Costing';

  $data['product'] = $this->pm->get_data('products',false);
  $data['category'] = $this->pm->get_data('categories',false);

  $this->load->view('costing/new_costing',$data);
}

public function saved_costing()
  {
  $info = $this->input->post();
    //var_dump($cusid); exit();
  $purchase = array(
    'pid'       => $info['product'],
    'pprice'    => $info['pprice'],
    'pdiscount' => $info['pdiscount'],
    'tpprice'   => $info['tpprice'],
    'crate'     => $info['crate'],
    'camount'   => $info['camount'],
    'uCost'     => $info['uCost'],
    'weight'    => $info['weight'],
    'quantity'  => $info['quantity'],
    'tweight'   => $info['tweight'],
    'asamount'  => $info['asamount'],
    'usdrate'   => $info['usdrate'],
    'aamount'   => $info['aamount'],
    'tasamount' => $info['tasamount'],
    'apamount'  => $info['apamount'],
    'taamount'  => $info['taamount'],
    'tcamount'  => $info['tcamount'],
    'custom'    => $info['custom'],
    'ocost'     => $info['ocost'],
    'tamount'   => $info['tamount'],
    'pmargin'   => $info['pmargin'],
    'sprice'    => $info['sprice'],
    'stock'     => $info['stock'],
    'note'      => $info['note'],
    'regby'     => $_SESSION['uid']
        );
       // var_dump($purchase); exit();
  $result = $this->pm->insert_data('costing',$purchase);
  
  $swhere = array(
    'pid' => $info['product']
              );

  $stpd = $this->pm->get_data('stock',$swhere);
  $this->pm->delete_data('stock',$swhere);

  if($stpd)
    {
    $tqnt = $stpd[0]['tquantity']+$info['stock'];
    $dtqnt = $stpd[0]['dtquantity'];
    }
  else
    {
    $tqnt = $info['stock'];
    $dtqnt = 0;
    }

  $stock = array(
    'compid'     => $_SESSION['compid'],
    'pid'        => $info['product'],
    'tquantity'  => $tqnt,
    'dtquantity' => $dtqnt,
    'regby'      => $_SESSION['uid']
          );
        //var_dump($stock_info);    
  $this->pm->insert_data('stock',$stock);   
     
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Costing add Successfully !</h4></div>'
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
  redirect('Costing');
}

public function view_costing($id)
  {
  $data['title'] = 'Costing';

  $where = array(
    'cstid' => $id
        );
   $field = array(
    'costing' => 'costing.*',
    'products' => 'products.pName,products.model,products.hsn'
        );    
  $join = array(
    'products' => 'products.pid = costing.pid'
        );

  $purchase = $this->pm->get_data('costing',$where,$field,$join);
    //var_dump($purchase); exit();
  $data['purchase'] = $purchase[0];
  $data['company'] = $this->pm->company_details();
    
  $this->load->view('costing/view_costing',$data);
}

public function edit_costing($id)
  {
  $data['title'] = 'costing';

  $where = array(
    'cstid' => $id
        );
  $purchase = $this->pm->get_data('costing',$where);
  $data['purchase'] = $purchase[0];

  $data['product'] = $this->pm->get_data('products',false);
  $data['category'] = $this->pm->get_data('categories',false);
    
  $this->load->view('costing/edit_costing',$data);
}

public function update_costing()
  {
  $info = $this->input->post();

  $purchase = array(
    'pid'       => $info['product'],
    'pprice'    => $info['pprice'],
    'pdiscount' => $info['pdiscount'],
    'tpprice'   => $info['tpprice'],
    'crate'     => $info['crate'],
    'camount'   => $info['camount'],
    'uCost'     => $info['uCost'],
    'weight'    => $info['weight'],
    'quantity'  => $info['quantity'],
    'tweight'   => $info['tweight'],
    'asamount'  => $info['asamount'],
    'usdrate'   => $info['usdrate'],
    'aamount'   => $info['aamount'],
    'tasamount' => $info['tasamount'],
    'apamount'  => $info['apamount'],
    'taamount'  => $info['taamount'],
    'tcamount'  => $info['tcamount'],
    'custom'    => $info['custom'],
    'ocost'     => $info['ocost'],
    'tamount'   => $info['tamount'],
    'pmargin'   => $info['pmargin'],
    'sprice'    => $info['sprice'],
    'stock'     => $info['stock'],
    'note'      => $info['note'],
    'upby'      => $_SESSION['uid']
        );

  $where = array(
    'cstid' => $info['cstid']
        );
  $pproduct = $this->pm->get_data('costing',$where);
  $result = $this->pm->update_data('costing',$purchase,$where);
  
  $length = count($pproduct);
        
  for($i = 0; $i < $length; $i++)
    {
    $swhere = array(
      'pid' => $pproduct[$i]['pid']
            );

    $spd = $this->pm->get_data('stock',$swhere);
    $this->pm->delete_data('stock',$swhere);

    if($spd)
      {
      if($pproduct)
        {
        $tquantity = ($spd[0]['tquantity']-$pproduct[$i]['stock']);
        $dtqnt = $spd[0]['dtquantity'];
        }
      else
        {
        $tquantity = $spd[0]['tquantity'];
        $dtqnt = $spd[0]['dtquantity'];
        }
      }
    else
      {
      $tquantity = 0;
      $dtqnt = 0;
      }

    $stock_info = array(
      'compid'     => $_SESSION['compid'],
      'pid'        => $pproduct[$i]['pid'],
      'tquantity'  => $tquantity,
      'dtquantity' => $dtqnt,
      'regby'      => $_SESSION['uid']
                );
        //var_dump($stock_info);    
    $this->pm->insert_data('stock',$stock_info); 
    }
    
  $swhere = array(
    'pid' => $info['product']
              );

  $stpd = $this->pm->get_data('stock',$swhere);
  $this->pm->delete_data('stock',$swhere);

  if($stpd)
    {
    $tqnt = $stpd[0]['tquantity']+$info['stock'];
    $dtqnt = $stpd[0]['dtquantity'];
    }
  else
    {
    $tqnt = $info['stock'];
    $dtqnt = 0;
    }

  $stock = array(
    'compid'     => $_SESSION['compid'],
    'pid'        => $info['product'],
    'tquantity'  => $tqnt,
    'dtquantity' => $dtqnt,
    'regby'      => $_SESSION['uid']
          );
        //var_dump($stock_info);    
  $this->pm->insert_data('stock',$stock);

  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Costing update Successfully !</h4></div>'
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
  redirect('Costing');
}

public function delete_costing($id)
  {
  $where = array(
    'cstid' => $id
        );
  $pproduct = $this->pm->get_data('costing',$where);
  $result = $this->pm->delete_data('costing',$where);
  
  $length = count($pproduct);
        
  for($i = 0; $i < $length; $i++)
    {
    $swhere = array(
      'pid' => $pproduct[$i]['pid']
            );

    $spd = $this->pm->get_data('stock',$swhere);
    $this->pm->delete_data('stock',$swhere);

    if($spd)
      {
      if($pproduct)
        {
        $tquantity = ($spd[0]['tquantity']-$pproduct[$i]['stock']);
        $dtqnt = $spd[0]['dtquantity'];
        }
      else
        {
        $tquantity = $spd[0]['tquantity'];
        $dtqnt = $spd[0]['dtquantity'];
        }
      }
    else
      {
      $tquantity = 0;
      $dtqnt = 0;
      }

    $stock_info = array(
      'compid'     => $_SESSION['compid'],
      'pid'        => $pproduct[$i]['pid'],
      'tquantity'  => $tquantity,
      'dtquantity' => $dtqnt,
      'regby'      => $_SESSION['uid']
                );
        //var_dump($stock_info);    
    $this->pm->insert_data('stock',$stock_info); 
    }
  if($result)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Costing delete Successfully !</h4></div>'
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
  redirect('Costing');
}

public function costing_reports()
  {
  $data['title'] = 'Costing Reports';

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
            //var_dump($employee); exit();
      $data['costing'] = $this->pm->get_dcosting_data($sdate,$edate);
      }
    else if($report == 'monthlyReports')
      {
      $month = $_GET['month'];
      $data['month'] = $month;
      $year = $_GET['year'];
      $data['year'] = $year;
            //var_dump($data['month']); exit();
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
      
      $data['costing'] = $this->pm->get_mcosting_data($month,$year);
      }
    else if ($report == 'yearlyReports')
      {
      $year = $_GET['ryear'];
      $data['year'] = $year;
      $data['report'] = $report;

      $data['costing'] = $this->pm->get_ycosting_data($year);
      }
    }
  else
    {
    $data['costing'] = $this->pm->get_costing_data();
    }

  $this->load->view('costing/costing_report',$data);
}




}