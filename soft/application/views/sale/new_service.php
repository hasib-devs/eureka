<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sale Service</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Sale Service</li>
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
                <h3 class="card-title">Sale Service Information</h3>
              </div>
              <?php 
                $link = '#customer_add';
              ?>

              <div class="card-body">
                <form method="POST" action="<?php echo base_url() ?>Sale/saved_sale_service" enctype='multipart/form-data' >
                  <div class="row">
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Service Date *</label>
                      <input type="text" name="date" class="form-control datepicker" value="<?php echo date('m/d/Y') ?>" required >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Select Customer *</label>
                      <select class="form-control select2" name="customer" id="customer" required >
                        <option value="">Select One</option>
                        <option value="cust">New Customer</option>
                        <?php foreach($customer as $value){ ?>
                        <option value="<?php echo $value['custid']; ?>"><?php echo $value['custName']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Select Service *</label>
                      <select class="form-control select2" id="product" >
                        <option value="">Select One</option>
                        <?php foreach($service as $value){ ?>
                        <option value="<?php echo $value['sid']; ?>"><?php echo $value['sName'].' ( '.$value['sCode'].' )'; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-12" >
                    <table id="mtable" class="table table-bordered table-striped">
                      <thead class="btn-default">
                        <tr>
                          <th>Service</th>
                          <th>Quantity</th>
                          <th>Price</th>
                          <th>Total</th> 
                          <th>Action</th>                       
                        </tr>
                      </thead>
                      <tbody id="tbody">

                      </tbody>
                      <tbody>
                        <tr>
                          <td colspan="3" align="right">Total Amount</td>
                          <td>
                            <input type="text" class="form-control" name="tAmount" id="tAmount" required readonly >
                          </td>
                          <td></td>
                        </tr>
                        <tr>
                          <td colspan="3" align="right">Discount Amount</td>
                          <td>
                            <input type="text" class="form-control" name="discount" id="discount" placeholder="Amount" onkeyup="calculate_remain()" onkeypress="return isNumberKey(event)" value="0" >
                          </td>
                          <td></td>
                        </tr>
                        <tr>
                          <td colspan="3" align="right">VAT Amount</td>
                          <td>
                            <input type="text" class="form-control" name="salevat" id="salevat" placeholder="Amount" onkeyup="calculate_remain()" onkeypress="return isNumberKey(event)" value="0" >
                          </td>
                          <td></td>
                        </tr>
                        <tr>
                          <td colspan="3" align="right">Net Amount *</td>
                          <td><input type="text" class="form-control" name="nAmount" id="nAmount" required readonly ></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td colspan="3" align="right">Paid Amount *</td>
                          <td><input type="text" class="form-control" name="pAmount" id="pAmount" onkeyup="calculate_remain()" onkeypress="return isNumberKey(event)" value="0" required ></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td colspan="3" align="right">Due Amount</td>
                          <td><input type="text" class="form-control" name="dAmount" id="dAmount" readonly ></td>
                          <td></td>
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
                      <label>Account No *</label>
                      <select class="form-control" name="accountNo" id="accountNo" >
                        <option value="">Select Payment Method First</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Note</label>
                      <textarea type="text" class="form-control" name="note" placeholder="If have any note" rows="4" ></textarea>
                    </div>
                  </div>
                    
                  <div class="form-group col-md-12 col-sm-12 col-xs-12" style="margin-top:20px; text-align: center;">
                    <button type="submit" class="btn btn-info"><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
                    <a href="<?php echo site_url('servlist'); ?>" class="btn btn-danger" ><i class="fa fa-arrow-left" ></i>&nbsp;&nbsp;Back</a>
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
      $('#product').change(function(){
        var id = $('#product').val();
        var url = '<?php echo base_url() ?>'+'Sale/get_service_details/'+id;
          //alert(id); alert(url);
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
        $('#nAmount').val(parseFloat(sum).toFixed(2));
        $('#dAmount').val(parseFloat(sum).toFixed(2));
        }

      function calculate_remain(){
        var total = $('#tAmount').val();
        var dis = $('#discount').val();
        var vat = $('#salevat').val();
        var paid = $('#pAmount').val();
        
        var tsa = +total + +vat;
        var tpa = +paid + +dis;

        var tna = tsa - dis;
        var due = tsa - tpa;
        
        $('#nAmount').val(parseFloat(tna).toFixed(2));
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
    
    <script type="text/javascript" >
      $(function(){
        $('#customer').on('change', function(){
          var id = $(this).val();
          if(id == 'cust')
            { 
            window.location = "/newCustomer"; 
            }
          return false;
          });
        });
    </script>