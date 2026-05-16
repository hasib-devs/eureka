<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Customer Ledger</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Customer Ledger</li>
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
                <h3 class="card-title">Customer Ledger</h3>
              </div>

              <div class="card-body">
                <div class="col-sm-12 col-md-12 col-12">
                  <form action="<?php echo base_url() ?>custLedger" method="get">
                    <div class="col-md-12 col-sm-12 col-12">
                      <div class="form-group">
                        <b>
                          <input type="radio" name="reports" value="ocust" id="ocust" required >&nbsp;&nbsp;Customer All Ledger&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="reports" value="dailyReports" id="daily" required >&nbsp;&nbsp;Daily Ledger&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="reports" value="monthlyReports" id="monthly" required >&nbsp;&nbsp;Monthly Ledger&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="reports" value="yearlyReports" id="yearly" required >&nbsp;&nbsp;Yearly Ledger
                        </b>
                      </div>

                      <div class="d-none" id="dreports">
                        <div class="row">
                          <div class="form-group col-md-2 col-sm-2 col-12">
                            <label>Start Date *</label>
                            <input type="text" class="form-control datepicker" name="sdate" value="<?php echo date('m/d/Y') ?>" id="sdate" required="" >
                          </div>
                          <div class="form-group col-md-2 col-sm-2 col-12">
                            <label>End Date *</label>
                            <input type="text" class="form-control datepicker" name="edate" value="<?php echo date('m/d/Y') ?>" id="edate" required="" >
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-12">
                            <label>Select Customer *</label>
                            <select name="dcustomer" class="form-control select2" id="dcustomer" required="" style="width: 100%;" >
                              <option value="">Select One</option>
                              <?php foreach ($customer as $value) { ?>
                              <option value="<?php echo $value['custid']; ?>" ><?php echo $value['custName'].' ( '.$value['custMobile'].' )'; ?></option>
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
                          <div class="form-group col-md-2 col-sm-2 col-12">
                            <label>Select Month *</label>
                            <select class="form-control" name="month" id="month" required="" >
                              <option value="">Select One</option>
                              <option value="01">January</option>
                              <option value="02">February</option>
                              <option value="03">March</option>
                              <option value="04">April</option>
                              <option value="05">May</option>
                              <option value="06">June</option>
                              <option value="07">July</option>
                              <option value="08">August</option>
                              <option value="09">September</option>
                              <option value="10">October</option>
                              <option value="11">November</option>
                              <option value="12">December</option>
                            </select>
                          </div>
                          <div class="form-group col-md-2 col-sm-2 col-12">
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
                            <select name="mcustomer" class="form-control select2" id="mcustomer" required="" style="width: 100%" >
                              <option value="">Select One</option>
                              <?php foreach ($customer as $value) { ?>
                              <option value="<?php echo $value['custid']; ?>" ><?php echo $value['custName'].' ( '.$value['custMobile'].' )'; ?></option>
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
                          <div class="form-group col-md-2 col-sm-2 col-12">
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
                            <select name="ycustomer" class="form-control select2" id="ycustomer" required="" style="width: 100%" >
                              <option value="">Select One</option>
                              <?php foreach ($customer as $value) { ?>
                              <option value="<?php echo $value['custid']; ?>" ><?php echo $value['custName'].' ( '.$value['custMobile'].' )'; ?></option>
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
                            <select class="form-control select2" name="customer" id="customer" required="" style="width: 100%" >
                              <option value="">Select One</option>
                              <?php foreach ($customer as $value) { ?>
                              <option value="<?php echo $value['custid']; ?>" ><?php echo $value['custName'].' ( '.$value['custMobile'].' )'; ?></option>
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
                </div><hr>

                <div class="box-body">
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
                    <?php if(isset($_GET['search'])) { ?>
                    <div class="col-sm-12 col-md-12 col-12">
                      <div class="col-sm-12 col-md-12 col-12">
                        Name&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $cust[0]['custName']; ?>
                      </div>
                      <div class="col-sm-12 col-md-12 col-12">
                        Address&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $cust[0]['custAddress']; ?>
                      </div>
                      <div class="col-sm-12 col-md-12 col-12">
                        Contact No&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $cust[0]['custMobile']; ?>
                      </div>
                    </div>
                    <?php if ($report == 'dailyReports') { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Customer Ledger Reports in : <?php echo $sdate.' - '.$edate; ?></b></h3>
                    </div>
                    <?php } else if ($report == 'monthlyReports') { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Customer Ledger Reports in : <?php echo $name.' '.$year; ?></b></h3>
                    </div>
                    <?php } else if ($report == 'yearlyReports') { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Customer Ledger Reports in : <?php echo $year; ?></b></h3>
                    </div>
                    <?php } ?>
                  
                    <div class="col-sm-12 col-md-12 col-12">
                      <table id="exam22ple" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th class="d-none">#SN.</th>
                            <th>Date</th>
                            <th>Invoice No.</th>
                            <th>Particulars</th>
                            <th>Total</th>
                            <th>Discount</th>
                            <th>Payment</th>
                            <th>Balance</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $custba = 0;
                          if($sale != null) { ?>

                          <?php
                          $i = 0;
                          $tpsa = 0;
                          $tpa = 0;
                          $tda = 0;
                          $tdu = 0;
                          foreach ($sale as $value){
                          $i++;
                          
                          $tsalses = $this->db->select("SUM(dAmount) as total")
                                            ->FROM('sales')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tsbamt = $tsalses && $tsalses->total ? $tsalses->total : 0;
                          
                          $tspay = $this->db->select("SUM(sales_payment.pAmount) as total,sales.custid")
                                            ->FROM('sales_payment')
                                            ->join('sales','sales.said = sales_payment.said','left')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('sales_payment.regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tspamt = $tspay && $tspay->total ? $tspay->total : 0;
                          
                          $tcvoucher = $this->db->select("SUM(tAmount) as total")
                                            ->FROM('vaucher')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->where('status',1)
                                            ->get()
                                            ->row();
                          $tcvamt = $tcvoucher && $tcvoucher->total ? $tcvoucher->total : 0;
                          
                          $treturn = $this->db->select("SUM(pAmount-tAmount) as total")
                                            ->FROM('returns')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $trpamt = $treturn && $treturn->total ? $treturn->total : 0;
                          
                          $tservice = $this->db->select("SUM(dAmount) as total")
                                            ->FROM('services_sale')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tssamt = $tservice && $tservice->total ? $tservice->total : 0;
                          
                          $custba = ($tsbamt+$tssamt)-($tspamt+$tcvamt+$trpamt);
                          ?>
                          <tr class="gradeX">
                            <td class="d-none" ><?php echo date('Ymdhis',strtotime($value->regdate)); ?></td>
                            <td><?php echo date('d-m-Y',strtotime($value->saDate)); ?></td>
                            <td><?php echo $value->invoice; ?></td>
                            <td><?php echo 'Sales'; ?></td>
                            <td><?php echo number_format($value->tAmount, 2); $tpsa += $value->tAmount; ?></td> 
                            <td><?php echo number_format($value->disAmount, 2); $tda += $value->disAmount; ?></td>
                            <td><?php echo number_format($value->pAmount, 2); $tpa += $value->pAmount; $tdu += $value->dAmount; ?></td>
                            <td><?php echo number_format($custba, 2); ?></td>
                          </tr>   
                          <?php } ?> 
                          <?php } else{ ?>
                          <?php $i = 0; ?>
                          <?php $tpsa = 0; $tpa = 0; $tda = 0; $tdu = 0; ?>
                          <?php } ?>

                          <?php if ($voucher != null) { ?>

                          <?php
                          $j = $i;
                          $tcva = 0;
                          foreach ($voucher as $value) {
                          $j++;
                          $tsalses = $this->db->select("SUM(dAmount) as total")
                                            ->FROM('sales')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tsbamt = $tsalses && $tsalses->total ? $tsalses->total : 0;
                          
                          $tspay = $this->db->select("SUM(sales_payment.pAmount) as total,sales.custid")
                                            ->FROM('sales_payment')
                                            ->join('sales','sales.said = sales_payment.said','left')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('sales_payment.regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tspamt = $tspay && $tspay->total ? $tspay->total : 0;
                          
                          $tcvoucher = $this->db->select("SUM(tAmount) as total")
                                            ->FROM('vaucher')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->where('status',1)
                                            ->get()
                                            ->row();
                          $tcvamt = $tcvoucher && $tcvoucher->total ? $tcvoucher->total : 0;
                          
                          $treturn = $this->db->select("SUM(pAmount-tAmount) as total")
                                            ->FROM('returns')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $trpamt = $treturn && $treturn->total ? $treturn->total : 0;
                          
                          $tservice = $this->db->select("SUM(dAmount) as total")
                                            ->FROM('services_sale')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tssamt = $tservice && $tservice->total ? $tservice->total : 0;
                          
                          $custba = ($tsbamt+$tssamt)-($tspamt+$tcvamt+$trpamt);
                          ?>
                          <tr class="gradeX">
                            <td class="d-none" ><?php echo date('Ymdhis',strtotime($value->regdate)); ?></td>
                            <td><?php echo date('d-m-Y',strtotime($value->vuDate)); ?></td>
                            <td><?php echo $value->invoice; ?></td>
                            <td><?php echo $value->vauchertype; ?></td>
                            <td><?php echo '00'; ?></td> 
                            <td><?php echo '00'; ?></td> 
                            <td><?php echo number_format($value->tAmount, 2); $tcva += $value->tAmount; ?></td> 
                            <td><?php echo number_format($custba, 2); ?></td>
                          </tr>   
                          <?php } ?>
                          <?php } else{ ?>
                          <?php $j = $i or $j = 0; ?>
                          <?php $tcva = 0; ?>
                          <?php } ?> 

                          <?php if ($return != null) { ?>

                          <?php
                          $k = $j;
                          $tra = 0;
                          $trda = 0;
                          $trpa = 0;
                          foreach ($return as $value) {
                          $k++;
                          
                          $tsalses = $this->db->select("SUM(dAmount) as total")
                                            ->FROM('sales')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tsbamt = $tsalses && $tsalses->total ? $tsalses->total : 0;
                          
                          $tspay = $this->db->select("SUM(sales_payment.pAmount) as total,sales.custid")
                                            ->FROM('sales_payment')
                                            ->join('sales','sales.said = sales_payment.said','left')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('sales_payment.regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tspamt = $tspay && $tspay->total ? $tspay->total : 0;
                          
                          $tcvoucher = $this->db->select("SUM(tAmount) as total")
                                            ->FROM('vaucher')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->where('status',1)
                                            ->get()
                                            ->row();
                          $tcvamt = $tcvoucher && $tcvoucher->total ? $tcvoucher->total : 0;
                          
                          $treturn = $this->db->select("SUM(pAmount-tAmount) as total")
                                            ->FROM('returns')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $trpamt = $treturn && $treturn->total ? $treturn->total : 0;
                          
                          $tservice = $this->db->select("SUM(dAmount) as total")
                                            ->FROM('services_sale')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tssamt = $tservice && $tservice->total ? $tservice->total : 0;
                          
                          $custba = ($tsbamt+$tssamt)-($tspamt+$tcvamt+$trpamt);
                          ?>
                          <tr class="gradeX">
                            <td class="d-none" ><?php echo date('Ymdhis',strtotime($value->regdate)); ?></td>
                            <td><?php echo date('d-m-Y',strtotime($value->rDate)); ?></td>
                            <td><?php echo $value->rCode; ?></td>
                            <td><?php echo 'Product Returns'; ?></td>
                            <td><?php echo number_format($value->tAmount, 2); $tra += $value->tAmount; ?></td> 
                            <td><?php echo number_format($value->sAmount, 2); $trda += $value->sAmount; ?></td> 
                            <td><?php echo number_format($value->pAmount, 2); $trpa += $value->pAmount; ?></td> 
                            <td><?php echo number_format($custba, 2); ?></td>
                          </tr>   
                          <?php } ?>
                          <?php } else{ ?>
                          <?php $k = $j or $k = $i or $k = 0; ?>
                          <?php $tra = 0; $trda = 0; $trpa = 0; ?>
                          <?php } ?>
                          
                          <?php if ($spay != null) { ?>

                          <?php
                          $l = $k;
                          $tdspa = 0;
                          foreach ($spay as $value) {
                          $l++;
                          
                          $tsalses = $this->db->select("SUM(dAmount) as total")
                                            ->FROM('sales')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tsbamt = $tsalses && $tsalses->total ? $tsalses->total : 0;
                          
                          $tspay = $this->db->select("SUM(sales_payment.pAmount) as total,sales.custid")
                                            ->FROM('sales_payment')
                                            ->join('sales','sales.said = sales_payment.said','left')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('sales_payment.regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tspamt = $tspay && $tspay->total ? $tspay->total : 0;
                          
                          $tcvoucher = $this->db->select("SUM(tAmount) as total")
                                            ->FROM('vaucher')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->where('status',1)
                                            ->get()
                                            ->row();
                          $tcvamt = $tcvoucher && $tcvoucher->total ? $tcvoucher->total : 0;
                          
                          $treturn = $this->db->select("SUM(pAmount-tAmount) as total")
                                            ->FROM('returns')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $trpamt = $treturn && $treturn->total ? $treturn->total : 0;
                          
                          $tservice = $this->db->select("SUM(dAmount) as total")
                                            ->FROM('services_sale')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tssamt = $tservice && $tservice->total ? $tservice->total : 0;
                          
                          $custba = ($tsbamt+$tssamt)-($tspamt+$tcvamt+$trpamt);
                          ?>
                          <tr class="gradeX">
                            <td class="d-none" ><?php echo date('Ymdhis',strtotime($value->regdate)); ?></td>
                            <td><?php echo date('d-m-Y',strtotime($value->regdate)); ?></td>
                            <td><?php echo $value->spid; ?></td>
                            <td><?php echo 'Sales Payment'; ?></td>
                            <td><?php echo '00'; ?></td>
                            <td><?php echo '00'; ?></td>
                            <td><?php echo number_format($value->pAmount, 2); $tdspa += $value->pAmount; ?></td> 
                            <td><?php echo number_format($custba, 2); ?></td>
                          </tr>   
                          <?php } ?>
                          <?php } else{ ?>
                          <?php $l = $k or $l = $j or $l = $i or $l = 0; ?>
                          <?php $tdspa = 0; ?>
                          <?php } ?>
                          
                          <?php if ($service != null) { ?>

                          <?php
                          $m = $l;
                          $tssa = 0;
                          $tsdsa = 0;
                          $tspsa = 0;
                          $tdsa = 0;
                          foreach ($service as $value) {
                          $m++;
                          
                          $tsalses = $this->db->select("SUM(dAmount) as total")
                                            ->FROM('sales')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tsbamt = $tsalses && $tsalses->total ? $tsalses->total : 0;
                          
                          $tspay = $this->db->select("SUM(sales_payment.pAmount) as total,sales.custid")
                                            ->FROM('sales_payment')
                                            ->join('sales','sales.said = sales_payment.said','left')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('sales_payment.regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tspamt = $tspay && $tspay->total ? $tspay->total : 0;
                          
                          $tcvoucher = $this->db->select("SUM(tAmount) as total")
                                            ->FROM('vaucher')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->where('status',1)
                                            ->get()
                                            ->row();
                          $tcvamt = $tcvoucher && $tcvoucher->total ? $tcvoucher->total : 0;
                          
                          $treturn = $this->db->select("SUM(pAmount-tAmount) as total")
                                            ->FROM('returns')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $trpamt = $treturn && $treturn->total ? $treturn->total : 0;
                          
                          $tservice = $this->db->select("SUM(dAmount) as total")
                                            ->FROM('services_sale')
                                            ->WHERE('custid',$cust[0]['custid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tssamt = $tservice && $tservice->total ? $tservice->total : 0;
                          
                          $custba = ($tsbamt+$tssamt)-($tspamt+$tcvamt+$trpamt);
                          ?>
                          <tr class="gradeX">
                            <td class="d-none" ><?php echo date('Ymdhis',strtotime($value->regdate)); ?></td>
                            <td><?php echo date('d-m-Y',strtotime($value->ssDate)); ?></td>
                            <td><?php echo $value->ssCode; ?></td>
                            <td><?php echo 'Service Sales'; ?></td>
                            <td><?php echo number_format($value->tAmount, 2); $tssa += $value->tAmount; ?></td> 
                            <td><?php echo number_format($value->disAmount, 2); $tsdsa += $value->disAmount; ?></td>
                            <td><?php echo number_format($value->pAmount, 2); $tspsa += $value->pAmount; $tdsa += $value->dAmount; ?></td> 
                            <td><?php echo number_format($custba, 2); ?></td>
                          </tr>   
                          <?php } ?>
                          <?php } else{ ?>
                          <?php $tssa = 0; $tsdsa = 0; $tspsa = 0; $tdsa = 0; ?>
                          <?php } ?> 
                        </tbody>
                        <tfoot>
                          <tr>
                            <td class="d-none" ></td>
                            <th colspan="3" style="text-align: right;">Total</th>
                            <th><?php echo number_format(($tpsa+$tssa+$trpa), 2); ?></th>
                            <th><?php echo number_format(($tda+$tsdsa), 2); ?></th>
                            <th><?php echo number_format(($tpa+$tcva+$tdspa+$tspsa+$tra), 2); ?></th>
                            <th><?php echo number_format(($tdu+$tdsa+$trpa)-($tcva+$tdspa+$tra), 2); ?></th>
                          </tr>
                          <tr>
                            <td class="d-none" ></td>
                            <th colspan="3" style="text-align: right;">Total Sales</th>
                            <th><?php echo number_format(($tpsa+$tssa), 2); ?></th>
                            <th colspan="3"  ></th>
                          </tr>
                          <tr>
                            <td class="d-none" ></td>
                            <th colspan="3" style="text-align: right;">Total Sales Paid</th>
                            <th><?php echo number_format(($tpa+$tspsa), 2); ?></th>
                            <th colspan="3"  ></th>
                          </tr>
                          <tr>
                            <td class="d-none" ></td>
                            <th colspan="3" style="text-align: right;">Total Discount</th>
                            <th><?php echo number_format(($tda+$tsdsa), 2); ?></th>
                            <th colspan="3"  ></th>
                          </tr>
                          <tr>
                            <td class="d-none" ></td>
                            <th colspan="3" style="text-align: right;">Total Payment</th>
                            <th><?php echo number_format(($tcva+$tdspa), 2); ?></th>
                            <th colspan="3"  ></th>
                          </tr>
                          <tr>
                            <td class="d-none" ></td>
                            <th colspan="3" style="text-align: right;">Total Return</th>
                            <th><?php echo number_format(($tra), 2); ?></th>
                            <th colspan="3"  ></th>
                          </tr>
                          <tr>
                            <td class="d-none" ></td>
                            <th colspan="3" style="text-align: right;">Total Return Paid</th>
                            <th><?php echo number_format(($trpa), 2); ?></th>
                            <th colspan="3"  ></th>
                          </tr>
                          <tr>
                            <td class="d-none" ></td>
                            <th colspan="3" style="text-align: right;">Due Amount</th>
                            <th><?php echo number_format(($tdu+$tdsa+$trpa)-($tcva+$tdspa+$tra), 2); ?></th>
                            <th colspan="3"  ></th>
                          </tr>
                        </tfoot>
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
                    <?php } ?>
                  </div><br>
                  <div class="form-group col-md-12 col-sm-12 col-12" style="text-align: center; margin-top: 20px">
                    <a href="javascript:void(0)" onclick="printDiv('print')" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
                  </div>
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
    
    <script>
      $(document).ready(function() {
        var table = $('#exam22ple').DataTable({
        paging: false,
        order: [[0, 'asc']],
        });
        });
    </script>
