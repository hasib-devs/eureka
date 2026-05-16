<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <!--<section class="content-header">-->
    <!--  <div class="container-fluid">-->
    <!--    <div class="row mb-2">-->
    <!--      <div class="col-sm-6">-->
    <!--        <h1>Quotation</h1>-->
    <!--      </div>-->
    <!--      <div class="col-sm-6">-->
    <!--        <ol class="breadcrumb float-sm-right">-->
    <!--          <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>-->
    <!--          <li class="breadcrumb-item active">Quotation</li>-->
    <!--        </ol>-->
    <!--      </div>-->
    <!--    </div>-->
    <!--  </div>-->
    <!--</section>-->

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Quotation Information</h3>
              </div>

              <div class="card-body">
                <form method="POST" action="<?php echo site_url("Quotation/save_quotation") ?>">
                  <div class="row sticky-top">
                    <div class="form-group col-md-3 col-sm-3 col-12">
                      <label>Quotation Date *</label>
                      <input type="text" name="date" class="form-control datepicker" value="<?php echo date('m/d/Y') ?>" required >
                    </div>
                    
                    <!--<div class="form-group col-md-4 col-sm-4 col-12">-->
                    <!--  <label>Select Customer *</label>-->
                    <!--  <select class="form-control" name="customer" required >-->
                    <!--    <option value=" ">Select One</option>-->
                    <!--    <?php foreach($customer as $value){ ?>-->
                    <!--    <option value="<?php echo $value['custid']; ?>"><?php echo $value['custName'].' ( '.$value['custCode'].' )'; ?></option>-->
                    <!--    <?php } ?>-->
                    <!--  </select>-->
                    <!--</div>-->
                    
                    
                    
                    <div class="form-group col-md-3 col-sm-3 col-12">
                      <label>Select Customer *</label>
                      <div class="input-group input-group-sm">
                        <select name="customer" id="customer" class="form-control select2" required >
                        </select>
                        <span class="input-group-append">
                          <button type="button" class="btn btn-danger btn-sm customer_add" data-toggle="modal" data-target=".bs-example-modal-customer_add" ><i class="fa fa-plus"></i></button>
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
                       <select class="form-control select2" id="product" >
                        <option value="">Select One</option>
                      </select>
                    </div>
                    
                    <!--<div class="form-group col-md-4 col-sm-4 col-12">-->
                    <!--  <label>Select Product *</label>                        -->
                    <!--  <select id="products" class="form-control">-->
                    <!--    <option value=" ">Select One</option>-->
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
                          <!--<th>INR Price</th>-->
                          <th>Unit Price</th>
                          <th>Total Price</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="tbody">

                      </tbody>
                    </table>
                  </div>

                  <div class="row" >
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Total Price *</label>                        
                      <input type="text" class="form-control" name="tAmount" id="tAmount" required readonly >
                    </div> 
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Note</label>                        
                      <input type="text" class="form-control" name="note" placeholder="If have any note">
                    </div>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-12" style="margin-top:20px; text-align: center;">
                    <button type="submit" class="btn btn-info"><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
                    <a href="<?php echo site_url('Quotation') ?>" class="btn btn-danger" ><i class="fa fa-arrow-left" ></i>&nbsp;&nbsp;Back</a>
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
    $q2uery = $this->db->select('custid')
                  ->from('customers')
                  ->limit(1)
                  ->order_by('custid','DESC')
                  ->get()
                  ->row();
    if($q2uery)
      {
      $s2n = $q2uery->custid+1;
      }
    else
      {
      $s2n = 1;
      }
    //var_dump($sn); exit();
    $c2n = strtoupper(substr($_SESSION['compname'],0,3));
    $p2c = sprintf("%'05d",$s2n);

    $c2usid = 'C-'.$c2n.$p2c;
    ?>
    
    <div id="customer_add" class="modal fade bs-example-modal-customer_add" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Customer Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="col-md-12 col-sm-12 col-xs-12">
            <form method="POST" action="#" >
              <div class="row">
          
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Name *</label>
                  <input type="text" class="form-control" name="custName" id="custName" placeholder="Customer Name" required >
                </div>
                
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Father's Name</label>
                  <input type="text" class="form-control" name="custfName" id="custfName" placeholder="Father's Name" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Mother's Name </label>
                  <input type="text" class="form-control" name="custmName" id="custmName" placeholder="Mother's Name" >
                </div>
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Spouse Name </label>
                  <input type="text" class="form-control" name="spouse" id="spouse" placeholder="Spouse Name" >
                </div>
                
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label>Contact Number</label>
                  <input type="text" class="form-control" name="custMobile" id="custMobile" placeholder="Mobile Number">
                </div>
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label>Customer Email</label>
                  <input type="email" class="form-control" name="custEmail" id="custEmail" placeholder="example@sunshine.com">
                </div>
                
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                  <label>Present Address</label>
                  <input type="text" class="form-control" name="custAddress" id="custAddress" placeholder="Present Address">
                </div>
                
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Permanent Address</label>
                  <input type="text" class="form-control" name="custpAddress" id="custpAddress" placeholder="Permanent Address" >
                </div>

                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Customer Gender</label>
                  <select class="form-control" name="custGender" id="custGender">
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
                
                <div class="form-group col-md-6 col-sm-6 col-12">
                  <label>Driving License Number *</label>
                  <input type="text" class="form-control" name="custDriving" id="custDriving" placeholder="Driving License Number" required >
                </div>
                
                
                
                <!--<div class="form-group col-md-6 col-sm-6 col-12">-->
                <!--  <label>Opening Balance</label>-->
                <!--  <input type="text" class="form-control" name="balance" id="balance" placeholder="Opening Balance" >-->
                <!--</div>-->
                
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="pbsubmit" ><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

<?php $this->load->view('footer/footer'); ?>



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
          var base_url = '<?php echo base_url() ?>'+'Quotation/getProduct/' + id;
          // alert(id);
          // alert(base_url);
          $.ajax({
            type: 'GET',
            url: base_url,
            dataType: 'text',
            success: function(data){
              var jsondata = JSON.parse(data);                
              $('#tbody').append(jsondata);
              
              calculatePrice();
              }
            });
          });
        });
    </script>

    <script type="text/javascript">
      function totalPrice(id){
        var tp = $('#quantity_'+id).val();
        var quantity = $('#sprice_'+id).val();
    
        var totalPrice = parseFloat(quantity) * parseFloat(tp);
        $('#tprice_' + id).val(parseFloat(totalPrice).toFixed(2));

        calculatePrice();
        }

      function calculatePrice(){
        var sum=0;
        $(".tprice").each(function(){
          sum += parseFloat($(this).val());
          });
        $('#tAmount').val(parseFloat(sum).toFixed(2));
        }
    </script>