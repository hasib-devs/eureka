<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Pre-Order Report</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Pre-Order Report</li>
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
                <h3 class="card-title">Pre-Order Report</h3>
              </div>

              <div class="card-body">
                <div class="row">
                  <form action="<?php echo base_url() ?>orderReport" method="get">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <div class="form-group">
                        <b>
                        <input type="radio" name="reports" value="allReports" id="allReport" required >&nbsp;&nbsp;User All Reports&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="reports" value="dailyReports" id="daily" required >&nbsp;&nbsp;Daily Reports&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="reports" value="monthlyReports" id="monthly" required >&nbsp;&nbsp;Monthly Reports&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="reports" value="yearlyReports" id="yearly" required >&nbsp;&nbsp;Yearly Reports
                        </b>
                      </div>
        
                      <div class="d-none" id="areport">
                        <div class="row">
                          <div class="form-group col-md-4 col-sm-4 col-xs-12">
                            <label>Select User *</label>
                            <select class="form-control" name="customer" id="customer" required="" >
                              <option value="">Select One</option>
                              <?php foreach($customer as $value){ ?>
                              <option value="<?php echo $value['uid']; ?>"><?php echo $value['name']; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="form-group col-md-4 col-sm-4 col-xs-12">
                            <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>
                          </div>
                        </div>
                      </div>
        
                      <div class="d-none" id="dreports">
                        <div class="row">
                          <div class="form-group col-md-3 col-sm-3 col-xs-12">
                            <label>Start Date *</label>
                            <input type="text" class="form-control datepicker" name="sdate" id="sdate" placeholder="Start Date" required="" >
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-xs-12">
                            <label>End Date *</label>
                            <input type="text" class="form-control datepicker" name="edate" id="edate" placeholder="End Date" required="" >
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-xs-12">
                            <label>Select User *</label>
                            <select class="form-control" name="dcustomer" id="dcustomer" required="" >
                              <option value="">Select One</option>
                              <option value="All">All User</option>
                              <?php foreach($customer as $value){ ?>
                              <option value="<?php echo $value['uid']; ?>"><?php echo $value['name']; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-xs-12">
                            <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>
                          </div>
                        </div>
                      </div>
        
                      <div class="d-none" id="mreports">
                        <div class="row">
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
                            <select class="form-control" name="year" id="year" required="" >
                              <?php $d = date("Y"); ?>
                              <option value="">Select One</option>
                              <?php for ($x = 2020; $x <= $d; $x++) { ?>
                              <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-xs-12">
                            <label>Select User *</label>
                            <select class="form-control" name="mcustomer" id="mcustomer" required="" >
                              <option value="">Select One</option>
                              <option value="All">All User</option>
                              <?php foreach($customer as $value){ ?>
                              <option value="<?php echo $value['uid']; ?>"><?php echo $value['name']; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-xs-12">
                            <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp;Search</button>
                          </div>
                        </div>
                      </div>
        
                      <div class="d-none" id="yreports">
                        <div class="row">
                          <div class="form-group col-md-3 col-sm-3 col-xs-12">
                            <label>Select Year *</label>
                            <select class="form-control" name="ryear" id="ryear" required="" >
                              <?php $d = date("Y"); ?>
                              <option value="">Select One</option>
                              <?php for ($x = 2020; $x <= $d; $x++) { ?>
                              <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-xs-12">
                            <label>Select User *</label>
                            <select class="form-control" name="ycustomer" id="ycustomer" required="" >
                              <option value="">Select One</option>
                              <option value="All">All User</option>
                              <?php foreach($customer as $value){ ?>
                              <option value="<?php echo $value['uid']; ?>"><?php echo $value['name']; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-xs-12">
                            <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp;Search</button>
                          </div>
                        </div>
                      </div>
        
                    </div>
                  </form>
                </div><br>
                
                <div id="print">
                  <?php if(isset($_GET['search'])) { ?>
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
                    
                    <div class="col-sm-12 col-md-12 col-12">
                      <div class="col-sm-12 col-md-12 col-12">
                        <b>Staff Name&nbsp;&nbsp;:&nbsp;&nbsp;</b><?php if(isset($users[0]['name'])){echo $users[0]['name'];}else{echo '';} ?>
                      </div>
                      <div class="col-sm-12 col-md-12 col-12">
                        <b>Contact No&nbsp;&nbsp;:&nbsp;&nbsp;</b><?php if(isset($users[0]['mobile'])){echo $users[0]['mobile'];}else{echo '';} ?>
                      </div>
                      <div class="col-sm-12 col-md-12 col-12">
                        <?php 
                        if(isset($users[0]['uid']))
                          {
                          $query = $this->db->select('*')->from('order')->where('regby',$users[0]['uid'])->get();

                          $count = $query->num_rows();
                          }
                        else
                          {
                          $count = 0;
                          }
                        ?>
                        <b>Total Order&nbsp;&nbsp;:&nbsp;&nbsp;</b><?php echo $count; ?>
                      </div>
                    </div>
                  <?php if ($report == 'dailyReports') { ?>
                  <div class="box-header" style="text-align: center;">
                    <h3 class="box-title"><b>Order Report in : <?php echo $sdate.' - '.$edate; ?></b></h3>
                  </div>
        
                  <?php } else if ($report == 'monthlyReports') { ?>
                  <div class="box-header" style="text-align: center;">
                    <h3 class="box-title"><b>Order Report in : <?php echo $name.' '.$year; ?></b></h3>
                  </div>
        
                  <?php } else if ($report == 'yearlyReports') { ?>
                  <div class="box-header" style="text-align: center;">
                    <h3 class="box-title"><b>Order Report in : <?php echo $year; ?></b></h3>
                  </div>
                  <?php } ?>
                <?php } ?>

                  <table id="" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#SN.</th>
                        <th>Date</th>
                        <th>Order No.</th>
                        <th>Customer</th>
                        <th>Mobile</th>
                        <th>Address</th>
                        <th>Amount</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $i = 0;
                      $ta = 0;
                      foreach ($sale as $value){
                      $i++;
                      ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo date('d-M-Y',strtotime($value->oDate)); ?></td>
                        <td><?php echo $value->oCode; ?></td>
                        <td><?php echo $value->custName; ?></td>
                        <td><?php echo $value->custMobile; ?></td>
                        <td><?php echo $value->custAddress; ?></td>
                        <td><?php echo number_format($value->tAmount, 2); $ta += $value->tAmount; ?></td>
                        <td>
                        <?php if($value->status == 1){ ?>
                        <?php echo 'On Process'; ?>
                        <?php } else if($value->status == 2){ ?>
                        <?php echo 'Sales Order'; ?>
                        <?php } else if($value->status == 5){ ?>
                        <?php echo 'Canceled'; ?>
                        <?php } else{ ?>
                        <?php echo 'N/A'; ?>
                        <?php } ?>
                      </td>
                      </tr>
                      <?php } ?>
                      <tr>
                        <th colspan="6" style="text-align: right;">Total Amount</th>
                        <td><?php echo number_format($ta, 2); ?></td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                </div><br>
                
                <div class="form-group col-md-12" style="text-align: center; margin-top: 20px">
                  <a href="javascript:void(0)" value="Print" onclick="printDiv('print')" class="btn btn-primary"><i class="fa fa-print"></i>  Print</a>
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
          $('#areport').attr('class','d-none');

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
          $('#areport').attr('class','d-none');

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
          $('#areport').attr('class','d-none');

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

        $('#allReport').click(function(){
          $('#areport').removeAttr('class','d-none');
          $('#yreports').attr('class','d-none');
          $('#dreports').attr('class','d-none');
          $('#mreports').attr('class','d-none');

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