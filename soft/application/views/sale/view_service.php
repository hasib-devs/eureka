<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>
  
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sale Service</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Sale Service</li>
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
                <h3 class="card-title">Sale Service Information</h3>
              </div>

              <div class="card-body">
                <div class="invoice p-3 mb-3">

                  <div id="print">
                      <br><br><br><br><br><br>
                    <div class="row">
                        <div class="col-sm-6 col-6 invoice-col">Invoice No: <?php echo $prints['ssCode']; ?></div>
                        <div class="col-sm-6 col-6 invoice-col" style="text-align:right;">Date: <?php echo date('d-M-Y', strtotime($prints['ssDate'])); ?></div>
                    </div>
                    <br><br>
                    <div class="row invoice-info">
                      <div class="col-sm-8 col-8 invoice-col">
                        <!--<?php if($company){ ?><img src="<?php echo base_url().'upload/company/'.$company->com_logo; ?>" style="height:80px; width:auto;"><?php } ?><br>-->
                        <!--<div style="padding: 10px;">-->
                        <!--  <span style="padding: 10px; border: 2px solid #29B577; color: #29B577;">Billing From</span>-->
                        <!--</div>-->
                        <!--<address>-->
                        <!--  <?php if($company){ ?><h3><b><?php echo $company->com_name; ?></b></h3><?php } ?>-->
                        <!--  <p style=""><?php if($company){ ?><?php echo $company->com_address; ?><?php } ?><br>-->
                        <!--  <b>Mobile : </b><?php if($company){ ?><?php echo $company->com_mobile; ?><?php } ?><br>-->
                        <!--  <b>Email : </b><?php if($company){ ?><?php echo $company->com_email; ?><?php } ?><br>-->
                        <!--  <b>Website : </b><?php if($company){ ?><?php echo $company->com_web; ?><?php } ?></p>-->
                        <!--</address>-->
                        <div>To</div>
                        <div>Name: <?php echo $prints['custName']; ?></div>
                        <div>Father’s name: <?php echo $prints['custfName']; ?></div>
                        <div>Address: <?php echo $prints['custAddress']; ?></div>
                        <div>NID: <?php echo $prints['custNid']; ?></div>
                        <div>Contact Number:<?php echo $prints['custMobile']; ?></div>
                      </div>
                      <!--<div class="col-sm-4 col-4 invoice-col">-->
                      <!--  <div class="col-sm-12 col-12">-->
                      <!--    <h3>Sale Invoice</h3>-->
                      <!--    <p style=""><b>Invoice No. # </b><?php echo $prints['invoice']; ?><br>-->
                      <!--    <b>Billing Date #</b> <?php echo date('d-m-Y', strtotime($prints['saDate'])); ?><br>-->
                      <!--    <b>Bill Create #</b> <?php echo $prints['name']; ?></p>-->
                      <!--  </div>-->
                        
                      <!--  <div style="padding: 10px;">-->
                      <!--    <span style="padding: 10px; border: 2px solid #29B577; color: #29B577;">Billing To</span>-->
                      <!--  </div>-->
                        
                      <!--  <address>-->
                      <!--    <h3><b><?php echo $prints['custName']; ?></b></h3>-->
                      <!--    <p style=""><?php echo $prints['custAddress']; ?><br>-->
                      <!--    <b>Mobile : </b><?php echo $prints['custMobile']; ?></p>-->
                      <!--  </address>-->
                      <!--</div>-->
                    </div>
                    <br><br>
                    <div class="row" style="">
                      <div class="col-sm-12 col-12">
                        <table class="table table-striped table-bordered">
                          <thead>
                            <tr>
                              <th>SN</th>
                              <th>Service</th>
                              <th>Details</th>
                              <th>Qty</th>
                              <th>Rate</th>
                              <th>Total</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $i = 0;
                            $tq = 0;
                            $stotal = 0;
                            foreach ($salesp as $value){
                            $i++;
                            ?>
                            <tr>
                              <td><?php echo $i; ?></td>
                              <td><?php echo $value['sCode'].' '.$value['sName']; ?></td>
                              <td><?php echo $value['sDetails']; ?></td>
                              <td><?php echo round($value['quantity']); $tq += $value['quantity']; ?></td>
                              <td><?php echo number_format($value['sprice'], 2);; ?></td>
                              <td style="text-align:right;" ><?php echo number_format($value['tprice'], 2); $stotal += $value['tprice']; ?></td>
                            </tr>
                            <?php } ?>
                          </tbody>
                          <tbody>
                            <tr>
                              <td colspan="3" align="right"><b>Grand Total :</b></td>
                              <td><b><?php echo $tq; ?></b></td>
                              <td></td>
                              <td style="text-align:right;" ><b><?php echo number_format($stotal, 2); ?></b></td>
                            </tr>
                          </tbody>
                          <tbody>
                            <tr>
                              <td colspan="3">
                                <span class="col-md-12 col-12" ><b>Note / Remarks</b></span><br>
                                <span class="col-md-12 col-12" ><b><?php echo $prints['notes']; ?></b></span>
                              </td>
                              <td colspan="4">
                                <table style="width: 100%;">
                                  <tr>
                                    <td><b>Discount Amount</b></td>
                                    <td style="text-align:right;"><?php echo number_format($prints['disAmount'], 2); ?></td>
                                  </tr>
                                  <tr>
                                    <td><b>VAT Amount</b></td>
                                    <td style="text-align:right;"><?php echo number_format($prints['vAmount'], 2); ?></td>
                                  </tr>
                                  <tr>
                                    <td><b>Net Amount</b></td>
                                    <td style="text-align:right;"><?php echo number_format((($prints['tAmount']+$prints['vAmount'])-$prints['disAmount']), 2); ?></td>
                                  </tr>
                                  <tr>
                                    <td><b>Paid Amount</b></td>
                                    <td style="text-align:right;"><?php echo number_format($prints['pAmount']+$spay->total, 2); ?></td>
                                  </tr>
                                  <tr>
                                    <td><b>Due Amount</b></td>
                                    <td style="text-align:right;"><?php echo number_format($prints['dAmount']-$spay->total, 2); ?></td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                            <tr style="text-align: center;">
                              <?php $twa = round(abs($prints['pAmount']+$spay->total)); ?>
                              <td colspan="6"><b>( In Words&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo convertNumber($twa); ?> )</b></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    
                    <div class="row" id="header" style="display: none">
                      <div class="col-md-12 col-12" style="text-align: center; position: absolute; bottom: 0;">
                        <div class="row">
                          <div class="col-md-6 col-sm-6 col-6" >
                            <p>------------------------------</p>
                            <p>Received By</p>
                          </div>
                          <div class="col-md-6 col-sm-6 col-6">
                            <p>------------------------------</p>
                            <p>Authorized By</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row no-print" >
                    <div class="col-12" style="text-align: center;">
                      <a href="javascript:void(0)" class="btn btn-primary" onclick="printDiv('print')" ><i class="fas fa-print"></i> Print</a>
                      <a href="<?php echo site_url('servlist'); ?>" class="btn btn-danger" ><i class="fas fa-arrow-left"></i> Back</a>
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

    <?php
      function convertNumber($number){
        $words = array(
          '0'=> '' ,'1'=> 'one' ,'2'=> 'two' ,'3' => 'three','4' => 'four','5' => 'five','6' => 'six','7' => 'seven','8' => 'eight','9' => 'nine','10' => 'ten','11' => 'eleven','12' => 'twelve','13' => 'thirteen','14' => 'fouteen','15' => 'fifteen','16' => 'sixteen','17' => 'seventeen','18' => 'eighteen','19' => 'nineteen','20' => 'twenty','30' => 'thirty','40' => 'fourty','50' => 'fifty','60' => 'sixty','70' => 'seventy','80' => 'eighty','90' => 'ninty');
    
        $number_length = strlen($number);

        $number_array = array(0,0,0,0,0,0,0,0,0);        
        $received_number_array = array();
    
        for($i=0;$i<$number_length;$i++)
          {    
          $received_number_array[$i] = substr($number,$i,1);    
          }
        
        for($i=9-$number_length,$j=0;$i<9;$i++,$j++)
          { 
          $number_array[$i] = $received_number_array[$j]; 
          }
        $number_to_words_string = "";

        for($i=0,$j=1;$i<9;$i++,$j++)
          {
          if($i==0 || $i==2 || $i==4 || $i==7)
            {
            if($number_array[$j]==0 || $number_array[$i] == "1")
              {
              $number_array[$j] = intval($number_array[$i])*10+$number_array[$j];
              $number_array[$i] = 0;
              }
            }
          }
        $value = "";
        for($i=0;$i<9;$i++)
          {
          if($i==0 || $i==2 || $i==4 || $i==7)
            {    
            $value = $number_array[$i]*10; 
            }
          else
            { 
            $value = $number_array[$i];    
            }            
          if($value!=0)
            {
            $number_to_words_string.= $words["$value"]." ";
            }
          if($i==1 && $value!=0)
            {
            $number_to_words_string.= "Crores ";
            }
          if($i==3 && $value!=0)
            {
            $number_to_words_string.= "Lakhs ";
            }
          if($i==5 && $value!=0)
            {
            $number_to_words_string.= "Thousand ";
            }
          if($i==6 && $value!=0)
            {
            $number_to_words_string.= "Hundred ";
            }            
          }
        if($number_length>9)
          {
          $number_to_words_string = "Sorry This does not support more than 99 Crores";
          }
        return ucwords(strtolower($number_to_words_string)." Taka Only.");
        }
    ?>