<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sales</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Sales</li>
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
                <h3 class="card-title">Update Sale Information</h3>
              </div>

              <div class="card-body">
                <form method="POST" action="<?php echo base_url() ?>Sale/update_sale" >
                  <input type="hidden" name="said" value="<?php echo $sale['said']; ?>" required >
                  <div class="row sticky-top">
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Sale Date *</label>
                      <input type="text" name="date" class="form-control datepicker" value="<?php echo date('m/d/Y', strtotime($sale['saDate'])) ?>" required >
                    </div> 
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Select Customer *</label>
                      <select class="form-control select2" name="customer" required >
                        <option value="">Select One</option>
                        <?php foreach($customer as $value){ ?>
                        <option <?php echo ($sale['custid'] == $value['custid'])?'selected':''?> value="<?php echo $value['custid']; ?>"><?php echo $value['custName']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Sales Type</label>
                      <select class="form-control" name="saType" id="saType" required >
                        <option <?php echo ($sale['saType'] == 1)?'selected':''?> value="1">Retail Sales</option>
                        <option <?php echo ($sale['saType'] == 2)?'selected':''?> value="2">Wholesale</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Product Category</label>
                      <select class="form-control" name="category" id="category">
                        <option value="">Select One</option>
                        <?php foreach($category as $value){ ?>
                        <option value="<?php echo $value['catid']; ?>"><?php echo $value['catName']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                      <label>Select Product</label>
                        <!--<select class="form-control select2" id="product" >-->
                          <!--  <option value="">Select One</option>-->
                          <!--  <?php foreach($product as $value){ ?>-->
                          <!--  <option value="<?php echo $value['pid']; ?>"><?php echo $value['pName'].' ( '.$value['pCode'].' )'; ?></option>-->
                          <!--  <?php } ?>-->
                        
                       <select class="form-control select2" id="product" >
                        <option value="">Select One</option>
                      <!--</select>-->
                      </select>
                    </div>
                  </div>

                  <div class="col-sm-12 col-md-12 col-12"  >
                    <table id="mtable" class="table table-bordered table-striped">
                      <thead class="btn-default">
                        <tr>
                          <th>Product</th>
                          <th>Purchase</th>
                          <th>Part No.</th>
                          <th>Model</th>
                          <th>INR Price</th>
                          <th>Rate</th>
                          <th>Stock</th>
                          <th>Qnt.</th>
                          <th>Price</th>
                          <th>Dis</th>
                          <th>Incre.</th>
                          <th>Total</th> 
                          <th>Action</th>    
                        </tr>
                      </thead>
                      <tbody id="tbody">
                        <?php
                        $sl = 0;
                        foreach($salesp as $value){
                        $id = $value['pid'];
                        $sqt = $this->db->select('tquantity')
                                    ->from('stock')
                                    ->where('pid',$id)
                                    ->get()
                                    ->row();
                        ?>
                        <tr>
                          <td>
                            <?php echo $value['pName'].' ( '.$value['pCode'].' )'; ?>
                            <input type='hidden' name='product[]' value="<?php echo $value['pid']; ?>" required>
                          </td>
                          <td>
                            <select class="form-control" name="spType[]" onchange="productcostPrice(<?php echo $value['pid']; ?>)" id="spType_<?php echo $value['pid']; ?>" required >
                              <option <?php echo ($value['spType'] == 1)?'selected':''?> value="1">Costing</option>
                              <option <?php echo ($value['spType'] == 2)?'selected':''?> value="2">Local</option>
                            </select>
                          </td>
                          <td>
                            <input type='text' class='form-control' name='pChassis[]' value="<?php echo $value['spChassis']; ?>" required >
                          </td>
                          <td>
                            <input type='text' class='form-control' name='pColor[]' value="<?php echo $value['spColor']; ?>" >
                          </td>
                          <td>
                            <input type='text' class='form-control' name='pEngine[]' id='pEngine_<?php echo $id ?>' value="<?php echo $value['spEngine']; ?>" required readonly >
                          </td>
                          <td><input type='text' class='form-control' onkeyup='totalPrice(<?php echo $id ?>)' name='iRate[]' id='iRate_<?php echo $id ?>' value='<?php echo $value['iRate']; ?>' required <?php if($sale['saType'] == 1){ ?>readonly<?php } ?> ></td>
                          <td>
                            <?php echo isset($sqt->tquantity); ?>
                          </td>
                          <td>
                            <input type='text' class='form-control' onkeyup='totalPrice(<?php echo $id ?>)' name='quantity[]' id='quantity_<?php echo $id ?>' value="<?php echo $value['quantity']; ?>" required >
                          </td>
                          <td>
                            <input type='text' class='form-control' onkeyup='totalPrice(<?php echo $id ?>)' name='sprice[]' id='sprice_<?php echo $id ?>' value="<?php echo $value['sprice']; ?>" required  >
                          </td>
                          <td>
                            <input type='text' class='form-control' onkeyup='totalPrice(<?php echo $id ?>)' name='pdiscount[]' id='pdiscount_<?php echo $id ?>' value="<?php echo $value['pdiscount']; ?>"  >
                          </td>
                          <td>
                            <input type='text' class='form-control' onkeyup='totalPrice(<?php echo $id ?>)' name='iprice[]' id='iprice_<?php echo $id ?>' value="<?php echo $value['iprice']; ?>"  >
                          </td>
                          <td>
                            <input type='text' class='tprice form-control' name='tprice[]' id='tprice_<?php echo $id ?>' value="<?php echo $value['tprice']; $sl += $value['tprice']; ?>" required readonly >
                          </td>
                          <td>
                            <span class='btn btn-danger item_remove' onclick='deleteProduct(this)' >x</span>
                          </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                      <tbody>
                        <tr>
                          <td colspan="11" align="right">Total Amount</td>
                          <td colspan="2"  >
                            <input type="text" class="form-control" name="tAmount" id="tAmount" value="<?php echo $sale['tAmount']; ?>" required readonly >
                          </td>
                        </tr>
                        <tr>
                          <td colspan="11" align="right">Discount Amount</td>
                          <td colspan="2" >
                            <input type="text" class="form-control" name="discount" id="discount" placeholder="Amount" onkeyup="calculate_remain()" onkeypress="return isNumberKey(event)" value="<?php echo $sale['disAmount']; ?>" >
                          </td>
                        </tr>
                        <tr>
                          <td colspan="11" align="right">VAT Amount</td>
                          <td colspan="2" >
                            <input type="text" class="form-control" name="salevat" id="salevat" placeholder="Amount" onkeyup="calculate_remain()" onkeypress="return isNumberKey(event)" value="<?php echo $sale['vat']; ?>" >
                          </td>
                        </tr>
                        <tr>
                          <td colspan="11" align="right">Shipping Charge Amount</td>
                          <td colspan="2" >
                            <input type="text" class="form-control" name="charge" id="charge" onkeyup="calculate_remain()" onkeypress="return isNumberKey(event)" value="<?php echo $sale['charge']; ?>" >
                          </td>
                        </tr>
                        <tr>
                          <td colspan="11" align="right">Net Amount *</td>
                          <td colspan="2" ><input type="text" class="form-control" name="nAmount" id="nAmount" value="<?php echo (($sale['tAmount']+$sale['vat'])-$sale['disAmount']); ?>" required readonly ></td>
                        </tr>
                        <?php
                        $pay = $this->db->select("SUM(pAmount) as total")
                                    ->FROM('sales_payment')
                                    ->WHERE('said',$sale['said'])
                                    ->get()
                                    ->row();
                        if($pay)
                          {
                          $tpay = $pay->total;
                          }
                        else
                          {
                          $tpay = 0;
                          }
                        ?>
                        <tr>
                          <td colspan="11" align="right">Payment Amount *</td>
                          <td colspan="2" ><input type="text" class="form-control" id="payAmount" value="<?php echo $tpay; ?>" readonly ></td>
                        </tr>
                        <tr>
                          <td colspan="11" align="right">Paid Amount *</td>
                          <td colspan="2" ><input type="text" class="form-control" name="pAmount" id="pAmount" onkeyup="calculate_remain()" onkeypress="return isNumberKey(event)" value="<?php echo $sale['pAmount']; ?>" required ></td>
                        </tr>
                        <tr>
                          <td colspan="11" align="right">Due Amount</td>
                          <td colspan="2" ><input type="text" class="form-control" name="dAmount" id="dAmount" value="<?php echo $sale['dAmount']; ?>" readonly ></td>
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
                        <option <?php echo ($sale['accountType'] == 'Courier')?'selected':''?> value="Courier">Courier</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Account No *</label>
                      <select class="form-control" name="accountNo" id="accountNo" required >
                        <option value="">Select Payment Method First</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Select Courier </label>
                      <select class="form-control select2" name="id" id="id"  >
                        <option value="0">Select One</option>
                        <!--<option value="cust">New Customer</option>-->
                        <?php foreach($courier as $value){ ?>
                        <option <?php echo ($sale['id'] == $value['id'])?'selected':''?> value="<?php echo $value['id']; ?>"><?php echo $value['courierName']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Select Courier Employee </label>
                      <select class="form-control select2" name="cempid"  >
                        <option value="0">Select One</option>
                        <?php foreach($employees as $value){ ?>
                        <option <?php echo ($sale['cempid'] == $value['empid'])?'selected':''?> value="<?php echo $value['empid']; ?>"><?php echo $value['empName']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Note</label>
                      <textarea type="text" class="form-control" name="note" placeholder="If have any note" rows="4" ><?php echo $sale['note']; ?></textarea>
                    </div>
                    
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Comment</label>
                      <textarea type="text" class="form-control" name="comment" placeholder="If have any comment" rows="4" ><?php echo $sale['comment']; ?></textarea>
                    </div>
                    
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-xs-12" style="margin-top:20px; text-align: center;">
                    <button type="submit" class="btn btn-info"><i class="far fa-save"></i>&nbsp;&nbsp;Update</button>
                    <a href="<?php echo site_url('Sale') ?>" class="btn btn-danger" ><i class="fa fa-arrow-left" ></i>&nbsp;&nbsp;Back</a>
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
    
    <script>
      $(document).ready(function() {
        $('#category').change(function() {
        var catid = $(this).val();
        if (catid != "") {
          $.ajax({
            url: '<?php echo base_url("Sale/get_product"); ?>',
            type: 'POST',
            data: {catid: catid},
            success: function(response) {
              $('#product').html(response);
            }
          });
          } else {
            $('#product').html('<option value="">Select One</option>');
          }
        });
      });
   </script>
   
    <script type="text/javascript">
      $('#product').change(function(){
        var id = $('#product').val();
        var saType = $('#saType').val();
        var url = '<?php echo base_url() ?>'+'Sale/get_product_details/'+id+ '/' + saType;
          //alert(id); exit();
        $.ajax({
          type: 'GET',
          url: url,
          dataType: 'text',
          success: function(data){
              //alert(data); exit();
            var jsondata = JSON.parse(data);
            $('#mtable').append(jsondata);
            calculateTotalPrice();
            }
          });
        });
    </script>

    <script type="text/javascript" >
      function deleteProduct(o){
        var p=o.parentNode.parentNode;
         p.parentNode.removeChild(p);
         
        calculateTotalPrice();
        }
    </script>

    <script type="text/javascript">
      function totalPrice(id){
        var pices = $('#quantity_'+id).val();
        var dis = $('#pdiscount_'+id).val();
        var salePrice = $('#sprice_'+id).val();
        var iprice = $('#iprice_'+id).val();
        var pEngine = $('#pEngine_'+id).val();
        var iRate = $('#iRate_'+id).val();
                //alert(pices); alert(salePrice);
        var discc = dis.slice(-1);
        var disca = dis.substring(0, dis.length - 1);
        
        if(discc == '%')
          {
          var da = ((salePrice*disca)/100);
          }
        else
          {
          var da = dis;
          }
        if(iRate > 1)
          {
          var saprice = pEngine * iRate;
          
          $('#sprice_'+id).val(parseFloat(saprice).toFixed(2));
          }
        else
          {
          var saprice = salePrice;
          }
          //alert(da);
        var aprice = +saprice + +iprice;
        //var adis = ((aprice*da)/100);
        var sprice = (aprice-da);
        var totalPrice = sprice*pices;
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
        $('#nAmount').val(parseFloat(sum).toFixed(2));
        //$('#dAmount').val(parseFloat(sum).toFixed(2));
        calculate_remain();
        }

      function calculate_remain(){
        var total = $('#tAmount').val();
        var dis = $('#discount').val();
        var vat = $('#salevat').val();
        var tca = $('#charge').val();
        var paid = $('#pAmount').val();
        var pay = $('#payAmount').val();
        //alert(total);alert(dis);alert(vat);alert(tca);alert(paid);alert(pay);
        var tsa = +total + +vat + +tca ;
        var tpa = +paid + +dis+ +pay;

        var tna = tsa - dis;
        var due = tsa - tpa;
        
        $('#nAmount').val(parseFloat(tna).toFixed(2));
        $('#dAmount').val(parseFloat(due).toFixed(2));
        }
      
      function productcostPrice(id){
        var spid = $('#spType_'+id).val();
        var url = '<?php echo base_url() ?>'+'Sale/get_product_price_data/'+id+'/'+spid;
          //alert(id); alert(url);
        $.ajax({
          type: 'GET',
          url: url,
          dataType: 'json',
          success: function(data){
              //alert(data); //exit();
            var pprice = parseFloat(data.pprice || 0).toFixed(2);
            var sprice = parseFloat(data.sprice || 0).toFixed(2);

            $('#pEngine_' + id).val(pprice);
            $('#sprice_' + id).val(sprice);
            
            calculateTotalPrice();
            }
          });
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