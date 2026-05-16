<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Report</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Report</li>
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
                <h3 class="card-title">Report</h3>
              </div>

              <div class="card-body">
                <div class="row">
                  <?php if($_SESSION['salesreports'] == 1){ ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url('saleReport'); ?>" > 
                      <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-chart-pie"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Sales Report</span>
                          <span class="info-box-number"><?php echo number_format($sale->total, 2); ?></span>
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } if($_SESSION['purchasereports'] == 1){ ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url('purchaseReport'); ?>" > 
                      <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-chart-bar"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Purchase Report</span>
                          <span class="info-box-number"><?php echo number_format($purchase->total, 2); ?></span>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url('serviceReport'); ?>" > 
                      <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-chart-bar"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Service Report</span>
                          <span class="info-box-number"><?php echo number_format($tsersale->total, 2); ?></span>
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } if($_SESSION['profitreports'] == 1){ ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url('profil-Loss'); ?>" > 
                      <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Profit / Loss Report</span>
                          <?php $ti = $psale->total+$pcvoucher->total; ?>
                          <?php $te = $ppurchase->total+$pdvoucher->total+$pempp->total+$preturn->total+$psvoucher->total; ?>
                          <span class="info-box-number"><?php echo number_format(($ti-$te), 2); ?></span>
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } if($_SESSION['spprofit'] == 1){ ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url() ?>spReports" > 
                      <div class="info-box bg-danger">
                        <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Sale / Purchase Profit</span>
                          <span class="info-box-number">00</span>
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } if($_SESSION['custreports'] == 1){ ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url() ?>custReport" > 
                      <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Customer Report</span>
                          <span class="info-box-number"><?php echo $customer; ?></span>
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } if($_SESSION['custledger'] == 1){ ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url() ?>custLedger" > 
                      <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="far fa-user"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Customer Ledger</span>
                          <span class="info-box-number"><?php echo $customer; ?></span>
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } if($_SESSION['supreports'] == 1){ ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url() ?>supReport" > 
                      <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Supplier Report</span>
                          <span class="info-box-number"><?php echo $supplier; ?></span>
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } if($_SESSION['supledger'] == 1){ ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url() ?>supLedger" > 
                      <div class="info-box bg-danger">
                        <span class="info-box-icon"><i class="far fa-user"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Supplier Ledger</span>
                          <span class="info-box-number"><?php echo $supplier; ?></span>
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } if($_SESSION['stockreports'] == 1){ ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url() ?>stockReport" > 
                      <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-layer-group"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Stock Report</span>
                          <span class="info-box-number"><?php echo $stock->total; ?></span>
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } if($_SESSION['orderreports'] == 1){ ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url() ?>orderReport" > 
                      <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Order Report</span>
                          <!--<span class="info-box-number">00</span>-->
                        </div>
                      </div>
                    </a>
                  </div>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url() ?>vReports" > 
                      <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-adjust"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Voucher Report</span>
                          <span class="info-box-number"><?php echo number_format($voucher->total, 2); ?></span>
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } if($_SESSION['dailyreports'] == 1){ ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url() ?>dReport" > 
                      <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fab fa-amazon-pay"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Daily Report</span>
                          <?php $pamount = (($psale->total+$pcvoucher->total)-($ppurchase->total+$pdvoucher->total+$pempp->total+$preturn->total+$psvoucher->total)); ?>
                          <?php $ti = $csale->total+$ccvoucher->total+$pamount; ?>
                          <?php $te = $cpurchase->total+$cdvoucher->total+$csvoucher->total; ?>
                          <span class="info-box-number"><?php echo number_format(($ti-$te), 2); ?></span>
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } if($_SESSION['cashbook'] == 1){ ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url() ?>cashReport" > 
                      <div class="info-box bg-danger">
                        <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Cash Book</span>
                          <!--<span class="info-box-number">00</span>-->
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } if($_SESSION['bankbook'] == 1){ ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url() ?>bankReport" > 
                      <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-university"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Bank Book</span>
                          <!--<span class="info-box-number">00</span>-->
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } if($_SESSION['mobilebook'] == 1){ ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url() ?>mobileReport" > 
                      <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-mobile-alt"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Mobile Book</span>
                          <!--<span class="info-box-number">00</span>-->
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } if($_SESSION['swpreports'] == 1){ ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url() ?>salesiReport" > 
                      <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="far fa-money-bill-alt"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Profit Report (Sale Wise)</span>
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } if($_SESSION['duereports'] == 1){ ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url() ?>salesdReport" > 
                      <div class="info-box bg-danger">
                        <span class="info-box-icon"><i class="far fa-money-bill-alt"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Due Report (Sale Wise)</span>
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } if($_SESSION['btransreports'] == 1){ ?>
                  <!--<div class="col-md-3 col-sm-6 col-12">-->
                  <!--  <a href="<?php echo base_url() ?>bankTReport" > -->
                  <!--    <div class="info-box bg-info">-->
                  <!--      <span class="info-box-icon"><i class="far fa-money-bill-alt"></i></span>-->
                  <!--      <div class="info-box-content">-->
                  <!--        <span class="info-box-text">Bank Transaction Report</span>-->
                  <!--      </div>-->
                  <!--    </div>-->
                  <!--  </a>-->
                  <!--</div>-->
                  <?php } if($_SESSION['duepreports'] == 1){ ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url() ?>salesDPReport" > 
                      <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-adjust"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Due Payment Report</span>
                          <?php 
                          $tsdp = $this->db->select("SUM(`pAmount`) as total")->FROM('sales_payment')->get()->row();
                          ?>
                          <span class="info-box-number"><?php echo number_format($tsdp->total, 2); ?></span>
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } if($_SESSION['expensereports'] == 1){ ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url() ?>costReport" > 
                      <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-adjust"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Expense Report</span>
                          <!--<span class="info-box-number"><?php echo number_format($tsdp->total, 2); ?></span>-->
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } if($_SESSION['btransferreports'] == 1){ ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url() ?>transReport" > 
                      <div class="info-box bg-danger">
                        <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Total Balance transfer</span>
                          <!--<span class="info-box-number">00</span>-->
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } if($_SESSION['alltransreports'] == 1){ ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url('allTReport'); ?>" > 
                      <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-chart-pie"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">All Transaction Report</span>
                          <!--<span class="info-box-number">00</span>-->
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } ?>
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url(); ?>costingReport" > 
                      <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-chart-bar"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Costing Report</span>
                          <span class="info-box-number"></span>
                        </div>
                      </div>
                    </a>
                  </div>
                  
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url(); ?>saleCReport" > 
                      <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-chart-bar"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Courier Report</span>
                          <span class="info-box-number"></span>
                        </div>
                      </div>
                    </a>
                  </div>
                  
                  <div class="col-md-3 col-sm-6 col-12">
                    <a href="<?php echo base_url('stockLedger'); ?>" > 
                      <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-chart-pie"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Stock Ledger</span>
                          <span class="info-box-number"></span>
                        </div>
                      </div>
                    </a>
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