<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sub Account Type</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Sub Account Type</li>
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
          <div class="col-md-8 col-sm-8 col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Sub Account Type List</h3>
              </div>
              <div class="card-body">
                <table id="example" class="table table-responsive table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 5%;">#SN.</th>
                      <th>Type</th>
                      <th>Major Type</th>
                      <th>Sub Type</th>
                      <th style="width: 15%;">Action</th>
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
                      <td>
                        <?php if($_SESSION['editSType'] == 1){ ?>
                        <button type="button" class="btn btn-success btn-sm euser" data-toggle="modal" data-target=".bs-example-modal-euser" data-id="<?php echo $value['cat_id']; ?>" id="<?php echo $value['cat_id']; ?>" onclick="document.getElementById('euser').style.display='block'" ><i class="fa fa-edit"></i></button>
                        <?php } if($_SESSION['deleteSType'] == 1){ ?>
                        <a class="btn btn-danger btn-sm" href="<?php echo site_url('Chartofaccounting/delete_chart_of_account_type').'/'.$value['cat_id'] ?>" onclick="return confirm('Are you sure you want to delete this Account Type ?');" ><i class="fa fa-trash"></i></a>
                        <?php } ?>
                      </td>
                    </tr>   
                    <?php } ?> 
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <?php if($_SESSION['newSType'] == 1){ ?>
          <div class="col-md-4 col-sm-4 col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Sub Account Type</h3>
              </div>
              <div class="card-body">
                <form method="POST" action="<?php echo site_url("Chartofaccounting/save_chart_of_account_type") ?>">
                  <div class="form-group col-md-12 col-sm-12 col-12">
                    <label>Select Account *</label>
                    <select type="text" class="form-control" name="atype" id="mAccount" required >
                      <option value="">Select One</option>
                      <?php foreach ($atype as $value) { ?>
                      <option value="<?php echo $value['catid']; ?>"><?= $value['catName']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-12">
                    <label>Select Major Account *</label>
                    <select type="text" class="form-control" name="sAccount" id="sAccount" required >
                      <option value="">Select One</option>
                    </select>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-12">
                    <label>Sub Account Type *</label>
                    <input type="text" name="catype" class="form-control" placeholder="Account Type" required >
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-12" >
                    <button type="submit" class="form-control btn btn-primary"><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </section>
  </div>

    <div class="modal fade bs-example-modal-euser" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content" >
          <div class="modal-header">
            <h4 class="modal-title">Chart of Account Type</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form action="<?php echo base_url('Chartofaccounting/update_chart_of_account_type');?>" method="post">
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Select Account *</label>
              <select type="text" class="form-control" name="atype" id="atype" required >
                <option value="">Select One</option>
                <?php foreach ($atype as $value) { ?>
                <option value="<?php echo $value['catid']; ?>"><?= $value['catName']; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Select Major Account *</label>
              <select type="text" class="form-control" name="sAccount" id="s2Account" required >
                <option value="">Select One</option>
              </select>
            </div>
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Sub Account Type *</label>
              <input type="text" name="catype" id="caType" class="form-control" placeholder="Account Type" required >
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
        $('#mAccount').change(function(){
          var url = "<?php echo base_url(); ?>Chartofaccounting/get_sub_account_data";
          var id = $('#mAccount').val() ;
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
          var url = '<?php echo base_url() ?>Chartofaccounting/get_chart_account_type_data';
              //alert(url);
          $.ajax({
            method: 'POST',
            url     : url,
            dataType: 'json',
            data    : {'id' : id},
            success:function(data){ 
              //alert(data);
              var HTML = data['catid'];
              var HTML2 = data['caType'];
                //alert(HTML);
              $("#atype").val(HTML);
              $("#caType").val(HTML2);
              },
              error:function(){
              alert('error');
              }
            });
        });
      });
    </script>
    
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