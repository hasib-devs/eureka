<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Access Setup</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Access Setup</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Access Setup Information</h3>
              </div>

              <div class="card-body">
        		<div class="row">
        		  <div class="col-md-12 col-sm-12 col-12">
              		<table>
              		  <tbody>
                        <tr>
                          <td>User Type</td>
                          <td>: <?= $user[0]['lavelName']; ?></td>
                        </tr>
                        <tr>
                          <td>Status</td>
                          <td>: <?= $user[0]['status']; ?></td>
                        </tr>
                      </tbody>
    			    </table>
                  </div>
            
            	  <div class="col-md-12 col-sm-12 col-12">
                    <div class="box-header">
                      <h3 class="box-title">List of Pages And Functions</h3>
                    </div>
                    <div class="box-body">
                      <form action="<?= base_url().'Access_setup/setup_user_access/'.$user[0]['ax_id']; ?>" method="post">
                        <div class="row">
		                  <div class="col-md-4 col-sm-4 col-12">
                            <h5 style="background-color: #007bff; color: #fff; padding-left: 20px; border-radius: 10px;padding: 10px;">
                              <label><input type="checkbox" name="dashboard" value="1" <?php if($master[0]['dashboard'] == '1'){ ?>checked<?php } ?>> Dashboard</label>
                            </h5>
                            <div class="page_box" >
                              <div class="col-md-12 col-sm-12 col-12">
                                <table class="table table-bordered table-striped">
                                  <thead>
                                    <tr>
                                      <th style="width: 70%;">Page</th>
                                      <th style="width: 30%;">Function</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="todaysales" value="1" <?php if($page[0]['todaysales'] == '1'){ ?>checked<?php } ?>> Today Sales</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type:none;"></ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="todaypurchase" value="1" <?php if($page[0]['todaypurchase'] == '1'){ ?>checked<?php } ?>> Today Purchases</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type:none;"></ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="todayexpense" value="1" <?php if($page[0]['todayexpense'] == '1'){ ?>checked<?php } ?>> Today Expenses</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type:none;"></ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="todayincome" value="1" <?php if($page[0]['todayincome'] == '1'){ ?>checked<?php } ?>> Today Income</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type:none;"></ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="lastdSales" value="1" <?php if($page[0]['lastdSales'] == '1'){ ?>checked<?php } ?>> Last 7 Days Sale</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type:none;"></ul>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4 col-sm-4 col-12">
                            <h5 style="background-color: #007bff; color: #fff; padding-left: 20px; border-radius: 10px;padding: 10px;">
                              <label><input type="checkbox" name="inventory" value="1" <?php if($master[0]['inventory'] == '1'){ ?>checked<?php } ?>> Inventory</label>
                            </h5>
                            <div class="page_box" >
                              <div class="col-md-12 col-sm-12 col-12">
                                <table class="table table-bordered table-striped">
                                  <thead>
                                    <tr>
                                      <th style="width: 40%;">Page</th>
                                      <th style="width: 60%;">Function</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="products" value="1" <?php if($page[0]['products'] == '1'){ ?>checked<?php } ?>> Products</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newProduct" value="1" <?php if($function[0]['newProduct'] == '1'){ ?>checked<?php } ?>> New Product</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editProduct" value="1" <?php if($function[0]['editProduct'] == '1'){ ?>checked<?php } ?>> Edit Product</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteProduct" value="1" <?php if($function[0]['deleteProduct'] == '1'){ ?>checked<?php } ?>> Delete Product</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="storeProduct" value="1" <?php if($function[0]['storeProduct'] == '1'){ ?>checked<?php } ?>> Store Product</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="services" value="1" <?php if($page[0]['services'] == '1'){ ?>checked<?php } ?>> Services</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newService" value="1" <?php if($function[0]['newService'] == '1'){ ?>checked<?php } ?>> New Service</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editService" value="1" <?php if($function[0]['editService'] == '1'){ ?>checked<?php } ?>> Edit Service</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteService" value="1" <?php if($function[0]['deleteService'] == '1'){ ?>checked<?php } ?>> Delete Service</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="purchases" value="1" <?php if($page[0]['purchases'] == '1'){ ?>checked<?php } ?>> Purchases</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newPurchase" value="1" <?php if($function[0]['newPurchase'] == '1'){ ?>checked<?php } ?>> New Purchase</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editPurchase" value="1" <?php if($function[0]['editPurchase'] == '1'){ ?>checked<?php } ?>> Edit Purchase</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deletePurchase" value="1" <?php if($function[0]['deletePurchase'] == '1'){ ?>checked<?php } ?>> Delete Purchase</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="sales" value="1" <?php if($page[0]['sales'] == '1'){ ?>checked<?php } ?>> Sales</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newSale" value="1" <?php if($function[0]['newSale'] == '1'){ ?>checked<?php } ?>> New Sale</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editSale" value="1" <?php if($function[0]['editSale'] == '1'){ ?>checked<?php } ?>> Edit Sale</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteSale" value="1" <?php if($function[0]['deleteSale'] == '1'){ ?>checked<?php } ?>> Delete Sale</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="salesbrta" value="1" <?php if($function[0]['salesbrta'] == '1'){ ?>checked<?php } ?>> Sales BRTA</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="saleservices" value="1" <?php if($page[0]['saleservices'] == '1'){ ?>checked<?php } ?>> Sale Services</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newSService" value="1" <?php if($function[0]['newSService'] == '1'){ ?>checked<?php } ?>> New Sale Services</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editSService" value="1" <?php if($function[0]['editSService'] == '1'){ ?>checked<?php } ?>> Edit Sale Services</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteSService" value="1" <?php if($function[0]['deleteSService'] == '1'){ ?>checked<?php } ?>> Delete Sale Services</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="salereturns" value="1" <?php if($page[0]['services'] == '1'){ ?>checked<?php } ?>> Sale Returns</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newSReturns" value="1" <?php if($function[0]['newSReturns'] == '1'){ ?>checked<?php } ?>> New Sale Return</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editSReturns" value="1" <?php if($function[0]['editSReturns'] == '1'){ ?>checked<?php } ?>> Edit Sale Return</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteSReturns" value="1" <?php if($function[0]['deleteSReturns'] == '1'){ ?>checked<?php } ?>> Delete Sale Return</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="purreturns" value="1" <?php if($page[0]['purreturns'] == '1'){ ?>checked<?php } ?>> Purchases Returns</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newPReturns" value="1" <?php if($function[0]['newPReturns'] == '1'){ ?>checked<?php } ?>> New Purchase Returns</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editPReturns" value="1" <?php if($function[0]['editPReturns'] == '1'){ ?>checked<?php } ?>> Edit Purchase Returns</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deletePReturns" value="1" <?php if($function[0]['deletePReturns'] == '1'){ ?>checked<?php } ?>> Delete Purchase Returns</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="preorder" value="1" <?php if($page[0]['preorder'] == '1'){ ?>checked<?php } ?>> Pre-Order</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newPreorder" value="1" <?php if($function[0]['newPreorder'] == '1'){ ?>checked<?php } ?>> New Pre-Order</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editPreorder" value="1" <?php if($function[0]['editPreorder'] == '1'){ ?>checked<?php } ?>> Edit Pre-Order</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deletePreorder" value="1" <?php if($function[0]['deletePreorder'] == '1'){ ?>checked<?php } ?>> Delete Pre-Order</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="quotations" value="1" <?php if($page[0]['quotations'] == '1'){ ?>checked<?php } ?>> Quotations</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newQuotations" value="1" <?php if($function[0]['newQuotations'] == '1'){ ?>checked<?php } ?>> New Quotations</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editQuotations" value="1" <?php if($function[0]['editQuotations'] == '1'){ ?>checked<?php } ?>> Edit Quotations</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteQuotations" value="1" <?php if($function[0]['deleteQuotations'] == '1'){ ?>checked<?php } ?>> Delete Quotations</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4 col-sm-4 col-12">
                            <h5 style="background-color: #007bff; color: #fff; padding-left: 20px; border-radius: 10px;padding: 10px;">
                              <label><input type="checkbox" name="brtaregstar" value="1" <?php if($master[0]['brtaregstar'] == '1'){ ?>checked<?php } ?>> BRTA Registration</label>
                            </h5>
                            <div class="page_box" >
                              <div class="col-md-12 col-sm-12 col-12">
                                <table class="table table-bordered table-striped">
                                  <thead>
                                    <tr>
                                      <th style="width: 40%;">Page</th>
                                      <th style="width: 60%;">Function</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="brtareglist" value="1" <?php if($page[0]['brtareglist'] == '1'){ ?>checked<?php } ?>> BRTA Reg. List</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editbrtareg" value="1" <?php if($function[0]['editbrtareg'] == '1'){ ?>checked<?php } ?>> Edit BRTA Reg.</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4 col-sm-4 col-12">
                            <h5 style="background-color: #007bff; color: #fff; padding-left: 20px; border-radius: 10px;padding: 10px;">
                              <label><input type="checkbox" name="accounting" value="1" <?php if($master[0]['accounting'] == '1'){ ?>checked<?php } ?>> Accounting</label>
                            </h5>
                            <div class="page_box" >
                              <div class="col-md-12 col-sm-12 col-12">
                                <table class="table table-bordered table-striped">
                                  <thead>
                                    <tr>
                                      <th style="width: 50%;">Page</th>
                                      <th style="width: 50%;">Function</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="majortype" value="1" <?php if($page[0]['majortype'] == '1'){ ?>checked<?php } ?>> Major Type</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newMType" value="1" <?php if($function[0]['newMType'] == '1'){ ?>checked<?php } ?>> New Major Type</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editMType" value="1" <?php if($function[0]['editMType'] == '1'){ ?>checked<?php } ?>> Edit Major Type</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteMType" value="1" <?php if($function[0]['deleteMType'] == '1'){ ?>checked<?php } ?>> Delete Major Type</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="subtype" value="1" <?php if($page[0]['subtype'] == '1'){ ?>checked<?php } ?>> Sub Type</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newSType" value="1" <?php if($function[0]['newSType'] == '1'){ ?>checked<?php } ?>> New Sub Type</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editSType" value="1" <?php if($function[0]['editSType'] == '1'){ ?>checked<?php } ?>> Edit Sub Type</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteSType" value="1" <?php if($function[0]['deleteSType'] == '1'){ ?>checked<?php } ?>> Delete Sub Type</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="voucherentry" value="1" <?php if($page[0]['voucherentry'] == '1'){ ?>checked<?php } ?>> Voucher Entry</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newVEntry" value="1" <?php if($function[0]['newVEntry'] == '1'){ ?>checked<?php } ?>> New Voucher Entry</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editVEntry" value="1" <?php if($function[0]['editVEntry'] == '1'){ ?>checked<?php } ?>> Edit Voucher Entry</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteVEntry" value="1" <?php if($function[0]['deleteVEntry'] == '1'){ ?>checked<?php } ?>> Delete Voucher Entry</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="voucherreports" value="1" <?php if($page[0]['voucherreports'] == '1'){ ?>checked<?php } ?>> Voucher Reports</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="journalentry" value="1" <?php if($page[0]['journalentry'] == '1'){ ?>checked<?php } ?>> Journal Entry</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="trailbalance" value="1" <?php if($page[0]['trailbalance'] == '1'){ ?>checked<?php } ?>> Trail Balance</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="balancesheet" value="1" <?php if($page[0]['balancesheet'] == '1'){ ?>checked<?php } ?>> Balance Sheet</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="generalledger" value="1" <?php if($page[0]['generalledger'] == '1'){ ?>checked<?php } ?>> General Ledger</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="incomestatement" value="1" <?php if($page[0]['incomestatement'] == '1'){ ?>checked<?php } ?>> Income Statement</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4 col-sm-4 col-12">
                            <h5 style="background-color: #007bff; color: #fff; padding-left: 20px; border-radius: 10px;padding: 10px;">
                              <label><input type="checkbox" name="hrpayroll" value="1" <?php if($master[0]['hrpayroll'] == '1'){ ?>checked<?php } ?>> HR & Payroll</label>
                            </h5>
                            <div class="page_box" >
                              <div class="col-md-12 col-sm-12 col-12">
                                <table class="table table-bordered table-striped">
                                  <thead>
                                    <tr>
                                      <th style="width: 40%;">Page</th>
                                      <th style="width: 60%;">Function</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="customers" value="1" <?php if($page[0]['customers'] == '1'){ ?>checked<?php } ?>> Customers</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newCustomer" value="1" <?php if($function[0]['newCustomer'] == '1'){ ?>checked<?php } ?>> New Customer</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editCustomer" value="1" <?php if($function[0]['editCustomer'] == '1'){ ?>checked<?php } ?>> Edit Customer</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteCustomer" value="1" <?php if($function[0]['deleteCustomer'] == '1'){ ?>checked<?php } ?>> Delete Customer</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="suppliers" value="1" <?php if($page[0]['suppliers'] == '1'){ ?>checked<?php } ?>> Suppliers</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newSupplier" value="1" <?php if($function[0]['newSupplier'] == '1'){ ?>checked<?php } ?>> New Supplier</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editSupplier" value="1" <?php if($function[0]['editSupplier'] == '1'){ ?>checked<?php } ?>> Edit Supplier</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteSupplier" value="1" <?php if($function[0]['deleteSupplier'] == '1'){ ?>checked<?php } ?>> Delete Supplier</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="employees" value="1" <?php if($page[0]['employees'] == '1'){ ?>checked<?php } ?>> Employees</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newEmployee" value="1" <?php if($function[0]['newEmployee'] == '1'){ ?>checked<?php } ?>> New Employee</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editEmployee" value="1" <?php if($function[0]['editEmployee'] == '1'){ ?>checked<?php } ?>> Edit Employee</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteEmployee" value="1" <?php if($function[0]['deleteEmployee'] == '1'){ ?>checked<?php } ?>> Delete Employee</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="users" value="1" <?php if($page[0]['users'] == '1'){ ?>checked<?php } ?>> Users</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newUser" value="1" <?php if($function[0]['newUser'] == '1'){ ?>checked<?php } ?>> New User</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editUser" value="1" <?php if($function[0]['editUser'] == '1'){ ?>checked<?php } ?>> Edit User</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteUser" value="1" <?php if($function[0]['deleteUser'] == '1'){ ?>checked<?php } ?>> Delete User</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="emppayments" value="1" <?php if($page[0]['emppayments'] == '1'){ ?>checked<?php } ?>> Employee Payments</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newEmppay" value="1" <?php if($function[0]['newEmppay'] == '1'){ ?>checked<?php } ?>> New Emp. Payment</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4 col-sm-4 col-12">
                            <h5 style="background-color: #007bff; color: #fff; padding-left: 20px; border-radius: 10px;padding: 10px;">
                              <label><input type="checkbox" name="reports" value="1" <?php if($master[0]['reports'] == '1'){ ?>checked<?php } ?>> Reports</label>
                            </h5>
                            <div class="page_box" >
                              <div class="col-md-12 col-sm-12 col-12">
                                <table class="table table-bordered table-striped">
                                  <thead>
                                    <tr>
                                      <th style="width: 70%;">Page</th>
                                      <th style="width: 30%;">Function</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="salesreports" value="1" <?php if($page[0]['salesreports'] == '1'){ ?>checked<?php } ?>> Sales Reports</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="purchasereports" value="1" <?php if($page[0]['purchasereports'] == '1'){ ?>checked<?php } ?>> Purchase Reports</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="profitreports" value="1" <?php if($page[0]['profitreports'] == '1'){ ?>checked<?php } ?>> Profit / Loss Reports</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="spprofit" value="1" <?php if($page[0]['spprofit'] == '1'){ ?>checked<?php } ?>> Sales / Purchase Profit</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="custreports" value="1" <?php if($page[0]['custreports'] == '1'){ ?>checked<?php } ?>> Customer Reports</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="custledger" value="1" <?php if($page[0]['custledger'] == '1'){ ?>checked<?php } ?>> Customer Ledger</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="supreports" value="1" <?php if($page[0]['supreports'] == '1'){ ?>checked<?php } ?>> Suppliers Reports</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="supledger" value="1" <?php if($page[0]['supledger'] == '1'){ ?>checked<?php } ?>> Suppliers Ledger</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="stockreports" value="1" <?php if($page[0]['stockreports'] == '1'){ ?>checked<?php } ?>> Stock Reports</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="orderreports" value="1" <?php if($page[0]['orderreports'] == '1'){ ?>checked<?php } ?>> Order Reports</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="dailyreports" value="1" <?php if($page[0]['dailyreports'] == '1'){ ?>checked<?php } ?>> Daily Reports</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="cashbook" value="1" <?php if($page[0]['cashbook'] == '1'){ ?>checked<?php } ?>> Cash Book</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="bankbook" value="1" <?php if($page[0]['bankbook'] == '1'){ ?>checked<?php } ?>> Bank Book</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="mobilebook" value="1" <?php if($page[0]['mobilebook'] == '1'){ ?>checked<?php } ?>> Mobile Book</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="swpreports" value="1" <?php if($page[0]['swpreports'] == '1'){ ?>checked<?php } ?>> Profit Reports (Sales Wise)</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="duereports" value="1" <?php if($page[0]['duereports'] == '1'){ ?>checked<?php } ?>> Due Reports (Sales Wise)</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="btransreports" value="1" <?php if($page[0]['btransreports'] == '1'){ ?>checked<?php } ?>> Bank Transaction Reports </label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="duepreports" value="1" <?php if($page[0]['duepreports'] == '1'){ ?>checked<?php } ?>> Due Payment Reports</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="expensereports" value="1" <?php if($page[0]['expensereports'] == '1'){ ?>checked<?php } ?>> Expense Reports</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="btransferreports" value="1" <?php if($page[0]['btransferreports'] == '1'){ ?>checked<?php } ?>> Balance Transfer Reports</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="alltransreports" value="1" <?php if($page[0]['alltransreports'] == '1'){ ?>checked<?php } ?>> All Transaction Reports</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4 col-sm-4 col-12">
                            <h5 style="background-color: #007bff; color: #fff; padding-left: 20px; border-radius: 10px;padding: 10px;">
                              <label><input type="checkbox" name="settings" value="1" <?php if($master[0]['settings'] == '1'){ ?>checked<?php } ?>> Settings</label>
                            </h5>
                            <div class="page_box" >
                              <div class="col-md-12 col-sm-12 col-12">
                                <table class="table table-bordered table-striped">
                                  <thead>
                                    <tr>
                                      <th style="width: 40%;">Page</th>
                                      <th style="width: 60%;">Function</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="category" value="1" <?php if($page[0]['category'] == '1'){ ?>checked<?php } ?>> Category</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newCategory" value="1" <?php if($function[0]['newCategory'] == '1'){ ?>checked<?php } ?>> New Category</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editCategory" value="1" <?php if($function[0]['editCategory'] == '1'){ ?>checked<?php } ?>> Edit Category</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteCategory" value="1" <?php if($function[0]['deleteCategory'] == '1'){ ?>checked<?php } ?>> Delete Category</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="units" value="1" <?php if($page[0]['units'] == '1'){ ?>checked<?php } ?>> Units</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newUnit" value="1" <?php if($function[0]['newUnit'] == '1'){ ?>checked<?php } ?>> New Unit</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editUnit" value="1" <?php if($function[0]['editUnit'] == '1'){ ?>checked<?php } ?>> Edit Unit</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteUnit" value="1" <?php if($function[0]['deleteUnit'] == '1'){ ?>checked<?php } ?>> Delete Unit</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="department" value="1" <?php if($page[0]['department'] == '1'){ ?>checked<?php } ?>> Department</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newDepartment" value="1" <?php if($function[0]['newDepartment'] == '1'){ ?>checked<?php } ?>> New Department</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editDepartment" value="1" <?php if($function[0]['editDepartment'] == '1'){ ?>checked<?php } ?>> Edit Department</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteDepartment" value="1" <?php if($function[0]['deleteDepartment'] == '1'){ ?>checked<?php } ?>> Delete Department</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="cashaccount" value="1" <?php if($page[0]['cashaccount'] == '1'){ ?>checked<?php } ?>> Cash Account</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newCAccount" value="1" <?php if($function[0]['newCAccount'] == '1'){ ?>checked<?php } ?>> New Cash Account</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editCAccount" value="1" <?php if($function[0]['editCAccount'] == '1'){ ?>checked<?php } ?>> Edit Cash Account</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteCAccount" value="1" <?php if($function[0]['deleteCAccount'] == '1'){ ?>checked<?php } ?>> Delete Cash Account</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="bankaccount" value="1" <?php if($page[0]['bankaccount'] == '1'){ ?>checked<?php } ?>> Bank Account</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newBAccount" value="1" <?php if($function[0]['newBAccount'] == '1'){ ?>checked<?php } ?>> New Bank Account</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editBAccount" value="1" <?php if($function[0]['editBAccount'] == '1'){ ?>checked<?php } ?>> Edit Bank Account</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteBAccount" value="1" <?php if($function[0]['deleteBAccount'] == '1'){ ?>checked<?php } ?>> Delete Bank Account</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="mobileaccount" value="1" <?php if($page[0]['mobileaccount'] == '1'){ ?>checked<?php } ?>> Mobile Account</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newMAccount" value="1" <?php if($function[0]['newMAccount'] == '1'){ ?>checked<?php } ?>> New Mobile Account</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editMAccount" value="1" <?php if($function[0]['editMAccount'] == '1'){ ?>checked<?php } ?>> Edit Mobile Account</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteMAccount" value="1" <?php if($function[0]['deleteMAccount'] == '1'){ ?>checked<?php } ?>> Delete Mobile Account</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="usertype" value="1" <?php if($page[0]['usertype'] == '1'){ ?>checked<?php } ?>> User Type</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newUType" value="1" <?php if($function[0]['newUType'] == '1'){ ?>checked<?php } ?>> New User Type</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editUType" value="1" <?php if($function[0]['editUType'] == '1'){ ?>checked<?php } ?>> Edit User Type</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteUType" value="1" <?php if($function[0]['deleteUType'] == '1'){ ?>checked<?php } ?>> Delete User Type</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="purchasetype" value="1" <?php if($page[0]['purchasetype'] == '1'){ ?>checked<?php } ?>> Purchase Type</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newPType" value="1" <?php if($function[0]['newPType'] == '1'){ ?>checked<?php } ?>> New Purchase Type</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editPType" value="1" <?php if($function[0]['editPType'] == '1'){ ?>checked<?php } ?>> Edit Purchase Type</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deletePType" value="1" <?php if($function[0]['deletePType'] == '1'){ ?>checked<?php } ?>> Delete Purchase Type</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="balancetransfer" value="1" <?php if($page[0]['balancetransfer'] == '1'){ ?>checked<?php } ?>> Balance Transfer</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="newTransfer" value="1" <?php if($function[0]['newTransfer'] == '1'){ ?>checked<?php } ?>> New Balance Transfer</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="editTransfer" value="1" <?php if($function[0]['editTransfer'] == '1'){ ?>checked<?php } ?>> Edit Balance Transfer</label>
                                            </div>
                                          </li>
                                          <li>
                                            <div class="checkbox">
                                              <label><input type="checkbox" name="deleteTransfer" value="1" <?php if($function[0]['deleteTransfer'] == '1'){ ?>checked<?php } ?>> Delete Balance Transfer</label>
                                            </div>
                                          </li>
                                        </ul>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4 col-sm-4 col-12">
                            <h5 style="background-color: #007bff; color: #fff; padding-left: 20px; border-radius: 10px;padding: 10px;">
                              <label><input type="checkbox" name="accesssetup" value="1" <?php if($master[0]['accesssetup'] == '1'){ ?>checked<?php } ?>> Access Setup</label>
                            </h5>
                            <div class="page_box" >
                              <div class="col-md-12 col-sm-12 col-12">
                                <table class="table table-bordered table-striped">
                                  <thead>
                                    <tr>
                                      <th style="width: 60%;">Page</th>
                                      <th style="width: 40%;">Function</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>
                                        <div class="checkbox">
                                          <label><input type="checkbox" name="accesssetuplist" value="1" <?php if($page[0]['accesssetuplist'] == '1'){ ?>checked<?php } ?>> Access Setup</label>
                                        </div>
                                      </td>
                                      <td>
                                        <ul style="list-style-type: none; margin-left: -40px;">
                                        </ul>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>


						</div>
	              		<div class="col-md-12 col-sm-12 col-12" style="text-align: center;">
                    	  <button type="submit" class="btn btn-info"><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
                          <a href="<?php echo site_url('userAccess') ?>" class="btn btn-danger" ><i class="fa fa-arrow-left" ></i>&nbsp;&nbsp;Back</a>
                    	</div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<?php $this->load->view('footer/footer');?>