<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Chart of Accounting</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Chart 0f Accounting</li>
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
              <div class="card-header">
                <h3 class="card-title">Chart of Accounting</h3>
              </div>

              <div class="card-body">
                <!--<div class="col-sm-12 col-md-12 col-12">-->
                <!--  <form action="<?php echo base_url() ?>coaReport" method="get">-->
                <!--    <div class="col-md-12 col-sm-12 col-12">-->
                <!--      <div class="form-group col-md-12 col-sm-12 col-12">-->
                <!--        <b>-->
                <!--          <input type="radio" name="reports" value="dailyReports" id="daily" required >&nbsp;&nbsp;Daily Reports&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
                <!--          <input type="radio" name="reports" value="monthlyReports" id="monthly" required >&nbsp;&nbsp;Monthly Reports&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
                <!--          <input type="radio" name="reports" value="yearlyReports" id="yearly" required >&nbsp;&nbsp;Yearly Reports-->
                <!--        </b>-->
                <!--      </div>-->

                <!--      <div class="d-none" id="dreports">-->
                <!--        <div class="row">-->
                <!--          <div class="form-group col-md-2 col-sm-2 col-12">-->
                <!--            <label>Start Date *</label>-->
                <!--            <input type="text" class="form-control datepicker" name="sdate" value="<?php echo date('m/d/Y') ?>" id="sdate" required="" >-->
                <!--          </div>-->
                <!--          <div class="form-group col-md-2 col-sm-2 col-12">-->
                <!--            <label>End Date *</label>-->
                <!--            <input type="text" class="form-control datepicker" name="edate" value="<?php echo date('m/d/Y') ?>" id="edate" required="" >-->
                <!--          </div>-->
                <!--          <div class="form-group col-md-2 col-sm-2 col-12">-->
                <!--            <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>-->
                <!--          </div>-->
                <!--        </div>-->
                <!--      </div>-->

                <!--      <div class="d-none" id="mreports">-->
                <!--        <div class="row">-->
                <!--          <div class="form-group col-md-2 col-sm-2 col-12">-->
                <!--            <label>Select Month *</label>-->
                <!--            <select class="form-control" name="month" id="month" required="" >-->
                <!--              <option value="">Select One</option>-->
                <!--              <option value="1">January</option>-->
                <!--              <option value="2">February</option>-->
                <!--              <option value="3">March</option>-->
                <!--              <option value="4">April</option>-->
                <!--              <option value="5">May</option>-->
                <!--              <option value="6">June</option>-->
                <!--              <option value="7">July</option>-->
                <!--              <option value="8">August</option>-->
                <!--              <option value="9">September</option>-->
                <!--              <option value="10">October</option>-->
                <!--              <option value="11">November</option>-->
                <!--              <option value="12">December</option>-->
                <!--            </select>-->
                <!--          </div>-->
                <!--          <div class="form-group col-md-2 col-sm-2 col-12">-->
                <!--            <label>Select Year *</label>-->
                <!--            <select class="form-control" name="year" id="year" required="" >-->
                <!--              <?php $d = date("Y"); ?>-->
                <!--              <option value="">Select One</option>-->
                <!--              <?php for ($x = 2020; $x <= $d; $x++) { ?>-->
                <!--              <option value="<?php echo $x; ?>"><?php echo $x; ?></option>-->
                <!--              <?php } ?>-->
                <!--            </select>-->
                <!--          </div>-->
                <!--          <div class="form-group col-md-2 col-sm-2 col-12">-->
                <!--            <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>-->
                <!--          </div>-->
                <!--        </div>-->
                <!--      </div>-->

                <!--      <div class="d-none" id="yreports">-->
                <!--        <div class="row">-->
                <!--          <div class="form-group col-md-2 col-sm-2 col-12">-->
                <!--            <label>Select Year *</label>-->
                <!--            <select class="form-control" name="ryear" id="ryear" required="" >-->
                <!--              <?php $d = date("Y"); ?>-->
                <!--              <option value="">Select One</option>-->
                <!--              <?php for ($x = 2020; $x <= $d; $x++) { ?>-->
                <!--              <option value="<?php echo $x; ?>"><?php echo $x; ?></option>-->
                <!--              <?php } ?>-->
                <!--            </select>-->
                <!--          </div>-->
                <!--          <div class="form-group col-md-2 col-sm-2 col-xs-12">-->
                <!--            <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp;Search</button>-->
                <!--          </div>-->
                <!--        </div>-->
                <!--      </div>-->
                <!--    </div>-->
                <!--  </form>-->
                <!--</div>-->

                <div class="col-sm-12 col-md-12 col-12">
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

                    <div class="row">
                      <div class="col-sm-12 col-md-12 col-12" ><h3><b>Capital Account</b></h3></div>
                      <div class="col-sm-12 col-md-12 col-12" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Reserves & Surplus</b></div>
                      <table class="table table-bordered" style="width: 100%; margin-left: 22px;">
                        <tbody>
                          <?php
                          $i = 0;
                          foreach($equity as $value){
                          $i++;
                          ?>
                          <tr>
                            <td style="width: 5%"><?php echo $i; ?></td>
                            <td><?php echo $value->name; ?></td>
                            <td style="width: 15%"><?php echo number_format($value->caamount, 2); ?></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                    
                    <div class="row">
                      <div class="col-sm-12 col-md-12 col-12" ><h3><b>Current Assets</b></h3></div>
                      <div class="col-sm-12 col-md-12 col-12" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Bank Accounts</b></div>
                      <table class="table table-bordered" style="width: 100%; margin-left: 22px;">
                        <tbody>
                          <?php
                          $i = 0;
                          foreach($bank as $value){
                          $i++;
                          ?>
                          <tr>
                            <td style="width: 5%"><?php echo $i; ?></td>
                            <td><?php echo $value->bankName.' '.$value->branchName.' '.$value->accountNo.' '.$value->accountName; ?></td>
                            <td style="width: 15%"><?php echo number_format($value->balance, 2); ?></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                      <div class="col-sm-12 col-md-12 col-12" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Cash Accounts</b></div>
                      <table class="table table-bordered" style="width: 100%; margin-left: 22px;">
                        <tbody>
                          <?php
                          $i = 0;
                          foreach($cash as $value){
                          $i++;
                          ?>
                          <tr>
                            <td style="width: 5%"><?php echo $i; ?></td>
                            <td><?php echo $value->cashName; ?></td>
                            <td style="width: 15%"><?php echo number_format($value->balance, 2); ?></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                      <div class="col-sm-12 col-md-12 col-12" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Mobile Accounts</b></div>
                      <table class="table table-bordered" style="width: 100%; margin-left: 22px;">
                        <tbody>
                          <?php
                          $i = 0;
                          foreach($mobile as $value){
                          $i++;
                          ?>
                          <tr>
                            <td style="width: 5%"><?php echo $i; ?></td>
                            <td><?php echo $value->accountName.' '.$value->accountNo; ?></td>
                            <td style="width: 15%"><?php echo number_format($value->balance, 2); ?></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                    
                    <?php $tsp = 0;  foreach($stock as $value){ ?>
                    <?php $tsp += $value->tquantity*$value->pprice; ?>
                    <?php } ?>
                          
                    <div class="row">
                      <div class="col-sm-12 col-md-12 col-12" ><h3><b>Deposits Assets</b></h3></div>
                      <div class="col-sm-12 col-md-12 col-12" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Stock in Hands</b><span style="float: right;"><?php echo number_format($tsp, 2); ?></span></div>
                      <div class="col-sm-12 col-md-12 col-12" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Sundry Debtors</b><span style="float: right;"><?php echo number_format($sdue, 2); ?></span></div>
                      <table class="table table-bordered" style="width: 100%; margin-left: 22px;">
                        <tbody>
                          <?php
                          $i = 0;
                          foreach($assets as $value){
                          $i++;
                          ?>
                          <tr>
                            <td style="width: 5%"><?php echo $i; ?></td>
                            <td><?php echo $value->cadetails; ?></td>
                            <td style="width: 15%"><?php echo number_format($value->caamount, 2); ?></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                    
                    <div class="row">
                      <div class="col-sm-12 col-md-12 col-12" ><h3><b>Current Liabilities</b></h3></div>
                      <div class="col-sm-12 col-md-12 col-12" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Sundry Creditors</b><span style="float: right;"><?php echo number_format($pdue, 2); ?></span></div>
                      <table class="table table-bordered" style="width: 100%; margin-left: 22px;">
                        <tbody>
                          <?php
                          $i = 0;
                          foreach($liability as $value){
                          $i++;
                          ?>
                          <tr>
                            <td style="width: 5%"><?php echo $i; ?></td>
                            <td><?php echo $value->cadetails; ?></td>
                            <td style="width: 15%"><?php echo number_format($value->caamount, 2); ?></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                    
                    <div class="row">
                      <div class="col-sm-12 col-md-12 col-12" ><h3><b>Expenses</b></h3></div>
                      <table class="table table-bordered" style="width: 100%; margin-left: 22px;">
                        <tbody>
                          <?php
                          $i = 0;
                          foreach($expense as $value){
                          $i++;
                          ?>
                          <tr>
                            <td style="width: 5%"><?php echo $i; ?></td>
                            <td><?php echo $value->cadetails; ?></td>
                            <td style="width: 15%"><?php echo number_format($value->caamount, 2); ?></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                    
                    <div class="row">
                      <div class="col-sm-12 col-md-12 col-12" ><h3><b>Purchase Accounts</b><span style="float: right;"><?php echo number_format($purchase->total, 2); ?></span></h3></div>
                    </div>
                    
                    <div class="row">
                      <div class="col-sm-12 col-md-12 col-12" ><h3><b>Sale Accounts</b><span style="float: right;"><?php echo number_format($sale->total, 2); ?></span></h3></div>
                    </div>
                    
                  </div>
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
      $(document).ready(function() {
        $('#daily').click(function(){
          $('#dreports').removeAttr('class','d-none');
          $('#mreports').attr('class','d-none');
          $('#yreports').attr('class','d-none');

          $('#sdate').attr('required','required');
          $('#edate').attr('required','required');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          
          $('#ryear').removeAttr('required','required');
          });

        $('#monthly').click(function(){
          $('#mreports').removeAttr('class','d-none');
          $('#dreports').attr('class','d-none');
          $('#yreports').attr('class','d-none');

          $('#sdate').removeAttr('required','required');
          $('#edate').removeAttr('required','required');
          
          $('#month').attr('required','required');
          $('#year').attr('required','required');
          
          $('#ryear').removeAttr('required','required');
          });

        $('#yearly').click(function(){
          $('#yreports').removeAttr('class','d-none');
          $('#dreports').attr('class','d-none');
          $('#mreports').attr('class','d-none');

          $('#sdate').removeAttr('required','required');
          $('#edate').removeAttr('required','required');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          
          $('#ryear').attr('required','required');
          });
        });
    </script>