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
                <form method="POST" action="<?php echo site_url("Lcmanagement/saved_lc_management") ?>">
                  <div class="col-md-12 col-sm-12 col-12">
                    <div class="row">
                      <div class="form-group col-md-4 col-sm-4 col-xs-12">
                        <label>LC Date *</label>
                        <input type="text" name="date" class="form-control datepicker" value="<?php echo date('m/d/Y') ?>" required >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Select Supplier *</label>
                        <select class="form-control select2" name="supplier" required >
                          <option value="">Select One</option>
                          <?php foreach($supplier as $value){ ?>
                          <option value="<?php echo $value['supid']; ?>"><?php echo $value['supName']; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Select Product *</label>
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
                        <tbody id="mtable">

                        </tbody>
                        <tbody>
                          <tr>
                            <td colspan="6" align="right">Total Amount *</td>
                            <td colspan="2" ><input type="text" class="form-control" name="tAmount" id="tAmount" value="0" required readonly ></td>
                          </tr>
                          <tr>
                            <td colspan="6" align="right">Paid Amount *</td>
                            <td colspan="2" ><input type="text" class="form-control" name="pAmount" id="pAmount" onkeypress="return isNumberKey(event)" onkeyup="calculate_remain()" value="0" required ></td>
                          </tr>
                          <tr>
                            <td colspan="6" align="right">Due Amount *</td>
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
                      <a href="<?php echo site_url() ?>Lcmanagement" class="btn btn-danger" ><i class="fa fa-arrow-left" ></i>&nbsp;&nbsp;Back</a>
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

    function calculate_remain()
      {
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
  
