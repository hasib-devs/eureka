<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Stock Report</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Stock Report</li>
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
                <h3 class="card-title">Stock Report</h3>
                <!--<a class="btn btn-primary" href="<?php echo site_url('Product/export_action'); ?>" style="float: right" ><i class="far fa-file-excel"></i> Export Excel</a>-->
              </div>

              <div class="card-body">
                <div class="col-md-12 col-sm-12 col-12">
                  <form action="<?php echo base_url() ?>stockReport" method="get">
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
                            <input type="date" class="form-control datepicker" name="sdate" value="<?php echo date('m/d/Y') ?>" id="sdate" required="" >
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-12">
                            <label>End Date *</label>
                            <input type="date" class="form-control datepicker" name="edate" value="<?php echo date('m/d/Y') ?>" id="edate" required="" >
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-12">
                            <label>Select Category *</label>
                            <div>
                            <select class="form-control select2" name="dcategory" required="" id="dsupplier" style="width: 100%;" >
                              <option value="All">All Category</option>
                              <?php foreach($category as $row){ ?>
                              <option value="<?php echo $row['catid']; ?>"><?php echo $row['catName']; ?></option>
                              <?php } ?>
                            </select>
                            </div>
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-12">
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
                            <?php $d = date("Y"); ?>
                            <select class="form-control" name="year" id="year" required="" >
                              <option value="">Select One</option>
                              <?php for ($x = 2020; $x <= $d; $x++) { ?>
                              <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-12">
                            <label>Select Category *</label>
                            <div>
                            <select class="form-control select2" name="mcategory" required="" id="msupplier" style="width: 100%;" >
                              <option value="All">All Category</option>
                              <?php foreach($category as $row){ ?>
                              <option value="<?php echo $row['catid']; ?>"><?php echo $row['catName']; ?></option>
                              <?php } ?>
                            </select>
                            </div>
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-12">
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
                            <label>Select Category *</label>
                            <div>
                            <select class="form-control select2" name="ycategory" required="" id="ysupplier" style="width: 100%;" >
                              <option value="All">All Category</option>
                              <?php foreach($category as $row){ ?>
                              <option value="<?php echo $row['catid']; ?>"><?php echo $row['catName']; ?></option>
                              <?php } ?>
                            </select>
                            </div>
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-12">
                            <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>
                          </div>
                        </div>
                      </div>

                    </div>
                  </form>
                </div>
                
                <div id="print">
                  <?php if(isset($_GET['search'])) { ?>
                  <?php if ($report == 'dailyReports') { ?>
                  <div class="box-header" style="text-align: center;">
                    <h3 class="box-title"><b>Products Stock Reports in : <?php echo $sdate.' - '.$edate; ?></b></h3>
                  </div>
                  <?php } else if ($report == 'monthlyReports') { ?>
                  <div class="box-header" style="text-align: center;">
                    <h3 class="box-title"><b>Products Stock Reports in : <?php echo $name.' '.$year; ?></b></h3>
                  </div>
                  <?php } else if ($report == 'yearlyReports') { ?>
                  <div class="box-header" style="text-align: center;">
                    <h3 class="box-title"><b>Products Stock Reports in : <?php echo $year; ?></b></h3>
                  </div>
                  <?php } ?>
                  <?php } ?>    
                  <table id="example1" class="table table-striped table-bordered" >
                    <thead>
                      <tr>
                        <th style="width: 5%;">#SN.</th>
                        <th>Name</th>
                        <th>HS Code</th>
                        <th>Part No.</th>
                        <th>INR Price</th>
                        <th>Sale</th>
                        <th>Purchase</th>
                        <th>Store QNT.</th>
                        <th>Costing QNT.</th>
                        <th>Purchase QNT.</th>
                        <th>Sale QNT.</th>
                        <th>Stock</th>
                        <th>S-Value</th>
                        <th style="width: 10%;">P-Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $i = 0;
                      $tspiq = 0;
                      $tcpiq = 0;
                      $tpiq = 0;
                      $tpoq = 0;
                      $tpsq = 0;
                      $tpsp = 0;
                      $tppp = 0;
                      $tdpsq = 0;
                      foreach ($product as $value){
                      $i++;
                      $pid = $value['pid'];
                      if(isset($_GET['search']))
                        {
                        $report = $_GET['reports'];
                        if($report == 'dailyReports')
                          {
                          $sp = $this->db->select("SUM(quantity) as tpq")
                                    ->from('store_product')
                                    ->where('pid',$pid)
                                    ->where('DATE(regdate) >=',$sdate)
                                    ->where('DATE(regdate) <=',$edate)
                                    ->get()
                                    ->row();
                                    
                          $pp = $this->db->select("SUM(quantity) as tpq")
                                    ->from('purchase_product')
                                    ->join('purchase', 'purchase.puid = purchase_product.puid', 'left')
                                    ->where('pid',$pid)
                                    ->where('DATE(puDate) >=',$sdate)
                                    ->where('DATE(puDate) <=',$edate)
                                    ->get()
                                    ->row();

                          $spp = $this->db->select("SUM(quantity) as tsq")
                                    ->from('sale_product')
                                    ->join('sales', 'sales.said = sale_product.said', 'left')
                                    ->where('pid',$pid)
                                    ->where('DATE(saDate) >=',$sdate)
                                    ->where('DATE(saDate) <=',$edate)
                                    ->get()
                                    ->row();
                      
                          $rp = $this->db->select("SUM(quantity) as trq")
                                    ->from('returns_product')
                                    ->join('returns', 'returns.rid = returns_product.rid', 'left')
                                    ->where('pid',$pid)
                                    ->where('DATE(rDate) >=',$sdate)
                                    ->where('DATE(rDate) <=',$edate)
                                    ->get()
                                    ->row();
                      
                          $rpp = $this->db->select("SUM(quantity) as trq")
                                    ->from('preturns_product')
                                    ->join('preturns', 'preturns.prid = preturns_product.prid', 'left')
                                    ->where('pid',$pid)
                                    ->where('DATE(prDate) >=',$sdate)
                                    ->where('DATE(prDate) <=',$edate)
                                    ->get()
                                    ->row();
                                    
                          $cpp = $this->db->select("SUM(stock) as trq")
                                    ->from('costing')
                                    ->where('pid',$pid)
                                    ->where('DATE(regdate) >=',$sdate)
                                    ->where('DATE(regdate) <=',$edate)
                                    ->get()
                                    ->row();
                          }
                        else if($report == 'monthlyReports')
                          {
                          $sp = $this->db->select("SUM(quantity) as tpq")
                                    ->from('store_product')
                                    ->where('pid',$pid)
                                    ->where('MONTH(regdate)',$month)
                                    ->where('YEAR(regdate)',$year)
                                    ->get()
                                    ->row();
                                    
                          $pp = $this->db->select("SUM(quantity) as tpq")
                                    ->from('purchase_product')
                                    ->join('purchase', 'purchase.puid = purchase_product.puid', 'left')
                                    ->where('pid',$pid)
                                    ->where('MONTH(puDate)',$month)
                                    ->where('YEAR(puDate)',$year)
                                    ->get()
                                    ->row();

                          $spp = $this->db->select("SUM(quantity) as tsq")
                                    ->from('sale_product')
                                    ->join('sales', 'sales.said = sale_product.said', 'left')
                                    ->where('pid',$pid)
                                    ->where('MONTH(saDate)',$month)
                                    ->where('YEAR(saDate)',$year)
                                    ->get()
                                    ->row();
                      
                          $rp = $this->db->select("SUM(quantity) as trq")
                                    ->from('returns_product')
                                    ->join('returns', 'returns.rid = returns_product.rid', 'left')
                                    ->where('pid',$pid)
                                    ->where('MONTH(rDate)',$month)
                                    ->where('YEAR(rDate)',$year)
                                    ->get()
                                    ->row();
                      
                          $rpp = $this->db->select("SUM(quantity) as trq")
                                    ->from('preturns_product')
                                    ->join('preturns', 'preturns.prid = preturns_product.prid', 'left')
                                    ->where('pid',$pid)
                                    ->where('MONTH(prDate)',$month)
                                    ->where('YEAR(prDate)',$year)
                                    ->get()
                                    ->row();
                                    
                          $cpp = $this->db->select("SUM(stock) as trq")
                                    ->from('costing')
                                    ->where('pid',$pid)
                                    ->where('MONTH(regdate)',$month)
                                    ->where('YEAR(regdate)',$year)
                                    ->get()
                                    ->row();
                          }
                        else if($report == 'yearlyReports')
                          {
                          $sp = $this->db->select("SUM(quantity) as tpq")
                                    ->from('store_product')
                                    ->where('pid',$pid)
                                    ->where('YEAR(regdate)',$year)
                                    ->get()
                                    ->row();
                                    
                          $pp = $this->db->select("SUM(quantity) as tpq")
                                    ->from('purchase_product')
                                    ->join('purchase', 'purchase.puid = purchase_product.puid', 'left')
                                    ->where('pid',$pid)
                                    ->where('YEAR(puDate)',$year)
                                    ->get()
                                    ->row();

                          $spp = $this->db->select("SUM(quantity) as tsq")
                                    ->from('sale_product')
                                    ->join('sales', 'sales.said = sale_product.said', 'left')
                                    ->where('pid',$pid)
                                    ->where('YEAR(saDate)',$year)
                                    ->get()
                                    ->row();
                      
                          $rp = $this->db->select("SUM(quantity) as trq")
                                    ->from('returns_product')
                                    ->join('returns', 'returns.rid = returns_product.rid', 'left')
                                    ->where('pid',$pid)
                                    ->where('YEAR(rDate)',$year)
                                    ->get()
                                    ->row();
                      
                          $rpp = $this->db->select("SUM(quantity) as trq")
                                    ->from('preturns_product')
                                    ->join('preturns', 'preturns.prid = preturns_product.prid', 'left')
                                    ->where('pid',$pid)
                                    ->where('YEAR(prDate)',$year)
                                    ->get()
                                    ->row();
                          $cpp = $this->db->select("SUM(stock) as trq")
                                    ->from('costing')
                                    ->where('pid',$pid)
                                    ->where('YEAR(regdate)',$year)
                                    ->get()
                                    ->row();
                          }
                        }
                      else
                        {
                        $sp = $this->db->select("SUM(quantity) as tpq")
                                    ->from('store_product')
                                    ->where('pid',$pid)
                                    ->get()
                                    ->row();
                                    
                        $pp = $this->db->select("SUM(quantity) as tpq")
                                    ->from('purchase_product')
                                    ->where('pid',$pid)
                                    ->get()
                                    ->row();

                        $spp = $this->db->select("SUM(quantity) as tsq")
                                    ->from('sale_product')
                                    ->where('pid',$pid)
                                    ->get()
                                    ->row();
                      
                        $rp = $this->db->select("SUM(quantity) as trq")
                                    ->from('returns_product')
                                    ->where('pid',$pid)
                                    ->get()
                                    ->row();
                      
                        $rpp = $this->db->select("SUM(quantity) as trq")
                                    ->from('preturns_product')
                                    ->where('pid',$pid)
                                    ->get()
                                    ->row();
                        
                        $cpp = $this->db->select("SUM(stock) as trq")
                                    ->from('costing')
                                    ->where('pid',$pid)
                                    ->get()
                                    ->row();
                        }
                      
                      $saleproduct = $this->db->select('spEngine')
                                    ->from('sale_product')
                                    ->where('pid',$pid)
                                    ->get()
                                    ->row();
                                    
                      $price = $this->db->select('tamount,sprice,pprice')
                                    ->from('costing')
                                    ->where('pid',$pid)
                                    ->get()
                                    ->row();
                      if($price)
                        {
                        $pprice = $price->tamount;
                        $sprice = $price->sprice;
                        $inrprice = $price->pprice;
                        }
                      else
                        {
                        $pprice = $value['pprice'];
                        $sprice = $value['sprice'];
                        if($saleproduct)
                          {
                          $inrprice = $saleproduct->spEngine;
                          }
                        else
                          {
                          $inrprice = 0;
                          }
                        }
                      $stock = $this->db->select('*')
                                    ->from('stock')
                                    ->where('pid',$value['pid'])
                                    ->get()
                                    ->row();

                      if($stock)
                        {
                        $st = $stock->tquantity;
                        }
                      else
                        {
                        $st = '0';
                        }
                      $instk = ($sp->tpq+$pp->tpq+$rp->trq+$cpp->trq);
                      $outstk = ($spp->tsq+$rpp->trq);
                      $cpstk = ($instk-$outstk);
                      ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $value['pName']; ?></td>
                        <td><?php echo $value['hsn']; ?></td>
                        <td><?php echo $value['partNo']; ?></td>
                        <td><?php echo number_format($inrprice, 2); ?></td>
                        <td><?php echo number_format($sprice, 2); ?></td>
                        <td><?php echo number_format($pprice, 2); ?></td>
                        <td><?php echo $sp->tpq; $tspiq += $sp->tpq; ?></td>
                        <td><?php echo $cpp->trq; $tcpiq += $cpp->trq; ?></td>
                        <td><?php echo ($pp->tpq-$rpp->trq); $tpiq += ($pp->tpq-$rpp->trq); ?></td>
                        <td><?php echo ($spp->tsq-$rp->trq); $tpoq += ($spp->tsq-$rp->trq); ?></td>
                        <td><?php echo $cpstk; $tpsq += $cpstk; ?></td>
                        <td><?php echo number_format(($cpstk*$sprice), 2); $tpsp += ($cpstk*$sprice); ?></td>
                        <td><?php echo number_format(($cpstk*$pprice), 2); $tppp += ($cpstk*$pprice); ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th colspan="7" style="text-align: right;" >Total</th>
                        <th><?php echo $tspiq; ?></th>
                        <th><?php echo $tcpiq; ?></th>
                        <th><?php echo $tpiq; ?></th>
                        <th><?php echo $tpoq; ?></th>
                        <th><?php echo $tpsq; ?></th>
                        <th><?php echo number_format($tpsp, 2); ?></th>
                        <th><?php echo number_format($tppp, 2); ?></th>
                      </tr>
                    </tfoot>
                  </table>
                </div><br>
                <div class="form-group col-md-12" style="text-align: center;margin-top: 20px">
                  <a href="javascript:void(0)" style="width: 100px;" value="Print" onclick="printDiv('print')" class="btn btn-primary"><i class="fa fa-print"> </i>  Print</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<?php $this->load->view('footer/footer'); ?>

    <script type="text/javascript" >
      $(document).ready(function(){
        $('#daily').click(function(){
          $('#dreports').removeAttr('class','d-none');
          $('#mreports').attr('class','d-none');
          $('#yreports').attr('class','d-none');

          $('#sdate').attr('required','required');
          $('#edate').attr('required','required');
          $('#ddate').attr('required','required');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          $('#msupplier').removeAttr('required','required');
          
          $('#ryear').removeAttr('required','required');
          $('#ysupplier').removeAttr('required','required');
          });

        $('#monthly').click(function(){
          $('#mreports').removeAttr('class','d-none');
          $('#dreports').attr('class','d-none');
          $('#yreports').attr('class','d-none');

          $('#sdate').removeAttr('required','required');
          $('#edate').removeAttr('required','required');
          $('#dsupplier').removeAttr('required','required');
          
          $('#month').attr('required','required');
          $('#year').attr('required','required');
          $('#msupplier').attr('required','required');
          
          $('#ryear').removeAttr('required','required');
          $('#ysupplier').removeAttr('required','required');
          });

        $('#yearly').click(function(){
          $('#yreports').removeAttr('class','d-none');
          $('#dreports').attr('class','d-none');
          $('#mreports').attr('class','d-none');

          $('#sdate').removeAttr('required','required');
          $('#edate').removeAttr('required','required');
          $('#dsupplier').removeAttr('required','required');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          $('#msupplier').removeAttr('required','required');
          
          $('#ryear').attr('required','required');
          $('#ysupplier').attr('required','required');
          });
        });
    </script>