<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Order</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Order</li>
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
                <h3 class="card-title">Order Information</h3>
              </div>

              <div class="card-body">
                <form method="POST" action="<?php echo site_url("Order/savle_sale_Order") ?>">
                  <input type="hidden" name="oid" value="<?php echo $quotation['oid']; ?>" required >
                  <div class="row">
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Order Date *</label>
                      <input type="text" name="date" class="form-control datepicker" value="<?php echo date('m/d/Y',strtotime($quotation['oDate'])) ?>" required >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Select Customer *</label>                        
                      <select class="form-control" name="customer" required >
                        <option value="">Select One</option>
                        <?php foreach($customer as $value){ ?>
                        <option <?php echo ($quotation['custid'] == $value['custid'])?'selected':''?> value="<?php echo $value['custid']; ?>"><?php echo $value['custName'].' ( '.$value['custCode'].' )'; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Select Product</label>                        
                      <select class="form-control chosen" id="products" >
                        <option value="">Select One</option>
                        <?php foreach($product as $value){ ?>
                        <option value="<?php echo $value['pid']; ?>"><?php echo $value['pName'].' ( '.$value['pCode'].' )'; ?></option>
                        <?php } ?>
                      </select>
                    </div>
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
                        <?php 
                        foreach($pquotation as $value){
                        $pid = $value['pid'];
                        ?>
                        <tr>
                          <td>
                            <?php echo $value['pName'].' ( '.$value['pCode'].' )'; ?>
                            <input type="hidden" name='product[]' value="<?php echo $value['pid']; ?>" required >
                          </td> 
                          <td>
                            <input type='text' class='form-control' name='quantity[]' id="quantity_<?php echo $value['pid']?>" onkeyup="getTotal('<?php echo $pid; ?>')" value="<?php echo $value['oQnt']; ?>" required >
                          </td>
                          <td>
                            <input type='text' class='form-control' name='sprice[]' id='sprice_<?php echo $pid?>' onkeyup='getTotal(<?php echo $value['pid']; ?>)' value='<?php echo $value['oPrice']; ?>' required >
                          </td>
                          <td>
                            <input type='text' class='form-control tprice' name='tprice[]' id='tprice_<?php echo $pid?>' value='<?php echo $value['tPrice']; ?>' required readonly >
                          </td>
                          <td>
                            <input type="button" class="btn btn-danger" value="Remove" onClick="$(this).parent().parent().remove();">
                          </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                  <div class="row" >
                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                      <label>Total Amount</label>                        
                      <input type="text" class="form-control" name="tAmount" id="tAmount" value="<?php echo $quotation['tAmount']; ?>" readonly >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Paid Amount *</label>                        
                      <input type="text" class="form-control" name="pAmount" id="pAmount" onkeyup="calculate_remain()" value="<?php echo $quotation['pAmount']; ?>" required >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Due Amount</label>                        
                      <input type="text" class="form-control" name="dAmount" id="dAmount" value="<?php echo $quotation['dAmount']; ?>" readonly >
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                      <label>Note</label>                        
                      <input type="text" class="form-control" value="<?php echo $quotation['note']; ?>" name="note" >
                    </div>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px; text-align: center;">
                    <button type="submit" class="btn btn-info"><i class="far fa-save"></i>&nbsp;&nbsp;Sale Order</button>
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

    <script type="text/javascript">
      $(document).ready(function(){
        $('#products').change(function(){        
          var id = $('#products').val();
          var base_url = '<?php echo base_url() ?>'+'Quotation/getProduct/' + id;
          // alert(id);alert(base_url);
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