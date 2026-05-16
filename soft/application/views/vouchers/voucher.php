<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Voucher</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Voucher</li>
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
                <h3 class="card-title">Voucher List</h3>
                <a href="<?php echo site_url('newVoucher') ?>" class="btn btn-primary" style="float: right" ><i class="fa fa-plus"></i> New Voucher</a>
              </div>

              <div class="card-body">
                <table id="example" class="table table-responsive table-bordered" >
                  <thead>
                    <tr>
                      <th style="width: 5%;">#SN.</th>
                      <th>Invoice</th>
                      <th>Date</th>
                      <th>Vaucher Type</th>
                      <th>Emplyoee</th>
                      <!-- <th>Customer</th>
                      <th>Supplier</th> -->
                      <th>Reference</th>
                      <th>Amount</th>
                      <th>Status</th>
                      <th style="width: 10%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 0;
                    foreach ($vaucher as $value) {
                    $i++;
                    $cid = $value['custid'];
                    $eid = $value['regby'];
                    $sid = $value['supid'];

                    $customer = $this->db->select('custCode,custName')
                                      ->from('customers')
                                      ->where('custid',$cid)
                                      ->get()
                                      ->row();

                    $employee = $this->db->select('name')
                                      ->from('users')
                                      ->where('uid',$eid)
                                      ->get()
                                      ->row();

                    $supplier = $this->db->select('supCode,supName')
                                      ->from('suppliers')
                                      ->where('supid',$sid)
                                      ->get()
                                      ->row();
                    ?>
                    <tr class="gradeX">
                      <td><?php echo $i; ?></td>
                      <td><?php echo $value['invoice']; ?></td>
                      <td><?php echo date('d-m-Y', strtotime($value['vuDate'])); ?></td>
                      <td><?php echo $value['vauchertype']; ?></td>
                      <td>
                          <?php if($employee){ ?>
                          <?php echo $employee->name; ?>
                          <?php } else{ ?>
                          <?php echo 'N/A'; ?>
                          <?php } ?>
                      </td>
                      <!-- <td>
                          <?php if($customer){ ?>
                          <?php echo $customer->custName; ?>
                          <?php } else{ ?>
                          <?php echo 'N/A'; ?>
                          <?php } ?>
                      </td>
                      <td>
                          <?php if($supplier){ ?>
                          <?php echo $supplier->supName; ?>
                          <?php } else{ ?>
                          <?php echo 'N/A'; ?>
                          <?php } ?>
                      </td> -->
                      <td><?php echo $value['notes']; ?></td>
                      <td><?php echo number_format($value['tAmount'], 2); ?></td>
                      <td><?php if($value['status'] == 1){ ?><span style="color: green;"><?php echo "Approve"; ?></span><?php } else{ ?><span style="color: red;"><?php echo "Pending";?></span><?php } ?></td>
                      <td>
                        <a class="btn btn-info btn-xs" href="<?php echo site_url().'viewVoucher/'.$value['vuid']; ?>"><i class="fa fa-eye"></i></a>
                        <?php if($value['status'] == 0){ ?>
                        <a class="btn btn-success btn-xs" href="<?php echo site_url().'editVoucher/'.$value['vuid']; ?>"><i class="fa fa-edit"></i></a>
                        <a class="btn btn-danger btn-xs" href="<?php echo site_url('Voucher/voucher_delete').'/'.$value['vuid']; ?>" onclick="return confirm('Are you sure you want to delete this Voucher ?');" ><i class="fa fa-trash"></i></a>
                        <a class="btn btn-warning btn-xs" href="<?php echo site_url('Voucher/voucher_approve').'/'.$value['vuid']; ?>" onclick="return confirm('Are you sure you want to Approve this Voucher ?');" ><i class="fa fa-check"></i></a>
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