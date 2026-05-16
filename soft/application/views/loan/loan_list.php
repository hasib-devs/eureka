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
                <h3 class="card-title">Staff / Employee Loan Lists</h3>
                <a href="<?php echo site_url(); ?>newempLoan" class="btn btn-primary" style="float: right" ><i class="fa fa-plus"></i> New Loan</a>
              </div>

              <div class="card-body">
                <table id="example" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>#SN.</th>
                      <th>Name</th>
                      <th>ID</th>
                      <th>Date</th>
                      <th>Loan</th>
                      <!--<th>Attendance</th>-->
                      <!--<th>Advance</th>-->
                      <!--<th>Payment</th>-->
                      <th>Note</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 0;
                    foreach ($loan as $key => $value) {
                    $i++;
                    ?>
                    <tr class="gradeX">
                      <td><?php echo $i; ?></td>
                      <td><?php echo $value['empName']; ?></td>
                      <td><?php echo $value['empCode']; ?></td>
                      <!--<td>-->
                       
                      <!--  $month = $value['month'];-->
                      <!--  if($month == 01)-->
                      <!--    {-->
                      <!--    $name = 'January';-->
                      <!--    }-->
                      <!--  else if($month == 02)-->
                      <!--    {-->
                      <!--    $name = 'February';-->
                      <!--    }-->
                      <!--  else if($month == 03)-->
                      <!--    {-->
                      <!--    $name = 'March';-->
                      <!--    }-->
                      <!--  else if($month == 04)-->
                      <!--    {-->
                      <!--    $name = 'April';-->
                      <!--    }-->
                      <!--  else if($month == 05)-->
                      <!--    {-->
                      <!--    $name = 'May';-->
                      <!--    }-->
                      <!--  else if($month == 06)-->
                      <!--    {-->
                      <!--    $name = 'June';-->
                      <!--    }-->
                      <!--  else if($month == 07)-->
                      <!--    {-->
                      <!--    $name = 'July';-->
                      <!--    }-->
                      <!--  else if($month == 8)-->
                      <!--    {-->
                      <!--    $name = 'August';-->
                      <!--    }-->
                      <!--  else if($month == 9)-->
                      <!--    {-->
                      <!--    $name = 'September';-->
                      <!--    }-->
                      <!--  else if($month == 10)-->
                      <!--    {-->
                      <!--    $name = 'October';-->
                      <!--    }-->
                      <!--  else if($month == 11)-->
                      <!--    {-->
                      <!--    $name = 'November';-->
                      <!--    }-->
                      <!--  else-->
                      <!--    {-->
                      <!--    $name = 'December';-->
                      <!--    }-->
                      <!--  ?>-->
                      <!--  <?php echo $name.' '.$value['year']; ?>-->
                      <!--</td>-->
                      <td><?php echo $value['year']?></td>
                      <td><?php echo $value['month']?></td>
                      <td><?php echo number_format($value['loan'], 2) ?></td>
                      <!--<td><?php echo $value['attday']; ?></td>-->
                      <!--<td><?php echo number_format($value['aAmount'], 2) ?></td>-->
                      <!--<td><?php echo number_format($value['pAmount'], 2) ?></td>-->
                      <td><?php echo $value['note']; ?></td>
                      <td>
                        <a class="btn btn-success btn-xs" href="<?php echo site_url().'viewLoan/'.$value['lid'] ?>" ><i class="fa fa-eye"></i></a>
                        <a class="btn btn-danger btn-xs" href="<?php echo site_url('Loan/delete_loan').'/'.$value['lid']; ?>" onclick="return confirm('Are you sure you want to delete this Payment ?');" ><i class="fa fa-trash"></i></a>
                        <!--<a href="#" class="payment btn btn-warning btn-xs" data-toggle="modal" data-target=".bs-example-modal-payment" data-id="<?php echo $value['lid']; ?>" id="<?php echo $value['lid']; ?>" onclick="document.getElementById('payment').style.display='block'" title="payment" ><i class="fa fa-plus"></i></a>-->
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
    </section>
  </div>
<div id="payment" class="modal fade bs-example-modal-payment" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" > Payment Information</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form action="<?php echo base_url('Loan/save_sales_payment');?>" method="post">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="form-group">
                <label>Payment Date *</label>
                <input type="text" name="spDate" class="form-control datepicker" value="<?php echo date('m/d/Y') ?>" required >
              </div>
              <div class="form-group">
                <label>Due Amount</label>
                <input type="text" class="form-control" name="loan" id="loan" readonly >
              </div>
              <div class="form-group">
                <label>Paid Amount *</label>
                <input type="text" class="form-control" name="pAmount" required >
              </div>
              <div class="form-group">
                <label>Account Type *</label>
                <select class="form-control" name="accountType" id="accountType" required >
                  <option value="Cash">Cash</option>
                  <!--<option value="Bank">Bank</option>-->
                  <!--<option value="Mobile">Mobile</option>-->
                </select>
              </div>
              <div class="form-group">
                <label>Account Number *</label>
                <select name="accountNo" id="accountNo" class="form-control" required >
                  <option value="">Select Account Type First</option>
                </select>
              </div>
              <div class="form-group">
                <label>Notes</label>
                <input type="text" class="form-control" name="notes" placeholder="If have any notes" >
              </div>
            </div>
            <input type="hidden" id="lid" name="lid" required >
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" id="pbsubmit" ><i class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    
    

    
        <script type="text/javascript">
      $(document).ready(function(){
        $(document).on('click','.payment',function(){
          var lid = $(this).attr("id");
            //alert(l_id);
          $('input[name="lid"]').val(lid);
          });

        $(document).on('click','.payment',function(){
          var id = $(this).attr("id");
            //alert(id);
          var url = "<?php echo base_url(); ?>Loan/get_loan_payment";
            //alert(url);
          $.ajax({
            method: "POST",
            url     : url,
            dataType: "json",
            data    : {'id' : id},
            success:function(data){
                //alert(data);
              if(data["tpa"] == id)
                {
                var HTML = '0';
                var HTML2 = data["loan"];
                }
              else
                {
                var HTML = data["tpa"];
                var HTML2 = data["loan"]-data["tpa"];
                }
                //alert(HTML2);
              $("#pAmount").val(HTML);
              $("#loan").val(HTML2);
              },
            error:function(){
              alert('error');
              }
            });
          });
        });
    </script>
<?php $this->load->view('footer/footer'); ?>