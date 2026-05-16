<?php $this->load->view('header/regHeader'); ?>

<style type="text/css">
    .fm_bg {
        font-size: 13px;
    }

    img {
        width: 100%;
    }

    .cus_tbl>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
        padding: 2px;
        line-height: 1.3;
        vertical-align: top;
    }

    .cus_tbl {
        font-size: 12px;
    }

    .table tr,
    th,
    td {
        border: 1px solid #ddd;
        width: 180px;
        height: 10px !important;

    }

    .table tr,
    th,
    td {
        padding: 0px 5px !important;

    }

    ul {
        padding-left: 10px !important;
    }

    .text_font {
        margin: 0px 0px !important;
        font-size: 15px;
        padding: 0px 0px !important;
    }

    .text_font tr,
    th,
    td {
        font-size: 13px !important;
    }

    .bio-data {
        line-height: 2.0em;
    }

    .photo {
        width: 100px;
        height: 120px;
        border: 1px solid black;
        float: right;
        overflow: hidden;
        clear: both;
        display: inline-block;
        margin: -90px 30px 0px 0px;
    }

    .banar {
        background: #f3f3f3;
        line-height: 2.4em;
    }

    .bnar_bottom {
        height: 80px;
    }

    .table1 tr td {
        height: 50px !important;
        line-height: 25px !important;
    }

    .table1 {
        margin-top: 20px;
        width: 90%;
        margin: 0 auto;

    }

    img.watermarkimage {
        display: block;
        position: absolute;
        opacity: .09;
        left: 135px !important;
        top: 472px;
        width: 522px;
        z-index: 100;
    }

    .container {
        width: 21cm;
        height: 29.7cm;
    }
    .gap{
        margin-top:200px;
    }
</style>

<div class="container fm_bg page" style="margin-top:10px;"> <br>
	
	<div class="row">
		<div class="col-md-12 col-print-12">

			<br>
			<br>
			<br>
			<p class="text-center text_font"><b>SALES RECEIPT</b></p>
			<br>
			<br>
			<div class="row">
				<div class="col-md-6 col-print-6"> <b>Memo No : <?php echo $prints['invoice']; ?></b> </div>
				<div class="col-md-3 col-print-3"></div>
				<div class="col-md-3 col-print-3"><b>Date : <?php echo date('d-M-Y', strtotime($prints['saDate'])); ?></b></div>
			</div>
			<br>

			<div class="row">
				<div class="col-md-3 col-print-3">To</div>
			</div>
			<div class="row">
				<div class="col-md-3 col-print-3">Name</div>
				<div class="col-md-4 col-print-4" style="text-transform: uppercase;">:<?php echo $prints['custName']; ?></div>
			</div>
			<div class="row">
				<div class="col-md-3 col-print-3">Father’s Name</div>
				<div class="col-md-4 col-print-4" style="text-transform: uppercase;">:<?php echo $prints['custfName']; ?></div>
			</div>
			<div class="row">
				<div class="col-md-3 col-print-3">Address</div>
				<div class="col-md-4 col-print-4" style="text-transform: uppercase;">:<?php echo $prints['custAddress']; ?></div>
			</div>
		








	<table class="table table-bordered table-striped" style="width:100%;margin-top: 50px;height: 100px;">
		<tbody>
		    <tr>
    			<th style="text-align:center;">SL</th>
    			<th style="text-align:center;">Description</th>
    			<th style="text-align:center;">Quantity</th>
    			<th style="text-align:center;">Rate</th>
    			<th style="text-align:center;">Amount</th>
	        </tr>

			<tr class="item-row">
    			<td style="text-align:center;">01</td>
    			<td>Engine No</td>
    			<td colspan="3"><?php echo $salesp['pEngine']; ?></td>
		    </tr>
			<tr class="item-row">
    			<td style="text-align:center;">02</td>
    			<td>Chassis No</td>
    			<td colspan="3"><?php echo $salesp['pChassis']; ?></td>
		    </tr>
			<tr class="item-row">
    			<td style="text-align:center;">03</td>
    			<td>Colour</td>
    			<td colspan="3"><?php echo $salesp['pColor']; ?></td>
		    </tr>
			<tr class="item-row" style="text-align:center;">
    			<td style="vertical-align:middle;">04</td>
    			<td style="vertical-align:middle;"><?php echo $salesp['pName'].'<br>Brand New<br>'.$salesp['catName'].'<br>Model: '.$salesp['pCode'].'<br>'.$salesp['capacity'].' CC'; ?></td>
    			<td style="vertical-align:middle;"><b><?php echo $salesp['quantity'].' ('.convertNumber($salesp['quantity']).')'; ?></b></td>
    			<td style="vertical-align:middle;font-weight:bold;"><?php echo $salesp['sprice'].'/='; ?></td>
    			<td style="vertical-align:middle;font-weight:bold;"><?php echo $salesp['tprice'].'/='; ?></td>
		    </tr>
				
	
	    </tbody>
	</table>
	
	
	<div class="row" style="margin-top: 80px;">
			 	<div class="row">
					<div class="col-md-6 col-print-6"><h5>Prepared By</h5></div>
					<div class="col-md-6 col-print-6 text-right"><h5>Authorized Signature</h5></div>
				</div>
	</div>

	
	</div>
</div>

<script type="text/javascript">
$(window).on("load", function()
{
	// install firefox addon in order to use this plugin
	if(window.jsPrintSetup)
	{
					// set page header
			jsPrintSetup.setOption('headerStrLeft', '');
			jsPrintSetup.setOption('headerStrCenter', '');
			jsPrintSetup.setOption('headerStrRight', '');
					// set empty page footer
			jsPrintSetup.setOption('footerStrLeft', '');
			jsPrintSetup.setOption('footerStrCenter', '');
			jsPrintSetup.setOption('footerStrRight', '');
			}
});
</script>
</div>

<?php $this->load->view('footer/regFooter'); ?>

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
        return ucwords(strtolower($number_to_words_string));
        }
    ?>