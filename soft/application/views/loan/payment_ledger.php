<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Employee Payment Ledger</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Employee Payment Ledger</li>
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
                <h3 class="card-title">Employee Payment Ledger</h3>
              </div>

              <div class="card-body">
                <div class="col-sm-12 col-md-12 col-12">
                  <form action="<?php echo base_url() ?>empPLedger" method="get">
                    <div class="col-md-12 col-sm-12 col-12">
                      <div class="form-group">
                        <b>
                          <input type="radio" name="reports" value="ocust" id="ocust" required >&nbsp;&nbsp;Employee All Ledger&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="reports" value="monthlyReports" id="monthly" required >&nbsp;&nbsp;Monthly Ledger&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="reports" value="yearlyReports" id="yearly" required >&nbsp;&nbsp;Yearly Ledger
                        </b>
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
                            <label>Select Employee *</label>
                            <select class="form-control select2" name="memp" id="mcustomer" required="" style="width: 100%;">
                              <option value="">Select One</option>
                              <?php foreach($employee as $value) { ?>
                              <option value="<?php echo $value['employeeID']; ?>" ><?php echo $value['employeeName'].' ( '.$value['emp_id'].' )'; ?></option>
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
                            <label>Select Employee *</label>
                            <select class="form-control select2" name="yemp" id="ycustomer" required="" style="width: 100%;">
                              <option value="">Select One</option>
                              <?php foreach($employee as $value) { ?>
                              <option value="<?php echo $value['employeeID']; ?>" ><?php echo $value['employeeName'].' ( '.$value['emp_id'].' )'; ?></option>
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
                            <label>Select Employee *</label>
                            <select name="emp" class="form-control select2" id="customer" required="" style="width: 100%;">
                              <option value="">Select One</option>
                              <?php foreach($employee as $value) { ?>
                              <option value="<?php echo $value['employeeID']; ?>" ><?php echo $value['employeeName'].' ( '.$value['emp_id'].' )'; ?></option>
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
                      <?php if($company) { ?>
                    <div class="row" id="header" style="display: none;" >
                      <div class="col-sm-2 col-md-2 col-2" style="margin-top: 30px;">
                        <img src="<?php echo base_url().'upload/company/'.$company->com_logo; ?>"  style="width: 100%;">
                      </div>
                      <div class="col-sm-8 col-md-8 col-8" style="text-align: center;">
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
                    <?php } ?>
                  <?php if(isset($_GET['search'])) { ?>
                    <div class="col-sm-12 col-md-12 col-12">
                      <div class="col-sm-12 col-md-12 col-12">
                        Employee ID&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $emp[0]['emp_id']; ?>
                      </div>
                      <div class="col-sm-12 col-md-12 col-12">
                        Employee Name&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $emp[0]['employeeName']; ?>
                      </div>
                      <div class="col-sm-12 col-md-12 col-12">
                        Address&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $emp[0]['empaddress']; ?>
                      </div>
                      <div class="col-sm-12 col-md-12 col-12">
                        Contact No&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $emp[0]['phone']; ?>
                      </div>
                    </div>
                    <?php if ($report == 'monthlyReports') { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Employee Ledger Reports in : <?php echo $name.' '.$year; ?></b></h3>
                    </div>
                    <?php } else if ($report == 'yearlyReports') { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Employee Ledger Reports in : <?php echo $year; ?></b></h3>
                    </div>
                    <?php } else { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Employee Ledger Reports</b></h3>
                    </div>
                    <?php } ?>
                  
                    <div id="table-content ">
                      <div class="table-responsive">
                        <table id="exam22ple" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>#SN.</th>
                            <th>Date</th>
                            <th>Salary</th>
                            <th>Attendance</th>
                            <th>House Rent</th>
                            <!--<th>Lunch Bill</th>-->
                            <!--<th>Provident</th>-->
                            <th>Bonus</th>
                            <th>Allowance</th>
                            <th>Net Amount</th>
                            <th>Paid</th>
                            <th>Due</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $i = 0;
                          $tsa = 0;
                          $tpa = 0;
                          $tda = 0;
                          $tdu = 0;
                          foreach($payment as $value){
                          $i++;
                          ?>
                          <tr class="gradeX">
                            <td><?php echo $i; ?></td>
                            <td>
                              <?php
                                $month = $value->month;
                                if($month == 01)
                                  {
                                  $name = 'January';
                                  }
                                elseif($month == 02)
                                  {
                                  $name = 'February';
                                  }
                                elseif($month == 03)
                                  {
                                  $name = 'March';
                                  }
                                elseif($month == 04)
                                  {
                                  $name = 'April';
                                  }
                                elseif($month == 05)
                                  {
                                  $name = 'May';
                                  }
                                elseif($month == 06)
                                  {
                                  $name = 'June';
                                  }
                                elseif($month == 07)
                                  {
                                  $name = 'July';
                                  }
                                elseif($month == 8)
                                  {
                                  $name = 'August';
                                  }
                                elseif($month == 9)
                                  {
                                  $name = 'September';
                                  }
                                elseif($month == 10)
                                  {
                                  $name = 'October';
                                  }
                                elseif($month == 11)
                                  {
                                  $name = 'November';
                                  }
                                else
                                  {
                                  $name = 'December';
                                  }
                                ?>
                                <?php echo $name.' '.$value->year; ?>
                            </td>
                            <td><?php echo number_format(($value->salary), 2); $tsa += $value->salary; ?></td>
                            <td><?php echo $value->attday; ?></td> 
                            <td><?php echo number_format(($value->hrAmount), 2); $tda += $value->hrAmount; ?></td>
                            <!--<td><?php echo number_format(($value->lbAmount), 2); $tpa += $value->lbAmount; ?></td>-->
                            <!--<td><?php echo number_format(($value->pfAmount), 2); $tdu += $value->pfAmount; ?></td>-->
                            <td></td>
                            <td></td>
                            <td><?php echo number_format(($value->nAmount), 2); ?></td>
                            <td><?php echo number_format(($value->pAmount), 2); ?></td> 
                            <td><?php echo number_format(($value->dAmount), 2); ?></td>
                          </tr>   
                          <?php } ?> 
                        </tbody>
                      </table>
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
        $('#monthly').click(function(){
          $('#mreports').removeAttr('class','d-none');
          $('#yreports').attr('class','d-none');
          $('#reports').attr('class','d-none');
          
          $('#month').attr('required','required');
          $('#year').attr('required','required');
          $('#mcustomer').attr('required','required');
          
          $('#ryear').removeAttr('required','required');
          $('#ycustomer').removeAttr('required','required');

          $('#customer').removeAttr('required','required');
          });

        $('#yearly').click(function(){
          $('#yreports').removeAttr('class','d-none');
          $('#mreports').attr('class','d-none');
          $('#reports').attr('class','d-none');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          $('#mcustomer').removeAttr('required','required');
          
          $('#ryear').attr('required','required');
          $('#ycustomer').attr('required','required');

          $('#customer').removeAttr('required','required');
          });

        $('#ocust').click(function(){
          $('#yreports').attr('class','d-none');
          $('#mreports').attr('class','d-none');
          $('#reports').removeAttr('class','d-none');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          $('#mcustomer').removeAttr('required','required');
          
          $('#ryear').removeAttr('required','required');
          $('#ycustomer').removeAttr('required','required');

          $('#customer').attr('required','required');
          });
        });
    </script> 
    
    <script type="text/javascript">
      $(function(){
        $("#exam22ple").DataTable({
          "order": [[ 1, "asc" ]]
          });
        });
    </script>