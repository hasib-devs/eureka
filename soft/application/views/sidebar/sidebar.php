  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <?php if($_SESSION['dashboard'] == 1){ ?>
      <li class="nav-item has-treeview menu-open">
        <a href="<?php echo base_url(); ?>Dashboard" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/Dashboard'){ ?> active <?php } ?>">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p> Dashboard</p>
        </a>
      </li>
      <?php } if($_SESSION['inventory'] == 1){ ?>
      <li class="nav-item <?php if($_SERVER['REQUEST_URI'] == '/items' || $_SERVER['REQUEST_URI'] == '/service' || $_SERVER['REQUEST_URI'] == '/Purchase' || $_SERVER['REQUEST_URI'] == '/Sale' || $_SERVER['REQUEST_URI'] == '/servlist' || $_SERVER['REQUEST_URI'] == '/Return' || $_SERVER['REQUEST_URI'] == '/pReturn' || $_SERVER['REQUEST_URI'] == '/Order' || $_SERVER['REQUEST_URI'] == '/Quotation'){ ?> menu-open <?php } ?>">
        <a href="#" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/items' || $_SERVER['REQUEST_URI'] == '/service' || $_SERVER['REQUEST_URI'] == '/Purchase' || $_SERVER['REQUEST_URI'] == '/Sale' || $_SERVER['REQUEST_URI'] == '/servlist' || $_SERVER['REQUEST_URI'] == '/Return' || $_SERVER['REQUEST_URI'] == '/pReturn' || $_SERVER['REQUEST_URI'] == '/Order' || $_SERVER['REQUEST_URI'] == '/Quotation'){ ?> active <?php } ?>">
          <i class="nav-icon fas fa-warehouse"></i>
          <p> Inventory <i class="fas fa-angle-left right"></i></p>
        </a>
        <ul class="nav nav-treeview">
          <?php if($_SESSION['products'] == 1){ ?>
          <li class="nav-item">
            <a href="<?php echo base_url(); ?>items" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/items'){ ?> active <?php } ?>">
              <i class="far fa-circle nav-icon"></i>
              <p> Product Information </p>
            </a>
          </li>
          <?php } if($_SESSION['services'] == 1){ ?>
          <li class="nav-item">
            <a href="<?php echo base_url(); ?>service" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/service'){ ?> active <?php } ?>">
              <i class="far fa-circle nav-icon"></i>
              <p> Services </p>
            </a>
          </li>
          <?php } if($_SESSION['purchases'] == 1){ ?>
          <li class="nav-item">
            <a href="<?php echo base_url(); ?>Purchase" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/Purchase'){ ?> active <?php } ?>">
              <i class="far fa-circle nav-icon"></i>
              <p> Purchases Order</p>
            </a>
          </li>
          <?php } if($_SESSION['sales'] == 1){ ?>
          <li class="nav-item">
            <a href="<?php echo base_url(); ?>Sale" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/Sale'){ ?> active <?php } ?>">
              <i class="far fa-circle nav-icon"></i>
              <p> Sales</p>
            </a>
          </li>
          <?php } if($_SESSION['saleservices'] == 1){ ?>
          <li class="nav-item">
            <a href="<?php echo base_url(); ?>servlist" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/servlist'){ ?> active <?php } ?>">
              <i class="far fa-circle nav-icon"></i>
              <p> Sale Services </p>
            </a>
          </li>
          <?php } if($_SESSION['salereturns'] == 1){ ?>
          <li class="nav-item">
            <a href="<?php echo base_url(); ?>Return" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/Return'){ ?> active <?php } ?>">
              <i class="far fa-circle nav-icon"></i>
              <p> Sales Returns</p>
            </a>
          </li>
          <?php } if($_SESSION['purreturns'] == 1){ ?>
          <li class="nav-item">
            <a href="<?php echo base_url(); ?>pReturn" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/pReturn'){ ?> active <?php } ?>">
              <i class="far fa-circle nav-icon"></i>
              <p> Purchases Returns</p>
            </a>
          </li>
          <?php } if($_SESSION['preorder'] == 1){ ?>
          <li class="nav-item">
            <a href="<?php echo base_url(); ?>Order" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/Order'){ ?> active <?php } ?>">
              <i class="far fa-circle nav-icon"></i>
              <p> Pre-Order</p>
            </a>
          </li>
          <?php } if($_SESSION['quotations'] == 1){ ?>
          <li class="nav-item">
            <a href="<?php echo base_url(); ?>Quotation" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/Quotation'){ ?> active <?php } ?>">
              <i class="far fa-circle nav-icon"></i>
              <p> Quotation</p>
            </a>
          </li>
          <?php } ?>
          <li class="nav-item">
            <a href="<?php echo base_url(); ?>Lcmanagement" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/Lcmanagement'){ ?> active <?php } ?>">
              <i class="far fa-circle nav-icon"></i>
              <p> LC Management</p>
            </a>
          </li>
          
        </ul>
      </li>

        <?php } if($_SESSION['brtaregstar'] == 1){ ?>
        <!--<li class="nav-item">-->
        <!--  <a href="<?php echo base_url(); ?>saleDList" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/saleDList'){ ?> active <?php } ?>">-->
        <!--    <i class="nav-icon fas fa-receipt"></i>-->
        <!--    <p> BRTA Registration</p>-->
        <!--  </a>-->
        <!--</li>-->
        <?php } if($_SESSION['role'] <= 3){ ?>
        <li class="nav-item">
          <a href="<?php echo base_url(); ?>Costing" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/Costing'){ ?> active <?php } ?>">
            <i class="nav-icon fas fa-receipt"></i>
            <p> Costing</p>
          </a>
        </li>
      
      <!--<li class="nav-item">-->
      <!--  <a href="<?php echo base_url(); ?>Voucher" class="nav-link">-->
      <!--    <i class="nav-icon fas fa-receipt"></i>-->
      <!--    <p> Vouchers</p>-->
      <!--  </a>-->
      <!--</li>-->
      <?php } if($_SESSION['accounting'] == 1){ ?>
      <li class="nav-item <?php if($_SERVER['REQUEST_URI'] == '/subAType' || $_SERVER['REQUEST_URI'] == '/chaType' || $_SERVER['REQUEST_URI'] == '/chAccount' || $_SERVER['REQUEST_URI'] == '/rVoucher' || $_SERVER['REQUEST_URI'] == '/pVoucher' || $_SERVER['REQUEST_URI'] == '/coaReport' || $_SERVER['REQUEST_URI'] == '/journalEntry' || $_SERVER['REQUEST_URI'] == '/trailBalance' || $_SERVER['REQUEST_URI'] == '/balanceSheet' || $_SERVER['REQUEST_URI'] == '/generalLedger' || $_SERVER['REQUEST_URI'] == '/incomeStatement'){ ?> menu-open <?php } ?>">
        <a href="#" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/subAType' || $_SERVER['REQUEST_URI'] == '/chaType' || $_SERVER['REQUEST_URI'] == '/chAccount' || $_SERVER['REQUEST_URI'] == '/rVoucher' || $_SERVER['REQUEST_URI'] == '/pVoucher' || $_SERVER['REQUEST_URI'] == '/coaReport' || $_SERVER['REQUEST_URI'] == '/journalEntry' || $_SERVER['REQUEST_URI'] == '/trailBalance' || $_SERVER['REQUEST_URI'] == '/balanceSheet' || $_SERVER['REQUEST_URI'] == '/generalLedger' || $_SERVER['REQUEST_URI'] == '/incomeStatement'){ ?> active <?php } ?>">
          <i class="nav-icon fas fa-balance-scale"></i>
          <p> Acounting <i class="fas fa-angle-left right"></i></p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="<?php echo base_url(); ?>Voucher" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/subAType'){ ?> active <?php } ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Vouchers</p>
                </a>
          </li>
          <?php if($_SESSION['majortype'] == 1){ ?>
          <!--<li class="nav-item">-->
          <!--  <a href="<?php echo base_url(); ?>subAType" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/subAType'){ ?> active <?php } ?>">-->
          <!--    <i class="far fa-circle nav-icon"></i>-->
          <!--    <p>Major Type</p>-->
          <!--  </a>-->
          <!--</li>-->
          <?php } if($_SESSION['subtype'] == 1){ ?>
          <!--<li class="nav-item">-->
          <!--  <a href="<?php echo base_url(); ?>chaType" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/chaType'){ ?> active <?php } ?>">-->
          <!--    <i class="far fa-circle nav-icon"></i>-->
          <!--    <p>Sub Type</p>-->
          <!--  </a>-->
          <!--</li>-->
          <?php } if($_SESSION['voucherentry'] == 1){ ?>
          <!--<li class="nav-item">-->
          <!--  <a href="<?php echo base_url(); ?>chAccount" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/chAccount'){ ?> active <?php } ?>">-->
          <!--    <i class="far fa-circle nav-icon"></i>-->
          <!--    <p>Voucher Entry</p>-->
          <!--  </a>-->
          <!--</li>-->
          <!--<li class="nav-item">-->
          <!--  <a href="<?php echo base_url(); ?>rVoucher" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/rVoucher'){ ?> active <?php } ?>">-->
          <!--    <i class="far fa-circle nav-icon"></i>-->
          <!--    <p>Receive Voucher</p>-->
          <!--  </a>-->
          <!--</li>-->
          <!--<li class="nav-item">-->
          <!--  <a href="<?php echo base_url(); ?>pVoucher" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/pVoucher'){ ?> active <?php } ?>">-->
          <!--    <i class="far fa-circle nav-icon"></i>-->
          <!--    <p>Payment Voucher</p>-->
          <!--  </a>-->
          <!--</li>-->
          <?php } if($_SESSION['voucherreports'] == 1){ ?>
          <!--<li class="nav-item">-->
          <!--  <a href="<?php echo base_url(); ?>coaReport" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/coaReport'){ ?> active <?php } ?>">-->
          <!--    <i class="far fa-circle nav-icon"></i>-->
          <!--    <p>Voucher Reports</p>-->
          <!--  </a>-->
          <!--</li>-->
          <?php } if($_SESSION['journalentry'] == 1){ ?>
          <!--<li class="nav-item">-->
          <!--  <a href="<?php echo base_url(); ?>journalEntry" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/journalEntry'){ ?> active <?php } ?>">-->
          <!--    <i class="far fa-circle nav-icon"></i>-->
          <!--    <p>Journal Entry</p>-->
          <!--  </a>-->
          <!--</li>-->
          <?php } if($_SESSION['trailbalance'] == 1){ ?>
          <li class="nav-item">
            <a href="<?php echo base_url(); ?>trailBalance" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/trailBalance'){ ?> active <?php } ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Trial Balance</p>
            </a>
          </li>
          <?php } if($_SESSION['balancesheet'] == 1){ ?>
          <li class="nav-item">
            <a href="<?php echo base_url(); ?>balanceSheet" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/balanceSheet'){ ?> active <?php } ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Balance Sheet</p>
            </a>
          </li>
          <?php } if($_SESSION['generalledger'] == 1){ ?>
          <!--<li class="nav-item">-->
          <!--  <a href="<?php echo base_url(); ?>generalLedger" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/generalLedger'){ ?> active <?php } ?>">-->
          <!--    <i class="far fa-circle nav-icon"></i>-->
          <!--    <p>General Ledger</p>-->
          <!--  </a>-->
          <!--</li>-->
          <?php } if($_SESSION['incomestatement'] == 1){ ?>
          <li class="nav-item">
            <a href="<?php echo base_url(); ?>incomeStatement" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/incomeStatement'){ ?> active <?php } ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Income Statement</p>
            </a>
          </li>
          <?php } ?>
        </ul>
      </li>

      <?php } if($_SESSION['hrpayroll'] == 1){ ?>
      <li class="nav-item <?php if($_SERVER['REQUEST_URI'] == '/Customer' || $_SERVER['REQUEST_URI'] == '/Supplier' || $_SERVER['REQUEST_URI'] == '/Employee' || $_SERVER['REQUEST_URI'] == '/User' || $_SERVER['REQUEST_URI'] == '/empPayment'){ ?> menu-open <?php } ?>">
        <a href="#" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/Customer' || $_SERVER['REQUEST_URI'] == '/Supplier' || $_SERVER['REQUEST_URI'] == '/Employee' || $_SERVER['REQUEST_URI'] == '/User' || $_SERVER['REQUEST_URI'] == '/empPayment'){ ?> active <?php } ?>">
          <i class="nav-icon fas fa-users"></i>
          <p> HR & Payroll <i class="fas fa-angle-left right"></i></p>
        </a>
        <ul class="nav nav-treeview">
          <?php if($_SESSION['customers'] == 1){ ?>
          <li class="nav-item">
            <a href="<?php echo base_url(); ?>Customer" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/Customer'){ ?> active <?php } ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Customers</p>
            </a>
          </li>
          <?php } if($_SESSION['suppliers'] == 1){ ?>
          <li class="nav-item">
            <a href="<?php echo base_url(); ?>Supplier" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/Supplier'){ ?> active <?php } ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Suppliers</p>
            </a>
          </li>
          <?php } if($_SESSION['employees'] == 1){ ?>
          <li class="nav-item">
            <a href="<?php echo base_url(); ?>Employee" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/Employee'){ ?> active <?php } ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Staff / Employees</p>
            </a>
          </li>
          <?php } if($_SESSION['users'] == 1){ ?>
          <li class="nav-item">
            <a href="<?php echo base_url(); ?>User" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/User'){ ?> active <?php } ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Users</p>
            </a>
          </li>
          <?php } if($_SESSION['emppayments'] == 1){ ?>
          <li class="nav-item">
            <a href="<?php echo base_url(); ?>empPayment" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/empPayment'){ ?> active <?php } ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Employee Payment</p>
            </a>
          </li>
          <?php } ?>
        </ul>
      </li>
        
        
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class=" nav-icon fa fa-users"></i>
            <p>Loan <i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url(); ?>empLoan" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Employee Loan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url(); ?>newempLoan" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>New Loan</p>
              </a>
            </li>
            <!--<li class="nav-item">-->
            <!--  <a class="nav-link">-->
            <!--    <i class="far fa-circle nav-icon"></i>-->
            <!--    <p>Loan Details</p>-->
            <!--  </a>-->
            <!--</li>-->
          </ul>
        </li>
        
      <?php } if($_SESSION['reports'] == 1){ ?>
      <li class="nav-item">
        <a href="<?php echo base_url(); ?>uReport" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/uReport'){ ?> active <?php } ?>">
          <i class="nav-icon far fa-flag"></i>
          <p> Reports </p>
        </a>
      </li>
      <?php } if($_SESSION['settings'] == 1){ ?>
      <li class="nav-item">
        <a href="<?php echo base_url(); ?>Setting" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/Setting'){ ?> active <?php } ?>">
          <i class="nav-icon fas fa-cog"></i>
          <p> Settings</p>
        </a>
      </li>
      <?php } if($_SESSION['accesssetup'] == 1){ ?>
      <li class="nav-item">
        <a href="<?php echo base_url(); ?>userAccess" class="nav-link <?php if($_SERVER['REQUEST_URI'] == '/userAccess'){ ?> active <?php } ?>">
          <i class="nav-icon fas fa-cog"></i>
          <p> Access Setup</p>
        </a>
      </li>
      <?php } ?>
      <li class="nav-item">
        <a href="<?php echo base_url(); ?>Login/logout" class="nav-link">
          <i class="nav-icon far fa-arrow-alt-circle-left"></i>
          <p> Logout</p>
        </a>
      </li>

    </ul>
  </nav>