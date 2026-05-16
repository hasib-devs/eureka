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
            <br>
            <br>
            <br>
            <p class="text-center text_font"><b style="font-size:20px;">TO WHOM IT MAY CONCERN</b></p>
            <br>
            <div class="row">
                <div class="col-md-4 col-print-4"> <b>Memo no : <?php echo $prints['invoice']; ?></b> </div>
                <div class="col-md-2 col-print-2"></div>
                <div class="col-md-3 col-print-3"></div>
                <div class="col-md-3 col-print-3"><b><?php echo 'Date: '.date('d/m/Y', strtotime($prints['saDate'])); ?></b></div>
            </div>
            <br>

            <div class="row">

                <p style="line-height:25px">TO<br><span>THE ASSTT. DIRECTOR (REGISTRATION)<span><br>
                            BANGLADESH ROAD TRANSPORT AUTHORITY<br>

                            <b>SUB: REGISTRATION <?php echo $salesp['pName'].' '.$salesp['capacity'].' CC'; ?> MOTORCYCLE</b><br>
                            DEAR SIR<br>
                            WE HAVE SOLD <?php echo $salesp['quantity'].' '.$salesp['pName'].' '.$salesp['capacity'].' CC'; ?><br>
                            NAME : <span style="text-transform: uppercase;font-weight:bold;"><?php echo $prints['custName']; ?></span><br>
                            FATHER/HUSBAND : <span style="text-transform: uppercase;font-weight:bold;"><?php echo $prints['custfName']; ?></span><br>
                            ADDRESS : <span style="text-transform: uppercase;font-weight:bold;"><?php echo $prints['custAddress']; ?></span><br>
                            MOBILE NO : <span style="font-weight:bold;"><?php echo $prints['custMobile']; ?></span></span></span></p>
                <br>

                <div class="row row_border">
                    <div class="col-md-4 col-print-4 bio-data">01. BRAND : </div>
                    <div class="col-md-8 col-print-8 bio-data">?????????</div>
                </div>

                <div class="row row_border">
                    <div class="col-md-4 col-print-4 bio-data">02. MODEL :</div>
                    <div class="col-md-8 col-print-8 bio-data"><?php echo $salesp['pName'].' '.$salesp['capacity'].' CC'; ?></div>
                </div>

                <div class="row row_border">
                    <div class="col-md-4 col-print-4 bio-data">03. TYPE OF BODY :</div>
                    <div class="col-md-8 col-print-8 bio-data">M/C</div>
                </div>

                <div class="row row_border">
                    <div class="col-md-4 col-print-4 bio-data">04. CLASS OF BODY :</div>
                    <div class="col-md-8 col-print-8 bio-data">M/C</div>
                </div>

                <div class="row row_border">
                    <div class="col-md-4 col-print-4 bio-data">05. Chassis No :</div>
                    <div class="col-md-8 col-print-8 bio-data"><b><?php echo $salesp['pChassis']; ?></b></div>
                </div>

                <div class="row row_border">
                    <div class="col-md-4 col-print-4 bio-data">06. Engine No : </div>
                    <div class="col-md-8 col-print-8 bio-data"><b><?php echo $salesp['pEngine']; ?></b></div>
                </div>
                <div class="row row_border">
                    <div class="col-md-4 col-print-4 bio-data">07. Color of Vehicle :</div>
                    <div class="col-md-8 col-print-8 bio-data"><?php echo $salesp['pColor']; ?></div>
                </div>

                <div class="row row_border">
                    <div class="col-md-4 col-print-4 bio-data">08. NUMBER OF CYLINDER :</div>
                    <div class="col-md-8 col-print-8 bio-data"> <?php echo $salesp['nCylinder']; ?></div>
                </div>

                <div class="row row_border">
                    <div class="col-md-4 col-print-4 bio-data">09. CUBIC CAPACITY :</div>
                    <div class="col-md-8 col-print-8 bio-data"> <?php echo $salesp['capacity']; ?></div>
                </div>

                <div class="row row_border">
                    <div class="col-md-4 col-print-4 bio-data">10. FUEL USED :</div>
                    <div class="col-md-8 col-print-8 bio-data"><span><?php echo $salesp['fUsed']; ?></span></div>
                </div>

                <div class="row row_border">
                    <div class="col-md-4 col-print-4 bio-data">11. YEAR OF MANUFACTURE :</div>
                    <div class="col-md-8 col-print-8 bio-data"><?php echo $salesp['manufacture']; ?></div>
                </div>

                <div class="row row_border">
                    <div class="col-md-4 col-print-4 bio-data">12. MAKERS COUNTRY :</div>
                    <div class="col-md-8 col-print-8 bio-data"><?php echo $salesp['mkCountry']; ?></div>
                </div>

                <div class="row row_border">
                    <div class="col-md-4 col-print-4 bio-data">13. UNLADEN WEIGHT :</div>
                    <div class="col-md-8 col-print-8 bio-data"><?php echo $salesp['uWeight'].' KG'; ?></div>
                </div>

                <div class="row row_border">
                    <div class="col-md-4 col-print-4 bio-data">14. SEATS (INCLUDE DRIVER) :</div>
                    <div class="col-md-8 col-print-8 bio-data"><?php echo $salesp['seats']; ?></div>
                </div>

                <div class="row row_border">
                    <div class="col-md-4 col-print-4 bio-data">15. WHEEL BASE :</div>
                    <div class="col-md-8 col-print-8 bio-data"><?php echo $salesp['wBase']; ?></div>
                </div>

                <div class="row" style="margin-top: 40px;">

                    <div class="col-md-8 col-print-9">
                        <h5></h5>
                    </div>
                    <div class="col-md-3 col-print-3">
                        <h5>Authorized Signature</h5>
                    </div>

                </div>

            </div>

        </div>

        <br>
    </div>

    <script src="js/bootstrap.min.js"></script>

</div>

<?php $this->load->view('footer/regFooter'); ?>