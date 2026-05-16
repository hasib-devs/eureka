<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Bike_Registration extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("prime_model", "pm");
        $this->checkPermission();
    }

    public function index()
    {
        $data['title'] = 'Bike Registration';
        $other = array(
            'join' => 'left',
            'order_by' => 'spid',
        );
        $field = array(
            'sales' => 'sales.*',
            'customers' => 'customers.custName,customers.custMobile',
            'sale_product' => 'sale_product.*',
            'products' => 'products.*',
            
        );
        $join = array(
          'sales' => 'sales.said = sale_product.said',
            'customers' => 'customers.custid = sales.custid',
            'products' => 'products.pid = sale_product.pid',
        );
        $data['sales'] = $this->pm->get_data('sale_product', false, $field, $join, $other);

        $this->load->view('bikeRegistration/registration', $data);
    }
    public function reg_form($id)
    {
        $data['title'] = 'Registration Form';

        $where = array(
          'said' => $id
              );
        $other = array(
          'join' => 'left'
              );
        $field = array(
          'sales' => 'sales.*',
          'customers' => 'customers.*',
          'users' => 'users.name'
                );
        $join = array(
          'customers' => 'customers.custid = sales.custid',
          'users' => 'users.uid = sales.regby'
                );
        $prints = $this->pm->get_data('sales',$where,$field,$join,$other);
        $data['prints'] = $prints[0];

        $pfield = array(
            'sale_product' => 'sale_product.*',
            'products' => 'products.*',
            'categories' => 'categories.catName'
                );
          $pjoin = array(
            'products' => 'products.pid = sale_product.pid',
            'categories' => 'categories.catid = products.catid'
                );
        
          $salesp = $this->pm->get_data('sale_product',$where,$pfield,$pjoin,$other);
          $data['salesp'] = $salesp[0];

        $this->load->view('bikeRegistration/registration_form', $data);
    }
    public function owner_form($id)
    {
        $data['title'] = 'Owner Form';

        $where = array(
            'said' => $id
                );
          $other = array(
            'join' => 'left'
                );
          $field = array(
            'sales' => 'sales.*',
            'customers' => 'customers.*',
            'users' => 'users.name'
                  );
          $join = array(
            'customers' => 'customers.custid = sales.custid',
            'users' => 'users.uid = sales.regby'
                  );
          $prints = $this->pm->get_data('sales',$where,$field,$join,$other);
          $data['prints'] = $prints[0];

          $pfield = array(
            'sale_product' => 'sale_product.*',
            'products' => 'products.*',
            'categories' => 'categories.catName'
                );
          $pjoin = array(
            'products' => 'products.pid = sale_product.pid',
            'categories' => 'categories.catid = products.catid'
                );
        
          $salesp = $this->pm->get_data('sale_product',$where,$pfield,$pjoin,$other);
          $data['salesp'] = $salesp[0];

        $this->load->view('bikeRegistration/owner_form', $data);
    }
    public function challan_form($id)
    {
        $data['title'] = 'Challan Form';
        
          $where = array(
            'said' => $id
                );
          $other = array(
            'join' => 'left'
                );
          $field = array(
            'sales' => 'sales.*',
            'customers' => 'customers.*',
            'users' => 'users.name'
                  );
          $join = array(
            'customers' => 'customers.custid = sales.custid',
            'users' => 'users.uid = sales.regby'
                  );
          $prints = $this->pm->get_data('sales',$where,$field,$join,$other);
          $data['prints'] = $prints[0];

          $pfield = array(
            'sale_product' => 'sale_product.*',
            'products' => 'products.*',
            'categories' => 'categories.catName'
                );
          $pjoin = array(
            'products' => 'products.pid = sale_product.pid',
            'categories' => 'categories.catid = products.catid'
                );
        
          $salesp = $this->pm->get_data('sale_product',$where,$pfield,$pjoin,$other);
          $data['salesp'] = $salesp[0];

        $this->load->view('bikeRegistration/challan_form', $data);
    }
    public function concern_form($id)
    {
        $data['title'] = 'To Whom It May Concern Form';
        
          $where = array(
            'said' => $id
                );
          $other = array(
            'join' => 'left'
                );
          $field = array(
            'sales' => 'sales.*',
            'customers' => 'customers.*',
            'users' => 'users.name'
                  );
          $join = array(
            'customers' => 'customers.custid = sales.custid',
            'users' => 'users.uid = sales.regby'
                  );
          $prints = $this->pm->get_data('sales',$where,$field,$join,$other);
          $data['prints'] = $prints[0];

          $pfield = array(
            'sale_product' => 'sale_product.*',
            'products' => 'products.*',
            'categories' => 'categories.catName'
                );
          $pjoin = array(
            'products' => 'products.pid = sale_product.pid',
            'categories' => 'categories.catid = products.catid'
                );
        
          $salesp = $this->pm->get_data('sale_product',$where,$pfield,$pjoin,$other);
          $data['salesp'] = $salesp[0];


        $this->load->view('bikeRegistration/concern_form', $data);
    }
    public function ongikarnama($id)
    {
        $data['title'] = 'Ongikarnama';
        
          $where = array(
            'said' => $id
                );
          $other = array(
            'join' => 'left'
                );
          $field = array(
            'sales' => 'sales.*',
            'customers' => 'customers.*',
            'users' => 'users.name'
                  );
          $join = array(
            'customers' => 'customers.custid = sales.custid',
            'users' => 'users.uid = sales.regby'
                  );
          $prints = $this->pm->get_data('sales',$where,$field,$join,$other);
          $data['prints'] = $prints[0];

          $pfield = array(
            'sale_product' => 'sale_product.*',
            'products' => 'products.*',
            'categories' => 'categories.catName'
                );
          $pjoin = array(
            'products' => 'products.pid = sale_product.pid',
            'categories' => 'categories.catid = products.catid'
                );
        
          $salesp = $this->pm->get_data('sale_product',$where,$pfield,$pjoin,$other);
          $data['salesp'] = $salesp[0];

        $this->load->view('bikeRegistration/ongikarnama', $data);
    }
    public function bank_form($id)
    {
        $data['title'] = 'Bank Assessment Form';
        
        $where = array(
          'said' => $id
              );
        $other = array(
          'join' => 'left'
              );
        $field = array(
          'sales' => 'sales.*',
          'customers' => 'customers.*',
          'users' => 'users.name'
                );
        $join = array(
          'customers' => 'customers.custid = sales.custid',
          'users' => 'users.uid = sales.regby'
                );
        $prints = $this->pm->get_data('sales',$where,$field,$join,$other);
        $data['prints'] = $prints[0];

        $pfield = array(
            'sale_product' => 'sale_product.*',
            'products' => 'products.*',
            'categories' => 'categories.catName'
                );
          $pjoin = array(
            'products' => 'products.pid = sale_product.pid',
            'categories' => 'categories.catid = products.catid'
                );
        
          $salesp = $this->pm->get_data('sale_product',$where,$pfield,$pjoin,$other);
          $data['salesp'] = $salesp[0];

        $this->load->view('bikeRegistration/bank_assessment', $data);
    }
    public function gatePass($id)
    {
        $data['title'] = 'Gate Pass';
        
          $where = array(
            'said' => $id
                );
          $other = array(
            'join' => 'left'
                );
          $field = array(
            'sales' => 'sales.*',
            'customers' => 'customers.*',
            'users' => 'users.name'
                  );
          $join = array(
            'customers' => 'customers.custid = sales.custid',
            'users' => 'users.uid = sales.regby'
                  );
          $prints = $this->pm->get_data('sales',$where,$field,$join,$other);
          $data['prints'] = $prints[0];

          $pfield = array(
            'sale_product' => 'sale_product.*',
            'products' => 'products.*',
            'categories' => 'categories.catName'
                );
          $pjoin = array(
            'products' => 'products.pid = sale_product.pid',
            'categories' => 'categories.catid = products.catid'
                );
        
          $salesp = $this->pm->get_data('sale_product',$where,$pfield,$pjoin,$other);
          $data['salesp'] = $salesp[0];

        $this->load->view('bikeRegistration/gatepass', $data);
    }
    public function salesReceipt($id)
    {
        $data['title'] = 'Sales Receipt';
        
        $where = array(
            'said' => $id
                );
          $other = array(
            'join' => 'left'
                );
          $field = array(
            'sales_duplicate' => 'sales_duplicate.*',
            'customers' => 'customers.*',
            'users' => 'users.name'
                  );
          $join = array(
            'customers' => 'customers.custid = sales_duplicate.custid',
            'users' => 'users.uid = sales_duplicate.regby'
                  );
          $prints = $this->pm->get_data('sales_duplicate',$where,$field,$join,$other);
          $data['prints'] = $prints[0];

          $pfield = array(
            'sale_product' => 'sale_product.*',
            'products' => 'products.*',
            'categories' => 'categories.catName'
                );
          $pjoin = array(
            'products' => 'products.pid = sale_product.pid',
            'categories' => 'categories.catid = products.catid'
                );
        
          $salesp = $this->pm->get_data('sale_product',$where,$pfield,$pjoin,$other);
          $data['salesp'] = $salesp[0];


        $this->load->view('bikeRegistration/sales_receipt', $data);
    }
    public function brtaApplication($id)
    {
        $data['title'] = 'BRTA Registration Application';
        
          $where = array(
            'said' => $id
                );
          $other = array(
            'join' => 'left'
                );
          $field = array(
            'sales' => 'sales.*',
            'customers' => 'customers.*',
            'users' => 'users.name'
                  );
          $join = array(
            'customers' => 'customers.custid = sales.custid',
            'users' => 'users.uid = sales.regby'
                  );
          $prints = $this->pm->get_data('sales',$where,$field,$join,$other);
          $data['prints'] = $prints[0];

          $pfield = array(
            'sale_product' => 'sale_product.*',
            'products' => 'products.*',
            'categories' => 'categories.catName'
                );
          $pjoin = array(
            'products' => 'products.pid = sale_product.pid',
            'categories' => 'categories.catid = products.catid'
                );
        
          $salesp = $this->pm->get_data('sale_product',$where,$pfield,$pjoin,$other);
          $data['salesp'] = $salesp[0];
        
        $this->load->view('bikeRegistration/brta_application', $data);
    }
    public function certificate($id)
    {
        $data['title'] = 'প্রত্যায়ন পত্র';
        
        $where = array(
            'said' => $id
                );
          $other = array(
            'join' => 'left'
                );
          $field = array(
            'sales' => 'sales.*',
            'customers' => 'customers.*',
            'users' => 'users.name'
                  );
          $join = array(
            'customers' => 'customers.custid = sales.custid',
            'users' => 'users.uid = sales.regby'
                  );
          $prints = $this->pm->get_data('sales',$where,$field,$join,$other);
          $data['prints'] = $prints[0];

          $pfield = array(
            'sale_product' => 'sale_product.*',
            'products' => 'products.*',
            'categories' => 'categories.catName'
                );
          $pjoin = array(
            'products' => 'products.pid = sale_product.pid',
            'categories' => 'categories.catid = products.catid'
                );
        
          $salesp = $this->pm->get_data('sale_product',$where,$pfield,$pjoin,$other);
          $data['salesp'] = $salesp[0];
        
        $this->load->view('bikeRegistration/certificate', $data);
    }

}
