<body class="hold-transition layout-top-nav">
  <div class="wrapper">

    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
      <div class="container">
        <a href="<?php echo base_url('trackOrder'); ?>" class="navbar-brand">
          <span class="brand-text font-weight-light"><?php if($company){ ?><img src="<?php echo base_url().'upload/company/'.$company->com_logo; ?>" style="height:50px; width:auto;">&nbsp;&nbsp;<b><?php echo $company->com_name; ?></b><?php } ?></span>
        </a>
      
        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <ul class="navbar-nav ml-auto">
          <?php if($company){ ?><i class="fa fa-phone"></i>&nbsp;<?php echo $company->com_mobile; ?><?php } ?>
        </ul>

        <ul class="navbar-nav ml-auto">
          <?php if($company){ ?><i class="fa fa-map-marker"></i>&nbsp;<?php echo $company->com_address; ?><?php } ?>
        </ul>
      </div>
    </nav>