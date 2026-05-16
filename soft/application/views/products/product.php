<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>


<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Product Information List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Product Information</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- Exception Message -->
    <?php
    $exception = $this->session->userdata('exception');
    if(isset($exception))
    {
    echo $exception;
    $this->session->unset_userdata('exception');
    } ?>

  <!-- Main Content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Product Information List</h3>
              <div class="float-right">
                <?php if ($_SESSION['newCategory'] == 1) { ?>
                  <a class="btn btn-info" href="<?php echo site_url().'Category'; ?>" style="margin-left: 10px;">
                    <i class="fa fa-plus" style="padding-right: 10px;"></i>Product Category
                  </a>
                <?php } if ($_SESSION['newProduct'] == 1) { ?>
                  <a class="btn btn-primary" href="<?php echo site_url().'newProduct'; ?>" style="margin-left: 10px;">
                    <i class="fa fa-plus" style="padding-right: 10px;"></i>New Product
                  </a>
                <?php } if ($_SESSION['storeProduct'] == 1) { ?>
                  <button type="button" class="btn btn-danger storeProduct" data-toggle="modal" data-target=".bs-example-modal-product_store" onclick="document.getElementById('storeProduct').style.display='block'" style="margin-left: 10px;">
                    <i class="fa fa-plus"></i>&nbsp;Store Product
                  </button>
                <?php } ?>
                <button type="button" class="btn btn-warning template" data-toggle="modal" data-target=".bs-example-modal-template" style="float: right; margin-left: 10px;" ><i class="far fa-file-excel"></i> Import / Export</button>
              </div>
            </div>

            <!-- Card Body -->
            <div class="card-body">
              <table id="example" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th style="width: 1%;">#SN</th>
                    <!--<th>Image</th>-->
                    <th>Product</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Category</th>
                    <th>Code</th>
                    <!--<th>Origin</th>-->
                    <!--<th>CC</th>-->
                    <!--<th>Colour</th>-->
                    <th>HS Code</th>
                    <th>Part No.</th>
                    <th>Stock</th>
                    <th style="width: 10%;">Action</th> 
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 0;
                  foreach ($product as $value) {
                    $i++;
                    
                    $stock = $this->db->select('*')
                                      ->from('stock')
                                      ->where('pid', $value['pid'])
                                      ->get()
                                      ->row();

                    $st = $stock ? $stock->tquantity : '0';
                    
                    $sp = $this->db->select("SUM(quantity) as tpq")
                                   ->from('store_product')
                                   ->where('pid', $value['pid'])
                                   ->get()
                                   ->row();
                                
                    $pp = $this->db->select("SUM(quantity) as tpq")
                                   ->from('purchase_product')
                                   ->where('pid', $value['pid'])
                                   ->get()
                                   ->row();

                    $spp = $this->db->select("SUM(quantity) as tsq")
                                    ->from('sale_product')
                                    ->where('pid', $value['pid'])
                                    ->get()
                                    ->row();
                  
                    $rp = $this->db->select("SUM(quantity) as trq")
                                   ->from('returns_product')
                                   ->where('pid', $value['pid'])
                                   ->get()
                                   ->row();
                  
                    $rpp = $this->db->select("SUM(quantity) as trq")
                                    ->from('preturns_product')
                                    ->where('pid', $value['pid'])
                                    ->get()
                                    ->row();
                        //var_dump($stock_info);
                  ?>
                  <tr>
                    <td><?php echo $i; ?></td>
                    <!--<td>-->
                    <!--  <?php if($value['image'] == null) { ?>-->
                    <!--  <i class="fa fa-shopping-cart fa-4x" aria-hidden="true"></i>-->
                    <!--  <?php } else { ?> -->
                    <!--  <img src="<?php echo base_url().'/upload/product/'.$value['image']; ?>" style="width: 50px; height: 50px;">-->
                    <!--  <?php } ?> -->
                    <!--</td>-->
                    <td><?php echo $value['pName']; ?></td>
                    <td><?php echo $value['brand']; ?></td>
                    <td><?php echo $value['model']; ?></td>
                    <td><?php echo $value['catName']; ?></td>
                    <td><?php echo $value['pCode']; ?></td>
                    <!--<td><?php echo $value['mkCountry']; ?></td>-->
                    <!--<td><?php echo $value['capacity']; ?></td>-->
                    <!--<td><?php echo $value['pColor']; ?></td>-->
                    <td><?php echo $value['hsn']; ?></td>
                    <td><?php echo $value['partNo']; ?></td>
                    <td><?php echo $st; ?></td>
                    <td>
                      <a class="btn btn-info btn-xs" href="<?php echo site_url().'viewItems/'.$value['pid']; ?>"><i class="fa fa-eye"></i></a>
                      <?php if ($_SESSION['editProduct'] == 1) { ?>
                        <a class="btn btn-success btn-xs" href="<?php echo site_url().'editItems/'.$value['pid']; ?>"><i class="fa fa-edit"></i></a>
                      <?php } if ($_SESSION['deleteProduct'] == 1) { ?>
                        <a class="btn btn-danger btn-xs" href="<?php echo site_url('Product/delete_products').'/'.$value['pid']; ?>" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash"></i></a>
                      <?php } ?>
                      <a class="btn btn-warning btn-xs" href="<?php echo base_url().'pBarcode/'.$value['pid']; ?>" title="barcode"><i class="fa fa-barcode"></i></a>
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
  
  
    <div class="modal fade bs-example-modal-product_add" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Product Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form method="POST" action="<?php echo base_url() ?>Product/save_product" enctype="multipart/form-data" >
            <div class="col-md-12 col-sm-12 col-12" style="overflow-y:scroll;overflow-x:hidden;height:50vh;">
              <div class="row">
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Product Name *</label>
                  <input type="text" class="form-control" name="pName" placeholder="Product Name" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Brand *</label>
                  <input type="text" class="form-control" name="brand" placeholder="Brand" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Model Number *</label>
                  <input type="text" class="form-control" name="model" placeholder="Model Number" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Product Category</label>
                  <select class="form-control" name="category" >
                    <option value="">Select One</option>
                    <?php foreach($category as $value){ ?>
                    <option value="<?php echo $value['catid']; ?>"><?php echo $value['catName']; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Product Code*</label>
                  <input type="text" class="form-control" name="pCode" placeholder="Product Code" required >
                </div>
                
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Origin</label>
                  <input type="text" class="form-control" name="mkName" placeholder="Origin" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>CC</label>
                  <input type="text" class="form-control" name="capacity" placeholder="Cubic Capacity"  >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Colour </label>
                  <input type="text" class="form-control" name="pColor" placeholder="Items Color"  >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Chassis No. </label>
                  <input type="text" class="form-control" name="pChassis" placeholder="Chassis Number" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Engine No. </label>
                  <input type="text" class="form-control" name="pEngine" placeholder="Engine Number" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Weight </label>
                  <input type="text" class="form-control" name="uWeight" placeholder="Weight"  >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Select Unit </label>
                  <select class="form-control" name="unit" >
                    <option value="">Select One</option>
                    <?php  foreach($unit as $value){ ?>
                    <option value="<?php echo $value['untid']; ?>"><?php echo $value['unitName']; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Select Supplier </label>
                  <select class="form-control" name="supplier" >
                    <option value="">Select One</option>
                    <?php  foreach($supplier as $value){ ?>
                    <option value="<?php echo $value['supid']; ?>"><?php echo $value['supName']; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Purchase Price </label>
                  <input type="text" class="form-control" name="pprice" placeholder="Amount"  >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Sale Price </label>
                  <input type="text" class="form-control" name="sprice" placeholder="Amount"  >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Items Warranty </label>
                  <input type="text" class="form-control" name="warranty" placeholder="Items warranty"  >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Year of Manufacture </label>
                  <input type="text" class="form-control" name="manufacture" placeholder="Year of Manufacture"  >
                </div>
                <!--<div class="form-group col-md-6 col-sm-6 col-12">-->
                <!--  <label>Horse Power </label>-->
                <!--  <input type="text" class="form-control" name="power" placeholder="Horse Power"  >-->
                <!--</div>-->
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>RPM </label>
                  <input type="text" class="form-control" name="rpm" placeholder="RPM"  >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Wheel Base </label>
                  <input type="text" class="form-control" name="wBase" placeholder="Wheel Base" >
                </div>

                <!--<div class="form-group col-md-6 col-sm-6 col-12">-->
                <!--  <label>Maximum Laden Weight </label>-->
                <!--  <input type="text" class="form-control" name="lWeight" placeholder="Maximum Laden Weight" >-->
                <!--</div>-->
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Size of Tire </label>
                  <input type="text" class="form-control" name="sTire" placeholder="Size of Tire" >
                </div>
                <!--<div class="form-group col-md-6 col-sm-6 col-12">-->
                <!--  <label>Bike Label </label>-->
                <!--  <input type="text" class="form-control" name="bLabel" placeholder="Bike Label"  >-->
                <!--</div>-->
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Number of Cylinders </label>
                  <input type="text" class="form-control" name="nCylinder" placeholder="Number of Cylinders"  >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Fuel Used </label>
                  <input type="text" class="form-control" name="fUsed" placeholder="Fuel Used"  >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Fuel Tank Capacity </label>
                  <input type="text" class="form-control" name="tCapacity" placeholder="Fuel Tank Capacity"  >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Seats </label>
                  <input type="text" class="form-control" name="seats" value="2" placeholder="Seats" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Pre. Regn. No. (If)</label>
                  <input type="text" class="form-control" name="prNumber" placeholder="Pre. Regn. No. (If)" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Maker's Country</label>
                  <input type="text" class="form-control" name="mkCountry" placeholder="Maker's Country" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Products Image <br><small style="color: red; font-size:10px">( Maximum image size 500kb and png, jpg format )</small></label>
                  <input type="file" name="userfile" >
                </div>
              </div>
            </div>
            <div class="modal-footer form-group">
              <button type="submit" class="btn btn-primary"><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div id="myModal" class="modal fade bs-example-modal-product_store" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Store Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form action="<?php echo base_url() ?>Product/save_product_store" method="POST" >
            <div class="col-md-12 col-sm-12 col-12">
              <div class="form-group">
                <label>Select Items</label>
                <div style="width: 100%;" >
                <select class="form-control" id="select2" name="product" required style="width: 100%;" >
                  <option value="">Select One</option>
                  <?php foreach($product as $value) { ?>
                  <option value="<?php echo $value['pid']; ?>"><?php echo $value['pName'].' ( '.$value['partNo'].' )'; ?></option>
                  <?php } ?>
                </select>
                </div>
              </div>
              <div class="form-group">
                <label>Product Quantity</label>
                <input type="text" class="form-control" name="quantity" placeholder="Product Quantity" required >
              </div>
            </div>
            <div class="modal-footer form-group">
              <button type="submit" class="btn btn-primary"><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    
    <div class="modal fade bs-example-modal-template" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content" >
          <div class="modal-header">
            <h4 class="modal-title">Product Template</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="row">
            <div class="form-group col-md-6 col-sm-6 col-12">
              <div style="width: 100%; height: 80px; background: #fff4f4; text-align: center;">
                <a href="<?php echo base_url('assets/templates/products.xlsx') ?>" style="padding:1em;text-align: center;display:inline-block;text-decoration: none !important;margin:0 auto;">Demo Template</a>
              </div>
            </div>
            <div class="form-group col-md-6 col-sm-6 col-12">
              <div style="width: 100%; height: 80px; background: #fff4f4; text-align: center;">
                <a href="<?php echo base_url('Product/product_export') ?>" style="padding:1em;text-align: center;display:inline-block;text-decoration: none !important;margin:0 auto;">All Products</a>
              </div>
            </div>
          </div>
          <div class="col-md-12 col-sm-12 col-12">
            <form method="post" action="<?php echo base_url() ?>Product/excel_import" enctype="multipart/form-data">
              <div class="form-group col-md-12 col-sm-12 col-12">
                <label>Import Template<span style="color: red">*</span></label>
                <input type="file" name="file" id="file" required accept=".xls, .xlsx" >
              </div>
              <div class="form-group col-md-12 col-sm-12 col-xs-12" style="margin-top: 25px; text-align: center;">
                <button type="submit" class="btn btn-info"><i class="far fa-save"></i>&nbsp;&nbsp;Import</button>
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
      $(document).ready(function() {
        $("#select2").select2({
          dropdownParent: $("#myModal")
          });
        });
    </script>
