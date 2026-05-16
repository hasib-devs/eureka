<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sale Service</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Sale Service</li>
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
                <h3 class="card-title">Sale Service List</h3>
                <a href="<?php echo site_url('newService') ?>" class="btn btn-primary" style="float: right; margin-right: 10px;" ><i class="fa fa-plus"></i> New Service Sale</a>
              </div>

              <div class="card-body">
                <table id="example" class="table table-responsive table-bordered" >
                  <thead>
                    <tr>
                      <th style="width: 5%;">#SN.</th>
                      <th>Invoice</th>
                      <th>Date</th>
                      <th>Customer</th>
                      <th>Total</th>
                      <th>Discount</th>
                      <th>Paid</th>
                      <th>Due</th>
                      <th style="width: 13%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 0;
                    foreach ($sales as $value){
                    $i++;
                    
                    $pay = $this->db->select("SUM(pAmount) as total")
                                    ->FROM('service_payment')
                                    ->WHERE('ssid',$value['ssid'])
                                    ->get()
                                    ->row();
                    if($pay)
                      {
                      $tpay = $pay->total;
                      }
                    else
                      {
                      $tpay = 0;
                      }
                    ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $value['ssCode']; ?></td>
                      <td><?php echo date('d-m-Y',strtotime($value['ssDate'])); ?></td>
                      <td><?php echo $value['custName']; ?></td>
                      <td><?php echo number_format($value['tAmount'], 2); ?></td>
                      <td><?php echo number_format($value['disAmount'], 2); ?></td>
                      <td><?php echo number_format($value['pAmount']+$tpay, 2); ?></td>
                      <td><?php echo number_format($value['dAmount']-$tpay, 2); ?></td>
                      <td>
                        <a class="btn btn-info btn-xs" href="<?php echo site_url().'viewSSale/'.$value['ssid']; ?>" title="view sales"><i class="fa fa-eye"></i></a>
                        <a class="btn btn-success btn-xs" href="<?php echo site_url().'editSSale/'.$value['ssid']; ?>" title="edit sales" ><i class="fa fa-edit"></i></a>
                        <a class="btn btn-danger btn-xs" href="<?php echo site_url('Sale/delete_sales_service').'/'.$value['ssid']; ?>" onclick="return confirm('Are you sure you want to delete this Sale Service ?');" title="delete sales" ><i class="fa fa-trash"></i></a>
                        <?php if(($value['dAmount']-$tpay) > 0){ ?>
                        <a href="#" class="payment btn btn-warning btn-xs" data-toggle="modal" data-target=".bs-example-modal-payment" data-id="<?php echo $value['ssid']; ?>" id="<?php echo $value['ssid']; ?>" onclick="document.getElementById('payment').style.display='block'" title="sales payment" ><i class="fa fa-plus"></i></a>
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

    <div id="payment" class="modal fade bs-example-modal-payment" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" > Payment Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form action="<?php echo base_url('Sale/save_service_payment');?>" method="post">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="form-group">
                <label>Due Amount</label>
                <input type="text" class="form-control" name="tAmount" id="tAmount" readonly >
              </div>
              <div class="form-group">
                <label>Paid Amount *</label>
                <input type="text" class="form-control" name="pAmount" id="pAmount" placeholder="Amount" required >
              </div>
              <div class="form-group">
                <label>Payment Method *</label>
                <select class="form-control" name="accountType" id="accountType" required >
                  <option value="Cash">Cash</option>
                  <option value="Bank">Bank</option>
                  <option value="Mobile">Mobile</option>
                </select>
              </div>
              <div class="form-group">
                <label>Account No *</label>
                <select class="form-control" name="accountNo" id="accountNo" >
                  <option value="">Select Payment Method First</option>
                </select>
              </div>
              <div class="form-group">
                <label>Notes</label>
                <input type="text" class="form-control" name="notes" placeholder="If Have any Notes" >
              </div>
            </div>
            <input type="hidden" id="ssid" name="ssid" required >
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" id="pbsubmit" ><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>

<?php $this->load->view('footer/footer'); ?>

    <script type="text/javascript">
      $(document).ready(function(){
        $(document).on('click','.payment',function(){
          var ssid = $(this).attr("id");
            //alert(l_id);
          $('input[name="ssid"]').val(ssid);
          });

        $(document).on('click','.payment',function(){
          var id = $(this).attr("id");
          var url = "<?php echo base_url(); ?>Sale/get_service_payment";
            //alert(id); alert(url);
          $.ajax({
            method: "POST",
            url     : url,
            dataType: "json",
            data    : {'id' : id},
            success:function(data){
            //alert(data);
              var HTML = data;
                //alert(HTML2);
              $("#tAmount").val(HTML);
              $("#pAmount").val(HTML);
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