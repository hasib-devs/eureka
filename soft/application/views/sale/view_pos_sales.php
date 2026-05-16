<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar22'); ?>

  <div class="basic-form-area mg-b-15" style="min-height: 550px;">
    <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-12">
            <div class="card">

              <div class="card-body">
                <div class="row">
                  <!--<div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center;" >-->
                  <!--  <img src="<?php echo base_url().'upload/company/'.$company->com_logo; ?>" style="width: 100%">-->
                  <!--</div>-->
                  <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center;" >
                    <h3><b><?php echo $company->com_name; ?></b></h3>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center;" >
                    <b><?php echo $company->com_address; ?></b>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center;" >
                    <b>Mobile: <?php echo $company->com_mobile; ?></b>
                  </div>
                 <div class="col-md-12 col-sm-12 col-xs-12">
                    <h3 style="text-align: center; border: 2px solid; padding: 10px;">INVOICE</h3>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      Inv. No&nbsp;:&nbsp;<?php echo $sales->invoice; ?>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      Date&nbsp;:&nbsp;<?php echo date('d-m-Y', strtotime($sales->saDate )); ?>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      Customer&nbsp;:&nbsp;<?php echo $sales->custName; ?>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      Mobile&nbsp;:&nbsp;<?php echo $sales->custMobile; ?>
                    </div>
                  </div>
                  
                  <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 10px;" >
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>SN</th>
                          <th>ITEM</th>
                          <th>QTY</th>
                          <th>AMOUNT</th>
                        </tr>
                      </thead>
                        <tbody>
                          <?php
                          $i = 0;
                          $st = 0;
                          foreach ($salesp as $value){
                          $i++;
                          ?>
                          <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $value->pName.' ( '.$value->pCode.' )'; ?></td>
                            <td><?php echo $value->quantity; ?></td>
                            <td><?php echo number_format($value->tprice, 2); $st += $value->tprice; ?></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                        <tbody>
                          <tr>
                            <th colspan="3" style="text-align: right;">Total :</th>
                            <td><?php echo number_format($st, 2); ?></td>
                          </tr>
                          <tr>
                            <th colspan="3" style="text-align: right;">VAT Amount (+) <?php if($sales->vType == '%') { ?>(<?php echo $sales->vCost; ?>)<?php } ?> :</th>
                            <td><?php echo number_format($sales->vat, 2); ?></td>
                          </tr>
                          <tr>
                            <th colspan="3" style="text-align: right;">Discount (-) <?php if($sales->disType == '%') { ?>(<?php echo $sales->discount; ?>)<?php } ?> :</th>
                            <td><?php echo number_format($sales->disAmount, 2); ?></td>
                          </tr>
                          <tr>
                            <th colspan="3" style="text-align: right; font-weight: bold;">Net Amount :</th>
                            <td><?php echo number_format($sales->tAmount, 2); ?></td>
                          </tr>
                        </tbody>
                      </table>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12" >
                    <b>Notes : </b><?php echo $sales->note; ?>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php $this->load->view('footer/footer22'); ?>



    <script type="text/javascript">
        $(window).on('load', function() {
          window.print();
          setTimeout("closePrintView()", 3000);
          });
        
        function closePrintView() {
        document.location.href = 'https://smjbikes.com/posSales';
        }

    </script>