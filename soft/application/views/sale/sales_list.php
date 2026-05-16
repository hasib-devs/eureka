<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sales</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Sales</li>
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
                <h3 class="card-title">Sales List</h3>
                <?php if($_SESSION['newSale'] == 1){ ?>
                <a href="<?php echo site_url('newSale') ?>" class="btn btn-primary" style="float: right; margin-right: 10px;" ><i class="fa fa-plus"></i> New Sale</a>
                <?php } ?>
                <!-- <a href="<?php echo site_url('Sale/sales_export_action') ?>" class="btn btn-success" style="float: right; margin-right: 10px;" ><i class="fa fa-list"></i> Export Sales</a> -->
              </div>

              <div class="card-body">
                <table id="example1" class="table table-striped table-bordered" >
                  <thead>
                    <tr>
                      <th style="width: 5%;">#SN.</th>
                      <th>Invoice</th>
                      <th>Date</th>
                      <th>Customer</th>
                      <th>Mobile</th>
                      <th>Total</th>
                      <th>Discount</th>
                      <th>Paid</th>
                      <th>Due</th>
                      <th>Status</th>
                      <th style="width: 10% !important;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 0;
                    foreach ($sales as $value){
                    $i++;
                    
                    $payment = $this->db->select("*")
                                    ->FROM('sales_payment')
                                    ->WHERE('said',$value['said'])
                                    ->get()
                                    ->result();
                                    
                    $tpur = $this->db->select("psType")
                                    ->FROM('sales_duplicate')
                                    ->WHERE('said',$value['said'])
                                    ->get()
                                    ->row();
                    
                    $pay = $this->db->select("SUM(pAmount) as total")
                                    ->FROM('sales_payment')
                                    ->WHERE('said',$value['said'])
                                    ->get()
                                    ->row();
                                    
                    $cat = $this->db->select("sale_product.*, products.catid,categories.catid")
                                    ->FROM('sale_product')
                                    ->join('products','products.pid = sale_product.pid', 'left')
                                    ->join('categories', 'categories.catid = products.catid', 'left')
                                    ->WHERE('said', $value['said'])
                                    ->get()
                                    ->row();
                    // var_dump($cat->catid);
                    if($pay)
                      {
                      $tpay = $pay->total;
                      }
                    else
                      {
                      $tpay = 0;
                      }
                    
                    $return = $this->db->select("invoice")
                                    ->FROM('returns')
                                    ->WHERE('invoice',$value['invoice'])
                                    ->get()
                                    ->row();
                    ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $value['invoice']; ?></td>
                      <td><?php echo date('d-m-Y',strtotime($value['saDate'])); ?></td>
                      <td><?php echo $value['custName']; ?></td>
                      <td><?php echo $value['custMobile']; ?></td>
                      <td><?php echo number_format($value['tAmount'], 2); ?></td>
                      <td><?php echo number_format($value['disAmount'], 2); ?></td>
                      <td><?php echo number_format($value['pAmount']+$tpay, 2); ?></td>
                      <td><?php echo number_format($value['dAmount']-$tpay, 2); ?></td>
                      <td>
                        <?php
                        if($return){
                          echo '<span style="color:red">Return</span>';
                        }else if(round($value['dAmount']-$tpay) <= 0){
                          echo '<span style="color:green">PAID</span>';
                        }else if($value['dAmount'] < $value['tAmount']) {
                          echo '<span style="color:red">PARTIAL</span>';
                        }else{
                          echo '<span style="color:red">DUE</span>';
                        } ?>
                      </td>
                      <td>
                        <?php if($cat->catid == 1){?>
                        <a class="btn btn-info btn-xs" href="<?php echo site_url().'viewSale/'.$value['said']; ?>" title="view sales"><i class="fa fa-eye"></i></a>
                        <?php } else {?>
                        <a class="btn btn-info btn-xs" href="<?php echo site_url().'viewPartSale/'.$value['said']; ?>" title="view part sales"><i class="fa fa-eye"></i></a>
                        <?php }?>
                        <a class="btn btn-info btn-xs" href="<?php echo site_url().'viewSChalan/'.$value['said']; ?>" title="view challan" ><i class="fa fa-file"></i></a>
                        <?php if($_SESSION['editSale'] == 1){ ?>
                        <a class="btn btn-success btn-xs" href="<?php echo site_url().'editSale/'.$value['said']; ?>" title="edit sales" ><i class="fa fa-edit"></i></a>
                        <?php } if($_SESSION['deleteSale'] == 1){ ?>
                        <a class="btn btn-danger btn-xs" href="<?php echo site_url('Sale/delete_sales').'/'.$value['said']; ?>" onclick="return confirm('Are you sure you want to delete this Sales ?');" title="delete sales" ><i class="fa fa-trash"></i></a>
                        <?php } if(round($value['dAmount']-$tpay) > 0){ ?>
                        <a href="#" class="payment btn btn-warning btn-xs" data-toggle="modal" data-target=".bs-example-modal-payment" data-id="<?php echo $value['said']; ?>" id="<?php echo $value['said']; ?>" onclick="document.getElementById('payment').style.display='block'" title="sales payment" ><i class="fa fa-plus"></i></a>
                        <?php } ?>
                        <?php if($_SESSION['salesbrta'] == 1){ ?>
                        <?php if(!$tpur){ ?>
                        <!--<a class="btn btn-secondary btn-xs" href="<?php echo site_url().'newUSale/'.$value['said']; ?>" title="sales BRTA Registration"><i class="fa fa-check"></i></a>-->
                        <?php } } ?>
                      </td>
                    </tr>
                    
                    <?php
                    $j = 0;
                    foreach($payment as $pvalue){
                    $j++;
                    ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td></td>
                      <td><?php echo date('d-m-Y',strtotime($pvalue->regdate)); ?></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td><?php echo number_format($pvalue->pAmount, 2); ?></td>
                      <td></td>
                      <td></td>
                      <td><?php echo '<span style="color:green">Paid</span>'; ?></td>
                      <td>
                        <?php if($_SESSION['role'] == 2){ ?>
                        <a class="btn btn-danger btn-xs" href="<?php echo site_url('Sale/delete_sales_payment').'/'.$pvalue->spid; ?>" onclick="return confirm('Are you sure you want to delete this Sales Payment ?');" title="delete sales" ><i class="fa fa-trash"></i></a>
                        <?php } ?> 
                      </td>
                    </tr>   
                    <?php } ?> 
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

    <div id="payment" class="modal fade bs-example-modal-payment" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" > Payment Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form action="<?php echo base_url('Sale/save_sales_payment');?>" method="post">
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
            <input type="hidden" id="said" name="said" required >
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
          var said = $(this).attr("id");
            //alert(l_id);
          $('input[name="said"]').val(said);
          });

        $(document).on('click','.payment',function(){
          var id = $(this).attr("id");
            //alert(id);
          var url = "<?php echo base_url(); ?>Sale/get_sales_payment";
            //alert(url);
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