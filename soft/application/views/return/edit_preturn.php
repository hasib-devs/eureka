<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <!--<section class="content-header">-->
    <!--  <div class="container-fluid">-->
    <!--    <div class="row mb-2">-->
    <!--      <div class="col-sm-6">-->
    <!--        <h1>Return</h1>-->
    <!--      </div>-->
    <!--      <div class="col-sm-6">-->
    <!--        <ol class="breadcrumb float-sm-right">-->
    <!--          <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>-->
    <!--          <li class="breadcrumb-item active">Return</li>-->
    <!--        </ol>-->
    <!--      </div>-->
    <!--    </div>-->
    <!--  </div>-->
    <!--</section>-->

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Return Information</h3>
              </div>

              <div class="card-body">
                <form method="POST" action="<?php echo base_url() ?>Returns/save_preturns" >
                  <div class="row">
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Return Date *</label>
                      <input type="text" class="form-control datepicker" name="date" value="<?php echo date('m/d/Y'); ?>" required >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Select Supplier *</label>
                      <select class="form-control" name="supplier" required >
                        <option value="">Select One</option>
                        <?php foreach ($supplier as $value) { ?>
                        <option <?php echo ($returns['supid'] == $value['supid'])?'selected':''?> value="<?php echo $value['supid'] ?>"><?php echo $value['supName']; ?></option>
                        <?php } ?>
                      </select>
                    </div> 
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Select Product</label>
                      <select class="form-control" id="productID" >
                        <option value="">Select One</option>
                        <?php foreach($product as $value){ ?>
                        <option value="<?php echo $value['pid']; ?>"><?php echo $value['pName'].' ( '.$value['partNo'].' - '.$value['pCode'].' )'; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-12" >
                    <table id="mtable" class="table table-bordered table-striped">
                      <thead class="btn-default">
                        <tr>
                          <th>Products</th>
                          <th>Quantity</th>              
                          <th>Sale Price</th>
                          <th>Total Price</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="tbody">
                        <?php
                        foreach($rproduct as $value){
                        $id = $value['pid'];
                        ?>
                        <tr>
                          <td>
                            <?php echo $value['pName']." ( ".$value['pCode']." )"; ?>
                            <input type='hidden' name='product[]' value="<?php echo $id; ?>" required >
                          </td>       
                          <td>
                            <input type='text' onkeyup='totalPrice(<?php echo $id ?>)' name='quantity[]' id='quantity_<?php echo $id ?>' value="<?php echo $value['quantity']?>">
                          </td>
                          <td>
                            <input type='text' onkeyup='totalPrice(<?php echo $id ?>)' name='sprice[]' id='sprice_<?php echo $id ?>' value="<?php echo $value['pprice']?>">
                          </td>
                          <td>
                            <input type='text' class='tprice' name='tprice[]' readonly id='tprice_<?php echo $id ?>' value="<?php echo $value['tprice']?>">
                          </td>
                          <td>
                            <input type="button" class="btn btn-danger" value="Remove" onClick="$(this).parent().parent().remove();">
                          </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>

                  <div class="row">
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Total Amount *</label>
                      <input type="text" class="form-control" name="tAmount" id="tAmount" value="<?php echo $returns['tAmount']; ?>" readonly required  >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Payment Method *</label>
                      <select class="form-control" name="accountType" id="accountType" required >
                        <option value="">Select One</option>
                        <option <?php echo ($returns['accountType'] == 'Cash')?'selected':''?> value="Cash">Cash</option>
                        <option <?php echo ($returns['accountType'] == 'Bank')?'selected':''?> value="Bank">Bank</option>
                        <option <?php echo ($returns['accountType'] == 'Mobile')?'selected':''?> value="Mobile">Mobile</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Account No *</label>
                      <select class="form-control" name="accountNo" id="accountNo" required >
                        <option value="">Select Payment Method First</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                      <label>Invoice No *</label>
                      <input type="text" class="form-control" name="invoice" value="<?php echo $returns['challanNo']; ?>"  required >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Note</label>
                      <input type="text" class="form-control" name="note" value="<?php echo $returns['note']; ?>" >
                    </div>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px; text-align: center;">
                    <button type="submit" class="btn btn-primary"><i class="far fa-save"></i>&nbsp;&nbsp;Update</button>
                    <a href="<?php echo site_url('pReturn') ?>" class="btn btn-danger" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
      $('#productID').on('change',function(){
        var id = $('#productID option:selected').val();
        //alert(productID);
        var info = {'id':id};
        var base_url = '<?php echo base_url() ?>'+'Returns/getDetails/';
        
        $.ajax({
          type: 'POST',
          async: false,
          url: base_url,
          data:info,
          dataType: 'json',
          success: function (data) {                            
            $('#mtable tbody').append(data);
            }
          });
        });
    </script>

    <script type="text/javascript">
      function totalPrice(id){
        var pices = $('#quantity_'+id).val();
        var salePrice = $('#sprice_'+id).val();
        
        var totalPrice = salePrice * pices;
        $('#tprice_'+id).val(parseFloat(totalPrice).toFixed(2));
        
        calculateTotalPrice();
        }

      function calculateTotalPrice(){
        var sum=0;
        $(".tprice").each(function(){
          sum += parseFloat($(this).val());
          });
        $('#tAmount').val(parseFloat(sum).toFixed(2));
        }
    </script>

    <script type="text/javascript">
      $(document).ready(function(){
        var value = $("#accountType").val();
        $('#accountNo').empty();
        getAccountNo(value, '#accountNo');
        $('#accountNo').val("<?php echo $returns['accountNo']; ?>");
        });

      var url = '<?php echo site_url('Voucher')?>';

      $('#accountType').on('change',function(){
        var value = $(this).val();
        $('#accountNo').empty();
        getAccountNo(value,'#accountNo');
        });

      function getAccountNo(value,place){
        $(place).empty();
        if(value != '')
          {
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
          } 
        else {
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