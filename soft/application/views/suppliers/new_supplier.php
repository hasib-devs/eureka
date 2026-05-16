<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Supplier</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
						<li class="breadcrumb-item active">Supplier</li>
					</ol>
				</div>
			</div>
		</div>
	</section>

	<?php
    $exception = $this->session->userdata('exception');
    if(isset($exception))
    {
    echo $exception;
    $this->session->unset_userdata('exception');
    } ?>

	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-12">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Supplier Information</h3>
						</div>

						<div class="card-body">
							<div class="col-md-12 col-sm-12 col-12">
							  <form autocomplete="off" action="<?php echo base_url('Supplier/save_supplier');?>" method="post">
                				<div class="row">
                					<div class="form-group col-md-4 col-sm-4 col-12">
                					    <label>Supplier Name *</label>
                						<input type="text" class="form-control" name="supName" placeholder="Supplier Name" required>
                					</div>
                					<div class="form-group col-md-4 col-sm-4 col-12">
                					    <label>Contact Number *</label>
                						<input type="text" class="form-control" name="supMobile" placeholder="Mobile Number" onkeypress="return isNumberKey(event)" maxlength="11" required minlength="11">
                					</div>
                					<div class="form-group col-md-4 col-sm-4 col-12">
                					    <label>Supplier Company</label>
                						<input type="text" class="form-control" name="supCompany" placeholder="Supplier Company">
                					</div>
                					<div class="form-group col-md-4 col-sm-4 col-12">
                					    <label>Supplier Email</label>
                						<input type="email" class="form-control" name="supEmail" placeholder="example@sunshine.com">
                					</div>
                					<div class="form-group col-md-4 col-sm-4 col-12">
                					    <label>Supplier Address</label>
                						<input type="text" class="form-control" name="supAddress" placeholder="Address">
                					</div>
                					<div class="form-group col-md-4 col-sm-4 col-12">
                					    <label>Advance Amount</label>
                						<input type="text" class="form-control" name="balance" placeholder="Advance Amount">
                					</div>
                					<div class="form-group col-md-4 col-sm-4 col-12">
                						<label>Account Type *</label>
                						<select class="form-control" name="accountType" id="accountType" required >
                							<option value="Cash">Cash</option>
                							<option value="Bank">Bank</option>
                							<option value="Mobile">Mobile</option>
                						</select>
                					</div>
                					<div class="form-group col-md-4 col-sm-4 col-12">
                						<label>Account Number *</label>
                						<select name="accountNo" id="accountNo" class="form-control" required >
                							<option value="">Select Account Type First</option>
                						</select>
                					</div>
                					<div class="form-group col-md-4 col-sm-4 col-12">
                                      <label>Bank Name *</label>
                                      <input type="text" class="form-control" name="supBank" placeholder="Bank Name" required >
                                    </div>
                                    <div class="form-group col-md-4 col-sm-4 col-12">
                                      <label>Branch Name</label>
                                      <input type="text" class="form-control" name="supBranch" placeholder="Branch Name" >
                                    </div>
                					<div class="form-group col-md-4 col-sm-4 col-12">
                                      <label>Account Name *</label>
                                      <input type="text" class="form-control" name="supAName" placeholder="Account Name" required >
                                    </div>
                                    <div class="form-group col-md-4 col-sm-4 col-12">
                                      <label>Account No *</label>
                                      <input type="text" class="form-control" name="supANumber" placeholder="Account No." required >
                                    </div>
                                    <div class="form-group col-md-4 col-sm-4 col-12">
                                      <label>Routing No.</label>
                                      <input type="text" class="form-control" name="supRNumber" placeholder="Routing No." required >
                                    </div>
                                    
                					<div class="form-group col-md-12 col-sm-12 col-12" style="margin-top: 20px; text-align: center;">
                                        <button type="submit" class="btn btn-info"><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
                                        <a href="<?php echo site_url('newPurchase'); ?>" class="btn btn-danger" ><i class="fa fa-arrow-left" ></i>&nbsp;&nbsp;Back</a>
                                    </div>
                                    
                        			</form>
                        		</div>
                	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer/footer'); ?>

    <script type="text/javascript">
    	$(document).ready(function() {
    		var value = $("#accountType").val();
    		$('#accountNo').empty();
    		getAccountNo(value, '#accountNo');
    		$('#accountNo').val(1);
    	});
    	$('#accountType').on('change', function() {
    		var value = $(this).val();
    		$('#accountNo').empty();
    		getAccountNo(value, '#accountNo');
    	});
    
    	function getAccountNo(value, place) {
    		$(place).empty();
    		if (value != '') {
    			$.ajax({
    				url: '<?php echo site_url()?>Voucher/getAccountNo',
    				async: false,
    				dataType: "json",
    				data: 'id=' + value,
    				type: "POST",
    				success: function(data) {
    					$(place).append(data);
    					$(place).trigger("chosen:updated");
    				}
    			});
    		} else {
    			customAlert('Please Select Account Type', "error", true);
    		}
    	}
    </script>