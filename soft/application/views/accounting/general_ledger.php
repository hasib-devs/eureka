<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>General Ledger</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">General Ledger</li>
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
                <h3 class="card-title p-3">General Ledger List</h3>
              </div>

              <div class="card-body">
                <div class="col-sm-12 col-md-12 col-12">
                  <form action="<?php echo base_url() ?>generalLedger" method="get">
                    <div class="col-md-12 col-sm-12 col-12">
                      <div class="form-group">
                        <b>
                          <input type="radio" name="reports" value="dailyReports" id="daily" required >&nbsp;&nbsp;Daily Reports&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="reports" value="monthlyReports" id="monthly" required >&nbsp;&nbsp;Monthly Reports&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="reports" value="yearlyReports" id="yearly" required >&nbsp;&nbsp;Yearly Reports
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
                    <?php if ($report == 'dailyReports'){ ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>General Ledger Reports in : <?php echo $sdate.' - '.$edate; ?></b></h3>
                    </div>
                    <?php } else if ($report == 'monthlyReports') { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>General Ledger Reports in : <?php echo $name.' '.$year; ?></b></h3>
                    </div>
                    <?php } else if ($report == 'yearlyReports') { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>General Ledger Reports in : <?php echo $year; ?></b></h3>
                    </div>
                    <?php } } else { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>General Ledger Reports</b></h3>
                    </div>
                    <?php } ?><br>
                  
                    <div id="table-content">
                      <table class="table table-bordered" style="width: 100%;">
                        <thead>
                          <tr>
                            <th style="width: 5%">#SN.</th>
                            <th style="width: 10%">Date</th>
                            <th>Description</th>
                            <th style="width: 15%">Credit Amount</th>
                            <th style="width: 15%">Debit Amount</th>
                          </tr>
                        </thead>
                      </table>

                      <?php if($sale) { ?>
                      <div><b><?php echo 'Sales'; ?></b></div>
                      <table class="table table-bordered" style="width: 100%;">
                        <tbody>
                          <?php
                          $k = 0;
                          $tla = 0;
                          foreach ($sale as $lvalue) {
                          $k++;
                          ?>
                          <tr>
                            <td style="width: 5%"><?php echo $k; ?></td>
                            <td style="width: 10%"><?php echo date('d-m-Y',strtotime($lvalue->saDate)); ?></td>
                            <td><?php echo 'Sales Amount'; ?></td>
                            <td style="width: 15%"><?php echo number_format(($lvalue->pAmount), 2); $tla += $lvalue->pAmount; ?></td>
                            <td style="width: 15%"><?php echo '00'; ?></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                        <tbody>
                          <tr>
                            <td colspan="3" style="text-align: right;">Total Amount</td>
                            <td><?php echo number_format(($tla), 2); ?></td>
                            <td><?php echo '00'; ?></td>
                          </tr>
                        </tbody>
                      </table>
                      <?php } ?>

                      <?php if($cvoucher) { ?>
                      <div><b><?php echo 'Received Voucher'; ?></b></div>
                      <table class="table table-bordered" style="width: 100%;">
                        <tbody>
                          <?php
                          $k = 0;
                          $tla = 0;
                          foreach ($cvoucher as $lvalue) {
                          $k++;
                          ?>
                          <tr>
                            <td style="width: 5%"><?php echo $k; ?></td>
                            <td style="width: 10%"><?php echo date('d-m-Y',strtotime($lvalue->vuDate)); ?></td>
                            <td><?php echo 'Received Voucher Amount'; ?></td>
                            <td style="width: 15%"><?php echo number_format(($lvalue->tAmount), 2); $tla += $lvalue->tAmount; ?></td>
                            <td style="width: 15%"><?php echo '00'; ?></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                        <tbody>
                          <tr>
                            <td colspan="3" style="text-align: right;">Total Amount</td>
                            <td><?php echo number_format(($tla), 2); ?></td>
                            <td><?php echo '00'; ?></td>
                          </tr>
                        </tbody>
                      </table>
                      <?php } ?>

                      <?php if($purchase) { ?>
                      <div><b><?php echo 'Purchase'; ?></b></div>
                      <table class="table table-bordered" style="width: 100%;">
                        <tbody>
                          <?php
                          $k = 0;
                          $tla = 0;
                          foreach ($purchase as $lvalue) {
                          $k++;
                          ?>
                          <tr>
                            <td style="width: 5%"><?php echo $k; ?></td>
                            <td style="width: 10%"><?php echo date('d-m-Y',strtotime($lvalue->puDate)); ?></td>
                            <td><?php echo 'Purchase Amount'; ?></td>
                            <td style="width: 15%"><?php echo '00'; ?></td>
                            <td style="width: 15%"><?php echo number_format(($lvalue->pAmount), 2); $tla += $lvalue->pAmount; ?></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                        <tbody>
                          <tr>
                            <td colspan="3" style="text-align: right;">Total Amount</td>
                            <td><?php echo '00'; ?></td>
                            <td><?php echo number_format(($tla), 2); ?></td>
                          </tr>
                        </tbody>
                      </table>
                      <?php } ?>

                      <?php if($payment) { ?>
                      <div><b><?php echo 'Payment'; ?></b></div>
                      <table class="table table-bordered" style="width: 100%;">
                        <tbody>
                          <?php
                          $k = 0;
                          $tla = 0;
                          foreach ($payment as $lvalue) {
                          $k++;
                          ?>
                          <tr>
                            <td style="width: 5%"><?php echo $k; ?></td>
                            <td style="width: 10%"><?php echo date('d-m-Y',strtotime($lvalue->regdate)); ?></td>
                            <td><?php echo 'Employee Payment'; ?></td>
                            <td style="width: 15%"><?php echo '00'; ?></td>
                            <td style="width: 15%"><?php echo number_format(($lvalue->salary), 2); $tla += $lvalue->salary; ?></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                        <tbody>
                          <tr>
                            <td colspan="3" style="text-align: right;">Total Amount</td>
                            <td><?php echo '00'; ?></td>
                            <td><?php echo number_format(($tla), 2); ?></td>
                          </tr>
                        </tbody>
                      </table>
                      <?php } ?>

                      <?php if($return) { ?>
                      <div><b><?php echo 'Return'; ?></b></div>
                      <table class="table table-bordered" style="width: 100%;">
                        <tbody>
                          <?php
                          $k = 0;
                          $tla = 0;
                          foreach ($return as $lvalue) {
                          $k++;
                          ?>
                          <tr>
                            <td style="width: 5%"><?php echo $k; ?></td>
                            <td style="width: 10%"><?php echo date('d-m-Y',strtotime($lvalue->rDate)); ?></td>
                            <td><?php echo 'Product Return'; ?></td>
                            <td style="width: 15%"><?php echo '00'; ?></td>
                            <td style="width: 15%"><?php echo number_format(($lvalue->pAmount), 2); $tla += $lvalue->pAmount; ?></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                        <tbody>
                          <tr>
                            <td colspan="3" style="text-align: right;">Total Amount</td>
                            <td><?php echo '00'; ?></td>
                            <td><?php echo number_format(($tla), 2); ?></td>
                          </tr>
                        </tbody>
                      </table>
                      <?php } ?>

                      <?php if($dvoucher) { ?>
                      <div><b><?php echo 'Payment Voucher'; ?></b></div>
                      <table class="table table-bordered" style="width: 100%;">
                        <tbody>
                          <?php
                          $k = 0;
                          $tla = 0;
                          foreach ($dvoucher as $value) {
                          $k++;
                          ?>
                          <tr>
                            <td style="width: 5%"><?php echo $k; ?></td>
                            <td style="width: 10%"><?php echo date('d-m-Y',strtotime($value->vuDate)); ?></td>
                            <td><?php echo 'Payment Voucher Amount'; ?></td>
                            <td style="width: 15%"><?php echo '00'; ?></td>
                            <td style="width: 15%"><?php echo number_format(($value->tAmount), 2); $tla += $value->tAmount; ?></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                        <tbody>
                          <tr>
                            <td colspan="3" style="text-align: right;">Total Amount</td>
                            <td><?php echo '00'; ?></td>
                            <td><?php echo number_format(($tla), 2); ?></td>
                          </tr>
                        </tbody>
                      </table>
                      <?php } ?>
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