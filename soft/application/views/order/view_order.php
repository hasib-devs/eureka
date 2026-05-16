<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Pre-Order</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Pre-Order</li>
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
                <h3 class="card-title">Pre-Order Information</h3>
              </div>

              <div class="card-body">
                <div class="invoice p-3 mb-3">
                  <div id="print">
                    <div class="row">
                      <div class="col-12">
                        <h4>
                          <?php if($company){ ?><img src="<?php echo base_url().'upload/company/'.$company->com_logo; ?>" style="height:50px; width:auto;">&nbsp;&nbsp;<?php echo $company->com_name; ?><?php } ?>
                        </h4>
                      </div>
                    </div>
                    <div class="row invoice-info">
                      <div class="col-sm-4 col-4 invoice-col">
                        From
                        <address>
                          Address : <?php if($company){ ?><?php echo $company->com_address; ?><?php } ?><br>
                          Phone : <?php if($company){ ?><?php echo $company->com_mobile; ?><?php } ?><br>
                          Email : <?php if($company){ ?><?php echo $company->com_email; ?><?php } ?><br>
                        </address>
                      </div>
                      <div class="col-sm-4 col-4 invoice-col">
                        To
                        <address>
                          Customer : <?php echo $quotation['custName']; ?><br>
                          Phone : <?php echo $quotation['custMobile']; ?><br>
                          Address : <?php echo $quotation['custAddress']; ?><br>
                        </address>
                      </div>
                      <div class="col-sm-4 col-4 invoice-col">
                        <b>Order No. # <?php echo $quotation['oCode']; ?></b><br>
                        <b>Order Date :</b> <?php echo date('d-M-Y', strtotime($quotation['oDate'])); ?><br>
                        <b>Order Create :</b> <?php echo $quotation['name']; ?>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-sm-12 col-md-12 col-12">
                        <h3 style="text-align: center;"><b>Order Invoice</b></h3>
                      </div>
                      <div class="col-md-12 col-sm-12 col-12">
                        <table class="table table-bordered">
                          <thead class="btn-default">
                            <tr>
                              <th style="width: 5%;">#SN.</th>
                              <th>Product</th>
                              <th>Quantity</th>      
                              <th>Unit Price</th>
                              <th>Total Price</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $i = 0;
                            $ta = 0;
                            foreach($pquotation as $value){
                            $i++;
                            ?>
                            <tr>
                              <td><?php echo $i; ?></td>
                              <td><?php echo $value['pName'].' ( '.$value['pCode'].' )'; ?></td>
                              <td><?php echo $value['oQnt']; ?></td>
                              <td><?php echo number_format($value['oPrice'], 2); ?></td>
                              <td><?php echo number_format($value['tPrice'], 2); $ta += $value['tPrice']; ?></td>
                            </tr>
                            <?php } ?>
                          </tbody>
                          <tbody>
                            <tr>
                              <td colspan="4" align="right" >Total Amount</td>
                              <td><?php echo number_format($ta, 2); ?></td>
                            </tr>
                            <tr>
                              <td colspan="4" align="right" >Paid Amount</td>
                              <td><?php echo number_format($quotation['pAmount'], 2); ?></td>
                            </tr>
                          </tbody>
                          <tbody style="text-align: center;">
                            <tr>
                              <?php $twa = abs($quotation['pAmount']); ?>
                              <td colspan="5">( In Words&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo convertNumber($twa); ?> )</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                        
                    <div class="row">
                      <div class="col-md-12 col-sm-12 col-12">
                        <div class="form-group col-md-12 col-sm-12 col-12">
                          Note / Remarks
                        </div>
                        <?php if($quotation['note']){ ?>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                          <?php echo $quotation['note']; ?>
                        </div>
                        <?php } ?>
                      </div>
                    </div>
                        
                    <div class="col-md-12 col-sm-12 col-12" style="text-align: center;">
                      <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                          <p>------------------------------</p>
                          <p>Prepared By</p>
                        </div>
                        <div class="col-md-4 col-sm-4 col-4">
                          <p>------------------------------</p>
                          <p>Verified By</p>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                          <p>------------------------------</p>
                          <p>Authorized By</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-12" style="text-align: center;margin-top: 20px">
                    <a href="javascript:void(0)" onclick="printDiv('print')" class="btn btn-primary"><i class="fa fa-print"> </i> Print</a>
                    <a href="<?php echo site_url('Order') ?>" class="btn btn-danger" ><i class="fas fa-arrow-left"></i> Back</a>
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