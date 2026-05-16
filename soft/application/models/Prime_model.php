<?php
if(!defined('BASEPATH'))
  exit('No direct script access allowed');

class Prime_model extends CI_Model {

public function get_data($table,$where = false,$fields = false,$join_table = false,$other = false)
  {
  if ($fields != false)
    {
    foreach ($fields as $coll => $value)
      {
      $this->db->select($value);
      }
    }

  $this->db->from($table);

  if($join_table != false)
    {
    if(is_array($other) && array_key_exists('join',$other))
      {
      foreach($join_table as $coll => $value)
        {
        $this->db->join($coll, $value, $other['join']);
        }
      }
    else
      {
      foreach($join_table as $coll => $value)
        {
        $this->db->join($coll, $value);
        }
      }
    }

  if($where != false)
    {
    $this->db->where($where);
    }

  if($other != false)
    {
    if(array_key_exists('or_where', $other))
      {
      $this->db->or_where($other['or_where']);
      }
    if(array_key_exists('order_by', $other))
      {
      $this->db->order_by($other['order_by'], 'desc');
      }
    if(array_key_exists('group_by', $other))
      {
      $this->db->group_by($other['group_by']);
      }
    if(array_key_exists('limit', $other))
      {
      if(array_key_exists('offset', $other))
        {
        $this->db->limit($other['limit'], $other['offset']);
        }
      else
        {
        $this->db->limit($other['limit']);
        }
      }

    if(array_key_exists('like', $other))
      {
      foreach ($other['like'] as $key => $value)
        {
        $this->db->like($key, $value);
        }
      }
    if(array_key_exists('or_like', $other))
      {
      foreach ($other['or_like'] as $key => $value)
        {
        $this->db->or_like($key, $value);
        }
      }
    }
  $query = $this->db->get();

  $result = $query->result_array();

  return $result;
}

public function insert_data($table,$data)
  {
  $this->db->insert($table,$data);
  
  return $this->db->insert_id();
}

public function update_data($table,$data = false,$where = false)
  {
  $this->db->update($table,$data,$where);

  return $this->db->affected_rows();
}

public function delete_data($table, $where)
  {
  $this->db->where($where);
  $this->db->delete($table);
  
  return $this->db->affected_rows();
}

public function count_all($tbl)
  {
  return $this->db->count_all($tbl);
}

public function all_query($sql)
  {
  return $result = $this->db->query($sql)->result_array();
}

public function get_category_data($id)
  {
  $query = $this->db->select('*')
                ->from('categories')
                ->where('catid',$id)
                ->get()
                ->row();
  return $query;
}

public function get_unit_data($id)
  {
  $query = $this->db->select('*')
                ->from('sma_units')
                ->where('untid',$id)
                ->get()
                ->row();
  return $query;
}

public function get_cost_type_data($id)
  {
  $query = $this->db->select("*")
                  ->from('cost_type')
                  ->where('ctid',$id)
                  ->get()
                  ->row();

  return $query;  
}

public function get_customer_data($id)
  {
  $query = $this->db->select('*')
                  ->from('customers')
                  ->where('custid',$id)
                  ->get()
                  ->row();
  return $query; 
}

public function company_details()
  {
  $query = $this->db->select('*')
              ->from('com_profile')
              ->where('com_pid',1)
              ->get()
              ->row();
  return $query;  
}

public function get_stock_data($id)
  {
  $query = $this->db->select('*')
                ->from('stock')
                ->where('pid',$id)
                ->get()
                ->row();
  return $query;
}

public function get_product_stock_data()
  {
  $stock = $this->db->select('pid')
                ->from('products')
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $pdid = array_map (function($value){
  return $value['pid'];
  },$stock);
    //var_dump($emp_id); exit();
  if($pdid)
    {
    $pid = $pdid;
    }
  else
    {
    $pid = 0;
    }
      
  $query = $this->db->select('
                        stock.*,
                        products.*')
                ->from('stock')
                ->join('products','products.pid = stock.pid','left')
                ->where_in('stock.pid',$pid)
                ->where('tquantity > 0')
                ->get()
                ->result();
  return $query;  
}

public function get_product_sstock_data($catid)
  {
  $emp = $this->db->select('pid')
                ->from('products')
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
  return $value['pid'];
  },$emp);
    //var_dump($emp_id); exit();
  if($emp_id)
    {
    $empid = $emp_id;
    }
  else
    {
    $empid = 0;
    }
      
  $query = $this->db->select('
                        stock.*,
                        products.pName,
                        products.pCode,
                        products.pprice,
                        products.sprice,
                        products.catid,
                        products.untid')
                ->from('stock')
                ->join('products','products.pid = stock.pid','left')
                ->where_in('stock.pid',$empid)
                ->where('catid',$catid)
                ->where('tquantity > 0')
                ->get()
                ->result();
  return $query;  
}

public function get_product_ptype_sstock_data($catid)
  {
  $emp = $this->db->select('pid')
                ->from('products')
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
  return $value['pid'];
  },$emp);
    //var_dump($emp_id); exit();
  if($emp_id)
    {
    $empid = $emp_id;
    }
  else
    {
    $empid = 0;
    }
  
  if($catid == 1)
    {
    $query = $this->db->select('
                        stock.*,
                        products.pName,
                        products.pCode,
                        products.pprice,
                        products.sprice,
                        products.catid,
                        products.untid')
                ->from('stock')
                ->join('products','products.pid = stock.pid','left')
                ->where_in('stock.pid',$empid)
                ->where('tquantity > 0')
                ->get()
                ->result();
    }
  else
    {
    $query = $this->db->select('
                        stock.*,
                        products.pName,
                        products.pCode,
                        products.pprice,
                        products.sprice,
                        products.catid,
                        products.untid')
                ->from('stock')
                ->join('products','products.pid = stock.pid','left')
                ->where_in('stock.pid',$empid)
                ->where('dtquantity > 0')
                ->get()
                ->result();
    }
  return $query;  
}

public function get_supplier_data($id)
  {
  $query = $this->db->select('*')
                  ->from('suppliers')
                  ->where('supid',$id)
                  ->get()
                  ->row();
  return $query; 
}

public function get_purchase_payment($id)
  {
  $payment = $this->db->select('SUM(pAmount) as total')
              ->from('purchase_payment')
              ->where('puid',$id)
              ->get()
              ->row();

  $purchase = $this->db->select('dAmount')
              ->from('purchase')
              ->where('puid',$id)
              ->get()
              ->row();
  if($payment)
    {
    $query = $purchase->dAmount - $payment->total;
    }
  else
    {
    $query = $payment->total;
    }
  return $query; 
}

public function get_purchses_data()
  {
  $query = $this->db->select('
                          purchase.*,
                          suppliers.supCode,
                          suppliers.supName')
                  ->from('purchase')
                  ->join('suppliers','suppliers.supid = purchase.supid','left')
                  ->get()
                  ->result();
  return $query;
}

public function get_dpurchses_data($sdate,$edate,$supplier)
  {
  if($supplier == 'All')
    {
    $query = $this->db->select('
                          purchase.*,
                          suppliers.supCode,
                          suppliers.supName')
                  ->from('purchase')
                  ->join('suppliers','suppliers.supid = purchase.supid','left')
                  ->where('purchase.puDate >=',$sdate)
                  ->where('purchase.puDate <=',$edate)
                  ->get()
                  ->result();
      }
    else
      {
      $query = $this->db->select('
                          purchase.*,
                          suppliers.supCode,
                          suppliers.supName')
                  ->from('purchase')
                  ->join('suppliers','suppliers.supid = purchase.supid','left')
                  ->where('purchase.puDate >=',$sdate)
                  ->where('purchase.puDate <=',$edate)
                  ->where('purchase.supid',$supplier)
                  ->get()
                  ->result();
      }
  return $query;  
}

public function get_mpurchses_data($month,$year,$supplier)
  {
  if($supplier == 'All')
    {
    $query = $this->db->select('
                          purchase.*,
                          suppliers.supCode,
                          suppliers.supName')
                  ->from('purchase')
                  ->join('suppliers','suppliers.supid = purchase.supid','left')
                  ->where('MONTH(puDate)',$month)
                  ->where('YEAR(puDate)',$year)
                  ->get()
                  ->result();
    }
  else
    {
    $query = $this->db->select('
                          purchase.*,
                          suppliers.supCode,
                          suppliers.supName')
                  ->from('purchase')
                  ->join('suppliers','suppliers.supid = purchase.supid','left')
                  ->where('MONTH(puDate)',$month)
                  ->where('YEAR(puDate)',$year)
                  ->where('purchase.supid',$supplier)
                  ->get()
                  ->result();
    }
  return $query;  
}

public function get_ypurchses_data($year,$supplier)
  {
  if($supplier == 'All')
    {
    $query = $this->db->select('
                          purchase.*,
                          suppliers.supCode,
                          suppliers.supName')
                  ->from('purchase')
                  ->join('suppliers','suppliers.supid = purchase.supid','left')
                  ->where('YEAR(puDate)',$year)
                  ->get()
                  ->result();
    }
  else
    {
    $query = $this->db->select('
                          purchase.*,
                          suppliers.supCode,
                          suppliers.supName')
                  ->from('purchase')
                  ->join('suppliers','suppliers.supid = purchase.supid','left')
                  ->where('YEAR(puDate)',$year)
                  ->where('purchase.supid',$supplier)
                  ->get()
                  ->result();
    }
  return $query;  
}

public function get_sales_payment($id)
  {
  $payment = $this->db->select('SUM(pAmount) as total')
                  ->from('sales_payment')
                  ->where('said',$id)
                  ->get()
                  ->row();

  $purchase = $this->db->select('dAmount')
                  ->from('sales')
                  ->where('said',$id)
                  ->get()
                  ->row();
  if($payment)
    {
    $query = $purchase->dAmount - $payment->total;
    }
  else
    {
    $query = $payment->total;
    }
  return $query; 
}

public function get_sales_data()
  {
  $query = $this->db->select('
                          sales.*,
                          customers.custCode,
                          customers.custName,
                          customers.custMobile,
                          users.name,
                          users.mobile')
                  ->from('sales')
                  ->join('customers','customers.custid = sales.custid','left')
                  ->join('users','users.uid = sales.regby','left')
                  ->get()
                  ->result();
  return $query;  
}

public function get_dsales_data($sdate,$edate,$customer,$employee)
  {
  if($customer == 'All' && $employee == 'All')
    {
    $query = $this->db->select('
                          sales.*,
                          customers.custCode,
                          customers.custName,
                          customers.custMobile,
                          users.name,
                          users.mobile')
                    ->from('sales')
                    ->join('customers','customers.custid = sales.custid','left')
                    ->join('users','users.uid = sales.regby','left')
                    ->where('sales.saDate >=',$sdate)
                    ->where('sales.saDate <=',$edate)
                    ->get()
                    ->result();
    }
  else if($customer == 'All')
    {
    $query = $this->db->select('
                          sales.*,
                          customers.custCode,
                          customers.custName,
                          customers.custMobile,
                          users.name,
                          users.mobile')
                    ->from('sales')
                    ->join('customers','customers.custid = sales.custid','left')
                    ->join('users','users.uid = sales.regby','left')
                    ->where('sales.saDate >=',$sdate)
                    ->where('sales.saDate <=',$edate)
                    ->where('sales.regby',$employee)
                    ->get()
                    ->result();
    }
  else if($employee == 'All')
    {
    $query = $this->db->select('
                          sales.*,
                          customers.custCode,
                          customers.custName,
                          customers.custMobile,
                          users.name,
                          users.mobile')
                    ->from('sales')
                    ->join('customers','customers.custid = sales.custid','left')
                    ->join('users','users.uid = sales.regby','left')
                    ->where('sales.saDate >=',$sdate)
                    ->where('sales.saDate <=',$edate)
                    ->where('sales.custid',$customer)
                    ->get()
                    ->result();
    }
  else
    {
    $query = $this->db->select('
                          sales.*,
                          customers.custCode,
                          customers.custName,
                          customers.custMobile,
                          users.name,
                          users.mobile')
                    ->from('sales')
                    ->join('customers','customers.custid = sales.custid','left')
                    ->join('users','users.uid = sales.regby','left')
                    ->where('sales.saDate >=',$sdate)
                    ->where('sales.saDate <=',$edate)
                    ->where('sales.custid',$customer)
                    ->where('sales.regby',$employee)
                    ->get()
                    ->result();
    }
  return $query;  
}

public function get_msales_data($month,$year,$customer,$employee)
  {
  if($customer == 'All' && $employee == 'All')
    {
    $query = $this->db->select('
                          sales.*,
                          customers.custCode,
                          customers.custName,
                          customers.custMobile,
                          users.name,
                          users.mobile')
                    ->from('sales')
                    ->join('customers','customers.custid = sales.custid','left')
                    ->join('users','users.uid = sales.regby','left')
                    ->where('MONTH(sales.saDate)',$month)
                    ->where('YEAR(sales.saDate)',$year)
                    ->get()
                    ->result();
    }
  else if ($customer == 'All')
    {
    $query = $this->db->select('
                          sales.*,
                          customers.custCode,
                          customers.custName,
                          customers.custMobile,
                          users.name,
                          users.mobile')
                    ->from('sales')
                    ->join('customers','customers.custid = sales.custid','left')
                    ->join('users','users.uid = sales.regby','left')
                    ->where('MONTH(sales.saDate)',$month)
                    ->where('YEAR(sales.saDate)',$year)
                    ->where('sales.regby',$employee)
                    ->get()
                    ->result();
    }
  else if ($employee == 'All')
    {
    $query = $this->db->select('
                          sales.*,
                          customers.custCode,
                          customers.custName,
                          customers.custMobile,
                          users.name,
                          users.mobile')
                    ->from('sales')
                    ->join('customers','customers.custid = sales.custid','left')
                    ->join('users','users.uid = sales.regby','left')
                    ->where('MONTH(sales.saDate)',$month)
                    ->where('YEAR(sales.saDate)',$year)
                    ->where('sales.custid',$customer)
                    ->get()
                    ->result();
    }
  else
    {
    $query = $this->db->select('
                          sales.*,
                          customers.custCode,
                          customers.custName,
                          customers.custMobile,
                          users.name,
                          users.mobile')
                    ->from('sales')
                    ->join('customers','customers.custid = sales.custid','left')
                    ->join('users','users.uid = sales.regby','left')
                    ->where('MONTH(sales.saDate)',$month)
                    ->where('YEAR(sales.saDate)',$year)
                    ->where('sales.custid',$customer)
                    ->where('sales.regby',$employee)
                    ->get()
                    ->result();
    }
  return $query;  
}

public function get_ysales_data($year,$customer,$employee)
  {
  if($customer == 'All' && $employee == 'All')
    {
    $query = $this->db->select('
                          sales.*,
                          customers.custCode,
                          customers.custName,
                          customers.custMobile,
                          users.name,
                          users.mobile')
                    ->from('sales')
                    ->join('customers','customers.custid = sales.custid','left')
                    ->join('users','users.uid = sales.regby','left')
                    ->where('YEAR(sales.saDate)',$year)
                    ->get()
                    ->result();
    }
  else if ($customer == 'All')
    {
    $query = $this->db->select('
                          sales.*,
                          customers.custCode,
                          customers.custName,
                          customers.custMobile,
                          users.name,
                          users.mobile')
                    ->from('sales')
                    ->join('customers','customers.custid = sales.custid','left')
                    ->join('users','users.uid = sales.regby','left')
                    ->where('YEAR(sales.saDate)',$year)
                    ->where('sales.regby',$employee)
                    ->get()
                    ->result();
    }
  else if ($employee == 'All')
    {
    $query = $this->db->select('
                          sales.*,
                          customers.custCode,
                          customers.custName,
                          customers.custMobile,
                          users.name,
                          users.mobile')
                    ->from('sales')
                    ->join('customers','customers.custid = sales.custid','left')
                    ->join('users','users.uid = sales.regby','left')
                    ->where('YEAR(sales.saDate)',$year)
                    ->where('sales.custid',$customer)
                    ->get()
                    ->result();
    }
  else
    {
    $query = $this->db->select('
                          sales.*,
                          customers.custCode,
                          customers.custName,
                          customers.custMobile,
                          users.name,
                          users.mobile')
                    ->from('sales')
                    ->join('customers','customers.custid = sales.custid','left')
                    ->join('users','users.uid = sales.regby','left')
                    ->where('YEAR(sales.saDate)',$year)
                    ->where('sales.custid',$customer)
                    ->where('sales.regby',$employee)
                    ->get()
                    ->result();
    }
  return $query;  
}

public function today_sales_amount()
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('sales')
                  ->where('saDate',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function today_purchases_amount()
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('purchase')
                  ->where('puDate',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function today_cvoucher_amount()
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Credit Voucher')
                  ->where('vuDate',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function today_dvoucher_amount()
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Debit Voucher')
                  ->where('vuDate',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function today_svoucher_amount()
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Supplier Pay')
                  ->where('vuDate',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function today_emp_payments_amount()
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`salary`) as total")
                  ->FROM('employee_payment')
                  ->where('DATE(regdate)',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function today_returns_amount()
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('returns')
                  ->where('rDate',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function today_due_payment()
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('sales_payment')
                  ->where('DATE(regdate)',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function today_bank_withdraw()
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`amount`) as total")
                  ->FROM('transfer_account')
                  ->where('facType','Bank')
                  ->where('DATE(regdate)',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function today_bank_transfer()
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`amount`) as total")
                  ->FROM('transfer_account')
                  ->where('sacType','Bank')
                  ->where('DATE(regdate)',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function total_sale()
  {
  $query = $this->db->select("SUM(tAmount) as total")
                  ->FROM('sales')
                  ->get()
                  ->row();
  return $query;  
}

public function graph_data_point()
  {
  $date_arr = $this->getLastNDays(7, 'Y-m-d');
  $dataPoints = array();

  for($i = 0; $i < 7; $i++)
    {
    array_push($dataPoints, array("y" => $this->get_today_sale(preg_replace('/[^A-Za-z0-9\-]/','',$date_arr[$i])),"label" => preg_replace('/[^A-Za-z0-9\-]/','',$date_arr[$i])));
    }

    return $dataPoints;
}

public function get_today_sale($date)
  {
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('sales')
                  ->where('saDate',$date)
                  ->get()
                  ->row();
    
  if($query->total)
    {
    return $query->total;
    }
  else
    {
    $dt = 0;
    return $dt;
    }
}

public function getLastNDays($days, $format = 'd-m')
  {
  $m = date("m"); $de= date("d"); $y= date("Y");
  $dateArray = array();
  for($i=0; $i<=$days-1; $i++)
    {
    $dateArray[] = '"'.date($format, mktime(0,0,0,$m,($de-$i),$y)).'"'; 
    }
  return array_reverse($dateArray);
}

public function get_dept_data($id)
  {
  $query = $this->db->select('*')
                  ->from('department')
                  ->where('dptid',$id)
                  ->get()
                  ->row();
  return $query; 
}
public function get_courier_data($id)
  {
  $query = $this->db->select('*')
                  ->from('courier')
                  ->where('id',$id)
                  ->get()
                  ->row();
  return $query; 
}
public function get_emp_data($id)
  {
  $query = $this->db->select('*')
                  ->from('employees')
                  ->where('empid',$id)
                  ->get()
                  ->row();
  return $query; 
}

public function sales_adata()
  {
  $query = $this->db->select('*')
                  ->from('sales')
                  ->get()
                  ->result();
  return $query;  
}

public function sales_ddata($sdate,$edate)
  {
  $query = $this->db->select('*')
                    ->from('sales')
                    ->where('saDate >=',$sdate)
                    ->where('saDate <=',$edate)
                    ->get()
                    ->result();
  return $query;  
}

public function sales_mdata($month,$year)
  {
  $query = $this->db->select('*')
                    ->from('sales')
                    ->where('MONTH(saDate)',$month)
                    ->where('YEAR(saDate)',$year)
                    ->get()
                    ->result();
  return $query;  
}

public function sales_ydata($year)
  {
  $query = $this->db->select('*')
                    ->from('sales')
                    ->where('YEAR(saDate)',$year)
                    ->get()
                    ->result();

  return $query;  
}

public function sales_due_adata()
  {
  $query = $this->db->select('
                        sales.*,
                        customers.custName,
                        customers.custMobile,
                        courier.courierName')
                  ->from('sales')
                  ->join('customers','customers.custid = sales.custid','left')
                  ->join('courier','courier.id = sales.id','left')
                  //->where('tAmount > pAmount')
                  ->get()
                  ->result();
  return $query;  
}

public function sales_due_ddata($sdate,$edate)
  {
  $query = $this->db->select('
                        sales.*,
                        customers.custName,
                        customers.custMobile,
                        courier.courierName')
                  ->from('sales')
                  ->join('customers','customers.custid = sales.custid','left')
                  ->join('courier','courier.id = sales.id','left')
                  ->where('tAmount > pAmount')
                  ->where('saDate >=',$sdate)
                  ->where('saDate <=',$edate)
                  ->get()
                  ->result();
  return $query;  
}

public function sales_due_mdata($month,$year)
  {
  $query = $this->db->select('
                        sales.*,
                        customers.custName,
                        customers.custMobile,
                        courier.courierName')
                  ->from('sales')
                  ->join('customers','customers.custid = sales.custid','left')
                  ->join('courier','courier.id = sales.id','left')
                  ->where('tAmount > pAmount')
                  ->where('MONTH(saDate)',$month)
                  ->where('YEAR(saDate)',$year)
                  ->get()
                  ->result();
  return $query;  
}

public function sales_due_ydata($year)
  {
  $query = $this->db->select('
                        sales.*,
                        customers.custName,
                        customers.custMobile,
                        courier.courierName')
                  ->from('sales')
                  ->join('customers','customers.custid = sales.custid','left')
                  ->join('courier','courier.id = sales.id','left')
                  ->where('tAmount > pAmount')
                  ->where('YEAR(saDate)',$year)
                  ->get()
                  ->result();

  return $query;  
}

public function sales_due_payment_data()
  {
  $query = $this->db->select('
                        sales_payment.*,
                        sales.invoice,
                        customers.custName')
                ->from('sales_payment')
                ->join('sales','sales.said = sales_payment.said')
                ->join('customers','customers.custid = sales.custid','left')
                ->get()
                ->result();
  return $query; 
}

public function sales_due_dpayment_data($sdate,$edate)
  {
  $query = $this->db->select('
                        sales_payment.*,
                        sales.invoice,
                        customers.custName')
                ->from('sales_payment')
                ->join('sales','sales.said = sales_payment.said')
                ->join('customers','customers.custid = sales.custid','left')
                ->where('DATE(sales_payment.regdate) >=',$sdate)
                ->where('DATE(sales_payment.regdate) <=',$edate)
                ->get()
                ->result();
  return $query; 
}

public function sales_due_mpayment_data($month,$year)
  {
  $query = $this->db->select('
                        sales_payment.*,
                        sales.invoice,
                        customers.custName')
                ->from('sales_payment')
                ->join('sales','sales.said = sales_payment.said')
                ->join('customers','customers.custid = sales.custid','left')
                ->where('MONTH(sales_payment.regdate)',$month)
                ->where('YEAR(sales_payment.regdate)',$year)
                ->get()
                ->result();
  return $query; 
}

public function sales_due_ypayment_data($year)
  {
  $query = $this->db->select('
                        sales_payment.*,
                        sales.invoice,
                        customers.custName')
                ->from('sales_payment')
                ->join('sales','sales.said = sales_payment.said')
                ->join('customers','customers.custid = sales.custid','left')
                ->where('YEAR(sales_payment.regdate)',$year)
                ->get()
                ->result();
  return $query; 
}

public function get_voucher_data()
  {
  $query = $this->db->select("*")
                  ->from('vaucher')
                  //->where('vauchertype','Credit Voucher')
                  ->get()
                  ->result();
  return $query;  
}

public function get_dall_voucher_data($sdate,$edate,$vtype)
  {
  if($vtype == 'All')
    {
    $query = $this->db->select("*")
                    ->from('vaucher')
                    ->where('vuDate >=', $sdate)
                    ->where('vuDate <=', $edate)
                    //->where('vauchertype','Credit Voucher')
                    ->get()
                    ->result();
    }
  else
    {
    $query = $this->db->select("*")
                    ->from('vaucher')
                    ->where('vuDate >=', $sdate)
                    ->where('vuDate <=', $edate)
                    ->where('vauchertype',$vtype)
                    //->where('vauchertype','Credit Voucher')
                    ->get()
                    ->result();
    }
  return $query;  
}

public function get_mall_voucher_data($month,$year,$vtype)
  {
  if($vtype == 'All')
    {
    $query = $this->db->select("*")
                    ->from('vaucher')
                    ->where('MONTH(vuDate)',$month)
                    ->where('YEAR(vuDate)',$year)
                    //->where('vauchertype','Credit Voucher')
                    ->get()
                    ->result();
    }
  else
    {
    $query = $this->db->select("*")
                    ->from('vaucher')
                    ->where('MONTH(vuDate)',$month)
                    ->where('YEAR(vuDate)',$year)
                    ->where('vauchertype',$vtype)
                    //->where('vauchertype','Credit Voucher')
                    ->get()
                    ->result();
    }
  return $query;  
}

public function get_yall_voucher_data($year,$vtype)
  {
  if($vtype == 'All')
    {
    $query = $this->db->select("*")
                    ->from('vaucher')
                    ->where('YEAR(vuDate)',$year)
                    //->where('vauchertype','Credit Voucher')
                    ->get()
                    ->result();
    }
  else
    {
    $query = $this->db->select("*")
                    ->from('vaucher')
                    ->where('YEAR(vuDate)',$year)
                    ->where('vauchertype',$vtype)
                    //->where('vauchertype','Credit Voucher')
                    ->get()
                    ->result();
    }
  return $query;  
}

public function total_sales_amount()
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('sales')
                  ->get()
                  ->row();
  return $query;  
}

public function total_sales_payment_amount()
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('sales_payment')
                  ->get()
                  ->row();
  return $query;  
}

public function total_purchases_amount()
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('purchase')
                  ->get()
                  ->row();
  return $query;  
}

public function total_purchases_product_amount()
  {
  $query = $this->db->select('sale_product.quantity,sales.saDate,sales.invoice,costing.tamount')
            ->from('sale_product')
            ->join('sales','sales.said = sale_product.said','left')
            ->join('costing','costing.pid = sale_product.pid','left')
            ->get()
            ->result();
  return $query;
}

public function total_emp_payments_amount()
  {
  $query = $this->db->select("SUM(`salary`) as total")
                  ->FROM('employee_payment')
                  ->get()
                  ->row();
  return $query;  
}

public function total_returns_amount()
  {
  $emp = $this->db->select('invoice')
                ->from('sales')
                ->where('pAmount >', 0)
                ->get()
                ->result_array();
        //var_dump($emp); exit();
  $emp_id = array_map(function ($value){
    return $value['invoice'];
    }, $emp);

  if($emp_id)
    {
    $invoice = $emp_id;
    }
  else
    {
    $invoice = 0;
    }
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('returns')
                  //->where('invoice', $invoice)
                  ->get()
                  ->row();
  return $query;  
}

public function total_preturns_amount()
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('preturns')
                  ->get()
                  ->row();
  return $query;  
}

public function total_cvoucher_amount()
  {
  $query = $this->db->select("SUM(tAmount) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Credit Voucher')
                  ->get()
                  ->row();
  return $query;  
}

public function total_dvoucher_amount()
  {
  $query = $this->db->select("SUM(tAmount) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Debit Voucher')
                  ->get()
                  ->row();
  return $query;  
}

public function total_svoucher_amount()
  {
  $query = $this->db->select("SUM(tAmount) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Supplier Pay')
                  ->get()
                  ->row();
  return $query;  
}

public function total_dsales_amount($sdate,$edate)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('sales')
                  ->where('sales.saDate >=', $sdate)
                  ->where('sales.saDate <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function total_dsales_payment_amount($sdate,$edate)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('sales_payment')
                  ->where('DATE(regdate) >=', $sdate)
                  ->where('DATE(regdate) <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function total_dpurchases_product_amount($sdate,$edate)
  {
  $query = $this->db->select('sale_product.quantity,sales.saDate,costing.tamount')
                    ->from('sale_product')
                    ->join('sales','sales.said = sale_product.said','left')
                    ->join('costing','costing.pid = sale_product.pid','left')
                    ->where('sales.saDate >=', $sdate)
                    ->where('sales.saDate <=', $edate)
                    ->get()
                    ->result();
  return $query;  
}

public function total_demp_payments_amount($sdate,$edate)
  {
  $query = $this->db->select("SUM(`salary`) as total")
                  ->FROM('employee_payment')
                  ->where('regdate >=', $sdate)
                  ->where('regdate <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function total_dreturns_amount($sdate,$edate)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('returns')
                  ->where('rDate >=', $sdate)
                  ->where('rDate <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function total_dpreturns_amount($sdate,$edate)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('preturns')
                  ->where('prDate >=', $sdate)
                  ->where('prDate <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function total_dcvoucher_amount($sdate,$edate)
  {
  $query = $this->db->select("SUM(tAmount) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Credit Voucher')
                  ->where('vuDate >=', $sdate)
                  ->where('vuDate <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function total_ddvoucher_amount($sdate,$edate)
  {
  $query = $this->db->select("SUM(tAmount) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Debit Voucher')
                  ->where('vuDate >=', $sdate)
                  ->where('vuDate <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function total_dsvoucher_amount($sdate,$edate)
  {
  $query = $this->db->select("SUM(tAmount) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Supplier Pay')
                  ->where('vuDate >=', $sdate)
                  ->where('vuDate <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function total_msales_amount($month,$year)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('sales')
                  ->where('MONTH(saDate)',$month)
                  ->where('YEAR(saDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function total_msales_payment_amount($month,$year)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('sales_payment')
                  ->where('MONTH(regdate)',$month)
                  ->where('YEAR(regdate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function total_mpurchases_product_amount($month,$year)
  {
  $query = $this->db->select('sale_product.quantity,sales.saDate,costing.tamount')
                    ->from('sale_product')
                    ->join('sales','sales.said = sale_product.said','left')
                    ->join('costing','costing.pid = sale_product.pid','left')
                    ->where('MONTH(saDate)',$month)
                    ->where('YEAR(saDate)',$year)
                    ->get()
                    ->result();
  return $query;  
}

public function total_memp_payments_amount($month,$year)
  {
  $query = $this->db->select("SUM(`salary`) as total")
                  ->FROM('employee_payment')
                  ->where('MONTH(regdate)',$month)
                  ->where('YEAR(regdate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function total_mreturns_amount($month,$year)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('returns')
                  ->where('MONTH(rDate)',$month)
                  ->where('YEAR(rDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function total_mpreturns_amount($month,$year)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('preturns')
                  ->where('MONTH(prDate)',$month)
                  ->where('YEAR(prDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function total_mcvoucher_amount($month,$year)
  {
  $query = $this->db->select("SUM(tAmount) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Credit Voucher')
                  ->where('MONTH(vuDate)',$month)
                  ->where('YEAR(vuDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function total_mdvoucher_amount($month,$year)
  {
  $query = $this->db->select("SUM(tAmount) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Debit Voucher')
                  ->where('MONTH(vuDate)',$month)
                  ->where('YEAR(vuDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function total_msvoucher_amount($month,$year)
  {
  $query = $this->db->select("SUM(tAmount) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Supplier Pay')
                  ->where('MONTH(vuDate)',$month)
                  ->where('YEAR(vuDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function total_ysales_amount($year)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('sales')
                  ->where('YEAR(saDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function total_ysales_payment_amount($year)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('sales_payment')
                  ->where('YEAR(regdate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function total_ypurchases_product_amount($year)
  {
  $query = $this->db->select('sale_product.quantity,sales.saDate,costing.tamount')
                    ->from('sale_product')
                    ->join('sales','sales.said = sale_product.said','left')
                    ->join('costing','costing.pid = sale_product.pid','left')
                    ->where('YEAR(saDate)',$year)
                    ->get()
                    ->result();
  return $query;  
}

public function total_yemp_payments_amount($year)
  {
  $query = $this->db->select("SUM(`salary`) as total")
                  ->FROM('employee_payment')
                  ->where('YEAR(regdate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function total_yreturns_amount($year)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('returns')
                  ->where('YEAR(rDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function total_ypreturns_amount($year)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('preturns')
                  ->where('YEAR(prDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function total_ycvoucher_amount($year)
  {
  $query = $this->db->select("SUM(tAmount) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Credit Voucher')
                  ->where('YEAR(vuDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function total_ydvoucher_amount($year)
  {
  $query = $this->db->select("SUM(tAmount) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Debit Voucher')
                  ->where('YEAR(vuDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function total_ysvoucher_amount($year)
  {
  $query = $this->db->select("SUM(tAmount) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Supplier Pay')
                  ->where('YEAR(vuDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function total_sales_product()
  {
  $query = $query = $this->db->select("
                        sale_product.quantity as tq,
                        sale_product.sprice,
                        sale_product.tprice as ta,
                        sale_product.pid,
                        sale_product.tsQnt,
                        sale_product.spChassis,
                        sale_product.spColor,
                        sale_product.spType,
                        sales.compid,
                        sales.saDate,
                        sales.invoice")
                    ->from('sale_product')
                    ->join('sales','sales.said = sale_product.said','left')
                    //->group_by('pid')
                    ->get()
                    ->result();
  return $query;
}

public function total_dsales_product($sdate,$edate)
  {
  $query = $query = $this->db->select("
                        sale_product.quantity as tq,
                        sale_product.sprice,
                        sale_product.tprice as ta,
                        sale_product.pid,
                        sale_product.tsQnt,
                        sale_product.spChassis,
                        sale_product.spColor,
                        sale_product.spType,
                        sales.compid,
                        sales.saDate,
                        sales.invoice")
                    ->from('sale_product')
                    ->join('sales','sales.said = sale_product.said','left')
                    //->group_by('pid')
                    ->where('saDate >=', $sdate)
                    ->where('saDate <=', $edate)
                    ->get()
                    ->result();
  return $query;
}

public function total_msales_product($month,$year)
  {
  $query = $query = $this->db->select("
                        sale_product.quantity as tq,
                        sale_product.sprice,
                        sale_product.tprice as ta,
                        sale_product.pid,
                        sale_product.tsQnt,
                        sale_product.spChassis,
                        sale_product.spColor,
                        sale_product.spType,
                        sales.compid,
                        sales.saDate,
                        sales.invoice")
                    ->from('sale_product')
                    ->join('sales','sales.said = sale_product.said','left')
                    //->group_by('pid')
                    ->where('MONTH(saDate)',$month)
                    ->where('YEAR(saDate)',$year)
                    ->get()
                    ->result();
  return $query;
}

public function total_ysales_product($year)
  {
  $query = $this->db->select("
                        sale_product.quantity as tq,
                        sale_product.sprice,
                        sale_product.tprice as ta,
                        sale_product.pid,
                        sale_product.tsQnt,
                        sale_product.spChassis,
                        sale_product.spColor,
                        sale_product.spType,
                        sales.compid,
                        sales.saDate,
                        sales.invoice")
                    ->from('sale_product')
                    ->join('sales','sales.said = sale_product.said','left')
                    //->group_by('pid')
                    ->where('YEAR(saDate)',$year)
                    ->get()
                    ->result();
  return $query;
}

public function total_purchase()
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('purchase')
                  ->get()
                  ->row();
  return $query;  
}
public function total_service_sale()
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('services_sale')
                  ->get()
                  ->row();
  return $query;  
}

public function total_customer()
  {
  $query = $this->db->select('*')
                ->from('customers')
                ->get();

  $count_row = $query->num_rows();

  return $count_row;
}

public function total_supplier()
  {
  $query = $this->db->select('*')
                ->from('suppliers')
                ->get();

  $count_row = $query->num_rows();

  return $count_row;
}

public function total_stock()
  {
  $query = $this->db->select('SUM(`tquantity`) as total')
                ->from('stock')
                ->get()
                ->row();
  return $query;
}

public function total_voucher()
  {
  $query = $this->db->select("SUM(`tAmount`) as total")
                    ->FROM('vaucher')
                    ->get()
                    ->row();
  return $query;  
}

public function pre_sales_amount()
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('sales')
                  ->where('saDate <',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function pre_purchases_amount()
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('purchase')
                  ->where('puDate <',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function pre_emp_payments_amount()
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`salary`) as total")
                  ->FROM('employee_payment')
                  ->where('DATE(regdate) <',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function pre_returns_amount()
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('returns')
                  ->where('rDate <',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function pre_cvoucher_amount()
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Credit Voucher')
                  ->where('vuDate <',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function pre_dvoucher_amount()
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Debit Voucher')
                  ->where('vuDate <',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function pre_svoucher_amount()
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Supplier Pay')
                  ->where('vuDate <',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function cash_sales_amount()
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('sales')
                  ->where('accountType','Cash')
                  ->get()
                  ->row();
  return $query;  
}

public function cash_purchases_amount()
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('purchase')
                  ->where('accountType','Cash')
                  ->get()
                  ->row();
  return $query;  
}

public function cash_cvoucher_amount()
  {
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Credit Voucher')
                  ->where('accountType','Cash')
                  ->get()
                  ->row();
  return $query;  
}

public function cash_dvoucher_amount()
  {
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Debit Voucher')
                  ->where('accountType','Cash')
                  ->get()
                  ->row();
  return $query;  
}

public function cash_svoucher_amount()
  {
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Supplier Pay')
                  ->where('accountType','Cash')
                  ->get()
                  ->row();
  return $query;  
}

public function cash_emp_payments_amount()
  {
  $query = $this->db->select("SUM(`salary`) as total")
                  ->FROM('employee_payment')
                  ->where('accountType','Cash')
                  ->get()
                  ->row();
  return $query;  
}

public function cash_returns_amount()
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('returns')
                  ->where('accountType','Cash')
                  ->get()
                  ->row();
  return $query;  
}

public function bank_sales_amount()
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('sales')
                  ->where('accountType','Bank')
                  ->get()
                  ->row();
  return $query;  
}

public function bank_purchases_amount()
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('purchase')
                  ->where('accountType','Bank')
                  ->get()
                  ->row();
  return $query;  
}

public function bank_cvoucher_amount()
  {
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Credit Voucher')
                  ->where('accountType','Bank')
                  ->get()
                  ->row();
  return $query;  
}

public function bank_dvoucher_amount()
  {
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Debit Voucher')
                  ->where('accountType','Bank')
                  ->get()
                  ->row();
  return $query;  
}

public function bank_svoucher_amount()
  {
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Supplier Pay')
                  ->where('accountType','Bank')
                  ->get()
                  ->row();
  return $query;  
}

public function bank_emp_payments_amount()
  {
  $query = $this->db->select("SUM(`salary`) as total")
                  ->FROM('employee_payment')
                  ->where('accountType','Bank')
                  ->get()
                  ->row();
  return $query;  
}

public function bank_returns_amount()
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('returns')
                  ->where('accountType','Bank')
                  ->get()
                  ->row();
  return $query;  
}

public function mobile_sales_amount()
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('sales')
                  ->where('accountType','Mobile')
                  ->get()
                  ->row();
  return $query;  
}

public function mobile_purchases_amount()
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('purchase')
                  ->where('accountType','Mobile')
                  ->get()
                  ->row();
  return $query;  
}

public function mobile_cvoucher_amount()
  {
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Credit Voucher')
                  ->where('accountType','Mobile')
                  ->get()
                  ->row();
  return $query;  
}

public function mobile_dvoucher_amount()
  {
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Debit Voucher')
                  ->where('accountType','Mobile')
                  ->get()
                  ->row();
  return $query;  
}

public function mobile_svoucher_amount()
  {
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->WHERE('vauchertype','Supplier Pay')
                  ->where('accountType','Mobile')
                  ->get()
                  ->row();
  return $query;  
}

public function mobile_emp_payments_amount()
  {
  $query = $this->db->select("SUM(`salary`) as total")
                  ->FROM('employee_payment')
                  ->where('accountType','Mobile')
                  ->get()
                  ->row();
  return $query;  
}

public function mobile_returns_amount()
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('returns')
                  ->where('accountType','Mobile')
                  ->get()
                  ->row();
  return $query;  
}

public function total_cash_account()
  {
  $query = $this->db->select('*')
                ->from('cash')
                ->get();

  $count_row = $query->num_rows();

  return $count_row;
}

public function total_bank_account()
  {
  $query = $this->db->select('*')
                ->from('bankaccount')
                ->get();

  $count_row = $query->num_rows();

  return $count_row;
}

public function total_mobile_account()
  {
  $query = $this->db->select('*')
                ->from('mobileaccount')
                ->get();

  $count_row = $query->num_rows();

  return $count_row;
}

public function get_cash_account($id)
  {
  $query = $this->db->select('*')
                ->from('cash')
                ->where('ca_id',$id)
                ->get()
                ->row();
  return $query;
}

public function get_bank_account($id)
  {
  $query = $this->db->select('*')
                ->from('bankaccount')
                ->where('ba_id',$id)
                ->get()
                ->row();
  return $query;
}

public function get_mobile_transaction($id)
  {
  $query = $this->db->select('*')
                ->from('mobileaccount')
                ->where('ma_id',$id)
                ->get()
                ->row();
  return $query;
}

public function get_cost_report_data()
  {
  $query = $this->db->select('vaucher.*,cost_type.costName')
                ->from('vaucher')
                ->join('cost_type','cost_type.ctid = vaucher.costType','left')
                // ->join('vaucher_particular','vaucher_particular.vuid = vaucher.vuid','left')
                ->where('vaucher.vauchertype','Debit Voucher')
                ->get()
                ->result();
  return $query; 
}

public function get_dcost_report_data($sdate,$edate,$vtype)
  {
  $query = $this->db->select('vaucher.*,cost_type.costName')
                ->from('vaucher')
                ->join('cost_type','cost_type.ctid = vaucher.costType','left')
                ->where('vaucher.vauchertype','Debit Voucher')
                ->where('DATE(vuDate) >=',$sdate)
                ->where('DATE(vuDate) <=',$edate)
                ->where('vaucher.costType',$vtype)
                ->get()
                ->result();
  return $query; 
}

public function get_mcost_report_data($month,$year,$vtype)
  {
  $query = $this->db->select('vaucher.*,cost_type.costName')
                ->from('vaucher')
                ->join('cost_type','cost_type.ctid = vaucher.costType','left')
                ->where('vaucher.vauchertype','Debit Voucher')
                ->where('MONTH(vaucher.vuDate)',$month)
                ->where('YEAR(vaucher.vuDate)',$year)
                ->where('vaucher.costType',$vtype)
                ->get()
                ->result();
  return $query; 
}

public function get_ycost_report_data($year,$vtype)
  {
  $query = $this->db->select('vaucher.*,cost_type.costName')
                ->from('vaucher')
                ->join('cost_type','cost_type.ctid = vaucher.costType','left')
                ->where('vaucher.vauchertype','Debit Voucher')
                ->where('YEAR(vaucher.vuDate)',$year)
                ->where('vaucher.costType',$vtype)
                ->get()
                ->result();
  return $query; 
}

public function get_bank_purchase_data()
  {
  $query = $this->db->select('*')
                    ->from('purchase')
                    ->where('accountType','Bank')
                    ->get()
                    ->result();

  return $query;  
}

public function get_bank_sale_data()
  {
  $query = $this->db->select('*')
                    ->from('sales')
                    ->where('accountType','Bank')
                    ->get()
                    ->result();

  return $query;  
}

public function get_bank_sreturn_data()
  {
  $query = $this->db->select('*')
                    ->from('returns')
                    ->where('accountType','Bank')
                    ->get()
                    ->result();

  return $query;  
}

public function get_bank_preturn_data()
  {
  $query = $this->db->select('*')
                    ->from('preturns')
                    ->where('accountType','Bank')
                    ->get()
                    ->result();

  return $query;  
}

public function get_bank_voucher_data()
  {
  $query = $this->db->select('*')
                    ->from('vaucher')
                    ->where('accountType','Bank')
                    ->get()
                    ->result();

  return $query;  
}

public function get_bank_dpurchase_data($sdate,$edate)
  {
  $query = $this->db->select('*')
                    ->from('purchase')
                    ->where('accountType','Bank')
                    ->where('puDate >=',$sdate)
                    ->where('puDate <=',$edate)
                    ->get()
                    ->result();

  return $query;  
}

public function get_bank_dsale_data($sdate,$edate)
  {
  $query = $this->db->select('*')
                    ->from('sales')
                    ->where('accountType','Bank')
                    ->where('saDate >=',$sdate)
                    ->where('saDate <=',$edate)
                    ->get()
                    ->result();

  return $query;  
}

public function get_bank_dsreturn_data($sdate,$edate)
  {
  $query = $this->db->select('*')
                    ->from('returns')
                    ->where('accountType','Bank')
                    ->where('rDate >=',$sdate)
                    ->where('rDate <=',$edate)
                    ->get()
                    ->result();

  return $query;  
}

public function get_bank_dpreturn_data($sdate,$edate)
  {
  $query = $this->db->select('*')
                    ->from('preturns')
                    ->where('accountType','Bank')
                    ->where('prDate >=',$sdate)
                    ->where('prDate <=',$edate)
                    ->get()
                    ->result();

  return $query;  
}

public function get_bank_dvoucher_data($sdate,$edate)
  {
  $query = $this->db->select('*')
                    ->from('vaucher')
                    ->where('accountType','Bank')
                    ->where('vuDate >=',$sdate)
                    ->where('vuDate <=',$edate)
                    ->get()
                    ->result();

  return $query;  
}

public function get_bank_mpurchase_data($month,$year)
  {
  $query = $this->db->select('*')
                    ->from('purchase')
                    ->where('accountType','Bank')
                    ->where('MONTH(puDate)',$month)
                    ->where('YEAR(puDate)',$year)
                    ->get()
                    ->result();

  return $query;  
}

public function get_bank_msale_data($month,$year)
  {
  $query = $this->db->select('*')
                    ->from('sales')
                    ->where('accountType','Bank')
                    ->where('MONTH(saDate)',$month)
                    ->where('YEAR(saDate)',$year)
                    ->get()
                    ->result();

  return $query;  
}

public function get_bank_msreturn_data($month,$year)
  {
  $query = $this->db->select('*')
                    ->from('returns')
                    ->where('accountType','Bank')
                    ->where('MONTH(rDate)',$month)
                    ->where('YEAR(rDate)',$year)
                    ->get()
                    ->result();

  return $query;  
}

public function get_bank_mpreturn_data($month,$year)
  {
  $query = $this->db->select('*')
                    ->from('preturns')
                    ->where('accountType','Bank')
                    ->where('MONTH(prDate)',$month)
                    ->where('YEAR(prDate)',$year)
                    ->get()
                    ->result();

  return $query;  
}

public function get_bank_mvoucher_data($month,$year)
  {
  $query = $this->db->select('*')
                    ->from('vaucher')
                    ->where('accountType','Bank')
                    ->where('MONTH(vuDate)',$month)
                    ->where('YEAR(vuDate)',$year)
                    ->get()
                    ->result();

  return $query;  
}

public function get_bank_ypurchase_data($year)
  {
  $query = $this->db->select('*')
                    ->from('purchase')
                    ->where('accountType','Bank')
                    ->where('YEAR(puDate)',$year)
                    ->get()
                    ->result();

  return $query;  
}

public function get_bank_ysale_data($year)
  {
  $query = $this->db->select('*')
                    ->from('sales')
                    ->where('accountType','Bank')
                    ->where('YEAR(saDate)',$year)
                    ->get()
                    ->result();

  return $query;  
}

public function get_bank_ysreturn_data($year)
  {
  $query = $this->db->select('*')
                    ->from('returns')
                    ->where('accountType','Bank')
                    ->where('YEAR(rDate)',$year)
                    ->get()
                    ->result();

  return $query;  
}

public function get_bank_ypreturn_data($year)
  {
  $query = $this->db->select('*')
                    ->from('preturns')
                    ->where('accountType','Bank')
                    ->where('YEAR(prDate)',$year)
                    ->get()
                    ->result();

  return $query;  
}

public function get_bank_yvoucher_data($year)
  {
  $query = $this->db->select('*')
                    ->from('vaucher')
                    ->where('accountType','Bank')
                    ->where('YEAR(vuDate)',$year)
                    ->get()
                    ->result();

  return $query;  
}

public function user_order_ledger()
  {
  $query = $this->db->select('
                        order.*,
                        customers.custName,
                        customers.custMobile,
                        customers.custAddress')
                ->from('order')
                ->join('customers','customers.custid = order.custid','left')
                ->get()
                ->result();
  return $query;  
}

public function user_dorder_ledger($uid,$sdate,$edate)
  {
  if($uid == 'All')
    {
    $query = $this->db->select('
                        order.*,
                        customers.custName,
                        customers.custMobile,
                        customers.custAddress')
                ->from('order')
                ->join('customers','customers.custid = order.custid','left')
                ->where('oDate >=',$sdate)
                ->where('oDate <=',$edate)
                ->get()
                ->result();
      }
    else
      {
      $query = $this->db->select('
                        order.*,
                        customers.custName,
                        customers.custMobile,
                        customers.custAddress')
                ->from('order')
                ->join('customers','customers.custid = order.custid','left')
                ->where('oDate >=',$sdate)
                ->where('oDate <=',$edate)
                ->where('order.regby',$uid)
                ->get()
                ->result();
      }
  return $query;  
}

public function user_morder_ledger($uid,$month,$year)
  {
  if($uid == 'All')
    {
    $query = $this->db->select('
                        order.*,
                        customers.custName,
                        customers.custMobile,
                        customers.custAddress')
                ->from('order')
                ->join('customers','customers.custid = order.custid','left')
                ->where('MONTH(oDate)',$month)
                ->where('YEAR(oDate)',$year)
                ->get()
                ->result();
      }
    else
      {
      $query = $this->db->select('
                        order.*,
                        customers.custName,
                        customers.custMobile,
                        customers.custAddress')
                ->from('order')
                ->join('customers','customers.custid = order.custid','left')
                ->where('MONTH(oDate)',$month)
                ->where('YEAR(oDate)',$year)
                ->where('order.regby',$uid)
                ->get()
                ->result();
      }
  return $query;  
}

public function user_yorder_ledger($uid,$year)
  {
  if($uid == 'All')
    {
    $query = $this->db->select('
                    order.*,
                    customers.custName,
                    customers.custMobile,
                    customers.custAddress')
                ->from('order')
                ->join('customers','customers.custid = order.custid','left')
                ->where('YEAR(oDate)',$year)
                ->get()
                ->result();
      }
    else
      {
      $query = $this->db->select('
                    order.*,
                    customers.custName,
                    customers.custMobile,
                    customers.custAddress')
                ->from('order')
                ->join('customers','customers.custid = order.custid','left')
                ->where('YEAR(oDate)',$year)
                ->where('order.regby',$uid)
                ->get()
                ->result();
      }
  return $query;  
}

public function user_aorder_ledger($uid)
  {
  $query = $this->db->select('
                    order.*,
                    customers.custName,
                    customers.custMobile,
                    customers.custAddress')
            ->from('order')
            ->join('customers','customers.custid = order.custid','left')
            ->where('order.regby',$uid)
            ->get()
            ->result();
  return $query;  
}

public function get_sales_is_data()
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('sales')
                  ->get()
                  ->row();
  return $query;  
}

public function get_voucher_is_data()
  {
  $query = $this->db->select("SUM(tAmount) as total")
                  ->from('vaucher')
                  ->where('vauchertype','Credit Voucher')
                  ->get()
                  ->row();
  return $query;  
}

public function get_purchase_is_data()
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('purchase')
                  ->get()
                  ->row();
  return $query;  
}

public function get_empp_is_data()
  {
  $query = $this->db->select("sum(salary) as total")
                  ->FROM('employee_payment')
                  ->get()
                  ->row();
  return $query;  
}

public function get_return_is_data()
  {
  $query = $this->db->select("sum(pAmount) as total")
                  ->FROM('returns')
                  ->get()
                  ->row();
  return $query;  
}

public function get_dvoucher_bs_data()
  {
  $query = $this->db->select("sum(tAmount) as total")
                ->from('vaucher')
                ->where_not_in('vauchertype','Credit Voucher')
                ->get()
                ->row();
  return $query;  
}

public function get_sales_dis_data($sdate,$edate)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('sales')
                  ->where('saDate >=', $sdate)
                  ->where('saDate <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function get_voucher_dis_data($sdate,$edate)
  {
  $query = $this->db->select("SUM(tAmount) as total")
                  ->from('vaucher')
                  ->where('vauchertype','Credit Voucher')
                  ->where('vuDate >=', $sdate)
                  ->where('vuDate <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function get_purchase_dis_data($sdate,$edate)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('purchase')
                  ->where('puDate >=', $sdate)
                  ->where('puDate <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function get_empp_dis_data($sdate,$edate)
  {
  $query = $this->db->select("sum(salary) as total")
                  ->FROM('employee_payment')
                  ->where('DATE(regdate) >=', $sdate)
                  ->where('DATE(regdate) <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function get_return_dis_data($sdate,$edate)
  {
  $query = $this->db->select("sum(pAmount) as total")
                  ->FROM('returns')
                  ->where('rDate >=', $sdate)
                  ->where('rDate <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function get_dvoucher_dis_data($sdate,$edate)
  {
  $query = $this->db->select("SUM(tAmount) as total")
                  ->from('vaucher')
                  ->where_not_in('vauchertype','Credit Voucher')
                  ->where('vuDate >=', $sdate)
                  ->where('vuDate <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function get_sales_mis_data($month,$year)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('sales')
                  ->where('MONTH(saDate)',$month)
                  ->where('YEAR(saDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_cvoucher_mis_data($month,$year)
  {
  $query = $this->db->select("SUM(tAmount) as total")
                  ->from('vaucher')
                  ->where('vauchertype','Credit Voucher')
                  ->where('MONTH(vuDate)',$month)
                  ->where('YEAR(vuDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_purchase_mis_data($month,$year)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('purchase')
                  ->where('MONTH(puDate)',$month)
                  ->where('YEAR(puDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_empp_mis_data($month,$year)
  {
  $query = $this->db->select("SUM(salary) as total")
                  ->FROM('employee_payment')
                  ->where('month',$month)
                  ->where('year',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_return_mis_data($month,$year)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('returns')
                  ->where('MONTH(rDate)',$month)
                  ->where('YEAR(rDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_dvoucher_mis_data($month,$year)
  {
  $query = $this->db->select("SUM(tAmount) as total")
                  ->from('vaucher')
                  ->where_not_in('vauchertype','Credit Voucher')
                  ->where('MONTH(vuDate)',$month)
                  ->where('YEAR(vuDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_sales_yis_data($year)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('sales')
                  ->where('YEAR(saDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_cvoucher_yis_data($year)
  {
  $query = $this->db->select("SUM(tAmount) as total")
                  ->from('vaucher')
                  ->where('vauchertype','Credit Voucher')
                  ->where('YEAR(vuDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_purchase_yis_data($year)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('purchase')
                  ->where('YEAR(puDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_empp_yis_data($year)
  {
  $query = $this->db->select("SUM(salary) as total")
                  ->FROM('employee_payment')
                  ->where('year',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_return_yis_data($year)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                  ->FROM('returns')
                  ->where('YEAR(rDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_dvoucher_yis_data($year)
  {
  $query = $this->db->select("SUM(tAmount) as total")
                  ->from('vaucher')
                  ->where_not_in('vauchertype','Credit Voucher')
                  ->where('YEAR(vuDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function company_profile_details()
  {
  $query = $this->db->select('*')
              ->from('com_profile')
              ->where('com_pid',1)
              ->get()
              ->row();
  return $query;  
}

public function get_tasset_tb_data()
  {
  $query = $this->db->select("SUM(`caamount`) as total")
                  ->FROM('chart_account')
                  ->where('catid',1)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tliability_tb_data()
  {
  $query = $this->db->select("SUM(`caamount`) as total")
                  ->FROM('chart_account')
                  ->where('catid',2)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tequity_tb_data()
  {
  $query = $this->db->select("SUM(`caamount`) as total")
                  ->FROM('chart_account')
                  ->where('catid',3)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tincome_tb_data()
  {
  $query = $this->db->select("SUM(`caamount`) as total")
                  ->FROM('chart_account')
                  ->where('catid',4)
                  ->get()
                  ->row();
  return $query;  
}

public function get_texpense_tb_data()
  {
  $query = $this->db->select("SUM(`caamount`) as total")
                  ->FROM('chart_account')
                  ->where('catid',5)
                  ->get()
                  ->row();
  return $query;  
}

public function get_sale_tb_data()
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('sales')
                  ->get()
                  ->row();
  return $query;  
}

public function get_tcvoucher_tb_data()
  {
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->where_not_in('vauchertype','Credit Voucher')
                  ->get()
                  ->row();
  return $query;  
}

public function get_tpurchase_tb_data()
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('purchase')
                  ->get()
                  ->row();
  return $query;  
}

public function get_tpayment_tb_data()
  {
  $query = $this->db->select("SUM(`salary`) as total")
                  ->FROM('employee_payment')
                  ->get()
                  ->row();
  return $query;  
}

public function get_purchase_return_tb_data()
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('preturns')
                  ->get()
                  ->row();
  return $query;  
}

public function get_treturn_tb_data()
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('returns')
                  ->get()
                  ->row();
  return $query;  
}

public function get_mobile_tb_data()
  {
  $query = $this->db->select("SUM(`current`) as total")
                  ->FROM('mobileaccount')
                  ->get()
                  ->row();
  return $query;  
}

public function get_cash_tb_data()
  {
  $query = $this->db->select("SUM(`current`) as total")
                  ->FROM('cash')
                  ->get()
                  ->row();
  return $query;  
}

public function get_bank_tb_data()
  {
  $query = $this->db->select("SUM(`current`) as total")
                  ->FROM('bankaccount')
                  ->get()
                  ->row();
  return $query;  
}

public function get_closing_inventory_data()
  {
  $query = $this->db->select("stock.tquantity,products.pprice as tpprice,products.pid")
                  ->FROM('stock')
                  ->join('products','products.pid = stock.pid','left')
                  ->get()
                  ->result();
  return $query;  
}

public function sales_due_data()
  {
  $query = $this->db->select('SUM(dAmount) as total')
                  ->from('sales')
                  ->where('tAmount > pAmount')
                  ->get()
                  ->row();
  return $query;  
}

public function purchase_due_data()
  {
  $query = $this->db->select('SUM(dAmount) as total')
                  ->from('purchase')
                  ->where('tAmount > pAmount')
                  ->get()
                  ->row();
  return $query;  
}

public function get_expense_adata()
  {
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->where('	vauchertype','Debit Voucher')
                  ->get()
                  ->row();
  return $query;  
}

public function get_tasset_dtb_data($sdate,$edate)
  {
  $query = $this->db->select("SUM(`caamount`) as total")
                  ->FROM('chart_account')
                  ->where('catid',1)
                  ->where('DATE(regdate) >=', $sdate)
                  ->where('DATE(regdate) <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tliability_dtb_data($sdate,$edate)
  {
  $query = $this->db->select("SUM(`caamount`) as total")
                  ->FROM('chart_account')
                  ->where('catid',2)
                  ->where('DATE(regdate) >=', $sdate)
                  ->where('DATE(regdate) <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tequity_dtb_data($sdate,$edate)
  {
  $query = $this->db->select("SUM(`caamount`) as total")
                  ->FROM('chart_account')
                  ->where('catid',3)
                  ->where('DATE(regdate) >=', $sdate)
                  ->where('DATE(regdate) <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tincome_dtb_data($sdate,$edate)
  {
  $query = $this->db->select("SUM(`caamount`) as total")
                  ->FROM('chart_account')
                  ->where('catid',4)
                  ->where('DATE(regdate) >=', $sdate)
                  ->where('DATE(regdate) <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function get_texpense_dtb_data($sdate,$edate)
  {
  $query = $this->db->select("SUM(`caamount`) as total")
                  ->FROM('chart_account')
                  ->where('catid',5)
                  ->where('DATE(regdate) >=', $sdate)
                  ->where('DATE(regdate) <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tsale_dtb_data($sdate,$edate)
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('sales')
                  ->where('saDate >=', $sdate)
                  ->where('saDate <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tcvoucher_dtb_data($sdate,$edate)
  {
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->where('vauchertype','Credit Voucher')
                  ->where('vuDate >=', $sdate)
                  ->where('vuDate <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tpurchase_dtb_data($sdate,$edate)
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('purchase')
                  ->where('puDate >=', $sdate)
                  ->where('puDate <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tpayment_dtb_data($sdate,$edate)
  {
  $query = $this->db->select("SUM(`salary`) as total")
                  ->FROM('employee_payment')
                  ->where('DATE(regdate) >=', $sdate)
                  ->where('DATE(regdate) <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function get_treturn_dtb_data($sdate,$edate)
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('returns')
                  ->where('rDate >=', $sdate)
                  ->where('rDate <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tdvoucher_dtb_data($sdate,$edate)
  {
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->where_not_in('vauchertype','Credit Voucher')
                  ->where('vuDate >=', $sdate)
                  ->where('vuDate <=', $edate)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tasset_mtb_data($month,$year)
  {
  $query = $this->db->select("SUM(`caamount`) as total")
                  ->FROM('chart_account')
                  ->where('catid',1)
                  ->where('MONTH(regdate)',$month)
                  ->where('YEAR(regdate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tliability_mtb_data($month,$year)
  {
  $query = $this->db->select("SUM(`caamount`) as total")
                  ->FROM('chart_account')
                  ->where('catid',2)
                  ->where('MONTH(regdate)',$month)
                  ->where('YEAR(regdate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tequity_mtb_data($month,$year)
  {
  $query = $this->db->select("SUM(`caamount`) as total")
                  ->FROM('chart_account')
                  ->where('catid',3)
                  ->where('MONTH(regdate)',$month)
                  ->where('YEAR(regdate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tincome_mtb_data($month,$year)
  {
  $query = $this->db->select("SUM(`caamount`) as total")
                  ->FROM('chart_account')
                  ->where('catid',4)
                  ->where('MONTH(regdate)',$month)
                  ->where('YEAR(regdate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_texpense_mtb_data($month,$year)
  {
  $query = $this->db->select("SUM(`caamount`) as total")
                  ->FROM('chart_account')
                  ->where('catid',5)
                  ->where('MONTH(regdate)',$month)
                  ->where('YEAR(regdate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tsale_mtb_data($month,$year)
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('sales')
                  ->where('MONTH(saDate)',$month)
                  ->where('YEAR(saDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tcvoucher_mtb_data($month,$year)
  {
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->where('vauchertype','Credit Voucher')
                  ->where('MONTH(vuDate)',$month)
                  ->where('YEAR(vuDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tpurchase_mtb_data($month,$year)
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('purchase')
                  ->where('MONTH(puDate)',$month)
                  ->where('YEAR(puDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tpayment_mtb_data($month,$year)
  {
  $query = $this->db->select("SUM(`salary`) as total")
                  ->FROM('employee_payment')
                  ->where('month',$month)
                  ->where('year',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_treturn_mtb_data($month,$year)
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('returns')
                  ->where('MONTH(rDate)',$month)
                  ->where('YEAR(rDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tdvoucher_mtb_data($month,$year)
  {
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->where_not_in('vauchertype','Credit Voucher')
                  ->where('MONTH(vuDate)',$month)
                  ->where('YEAR(vuDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tasset_ytb_data($year)
  {
  $query = $this->db->select("SUM(`caamount`) as total")
                  ->FROM('chart_account')
                  ->where('catid',1)
                  ->where('YEAR(regdate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tliability_ytb_data($year)
  {
  $query = $this->db->select("SUM(`caamount`) as total")
                  ->FROM('chart_account')
                  ->where('catid',2)
                  ->where('YEAR(regdate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tequity_ytb_data($year)
  {
  $query = $this->db->select("SUM(`caamount`) as total")
                  ->FROM('chart_account')
                  ->where('catid',3)
                  ->where('YEAR(regdate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tincome_ytb_data($year)
  {
  $query = $this->db->select("SUM(`caamount`) as total")
                  ->FROM('chart_account')
                  ->where('catid',4)
                  ->where('YEAR(regdate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_texpense_ytb_data($year)
  {
  $query = $this->db->select("SUM(`caamount`) as total")
                  ->FROM('chart_account')
                  ->where('catid',5)
                  ->where('YEAR(regdate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tsale_ytb_data($year)
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('sales')
                  ->where('YEAR(saDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tcvoucher_ytb_data($year)
  {
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->where('vauchertype','Credit Voucher')
                  ->where('YEAR(vuDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tpurchase_ytb_data($year)
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('purchase')
                  ->where('YEAR(puDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tpayment_ytb_data($year)
  {
  $query = $this->db->select("SUM(`salary`) as total")
                  ->FROM('employee_payment')
                  ->where('year',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_treturn_ytb_data($year)
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('returns')
                  ->where('YEAR(rDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_tdvoucher_ytb_data($year)
  {
  $query = $this->db->select("SUM(`tAmount`) as total")
                  ->FROM('vaucher')
                  ->where_not_in('vauchertype','Credit Voucher')
                  ->where('YEAR(vuDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_asset_dbs_data($sdate,$edate)
  {
  $emp = $this->db->select('catype')
                ->from('chart_account')
                ->where('DATE(regdate) >=', $sdate)
                ->where('DATE(regdate) <=', $edate)
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
    return $value['catype'];
    },$emp);

  if($emp_id == null)
    {
    $emp_id = 0;
    }

  $query = $this->db->select("*")
                ->from('chart_atype')
                ->where('catid',1)
                ->where_in('cat_id',$emp_id)
                ->get()
                ->result();
  return $query;  
}

public function get_liability_dbs_data($sdate,$edate)
  {
  $emp = $this->db->select('catype')
                ->from('chart_account')
                ->where('DATE(regdate) >=', $sdate)
                ->where('DATE(regdate) <=', $edate)
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
    return $value['catype'];
    },$emp);

  if($emp_id == null)
    {
    $emp_id = 0;
    }

  $query = $this->db->select("*")
                ->from('chart_atype')
                ->where('catid',2)
                ->where_in('cat_id',$emp_id)
                ->get()
                ->result();
  return $query;  
}

public function get_equity_dbs_data($sdate,$edate)
  {
  $emp = $this->db->select('catype')
                ->from('chart_account')
                ->where('DATE(regdate) >=', $sdate)
                ->where('DATE(regdate) <=', $edate)
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
    return $value['catype'];
    },$emp);

  if($emp_id == null)
    {
    $emp_id = 0;
    }

  $query = $this->db->select("*")
                ->from('chart_atype')
                ->where('catid',3)
                ->where_in('cat_id',$emp_id)
                ->get()
                ->result();
  return $query;  
}

public function get_income_dbs_data($sdate,$edate)
  {
  $emp = $this->db->select('catype')
                ->from('chart_account')
                ->where('DATE(regdate) >=', $sdate)
                ->where('DATE(regdate) <=', $edate)
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
    return $value['catype'];
    },$emp);

  if($emp_id == null)
    {
    $emp_id = 0;
    }

  $query = $this->db->select("*")
                ->from('chart_atype')
                ->where('catid',4)
                ->where_in('cat_id',$emp_id)
                ->get()
                ->result();
  return $query;  
}

public function get_expense_dbs_data($sdate,$edate)
  {
  $emp = $this->db->select('catype')
                ->from('chart_account')
                ->where('DATE(regdate) >=', $sdate)
                ->where('DATE(regdate) <=', $edate)
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
    return $value['catype'];
    },$emp);

  if($emp_id == null)
    {
    $emp_id = 0;
    }

  $query = $this->db->select("*")
                ->from('chart_atype')
                ->where('catid',5)
                ->where_in('cat_id',$emp_id)
                ->get()
                ->result();
  return $query;  
}

public function get_sale_dbs_data($sdate,$edate)
  {
  $query = $this->db->select("*")
                ->from('sales')
                ->where('saDate >=', $sdate)
                ->where('saDate <=', $edate)
                ->get()
                ->result();
  return $query;  
}

public function get_cvoucher_dbs_data($sdate,$edate)
  {
  $query = $this->db->select("*")
                ->from('vaucher')
                ->where('vauchertype','Credit Voucher')
                ->where('vuDate >=', $sdate)
                ->where('vuDate <=', $edate)
                ->get()
                ->result();
  return $query;  
}

public function get_purchase_dbs_data($sdate,$edate)
  {
  $query = $this->db->select("*")
                ->from('purchase')
                ->where('puDate >=', $sdate)
                ->where('puDate <=', $edate)
                ->get()
                ->result();
  return $query;  
}

public function get_payment_dbs_data($sdate,$edate)
  {
  $query = $this->db->select("*")
                ->from('employee_payment')
                ->where('DATE(regdate) >=', $sdate)
                ->where('DATE(regdate) <=', $edate)
                ->get()
                ->result();
  return $query;  
}

public function get_return_dbs_data($sdate,$edate)
  {
  $query = $this->db->select("*")
                ->from('returns')
                ->where('rDate >=', $sdate)
                ->where('rDate <=', $edate)
                ->get()
                ->result();
  return $query;  
}

public function get_dvoucher_dbs_data($sdate,$edate)
  {
  $query = $this->db->select("sum(tAmount) as total")
                ->from('vaucher')
                ->where_not_in('vauchertype','Credit Voucher')
                ->where('vuDate >=', $sdate)
                ->where('vuDate <=', $edate)
                ->get()
                ->row();
  return $query;  
}

public function get_asset_mbs_data($month,$year)
  {
  $emp = $this->db->select('catype')
                ->from('chart_account')
                ->where('MONTH(regdate)',$month)
                ->where('YEAR(regdate)',$year)
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
    return $value['catype'];
    },$emp);

  if($emp_id == null)
    {
    $emp_id = 0;
    }

  $query = $this->db->select("*")
                ->from('chart_atype')
                ->where('catid',1)
                ->where_in('cat_id',$emp_id)
                ->get()
                ->result();
  return $query;  
}

public function get_liability_mbs_data($month,$year)
  {
  $emp = $this->db->select('catype')
                ->from('chart_account')
                ->where('MONTH(regdate)',$month)
                ->where('YEAR(regdate)',$year)
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
    return $value['catype'];
    },$emp);

  if($emp_id == null)
    {
    $emp_id = 0;
    }

  $query = $this->db->select("*")
                ->from('chart_atype')
                ->where('catid',2)
                ->where_in('cat_id',$emp_id)
                ->get()
                ->result();
  return $query;  
}

public function get_equity_mbs_data($month,$year)
  {
  $emp = $this->db->select('catype')
                ->from('chart_account')
                ->where('MONTH(regdate)',$month)
                ->where('YEAR(regdate)',$year)
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
    return $value['catype'];
    },$emp);

  if($emp_id == null)
    {
    $emp_id = 0;
    }

  $query = $this->db->select("*")
                ->from('chart_atype')
                ->where('catid',3)
                ->where_in('cat_id',$emp_id)
                ->get()
                ->result();
  return $query;  
}

public function get_income_mbs_data($month,$year)
  {
  $emp = $this->db->select('catype')
                ->from('chart_account')
                ->where('MONTH(regdate)',$month)
                ->where('YEAR(regdate)',$year)
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
    return $value['catype'];
    },$emp);

  if($emp_id == null)
    {
    $emp_id = 0;
    }

  $query = $this->db->select("*")
                ->from('chart_atype')
                ->where('catid',4)
                ->where_in('cat_id',$emp_id)
                ->get()
                ->result();
  return $query;  
}

public function get_expense_mbs_data($month,$year)
  {
  $emp = $this->db->select('catype')
                ->from('chart_account')
                ->where('MONTH(regdate)',$month)
                ->where('YEAR(regdate)',$year)
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
    return $value['catype'];
    },$emp);

  if($emp_id == null)
    {
    $emp_id = 0;
    }

  $query = $this->db->select("*")
                ->from('chart_atype')
                ->where('catid',5)
                ->where_in('cat_id',$emp_id)
                ->get()
                ->result();
  return $query;  
}

public function get_sale_mbs_data($month,$year)
  {
  $query = $this->db->select("*")
                ->from('sales')
                ->where('MONTH(saDate)',$month)
                ->where('YEAR(saDate)',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_cvoucher_mbs_data($month,$year)
  {
  $query = $this->db->select("*")
                ->from('vaucher')
                ->where('vauchertype','Credit Voucher')
                ->where('MONTH(vuDate)',$month)
                ->where('YEAR(vuDate)',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_purchase_mbs_data($month,$year)
  {
  $query = $this->db->select("*")
                ->from('purchase')
                ->where('MONTH(puDate)',$month)
                ->where('YEAR(puDate)',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_payment_mbs_data($month,$year)
  {
  $query = $this->db->select("*")
                ->from('employee_payment')
                ->where('month',$month)
                ->where('year',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_return_mbs_data($month,$year)
  {
  $query = $this->db->select("*")
                ->from('returns')
                ->where('MONTH(rDate)',$month)
                ->where('YEAR(rDate)',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_dvoucher_mbs_data($month,$year)
  {
  $query = $this->db->select("sum(tAmount) as total")
                ->from('vaucher')
                ->where_not_in('vauchertype','Credit Voucher')
                ->where('MONTH(vuDate)',$month)
                ->where('YEAR(vuDate)',$year)
                ->get()
                ->row();
  return $query;  
}

public function get_asset_ybs_data($year)
  {
  $emp = $this->db->select('catype')
                ->from('chart_account')
                ->where('YEAR(regdate)',$year)
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
    return $value['catype'];
    },$emp);

  if($emp_id == null)
    {
    $emp_id = 0;
    }

  $query = $this->db->select("*")
                ->from('chart_atype')
                ->where('catid',1)
                ->where_in('cat_id',$emp_id)
                ->get()
                ->result();
  return $query;  
}

public function get_liability_ybs_data($year)
  {
  $emp = $this->db->select('catype')
                ->from('chart_account')
                ->where('YEAR(regdate)',$year)
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
    return $value['catype'];
    },$emp);

  if($emp_id == null)
    {
    $emp_id = 0;
    }

  $query = $this->db->select("*")
                ->from('chart_atype')
                ->where('catid',2)
                ->where_in('cat_id',$emp_id)
                ->get()
                ->result();
  return $query;  
}

public function get_equity_ybs_data($year)
  {
  $emp = $this->db->select('catype')
                ->from('chart_account')
                ->where('YEAR(regdate)',$year)
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
    return $value['catype'];
    },$emp);

  if($emp_id == null)
    {
    $emp_id = 0;
    }

  $query = $this->db->select("*")
                ->from('chart_atype')
                ->where('catid',3)
                ->where_in('cat_id',$emp_id)
                ->get()
                ->result();
  return $query;  
}

public function get_income_ybs_data($year)
  {
  $emp = $this->db->select('catype')
                ->from('chart_account')
                ->where('YEAR(regdate)',$year)
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
    return $value['catype'];
    },$emp);

  if($emp_id == null)
    {
    $emp_id = 0;
    }

  $query = $this->db->select("*")
                ->from('chart_atype')
                ->where('catid',4)
                ->where_in('cat_id',$emp_id)
                ->get()
                ->result();
  return $query;  
}

public function get_expense_ybs_data($year)
  {
  $emp = $this->db->select('catype')
                ->from('chart_account')
                ->where('YEAR(regdate)',$year)
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
    return $value['catype'];
    },$emp);

  if($emp_id == null)
    {
    $emp_id = 0;
    }

  $query = $this->db->select("*")
                ->from('chart_atype')
                ->where('catid',5)
                ->where_in('cat_id',$emp_id)
                ->get()
                ->result();
  return $query;  
}

public function get_sale_ybs_data($year)
  {
  $query = $this->db->select("*")
                ->from('sales')
                ->where('YEAR(saDate)',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_cvoucher_ybs_data($year)
  {
  $query = $this->db->select("*")
                ->from('vaucher')
                ->where('vauchertype','Credit Voucher')
                ->where('YEAR(vuDate)',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_purchase_ybs_data($year)
  {
  $query = $this->db->select("*")
                ->from('purchase')
                ->where('YEAR(puDate)',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_payment_ybs_data($year)
  {
  $query = $this->db->select("*")
                ->from('employee_payment')
                ->where('year',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_return_ybs_data($year)
  {
  $query = $this->db->select("*")
                ->from('returns')
                ->where('YEAR(rDate)',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_dvoucher_ybs_data($year)
  {
  $query = $this->db->select("sum(tAmount) as total")
                ->from('vaucher')
                ->where_not_in('vauchertype','Credit Voucher')
                ->where('YEAR(vuDate)',$year)
                ->get()
                ->row();
  return $query;  
}

public function get_return_bs_data()
  {
  $query = $this->db->select("*")
                ->from('returns')
                ->get()
                ->result();
  return $query;  
}

public function get_preturn_data()
  {
  $query = $this->db->select("*")
                ->from('preturns')
                ->get()
                ->result();
  return $query;  
}

public function get_dpurchase_data($sdate,$edate,$ttype,$acid)
  {
  $this->db->select("*")
                ->from('purchase')
                ->where('puDate >=',$sdate)
                ->where('puDate <=',$edate);
  if($ttype != 'All')
    {
    $this->db->where('accountType',$ttype);
    }
  if($acid != 'All')
    {
    $this->db->where('accountNo',$acid);
    }
                
  $query = $this->db->get()->result();
  return $query;
}

public function get_dsale_data($sdate,$edate,$ttype,$acid)
  {
  $this->db->select("*")
                ->from('sales')
                ->where('saDate >=',$sdate)
                ->where('saDate <=',$edate);
  if($ttype != 'All')
    {
    $this->db->where('accountType',$ttype);
    }
  if($acid != 'All')
    {
    $this->db->where('accountNo',$acid);
    }
                
  $query = $this->db->get()->result();
  return $query;
}

public function get_dsreturn_data($sdate,$edate,$ttype,$acid)
  {
  $this->db->select("*")
                ->from('returns')
                ->where('rDate >=',$sdate)
                ->where('rDate <=',$edate);
  if($ttype != 'All')
    {
    $this->db->where('accountType',$ttype);
    }
  if($acid != 'All')
    {
    $this->db->where('accountNo',$acid);
    }
                
  $query = $this->db->get()->result();
  return $query;
}

public function get_dpreturn_data($sdate,$edate,$ttype,$acid)
  {
  $this->db->select("*")
                ->from('preturns')
                ->where('prDate >=',$sdate)
                ->where('prDate <=',$edate);
  if($ttype != 'All')
    {
    $this->db->where('accountType',$ttype);
    }
  if($acid != 'All')
    {
    $this->db->where('accountNo',$acid);
    }
                
  $query = $this->db->get()->result();
  return $query;
}

public function get_dvoucher_data($sdate,$edate,$ttype,$acid)
  {
  $this->db->select("*")
                ->from('vaucher')
                ->where('vuDate >=',$sdate)
                ->where('vuDate <=',$edate);
  if($ttype != 'All')
    {
    $this->db->where('accountType',$ttype);
    }
  if($acid != 'All')
    {
    $this->db->where('accountNo',$acid);
    }
                
  $query = $this->db->get()->result();
  return $query;
}

public function get_mpurchase_data($month,$year,$ttype,$acid)
  {
  $this->db->select("*")
                ->from('purchase')
                ->where('MONTH(puDate)',$month)
                ->where('YEAR(puDate)',$year);
  if($ttype != 'All')
    {
    $this->db->where('accountType',$ttype);
    }
  if($acid != 'All')
    {
    $this->db->where('accountNo',$acid);
    }
                
  $query = $this->db->get()->result();
  return $query;
}

public function get_msale_data($month,$year,$ttype,$acid)
  {
  $this->db->select("*")
                ->from('sales')
                ->where('MONTH(saDate)',$month)
                ->where('YEAR(saDate)',$year);
  if($ttype != 'All')
    {
    $this->db->where('accountType',$ttype);
    }
  if($acid != 'All')
    {
    $this->db->where('accountNo',$acid);
    }
                
  $query = $this->db->get()->result();
  return $query;
}

public function get_msreturn_data($month,$year,$ttype,$acid)
  {
  $this->db->select("*")
                ->from('returns')
                ->where('MONTH(rDate)',$month)
                ->where('YEAR(rDate)',$year);
  if($ttype != 'All')
    {
    $this->db->where('accountType',$ttype);
    }
  if($acid != 'All')
    {
    $this->db->where('accountNo',$acid);
    }
                
  $query = $this->db->get()->result();
  return $query;
}

public function get_mpreturn_data($month,$year,$ttype,$acid)
  {
  $this->db->select("*")
                ->from('preturns')
                ->where('MONTH(prDate)',$month)
                ->where('YEAR(prDate)',$year);
  if($ttype != 'All')
    {
    $this->db->where('accountType',$ttype);
    }
  if($acid != 'All')
    {
    $this->db->where('accountNo',$acid);
    }
                
  $query = $this->db->get()->result();
  return $query;
}

public function get_mvoucher_data($month,$year,$ttype,$acid)
  {
  $this->db->select("*")
                ->from('vaucher')
                ->where('MONTH(vuDate)',$month)
                ->where('YEAR(vuDate)',$year);
  if($ttype != 'All')
    {
    $this->db->where('accountType',$ttype);
    }
  if($acid != 'All')
    {
    $this->db->where('accountNo',$acid);
    }
                
  $query = $this->db->get()->result();
  return $query;
}

public function get_ypurchase_data($year,$ttype,$acid)
  {
  $this->db->select("*")
                ->from('purchase')
                ->where('YEAR(puDate)',$year);
  if($ttype != 'All')
    {
    $this->db->where('accountType',$ttype);
    }
  if($acid != 'All')
    {
    $this->db->where('accountNo',$acid);
    }
                
  $query = $this->db->get()->result();
  return $query;
}

public function get_ysale_data($year,$ttype,$acid)
  {
  $this->db->select("*")
                ->from('sales')
                ->where('YEAR(saDate)',$year);
  if($ttype != 'All')
    {
    $this->db->where('accountType',$ttype);
    }
  if($acid != 'All')
    {
    $this->db->where('accountNo',$acid);
    }
                
  $query = $this->db->get()->result();
  return $query;  
}

public function get_ysreturn_data($year,$ttype,$acid)
  {
  $this->db->select("*")
                ->from('returns')
                ->where('YEAR(rDate)',$year);
  if($ttype != 'All')
    {
    $this->db->where('accountType',$ttype);
    }
  if($acid != 'All')
    {
    $this->db->where('accountNo',$acid);
    }
                
  $query = $this->db->get()->result();
  return $query;  
}

public function get_ypreturn_data($year,$ttype,$acid)
  {
  $this->db->select("*")
                ->from('preturns')
                ->where('YEAR(prDate)',$year);
  if($ttype != 'All')
    {
    $this->db->where('accountType',$ttype);
    }
  if($acid != 'All')
    {
    $this->db->where('accountNo',$acid);
    }
                
  $query = $this->db->get()->result();
  return $query;  
}

public function get_yvoucher_data($year,$ttype,$acid)
  {
  $this->db->select("*")
            ->from('vaucher')
            ->where('YEAR(vuDate)',$year);
  if($ttype != 'All')
    {
    $this->db->where('accountType',$ttype);
    }
  if($acid != 'All')
    {
    $this->db->where('accountNo',$acid);
    }
                
  $query = $this->db->get()->result();
  return $query;  
}

public function get_sales_payment_data($id)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                ->from('sales_payment')
                ->where('said',$id)
                ->get()
                ->row();
  return $query;  
}

public function sales_cust_ledger_data($custid)
  {
  $query = $this->db->select("*")
                  ->FROM('sales')
                  ->WHERE('custid',$custid)
                  //->order_by('said','ASC')
                  ->get()
                  ->result();
  return $query;  
}

public function sales_payment_cust_ledger_data($custid)
  {
  $customer = $this->db->select('said')
                    ->from('sales')
                    ->WHERE('custid',$custid)
                    ->get()
                    ->result_array();
  $sales = array_map (function($value){
      return $value['said'];
      },$customer);
        //var_dump($emp_id); exit();
    if($sales)
      {
      $said = $sales;
      }
    else
      {
      $said = 0;
      }
  $query = $this->db->select('*')
                ->from('sales_payment')
                ->where_in('said',$said)
                //->order_by('spid','ASC')
                ->get()
                ->result();
  return $query;  
}

public function voucher_cust_ledger_data($custid)
  {
  $query = $this->db->select("*")
                  ->FROM('vaucher')
                  ->WHERE('custid',$custid)
                  ->order_by('regdate','ASC')
                  ->get()
                  ->result();
  return $query;  
}

public function return_cust_ledger_data($custid)
  {
  $query = $this->db->select("*")
                  ->FROM('returns')
                  ->WHERE('custid',$custid)
                  //->order_by('rid','ASC')
                  ->get()
                  ->result();
  return $query;  
}

public function service_cust_ledger_data($custid)
  {
  $query = $this->db->select("*")
                  ->FROM('services_sale')
                  ->WHERE('custid',$custid)
                 // ->order_by('ssid','ASC')
                  ->get()
                  ->result();
  return $query;  
}

public function sales_dcust_ledger_data($custid,$sdate,$edate)
  {
  $query = $this->db->select("*")
                  ->FROM('sales')
                  ->WHERE('custid',$custid)
                  ->where('saDate >=', $sdate)
                  ->where('saDate <=', $edate)
                  //->order_by('said','ASC')
                  ->get()
                  ->result();
  return $query;  
}

public function sales_payment_dcust_ledger_data($custid,$sdate,$edate)
  {
  $customer = $this->db->select('said')
                    ->from('sales')
                    ->WHERE('custid',$custid)
                    ->get()
                    ->result_array();
  $sales = array_map (function($value){
      return $value['said'];
      },$customer);
        //var_dump($emp_id); exit();
    if($sales)
      {
      $said = $sales;
      }
    else
      {
      $said = 0;
      }

  $query = $this->db->select('*')
                ->from('sales_payment')
                ->where_in('said',$said)
                ->where('DATE(regdate) >=', $sdate)
                ->where('DATE(regdate) <=', $edate)
                //->order_by('spid','ASC')
                ->get()
                ->result();
  return $query;  
}

public function voucher_dcust_ledger_data($custid,$sdate,$edate)
  {
  $query = $this->db->select("*")
                  ->FROM('vaucher')
                  ->WHERE('custid',$custid)
                  ->where('vuDate >=', $sdate)
                  ->where('vuDate <=', $edate)
                  //->order_by('vuid','ASC')
                  ->get()
                  ->result();
  return $query;  
}

public function return_dcust_ledger_data($custid,$sdate,$edate)
  {
  $query = $this->db->select("*")
                  ->FROM('returns')
                  ->WHERE('custid',$custid)
                  ->where('rDate >=', $sdate)
                  ->where('rDate <=', $edate)
                  //->order_by('rid','ASC')
                  ->get()
                  ->result();
  return $query;  
}

public function service_dcust_ledger_data($custid,$sdate,$edate)
  {
  $query = $this->db->select("*")
                  ->FROM('services_sale')
                  ->WHERE('custid',$custid)
                  ->where('ssDate >=', $sdate)
                  ->where('ssDate <=', $edate)
                  //->order_by('ssid','ASC')
                  ->get()
                  ->result();
  return $query;  
}

public function sales_mcust_ledger_data($custid,$month,$year)
  {
  $query = $this->db->select("*")
                  ->FROM('sales')
                  ->WHERE('custid',$custid)
                  ->where('MONTH(saDate)',$month)
                  ->where('YEAR(saDate)',$year)
                  ->get()
                  ->result();
  return $query;  
}

public function sales_payment_mcust_ledger_data($custid,$month,$year)
  {
  $customer = $this->db->select('said')
                    ->from('sales')
                    ->WHERE('custid',$custid)
                    ->get()
                    ->result_array();
  $sales = array_map (function($value){
      return $value['said'];
      },$customer);
        //var_dump($emp_id); exit();
    if($sales)
      {
      $said = $sales;
      }
    else
      {
      $said = 0;
      }

  $query = $this->db->select('*')
                ->from('sales_payment')
                ->where_in('said',$said)
                ->where('MONTH(regdate)',$month)
                ->where('YEAR(regdate)',$year)
                ->get()
                ->result();
  return $query;  
}

public function voucher_mcust_ledger_data($custid,$month,$year)
  {
  $query = $this->db->select("*")
                  ->FROM('vaucher')
                  ->WHERE('custid',$custid)
                  ->where('MONTH(vuDate)',$month)
                  ->where('YEAR(vuDate)',$year)
                  ->get()
                  ->result();
  return $query;  
}

public function return_mcust_ledger_data($custid,$month,$year)
  {
  $query = $this->db->select("*")
                  ->FROM('returns')
                  ->WHERE('custid',$custid)
                  ->where('MONTH(rDate)',$month)
                  ->where('YEAR(rDate)',$year)
                  ->get()
                  ->result();
  return $query;  
}

public function service_mcust_ledger_data($custid,$month,$year)
  {
  $query = $this->db->select("*")
                  ->FROM('services_sale')
                  ->WHERE('custid',$custid)
                  ->where('MONTH(ssDate)',$month)
                  ->where('YEAR(ssDate)',$year)
                  ->get()
                  ->result();
  return $query;  
}

public function sales_ycust_ledger_data($custid,$year)
  {
  $query = $this->db->select("*")
                  ->FROM('sales')
                  ->WHERE('custid',$custid)
                  ->where('YEAR(saDate)',$year)
                  ->get()
                  ->result();
  return $query;  
}

public function sales_payment_ycust_ledger_data($custid,$year)
  {
  $customer = $this->db->select('said')
                    ->from('sales')
                    ->WHERE('custid',$custid)
                    ->get()
                    ->result_array();
  $sales = array_map (function($value){
      return $value['said'];
      },$customer);
        //var_dump($emp_id); exit();
    if($sales)
      {
      $said = $sales;
      }
    else
      {
      $said = 0;
      }

  $query = $this->db->select('*')
                ->from('sales_payment')
                ->where_in('said',$said)
                ->where('YEAR(regdate)', $year)
                ->get()
                ->result();
  return $query;  
}

public function voucher_ycust_ledger_data($custid,$year)
  {
  $query = $this->db->select("*")
                  ->FROM('vaucher')
                  ->WHERE('custid',$custid)
                  ->where('YEAR(vuDate)',$year)
                  ->get()
                  ->result();
  return $query;  
}

public function return_ycust_ledger_data($custid,$year)
  {
  $query = $this->db->select("*")
                  ->FROM('returns')
                  ->WHERE('custid',$custid)
                  ->where('YEAR(rDate)',$year)
                  ->get()
                  ->result();
  return $query;  
}

public function service_ycust_ledger_data($custid,$year)
  {
  $query = $this->db->select("*")
                  ->FROM('services_sale')
                  ->WHERE('custid',$custid)
                  ->where('YEAR(ssDate)',$year)
                  ->get()
                  ->result();
  return $query;  
}


public function get_spurchase_data($sid)
  {
  $query = $this->db->select('*')
              ->from('purchase')
              ->where('supid',$sid)
              ->get()
              ->result();

  return $query;  
}

public function get_sp_payment_data($sid)
  {             
  $query = $this->db->select('*')
                ->from('supplier_payment')
                ->where('supid',$sid)
                ->get()
                ->result();
  return $query;  
}

public function get_svoucher_data($sid)
  {
  $query = $this->db->select('*')
              ->from('vaucher')
              ->where('supid',$sid)
              ->get()
              ->result();

  return $query;  
}

public function get_sreturn_data($sid)
  {
  $query = $this->db->select('*')
              ->from('preturns')
              ->where('supid',$sid)
              ->get()
              ->result();

  return $query;  
}

public function get_dspurchase_data($sdate,$edate,$sid)
  {
  $query = $this->db->select('*')
                  ->from('purchase')
                  ->where('puDate >=', $sdate)
                  ->where('puDate <=', $edate)
                  ->where('supid',$sid)
                  ->get()
                  ->result();

  return $query;  
}

public function get_dsp_payment_data($sdate,$edate,$sid)
  {
  $query = $this->db->select('*')
                ->from('supplier_payment')
                ->where('supid',$sid)
                ->where('DATE(regdate) >=', $sdate)
                ->where('DATE(regdate) <=', $edate)
                ->get()
                ->result();
  return $query;  
}

public function get_dsvoucher_data($sdate,$edate,$sid)
  {
  $query = $this->db->select('*')
                  ->from('vaucher')
                  ->where('vuDate >=', $sdate)
                  ->where('vuDate <=', $edate)
                  ->where('supid',$sid)
                  ->get()
                  ->result();
  return $query;  
}

public function get_dspreturn_data($sdate,$edate,$sid)
  {
  $query = $this->db->select('*')
                  ->from('preturns')
                  ->where('prDate >=', $sdate)
                  ->where('prDate <=', $edate)
                  ->where('supid',$sid)
                  ->get()
                  ->result();
  return $query;  
}

public function get_mspurchase_data($month,$year,$sid)
  {
  $query = $this->db->select('*')
                  ->from('purchase')
                  ->where('MONTH(puDate)',$month)
                  ->where('YEAR(puDate)',$year)
                  ->where('supid',$sid)
                  ->get()
                  ->result();

  return $query;  
}

public function get_msp_payment_data($month,$year,$sid)
  {
  $query = $this->db->select('*')
                ->from('supplier_payment')
                ->where('supid',$sid)
                ->where('MONTH(regdate)', $month)
                ->where('YEAR(regdate)', $year)
                ->get()
                ->result();
  return $query;  
}

public function get_msvoucher_data($month,$year,$sid)
  {
  $query = $this->db->select('*')
              ->from('vaucher')
              ->where('MONTH(vuDate)',$month)
              ->where('YEAR(vuDate)',$year)
              ->where('supid',$sid)
              ->get()
              ->result();
  return $query;  
}

public function get_mspreturn_data($month,$year,$sid)
  {
  $query = $this->db->select('*')
              ->from('preturns')
              ->where('MONTH(prDate)',$month)
              ->where('YEAR(prDate)',$year)
              ->where('supid',$sid)
              ->get()
              ->result();
  return $query;  
}

public function get_yspurchase_data($year,$sid)
  {
  $query = $this->db->select('*')
              ->from('purchase')
              ->where('YEAR(puDate)',$year)
              ->where('supid',$sid)
              ->get()
              ->result();

  return $query;  
}

public function get_ysp_payment_data($year,$sid)
  {
  $query = $this->db->select('*')
                ->from('supplier_payment')
                ->where('supid',$sid)
                ->where('YEAR(regdate)', $year)
                ->get()
                ->result();
  return $query;  
}

public function get_ysvoucher_data($year,$sid)
  {
  $query = $this->db->select('*')
              ->from('vaucher')
              ->where('YEAR(vuDate)',$year)
              ->where('supid',$sid)
              ->get()
              ->result();
  return $query;  
}

public function get_yspreturn_data($year,$sid)
  {
  $query = $this->db->select('*')
              ->from('preturns')
              ->where('YEAR(prDate)',$year)
              ->where('supid',$sid)
              ->get()
              ->result();
  return $query;  
}

public function get_supplier_amount($id)
  {
  $sup = $this->db->select('balance')
              ->from('suppliers')
              ->where('supid',$id)
              ->get()
              ->row();
  if($sup)
    {
    $sob = $sup->balance;
    }
  else
    {
    $sob = 0;
    }
  $tpur = $this->db->select("SUM(pAmount) as total")
                  ->FROM('purchase')
                  ->WHERE('supid',$id)
                  ->get()
                  ->row();
  if($tpur)
    {
    $tpa = $tpur->total;
    }
  else
    {
    $tpa = 0;
    }
  $tvpaid = $this->db->select("SUM(tAmount) as total")
                    ->FROM('vaucher')
                    ->WHERE('supid',$id)
                    ->get()
                    ->row();
  if($tvpaid)
    {
    $tva = $tvpaid->total;
    }
  else
    {
    $tva = 0;
    }           
  $customer = $this->db->select('puid')
                    ->from('purchase')
                    ->WHERE('supid',$id)
                    ->get()
                    ->result_array();
  $sales = array_map (function($value){
      return $value['puid'];
      },$customer);
        //var_dump($emp_id); exit();
    if($sales)
      {
      $said = $sales;
      }
    else
      {
      $said = 0;
      }
        
  $pay = $this->db->select('SUM(pAmount) as total')
            ->from('purchase_payment')
            ->where_in('puid',$said)
            ->get()
            ->row();
  if($pay)
    {
    $tppa = $pay->total;
    }
  else
    {
    $tppa = 0;
    }  
  $tadp = $this->db->select("SUM(pAmount) as total")
                  ->FROM('supplier_payment')
                  ->WHERE('supid',$id)
                  ->get()
                  ->row();
  
  if($tadp)
    {
    $tapa = $tadp->total;
    }
  else
    {
    $tapa = 0;
    } 
    
  $query = (($sob+$tapa)-($tpa+$tva+$tppa));
  return $query;  
}

public function dsales_due_data($sdate,$edate)
  {
  $query = $this->db->select('SUM(dAmount) as total')
                  ->from('sales')
                  ->where('tAmount > pAmount')
                  ->where('saDate >=',$sdate)
                  ->where('saDate <=',$edate)
                  ->get()
                  ->row();
  return $query;  
}

public function dpurchase_due_data($sdate,$edate)
  {
  $query = $this->db->select('SUM(dAmount) as total')
                  ->from('purchase')
                  ->where('tAmount > pAmount')
                  ->where('puDate >=',$sdate)
                  ->where('puDate <=',$edate)
                  ->get()
                  ->row();
  return $query;  
}

public function get_dpurchase_return_tb_data($sdate,$edate)
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('preturns')
                  ->where('prDate >=',$sdate)
                  ->where('prDate <=',$edate)
                  ->get()
                  ->row();
  return $query;  
}

public function msales_due_data($month,$year)
  {
  $query = $this->db->select('SUM(dAmount) as total')
                  ->from('sales')
                  ->where('tAmount > pAmount')
                  ->where('MONTH(saDate)',$month)
                  ->where('YEAR(saDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function mpurchase_due_data($month,$year)
  {
  $query = $this->db->select('SUM(dAmount) as total')
                  ->from('purchase')
                  ->where('tAmount > pAmount')
                  ->where('MONTH(puDate)',$month)
                  ->where('YEAR(puDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_mpurchase_return_tb_data($month,$year)
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('preturns')
                  ->where('MONTH(prDate)',$month)
                  ->where('YEAR(prDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function ysales_due_data($year)
  {
  $query = $this->db->select('SUM(dAmount) as total')
                  ->from('sales')
                  ->where('tAmount > pAmount')
                  ->where('YEAR(saDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function ypurchase_due_data($year)
  {
  $query = $this->db->select('SUM(dAmount) as total')
                  ->from('purchase')
                  ->where('tAmount > pAmount')
                  ->where('YEAR(puDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_ypurchase_return_tb_data($year)
  {
  $query = $this->db->select("SUM(`pAmount`) as total")
                  ->FROM('preturns')
                  ->where('YEAR(prDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}












public function check_user_email($id)
  {
  $query = $this->db->select('*')
                ->from('users')
                ->where('email',$id)
                ->get();

  $count_row = $query->num_rows();

  if($count_row == 0)
    {
    return 1;
    }
  else
    {
    return 0;
    }
}

public function get_user_notice()
  {
  $query = $this->db->select('*')
                    ->from('notice')
                    ->or_where('ntype','All')
                    ->or_where('ntype',$_SESSION['uid'])
                    ->order_by('nid','DESC')
                    ->get()
                    ->result();
  return $query;
}

public function get_user_role_data($id)
  {
  $query = $this->db->select('*')
                ->from('access_lavels')
                ->where('ax_id',$id)
                ->get()
                ->row();
  return $query;
}

public function get_sales_customer_data($id)
  {
  $cust = $this->db->select('customerID')
                  ->from('sales')
                  ->where('saleID',$id)
                  ->get()
                  ->row();
                  
  $query = $this->db->select('mobile')
                  ->from('customers')
                  ->where('customerID',$cust->customerID)
                  ->get()
                  ->row();
  return $query; 
}

public function get_employee()
  {
  $emp = $this->db->select('empid')
                ->from('users')
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
  return $value['empid'];
  },$emp);
    //var_dump($emp_id); exit();
  if($emp_id == NULL)
      {
      $empid = 0;
      }
  else{
      $empid = $emp_id;
      }
    //var_dump($empid); exit();
  return $this->db->select('empid,empName')
              ->from('employees')
              ->where_not_in('empid',$empid)
              ->get()
              ->result();
}

public function get_user_data($id)
  {
  $query = $this->db->select('*')
                ->from('users')
                ->where('uid',$id)
                ->get()
                ->row();
  return $query;
}

public function supplier_purchases_due_details($id,$sid)
  {
  $query = $this->db->select("SUM(`due`) as total")
                  ->FROM('purchase')
                  ->where_not_in('purchaseID',$id)
                  ->where('supplier',$sid)
                  ->get()
                  ->row();
  return $query;  
}

public function supplier_paid_details($sid)
  {
  $query = $this->db->select("SUM(`totalamount`) as total")
                  ->FROM('vaucher')
                  ->where('supplier',$sid)
                  ->get()
                  ->row();
  return $query;  
}

public function customer_sales_due_details($id,$cusid)
  {
  $query = $this->db->select("SUM(`dAmount`) as total")
                  ->FROM('sales')
                  ->where_not_in('said',$id)
                  ->WHERE('custid',$cusid)
                  ->get()
                  ->row();
  return $query;  
}

public function customer_vaucher_paid_details($cusid)
  {
  $query = $this->db->select('SUM(`tAmount`) as total')
                  ->from('vaucher')
                  ->where('custid',$cusid)
                  ->where('vauchertype','Credit Voucher')
                  ->get()
                  ->row();
  return $query; 
}

public function customer_returns_details($cusid)
  {
  $query = $this->db->select('SUM(`pAmount`) as total')
                  ->from('returns')
                  ->where('custid',$cusid)
                  ->get()
                  ->row();
  return $query; 
}

public function get_profile_data()
  {
  $query = $this->db->select('*')
                ->from('users')
                ->where('uid',$_SESSION['uid'])
                ->get()
                ->row();
  return $query;
}

public function current_password_check($cpassword)
  {
  return $this->db->select('*')
                ->from('users')
                ->where('password',$cpassword)
                ->get()
                ->row();
}

public function check_email($empemail)
  {
  return $this->db->select('*')
                    ->from('users')
                    ->where('email',$empemail)
                    ->get()
                    ->row();
}

public function check_mobile_number($mid)
  {
  return $this->db->select('*')
                    ->from('users')
                    ->where('mobile',$mid)
                    ->get()
                    ->row();
}

public function total_category()
  {
  $query = $this->db->select('*')
                ->from('categories')
                ->where('compid',$_SESSION['compid'])
                ->get();

  $count_row = $query->num_rows();

  return $count_row;
}

public function total_unit()
  {
  $query = $this->db->select('*')
                ->from('sma_units')
                ->where('compid',$_SESSION['compid'])
                ->get();

  $count_row = $query->num_rows();

  return $count_row;
}

public function total_expense_type()
  {
  $query = $this->db->select('*')
                ->from('cost_type')
                ->where('compid',$_SESSION['compid'])
                ->get();

  $count_row = $query->num_rows();

  return $count_row;
}

public function total_depertment()
  {
  $query = $this->db->select('*')
                ->from('department')
                ->where('compid',$_SESSION['compid'])
                ->get();

  $count_row = $query->num_rows();

  return $count_row;
}
public function total_courier()
  {
  $query = $this->db->select('*')
                ->from('courier')
                ->get();

  $count_row = $query->num_rows();

  return $count_row;
}

public function total_notice()
  {
  $query = $this->db->select('*')
                ->from('notice')
                ->or_where('ntype','All')
                ->or_where('ntype',$_SESSION['uid'])
                ->get();

  $count_row = $query->num_rows();

  return $count_row;
}

public function total_user_type()
  {
  $query = $this->db->select('*')
                ->from('access_lavels')
                ->where('ax_id >',2)
                ->get();

  $count_row = $query->num_rows();

  return $count_row;
}

public function total_balance_transfer()
  {
  $query = $this->db->select('SUM(amount) as total')
                ->from('transfer_account')
                ->get()
                ->row();

  return $query;
}

public function product_fetch_data()
  {
  $query = $this->db->select('
                        products.*,
                        categories.catName,
                        sma_units.unitName')
                ->from('products')
                ->join('categories','categories.catid = products.catid','left')
                ->join('sma_units','sma_units.untid = products.untid','left')
                ->order_by("pid","DESC")
                ->get()
                ->result();
  return $query;
}

public function insert_product_data($data)
  {
  $this->db->insert_batch('products',$data);
}

public function total_employee()
  {
  $query = $this->db->select('*')
                ->from('employees')
                ->where('compid',$_SESSION['compid'])
                ->get();

  $count_row = $query->num_rows();

  return $count_row;
}

public function total_user()
  {
  $query = $this->db->select('*')
                ->from('users')
                ->where('compid',$_SESSION['compid'])
                ->get();

  $count_row = $query->num_rows();

  return $count_row;
}

public function customer_fetch_data($compid)
  {
  $this->db->where('compid',$compid);
  $query = $this->db->get("customers");
  return $query->result();
}

public function insert_customer_data($data)
  {
  $this->db->insert_batch('customers',$data);
}

public function supplier_fetch_data($compid)
  {
  $this->db->order_by("supplierID","DESC");
  $this->db->where('compid',$compid);
  $query = $this->db->get("suppliers");
  return $query->result();
}

public function insert_supplier_data($data)
  {
  $this->db->insert_batch('suppliers',$data);
}

public function count_all_user()
  {
  $query = $this->db->select('*')
                ->from('users')
                ->where('userrole',2)
                ->get();

  $count_row = $query->num_rows();
  return $count_row;
}

public function count_all_active_user()
  {
  $query = $this->db->select('*')
                ->from('users')
                ->where('userrole',2)
                ->where('status','Active')
                ->get();

  $count_row = $query->num_rows();
  return $count_row;
}

public function count_all_inactive_user()
  {
  $query = $this->db->select('*')
                ->from('users')
                ->where('userrole',2)
                ->where('status','Inactive')
                ->get();

  $count_row = $query->num_rows();
  return $count_row;
}

public function count_all_today_user()
  {
  $d = date('d'); $m = date('m'); $y = date('Y');

  $query = $this->db->select('*')
                ->from('users')
                ->where('userrole',2)
                ->where('DAY(regdate)',$d)
                ->where('MONTH(regdate)',$m)
                ->where('YEAR(regdate)',$y)
                ->get();

  $count_row = $query->num_rows();
  return $count_row;
}

public function count_all_month_user()
  {
  $m = date('m'); $y = date('Y');

  $query = $this->db->select('*')
                ->from('users')
                ->where('userrole',2)
                ->where('MONTH(regdate)',$m)
                ->where('YEAR(regdate)',$y)
                ->get();

  $count_row = $query->num_rows();
  return $count_row;
}

public function get_page_and_function()
  {
  $query = $this->db->select('
              tbl_page_functions.pfunc_id,
              tbl_page_functions.pageid,
              tbl_page_functions.fcname,
              tbl_pages.pageid,
              tbl_pages.master_page,
              tbl_pages.cname,
              tbl_master_page_title.master_id,
              tbl_master_page_title.c_master_title')
            ->from('tbl_pages')
            ->join('tbl_master_page_title','tbl_master_page_title.master_id = tbl_pages.master_page','left')
            ->join('tbl_page_functions','tbl_page_functions.pageid = tbl_pages.pageid','left')
            ->get()
            ->result();
  return $query;
}

public function saveNewMaster_data($data)
  {
  $column = $data['master_title'] ;
  $table = 'tbl_user_m_permission';

  $fields = array(
    'preferences' => array('type' => 'INT','constraint' => 5 )
      );

  $fields2 = array(
    'preferences' => array(
      'name' => $column ,
      'type' => 'INT',
      'constraint' => 5
        ),
      );
    // $add = mysql_query("ALTER TABLE $table ADD $column INT( 1 ) NOT NULL");
  $this->load->dbforge();
  $this->dbforge->add_column('tbl_user_m_permission',$fields);

  $this->load->dbforge();
  $add = $this->dbforge->modify_column('tbl_user_m_permission',$fields2);
  // var_dump($add); exit();
  return $this->db->insert('tbl_master_page_title', $data); 
}

public function saveNewPage_data($data)
  {
  $column = $data['pagename'] ;
  $table = 'tbl_user_p_permission';

  $fields = array(
    'preferences' => array('type' => 'INT','constraint' => 5 )
      );

  $fields2 = array(
    'preferences' => array(
      'name' => $column,
      'type' => 'INT',
      'constraint' => 5
        ),
      );
    // $add = mysql_query("ALTER TABLE $table ADD $column INT( 1 ) NOT NULL");
  $this->load->dbforge();
  $this->dbforge->add_column('tbl_user_p_permission',$fields);

  $this->load->dbforge();
  $add = $this->dbforge->modify_column('tbl_user_p_permission',$fields2);
    // var_dump($add); exit();
  return $this->db->insert('tbl_pages', $data); 
}

public function saveNewPageFunction_data($data)
  {
  $column = $data['pfunc_name'] ;
  $table = 'tbl_user_f_permission';

  $fields = array(
    'preferences' => array('type' => 'INT','constraint' => 5 )
      );

  $fields2 = array(
    'preferences' => array(
      'name' => $column,
      'type' => 'INT',
      'constraint' => 5
        ),
      );
    // $add = mysql_query("ALTER TABLE $table ADD $column INT( 1 ) NOT NULL");
  $this->load->dbforge();
  $this->dbforge->add_column('tbl_user_f_permission',$fields);

  $this->load->dbforge();
  $add = $this->dbforge->modify_column('tbl_user_f_permission', $fields2);
    // var_dump($add); exit();
  return $this->db->insert('tbl_page_functions',$data); 
}

public function get_page_data_by_master($id)
  {
  $query = $this->db->select('*')
            ->from('tbl_pages')
            ->where('master_page',$id)
            ->get()
            ->result();
  return $query;
}

public function get_user_permission_data()
  {
  $emp = $this->db->select('compid')
                ->from('tbl_user_m_permission')
                ->get()
                ->result_array();
  //var_dump($emp); exit();
  $emp_id = array_map (function($value){
    return $value['compid'];
    },$emp);

  if ($emp_id == null) {
    $emp_id = 0;
    }

  $emps = $this->db->select('compid,name,compname')
                ->from('users')
                ->where_not_in('compid',$emp_id)
                ->where('userrole',2)
                ->get()
                ->result();
  return $emps;
}

public function get_help_reply_data($id)
  {
  $query = $this->db->select("help_support_reply.reply,users.name")
                    ->from('help_support_reply')
                    ->join('users','users.uid = help_support_reply.regby','left')
                    ->where('hp_id',$id)
                    ->get()
                    ->result();
  return $query;
}

public function get_user_notice_data($id)
  {
  $query = $this->db->select('*')
                  ->from('notice')
                  ->where('nid',$id)
                  ->get()
                  ->row();
  return $query; 
}

public function today_sales($cid)
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`totalAmount`) as total,SUM(`paidAmount`) as ptotal")
                  ->FROM('sales')
                  ->where('compid',$cid)
                  ->where('saleDate',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function today_purchases($cid)
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`totalPrice`) as total,SUM(`paidAmount`) as ptotal")
                  ->FROM('purchase')
                  ->where('compid',$cid)
                  ->where('purchaseDate',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function today_emp_payments($cid)
  {
  $d = date('d');
  $m = date('m');
  $y = date('Y');
  $query = $this->db->select("SUM(`salary`) as total")
                  ->FROM('employee_payment')
                  ->where('compid',$cid)
                  ->where('DAY(regdate)',$d)
                  ->where('MONTH(regdate)',$m)
                  ->where('YEAR(regdate)',$y)
                  ->get()
                  ->row();
  return $query;  
}

public function today_returns($cid)
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`paidAmount`) as total")
                  ->FROM('returns')
                  ->where('compid',$cid)
                  ->where('returnDate',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function today_cvouchers($cid)
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`totalamount`) as total")
                  ->FROM('vaucher')
                  ->where('compid',$cid)
                  ->WHERE('vauchertype','Credit Voucher')
                  ->where('voucherdate',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function today_dvouchers($cid)
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`totalamount`) as total")
                  ->FROM('vaucher')
                  ->where('compid',$cid)
                  ->WHERE('vauchertype','Debit Voucher')
                  ->where('voucherdate',$date)
                  ->get()
                  ->row();
  return $query;  
}

public function today_svouchers($cid)
  {
  $date = date('Y-m-d');
  $query = $this->db->select("SUM(`totalamount`) as total")
                  ->FROM('vaucher')
                  ->where('compid',$cid)
                  ->WHERE('vauchertype','Supplier Pay')
                  ->where('voucherdate',$date)
                  ->get()
                  ->row();
  return $query;  
}

// public function get_mall_emp_payments_data($month,$year)
//     {
//     $query = $this->db->select('*')
//                     ->from('employee_payment')
//                     ->where('compid',$_SESSION['compid'])
//                     ->where('MONTH(regdate)',$month)
//                     ->where('YEAR(regdate)',$year)
//                     ->get()
//                     ->result();
//     return $query; 
// }

// public function get_mall_returns_data($month,$year)
//     {
//     $query = $this->db->select('*')
//                     ->from('returns')
//                     ->where('compid',$_SESSION['compid'])
//                     ->where('MONTH(returnDate)',$month)
//                     ->where('YEAR(returnDate)',$year)
//                     ->get()
//                     ->result();
//     return $query; 
// }

// public function get_mall_vouchers_data($month,$year)
//     {
//     $query = $this->db->select('*')
//                     ->from('vaucher')
//                     ->where('compid',$_SESSION['compid'])
//                     ->where('MONTH(voucherdate)',$month)
//                     ->where('YEAR(voucherdate)',$year)
//                     ->get()
//                     ->result();
//     return $query; 
// }

// public function get_yall_emp_payments_data($year)
//     {
//     $query = $this->db->select('*')
//                     ->from('employee_payment')
//                     //->where('company',$_SESSION['company'])
//                     ->where('YEAR(regdate)',$year)
//                     ->get()
//                     ->result();
//     return $query; 
// }

public function get_salary33_emp($id,$id2)
  {
  $emp = $this->db->select('empid')
                ->from('employee_payment')
                ->where('year',$id)
                ->where('month',$id2)
                ->where('compid',$_SESSION['compid'])
                ->get()
                ->result_array();
  //var_dump($emp); exit();
  $emp_id = array_map (function($value)
    {
    return $value['empid'];
    },$emp);

  if($emp_id == null)
    {
    $emp_id = 0;
    }

  $emps = $this->db->select('
                    employees.empid,
                    employees.empName,
                    employees.joinDate,
                    employees.salary,
                    department.dptName')
                ->from('employees')
                ->join('department','department.dptid = employees.dptid','left')
                ->where_not_in('employees.empid',$emp_id)
                ->get()
                ->result();
  return $emps;
}

public function get_salary_emp($id,$id2,$id3)
  {
  $emp = $this->db->select('empid')
                ->from('employee_payment')
                ->where('month',$id)
                ->where('year',$id2)
                ->where('empid',$id3)
                ->get()
                ->row();
  //var_dump($emp); exit();
    //   $emp_id = array_map (function($value)
    //     {
    //     return $value['empid'];
    //     },$emp);

  if($emp)
    {
    $empid = $emp->empid;
    }
  else
    {
    $empid = $id3;
    }

  $emps = $this->db->select('
                    employees.empid,
                    employees.empName,
                    employees.joinDate,
                    employees.salary,
                    department.dptName,
                    SUM(employee_payment.salary) as total')
                ->from('employees')
                ->join('department','department.dptid = employees.dptid','left')
                ->join('employee_payment','employee_payment.empid = employees.empid','left')
                ->where('employees.empid',$empid)
                ->get()
                ->row();
  return $emps;
}

public function get_salary_empOne($id,$id2,$id3)
  {
  $emp = $this->db->select('empid')
                ->from('employee_payment')
                ->where('month',$id)
                ->where('year',$id2)
                ->where('empid',$id3)
                ->get()
                ->row();
  //var_dump($emp); exit();
    //   $emp_id = array_map (function($value)
    //     {
    //     return $value['empid'];
    //     },$emp);

  if($emp)
    {
    $empid = $emp->empid;
    }
  else
    {
    $empid = $id3;
    }

  $emps = $this->db->select('
                    employees.empid,
                    employees.empName,
                    employees.joinDate,
                    employees.salary,
                    SUM(employee_payment.salary) as total')
                ->from('employees')
                ->join('department','department.dptid = employees.dptid','left')
                ->join('employee_payment','employee_payment.empid = employees.empid','left')
                ->where('employees.empid',$empid)
                ->get()
                ->row();
  return $emps;
}
public function get_order_track_data($oid)
  {
  $query = $this->db->select('order.*,customers.custName,customers.custMobile,customers.custEmail,customers.custAddress')
              ->from('order')
              ->join('customers','customers.custid = order.custid','left')
              ->where('oCode',$oid)
              ->get()
              ->row();
  return $query;  
}

public function get_morder_track_data($oid)
  {
  $query = $this->db->select('order.*,customers.custName,customers.custMobile,customers.custEmail,customers.custAddress')
              ->from('order')
              ->join('customers','customers.custid = order.custid','left')
              ->where_in('customers.custMobile',$oid)
              ->get()
              ->result();
  return $query;  
}

public function sales_fetch_data()
  {
  $query = $this->db->select('sales.*,customers.custName,customers.custMobile,customers.custEmail,customers.custAddress,users.name,users.mobile as umobile')
              ->from('sales')
              ->join('customers','customers.custid = sales.customerID','left')
              ->join('users','users.uid = sales.regby','left')
              ->where('sales.compid',$_SESSION['compid'])
              ->get()
              ->result();
  return $query;  
}

public function purchase_mdue_data($month, $year)
  {
  $query = $this->db->select('SUM(purchase.due) as total')
                  ->from('purchase')
                  ->where('totalPrice > paidAmount')
                  ->where('MONTH(purchaseDate)',$month)
                  ->where('YEAR(purchaseDate)',$year)
                  ->get()
                  ->row();
  return $query;  
}

public function get_dproduct_sstock_data()
  {
  $emp = $this->db->select('productID')
        ->from('products')
        ->get()
        ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
  return $value['productID'];
  },$emp);
    //var_dump($emp_id); exit();
  if($emp_id == NULL)
      {
      $empid = 0;
      }
  else{
      $empid = $emp_id;
      }
      
  $query = $this->db->select('stock.*,products.productName,products.productcode,products.pprice,products.sprice')
                ->from('stock')
                ->join('products','products.productID = stock.product','left')
                ->where('dtquantity > 0')
                ->where_in('stock.product',$empid)
                ->get()
                ->result();
  return $query;  
}

public function damage_product_data()
  {
  $query = $this->db->select('stock.*,products.productName,products.productcode')
                ->from('stock')
                ->join('products','products.productID = stock.product','left')
                ->where('dtquantity > 0')
                ->get()
                ->result();
  return $query;  
}

public function count_total_order()
  {
  $query = $this->db->select('*')
                ->from('order')
                ->get();

  $count_row = $query->num_rows();

  if($count_row)
    {
    return $count_row;
    }
  else
    {
    return 0;
    }
}

public function count_total_porder()
  {
  $query = $this->db->select('*')
                ->from('order')
                ->where('status',1)
                ->get();

  $count_row = $query->num_rows();

  if($count_row)
    {
    return $count_row;
    }
  else
    {
    return 0;
    }
}

public function count_total_corder()
  {
  $query = $this->db->select('*')
                ->from('order')
                ->where('status',5)
                ->get();

  $count_row = $query->num_rows();

  if($count_row)
    {
    return $count_row;
    }
  else
    {
    return 0;
    }
}

public function count_total_sorder()
  {
  $query = $this->db->select('*')
                ->from('order')
                ->where('status',2)
                ->get();

  $count_row = $query->num_rows();

  if($count_row)
    {
    return $count_row;
    }
  else
    {
    return 0;
    }
}

public function get_transfer_account_data()
  {
  $query = $this->db->select('*')
                ->from('transfer_account')
                ->get()
                ->result();
  return $query; 
}

public function get_dtransfer_account_data($sdate,$edate)
  {
  $query = $this->db->select('*')
                ->from('transfer_account')
                ->where('DATE(regdate) >=',$sdate)
                ->where('DATE(regdate) <=',$edate)
                ->get()
                ->result();
  return $query; 
}

public function get_mtransfer_account_data($month,$year)
  {
  $query = $this->db->select('*')
                ->from('transfer_account')
                ->where('MONTH(regdate)',$month)
                ->where('YEAR(regdate)',$year)
                ->get()
                ->result();
  return $query; 
}

public function get_ytransfer_account_data($year)
  {
  $query = $this->db->select('*')
                ->from('transfer_account')
                ->where('YEAR(regdate)',$year)
                ->get()
                ->result();
  return $query; 
}

public function get_sales_emi_payment($id)
  {
  $query = $this->db->select('*')
              ->from('sales')
              ->where('saleID',$id)
              ->get()
              ->row();
  return $query; 
}

public function get_sub_account_type_data($id)
  {
  $query = $this->db->select('*')
                    ->from('sub_AType')
                    ->where('satid',$id)
                    ->get()
                    ->row();

  return $query;  
}

public function get_sub_account_data($id)
  {
  $query = $this->db->select('*')
                    ->from('sub_AType')
                    ->where('catid',$id)
                    ->get()
                    ->result();

  return $query;  
}

public function get_chart_account_type_data($id)
  {
  $query = $this->db->select('*')
                    ->from('chart_atype')
                    ->where('cat_id',$id)
                    ->get()
                    ->row();

  return $query;  
}

public function get_chart_accounting_data($id)
  {
  $query = $this->db->select("chart_account.*,chart_atype.*")
                ->from('chart_account')
                ->join('chart_atype','chart_atype.cat_id = chart_account.catype','left')
                ->where('ca_id',$id)
                ->get()
                ->row();

  return $query;  
}

public function account_type_data($id)
  {
  $catid = $this->db->select('catid')
                    ->from('chart_account')
                    ->where('ca_id',$id)
                    ->get()
                    ->row();

  $query = $this->db->select('cat_id,caType')
                    ->from('chart_atype')
                    ->where('catid',$catid->catid)
                    ->get()
                    ->result();

  return $query;  
}

public function get_tsale_tb_data()
  {
  $query = $this->db->select("SUM(`paidAmount`) as total")
                  ->FROM('sales')
                  ->get()
                  ->row();
  return $query;  
}

public function get_tdvoucher_tb_data()
  {
  $query = $this->db->select("SUM(`totalamount`) as total")
                  ->FROM('vaucher')
                  ->where_not_in('vauchertype','Credit Voucher')
                  ->get()
                  ->row();
  return $query;  
}

public function get_asset_bs_data()
  {
  $query = $this->db->select("*")
                ->from('chart_atype')
                ->where('catid',1)
                ->get()
                ->result();
  return $query;  
}

public function get_liability_bs_data()
  {
  $query = $this->db->select("*")
                ->from('chart_atype')
                ->where('catid',2)
                ->get()
                ->result();
  return $query;  
}

public function get_equity_bs_data()
  {
  $query = $this->db->select("*")
                ->from('chart_atype')
                ->where('catid',3)
                ->get()
                ->result();
  return $query;  
}

public function get_income_bs_data()
  {
  $query = $this->db->select("*")
                ->from('chart_atype')
                ->where('catid',4)
                ->get()
                ->result();
  return $query;  
}

public function get_expense_bs_data()
  {
  $query = $this->db->select("*")
                ->from('chart_atype')
                ->where('catid',5)
                ->get()
                ->result();
  return $query;  
}

public function get_sale_bs_data()
  {
  $query = $this->db->select("*")
                ->from('sales')
                ->get()
                ->result();
  return $query;  
}

public function get_cvoucher_bs_data()
  {
  $query = $this->db->select("*")
                ->from('vaucher')
                ->where('vauchertype','Credit Voucher')
                ->get()
                ->result();
  return $query;  
}

public function get_purchase_bs_data()
  {
  $query = $this->db->select("*")
                ->from('purchase')
                ->get()
                ->result();
  return $query;  
}

public function get_payment_bs_data()
  {
  $query = $this->db->select("*")
                ->from('employee_payment')
                ->get()
                ->result();
  return $query;  
}

public function get_dvoucher_gl_data()
  {
  $query = $this->db->select("*")
                ->from('vaucher')
                ->where_not_in('vauchertype','Credit Voucher')
                ->get()
                ->result();
  return $query;  
}

public function get_purchase_type_data($id)
  {
  $query = $this->db->select("*")
                ->from('purchase_type')
                ->where('ptid',$id)
                ->get()
                ->row();
  return $query;  
}

public function get_equity_data()
  {
  $query = $this->db->select("chart_account.*,users.name")
                ->from('chart_account')
                ->join('users','users.uid = chart_account.regby','left')
                ->where('chart_account.catid',3)
                ->where('chart_account.caamount >',0)
                ->get()
                ->result();
  return $query;  
}

public function get_bank_data()
  {
  $query = $this->db->select("*")
                ->from('bankaccount')
                ->get()
                ->result();
  return $query;  
}

public function get_cash_data()
  {
  $query = $this->db->select("*")
                ->from('cash')
                ->get()
                ->result();
  return $query;  
}

public function get_mobile_data()
  {
  $query = $this->db->select("*")
                ->from('mobileaccount')
                ->get()
                ->result();
  return $query;  
}

public function get_sale_due_data()
  {
  $payment = $this->db->select('SUM(pAmount) as total')
                  ->from('sales_payment')
                  ->get()
                  ->row();

  $purchase = $this->db->select('dAmount')
                  ->from('sales')
                  ->get()
                  ->row();
  if($purchase && $payment)
    {
    $query = $purchase->dAmount - $payment->total;
    }
  else if($purchase)
    {
    $query = $payment->total;
    }
  else
    {
    $query = 0;
    }
    
  return $query;
}

public function get_assets_data()
  {
  $query = $this->db->select("chart_account.*,chart_atype.caType")
                ->from('chart_account')
                ->join('chart_atype','chart_atype.cat_id = chart_account.catype','left')
                ->where('chart_account.catid',1)
                ->where('chart_account.caamount >',0)
                ->get()
                ->result();
  return $query;  
}

public function get_purchse_due_data()
  {
  $payment = $this->db->select('SUM(pAmount) as total')
                  ->from('purchase_payment')
                  ->get()
                  ->row();

  $purchase = $this->db->select('dAmount')
              ->from('purchase')
              ->get()
              ->row();
  if($purchase && $payment)
    {
    $query = $purchase->dAmount - $payment->total;
    }
  else if($purchase)
    {
    $query = $payment->total;
    }
  else
    {
    $query = 0;
    }
  return $query; 
}

public function get_liability_data()
  {
  $query = $this->db->select("chart_account.*,chart_atype.caType")
                ->from('chart_account')
                ->join('chart_atype','chart_atype.cat_id = chart_account.catype','left')
                ->where('chart_account.catid',2)
                ->where('chart_account.caamount >',0)
                ->get()
                ->result();
  return $query;  
}

public function get_expense_data()
  {
  $query = $this->db->select("chart_account.*,chart_atype.caType")
                ->from('chart_account')
                ->join('chart_atype','chart_atype.cat_id = chart_account.catype','left')
                ->where('chart_account.catid',5)
                ->where('chart_account.caamount >',0)
                ->get()
                ->result();
  return $query;  
}

public function get_service_data($id)
  {
  $query = $this->db->select("*")
                ->from('services')
                ->where('sid',$id)
                ->get()
                ->row();
  return $query;  
}

public function get_sale_service_data()
  {
  $query = $this->db->select('
                          services_sale.*,
                          customers.custid,
                          customers.custName,
                          customers.custMobile,
                          customers.custEmail,
                          customers.custAddress,
                          users.empid,
                          users.name,
                          users.mobile as umobile')
                  ->from('services_sale')
                  ->join('customers','customers.custid = services_sale.custid','left')
                  ->join('users','users.uid = services_sale.regby','left')
                  ->get()
                  ->result();
  return $query;  
}

public function get_dsale_service_data($sdate,$edate,$custid)
  {
  if($custid == 'All')
    {
    $query = $this->db->select('
                          services_sale.*,
                          customers.custid,
                          customers.custName,
                          customers.custMobile,
                          customers.custEmail,
                          customers.custAddress,
                          users.empid,
                          users.name,
                          users.mobile as umobile')
                    ->from('services_sale')
                    ->join('customers','customers.custid = services_sale.custid','left')
                    ->join('users','users.uid = services_sale.regby','left')
                    ->where('services_sale.ssDate >=',$sdate)
                    ->where('services_sale.ssDate <=',$edate)
                    ->get()
                    ->result();
    }
  else
    {
    $query = $this->db->select('
                          services_sale.*,
                          customers.custid,
                          customers.custName,
                          customers.custMobile,
                          customers.custEmail,
                          customers.custAddress,
                          users.empid,
                          users.name,
                          users.mobile as umobile')
                    ->from('services_sale')
                    ->join('customers','customers.custid = services_sale.custid','left')
                    ->join('users','users.uid = services_sale.regby','left')
                    ->where('services_sale.ssDate >=',$sdate)
                    ->where('services_sale.ssDate <=',$edate)
                    ->where('services_sale.custid',$custid)
                    ->get()
                    ->result();
    }
  return $query;  
}

public function get_msale_service_data($month,$year,$custid)
  {
  if($custid == 'All')
    {
    $query = $this->db->select('
                          services_sale.*,
                          customers.custid,
                          customers.custName,
                          customers.custMobile,
                          customers.custEmail,
                          customers.custAddress,
                          users.empid,
                          users.name,
                          users.mobile as umobile')
                    ->from('services_sale')
                    ->join('customers','customers.custid = services_sale.custid','left')
                    ->join('users','users.uid = services_sale.regby','left')
                    ->where('MONTH(services_sale.ssDate)',$month)
                    ->where('YEAR(services_sale.ssDate)',$year)
                    ->get()
                    ->result();
    }
  else
    {
    $query = $this->db->select('
                          services_sale.*,
                          customers.custid,
                          customers.custName,
                          customers.custMobile,
                          customers.custEmail,
                          customers.custAddress,
                          users.empid,
                          users.name,
                          users.mobile as umobile')
                    ->from('services_sale')
                    ->join('customers','customers.custid = services_sale.custid','left')
                    ->join('users','users.uid = services_sale.regby','left')
                    ->where('MONTH(services_sale.ssDate)',$month)
                    ->where('YEAR(services_sale.ssDate)',$year)
                    ->where('services_sale.custid',$custid)
                    ->get()
                    ->result();
    }
  return $query;  
}

public function get_ysale_service_data($year,$custid)
  {
  if($custid == 'All')
    {
    $query = $this->db->select('
                          services_sale.*,
                          customers.custid,
                          customers.custName,
                          customers.custMobile,
                          customers.custEmail,
                          customers.custAddress,
                          users.empid,
                          users.name,
                          users.mobile as umobile')
                    ->from('services_sale')
                    ->join('customers','customers.custid = services_sale.custid','left')
                    ->join('users','users.uid = services_sale.regby','left')
                    ->where('YEAR(services_sale.ssDate)',$year)
                    ->get()
                    ->result();
    }
  else
    {
    $query = $this->db->select('
                          services_sale.*,
                          customers.custid,
                          customers.custName,
                          customers.custMobile,
                          customers.custEmail,
                          customers.custAddress,
                          users.empid,
                          users.name,
                          users.mobile as umobile')
                    ->from('services_sale')
                    ->join('customers','customers.custid = services_sale.custid','left')
                    ->join('users','users.uid = services_sale.regby','left')
                    ->where('YEAR(services_sale.ssDate)',$year)
                    ->where('services_sale.custid',$custid)
                    ->get()
                    ->result();
    }
  return $query;  
}


public function get_service_payment($id)
  {
  $payment = $this->db->select('SUM(pAmount) as total')
                  ->from('service_payment')
                  ->where('ssid',$id)
                  ->get()
                  ->row();

  $purchase = $this->db->select('dAmount')
                  ->from('services_sale')
                  ->where('ssid',$id)
                  ->get()
                  ->row();
  if($payment)
    {
    $query = $purchase->dAmount - $payment->total;
    }
  else
    {
    $query = $payment->total;
    }
  return $query; 
}

public function get_service_payment_data($id)
  {
  $query = $this->db->select("SUM(pAmount) as total")
                ->from('service_payment')
                ->where('ssid',$id)
                ->get()
                ->row();
  return $query;  
}

public function get_voucher_ledger_data()
  {
  $query = $this->db->select("chart_account.*,caccount_type.catName,chart_atype.caType,sub_AType.scaType")
                ->from('chart_account')
                ->join('caccount_type','caccount_type.catid = chart_account.catid','left')
                ->join('chart_atype','chart_atype.cat_id = chart_account.catype','left')
                ->join('sub_AType','sub_AType.satid = chart_account.satid','left')
                ->get()
                ->result();
  return $query;  
}

public function get_dvoucher_ledger_data($sdate,$edate,$vuType)
  {
  if($vuType = 'All')
    {
    $query = $this->db->select("chart_account.*,caccount_type.catName,chart_atype.caType,sub_AType.scaType")
                ->from('chart_account')
                ->join('caccount_type','caccount_type.catid = chart_account.catid','left')
                ->join('chart_atype','chart_atype.cat_id = chart_account.catype','left')
                ->join('sub_AType','sub_AType.satid = chart_account.satid','left')
                ->where('DATE(chart_account.regdate) >=',$sdate)
                ->where('DATE(chart_account.regdate) <=',$edate)
                ->get()
                ->result();
    }
  else
    {
    $query = $this->db->select("chart_account.*,caccount_type.catName,chart_atype.caType,sub_AType.scaType")
                ->from('chart_account')
                ->join('caccount_type','caccount_type.catid = chart_account.catid','left')
                ->join('chart_atype','chart_atype.cat_id = chart_account.catype','left')
                ->join('sub_AType','sub_AType.satid = chart_account.satid','left')
                ->where('DATE(chart_account.regdate) >=',$sdate)
                ->where('DATE(chart_account.regdate) <=',$edate)
                ->where('chart_account.cadetails',$vuType)
                ->get()
                ->result();
    }
  return $query;  
}

public function get_mvoucher_ledger_data($month,$year,$vuType)
  {
  if($vuType = 'All')
    {
    $query = $this->db->select("chart_account.*,caccount_type.catName,chart_atype.caType,sub_AType.scaType")
                ->from('chart_account')
                ->join('caccount_type','caccount_type.catid = chart_account.catid','left')
                ->join('chart_atype','chart_atype.cat_id = chart_account.catype','left')
                ->join('sub_AType','sub_AType.satid = chart_account.satid','left')
                ->where('MONTH(chart_account.regdate)',$month)
                ->where('YEAR(chart_account.regdate)',$year)
                ->get()
                ->result();
    }
  else
    {
    $query = $this->db->select("chart_account.*,caccount_type.catName,chart_atype.caType,sub_AType.scaType")
                ->from('chart_account')
                ->join('caccount_type','caccount_type.catid = chart_account.catid','left')
                ->join('chart_atype','chart_atype.cat_id = chart_account.catype','left')
                ->join('sub_AType','sub_AType.satid = chart_account.satid','left')
                ->where('MONTH(chart_account.regdate)',$month)
                ->where('YEAR(chart_account.regdate)',$year)
                ->where('chart_account.cadetails',$vuType)
                ->get()
                ->result();
    }
  return $query;  
}

public function get_yvoucher_ledger_data($year,$vuType)
  {
  if($vuType = 'All')
    {
    $query = $this->db->select("chart_account.*,caccount_type.catName,chart_atype.caType,sub_AType.scaType")
                ->from('chart_account')
                ->join('caccount_type','caccount_type.catid = chart_account.catid','left')
                ->join('chart_atype','chart_atype.cat_id = chart_account.catype','left')
                ->join('sub_AType','sub_AType.satid = chart_account.satid','left')
                ->where('YEAR(chart_account.regdate)',$year)
                ->get()
                ->result();
    }
  else
    {
    $query = $this->db->select("chart_account.*,caccount_type.catName,chart_atype.caType,sub_AType.scaType")
                ->from('chart_account')
                ->join('caccount_type','caccount_type.catid = chart_account.catid','left')
                ->join('chart_atype','chart_atype.cat_id = chart_account.catype','left')
                ->join('sub_AType','sub_AType.satid = chart_account.satid','left')
                ->where('YEAR(chart_account.regdate)',$year)
                ->where('chart_account.cadetails',$vuType)
                ->get()
                ->result();
    }
  return $query;  
}

public function get_product_chassis_data()
  {
  $emp = $this->db->select('spChassis')
                ->from('sale_product')
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
  return $value['spChassis'];
  },$emp);
    //var_dump($emp_id); exit();
  if($emp_id)
    {
    $empid = $emp_id;
    }
  else
    {
    $empid = 0;
    }
      
  $query = $this->db->select('ppChassis')
                ->from('purchase_chassis')
                //->where_not_in('ppChassis',$empid)
                ->group_by('ppChassis')
                ->get()
                ->result();
  return $query;  
}

public function get_product_engine_data()
  {
  $emp = $this->db->select('spEngine')
                ->from('sale_product')
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
  return $value['spEngine'];
  },$emp);
    //var_dump($emp_id); exit();
  if($emp_id)
    {
    $empid = $emp_id;
    }
  else
    {
    $empid = 0;
    }
      
  $query = $this->db->select('ppEngine')
                ->from('purchase_chassis')
               // ->where_not_in('ppEngine',$empid)
                ->group_by('ppEngine')
                ->get()
                ->result();
  return $query;  
}

public function get_product_color_data()
  {
  $emp = $this->db->select('spColor')
                ->from('sale_product')
                ->get()
                ->result_array();
    //var_dump($emp); exit();
  $emp_id = array_map (function($value){
  return $value['spColor'];
  },$emp);
    //var_dump($emp_id); exit();
  if($emp_id)
    {
    $empid = $emp_id;
    }
  else
    {
    $empid = 0;
    }
      
  $query = $this->db->select('ppColor')
                ->from('purchase_chassis')
                //->where_not_in('ppColor',$empid)
                ->group_by('ppColor')
                ->get()
                ->result();
  return $query;  
}

public function get_salesdata($sid)
  {
  return $this->db->select('
                    sales.*,
                    customers.*')
                ->from('sales')
                ->join('customers','customers.custid = sales.custid','left')
                ->where('said',$sid)
                ->get()
                ->row();
}

public function get_sales_products_data($sid)
  {
  return $this->db->select('sale_product.*,products.pCode,products.pName')
                ->from('sale_product')
                ->join('products','products.pid = sale_product.pid','left')
                ->where('said',$sid)
                ->get()
                ->result();
}

public function get_currency_data($id)
  {
  return $this->db->select('*')
                ->from('currency')
                ->where('cid',$id)
                ->get()
                ->row();
}

public function get_lc_payment($id)
  {
  $payment = $this->db->select('SUM(cAmount) as total')
              ->from('lc_payment')
              ->where('lcid',$id)
              ->get()
              ->row();

  $purchase = $this->db->select('dAmount')
              ->from('lc_management')
              ->where('lcid',$id)
              ->get()
              ->row();
  if($payment)
    {
    $query = $purchase->dAmount - $payment->total;
    }
  else
    {
    $query = $payment->total;
    }
  return $query; 
}

public function get_courier_account($id)
  {
  return $this->db->select('*')
                ->from('courier_account')
                ->where('caid',$id)
                ->get()
                ->row();
}



public function get_costing_data()
  {
  $query = $this->db->select('costing.*,products.pName,products.partNo,products.hsn')
                ->from('costing')
                ->join('products','products.pid = costing.pid','left')
                ->get()
                ->result();
  return $query;  
}

public function get_dcosting_data($sdate,$edate)
  {
  $query = $this->db->select('costing.*,products.pName,products.partNo,products.hsn')
                ->from('costing')
                ->join('products','products.pid = costing.pid','left')
                ->where('DATE(costing.regdate) >=',$sdate)
                ->where('DATE(costing.regdate) <=',$edate)
                ->get()
                ->result();
  return $query;  
}

public function get_mcosting_data($month,$year)
  {
  $query = $this->db->select('costing.*,products.pName,products.partNo,products.hsn')
                ->from('costing')
                ->join('products','products.pid = costing.pid','left')
                ->where('MONTH(costing.regdate)',$month)
                ->where('YEAR(costing.regdate)',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_ycosting_data($year)
  {
  $query = $this->db->select('costing.*,products.pName,products.partNo,products.hsn')
                ->from('costing')
                ->join('products','products.pid = costing.pid','left')
                ->where('YEAR(costing.regdate)',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_courier_sales_data()
  {
  $query = $this->db->select('
                          sales.*,
                          customers.custCode,
                          customers.custName,
                          customers.custMobile,
                          users.name,
                          users.mobile,
                          courier.courierName')
                  ->from('sales')
                  ->join('customers','customers.custid = sales.custid','left')
                  ->join('users','users.uid = sales.regby','left')
                  ->join('courier','courier.id = sales.id','left')
                  ->get()
                  ->result();
  return $query;  
}

public function get_courier_dsales_data($sdate,$edate,$caid)
  {
  if($caid == 'All')
    {
    $query = $this->db->select('
                          sales.*,
                          customers.custCode,
                          customers.custName,
                          customers.custMobile,
                          users.name,
                          users.mobile,
                          courier.courierName')
                    ->from('sales')
                    ->join('customers','customers.custid = sales.custid','left')
                    ->join('users','users.uid = sales.regby','left')
                    ->join('courier','courier.id = sales.id','left')
                    ->where('sales.saDate >=',$sdate)
                    ->where('sales.saDate <=',$edate)
                    ->get()
                    ->result();
    }
  else
    {
    $query = $this->db->select('
                          sales.*,
                          customers.custCode,
                          customers.custName,
                          customers.custMobile,
                          users.name,
                          users.mobile,
                          courier.courierName')
                    ->from('sales')
                    ->join('customers','customers.custid = sales.custid','left')
                    ->join('users','users.uid = sales.regby','left')
                    ->join('courier','courier.id = sales.id','left')
                    ->where('sales.saDate >=',$sdate)
                    ->where('sales.saDate <=',$edate)
                    ->where('sales.id',$caid)
                    ->get()
                    ->result();
    }
  return $query;  
}

public function get_courier_msales_data($month,$year,$caid)
  {
  if($caid == 'All')
    {
    $query = $this->db->select('
                          sales.*,
                          customers.custCode,
                          customers.custName,
                          customers.custMobile,
                          users.name,
                          users.mobile,
                          courier.courierName')
                    ->from('sales')
                    ->join('customers','customers.custid = sales.custid','left')
                    ->join('users','users.uid = sales.regby','left')
                    ->join('courier','courier.id = sales.id','left')
                    ->where('MONTH(sales.saDate)',$month)
                    ->where('YEAR(sales.saDate)',$year)
                    ->get()
                    ->result();
    }
  else
    {
    $query = $this->db->select('
                          sales.*,
                          customers.custCode,
                          customers.custName,
                          customers.custMobile,
                          users.name,
                          users.mobile,
                          courier.courierName')
                    ->from('sales')
                    ->join('customers','customers.custid = sales.custid','left')
                    ->join('users','users.uid = sales.regby','left')
                    ->join('courier','courier.id = sales.id','left')
                    ->where('MONTH(sales.saDate)',$month)
                    ->where('YEAR(sales.saDate)',$year)
                    ->where('sales.id',$caid)
                    ->get()
                    ->result();
    }
  return $query;
}

public function get_courier_ysales_data($year,$caid)
  {
  if($caid == 'All')
    {
    $query = $this->db->select('
                          sales.*,
                          customers.custCode,
                          customers.custName,
                          customers.custMobile,
                          users.name,
                          users.mobile,
                          courier.courierName')
                    ->from('sales')
                    ->join('customers','customers.custid = sales.custid','left')
                    ->join('users','users.uid = sales.regby','left')
                    ->join('courier','courier.id = sales.id','left')
                    ->where('YEAR(sales.saDate)',$year)
                    ->get()
                    ->result();
    }
  else
    {
    $query = $this->db->select('
                          sales.*,
                          customers.custCode,
                          customers.custName,
                          customers.custMobile,
                          users.name,
                          users.mobile,
                          courier.courierName')
                    ->from('sales')
                    ->join('customers','customers.custid = sales.custid','left')
                    ->join('users','users.uid = sales.regby','left')
                    ->join('courier','courier.id = sales.id','left')
                    ->where('YEAR(sales.saDate)',$year)
                    ->where('sales.id',$caid)
                    ->get()
                    ->result();
    }
  return $query;
}


public function get_purchase_total_quantity()
  {
  $purchase = $this->db->select('quantity,pid,ppid')
                      ->from('purchase_product')
                      ->order_by('puid','ASC')
                      ->get()
                      ->result();
  
  foreach($purchase as $value)
    {
    $puqnt = $this->db->select('SUM(quantity) as total')
                      ->from('purchase_product')
                      ->where('pid',$value->pid)
                      ->where('ppid <=',$value->ppid)
                      ->get()
                      ->row();
    $purdata = array(
      'tpQnt'    => $puqnt->total,
      'tpStatus' => 0
            );

    $where = array(
      'pid' => $value->pid,
      'ppid' => $value->ppid
            );
    $this->db->where($where);
    $this->db->update('purchase_product',$purdata);
    }
  return true; 
}

public function get_sales_total_quantity()
  {
  $purchase = $this->db->select('quantity,pid,spid')
                      ->from('sale_product')
                      ->where('tsStatus',1)
                      ->order_by('spid','ASC')
                      ->get()
                      ->result();
  
  foreach($purchase as $value)
    {
    $puqnt = $this->db->select('SUM(quantity) as total')
                      ->from('sale_product')
                      ->where('pid',$value->pid)
                      ->where('spid <=',$value->spid)
                      ->get()
                      ->row();
    $purdata = array(
      'tsQnt'    => $puqnt->total,
      'tsStatus' => 0
            );

    $where = array(
      'pid' => $value->pid,
      'spid' => $value->spid
            );
    $this->db->where($where);
    $this->db->update('sale_product',$purdata);
    }
  return true; 
}


public function get_purchase_product_ledger_data($pid)
  {
  $query = $this->db->select("purchase_product.*,purchase.puDate,purchase.challanNo")
                ->from('purchase_product')
                ->join('purchase','purchase.puid = purchase_product.puid','left')
                //->where('purchase.compid',$_SESSION['compid'])
                ->where('purchase_product.pid',$pid)
                ->get()
                ->result();
  return $query;  
}

public function get_sale_product_ledger_data($pid)
  {
  $query = $this->db->select("sale_product.*,sales.saDate,sales.invoice")
                ->from('sale_product')
                ->join('sales','sales.said = sale_product.said','left')
                //->where('sales.compid',$_SESSION['compid'])
                ->where('sale_product.pid',$pid)
                ->get()
                ->result();
  return $query;  
}

public function get_preturn_product_ledger_data($pid)
  {
  $query = $this->db->select("preturns_product.*,preturns.prDate,preturns.prCode")
                ->from('preturns_product')
                ->join('preturns','preturns.prid = preturns_product.prid','left')
                //->where('preturns.compid',$_SESSION['compid'])
                ->where('preturns_product.pid',$pid)
                ->get()
                ->result();
  return $query;  
}

public function get_sreturn_product_ledger_data($pid)
  {
  $query = $this->db->select("returns_product.*,returns.rDate,returns.rCode")
                ->from('returns_product')
                ->join('returns','returns.rid = returns_product.rid','left')
                //->where('returns.compid',$_SESSION['compid'])
                ->where('returns_product.pid',$pid)
                ->get()
                ->result();
  return $query;  
}

public function get_rmanufacture_product_ledger_data($pid)
  {
  $query = $this->db->select("*")
                ->from('costing')
                ->where('pid',$pid)
                ->get()
                ->result();
  return $query;  
}

public function get_purchase_dproduct_ledger_data($pid,$sdate,$edate)
  {
  $query = $this->db->select("purchase_product.*,purchase.puDate,purchase.challanNo")
                ->from('purchase_product')
                ->join('purchase','purchase.puid = purchase_product.puid','left')
                //->where('purchase.compid',$_SESSION['compid'])
                ->where('purchase_product.pid',$pid)
                ->where('purchase.puDate >=',$sdate)
                ->where('purchase.puDate <=',$edate)
                ->get()
                ->result();
  return $query;  
}

public function get_sale_dproduct_ledger_data($pid,$sdate,$edate)
  {
  $query = $this->db->select("sale_product.*,sales.saDate,sales.invoice")
                ->from('sale_product')
                ->join('sales','sales.said = sale_product.said','left')
                //->where('sales.compid',$_SESSION['compid'])
                ->where('sale_product.pid',$pid)
                ->where('sales.saDate >=',$sdate)
                ->where('sales.saDate <=',$edate)
                ->get()
                ->result();
  return $query;  
}

public function get_preturn_dproduct_ledger_data($pid,$sdate,$edate)
  {
  $query = $this->db->select("preturns_product.*,preturns.prDate,preturns.prCode")
                ->from('preturns_product')
                ->join('preturns','preturns.prid = preturns_product.prid','left')
                //->where('preturns.compid',$_SESSION['compid'])
                ->where('preturns_product.pid',$pid)
                ->where('preturns.prDate >=',$sdate)
                ->where('preturns.prDate <=',$edate)
                ->get()
                ->result();
  return $query;  
}

public function get_sreturn_dproduct_ledger_data($pid,$sdate,$edate)
  {
  $query = $this->db->select("returns_product.*,returns.rDate,returns.rCode")
                ->from('returns_product')
                ->join('returns','returns.rid = returns_product.rid','left')
                ///->where('returns.compid',$_SESSION['compid'])
                ->where('returns_product.pid',$pid)
                ->where('returns.srDate >=',$sdate)
                ->where('returns.srDate <=',$edate)
                ->get()
                ->result();
  return $query;  
}

public function get_rmanufacture_dproduct_ledger_data($pid,$sdate,$edate)
  {
  $query = $this->db->select("*")
                ->from('costing')
                ->where('pid',$pid)
                ->where('DATE(regdate) >=',$sdate)
                ->where('DATE(regdate) <=',$edate)
                ->get()
                ->result();
  return $query;  
}

public function get_purchase_mproduct_ledger_data($pid,$month,$year)
  {
  $query = $this->db->select("purchase_product.*,purchase.puDate,purchase.challanNo")
                ->from('purchase_product')
                ->join('purchase','purchase.puid = purchase_product.puid','left')
                //->where('purchase.compid',$_SESSION['compid'])
                ->where('purchase_product.pid',$pid)
                ->where('MONTH(puDate)',$month)
                ->where('YEAR(puDate)',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_sale_mproduct_ledger_data($pid,$month,$year)
  {
  $query = $this->db->select("sale_product.*,sales.saDate,sales.invoice")
                ->from('sale_product')
                ->join('sales','sales.said = sale_product.said','left')
                //->where('sales.compid',$_SESSION['compid'])
                ->where('sale_product.pid',$pid)
                ->where('MONTH(saDate)',$month)
                ->where('YEAR(saDate)',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_preturn_mproduct_ledger_data($pid,$month,$year)
  {
  $query = $this->db->select("preturns_product.*,preturns.prDate,preturns.prCode")
                ->from('preturns_product')
                ->join('preturns','preturns.prid = preturns_product.prid','left')
                //->where('preturns.compid',$_SESSION['compid'])
                ->where('preturns_product.pid',$pid)
                ->where('MONTH(prDate)',$month)
                ->where('YEAR(prDate)',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_sreturn_mproduct_ledger_data($pid,$month,$year)
  {
  $query = $this->db->select("returns_product.*,returns.rDate,returns.rCode")
                ->from('returns_product')
                ->join('returns','returns.rid = returns_product.rid','left')
                //->where('returns.compid',$_SESSION['compid'])
                ->where('returns_product.pid',$pid)
                ->where('MONTH(srDate)',$month)
                ->where('YEAR(srDate)',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_rmanufacture_mproduct_ledger_data($pid,$month,$year)
  {
  $query = $this->db->select("*")
                ->from('costing')
                ->where('pid',$pid)
                ->where('MONTH(regdate)',$month)
                ->where('YEAR(regdate)',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_purchase_yproduct_ledger_data($pid,$year)
  {
  $query = $this->db->select("purchase_product.*,purchase.puDate,purchase.challanNo")
                ->from('purchase_product')
                //->join('purchase','purchase.puid = purchase_product.puid','left')
                ->where('purchase.compid',$_SESSION['compid'])
                ->where('purchase_product.pid',$pid)
                ->where('YEAR(puDate)',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_sale_yproduct_ledger_data($pid,$year)
  {
  $query = $this->db->select("sale_product.*,sales.saDate,sales.invoice")
                ->from('sale_product')
                ->join('sales','sales.said = sale_product.said','left')
                //->where('sales.compid',$_SESSION['compid'])
                ->where('sale_product.pid',$pid)
                ->where('YEAR(saDate)',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_preturn_yproduct_ledger_data($pid,$year)
  {
  $query = $this->db->select("preturns_product.*,preturns.prDate,preturns.prCode")
                ->from('preturns_product')
                ->join('preturns','preturns.prid = preturns_product.prid','left')
                //->where('preturns.compid',$_SESSION['compid'])
                ->where('preturns_product.pid',$pid)
                ->where('YEAR(prDate)',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_sreturn_yproduct_ledger_data($pid,$year)
  {
  $query = $this->db->select("returns_product.*,returns.rDate,returns.rCode")
                ->from('returns_product')
                ->join('returns','returns.rid = returns_product.rid','left')
                //->where('returns.compid',$_SESSION['compid'])
                ->where('returns_product.pid',$pid)
                ->where('YEAR(srDate)',$year)
                ->get()
                ->result();
  return $query;  
}

public function get_rmanufacture_yproduct_ledger_data($pid,$year)
  {
  $query = $this->db->select("*")
                ->from('costing')
                ->where('pid',$pid)
                ->where('YEAR(regdate)',$year)
                ->get()
                ->result();
  return $query;  
}


public function get_account_amount_data($actype,$acid)
  {
  $sa = $this->db->select_sum('pAmount','total')
               ->where('accountType',$actype)
               ->where('accountNo',$acid)
               ->get('sales')
               ->row();

  $saa = $sa && $sa->total ? $sa->total : 0;

  $pa = $this->db->select_sum('pAmount','total')
               ->where('accountType',$actype)
               ->where('accountNo',$acid)
               ->get('purchase')
               ->row();

  $paa = $pa && $pa->total ? $pa->total : 0;

  $va = $this->db->select_sum('tAmount','total')
               ->where('accountType',$actype)
               ->where('accountNo',$acid)
               ->where('vauchertype','Credit Voucher')
               ->get('vaucher')
               ->row();

  $cva = $va && $va->total ? $va->total : 0;

  $v2a = $this->db->select_sum('tAmount','total')
               ->where('accountType',$actype)
               ->where('accountNo',$acid)
               ->where_not_in('vauchertype','Credit Voucher')
               ->get('vaucher')
               ->row();

  $dva = $v2a && $v2a->total ? $v2a->total : 0;

  $temp = $this->db->select_sum('salary','total')
               ->where('accountType',$actype)
               ->where('accountNo',$acid)
               ->get('employee_payment')
               ->row();

  $tempa = $temp && $temp->total ? $temp->total : 0;
  
  $tr = $this->db->select_sum('tAmount', 'total')
            ->select_sum('pAmount', 'ptotal')
            ->where('accountType', $actype)
            ->where('accountNo', $acid)
            ->get('returns')
            ->row();

  $tra = $tr && $tr->total ? $tr->total : 0;
  $trpa = $tr && $tr->ptotal ? $tr->ptotal : 0;
  
  $ad = $this->db->select_sum('pAmount', 'total')
            ->where('accountType', $actype)
            ->where('accountNo', $acid)
            ->get('supplier_payment')
            ->row();

  $tad = $ad && $ad->total ? $ad->total : 0;
  
  $ecoa = $this->db->select_sum('caamount', 'total')
            ->where('accountType', $actype)
            ->where('accountNo', $acid)
            ->where('catid',5)
            ->get('chart_account')
            ->row();

  $tecoa = $ecoa && $ecoa->total ? $ecoa->total : 0;
  
  $pra = $this->db->select_sum('tAmount', 'total')
            ->where('accountType', $actype)
            ->where('accountNo', $acid)
            ->get('preturns')
            ->row();

  $tpra = $pra && $pra->total ? $pra->total : 0;

  $query = (($saa+$cva+$tra)-($paa+$tempa+$trpa+$tad+$tecoa+$tpra));
  
  return $query;  
}


public function get_product_price_data($id,$spid)
  {
  $cost = $this->db->select('pprice,sprice')
                  ->from('costing')
                  ->where('pid',$id)
                  ->get()
                  ->row();
  
  $product = $this->db->select('pprice,sprice')
                  ->from('products')
                  ->where('pid',$id)
                  ->get()
                  ->row();
  
  if($spid == 1)
    {
    return $cost;
    }
  else
    {
    return $product;
    }
}





}