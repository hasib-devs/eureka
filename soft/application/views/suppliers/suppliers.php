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
							<h3 class="card-title">Supplier List</h3>
							<?php if($_SESSION['newSupplier'] == 1){ ?>
							<button type="button" class="btn btn-primary add_supplier" data-toggle="modal" data-target=".bs-example-modal-add_supplier" style="float: right;"><i class="fa fa-plus"></i> New Supplier</button>
							<?php } ?>
							<!-- <button type="button" class="btn btn-success template" data-toggle="modal" data-target=".bs-example-modal-template" style="float: right; margin-right: 10px;" ><i class="far fa-file-excel"></i> Import</button> -->
						</div>

						<div class="card-body">
							<div class="">
								<table id="example" class="table table-responsive table-bordered">
									<thead>
										<tr>
											<th style="width: 5%;">#SN.</th>
											<!--<th>ID</th>-->
											<th>Name</th>
											<!--<th>Company</th>-->
											<th>Mobile</th>
											<th>Email</th>
											<!--<th>Address</th>-->
											<th>Advance</th>
											<th>Paid</th>
											<th>Due</th>
											<th>Remain</th>
											<th>Bank</th>
											<th>Iic Number</th>
											<th>BIN/GST</th>
											<th>Pin Number</th>
											<th>Bin Number</th>
											
											<th style="width: 10%;">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
                                        $i = 0;
                                        foreach ($supplier as $value){
                                        $i++;
                                        
                                        $payment = $this->db->select('SUM(pAmount) as total')
                                                          ->from('supplier_payment')
                                                          ->where('supid',$value['supid'])
                                                          ->get()
                                                          ->row();
                                        
                                        $purchase = $this->db->select('SUM(pAmount) as total,SUM(tAmount) as tpa,SUM(vAmount) as tva')
                                                          ->from('purchase')
                                                          ->where('supid',$value['supid'])
                                                          ->get()
                                                          ->row();
                                        
                                        $supbank = $this->db->select('*')
                                                          ->from('sup_account')
                                                          ->where('supid',$value['supid'])
                                                          ->get()
                                                          ->result();
                                        ?>
										<tr>
											<td><?php echo $i; ?></td>
											<!--<td><?php echo $value['supCode']; ?></td>-->
											<td><?php echo $value['supName']; ?></td>
											<!--<td><?php echo $value['supCompany']; ?></td>-->
											<td><?php echo $value['supMobile']; ?></td>
											<td><?php echo $value['supEmail']; ?></td>
											<!--<td><?php echo $value['supAddress']; ?></td>-->
											<td><?php echo number_format($value['balance']+$payment->total, 2); ?></td>
											<td><?php echo number_format($purchase->total, 2); ?></td>
											<td><?php echo number_format(($purchase->tpa+$purchase->tva)-$purchase->total, 2); ?>
											</td>
											<td><?php echo number_format((($value['balance']+$payment->total)-$purchase->total), 2); ?>
											</td>
											<td>
                                              <?php foreach($supbank as $result){ ?>
                                              <?php echo $result->supBank.' '.$result->supBranch.' '.$result->supANumber.' '.$result->supAName.' '.$result->supRNumber; ?><br>
                                              <?php } ?>
                                            </td>
                                            <td><?php echo $value['iicN'];?></td>
                                            <td><?php echo $value['bingst'];?></td>
                                            <td><?php echo $value['pinN'];?></td>
                                            <td><?php echo $value['binN'];?></td>
											<td>
											    <?php if($_SESSION['editSupplier'] == 1){ ?>
												<button type="button" class="btn btn-success btn-xs supplier_edit"
													data-toggle="modal" data-target=".bs-example-modal-supplier_edit"
													data-id="<?php echo $value['supid'];?>"
													id="<?php echo $value['supid']; ?>"
													onclick="document.getElementById('supplier_edit').style.display='block'"><i
														class="fa fa-edit"></i></button>
												<?php } if($_SESSION['deleteSupplier'] == 1){ ?>
												<a class=" btn btn-danger btn-xs"
													href="<?php echo site_url('Supplier/delete_supplier').'/'.$value['supid']; ?>"
													onclick="return confirm('Are you sure you want to delete this Supplier ?');"><i
														class="fa fa-trash"></i></a>
												<?php } ?>
												<a href="#" class="payment btn btn-warning btn-xs" data-toggle="modal"
													data-target=".bs-example-modal-payment"
													data-id="<?php echo $value['supid']; ?>"><i
														class="fa fa-plus"></i></a>
												<button type="button" class="btn btn-info btn-xs supAccount"
													data-toggle="modal" data-target=".bs-example-modal-supAccount"
													data-id="<?php echo $value['supid'];?>"
													id="<?php echo $value['supid']; ?>"
													onclick="document.getElementById('supAccount').style.display='block'" title="bank account"><i
														class="fa fa-check"></i></button>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<div class="modal fade bs-example-modal-add_supplier" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">New Supplier Information</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">×</span></button>
			</div>
			<form autocomplete="off" action="<?php echo base_url('Supplier/save_supplier');?>" method="post">
				<div class="col-md-12 col-sm-12 col-12">
				  <div class="row">
					<div class="form-group col-md-6 col-sm-6 col-12">
					    <label>Supplier Name *</label>
						<input type="text" class="form-control" name="supName" placeholder="Supplier Name" required>
					</div>
					<div class="form-group col-md-6 col-sm-6 col-12">
					    <label>Mobile Number *</label>
						<input type="text" class="form-control" name="supMobile" placeholder="Mobile Number"
							onkeypress="return isNumberKey(event)" maxlength="11" required minlength="11">
					</div>
					<div class="form-group col-md-6 col-sm-6 col-12">
					    <label>Supplier Company</label>
						<input type="text" class="form-control" name="supCompany" placeholder="Supplier Company">
					</div>
					<div class="form-group col-md-6 col-sm-6 col-12">
					    <label>Supplier Email</label>
						<input type="email" class="form-control" name="supEmail" placeholder="example@sunshine.com">
					</div>
					<div class="form-group col-md-6 col-sm-6 col-12">
					    <label>Supplier Address</label>
						<input type="text" class="form-control" name="supAddress" placeholder="Address">
					</div>
					<div class="form-group col-md-6 col-sm-6 col-12">
					    <label>Advance Amount</label>
						<input type="text" class="form-control" name="balance" placeholder="Amount">
					</div>
					<div class="form-group col-md-6 col-sm-6 col-12">
						<label>Account Type *</label>
						<select class="form-control" name="accountType" id="accountType" required>
							<option value="Cash">Cash</option>
							<option value="Bank">Bank</option>
							<option value="Mobile">Mobile</option>
						</select>
					</div>
					<div class="form-group col-md-6 col-sm-6 col-12">
						<label>Account Number *</label>
						<select name="accountNo" id="accountNo" class="form-control" required>
							<option value="">Select Account Type First</option>
						</select>
					</div>
					<div class="form-group col-md-6 col-sm-6 col-12">
                      <label>Bank Name</label>
                      <input type="text" class="form-control" name="supBank" placeholder="Bank Name" >
                    </div>
                    <div class="form-group col-md-6 col-sm-6 col-12">
                      <label>Branch Name</label>
                      <input type="text" class="form-control" name="supBranch" placeholder="Branch Name" >
                    </div>
					<div class="form-group col-md-6 col-sm-6 col-12">
                      <label>Account Name *</label>
                      <input type="text" class="form-control" name="supAName" placeholder="Account Name" required >
                    </div>
                    <div class="form-group col-md-6 col-sm-6 col-12">
                      <label>Account No *</label>
                      <input type="text" class="form-control" name="supANumber" placeholder="Account No." required >
                    </div>
                    <div class="form-group col-md-6 col-sm-6 col-12">
                      <label>Routing No.</label>
                      <input type="text" class="form-control" name="supRNumber" placeholder="Routing No." required >
                    </div>
                    <!--new field-->
                                    
                    <div class="form-group col-md-6 col-sm-6 col-12">
                      <label>Iic Number.</label>
                      <input type="text" class="form-control" name="iicN" placeholder="Iic Number."  >
                    </div>
                    <div class="form-group col-md-6 col-sm-6 col-12">
                      <label>BIN/GST.</label>
                      <input type="text" class="form-control" name="bingst" placeholder="BIN/GST."  >
                    </div>
                    <div class="form-group col-md-6 col-sm-6 col-12">
                      <label>Pin Number.</label>
                      <input type="text" class="form-control" name="pinN" placeholder="Pin Number."  >
                    </div>
                    <div class="form-group col-md-6 col-sm-6 col-12">
                      <label>Bin Number.</label>
                      <input type="text" class="form-control" name="binN" placeholder="Bin Number."  >
                    </div>
                  </div>
                </div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary"><i
								class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal"><i
								class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
					</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade bs-example-modal-supplier_edit" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Update Supplier Information</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">×</span></button>
			</div>
			<form action="<?php echo base_url('Supplier/update_supplier');?>" method="post">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="row">
						<div class="form-group col-md-6 col-sm-6 col-12">
							<label>Supplier Name *</label>
							<input type="text" class="form-control" name="supName" id="supName" required>
						</div>
						<div class="form-group col-md-6 col-sm-6 col-12">
							<label>Contact Number *</label>
							<input type="text" class="form-control" name="supMobile" id="supMobile"
								onkeypress="return isNumberKey(event)" maxlength="11" required>
						</div>
						<div class="form-group col-md-6 col-sm-6 col-12">
							<label>Supplier Company</label>
							<input type="text" class="form-control" name="supCompany" id="supCompany">
						</div>
						<div class="form-group col-md-6 col-sm-6 col-12">
							<label>Email</label>
							<input type="email" class="form-control" name="supEmail" id="supEmail">
						</div>
						<div class="form-group col-md-6 col-sm-6 col-12">
							<label>Address</label>
							<input type="text" class="form-control" name="supAddress" id="supAddress">
						</div>
						<div class="form-group col-md-6 col-sm-6 col-12">
							<label>Advance Amount</label>
							<input type="text" class="form-control" name="balance" id="balance">
						</div>
						<!--new field-->
                                    
                        <div class="form-group col-md-6 col-sm-6 col-12">
                          <label>Iic Number.</label>
                          <input type="text" class="form-control" name="iicN" id="iicN" placeholder="Iic Number."  >
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-12">
                          <label>BIN/GST.</label>
                          <input type="text" class="form-control" name="bingst" id="bingst" placeholder="BIN/GST."  >
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-12">
                          <label>Pin Number.</label>
                          <input type="text" class="form-control" name="pinN" id="pinN" placeholder="Pin Number."  >
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-12">
                          <label>Bin Number.</label>
                          <input type="text" class="form-control" name="binN" id="binN" placeholder="Bin Number."  >
                        </div>
						<div class="form-group col-md-6 col-sm-6 col-12">
							<label>Status</label>
							<select class="form-control" name="status" id="status">
								<option value="Active">Active</option>
								<option value="Inactive">Inactive</option>
							</select>
						</div>
					</div>
					<input type="hidden" id="supid" name="supid" required>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary"><i
								class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal"><i
								class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade bs-example-modal-template" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Supplier template</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">×</span></button>
			</div>
			<div class="row">
				<div class="form-group col-md-6 col-sm-6 col-12">
					<div style="width: 100%; height: 100px; background: #fff4f4;text-align: center;">
						<a href="<?php echo base_url('assets/templates/suppliers.xlsx'); ?>"
							style="padding:1em; text-align: center; display:inline-block; text-decoration: none !important; margin:0 auto;">New
							template</a>
					</div>
				</div>
				<div class="form-group col-md-6 col-sm-6 col-12">
					<div style="width: 100%;height: 100px;background: #fff4f4;text-align: center;">
						<a href="<?php echo base_url('Supplier/export_action') ?>"
							style="padding:1em;text-align: center;display:inline-block;text-decoration: none !important;margin:0 auto;">Exists
							template</a>
					</div>
				</div>
			</div>
			<div class="col-md-12 col-sm-12 col-12">
				<form method="post" id="import_form" enctype="multipart/form-data">
					<div class="form-group col-md-12 col-sm-12 col-12">
						<label>Import Template<span style="color: red;">*</span></label>
						<input type="file" name="file" id="file" required accept=".xls, .xlsx">
					</div>
					<div class="form-group col-md-12 col-sm-12 col-12" style="margin-top: 25px; text-align: center;">
						<input type="submit" name="import" value="Import" class="btn btn-info">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade bs-example-modal-payment" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Advance Payment Information</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">×</span></button>
			</div>
			<form action="<?php echo base_url('Supplier/save_supplier_payment'); ?>" method="post">
				<div class="col-md-12 col-sm-12 col-12">
					<div class="form-group">
						<label>Paid Amount *</label>
						<input type="text" class="form-control" name="pAmount" placeholder="Amount" required>
					</div>
					<div class="form-group">
						<label>Account Type *</label>
						<select class="form-control" name="accountType" id="accountType" required>
							<option value="Cash">Cash</option>
							<option value="Bank">Bank</option>
							<option value="Mobile">Mobile</option>
						</select>
					</div>
					<div class="form-group">
						<label>Account Number *</label>
						<select name="accountNo" id="accountNo" class="form-control" required>
							<option value="">Select Account Type First</option>
						</select>
					</div>
				</div>
				<input type="hidden" id="supid" name="supid" required>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" id="pbsubmit"><i
							class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i
							class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade bs-example-modal-supAccount" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Supplier Bank Account</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">×</span></button>
			</div>
			<form action="<?php echo base_url('Supplier/supplier_bank_account');?>" method="post">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="row">
					    <div class="form-group col-md-6 col-sm-6 col-12">
                          <label>Bank Name *</label>
                          <input type="text" class="form-control" name="supBank" placeholder="Bank Name" required >
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-12">
                          <label>Branch Name</label>
                          <input type="text" class="form-control" name="supBranch" placeholder="Branch Name" >
                        </div>
						<div class="form-group col-md-6 col-sm-6 col-12">
                          <label>Account Name *</label>
                          <input type="text" class="form-control" name="supAName" placeholder="Account Name" required >
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-12">
                          <label>Account No *</label>
                          <input type="text" class="form-control" name="supANumber" placeholder="Account No." required >
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-12">
                          <label>Routing No.</label>
                          <input type="text" class="form-control" name="supRNumber" placeholder="Routing No." required >
                        </div>
					</div>
					<input type="hidden" id="supid" name="supid" required>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary"><i
								class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal"><i
								class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>


<?php $this->load->view('footer/footer'); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$(document).on('click', '.supplier_edit', function() {
			var supid = $(this).attr('id');
			//alert(l_id);
			$('input[name="supid"]').val(supid);
		});
		$(document).on('click', '.supplier_edit', function() {
			var id = $(this).attr('id');
			//alert(id);
			var url = '<?php echo base_url() ?>Supplier/get_supplier_data';
			//alert(url);
			$.ajax({
				method: 'POST',
				url: url,
				dataType: 'json',
				data: {
					'id': id
				},
				success: function(data) {
					//alert(data);
					var HTML = data["supName"];
					var HTML2 = data["supCompany"];
					var HTML3 = data["supMobile"];
					var HTML4 = data["supEmail"];
					var HTML5 = data["supAddress"];
					var HTML6 = data["balance"];
					var HTML7 = data["status"];
					var HTML8 = data["iicN"];
					var HTML9 = data["bingst"];
					var HTML10 = data["pinN"];
					var HTML11 = data["binN"];
					//alert(HTML);
					$("#supName").val(HTML);
					$("#supCompany").val(HTML2);
					$("#supMobile").val(HTML3);
					$("#supEmail").val(HTML4);
					$("#supAddress").val(HTML5);
					$("#balance").val(HTML6);
					$("#status").val(HTML7);
					$("#iicN").val(HTML8);
					$("#bingst").val(HTML9);
					$("#pinN").val(HTML10);
					$("#binN").val(HTML11);
				},
				error: function() {
					alert('error');
				}
			});
		});
	});
</script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#import_form').on('submit', function(event) {
			event.preventDefault();
			$.ajax({
				url: "<?php echo base_url(); ?>Supplier/excel_import",
				method: "POST",
				data: new FormData(this),
				contentType: false,
				cache: false,
				processData: false,
				success: function(data) {
					$('#file').val('');
					load_data();
					alert(data);
				}
			});
		});
	});
</script>

<script type="text/javascript">
	$(document).ready(function() {
		$(".payment").click(function() {
			var supid = $(this).data('id');
			//alert(puid);
			$('input[name="supid"]').val(supid);
		});
	});
</script>

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

<script type="text/javascript">
	$(document).ready(function() {
		$(".supAccount").click(function() {
			var supid = $(this).data('id');
			//alert(puid);
			$('input[name="supid"]').val(supid);
		});
	});
</script>