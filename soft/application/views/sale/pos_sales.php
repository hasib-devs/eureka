<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar22'); ?>

  <div  class="basic-form-area mg-b-15" style="min-height: 550px;">
    <div class="container-fluid">
      <div class="row" style="margin: 15px 15px 0px 15px;">
        <?php
        $exception=$this->session->userdata('exception');
        if(isset($exception))
        {
        echo $exception;
        $this->session->unset_userdata('exception');
        } ?>
        
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
          <div class="sparkline8-list basic-res-b-30 shadow-reset">
            <div class="sparkline8-hd">
           
            </div>
            <div class="sparkline8-graph">
              <div class="basic-login-form-ad">
                <div class="row">
                  <div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="basic-login-inner">
                      <form method="POST" action="<?php echo site_url('Sale/save_pos_sale') ?>" enctype="multipart/form-data" >
                        <div class="row">
                          <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label>CUSTOMER *</label>
                            <select class="form-control select2" name="customer" required >
                                <option value="">Select One</option>
                                <?php foreach($customer as $value){ ?>
                                <option value="<?php echo $value['custid']; ?>"><?php echo $value['custName'].' ( '.$value['custCode'].' )'; ?></option>
                                <?php } ?>
                            </select>
                          </div>
                          <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label>Select Products *</label>
                            <input type="text" id="productID" class="form-control" placeholder="Search Product Name / Code" list="pdlist"  autofocus >
                            <datalist id="pdlist" >
                              <?php foreach($product as $value){ ?>
                              <option value="<?php echo $value['pCode']; ?>"><?php echo $value['pName'].' ( '.$value['pCode'].' )'; ?></option>
                              <?php } ?>
                            </datalist>
                          </div> 
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <table id="mtable" style="margin-top:20px;" class="table">
                            <thead>
                              <tr style="background: #000; color: #fff;">
                                <th style="width: 50%;">ITEM</th>
                                <th style="width: 15%;">QTY</th>           
                                <th style="width: 15%;">RATE</th>
                                <th style="width: 15%;">AMOUNT</th>                      
                                <th style="width: 10%;">#</th>
                              </tr>
                            </thead>
                            <tbody id="tbody">

                            </tbody>
                          </table>
                        </div>

                        <div class="row">
                          <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label>VAT & Tax (%) (+) </label>
                            <input type="hidden" id="tAmount" value="0" >
                            <input type="text" class="form-control" name="vCost" id="vCost" onkeyup="vatcostcalculator()" value="0" >
                            <input type="hidden" class="form-control" name="vType" id="vType" value="0" >
                            <input type="hidden" class="form-control" name="vAmount" id="vAmount" value="0" >
                          </div>
                          <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label>Discount (-)</label>                        
                            <input type="text" class="form-control" name="discount" id="discount" onkeyup="discountType()" value="0" >
                            <input type="hidden" class="form-control" id="disType" name="disType" value="0" >
                            <input type="hidden" class="form-control" id="disAmount" name="disAmount" value="0" >
                          </div>
                          <div class="form-group col-md-4 col-sm-4 col-xs-12">
                            <label>TOTAL*</label>                        
                            <input type="text" class="form-control" name="nAmount" id="nAmount" placeholder="Total Amount" required readonly >
                          </div>
                          <div class="form-group col-md-4 col-sm-4 col-xs-12">
                            <label style="color: green;">PAID*</label>                        
                            <input type="text" class="form-control" name="pAmount" id="pAmount" onkeyup="duecalculator()" placeholder="Paid Amount" required >
                          </div>
                          <div class="form-group col-md-4 col-sm-4 col-xs-12">
                            <label style="color: red;">DUE</label>                        
                            <input type="text" class="form-control" name="dAmount" id="dAmount" placeholder="Due Amount" value="0" required readonly >
                          </div>
                          <div class="form-group col-md-6 col-sm-6 col-12">
                          <label>PAYMENT MODE *</label>
                          <select class="form-control" name="accountType" id="accountType" required >
                            <option value="Cash">Cash</option>
                            <option value="Bank">Bank</option>
                            <option value="Mobile">Mobile</option>
                          </select>
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-12">
                          <label>ACCOUNT *</label>
                          <select class="form-control" name="accountNo" id="accountNo" required >
                            <option value="">Select Account Type First</option>
                          </select>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                          <label>Notes</label>                        
                            <input type="text" class="form-control" name="note" placeholder="If have any notes" >
                        </div>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12" style="text-align: center; margin-top: 20px;">
                          <button type="submit" class="btn btn-success form-control" ><i class="fa fa-floppy-o" ></i> PAY NOW</button>
                          <a class="btn btn-danger form-control mt-2" href="<?php echo site_url(); ?>posSales" ><i class="fa fa-trash"></i> CANCEL</a>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div style="background: #F5F5F5;" class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
          <div class="sparkline8-list basic-res-b-30 shadow-reset">
            <div class="sparkline8-graph">
              <div class="basic-login-form-ad">
                <div class="row">
                  <?php $i = 0; foreach($product as $value){ $i++; ?>
                  <div  class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="background: white; text-align: center;">
                    <button class="btn btn-link" value="<?php echo $value['pCode']; ?>" id="productcode_<?php echo $value['pid']; ?>" onclick="pos_product_add(<?php echo $value['pid']; ?>)" title="<?php echo $value['pName']; ?>" >
                      <?php if($value['image'] == null){ ?>
                      <img src="<?php echo base_url().'assets/product.png'; ?>" style=" width: 50%;" >
                      <?php } else{ ?>
                      <img src="<?php echo base_url().'upload/product/'.$value['image']; ?>" style="width: 100%; height: 60px;" >
                      <?php } ?>
                      <p align="left" style="color: black; font-size: 13px; font-weight: bold; text-align: center;"><?php echo $value['pName'].' ( '.$value['pCode'].' )'; ?></p>
                    </button>
                  </div>
                  <?php if($i%4 == 0) { ?>
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
                  <?php } ?>
                  <?php } ?>
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
      $(document).ready(function(){
        $('#productID').on('keyup',function(){
          var id = $('#productID').val();
          var url = '<?php echo base_url() ?>'+'Sale/get_pos_sale_details/';
          //$('#productID').val('');
            //alert(id); alert(url); 
          $.ajax({
            type: 'POST',
            async: false,
            url: url,
            data:{'id' : id},
            dataType: 'text',
            success: function (data){
            //alert(data); 
              var jsondata = JSON.parse(data);                          
              $('#mtable tbody').append(jsondata);
              $('#productID').val('');
              calculatePrice();
              }
            });
          });
        });
    </script>
  
    <script type="text/javascript" >
      function pos_product_add(id){
        var id = $('#productcode_'+id).val();
        var url = '<?php echo base_url() ?>'+'Sale/get_pos_sale_details/';
        // console.log(url);
        // $('#productID').val('');
        //alert(id); alert(url);
        $.ajax({
          type: 'POST',
          async: false,
          url: url,
          data:{'id' : id},
          dataType: 'text',
          success: function (data){
            // alert(data); 
            var jsondata = JSON.parse(data);   
            $('#mtable tbody').append(jsondata);
            //$('#productID').val('');
            calculatePrice();
            }
          });
        }
    </script>
  
    <script type="text/javascript" >
      function deleteProduct(o) {
        var p=o.parentNode.parentNode;
        p.parentNode.removeChild(p);
         
        calculatePrice();
        }
    </script>

    <script type="text/javascript">
      function getTotal(id)
        {
        var pices = $('#quantity_'+id).val();
        var salePrice = $('#tp_'+id).val();

        var totalPrice = (parseFloat(salePrice).toFixed(2)*pices);
        $('#totalPrice_'+id).val(parseFloat(totalPrice).toFixed(2));
        
        calculatePrice();
        }
        
      function calculatePrice(){
        var totalPrice = Number(0),pruchaseCost;
        $("input[name='tPrice[]']").each(function () {
          totalPrice = Number(parseFloat(totalPrice) + parseFloat($(this).val()));
          });
        //alert(totalPrice);
        $('#tAmount').val(totalPrice.toFixed(2));
        $('#nAmount').val(totalPrice.toFixed(2));
        $('#pAmount').val(totalPrice.toFixed(2));
        }
    </script>
    
    <script type="text/javascript">
      function vatcostcalculator(){
        var vat = $('#vCost').val();
        var total = $('#tAmount').val();
        var discc = vat.slice(-1);
        var disca = vat.substring(0, vat.length - 1);
        //alert(discc);
        $('#vType').val(discc);
        
        if(discc == '%')
          {
          var da = parseFloat(total).toFixed(2)*parseFloat(disca).toFixed(2);
          var dat = parseFloat(da).toFixed(2)/100;
            //alert(da);alert(dat);
          //var remaining = parseFloat(total).toFixed(2)-parseFloat(dat).toFixed(2);
            
          $('#vAmount').val(dat);
          }
        else
          {
          var remaining = parseFloat(total).toFixed(2)-parseFloat(vat).toFixed(2);
          $('#vAmount').val(vat);
          }
            //alert(remaining);
        shippingCost();
        }
    </script>
    
    <script type="text/javascript">
      function discountType(){
        var disc = $('#discount').val();
        var total = $('#tAmount').val();
        var discc = disc.slice(-1);
        var disca = disc.substring(0, disc.length - 1);
            //alert(discc);
        $('#disType').val(discc);
        
        if(discc == '%')
          {
          var da = parseFloat(total).toFixed(2)*parseFloat(disca).toFixed(2);
          var dat = parseFloat(da).toFixed(2)/100;
            //alert(da);alert(dat);
          var remaining = parseFloat(total).toFixed(2)-parseFloat(dat).toFixed(2);
            
          $('#disAmount').val(dat);
          }
        else
          {
          var remaining = parseFloat(total).toFixed(2)-parseFloat(disc).toFixed(2);
          $('#disAmount').val(disc);
          }
          //alert(remaining);
        shippingCost();
        }
    </script>
    
    <script type="text/javascript">
      function shippingCost(){
        var vat = $('#vAmount').val();
        var disc = $('#disAmount').val();
        var total = $('#tAmount').val();
        
        var due = ((+total + +vat)-disc);
            
        $('#nAmount').val(parseFloat(due).toFixed(2));
        $('#pAmount').val(parseFloat(due).toFixed(2));
        }
        
      function duecalculator(){
        var disc = $('#pAmount').val();
        var total = $('#nAmount').val();
        
        var remaining = parseFloat(total).toFixed(2)-parseFloat(disc).toFixed(2);
            
        $('#dAmount').val(parseFloat(remaining).toFixed(2));
        }
    </script>
    
    <script type="text/javascript">
      $(function(){
        $("#pos_sales_submit").submit(function(){
        dataString = $("#pos_sales_submit").serialize();
          //alert("hello");
          $.ajax({
            type: "POST",
            url: "<?php echo site_url('Sale/save_pos_sale') ?>",
            data: dataString,
            success: function(data){
              //alert(result);
              printDiv('print');
              location.reload();
              }
            });
          return false;
        });
      });
    </script>
    
    <script type="text/javascript" >
      (function(){
        var textField = document.getElementById('productID');
   
        if(textField){
          textField.addEventListener('keydown', function(mozEvent){
            var event = window.event || mozEvent;
            if(event.keyCode === 13){
              event.preventDefault();
              }
            });
          }
        })();
    </script>
    
    <script type="text/javascript">
      $(document).ready(function(){
        var value = $("#accountType").val();
        $('#accountNo').val(1);
        getAccountNo(value, '#accountNo');
        //$('#accountNo').val(1);
        });
    
      $('#accountType').on('change',function(){
        var value = $(this).val();
        $('#accountNo').empty();
        getAccountNo(value, '#accountNo');
        });
        
        function getAccountNo(value,place){
          $(place).empty();
          if(value != ''){
              //alert(value);
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