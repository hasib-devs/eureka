<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Customer</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Customer</li>
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
                <h3 class="card-title">Customer Information</h3>
              </div>
              <?php 
                $link = '#customer_add';
              ?>

              <div class="card-body">
                <form method="post" action="<?php echo base_url('Customer/save_customer'); ?>" enctype="multipart/form-data" >
            <div class="col-md-12 col-sm-12 col-12">
              <div class="row">
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Name *</label>
                  <input type="text" class="form-control" name="custName" placeholder="Customer Name" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Father's Name *</label>
                  <input type="text" class="form-control" name="custfName" placeholder="Father's Name" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Mother's Name </label>
                  <input type="text" class="form-control" name="custmName" placeholder="Mother's Name" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Spouse Name </label>
                  <input type="text" class="form-control" name="spouse" placeholder="Spouse Name" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Contact Number *</label>
                  <input type="text" class="form-control" name="custMobile" placeholder="Mobile Number" onkeypress="return isNumberKey(event)" maxlength="11" minlength="11" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Email</label>
                  <input type="email" class="form-control" name="custEmail" placeholder="example@sunshine.com" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Present Address *</label>
                  <input type="text" class="form-control" name="custAddress" placeholder="Present Address" required >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Permanent Address</label>
                  <input type="text" class="form-control" name="custpAddress" placeholder="Permanent Address" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label> BRTA Office </label>
                  <input type="text" class="form-control" name="brtaDis" placeholder="BRTA Office" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Gender</label>
                  <select class="form-control" name="custGender" >
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Others">Others</option>
                  </select>
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Date of Birth</label>
                  <input type="text" name="custDob" class="form-control datepicker" value="<?php echo date('m/d/Y') ?>" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Nationality </label>
                  <input type="text" name="custNation" class="form-control" placeholder="Nationality" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>NID Number</label>
                  <input type="text" class="form-control" name="custNid" placeholder="NID Number" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>NID Image</label>
                  <input type="file" name="custNFiles" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Driving License Number</label>
                  <input type="text" class="form-control" name="custDriving" placeholder="Driving License Number"  >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Driving License Image</label>
                  <input type="file" name="custDFiles"  >
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary"><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
              <a href="<?php echo site_url('newSale'); ?>" class="btn btn-danger" ><i class="fa fa-arrow-left" ></i>&nbsp;&nbsp;Back</a>
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
        var url = '<?php echo base_url() ?>'+'Sale/get_product_details/'+id;
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