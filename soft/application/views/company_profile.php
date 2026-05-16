<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>
    
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Company Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Company Profile</li>
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
                <h3 class="card-title">Company Profile Information</h3>
              </div>
              <div class="card-body">
                <form action="<?php echo base_url('Home/save_company_profile'); ?>" method="post"
                    enctype='multipart/form-data'>
                  <div class="row">
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Company Name *</label>
                      <input type="text" class="form-control" name="com_name" placeholder="Company Name" value="<?php if($company){ ?><?php echo $company->com_name; ?><?php } ?>" required>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Company Address *</label>
                      <input type="text" class="form-control" name="com_address" placeholder="Company Address" value="<?php if($company){ ?><?php echo $company->com_address; ?><?php } ?>" required >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Mobile Number *</label>
                      <input type="text" class="form-control" name="com_mobile" placeholder="Mobile Number" value="<?php if($company){ ?><?php echo $company->com_mobile; ?><?php } ?>" onkeypress="return isNumberKey(event)" maxlength="11" minlength="11" required >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Email</label>
                      <input type="email" class="form-control" name="com_email" placeholder="exemple@sunshine.com" value="<?php if($company){ ?><?php echo $company->com_email; ?><?php } ?>">
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Capital</label>
                      <input type="text" class="form-control" name="balance" placeholder="Amount" value="<?php if($company){ ?><?php echo $company->com_balance; ?><?php } ?>">
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Equipment Cost</label>
                      <input type="text" class="form-control" name="equipment" placeholder="Equipment Amount" value="<?php if($company){ ?><?php echo $company->equipment; ?><?php } ?>">
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Company Web</label>
                      <input type="text" class="form-control" name="com_web" placeholder="Company Web" value="<?php if($company){ ?><?php echo $company->com_web; ?><?php } ?>">
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Company Facebook</label>
                      <input type="text" class="form-control" name="com_fab" placeholder="Company Facebook" value="<?php if($company){ ?><?php echo $company->com_fab; ?><?php } ?>">
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Company Vat Reg. no.</label>
                      <input type="text" class="form-control" name="com_vat" placeholder="Company Vat Reg." value="<?php if($company){ ?><?php echo $company->com_vat; ?><?php } ?>">
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Company Logo</label>
                      <input type="file" name="userfile">
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Authority Signature</label>
                      <input type="file" name="signaturefile">
                    </div>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-12" style="margin-top: 20px; text-align: center;">
                    <button type="submit" class="btn btn-info"><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<?php $this->load->view('footer/footer'); ?>