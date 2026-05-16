<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Pre-Order</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Pre-Order</li>
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
                <h3 class="card-title">Pre-Order Information</h3>
              </div>

              <div class="card-body">
                <form method="POST" action="<?php echo site_url("Order/save_order") ?>">
                  <div class="row">
                    <div class="form-group col-md-3 col-sm-3 col-12">
                      <label>Order Date *</label>
                      <input type="text" name="date" class="form-control datepicker" value="<?php echo date('m/d/Y') ?>" required >
                    </div>
                    <div class="form-group col-md-3 col-sm-3 col-12">
                      <label>Select Customer *</label>
                      <select class="form-control select2" name="customer" required >
                        <option value="">Select One</option>
                        <?php foreach($customer as $value){ ?>
                        <option value="<?php echo $value['custid']; ?>"><?php echo $value['custName'].' ( '.$value['custMobile'].' )'; ?></option>
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
                       <select class="form-control select2" id="product" >
                        <option value="">Select One</option>
                      </select>
                    </div>
                    <!--<div class="form-group col-md-4 col-sm-4 col-12">-->
                    <!--  <label>Select Product *</label>                        -->
                    <!--  <select class="form-control select2" id="products" >-->
                    <!--    <option value="">Select One</option>-->
                    <!--    <?php foreach($product as $value){ ?>-->
                    <!--    <option value="<?php echo $value['pid']; ?>"><?php echo $value['pName'].' ( '.$value['pCode'].' )'; ?></option>-->
                    <!--    <?php } ?>-->
                    <!--  </select>-->
                    <!--</div>-->
                  </div>

                    <div class="col-md-12 col-sm-12 col-12" >
                      <table id="mtable" class="table table-bordered table-striped">
                        <thead class="btn-default">
                          <tr>
                            <th>Product</th>
                            <th>Quantity</th>      
                            <th>Unit Price</th>
                            <th>Total Price</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody id="tbody">

                        </tbody>
                      </table>
                    </div>
                  
                  <div class="row">
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Total Price *</label>                        
                      <input type="text" class="form-control" name="tAmount" id="tAmount" required readonly >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Paid Amount *</label>                        
                      <input type="text" class="form-control" name="pAmount" onkeyup="calculate_remain()" id="pAmount" value="0" required >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Due Amount</label>                        
                      <input type="text" class="form-control" name="dAmount" id="dAmount" readonly >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Note</label>                        
                      <input type="text" class="form-control" name="note" placeholder="If have any note">
                    </div>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-12" style="margin-top:20px; text-align: center;">
                    <button type="submit" class="btn btn-info"><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
                    <a href="<?php echo site_url('Order') ?>" class="btn btn-danger" ><i class="fa fa-arrow-left" ></i>&nbsp;&nbsp;Back</a>
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
          var base_url = '<?php echo base_url() ?>'+'Order/getProduct/'+id;
           //alert(id); alert(base_url);
          $.ajax({
            type: 'GET',
            url: base_url,
            dataType: 'text',
            success: function(data){
              var jsondata = JSON.parse(data);                
              $('#tbody').append(jsondata);
              }
            });
          });
        });
    </script>

    <script type="text/javascript">
      function getTotal(id)
        {
        var tp = $('#sprice_' + id).val();
        var quantity = $('#quantity_' + id).val();
        
        var totalPrice = parseFloat(quantity) * parseFloat(tp);
        $('#tprice_' + id).val(parseFloat(totalPrice).toFixed(2));
        calculatePrice();
        }

      function calculatePrice()
        {
        var sum=0;
        $(".tprice").each(function()
            {
            sum += parseFloat($(this).val());
            });
        $('#tAmount').val(parseFloat(sum).toFixed(2));
        $('#dAmount').val(parseFloat(sum).toFixed(2));
        }
        
    function calculate_remain(){
        var paid = $('#pAmount').val();
        var total = $('#tAmount').val();
        
        var remaining = parseFloat(total).toFixed(2)-parseFloat(paid).toFixed(2);
        
        $('#dAmount').val(remaining);
        }
    </script>