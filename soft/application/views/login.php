<?php $this->load->view('header/header'); ?>

<body class="hold-transition register-page">
  <div class="register-box">
    <div class="register-logo">
      <?php $company = $this->pm->company_profile_details(); ?>
      <img src="<?php echo base_url().'upload/company/'.$company->com_logo; ?>" style="width: 100%;" alert="logo">
    </div>

    <div class="card">
      <div class="card-body register-card-body">
        <p class="login-box-msg">Login Your Account</p>
        <form class="form-group" action="<?php echo base_url('Login/loginProcess'); ?>" method="post">
          <div class="form-group">
            <label class="form-control-label">USERNAME</label>
            <input type="text" class="form-control" name="username" required >
          </div>
          <div class="form-group">
            <label class="form-control-label">PASSWORD</label>
            <input type="password" class="form-control" name="password" required >
          </div>
          <div class="row">
            <div class="col-lg-6">
              <input type="checkbox" id="remember">
              <label for="remember">Remember Me</label>
            </div>
            <div class="col-lg-6">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
          </div>
        </form>

        <!--<p class="mt-3 mb-1">-->
        <!--  <a href="<?php echo base_url(); ?>forgetPassword">I forgot my password</a>-->
        <!--</p>-->
      </div>
    </div>
  </div>

    <!-- jQuery -->
    <script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>
  </body>
</html>