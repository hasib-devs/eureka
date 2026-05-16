  <style>
    .brand-link .brand-image {
    	float: none; 
    	line-height: .8;
    	margin-left: .8rem;
    	margin-right: .5rem;
    	margin-top: -3px;
    	max-height: 65px;
    	width: auto;
    }
  </style>
  <body class="hold-transition sidebar-mini">
    <div class="wrapper">
      <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url() ?>uNotice">
              <i class="far fa-bell"></i>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#"><?= $_SESSION['name'] ?>&nbsp;&nbsp;&nbsp;<i class="fas fa-angle-down"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <a href="<?php echo base_url(); ?>myProfile" class="dropdown-item">My Profile</a>
              <div class="dropdown-divider"></div>
              <a href="<?php echo base_url(); ?>comProfile" class="dropdown-item">Company Profile</a>
              <div class="dropdown-divider"></div>
              <a href="<?php echo base_url(); ?>aSetting" class="dropdown-item">Change Password</a>
              <div class="dropdown-divider"></div>
              <a href="<?php echo base_url(); ?>Login/logout" class="dropdown-item">Logout</a>
            </div>
          </li>
        </ul>
      </nav>
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background:black;">
        <a href="<?php echo base_url(); ?>Dashboard" class="brand-link">
          <?php $company = $this->pm->company_details(); ?>
            <img src="<?php echo base_url().'upload/company/'.$company->com_logo; ?>" alt="Logo" class="brand-image elevation-3" style="opacity: .8">
          <!--<span class="brand-text font-weight-light"><?= $_SESSION['compname'] ?></span>-->
        </a>
            <!-- Sidebar -->
        <div class="sidebar">
        <div class="mt-3 mb-3" style="text-align: center;">
          <a href="<?php echo base_url(); ?>posSales" style="background-color: #fff; padding: 10px; border-radius: 20px; color: #000;" ><i class="fa fa-plus"></i> POS SALE</a>
        </div>
          <?php $this->load->view('sidebar/sidebar'); ?>
        </div>
      </aside>