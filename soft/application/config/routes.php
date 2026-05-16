<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


$route['forgetPassword'] = 'Login/forget_password';
$route['otpPassword'] = 'Login/otp_password';
$route['passwordSetup'] = 'Login/password_setup';

$route['Dashboard'] = 'Home';
$route['Setting'] = 'Home/setting_pages';
$route['uSetting'] = 'Home/user_setting_pages';
$route['uReport'] = 'Home/user_reports_pages';
$route['myProfile'] = 'Home/profile';
$route['comProfile'] = 'Home/company_profile';
$route['aSetting'] = 'Home/account_setting';

$route['Category'] = 'Category';
$route['Unit'] = 'Category/product_units';
$route['puType'] = 'Category/purchase_type';
$route['currency'] = 'Category/currency';

$route['Expense'] = 'Cost';
$route['costReport'] = 'Cost/expense_report_list';

$route['Department'] = 'Employee/emp_department';
$route['Employee'] = 'Employee/employee_info';

$route['Courier'] = 'Courier/emp_courier';

$route['Customer'] = 'Customer';
$route['newCustomer'] = 'Customer/new_Customer';
$route['viewCust/(:num)'] = 'Customer/view_customer/$1';
$route['custReport'] = 'Customer/all_customer_reports';
$route['custLedger'] = 'Customer/customer_ledger_report';

$route['Supplier'] = 'Supplier';
$route['newSup'] = 'Supplier/new_supplier';
$route['supReport'] = 'Supplier/supplier_report';
$route['supLedger'] = 'Supplier/supplier_ledger';

$route['items'] = 'Product';
$route['newProduct'] = 'Product/new_products';
$route['viewItems/(:num)'] = 'Product/view_product/$1';
$route['editItems/(:num)'] = 'Product/edit_products/$1';
$route['pBarcode/(:num)'] = 'Product/product_barcode/$1';
$route['stockReport'] = 'Product/product_reports';
$route['stockLedger'] = 'Product/product_ledger';
$route['service'] = 'Product/service_list';

$route['Purchase'] = 'Purchase';
$route['newPurchase'] = 'Purchase/new_purchase';
$route['viewPurchase/(:num)'] = 'Purchase/view_purchase/$1';
$route['editPurchase/(:num)'] = 'Purchase/edit_purchase/$1';
$route['apvPurchase/(:num)'] = 'Purchase/approve_purchase/$1';
$route['purchaseReport'] = 'Purchase/purchases_reports';

$route['empLoan'] = 'Loan';
$route['newempLoan'] = 'Loan/AddInfo';
$route['viewLoan/(:num)'] = 'Loan/emp_loan_details/$1';

$route['Sale'] = 'Sale';
$route['newSale'] = 'Sale/new_sale';
$route['viewSale/(:num)'] = 'Sale/view_invoice/$1';
$route['viewPartSale/(:num)'] = 'Sale/view_part_invoice/$1';
$route['pdfSale/(:num)'] = 'Sale/pdf_sale/$1';
$route['viewSChalan/(:num)'] = 'Sale/view_sales_challan/$1';
$route['editSale/(:num)'] = 'Sale/edit_sale/$1';
$route['saleReport'] = 'Sale/all_sales_reports';
$route['saleCReport'] = 'Sale/courier_sales_reports';
$route['newUSale/(:num)'] = 'Sale/edit_new_sales/$1';
$route['saleDList'] = 'Sale/sales_duplicate_list';
$route['editUSale/(:num)'] = 'Sale/edit_user_new_sales/$1';
$route['viewUSale/(:num)'] = 'Sale/view_new_sales/$1';
$route['salesiReport'] = 'Sale/sales_invoice_reports';
$route['salesdReport'] = 'Sale/sales_due_reports';
$route['salesDPReport'] = 'Sale/sales_due_payment_reports';
$route['servlist'] = 'Sale/service_sale_list';
$route['serviceReport'] = 'Sale/service_sale_reports';

$route['newService'] = 'Sale/new_service_sale';
$route['viewSSale/(:num)'] = 'Sale/view_sale_service/$1';
$route['editSSale/(:num)'] = 'Sale/edit_sale_service/$1';
$route['posSales'] = 'Sale/pos_sales_info';
$route['printPSale/(:num)'] = 'Sale/pos_sales_details/$1';

$route['Return'] = 'Returns';
$route['newReturn'] = 'Returns/new_return';
$route['viewReturn/(:num)'] = 'Returns/view_return/$1';
$route['editReturn/(:num)'] = 'Returns/edit_returns/$1';
$route['pReturn'] = 'Returns/purchase_return_list';
$route['newpReturn'] = 'Returns/new_purchase_return';
$route['viewpReturn/(:num)'] = 'Returns/view_purchase_return/$1';
$route['editpReturn/(:num)'] = 'Returns/edit_purchase_return/$1';

$route['Voucher'] = 'Voucher';
$route['newVoucher'] = 'Voucher/new_voucher';
$route['viewVoucher/(:num)'] = 'Voucher/voucher_details/$1';
$route['moneyReceipt/(:num)'] = 'Voucher/money_receipt/$1';
$route['editVoucher/(:num)'] = 'Voucher/voucher_edit/$1';

$route['vReports'] = 'Voucher/voucher_report';

$route['profil-Loss'] = 'Voucher/profit_loss';
$route['dReport'] = 'Voucher/daily_report';
$route['spReports'] = 'Voucher/sale_purchase_profit_report';
$route['notice'] = 'Voucher/user_notice';

$route['rVoucher'] = 'Voucher';
$route['newRVoucher'] = 'Voucher/new_received_voucher';
$route['viewRVoucher/(:num)'] = 'Voucher/received_voucher_details/$1';
$route['editRVoucher/(:num)'] = 'Voucher/received_voucher_edit/$1';
$route['pVoucher'] = 'Voucher/payment_voucher_list';
$route['newPVoucher'] = 'Voucher/new_payment_voucher';
$route['viewPVoucher/(:num)'] = 'Voucher/payment_voucher_details/$1';
$route['editPVoucher/(:num)'] = 'Voucher/payment_voucher_edit/$1';
$route['vrReport'] = 'Voucher/voucher_received_report';
$route['vpReport'] = 'Voucher/voucher_payment_report';

$route['Quotation'] = 'Quotation';
$route['newQuotation'] = 'Quotation/new_quotation';
$route['viewQuotation/(:num)'] = 'Quotation/view_quotation/$1';
$route['editQuotation/(:num)'] = 'Quotation/edit_quotation/$1';
$route['pdfQuotation/(:num)'] = 'Quotation/pdf_quotation/$1';

$route['cashReport'] = 'CashAccount/cash_reports';
$route['transAccount'] = 'CashAccount/transfer_account_list';
$route['transReport'] = 'CashAccount/transfer_account_report';

$route['BankAccount'] = 'BankAccount';
$route['bankReport'] = 'BankAccount/mobile_reports';
$route['bankTReport'] = 'BankAccount/bank_transation_reports';
$route['allTReport'] = 'BankAccount/all_transation_reports';

$route['MobileAccount'] = 'MobileAccount';
$route['mobileReport'] = 'MobileAccount/mobile_reports';

$route['newOrder'] = 'Order/new_Order';
$route['viewOrder/(:num)'] = 'Order/view_Order/$1';
$route['editOrder/(:num)'] = 'Order/edit_Order/$1';
$route['saleOrder/(:num)'] = 'Order/sale_Order/$1';
$route['orderReport'] = 'Order/order_ledger_report';



$route['Lcmanagement'] = 'Lcmanagement/index';
$route['newLcmanagement'] = 'Lcmanagement/new_lc_management';
$route['viewLcmanagement/(:num)'] = 'Lcmanagement/view_lc_management/$1';
$route['editLcmanagement/(:num)'] = 'Lcmanagement/edit_lc_management/$1';







$route['uNotice'] = 'User/user_notice_lists';
$route['uRole'] = 'User/user_role';
$route['User'] = 'User/user_list';
$route['helpSupport'] = 'User/help_support';
$route['userList'] = 'User/all_user_lists';

$route['pageSetup'] = 'Access_setup';
$route['userAccess'] = 'Access_setup/user_access_setup';
$route['viewUAccess/(:num)'] = 'Access_setup/view_uaccess_setup/$1';
$route['editUAccess/(:num)'] = 'Access_setup/edit_uaccess_setup/$1';

$route['empPayment'] = 'Employee_payment';
$route['newempPayment'] = 'Employee_payment/AddInfo';
$route['empPaymentDetails/(:num)'] = 'Employee_payment/emp_payment_details/$1';

$route['trackOrder'] = 'Webhome/track_order';

$route['subAType'] = 'Chartofaccounting/sub_account_type_list';
$route['chaType'] = 'Chartofaccounting/chart_account_type_list';
$route['chAccount'] = 'Chartofaccounting/chart_account_list';
$route['newCAccount'] = 'Chartofaccounting/new_chart_account';
$route['editCAccount/(:num)'] = 'Chartofaccounting/edit_chart_account/$1';
$route['journalEntry'] = 'Chartofaccounting/journal_entry_account_list';
$route['incomeStatement'] = 'Chartofaccounting/income_statement_list';
$route['trailBalance'] = 'Chartofaccounting/trail_balance_list';
$route['balanceSheet'] = 'Chartofaccounting/balance_sheet_list';
$route['generalLedger'] = 'Chartofaccounting/general_ledger_list';
$route['coaReport'] = 'Chartofaccounting/chart_of_account_report';
$route['voucherLedger'] = 'Chartofaccounting/voucher_ledger_report';

$route['bikeRegistration'] = 'Bike_Registration';
$route['regForm/(:num)'] = 'Bike_Registration/reg_form/$1';
$route['ownerForm/(:num)'] = 'Bike_Registration/owner_form/$1';
$route['challanForm/(:num)'] = 'Bike_Registration/challan_form/$1';
$route['concernForm/(:num)'] = 'Bike_Registration/concern_form/$1';
$route['ongikarnama/(:num)'] = 'Bike_Registration/ongikarnama/$1';
$route['bankForm/(:num)'] = 'Bike_Registration/bank_form/$1';
$route['gatePass/(:num)'] = 'Bike_Registration/gatePass/$1';
$route['salesReceipt/(:num)'] = 'Bike_Registration/salesReceipt/$1';
$route['brtaApplication/(:num)'] = 'Bike_Registration/brtaApplication/$1';
$route['certificate/(:num)'] = 'Bike_Registration/certificate/$1';


$route['Costing'] = 'Costing/index';
$route['newCosting'] = 'Costing/new_costing';
$route['vieCosting/(:num)'] = 'Costing/view_costing/$1';
$route['editCosting/(:num)'] = 'Costing/edit_costing/$1';
$route['costingReport'] = 'Costing/costing_reports';

