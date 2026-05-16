<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Balance Sheet</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Balance Sheet</li>
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
                <h3 class="card-title p-3">Balance Sheet List</h3>
              </div>

              <div class="card-body">

                <div class="box-body">
                  <div id="print">
                    <div class="row" id="header" style="display: none">
                      <div class="col-sm-2 col-md-2 col-2" style="margin-top: 30px;">
                        <img src="<?php echo base_url($company->com_logo); ?>" style="width: 100%;">
                      </div>
                      <div class="col-sm-12 col-md-12 col-12" style="text-align:center;">
                        <div class="col-sm-12 col-md-12 col-12">
                          <h3><b><?php echo $company->com_name; ?></b></h3>
                        </div>
                        <div class="col-sm-12 col-md-12 col-12">
                          <b><?php echo $company->com_address; ?></b>
                        </div>
                      </div>
                    </div>

                    <?php if(isset($_GET['search'])) { ?>
                    <?php if ($report == 'dailyReports'){ ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Balance Sheet Reports in : <?php echo $sdate.' - '.$edate; ?></b></h3>
                    </div>
                    <?php } else if ($report == 'monthlyReports') { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Balance Sheet Reports in : <?php echo $name.' '.$year; ?></b></h3>
                    </div>
                    <?php } else if ($report == 'yearlyReports') { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Balance Sheet Reports in : <?php echo $year; ?></b></h3>
                    </div>
                    <?php } } else { ?>
                    <div class="box-header" style="text-align: center;">
                      <h2 class="box-title"><b>Balance Sheet</b></h2>
                    </div>
                    <?php } ?><br>

                                    
                    <div class="table-content">
                      <?php 
                      $net_profit = 0; 
                      $diff = 0;
                      $assets = 0;
                      $liabilities = 0;
                      ?>
                      <table class="table table-bordered table-striped">
                        <thead>
                          <th>Asset</th>
                          <th>Taka</th>
                          <th>Liabilities</th>
                          <th>Taka</th>
                        </thead>
                        <tbody>
                          <tr>
                            <th>Cash</th>
                            <td><?php echo number_format($cash->total, 2);?></td>
                            <th>Account Payable</th>
                            <td>
                              <?php echo number_format(($payable->total), 2);?>
                            </td>
                          </tr>
                          <tr>
                            <th>Bank</th>
                            <td>
                              <?php echo number_format(($bank->total), 2);?>
                            </td>
                            <th>Net Profit</th>
                            <td>
                              <?php $net_profit = ($sale->total - ($purchase->total + $dvoucher->total));?>
                              <?php echo number_format($net_profit, 2);?>
                            </td>
                          </tr>
                          <tr>
                            <th>Mobile</th>
                            <td>
                              <?php echo number_format(($mobile->total),2); ?>
                            </td>
                            <th>Capital</th>
                            <td>
                              <?php echo number_format(($company->com_balance), 2);?>
                            </td>
                          </tr>
                          <tr>
                            <th>Account Receivable</th>
                            <td>
                              <?php echo number_format(($receivable->total),2); ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Closing Stock</th>
                            <?php
                            $total=0;
                            foreach($closing_inv as $inv){
                              $total += $inv->tquantity*$inv->tpprice;
                              }?>
                            <td><?php echo number_format(($total), 2); ?></td>
                          </tr>
                        </tbody>
                        <tfoot>
                          <th></th>
                          <td>
                            <?php echo number_format(($cash->total + $bank->total + $mobile->total + $receivable->total+$total), 2); ?>
                          </td>
                          <th></th>
                          <td>
                            <?php echo number_format(($payable->total + $net_profit + $company->com_balance), 2); ?>
                          </td>
                        </tfoot>
                      </table>
                    </div>

                    <!-- <div class="col-sm-12 col-md-12 col-12">
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
                    </div> -->

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