<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Product Reports</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Product Reports</li>
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
        <div class="row">
          <div class="col-md-12 col-sm-12 col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Product Ledger</h3>
              </div>

              <div class="card-body" >
                <div class="col-sm-12 col-md-12 col-12">
                  <form action="<?php echo base_url() ?>stockLedger" method="get">
                    <div class="col-md-12 col-sm-12 col-12">
                      <div class="form-group">
                        <b>
                          <input type="radio" name="reports" value="ocust" id="ocust" required >&nbsp;&nbsp;All Ledger&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="reports" value="dailyReports" id="daily" required >&nbsp;&nbsp;Daily Ledger&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="reports" value="monthlyReports" id="monthly" required >&nbsp;&nbsp;Monthly Ledger&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="reports" value="yearlyReports" id="yearly" required >&nbsp;&nbsp;Yearly Ledger
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
                            <label>Select Product *</label>
                            <select class="form-control select2" name="dproduct" id="dproduct" required="" style="width: 100%;" >
                              <option value="">Select One</option>
                              <?php foreach($product as $value){ ?>
                              <option value="<?php echo $value['pid']; ?>" ><?php echo $value['pName'].' ( '.$value['partNo'].' )'; ?></option>
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
                            <label>Select Product *</label>
                            <select class="form-control select2" name="mproduct" id="mproduct" required="" style="width: 100%;" >
                              <option value="">Select One</option>
                              <?php foreach($product as $value){ ?>
                              <option value="<?php echo $value['pid']; ?>" ><?php echo $value['pName'].' ( '.$value['partNo'].' )'; ?></option>
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
                            <label>Select Product *</label>
                            <select class="form-control select2" name="yproduct" id="yproduct" required="" style="width: 100%;" >
                              <option value="">Select One</option>
                              <?php foreach($product as $value){ ?>
                              <option value="<?php echo $value['pid']; ?>" ><?php echo $value['pName'].' ( '.$value['partNo'].' )'; ?></option>
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
                            <label>Select Product *</label>
                            <select class="form-control select2" name="aproduct" id="aproduct" required="" style="width: 100%;" >
                              <option value="">Select One</option>
                              <?php foreach($product as $value){ ?>
                              <option value="<?php echo $value['pid']; ?>" ><?php echo $value['pName'].' ( '.$value['partNo'].' )'; ?></option>
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
                
                <div class="col-sm-12 col-md-12 col-12">
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
                    <div class="col-sm-12 col-md-12 col-12">
                      <div class="col-sm-12 col-md-12 col-12">
                        <b>Part No.&nbsp;&nbsp;:&nbsp;&nbsp;</b><?php echo $spdetails[0]['partNo']; ?>
                      </div>
                      <div class="col-sm-12 col-md-12 col-12">
                        <b>Product Name&nbsp;&nbsp;:&nbsp;&nbsp;</b><?php echo $spdetails[0]['pName']; ?>
                      </div>
                    </div>
                    <?php if ($report == 'dailyReports') { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Product Ledger Reports in : <?php echo $sdate.' - '.$edate; ?></b></h3>
                    </div>
                    <?php } else if ($report == 'monthlyReports') { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Product Ledger Reports in : <?php echo $name.' '.$year; ?></b></h3>
                    </div>
                    <?php } else if ($report == 'yearlyReports') { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Product Ledger Reports in : <?php echo $year; ?></b></h3>
                    </div>
                    <?php } ?>
                  
                    <div class="col-sm-12 col-md-12 col-12">
                      <table id="" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>#SN.</th>
                            <th>Date</th>
                            <th>Invoice No.</th>
                            <th>Particulars</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if($pproduct){ ?>

                          <?php
                          $i = 0;
                          foreach($pproduct as $value){
                          $i++;
                          ?>
                          <tr class="gradeX">
                            <td><?php echo $i; ?></td>
                            <td><?php echo date('d-m-Y',strtotime($value->puDate)); ?></td>
                            <td><?php echo $value->challanNo; ?></td>
                            <td><?php echo 'Prurchase'; ?></td>
                            <td><?php echo $value->quantity; ?></td> 
                            <td><?php echo number_format($value->pprice, 2); ?></td> 
                            <td><?php echo number_format($value->tprice, 2); ?></td>
                          </tr>   
                          <?php } ?> 
                          <?php } else{ ?>
                          <?php $i = 0; ?>
                          <?php } ?>

                          <?php if($sproduct){ ?>

                          <?php
                          $j = $i;
                          foreach($sproduct as $value){
                          $j++;
                          ?>
                          <tr class="gradeX">
                            <td><?php echo $j; ?></td>
                            <td><?php echo date('d-m-Y',strtotime($value->saDate)); ?></td>
                            <td><?php echo $value->invoice; ?></td>
                            <td><?php echo 'Sales'; ?></td>
                            <td><?php echo $value->quantity; ?></td> 
                            <td><?php echo number_format($value->sprice, 2); ?></td> 
                            <td><?php echo number_format($value->tprice, 2); ?></td>
                          </tr>   
                          <?php } ?>
                          <?php } else{ ?>
                          <?php $j = $i or $j = 0; ?>
                          <?php } ?> 

                          <?php if($prproduct){ ?>

                          <?php
                          $k = $j;
                          foreach($prproduct as $value){
                          $k++;
                          ?>
                          <tr class="gradeX">
                            <td><?php echo $k; ?></td>
                            <td><?php echo date('d-m-Y',strtotime($value->prDate)); ?></td>
                            <td><?php echo $value->prCode; ?></td>
                            <td><?php echo 'Purchase Returns'; ?></td>
                            <td><?php echo $value->quantity; ?></td> 
                            <td><?php echo number_format($value->pPrice, 2); ?></td> 
                            <td><?php echo number_format($value->tPrice, 2); ?></td>
                          </tr>   
                          <?php } ?>
                          <?php } else{ ?>
                          <?php $k = $j or $k = $i or $k = 0; ?>
                          <?php } ?> 
                          
                          <?php if($srproduct){ ?>

                          <?php
                          $l = $k;
                          foreach($srproduct as $value){
                          $l++;
                          ?>
                          <tr class="gradeX">
                            <td><?php echo $l; ?></td>
                            <td><?php echo date('d-m-Y',strtotime($value->rDate)); ?></td>
                            <td><?php echo $value->rCode; ?></td>
                            <td><?php echo 'Sales Returns'; ?></td>
                            <td><?php echo $value->quantity; ?></td> 
                            <td><?php echo number_format($value->sprice, 2); ?></td> 
                            <td><?php echo number_format($value->tprice, 2); ?></td>
                          </tr>   
                          <?php } ?>
                          <?php } else{ ?>
                          <?php $l = $k or $l = $j or $l = $i or $l = 0; ?>
                          <?php } ?> 
                          
                          <?php if($mrproduct){ ?>

                          <?php
                          $m = $l;
                          foreach($mrproduct as $value){
                          $m++;
                          ?>
                          <tr class="gradeX">
                            <td><?php echo $m; ?></td>
                            <td><?php echo date('d-m-Y',strtotime($value->regdate)); ?></td>
                            <td><?php echo $value->note; ?></td>
                            <td><?php echo 'Costing Product'; ?></td>
                            <td><?php echo $value->stock; ?></td> 
                            <td><?php echo number_format($value->sprice, 2); ?></td> 
                            <td><?php echo number_format($value->stock*$value->sprice, 2); ?></td>
                          </tr>   
                          <?php } ?>
                          <?php } ?>
                        </tbody>
                      </table>
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
          $('#dproduct').attr('required','required');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          $('#mproduct').removeAttr('required','required');
          
          $('#ryear').removeAttr('required','required');
          $('#yproduct').removeAttr('required','required');

          $('#aproduct').removeAttr('required','required');
          });

        $('#monthly').click(function(){
          $('#mreports').removeAttr('class','d-none');
          $('#dreports').attr('class','d-none');
          $('#yreports').attr('class','d-none');
          $('#reports').attr('class','d-none');

          $('#sdate').removeAttr('required','required');
          $('#edate').removeAttr('required','required');
          $('#dproduct').removeAttr('required','required');
          
          $('#month').attr('required','required');
          $('#year').attr('required','required');
          $('#mproduct').attr('required','required');
          
          $('#ryear').removeAttr('required','required');
          $('#yproduct').removeAttr('required','required');

          $('#aproduct').removeAttr('required','required');
          });

        $('#yearly').click(function(){
          $('#yreports').removeAttr('class','d-none');
          $('#dreports').attr('class','d-none');
          $('#mreports').attr('class','d-none');
          $('#reports').attr('class','d-none');

          $('#sdate').removeAttr('required','required');
          $('#edate').removeAttr('required','required');
          $('#dproduct').removeAttr('required','required');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          $('#mproduct').removeAttr('required','required');
          
          $('#ryear').attr('required','required');
          $('#yproduct').attr('required','required');

          $('#aproduct').removeAttr('required','required');
          });

        $('#ocust').click(function(){
          $('#yreports').attr('class','d-none');
          $('#dreports').attr('class','d-none');
          $('#mreports').attr('class','d-none');
          $('#reports').removeAttr('class','d-none');

          $('#sdate').removeAttr('required','required');
          $('#edate').removeAttr('required','required');
          $('#dproduct').removeAttr('required','required');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          $('#mproduct').removeAttr('required','required');
          
          $('#ryear').removeAttr('required','required');
          $('#yproduct').removeAttr('required','required');

          $('#aproduct').attr('required','required');
          });
        });
    </script> 
