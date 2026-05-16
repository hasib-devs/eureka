<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Income Statement</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Income Statement</li>
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
                <h3 class="card-title p-3">Income Statement</h3>
              </div>

              <div class="card-body">
                <div class="col-sm-12 col-md-12 col-12">
                  <form action="<?php echo base_url() ?>incomeStatement" method="get">
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
                            <input type="text" class="form-control datepicker" name="sdate" value="<?php echo date('m/d/Y') ?>" id="sdate" required="">
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-12">
                            <label>End Date *</label>
                            <input type="text" class="form-control datepicker" name="edate" value="<?php echo date('m/d/Y') ?>" id="edate" required="">
                          </div>
                          <div class="form-group col-md-2 col-sm-2 col-12">
                            <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus"></i>&nbsp;Search</button>
                          </div>
                        </div>
                      </div>

                      <div class="d-none" id="mreports">
                        <div class="row">
                          <div class="form-group col-md-3 col-sm-3 col-12">
                            <label>Select Month *</label>
                            <select class="form-control" name="month" id="month" required="">
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
                            <select class="form-control" name="year" id="year" required="">
                              <?php $d = date("Y"); ?>
                              <option value="">Select One</option>
                              <?php for ($x = 2020; $x <= $d; $x++) { ?>
                              <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="form-group col-md-2 col-sm-2 col-12">
                            <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus"></i>&nbsp;Search</button>
                          </div>
                        </div>
                      </div>

                      <div class="d-none" id="yreports">
                        <div class="row">
                          <div class="form-group col-md-3 col-sm-3 col-12">
                            <label>Select Year *</label>
                            <select class="form-control" name="ryear" id="ryear" required="">
                              <?php $d = date("Y"); ?>
                              <option value="">Select One</option>
                              <?php for ($x = 2020; $x <= $d; $x++) { ?>
                              <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="form-group col-md-2 col-sm-2 col-12">
                            <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus"></i>&nbsp;Search</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div><hr>

                <div class="box-body">
                  <div id="print">
                    <div class="row" id="header" style="display: none">
                      <div class="col-sm-12 col-md-12 col-12" style="text-align:center;">
                        <div class="col-sm-12 col-md-12 col-12">
                          <h3><b><?php echo $company->com_name; ?></b></h3>
                        </div>
                        <div class="col-sm-12 col-md-12 col-12">
                          <b><?php echo $company->com_address; ?></b>
                        </div>
                        <div class="col-sm-12 col-md-12 col-12">
                          <h2><b>Income Statement</b></h2>
                        </div>
                      </div>
                    </div>

                    <?php if(isset($_GET['search'])) { ?>
                    <?php if ($report == 'dailyReports'){ ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Income Statement Reports in : <?php echo $sdate.' - '.$edate; ?></b></h3>
                    </div>
                    <?php } else if ($report == 'monthlyReports') { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Income Statement Reports in : <?php echo $name.' '.$year; ?></b></h3>
                    </div>
                    <?php } else if ($report == 'yearlyReports') { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Income Statement Reports in : <?php echo $year; ?></b></h3>
                    </div>
                    <?php } else { ?>
                    <div class="box-header" style="text-align: center;">
                      <h3 class="box-title"><b>Income Statement Reports</b></h3>
                    </div>
                    <?php } } ?><br>
                    <?php $gProfit = 0; ?>
                    <div id="table-content">
                      <table id="" class="table table-striped">
                        <thead>
                          <tr>
                            <th>Description</th>
                            <th>Amount</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="gradeX">
                            <td><?php echo 'Net Sale / Total Sale'; ?></td>
                            <td><?php echo number_format(($sale->total), 2); ?></td>
                          </tr>
                          <tr class="gradeX">
                            <td><?php echo 'Cost of Sale / Product Purchase Price'; ?></td>
                            <td><?php echo number_format(($purchase->total), 2); ?></td>
                          </tr>
                          <tr class="gradeX">
                            <td><?php echo 'Closing Stock'; ?></td>
                            <?php
                            $total=0;
                            foreach($closing_inv as $inv){
                              if(isset($_GET['search']))
                                {
                              if($report == 'dailyReports')
                                {
                                $puchase = $this->db->select("SUM(quantity) as total")
                                                  ->FROM('purchase_product')
                                                  ->where('pid',$inv->pid)
                                                  ->where('DATE(regdate) >=', $sdate)
                                                  ->where('DATE(regdate) <=', $edate)
                                                  ->get()
                                                  ->row();
                                if($puchase)
                                  {
                                  $tpqnt = $puchase->total;
                                  }
                                else
                                  {
                                  $tpqnt = 0;
                                  }
                                
                                $sale = $this->db->select("SUM(quantity) as total")
                                                  ->FROM('sale_product')
                                                  ->where('pid',$inv->pid)
                                                  ->where('DATE(regdate) >=', $sdate)
                                                  ->where('DATE(regdate) <=', $edate)
                                                  ->get()
                                                  ->row();
                                if($sale)
                                  {
                                  $tsqnt = $sale->total;
                                  }
                                else
                                  {
                                  $tsqnt = 0;
                                  }
                                
                                $return = $this->db->select("SUM(quantity) as total")
                                                  ->FROM('returns_product')
                                                  ->where('pid',$inv->pid)
                                                  ->where('DATE(regdate) >=', $sdate)
                                                  ->where('DATE(regdate) <=', $edate)
                                                  ->get()
                                                  ->row();
                                if($return)
                                  {
                                  $trqnt = $return->total;
                                  }
                                else
                                  {
                                  $trqnt = 0;
                                  }
                                
                                $preturn = $this->db->select("SUM(quantity) as total")
                                                  ->FROM('preturns_product')
                                                  ->where('pid',$inv->pid)
                                                  ->where('DATE(regdate) >=', $sdate)
                                                  ->where('DATE(regdate) <=', $edate)
                                                  ->get()
                                                  ->row();
                                if($preturn)
                                  {
                                  $tprqnt = $preturn->total;
                                  }
                                else
                                  {
                                  $tprqnt = 0;
                                  }
                                
                                $tqnt = ($tpqnt+$trqnt)-($tsqnt+$tprqnt);
                                $total += $tqnt*$inv->tpprice;
                                }
                              else if ($report == 'monthlyReports')
                                {
                                $puchase = $this->db->select("SUM(quantity) as total")
                                                  ->FROM('purchase_product')
                                                  ->where('pid',$inv->pid)
                                                  ->where('MONTH(regdate)',$month)
                                                  ->where('YEAR(regdate)',$year)
                                                  ->get()
                                                  ->row();
                                if($puchase)
                                  {
                                  $tpqnt = $puchase->total;
                                  }
                                else
                                  {
                                  $tpqnt = 0;
                                  }
                                
                                $sale = $this->db->select("SUM(quantity) as total")
                                                  ->FROM('sale_product')
                                                  ->where('pid',$inv->pid)
                                                  ->where('MONTH(regdate)',$month)
                                                  ->where('YEAR(regdate)',$year)
                                                  ->get()
                                                  ->row();
                                if($sale)
                                  {
                                  $tsqnt = $sale->total;
                                  }
                                else
                                  {
                                  $tsqnt = 0;
                                  }
                                
                                $return = $this->db->select("SUM(quantity) as total")
                                                  ->FROM('returns_product')
                                                  ->where('pid',$inv->pid)
                                                  ->where('MONTH(regdate)',$month)
                                                  ->where('YEAR(regdate)',$year)
                                                  ->get()
                                                  ->row();
                                if($return)
                                  {
                                  $trqnt = $return->total;
                                  }
                                else
                                  {
                                  $trqnt = 0;
                                  }
                                
                                $preturn = $this->db->select("SUM(quantity) as total")
                                                  ->FROM('preturns_product')
                                                  ->where('pid',$inv->pid)
                                                  ->where('MONTH(regdate)',$month)
                                                  ->where('YEAR(regdate)',$year)
                                                  ->get()
                                                  ->row();
                                if($preturn)
                                  {
                                  $tprqnt = $preturn->total;
                                  }
                                else
                                  {
                                  $tprqnt = 0;
                                  }
                                
                                $tqnt = ($tpqnt+$trqnt)-($tsqnt+$tprqnt);
                                $total += $tqnt*$inv->tpprice;
                                }
                              else if ($report == 'yearlyReports')
                                {
                                $puchase = $this->db->select("SUM(quantity) as total")
                                                  ->FROM('purchase_product')
                                                  ->where('pid',$inv->pid)
                                                  ->where('YEAR(regdate)',$year)
                                                  ->get()
                                                  ->row();
                                if($puchase)
                                  {
                                  $tpqnt = $puchase->total;
                                  }
                                else
                                  {
                                  $tpqnt = 0;
                                  }
                                
                                $sale = $this->db->select("SUM(quantity) as total")
                                                  ->FROM('sale_product')
                                                  ->where('pid',$inv->pid)
                                                  ->where('YEAR(regdate)',$year)
                                                  ->get()
                                                  ->row();
                                if($sale)
                                  {
                                  $tsqnt = $sale->total;
                                  }
                                else
                                  {
                                  $tsqnt = 0;
                                  }
                                
                                $return = $this->db->select("SUM(quantity) as total")
                                                  ->FROM('returns_product')
                                                  ->where('pid',$inv->pid)
                                                  ->where('YEAR(regdate)',$year)
                                                  ->get()
                                                  ->row();
                                if($return)
                                  {
                                  $trqnt = $return->total;
                                  }
                                else
                                  {
                                  $trqnt = 0;
                                  }
                                
                                $preturn = $this->db->select("SUM(quantity) as total")
                                                  ->FROM('preturns_product')
                                                  ->where('pid',$inv->pid)
                                                  ->where('YEAR(regdate)',$year)
                                                  ->get()
                                                  ->row();
                                if($preturn)
                                  {
                                  $tprqnt = $preturn->total;
                                  }
                                else
                                  {
                                  $tprqnt = 0;
                                  }
                                
                                $tqnt = ($tpqnt+$trqnt)-($tsqnt+$tprqnt);
                                $total += $tqnt*$inv->tpprice;
                                }
                                }
                              else
                                {
                                $total += $inv->tquantity*$inv->tpprice;
                                }
                              }
                            ?>
                            <td><?php echo number_format(($total), 2); ?></td>
                          </tr>
                        </tbody>
                        <tbody>
                          <tr>
                            <th colspan="1" style="text-align: right;">Gross Profit</th>
                            <th>
                              <?php $gProfit = ($sale->total + $total) - $purchase->total; ?>
                              <?php echo number_format(($gProfit), 2); ?>
                            </th>
                          </tr>
                          <tr class="gradeX">
                            <td><?php echo 'Expense'; ?></td>
                            <td><?php echo number_format(($dvoucher->total), 2); ?></td>
                          </tr>
                          <tr>
                            <th style="text-align: right;">Net Profit</th>
                            <th><?php echo number_format(($gProfit - $dvoucher->total ), 2); ?></th>
                          </tr>
                        </tbody>
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