<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Courier</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Courier</li>
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
          <div class="col-md-8 col-sm-8 col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Courier List</h3>
              </div>

              <div class="card-body">
                <table class="table table-responsive table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 5%;">#SN.</th>
                      <th>Courier Name</th>
                      <th>Employee Name</th>
                      <th style="width: 12%;">Status</th>
                      <th>Created Date</th>
                      <th style="width: 13%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 0;
                    foreach ($dept as $value) {
                    $i++;
                    ?>
                    <tr class="gradeX">
                      <td><?php echo $i; ?></td>
                      <td><?php echo $value['courierName']; ?></td>  
                      <td><?php echo $value['empName']; ?></td>  
                      <td><?php echo $value['status']; ?></td>
                      <td><?php echo date('d-m-Y', strtotime($value['regdateone'])); ?></td>     
                      <td>
                        <?php if($_SESSION['editDepartment'] == 1){ ?>
                        <button type="button" class="btn btn-primary btn-xs dept_edit" data-toggle="modal" data-target=".bs-example-modal-sm" data-id="<?php echo $value['id']; ?>" onclick="document.getElementById('dept_edit').style.display='block'" ><i class="fa fa-edit"></i></button>
                        <?php } if($_SESSION['deleteDepartment'] == 1){ ?>
                        <a class=" btn btn-danger btn-xs" href="<?php echo site_url('Courier/delete_courier').'/'.$value['id'] ?>" onclick="return confirm('Are you sure you want to delete this Courier ?');" ><i class="fa fa-trash"></i></a>
                        <?php } ?>
                      </td>
                    </tr>   
                    <?php } ?> 
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
              <div class="modal-content" >
                <div class="modal-header">
                  <h5 class="modal-title">Courier Information edit</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                  </button>
                </div>
                <form action="<?php echo base_url('Courier/update_courier');?>" method="post">
                    <div class="form-group col-md-12 col-sm-12 col-12">
                      <label>Select Employee *</label>
                      <select class="form-control select2" name="empID" id="empID" required >
                        <option value="">Select One</option>
                        <!--<option value="cust">New Customer</option>-->
                        <?php foreach($emp as $value){ ?>
                        <option value="<?php echo $value['empid']; ?>"><?php echo $value['empName']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  <div class="form-group col-md-12 col-sm-12 col-12">
                    <label>Courier Name *</label>
                    <input type="text" class="form-control" name="courierName" id="courierName" value="fa" required >
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-12">
                    <label>Status</label>
                    <select class="form-control" name="status" id="status" >
                      <option value="Active">Active</option>
                      <option value="Inactive">Inactive</option>
                    </select>
                  </div>
                  <input type="hidden" id="id" name="id" required >
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="far fa-save"></i>&nbsp;&nbsp;Update</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <?php if($_SESSION['newDepartment'] == 1){ ?>
          <div class="col-md-4 col-sm-4 col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Courier Information</h3>
              </div>
              <div class="card-body">
                <form method="POST" action="<?php echo site_url("Courier/save_courier") ?>">
                  <div class="form-group col-md-12 col-sm-12 col-12">
                      <label>Select Employee *</label>
                      <select class="form-control select2" name="empID" id="empID" required >
                        <option value="">Select One</option>
                        <!--<option value="cust">New Customer</option>-->
                        <?php foreach($emp as $value){ ?>
                        <option value="<?php echo $value['empid']; ?>"><?php echo $value['empName']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  <div class="form-group col-md-12 col-sm-12 col-12">
                    <label>Courier Name *</label>
                    <input type="text" class="form-control" name="courierName" id="courierName" required >
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

<?php $this->load->view('footer/footer'); ?>

    <script type="text/javascript">
      $(document).ready(function(){
        $(".dept_edit").click(function(){
          var id = $(this).data('id');
          //alert(l_id);
          $('input[name="id"]').val(id);
          });

        $('.dept_edit').click(function(){
          var id = $(this).data('id');
            //alert(id);
          var url = '<?php echo base_url() ?>Courier/get_courier_data';
            //alert(url);
          $.ajax({
            method: 'POST',
            url     : url,
            dataType: 'json',
            data    : {'id' : id},
            success:function(data){ 
              //alert(data);
              var HTML = data["courierName"];
              var HTML2 = data["status"];
                //alert(HTML);
              $("#courierName").val(HTML);
              $("#status").val(HTML2);
              },
            error:function(){
              alert('error');
              }
            });
          });
        });
    </script>