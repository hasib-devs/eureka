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
                <h3 class="card-title">Voucher Entry List</h3>
                <?php if($_SESSION['newVEntry'] == 1){ ?>
                <a href="<?php echo site_url('newCAccount'); ?>" class="btn btn-primary" style="float: right" ><i class="fa fa-plus"></i> New Voucher</a>
                <?php } ?>
                <!--<button type="button" class="btn btn-primary newAccount" data-toggle="modal" data-target=".bs-example-modal-newAccount" style="float: right" ><i class="fa fa-plus"></i> New Account</button>-->
              </div>
              <div class="card-body">
                <table id="example" class="table table-responsive table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 5%;">#SN.</th>
                      <th>Account</th>
                      <th>Major Account</th>
                      <th>Sub Type</th>
                      <th>Details</th>
                      <th>Narration</th>
                      <th>Amount</th>
                      <th style="width: 8%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 0;
                    foreach ($caccount as $value) {
                    $i++;
                    ?>
                    <tr class="gradeX">
                      <td><?php echo $i; ?></td>
                      <td><?php echo $value['catName']; ?></td>
                      <td><?php echo $value['scaType']; ?></td>
                      <td><?php echo $value['caType']; ?></td>
                      <td><?php echo $value['cadetails']; ?></td>
                      <td><?php echo $value['narration']; ?></td>
                      <td><?php echo number_format($value['caamount'], 2); ?></td>
                      <td>
                        <!--<button type="button" class="btn btn-success btn-xs euser" data-toggle="modal" data-target=".bs-example-modal-euser" data-id="<?php echo $value['ca_id']; ?>" id="<?php echo $value['ca_id']; ?>" onclick="document.getElementById('euser').style.display='block'" ><i class="fa fa-edit"></i></button>-->
                        <?php if($_SESSION['editVEntry'] == 1){ ?>
                        <a class="btn btn-success btn-xs" href="<?php echo site_url().'editCAccount/'.$value['ca_id'] ?>" ><i class="fa fa-edit"></i></a>
                        <?php } if($_SESSION['deleteVEntry'] == 1){ ?>
                        <a class="btn btn-danger btn-xs" href="<?php echo site_url('Chartofaccounting/delete_chart_of_accounting').'/'.$value['ca_id'] ?>" onclick="return confirm('Are you sure you want to delete this Accounting ?');" ><i class="fa fa-trash"></i></a>
                        <?php } ?>
                      </td>
                    </tr>   
                    <?php } ?> 
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
    
    <div class="modal fade bs-example-modal-newAccount" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content" >
          <div class="modal-header">
            <h4 class="modal-title">Account Entry Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form action="<?php echo base_url('Chartofaccounting/save_chart_of_accounting');?>" method="post">
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Accounting Type *</label>
              <select type="text" class="form-control" name="atype" id="atype" required >
                <option value="">Select One</option>
                <?php foreach ($atype as $value) { ?>
                <option value="<?php echo $value['catid']; ?>"><?= $value['catName']; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Select Sub Account *</label>
              <select type="text" class="form-control" name="sAccount" id="sAccount" required >
                <option value="">Select One</option>
              </select>
            </div>
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Account Type *</label>
              <select type="text" class="form-control" name="catype" id="catype" required >
                <option value="">Select Account First</option>
              </select>
            </div>
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Detalis *</label>
              <input type="text" class="form-control" name="cadetails" placeholder="Detalis" required >
            </div>
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Amount *</label>
              <input type="text" class="form-control" name="amount" placeholder="Amount" required >
            </div>
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Payment Method *</label>
              <select class="form-control" name="accountType" id="accountType" required >
                <option value="Cash">Cash</option>
                <option value="Bank">Bank</option>
                <option value="Mobile">Mobile</option>
              </select>
            </div>
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Account Number *</label>
              <select class="form-control" name="accountNo" id="accountNo" required >
                <option value="">Select Account Type First</option>
              </select>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary"><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    
    <div class="modal fade bs-example-modal-euser" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content" >
          <div class="modal-header">
            <h4 class="modal-title">Account Entry Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form action="<?php echo base_url('Chartofaccounting/update_chart_of_accounting');?>" method="post">
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Accounting Type *</label>
              <select type="text" class="form-control" name="atype" id="a2type" required >
                <option value="">Select One</option>
                <?php foreach ($atype as $value) { ?>
                <option value="<?php echo $value['catid']; ?>"><?= $value['catName']; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Select Sub Account *</label>
              <select type="text" class="form-control" name="sAccount" id="s2Account" required >
                <option value="">Select One</option>
              </select>
            </div>
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Account Type *</label>
              <select type="text" class="form-control" name="catype" id="ca2type" required >
                <option value="">Select Account First</option>
              </select>
            </div>
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Detalis *</label>
              <input type="text" class="form-control" name="cadetails" id="cadetails" placeholder="Detalis" required >
            </div>
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Amount *</label>
              <input type="text" class="form-control" name="amount" id="caamount" placeholder="Amount" required >
            </div>
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Payment Method *</label>
              <select class="form-control" name="accountType" id="account2Type" required >
                <option value="Cash">Cash</option>
                <option value="Bank">Bank</option>
                <option value="Mobile">Mobile</option>
              </select>
            </div>
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Account Number *</label>
              <select class="form-control" name="accountNo" id="account2No" required >
                <option value="">Select Account Type First</option>
              </select>
            </div>
            <input type="hidden" name="catid" id="catid" required >
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary"><i class="far fa-save"></i>&nbsp;&nbsp;Update</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
            </div>
          </form>
        </div>
      </div>
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
        $(document).on('click','.euser',function(){
          var catid = $(this).attr("id");
          //alert(l_id);
          $('input[name="catid"]').val(catid);
          });

        $(document).on('click','.euser',function(){
          var id = $(this).attr('id');
              //alert(id);
          var url = '<?php echo base_url() ?>Chartofaccounting/get_chart_accounting_data';
              //alert(url);
          $.ajax({
            method: 'POST',
            url     : url,
            dataType: 'json',
            data    : {'id' : id},
            success:function(data){ 
              //alert(data);
              var HTML = data['catid'];
              var HTML2 = data['cadetails'];
              var HTML3 = data['caamount'];
                //alert(HTML);
              $("#a2type").val(HTML);
              $("#cadetails").val(HTML2);
              $("#caamount").val(HTML3);
              },
              error:function(){
              alert('error');
              }
            });

          var url2 = "<?php echo base_url(); ?>Chartofaccounting/account_type_data";
          $.ajax({
            method: "POST",
            url     : url2,
            dataType: 'json',
            data    : {'id':id},
            success:function(data){
            //alert(data);
              $('#ca2type').removeAttr("disabled")
              var HTML = "";
              for (var key in data) 
                {
                if(data.hasOwnProperty(key))
                  {
                  HTML +="<option value='"+data[key]["cat_id"]+"'>" + data[key]["caType"]+"</option>";
                }}
              $("#ca2type").html(HTML);
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
        $('#a2type').change(function(){
          var url = "<?php echo base_url(); ?>Chartofaccounting/get_sub_account_data";
          var id = $('#a2type').val() ;
              //alert(id); alert(url);
          $.ajax({
            method: "POST",
            url     : url,
            dataType: 'json',
            data    : {"id" : id},
            success:function(data){ 
                //alert(data);
              $('#s2Account').removeAttr("disabled")
              var HTML = "<option value=''>Select One</option>";
              for (var key in data) 
                {
                if(data.hasOwnProperty(key))
                  {
                  HTML +="<option value='"+data[key]["satid"]+"'>" + data[key]["scaType"]+"</option>";
                }}
              $("#s2Account").html(HTML);
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
        $('#s2Account').change(function(){
          var url = "<?php echo base_url(); ?>Chartofaccounting/get_account_type";
          var id = $('#s2Account').val();
          //alert(id);alert(id2);
          $.ajax({
            method: "POST",
            url     : url,
            dataType: 'json',
            data    : {'id':id},
            success:function(data){ 
            //alert(data);
              $('#ca2type').removeAttr("disabled")
              var HTML = "<option value=''>Select One</option>";
              for (var key in data) 
                {
                if(data.hasOwnProperty(key))
                  {
                  HTML +="<option value='"+data[key]["cat_id"]+"'>" + data[key]["caType"]+"</option>";
                }}
              $("#ca2type").html(HTML);
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
    
    <script type="text/javascript">
      $(document).ready(function(){
        var value = $("#account2Type").val();
        $('#account2No').empty();
        getAccountNo(value, '#account2No');
        $('#account2No').val(1);
        });
              
      $('#account2Type').on('change',function(){
        var value = $(this).val();
        $('#account2No').empty();
        getAccountNo(value, '#account2No');
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
