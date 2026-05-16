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
                <h3 class="card-title">Sale Information</h3>
              </div>
              <?php 
                $link = '#customer_add';
              ?>
              <div class="card-body">
                <form method="POST" action="<?php echo base_url() ?>Sale/saved_sale" enctype='multipart/form-data' >
                  <div class="row sticky-top">
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Sale Date *</label>
                      <input type="text" name="date" class="form-control datepicker" value="<?php echo date('m/d/Y') ?>" required >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Select Customer *</label>
                      <div class="input-group input-group-sm">
                        <select name="customer" id="customer" class="form-control select2" required >
                          <option value="">Select One</option>
                        </select>
                        <span class="input-group-append">
                          <button type="button" class="btn btn-danger btn-sm customer_add" data-toggle="modal" data-target=".bs-example-modal-customer_add" ><i class="fa fa-plus"></i></button>
                        </span>
                      </div>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Sales Type</label>
                      <select class="form-control" name="saType" id="saType" required >
                        <option value="1">Retail Sales</option>
                        <option value="2">Wholesale</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Product Category</label>
                      <select class="form-control select2" name="category" id="category">
                        <option value="">Select One</option>
                        <?php foreach($category as $value){ ?>
                        <option value="<?php echo $value['catid']; ?>"><?php echo $value['catName']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
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

                  <div class="col-md-12 col-sm-12 col-12" >
                    <table id="mtable" class="table table-bordered table-striped">
                      <thead class="btn-default" >
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
                      <tbody id="tbody" >

                      </tbody>
                      <tbody>
                        <tr>
                          <td colspan="11" align="right">Total Amount</td>
                          <td colspan="2" >
                            <input type="text" class="form-control" name="tAmount" id="tAmount" required readonly >
                          </td>
                        </tr>
                        <tr>
                          <td colspan="11" align="right">Discount Amount</td>
                          <td colspan="2" >
                            <input type="text" class="form-control" name="discount" id="discount" placeholder="Amount" onkeyup="calculate_remain()" onkeypress="return isNumberKey(event)" value="0" >
                          </td>
                        </tr>
                        <tr>
                          <td colspan="11" align="right">VAT Amount</td>
                          <td colspan="2" >
                            <input type="text" class="form-control" name="salevat" id="salevat" placeholder="Amount" onkeyup="calculate_remain()" onkeypress="return isNumberKey(event)" value="0" >
                          </td>
                        </tr>
                        <tr>
                          <td colspan="11" align="right">Shipping Charge Amount</td>
                          <td colspan="2" >
                            <input type="text" class="form-control" name="charge" id="charge" placeholder="Amount" onkeyup="calculate_remain()" onkeypress="return isNumberKey(event)" value="0" >
                          </td>
                        </tr>
                        <tr>
                          <td colspan="11" align="right">Net Amount *</td>
                          <td colspan="2" ><input type="text" class="form-control" name="nAmount" id="nAmount" required readonly ></td>
                        </tr>
                        <tr>
                          <td colspan="11" align="right">Paid Amount *</td>
                          <td colspan="2" ><input type="text" class="form-control" name="pAmount" id="pAmount" onkeyup="calculate_remain()" onkeypress="return isNumberKey(event)" value="0" required ></td>
                        </tr>
                        <tr>
                          <td colspan="11" align="right">Due Amount</td>
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
                        <option value="Courier">Courier</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Account No *</label>
                      <select class="form-control" name="accountNo" id="accountNo" >
                        <option value="">Select Payment Method First</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Select Courier</label>
                      <select class="form-control select2" name="id" id="id"  >
                        <option value="0">Select One</option>
                        <?php foreach($courier as $value){ ?>
                        <option value="<?php echo $value['id']; ?>"><?php echo $value['courierName']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Select Courier Employee</label>
                      <select class="form-control select2" name="cempid"  >
                        <option value="0">Select One</option>
                        <?php foreach($employees as $value){ ?>
                        <option value="<?php echo $value['empid']; ?>"><?php echo $value['empName']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Note</label>
                      <textarea type="text" class="form-control" name="note" placeholder="If have any note" rows="4" ></textarea>
                    </div>
                    
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Comment</label>
                      <textarea type="text" class="form-control" name="comment" placeholder="If have any comment" rows="4" ></textarea>
                    </div>
                    
                    
                  </div>
                    
                  <div class="form-group col-md-12 col-sm-12 col-xs-12" style="margin-top:20px; text-align: center;">
                    <button type="submit" class="btn btn-info"><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
                    <a href="<?php echo site_url('Sale'); ?>" class="btn btn-danger" ><i class="fa fa-arrow-left" ></i>&nbsp;&nbsp;Back</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  
    <div id=customer_add class="modal fade bs-example-modal-customer_add" tabindex="-1" role="dialog" aria-hidden="true">
       <div class="modal-dialog modal-md">
        <div class="modal-content" >
          <div class="modal-header">
            <h4 class="modal-title">Customer Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form method="post" action="#" enctype="multipart/form-data" >
            <div class="col-md-12 col-sm-12 col-12">
              <div class="row">
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Name *</label>
                  <input type="text" class="form-control" name="custName" id="custName" placeholder="Customer Name" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Father's Name</label>
                  <input type="text" class="form-control" name="custfName" id="custfName" placeholder="Father's Name">
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Mother's Name </label>
                  <input type="text" class="form-control" name="custmName" id="custmName" placeholder="Mother's Name" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Spouse Name </label>
                  <input type="text" class="form-control" name="spouse" id="spouse" placeholder="Spouse Name" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Contact Number</label>
                  <input type="text" class="form-control" name="custMobile" id="custMobile" placeholder="Mobile Number" onkeypress="return isNumberKey(event)" minlength="11">
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Email</label>
                  <input type="email" class="form-control" name="custEmail" id="custEmail" placeholder="example@sunshine.com" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Present Address</label>
                  <input type="text" class="form-control" name="custAddress" id="custAddress" placeholder="Present Address">
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Permanent Address</label>
                  <input type="text" class="form-control" name="custpAddress" id="custpAddress" placeholder="Permanent Address" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Gender</label>
                  <select class="form-control" name="custGender" id="custGender" >
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Others">Others</option>
                  </select>
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Date of Birth</label>
                  <input type="text" name="custDob" class="form-control datepicker" id="custDob" value="<?php echo date('m/d/Y') ?>" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Nationality </label> 
                  <input type="text" name="custNation" class="form-control" id="custNation" placeholder="Nationality" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>NID Number</label>
                  <input type="text" class="form-control" name="custNid" id="custNid" placeholder="NID Number" >
                </div>
                <!--<div class="form-group col-md-6 col-sm-6 col-12">-->
                <!--  <label>NID Image</label>-->
                <!--  <input type="file" name="custNFiles" id="custNFiles" >-->
                <!--</div>-->
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Driving License Number</label>
                  <input type="text" class="form-control" name="custDriving" id="custDriving" placeholder="Driving License Number">
                </div>
                <!--<div class="form-group col-md-6 col-sm-6 col-12">-->
                <!--  <label>Driving License Image</label>-->
                <!--  <input type="file" name="custDFiles" id="custDFiles" >-->
                <!--</div>-->
              </div>
            </div>
            <div class="modal-footer">
              <button id='pbsubmit' type="submit" class="btn btn-primary"><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
            </div>
          </form>
        </div>
      </div>
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
    
    <script type="text/javascript" >
        load_customers();
        function load_customers(){
          var url = "<?php echo base_url()?>Sale/get_sale_customer";
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
                if (data.hasOwnProperty(key))
                  {
                  HTML +="<option value='"+data[key]["custid"]+"'>" + data[key]["custName"]+' ( '+data[key]["custMobile"]+' )'+"</option>";
                  }
                }
              $("#customer").html(HTML);
              }
            });
          }
          
        $("#pbsubmit").click(function(){
          var custName = $("#custName").val();
          var custfName = $("#custfName").val();
          var custmName = $("#custmName").val();
          var spouse = $("#spouse").val();
          var custMobile = $("#custMobile").val();
          var custEmail = $("#custEmail").val();
          var custAddress = $("#custAddress").val();
          var custpAddress = $("#custpAddress").val();
          var custGender = $("#custGender").val();
          var custDob = $("#custDob").val();
          var custNation = $("#custNation").val();
          var custNid = $("#custNid").val();
          //var custNFiles = $("#custNFiles").val();
          var custDriving = $("#custDriving").val();
          //var custDFiles = $("#custDFiles").val();
          
          var dataString = 'custName='+ custName + '&custfName='+ custfName + '&custmName='+ custmName + '&spouse='+ spouse + '&custMobile='+ custMobile + '&custEmail='+ custEmail + '&custAddress='+ custAddress + '&custpAddress='+ custpAddress + '&custGender='+ custGender + '&custDob='+ custDob + '&custNation='+ custNation + '&custNid='+ custNid + '&custDriving='+ custDriving;
          //alert(dataString);
          $.ajax({
            type: "POST",
            url: "<?php echo site_url('Customer/add_customer') ?>",
            data: dataString,
            cache: false,
            success: function(result){
              //alert(result);
              load_customers();
              $(".card-body").css({ overflow:"auto !important;" });
              $('#customer_add').remove();
              $('.modal-backdrop').remove();
              
              window.location.reload();
              }
            });
          return false;
        });
      
    </script>
    
    <script type="text/javascript">
      $('#product').change(function(){
        var id = $('#product').val();
        var saType = $('#saType').val();
        var url = '<?php echo base_url() ?>'+'Sale/get_product_details/'+id+ '/' + saType;
          //alert(id);// exit();
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
              //  alert(dis); alert(salePrice);
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
        $('#dAmount').val(parseFloat(sum).toFixed(2));
        }

      function calculate_remain(){
        var total = $('#tAmount').val();
        var dis = $('#discount').val();
        var vat = $('#salevat').val();
        var tca = $('#charge').val();
        var paid = $('#pAmount').val();
        
        var tsa = +total + +vat + +tca;
        var tpa = +paid + +dis;

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
    
    
<!--    <script>-->

<!--  document.getElementById('customer').addEventListener('change', function () {-->
<!--    const selectedOption = this.options[this.selectedIndex];-->
<!--    const phone = selectedOption.getAttribute('data-phone');-->
<!--    document.getElementById('phone').value = phone;-->
<!--  });-->
<!--</script>-->
    
    