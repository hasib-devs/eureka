<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>LC Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">LC Management</li>
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
                <h3 class="card-title">LC Management List</h3>
                <?php if($_SESSION['newPurchase'] == 1){ ?>
                <a href="<?php echo site_url(); ?>newLcmanagement" class="btn btn-primary" style="float: right;" ><i class="fa fa-plus"></i>&nbsp;New LC Management</a>
                <?php } ?>
              </div>

              <div class="card-body">
                <table id="example" class="table table-striped table-bordered" >
                  <thead>
                    <tr>
                      <th style="width: 5%;">#SN.</th>
                      <th>Challan</th>
                      <th>Date</th>
                      <th>Supplier</th>
                      <th>Products</th>
                      <th>Quantity</th>
                      <th>Total</th>
                      <th>Paid</th>
                      <th>Due</th>
                      <th style="width: 10%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 0;
                    foreach ($purchase as $value){
                    $i++;
                    
                    $pp = $this->db->select('
                                        lc_product.quantity,
                                        products.pName,
                                        products.pCode')
                                  ->from('lc_product')
                                  ->join('products','products.pid = lc_product.pid','left')
                                  ->where('lcid',$value['lcid'])
                                  ->get()
                                  ->result();
                                  
                    $payment = $this->db->select('SUM(cAmount) as total')
                                      ->from('lc_payment')
                                      ->where('lcid',$value['lcid'])
                                      ->get()
                                      ->row();
                    if($payment)
                      {
                      $tpa = $payment->total;
                      }
                    else
                      {
                      $tpa = 0;
                      }
                    ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $value['lcCode'] ?></td>
                      <td><?php echo date('d-m-Y',strtotime($value['lcDate'])) ?></td>
                      <td><?php echo $value['supName']; ?></td>
                      <td>
                        <?php foreach($pp as $p){ ?>
                        <?php echo $p->pName.'-'.$p->pCode; ?><br>
                        <?php } ?>
                      </td>
                      <td>
                        <?php foreach($pp as $p){ ?>
                        <?php echo $p->quantity; ?><br>
                        <?php } ?>
                      </td>
                      <td><?php echo number_format($value['tAmount'], 2) ?></td>
                      <td><?php echo number_format(($value['pAmount']+$tpa), 2) ?></td>
                      <td><?php echo number_format(($value['dAmount']-$tpa), 2); ?></td>
                      <td>
                        <a class="btn btn-info btn-xs" href="<?php echo site_url().'viewLcmanagement/'.$value['lcid']; ?>"><i class="fa fa-eye"></i></a>
                        <a class="btn btn-success btn-xs" href="<?php echo site_url().'editLcmanagement/'.$value['lcid']; ?>"><i class="fa fa-edit"></i></a>
                        <a class="btn btn-danger btn-xs" href="<?php echo site_url('Lcmanagement/delete_lc_management').'/'.$value['lcid']; ?>" onclick="return confirm('Are you sure you want to delete this LC Management ?');" ><i class="fa fa-trash"></i></a>
                        <?php if(($value['dAmount']-$tpa) > 0){ ?>
                        <a href="#" class="payment btn btn-warning btn-xs" data-toggle="modal" data-target=".bs-example-modal-payment" data-id="<?php echo $value['lcid']; ?>"><i class="fa fa-plus"></i></a>
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

    <div class="modal fade bs-example-modal-payment" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" > Payment Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form action="<?php echo base_url('Lcmanagement/save_lc_payment');?>" method="post">
            <div class="col-md-12 col-sm-12 col-12">
              <div class="form-group">
                <label>Payment Date *</label>
                <input type="text" name="lcpDate" class="form-control datepicker" value="<?php echo date('m/d/Y') ?>" required >
              </div>
              <div class="form-group">
                <label>Due Amount</label>
                <input type="text" class="form-control" name="tAmount" id="tAmount" readonly >
              </div>
              <div class="form-group">
                <label>Paid Amount *</label>
                <input type="text" class="form-control" name="pAmount" id="pAmount" onkeyup="calculateamount()" placeholder="Amount" required >
              </div>
              <div class="form-group">
                <label>Select Currency *</label>
                <select class="form-control" name="currency" required >
                  <option value="">Select One</option>
                  <?php foreach($currency as $value){ ?>
                  <option value="<?php echo $value['cid']; ?>"><?php echo $value['cName']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label>Rate *</label>
                <input type="text" class="form-control" name="pRate" id="pRate" onkeyup="calculateamount()" placeholder="Amount" required >
              </div>
              <div class="form-group">
                <label>Payable Amount *</label>
                <input type="text" class="form-control" name="cAmount" id="cAmount" placeholder="Amount" required readonly >
              </div>
              <div class="form-group">
                <label>Account Type *</label>
                <select class="form-control" name="accountType" id="accountType" required >
                  <option value="Cash">Cash</option>
                  <option value="Bank">Bank</option>
                  <option value="Mobile">Mobile</option>
                </select>
              </div>
              <div class="form-group">
                <label>Account Number *</label>
                <select class="form-control" name="accountNo" id="accountNo" required >
                  <option value="">Select Account Type First</option>
                </select>
              </div>
              <div class="form-group">
                <label>Notes</label>
                <input type="text" class="form-control" name="notes" placeholder="If Have any notes" >
              </div>
            </div>
            <input type="hidden" id="lcid" name="lcid" required >
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
        $(".payment").click(function(){
          var lcid = $(this).data('id');
            //alert(puid);
          $('input[name="lcid"]').val(lcid);
          });

        $('.payment').click(function(){
          var id = $(this).data('id');
          var url = '<?php echo base_url() ?>Lcmanagement/get_lc_payment';
            //alert(url);
          $.ajax({
            type: 'POST',
            async: false,
            url: url,
            data:{"id":id},
            dataType: 'json',
            success: function(data){
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
      function calculateamount()
        {
        var rate = $('#pRate').val();
        var total = $('#pAmount').val();
            //alert(discc);
        var da = (total/rate);
          //alert(remaining);
        $('#cAmount').val(parseFloat(da).toFixed(2));
        }
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