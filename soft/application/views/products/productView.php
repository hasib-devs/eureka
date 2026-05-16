<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Items</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Items</li>
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
                <h3 class="card-title">Items Information</h3>
              </div>

              <div class="card-body">
                <div class="row">
                  <div class="col-md-4 col-sm-4 col-12">
                    <div class="col-md-12 col-sm-12 col-12" >
                      <?php if($product['image'] == null){ ?>
                      <i class="fa fa-shopping-cart fa-5x" ></i>
                      <?php } else{ ?>
                      <img src="<?php echo base_url().'upload/product/'.$product['image'];?>" style="width: 90%;height: 170px;" alt="Product Image" >
                      <?php } ?>
                    </div>    
                  </div>
                  <div class="col-md-8 col-sm-8 col-12">
                    <table class="table table-bordered table-striped">
                      <tr>
                        <td>Model No.</td>
                        <td><?php if(isset($product['pCode'])){echo $product['pCode'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Model Name</td>
                        <td><?php if(isset($product['pName'])){echo $product['pName'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Chassis No.</td>
                        <td><?php if(isset($product['pChassis'])){echo $product['pChassis'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Chassis No.</td>
                        <td><?php if(isset($product['pChassis'])){echo $product['pChassis'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Engine No.</td>
                        <td><?php if(isset($product['pEngine'])){echo $product['pEngine'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Category Name</td>
                        <td><?php if(isset($product['catName'])){echo $product['catName'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Unit Name</td>
                        <td><?php if(isset($product['unitName'])){echo $product['unitName'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Part No</td>
                        <td><?php if(isset($product['partNo'])){echo $product['partNo'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Supplier</td>
                        <td><?php if(isset($product['supName'])){echo $product['supName'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Purchase Price</td>
                        <td><?php if(isset($product['pprice'])){echo number_format($product['pprice'], 2);}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Sale Price</td>
                        <td><?php if(isset($product['sprice'])){echo number_format($product['sprice'], 2);}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Items Color</td>
                        <td><?php if(isset($product['pColor'])){echo $product['pColor'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Warranty</td>
                        <td><?php if(isset($product['warranty'])){echo $product['warranty'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Year of Manufacture</td>
                        <td><?php if(isset($product['manufacture'])){echo $product['manufacture'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Horse Power</td>
                        <td><?php if(isset($product['power'])){echo $product['power'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Size</td>
                        <td><?php if(isset($product['rpm'])){echo $product['rpm'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Cubic Capacity</td>
                        <td><?php if(isset($product['capacity'])){echo $product['capacity'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Wheel Base</td>
                        <td><?php if(isset($product['wBase'])){echo $product['wBase'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Unladen Weight</td>
                        <td><?php if(isset($product['uWeight'])){echo $product['uWeight'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Maximum Laden Weight</td>
                        <td><?php if(isset($product['lWeight'])){echo $product['lWeight'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Size of Tire</td>
                        <td><?php if(isset($product['sTire'])){echo $product['sTire'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Bike Label</td>
                        <td><?php if(isset($product['bLabel'])){echo $product['bLabel'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Number of Cylinders</td>
                        <td><?php if(isset($product['nCylinder'])){echo $product['nCylinder'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Fuel Used</td>
                        <td><?php if(isset($product['fUsed'])){echo $product['fUsed'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Fuel Tank Capacity</td>
                        <td><?php if(isset($product['tCapacity'])){echo $product['tCapacity'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Seats</td>
                        <td><?php if(isset($product['seats'])){echo $product['seats'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Pre. Regn. No.</td>
                        <td><?php if(isset($product['prNumber'])){echo $product['prNumber'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Maker's Name</td>
                        <td><?php if(isset($product['mkName'])){echo $product['mkName'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Maker's Country</td>
                        <td><?php if(isset($product['mkCountry'])){echo $product['mkCountry'];}else{echo '';}?></td>
                      </tr>
                      <tr>
                        <td>Stock</td>
                        <td><?php 
                        if(isset($product['pid'])){
                          $stock = $this->pm->get_stock_data($product['pid']);
                          if(isset($stock->tquantity)){
                            echo $stock->tquantity;
                            }else{
                              echo 'Stock out';
                            }    
                          }else{
                            echo '';
                          }
                        ?></td>
                      </tr>
                    </table>
                  </div>
                </div>
                <div class="col-sm-12 col-md-12 col-12" style="text-align: center;">
                  <a href="<?php echo site_url(); ?>items" class="btn btn-danger" ><i class="fa fa-arrow-left"></i> Back</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<?php $this->load->view('footer/footer'); ?>