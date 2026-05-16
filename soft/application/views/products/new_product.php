<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>New Products Information</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Products</li>
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
                <h3 class="card-title">Product Information</h3>
              </div>

              <div class="card-body">
                <form method="POST" action="<?php echo base_url() ?>Product/save_product" enctype="multipart/form-data" >
                <div class="col-md-12 col-sm-12 col-12">
                  <div class="row">
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Product Category</label>
                      <select class="form-control select2" name="category" id="category" required >
                        <option value="">Select One</option>
                        <?php foreach($category as $value){ ?>
                        <option value="<?php echo $value['catid']; ?>"><?php echo $value['catName']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="d-none form-group col-md-4 col-sm-4 col-12" id="prNumber">
                      <label>Pre. Regn. No. (If)</label>
                      <input type="text" class="form-control" name="prNumber" placeholder="Pre. Regn. No. (If)" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Product Name *</label>
                      <input type="text" class="form-control" name="pName" placeholder="Product Name" required >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Brand *</label>
                      <input type="text" class="form-control" name="brand" placeholder="Brand" >
                    </div>
                    <div class="d-none form-group col-md-4 col-sm-6 col-12" id="model">
                      <label>Model Number *</label>
                      <input type="text" class="form-control" name="model" placeholder="Model Number" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label id="pCode">Part No. *</label>
                      <input type="text" class="form-control" name="pCode" id="pCode" placeholder="Product Code" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label id="partNo">Part No. *</label>
                      <input type="text" class="form-control" name="partNo" id="partNo" placeholder="part No" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Origin</label>
                      <input type="text" class="form-control" name="mkName" placeholder="Origin" >
                    </div>
                    <div class="d-none form-group col-md-4 col-sm-4 col-12" id="cc">
                      <label>CC</label>
                      <input type="text" class="form-control" name="capacity" placeholder="Cubic Capacity"  >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Colour </label>
                      <input type="text" class="form-control" name="pColor" placeholder="Items Color"  >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Weight </label>
                      <input type="text" class="form-control" name="uWeight" placeholder="Weight"  >
                    </div>
                    <div class="d-none form-group col-md-4 col-sm-4 col-12" id="pChassis">
                      <label>Chassis No. </label>
                      <input type="text" class="form-control" name="pChassis" placeholder="Chassis Number" >
                    </div>
                    <div class="d-none form-group col-md-4 col-sm-4 col-12" id="pEngine">
                      <label>Engine No. </label>
                      <input type="text" class="form-control" name="pEngine" placeholder="Engine Number" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Select Unit </label>
                      <select class="form-control" name="unit" >
                        <option value="">Select One</option>
                        <?php  foreach($unit as $value){ ?>
                        <option value="<?php echo $value['untid']; ?>"><?php echo $value['unitName']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <!--<div class="form-group col-md-6 col-sm-6 col-12">-->
                    <!--  <label>Select Supplier </label>-->
                    <!--  <select class="form-control" name="supplier" >-->
                    <!--    <option value="">Select One</option>-->
                    <!--    <?php  foreach($supplier as $value){ ?>-->
                    <!--    <option value="<?php echo $value['supid']; ?>"><?php echo $value['supName']; ?></option>-->
                    <!--    <?php } ?>-->
                    <!--  </select>-->
                    <!--</div>-->
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Purchase Price </label>
                      <input type="text" class="form-control" name="pprice" placeholder="Amount"  >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Sale Price </label>
                      <input type="text" class="form-control" name="sprice" placeholder="Amount"  >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Item's Warranty </label>
                      <input type="text" class="form-control" name="warranty" placeholder="Items warranty"  >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Year of Manufacture </label>
                      <input type="text" class="form-control" name="manufacture" placeholder="Year of Manufacture"  >
                    </div>
                    <!--<div class="form-group col-md-6 col-sm-6 col-12">-->
                    <!--  <label>Horse Power </label>-->
                    <!--  <input type="text" class="form-control" name="power" placeholder="Horse Power"  >-->
                    <!--</div>-->
                    
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Size </label>
                      <input type="text" class="form-control" name="rpm" placeholder="Size"  >
                    </div>
                    
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>HS Code </label>
                      <input type="text" class="form-control" name="hsn" placeholder="HS Code"  >
                    </div>
                    
                    <div class="d-none form-group col-md-4 col-sm-4 col-12" id="wBase">
                      <label>Wheel Base </label>
                      <input type="text" class="form-control" name="wBase" placeholder="Wheel Base" >
                    </div>
    
                    <!--<div class="form-group col-md-6 col-sm-6 col-12">-->
                    <!--  <label>Maximum Laden Weight </label>-->
                    <!--  <input type="text" class="form-control" name="lWeight" placeholder="Maximum Laden Weight" >-->
                    <!--</div>-->
                    <div class="d-none form-group col-md-4 col-sm-4 col-12" id="sTire">
                      <label>Size of Tire </label>
                      <input type="text" class="form-control" name="sTire" placeholder="Size of Tire" >
                    </div>
                    <!--<div class="form-group col-md-6 col-sm-6 col-12">-->
                    <!--  <label>Bike Label </label>-->
                    <!--  <input type="text" class="form-control" name="bLabel" placeholder="Bike Label"  >-->
                    <!--</div>-->
                    <div class="d-none form-group col-md-4 col-sm-4 col-12" id="nCylinder">
                      <label>Number of Cylinders </label>
                      <input type="text" class="form-control" name="nCylinder" placeholder="Number of Cylinders"  >
                    </div>
                    <div class="d-none form-group col-md-4 col-sm-4 col-12" id="fUsed">
                      <label>Fuel Used </label>
                      <input type="text" class="form-control" name="fUsed" placeholder="Fuel Used"  >
                    </div>
                    <div class="d-none form-group col-md-4 col-sm-4 col-12" id="tCapacity">
                      <label>Fuel Tank Capacity </label>
                      <input type="text" class="form-control" name="tCapacity" placeholder="Fuel Tank Capacity"  >
                    </div>
                    <div class="d-none form-group col-md-4 col-sm-4 col-12" id="seats">
                      <label>Seats </label>
                      <input type="text" class="form-control" name="seats" value="2" placeholder="Seats" >
                    </div>
                    <div class="d-none form-group col-md-4 col-sm-4 col-12" id="mkCountry">
                      <label>Maker's Country</label>
                      <input type="text" class="form-control" name="mkCountry" placeholder="Maker's Country" >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Products Image <br><small style="color: red; font-size:10px">( Maximum image size 500kb and png, jpg format )</small></label>
                      <input type="file" name="userfile" >
                    </div>
                  </div>
                </div>
                <div class="col-md-12 col-sm-12 col-12 form-group" style="text-align:center;">
                  <button type="submit" class="btn btn-primary"><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
                  <a href="<?php echo site_url('Product') ?>" class="btn btn-danger" ><i class="fa fa-arrow-left" ></i>&nbsp;&nbsp;Back</a>
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

      <script type="text/javascript">
        $(document).ready(function(){
          $('#category').change(function(){
            var catid = $('#category').val();
              //alert(catid);
            if(catid <= 8)
              {
              $('#pChassis').removeAttr('class','d-none');
              $('#pChassis').attr('class','form-group col-md-6 col-sm-6 col-12');
              $('#pEngine').removeAttr('class','d-none');
              $('#pEngine').attr('class','form-group col-md-6 col-sm-6 col-12');
              $('#cc').removeAttr('class','d-none');
              $('#cc').attr('class','form-group col-md-6 col-sm-6 col-12');
              $('#wBase').removeAttr('class','d-none');
              $('#wBase').attr('class','form-group col-md-6 col-sm-6 col-12');
              $('#nCylinder').removeAttr('class','d-none');
              $('#nCylinder').attr('class','form-group col-md-6 col-sm-6 col-12');
              $('#sTire').removeAttr('class','d-none');
              $('#sTire').attr('class','form-group col-md-6 col-sm-6 col-12');
              $('#seats').removeAttr('class','d-none');
              $('#seats').attr('class','form-group col-md-6 col-sm-6 col-12');
              $('#mkCountry').removeAttr('class','d-none');
              $('#mkCountry').attr('class','form-group col-md-6 col-sm-6 col-12');
              $('#prNumber').removeAttr('class','d-none');
              $('#prNumber').attr('class','form-group col-md-6 col-sm-6 col-12');
              $('#tCapacity').removeAttr('class','d-none');
              $('#tCapacity').attr('class','form-group col-md-6 col-sm-6 col-12');
              $('#fUsed').removeAttr('class','d-none');
              $('#fUsed').attr('class','form-group col-md-6 col-sm-6 col-12');
              $('#model').removeAttr('class','d-none');
              $('#model').attr('class','form-group col-md-6 col-sm-6 col-12');
              document.getElementById('pCode').innerHTML= 'Product Code*';
              document.getElementById('partNo').innerHTML= 'Part No.*';
              }
            else
              {
              $('#pChassis').attr('class','d-none form-group col-md-6 col-sm-6 col-12');
              $('#pEngine').attr('class','d-none form-group col-md-6 col-sm-6 col-12');
              $('#cc').attr('class','d-none form-group col-md-6 col-sm-6 col-12');
              $('#wBase').attr('class','d-none form-group col-md-6 col-sm-6 col-12');
              $('#nCylinder').attr('class','d-none form-group col-md-6 col-sm-6 col-12');
              $('#sTire').attr('class','d-none form-group col-md-6 col-sm-6 col-12');
              $('#seats').attr('class','d-none form-group col-md-6 col-sm-6 col-12');
              $('#mkCountry').attr('class','d-none form-group col-md-6 col-sm-6 col-12');
              $('#prNumber').attr('class','d-none form-group col-md-6 col-sm-6 col-12');
              $('#tCapacity').attr('class','d-none form-group col-md-6 col-sm-6 col-12');
              $('#fUsed').attr('class','d-none form-group col-md-6 col-sm-6 col-12');
              $('#model').attr('class','d-none form-group col-md-6 col-sm-6 col-12');
              document.getElementById('pCode').innerHTML= 'Part No.*';
              }
            });
          });
      </script>

    <script type="text/javascript">
      $(function(){
        $(".select2").select2();
      });
    </script>
    