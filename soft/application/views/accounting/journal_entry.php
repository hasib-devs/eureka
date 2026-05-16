<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Journal Entry</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Journal Entry</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-12">
            <div class="card">
              <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">Journal Entry</h3>
                <ul class="nav nav-pills ml-auto p-2">
                  <li class="nav-item"><a class="nav-link active" href="#tab_customer" data-toggle="tab">Customer Journal</a></li>
                  <li class="nav-item"><a class="nav-link" href="#tab_supplier" data-toggle="tab">Supplier Journal</a></li>
                </ul>
              </div>

              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_customer">
                    <div class="col-sm-12 col-md-12 col-12">
                      <form action="<?php echo base_url() ?>journalEntry" method="get">
                        <input type="hidden" name="rtype" value="customer" required >
                        <div class="col-md-12 col-sm-12 col-12">
                          <div class="form-group">
                            <b>
                              <input type="radio" name="reports" value="ocust" id="ocust" required >&nbsp;&nbsp;Customer All Journal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input type="radio" name="reports" value="dailyReports" id="daily" required >&nbsp;&nbsp;Daily Journal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input type="radio" name="reports" value="monthlyReports" id="monthly" required >&nbsp;&nbsp;Monthly Journal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input type="radio" name="reports" value="yearlyReports" id="yearly" required >&nbsp;&nbsp;Yearly Journal
                            </b>
                          </div>

                          <div class="d-none" id="dreports">
                            <div class="row">
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <label>Start Date *</label>
                                <input type="text" class="form-control datepicker" name="sdate" value="<?php echo date('m/d/Y') ?>" id="sdate" required="" >
                              </div>
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <label>End Date *</label>
                                <input type="text" class="form-control datepicker" name="edate" value="<?php echo date('m/d/Y') ?>" id="edate" required="" >
                              </div>
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <label>Select Customer *</label>
                                <select name="dcustomer" class="form-control" id="dcustomer" required="" >
                                  <option value="">Select One</option>
                                  <?php foreach ($customer as $value) { ?>
                                  <option value="<?php echo $value['custid']; ?>" ><?php echo $value['custName'].' ( '.$value['custCode'].' )'; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                              <div class="form-group col-md-2 col-sm-2 col-12">
                                <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>
                              </div>
                            </div>
                          </div>

                          <div class="d-none" id="mreports">
                            <div class="row">
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <label>Select Month *</label>
                                <select class="form-control" name="month" id="month" required="" >
                                  <option value="">Select One</option>
                                  <option value="1">January</option>
                                  <option value="2">February</option>
                                  <option value="3">March</option>
                                  <option value="4">April</option>
                                  <option value="5">May</option>
                                  <option value="6">June</option>
                                  <option value="7">July</option>
                                  <option value="8">August</option>
                                  <option value="9">September</option>
                                  <option value="10">October</option>
                                  <option value="11">November</option>
                                  <option value="12">December</option>
                                </select>
                              </div>
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <label>Select Year *</label>
                                <select class="form-control" name="year" id="year" required="" >
                                  <?php $d = date("Y"); ?>
                                  <option value="">Select One</option>
                                  <?php for ($x = 2020; $x <= $d; $x++) { ?>
                                  <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <label>Select Customer *</label>
                                <select name="mcustomer" class="form-control" id="mcustomer" required="" >
                                  <option value="">Select One</option>
                                  <?php foreach ($customer as $value) { ?>
                                  <option value="<?php echo $value['custid']; ?>" ><?php echo $value['custName'].' ( '.$value['custCode'].' )'; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                              <div class="form-group col-md-2 col-sm-2 col-12">
                                <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>
                              </div>
                            </div>
                          </div>

                          <div class="d-none" id="yreports">
                            <div class="row">
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <label>Select Year *</label>
                                <select class="form-control" name="ryear" id="ryear" required="" >
                                  <?php $d = date("Y"); ?>
                                  <option value="">Select One</option>
                                  <?php for ($x = 2020; $x <= $d; $x++) { ?>
                                  <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <label>Select Customer *</label>
                                <select name="ycustomer" class="form-control" id="ycustomer" required="" >
                                  <option value="">Select One</option>
                                  <?php foreach ($customer as $value) { ?>
                                  <option value="<?php echo $value['custid']; ?>" ><?php echo $value['custName'].' ( '.$value['custCode'].' )'; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                              <div class="form-group col-md-2 col-sm-2 col-12">
                                <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>
                              </div>
                            </div>
                          </div>

                          <div class="d-none" id="reports">
                            <div class="row">
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <label>Select Customer *</label>
                                <select name="customer" class="form-control" id="customer" required="" >
                                  <option value="">Select One</option>
                                  <?php foreach ($customer as $value) { ?>
                                  <option value="<?php echo $value['custid']; ?>" ><?php echo $value['custName'].' ( '.$value['custCode'].' )'; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                              <div class="form-group col-md-2 col-sm-2 col-12">
                                <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>
                              </div>
                            </div>
                          </div>

                        </div>
                      </form>
                    </div>
                  </div>

                  <div class="tab-pane" id="tab_supplier">
                    <div class="col-md-12 col-sm-12 col-12">
                      <form action="<?php echo base_url() ?>journalEntry" method="get">
                        <input type="hidden" name="rtype" value="supplier" required >
                        <div class="col-md-12 col-sm-12 col-12">
                          <div class="form-group col-md-12 col-sm-12 col-12">
                            <b>
                              <input type="radio" name="reports" value="asupplier" id="allsupp" required >&nbsp;&nbsp;Supplier All Journal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input type="radio" name="reports" value="dailysReports" id="sdaily" required >&nbsp;&nbsp;Daily Journal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input type="radio" name="reports" value="monthlysReports" id="smonthly" required >&nbsp;&nbsp;Monthly Journal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <input type="radio" name="reports" value="yearlysReports" id="syearly" required >&nbsp;&nbsp;Yearly Journal
                            </b>
                          </div>

                          <div class="d-none" id="allsreports">
                            <div class="row">
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <label>Select Supplier *</label>
                                <select class="form-control" name="supplier" required="" id="supplier" >
                                  <option value="">Select One</option>
                                  <?php foreach($supplier as $row){ ?>
                                  <option value="<?php echo $row['supid']; ?>"><?php echo $row['supName'].' ( '.$row['supCode'].' )'; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>
                              </div>
                            </div>
                          </div>

                          <div class="d-none" id="dsreports">
                            <div class="row">
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <label>Start Date *</label>
                                <input type="text" class="form-control datepicker" name="ssdate" value="<?php echo date('m/d/Y') ?>" id="ssdate" required="" >
                              </div>
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <label>End Date *</label>
                                <input type="text" class="form-control datepicker" name="esdate" value="<?php echo date('m/d/Y') ?>" id="esdate" required="" >
                              </div>
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <label>Select Supplier *</label>
                                <select class="form-control" name="dsupplier"  required="" id="dsupplier" >
                                  <option value="">Select One</option>
                                  <?php foreach($supplier as $row){ ?>
                                  <option value="<?php echo $row['supid']; ?>"><?php echo $row['supName'].' ( '.$row['supCode'].' )'; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>
                              </div>
                            </div>
                          </div>

                          <div class="d-none" id="msreports">
                            <div class="row">
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <label>Select Month *</label>
                                <select class="form-control" name="smonth" id="smonth" required="" >
                                  <option value="">Select One</option>
                                  <option value="1">January</option>
                                  <option value="2">February</option>
                                  <option value="3">March</option>
                                  <option value="4">April</option>
                                  <option value="5">May</option>
                                  <option value="6">June</option>
                                  <option value="7">July</option>
                                  <option value="8">August</option>
                                  <option value="9">September</option>
                                  <option value="10">October</option>
                                  <option value="11">November</option>
                                  <option value="12">December</option>
                                </select>
                              </div>
                              <div class="form-group col-md-2 col-sm-2 col-12">
                                <label>Select Year *</label>
                                <select class="form-control" name="syear" id="syear" required="" >
                                  <?php $d = date("Y"); ?>
                                  <option value="">Select One</option>
                                  <?php for ($x = 2020; $x <= $d; $x++) { ?>
                                  <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <label>Select Supplier *</label>
                                <select class="form-control chosen" name="msupplier"  required="" id="msupplier" >
                                  <option value="">Select One</option>
                                  <?php foreach($supplier as $row){ ?>
                                  <option value="<?php echo $row['supid']; ?>"><?php echo $row['supName'].' ( '.$row['supCode'].' )'; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>
                              </div>
                            </div>
                          </div>

                          <div class="d-none" id="ysreports">
                            <div class="row">
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <label>Select Year *</label>
                                <select class="form-control" name="rsyear" id="rsyear" required="" >
                                  <?php $d = date("Y"); ?>
                                  <option value="">Select One</option>
                                  <?php for ($x = 2020; $x <= $d; $x++) { ?>
                                  <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <label>Select Supplier *</label>
                                <select class="form-control" name="ysupplier"  required="" id="ysupplier" >
                                  <option value="">Select One</option>
                                  <?php foreach($supplier as $row){ ?>
                                  <option value="<?php echo $row['supid']; ?>"><?php echo $row['supName'].' ( '.$row['supCode'].' )'; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                              <div class="form-group col-md-3 col-sm-3 col-12">
                                <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>
                              </div>
                            </div>
                          </div>

                        </div>
                      </form>
                    </div>
                  </div>
                </div><hr>

                <div class="box-body">
                  <?php if(isset($_GET['search'])) { ?>
                  <div id="print">
                    <div class="row" id="header" style="display: none" >
                      <div class="col-sm-2 col-md-2 col-2" style="margin-top: 30px;">
                        <img src="<?php echo base_url($company->com_logo); ?>"  style="width: 100%;">
                      </div>
                      <div class="col-sm-10 col-md-10 col-10">
                        <div class="col-sm-12 col-md-12 col-12">
                          <h3><b><?php echo $company->com_name; ?></b></h3>
                        </div>
                        <div class="col-sm-12 col-md-12 col-12">
                          <b>Address&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $company->com_address; ?></b>
                        </div>
                        <div class="col-sm-12 col-md-12 col-12">
                          <b>Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $company->com_email; ?></b>
                        </div>
                        <div class="col-sm-12 col-md-12 col-12">
                          <b>Mobile&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $company->com_mobile; ?></b>
                        </div>
                      </div>
                    </div>

                    <?php if($rtype == 'customer') { ?>
                    <?php if ($report == 'dailyReports'){ ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Customer Journal Reports in : <?php echo $sdate.' - '.$edate; ?></b></h3>
                    </div>
                    <?php } else if ($report == 'monthlyReports') { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Customer Journal Reports in : <?php echo $name.' '.$year; ?></b></h3>
                    </div>
                    <?php } else if ($report == 'yearlyReports') { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Customer Journal Reports in : <?php echo $year; ?></b></h3>
                    </div>
                    <?php } else { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Customer Journal Reports</b></h3>
                    </div>
                    <?php } ?><br>

                    <div class="row">
                      <div class="col-sm-6 col-md-6 col-12">
                        <div class="col-sm-12 col-md-12 col-12">
                          Customer ID&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $cust[0]['custCode']; ?>
                        </div>
                        <div class="col-sm-12 col-md-12 col-12">
                          Customer Name&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $cust[0]['custName']; ?>
                        </div>
                      </div>
                      <div class="col-sm-6 col-md-6 col-12">
                        <div class="col-sm-12 col-md-12 col-12">
                          Address&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $cust[0]['custAddress']; ?>
                        </div>
                        <div class="col-sm-12 col-md-12 col-12">
                          Contact No&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $cust[0]['custMobile']; ?>
                        </div>
                      </div>
                    </div>
                    <?php } else if($rtype == 'supplier') { ?>
                    <?php if ($report == 'dailysReports') { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Supplier Journal Reports in : <?php echo $sdate.' - '.$edate; ?></b></h3>
                    </div>
                    <?php } else if ($report == 'monthlysReports') { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Supplier Journal Reports in : <?php echo $name.' '.$year; ?></b></h3>
                    </div>
                    <?php } else if ($report == 'yearlysReports') { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Supplier Journal Reports in : <?php echo $year; ?></b></h3>
                    </div>
                    <?php } else { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Supplier Journal Reports</b></h3>
                    </div>
                    <?php } ?>

                    <div class="row">
                      <div class="col-sm-6 col-md-6 col-xs-12">
                        <div class="col-sm-12 col-md-12 col-xs-12">
                          Supplier ID&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $supp[0]['supCode']; ?>
                        </div>
                        <div class="col-sm-12 col-md-12 col-12">
                          Supplier Name&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $supp[0]['supName']; ?>
                        </div>
                      </div>
                      <div class="col-sm-6 col-md-6 col-xs-12">
                        <div class="col-sm-12 col-md-12 col-12">
                          Address&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $supp[0]['supAddress']; ?>
                        </div>
                        <div class="col-sm-12 col-md-12 col-12">
                          Contact No&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $supp[0]['supMobile']; ?>
                        </div>
                      </div>
                    </div>
                    <?php } ?><br>
                  
                    <div id="table-content">
                      <table id="" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>#SN.</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Reference</th>
                          </tr>
                        </thead>
                        <?php if($rtype == 'customer') { ?>
                        <tbody>
                          <?php if($cust[0]['balance'] > 0) { ?>
                          <?php $c = 1; ?>
                          <tr class="gradeX">
                            <td><?php echo $c; ?></td>
                            <td><?php echo date('d-m-Y',strtotime($cust[0]['regdate'])); ?></td>
                            <td><?php echo 'Opening Balance'; ?></td>
                            <td><?php echo number_format(($cust[0]['balance']), 2); ?></td> 
                            <td><?php echo '00'; ?></td> 
                            <td></td> 
                          </tr>   
                          <?php } else{ ?>
                          <?php $c = 0; ?>
                          <?php } ?>

                          <?php if($sale != null) { ?>

                          <?php
                          $i = $c;
                          $tsca = 0;
                          $tsda = 0;
                          foreach ($sale as $value){
                          $i++;
                          ?>
                          <tr class="gradeX">
                            <td><?php echo $i; ?></td>
                            <td><?php echo date('d-m-Y',strtotime($value->saDate)); ?></td>
                            <td><?php echo 'Sales Amount'; ?></td>
                            <td><?php echo number_format(($value->tAmount-$value->disAmount), 2); $tsda += ($value->tAmount-$value->disAmount); ?></td> 
                            <td><?php echo number_format(($value->pAmount), 2); $tsca += $value->pAmount; ?></td> 
                            <td><?php echo $value->invoice; ?></td>
                          </tr>   
                          <?php } ?> 
                          <?php } else{ ?>
                          <?php $i = 0; ?>
                          <?php $tsda = 0; $tsca = 0; ?>
                          <?php } ?>

                          <?php if($voucher != null) { ?>

                          <?php
                          $j = $i;
                          $tcva = 0;
                          foreach ($voucher as $value) {
                          $j++;
                          ?>
                          <tr class="gradeX">
                            <td><?php echo $j; ?></td>
                            <td><?php echo date('d-m-Y',strtotime($value->vuDate)); ?></td>
                            <td><?php echo 'Received Voucher'; ?></td>
                            <td><?php echo '00'; ?></td> 
                            <td><?php echo number_format(($value->tAmount), 2); $tcva += $value->tAmount; ?></td> 
                            <td><?php echo $value->invoice; ?></td>
                          </tr>   
                          <?php } ?>
                          <?php } else{ ?>
                          <?php $j = $i or $j = 0; ?>
                          <?php $tcva = 0; ?>
                          <?php } ?> 

                          <?php if($return != null) { ?>

                          <?php
                          $k = $j;
                          $tcra = 0;
                          $tdra = 0;
                          foreach ($return as $value) {
                          $k++;
                          ?>
                          <tr class="gradeX">
                            <td><?php echo $k; ?></td>
                            <td><?php echo date('d-m-Y',strtotime($value->rDate)); ?></td>
                            <td><?php echo 'Product Returns'; ?></td>
                            <td><?php echo number_format(($value->pAmount), 2); $tdra += $value->pAmount; ?></td>
                            <td><?php echo number_format(($value->tAmount), 2); $tcra += $value->tAmount; ?></td>
                            <td><?php echo $value->rCode; ?></td>
                          </tr>   
                          <?php } ?>
                          <?php } else{ ?>
                          <?php $tcra = 0; $tdra = 0; ?>
                          <?php } ?> 
                        </tbody>
                        <tfoot>
                          <tr>
                            <th colspan="3" style="text-align: right;">Total Amount</th>
                            <th><?php echo number_format(($cust[0]['balance']+$tsda+$tdra), 2); ?></th>
                            <th><?php echo number_format(($tsca+$tcva+$tcra), 2); ?></th>
                            <th></th>
                          </tr>
                        </tfoot>

                        <?php } else if($rtype == 'supplier') { ?>
                        <tbody>
                          <?php if($supp[0]['balance'] > 0) { ?>
                          <?php $s = 1; ?>
                          <tr class="gradeX">
                            <td><?php echo $s; ?></td>
                            <td><?php echo date('d-m-Y',strtotime($supp[0]['regdate'])); ?></td>
                            <td><?php echo 'Opening Balance'; ?></td>
                            <td><?php echo number_format(($supp[0]['balance']), 2); ?></td> 
                            <td><?php echo '00'; ?></td> 
                            <td></td> 
                          </tr>   
                          <?php } else{ ?>
                          <?php $s = 0; ?>
                          <?php } ?>

                          <?php if ($purchase != null) { ?>
                          <?php
                          $i = $s;
                          $tcpa = 0;
                          $tdpa = 0;
                          foreach ($purchase as $result){
                          $i++;
                          ?>
                          <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo date('d-m-Y',strtotime($result->puDate)); ?></td>
                            <td><?php echo 'Purchase Amount'; ?></td>
                            <td><?php echo number_format($result->tAmount, 2); $tdpa += $result->tAmount; ?></td>
                            <td><?php echo number_format($result->pAmount, 2); $tcpa += $result->pAmount; ?></td>
                            <td><?php echo $result->challanNo; ?></td>
                          </tr>
                          <?php } ?> 
                          <?php } else{ ?>
                          <?php $i = 0; ?>
                          <?php $tcpa = 0; $tdpa = 0; ?>
                          <?php } ?>

                          <?php if ($voucher != null) { ?>

                          <?php
                          $j = $i;
                          $tcva = 0;
                          foreach ($voucher as $value) {
                          $j++;
                          ?>
                          <tr class="gradeX">
                            <td><?php echo $j; ?></td>
                            <td><?php echo date('d-m-Y',strtotime($value->vuDate)); ?></td>
                            <td><?php echo 'Payment Voucher'; ?></td>
                            <td><?php echo '00'; ?></td>
                            <td><?php echo number_format(($value->tAmount), 2); $tcva += $value->tAmount; ?></td>
                            <td><?php echo $value->invoice; ?></td>
                          </tr>   
                          <?php } ?>
                          <?php } else{ ?>
                          <?php $j = $i or $j = 0; ?>
                          <?php $tcva = 0; ?>
                          <?php } ?> 
                        </tbody>
                        <tfoot>
                          <tr>
                            <th colspan="3" style="text-align: right;">Total Amount</th>
                            <th><?php echo number_format(($supp[0]['balance']+$tdpa+$tcva), 2); ?></th>
                            <th><?php echo number_format(($tcpa+$tcva), 2); ?></th>
                            <th></th>
                          </tr>
                        </tfoot>
                        <?php } ?>
                      </table>
                    </div>

                    <div class="col-sm-12 col-md-12 col-12">
                      <div class="row" style="margin-top: 30px; text-align: center;">
                        <div class="col-md-4 col-sm-4 col-4">
                          <p>------------------------------</p>
                          <p>Prepared By</p>
                        </div>
                        <div class="col-md-4 col-sm-4 col-4">
                          <p>------------------------------</p>
                          <p>Verified By</p>
                        </div>
                        <div class="col-md-4 col-sm-4 col-4">
                          <p>------------------------------</p>
                          <p>Authorized By</p>
                        </div>
                      </div>
                    </div>
                    
                  </div><br>
                  <div class="form-group col-md-12 col-sm-12 col-12" style="text-align: center; margin-top: 20px">
                    <a href="javascript:void(0)" onclick="printDiv('print')" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<?php $this->load->view('footer/footer'); ?>

    <script type="text/javascript">
      $(document).ready(function(){
        $('#daily').click(function(){
          $('#dreports').removeAttr('class','d-none');
          $('#mreports').attr('class','d-none');
          $('#yreports').attr('class','d-none');
          $('#reports').attr('class','d-none');

          $('#sdate').attr('required','required');
          $('#edate').attr('required','required');
          $('#dcustomer').attr('required','required');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          $('#mcustomer').removeAttr('required','required');
          
          $('#ryear').removeAttr('required','required');
          $('#ycustomer').removeAttr('required','required');

          $('#customer').removeAttr('required','required');
          });

        $('#monthly').click(function(){
          $('#mreports').removeAttr('class','d-none');
          $('#dreports').attr('class','d-none');
          $('#yreports').attr('class','d-none');
          $('#reports').attr('class','d-none');

          $('#sdate').removeAttr('required','required');
          $('#edate').removeAttr('required','required');
          $('#dcustomer').removeAttr('required','required');
          
          $('#month').attr('required','required');
          $('#year').attr('required','required');
          $('#mcustomer').attr('required','required');
          
          $('#ryear').removeAttr('required','required');
          $('#ycustomer').removeAttr('required','required');

          $('#customer').removeAttr('required','required');
          });

        $('#yearly').click(function(){
          $('#yreports').removeAttr('class','d-none');
          $('#dreports').attr('class','d-none');
          $('#mreports').attr('class','d-none');
          $('#reports').attr('class','d-none');

          $('#sdate').removeAttr('required','required');
          $('#edate').removeAttr('required','required');
          $('#dcustomer').removeAttr('required','required');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          $('#mcustomer').removeAttr('required','required');
          
          $('#ryear').attr('required','required');
          $('#ycustomer').attr('required','required');

          $('#customer').removeAttr('required','required');
          });

        $('#ocust').click(function(){
          $('#yreports').attr('class','d-none');
          $('#dreports').attr('class','d-none');
          $('#mreports').attr('class','d-none');
          $('#reports').removeAttr('class','d-none');

          $('#sdate').removeAttr('required','required');
          $('#edate').removeAttr('required','required');
          $('#dcustomer').removeAttr('required','required');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          $('#mcustomer').removeAttr('required','required');
          
          $('#ryear').removeAttr('required','required');
          $('#ycustomer').removeAttr('required','required');

          $('#customer').attr('required','required');
          });
        });
    </script>

    <script type="text/javascript" >
      $(document).ready(function(){
        $('#allsupp').click(function(){
          $('#allsreports').removeAttr('class','d-none');
          $('#dsreports').attr('class','d-none');
          $('#msreports').attr('class','d-none');
          $('#ysreports').attr('class','d-none');

          $('#supplier').attr('required','required');

          $('#ssdate').removeAttr('required','required');
          $('#esdate').removeAttr('required','required');
          $('#dsupplier').removeAttr('required','required');
          
          $('#smonth').removeAttr('required','required');
          $('#syear').removeAttr('required','required');
          $('#msupplier').removeAttr('required','required');
          
          $('#rsyear').removeAttr('required','required');
          $('#ysupplier').removeAttr('required','required');
          });

        $('#sdaily').click(function(){
          $('#allsreports').attr('class','d-none');
          $('#dsreports').removeAttr('class','d-none');
          $('#msreports').attr('class','d-none');
          $('#ysreports').attr('class','d-none');

          $('#supplier').removeAttr('required','required');

          $('#ssdate').attr('required','required');
          $('#esdate').attr('required','required');
          $('#dsupplier').attr('required','required');
          
          $('#smonth').removeAttr('required','required');
          $('#syear').removeAttr('required','required');
          $('#msupplier').removeAttr('required','required');
          
          $('#rsyear').removeAttr('required','required');
          $('#ysupplier').removeAttr('required','required');
          });

        $('#smonthly').click(function(){
          $('#allsreports').attr('class','d-none');
          $('#dsreports').attr('class','d-none');
          $('#msreports').removeAttr('class','d-none');
          $('#ysreports').attr('class','d-none');

          $('#supplier').removeAttr('required','required');

          $('#ssdate').removeAttr('required','required');
          $('#esdate').removeAttr('required','required');
          $('#dsupplier').removeAttr('required','required');
          
          $('#smonth').attr('required','required');
          $('#syear').attr('required','required');
          $('#msupplier').attr('required','required');
          
          $('#rsyear').removeAttr('required','required');
          $('#ysupplier').removeAttr('required','required');
          });

        $('#syearly').click(function(){
          $('#allsreports').attr('class','d-none');
          $('#dsreports').attr('class','d-none');
          $('#msreports').attr('class','d-none');
          $('#ysreports').removeAttr('class','d-none');

          $('#supplier').removeAttr('required','required');

          $('#ssdate').removeAttr('required','required');
          $('#esdate').removeAttr('required','required');
          $('#dsupplier').removeAttr('required','required');
          
          $('#smonth').removeAttr('required','required');
          $('#syear').removeAttr('required','required');
          $('#msupplier').removeAttr('required','required');
          
          $('#rsyear').attr('required','required');
          $('#ysupplier').attr('required','required');
          });
        });
    </script>