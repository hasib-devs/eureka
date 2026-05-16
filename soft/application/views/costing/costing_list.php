<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Costing</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Costing</li>
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
                <h3 class="card-title">Costing List</h3>
                <?php if($_SESSION['newPurchase'] == 1){ ?>
                <a href="<?php echo site_url(); ?>newCosting" class="btn btn-primary" style="float: right;" ><i class="fa fa-plus"></i>&nbsp;New Costing</a>
                <?php } ?>
              </div>

              <div class="card-body">
                <table id="example" class="table table-striped table-bordered" >
                  <thead>
                    <tr>
                      <th style="width: 5%;">#SN.</th>
                      <th>Product</th>
                      <th>Category</th>
                      <th>Part No.</th>
                      <th>Stock</th>
                      <th>Price INR</th>
                      <th>Discount</th>
                      <th>Convert Amount</th>
                      <th>Purchase Price</th>
                      <th>Sale Price</th>
                      <th style="width: 10%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 0;
                    foreach ($purchase as $value){
                    $i++;
                    ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $value['pName']; ?></td>
                      <td><?php echo $value['catName']; ?></td>
                      <td><?php echo $value['partNo']; ?></td>
                      <td><?php echo $value['stock']; ?></td>
                      <td><?php echo number_format($value['pprice'], 2); ?></td>
                      <td><?php echo number_format($value['pdiscount'], 2); ?></td>
                      <td><?php echo number_format($value['camount'], 2); ?></td>
                      <td><?php echo number_format($value['tamount'], 2); ?></td>
                      <td><?php echo number_format($value['sprice'], 2); ?></td>
                      <td>
                        <a class="btn btn-info btn-xs" href="<?php echo site_url().'vieCosting/'.$value['cstid']; ?>"><i class="fa fa-eye"></i></a>
                        <a class="btn btn-success btn-xs" href="<?php echo site_url().'editCosting/'.$value['cstid']; ?>"><i class="fa fa-edit"></i></a>
                        <a class="btn btn-danger btn-xs" href="<?php echo site_url('Costing/delete_costing').'/'.$value['cstid']; ?>" onclick="return confirm('Are you sure you want to delete this Costing ?');" ><i class="fa fa-trash"></i></a>
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

<?php $this->load->view('footer/footer'); ?>
