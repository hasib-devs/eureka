<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

<style>
    .invoice {
    	position: relative;
    	width: 70%;
    	margin: 5px auto;
    	background: #fff !important;
    	border: 1px solid #f4f4f4;
    }
    .col-xs-12{
        width:100%;
        float:left;
    }
</style>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Money Receipt</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Money Receipt</li>
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
                <h3 class="card-title">Money Receipt Information</h3>
              </div>

                <section class="invoice" style="margin-top:25px; padding-left: 15px; padding-right: 15px;border:1px solid lightgray;">
                    <div class="row no-print">
                        <div class="col-xs-12" style="margin-top:5px;">
                            <a href="<?php echo site_url('Voucher') ?>" class="btn btn-danger btn-xs pull-right"
                                style="margin-left:5px;"><i class="fa fa-backward"></i> Back</a>
                            <button class="btn btn-primary btn-xs pull-right" onclick="window.print();">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                        </div>
                    </div>
                    <div class="row" style="font-size: 13px;">
                
                        <div class="col-sm-12" style="margin-top:0px;">
                            <table style="width:40%;float: left;">
                                <tbody>
                                    <tr>
                                        <td>
                                            <img src="<?php echo base_url().'upload/company/'.$company->com_logo; ?>"
                                                style="height:70px; width:auto;padding-left:0%;">
                                            <p><b>Money Receipt No: 2210270005</b></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                
                            <table style="width:20%;float: left;">
                                <tbody>
                                    <tr>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;&nbsp;</td>
                                    </tr>
                                </tbody>
                            </table>
                
                            <table style="width:40%;float: left;line-height:15px;font-size:12px;">
                                <tbody>
                                    <tr>
                                        <td style="color:#009EDE;font-weight:bold;font-size:23px;line-height:23px;">
                                            M360 ICT </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;">
                                            Address: Gulshan Dhaka 1212 </td>
                                    </tr>
                                    <!---->
                                    <!--    <tr>-->
                                    <!--        <td style="font-size: 12px;">-->
                                    <!--            Address 2: -->
                                    <!--        </td>-->
                                    <!--    </tr>-->
                                    <tr>
                                        <td style="font-size:12px">
                                            Mobile: 09638336699 </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:12px;padding:2px;">
                                            Email: sup.m360ict@gmail.com </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:12px;padding:2px;">
                                            Website: </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:12px;padding:2px;">
                                            Facebook: </td>
                                    </tr>
                                </tbody>
                            </table>
                
                            <table style="width:100%">
                                <tbody>
                                    <tr>
                                        <td style="height:0px;"></td>
                                    </tr>
                                </tbody>
                            </table>
                
                            <div class="col-md-12">
                                <div class="col-md-4"></div>
                                <div class="col-md-4" style="color:white;text-align: center;">
                                    <h4 style="font-size: 15px;margin-top: 5px;margin-bottom: 5px;color: #000">MONEY RECEIPT</h4>
                                    <hr style="margin:0">
                                    <p style="margin-bottom: 5px;color: #000">Customer Copy</p>
                                </div>
                                <div class="col-md-4" style="text-align: right;">
                                    Date: <span style="border-bottom:dotted;">27/10/2022</span>
                
                                </div>
                            </div>
                
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4 style="word-spacing:3px;font-size: 14px;font-size: 14px">Recieved with thanks from Mrs./Ms/Mr
                                        <span style="border-bottom: dotted;">Rakibul islam</span>
                                    </h4>
                
                                    <!-- <h4>
                								On against of: <span>[ ]</span>
                							</h4> -->
                                    <h4 style="word-spacing:3px;font-size: 14px;line-height:1.5;">Address: <span
                                            style="border-bottom:dotted;"></span>
                                        <span style="border-bottom: dotted;"></span>
                
                                        Cash:
                                        <span style="border-bottom: dotted;">Rakibul islam</span>
                
                                        Total Received Amount:
                                        <span style="border-bottom: dotted;">5,000.00</span>
                
                                        <span>Amount in word BDT: <span style="border-bottom: dotted;">Five Thousand BDT only</span>
                                        </span>
                
                                        And Balance :
                
                                        <span style="border-bottom: dotted;">Due 58,000.00</span>
                                        <p>
                                            <b>Note :</b>
                                        </p>
                
                                    </h4>
                                </div>
                            </div>
                
                            <div class="row" style="height: 5px">
                                <!-- <div class="col-md-8"></div>
                						<div class="col-md-4">
                							<div class="text-right">
                								<div class="text-right">
                									<span style="font-size: 15px;"><b>&nbsp;&nbsp;</b></span>
                								</div>
                							</div>
                						</div> -->
                            </div>
                
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="text-left">
                                        <div class="text-left">
                                            <span style="font-size: 15px;"><b>&nbsp;&nbsp;</b></span>
                                            <hr style="margin-top: 0px; margin-bottom: 0px; border: 0; border-top: 1px solid black; width:120px;"
                                                align="left">
                                            <span style="font-size: 15px;">Customer Signature</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <div class="text-right">
                                        <div class="text-right">
                                            <span style="font-size: 15px;"><b>&nbsp;&nbsp;</b></span>
                                            <hr style="margin-top: 0px; margin-bottom: 0px; border: 0; border-top: 1px solid black; width: 100px;"
                                                align="right">
                                            <span style="font-size: 15px;">Cashier</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <table style="width:100%">
                                <tbody>
                                    <tr>
                                        <td style="text-align:center;background: lightgray !important;" colspan="5">
                                            <div id="thanksMsg" style="background: lightgray !important;">Thanks for your business with
                                                us</div>
                                        </td>
                                    </tr>
                
                                    <tr>
                                        <td style="text-align:center;font-size:11px;" colspan="5">Trabill Developed By: M360 ICT Ltd.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                
                        </div>
                    </div>
                </section>

                <div class="form-group col-md-12 col-sm-12 col-12" style="text-align: center; margin-top: 20px">
                  <a href="javascript:void(0)" onclick="printDiv('print')" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
                  <a href="<?php echo site_url('Voucher') ?>" class="btn btn-danger" ><i class="fa fa-arrow-left"></i> Back</a>
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