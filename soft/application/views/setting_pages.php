<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Setting</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Setting</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Setting</h3>
            </div>

            <div class="card-body">
              <div class="row">
                <?php if($_SESSION['category'] == 1){ ?>
                <div class="col-md-3 col-sm-6 col-12">
                  <a href="<?php echo base_url('Category') ?>" > 
                    <div class="info-box bg-info">
                      <span class="info-box-icon"><i class="fas fa-align-center"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Category</span>
                        <span class="info-box-number"><?php echo $category; ?></span>
                      </div>
                    </div>
                  </a>
                </div>
                <?php } if($_SESSION['units'] == 1){ ?>
                <div class="col-md-3 col-sm-6 col-12">
                  <a href="<?php echo base_url('Unit') ?>" > 
                    <div class="info-box bg-success">
                      <span class="info-box-icon"><i class="fas fa-align-center"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Unit</span>
                        <span class="info-box-number"><?php echo $unit; ?></span>
                      </div>
                    </div>
                  </a>
                </div>
                <?php } if($_SESSION['department'] == 1){ ?>
                <div class="col-md-3 col-sm-6 col-12">
                  <a href="<?php echo base_url('Department') ?>" > 
                    <div class="info-box bg-danger">
                      <span class="info-box-icon"><i class="fas fa-align-center"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Department</span>
                        <span class="info-box-number"><?php echo $dept; ?></span>
                      </div>
                    </div>
                  </a>
                </div>
                <?php } if($_SESSION['courier'] == 1){ ?>
                <div class="col-md-3 col-sm-6 col-12">
                  <a href="<?php echo base_url('Courier') ?>" > 
                    <div class="info-box bg-danger">
                      <span class="info-box-icon"><i class="fas fa-align-center"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Courier</span>
                        <span class="info-box-number"><?php echo $courier; ?></span>
                      </div>
                    </div>
                  </a>
                </div>
                <?php } if($_SESSION['cashaccount'] == 1){ ?>
                <div class="col-md-3 col-sm-6 col-12">
                  <a href="<?php echo base_url('CashAccount') ?>" > 
                    <div class="info-box bg-info">
                      <span class="info-box-icon"><i class="fas fa-align-center"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Cash Account</span>
                        <span class="info-box-number"><?php echo $cash; ?></span>
                      </div>
                    </div>
                  </a>
                </div>
                <?php } if($_SESSION['bankaccount'] == 1){ ?>
                <div class="col-md-3 col-sm-6 col-12">
                  <a href="<?php echo base_url('BankAccount') ?>" > 
                    <div class="info-box bg-success">
                      <span class="info-box-icon"><i class="fas fa-align-center"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Bank Account</span>
                        <span class="info-box-number"><?php echo $bank; ?></span>
                      </div>
                    </div>
                  </a>
                </div>
                <?php } if($_SESSION['mobileaccount'] == 1){ ?>
                <div class="col-md-3 col-sm-6 col-12">
                  <a href="<?php echo base_url('MobileAccount') ?>" > 
                    <div class="info-box bg-warning">
                      <span class="info-box-icon"><i class="fas fa-align-center"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Mobile Account</span>
                        <span class="info-box-number"><?php echo $mobile; ?></span>
                      </div>
                    </div>
                  </a>
                </div>
                <?php } if($_SESSION['accesssetup'] == 1){ ?>
                <!--<div class="col-md-3 col-sm-6 col-12">-->
                <!--  <a href="<?php echo base_url('uNotice') ?>" > -->
                <!--    <div class="info-box bg-danger">-->
                <!--      <span class="info-box-icon"><i class="fas fa-align-center"></i></span>-->
                <!--      <div class="info-box-content">-->
                <!--        <span class="info-box-text">Notification</span>-->
                <!--        <span class="info-box-number"><?php echo $notice; ?></span>-->
                <!--      </div>-->
                <!--    </div>-->
                <!--  </a>-->
                <!--</div>-->
                <?php } if($_SESSION['usertype'] == 1){ ?>
                <div class="col-md-3 col-sm-6 col-12">
                  <a href="<?php echo base_url('uRole') ?>" > 
                    <div class="info-box bg-info">
                      <span class="info-box-icon"><i class="fas fa-align-center"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">User Type</span>
                        <span class="info-box-number"><?php echo $utype; ?></span>
                      </div>
                    </div>
                  </a>
                </div>
                <?php } if($_SESSION['purchasetype'] == 1){ ?>
                <div class="col-md-3 col-sm-6 col-12">
                  <a href="<?php echo base_url('puType') ?>" > 
                    <div class="info-box bg-danger">
                      <span class="info-box-icon"><i class="fas fa-align-center"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Purchase Type</span>
                        <span class="info-box-number"><?php echo $putype; ?></span>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                  <a href="<?php echo base_url('Expense') ?>" > 
                    <div class="info-box bg-info">
                      <span class="info-box-icon"><i class="fas fa-align-center"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Expense Type</span>
                        <span class="info-box-number"><?php echo $expense; ?></span>
                      </div>
                    </div>
                  </a>
                </div>
                <?php } if($_SESSION['balancetransfer'] == 1){ ?>
                <div class="col-md-3 col-sm-6 col-12">
                  <a href="<?php echo base_url('transAccount') ?>" > 
                    <div class="info-box bg-success">
                      <span class="info-box-icon"><i class="fas fa-align-center"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Balance Transfer</span>
                        <span class="info-box-number"><?php echo number_format($transfer->total, 2); ?></span>
                      </div>
                    </div>
                  </a>
                </div>
                <?php } ?>
                
                <div class="col-md-3 col-sm-6 col-12">
                  <a href="<?php echo base_url('currency') ?>" > 
                    <div class="info-box bg-success">
                      <span class="info-box-icon"><i class="fas fa-align-center"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">currency</span>
                        <span class="info-box-number"><?php echo $currency; ?></span>
                      </div>
                    </div>
                  </a>
                </div>
                
                <div class="col-md-3 col-sm-6 col-12">
                  <a href="<?php echo base_url() ?>Courieraccount" > 
                    <div class="info-box bg-success">
                      <span class="info-box-icon"><i class="fas fa-align-center"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Courier Account</span>
                        <span class="info-box-number"><?php echo $couraccount; ?></span>
                      </div>
                    </div>
                  </a>
                </div>
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<?php $this->load->view('footer/footer'); ?>