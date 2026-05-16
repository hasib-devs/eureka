<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Quotation</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Quotation</li>
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
                <h3 class="card-title">Quotation List</h3>
                <?php if($_SESSION['newQuotations'] == 1){ ?>
                <a href="<?php echo site_url('newQuotation'); ?>" class="btn btn-primary" style="float: right;" ><i class="fa fa-plus"></i>&nbsp;New Quotation</a>
                <?php } ?>
              </div>

              <div class="card-body">
                <table id="example" class="table table-responsive table-bordered" >
                  <thead>
                    <tr>
                      <th style="width: 5%;">#SN.</th>
                      <th>Q No.</th>
                      <th>Date</th>
                      <th>Customer</th>
                      <th>Product</th>
                      <th>Quantity</th>
                      <th>Total</th>
                      <th style="width: 10%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 0;
                    foreach ($quotation as $value) {
                    $id = $value['qutid'];
                    $i++;

                    $rp = $this->db->select('quotation_product.quantity,products.pName,products.pCode')
                                    ->from('quotation_product')
                                    ->join('products','products.pid = quotation_product.pid','left')
                                    ->where('qutid',$value['qutid'])
                                    ->get()
                                    ->result();
                    ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $value['qinvoice']; ?></td>
                      <td><?php echo date('d-M-Y', strtotime($value['qutDate'])) ?></td>
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
                      <td>
                        <a class="btn btn-info btn-xs" href="<?php echo site_url().'viewQuotation/'.$id; ?>"><i class="fa fa-eye"></i></a>
                        <?php if($_SESSION['editQuotations'] == 1){ ?>
                        <a class="btn btn-success btn-xs" href="<?php echo site_url().'editQuotation/'.$id; ?>"><i class="fa fa-edit"></i></a>
                        <?php } if($_SESSION['deleteQuotations'] == 1){ ?>
                        <a class="btn btn-danger btn-xs" href="<?php echo site_url('Quotation/delete_quotation').'/'.$id; ?>" onclick="return confirm('Are you sure you want to delete this Quotation ?');" ><i class="fa fa-trash"></i></a>
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
    
<?php $this->load->view('footer/footer'); ?>