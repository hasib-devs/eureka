<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Costing</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Costing</li>
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
                <h3 class="card-title">Costing Information</h3>
              </div>

              <div class="card-body">
                <div class="invoice p-3 mb-3">
                  <div id="print" >
                    
                    <div class="row invoice-info">
                      <div class="col-sm-3 col-3 invoice-col" style="text-align: right;">
                        <?php if($company){ ?><img src="<?php echo base_url().'upload/company/'.$company->com_logo; ?>" style="height: auto; width:100%;"><?php } ?>
                      </div>
                      <div class="col-sm-6 col-6 invoice-col" style="text-align: center;" >
                        <h3><?php echo $company->com_name; ?></h3>
                        <h5><?php echo $company->com_address; ?></h5>
                        <h5><?php echo $company->com_mobile.', '.$company->com_email; ?></h5>
                      </div>
                    </div><br><br>
                    
                    <div class="row invoice-info">
                      <div class="col-sm-7 col-7 invoice-col">
                        <b>Product :</b> <?php echo $purchase['pName']; ?><br>
                        <b>Model :</b> <?php echo $purchase['model']; ?><br>
                        <b>HSN :</b> <?php echo $purchase['hsn']; ?><br>
                        <b>Price INR :</b> <?php echo number_format($purchase['pprice'], 2); ?><br>
                        <b>Discount % :</b> <?php echo $purchase['pdiscount']; ?><br>
                        <b>INR Price :</b> <?php echo number_format($purchase['tpprice'], 2); ?><br>
                        <b>BDT Rate :</b> <?php echo number_format($purchase['crate'], 2); ?><br>
                        <b>Unit Price BDT  :</b> <?php echo number_format($purchase['camount'], 2); ?><br>
                        <b>Unit Cost % :</b> <?php echo number_format($purchase['uCost'], 2); ?><br>
                        <b>Weight per Unit (KG) :</b> <?php echo $purchase['weight']; ?><br>
                        <b>Quantity :</b> <?php echo $purchase['quantity']; ?><br>
                        <b>Total Amount BDT :</b> <?php echo number_format($purchase['aamount'], 2); ?><br>
                        <b>Total Weight (KG) :</b> <?php echo $purchase['tweight']; ?><br>
                      </div>
                      
                      <div class="col-sm-5 col-5 invoice-col">
                        <b>Assessable Amount USD :</b> <?php echo number_format($purchase['asamount'], 2); ?><br>
                        <b>USD Rate :</b> <?php echo number_format($purchase['usdrate'], 2); ?><br>
                        <b>Total Assessable Amount BDT :</b> <?php echo number_format($purchase['tasamount'], 2); ?><br>
                        <b>Assessable % :</b> <?php echo number_format($purchase['apamount'], 2).'%'; ?><br>
                        <b>Total Assessable Amount :</b> <?php echo number_format($purchase['taamount'], 2); ?><br>
                        <!--<b>Total Convert Amount :</b> <?php echo number_format($purchase['tcamount'], 2); ?><br>-->
                        <b>Per Pices Custom Duty :</b> <?php echo number_format($purchase['custom'], 2); ?><br>
                        <b>Other Costing :</b> <?php echo number_format($purchase['ocost'], 2); ?><br>
                        <b>Total Amount :</b> <?php echo number_format($purchase['tamount'], 2); ?><br>
                        <b>Sales Percentage :</b> <?php echo number_format($purchase['pmargin'], 2); ?><br>
                        <b>Sales Price :</b> <?php echo number_format($purchase['sprice'], 2); ?><br>
                      </div>
                    </div>

                    <div class="row">
                      <p class="lead">Note&nbsp;:&nbsp;</p>
                      <p class="lead"><?php echo $purchase['note']; ?></p>
                    </div><br>
                    
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
                      <a href="<?php echo site_url() ?>Costing" class="btn btn-danger" ><i class="fas fa-arrow-left"></i> Back</a>
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

    