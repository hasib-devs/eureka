<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Product</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Product</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Update Product Information</h3>
              </div>

              <div class="card-body">
                <form method="POST" action="<?php echo base_url() ?>Product/update_product" enctype="multipart/form-data" >
                  <div class="row">
                    <input type="hidden" class="form-control" name="pid" value="<?php echo $product['pid']; ?>" required >
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Product Name *</label>
                      <input type="text" class="form-control" name="pName" value="<?php echo $product['pName']; ?>" required >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Brand *</label>
                      <input type="text" class="form-control" name="brand" value="<?php echo $product['brand']; ?>" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Model Number *</label>
                      <input type="text" class="form-control" name="model" value="<?php echo $product['model']; ?>" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Product Category </label>
                      <select class="form-control" name="category"  >
                        <option value="">Select One</option>
                        <?php foreach($category as $value){ ?>
                        <option <?php echo ($product['catid'] == $value['catid'])?'selected':''?> value="<?php echo $value['catid']; ?>"><?php echo $value['catName']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Product Code *</label>
                      <input type="text" class="form-control" name="pCode" value="<?php echo $product['pCode']; ?>" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Part No *</label>
                      <input type="text" class="form-control" name="partNo" value="<?php echo $product['partNo']; ?>" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Origin</label>
                      <input type="text" class="form-control" name="mkCountry" value="<?php echo $product['mkCountry']; ?>" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>CC</label>
                      <input type="text" class="form-control" name="capacity" value="<?php echo $product['capacity']; ?>" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Colour </label>
                      <input type="text" class="form-control" name="pColor" value="<?php echo $product['pColor']; ?>"  >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Chassis No. *</label>
                      <input type="text" class="form-control" name="pChassis" value="<?php echo $product['pChassis']; ?>" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Engine No. *</label>
                      <input type="text" class="form-control" name="pEngine" value="<?php echo $product['pEngine']; ?>" >
                    </div>
                    
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Select Unit </label>
                      <select class="form-control" name="unit" >
                        <option value="">Select One</option>
                        <?php foreach($unit as $value){ ?>
                        <option <?php echo ($product['untid'] == $value['untid'])?'selected':''?> value="<?php echo $value['untid']; ?>"><?php echo $value['unitName']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <!--<div class="form-group col-md-4 col-sm-4 col-12">-->
                    <!--  <label>Select Supplier </label>-->
                    <!--  <select class="form-control" name="supplier" >-->
                    <!--    <option value="">Select One</option>-->
                    <!--    <?php foreach($supplier as $value){ ?>-->
                    <!--    <option <?php echo ($product['supid'] == $value['supid'])?'selected':''?> value="<?php echo $value['supid']; ?>"><?php echo $value['supName']; ?></option>-->
                    <!--    <?php } ?>-->
                    <!--  </select>-->
                    <!--</div>-->
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Purchase Price </label>
                      <input type="text" class="form-control" name="pprice" value="<?php echo $product['pprice']; ?>" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Sale Price </label>
                      <input type="text" class="form-control" name="sprice" value="<?php echo $product['sprice']; ?>"  >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Items Warranty </label>
                      <input type="text" class="form-control" name="warranty" value="<?php echo $product['warranty']; ?>" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Year of Manufacture </label>
                      <input type="text" class="form-control" name="manufacture" value="<?php echo $product['manufacture']; ?>" >
                    </div>
                    <!--<div class="form-group col-md-4 col-sm-4 col-12">-->
                    <!--  <label>Horse Power </label>-->
                    <!--  <input type="text" class="form-control" name="power" value="<?php echo $product['power']; ?>" >-->
                    <!--</div>-->
                    
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Size </label>
                      <input type="text" class="form-control" name="rpm" value="<?php echo $product['rpm']; ?>" >
                    </div>
                    
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>HS Code</label>
                      <input type="text" class="form-control" name="hsn" value="<?php echo $product['hsn']; ?>" >
                    </div>

                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Wheel Base </label>
                      <input type="text" class="form-control" name="wBase" value="<?php echo $product['wBase']; ?>" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Weight </label>
                      <input type="text" class="form-control" name="uWeight" value="<?php echo $product['uWeight']; ?>" >
                    </div>
                    <!--<div class="form-group col-md-4 col-sm-4 col-12">-->
                    <!--  <label>Maximum Laden Weight </label>-->
                    <!--  <input type="text" class="form-control" name="lWeight" value="<?php echo $product['lWeight']; ?>" >-->
                    <!--</div>-->
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Size of Tire </label>
                      <input type="text" class="form-control" name="sTire" value="<?php echo $product['sTire']; ?>" >
                    </div>
                    <!--<div class="form-group col-md-4 col-sm-4 col-12">-->
                    <!--  <label>Bike Label </label>-->
                    <!--  <input type="text" class="form-control" name="bLabel" value="<?php echo $product['bLabel']; ?>" >-->
                    <!--</div>-->
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Number of Cylinders</label>
                      <input type="text" class="form-control" name="nCylinder" value="<?php echo $product['nCylinder']; ?>" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Fuel Used</label>
                      <input type="text" class="form-control" name="fUsed" value="<?php echo $product['fUsed']; ?>" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Fuel Tank Capacity</label>
                      <input type="text" class="form-control" name="tCapacity" value="<?php echo $product['tCapacity']; ?>" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Seats</label>
                      <input type="text" class="form-control" name="seats" value="<?php echo $product['seats']; ?>" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Pre. Regn. No. (If)</label>
                      <input type="text" class="form-control" name="prNumber" value="<?php echo $product['prNumber']; ?>" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Maker's Name</label>
                      <input type="text" class="form-control" name="mkName" value="<?php echo $product['mkName']; ?>" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                      <label>Product Image <small style="color: red;">( Maximum image size 500kb and png, jpg format )</small></label><br>
                      <input type="file" name="userfile" >
                    </div>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-12" style="text-align: center; margin-top: 20px;">
                    <div class="col-md-9 col-md-offset-4">  
                      <button type="submit" class="btn btn-info"><i class="far fa-save"></i> Update</button>
                      <a href="<?php echo site_url(); ?>items" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<?php $this->load->view('footer/footer'); ?>