<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Return</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Return</li>
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
                <h3 class="card-title">Return List</h3>
                <?php if($_SESSION['newSReturns'] == 1){ ?>
                <a href="<?php echo site_url('newReturn') ?>" class="btn btn-primary" style="float: right;" ><i class="fa fa-plus"></i> New Return</a>
                <?php } ?>
              </div>

              <div class="card-body">
                <table id="example" class="table table-striped table-bordered" >
                  <thead>
                    <tr>
                      <th style="width: 5%;">#SN.</th>
                      <th>Date</th>
                      <th>R-Inv. No.</th>
                      <th>Customer</th>
                      <th>Product</th>
                      <th>Quantity</th>
                      <th>Total</th>
                      <th>Charge</th>
                      <th>Paid</th>
                      <th style="width: 10%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 0;
                    foreach ($return as $value) {
                    $i++;
                    
                    $rp = $this->db->select('returns_product.quantity,products.pName,products.pCode')
                                    ->from('returns_product')
                                    ->join('products','products.pid = returns_product.pid','left')
                                    ->where('rid',$value['rid'])
                                    ->get()
                                    ->result();
                    ?>
                    <tr class="gradeX" style="border: 1px solid #000;">
                      <td><?php echo $i; ?></td>
                      <td><?php echo date('d-M-Y',strtotime($value['rDate'])); ?></td>
                      <td><?php echo $value['rCode']; ?></td>
                      <td><?php echo $value['custName']; ?></td>
                      <td>
                        <?php foreach ($rp as $p) { ?>
                        <?php echo $p->pName.' ( '.$p->pCode.' )'; ?><br>
                        <?php } ?>
                      </td>
                      <td>
                        <?php foreach ($rp as $p) { ?>
                        <?php echo $p->quantity; ?><br>
                        <?php } ?>
                      </td>
                      <td><?php echo number_format($value['tAmount'], 2); ?></td>
                      <td><?php echo number_format($value['sAmount'], 2); ?></td>
                      <td><?php echo number_format($value['pAmount'], 2); ?></td>
                      <td>
                        <a class="btn btn-info btn-xs" href="<?php echo site_url().'viewReturn/'.$value['rid'] ?>"><i class="fa fa-eye"></i></a>
                        <?php if($_SESSION['deleteSReturns'] == 1){ ?>
                        <a class="btn btn-danger btn-xs" href="<?php echo site_url('Returns/delete_returns').'/'.$value['rid'] ?>" onclick="return confirm('Are you sure you want to delete this Returns ?');" ><i class="fa fa-trash"></i></a>
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

<?php $this->load->view('footer/footer'); ?>