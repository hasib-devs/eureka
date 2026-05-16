<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>LC Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">LC Management</li>
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
                <h3 class="card-title">LC Management Information</h3>
              </div>

              <div class="card-body">
                <form method="POST" action="<?php echo site_url("Lcmanagement/update_lc_management") ?>">
                  <div class="row">
                    <input type="hidden" name="lcid" value="<?php echo $purchase['lcid']; ?>" required >
                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                      <label>LC Date</label>
                      <input type="text" name="date" class="form-control datepicker" value="<?php echo date('m/d/Y',strtotime($purchase['lcDate'])) ?>" required >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Select Supplier *</label>
                      <select class="form-control select2" name="supplier" required >
                        <option value="">Select One</option>
                        <?php foreach($supplier as $value){ ?>
                        <option <?php echo ($purchase['supid'] == $value['supid'])?'selected':''?> value="<?php echo $value['supid']; ?>"><?php echo $value['supName']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Select Product </label>
                      <select class="form-control select2" id="product" >
                        <option value="">Select One</option>
                        <?php foreach($product as $value){ ?>
                        <option value="<?php echo $value['pid']; ?>"><?php echo $value['pName'].' ( '.$value['partNo'].' )'; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-12">
                    <table id="mtable" class="table table-bordered table-striped">
                      <thead class="btn-default">
                        <tr>
                          <th>Product</th>
                            <th>HS Code</th>
                            <th>Weight ( KG )</th>
                            <th>USD Per KG</th>
                            <th>Total USD</th>
                            <th>Quantity</th>
                            <th>USD Per Unit</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="tbody">
                        <?php foreach($pproduct as $value){
                        $id = $value['pid'];
                        ?>
                        <tr>
                          <td>
                            <?php echo $value['pName'].' ( '.$value['pCode'].' )'; ?>
                            <input type="hidden" name='product[]' value="<?php echo $value['pid']; ?>" required >
                          </td>
                          <td>
                            <input type='text' class="form-control" name='hscode[]' value="<?php echo $value['hscode']; ?>" required >
                          </td>
                          <td>
                            <input type='text' class="form-control" name='weight[]' id='weight_<?php echo $id; ?>' onkeyup='getTotal(<?php echo $id; ?>)' value='<?php echo $value['weight']; ?>' required >
                          </td>
                          <td>
                            <input type='text' class="form-control" name='uprice[]' id='uprice_<?php echo $id; ?>' onkeyup='getTotal(<?php echo $id; ?>)' value='<?php echo $value['uprice']; ?>' required >
                          </td>
                          <td>
                            <input type='text' class="form-control tprice" name='tprice[]' id="tprice_<?php echo $id; ?>" value="<?php echo $value['tprice']; ?>" required readonly >
                          </td>
                          <td>
                            <input type='text' class="form-control" name='quantity[]' id='quantity_<?php echo $id; ?>' onkeyup='getTotal(<?php echo $id; ?>)' value='<?php echo $value['quantity']; ?>' required >
                          </td>
                          <td>
                            <input type='text' class="form-control" name='upprice[]' id='upprice_<?php echo $id; ?>' value='<?php echo $value['upprice']; ?>' required readonly >
                          </td>
                          <td>
                            <span class='item_remove btn btn-danger btn-xs' onclick='deleteProduct(this)'>x</span>
                          </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                      <tbody>
                        <tr>
                          <td colspan="6" align="right">Total Amount *</td>
                          <td colspan="2" ><input type="text" class="form-control" name="tAmount" id="tAmount" value="<?php echo $purchase['tAmount']; ?>" required readonly ></td>
                        </tr>
                        <tr>
                          <td colspan="6" align="right">Paid Amount *</td>
                          <td colspan="2" ><input type="text" class="form-control" name="pAmount" id="pAmount" onkeyup="calculate_remain()" required value="<?php echo $purchase['pAmount']; ?>" onkeypress="return isNumberKey(event)" ></td>
                        </tr>
                        <tr>
                          <td colspan="6" align="right">Due Amount *</td>
                          <td colspan="2" ><input type="text" class="form-control" name="dAmount" id="dAmount" value="<?php echo $purchase['dAmount']; ?>" readonly ></td>
                        </tr>
                      </tbody>
                    </table>  
                  </div>    

                  <div class="row">
                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                      <label>Payment Method *</label>
                      <select class="form-control" name="accountType" id="accountType" required >
                        <option <?php echo ($purchase['accountType'] == 'Cash')?'selected':''?> value="Cash">Cash</option>
                        <option <?php echo ($purchase['accountType'] == 'Bank')?'selected':''?> value="Bank">Bank</option>
                        <option <?php echo ($purchase['accountType'] == 'Mobile')?'selected':''?> value="Mobile">Mobile</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                      <label>Account Number *</label>
                      <select name="accountNo" id="accountNo" class="form-control" required >
                        <option value="">Select Account Type First</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                      <label>Note</label>
                      <input type="text" class="form-control" name="note" value="<?php echo $purchase['notes']; ?>" placeholder="If have any note" >
                    </div>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-12" style="margin-top:20px; text-align: center;">
                    <button type="submit" class="btn btn-info"><i class="far fa-save"></i>&nbsp;&nbsp;Update</button>
                    <a href="<?php echo site_url() ?>Lcmanagement" class="btn btn-danger" ><i class="fa fa-arrow-left" ></i>&nbsp;&nbsp;Back</a>
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
      $('#product').change(function(){    
        var id = $('#product').val();
          //alert(id);
        var base_url = '<?php echo base_url() ?>'+'Lcmanagement/get_product/'+id;
              // alert(base_url);
        $.ajax({
          type: 'GET',
          url: base_url,
          dataType: 'text',
          success: function(data){
            var jsondata = JSON.parse(data);                
            $('#mtable').append(jsondata);

            calculatePrice();
            }
          });
        });
      });
  </script>

  <script type="text/javascript" >
    function deleteProduct(o){
      var p=o.parentNode.parentNode;
      p.parentNode.removeChild(p);
       
      calculatePrice();
      }
  </script>

  <script type="text/javascript">
    function getTotal(id){        
      var weight = $('#weight_'+id).val();
      var uprice = $('#uprice_'+id).val();
      
      var tprice = (weight*uprice);
      $('#tprice_' + id).val(parseFloat(tprice).toFixed(2));
      
      var quantity = $('#quantity_'+id).val();
        // alert(tp);alert(quantity);
      var price = (tprice/quantity);
      
      $('#upprice_' + id).val(parseFloat(price).toFixed(2));
      
      calculatePrice();
      }

    function calculatePrice(){
      var sum=0;
      $(".tprice").each(function()
        {
        sum += parseFloat($(this).val());
        });
      $('#tAmount').val(parseFloat(sum).toFixed(2));
      $('#dAmount').val(parseFloat(sum).toFixed(2));
      }

    function calculate_remain(){
      var total = $('#tAmount').val();
      var paid = $('#pAmount').val();
      
      var due = total - paid;
      $('#dAmount').val(parseFloat(due).toFixed(2));
      }
  </script>

    <script type="text/javascript">
        $(document).ready(function(){
          var value = $("#accountType").val();
          $('#accountNo').empty();
          getAccountNo(value, '#accountNo');
          $('#accountNo').val(<?php echo $purchase['accountNo'] ?>);
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
  