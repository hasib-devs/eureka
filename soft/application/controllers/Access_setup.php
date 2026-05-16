<?php 
defined ('BASEPATH') OR exit('no direct script access allowed');
class Access_setup extends CI_Controller

##############################################################################
{   	/* Code Start From Here */
##############################################################################

public function __construct()
  {
  parent::__construct();
  $this->load->model("prime_model","pm");
  $this->checkPermission();
}

	#############################################################
	#				/* Pages start*/							#
	#############################################################
						
public function index()
	{
	$data['title'] = 'Page Setup';
	
	$data['master'] = $this->pm->get_data('tbl_master_page_title',false);
	$data['pagelist'] = $this->pm->get_page_and_function();

	$this->load->view('user_role/page_setup',$data);
}

public function add_master_title()
	{
  $info = $this->input->post();

	$data = [
		'c_master_title' => $info['c_master_title'],
		'master_title'   => $info['master_title'],
		'regby'          => $_SESSION['uid']
			];
		// var_dump($data); exit();

	$result = $this->pm->saveNewMaster_data($data);

	if($result)
		{
		$sdata = [
			'exception'  =>'<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4><i class ="icon fa fa-check"></i>Master Page Add Successfully !</h4>
				</div>'
				];
		}
	else
		{
		$sdata = [
			'exception'  => '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4><i class ="icon fa fa-warning"></i> Failed !</h4>
				</div>'
				];
		}
  $this->session->set_userdata($sdata);
  redirect('pageSetup');
}	

public function add_page_title()
	{
  $info = $this->input->post();

	$data = [
		'pagename'    => $info['pagename'],
		'cname'       => $info['cname'],
		'master_page' => $info['master_page'],
		'regby'       => $_SESSION['uid']
			];
		// var_dump($data); exit();

	$result = $this->pm->saveNewPage_data($data);

	if($result)
		{
		$sdata = [
			'exception'  =>'<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4><i class ="icon fa fa-check"></i> Page Add Successfully !</h4>
				</div>'
				];
		}
	else
		{
		$sdata = [
			'exception'  => '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4><i class ="icon fa fa-warning"></i> Failed !</h4>
				</div>'
				];
		}
  $this->session->set_userdata($sdata);
  redirect('pageSetup');	
}

public function get_page_data()
  {
  $master = $this->pm->get_page_data_by_master($_POST['id']);
  $someJSON = json_encode($master);
  echo $someJSON;
}

public function add_page_function_title()
  {
  $info = $this->input->post();

  $data = [
	'master'     => $info['master'],
	'pageid'     => $info['pageid'],
	'fcname'     => $info['fcname'],
	'pfunc_name' => $info['pfunc_name'],
	'regby'      => $_SESSION['uid']
		];
		// var_dump($data); exit();

	$result = $this->pm->saveNewPageFunction_data($data);

	if($result)
		{
		$sdata = [
			'exception'  =>'<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4><i class ="icon fa fa-check"></i> Page Function Add Successfully !</h4>
				</div>'
				];
		}
	else
		{
		$sdata = [
			'exception'  => '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4><i class ="icon fa fa-warning"></i> Failed !</h4>
				</div>'
				];
		}
  $this->session->set_userdata($sdata);
  redirect('pageSetup');
}

public function user_access_setup()
  {
  $data = ['title' => 'Access Setup'];

  $where = array('ax_id >'=> 2);
  $data['user'] = $this->pm->get_data('access_lavels',$where);
  
  $this->load->view('user_role/user_access_setup',$data);
}

public function view_uaccess_setup($id)
  {
  $data = ['title' => 'Access Setup'];

  $where = array('utype'=> $id);
  $data['master'] = $this->pm->get_data('tbl_user_m_permission',$where);
  $data['page'] = $this->pm->get_data('tbl_user_p_permission',$where);
  $data['function'] = $this->pm->get_data('tbl_user_f_permission',$where);

  $uwhere = array('ax_id'=> $id);
  $data['user'] = $this->pm->get_data('access_lavels',$uwhere);
  
  $this->load->view('user_role/view_uaccess_setup',$data);
}

public function edit_uaccess_setup($id)
  {
  $data = ['title' => 'Access Setup'];

  $where = array('utype'=> $id);
  $data['master'] = $this->pm->get_data('tbl_user_m_permission',$where);
  $data['page'] = $this->pm->get_data('tbl_user_p_permission',$where);
  $data['function'] = $this->pm->get_data('tbl_user_f_permission',$where);

  $uwhere = array('ax_id'=> $id);
  $data['user'] = $this->pm->get_data('access_lavels',$uwhere);
  
  $this->load->view('user_role/edit_uaccess_setup',$data);
}

public function setup_user_access($id)
  {
  $info = $this->input->post();

  $where = array(
    'utype' => $id
        );

  if(isset($info['dashboard']))
    {
    $dashboard = 1;
    }
  else
    {
    $dashboard = 0;
    }
  if(isset($info['inventory']))
    {
    $inventory = 1;
    }
  else
    {
    $inventory = 0;
    }
  if(isset($info['brtaregstar']))
    {
    $brtaregstar = 1;
    }
  else
    {
    $brtaregstar = 0;
    }
  if(isset($info['accounting']))
    {
    $accounting = 1;
    }
  else
    {
    $accounting = 0;
    }
  if(isset($info['hrpayroll']))
    {
    $hrpayroll = 1;
    }
  else
    {
    $hrpayroll = 0;
    }
  if(isset($info['reports']))
    {
    $reports = 1;
    }
  else
    {
    $reports = 0;
    }
  if(isset($info['settings']))
    {
    $settings = 1;
    }
  else
    {
    $settings = 0;
    }
  if(isset($info['accesssetup']))
    {
    $accesssetup = 1;
    }
  else
    {
    $accesssetup = 0;
    }

  $mdata = [
    'dashboard'   => $dashboard,
    'inventory'   => $inventory,
    'brtaregstar' => $brtaregstar,
    'accounting'  => $accounting,
    'hrpayroll'   => $hrpayroll,
    'reports'     => $reports,
    'settings'    => $settings,
    'accesssetup' => $accesssetup,
    'upby'        => $_SESSION['uid']
      ];
  //var_dump($data); exit();
  $result = $this->pm->update_data('tbl_user_m_permission',$mdata,$where);
  
  if(isset($info['todaysales']))
    {
    $todaysales = 1;
    }
  else
    {
    $todaysales = 0;
    }
  if(isset($info['todaypurchase']))
    {
    $todaypurchase = 1;
    }
  else
    {
    $todaypurchase = 0;
    }
  if(isset($info['todayexpense']))
    {
    $todayexpense = 1;
    }
  else
    {
    $todayexpense = 0;
    }
  if(isset($info['todayincome']))
    {
    $todayincome = 1;
    }
  else
    {
    $todayincome = 0;
    }
  if(isset($info['lastdSales']))
    {
    $lastdSales = 1;
    }
  else
    {
    $lastdSales = 0;
    }
  if(isset($info['products']))
    {
    $products = 1;
    }
  else
    {
    $products = 0;
    }
  if(isset($info['services']))
    {
    $services = 1;
    }
  else
    {
    $services = 0;
    }
  if(isset($info['purchases']))
    {
    $purchases = 1;
    }
  else
    {
    $purchases = 0;
    }
  if(isset($info['sales']))
    {
    $sales = 1;
    }
  else
    {
    $sales = 0;
    }
  if(isset($info['saleservices']))
    {
    $saleservices = 1;
    }
  else
    {
    $saleservices = 0;
    }
  if(isset($info['salereturns']))
    {
    $salereturns = 1;
    }
  else
    {
    $salereturns = 0;
    }
  if(isset($info['purreturns']))
    {
    $purreturns = 1;
    }
  else
    {
    $purreturns = 0;
    }
  if(isset($info['preorder']))
    {
    $preorder = 1;
    }
  else
    {
    $preorder = 0;
    }
  if(isset($info['quotations']))
    {
    $quotations = 1;
    }
  else
    {
    $quotations = 0;
    }
  if(isset($info['brtareglist']))
    {
    $brtareglist = 1;
    }
  else
    {
    $brtareglist = 0;
    }
  if(isset($info['majortype']))
    {
    $majortype = 1;
    }
  else
    {
    $majortype = 0;
    }
  if(isset($info['subtype']))
    {
    $subtype = 1;
    }
  else
    {
    $subtype = 0;
    }
  if(isset($info['voucherentry']))
    {
    $voucherentry = 1;
    }
  else
    {
    $voucherentry = 0;
    }
  if(isset($info['voucherreports']))
    {
    $voucherreports = 1;
    }
  else
    {
    $voucherreports = 0;
    }
  if(isset($info['journalentry']))
    {
    $journalentry = 1;
    }
  else
    {
    $journalentry = 0;
    }
  if(isset($info['trailbalance']))
    {
    $trailbalance = 1;
    }
  else
    {
    $trailbalance = 0;
    }
  if(isset($info['balancesheet']))
    {
    $balancesheet = 1;
    }
  else
    {
    $balancesheet = 0;
    }
  if(isset($info['generalledger']))
    {
    $generalledger = 1;
    }
  else
    {
    $generalledger = 0;
    }
  if(isset($info['incomestatement']))
    {
    $incomestatement = 1;
    }
  else
    {
    $incomestatement = 0;
    }
  if(isset($info['customers']))
    {
    $customers = 1;
    }
  else
    {
    $customers = 0;
    }
  if(isset($info['suppliers']))
    {
    $suppliers = 1;
    }
  else
    {
    $suppliers = 0;
    }
  if(isset($info['employees']))
    {
    $employees = 1;
    }
  else
    {
    $employees = 0;
    }
  if(isset($info['users']))
    {
    $users = 1;
    }
  else
    {
    $users = 0;
    }
  if(isset($info['emppayments']))
    {
    $emppayments = 1;
    }
  else
    {
    $emppayments = 0;
    }
  if(isset($info['salesreports']))
    {
    $salesreports = 1;
    }
  else
    {
    $salesreports = 0;
    }
  if(isset($info['purchasereports']))
    {
    $purchasereports = 1;
    }
  else
    {
    $purchasereports = 0;
    }
  if(isset($info['profitreports']))
    {
    $profitreports = 1;
    }
  else
    {
    $profitreports = 0;
    }
  if(isset($info['spprofit']))
    {
    $spprofit = 1;
    }
  else
    {
    $spprofit = 0;
    }
  if(isset($info['custreports']))
    {
    $custreports = 1;
    }
  else
    {
    $custreports = 0;
    }
  if(isset($info['custledger']))
    {
    $custledger = 1;
    }
  else
    {
    $custledger = 0;
    }
  if(isset($info['supreports']))
    {
    $supreports = 1;
    }
  else
    {
    $supreports = 0;
    }
  if(isset($info['supledger']))
    {
    $supledger = 1;
    }
  else
    {
    $supledger = 0;
    }
  if(isset($info['stockreports']))
    {
    $stockreports = 1;
    }
  else
    {
    $stockreports = 0;
    }
  if(isset($info['orderreports']))
    {
    $orderreports = 1;
    }
  else
    {
    $orderreports = 0;
    }
  if(isset($info['dailyreports']))
    {
    $dailyreports = 1;
    }
  else
    {
    $dailyreports = 0;
    }
  if(isset($info['cashbook']))
    {
    $cashbook = 1;
    }
  else
    {
    $cashbook = 0;
    }
  if(isset($info['bankbook']))
    {
    $bankbook = 1;
    }
  else
    {
    $bankbook = 0;
    }
  if(isset($info['mobilebook']))
    {
    $mobilebook = 1;
    }
  else
    {
    $mobilebook = 0;
    }
  if(isset($info['swpreports']))
    {
    $swpreports = 1;
    }
  else
    {
    $swpreports = 0;
    }
  if(isset($info['duereports']))
    {
    $duereports = 1;
    }
  else
    {
    $duereports = 0;
    }
  if(isset($info['btransreports']))
    {
    $btransreports = 1;
    }
  else
    {
    $btransreports = 0;
    }
  if(isset($info['duepreports']))
    {
    $duepreports = 1;
    }
  else
    {
    $duepreports = 0;
    }
  if(isset($info['expensereports']))
    {
    $expensereports = 1;
    }
  else
    {
    $expensereports = 0;
    }
  if(isset($info['btransferreports']))
    {
    $btransferreports = 1;
    }
  else
    {
    $btransferreports = 0;
    }
  if(isset($info['alltransreports']))
    {
    $alltransreports = 1;
    }
  else
    {
    $alltransreports = 0;
    }
  if(isset($info['category']))
    {
    $category = 1;
    }
  else
    {
    $category = 0;
    }
  if(isset($info['units']))
    {
    $units = 1;
    }
  else
    {
    $units = 0;
    }
  if(isset($info['department']))
    {
    $department = 1;
    }
  else
    {
    $department = 0;
    }
  if(isset($info['cashaccount']))
    {
    $cashaccount = 1;
    }
  else
    {
    $cashaccount = 0;
    }
  if(isset($info['bankaccount']))
    {
    $bankaccount = 1;
    }
  else
    {
    $bankaccount = 0;
    }
  if(isset($info['mobileaccount']))
    {
    $mobileaccount = 1;
    }
  else
    {
    $mobileaccount = 0;
    }
  if(isset($info['usertype']))
    {
    $usertype = 1;
    }
  else
    {
    $usertype = 0;
    }
  if(isset($info['purchasetype']))
    {
    $purchasetype = 1;
    }
  else
    {
    $purchasetype = 0;
    }
  if(isset($info['balancetransfer']))
    {
    $balancetransfer = 1;
    }
  else
    {
    $balancetransfer = 0;
    }
  if(isset($info['accesssetuplist']))
    {
    $accesssetuplist = 1;
    }
  else
    {
    $accesssetuplist = 0;
    }

  $pdata = [
    'todaysales'       => $todaysales,
    'todaypurchase'    => $todaypurchase,
    'todayexpense'     => $todayexpense,
    'todayincome'      => $todayincome,
    'lastdSales'       => $lastdSales,
    'products'         => $products,
    'services'         => $services,
    'purchases'        => $purchases,
    'sales'            => $sales,
    'saleservices'     => $saleservices,
    'salereturns'      => $salereturns,
    'purreturns'       => $purreturns,
    'preorder'         => $preorder,
    'quotations'       => $quotations,
    'brtareglist'      => $brtareglist,
    'majortype'        => $majortype,
    'subtype'          => $subtype,
    'voucherentry'     => $voucherentry,
    'voucherreports'   => $voucherreports,
    'journalentry'     => $journalentry,
    'trailbalance'     => $trailbalance,
    'balancesheet'     => $balancesheet,
    'generalledger'    => $generalledger,
    'incomestatement'  => $incomestatement,
    'customers'        => $customers,
    'suppliers'        => $suppliers,
    'employees'        => $employees,
    'users'            => $users,
    'emppayments'      => $emppayments,
    'salesreports'     => $salesreports,
    'purchasereports'  => $purchasereports,
    'profitreports'    => $profitreports,
    'spprofit'         => $spprofit,
    'custreports'      => $custreports,
    'custledger'       => $custledger,
    'supreports'       => $supreports,
    'supledger'        => $supledger,
    'stockreports'     => $stockreports,
    'orderreports'     => $orderreports,
    'dailyreports'     => $dailyreports,
    'cashbook'         => $cashbook,
    'bankbook'         => $bankbook,
    'mobilebook'       => $mobilebook,
    'swpreports'       => $swpreports,
    'duereports'       => $duereports,
    'btransreports'    => $btransreports,
    'duepreports'      => $duepreports,
    'expensereports'   => $expensereports,
    'btransferreports' => $btransferreports,
    'alltransreports'  => $alltransreports,
    'category'         => $category,
    'units'            => $units,
    'department'       => $department,
    'cashaccount'      => $cashaccount,
    'bankaccount'      => $bankaccount,
    'mobileaccount'    => $mobileaccount,
    'usertype'         => $usertype,
    'purchasetype'     => $purchasetype,
    'balancetransfer'  => $balancetransfer,
    'accesssetuplist'  => $accesssetuplist,
    'upby'             => $_SESSION['uid']
            ];
  
  $result2 = $this->pm->update_data('tbl_user_p_permission',$pdata,$where);
  
  if(isset($info['newProduct']))
    {
    $newProduct = 1;
    }
  else
    {
    $newProduct = 0;
    }
  if(isset($info['editProduct']))
    {
    $editProduct = 1;
    }
  else
    {
    $editProduct = 0;
    }
  if(isset($info['deleteProduct']))
    {
    $deleteProduct = 1;
    }
  else
    {
    $deleteProduct = 0;
    }
  if(isset($info['storeProduct']))
    {
    $storeProduct = 1;
    }
  else
    {
    $storeProduct = 0;
    }
  if(isset($info['newService']))
    {
    $newService = 1;
    }
  else
    {
    $newService = 0;
    }
  if(isset($info['editService']))
    {
    $editService = 1;
    }
  else
    {
    $editService = 0;
    }
  if(isset($info['deleteService']))
    {
    $deleteService = 1;
    }
  else
    {
    $deleteService = 0;
    }
  if(isset($info['newPurchase']))
    {
    $newPurchase = 1;
    }
  else
    {
    $newPurchase = 0;
    }
  if(isset($info['editPurchase']))
    {
    $editPurchase = 1;
    }
  else
    {
    $editPurchase = 0;
    }
  if(isset($info['deletePurchase']))
    {
    $deletePurchase = 1;
    }
  else
    {
    $deletePurchase = 0;
    }
  if(isset($info['newSale']))
    {
    $newSale = 1;
    }
  else
    {
    $newSale = 0;
    }
  if(isset($info['editSale']))
    {
    $editSale = 1;
    }
  else
    {
    $editSale = 0;
    }
  if(isset($info['deleteSale']))
    {
    $deleteSale = 1;
    }
  else
    {
    $deleteSale = 0;
    }
  if(isset($info['salesbrta']))
    {
    $salesbrta = 1;
    }
  else
    {
    $salesbrta = 0;
    }
  if(isset($info['newSService']))
    {
    $newSService = 1;
    }
  else
    {
    $newSService = 0;
    }
  if(isset($info['editSService']))
    {
    $editSService = 1;
    }
  else
    {
    $editSService = 0;
    }
  if(isset($info['deleteSService']))
    {
    $deleteSService = 1;
    }
  else
    {
    $deleteSService = 0;
    }
  if(isset($info['newSReturns']))
    {
    $newSReturns = 1;
    }
  else
    {
    $newSReturns = 0;
    }
  if(isset($info['editSReturns']))
    {
    $editSReturns = 1;
    }
  else
    {
    $editSReturns = 0;
    }
  if(isset($info['deleteSReturns']))
    {
    $deleteSReturns = 1;
    }
  else
    {
    $deleteSReturns = 0;
    }
  if(isset($info['newPReturns']))
    {
    $newPReturns = 1;
    }
  else
    {
    $newPReturns = 0;
    }
  if(isset($info['editPReturns']))
    {
    $editPReturns = 1;
    }
  else
    {
    $editPReturns = 0;
    }
  if(isset($info['deletePReturns']))
    {
    $deletePReturns = 1;
    }
  else
    {
    $deletePReturns = 0;
    }
  if(isset($info['newPreorder']))
    {
    $newPreorder = 1;
    }
  else
    {
    $newPreorder = 0;
    }
  if(isset($info['editPreorder']))
    {
    $editPreorder = 1;
    }
  else
    {
    $editPreorder = 0;
    }
  if(isset($info['deletePreorder']))
    {
    $deletePreorder = 1;
    }
  else
    {
    $deletePreorder = 0;
    }
  if(isset($info['newQuotations']))
    {
    $newQuotations = 1;
    }
  else
    {
    $newQuotations = 0;
    }
  if(isset($info['editQuotations']))
    {
    $editQuotations = 1;
    }
  else
    {
    $editQuotations = 0;
    }
  if(isset($info['deleteQuotations']))
    {
    $deleteQuotations = 1;
    }
  else
    {
    $deleteQuotations = 0;
    }
  if(isset($info['editbrtareg']))
    {
    $editbrtareg = 1;
    }
  else
    {
    $editbrtareg = 0;
    }
  if(isset($info['newMType']))
    {
    $newMType = 1;
    }
  else
    {
    $newMType = 0;
    }
  if(isset($info['editMType']))
    {
    $editMType = 1;
    }
  else
    {
    $editMType = 0;
    }
  if(isset($info['deleteMType']))
    {
    $deleteMType = 1;
    }
  else
    {
    $deleteMType = 0;
    }
  if(isset($info['newSType']))
    {
    $newSType = 1;
    }
  else
    {
    $newSType = 0;
    }
  if(isset($info['editSType']))
    {
    $editSType = 1;
    }
  else
    {
    $editSType = 0;
    }
  if(isset($info['deleteSType']))
    {
    $deleteSType = 1;
    }
  else
    {
    $deleteSType = 0;
    }
  if(isset($info['newVEntry']))
    {
    $newVEntry = 1;
    }
  else
    {
    $newVEntry = 0;
    }
  if(isset($info['editVEntry']))
    {
    $editVEntry = 1;
    }
  else
    {
    $editVEntry = 0;
    }
  if(isset($info['deleteVEntry']))
    {
    $deleteVEntry = 1;
    }
  else
    {
    $deleteVEntry = 0;
    }
  if(isset($info['newCustomer']))
    {
    $newCustomer = 1;
    }
  else
    {
    $newCustomer = 0;
    }
  if(isset($info['editCustomer']))
    {
    $editCustomer = 1;
    }
  else
    {
    $editCustomer = 0;
    }
  if(isset($info['deleteCustomer']))
    {
    $deleteCustomer = 1;
    }
  else
    {
    $deleteCustomer = 0;
    }
  if(isset($info['newSupplier']))
    {
    $newSupplier = 1;
    }
  else
    {
    $newSupplier = 0;
    }
  if(isset($info['editSupplier']))
    {
    $editSupplier = 1;
    }
  else
    {
    $editSupplier = 0;
    }
  if(isset($info['deleteSupplier']))
    {
    $deleteSupplier = 1;
    }
  else
    {
    $deleteSupplier = 0;
    }
  if(isset($info['newEmployee']))
    {
    $newEmployee = 1;
    }
  else
    {
    $newEmployee = 0;
    }
  if(isset($info['editEmployee']))
    {
    $editEmployee = 1;
    }
  else
    {
    $editEmployee = 0;
    }
  if(isset($info['deleteEmployee']))
    {
    $deleteEmployee = 1;
    }
  else
    {
    $deleteEmployee = 0;
    }
  if(isset($info['newUser']))
    {
    $newUser = 1;
    }
  else
    {
    $newUser = 0;
    }
  if(isset($info['editUser']))
    {
    $editUser = 1;
    }
  else
    {
    $editUser = 0;
    }
  if(isset($info['deleteUser']))
    {
    $deleteUser = 1;
    }
  else
    {
    $deleteUser = 0;
    }
  if(isset($info['newEmppay']))
    {
    $newEmppay = 1;
    }
  else
    {
    $newEmppay = 0;
    }
  if(isset($info['newCategory']))
    {
    $newCategory = 1;
    }
  else
    {
    $newCategory = 0;
    }
  if(isset($info['editCategory']))
    {
    $editCategory = 1;
    }
  else
    {
    $editCategory = 0;
    }
  if(isset($info['deleteCategory']))
    {
    $deleteCategory = 1;
    }
  else
    {
    $deleteCategory = 0;
    }
  if(isset($info['newUnit']))
    {
    $newUnit = 1;
    }
  else
    {
    $newUnit = 0;
    }
  if(isset($info['editUnit']))
    {
    $editUnit = 1;
    }
  else
    {
    $editUnit = 0;
    }
  if(isset($info['deleteUnit']))
    {
    $deleteUnit = 1;
    }
  else
    {
    $deleteUnit = 0;
    }
  if(isset($info['newDepartment']))
    {
    $newDepartment = 1;
    }
  else
    {
    $newDepartment = 0;
    }
  if(isset($info['editDepartment']))
    {
    $editDepartment = 1;
    }
  else
    {
    $editDepartment = 0;
    }
  if(isset($info['deleteDepartment']))
    {
    $deleteDepartment = 1;
    }
  else
    {
    $deleteDepartment = 0;
    }
  if(isset($info['newCAccount']))
    {
    $newCAccount = 1;
    }
  else
    {
    $newCAccount = 0;
    }
  if(isset($info['editCAccount']))
    {
    $editCAccount = 1;
    }
  else
    {
    $editCAccount = 0;
    }
  if(isset($info['deleteCAccount']))
    {
    $deleteCAccount = 1;
    }
  else
    {
    $deleteCAccount = 0;
    }
  if(isset($info['newBAccount']))
    {
    $newBAccount = 1;
    }
  else
    {
    $newBAccount = 0;
    }
  if(isset($info['editBAccount']))
    {
    $editBAccount = 1;
    }
  else
    {
    $editBAccount = 0;
    }
  if(isset($info['deleteBAccount']))
    {
    $deleteBAccount = 1;
    }
  else
    {
    $deleteBAccount = 0;
    }
  if(isset($info['newMAccount']))
    {
    $newMAccount = 1;
    }
  else
    {
    $newMAccount = 0;
    }
  if(isset($info['editMAccount']))
    {
    $editMAccount = 1;
    }
  else
    {
    $editMAccount = 0;
    }
  if(isset($info['deleteMAccount']))
    {
    $deleteMAccount = 1;
    }
  else
    {
    $deleteMAccount = 0;
    }
  if(isset($info['newUType']))
    {
    $newUType = 1;
    }
  else
    {
    $newUType = 0;
    }
  if(isset($info['editUType']))
    {
    $editUType = 1;
    }
  else
    {
    $editUType = 0;
    }
  if(isset($info['deleteUType']))
    {
    $deleteUType = 1;
    }
  else
    {
    $deleteUType = 0;
    }
  if(isset($info['newPType']))
    {
    $newPType = 1;
    }
  else
    {
    $newPType = 0;
    }
  if(isset($info['editPType']))
    {
    $editPType = 1;
    }
  else
    {
    $editPType = 0;
    }
  if(isset($info['deletePType']))
    {
    $deletePType = 1;
    }
  else
    {
    $deletePType = 0;
    }
  if(isset($info['newTransfer']))
    {
    $newTransfer = 1;
    }
  else
    {
    $newTransfer = 0;
    }
  if(isset($info['editTransfer']))
    {
    $editTransfer = 1;
    }
  else
    {
    $editTransfer = 0;
    }
  if(isset($info['deleteTransfer']))
    {
    $deleteTransfer = 1;
    }
  else
    {
    $deleteTransfer = 0;
    }

  $fdata = [
    'newProduct'       => $newProduct,
    'editProduct'      => $editProduct,
    'deleteProduct'    => $deleteProduct,
    'storeProduct'     => $storeProduct,
    'newService'       => $newService,
    'editService'      => $editService,
    'deleteService'    => $deleteService,
    'newPurchase'      => $newPurchase,
    'editPurchase'     => $editPurchase,
    'deletePurchase'   => $deletePurchase,
    'newSale'          => $newSale,
    'editSale'         => $editSale,
    'deleteSale'       => $deleteSale,
    'salesbrta'        => $salesbrta,
    'newSService'      => $newSService,
    'editSService'     => $editSService,
    'deleteSService'   => $deleteSService,
    'newSReturns'      => $newSReturns,
    'editSReturns'     => $editSReturns,
    'deleteSReturns'   => $deleteSReturns,
    'newPReturns'      => $newPReturns,
    'editPReturns'     => $editPReturns,
    'deletePReturns'   => $deletePReturns,
    'newPreorder'      => $newPreorder,
    'editPreorder'     => $editPreorder,
    'deletePreorder'   => $deletePreorder,
    'newQuotations'    => $newQuotations,
    'editQuotations'   => $editQuotations,
    'deleteQuotations' => $deleteQuotations,
    'editbrtareg'      => $editbrtareg,
    'newMType'         => $newMType,
    'editMType'        => $editMType,
    'deleteMType'      => $deleteMType,
    'newSType'         => $newSType,
    'editSType'        => $editSType,
    'deleteSType'      => $deleteSType,
    'newVEntry'        => $newVEntry,
    'editVEntry'       => $editVEntry,
    'deleteVEntry'     => $deleteVEntry,
    'newCustomer'      => $newCustomer,
    'editCustomer'     => $editCustomer,
    'deleteCustomer'   => $deleteCustomer,
    'newSupplier'      => $newSupplier,
    'editSupplier'     => $editSupplier,
    'deleteSupplier'   => $deleteSupplier,
    'newEmployee'      => $newEmployee,
    'editEmployee'     => $editEmployee,
    'deleteEmployee'   => $deleteEmployee,
    'newUser'          => $newUser,
    'editUser'         => $editUser,
    'deleteUser'       => $deleteUser,
    'newEmppay'        => $newEmppay,
    'newCategory'      => $newCategory,
    'editCategory'     => $editCategory,
    'deleteCategory'   => $deleteCategory,
    'newUnit'          => $newUnit,
    'editUnit'         => $editUnit,
    'deleteUnit'       => $deleteUnit,
    'newDepartment'    => $newDepartment,
    'editDepartment'   => $editDepartment,
    'deleteDepartment' => $deleteDepartment,
    'newCAccount'      => $newCAccount,
    'editCAccount'     => $editCAccount,
    'deleteCAccount'   => $deleteCAccount,
    'newBAccount'      => $newBAccount,
    'editBAccount'     => $editBAccount,
    'deleteBAccount'   => $deleteBAccount,
    'newMAccount'      => $newMAccount,
    'editMAccount'     => $editMAccount,
    'deleteMAccount'   => $deleteMAccount,
    'newUType'         => $newUType,
    'editUType'        => $editUType,
    'deleteUType'      => $deleteUType,
    'newPType'         => $newPType,
    'editPType'        => $editPType,
    'deletePType'      => $deletePType,
    'newTransfer'      => $newTransfer,
    'editTransfer'     => $editTransfer,
    'deleteTransfer'   => $deleteTransfer,
    'upby'             => $_SESSION['uid']
      ];
  //var_dump($data2); exit();
  $result3 = $this->pm->update_data('tbl_user_f_permission',$fdata,$where);

  if($result && $result && $result3)
    {
    $sdata = [
      'exception' =>'<div class="alert alert-success alert-dismissible">
      <h4><i class="icon fa fa-check"></i> User Page and Function Access add Successfully !</h4>
      </div>'
          ];    
    }
  else
    {
    $sdata=[
      'exception' =>'<div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-ban"></i> Failed !</h4>
      </div>'
          ];
    }

  $this->session->set_userdata($sdata);
  redirect('userAccess');
}




	#########################################################
	#				/* Pages End */							#
	#########################################################


############################################################################
}   	/* Code Ends Here */
############################################################################