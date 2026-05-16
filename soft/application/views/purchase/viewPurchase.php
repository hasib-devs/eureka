<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Purchase Order</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Purchase Order</li>
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
                <h3 class="card-title">Purchase Order Information</h3>
              </div>

              <div class="card-body">
                <div class="invoice p-3 mb-3">
                  <div id="print" style="margin-bottom:100px;">
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
                    <div class="row">
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
                        <b>Challan No. # <?php echo $purchase['challanNo']; ?></b><br>
                        <b>Payment Mode :</b> <?php echo $purchase['accountType']; ?><br>
                        <b>Purchase Date :</b> <?php echo date('d-M-Y', strtotime($purchase['puDate'])); ?><br>
                        <b>Purchase Type :</b> <?php echo $purchase['ptName']; ?>
                      </div>
                    </div>
                      
                    

                    <div class="row">
                      <div class="col-12 table-responsive">
                        <table class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>SL.</th>
                              <th>Brand</th>
                              <th>Model</th>
                              <th>Part No</th>
                              <th>Product Name</th>
                              <th>Quantity</th>
                              <th>Price</th>
                              <th>Total</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $i = 0;
                            $tq = 0;
                            $tpa = 0;
                            foreach ($pproduct as $value) {
                            $i++;
                            $pp = $this->db->select('*')
                                      ->from('purchase_chassis')
                                      ->where('puid',$value['puid'])
                                      ->where('ppid',$value['ppid'])
                                      ->where('pid',$value['pid'])
                                      ->get()
                                      ->result();
                            ?>
                            <tr>
                              <td><?php echo $i; ?></td>
                              <td><?php echo $value['brand']; ?></td>
                              <td><?php echo $value['model']; ?></td>
                              <td><?php echo $value['partNo']; ?></td>
                              <td><?php echo $value['pName']; ?></td>
                              <td><?php echo round($value['quantity']); $tq += $value['quantity']; ?></td>
                              <td><?php echo number_format($value['pprice'], 2); ?></td>
                              <td><?php echo number_format($value['tprice'], 2); $tpa += $value['tprice']; ?></td>
                            </tr>
                            <?php } ?>
                          </tbody>
                          <tbody>
                            <tr>
                              <td colspan="7" align="right">Total Amount :</td>
                              <td><?php echo number_format($tpa + $purchase['vAmount'], 2); ?></td>
                            </tr>
                          </tbody>
                          <tbody style="text-align: center;">
                            <tr>
                              <?php $twa = round(abs($tpa)); ?>
                              <td colspan="8">( In Words&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo convertNumber($twa); ?> )</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>

                    <div class="row">
                      <p class="lead">Note / Remarks&nbsp;:&nbsp;</p>
                      <p class="lead"><?php echo $purchase['note']; ?></p>
                    </div>
                    <br><br><br><br><br><br><br><br><br><br><br><br>
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
                      <a href="<?php echo site_url('Purchase') ?>" class="btn btn-danger" ><i class="fas fa-arrow-left"></i> Back</a>
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