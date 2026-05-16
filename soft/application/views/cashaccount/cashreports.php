<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Cash Book</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Cash Book</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Cash Book</h3>
            </div>

            <div class="card-body">
              <div class="col-sm-12 col-md-12 col-12">
                <div id="print">
                  <div class="row">
                    <?php if($company){ ?>
                    <div class="col-sm-4 col-md-4 col-xs-4" style="margin-top: 25px;" >
                      <img src="<?php echo base_url().'upload/company/'.$company->com_logo; ?>" style="height:90px; width:auto;">
                    </div>
                    <div class="col-sm-8 col-md-8 col-8">
                      <div class="col-sm-12 col-md-12 col-12">
                        <h3><b><?php echo $company->com_name; ?></b></h3>
                      </div>
                      <div class="col-sm-12 col-md-12 col-12">
                        Address&nbsp;:&nbsp;<?php echo $company->com_address; ?>
                      </div>
                      <div class="col-sm-12 col-md-12 col-12">
                        Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<?php echo $company->com_email; ?>
                      </div>
                      <div class="col-sm-12 col-md-12 col-12">
                        Mobile&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<?php echo $company->com_mobile; ?>
                      </div>
                    </div>
                  </div>
                  <?php } ?>
                  <div class="col-md-12 col-sm-12 col-12">
                    <div style="text-align: center;"><h3><b>Cash Book</b></h3></div>
                  </div>
                  <div class="col-md-12 col-sm-12 col-12">
                    <div>
                      <b>Date : <?php echo date("d-m-Y"). "<br>"; ?></b>
                    </div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                      <table class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th style="width: 5%;">#SN.</th>
                            <th>Account Name</th>
                            <th>Opening Balance</th>
                            <th>Current Balance</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $i = 0;
                          $tba = 0;
                          foreach ($cash as $value) {
                          $id = $value['ca_id'];
                          //var_dump($id);
                          $i++;
                          
                          $actype = 'Cash';
                          $current = $this->pm->get_account_amount_data($actype,$id);
                          ?>
                          <tr class="gradeX">
                            <td><?php echo $i; ?></td>
                            <td><?php echo $value['cashName']; ?></td>
                            <td><?php echo number_format(($value['balance']), 2); ?></td>
                            <td><?php echo number_format($value['balance']+$current, 2); $tba += $value['balance']+$current; ?></td>
                          </tr>   
                          <?php } ?> 
                        </tbody>
                        <tfoot>
                          <tr>
                            <th colspan="3" style="text-align: right;">Grand Total</th>
                            <td><?php echo number_format(($tba), 2); ?></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="form-group col-md-12 col-sm-12 col-12" style="text-align: center; margin-top: 20px">
                  <a href="javascript:void(0)" onclick="printDiv('print')" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<?php $this->load->view('footer/footer'); ?>