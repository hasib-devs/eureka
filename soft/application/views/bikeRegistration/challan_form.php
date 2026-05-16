<?php $this->load->view('header/regHeader'); ?>

<style type="text/css">
    .fm_bg {
        font-size: 13px;
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
        line-height: 1.3;
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
        font-size: 15px;
        padding: 0px 0px !important;
    }

    .text_font tr,
    th,
    td {
        font-size: 13px !important;
    }

    .bio-data {
        line-height: 2.0em;
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

    img.watermarkimage {
        display: block;
        position: absolute;
        opacity: .09;
        left: 135px !important;
        top: 472px;
        width: 522px;
        z-index: 100;
    }

    .container {
        width: 21cm;
        height: 29.7cm;
    }
    .gap{
        margin-top:200px;
    }
</style>


<div class="container fm_bg page">

    <div class="row">
        <div class="col-md-12 col-print-12">
            <!-- <img class="watermarkimage" src="https://abmhost.xyz/nasirtvs/bike/public/images/tvs.png" alt=""> -->

            <!-- <img src="https://abmhost.xyz/demo/bajaj/bike/public/uploads/company_logo7.jpg" alt=""> -->
            <div class="gap"></div>
            <p class="text-center text_font"><b>DELIVERY CHALLAN</b></p>
            <br>
            <br>
            <div class="row">
                <div class="col-md-4 col-print-4"> <b>Memo no : <?php echo $prints['invoice']; ?></b> </div>
                <div class="col-md-2 col-print-2"></div>
                <div class="col-md-3 col-print-3"></div>
                <div class="col-md-3 col-print-3"><b><?php echo 'Date: '.date('d/m/Y', strtotime($prints['saDate'])); ?></b></div>
            </div>
            <br>

            <div class="row banar">
                <div class="col-md-3 col-print-3">Name :</div>
                <div class="col-md-4 col-print-4" style="text-transform: uppercase;font-weight:bold;"><?php echo $prints['custName']; ?></div>
                <div class="col-md-2 col-print-2">Phone no :</div>
                <div class="col-md-3 col-print-3" style="font-weight:bold"><?php echo $prints['custMobile']; ?></div>
            </div>
            <div class="row banar">
                <div class="col-md-3 col-print-3">Father/Husband Name :</div>
                <div class="col-md-4 col-print-4" style="text-transform: uppercase;font-weight:bold;"><?php echo $prints['custfName']; ?></div>
                <div class="col-md-2 col-print-2">NID no :</div>
                <div class="col-md-3 col-print-3" style="font-weight:bold"><?php echo $prints['custNid']; ?></div>
            </div>
            <div class="row banar">
                <div class="col-md-3 col-print-3">Address :</div>
                <div class="col-md-9 col-print-9" style="text-transform: uppercase;font-weight:bold;"><?php echo $prints['custAddress']; ?></div>

            </div>
            <br>

            <div class="row">
                <div class="col-md-12 col-print-12 text-justify">Please Receive the undermentioned vehicles / with
                    standard / Extra tools with spare wheel and accessories on the following particulars :</div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-md-4 col-print-4 bio-data">01. Chassis No :</div>
                <div class="col-md-8 col-print-8 bio-data"><b><?php echo $salesp['pChassis']; ?></b></div>
            </div>

            <div class="row">
                <div class="col-md-4 col-print-4 bio-data">02. Engine No : </div>
                <div class="col-md-8 col-print-8 bio-data"><b><?php echo $salesp['pEngine']; ?></b></div>
            </div>

            <div class="row">
                <div class="col-md-4 col-print-4 bio-data">03. Make &amp; Model of Vehicle :</div>
                <div class="col-md-8 col-print-8 bio-data"><span><?php echo $salesp['pName']; ?></span></div>
            </div>

            <div class="row">
                <div class="col-md-4 col-print-4 bio-data">04. Year of Manufacture :</div>
                <div class="col-md-8 col-print-8 bio-data"><?php echo $salesp['manufacture']; ?></div>
            </div>

            <div class="row">
                <div class="col-md-4 col-print-4 bio-data">05. No. of Cylinder with CC :</div>
                <div class="col-md-8 col-print-8 bio-data"><?php echo $salesp['nCylinder'].' Cylinder(s) '.$salesp['capacity'].' CC'; ?></div>
            </div>

            <div class="row">
                <div class="col-md-4 col-print-4 bio-data">06. Seating Capacity :</div>
                <div class="col-md-8 col-print-8 bio-data"><?php echo $salesp['seats']; ?></div>
            </div>

            <div class="row">
                <div class="col-md-4 col-print-4 bio-data">07. Color of Vehicle :</div>
                <div class="col-md-8 col-print-8 bio-data"><?php echo $salesp['pColor']; ?></div>
            </div>

            <div class="row">
                <div class="col-md-4 col-print-4 bio-data">08. Tyre Size :</div>
                <div class="col-md-8 col-print-8 bio-data"><?php echo $salesp['sTire']; ?></div>
            </div>

            <br>

            <div class="row banar bnar_bottom">
                <div class="col-md-12" style=""><textarea style="width:100%"></textarea></div>
                <!--<div class="col-md-12" style=""><textarea style="width:100%">REMARKS : SERVICE BOOK,  DOCUMENT COVER,  M/C GATE PASS</textarea></div>-->
            </div>
            <br>
            <div class="row">
                <div class="col-md-12 col-print-12 text-justify">Received with thanks the above mentioned Vehicle (s)
                    with perfect condition along with tools and accessories.</div>
            </div>

            <div class="row" style="margin-top: 150px;">
                <div class="row">
                    <div class="col-md-6 col-print-6">
                        <h5></h5>
                    </div>
                    <div class="col-md-6 col-print-6 text-right">
                        <h5>Authorized Signature</h5>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<?php $this->load->view('footer/regFooter'); ?>