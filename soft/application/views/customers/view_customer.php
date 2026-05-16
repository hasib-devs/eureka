<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Customer</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Customer</li>
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
                <h3 class="card-title">Customer Information</h3>
              </div>

              <div class="card-body">
                <div class="box-body">
                  <div id="print">
                    <div class="row" id="header" style="display: none" >
                      <div class="col-sm-2 col-md-2 col-2" style="margin-top: 30px;">
                        <img src="<?php echo base_url($company->com_logo); ?>"  style="width: 100%;">
                      </div>
                      <div class="col-sm-10 col-md-10 col-10">
                        <div class="col-sm-12 col-md-12 col-12">
                          <h3><b><?php echo $company->com_name; ?></b></h3>
                        </div>
                        <div class="col-sm-12 col-md-12 col-12">
                          <b>Address&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $company->com_address; ?></b>
                        </div>
                        <div class="col-sm-12 col-md-12 col-12">
                          <b>Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $company->com_email; ?></b>
                        </div>
                        <div class="col-sm-12 col-md-12 col-12">
                          <b>Mobile&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $company->com_mobile; ?></b>
                        </div>
                      </div>
                    </div>
                  
                    <div class="col-sm-12 col-md-12 col-12">
                      <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                          <b>Customer ID : </b>
                        </div>
                        <div class="col-md-8 col-sm-8 col-8">
                          <?php echo $customer->custCode; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                          <b>Customer Name : </b>
                        </div>
                        <div class="col-md-8 col-sm-8 col-8">
                          <?php echo $customer->custName; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                          <b>Father's Name : </b>
                        </div>
                        <div class="col-md-8 col-sm-8 col-8">
                          <?php echo $customer->custfName; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                          <b>Mother's Name : </b>
                        </div>
                        <div class="col-md-8 col-sm-8 col-8">
                          <?php echo $customer->custmName; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                          <b>Spouse Name : </b>
                        </div>
                        <div class="col-md-8 col-sm-8 col-8">
                          <?php echo $customer->spouse; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                          <b>Mobile Number : </b>
                        </div>
                        <div class="col-md-8 col-sm-8 col-8">
                          <?php echo $customer->custMobile; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                          <b>Customer Email : </b>
                        </div>
                        <div class="col-md-8 col-sm-8 col-8">
                          <?php echo $customer->custEmail; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                          <b>Gender : </b>
                        </div>
                        <div class="col-md-8 col-sm-8 col-8">
                          <?php echo $customer->custGender; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                          <b>Date of Birth : </b>
                        </div>
                        <div class="col-md-8 col-sm-8 col-8">
                          <?php echo date('d-m-Y',strtotime($customer->custDob)); ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                          <b>Present Address : </b>
                        </div>
                        <div class="col-md-8 col-sm-8 col-8">
                          <?php echo $customer->custAddress; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                          <b>Permanent Address : </b>
                        </div>
                        <div class="col-md-8 col-sm-8 col-8">
                          <?php echo $customer->custpAddress; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                          <b>Nationality : </b>
                        </div>
                        <div class="col-md-8 col-sm-8 col-8">
                          <?php echo $customer->custNation; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                          <b>NID Number : </b>
                        </div>
                        <div class="col-md-8 col-sm-8 col-8">
                          <?php echo $customer->custNid; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                          <b>Customer Bank : </b>
                        </div>
                        <div class="col-md-8 col-sm-8 col-8">
                          <?php echo $customer->custBank; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                          <b>Bank Account Number : </b>
                        </div>
                        <div class="col-md-8 col-sm-8 col-8">
                          <?php echo $customer->custBNumber; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                          <b>NID Image : </b>
                        </div>
                        <div class="col-md-8 col-sm-8 col-8">
                          <img src="<?php echo base_url().'upload/customer/'.$customer->custNFiles; ?>"  style="width: 50px; height: auto;">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                          <b>Driving License : </b>
                        </div>
                        <div class="col-md-8 col-sm-8 col-8">
                          <?php echo $customer->custDriving; ?>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                          <b>Driving License Image : </b>
                        </div>
                        <div class="col-md-8 col-sm-8 col-8">
                          <img src="<?php echo base_url().'upload/customer/'.$customer->custDFiles; ?>"  style="width: 50px; height: auto;">
                        </div>
                      </div>
                    </div>
                  </div><br>
                  <div class="form-group col-md-12 col-sm-12 col-12" style="text-align: center; margin-top: 20px">
                    <a href="javascript:void(0)" onclick="printDiv('print')" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
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