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

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">BRTA Registration Information</h3>
              </div>

              <div class="card-body">
                <form method="POST" action="<?php echo base_url() ?>Sale/update_new_sale" >
                  <input type="hidden" name="said" value="<?php echo $sale['said']; ?>" required >
                  <input type="hidden" name="invoice" value="<?php echo $sale['invoice']; ?>" required >
                  <input type="hidden" name="date" value="<?php echo date('m/d/Y', strtotime($sale['saDate'])); ?>" required >
                  <input type="hidden" name="customer" value="<?php echo $sale['custid']; ?>" required >

                  <div class="col-sm-12 col-md-12 col-12"  >
                    <table id="mtable" class="table table-bordered table-striped">
                      <thead class="btn-default">
                        <tr>
                          <th>Product</th>
                          <th>Chassis</th>
                          <th>Engine</th>
                          <th>Quantity</th>
                          <th>Price</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody id="tbody">
                        <?php
                        $sl = 0;
                        foreach($salesp as $value){
                        $id = $value['pid'];
                        ?>
                        <tr>
                          <td>
                            <?php echo $value['pName'].' ( '.$value['pCode'].' )'; ?>
                            <input type='hidden' name='product[]' value="<?php echo $value['pid']; ?>" required>
                          </td>
                          <td>
                            <input type='text' class='form-control' name='pChassis[]' value="<?php echo $value['spChassis']; ?>" required >
                          </td>
                          <td>
                            <input type='text' class='form-control' name='pEngine[]' value="<?php echo $value['spEngine']; ?>" required >
                          </td>
                          <td>
                            <input type='text' class='form-control' onkeyup='totalPrice(<?php echo $id ?>)' name='quantity[]' id='quantity_<?php echo $id ?>' value="<?php echo $value['quantity']; ?>" required readonly >
                          </td>
                          <td>
                            <input type='text' class='form-control' onkeyup='totalPrice(<?php echo $id ?>)' name='sprice[]' id='sprice_<?php echo $id ?>' value="<?php echo $value['sprice']; ?>" required >
                          </td>
                          <td>
                            <input type='text' class='tprice form-control' name='tprice[]' id='tprice_<?php echo $id ?>' value="<?php echo $value['tprice']; $sl += $value['tprice']; ?>" required readonly >
                          </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                      <tbody>
                        <tr>
                          <td colspan="5" align="right">Total Amount</td>
                          <td>
                            <input type="text" class="form-control" name="tAmount" id="tAmount" value="<?php echo $sale['tAmount']; ?>" required readonly >
                          </td>
                        </tr>
                        <tr>
                          <td colspan="5" align="right">Discount Amount</td>
                          <td>
                            <input type="text" class="form-control" name="discount" id="discount" placeholder="Amount" onkeyup="calculate_remain()" onkeypress="return isNumberKey(event)" value="<?php echo $sale['disAmount']; ?>" >
                          </td>
                        </tr>
                        <tr>
                          <td colspan="5" align="right">VAT Amount</td>
                          <td>
                            <input type="text" class="form-control" name="salevat" id="salevat" placeholder="Amount" onkeyup="calculate_remain()" onkeypress="return isNumberKey(event)" value="<?php echo $sale['vAmount']; ?>" >
                          </td>
                        </tr>
                        <tr>
                          <td colspan="5" align="right">Net Amount *</td>
                          <td>
                            <input type="text" class="form-control" name="nAmount" id="nAmount" value="<?php echo (($sale['tAmount']+$sale['vAmount'])-$sale['disAmount']); ?>" required readonly >
                          </td>
                        </tr>
                        <tr>
                          <td colspan="5" align="right">Paid Amount *</td>
                          <td>
                            <input type="text" class="form-control" name="pAmount" id="pAmount" onkeyup="calculate_remain()" onkeypress="return isNumberKey(event)" value="<?php echo $sale['pAmount']; ?>" required readonly >
                          </td>
                        </tr>
                        <tr>
                          <td colspan="5" align="right">Due Amount</td>
                          <td>
                            <input type="text" class="form-control" name="dAmount" id="dAmount" value="<?php echo $sale['dAmount']; ?>" readonly >
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                    
                  <div class="row">
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Payment Method *</label>
                      <select class="form-control" name="accountType" id="accountType" required >
                        <option value="">Select One</option>
                        <option <?php echo ($sale['accountType'] == 'Cash')?'selected':''?> value="Cash">Cash</option>
                        <option <?php echo ($sale['accountType'] == 'Bank')?'selected':''?> value="Bank">Bank</option>
                        <option <?php echo ($sale['accountType'] == 'Mobile')?'selected':''?> value="Mobile">Mobile</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Account No *</label>
                      <select class="form-control" name="accountNo" id="accountNo" required >
                        <option value="">Select Payment Method First</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Paper Submit Type *</label>
                      <div>
                        <input type="radio" name="psType" value="1" <?php echo ($sale['psType'] == 1)?'checked':''?> required >&nbsp;&nbsp;Yes&nbsp;&nbsp;
                        <input type="radio" name="psType" value="0" <?php echo ($sale['psType'] == 0)?'checked':''?> required >&nbsp;&nbsp;No
                      </div>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Note</label>
                      <textarea type="text" class="form-control" name="note" placeholder="If have any note" rows="4" ><?php echo $sale['note']; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-xs-12" style="margin-top:20px; text-align: center;">
                    <button type="submit" class="btn btn-info"><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
                    <a href="<?php echo site_url('saleDList') ?>" class="btn btn-danger" ><i class="fa fa-arrow-left" ></i>&nbsp;&nbsp;Back</a>
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
      function totalPrice(id){
        var pices = $('#quantity_'+id).val();
        var salePrice = $('#sprice_'+id).val();
                //alert(pices); alert(salePrice);
        var totalPrice = salePrice*pices;
        $('#tprice_'+id).val(parseFloat(totalPrice).toFixed(2));
        
        calculateTotalPrice();
        }

      function calculateTotalPrice(){
        var sum=0;
        $(".tprice").each(function(){
          sum += parseFloat($(this).val());
          });
            //alert(sum);
        $('#tAmount').val(parseFloat(sum).toFixed(2));
        $('#pAmount').val(parseFloat(sum).toFixed(2));
        $('#nAmount').val(parseFloat(sum).toFixed(2));
        // $('#dAmount').val(parseFloat(sum).toFixed(2));
        }

      function calculate_remain(){
        var total = $('#tAmount').val();
        var dis = $('#discount').val();
        var vat = $('#salevat').val();
        var paid = $('#pAmount').val();
        
        var tsa = +total + +vat;
        var tpa = +paid + +dis;

        var tna = tsa - dis;
        // var due = tsa - tpa;
        
        $('#nAmount').val(parseFloat(tna).toFixed(2));
        $('#pAmount').val(parseFloat(tna).toFixed(2));
        // $('#dAmount').val(parseFloat(due).toFixed(2));
        }
    </script>
    
    <script type="text/javascript">
      $(document).ready(function(){
        var value = $("#accountType").val();
        $('#accountNo').empty();
        getAccountNo(value, '#accountNo');
        $('#accountNo').val("<?php echo $sale['accountNo'] ?>");
        });

      $('#accountType').on('change',function(){
        var value = $(this).val();
        $('#accountNo').empty();
        getAccountNo(value, '#accountNo');
        });
        
      function getAccountNo(value,place){
        $(place).empty();
        if(value != ''){
          $.ajax({
            url: '<?php echo site_url()?>Voucher/getAccountNo',
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
        else
          {
          customAlert('Please Select Account Type', "error", true);
          }
        }
    </script>