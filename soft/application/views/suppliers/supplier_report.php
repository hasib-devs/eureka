<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Supplier Report</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Supplier Report</li>
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
                <h3 class="card-title">Supplier Report</h3>
              </div>

              <div class="card-body">
                <div class="col-sm-12 col-md-12 col-12">
                  <form action="<?php echo base_url() ?>supReport" method="get">
                    
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
                        <div class="form-group col-md-3 col-sm-3 col-12">
                          <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus"></i>&nbsp;Search</button>
                        </div>
                      </div>
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
                          <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus"></i>&nbsp;Search</button>
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
                          <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>
                        </div>
                      </div>
                    </div>

                  </form>
                </div>

                <div id="print">
                  <?php if(isset($_GET['search'])) { ?>
                    <?php if ($report == 'dailyReports') { ?>
                      <div class="box-header" style="text-align: center;">
                        <h3 class="box-title"><b>Supplier Reports in : <?php echo $sdate.' - '.$edate; ?></b></h3>
                      </div>
                    <?php } else if ($report == 'monthlyReports') { ?>
                      <div class="box-header" style="text-align: center;">
                        <h3 class="box-title"><b>Supplier Reports in : <?php echo $name.' '.$year; ?></b></h3>
                      </div>
                    <?php } else if ($report == 'yearlyReports') { ?>
                      <div class="box-header" style="text-align: center;">
                        <h3 class="box-title"><b>Supplier Reports in : <?php echo $year; ?></b></h3>
                      </div>
                    <?php } ?>
                  <?php } ?>    
                  <table id="example" class="table table-responsive table-bordered" >
                    <thead>
                      <tr>
                        <th style="width: 5%;">#SN.</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Opening</th>
                        <th>Advance</th>
                        <th>Purchases</th>
                        <th>Paid</th>
                        <th>Payment</th>
                        <th>Return</th>
                        <th>Due</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $i = 0;
                      $toa = 0;
                      $ta = 0;
                      $tpa = 0;
                      $pat = 0;
                      $tda = 0;
                      $tra = 0;
                      $ppat = 0;
                      foreach ($supplier as $row){
                      $i++;

                      $id = $row['supid'];

                      if(isset($_GET['search']))
                        {
                        $report = $_GET['reports'];
                        $data['report'] = $report;
                          //var_dump($data['report']); exit();
                        if($report == 'dailyReports')
                          {
                          $tpur = $this->db->select("SUM(tAmount) as total,SUM(pAmount) as ptotal,SUM(dAmount) as dtotal")
                                            ->FROM('purchase')
                                            ->WHERE('supid',$id)
                                            ->where('puDate >=', $sdate)
                                            ->where('puDate <=', $edate)
                                            ->get()
                                            ->row();

                          $tvpaid = $this->db->select("SUM(tAmount) as total")
                                              ->FROM('vaucher')
                                              ->WHERE('supid',$id)
                                              ->where('vuDate >=', $sdate)
                                              ->where('vuDate <=', $edate)
                                              ->get()
                                              ->row();
                          
                          $treturn = $this->db->select("SUM(tAmount) as total")
                                              ->FROM('preturns')
                                              ->WHERE('supid',$id)
                                              ->where('prDate >=', $sdate)
                                              ->where('prDate <=', $edate)
                                              ->get()
                                              ->row();
                          
                          $customer = $this->db->select('puid')
                                        ->from('purchase')
                                        ->WHERE('supid',$id)
                                        ->get()
                                        ->result_array();
                          $sales = array_map (function($value){
                              return $value['puid'];
                              },$customer);
                                //var_dump($emp_id); exit();
                            if($sales)
                              {
                              $said = $sales;
                              }
                            else
                              {
                              $said = 0;
                              }
                        
                          $pay = $this->db->select('SUM(pAmount) as total')
                                        ->from('purchase_payment')
                                        ->where_in('puid',$said)
                                        ->where('DATE(regdate) >=', $sdate)
                                        ->where('DATE(regdate) <=', $edate)
                                        ->get()
                                        ->row();
                          
                          $tadp = $this->db->select("SUM(pAmount) as total")
                                          ->FROM('supplier_payment')
                                          ->WHERE('supid',$id)
                                          ->where('DATE(regdate) >=', $sdate)
                                          ->where('DATE(regdate) <=', $edate)
                                          ->get()
                                          ->row();
                          }
                        else if($report == 'monthlyReports')
                          {
                          $tpur = $this->db->select("SUM(tAmount) as total,SUM(pAmount) as ptotal,SUM(dAmount) as dtotal")
                                            ->FROM('purchase')
                                            ->WHERE('supid',$id)
                                            ->where('MONTH(puDate)',$month)
                                            ->where('YEAR(puDate)',$year)
                                            ->get()
                                            ->row();

                          $tvpaid = $this->db->select("SUM(tAmount) as total")
                                            ->FROM('vaucher')
                                            ->WHERE('supid',$id)
                                            ->where('MONTH(vuDate)',$month)
                                            ->where('YEAR(vuDate)',$year)
                                            ->get()
                                            ->row();
                          
                          $treturn = $this->db->select("SUM(tAmount) as total")
                                              ->FROM('preturns')
                                              ->WHERE('supid',$id)
                                              ->where('MONTH(prDate)',$month)
                                              ->where('YEAR(prDate)',$year)
                                              ->get()
                                              ->row();
                          
                          $customer = $this->db->select('puid')
                                        ->from('purchase')
                                        ->WHERE('supid',$id)
                                        ->get()
                                        ->result_array();
                          $sales = array_map (function($value){
                              return $value['puid'];
                              },$customer);
                                //var_dump($emp_id); exit();
                            if($sales)
                              {
                              $said = $sales;
                              }
                            else
                              {
                              $said = 0;
                              }
                        
                          $pay = $this->db->select('SUM(pAmount) as total')
                                        ->from('purchase_payment')
                                        ->where_in('puid',$said)
                                        ->where('MONTH(regdate)',$month)
                                        ->where('YEAR(regdate)',$year)
                                        ->get()
                                        ->row();
                          
                          $tadp = $this->db->select("SUM(pAmount) as total")
                                          ->FROM('supplier_payment')
                                          ->WHERE('supid',$id)
                                          ->where('MONTH(regdate)',$month)
                                          ->where('YEAR(regdate)',$year)
                                          ->get()
                                          ->row();
                          }
                        elseif($report == 'yearlyReports')
                          {
                          $tpur = $this->db->select("SUM(tAmount) as total,SUM(pAmount) as ptotal,SUM(dAmount) as dtotal")
                                            ->FROM('purchase')
                                            ->WHERE('supid',$id)
                                            ->where('YEAR(puDate)',$year)
                                            ->get()
                                            ->row();

                          $tvpaid = $this->db->select("SUM(tAmount) as total")
                                            ->FROM('vaucher')
                                            ->WHERE('supid',$id)
                                            ->where('YEAR(vuDate)',$year)
                                            ->get()
                                            ->row();
                          
                          $treturn = $this->db->select("SUM(tAmount) as total")
                                              ->FROM('preturns')
                                              ->WHERE('supid',$id)
                                              ->where('YEAR(prDate)',$year)
                                              ->get()
                                              ->row();
                          
                          $customer = $this->db->select('puid')
                                        ->from('purchase')
                                        ->WHERE('supid',$id)
                                        ->get()
                                        ->result_array();
                          $sales = array_map (function($value){
                              return $value['puid'];
                              },$customer);
                                //var_dump($emp_id); exit();
                            if($sales)
                              {
                              $said = $sales;
                              }
                            else
                              {
                              $said = 0;
                              }
                        
                          $pay = $this->db->select('SUM(pAmount) as total')
                                        ->from('purchase_payment')
                                        ->where_in('puid',$said)
                                        ->where('YEAR(regdate)',$year)
                                        ->get()
                                        ->row();
                          
                          $tadp = $this->db->select("SUM(pAmount) as total")
                                          ->FROM('supplier_payment')
                                          ->WHERE('supid',$id)
                                          ->where('YEAR(regdate)',$year)
                                          ->get()
                                          ->row();
                          }
                        }
                      else
                        {
                        $tpur = $this->db->select("SUM(tAmount) as total,SUM(pAmount) as ptotal,SUM(dAmount) as dtotal")
                                          ->FROM('purchase')
                                          ->WHERE('supid',$id)
                                          ->get()
                                          ->row();

                        $tvpaid = $this->db->select("SUM(tAmount) as total")
                                            ->FROM('vaucher')
                                            ->WHERE('supid',$id)
                                            ->get()
                                            ->row();
                        
                        $treturn = $this->db->select("SUM(tAmount) as total")
                                              ->FROM('preturns')
                                              ->WHERE('supid',$id)
                                              ->get()
                                              ->row();
                        
                        $customer = $this->db->select('puid')
                                        ->from('purchase')
                                        ->WHERE('supid',$id)
                                        ->get()
                                        ->result_array();
                        $sales = array_map (function($value){
                          return $value['puid'];
                          },$customer);
                            //var_dump($emp_id); exit();
                        if($sales)
                          {
                          $said = $sales;
                          }
                        else
                          {
                          $said = 0;
                          }
                    
                        $pay = $this->db->select('SUM(pAmount) as total')
                                    ->from('purchase_payment')
                                    ->where_in('puid',$said)
                                    ->get()
                                    ->row();
                        
                        $tadp = $this->db->select("SUM(pAmount) as total")
                                          ->FROM('supplier_payment')
                                          ->WHERE('supid',$id)
                                          ->get()
                                          ->row();
                        }
                      ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row['supName']; ?></td>
                        <td><?php echo $row['supMobile']; ?></td>
                        <td><?php echo number_format($row['balance'], 2); $toa += $row['balance']; ?></td>
                        <td><?php echo number_format($row['balance']+$tadp->total, 2); $ta += $row['balance']+$tadp->total; ?></td>
                        <td><?php echo number_format($tpur->total, 2); $tpa += $tpur->total; ?></td>
                        <td><?php echo number_format($tpur->ptotal, 2); $ppat += $tpur->ptotal ?></td>
                        <td><?php echo number_format($tvpaid->total, 2); $pat += $tvpaid->total; ?></td>
                        <td><?php echo number_format($treturn->total, 2); $tra += $treturn->total; ?></td>
                        <td><?php echo number_format((($tpur->dtotal+$row['balance']+$tadp->total)-($tvpaid->total+$treturn->total)), 2); $tda += (($tpur->dtotal+$row['balance']+$tadp->total)-($tvpaid->total+$treturn->total)); ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="3" align="right"><b>Total Amount</b></td>
                        <td><b><?php echo number_format($toa, 2); ?></b></td>
                        <td><b><?php echo number_format($ta, 2); ?></b></td>
                        <td><b><?php echo number_format($tpa, 2); ?></b></td>
                        <td><b><?php echo number_format($ppat, 2); ?></b></td>
                        <td><b><?php echo number_format($pat, 2); ?></b></td>
                        <td><b><?php echo number_format($tra, 2); ?></b></td>
                        <td><b><?php echo number_format($tda, 2); ?></b></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div class="form-group col-md-12 col-sm-12 col-12" style="text-align: center; margin-top: 20px">
                  <a href="javascript:void(0)" onclick="printDiv('print')" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
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