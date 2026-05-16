<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Customer</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Customer</li>
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
                <h3 class="card-title">Customer List</h3>
                <?php if($_SESSION['newCustomer'] == 1){ ?>
                <button type="button" class="btn btn-primary add_customer" data-toggle="modal" data-target=".bs-example-modal-add_customer" style="float: right;" ><i class="fa fa-plus"></i> New Customer</button>
                <?php } ?>
                <!-- <button type="button" class="btn btn-success template" data-toggle="modal" data-target=".bs-example-modal-template" style="float: right; margin-right: 10px;" ><i class="far fa-file-excel"></i> Import</button> -->
              </div>

              <div class="card-body">
                <table id="example" class="table table-striped table-bordered" >
                  <thead>
                    <tr>
                      <th style="width: 5%;">#SN.</th>
                      <th>Name</th>
                      <th>Father's</th>
                      <th>Mobile</th>
                      <th>Address</th>
                      <th>BRTA Office</th>
                      <th>Email</th>
                      <th style="width: 10%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 0;
                    foreach ($customer as $value){
                    $i++;
                    ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $value['custName']; ?></td>
                      <td><?php echo $value['custfName']; ?></td>
                      <td><?php echo $value['custMobile']; ?></td>
                      <td><?php echo $value['custAddress']; ?></td>
                      <td><?php echo $value['brtaDis']; ?></td>
                      <td><?php echo $value['custEmail']; ?></td>
                      <td>
                        <a class="btn btn-info btn-xs" href="<?php echo site_url().'viewCust/'.$value['custid']; ?>" ><i class="fa fa-eye"></i></a>
                        <?php if($_SESSION['editCustomer'] == 1){ ?>
                        <button type="button" class="btn btn-primary btn-xs customer_edit" data-toggle="modal" data-target=".bs-example-modal-customer_edit" data-id="<?php echo $value['custid']; ?>" id="<?php echo $value['custid']; ?>" onclick="document.getElementById('customer_edit').style.display='block'" ><i class="fa fa-edit"></i></button>
                        <?php } if($_SESSION['deleteCustomer'] == 1){ ?>
                        <a class="btn btn-danger btn-xs" href="<?php echo site_url('Customer/delete_customer').'/'.$value['custid']; ?>" onclick="return confirm('Are you sure you want to delete this Customer ?');" ><i class="fa fa-trash"></i></a>
                        <?php } ?>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
                <?php echo $pagination_html; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

    <div class="modal fade bs-example-modal-add_customer" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content" >
          <div class="modal-header">
            <h4 class="modal-title">New Customer Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form method="post" action="<?php echo base_url('Customer/save_customer'); ?>" enctype="multipart/form-data" >
            <div class="col-md-12 col-sm-12 col-12">
              <div class="row">
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Name *</label>
                  <input type="text" class="form-control" name="custName" placeholder="Customer Name" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Father's Name</label>
                  <input type="text" class="form-control" name="custfName" placeholder="Father's Name" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Mother's Name </label>
                  <input type="text" class="form-control" name="custmName" placeholder="Mother's Name" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Spouse Name </label>
                  <input type="text" class="form-control" name="spouse" placeholder="Spouse Name" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Contact Number</label>
                  <input type="text" class="form-control" name="custMobile" placeholder="Mobile Number" onkeypress="return isNumberKey(event)" maxlength="11" minlength="11">
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Email</label>
                  <input type="email" class="form-control" name="custEmail" placeholder="example@sunshine.com" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Present Address</label>
                  <input type="text" class="form-control" name="custAddress" placeholder="Present Address">
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Permanent Address</label>
                  <input type="text" class="form-control" name="custpAddress" placeholder="Permanent Address" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label> BRTA Office </label>
                  <input type="text" class="form-control" name="brtaDis" placeholder="BRTA Office" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Gender</label>
                  <select class="form-control" name="custGender" >
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Others">Others</option>
                  </select>
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Date of Birth</label>
                  <input type="text" name="custDob" class="form-control datepicker" value="<?php echo date('m/d/Y') ?>" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Nationality </label>
                  <input type="text" name="custNation" class="form-control" placeholder="Nationality" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>NID Number</label>
                  <input type="text" class="form-control" name="custNid" placeholder="NID Number" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>NID Image</label>
                  <input type="file" name="custNFiles" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Driving License Number</label>
                  <input type="text" class="form-control" name="custDriving" placeholder="Driving License Number">
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Driving License Image</label>
                  <input type="file" name="custDFiles">
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Bank</label>
                  <input type="text" class="form-control" name="custBank" placeholder="Customer Bank"  >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Bank Account Number </label>
                  <input type="text" class="form-control" name="custBNumber" placeholder="Bank Account Number" >
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary"><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade bs-example-modal-customer_edit" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content" >
          <div class="modal-header">
            <h4 class="modal-title">Update Customer Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form action="<?php echo base_url('Customer/update_customer'); ?>" method="post" enctype="multipart/form-data" >
            <div class="col-md-12 col-sm-12 col-12">
              <div class="row">
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Name *</label>
                  <input type="text" class="form-control" name="custName" id="custName" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Father's Name</label>
                  <input type="text" class="form-control" name="custfName" id="custfName">
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Mothers's Name </label>
                  <input type="text" class="form-control" name="custmName" id="custmName" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Spouse Name</label>
                  <input type="text" class="form-control" name="spouse" id="spouse" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label>Contact Number</label>
                  <input type="text" class="form-control" name="custMobile" id="custMobile" onkeypress="return isNumberKey(event)" maxlength="11" minlength="11">
                </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label>Email</label>
                  <input type="email" class="form-control" name="custEmail" id="custEmail" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label>Present Address</label>
                  <input type="text" class="form-control" name="custAddress" id="custAddress">
                </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label>Permanent Address</label>
                  <input type="text" class="form-control" name="custpAddress" id="custpAddress" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label>BRTA Office</label>
                  <input type="text" class="form-control" name="brtaDis" id="brtaDis" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Gender</label>
                  <select class="form-control" name="custGender" id="custGender" >
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Others">Others</option>
                  </select>
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Date of Birth</label>
                  <input type="text" class="form-control datepicker" name="custDob" id="custDob" value="<?php echo date('m/d/Y') ?>" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Nationality </label>
                  <input type="text" name="custNation" class="form-control" id="custNation" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>NID Number</label>
                  <input type="text" class="form-control" name="custNid" id="custNid" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>NID Image</label>
                  <input type="file" name="custNFiles" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Driving License Number</label>
                  <input type="text" class="form-control" name="custDriving" id="custDriving">
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Driving License Image</label>
                  <input type="file" name="custDFiles" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Bank</label>
                  <input type="text" class="form-control" name="custBank" id="custBank" placeholder="Customer Bank"  >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Bank Account Number </label>
                  <input type="text" class="form-control" name="custBNumber" id="custBNumber" placeholder="Bank Account Number" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label>Status</label>
                  <select class="form-control" name="status" id="status" >
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                  </select>
                </div>
              </div>
              <input type="hidden" id="custid" name="custid" required >
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="far fa-save"></i>&nbsp;&nbsp;Update</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade bs-example-modal-template" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content" >
          <div class="modal-header">
            <h4 class="modal-title">Customer Template</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="row">
            <div class="form-group col-md-6 col-sm-6 col-12">
              <div style="width: 100%; height: 100px;background: #fff4f4;text-align: center;">
                <a href="<?php echo base_url('assets/templates/customers.xlsx') ?>" style="padding:1em;text-align: center;display:inline-block;text-decoration: none !important;margin:0 auto;">New template</a>
              </div>
            </div>
            <div class="form-group col-md-6 col-sm-6 col-12">
              <div style="width: 100%; height: 100px;background: #fff4f4;text-align: center;">
                <a href="<?php echo base_url('Customer/export_action') ?>" style="padding:1em;text-align: center;display:inline-block;text-decoration: none !important;margin:0 auto;">Exit  template</a>
              </div>
            </div>
          </div>
          <div class="col-md-12 col-sm-12 col-12">
            <form method="post" id="import_form" enctype="multipart/form-data">
              <div class="form-group col-md-12 col-sm-12 col-12">
                <label>Import Template<span style="color: red">*</span></label>
                <input type="file" name="file" id="file" required accept=".xls, .xlsx" >
              </div>
              <div class="form-group col-md-12 col-sm-12 col-xs-12" style="margin-top: 25px; text-align: center;">
                <input type="submit" name="import" value="Import" class="btn btn-info" >
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>

<?php $this->load->view('footer/footer'); ?>


  <script type="text/javascript">
    $(document).ready(function(){
      $(document).on('click','.customer_edit',function(){
        var custid = $(this).attr('id');
        $('input[name="custid"]').val(custid);
        });

      $(document).on('click','.customer_edit',function(){
        var id = $(this).attr('id');
        var url = '<?php echo base_url() ?>Customer/get_customer_data';
        $.ajax({
          method: 'POST',
          url     : url,
          dataType: 'json',
          data    : {'id' : id},
          success:function(data){ 
            var HTML = data["custName"];
            var HTML2 = data["custfName"];
            var HTML3 = data["custMobile"];
            var HTML4 = data["custEmail"];
            var HTML5 = data["custAddress"];
            var HTML6 = data["custpAddress"];
            var HTML7 = data["custNid"];
            var HTML8 = data["custDriving"];
            var HTML9 = data["status"];
            var HTML10 = data["custGender"];
            var HTML11 = data["custDob"];
            var HTML12 = data["custNation"];
            var HTML13 = data["custmName"];
            var HTML14 = data["spouse"];
            var HTML15 = data["brtaDis"];
            var HTML16 = data["custBank"];
            var HTML17 = data["custBNumber"];

            $("#custName").val(HTML);
            $("#custfName").val(HTML2);
            $("#custMobile").val(HTML3);
            $("#custEmail").val(HTML4);
            $("#custAddress").val(HTML5);
            $("#custpAddress").val(HTML6);
            $("#custNid").val(HTML7);
            $("#custDriving").val(HTML8);
            $("#status").val(HTML9);
            $("#custGender").val(HTML10);
            $("#custDob").val(HTML11);
            $("#custNation").val(HTML12);
            $("#custmName").val(HTML13);
            $("#spouse").val(HTML14);
            $("#brtaDis").val(HTML15);
            $("#custBank").val(HTML16);
            $("#custBNumber").val(HTML17);
            },
          error:function(){
            alert('error');
            }
          });
        });
      });
  </script>

    <script type="text/javascript" >
      $(document).ready(function(){
        $('#import_form').on('submit', function(event){
          event.preventDefault();
          $.ajax({
            url:"<?php echo base_url(); ?>Customer/excel_import",
            method:"POST",
            data:new FormData(this),
            contentType:false,
            cache:false,
            processData:false,
            success:function(data)
              {
              $('#file').val('');
              load_data();
              alert(data);
              }
            });
          });
        });
    </script>