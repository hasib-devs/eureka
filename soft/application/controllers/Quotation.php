<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');
  
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

class Quotation extends CI_Controller {

public function __construct()
  {
  parent::__construct();
  $this->load->model("prime_model","pm");
  $this->checkPermission();
}

public function index()
  {
  $data['title'] = 'Quotation';
  
  $total_records = $this->pm->count_all('quotation');

  $limit = 10;
  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  $offset = ($page - 1) * $limit;
  $total_pages = ceil($total_records / $limit);
  
  $other = array(
    'order_by' => 'qutid',
    'join' => 'left',
    'limit'    => $limit,
    'offset'   => $offset
        );
  $field = array(
    'quotation' => 'quotation.*',
    'customers' => 'customers.*'
        );
  $join = array(
    'customers' => 'customers.custid = quotation.custid'
        );
  $data['quotation'] = $this->pm->get_data('quotation',false,$field,$join,$other);
  //var_dump($data['purchase']); exit();
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
  
  $this->load->view('quotation/quotationlist',$data);
}

public function new_quotation() 
  {
  $data['title'] = 'Quotation';

  $where = array(
    'status' => 'Active'
        );
  $data['customer'] = $this->pm->get_data('customers',$where);
  $data['product'] = $this->pm->get_data('products',$where);
  $data['category'] = $this->pm->get_data('categories',$where);

  $this->load->view('quotation/newQuotation',$data);
}

public function getProduct($id)
  {
  $where = array(
    'pid' => $id
        );

  $productlist = $this->pm->get_data('products',$where);

  $str = "";
  foreach ($productlist as $value)
    {
    $id = $value['pid'];
    
    $costing = $this->db->select('pprice,sprice')
                ->from('costing')
                ->where('pid',$id)
                ->get()
                ->row();
    // if($costing)
    //   {
    //   $iprice = $costing->pprice;
    //   $sprice = $costing->sprice;
    //   }
    // else
    //   {
    //   $iprice = 0;
    //   $sprice = $value['sprice'];
    //   }
    $sprice = $value['sprice'];
    
    $str .= "<tr>
    <td>".$value['pName']." ( ".$value['pCode']." )"."<input type='hidden' name='product[]' value='".$value['pid']."' required ></td>
    <td><input type='text' class='form-control' onkeyup='totalPrice(".$id.")' name='quantity[]' id='quantity_".$value['pid']."' value='1' required ></td>
    <td><input type='text' class='form-control' onkeyup='totalPrice(".$id.")' name='sprice[]' id='sprice_".$value['pid']."' value='".$value['sprice']."' required ></td>
    <td><input type='text' class='form-control tprice' name='tprice[]' id='tprice_".$value['pid']."' value='".$sprice."' required readonly ></td>
    <td><span class='item_remove btn btn-danger btn-xs' onClick='$(this).parent().parent().remove();'>x</span></td></tr>";
    }
    
    //<td><input type='text' class='form-control' name='iprice[]' value='".$iprice."' readonly ></td>
  echo json_encode($str);
}

public function save_quotation()
  {
  $info = $this->input->post();

  $query = $this->db->select('qutid')
                ->from('quotation')
                ->limit(1)
                ->order_by('qutid','DESC')
                ->get()
                ->row();
  if($query)
    {
    $sn = $query->qutid+1;
    }
  else
    {
    $sn = 1;
    }

  $cn = strtoupper(substr($_SESSION['compname'],0,3));
  $pc = sprintf("%'05d",$sn);

  $cusid = 'Q-'.$cn.$pc;

  $quotation = array(
    'compid'   => $_SESSION['compid'],
    'qinvoice' => $cusid,
    'qutDate'  => date('Y-m-d',strtotime($info['date'])),
    'custid'   => $info['customer'],
    'tAmount'  => $info['tAmount'],
    'note'     => $info['note'],
    'regby'    => $_SESSION['uid']
        );
      //var_dump($quotation); exit();
  $result = $this->pm->insert_data('quotation',$quotation);
        //var_dump($purchase_id); exit();
  $length = count($info['product']);
    
  for($i = 0; $i < $length; $i++)
    {
    $qdata = array(
      'qutid'    => $result,
      'pid'      => $info['product'][$i],
      'quantity' => $info['quantity'][$i],
      'sprice'   => $info['sprice'][$i],
      'tprice'   => $info['tprice'][$i],
      'regby'    => $_SESSION['uid']
          );
      //var_dump($purchase_product);            
    $result2 = $this->pm->insert_data('quotation_product',$qdata);
    }
  if($result2)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> Quotation add Successfully !</h4></div>'
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
  redirect('Quotation');
}

public function view_quotation($id)
  {
  $data['title'] = 'Quotation';

  $where = array(
    'qutid' => $id
        );
  $join = array(
    'products' => 'products.pid = quotation_product.pid'
        );
  $data['pquotation'] = $this->pm->get_data('quotation_product',$where,false,$join);
  
  $field = array(
    'quotation' => 'quotation.*',
    'customers'=>'customers.*',
    'users' => 'users.name, users.compname'
        );
  $pjoin = array(
    'customers' => 'customers.custid = quotation.custid',
    'users' => 'users.uid = quotation.regby'
        );
  $quotation = $this->pm->get_data('quotation',$where,$field,$pjoin);
  $data['quotation'] = $quotation[0];    
  
  $data['company'] = $this->pm->company_details();
  
  $this->load->view('quotation/viewquotation',$data);
}

public function pdf_quotation($id)
  {
  $where = array(
    'qutid' => $id
        );
  $join = array(
    'products' => 'products.pid = quotation_product.pid'
        );
  $data['pquotation'] = $this->pm->get_data('quotation_product',$where,false,$join);
  
  $field = array(
    'quotation' => 'quotation.*',
    'customers'=>'customers.*',
    'users' => 'users.name, users.compname'
        );
  $pjoin = array(
    'customers' => 'customers.custid = quotation.custid',
    'users' => 'users.uid = quotation.regby'
        );
  $quotation = $this->pm->get_data('quotation',$where,$field,$pjoin);
  $data['quotation'] = $quotation[0];    
  
  $data['company'] = $this->pm->company_details();
  
  $html = $this->load->view('quotation/download_quotaion',$data,TRUE);
    //var_dump($html); exit();
  $options = new Options();
  $options->set('isRemoteEnabled', TRUE);

  $dompdf = new Dompdf($options);
  $dompdf->loadHtml($html);
  $dompdf->setPaper('A4', 'portrait');
  $dompdf->render();

  $dompdf->stream("quotation_".$id.".pdf", array("Attachment" => 1));
}

public function edit_quotation($id)
  {
  $data['title'] = 'Quotation';

  $cwhere = array(
    'status' => 'Active'
        );
  $data['customer'] = $this->pm->get_data('customers',$cwhere);
  $data['product'] = $this->pm->get_data('products',$cwhere);
  $data['category'] = $this->pm->get_data('categories',$cwhere);

  $where = array(
    'qutid' => $id
        );
  $join = array(
    'products' => 'products.pid = quotation_product.pid'
        );
  $data['pquotation'] = $this->pm->get_data('quotation_product',$where,false,$join);

  $quotation = $this->pm->get_data('quotation',$where);
  $data['quotation'] = $quotation[0];    
  
  $this->load->view('quotation/editquotation',$data);
}

public function update_Quotation()
  {
  $info = $this->input->post();

  $where = array(
    'qutid' => $info['qutid']
        );

  $quotation = array(
    'qutDate'  => date('Y-m-d',strtotime($info['date'])),
    'custid'   => $info['customer'],
    'tAmount'  => $info['tAmount'],
    'note'     => $info['note'],
    'upby'     => $_SESSION['uid']
        );

  $result = $this->pm->update_data('quotation',$quotation,$where);

  $this->pm->delete_data('quotation_product',$where);
  
  $length = count($this->input->post('product_id'));

  $length = count($info['product']);
    
  for($i = 0; $i < $length; $i++)
    {
    $qdata = array(
      'qutid'    => $info['qutid'],
      'pid'      => $info['product'][$i],
      'quantity' => $info['quantity'][$i],
      'sprice'   => $info['sprice'][$i],
      'tprice'   => $info['tprice'][$i],
      'regby'    => $_SESSION['uid']
          );
      //var_dump($purchase_product);            
    $result2 = $this->pm->insert_data('quotation_product',$qdata);
    }
  if($result2)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Quotation update Successfully !</h4>
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
  redirect('Quotation');
}

public function delete_quotation($id)
  {
  $where = array(
    'qutid' => $id
        );

  $result = $this->pm->delete_data('quotation',$where);
  $result2 = $this->pm->delete_data('quotation_product',$where);
  
  if($result && $result2)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-danger alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Quotation delete Successfully !</h4>
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
  redirect('Quotation');
}










}