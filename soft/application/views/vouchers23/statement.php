
  <div class="content-wrapper" style="min-height: 1126px;" >
    <section class="content-header">
      <h1>Statement Report</h1>
    </section>

    <div class="box">
      <div class="box-body">
        <div class="row" style="margin-bottom: 20px;">
          <div class="col-sm-12 col-md-12 col-xs-12">
            <form action="<?php echo base_url() ?>statementReport" method="get">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                  <b>
                    <input type="radio" name="reports" value="dailyReports" id="daily" required >&nbsp;&nbsp;Daily Reports&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="reports" value="monthlyReports" id="monthly" required >&nbsp;&nbsp;Monthly Reports&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="reports" value="yearlyReports" id="yearly" required >&nbsp;&nbsp;Yearly Reports
                  </b>
                </div>

                <div class="hidden" id="dreports">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group col-md-3 col-sm-3 col-xs-12">
                      <label>Select Date *</label>
                      <input type="text" class="form-control" name="sdate" value="<?php echo date('d-m-Y') ?>" id="date" required="" >
                    </div>
                    <div class="form-group col-md-1 col-sm-1 col-xs-12">
                      <button type="submit" name="search" class="btn btn-info" style="margin-top: 25px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>
                    </div>
                  </div>
                </div>

                <div class="hidden" id="mreports">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group col-md-3 col-sm-3 col-xs-12">
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
                    <div class="form-group col-md-3 col-sm-3 col-xs-12">
                      <label>Select Year *</label>
                      <?php $d = date("Y"); ?>
                      <select class="form-control" name="year" id="year" required="" >
                        <option value="">Select One</option>
                        <?php for ($x = 2020; $x <= $d; $x++) { ?>
                        <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group col-md-1 col-sm-1 col-xs-12">
                      <button type="submit" name="search" class="btn btn-info" style="margin-top: 25px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>
                    </div>
                  </div>
                </div>

                <div class="hidden" id="yreports">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group col-md-3 col-sm-3 col-xs-12">
                      <label>Select Year *</label>
                      <?php $d = date("Y"); ?>
                      <select class="form-control" name="ryear" id="ryear" required="" >
                        <option value="">Select One</option>
                        <?php for ($x = 2020; $x <= $d; $x++) { ?>
                        <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group col-md-1 col-sm-1 col-xs-12">
                      <button type="submit" name="search" class="btn btn-info" style="margin-top: 25px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>
                    </div>
                  </div>
                </div>

              </div>
            </form>
          </div>
        </div>

          <div id="print">
            <div id="header" style="display: none">
              <?php $this->load->view('elements/report_header');?>
            </div>

            <?php $categogy = $this->db->select('*')->from('category')->where('status','Active')->get()->result(); ?>

            <div class="col-md-12 col-sm-12 col-xs-12">
            <?php if(isset($_GET['search'])) { ?>
              <?php if ($report == 'dailyReports') { ?>
                <div class="box-header" style="text-align: center;">
                  <h3 class="box-title"><b>Daily Statement Reports in : <?php echo date('d-F-Y',strtotime($sdate)); ?></b></h3>
                </div>
                
                <div id="table-content">
                  <table class="table table-bordered table-striped" style="scrollX: true;">
                    <thead>
                      <tr>
                        <th style="width: 5%;">#SN.</th>
                        <th>Patient</th>
                        <th>Reference</th>
                        <th>Inv. No.</th>
                        <?php foreach ($categogy as $cvalue){ ?>
                        <th><?php echo $cvalue->catName; ?></th>
                        <?php } ?>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Discount</th>
                        <th>Due</th>
                        <th>Expense</th>
                        <th style="width: 8%;">Net Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                      <?php 
                      $sales = $this->db->select('sales.*,customers.custName,suppliers.supName')
                                        ->from('sales')
                                        ->join('customers','customers.customerID = sales.custid','left')
                                        ->join('suppliers','suppliers.supplierID = sales.reference','left')
                                        ->where('DATE(saleDate)',$sdate)
                                        ->get()
                                        ->result();
                      ?>
                      <?php 
                      $m = 0;
                      $tpa = 0;
                      $tppa = 0;
                      $tpda = 0;
                      $tpay = 0;
                      $tpdis = 0;
                      $texp = 0;
                      $tna = 0;
                      foreach($sales as $value){ 
                      $m++;
                      ?>
                      <tr>
                        <td style="border: 2px solid #000;"><?php echo $m; ?></td>
                        <td style="border: 2px solid #000;"><?php echo $value->custName; ?></td>
                        <td style="border: 2px solid #000;"><?php echo $value->supName; ?></td>
                        <td style="border: 2px solid #000;"><?php echo $value->saleID; ?></td>
                        <?php
                        foreach ($categogy as $cvalue){
                            
                        $tca = $this->db->select('SUM(sale_product.sprice) as tsamount,products.cat_id,sales.saleDate')
                                        ->from('sale_product')
                                        ->join('products','products.productID = sale_product.product','left')
                                        ->join('sales','sales.saleID = sale_product.saleID','left')
                                        ->where('cat_id',$cvalue->cat_id)
                                        ->where('custid',$value->custid)
                                        ->where('DATE(saleDate)',$sdate)
                                        ->get()
                                        ->row();
                        ?>
                        <td style="border: 2px solid #000;">
                          <?php if($tca && $tca->tsamount > 0){ ?>
                          <?php echo number_format($tca->tsamount, 2); ?>
                          <?php } else{ ?>
                          <?php echo "-"; ?>
                          <?php } ?>
                        </td>
                        <?php } ?>
                        <?php
                        $tcya = $this->db->select('SUM(sales.tprice) as ta,SUM(sales.paidAmount) as tp,SUM(sales.dueAmount) as td,SUM(sales.disAmount) as tdis,SUM(patient_payment.amount) as pppaid')
                                        ->from('sales')
                                        ->join('patient_payment','patient_payment.saleid = sales.saleID','left')
                                        ->where('custid',$value->custid)
                                        ->where('DATE(saleDate)',$sdate)
                                        ->get()
                                        ->row();
                        ?>
                        <td style="border: 2px solid #000;">
                          <?php if($tcya){ ?>
                          <?php $tcyata = $tcya->ta;  ?>
                          <?php } else{ ?>
                          <?php $tcyata = 0; ?>
                          <?php } ?>
                          
                          <?php if($tcyata > 0){ ?>
                          <?php echo number_format($tcyata); $tpa += $tcyata; ?>
                          <?php } else{ ?>
                          <?php echo "-"; ?>
                          <?php } ?>
                        </td>
                        <td style="border: 2px solid #000;">
                          <?php if($tcya && ($tcya->tp-$tcya->pppaid) > 0){ ?>
                          <?php echo number_format($tcya->tp-$tcya->pppaid); $tppa += $tcya->tp-$tcya->pppaid; ?>
                          <?php } else{ ?>
                          <?php echo "-"; ?>
                          <?php } ?>
                        </td>
                        <td style="border: 2px solid #000;">
                          <?php if($tcya && $tcya->tdis > 0){ ?>
                          <?php echo number_format($tcya->tdis); $tpda += $tcya->tdis; ?>
                          <?php } else{ ?>
                          <?php echo "-"; ?>
                          <?php } ?>
                        </td>
                        <?php
                        $tcpayment = $this->db->select('SUM(totalamount) as ta')
                                        ->from('vaucher')
                                        ->where('vauchertype','Credit Voucher')
                                        ->where('customerID',$value->custid)
                                        ->where('voucherdate',$sdate)
                                        ->get()
                                        ->row();
                        ?>
                        <td style="border: 2px solid #000;">
                          <?php if($tcya && $tcya->td > 0){ ?>
                          <?php echo number_format($tcya->td); $tpdis += $tcya->td; ?>
                          <?php } else{ ?>
                          <?php echo "-"; ?>
                          <?php } ?>
                        </td>
                        <?php
                        $tdpayment = $this->db->select('SUM(totalamount) as total')
                                        ->from('vaucher')
                                        ->where('vauchertype','Debit Voucher')
                                        ->where('voucherdate',$sdate)
                                        ->get()
                                        ->row();
                        ?>
                        <?php if($m == 1){ ?>
                        <td style="border: 2px solid #000;"><?php echo number_format($tdpayment->total); ?></td>
                        <td style="border: 2px solid #000;"><?php echo number_format(($tcya->tp-$tcya->pppaid)); $tna += (($tcya->tp-$tcya->pppaid)); ?></td>
                        <?php } else { ?>
                        <td style="border: 2px solid #000;"></td>
                        <td style="border: 2px solid #000;"><?php echo number_format(($tcya->tp)); $tna += (($tcya->tp-$tcya->pppaid)); ?></td>
                        <?php } ?>
                      </tr>  
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <th colspan="4" style="text-align: right;">Total Amount</th>
                      <?php
                      foreach ($categogy as $cvalue){
                          
                      $tcya = $this->db->select('SUM(sale_product.sprice) as tsAmount,products.cat_id,sales.saleDate')
                                      ->from('sale_product')
                                      ->join('products','products.productID = sale_product.product','left')
                                      ->join('sales','sales.saleID = sale_product.saleID','left')
                                      ->where('cat_id',$cvalue->cat_id)
                                      ->where('DATE(saleDate)',$sdate)
                                      ->get()
                                      ->row();
                      ?>
                      <th>
                        <?php if($tcya && $tcya->tsamount > 0){ ?>
                        <?php echo number_format($tcya->tsAmount); ?>
                        <?php } else{ ?>
                        <?php echo "-"; ?>
                        <?php } ?>
                      </th>
                      <?php } ?>
                      <th><?php echo number_format($tpa, 2); ?></th>
                      <th><?php echo number_format($tppa, 2); ?></th>
                      <th><?php echo number_format($tpda, 2); ?></th>
                      <th><?php echo number_format(($tpdis), 2); ?></th>
                        <?php
                          $texpa = $this->db->select('SUM(totalamount) as txp')
                                        ->from('vaucher')
                                        ->where('vauchertype','Debit Voucher')
                                        ->where('voucherdate',$sdate)
                                        ->get()
                                        ->row();
                          ?>
                      <th><?php echo number_format(($texpa->txp), 2); ?></th>
                      <th><?php echo number_format($tppa-$texpa->txp, 2); ?></th>
                    </tfoot>
                  </table>
                </div>
              <?php } else if ($report == 'monthlyReports') { ?>
                <div class="box-header" style="text-align: center;">
                  <h3 class="box-title"><b>Monthly Statement Reports in : <?php echo $name.' '.$year; ?></b></h3>
                </div>
                
                <div id="table-content">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#SN.</th>
                        <th>Month</th>
                        <?php
                        $categogy = $this->db->select('*')->from('category')->where('status','Active')->get()->result();
                        ?>
                        <?php foreach ($categogy as $cvalue){ ?>
                        <th><?php echo $cvalue->catName; ?></th>
                        <?php } ?>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Discount</th>
                        <!--<th>Payment</th>-->
                        <!--<th>Due</th>-->
                        <th>Expense</th>
                        <!--<th>Amount</th>-->
                        <th>Net Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $cyear = date("Y");
                        $cmonth = date("m");
                        $cdays = date("d");
                        if($month == $cmonth && $year == $cyear)
                          {
                          $voucher = $cdays;
                          }
                        else
                          {
                          $voucher = $days;
                          }
                      ?>
                      <?php 
                      $fdate = $year.'-'.$month .'-01';
                      $m = 0;
                      $tpa = 0;
                      $tppa = 0;
                      $tpda = 0;
                      $tpay = 0;
                      $tpdis = 0;
                      $texp = 0;
                      $tna = 0;
                      for($i = 1; $i <= $voucher; $i++){ 
                      
                      $day = date("Y-m-d", strtotime("$fdate +$m day"));
                      $m++;
                      ?>
                      <tr>
                        <td style="border: 2px solid #000;"><?php echo $m; ?></td>
                        <td style="border: 2px solid #000;"><?php echo date('d-m-Y',strtotime($day)); ?></td>
                        <?php
                        foreach ($categogy as $cvalue){
                        
                        $tca = $this->db->select('SUM(sale_product.sprice) as tsamount,products.cat_id,sales.saleDate')
                                        ->from('sale_product')
                                        ->join('products','products.productID = sale_product.product','left')
                                        ->join('sales','sales.saleID = sale_product.saleID','left')
                                        ->where('cat_id',$cvalue->cat_id)
                                        //->where('custid',$value->custid)
                                        ->where('DATE(saleDate)',$day)
                                        ->get()
                                        ->row();
                                        
                        // $tca = $this->db->select('SUM(sale_product.sprice) as tsamount,products.cat_id')
                        //                 ->from('sale_product')
                        //                 ->join('products','products.productID = sale_product.product','left')
                        //                 ->where('cat_id',$cvalue->cat_id)
                        //                 ->where('DATE(sale_product.regdate)',$day)
                        //                 ->get()
                        //                 ->row();
                        ?>
                        <td style="border: 2px solid #000;">
                          <?php if($tca){ ?>
                          <?php $tcata =  $tca->tsamount; ?>
                          <?php } else{ ?>
                          <?php $tcata = 0; ?>
                          <?php } ?>
                          
                          <?php if($tcata > 0){ ?>
                          <?php echo number_format($tcata); ?>
                          <?php } else{ ?>
                          <?php echo "-"; ?>
                          <?php } ?>
                        </td>
                        <?php } ?>
                        <?php
                        $tcya = $this->db->select('SUM(sales.tprice) as ta,SUM(sales.paidAmount) as tp,SUM(sales.dueAmount) as td,SUM(sales.disAmount) as tdis,SUM(patient_payment.amount) as pppaid')
                                        ->from('sales')
                                        ->join('patient_payment','patient_payment.saleid = sales.saleID','left')
                                        ->where('DATE(saleDate)',$day)
                                        ->get()
                                        ->row();
                        ?>
                        <td style="border: 2px solid #000;">
                          <?php if($tcya && $tcya->ta > 0){ ?>
                          <?php echo number_format($tcya->ta); $tpa += $tcya->ta; ?>
                          <?php } else{ ?>
                          <?php echo "-"; ?>
                          <?php } ?>
                        </td>
                        <td style="border: 2px solid #000;">
                          <?php if($tcya && ($tcya->tp-$tcya->pppaid) > 0){ ?>
                          <?php echo number_format($tcya->tp-$tcya->pppaid); $tppa += $tcya->tp-$tcya->pppaid; ?>
                          <?php } else{ ?>
                          <?php echo "-"; ?>
                          <?php } ?>
                        </td>
                        <td style="border: 2px solid #000;">
                          <?php if($tcya && $tcya->tdis > 0){ ?>
                          <?php echo number_format($tcya->tdis); $tpda += $tcya->tdis; ?>
                          <?php } else{ ?>
                          <?php echo "-"; ?>
                          <?php } ?>
                        </td>
                        <?php
                        $tcpayment = $this->db->select('SUM(totalamount) as ta')
                                        ->from('vaucher')
                                        ->where('vauchertype','Credit Voucher')
                                        ->where('voucherdate',$day)
                                        ->get()
                                        ->row();
                        ?>
                        <!--<td>-->
                        <!--  <?php if($tcpayment){ ?>-->
                        <!--  <?php echo number_format($tcpayment->ta); $tpay += $tcpayment->ta; ?>-->
                        <!--  <?php } else{ ?>-->
                        <!--  <?php echo "00"; ?>-->
                        <!--  <?php } ?>-->
                        <!--</td>-->
                        <!--<td>-->
                        <!--  <?php if($tcya){ ?>-->
                        <!--  <?php echo number_format($tcya->td-$tcpayment->ta); $tpdis += $tcya->td-$tcpayment->ta; ?>-->
                        <!--  <?php } else{ ?>-->
                        <!--  <?php echo "00"; ?>-->
                        <!--  <?php } ?>-->
                        <!--</td>-->
                        <?php
                        $tdpayment = $this->db->select('SUM(totalamount) as ta')
                                        ->from('vaucher')
                                        ->where('vauchertype','Debit Voucher')
                                        ->where('voucherdate',$day)
                                        ->get()
                                        ->row();
                        ?>
                        <!--<td style="border: 2px solid #000;"><?php echo "Expense"; ?></td>-->
                        <td style="border: 2px solid #000;">
                          <?php if($tdpayment && $tdpayment->ta > 0){ ?>
                          <?php echo number_format($tdpayment->ta); $texp += $tdpayment->ta; ?>
                          <?php } else{ ?>
                          <?php echo "-"; ?>
                          <?php } ?>
                        </td>
                        <td style="border: 2px solid #000;">
                          <?php echo number_format(($tcya->tp-$tcya->pppaid)-$tdpayment->ta); $tna += ($tcya->tp-$tcya->pppaid)-$tdpayment->ta; ?>
                        </td>
                      </tr>  
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <th colspan="2" style="text-align: right;">Total Amount</th>
                        <?php
                        foreach ($categogy as $cvalue){
                            
                        $tcya = $this->db->select('SUM(sale_product.sprice) as tsamount,products.cat_id')
                                        ->from('sale_product')
                                        ->join('products','products.productID = sale_product.product','left')
                                        ->where('cat_id',$cvalue->cat_id)
                                        ->where('MONTH(sale_product.regdate)',$month)
                                        ->where('YEAR(sale_product.regdate)',$year)
                                        ->get()
                                        ->row();
                        ?>
                        <th>
                          <?php if($tcya){ ?>
                          <?php echo number_format($tcya->tsamount); ?>
                          <?php } else{ ?>
                          <?php echo "-"; ?>
                          <?php } ?>
                        </th>
                        <?php } ?>
                      <th><?php echo number_format($tpa, 2); ?></th>
                      <th><?php echo number_format($tppa, 2); ?></th>
                      <th><?php echo number_format($tpda, 2); ?></th>
                      <!--<th><?php echo number_format($tpay, 2); ?></th>-->
                      <!--<th><?php echo number_format(($tpdis-$tpay), 2); ?></th>-->
                      <!--<th><?php echo "Total Expense"; ?></th>-->
                      <th><?php echo number_format(($texp), 2); ?></th>
                      <th><?php echo number_format(($tna), 2); ?></th>
                    </tfoot>
                  </table>
                </div>
              <?php } else if ($report == 'yearlyReports') { ?>
                <div class="box-header" style="text-align: center;">
                  <h3 class="box-title"><b>Yearly Statement Reports in : <?php echo $year; ?></b></h3>
                </div>
                
                <div id="table-content">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#SN.</th>
                        <th>Month</th>
                        <?php
                        $categogy = $this->db->select('*')->from('category')->where('status','Active')->get()->result();
                        ?>
                        <?php foreach ($categogy as $cvalue){ ?>
                        <th><?php echo $cvalue->catName; ?></th>
                        <?php } ?>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Discount</th>
                        <!--<th>Payment</th>-->
                        <!--<th>Due</th>-->
                        <th>Expense</th>
                        <!--<th>Amount</th>-->
                        <th>Net Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $cyear = date("Y");
                        $cmonth = date("m");
                        if($year == $cyear)
                          {
                          $voucher = $cmonth;
                          }
                        else
                          {
                          $voucher = $days;
                          }
                      ?>
                      <?php 
                      $m = 0;
                      $tpa = 0;
                      $tppa = 0;
                      $tpda = 0;
                      $tpay = 0;
                      $tpdis = 0;
                      $texp = 0;
                      $tna = 0;
                      for($i = 1; $i <= $voucher; $i++){ 
                      $m++;
                      ?>
                      <tr>
                        <td style="border: 2px solid #000;"><?php echo $m; ?></td>
                        <td style="border: 2px solid #000;">
                          <?php
                          if($m == 01)
                            {
                            $name = 'January';
                            }
                          elseif ($m == 02)
                            {
                            $name = 'February';
                            }
                          elseif ($m == 03)
                            {
                            $name = 'March';
                            }
                          elseif ($m == 04)
                            {
                            $name = 'April';
                            }
                          elseif ($m == 05)
                            {
                            $name = 'May';
                            }
                          elseif ($m == 06)
                            {
                            $name = 'June';
                            }
                          elseif ($m == 07)
                            {
                            $name = 'July';
                            }
                          elseif ($m == 8)
                            {
                            $name = 'August';
                            }
                          elseif ($m == 9)
                            {
                            $name = 'September';
                            }
                          elseif ($m == 10)
                            {
                            $name = 'October';
                            }
                          elseif ($m == 11)
                            {
                            $name = 'November';
                            }
                          else
                            {
                            $name = 'December';
                            }
                          ?>
                          <?php echo $name; ?>
                        </td>
                        <?php
                        foreach ($categogy as $cvalue){
                            
                        $tca = $this->db->select('SUM(sale_product.sprice) as tsamount,products.cat_id')
                                        ->from('sale_product')
                                        ->join('products','products.productID = sale_product.product','left')
                                        ->where('cat_id',$cvalue->cat_id)
                                        ->where('MONTH(sale_product.regdate)',$m)
                                        ->where('YEAR(sale_product.regdate)',$year)
                                        ->get()
                                        ->row();
                        ?>
                        <td style="border: 2px solid #000;">
                          <?php if($tca){ ?>
                          <?php $tcata = $tca->tsamount; ?>
                          <?php } else{ ?>
                          <?php $tcata =  0; ?>
                          <?php } ?>
                          
                          <?php if($tcata > 0){ ?>
                          <?php echo number_format($tcata); ?>
                          <?php } else{ ?>
                          <?php echo "-"; ?>
                          <?php } ?>
                        </td>
                        <?php } ?>
                        <?php
                        $tcya = $this->db->select('SUM(sales.tprice) as ta,SUM(sales.paidAmount) as tp,SUM(sales.dueAmount) as td,SUM(sales.disAmount) as tdis,SUM(patient_payment.amount) as pppaid')
                                        ->from('sales')
                                        ->join('patient_payment','patient_payment.saleid = sales.saleID','left')
                                        ->where('MONTH(saleDate)',$m)
                                        ->where('YEAR(saleDate)',$year)
                                        ->get()
                                        ->row();
                        ?>
                        <td style="border: 2px solid #000;">
                          <?php if($tcya && $tcya->ta > 0){ ?>
                          <?php echo number_format($tcya->ta); $tpa += $tcya->ta; ?>
                          <?php } else{ ?>
                          <?php echo "-"; ?>
                          <?php } ?>
                        </td>
                        <td style="border: 2px solid #000;">
                          <?php if($tcya && ($tcya->tp-$tcya->pppaid) > 0){ ?>
                          <?php echo number_format($tcya->tp-$tcya->pppaid); $tppa += $tcya->tp-$tcya->pppaid; ?>
                          <?php } else{ ?>
                          <?php echo "-"; ?>
                          <?php } ?>
                        </td>
                        <td style="border: 2px solid #000;">
                          <?php if($tcya && $tcya->tdis > 0){ ?>
                          <?php echo number_format($tcya->tdis); $tpda += $tcya->tdis; ?>
                          <?php } else{ ?>
                          <?php echo "-"; ?>
                          <?php } ?>
                        </td>
                        <?php
                        $tcpayment = $this->db->select('SUM(totalamount) as ta')
                                        ->from('vaucher')
                                        ->where('vauchertype','Credit Voucher')
                                        ->where('MONTH(voucherdate)',$m)
                                        ->where('YEAR(voucherdate)',$year)
                                        ->get()
                                        ->row();
                        ?>
                        <!--<td>-->
                        <!--  <?php if($tcpayment){ ?>-->
                        <!--  <?php echo number_format($tcpayment->ta); $tpay += $tcpayment->ta; ?>-->
                        <!--  <?php } else{ ?>-->
                        <!--  <?php echo "00"; ?>-->
                        <!--  <?php } ?>-->
                        <!--</td>-->
                        <!--<td>-->
                        <!--  <?php if($tcya){ ?>-->
                        <!--  <?php echo number_format($tcya->td-$tcpayment->ta); $tpdis += $tcya->td-$tcpayment->ta; ?>-->
                        <!--  <?php } else{ ?>-->
                        <!--  <?php echo "00"; ?>-->
                        <!--  <?php } ?>-->
                        <!--</td>-->
                        <?php
                        $tdpayment = $this->db->select('SUM(totalamount) as ta')
                                        ->from('vaucher')
                                        ->where('vauchertype','Debit Voucher')
                                        ->where('MONTH(voucherdate)',$m)
                                        ->where('YEAR(voucherdate)',$year)
                                        ->get()
                                        ->row();
                        ?>
                        <!--<td style="border: 2px solid #000;"><?php echo "Expense"; ?></td>-->
                        <td style="border: 2px solid #000;">
                          <?php if($tdpayment && $tdpayment->ta > 0){ ?>
                          <?php echo number_format($tdpayment->ta); $texp += $tdpayment->ta; ?>
                          <?php } else{ ?>
                          <?php echo "-"; ?>
                          <?php } ?>
                        </td>
                        <td style="border: 2px solid #000;">
                          <?php echo number_format(($tcya->tp-$tcya->pppaid)-$tdpayment->ta); $tna += ($tcya->tp-$tcya->pppaid)-$tdpayment->ta; ?>
                        </td>
                      </tr>  
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <th colspan="2" style="text-align: right;">Total Amount</th>
                        <?php
                        foreach ($categogy as $cvalue){
                            
                        $tcya = $this->db->select('SUM(sale_product.sprice) as tsamount,products.cat_id')
                                        ->from('sale_product')
                                        ->join('products','products.productID = sale_product.product','left')
                                        ->where('cat_id',$cvalue->cat_id)
                                        ->where('YEAR(sale_product.regdate)',$year)
                                        ->get()
                                        ->row();
                        ?>
                        <th>
                          <?php if($tcya){ ?>
                          <?php echo number_format($tcya->tsamount); ?>
                          <?php } else{ ?>
                          <?php echo "00"; ?>
                          <?php } ?>
                        </th>
                        <?php } ?>
                      <th><?php echo number_format($tpa, 2); ?></th>
                      <th><?php echo number_format($tppa, 2); ?></th>
                      <th><?php echo number_format($tpda, 2); ?></th>
                      <!--<th><?php echo number_format($tpay, 2); ?></th>-->
                      <!--<th><?php echo number_format(($tpdis-$tpay), 2); ?></th>-->
                      <!--<th><?php echo "Total Expense"; ?></th>-->
                      <th><?php echo number_format(($texp), 2); ?></th>
                      <th><?php echo number_format(($tna), 2); ?></th>
                    </tfoot>
                  </table>
                </div>
              <?php } ?>
            <?php } ?>
          </div>
          </div>
          <div class="form-group col-md-12 col-sm-12 col-xs-12" style="text-align: center; margin-top: 20px">      
            <a href="javascript:void(0)" value="Print" onclick="printDiv('print')" class="btn btn-primary"><i class="fa fa-print"> </i> Print</a>
          </div>
        
      </div>
    </div>
  </div>

    <script type="text/javascript">
      $(document).ready(function(){
        $('#daily').click(function(){
          $('#dreports').removeAttr('class','hidden');
          $('#mreports').attr('class','hidden');
          $('#yreports').attr('class','hidden');

          $('#date').attr('required','required');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          
          $('#ryear').removeAttr('required','required');
          });

        $('#monthly').click(function(){
          $('#mreports').removeAttr('class','hidden');
          $('#dreports').attr('class','hidden');
          $('#yreports').attr('class','hidden');

          $('#date').removeAttr('required','required');
          
          $('#month').attr('required','required');
          $('#year').attr('required','required');
          
          $('#ryear').removeAttr('required','required');
          });

        $('#yearly').click(function(){
          $('#yreports').removeAttr('class','hidden');
          $('#dreports').attr('class','hidden');
          $('#mreports').attr('class','hidden');

          $('#date').removeAttr('required','required');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          
          $('#ryear').attr('required','required');
          });
        });
    </script>