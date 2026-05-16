<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Purchase Order</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Purchase Order</li>
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
                <h3 class="card-title">Purchase Order Information</h3>
              </div>

              <div class="card-body">
                <form method="POST" action="<?php echo site_url("Purchase/update_purchase") ?>">
                  <div class="row">
                    <input type="hidden" name="puid" value="<?php echo $purchase['puid']; ?>" required >
                    <div class="form-group col-md-3 col-sm-3 col-xs-12">
                      <label>Purchase Date</label>
                      <input type="text" name="date" class="form-control datepicker" value="<?php echo date('m/d/Y',strtotime($purchase['puDate'])) ?>" required >
                    </div>
                    <div class="form-group col-md-3 col-sm-3 col-12">
                      <label>Select Supplier *</label>
                      <select class="form-control select2" name="supplier" required >
                        <option value="">Select One</option>
                        <?php foreach($supplier as $value){ ?>
                        <option <?php echo ($purchase['supid'] == $value['supid'])?'selected':''?> value="<?php echo $value['supid']; ?>"><?php echo $value['supName']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group col-md-3 col-sm-3 col-12">
                      <label>Product Category</label>
                      <select class="form-control select2" name="category" id="category">
                        <option value="">Select One</option>
                        <?php foreach($category as $value){ ?>
                        <option value="<?php echo $value['catid']; ?>"><?php echo $value['catName']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group col-md-3 col-sm-3 col-12">
                      <label>Select Product *</label>
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

                  <div class="col-md-12 col-sm-12 col-12">
                    <table id="mtable" class="table table-bordered table-striped">
                      <thead class="btn-default">
                        <tr>
                          <th>Product</th>
                            <th>Part No</th>
                            <th>HS Code</th>
                            <th>Model</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Tax (%)</th> 
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="tbody">
                        <?php foreach($pproduct as $value){
                        $id = $value['pid'];
                        
                        $qproduct = $this->db->select('*')
                                          ->from('purchase_chassis')
                                          ->where('ppid',$value['ppid'])
                                          ->where('pid',$id)
                                          ->get()
                                          ->result();
                        ?>
                        <tr>
                          <td>
                            <?php echo $value['pName'].' ( '.$value['pCode'].' )'; ?>
                            <input type="hidden" name='product[]' value="<?php echo $value['pid']; ?>" required >
                          </td>
                          <td>
                            <input type='text' class="form-control" name='partNo[]' id="partNo_<?php echo $id; ?>" value='<?php echo $value['partNo']; ?>' required >
                          </td>
                          <td>
                            <input type="text" class="form-control" name='pChassis[]' value="<?php echo $value['ppChassis']; ?>" required >
                          </td>
                          <td>
                            <input type="text" class="form-control" name='pEngine[]' value="<?php echo $value['ppEngine']; ?>" required >
                          </td>
                          <td>
                            <input type='text' class="form-control" name='quantity[]' id="quantity_<?php echo $id; ?>" onkeyup="getTotal('<?php echo $id ?>')" value="<?php echo $value['quantity']; ?>" required >
                          </td>
                          <td>
                            <input type='text' class="form-control" name='pprice[]' id='pprice_<?php echo $id; ?>' onkeyup='getTotal(<?php echo $id; ?>)' value='<?php echo $value['pprice']; ?>' required >
                          </td>
                          <td>
                            <input type='text' class="form-control" name='igst[]' id='igst_<?php echo $id; ?>' onkeyup='getTotal(<?php echo $id; ?>)' value='<?php echo $value['igst']; ?>' required >
                            <input type='hidden' class='taxamt' id='tax_<?php echo $id; ?>' value='<?php echo ($value['quantity']*(($value['igst']*$value['pprice'])/100)); ?>' required >
                          </td>
                          <td>
                            <input type='text' class="form-control tprice" id='tprice_<?php echo $id; ?>' name='tprice[]' value='<?php echo $value['tprice']; ?>' required readonly>
                          </td>
                          <td>
                            <span class='item_remove btn btn-danger btn-xs' onclick='deleteProduct(this)'>x</span>
                          </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                      <tbody>
                        <tr>
                          <td colspan="7" align="right">Total Amount *</td>
                          <td colspan="2" ><input type="text" class="form-control" name="tAmount" id="tAmount" value="<?php echo $purchase['tAmount']; ?>" required readonly ></td>

                        </tr>
                        <tr>
                          <td colspan="7" align="right">Vat & Tax Amount *</td>
                          <td colspan="2" ><input type="text" class="form-control" name="vAmount" id="vAmount" onkeyup="calculate_remain()" required value="<?php echo $purchase['vAmount']; ?>" onkeypress="return isNumberKey(event)" readonly  ></td>

                        </tr>
                        <tr>
                          <td colspan="7" align="right">Paid Amount *</td>
                          <td colspan="2" ><input type="text" class="form-control" name="pAmount" id="pAmount" onkeyup="calculate_remain()" required value="<?php echo $purchase['pAmount']; ?>" onkeypress="return isNumberKey(event)" ></td>
               
                        </tr>
                        <tr>
                          <td colspan="7" align="right">Remain Amount *</td>
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
                      <input type="text" class="form-control" name="note" value="<?php echo $purchase['note']; ?>" >
                    </div>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-12" style="margin-top:20px; text-align: center;">
                    <button type="submit" class="btn btn-info"><i class="far fa-save"></i>&nbsp;&nbsp;Update</button>
                    <a href="<?php echo site_url('Purchase') ?>" class="btn btn-danger" ><i class="fa fa-arrow-left" ></i>&nbsp;&nbsp;Back</a>
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
    
    <script type="text/javascript" >
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
                  $(".select2").select2();
                }
              });
            } else {
              $('#product').html('<option value="">Select One</option>');
            }
          });
        });
    </script>
    
  <script type="text/javascript">
    $(document).ready(function(){
      $('#product').change(function(){    
        var id = $('#product').val();
          //alert(id);
        var base_url = '<?php echo base_url() ?>'+'Purchase/get_product/'+id;
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
    function getTotal(id)
      {        
      var tp = $('#pprice_'+id).val();
      var quantity = $('#quantity_'+id).val();
      var igst = $('#igst_'+id).val();
        // alert(tp);alert(quantity);
      var upa = ((tp*igst)/100);
      var apa = (tp-upa);
      var tprice = (quantity*apa);
      
      var tax = (quantity*upa);
      
      $('#tprice_' + id).val(parseFloat(tprice).toFixed(2));
      $('#tax_' + id).val(parseFloat(tax).toFixed(2));
      
      calculatePrice();
      }
 
    function calculatePrice()
      {
      var sum=0;
      $(".tprice").each(function()
        {
        sum += parseFloat($(this).val());
        });
      
      var s2um=0;
      $(".taxamt").each(function()
        {
        s2um += parseFloat($(this).val());
        });
        
      $('#tAmount').val(parseFloat(sum).toFixed(2));
      $('#dAmount').val(parseFloat(sum).toFixed(2));
      
      $('#vAmount').val(parseFloat(s2um).toFixed(2));
      }

    function calculate_remain()
      {
      var total = $('#tAmount').val();
      var paid = $('#pAmount').val();
      var vat = $('#vAmount').val();

      var tpa = + total + +vat;
      
      var due = total - paid;
      $('#dAmount').val(parseFloat(due).toFixed(2));
      }
  </script>

  <script type="text/javascript">
    $(document).ready(function(){
      var value = $("#accountType").val();
      $('#accountNo').empty();
      getAccountNo(value, '#accountNo');
      $('#accountNo').val("<?php echo $purchase['accountNo'] ?>");
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