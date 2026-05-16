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
                <form method="POST" action="<?php echo site_url("Purchase/saved_purchase") ?>">
                  <div class="col-md-12 col-sm-12 col-12">
                    <div class="row">
                      <div class="form-group col-md-3 col-sm-3 col-xs-12">
                        <label>Purchase Date *</label>
                        <input type="text" name="date" class="form-control datepicker" value="<?php echo date('m/d/Y') ?>" required >
                      </div>
                      
                      <!--<div class="form-group col-md-3 col-sm-3 col-12">-->
                      <!--  <label>Select Supplier *</label>-->
                      <!--  <select class="form-control select2" name="supplier" id="supplier" required >-->
                      <!--    <option value="">Select One</option>-->
                      <!--    <option value="cust">New Supplier</option>-->
                      <!--    <?php foreach($supplier as $value){ ?>-->
                      <!--    <option value="<?php echo $value['supid']; ?>"><?php echo $value['supName']; ?></option>-->
                      <!--    <?php } ?>-->
                      <!--  </select>-->
                      <!--</div>-->
                      
                      
                     <div class="form-group col-md-3 col-sm-3 col-12">
                        <label>Select Supplier *</label>
                        <div class="input-group input-group-sm">
                          <select name="supplier" id="supplier" class="form-control select2" required >
                          </select>
                          <span class="input-group-append">
                            <button type="button" class="btn btn-danger btn-sm newSupplier" data-toggle="modal" data-target=".bs-example-modal-newSupplier" ><i class="fa fa-plus"></i></button>
                          </span>
                        </div>
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
                      <!--<div class="form-group col-md-4 col-sm-4 col-12">-->
                      <!--  <label>Select Product *</label>-->
                      <!--  <select class="form-control select2" id="product" >-->
                      <!--    <option value="">Select One</option>-->
                      <!--    <?php foreach($product as $value){ ?>-->
                      <!--    <option value="<?php echo $value['pid']; ?>"><?php echo $value['pName'].' ( '.$value['partNo'].' )'; ?></option>-->
                      <!--    <?php } ?>-->
                      <!--  </select>-->
                      <!--</div>-->
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
                        <tbody id="mtable">

                        </tbody>
                        <tbody>
                          <tr>
                            <td colspan="7" align="right">Total Amount *</td>
                            <td colspan="2" ><input type="text" class="form-control" name="tAmount" id="tAmount" value="0" required readonly ></td>
                          </tr>
                          <tr>
                            <td colspan="7" align="right">Tax Amount *</td>
                            <td colspan="2" ><input type="text" class="form-control" name="vAmount" id="vAmount" onkeypress="return isNumberKey(event)" onkeyup="calculate_remain()" value="0" required readonly ></td>
                          </tr>
                          <tr>
                            <td colspan="7" align="right">Paid Amount *</td>
                            <td colspan="2" ><input type="text" class="form-control" name="pAmount" id="pAmount" onkeypress="return isNumberKey(event)" onkeyup="calculate_remain()" value="0" required ></td>
                          </tr>
                          <tr>
                            <td colspan="7" align="right">Due Amount *</td>
                            <td colspan="2" ><input type="text" class="form-control" name="dAmount" id="dAmount" readonly ></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="row">
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Payment Method *</label>
                        <select class="form-control" name="accountType" id="accountType" required >
                          <option value="Cash">Cash</option>
                          <option value="Bank">Bank</option>
                          <option value="Mobile">Mobile</option>
                        </select>
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Account Number *</label>
                        <select name="accountNo" id="accountNo" class="form-control" required >
                          <option value="">Select Payment Method First</option>
                        </select>
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Note</label>
                        <input type="text" class="form-control" name="note" placeholder="If have any note">
                      </div>
                    </div>             
                    <div class="form-group col-md-12 col-sm-12 col-12" style="margin-top:20px; text-align: center;">
                      <button type="submit" class="btn btn-info"><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
                      <a href="<?php echo site_url('Purchase') ?>" class="btn btn-danger" ><i class="fa fa-arrow-left" ></i>&nbsp;&nbsp;Back</a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  
  
  
  
      <?php
    $q2uery = $this->db->select('supid')
                  ->from('suppliers')
                  ->limit(1)
                  ->order_by('supid','DESC')
                  ->get()
                  ->row();
    if($q2uery)
      {
      $s2n = $q2uery->supid+1;
      }
    else
      {
      $s2n = 1;
      }

    $c2n = strtoupper(substr($_SESSION['compname'],0,3));
    $p2c = sprintf("%'05d", $s2n);

    $c2usid = 'S-'.$c2n.$p2c;
    ?>
    <div id="supplier_add" class="modal fade bs-example-modal-newSupplier" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Supplier Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form method="POST" action="#">
            <div class="col-md-12 col-sm-12 col-12">
              <div class="row">

                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Supplier Name *</label>
                  <input type="text" class="form-control" id="supName" placeholder="Supplier Name" required >
                </div>
                
				
				<div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Supplier Mobile *</label>
                  <input type="text" class="form-control" id="supMobile" placeholder="Mobile Number" onkeypress="return isNumberKey(event)" required minlength="11" >
                </div>
                
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Supplier Company</label>
                  <input type="text" class="form-control" id="supCompany" placeholder="Supplier Company" >
                </div>
                
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Supplier Email</label>
                  <input type="email" class="form-control" id="supEmail" placeholder="example@sunshine.com" >
                </div>
                
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Supplier Address</label>
                  <input type="text" class="form-control" id="supAddress" placeholder="Address" >
                </div>
                
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Advance Amount</label>
                  <input type="text" class="form-control" id="balance" placeholder="Advance Amount" >
                </div>
                
              </div>
            </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="pbsubmit" ><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
          </div>
          </form>
        </div>
      </div>
    </div>
    
    

<?php $this->load->view('footer/footer'); ?>





    <script type="text/javascript" >
      $(function(){
        load_suppliers();
        function load_suppliers(){
          var url = "<?php echo base_url()?>Purchase/get_purchase_supplier";
          //alert(url);
          $.ajax({
            type:'POST',
            url: url,       
            dataType: 'json',
            success:function(data){ 
            //alert(data);
              var HTML = "<option value=''>Select One</option>";
              for (var key in data) 
                {
                if(data.hasOwnProperty(key))
                  {
                  HTML +="<option value='"+data[key]["supid"]+"'>" + data[key]["supName"]+' ( '+ data[key]["supCompany"]+' )'+"</option>";
                  }
                }
              $("#supplier").html(HTML);
               $('.modal-backdrop').remove();
              },
            error:function(data){
               alert('error');
              }
            });
          }

        $("#pbsubmit").click(function(){
        
          var supName = $("#supName").val();
          var supCompany = $("#supCompany").val();
          var supMobile = $("#supMobile").val();
          var supEmail = $("#supEmail").val();
          var supAddress = $("#supAddress").val();
          var balance = $("#balance").val();
          var dataString = '&supName='+ supName + '&supCompany='+ supCompany + '&supMobile='+ supMobile + '&supEmail='+ supEmail + '&supAddress='+ balance + '&balance='+ supAddress;
          // AJAX Code To Submit Form.
          $.ajax({
            type: "POST",
            url: "<?php echo site_url('Supplier/add_supplier') ?>",
            data: dataString,
            cache: false,
            success: function(result){
              //alert(result);
              load_suppliers();
              $('#supplier_add').remove();
              $('.modal-backdrop').remove();
              }
            });
          return false;
        });
      });
    </script>
    
    
    
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
          $('#accountNo').val(1);
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
  
    <script type="text/javascript">
      $(document).ready(function(){
        $('#supplier').change(function(){
          var id = $('#supplier').val();
          var url = '<?php echo base_url(); ?>Purchase/get_supplier_amount';
            //alert(id); alert(url);
          $.ajax({
            type: 'POST',
            async: false,
            url: url,
            data:{"id":id},
            dataType: 'json',
            success: function(data){
                //alert(data);
              var HTML = data;
                //alert(HTML2);
              $("#aAmount").val(HTML);
              },
            error:function(){
              alert('error');
              }
            });
          });
        });
    </script>
    
    <script type="text/javascript" >
      $(function(){
        $('#supplier').on('change', function(){
          var id = $(this).val();
          if(id == 'cust')
            { 
            window.location = "/newSup"; 
            }
          return false;
          });
        });
    </script>
