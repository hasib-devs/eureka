<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Service</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Service</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <?php
    $exception = $this->session->userdata('exception');
    if(isset($exception))
    {
    echo $exception;
    $this->session->unset_userdata('exception');
    } ?>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Services List</h3>
                <?php if($_SESSION['newService'] == 1){ ?>
                <button type="button" class="btn btn-primary product_add" data-toggle="modal" data-target=".bs-example-modal-product_add" style="float: right; margin-left: 10px;" ><i class="fa fa-plus"></i>&nbsp;New Service</button>
                <?php } ?>
              </div>

              <div class="card-body">
                <table id="example" class="table table-responsive table-bordered" >
                  <thead>
                    <tr>
                      <th style="width: 5%;">#SN.</th>
                      <th>Name</th>
                      <th>Code</th>
                      <th>Details</th>
                      <th>Price</th>
                      <th>Status</th>
                      <th style="width: 10%;">Action</th> 
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 0;
                    foreach ($services as $value){
                    $i++;
                    ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $value['sName']; ?></td>
                      <td><?php echo $value['sCode']; ?></td>
                      <td><?php echo $value['sDetails']; ?></td>
                      <td><?php echo number_format($value['sprice'], 2); ?></td>
                      <td><?php echo $value['status']; ?></td>
                      <td>
                        <?php if($_SESSION['editService'] == 1){ ?>
                        <button type="button" class="btn btn-success btn-xs editService" data-toggle="modal" data-target=".bs-example-modal-editService" data-id="<?php echo $value['sid']; ?>" id="<?php echo $value['sid']; ?>" onclick="document.getElementById('editService').style.display='block'" ><i class="fa fa-edit"></i></button>
                        <?php } if($_SESSION['deleteService'] == 1){ ?>
                        <a class="btn btn-danger btn-xs" href="<?php echo site_url('Product/delete_service').'/'.$value['sid']; ?>" onclick="return confirm('Are you sure you want to delete this Service ?');" ><i class="fa fa-trash"></i></a>
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


    <div class="modal fade bs-example-modal-product_add" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Service Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form method="POST" action="<?php echo base_url() ?>Product/save_service" enctype="multipart/form-data" >
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Service Name *</label>
              <input type="text" class="form-control" name="sName" placeholder="Service Name" required >
            </div>
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Service Details</label>
              <textarea class="form-control" name="sDetails" placeholder="Service Details" rows="4" ></textarea>
            </div>
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Service Price </label>
              <input type="text" class="form-control" name="sprice" placeholder="Amount" required >
            </div>
            <div class="modal-footer form-group">
              <button type="submit" class="btn btn-primary"><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade bs-example-modal-editService" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Service Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form method="POST" action="<?php echo base_url() ?>Product/update_service" enctype="multipart/form-data" >
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Service Name *</label>
              <input type="text" class="form-control" name="sName" id="sName" required >
            </div>
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Service Details</label>
              <textarea class="form-control" name="sDetails" id="sDetails" rows="4" ></textarea>
            </div>
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Service Price </label>
              <input type="text" class="form-control" name="sprice" id="sprice" required >
            </div>
            <div class="form-group col-md-12 col-sm-12 col-12">
              <label>Status</label>
              <select class="form-control" name="status" id="status" >
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>
            <input type="hidden" name="sid" id="sid" required >
            <div class="modal-footer form-group">
              <button type="submit" class="btn btn-primary"><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>

<?php $this->load->view('footer/footer'); ?>


    <script type="text/javascript">
      $(document).ready(function(){
        $(document).on('click','.editService',function(){
          var sid = $(this).attr('id');
          //alert(l_id);
          $('input[name="sid"]').val(sid);
          });

        $(document).on('click','.editService',function(){
          var id = $(this).attr('id');
          //alert(id);
          var url = '<?php echo base_url() ?>Product/get_service_data';
            //alert(url);
          $.ajax({
            method: 'POST',
            url     : url,
            dataType: 'json',
            data    : {'id' : id},
            success:function(data){ 
              //alert(data);
              var HTML = data["sName"];
              var HTML2 = data["sDetails"];
              var HTML3 = data["sprice"];
              var HTML4 = data["status"];
              //alert(HTML);
              $("#sName").val(HTML);
              $("#sDetails").val(HTML2);
              $("#sprice").val(HTML3);
              $("#status").val(HTML4);
              },
            error:function(){
              alert('error');
              }
            });
          });
        });
    </script>