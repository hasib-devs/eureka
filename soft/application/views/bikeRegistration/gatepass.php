<?php $this->load->view('header/regHeader'); ?>

<style type="text/css">
    .fm_bg {
        font-size: 13px;
    }

    .cus_font {
        font-family: 'Roboto', sans-serif;
    }

    .fontbold {
        font-weight: bold;
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
        font-size: 12px;
        padding: 0px 0px !important;
    }

    .text_font tr,
    th,
    td {
        font-size: 12px !important;
    }

    .bio-data {
        line-height: 2.0em;
        font-size: 11px;
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
        text-transform:uppercase;

    }

    .bnar_bottom {
        min-height: 80px;
        padding: 20px;
        border-radius: 10px;
        height: 95px;
        margin-top: 15px;
    }

    .table1 tr td {
        height: 35px !important;
        line-height: 35px !important;
    }

    .table1 {
        margin-top: 20px;
        width: 90%;
        margin: 0 auto;

    }

    td {
        padding: 5px !important;
        border: 1px solid #cdcdcd !important;
        text-align:center;
    }

    table th {
        padding: 5px !important;
        border: 1px solid #cdcdcd !important;
        text-transform:uppercase;
    }

    img.watermarkimage {
        display: block;
        position: absolute;
        opacity: .15;
        left: 135px !important;
        top: 405px;
        width: 522px;
        z-index: 100;
    }

    .container {
        width: 21cm;
        height: 29.7cm;
        /* border: 1px solid #cdcdcd; */
    }
</style>


<div class="container fm_bg page" style="margin-top:50px;"> <br>

    <div class="row">
        <div class="col-md-12 col-print-12">

            <br>
            <br>
            <br>
            <h2 class="text-center" style="font-size: 32px;font-weight: bold;"><u>GATE PASS</u></h2>
            <br>
            <br>
            <div class="row">
                <div class="col-md-12 col-print-12"><b style="float:right;">Date : <?php echo date('d/m/Y');?></b></div>
            </div>
            <br>
            <br>
            <br>

            <div class="row banar">
                <div class="col-md-3 col-print-3 fontbold">To</div>

            </div>
            <div class="row banar">
                <div class="col-md-3 col-print-3 fontbold">Name :</div>
                <div class="col-print-9"><?php echo $prints['custName']; ?></div>

            </div>
            <div class="row banar">
                <div class="col-md-3 col-print-3 fontbold">Father/Husband:</div>
                <div class="col-print-9"><?php echo $prints['custfName']; ?></div>
            </div>
            <div class="row banar">
                <div class="col-md-3 col-print-3  fontbold">Address :</div>
                <div class="col-print-9"><?php echo $prints['custAddress']; ?></div>

            </div>

            <table style="margin-top: 50px;">
                <tbody>
                    <tr>
                        <th style="width: 5%;">SL</th>
                        <th style="width: 50%;">Description</th>
                        <th style="width: 10%;">Engine No</th>
                        <th style="width: 15%;">Chassis No</th>
                        <th style="width: 20%;">Colour</th>
                        <th style="width: 20%;">Qty</th>
                    </tr>

                    <tr class="item-row">
                        <td>1</td>
                        <td style="text-align:left;"><?php echo 'Brand New'.$salesp['catName'].'<br>Brand: '.$salesp['pName'].'<br>Model: '.$salesp['pCode'].'<br>CC: '.$salesp['capacity'].'<br>Origin: '.$salesp['mkCountry'].'<br>Weight: '.$salesp['uWeight'].' Kg'; ?></td>
                        <td><?php echo $salesp['pEngine']; ?></td>
                        <td><?php echo $salesp['pChassis']; ?></td>
                        <td><?php echo $salesp['pColor']; ?></td>
                        <td><?php echo $salesp['quantity']; ?></td>
                    </tr>

                   

                </tbody>
            </table>

            <div class="row" style="margin-top: 100px;">
                <div class="row">
                    <div class="col-md-6 col-print-6" style="text-align:center;">
                    <p>---------------------------------------------</p>
                        <h5>Prepared By</h5>
                    </div>
                    <div class="col-md-6 col-print-6 text-right" style="text-align:center;">
                        <p>---------------------------------------------</p>
                        <h5>Authorized Signature</h5>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php $this->load->view('footer/regFooter'); ?>