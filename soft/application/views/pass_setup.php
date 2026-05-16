<?php $this->load->view('header/header'); ?>

<body class="hold-transition register-page">
  <div class="register-box">
    <div class="register-logo">
      <?php $company = $this->pm->company_profile_details(); ?>
      <img src="<?php echo base_url().'upload/company/'.$company->com_logo; ?>" style="width: 100%;" alert="logo">
    </div>

    <div class="card">
      <div class="card-body register-card-body">
        <p class="login-box-msg">New Password Setup</p>

        <?php
        $exception = $this->session->userdata('exception');
        if(isset($exception))
        {
        echo $exception;
        $this->session->unset_userdata('exception');
        } ?>
        <br>

        <form action="<?php echo base_url('Login/save_passord_setup');?>" method="POST" enctype="multipart/form-data" >
          <div class="form-group has-feedback">
            <label>New Password</label>
            <input type="password" name="npassword" id="npassword" required placeholder="New Password" class="form-control" style="border-radius: 5px;" minlength="6" maxlength="20" >
            <i class="fa fa-unlock-alt form-control-feedback" ></i>
          </div>
          <div class="form-group has-feedback">
            <label>Confirm Password</label>
            <input type="password" name="cpassword" id="cpassword" required placeholder="Confirm Password" class="form-control" onkeyup="checkPass(); return false;" style="border-radius: 5px;" minlength="6" maxlength="20" >
            <i class="fa fa-unlock-alt form-control-feedback" ></i>
          </div>
          <div class="form-group" style="text-align: center;">
            <button type="submit" name="submit" class="form-control btn btn-primary" style="border-radius: 15px;" ><i class="fa fa-sign-in" ></i> Password Setup</button>
          </div>
        </form>
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


  <script type="text/javascript" >   
    function checkPass()
      { 
      var npassword = document.getElementById('npassword');
      var cpassword = document.getElementById('cpassword');
      var goodColor = "#66cc66";
      var badColor = "#ff6666";
      if(npassword.value == cpassword.value)
        {
        cpassword.style.backgroundColor = goodColor;
        }
      else
        {
        cpassword.style.backgroundColor = badColor; 
        }
      } 
  </script>