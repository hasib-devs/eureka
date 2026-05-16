<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Costing</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Costing</li>
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
                <h3 class="card-title">Costing Information</h3>
              </div>

              <div class="card-body">
                <form method="POST" action="<?php echo site_url("Costing/update_costing") ?>">
                  <input type="hidden" name="cstid" value="<?php echo $purchase['cstid']; ?>" required >
                  <div class="col-md-12 col-sm-12 col-12">
                    <div class="row">
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Product Category *</label>
                        <select class="form-control select2" name="category" id="category" required >
                          <option value="">Select One</option>
                          <?php foreach($category as $value){ ?>
                          <option value="<?php echo $value['catid']; ?>"><?php echo $value['catName']; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Select Product *</label>
                        <select class="form-control select2" name="product" id="product" required >
                          <option value="">Select One</option>
                          <?php foreach($product as $value){ ?>
                          <option <?php echo ($purchase['pid'] == $value['pid'])?'selected':''?> value="<?php echo $value['pid']; ?>"><?php echo $value['pName'].' ( '.$value['partNo'].' '.$value['hsn'].' '.$value['model'].' )'; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Price INR *</label>
                        <input type="text" class="form-control" name="pprice" id="pprice" onkeyup="calculatediscount()" value="<?php echo $purchase['pprice']; ?>" placeholder="Amount" required >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Discount % *</label>
                        <input type="text" class="form-control" name="pdiscount" id="pdiscount" onkeyup="calculatediscount()" value="<?php echo $purchase['pdiscount']; ?>" required >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Total Price INR *</label>
                        <input type="text" class="form-control" name="tpprice" id="tpprice" value="<?php echo $purchase['tpprice']; ?>" placeholder="Amount" required readonly >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>BDT Rate *</label>
                        <input type="text" class="form-control" name="crate" id="crate" onkeyup="calculatebdt()" value="<?php echo $purchase['crate']; ?>" placeholder="Rate" required  >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Unit Price BDT *</label>
                        <input type="text" class="form-control" name="camount" id="camount" value="<?php echo $purchase['camount']; ?>" placeholder="Amount" required readonly >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Unit Cost % *</label>
                        <input type="text" class="form-control" name="uCost" id="uCost" onkeyup="calculatebdt()" value="<?php echo $purchase['uCost']; ?>" placeholder="Unit Cost" required  >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Weight per Unit (KG)</label>
                        <input type="text" class="form-control" name="weight" id="weight" onkeyup="calculateweight()" value="<?php echo $purchase['weight']; ?>" placeholder="Weight per unit"  >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Quantity</label>
                        <input type="text" class="form-control" name="quantity" id="quantity" onkeyup="calculateweight()" value="<?php echo $purchase['quantity']; ?>" placeholder="quantity"  >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Total Amount BDT</label>
                        <input type="text" class="form-control" name="aamount" id="aamount" value="<?php echo $purchase['aamount']; ?>" placeholder="Assessable Amount" readonly >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Total Weight (KG)</label>
                        <input type="text" class="form-control" name="tweight" id="tweight" value="<?php echo $purchase['tweight']; ?>" placeholder="Total Weight" readonly >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Assessable Amount USD</label>
                        <input type="text" class="form-control" name="asamount" id="asamount" value="<?php echo $purchase['asamount']; ?>" onkeyup="calculateusd()" placeholder="Assessable Amount"  >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>USD Rate</label>
                        <input type="text" class="form-control" name="usdrate" id="usdrate" value="<?php echo $purchase['usdrate']; ?>" onkeyup="calculateusd()" placeholder="USD Rate" >
                      </div>
                      
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Total Assessable Amount BDT</label>
                        <input type="text" class="form-control" name="tasamount" id="tasamount" value="<?php echo $purchase['tasamount']; ?>" placeholder="Assessable Amount" readonly >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Assessable %</label>
                        <input type="text" class="form-control" name="apamount" id="apamount" value="<?php echo $purchase['apamount']; ?>" onkeyup="calculateusd()" placeholder="Amount" >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Total Assessable Amount</label>
                        <input type="text" class="form-control" name="taamount" id="taamount" value="<?php echo $purchase['taamount']; ?>" placeholder="Total Assessable Amount" readonly >
                      </div>
                      <div class="d-none">
                        <label>Total Convert Amount</label>
                        <input type="text" class="form-control" name="tcamount" id="tcamount" value="<?php echo $purchase['tcamount']; ?>" placeholder="Total Convert Amount" readonly >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Per Pices Custom Duty</label>
                        <input type="text" class="form-control" name="custom" id="custom" onkeyup="calculatetotal()" value="<?php echo $purchase['custom']; ?>" placeholder="Custom Duty"  >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Other Costing</label>
                        <input type="text" class="form-control" name="ocost" id="ocost" onkeyup="calculatetotal()" value="<?php echo $purchase['ocost']; ?>" placeholder="Other Costing"  >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Purchase Price *</label>
                        <input type="text" class="form-control" name="tamount" id="tamount" value="<?php echo $purchase['tamount']; ?>" placeholder="Purchase Price" required readonly >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Sales Percentage % *</label>
                        <input type="text" class="form-control" name="pmargin" id="pmargin" onkeyup="calculatesprice()" value="<?php echo $purchase['pmargin']; ?>" placeholder="Sales Percentage" required  >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Sales Price *</label>
                        <input type="text" class="form-control" name="sprice" id="sprice" value="<?php echo $purchase['sprice']; ?>" placeholder="Sales Amount" required readonly >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Stock</label>
                        <input type="text" class="form-control" name="stock" value="<?php echo $purchase['stock']; ?>" placeholder="stock" >
                      </div>
                      <div class="form-group col-md-4 col-sm-4 col-12">
                        <label>Note</label>
                        <input type="text" class="form-control" name="note" value="<?php echo $purchase['note']; ?>" placeholder="If have any note">
                      </div>
                    </div>             
                    <div class="form-group col-md-12 col-sm-12 col-12" style="margin-top:20px; text-align: center;">
                      <button type="submit" class="btn btn-info"><i class="far fa-save"></i>&nbsp;&nbsp;Update</button>
                      <a href="<?php echo site_url() ?>Costing" class="btn btn-danger" ><i class="fa fa-arrow-left" ></i>&nbsp;&nbsp;Back</a>
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
  
  
  <script type="text/javascript" >
    $(document).ready(function(){
      $('#category').change(function(){
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
    function calculatediscount()
      {        
      var pprice = $('#pprice').val();
      var pdiscount = $('#pdiscount').val();
        // alert(tp);alert(quantity);
      var dis = ((pprice * pdiscount)/100);
      var tprice = (pprice - dis);
      
      $('#tpprice').val(parseFloat(tprice).toFixed(2));
      
      calculatebdt();
      }

    function calculatebdt()
      {
      var tpprice = $('#tpprice').val();
      var crate = $('#crate').val();
      var ucost = $('#uCost').val();
        //alert(tpprice); alert(crate); alert(ucost);
      var tprice = (tpprice*crate);
      var p2price = ((tprice*ucost)/100);
      var p22price = (+tprice + +p2price);
      
      $('#camount').val(parseFloat(tprice).toFixed(2));
      $('#tamount').val(parseFloat(p22price).toFixed(2));
      
      calculatesprice();
      }
    
    function calculatesprice()
      {
      var tamount = $('#tamount').val();
      var margin = $('#pmargin').val();
        //alert(asamount);alert(usdrate);
      var dis = ((tamount * margin)/100);
      var total = +tamount + +dis;
        //alert(due);
      $('#sprice').val(parseFloat(total).toFixed(2));
      }
      
      
      
      
    function calculateweight()
      {
      var weight = $('#weight').val();
      var quantity = $('#quantity').val();
      var camount = $('#camount').val();
      
      var due = ((quantity*weight));
      var tsa = (quantity*camount);
      
      $('#tweight').val(parseFloat(due).toFixed(2));
      $('#aamount').val(parseFloat(tsa).toFixed(2));
      calculateusd();
      }
    
    function calculateusd()
      {
      var asamount = $('#asamount').val();
      var usdrate = $('#usdrate').val();
      var weight = $('#tweight').val();
      var apamount = $('#apamount').val();
      var quantity = $('#quantity').val();
        //alert(asamount);alert(usdrate);
      var due = (usdrate*asamount);
      var tdue = (due*weight);
        //alert(due);
      //$('#aamount').val(parseFloat(due).toFixed(2));
      $('#tasamount').val(parseFloat(tdue).toFixed(2));
      
      var cvdue = ((tdue*apamount)/100);
      
      $('#taamount').val(parseFloat(cvdue).toFixed(2));
      $('#tcamount').val(parseFloat(cvdue).toFixed(2));
      
      var tcvdue = (cvdue/quantity);
      
      $('#custom').val(parseFloat(tcvdue).toFixed(2));
      
      calculatetotal();
      }
    
    function calculatetotal()
      {
      var tcamount = $('#camount').val();
      var custom = $('#custom').val();
      var ocost = $('#ocost').val();
        //alert(asamount);alert(usdrate);
      var due = +tcamount + +custom + +ocost;
        //alert(due);
      //$('#tamount').val(parseFloat(due).toFixed(2));
      
      calculatesprice();
      }
    
    
  </script>
