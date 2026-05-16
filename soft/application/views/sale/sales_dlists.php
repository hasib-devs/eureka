<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>BRTA Registration</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">BRTA Registration</li>
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
                <h3 class="card-title">BRTA Registration List</h3>
              </div>

              <div class="card-body">
                <table id="example" class="table table-responsive table-bordered" >
                  <thead>
                    <tr>
                      <th style="width: 5%;">#SN.</th>
                      <th>Date</th>
                      <th>Customer</th>
                      <th>Phone</th>
                      <th>Bike Name</th>
                      <th>Chassis</th>
                      <th>Engine</th>
                      <th>Papers</th>
                      <th style="width: 15%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 0;
                    foreach ($sales as $value){
                    $i++;
                    
                    $pp = $this->db->select('
                                        sale_product.spChassis,
                                        sale_product.spEngine,
                                        products.pName')
                                    ->from('sale_product')
                                    ->join('products','products.pid = sale_product.pid','left')
                                    ->where('said',$value['said'])
                                    ->get()
                                    ->result();
                    ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo date('d-m-Y',strtotime($value['saDate'])); ?></td>
                      <td><?php echo $value['custName']; ?></td>
                      <td><?php echo $value['custMobile']; ?></td>
                      <td>
                        <?php 
                        foreach($pp as $p){ ?>
                        <?php echo $p->pName; ?><br>
                        <?php } ?>
                      </td>
                      <td>
                        <?php 
                        foreach($pp as $p){ ?>
                        <?php echo $p->spChassis; ?><br>
                        <?php } ?>
                      </td>
                      <td>
                        <?php 
                        foreach($pp as $p){ ?>
                        <?php echo $p->spEngine; ?><br>
                        <?php } ?>
                      </td>
                      <td>
                        <?php if($value['psType'] == 1){ ?>
                        <?php echo '<span style="color:green">Received</span>'; ?>
                        <?php }else{ ?>
                        <?php echo '<span style="color:red">Not Received</span>'; ?>
                        <?php } ?>
                      </td>
                      <td>
                        <a class="btn btn-info btn-sm" href="<?php echo base_url().'regForm/'.$value['said']; ?>" title="registration view" ><?php echo $value['invoice']; ?></a>
                        <a class="btn btn-info btn-sm" href="<?php echo site_url().'viewUSale/'.$value['said']; ?>" title="Sale view" ><i class="fa fa-eye"></i></a>
                        <a class="btn btn-success btn-sm" href="<?php echo site_url().'editUSale/'.$value['said']; ?>" title="Edit Sale" ><i class="fa fa-edit"></i></a>
                        <!--<a class="btn btn-danger btn-sm" href="<?php echo site_url('Sale/delete_duplicate_sales').'/'.$value['said']; ?>" onclick="return confirm('Are you sure you want to delete this Sales ?');" ><i class="fa fa-trash"></i></a>-->
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

<?php $this->load->view('footer/footer'); ?>