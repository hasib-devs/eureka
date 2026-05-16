<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sale Purchase Reports</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Sale Purchase</li>
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
                <h3 class="card-title">Sale Purchase Reports</h3>
              </div>

              <div class="card-body">
                <div class="col-sm-12 col-md-12 col-12">
                  <form action="<?php echo base_url() ?>spReports" method="get">
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
                  <div id="print">
                    <div class="col-sm-12 col-md-12 col-12">
                      <?php if(isset($_GET['search'])) { ?>
                        <?php if ($report == 'dailyReports') { ?>
                          <div class="box-header" style="text-align: center;">
                            <h3 class="box-title"><b>Sale Purchase Reports in : <?php echo $sdate.' - '.$edate; ?></b></h3>
                          </div>
                        <?php } else if ($report == 'monthlyReports') { ?>
                          <div class="box-header" style="text-align: center;">
                            <h3 class="box-title"><b>Sale Purchase Reports in : <?php echo $name.' '.$year; ?></b></h3>
                          </div>
                        <?php } else if ($report == 'yearlyReports') { ?>
                          <div class="box-header" style="text-align: center;">
                            <h3 class="box-title"><b>Sale Purchase Reports in : <?php echo $year; ?></b></h3>
                          </div>
                        <?php } ?>
                      <?php } ?>
                      <table id="example" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th style="width: 5%;">#SN.</th>
                            <th>Invoice</th>
                            <th>Purchase Type</th>
                            <th>Part No.</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Sales</th>
                            <th>Puschases</th>
                            <th style="width: 10%;">Profit</th>
                            <th style="width: 10%;">Profit %</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $i = 0;
                          $tqnt = 0;
                          $tsa = 0;
                          $tpa = 0;
                          $tna = 0;
                          foreach ($salep as $value){
                          $i++;
                            
                          $product = $this->db->select('pName,pCode,pprice,partNo')
                                            ->from('products')
                                            ->where('pid',$value->pid)
                                            ->get()
                                            ->row();

                          $price = $this->db->select('tamount as tamount')
                                        ->from('costing')
                                        ->where('pid',$value->pid)
                                        ->get()
                                        ->row();
                                        
                          $pp = $this->db->select('SUM(tprice) as tpa,SUM(quantity) as tpq')
                                        ->from('purchase_product')
                                        ->where('pid',$value->pid)
                                        //->where('partNo',$value->spChassis)
                                        //->where('ppEngine',$value->spColor)
                                        //->where('tpQnt >',$value->tsQnt)
                                        ->get()
                                        ->row();
                          if($pp->tpq > 0)
                            {
                            $apprice = round($pp->tpa/$pp->tpq);
                            if($apprice < $value->sprice)
                              {
                              $p2price = $apprice;
                              }
                            else
                              {
                              $p2price = 0;
                              }
                            }
                          else
                            {
                            $p2price = 0;
                            }
                              
                          if($p2price > 0 && $value->spType == 2)
                            {
                            $pprice = $p2price;
                            }
                          else if($price && $value->spType == 1)
                            {
                            $pprice = $price->tamount;
                            }
                          else if($product)
                            {
                            $pprice = $product->pprice;
                            }
                          else
                            {
                            $pprice = 0;
                            }
                          ?>
                          <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $value->invoice; ?></td>
                            <td><?php if($value->spType == 1){echo 'Costing';}else{ echo 'Local Purchase';} ?></td>
                            <td><?php echo $product->partNo; ?></td>
                            <td><?php echo $product->pName; ?></td>
                            <td><?php echo number_format($value->tq, 2); $tqnt += $value->tq; ?></td>
                            <td><?php echo number_format($value->ta, 2); $tsa += $value->ta; ?></td>
                            <td><?php echo number_format(($value->tq*$pprice), 2); $tpa += ($value->tq*$pprice); ?></td>
                            <td><?php echo number_format(($value->ta-($value->tq*$pprice)), 2); $tna += ($value->ta-($value->tq*$pprice)); ?></td>
                            <td><?php if($pprice > 0){echo number_format(((($value->ta-($value->tq*$pprice))*100)/($value->tq*$pprice)), 2).' %';}else{ echo '0 %';} ?></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                        <tfoot>
                          <tr>
                            <th colspan="5" style="text-align: right;">Grand Total</th>
                            <th><?php echo $tqnt; ?></th>
                            <th><?php echo number_format($tsa, 2); ?></th>
                            <th><?php echo number_format($tpa, 2); ?></th>
                            <th><?php echo number_format($tna, 2); ?></th>
                            <th></th>
                          </tr>
                        </tfoot>
                      </table>
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
          $('#dvtype').attr('required','required');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          $('#mvtype').removeAttr('required','required');
          
          $('#ryear').removeAttr('required','required');
          $('#yvtype').removeAttr('required','required');
          });

        $('#monthly').click(function(){
          $('#mreports').removeAttr('class','d-none');
          $('#dreports').attr('class','d-none');
          $('#yreports').attr('class','d-none');

          $('#sdate').removeAttr('required','required');
          $('#edate').removeAttr('required','required');
          $('#dvtype').removeAttr('required','required');
          
          $('#month').attr('required','required');
          $('#year').attr('required','required');
          $('#mvtype').attr('required','required');
          
          $('#ryear').removeAttr('required','required');
          $('#yvtype').removeAttr('required','required');
          });

        $('#yearly').click(function(){
          $('#yreports').removeAttr('class','d-none');
          $('#dreports').attr('class','d-none');
          $('#mreports').attr('class','d-none');

          $('#sdate').removeAttr('required','required');
          $('#edate').removeAttr('required','required');
          $('#dvtype').removeAttr('required','required');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          $('#mvtype').removeAttr('required','required');
          
          $('#ryear').attr('required','required');
          $('#yvtype').attr('required','required');
          });
        });
    </script>