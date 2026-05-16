<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Courier Account</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Courier Account</li>
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
              <h3 class="card-title">Courier Account</h3>
              <?php if($_SESSION['newCAccount'] == 1){ ?>
              <button type="button" class="btn btn-primary newAccount" data-toggle="modal" data-target=".bs-example-modal-newAccount" style="float: right" ><i class="fa fa-plus"></i> New Courier Account</button>
              <?php } ?>
            </div>

            <div class="card-body">
              <table id="example" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>#SN.</th>
                    <th>Courier Name</th>
                    <th>Opening Balance</th>
                    <th>Status</th>
                    <th style="width: 10%;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 0;
                  $tba = 0;
                  foreach ($cash as $value){
                  $i++;
                
                  ?>
                  <tr class="gradeX">
                    <td><?php echo $i; ?></td>
                    <td><?php echo $value['caName']; ?></td>
                    <td><?php echo number_format($value['balance'], 2); ?></td>
                    <td><?php echo $value['status']; ?></td>
                    <td>
                      <?php if($_SESSION['editCAccount'] == 1){ ?>
                      <button type="button" class="btn btn-success btn-xs editCourier" data-toggle="modal" data-target=".bs-example-modal-editCourier" data-id="<?php echo $value['caid']; ?>" id="<?php echo $value['caid']; ?>" onclick="document.getElementById('editCourier').style.display='block'" ><i class="fa fa-edit"></i></button>
                      <?php } if($_SESSION['deleteCAccount'] == 1){ ?>
                      <a href="<?php echo site_url('Courieraccount/courier_account_delete').'/'.$value['caid'] ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this Courier Account ?');"><i class="fa fa-trash" aria-hidden="true"></i></a>
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

    <div class="modal fade bs-example-modal-newAccount" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Courier Account Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form action="<?php echo base_url('Courieraccount/save_courier_account');?>" method="post">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="form-group">
                <label>Courier Account Name *</label>
                <input type="text" name="caName" class="form-control" placeholder="Courier Account Name" required >
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

    <div class="modal fade bs-example-modal-editCourier" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" >Courier Account Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form action="<?php echo base_url('Courieraccount/update_courier_account');?>" method="post">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="form-group">
                <label>Courier Account Name *</label>
                <input type="text" name="caName" id="caName" class="form-control" placeholder="Courier Account Name" required >
              </div>
              <div class="form-group">
                <label>Opening Balance</label>
                <input type="text" name="balance" id="balance" class="form-control" placeholder=" Amount" required >
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
        $(document).on('click','.editCourier',function(){
          var caid = $(this).attr("id");
          //alert(l_id);
          $('input[name="caid"]').val(caid);
          });

        $(document).on('click','.editCourier',function(){
          var id = $(this).attr("id");
          var url = '<?php echo base_url() ?>Courieraccount/get_courier_account';
              //alert(id);alert(url);
          $.ajax({
            method: 'POST',
            url     : url,
            dataType: 'json',
            data    : {'id' : id},
            success:function(data){ 
                //alert(data);
              var HTML = data["caName"];
              var HTML2 = data["balance"];
                //alert(HTML);
              $("#caName").val(HTML);
              $("#balance").val(HTML2);
              },
            error:function(){
              alert('error');
              }
            });
          });
        });
    </script>
