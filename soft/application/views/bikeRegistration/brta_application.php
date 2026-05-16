<?php $this->load->view('header/regHeader'); ?>
<style type="text/css">
    .fm_bg {
        font-size: 13px;
        margin-left: 40px !important;
    }

    img {
        width: 100%;
    }

    .cus_tbl>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
        padding: 2px;
        line-height: 1.4;
        vertical-align: top;
    }

    .cus_tbl {
        font-size: 12px;
    }

    .table tr,
    th,
    td {
        border: 1px solid #ddd;
        width: 180px;
        height: 10px !important;

    }

    .table tr,
    th,
    td {
        padding: 0px 5px !important;

    }

    ul {
        padding-left: 10px !important;
    }

    .text_font {
        margin: 0px 0px !important;
        font-size: 12px;
        padding: 0px 0px !important;
    }

    .text_font tr,
    th,
    td {
        font-size: 12px !important;
    }

    .bio-data {
        line-height: 23px;
        /* border-right: 1px solid #cdcdcd; */
    }

    .row_border {
        border: 1px solid #cdcdcd;
    }

    .photo {
        width: 100px;
        height: 120px;
        border: 1px solid black;
        float: right;
        overflow: hidden;
        clear: both;
        display: inline-block;
        margin: -90px 30px 0px 0px;
    }

    .banar {
        background: #f3f3f3;
        line-height: 2.4em;
    }

    .bnar_bottom {
        height: 80px;
    }

    .table1 tr td {
        height: 50px !important;
        line-height: 25px !important;
    }

    .table1 {
        margin-top: 20px;
        width: 90%;
        margin: 0 auto;

    }
</style>


<div class="container fm_bg page" style="margin-top:10px;">

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8 col-print-12">

            <div style="margin-top:70px"></div>
            <div class="row">

                <p style="line-height:25px">TO<br><span>THE ASSTT. DIRECTOR<span><br>
                            <span style="text-transform:uppercase;">B.R.T.A & REGISTRATION AUTHORITY <?php echo $prints['brtaDis']; ?></span><br><br>

                            <b>Sub: REGISTRATION OF A NEW SOLD MOTORCYCLE</b><br><br>
                            Dear sir<br>
                            We have the pleasure to certify having one unit MOTORCYCLE/HERO/HONDA/HONDA/YAMAHA/TVS/PIAGGIO/DAYANG/JIALING:<br>


                            <br>
                            <div class="row">
                                <div class="col-md-4 col-print-4 bio-data">ENGINE NO </div>
                                <div class="col-md-8 col-print-8 bio-data">: <b><?php echo $salesp['pEngine']; ?></b></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-print-4 bio-data">CHASSIS NO</div>
                                <div class="col-md-8 col-print-8 bio-data">: <b><?php echo $salesp['pChassis']; ?></b></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-print-4 bio-data">COLOUR</div>
                                <div class="col-md-8 col-print-8 bio-data">: <?php echo $salesp['pColor']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-print-4 bio-data">MODEL</div>
                                <div class="col-md-8 col-print-8 bio-data">: <?php echo $salesp['pName']; ?></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-print-4 bio-data">CC</div>
                                <div class="col-md-8 col-print-8 bio-data">: <?php echo $salesp['capacity']; ?></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-print-4 bio-data">WEIGHT</div>
                                <div class="col-md-8 col-print-8 bio-data">: <?php echo $salesp['uWeight'].' KG'; ?></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-print-4 bio-data">CYLINDER CAPACITY</div>
                                <div class="col-md-8 col-print-8 bio-data">: <?php echo $salesp['nCylinder']; ?></div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-print-4 bio-data">SEATING CAPACITY</div>
                                <div class="col-md-8 col-print-8 bio-data">: <?php echo $salesp['seats']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-print-4 bio-data">HAS BEEN SOLD TO</div>
                                <div class="col-md-8 col-print-8 bio-data">: <?php echo $prints['custName']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-print-4 bio-data">FATHER NAME</div>
                                <div class="col-md-8 col-print-8 bio-data">: <?php echo $prints['custfName']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-print-4 bio-data">ADDRESS</div>
                                <div class="col-md-8 col-print-8 bio-data">: <?php echo $prints['custAddress']; ?></div>
                            </div>

                            <!-- <div class="row">
                    <div class="col-md-4 col-print-4 bio-data">BRAND </div>
                    <div class="col-md-8 col-print-8 bio-data">BAJAJ 4 STOKE MOTORCYCLE</div>
                </div>

          

                <div class="row">
                    <div class="col-md-4 col-print-4 bio-data">TYPE OF BODY</div>
                    <div class="col-md-8 col-print-8 bio-data">M/C</div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-print-4 bio-data">CLASS OF BODY</div>
                    <div class="col-md-8 col-print-8 bio-data">M/C</div>
                </div>


                
                

               

                

                <div class="row">
                    <div class="col-md-4 col-print-4 bio-data">10. FUEL USED</div>
                    <div class="col-md-8 col-print-8 bio-data"><span>PATROL</span></div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-print-4 bio-data">11. YEAR OF MANUFACTURE</div>
                    <div class="col-md-8 col-print-8 bio-data">2021</div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-print-4 bio-data">12. MAKERS COUNTRY</div>
                    <div class="col-md-8 col-print-8 bio-data">INDIA</div>
                </div>

                

             

                <div class="row">
                    <div class="col-md-4 col-print-4 bio-data">15. WHEEL BASE</div>
                    <div class="col-md-8 col-print-8 bio-data">1305</div>
                </div> -->

                            <br>
                            

                                <!--<p style="line-height:25px">-->
                                <!--    NAME : <span style="text-transform: uppercase;font-weight:bold;"><?php echo $prints['custName']; ?></span><br>-->
                                <!--    FATHER/HUSBAND : <span-->
                                <!--        style="text-transform: uppercase;font-weight:bold;"><?php echo $prints['custfName']; ?></span><br>-->
                                <!--    ADDRESS : <span style="text-transform: uppercase;font-weight:bold;"><?php echo $prints['custAddress']; ?></span><br>-->
                                <!--</p>-->

                                <p>
                                    It is requested that the above Motorcycle may kindly be Registered in the Name of the purchaser.
                                </p>

                            <div class="row" style="margin-top: 40px;">

                                <div class="col-md-8 col-print-9">
                                    <p>----------------------------------</p>
                                    <h5>THANK YOU</h5>
                                </div>
                                <div class="col-md-3 col-print-3">
                                    <p>-----------------------------</p>
                                    <h5>YOURS FAITHFULLY</h5>
                                </div>

                            </div>

            </div>

        </div>

        <br>
    </div>

    <script src="js/bootstrap.min.js"></script>

</div>

<?php $this->load->view('footer/regFooter'); ?>