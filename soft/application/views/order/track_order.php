<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar2'); ?>

  <div class="content-wrapper">
    <div class="content">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title m-0"><b>Order Product Track</b></h5>
              </div>
              <div class="card-body">
                <div class="col-sm-12 col-md-12 col-12">
                  <form action="<?php echo base_url() ?>trackOrder" method="get">
                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="row">
                          <div class="form-group col-md-3 col-sm-3 col-12">
                          </div>
                          <div class="form-group col-md-4 col-sm-4 col-12">
                            <label>Order ID / Code *</label>
                            <input type="text" class="form-control" name="oid" required placeholder="Order ID / Code" >
                          </div>
                          <div class="form-group col-md-2 col-sm-2 col-12">
                            <button type="submit" name="search" class="btn btn-info" style="margin-top: 30px;"><i class="fa fa-search-plus" ></i>&nbsp;Search</button>
                          </div>
                        </div>
                    </div>
                  </form>
                </div><hr>
                
                <div class="row">
                  <?php if(isset($_GET['search']) && $order){ ?>
                  <div class="col-lg-12 col-md-12 col-12 mb-60">
                    <div class="row">
                      <div class="col-sm-6 col-md-6 col-12">
                        <div class="col-sm-12 col-md-12 col-12">
                          <div class="form-group">
                            Name&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $order->customerName; ?>
                          </div>
                          <div class="form-group">
                            Address&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $order->address; ?>
                          </div>
                          <div class="form-group">
                            Contact No.&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $order->mobile; ?>
                          </div>
                          <div class="form-group">
                            Email&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $order->email; ?>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6 col-md-6 col-12">
                        <div class="col-sm-12 col-md-12 col-12">
                          <div class="form-group">
                            Order ID&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $order->oCode; ?>
                          </div>
                          <div class="form-group">
                            Order Date&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo date('d-m-Y', strtotime($order->oDate)); ?>
                          </div>
                          <div class="form-group">
                            Order Status&nbsp;&nbsp;:&nbsp;&nbsp;<?php if($order->status == 1){ ?><?php echo 'On Process'; ?><?php } else if($order->status == 2){ ?><?php echo 'Sales Order'; ?><?php } else if($order->status == 5){ ?><?php echo 'Canceled'; ?><?php } else{ ?><?php echo 'N/A'; ?><?php } ?>
                          </div>
                          <div class="form-group">
                            Order Delivery&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $order->dOption; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php
                  $pp = $this->db->select('order_product.*,products.*')->from('order_product')->join('products','products.productID = order_product.product','left')->where('oid',$order->oid)->get()->result();
                  ?>
                  <div class="col-lg-12 col-md-12 col-12 mb-60">
                    <div class="row">
                      <div class="col-sm-12 col-md-12 col-12">
                        <table class="table table-responsive table-bordered" >
                          <thead>
                            <tr>
                              <th style="width: 5%;">#SN.</th>
                              <th>Product Name</th>
                              <th>Quantity</th>      
                              <th>Unit Price</th>
                              <th style="width: 15%;">Total Price</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $i = 0;
                            $ta = 0;
                            foreach($pp as $value){
                            $i++;
                            ?>
                            <tr>
                              <td><?php echo $i; ?></td>
                              <td><?php echo $value->productName; ?></td>
                              <td><?php echo $value->oQnt; ?></td>
                              <td><?php echo number_format($value->oPrice, 2); ?></td>
                              <td><?php echo number_format($value->tPrice, 2); $ta += $value->tPrice; ?></td>
                            </tr>
                            <?php } ?>
                          </tbody>
                          <tbody>
                            <tr>
                              <td colspan="4" align="right" >Net Amount</td>
                              <td><?php echo number_format($ta, 2); ?></td>
                            </tr>
                            <tr>
                              <td colspan="4" align="right" >Shipping Cost</td>
                              <td><?php echo number_format($order->scost, 2); ?></td>
                            </tr>
                            <tr>
                              <td colspan="4" align="right" >Total Amount</td>
                              <td><?php echo number_format(($ta+$order->scost), 2); ?></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                      <div class="col-md-12 col-sm-12 col-12">
                        <div class="form-group col-md-12 col-sm-12 col-12">
                          Note / Remarks
                        </div>
                        <?php if($order->note){ ?>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                          <?php echo $order->note; ?>
                        </div>
                        <?php } ?>
                      </div>
                  </div>
                  <?php } else if(isset($_GET['search']) && $morder){ ?>
                  <div class="col-lg-12 col-md-12 col-12 mb-60">
                    <div class="row">
                      <div class="col-sm-12 col-md-12 col-12">
                        <table class="table table-responsive table-bordered" >
                          <thead>
                            <tr>
                              <th style="width: 5%;">#SN.</th>
                              <th>Date</th>
                              <th>Order Code</th>
                              <th style="width: 15%;">Price</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $i = 0;
                            $ta = 0;
                            foreach($morder as $value){
                            $i++;
                            ?>
                            <tr>
                              <td><?php echo $i; ?></td>
                              <td><?php echo date('d-m-Y', strtotime($value->oDate)); ?></td>
                              <td><?php echo $value->oCode; ?></td>
                              <td><?php echo number_format($value->tAmount, 2); $ta += $value->tAmount; ?></td>
                            </tr>
                            <?php } ?>
                          </tbody>
                          <tbody>
                            <tr>
                              <td colspan="3" align="right" >Net Amount</td>
                              <td><?php echo number_format($ta, 2); ?></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php $this->load->view('footer/footer2'); ?>