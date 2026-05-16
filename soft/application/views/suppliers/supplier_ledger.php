<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Supplier Ledger</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Supplier Ledger</li>
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
                <h3 class="card-title">Supplier Ledger</h3>
              </div>

              <div class="card-body">
                <div class="col-md-12 col-sm-12 col-12">
                  <form action="<?php echo base_url() ?>supLedger" method="get">
                    <div class="col-md-12 col-sm-12 col-12">
                      <div class="form-group col-md-12 col-sm-12 col-12">
                        <b>
                          <input type="radio" name="reports" value="ocust" id="ocust" required >&nbsp;&nbsp;Supplier All Ledger&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="reports" value="dailyReports" id="daily" required >&nbsp;&nbsp;Daily Reports&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="reports" value="monthlyReports" id="monthly" required >&nbsp;&nbsp;Monthly Reports&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="reports" value="yearlyReports" id="yearly" required >&nbsp;&nbsp;Yearly Reports
                        </b>
                      </div>
                      
                      <div class="d-none" id="reports">
                        <div class="row">
                          <div class="form-group col-md-3 col-sm-3 col-12">
                            <label>Select Supplier *</label>
                            <select class="form-control select2" name="supplier" id="supplier" required="" style="width: 100%;" >
                              <option value="">Select One</option>
                              <?php foreach($supplier as $row){ ?>
                              <option value="<?php echo $row['supid']; ?>"><?php echo $row['supName'].' ( '.$row['supCode'].' )'; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="form-group col-md-2 col-sm-2 col-12">
                            <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>
                          </div>
                        </div>
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
                            <label>Select Supplier *</label>
                            <select class="form-control select2" name="dsupplier"  required="" id="dsupplier" style="width: 100%;" >
                              <option value="">Select One</option>
                              <?php foreach($supplier as $row){ ?>
                              <option value="<?php echo $row['supid']; ?>"><?php echo $row['supName'].' ( '.$row['supCode'].' )'; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-12">
                            <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>
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
                            <label>Select Supplier *</label>
                            <select class="form-control select2" name="msupplier"  required="" id="msupplier" style="width: 100%;" >
                              <option value="">Select One</option>
                              <?php foreach($supplier as $row){ ?>
                              <option value="<?php echo $row['supid']; ?>"><?php echo $row['supName'].' ( '.$row['supCode'].' )'; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-12">
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
                            <label>Select Supplier *</label>
                            <select class="form-control select2" name="ysupplier"  required="" id="ysupplier" style="width: 100%;" >
                              <option value="">Select One</option>
                              <?php foreach($supplier as $row){ ?>
                              <option value="<?php echo $row['supid']; ?>"><?php echo $row['supName'].' ( '.$row['supCode'].' )'; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="form-group col-md-3 col-sm-3 col-12">
                            <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>
                          </div>
                        </div>
                      </div>

                    </div>
                  </form>
                </div>

                <div class="col-md-12 col-sm-12 col-12">
                  <div id="print">
                    <div class="col-sm-12 col-md-12 col-12">
                    <?php if(isset($_GET['search'])) { ?>
                      <div class="col-sm-12 col-md-12 col-12">
                        <div class="col-sm-12 col-md-12 col-xs-12">
                          Supplier ID&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $supp[0]['supCode']; ?>
                        </div>
                        <div class="col-sm-12 col-md-12 col-12">
                          Supplier Name&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $supp[0]['supName']; ?>
                        </div>
                        <div class="col-sm-12 col-md-12 col-12">
                          Address&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $supp[0]['supAddress']; ?>
                        </div>
                        <div class="col-sm-12 col-md-12 col-12">
                          Contact No&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $supp[0]['supMobile']; ?>
                        </div>
                      </div>
                      <?php if ($report == 'dailyReports') { ?>
                      <div class="box-header" style="text-align: center;">
                        <h3 class="box-title"><b>Supplier Ledger Reports in : <?php echo $sdate.' - '.$edate; ?></b></h3>
                      </div>

                      <?php } else if ($report == 'monthlyReports') { ?>
                      <div class="box-header" style="text-align: center;">
                        <h3 class="box-title"><b>Supplier Ledger Reports in : <?php echo $name.' '.$year; ?></b></h3>
                      </div>

                      <?php } else if ($report == 'yearlyReports') { ?>
                      <div class="box-header" style="text-align: center;">
                        <h3 class="box-title"><b>Supplier Ledger Reports in : <?php echo $year; ?></b></h3>
                      </div>
                      <?php } ?>
                    <?php } ?>

                      <table id="exam22ple" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th class="d-none">#SN.</th>
                            <th>Date</th>
                            <th>Invoice No.</th>
                            <th>Particulars</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                          $custba = 0;
                          if($purchase != null){ ?>
                          <?php
                          $i = 0;
                          $tob = 0;
                          $tpa = 0;
                          $tap = 0;
                          $taap = 0;
                          $td = 0;
                          foreach($purchase as $value){
                          $i++;
                          
                          $tsalses = $this->db->select("SUM(dAmount) as total")
                                            ->FROM('purchase')
                                            ->where('supid',$supp[0]['supid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tsbamt = $tsalses && $tsalses->total ? $tsalses->total : 0;
                          
                          $tspay = $this->db->select("SUM(purchase_payment.pAmount) as total,purchase.supid")
                                            ->FROM('purchase_payment')
                                            ->join('purchase','purchase.puid = purchase_payment.puid','left')
                                            ->where('supid',$supp[0]['supid'])
                                            ->where('purchase_payment.regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tspamt = $tspay && $tspay->total ? $tspay->total : 0;
                          
                          $tcvoucher = $this->db->select("SUM(tAmount) as total")
                                            ->FROM('vaucher')
                                            ->where('supid',$supp[0]['supid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->where('status',1)
                                            ->get()
                                            ->row();
                          $tcvamt = $tcvoucher && $tcvoucher->total ? $tcvoucher->total : 0;
                          
                          $treturn = $this->db->select("SUM(tAmount) as total")
                                            ->FROM('preturns')
                                            ->where('supid',$supp[0]['supid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $trpamt = $treturn && $treturn->total ? $treturn->total : 0;
                          
                          $custba = ($tsbamt)-($tspamt+$tcvamt+$trpamt);
                          ?>
                          <tr>
                            <td class="d-none" ><?php echo date('Ymdhis',strtotime($value->regdate)); ?></td>
                            <td><?php echo date('d-m-Y',strtotime($value->puDate)); ?></td>
                            <td><?php echo $value->challanNo; ?></td>
                            <td><?php echo 'Purchase'; ?></td>
                            <td><?php echo number_format($value->tAmount, 2); $tpa += $value->tAmount; ?></td>
                            <td><?php echo number_format($value->pAmount, 2); $taap += $value->pAmount; $td += $value->dAmount; ?></td>
                            <td><?php echo number_format($custba, 2); ?></td>
                          </tr>
                          <?php } ?> 
                          <?php } else{ ?>
                          <?php $i = 0; ?>
                          <?php $tpa = 0; $taap = 0; $td = 0; ?>
                          <?php } ?>

                          <?php if ($voucher != null) { ?>

                          <?php
                          $j = $i;
                          $tvpa = 0;
                          foreach ($voucher as $value) {
                          $j++;
                          
                          $tsalses = $this->db->select("SUM(dAmount) as total")
                                            ->FROM('purchase')
                                            ->where('supid',$supp[0]['supid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tsbamt = $tsalses && $tsalses->total ? $tsalses->total : 0;
                          
                          $tspay = $this->db->select("SUM(purchase_payment.pAmount) as total,purchase.supid")
                                            ->FROM('purchase_payment')
                                            ->join('purchase','purchase.puid = purchase_payment.puid','left')
                                            ->where('supid',$supp[0]['supid'])
                                            ->where('purchase_payment.regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tspamt = $tspay && $tspay->total ? $tspay->total : 0;
                          
                          $tcvoucher = $this->db->select("SUM(tAmount) as total")
                                            ->FROM('vaucher')
                                            ->where('supid',$supp[0]['supid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->where('status',1)
                                            ->get()
                                            ->row();
                          $tcvamt = $tcvoucher && $tcvoucher->total ? $tcvoucher->total : 0;
                          
                          $treturn = $this->db->select("SUM(tAmount) as total")
                                            ->FROM('preturns')
                                            ->where('supid',$supp[0]['supid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $trpamt = $treturn && $treturn->total ? $treturn->total : 0;
                          
                          $custba = ($tsbamt)-($tspamt+$tcvamt+$trpamt);
                          ?>
                          <tr class="gradeX">
                            <td class="d-none" ><?php echo date('Ymdhis',strtotime($value->regdate)); ?></td>
                            <td><?php echo date('d-m-Y',strtotime($value->vuDate)); ?></td>
                            <td><?php echo $value->invoice; ?></td>
                            <td><?php echo $value->vauchertype; ?></td>
                            <td><?php echo '00'; ?></td> 
                            <td><?php echo number_format(($value->tAmount), 2); $tvpa += $value->tAmount; ?></td> 
                            <td><?php echo number_format($custba, 2); ?></td>
                          </tr>   
                          <?php } ?>
                          <?php } else{ ?>
                          <?php $j = $i or $j = 0; ?>
                          <?php $tvpa = 0; ?>
                          <?php } ?> 
                          
                          <?php if ($payment != null) { ?>

                          <?php
                          $k = $j;
                          $tvia = 0;
                          $tvba = 0;
                          foreach ($payment as $value) {
                          $k++;
                          
                          $tsalses = $this->db->select("SUM(dAmount) as total")
                                            ->FROM('purchase')
                                            ->where('supid',$supp[0]['supid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tsbamt = $tsalses && $tsalses->total ? $tsalses->total : 0;
                          
                          $tspay = $this->db->select("SUM(purchase_payment.pAmount) as total,purchase.supid")
                                            ->FROM('purchase_payment')
                                            ->join('purchase','purchase.puid = purchase_payment.puid','left')
                                            ->where('supid',$supp[0]['supid'])
                                            ->where('purchase_payment.regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tspamt = $tspay && $tspay->total ? $tspay->total : 0;
                          
                          $tcvoucher = $this->db->select("SUM(tAmount) as total")
                                            ->FROM('vaucher')
                                            ->where('supid',$supp[0]['supid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->where('status',1)
                                            ->get()
                                            ->row();
                          $tcvamt = $tcvoucher && $tcvoucher->total ? $tcvoucher->total : 0;
                          
                          $treturn = $this->db->select("SUM(tAmount) as total")
                                            ->FROM('preturns')
                                            ->where('supid',$supp[0]['supid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $trpamt = $treturn && $treturn->total ? $treturn->total : 0;
                          
                          $custba = ($tsbamt)-($tspamt+$tcvamt+$trpamt);
                          ?>
                          <tr class="gradeX">
                            <td class="d-none" ><?php echo date('Ymdhis',strtotime($value->regdate)); ?></td>
                            <td><?php echo date('d-m-Y',strtotime($value->regdate)); ?></td>
                            <td><?php echo $value->spid; ?></td>
                            <td><?php echo 'Advance Payment'; ?></td>
                            <td><?php echo '00'; ?></td>
                            <td><?php echo number_format(($value->pAmount), 2); $tvba += $value->pAmount; ?></td> 
                            <td><?php echo number_format($custba, 2); ?></td>
                          </tr>   
                          <?php } ?>
                          <?php } else{ ?>
                          <?php $k = $j or $k = $i or $k = 0; ?>
                          <?php $tvia = 0; $tvba = 0; ?>
                          <?php } ?> 
                          
                          <?php if ($return != null) { ?>
                          <?php
                          $l = $k;
                          $tra = 0;
                          foreach ($return as $value) {
                          $l++;
                          
                          $tsalses = $this->db->select("SUM(dAmount) as total")
                                            ->FROM('purchase')
                                            ->where('supid',$supp[0]['supid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tsbamt = $tsalses && $tsalses->total ? $tsalses->total : 0;
                          
                          $tspay = $this->db->select("SUM(purchase_payment.pAmount) as total,purchase.supid")
                                            ->FROM('purchase_payment')
                                            ->join('purchase','purchase.puid = purchase_payment.puid','left')
                                            ->where('supid',$supp[0]['supid'])
                                            ->where('purchase_payment.regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $tspamt = $tspay && $tspay->total ? $tspay->total : 0;
                          
                          $tcvoucher = $this->db->select("SUM(tAmount) as total")
                                            ->FROM('vaucher')
                                            ->where('supid',$supp[0]['supid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->where('status',1)
                                            ->get()
                                            ->row();
                          $tcvamt = $tcvoucher && $tcvoucher->total ? $tcvoucher->total : 0;
                          
                          $treturn = $this->db->select("SUM(tAmount) as total")
                                            ->FROM('preturns')
                                            ->where('supid',$supp[0]['supid'])
                                            ->where('regdate <=',$value->regdate)
                                            ->get()
                                            ->row();
                          $trpamt = $treturn && $treturn->total ? $treturn->total : 0;
                          
                          $custba = ($tsbamt)-($tspamt+$tcvamt+$trpamt);
                          ?>
                          <tr class="gradeX">
                            <td class="d-none" ><?php echo date('Ymdhis',strtotime($value->regdate)); ?></td>
                            <td><?php echo date('d-m-Y',strtotime($value->prDate)); ?></td>
                            <td><?php echo $value->sprCodepid; ?></td>
                            <td><?php echo 'Purchase Returns'; ?></td>
                            <td><?php echo '00'; ?></td>
                            <td><?php echo number_format(($value->tAmount), 2); $tra += $value->tAmount; ?></td> 
                            <td><?php echo number_format($custba, 2); ?></td>
                          </tr>   
                          <?php } ?>
                          <?php } else{ ?>
                          <?php $tra = 0; ?>
                          <?php } ?> 
                        </tbody>
                        <tfoot>
                          <tr>
                            <td class="d-none" ></td>
                            <th colspan="3" style="text-align: right;">Total</th>
                            <th><?php echo number_format($tpa, 2); ?></th>
                            <th><?php echo number_format(($taap+$tvpa+$tvba+$tra), 2); ?></th>
                            <th><?php echo number_format(($tpa-($taap+$tvpa+$tvba+$tra)), 2); ?></th>
                          </tr>
                          <tr>
                            <td class="d-none" ></td>
                            <th colspan="3" style="text-align: right;">Total Purchase</th>
                            <th><?php echo number_format($tpa, 2); ?></th>
                            <th colspan="2"  ></th>
                          </tr>
                          <tr>
                            <td class="d-none" ></td>
                            <th colspan="3" style="text-align: right;">Total Purchase Paid</th>
                            <th><?php echo number_format($taap, 2); ?></th>
                            <th colspan="2"  ></th>
                          </tr>
                          <tr>
                            <td class="d-none" ></td>
                            <th colspan="3" style="text-align: right;">Total Payment</th>
                            <th><?php echo number_format(($tvpa+$tvba), 2); ?></th>
                            <th colspan="2"  ></th>
                          </tr>
                          <tr>
                            <td class="d-none" ></td>
                            <th colspan="3" style="text-align: right;">Total Return</th>
                            <th><?php echo number_format(($tra), 2); ?></th>
                            <th colspan="2"  ></th>
                          </tr>
                          <tr>
                            <td class="d-none" ></td>
                            <th colspan="3" style="text-align: right;">Due Amount</th>
                            <th><?php echo number_format(($tpa-($taap+$tvpa+$tvba+$tra)), 2); ?></th>
                            <th colspan="2"  ></th>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                  <div class="form-group col-sm-12 col-md-12 col-12" style="text-align: center; margin-top: 20px">
                    <a href="javascript:void(0)" style="width: 100px;" value="Print" onclick="printDiv('print')" class="btn btn-primary"><i class="fa fa-print"> </i>  Print</a>
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

    <script type="text/javascript" >
      $(document).ready(function(){
        $('#daily').click(function(){
          $('#dreports').removeAttr('class','d-none');
          $('#mreports').attr('class','d-none');
          $('#yreports').attr('class','d-none');
          $('#reports').attr('class','d-none');

          $('#sdate').attr('required','required');
          $('#edate').attr('required','required');
          $('#ddate').attr('required','required');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          $('#msupplier').removeAttr('required','required');
          
          $('#ryear').removeAttr('required','required');
          $('#ysupplier').removeAttr('required','required');
          
          $('#supplier').removeAttr('required','required');
          });

        $('#monthly').click(function(){
          $('#mreports').removeAttr('class','d-none');
          $('#dreports').attr('class','d-none');
          $('#yreports').attr('class','d-none');
          $('#reports').attr('class','d-none');

          $('#sdate').removeAttr('required','required');
          $('#edate').removeAttr('required','required');
          $('#dsupplier').removeAttr('required','required');
          
          $('#month').attr('required','required');
          $('#year').attr('required','required');
          $('#msupplier').attr('required','required');
          
          $('#ryear').removeAttr('required','required');
          $('#ysupplier').removeAttr('required','required');
          
          $('#supplier').removeAttr('required','required');
          });

        $('#yearly').click(function(){
          $('#yreports').removeAttr('class','d-none');
          $('#dreports').attr('class','d-none');
          $('#mreports').attr('class','d-none');
          $('#reports').attr('class','d-none');

          $('#sdate').removeAttr('required','required');
          $('#edate').removeAttr('required','required');
          $('#dsupplier').removeAttr('required','required');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          $('#msupplier').removeAttr('required','required');
          
          $('#ryear').attr('required','required');
          $('#ysupplier').attr('required','required');
          
          $('#supplier').removeAttr('required','required');
          });
          
        $('#ocust').click(function(){
          $('#yreports').attr('class','d-none');
          $('#dreports').attr('class','d-none');
          $('#mreports').attr('class','d-none');
          $('#reports').removeAttr('class','d-none');

          $('#sdate').removeAttr('required','required');
          $('#edate').removeAttr('required','required');
          $('#dsupplier').removeAttr('required','required');
          
          $('#month').removeAttr('required','required');
          $('#year').removeAttr('required','required');
          $('#msupplier').removeAttr('required','required');
          
          $('#ryear').removeAttr('required','required');
          $('#ysupplier').removeAttr('required','required');
          
          $('#supplier').attr('required','required');
          });
        });
    </script>
    
    <script>
      $(document).ready(function() {
        var table = $('#exam22ple').DataTable({
        paging: false,
        order: [[0, 'asc']],
        });
        });
    </script>
    
    