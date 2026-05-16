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
                <h3 class="card-title">Staff / Employee Loan Information</h3>
              </div>

              <div class="card-body">
                <div id="print" style="background-color:#fdfdfd;">
                 <div class="col-sm-12 col-md-12 col-12">
                    <?php if($company){ ?>
                    <div class="row">
                      <div class="col-sm-6 col-md-6 col-6" style="margin-top: 25px;" >
                        <img src="<?php echo base_url().'upload/company/'.$company->com_logo; ?>" style="height:100px; width:auto;">
                      </div>
                      <div class="col-sm-6 col-md-6 col-6 text-right">
                        <div class="col-sm-12 col-md-12 col-xs-12">
                          <h3><b><?php echo $company->com_name; ?></b></h3>
                        </div>
                        <div class="col-sm-12 col-md-12 col-12">
                          Address&nbsp;:&nbsp;<?php echo $company->com_address; ?>
                        </div>
                        <div class="col-sm-12 col-md-12 col-12">
                          Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<?php echo $company->com_email; ?>
                        </div>
                        <div class="col-sm-12 col-md-12 col-12">
                          Mobile&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<?php echo $company->com_mobile; ?>
                        </div>
                      </div>
                    </div>
                    <?php } ?>
                    <hr>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <h3 style="text-align: center;"><b>Employee Loan Slip</b></h3>
                    </div>
                    <hr style="height:2px;border-width:0;color:gray;background-color:gray">
                    <div class="row">
                      <div class="col-md-2 col-sm-2 col-xs-2"></div>
                      <div class="col-md-6 col-sm-6 col-xs-6" style="text-align:left; margin-left:-170px;">
                        <b>Payment Receipt No.&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $empp['lid']; ?></b>
                      </div>
                      <div class="col-md-4 col-sm-4 col-xs-4" style="text-align:right; margin-left:90px;">
                        <b>Date&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo date('d-m-Y', strtotime($empp['regdate'])); ?></b>
                      </div>
                    </div><br>

                  <table class="table table-bordered">
                    <tr style="line-height:6px;">
                      <td class="border border-dark" style="background-color: aliceblue !important; "><b>EMPLOYEE UID</b></td>
                      <td class="border border-dark" style=" "><?php echo  $empp['lid']; ?></td>
                      <td class="dark border border-dark" style="background-color: aliceblue !important; "><b>MONTH</b></td>
                      <td class="border border-dark" style=" "><?php  $monthNum= $empp['month']; 
                      $monthName = date('F', mktime(0, 0, 0, $monthNum, 10)); echo $monthName; // March
                      ?></td>
                    </tr>
                    <tr style="line-height:6px;">
                      <td class="dark border border-dark" style="background-color: aliceblue !important; "><b>EMPLOYEE NAME</b></td>
                      <td class="border border-dark" style=" "><?php echo $empp['empName']; ?></td>
                      <td class="dark border border-dark" style="background-color: aliceblue !important; "><b>YEAR</b></td>
                      <td class="border border-dark" style=" "> <?php echo  $empp['year']; ?></td>
                    </tr>
                    <!--<tr style="line-height:6px;">-->
                    <!--  <td class="dark border border-dark" style="background-color: aliceblue !important;  "><b>DEPARTMENT</b></td>-->
                    <!--  <td class="border border-dark" style=" "><?php echo $empp['deptName'];?></td>-->
                    <!--  <td class="dark border border-dark" style="background-color: aliceblue !important;  "><b>Attendance</b></td>-->
                    <!--  <td class="border border-dark" style=" "><?php echo $empp['attday'].' Days';?></td>-->
                    <!--</tr>-->
                    <!--<tr style="line-height:6px;">-->
                    <!--  <td class="dark border border-dark" style="background-color: aliceblue !important;  "><b>COMPANY NAME</b></td>-->
                    <!--  <td colspan="3" class="border border-dark" style=" "><?php echo $empp['com_name']; ?></td>-->
                    <!--</tr>-->
                  </table>
                  <table class="table table-bordered">
                    <tr style="line-height:6px;">
                      <td colspan="2" class="dark border border-dark" style="background-color: aliceblue !important;  "><b>DETAILS</b> </td>
                      <td colspan="2" class="dark border border-dark" style="background-color: aliceblue !important;  "><b>AMOUNT</b> </td>
                    </tr>
                    <tr style="line-height:6px;">
                      <td colspan="3" class="border border-dark" style=" "><?php  $monthNum= $empp['month']; 
                      $monthName = date('F', mktime(0, 0, 0, $monthNum, 10)); echo $monthName; // March
                       ?>&nbsp Loan </td>
                      <td colspan="1" class="border border-dark" style=" "><?php echo number_format($empp['loan'], 2); ?></td>
                    </tr>
                    <!--<?php if($empp['bonus']) {?>-->
                    <!--<tr style="line-height:6px;">-->
                    <!--  <td colspan="3" class="dark border border-dark" style="  ">Festival Bonus</td>-->
                    <!--  <td colspan="1" class="dark border border-dark" style="  "><?php echo number_format($empp['bonus'], 2); ?></td>-->
                    <!--</tr>-->
                    <!--<?php } ?>    -->
                    <!--<?php if($empp['aAmount']) {?>-->
                    <!--<tr style="line-height:6px;">-->
                    <!--  <td colspan="3" class="dark border border-dark" style="  ">Advance</td>-->
                    <!--  <td colspan="1" class="dark border border-dark" style="  "><?php echo number_format($empp['aAmount'], 2); ?></td>-->
                    <!--</tr>-->
                    <!--<?php } ?>-->
                    <tr style="line-height:6px;">
                      <td colspan="3" class="dark border border-dark" style="background-color: aliceblue !important;  "><b>TOTAL AMOUNT</b> </td>
                      <td colspan="1" class="dark border border-dark" style="background-color: aliceblue !important;  "><b><?php echo number_format($empp['loan'], 2); ?></b></td>
                    </tr>
                  </table>

                  <div class="row" >
                    <div class="col-md-2 col-sm-2 col-xs-2" style="text-align:left; margin-left:-170px;"></div>
                    <?php $twa = abs(round($empp['loan'])); ?>
                    <div class="col-md-10 col-sm-10 col-xs-10"><b>In Words&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo convertNumberToWordsForIndia($twa); ?></b></div>
                  </div><br>

                  <div class="row" style="margin-top: 40px;">
                    <div class="col-md-3 col-sm-3 col-xs-3" align="center">
                      <p>------------------------------</p>
                      <p>Employee</p>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-3" align="center">
                      <p>------------------------------</p>
                      <p>Prepared By</p>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-3" align="center">
                      <p>------------------------------</p>
                      <p>Verified By</p>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-3" align="center">
                      <p>------------------------------</p>
                      <p>Authorized By</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-12 col-md-12 col-xs-12" style="text-align: center; margin-top: 20px;">
                <a href="javascript:void(0)" onclick="printDiv('print')" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
                <a href="<?php echo site_url('empLoan') ?>" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Back</a>
              </div>   
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<?php $this->load->view('footer/footer'); ?>

    <?php
      function convertNumberToWordsForIndia($number){
        $words = array(
          '0'=> '' ,'1'=> 'one' ,'2'=> 'two' ,'3' => 'three','4' => 'four','5' => 'five','6' => 'six','7' => 'seven','8' => 'eight','9' => 'nine','10' => 'ten','11' => 'eleven','12' => 'twelve','13' => 'thirteen','14' => 'fouteen','15' => 'fifteen','16' => 'sixteen','17' => 'seventeen','18' => 'eighteen','19' => 'nineteen','20' => 'twenty','30' => 'thirty','40' => 'fourty','50' => 'fifty','60' => 'sixty','70' => 'seventy','80' => 'eighty','90' => 'ninty');
    
        $number_length = strlen($number);
        $number_array = array(0,0,0,0,0,0,0,0,0);        
        $received_number_array = array();
    
        for($i=0;$i<$number_length;$i++){    
          $received_number_array[$i] = substr($number,$i,1);    
          }

        for($i=9-$number_length,$j=0;$i<9;$i++,$j++){ 
          $number_array[$i] = $received_number_array[$j]; 
          }
        $number_to_words_string = "";

        for($i=0,$j=1;$i<9;$i++,$j++){
          if($i==0 || $i==2 || $i==4 || $i==7){
            if($number_array[$j]==0 || $number_array[$i] == "1"){
              $number_array[$j] = intval($number_array[$i])*10+$number_array[$j];
              $number_array[$i] = 0;
              }
            }
          }
        $value = "";
        for($i=0;$i<9;$i++){
          if($i==0 || $i==2 || $i==4 || $i==7){    
            $value = $number_array[$i]*10; 
            }
          else{ 
            $value = $number_array[$i];    
            }            
          if($value!=0){$number_to_words_string.= $words["$value"]." ";}
          if($i==1 && $value!=0){$number_to_words_string.= "Crores ";}
          if($i==3 && $value!=0){$number_to_words_string.= "Lakhs ";}
          if($i==5 && $value!=0){$number_to_words_string.= "Thousand ";}
          if($i==6 && $value!=0){$number_to_words_string.= "Hundred ";}
          }
        if($number_length>9){ $number_to_words_string = "Sorry This does not support more than 99 Crores"; }
        return ucwords(strtolower($number_to_words_string)." Taka Only.");
        }
    ?>