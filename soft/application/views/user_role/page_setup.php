<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Page Setup</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Page Setup</li>
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
          <div class="col-md-4 col-sm-4 col-12">
            <div class="card">
              <div class="card-header" style="background-color: #007bff; color: #fff;">
                <h3 class="card-title">New Master Add</h3>
              </div>

              <div class="card-body">
              	<div class="col-md-12 col-sm-12 col-12">
              		<form action="<?= base_url('Access_setup/add_master_title')  ?>" method="post">
                  	<div class="form-group col-md-12 col-sm-12 col-12">
                      <label>New Master Title Name (Client)*</label>
		                  <input type="text" name="c_master_title" class="form-control" placeholder="Master Title Name (Client)" required >
		                </div>
                    <div class="form-group col-md-12 col-sm-12 col-12">
		                	<label>New Master Title Name (Back-end)*</label>
		                	<input type="text" name="master_title" class="form-control" placeholder="Master Title Name (Back-end)" required>
		              	</div>
                    <div class="col-md-12 col-sm-12 col-12" style="text-align: center; margin-top: 20px;">
		                	<button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Add</button>
		              	</div>
                	</form>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4 col-sm-4 col-12">
            <div class="card">
              <div class="card-header" style="background-color: #007bff; color: #fff;">
                <h3 class="card-title">New Page Add</h3>
              </div>

              <div class="card-body">
                <form action="<?= base_url('Access_setup/add_page_title') ?>" method="post">
                  <div class="form-group col-md-12 col-sm-12 col-12">
						        <label>Master Title *</label>
						        <select name="master_page" class="form-control" required >
						          <option value="" >Select Master Title</option>
						          <?php foreach($master as $value) { ?>
						          <option value="<?= $value['master_id']; ?>" ><?= $value['c_master_title']; ?></option>
						          <?php } ?>
						        </select>
						      </div>
                  <div class="form-group col-md-12 col-sm-12 col-12">
					          <label>New Page Title Name (Client) *</label>
					          <input type="text" name="cname" class="form-control" placeholder="Page Title Name" required >
					        </div>
				          <div class="form-group col-md-12 col-sm-12 col-12">
					          <label>New Page Title Name (Back-end) *</label>
					          <input type="text" name="pagename" class="form-control" placeholder="Page Title Name" required >
					        </div>
				          <div class="col-md-12 col-sm-12 col-12" style="text-align: center; margin-top: 20px;">
					          <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Add</button>
					        </div>
                </form>
              </div>
            </div>
          </div>

          <div class="col-md-4 col-sm-4 col-12">
            <div class="card">
              <div class="card-header" style="background-color: #007bff; color: #fff;">
                <h3 class="card-title">New Function Add</h3>
              </div>

              <div class="card-body">
                <form action="<?= base_url('Access_setup/add_page_function_title') ?>" method="post">
                  <div class="form-group col-md-12 col-sm-12 col-12">
						        <label>Master Title *</label>
					          <select name="master" id="master" class="form-control" required >
	                    <option  value="">Select Master Title</option>
	                    <?php foreach($master as $value2){ ?>
	                    <option value="<?= $value2['master_id']; ?>"><?= $value2['c_master_title'] ?></option>
	                    <?php } ?>
					          </select>
					        </div>
			            <div class="form-group col-md-12 col-sm-12 col-12">
		              	<label>Page Title *</label>
                  	<select  name="pageid" id="page" class="form-control" required disabled >
                  	</select>
			            </div>
			            <div class="form-group col-md-12 col-sm-12 col-12">
		              	<label>New Function Name (Client)*</label>
		              	<input type="text" name="fcname" class="form-control" placeholder="New Function Name (Client)" required >
			            </div>
			            <div class="form-group col-md-12 col-sm-12 col-12">
		              	<label>New Function Name (Back-end)</label>
		              	<input type="text" name="pfunc_name" class="form-control" placeholder="New Function Name (Back-end)" required >
			            </div>
                  <div class="col-md-12 col-sm-12 col-12" style="text-align: center; margin-top: 20px;">
                    <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Add</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 col-sm-12 col-12">
            <div class="card">
              <div class="card-header" style="background-color: #007bff; color: #fff;">
                <h3 class="card-title">List of Pages And Functions</h3>
              </div>

              <div class="card-body">
                <table id="example" class="table table-bordered table-striped table-responsive">
          				<thead>
          					<tr>
          						<th style="width: 5%;">ID</th>
            					<th style="width: 30%;">Master</th>
            					<th style="width: 35%;">Page</th>
            					<th style="width: 30%;">Function</th>
          					</tr>
          				</thead>
          				<tbody>
          					<?php
          					$i = 0;
          					foreach($pagelist as $value){
          					$i++;
          					?>
          					<tr>
          						<td><?php echo $i; ?></td>
            					<td><?php echo $value->c_master_title; ?></td>
            					<td><?php echo $value->cname; ?></td>
            					<td><?php echo $value->fcname; ?></td>
          					</tr>
          					<?php } ?>
          				</tbody>
          			</table>
              </div>
            </div>
          </div>
        </div>
      </div>
  	</section>
  </div>

<?php $this->load->view('footer/footer');?>

    <script type="text/javascript">
    	$(function(){
    		$('#master').change(function(){
    			var url ='<?= base_url('Access_setup/get_page_data') ?>';
    			var id = $('#master').val();

			    $.ajax({
			      method: "POST",
			      url: url,       
			      dataType: 'json',
			      data    : {"id" : id},
			      success:function(data){ 
				      // alert(data);
	        	  $('#page').removeAttr('disabled')
              var HTML = '<option value="">Select Page</option>';
              for(var key in data) 
                {
                if(data.hasOwnProperty(key))
                  {
                  HTML +="<option value='"+data[key]["pageid"]+"'>"+data[key]["cname"]+"</option>";
                  }
                }
              $('#page').html(HTML);
			        },
			      error:function(data){
			        alert('Sorry');
			       }
      			});
      		});
      	});
    </script>