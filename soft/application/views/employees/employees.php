<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Staff / Employee</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Staff / Employee</li>
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
                <h3 class="card-title">Staff / Employee List</h3>
                <?php if($_SESSION['newEmployee'] == 1){ ?>
                <button type="button" class="btn btn-primary add_emp" data-toggle="modal" data-target=".bs-example-modal-aemp" style="float: right" ><i class="fa fa-plus"></i> New Staff</button>
                <?php } ?>
              </div>

              <div class="card-body">
                <table id="example" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>#SN.</th>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Mobile</th>
                      <th>Email</th>
                      <th>Address</th>
                      <th>Join</th>
                      <th>Salary</th>
                      <th>Status</th>
                      <th style="width: 8%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 0;
                    foreach ($employee as $value) {
                    $i++;
                    ?>
                    <tr class="gradeX">
                      <td><?php echo $i; ?></td>
                      <td><?php echo $value['empCode']; ?></td>
                      <td><?php echo $value['empName']; ?></td>
                      <td><?php echo $value['empMobile']; ?></td>
                      <td><?php echo $value['empEmail']; ?></td>
                      <td><?php echo $value['empAddress']; ?></td>
                      <td><?php echo date('d-m-Y', strtotime($value['joinDate'])); ?></td>
                      <td><?php echo number_format($value['salary'], 2); ?></td>
                      <td><?php echo $value['status']; ?></td>        
                      <td>
                        <?php if($_SESSION['editEmployee'] == 1){ ?>
                        <button type="button" class="btn btn-success btn-xs emp_edit" data-toggle="modal" data-target=".bs-example-modal-eemp" data-id="<?php echo $value['empid']; ?>" ><i class="fa fa-edit"></i></button>
                        <?php } if($_SESSION['deleteEmployee'] == 1){ ?>
                        <a class="btn btn-danger btn-xs" href="<?php echo site_url('Employee/delete_Employee').'/'.$value['empid'] ?>" onclick="return confirm('Are you sure you want to delete this Employee ?');" ><i class="fa fa-trash"></i></a>
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

    <div class="modal fade bs-example-modal-aemp" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Staff Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form action="<?php echo base_url('Employee/save_employee'); ?>" method="post" enctype='multipart/form-data' >
            <div class="col-md-12 col-sm-12 col-12">
              <div class="row">
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Staff / Employee Name *</label>
                  <input type="text" class="form-control" name="empName" placeholder="Staff Name" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Staff / Employee Father Name *</label>
                  <input type="text" class="form-control" name="empFName" placeholder="Staff Father Name" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Staff / Employee Mother Name *</label>
                  <input type="text" class="form-control" name="empMName" placeholder="Staff Mother Name" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Department</label>
                  <select class="form-control" name="dptid" required >
                    <option value="">Select One</option>
                    <?php foreach($dept as $value){ ?>
                    <option value="<?php echo $value['dptid']; ?>"><?php echo $value['dptName']; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Address *</label>
                  <input type="text" class="form-control" name="empAddress" placeholder="Address" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Contact Number *</label>
                  <input type="text" class="form-control" name="empMobile" placeholder="Mobile Number *" onkeypress="return isNumberKey(event)" maxlength="11" minlength="11" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Email</label>
                  <input type="email" class="form-control" name="empEmail" placeholder="example@gmail.com" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Joining Date *</label>
                  <input type="text" class="form-control datepicker" name="joinDate" placeholder="Joining Date" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Salary</label>
                  <input type="text" class="form-control" name="salary" placeholder="Salary" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>NID Number</label>
                  <input type="text" class="form-control" name="nid" placeholder="NID Number" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Employee Files</label>
                  <input type="file" name="userfile" >
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade bs-example-modal-eemp" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content" >
          <div class="modal-header">
            <h4 class="modal-title" >Update Staff Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form action="<?php echo base_url('Employee/update_Employee');?>" method="post" enctype='multipart/form-data' >
            <div class="col-md-12 col-sm-12 col-12">
              <div class="row">
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Staff / Employee Name *</label>
                  <input type="text" class="form-control" name="empName" id="empName" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Staff / Employee Father Name *</label>
                  <input type="text" class="form-control" name="empFName" id="empFName" placeholder="Staff Father Name" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Staff / Employee Mother Name *</label>
                  <input type="text" class="form-control" name="empMName" id="empMName" placeholder="Staff Mother Name" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Department</label>
                  <select class="form-control" name="dptid" id="dptid" required >
                    <option value="">Select One</option>
                    <?php foreach ($dept as $key => $value) { ?>
                    <option value="<?php echo $value['dptid']; ?>"><?php echo $value['dptName']; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Address *</label>
                  <input type="text" class="form-control" name="empAddress" id="empAddress" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Contact Number *</label>
                  <input type="text" class="form-control" name="empMobile" id="empMobile" onkeypress="return isNumberKey(event)" maxlength="11" minlength="11" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Email</label>
                  <input type="email" class="form-control" name="empEmail" id="empEmail" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Joining Date *</label>
                  <input type="text" class="form-control datepicker" id="joinDate" name="joinDate" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Salary</label>
                  <input type="text" class="form-control" name="salary" id="salary" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>NID</label>
                  <input type="text" class="form-control" name="nid" id="nid">
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Employee Files</label>
                  <input type="file" name="userfile" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12" >
                  <label>Status</label>
                  <select class="form-control" name="status" id="status" >
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                  </select>
                </div>
              </div>
              <input type="hidden" id="empid" name="empid" required >
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="far fa-save"></i>&nbsp;&nbsp;Update</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    
<?php $this->load->view('footer/footer'); ?>

    <script type="text/javascript">
      $(document).ready(function(){
        $(".emp_edit").click(function(){
          var empid = $(this).data('id');
          //alert(l_id);
          $('input[name="empid"]').val(empid);
          });

        $('.emp_edit').click(function(){
          var id = $(this).data('id');
          var url = '<?php echo base_url() ?>Employee/get_emp_data';
            //alert(url); alert(id);
          $.ajax({
            method: 'POST',
            url     : url,
            dataType: 'json',
            data    : {'id' : id},
            success:function(data){ 
              //alert(data);
              var HTML = data["empName"];
              var HTML2 = data["dptid"];
              var HTML3 = data["empAddress"];
              var HTML4 = data["empMobile"];
              var HTML5 = data["empEmail"];
              var HTML6 = data["joinDate"];
              var HTML7 = data["salary"];
              var HTML8 = data["nid"];
              var HTML9 = data["status"];
              var HTML10 = data["empFName"];
              var HTML12 = data["empMName"];
              //alert(HTML);
              $("#empName").val(HTML);
              $("#dptid").val(HTML2);
              $("#empAddress").val(HTML3);
              $("#empMobile").val(HTML4);
              $("#empEmail").val(HTML5);
              $("#joinDate").val(HTML6);
              $("#salary").val(HTML7);
              $("#nid").val(HTML8);
              $("#status").val(HTML9);
              $("#empFName").val(HTML10);
              $("#empMName").val(HTML12);
              },
            error:function(){
              alert('error');
              }
            });
          });
        });
    </script>