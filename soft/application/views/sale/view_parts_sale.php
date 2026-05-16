<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>
  <style>
      @font-face {
    font-family: 'Azonix';
    src: url('system/fonts/Azonix.otf') format('truetype');
}

h3 {
    font-family: 'Azonix', sans-serif !important;
    font-weight: bold !important;
}
p {
    font-family: 'Azonix', sans-serif !important;
    font-weight: bold !important;
}
td {
    font-family: 'Azonix', sans-serif !important;
    font-weight: bold !important;
}
th {
    font-family: 'Azonix', sans-serif !important;
    font-weight: bold !important;
}
  </style>
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>View Sales</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Sales</li>
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
                <h3 class="card-title">Sale Information</h3>
              </div>

              <div class="card-body">
                <div class="invoice p-3 mb-3">

                  <div id="print">
                    <div class="row invoice-info">
                      <div class="col-sm-3 col-3 invoice-col">
                        <?php if($company){?><img src="<?php echo base_url().'upload/company/'.$company->com_logo; ?>"style="height: auto; width: 100%;"><?php } ?><br>
                      </div> 
                      <div class="col-sm-6 col-6 invoice-col text-center">
                          <?php if($company){ ?><h3><img src="<?php echo base_url().'assets/unnamed.jpg'; ?>"style="height: 30px; width: auto;"></h3><?php } ?>
                          <p style=""><?php if($company){ ?><?php echo $company->com_address; ?><?php } ?><br>
                          Phone : <?php if($company){ ?><?php echo $company->com_mobile; ?><?php } ?><br>
                          Email : <?php if($company){ ?><?php echo $company->com_email; ?><?php } ?>
                        </div>
                    </div>
                    <hr>
                    
               <!--  <div class="row">
                        <div class="col-sm-4 col-4"></div>
                        <div class="col-sm-4 col-4" style="text-align:center;">
                            <p style="border:1px solid black;text-align:center;font-size:2rem;font-weight:bold;box-shadow: 5px 10px #888">INVOICE</p>
                        </div>
                        <div class="col-sm-4 col-4"></div>
                    </div> -->
                    
                     <div class="row">
                         
                        <div class="col-sm-7 col-7 invoice-col">
                            <table class="table table-borderless" style="font-size:18px">
    							<tbody>
    								<tr>
    									<td>Invoice No : <b><?php echo $prints['invoice']; ?></b> <br>
    									    Customer :  <b><?php echo $prints['custName']; ?></b> <br>
    									    Mobile No : <b><?php echo $prints['custMobile']; ?></b> <br>
    									    Address :  <b><?php echo $prints['custAddress']; ?></b>
    									
    									
    									</td>
    								     	
    								</tr>
    								
    								<!--<tr>-->
    								<!--	<td>Order No.</td>-->
    								<!--    <td>:  <b><?php echo $prints['invoice']; ?></b></td>	-->
    								<!--</tr>-->
    								
    							</tbody>
    						</table>
						</div>
						
						
                        <div class="col-sm-1 col-1 invoice-col"></div>
                        <div class="col-sm-4 col-4 invoice-col">
                            <table class="table table-borderless" style="font-size:18px">
    							<tbody>
    								<tr >
    									<td>Date : <?php echo date('d-m-Y', strtotime($prints['saDate'])); ?><br>
    									Courier : <?php echo $prints['courierName']; ?><br>
    								    Courier Man : <?php echo $prints['empName']; ?></td>
    								</tr>
    								
    							</tbody>
    						</table>
						</div>
                      </div>
                      
                    <br>
                    
                    <div class="row" style="">
                        
                      <div class="col-sm-12 col-12">
                          
                        <table class="table table-border" style="font-size:18px">
                          <thead >
                            <tr>
                              <th style="width: 1px; border: 1px solid black !important;">SL.</th>
                              <th style="border: 1px solid black !important;">Brand</th>
                              <!--<th style="border: 1px solid black !important;">Model</th>-->
                              <th style="border: 1px solid black !important;">Part No</th>
                              <!--<th style="border: 1px solid black !important;">HS Code</th>-->
                              <th style="border: 1px solid black !important;">Product Name</th>
                              <!--<?php if($prints['saType'] == 2){ ?><th style="border: 1px solid black !important;">Rate</th><?php } ?>-->
                              <th style="border: 1px solid black !important;">Quantity</th>
                              <th style="border: 1px solid black !important;">Price</th>
                              <th style="border: 1px solid black !important;">Discount</th>
                              <th style="border: 1px solid black !important;">Total</th>
                            </tr>
                          </thead>
                          
                          <tbody>
                            <?php
                            $i = 0;
                            $tq = 0;
                            $stotal = 0;
                            // var_dump($salesp);
                            foreach ($salesp as $value){
                            $i++;
                            
                            ?>
                            <tr>
                              <td style="border: 1px solid black !important; border-bottom: 2px solid black !important;"><?php echo $i; ?></td>
                              <td style="border: 1px solid black !important; border-bottom: 2px solid black !important;"><?php echo $value['catName']; ?></td>
                              <!--<td style="border: 1px solid black !important; border-bottom: 2px solid black !important;"><?php echo $value['spColor']; ?></td>-->
                              <td style="border: 1px solid black !important; border-bottom: 2px solid black !important;"><?php echo $value['spChassis']; ?></td>
                              <!--<td style="border: 1px solid black !important; border-bottom: 2px solid black !important;"><?php echo $value['spEngine']; ?></td>-->
                              <td style="border: 1px solid black !important; border-bottom: 2px solid black !important;"><?php echo $value['pName']; ?></td>
                              <!--<?php if($prints['saType'] == 2){ ?><td style="border: 1px solid black !important; border-bottom: 2px solid black !important;"><?php echo number_format($value['iRate'], 2); ?></td><?php } ?>-->

                              <td style="text-align:center; width:5px; border: 1px solid black !important; border-bottom: 2px solid black !important;">
                                <?php 
                                $rounded_quantity = round($value['quantity']);
                                echo $rounded_quantity; 
                                $tq += $value['quantity'];
                                echo ' ' . $value['unitName']; 
                                ?>
                              </td>

                              
                            <td style="text-align:right; border: 1px solid black !important; border-bottom: 2px solid black !important;"><?php echo number_format($value['sprice']+$value['iprice'], 2); ?></td>
                            <td style="text-align:right; border: 1px solid black !important; border-bottom: 2px solid black !important;"><?php echo $value['pdiscount']; ?></td>
                            <td style="text-align:right; border: 1px solid black !important; border-bottom: 2px solid black !important;"><?php echo number_format($value['tprice'], 2); $stotal += $value['tprice']; ?></td>
                            </tr>
                            <?php } ?>
                            
                          </tbody>
                          
                          
                          
                          <tbody>
                            <tr>
                              <td colspan="4"></td>
                              <td colspan="3" style="text-align:right; border: 1px solid black !important;"><b>Invoice Total :</b></td>
                              <!--<td><b><?php echo $tq; ?></b></td>-->
                              <td style="text-align:right; border: 1px solid black !important;"><b><?php echo number_format($stotal+$prints['vat'], 2); ?></b></td>
                            </tr>
                            <tr>
                              <td colspan="4"></td>
                              <td colspan="3" style="text-align:right; border: 1px solid black !important;"><b>Discount :</b></td>
                              <!--<td><b><?php echo $tq; ?></b></td>-->
                              <td style="text-align:right; border: 1px solid black !important;"><b><?php echo number_format($prints['disAmount'], 2); ?></b></td>
                            </tr>
                              <?php
                              $p3ay = $this->db->select("SUM(sales_payment.pAmount) as total,sales.custid")
                                                ->FROM('sales_payment')
                                                ->join('sales', 'sales.said = sales_payment.said', 'left')
                                                ->where_not_in('sales_payment.said',$prints['said'])
                                                ->where_in('custid',$prints['custid'])
                                                ->get()
                                                ->row();
                                if($p3ay)
                                  {
                                  $t3pay = $p3ay->total;
                                  }
                                else
                                  {
                                  $t3pay = 0;
                                  }
                                
                                $returns = $this->db->select("pAmount")
                                                ->FROM('returns')
                                                ->where('invoice',$prints['invoice'])
                                                ->get()
                                                ->row();
                                if($returns)
                                  {
                                  $trpay = $returns->pAmount;
                                  }
                                else
                                  {
                                  $trpay = 0;
                                  }
                                  ?>
                            <?php if($trpay){ ?>
                            <tr style="">
                              <td colspan="4"></td>
                              <td colspan="3" style="text-align:right;  border: 1px solid black !important;"><b>Net Amount :</b></td>
                              <td style="text-align:right;  border: 1px solid black !important;"><b><?php echo number_format($trpay, 2); ?></b></td>
                            </tr>
                            <tr style="">
                              <td colspan="4"></td>
                              <td colspan="3" style="text-align:right;  border: 1px solid black !important;"><b>Return Amount :</b></td>
                              <td style="text-align:right;  border: 1px solid black !important;"><b><?php echo number_format($trpay, 2); ?></b></td>
                            </tr>
                            <?php }else{ ?>
                            <tr style="">
                              <td colspan="4"></td>
                              <td colspan="3" style="text-align:right;  border: 1px solid black !important;"><b>Previous Due :</b></td>
                              <td style="text-align:right;  border: 1px solid black !important;"><b><?php echo number_format(($csdue->total-($cvpa->total+$cra->total+$t3pay)), 2); ?></b></td>
                            </tr>
                              
                            <tr style=""> 
                              <td colspan="4"></td>
                              <td colspan="3" style="text-align:right;  border: 1px solid black !important;"><b>Net Amount :</b></td>
                                  
                                  <td style="text-align:right;  border: 1px solid black !important;">
                                    <b>
                                      <?php
                                      $invoiceTotal = $stotal - $prints['disAmount'] + $prints['vat'];
                                      $previousDue = $csdue->total - ($cvpa->total + $cra->total+$t3pay);
                                      echo number_format($invoiceTotal+$previousDue, 2);
                                      ?>
                                    </b>
                                  </td>
                            </tr>
                              <?php
                              $pay = $this->db->select("SUM(pAmount) as total")
                                                ->FROM('sales_payment')
                                                ->WHERE('said',$prints['said'])
                                                ->get()
                                                ->row();
                                if($pay)
                                  {
                                  $tpay = $pay->total;
                                  }
                                else
                                  {
                                  $tpay = 0;
                                  }
                              ?>
                            <tr style="">  
                              <td colspan="4"></td>
                              <td colspan="3" style="text-align:right;  border: 1px solid black !important;"><b>Paid :</b></td>
                              <td style="text-align:right;  border: 1px solid black !important;"><b><?php echo number_format($prints['pAmount']+$tpay, 2); ?></b></td>
                            </tr>
                            
                            <tr> 
                              <td colspan="4"></td>
                              <td colspan="3" style="text-align:right;  border: 1px solid black !important; border-bottom: 2px solid black !important;"><b>Current Due :</b></td>
                               
                                <td style="text-align:right;  border: 1px solid black !important; border-bottom: 2px solid black !important;" >
                                    <b>
                                    <?php
                                        $totalDue = $invoiceTotal+$previousDue;
                                        $paidAmount = $prints['pAmount']+$tpay;
                                        $currentDue = $totalDue - $paidAmount;
                                        echo number_format($currentDue, 2);
                                    ?>
                                    </b>
                                </td>
                            </tr>
                            
                            <?php } ?>
                            
                          </tbody>
                          
                          
                          
                          <tbody>
                            <tr>
                                
                                
                              <td colspan="3">
                                  
                                <!--<div class="col-md-6 col-6" >We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.</div><br>-->
                                <div class="col-md-12 col-12" style="margin-top:8rem;">
                                    
                                    <div class="row" style="">
                                        
                                   
                                        
                                      <div class="col-md-6 col-sm-6 col-6" >
                                        <p class="lead1" style=" ">Note: <?php echo $prints['note']; ?></p> 
                                        <p>------------------------------</p>
                                        <p>Accounts Officer</p>
                                      </div>  
                                      
                                      <div class="col-md-6 col-sm-6 col-6" style="margin-top:45px">
                                        <p>------------------------------</p>
                                        <p>Authorized Signature</p>
                                      </div>
                                      
                                    </div>
                                    
                                    
                                </div>
                                
                              </td>
                              
                              <!--<td colspan="2">-->
                              <!--    <tr>-->
                              <!--        <td colspan="6" ></td>-->
                              <!--        <td colspan="2" style="text-align:right;"><b>Authorized Signatory</b></td>-->
                              <!--    </tr>-->
                                  
                              <!--</td>-->
                              
                              
                            </tr>
                            
                          </tbody>
                          
                          
                            <tr>
                                <?php $twa = round(abs($stotal)); ?>
                                <td colspan="5" style="font-size:1rem;">
                                    <!--<b>Amount In words:</b> <?php echo convertNumber($twa); ?><br>-->
                                    <b>Total in Word (Invoice Due): </b><?php echo convertNumber($twa); ?> <br>
                                    <b>Comment : </b> <?php echo $prints['comment']; ?> <br>
                                    
                                    <?php date_default_timezone_set('Asia/Dhaka'); // Set the timezone to BDT
                                        ?>
                                    <b>Created By : </b> <?php echo $prints['compname']; ?> , <b>Create Time : </b> <?php echo date('d-m-Y h:i:s A', strtotime($prints['regdate'])); ?>
                                    
                                </td>
                                <!--<td style="text-align:right;"><b>Discount:</b></td>-->
                                <!--<td style="text-align:right;">00</td>-->
                            </tr>
                          
                          
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                  <div class="row no-print" >
                    <div class="col-12" style="text-align: center;">
                      <a href="javascript:void(0)" class="btn btn-primary" onclick="printDiv('print')" ><i class="fas fa-print"></i> Print</a>
                      <a href="<?php echo site_url().'pdfSale/'.$prints['said']; ?>" class="btn btn-success" ><i class="fa fa-file"></i> Dowanload PDF</a>
                      <a href="<?php echo site_url('Sale') ?>" class="btn btn-danger" ><i class="fas fa-arrow-left"></i> Back</a>
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