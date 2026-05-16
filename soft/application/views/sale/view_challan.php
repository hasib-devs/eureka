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
                <h3 class="card-title">Delivery Challan Information</h3>
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
    							</tbody>
    						</table>
						</div>
						
						
                        <div class="col-sm-1 col-1 invoice-col">
                          
                        </div>
                        <div class="col-sm-4 col-4 invoice-col">
                            
                            <table class="table table-borderless" style="font-size:18px">
    							<tbody>
    								<tr>
    									<td>
    									<h4><b>Delivery Challan</b></h4>
    									Date : <?php echo date('d-m-Y', strtotime($prints['saDate'])); ?><br>
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
                              <th style="border: 1px solid black !important;">Part No</th>
                              <th style="border: 1px solid black !important;">Product Name</th>
                              <th style="border: 1px solid black !important;">Quantity</th>
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
                              <td style="border: 1px solid black !important; border-bottom: 2px solid black !important;"><?php echo $value['spChassis']; ?></td>
                              <td style="border: 1px solid black !important; border-bottom: 2px solid black !important;"><?php echo $value['pName']; ?></td>
                              <td style="text-align:center; width:5px; border: 1px solid black !important; border-bottom: 2px solid black !important;">
                                <?php 
                                $rounded_quantity = round($value['quantity']);
                                echo $rounded_quantity; 
                                $tq += $value['quantity'];
                                echo ' ' . $value['unitName']; 
                                ?>
                              </td>
                            </tr>
                            <?php } ?>
                          </tbody>
                          <tbody>
                            <tr>
                                
                                
                              <td colspan="3">
                                  
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
                            </tr>
                            
                          </tbody>
                          
                          
                            <tr>
                                <?php $twa = round(abs($stotal)); ?>
                                <td colspan="5" style="font-size:1rem;">
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
