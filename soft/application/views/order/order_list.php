<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Pre-Order</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Pre-Order</li>
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
                <h3 class="card-title">Pre-Order List</h3>
                <?php if($_SESSION['newPreorder'] == 1){ ?>
                <a href="<?php echo site_url('newOrder'); ?>" class="btn btn-primary" style="float: right;" ><i class="fa fa-plus"></i>&nbsp;Add Pre-Order</a>
                <?php } ?>
              </div>

              <div class="card-body">
                <table id="example" class="table table-responsive table-bordered" >
                  <thead>
                    <tr>
                      <th style="width: 5%;">#SN.</th>
                      <th>Order No.</th>
                      <th>Date</th>
                      <th>Customer</th>
                      <th>Price</th>
                      <th>Paid</th>
                      <th>Due</th>
                      <th>Status</th>
                      <th style="width: 10%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 0;
                    foreach ($order as $value) {
                    $id = $value['oid'];
                    $i++;
                    ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $value['oCode']; ?></td>
                      <td><?php echo date('d-M-Y', strtotime($value['oDate'])) ?></td>
                      <td><?php echo $value['custName']; ?><br><?php echo $value['custMobile']; ?></td>
                      <td><?php echo number_format($value['tAmount'], 2) ?></td>
                      <td><?php echo number_format($value['pAmount'], 2) ?></td>
                      <td><?php echo number_format($value['dAmount'], 2) ?></td>
                      <td>
                        <?php if($value['status'] == 1){ ?>
                        <?php echo 'On Process'; ?>
                        <?php } else if($value['status'] == 2){ ?>
                        <span style="color: green;"><?php echo 'Sales Order'; ?></span>
                        <?php } else if($value['status'] == 5){ ?>
                        <span style="color: red;"><?php echo 'Canceled'; ?></span>
                        <?php } else{ ?>
                        <?php echo 'N/A'; ?>
                        <?php } ?>
                      </td>
                      <td>
                        <a class="btn btn-info btn-sm" href="<?php echo site_url().'viewOrder/'.$id; ?>"><i class="fa fa-eye"></i></a>
                        <?php if($value['status'] == 1){ ?>
                        <?php if($_SESSION['editPreorder'] == 1){ ?>
                        <a class="btn btn-success btn-sm" href="<?php echo site_url().'editOrder/'.$id; ?>"><i class="fa fa-edit"></i></a>
                        <a class="btn btn-primary btn-sm" href="<?php echo site_url().'saleOrder/'.$id; ?>"><i class="fa fa-plus-circle"></i></a>
                        <?php } if($_SESSION['deletePreorder'] == 1){ ?>
                        <a class="btn btn-danger btn-sm" href="<?php echo site_url('Order/delete_Order').'/'.$id; ?>" onclick="return confirm('Are you sure you want to delete this Order ?');"><i class="fa fa-trash"></i></a>
                        <a class="btn btn-warning btn-sm" href="<?php echo site_url('Order/cancel_Order').'/'.$id; ?>" onclick="return confirm('Are you sure you want to cancel this Order ?');"><i class="fa fa-ban"></i></a>
                        <?php } } ?>
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