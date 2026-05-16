<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Received Voucher</h1>
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

    <section class="content">
      <div class="container">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Update Received Voucher Information</h3>
              </div>

              <div class="card-body">
                <form method="POST" action="<?php echo site_url('Voucher/update_received_voucher') ?>">
                  <div class="row">
                    <input type="hidden" name="vuid" class="form-control" value="<?php echo $voucher['vuid']; ?>" required >
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Voucher Date *</label>
                      <input type="text" name="date" class="form-control datepicker" value="<?php echo date('d-m-Y', strtotime($voucher['vuDate'])); ?>" required >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Select Customer *</label>
                      <select class="form-control select2" name="customerID" required >
                        <option value="">Select One</option>
                        <?php foreach($customer as $value){ ?>
                        <option <?php echo ($voucher['custid'] == $value['custid'])?'selected':''?> value="<?php echo $value['custid']; ?>"><?php echo $value['custName'].' ( '.$value['custCode'].' )'; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                    
                  <div class="row" style="background-color:#298894; color: black;" align="center">
                    <?php foreach ($voucherp as $value) { ?>
                    <div class="form-group col-md-8 col-sm-8 col-8">
                      <label>Particulars</label>
                      <input type="text" class="form-control" value="<?php echo $value['particulars']; ?>"  name="particular[]" >
                    </div>
                    <div class="col-md-4 col-sm-4 col-4">
                      <div class="form-group">
                        <label>Amount</label>
                        <input type="text" class="form-control" name="amount[]" value="<?php echo $value['amount']; ?>" >
                      </div>
                    </div>
                    <?php } ?>
                  </div>

                  <div class="row">
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Payment Method *</label>
                      <select class="form-control" name="accountType" id="accountType" required >
                        <option value="">Select One</option>
                        <option <?php echo ($voucher['accountType'] == 'Cash')?'selected':''?> value="Cash">Cash</option>
                        <option <?php echo ($voucher['accountType'] == 'Bank')?'selected':''?> value="Bank">Bank</option>
                        <option <?php echo ($voucher['accountType'] == 'Mobile')?'selected':''?> value="Mobile">Mobile</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Account No *</label>
                      <select class="form-control" name="accountNo" id="accountNo" required >
                        <option value="">Select One</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Notes</label>
                      <input type="text" class="form-control" name="note" value="<?php echo $voucher['notes']; ?>" >
                    </div>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-12" style="margin-top:20px; text-align: center;">
                    <button type="submit" class="btn btn-info"><i class="far fa-save"></i>&nbsp;&nbsp;Update</button>
                    <a href="<?php echo site_url('rVoucher') ?>" class="btn btn-danger" ><i class="fa fa-arrow-left" ></i>&nbsp;&nbsp;Back</a>
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
        var value = $("#accountType").val();
        $('#accountNo').empty();
        getAccountNo(value, '#accountNo');
        $('#accountNo').val("<?php echo $voucher['accountNo']; ?>");
        });

      var url = '<?php echo site_url('Voucher')?>';

      $('#accountType').on('change',function(){
        var value = $(this).val();
        $('#accountNo').empty();
        getAccountNo(value,'#accountNo');
        });

      function getAccountNo(value,place){
        $(place).empty();
        if (value != ''){
          $.ajax({
            url: url+'/getAccountNo',
            async: false,
            dataType: "json",
            data: 'id=' + value,
            type: "POST",
            success: function (data){               
              $(place).append(data);
              $(place).trigger("chosen:updated");
            }
          });

          }else {
            $.alert({
              title: 'Alert!',
              content: 'Please Select Account Type',
              type: "red",
              icon: 'fa fa-warning',
              theme: "material",
              });
            }
        }
    </script>