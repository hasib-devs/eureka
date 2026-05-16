<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>LC Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">LC Management</li>
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
                <h3 class="card-title">LC Management Information</h3>
              </div>

              <div class="card-body">
                <div class="invoice p-3 mb-3">
                  <div id="print" style="margin-bottom:100px;">
                   <br><br><br><br><br><br><br><br>
                    <div class="row">
                      <div class="col-12">
                        <h4>
                          <!--<?php if($company){ ?><img src="<?php echo base_url().'upload/company/'.$company->com_logo; ?>" style="height:50px; width:auto;">&nbsp;&nbsp;<?php echo $company->com_name; ?><?php } ?>-->
                          <small class="float-right">Print Date : <?php echo date('d-M-Y'); ?></small>
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
                          Supplier : <?php echo $purchase['supName'].' ( '.$purchase['supCode'].' )'; ?><br>
                          Phone : <?php echo $purchase['supMobile']; ?><br>
                          Address : <?php echo $purchase['supAddress']; ?><br>
                        </address>
                      </div>
                      <div class="col-sm-4 col-4 invoice-col">
                        <b>Challan No. # <?php echo $purchase['lcCode']; ?></b><br>
                        <b>LC Date :</b> <?php echo date('d-M-Y', strtotime($purchase['lcDate'])); ?>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <table class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>SN</th>
                              <th>Product</th>
                              <th>HS Code</th>
                              <th>Weight ( KG )</th>
                              <th>USD Per KG</th>
                              <th>Total USD</th>
                              <th>Quantity</th>
                              <th>USD Per Unit</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $i = 0;
                            $tq = 0;
                            foreach ($pproduct as $value) {
                            $i++;
                            ?>
                            <tr>
                              <td><?php echo $i; ?></td>
                              <td><?php echo $value['pName']; ?></td>
                              <td><?php echo $value['hscode']; ?></td>
                              <td><?php echo number_format($value['weight'], 2); ?></td>
                              <td><?php echo number_format($value['uprice'], 2); ?></td>
                              <td><?php echo number_format($value['tprice'], 2); ?></td>
                              <td><?php echo round($value['quantity']); $tq += $value['quantity']; ?></td>
                              <td><?php echo number_format($value['upprice'], 2); ?></td>
                            </tr>
                            <?php } ?>
                          </tbody>
                          <tbody>
                            <tr>
                              <td colspan="5" align="right">Total Amount</td>
                              <td><?php echo number_format($purchase['tAmount'], 2); ?></td>
                              <td><?php echo $tq; ?></td>
                              <td></td>
                            </tr>
                            <tr>
                              <td colspan="5" align="right">Paid Amount</td>
                              <td><?php echo number_format($purchase['pAmount'], 2); ?></td>
                              <td colspan="2" ></td>
                            </tr>
                            <tr>
                              <td colspan="5" align="right">Due Amount</td>
                              <td><?php echo number_format($purchase['dAmount'], 2); ?></td>
                              <td colspan="2" ></td>
                            </tr>
                          </tbody>
                          <tbody style="text-align: center;">
                            <tr>
                              <?php $twa = round(abs($purchase['pAmount'])); ?>
                              <td colspan="8">( In Words&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo convertNumber($twa); ?> )</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>

                    <div class="row">
                      <p class="lead">Note / Remarks&nbsp;:&nbsp;</p>
                      <p class="lead"><?php echo $purchase['notes']; ?></p>
                    </div><br><br><br><br><br><br><br><br><br><br><br><br>
                    
                    <div class="row">
                      <div class="col-md-12 col-12" style="text-align: center;">
                        <div class="row">
                          <div class="col-md-4 col-sm-4 col-4">
                            <p>------------------------------</p>
                            <p>Supplier</p>
                          </div>
                          <div class="col-md-4 col-sm-4 col-4">
                            <p>------------------------------</p>
                            <p>Verified By</p>
                          </div>
                          <div class="col-md-4 col-sm-4 col-4">
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
                      <a href="<?php echo site_url() ?>Lcmanagement" class="btn btn-danger" ><i class="fas fa-arrow-left"></i> Back</a>
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