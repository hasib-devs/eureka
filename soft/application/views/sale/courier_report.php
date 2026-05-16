<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Courier Sale Reports</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Courier Sale Reports</li>
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
                <h3 class="card-title">Courier Sale Reports</h3>
              </div>

              <div class="card-body">
                <div class="col-sm-12 col-md-12 col-12">
                  <form action="<?php echo base_url() ?>saleCReport" method="get">
                    <div class="col-md-12 col-sm-12 col-12">
                      <div class="form-group col-md-12 col-sm-12 col-12">
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
                          <div class="form-group col-md-3 col-sm-3 col-12">
                            <label>Select Courier *</label>
                            <select class="form-control" name="dcourier" id="dcourier" required="" >
                              <option value="">Select One</option>
                              <option value="All">All Courier</option>
                              <?php foreach($courier as $value){ ?>
                              <option value="<?php echo $value['caid']; ?>"><?php echo $value['caName']; ?></option>
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
                            <label>Select Courier *</label>
                            <select class="form-control" name="mcourier" id="mcourier" required="" >
                              <option value="">Select One</option>
                              <option value="All">All Courier</option>
                              <?php foreach($courier as $value){ ?>
                              <option value="<?php echo $value['caid']; ?>"><?php echo $value['caName']; ?></option>
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
                            <label>Select Courier *</label>
                            <select class="form-control" name="ycourier" id="ycourier" required="" >
                              <option value="">Select One</option>
                              <option value="All">All Courier</option>
                              <?php foreach($courier as $value){ ?>
                              <option value="<?php echo $value['caid']; ?>"><?php echo $value['caName']; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="form-group col-md-2 col-sm-2 col-xs-12">
                            <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp;Search</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>

                <div class="col-sm-12 col-md-12 col-12">
                  <div id="print">
                    <div class="col-sm-12 col-md-12 col-12">
                      <table id="example1" class="table table-responsive table-bordered" >
                        <thead>
                          <tr>
                            <th style="width: 5%;">#SN.</th>
                            <th>Invoice</th>
                            <th>Date</th>
                            <th>Sales Man</th>
                            <th>Customer</th>
                            <th>Courier</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Discount</th>
                            <th style="width: 10%;">Due</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $i = 0;
                          $ts = 0;
                          $tp = 0;
                          $td = 0;
                          $tda = 0;
                          foreach ($sales as $sale){
                          $i++;
                          
                          $pay = $this->db->select("SUM(pAmount) as total")
                                            ->FROM('sales_payment')
                                            ->WHERE('said',$sale->said)
                                            ->get()
                                            ->row();
                            if($pay)
                              {
                              $tpay = $pay->total;
                              }
                            else
                              {
                              $tpay = 0;
                              }
                          ?>
                          <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $sale->invoice; ?></td>
                            <td><?php echo date('j F Y', strtotime($sale->saDate)); ?></td>
                            <td><?php echo $sale->name; ?></td>
                            <td><?php echo $sale->custName; ?></td>
                            <td><?php echo $sale->courierName; ?></td>
                            <td><?php echo number_format($sale->tAmount, 2); $ts += $sale->tAmount; ?></td>
                            <td><?php echo number_format($sale->pAmount+$tpay, 2); $tp += $sale->pAmount+$tpay; ?></td>
                            <td><?php echo number_format($sale->disAmount, 2); $td += $sale->disAmount; ?></td>
                            <td><?php echo number_format($sale->dAmount-$tpay, 2); $tda += $sale->dAmount-$tpay; ?></td>
                          </tr> 
                          <?php } ?>
                        </tbody>
                        <tbody>
                          <tr>
                            <td colspan="6" align="right"><b>Total Amount</b></td>
                            <td><b><?php echo number_format($ts, 2); ?></b></td>
                            <td><b><?php echo number_format($tp, 2); ?></b></td>
                            <td><b><?php echo number_format($td, 2); ?></b></td>
                            <td><b><?php echo number_format($tda, 2); ?></b></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="row no-print" >
                      <div class="col-12" style="text-align: center;">
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="printDiv('print')" ><i class="fas fa-print"></i> Print</a>
                      </div>
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

<?php $this->load->view('footer/footer'); ?>

    <script type="text/javascript">
      $(document).ready(function() {
        $('#daily').click(function(){
          $('#dreports').removeAttr('class','d-none');
          $('#mreports').attr('class','d-none');
          $('#yreports').attr('class','d-none');

          $('#sdate').attr('required','required');
          $('#edate').attr('required','required');
          $('#dcourier').attr('required','required');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          $('#mcourier').removeAttr('required','required');
          
          $('#ryear').removeAttr('required','required');
          $('#ycourier').removeAttr('required','required');
          });

        $('#monthly').click(function(){
          $('#mreports').removeAttr('class','d-none');
          $('#dreports').attr('class','d-none');
          $('#yreports').attr('class','d-none');

          $('#sdate').removeAttr('required','required');
          $('#edate').removeAttr('required','required');
          $('#dcourier').removeAttr('required','required');
          
          $('#month').attr('required','required');
          $('#year').attr('required','required');
          $('#mcourier').attr('required','required');
          
          $('#ryear').removeAttr('required','required');
          $('#ycourier').removeAttr('required','required');
          });

        $('#yearly').click(function(){
          $('#yreports').removeAttr('class','d-none');
          $('#dreports').attr('class','d-none');
          $('#mreports').attr('class','d-none');

          $('#sdate').removeAttr('required','required');
          $('#edate').removeAttr('required','required');
          $('#dcourier').removeAttr('required','required');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          $('#mcourier').removeAttr('required','required');
          
          $('#ryear').attr('required','required');
          $('#ycourier').attr('required','required');
          });
        });
    </script>