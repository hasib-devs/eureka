<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>
  
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sales</h1>
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
                      <div class="col-sm-5 col-5 invoice-col">
                        <?php if($company){ ?><img src="<?php echo base_url().'upload/company/'.$company->com_logo; ?>" style="height:80px; width:auto;"><?php } ?><br>
                      </div>
                      <div class="col-sm-7 col-7 invoice-col">
                          <!--<?php if($company){ ?><h3><b><?php echo $company->com_name; ?></b></h3><?php } ?>-->
                        <p style=""><?php if($company){ ?><?php echo $company->com_address; ?><?php } ?><br>
                        Phone : <?php if($company){ ?><?php echo $company->com_mobile; ?><?php } ?>, 
                        Email : <?php if($company){ ?><?php echo $company->com_email; ?><?php } ?><br>
                      </div>
                    </div><hr>
                      <div class="row">
                        <div class="col-sm-4 col-4"></div>
                        <div class="col-sm-4 col-4" style="text-align:center;">
                            <p style="border:1px solid black;text-align:center;font-size:2rem;font-weight:bold;box-shadow: 5px 10px #888">INVOICE</p>
                        </div>
                        <div class="col-sm-4 col-4"></div>
                      </div>
                      <div class="row">
                        <div class="col-sm-6 col-6 invoice-col">
                          <table class="table table-borderless table-responsive " style="font-weight:bold;font-size:13px">
							<tbody>
							  <tr style="line-height: 6px !important;">
								<td>Invoice No. </td>
								<td>: <?php echo $prints['invoice']; ?></td>
							  </tr>
							  <tr style="line-height: 6px !important;">
								<td>Order No.</td>
								<td>: <?php echo $prints['invoice']; ?></td>
							  </tr>
							  <tr style="line-height: 6px !important;">
								<td>Customer Name </td>
								<td>: <?php echo $prints['custName']; ?></td>
							  </tr>
							  <tr style="line-height: 6px !important;">
								<td>Address</td>
								<td>: <?php echo $prints['custAddress']; ?></td>
							  </tr>
							  <tr style="line-height: 6px !important;">
								<td>Phone</td>
								<td>: <?php echo $prints['custMobile']; ?></td>
							  </tr>
							</tbody>
						  </table>
						</div>
                        <div class="col-sm-4 col-4 invoice-col"></div>
                        <div class="col-sm-2 col-2 invoice-col">
                          <table class="table table-borderless" style="font-weight:bold;font-size:13px">
							<tbody>
							  <tr style="line-height: 3px !important;">
								<td>Date </td>
								<td>: <?php echo date('d-m-Y', strtotime($prints['saDate'])); ?></td>
							  </tr>
							  <tr style="line-height: 3px !important;">
								<td>Sales Man </td>
								<td>: <?php echo $prints['name']; ?></td>
							  </tr>
							</tbody>
						  </table>
						</div>
                      </div>
                    <br><br>
                    <div class="row" style="">
                      <div class="col-sm-12 col-12">
                        <table class="table table-bordered">
                          <thead>
                            <tr style="background-color: rgba(0,0,0,.05);">
                              <th>SL No.</th>
                              <th>Part No.</th>
                              <th>Description of Goods</th>
                              <th>Reg No.</th>
                              <th>Engine No</th>
                              <th>Chassis No</th>
                              <th>Qty</th>
                              <th>Unit Price</th>
                              <th style="text-align:right;">Amount</th>
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
                              <td colspan="8" style="font-size:1.2rem;font-weight:bold;color:blue;">Product Model:</td>
                            </tr>
                            <tr>
                              <td><?php echo $i; ?></td>
                              <td><?php echo $value['pCode']; ?></td>
                              <td><?php echo 'Brand: '.$value['pName'].'<br>Model: '.$value['model'].'<br>Colour: '.$value['pColor'].'<br>CC: '.$value['capacity'].'<br>Origin: '.$value['mkCountry'].'<br>Weight: '.$value['uWeight'].' Kg'; ?></td>
                              <td><?php echo $value['prNumber']; ?></td>
                              <td><?php echo $value['spEngine']; ?></td>
                              <td><?php echo $value['spChassis']; ?></td>
                              <td><?php echo round($value['quantity']); $tq += $value['quantity']; ?></td>
                              <td><?php echo number_format($value['sprice'], 2);; ?></td>
                              <td style="text-align:right;"><?php echo number_format($value['tprice'], 2); $stotal += $value['tprice']; ?></td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <?php $twa = round(abs($prints['pAmount']+$spay->total)); ?>
                                <td colspan="6" style="font-size:1rem;">
                                    <b>Amount In words:</b> <?php echo convertNumber($twa); ?><br>
                                    <b>Prepared By:</b> <?php echo $prints['compname']; ?>
                                </td>
                                <td style="text-align:right;"><b>Discount:</b></td>
                                <td style="text-align:right;">00</td>
                            </tr>
                          </tbody>
                          <tbody>
                            <tr style="background-color: rgba(0,0,0,.05);">
                              <td colspan="6"></td>
                              <td style="text-align:right;"><b>Grand Total :</b></td>
                              <!--<td><b><?php echo $tq; ?></b></td>-->
                              <td style="text-align:right;"><b><?php echo number_format($stotal-$prints['disAmount']+$prints['vat'], 2); ?></b></td>
                            </tr>
                          </tbody>
                          <tbody>
                            <tr>
                              <td colspan="6">
                                <div class="col-md-6 col-6" >We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.</div><br>
                                <div class="col-md-12 col-12" style="margin-top:2rem;">
                                    <div class="row" style="text-align:center;">
                                      <div class="col-md-6 col-sm-6 col-6" >
                                        <p>------------------------------</p>
                                        <p>Received By</p>
                                      </div>
                                      <div class="col-md-6 col-sm-6 col-6">
                                        <p>------------------------------</p>
                                        <p>Dealt By</p>
                                      </div>
                                    </div>
                                </div>
                                
                              </td>
                              <td colspan="2">
                                  <tr>
                                      <td colspan="6" ></td>
                                      <td colspan="2" style="text-align:right;"><b>Authorized Signatory</b></td>
                                  </tr>
                                  
                              </td>
                              <!--<td colspan="4">-->
                              <!--  <table style="width: 100%;">-->
                              <!--    <tr>-->
                              <!--      <td><b>Discount Amount</b></td>-->
                              <!--      <td style="text-align:right;"><?php echo number_format($prints['disAmount'], 2); ?></td>-->
                              <!--    </tr>-->
                              <!--    <tr>-->
                              <!--      <td><b>VAT Amount</b></td>-->
                              <!--      <td style="text-align:right;"><?php echo number_format($prints['vat'], 2); ?></td>-->
                              <!--    </tr>-->
                              <!--    <tr>-->
                              <!--      <td><b>Net Amount</b></td>-->
                              <!--      <td style="text-align:right;"><?php echo number_format((($prints['tAmount']+$prints['vat'])-$prints['disAmount']), 2); ?></td>-->
                              <!--    </tr>-->
                              <!--    <tr>-->
                              <!--      <td><b>Paid Amount</b></td>-->
                              <!--      <td style="text-align:right;"><?php echo number_format($prints['pAmount']+$spay->total, 2); ?></td>-->
                              <!--    </tr>-->
                              <!--    <tr>-->
                              <!--      <td><b>Due Amount</b></td>-->
                              <!--      <td style="text-align:right;"><?php echo number_format($prints['dAmount']-$spay->total, 2); ?></td>-->
                              <!--    </tr>-->
                              <!--  </table>-->
                              <!--</td>-->
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    
                    <!--<div class="row" id="header" style="display: none">-->
                    <!--  <div class="col-md-12 col-12" style="text-align: center; position: absolute; bottom: 0;">-->
                    <!--    <div class="row">-->
                    <!--      <div class="col-md-6 col-sm-6 col-6" >-->
                    <!--        <p>------------------------------</p>-->
                    <!--        <p>Received By</p>-->
                    <!--      </div>-->
                    <!--      <div class="col-md-6 col-sm-6 col-6">-->
                    <!--        <p>------------------------------</p>-->
                    <!--        <p>Authorized By</p>-->
                    <!--      </div>-->
                    <!--    </div>-->
                    <!--  </div>-->
                    <!--</div>-->
                    
                    <div class="col-md-12 col-12">
                        <p style="border:3px solid red; padding:10px;border-radius:15px;">অত্র গাড়ী কেনার কাগজপত্র ২০ দিনের মধ্যে মালিকানা পরিবর্তন করিয়া নিব এবং নিতে বাধ্য থাকিব। কোন ত্রুটি বিচ্যুতি থাকিলে ২০ দিনের পরে কোন অভিযোগ 
                        করিব না। করিলে তা গ্রহণযোগ্য হইবে না, এবং ২০ দিনের মধ্যে কোন দূর্ঘটনা অথবা গাড়ী নিয়ে কোন আইন বিরোধী কাজে লিপ্ত হলে এস এম যে বাইকস দায়ী থাকিবে না।</p>
                    </div>
                  </div>
                </div>

                  <div class="row no-print" >
                    <div class="col-12" style="text-align: center;">
                      <a href="javascript:void(0)" class="btn btn-primary" onclick="printDiv('print')" ><i class="fas fa-print"></i> Print</a>
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