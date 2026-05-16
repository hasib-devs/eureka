<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Purchase Order</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Purchase Order</li>
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
                <h3 class="card-title">Purchase Order List</h3>
                <?php if($_SESSION['newPurchase'] == 1){ ?>
                <a href="<?php echo site_url('newPurchase'); ?>" class="btn btn-primary" style="float: right;" ><i class="fa fa-plus"></i>&nbsp;New Purchase Order</a>
                <?php } ?>
              </div>

              <div class="card-body">
                <table id="example1" class="table table-responsive table-bordered" >
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
                      <th>Due Status</th>
                      <th>Approval Status</th>
                      <th style="width: 10%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 0;
                    foreach ($purchase as $value){
                    $i++;
                    $pp = $this->db->select('
                                        purchase_product.ppChassis,
                                        purchase_product.ppEngine,
                                        purchase_product.quantity,
                                        products.pName,
                                        products.pCode')
                                  ->from('purchase_product')
                                  ->join('products','products.pid = purchase_product.pid','left')
                                  ->where('puid',$value['puid'])
                                  ->get()
                                  ->result();
                    $payment = $this->db->select('SUM(pAmount) as total')
                                      ->from('purchase_payment')
                                      ->where('puid',$value['puid'])
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
                      <td><?php echo $value['challanNo'] ?></td>
                      <td style="width: 10%;"><?php echo date('d-M-Y',strtotime($value['puDate'])) ?></td>
                      <td><?php echo $value['supName'] ?></td>
                      <td>
                        <?php foreach ($pp as $p) { ?>
                        <?php echo $p->pName.'-'.$p->pCode; ?><br>
                        <?php } ?>
                      </td>
                      <td>
                        <?php foreach ($pp as $p) { ?>
                        <?php echo $p->quantity; ?><br>
                        <?php } ?>
                      </td>
                      <td><?php echo number_format($value['tAmount'], 2) ?></td>
                      <td><?php echo number_format($value['pAmount']+$tpa, 2) ?></td>
                      <td><?php echo number_format($value['dAmount']-$tpa, 2); ?></td>
                      <td>
                        <?php
                        if($value['dAmount'] - $tpa == 0){
                          echo '<span style="color:green">PAID</span>';
                        }elseif ($value['dAmount'] < $value['dAmount']) {
                          echo '<span style="color:red">PARTIAL</span>';
                        }else{
                          echo '<span style="color:red">DUE</span>';
                        } ?>
                      </td>
                      
                      
                      <td style="text-align: center; color: <?php echo ($value['status'] == 1) ? 'green' : 'red'; ?>;">
                        <?php echo ($value['status'] == 1) ? 'Approved' : 'Not-Approved'; ?>
                      </td>
                      
                      
                     <td>
                        <a class="btn btn-info btn-xs" href="<?php echo site_url().'viewPurchase/'.$value['puid']; ?>"><i class="fa fa-eye"></i></a>
                        <?php if($value['status'] == 0){ ?>
                        <?php if($_SESSION['editPurchase'] == 1){ ?>
                        <a class="btn btn-success btn-xs" href="<?php echo site_url().'editPurchase/'.$value['puid']; ?>"><i class="fa fa-edit"></i></a>
                        
                        <a class="btn btn-info btn-xs" href="<?php echo site_url().'apvPurchase/'.$value['puid']; ?>" title="approve" ><i class="fa fa-check"></i></a>
                        
                        <?php } if($_SESSION['deletePurchase'] == 1){ ?>
                        <a class="btn btn-danger btn-xs" href="<?php echo site_url('Purchase/delete_purchases').'/'.$value['puid']; ?>" onclick="return confirm('Are you sure you want to delete this Purchase ?');" ><i class="fa fa-trash"></i></a>
                        <?php } if(($value['dAmount']-$tpa) > 0){ ?>
                        <!--<a href="#" class="payment btn btn-warning btn-sm" data-toggle="modal" data-target=".bs-example-modal-payment" data-id="<?php echo $value['puid']; ?>"><i class="fa fa-plus"></i></a>-->
                        <?php } ?> 
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

    <div class="modal fade bs-example-modal-payment" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" > Payment Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form action="<?php echo base_url('Purchase/save_purchase_payment');?>" method="post">
            <div class="col-md-12 col-sm-12 col-12">
              <div class="form-group">
                <label>Due Amount</label>
                <input type="text" class="form-control" name="tAmount" id="tAmount" readonly >
              </div>
              <div class="form-group">
                <label>Paid Amount *</label>
                <input type="text" class="form-control" name="pAmount" id="pAmount" placeholder="Amount" required >
              </div>
              <div class="form-group">
                <label>Notes</label>
                <input type="text" class="form-control" name="notes" placeholder="If Have any notes" >
              </div>
            </div>
            <input type="hidden" id="puid" name="puid" required >
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
          var puid = $(this).data('id');
            //alert(puid);
          $('input[name="puid"]').val(puid);
          });

        $('.payment').click(function(){
          var id = $(this).data('id');
          var url = '<?php echo base_url() ?>Purchase/get_purchase_payment';
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