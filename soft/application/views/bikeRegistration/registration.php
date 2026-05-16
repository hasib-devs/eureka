<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>
<div class="content-wrapper">


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

                        <div class="card-body">
                            <table id="example" class="table table-responsive table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#SN.</th>
                                        <th>Date</th>
                                        <th>Bike Name</th>
                                        <th>Customer</th>
                                        <th>Phone</th>
                                        <th>Chassis</th>
                                        <th>Engine</th>
                                        <th>Sale Details</th>
                                        <!-- <th style="width: 10%;">Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                    $i = 0;
                    foreach ($sales as $value){
                    $i++;
                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo date('d-M-Y',strtotime($value['saDate'])); ?></td>
                                        <td><?php echo $value['pName']; ?></td>
                                        <td><?php echo $value['custName']; ?></td>
                                        <td><?php echo $value['custMobile']; ?></td>
                                        <td><?php echo $value['spChassis']; ?></td>
                                        <td><?php echo $value['spEngine']; ?></td>
                                        <td>
                                            <a class="btn btn-info btn-sm"
                                                href="<?php echo base_url().'regForm/'.$value['said']; ?>"><?php echo $value['invoice']; ?></a>
                                            <!-- <a class="btn btn-success btn-sm"
                                                href="<?php echo base_url().'editSale/'.$value['said']; ?>"><i
                                                    class="fa fa-edit"></i></a>
                                            <a class="btn btn-danger btn-sm"
                                                href="<?php echo base_url('Sale/delete_sales').'/'.$value['said']; ?>"
                                                onclick="return confirm('Are you sure you want to delete this Sales ?');"><i
                                                    class="fa fa-trash"></i></a>
                                            <?php if($value['dAmount'] > 0){ ?>
                                            <a href="#" class="payment btn btn-warning btn-sm" data-toggle="modal"
                                                data-target=".bs-example-modal-payment"
                                                data-id="<?php echo $value['said']; ?>"
                                                id="<?php echo $value['said']; ?>"
                                                onclick="document.getElementById('payment').style.display='block'"><i
                                                    class="fa fa-plus"></i></a>
                                            <?php } ?> -->
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

<!-- <div id="payment" class="modal fade bs-example-modal-payment" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> Payment Information</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
            </div>
            <form action="<?php echo base_url('Sale/save_sales_payment');?>" method="post">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>Due Amount</label>
                        <input type="text" class="form-control" name="tAmount" id="tAmount" readonly>
                    </div>
                    <div class="form-group">
                        <label>Paid Amount *</label>
                        <input type="text" class="form-control" name="pAmount" id="pAmount" placeholder="Amount"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <input type="text" class="form-control" name="notes" placeholder="If Have any Notes">
                    </div>
                </div>
                <input type="hidden" id="said" name="said" required>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="pbsubmit"><i
                            class="far fa-save"></i>&nbsp;&nbsp;Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                            class="far fa-window-close"></i>&nbsp;&nbsp;Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div> -->

<?php $this->load->view('footer/regFooter'); ?>

<?php $this->load->view('footer/footer'); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.payment', function() {
            var said = $(this).attr("id");
            //alert(l_id);
            $('input[name="said"]').val(said);
        });
        $(document).on('click', '.payment', function() {
            var id = $(this).attr("id");
            //alert(id);
            var url = "<?php echo base_url(); ?>Sale/get_sales_payment";
            //alert(url);
            $.ajax({
                method: "POST",
                url: url,
                dataType: "json",
                data: {
                    'id': id
                },
                success: function(data) {
                    //alert(data);
                    var HTML = data;
                    //alert(HTML2);
                    $("#tAmount").val(HTML);
                    $("#pAmount").val(HTML);
                },
                error: function() {
                    alert('error');
                }
            });
        });
    });
</script>