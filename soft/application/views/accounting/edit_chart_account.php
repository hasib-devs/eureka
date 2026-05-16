<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Voucher Entry</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Voucher Entry</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <?php
    $exception=$this->session->userdata('exception');
    if(isset($exception))
    {
    echo $exception;
    $this->session->unset_userdata('exception');
    } ?>
    
    <section class="content">
      <div class="container">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Voucher Entry Information</h3>
              </div>
              <div class="card-body">
                <form action="<?php echo base_url('Chartofaccounting/update_chart_of_accounting');?>" method="post">
                  <input type="hidden" name="catid" value="<?php echo $account[0]['ca_id']; ?>" required >
                  <div class="row">
                    <!--<div class="form-group col-md-4 col-sm-4 col-12">-->
                    <!--  <label>Accounting Type *</label>-->
                    <!--  <select type="text" class="form-control" name="atype" id="atype" required >-->
                    <!--    <option value="">Select One</option>-->
                    <!--    <?php foreach ($atype as $value) { ?>-->
                    <!--    <option value="<?php echo $value['catid']; ?>"><?= $value['catName']; ?></option>-->
                    <!--    <?php } ?>-->
                    <!--  </select>-->
                    <!--</div>-->
                    <!--<div class="form-group col-md-4 col-sm-4 col-12">-->
                    <!--  <label>Select Major Account *</label>-->
                    <!--  <select type="text" class="form-control" name="sAccount" id="sAccount" required >-->
                    <!--    <option value="">Select One</option>-->
                    <!--  </select>-->
                    <!--</div>-->
                    <!--<div class="form-group col-md-4 col-sm-4 col-12">-->
                    <!--  <label>Account Sub Type *</label>-->
                    <!--  <select type="text" class="form-control" name="catype" id="catype" required >-->
                    <!--    <option value="">Select Account First</option>-->
                    <!--  </select>-->
                    <!--</div>-->
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Voucher Detalis *</label>
                      <select type="text" class="form-control select2" name="cadetails" id="cadetails" required >
                        <option value="">Select One</option>
                        <?php foreach ($caccount as $value) { ?>
                        <option <?php echo ($account[0]['cadetails'] == $value['cadetails'])?'selected':''?> value="<?php echo $value['cadetails']; ?>"><?= $value['cadetails']; ?></option>
                        <?php } ?>
                        <option value="newDetalis">New Detalis</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12" >
                      <label>Narration</label>
                      <input type="text" class="form-control" name="narration" value="<?php echo $account[0]['narration']; ?>" placeholder="Narration" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Amount *</label>
                      <input type="text" class="form-control" name="amount" value="<?php echo $account[0]['caamount']; ?>" required >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Payment Method *</label>
                      <select class="form-control" name="accountType" id="accountType" required >
                        <option <?php echo ($account[0]['accountType'] == 'Cash')?'selected':''?> value="Cash">Cash</option>
                        <option <?php echo ($account[0]['accountType'] == 'Bank')?'selected':''?> value="Bank">Bank</option>
                        <option <?php echo ($account[0]['accountType'] == 'Mobile')?'selected':''?> value="Mobile">Mobile</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Account Number *</label>
                      <select class="form-control" name="accountNo" id="accountNo" required >
                        <option value="">Select Account Type First</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-12" style="margin-top:20px; text-align: center;">
                    <button type="submit" class="btn btn-info"><i class="far fa-save"></i>&nbsp;&nbsp;Update</button>
                    <a href="<?php echo site_url('chAccount'); ?>" class="btn btn-danger" ><i class="fa fa-arrow-left" ></i>&nbsp;&nbsp;Back</a>
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
    
    <script type="text/javascript">
      $(document).ready(function(){
        $('#atype').change(function(){
          var url = "<?php echo base_url(); ?>Chartofaccounting/get_sub_account_data";
          var id = $('#atype').val() ;
              //alert(id); alert(url);
          $.ajax({
            method: "POST",
            url     : url,
            dataType: 'json',
            data    : {"id" : id},
            success:function(data){ 
                //alert(data);
              $('#sAccount').removeAttr("disabled")
              var HTML = "<option value=''>Select One</option>";
              for (var key in data) 
                {
                if(data.hasOwnProperty(key))
                  {
                  HTML +="<option value='"+data[key]["satid"]+"'>" + data[key]["scaType"]+"</option>";
                }}
              $("#sAccount").html(HTML);
              },
            error:function(data){
              alert('error');
              }
            });
          });
        });
    </script>
    
    <script type="text/javascript" >
      $(document).ready(function(){
        $('#sAccount').change(function(){
          var url = "<?php echo base_url(); ?>Chartofaccounting/get_account_type";
          var id = $('#sAccount').val();
          //alert(id);alert(id2);
          $.ajax({
            method: "POST",
            url     : url,
            dataType: 'json',
            data    : {'id':id},
            success:function(data){ 
            //alert(data);
              $('#catype').removeAttr("disabled")
              var HTML = "<option value=''>Select One</option>";
              for (var key in data) 
                {
                if(data.hasOwnProperty(key))
                  {
                  HTML +="<option value='"+data[key]["cat_id"]+"'>" + data[key]["caType"]+"</option>";
                }}
              $("#catype").html(HTML);
              },
            error:function(data){
              alert('error');
              }
            });
          });
        });
    </script>
    
    <script type="text/javascript">
      $(document).ready(function(){
        $('#cadetails').change(function(){
          var id = $('#cadetails').val();
          
          if(id == 'newDetalis')
            {
            $('#newDetalis').removeAttr('class','d-none');

            $('#new2Detalis').attr('required','required');
            }
          else
            {
            $('#newDetalis').attr('class','d-none');

            $('#new2Detalis').removeAttr('required','required');
            }
          });
        });
    </script>
    
    <script type="text/javascript">
      $(document).ready(function(){
        var value = $("#accountType").val();
        $('#accountNo').empty();
        getAccountNo(value, '#accountNo');
        $('#accountNo').val(1);
        });
              
      $('#accountType').on('change',function(){
        var value = $(this).val();
        $('#accountNo').empty();
        getAccountNo(value, '#accountNo');
        });
          
        function getAccountNo(value,place){
          $(place).empty();
          if(value != ''){
            $.ajax({
              url: '<?php echo site_url()?>Voucher/getAccountNo',
              async: false,
              dataType: "json",
              data: 'id=' + value,
              type: "POST",
              success: function (data){
                $(place).append(data);
                $(place).trigger("chosen:updated");
                }
              });
            }
          else
            {
            customAlert('Please Select Account Type', "error", true);
            }
          }
    </script>
    
