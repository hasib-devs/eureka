<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Quotation</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Quotation</li>
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
                <h3 class="card-title">Quotation Information</h3>
              </div>

              <div class="card-body">
                <div class="invoice p-3 mb-3">
                  <div id="print">
                    <div class="row invoice-info">
                      <div class="col-sm-12 col-12 invoice-col text-center">
                        <?php if($company){ ?><h3><b><?php echo $company->com_name; ?></b></h3><?php } ?>
                          <p style=""><?php if($company){ ?><?php echo $company->com_address; ?><?php } ?><br>
                          Phone : <?php if($company){ ?><?php echo $company->com_mobile; ?><?php } ?><br>
                          Email : <?php if($company){ ?><?php echo $company->com_email; ?><?php } ?>
                      </div>
                    </div><hr>
                    
                    <div class="row">
                      <div class="col-sm-5 col-5 invoice-col">
                        <table class="table table-borderless" style="font-size:18px">
    					  <tbody>
							<tr>
							  <td>
							    Invoice No : <b><?php echo $quotation['qinvoice']; ?></b> <br>
							    Customer :  <b><?php echo $quotation['custName']; ?></b> <br>
							    Mobile No : <b><?php echo $quotation['custMobile']; ?></b> <br>
							    Address :  <b><?php echo $quotation['custAddress']; ?></b>
							  </td>
							</tr>
    					  </tbody>
    					</table>
					  </div>
                      <div class="col-sm-5 col-5 invoice-col"></div>
                      <div class="col-sm-2 col-2 invoice-col">
                        <table class="table table-borderless" style="font-size:18px">
						  <tbody>
							<tr style="text-align:right">
							  <td>Date : <?php echo date('d-m-Y', strtotime($quotation['qutDate'])); ?></td>
							</tr>
    					  </tbody>
    					</table>
					  </div>
                    </div><br>
                    
                    <div class="row" style="">
                      <div class="col-sm-12 col-12">
                        <table class="table table-border" style="font-size:18px">
                          <thead >
                            <tr>
                              <tr>
                              <th style="width: 1px; border: 1px solid black !important;">SL.</th>
                              <th style="border: 1px solid black !important;">Brand</th>
                              <th style="border: 1px solid black !important;">Part No</th>
                              <th style="border: 1px solid black !important;">Product Name</th>
                              <th style="border: 1px solid black !important;">Quantity</th>
                              <th style="border: 1px solid black !important;">Price</th>
                              <th style="border: 1px solid black !important;">Total</th>
                            </tr>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $i = 0;
                            $tq = 0;
                            $stotal = 0;
                            foreach($pquotation as $value){
                            $i++;
                            ?>
                            <tr>
                              <td style="border: 1px solid black !important; border-bottom: 2px solid black !important;"><?php echo $i; ?></td>
                              <td style="border: 1px solid black !important; border-bottom: 2px solid black !important;"><?php echo $value['brand']; ?></td>
                              <td style="border: 1px solid black !important; border-bottom: 2px solid black !important;"><?php echo $value['partNo']; ?></td>
                              <td style="border: 1px solid black !important; border-bottom: 2px solid black !important;"><?php echo $value['pName']; ?></td>
                              
                              <td style="text-align:center; width:5px; border: 1px solid black !important; border-bottom: 2px solid black !important;">
                                <?php echo $value['quantity']; $tq += $value['quantity']; ?>
                              </td>
                              <td style="text-align:right; border: 1px solid black !important; border-bottom: 2px solid black !important;"><?php echo number_format($value['sprice'], 2);; ?></td>
                              <td style="text-align:right; border: 1px solid black !important; border-bottom: 2px solid black !important;"><?php echo number_format($value['tprice'], 2); $stotal += $value['tprice']; ?></td>
                            </tr>
                            <?php } ?>
                          </tbody>
                          <tbody>
                            <tr>
                              <td colspan="5"></td>
                              <td style="text-align:right; border: 1px solid black !important;"><b>Invoice Total :</b></td>
                              <td style="text-align:right; border: 1px solid black !important;"><b><?php echo number_format($stotal, 2); ?></b></td>
                            </tr>
                          </tbody>
                          <tbody>
                            <tr style="margin-top:8rem;" >
                                
                              <td colspan="4">
                                <p class="lead1" style=" ">Note: <?php echo $quotation['note']; ?></p> 
                                <div class="col-md-12 col-sm-12 col-12" style="text-align: center;"  >
                                    <p>------------------------------</p>
                                    <p>Accounts Officer</p>
                                </div>
                              </td>
                              <td colspan="3">
                                <div class="col-md-12 col-sm-12 col-12" style="margin-top:45px; text-align: center;">
                                    <p>------------------------------</p>
                                    <p>Authorized Signature</p>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                          <tbody>
                            <tr>
                              <td colspan="5" style="font-size:1rem;">
                                <b>Total in Word (Invoice Due): </b><?php echo convertNumber(round($stotal), 2); ?> <br>
                                    
                                <?php date_default_timezone_set('Asia/Dhaka');  ?>
                                <b>Created By : </b> <?php echo $quotation['compname']; ?> , <b>Create Time : </b> <?php echo date('d-m-Y h:i:s A', strtotime($quotation['regdate'])); ?>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-12" style="text-align: center;margin-top: 20px">
                    <a href="javascript:void(0)" onclick="printDiv('print')" class="btn btn-primary"><i class="fa fa-print"> </i> Print</a>
                    <a href="<?php echo site_url().'pdfQuotation/'.$quotation['qutid']; ?>" class="btn btn-success" ><i class="fa fa-file"></i> Dowanload PDF</a>
                    <a href="<?php echo site_url('Quotation') ?>" class="btn btn-danger" ><i class="fas fa-arrow-left"></i> Back</a>
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