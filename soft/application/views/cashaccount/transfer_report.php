<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>
  
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Transfer Report</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Transfer Report</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <?php
    $exception = $this->session->userdata('exception');
    if(isset($exception))
    {
    echo $exception;
    $this->session->unset_userdata('exception');
    } ?>

    <section class="content">
      <div class="container-fluid">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Balance Transfer Report</h3>
            </div>

            <div class="card-body">
              <div class="col-sm-12 col-md-12 col-12">
                  <form action="<?php echo base_url() ?>transReport" method="get">
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
                          <div class="form-group col-md-2 col-sm-2 col-12">
                            <label>Start Date *</label>
                            <input type="text" class="form-control datepicker" name="sdate" value="<?php echo date('m/d/Y') ?>" id="sdate" required="" >
                          </div>
                          <div class="form-group col-md-2 col-sm-2 col-12">
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
                          <div class="form-group col-md-2 col-sm-2 col-12">
                            <label>Month *</label>
                            <select class="form-control" name="month" id="month" required="" >
                              <option value="">Select Month</option>
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
                          <div class="form-group col-md-2 col-sm-2 col-xs-12">
                            <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" aria-hidden="true"></i>&nbsp;Search</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
                
              <div class="col-sm-12 col-md-12 col-12">
                <table id="example" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 5%;">#SN.</th>
                      <th style="width: 12%;">Date</th>
                      <th>1st Account</th>
                      <th>2nd Account</th>
                      <th>Transfer Amount</th>
                      <th>Note</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 0;
                    foreach ($cash as $value){
                    $i++;
                    $ac = $value->facType;

                    if($ac == 'Bank')
                      {
                      $where = array(
                        'ba_id' => $value->facAcno
                            );
                      $account = $this->pm->get_data('bankaccount',$where);
                      if(count($account) == 0)
                        {
                        $str = "N/A";
                        }
                      else
                        {
                        $str = $account[0]['bankName'].' '.$account[0]['branchName'].' '.$account[0]['accountNo'].' '.$account[0]['accountName'];
                        }
                      }
                    else if($ac == 'Mobile')
                      {
                      $where = array(
                        'ma_id' => $value->facAcno
                            );

                      $account = $this->pm->get_data('mobileaccount',$where);
                      if(count($account) == 0)
                        {
                        $str = "N/A";
                        }
                      else
                        {
                        $str = $account[0]['accountName'].' '.$account[0]['accountNo'];
                        }
                      }
                    else if($ac == 'Cash')
                      {
                      $where = array(
                        'ca_id' => $value->facAcno
                            );

                      $account = $this->pm->get_data('cash',$where);
                      if(count($account) == 0)
                        {
                        $str = "N/A";
                        }
                      else
                        {
                        $str = $account[0]['cashName'];
                        }
                      }

                    $a2c = $value->sacType;

                    if($a2c == 'Bank')
                      {
                      $where = array(
                        'ba_id' => $value->facAcno
                            );
                      $account = $this->pm->get_data('bankaccount',$where);
                      if(count($account) == 0)
                        {
                        $s2tr = "N/A";
                        }
                      else
                        {
                        $s2tr = $account[0]['bankName'].' '.$account[0]['branchName'].' '.$account[0]['accountNo'].' '.$account[0]['accountName'];
                        }
                      }
                    else if($a2c == 'Mobile')
                      {
                      $where = array(
                        'ma_id' => $value->facAcno
                            );

                      $account = $this->pm->get_data('mobileaccount',$where);
                      if(count($account) == 0)
                        {
                        $s2tr = "N/A";
                        }
                      else
                        {
                        $s2tr = $account[0]['accountName'].' '.$account[0]['accountNo'];
                        }
                      }
                    else if($a2c == 'Cash')
                      {
                      $where = array(
                        'ca_id' => $value->facAcno
                            );

                      $account = $this->pm->get_data('cash',$where);
                      if(count($account) == 0)
                        {
                        $s2tr = "N/A";
                        }
                      else
                        {
                        $s2tr = $account[0]['cashName'];
                        }
                      }
                    ?>
                    <tr class="gradeX">
                      <td><?php echo $i; ?></td>
                      <td><?php echo date('d-m-Y',strtotime($value->regdate)) ?></td>
                      <td><?php echo $value->facType.' :- '.$str; ?></td>
                      <td><?php echo $value->sacType.' :- '.$s2tr; ?></td>
                      <td><?php echo number_format($value->amount, 2); ?></td>
                      <td><?php echo $value->note; ?></td>
                    </tr>   
                    <?php } ?> 
                  </tbody>
                </table>
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