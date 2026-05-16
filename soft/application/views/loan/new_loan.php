<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Staff / Employee Loan</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Staff / Employee Loan</li>
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
                <h3 class="card-title">Staff / Employee Loan Information</h3>
              </div>

              <div class="card-body">
                <form action="<?php echo base_url('Loan/save_employee_payment'); ?>" method="POST">
                  <div class="row">
                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                      <label>Select Month *</label>
                      <select class="form-control" name="month" id="smonth" required >
                        <option value="">Select One</option>
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                      <label>Select Year *</label>
                      <select class="form-control" name="year" id="syear" required >
                        <?php $d = date("Y"); ?>
                        <option value="">Select One</option>
                        <?php for ($x = 2020; $x <= $d; $x++) { ?>
                        <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-4 col-12">
                      <label>Select Employee *</label>
                      <select class="form-control select2" name="employee" id="employee" required >
                        <option value="">Select One</option>
                        <?php foreach($employee as $value){ ?>
                        <option value="<?php echo $value['empid']; ?>"><?php echo $value['empName'].' ( '.$value['empCode'].' )'; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="row" style="margin-top:20px" >
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                          <thead>
                            <tr style="background: #000; color: #fff;">
                              <th>Employee</th>
                              <th>Loan</th>              
                              <!--<th>Basic</th>-->
                              <!--<th>Allowance</th>-->
                              <!--<th>House Rent</th>-->
                              <!--<th>Medical</th>-->
                              <!--<th>Attendance</th> -->
                              <!--<th>Advance</th>-->
                              <!--<th>Festival Bonus</th>-->
                              <!--<th>Payment</th>-->
                            </tr>
                          </thead>
                          <tbody id="empsalary">
                          
                          </tbody>
                        </table>
                    </div>
                  </div>

                  <div class="row" style="margin-top:20px" >
                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                      <label>Account Type *</label>                        
                      <select class="form-control" name="accountType" id="accountType" required >
                        <option value="">Select One</option>
                        <option value="Cash">Cash</option>
                        <option value="Bank">Bank</option>
                        <option value="Mobile">Mobile</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                      <label>Account No *</label>                        
                      <select class="form-control" name="accountNo" id="accountNo" required >
                        <option value="">Select One</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-6 col-xs-12">
                      <label>Note</label>                        
                      <input type="text" class="form-control" placeholder="if have any Note" name="note">
                    </div>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-xs-12" style="margin-top:20px; text-align: center;" >
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save" ></i> Submit</button>
                    <a href="<?php echo site_url('empLoan')?>" class="btn btn-danger" ><i class="fa fa-arrow-left" ></i> Back</a>
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
          $('#employee').change(function(){
            var url = "<?php echo base_url(); ?>Loan/get_emp_salary";
            var yid = $('#syear').val();
            var mid = $('#smonth').val();
            var id = $('#employee').val();
            alert(yid);alert(mid); alert(id);alert(url);
            $.ajax({
              method: "POST",
              url     : url,
              dataType: 'json',
              data    : {'id':id,'yid':yid,'mid':mid},
              success:function(data){ 
              alert(data);
              $("#empsalary").append(data);
                },
              error:function(data){
              alert('error');
              }
            });
          });
        });
      </script>

    <script type="text/javascript">
      function totalPrice(){
        var total = $('#salary').val();
        var attday = $('#attday').val();
        var adpa = $('#advance').val();
        var bonus = $('#bonus').val();
        var tpa = ((total*attday)/27);
        var totalPrice = ((+tpa + +bonus)-adpa);
        
        $('#payment').val(parseFloat(totalPrice).toFixed(2));
        }
    </script>

    <script type="text/javascript">

      $('#accountType').on('change',function(){
        var value = $(this).val();
        $('#accountNo').empty();
        getAccountNo(value, '#accountNo');
        });
        
        function getAccountNo(value,place){
          $(place).empty();
          if(value != ''){
            $.ajax({
              url: '<?php echo site_url()?>Loan/getAccountNo',
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