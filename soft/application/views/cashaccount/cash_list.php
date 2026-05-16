<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Cash Account</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Cash Account</li>
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
      <div class="container">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Cash Account</h3>
              <?php if($_SESSION['newCAccount'] == 1){ ?>
              <button type="button" class="btn btn-primary add_cash" data-toggle="modal" data-target=".bs-example-modal-acash" style="float: right" ><i class="fa fa-plus"></i> New Cash Account</button>
              <?php } ?>
            </div>

            <div class="card-body">
              <table id="example" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>#SN.</th>
                    <th>Cash Name</th>
                    <th>Opening Balance</th>
                    <th>Current Balance</th>
                    <th>Status</th>
                    <th>Last Update</th>
                    <th style="width: 10%;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 0;
                  $tba = 0;
                  foreach ($cash as $value){
                  $i++;
                  $id = $value['ca_id'];
                  
                  $actype = 'Cash';
                  $current = $this->pm->get_account_amount_data($actype,$id);
                  
                  ?>
                  <tr class="gradeX">
                    <td><?php echo $i; ?></td>
                    <td><?php echo $value['cashName']; ?></td>
                    <td><?php echo number_format(($value['balance']), 2); ?></td>
                    <td><?php echo number_format(($value['balance']+$current), 2); ?></td>
                    <td><?php echo $value['status']; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($value['update'])); ?></td>
                    <td>
                      <?php if($_SESSION['editCAccount'] == 1){ ?>
                      <button type="button" class="btn btn-success btn-sm cash_edit" data-toggle="modal" data-target=".bs-example-modal-cash_edit" data-id="<?php echo $value['ca_id']; ?>" id="<?php echo $value['ca_id']; ?>" onclick="document.getElementById('cash_edit').style.display='block'" ><i class="fa fa-edit"></i></button>
                      <?php } if($_SESSION['deleteCAccount'] == 1){ ?>
                      <a href="<?php echo site_url('CashAccount/cash_account_delete').'/'.$value['ca_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this Cash Account ?');"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
    </section>
  </div>

    <div class="modal fade bs-example-modal-acash" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Cash Account Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form action="<?php echo base_url('CashAccount/save_cash_account');?>" method="post">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="form-group">
                <label>Cash Name *</label>
                <input type="text" name="cashName" class="form-control" placeholder="Cash Name" required >
              </div>
              <div class="form-group">
                <label>Opening Balance</label>
                <input type="text" name="balance" class="form-control" placeholder="Amount" required >
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary"><i class="far fa-save"></i>&nbsp;&nbsp;Save</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade bs-example-modal-cash_edit" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" >Cash Book Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form action="<?php echo base_url('CashAccount/update_cash_account');?>" method="post">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="form-group">
                <label>Cash Name *</label>
                <input type="text" name="cashName" id="cashName" class="form-control" placeholder="Cash Name" required >
              </div>
              <div class="form-group">
                <label>Opening Balance</label>
                <input type="text" name="balance" id="balance" class="form-control" placeholder="Cash Amount" required >
                <input type="hidden" class="form-control" name="pbalance" id="pbalance" >
                <input type="hidden" class="form-control" name="current" id="current" >
              </div>
            </div>
            <input type="hidden" id="caid" name="caid" required >
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Update</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>

<?php $this->load->view('footer/footer'); ?>

    <script type="text/javascript">
      $(document).ready(function(){
        $(document).on('click','.cash_edit',function(){
          var caid = $(this).attr("id");
          //alert(l_id);
          $('input[name="caid"]').val(caid);
          });

        $(document).on('click','.cash_edit',function(){
          var id = $(this).attr("id");
          var url = '<?php echo base_url() ?>CashAccount/get_cash_account';
              //alert(id);alert(url);
          $.ajax({
            method: 'POST',
            url     : url,
            dataType: 'json',
            data    : {'id' : id},
            success:function(data){ 
                //alert(data);
              var HTML = data["cashName"];
              var HTML2 = data["balance"];
              var HTML6 = data["current"];
                //alert(HTML);
              $("#cashName").val(HTML);
              $("#balance").val(HTML2);
              $("#pbalance").val(HTML2);
              $("#current").val(HTML6);
              },
            error:function(){
              alert('error');
              }
            });
          });
        });
    </script>
