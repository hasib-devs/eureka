<?php $this->load->view('header/regHeader'); ?>
<style type="text/css">
    .fm_bg {
        font-size: 12px;
    }

    br {
        display: block;
        margin: 18px 0px !important;
        content: "";
    }

    .bio-data-box {
        line-height: 2.0em;
        border: 1px solid #797878;
        min-height: 33px;
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
        font-size: 15px;
        padding: 0px 0px !important;
    }

    .text_font tr,
    th,
    td {
        font-size: 13px !important;
    }

    .bio-data {
        line-height: 15px;
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


<div class="container fm_bg page" style="margin-top:00px;">
    <p class="text-center text_font"><b style="font-size: 25px;">OWNER'S PARTICULARS/SPEIMEN SIGNATURE</b></p>
    <div class="row">
        <div class="col-md-2"></div>

        <div class="col-md-8 col-print-12">

            <br>
            <br>
            <br>
            <br>
            <br>

            <div class="row" style="margin-top: 100px;">
                <div class="col-md-12">
                    <div class="photo">
                        <p class="text-center text_font" style="font-size: 10px;">3 Copies<br> Photograph</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 bio-data col-print-4">01. NAME (BLOCK LETTER) :</div>
                <div class="col-md-8 bio-data col-print-8" style="text-transform: uppercase;">
                    <b><?php echo $prints['custName']; ?></b>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-4 bio-data col-print-4">02. FATHER'S NAME :</div>
                <div class="col-md-8 bio-data col-print-8" style="text-transform: uppercase;">
                    <b><?php echo $prints['custfName']; ?></b>
                </div>

            </div>
            <br>

            <div class="row">
                <div class="col-md-4 bio-data col-print-4">03. Mother's Name :</div>
                <div class="col-md-8 bio-data col-print-8" style="text-transform: uppercase;">
                    <b><?php echo $prints['custmName']; ?></b>
                </div>

            </div>
            <br>

            <div class="row">
                <div class="col-md-4 bio-data col-print-4">04. HUSBAND NAME'S NAME :</div>
                <div class="col-md-8 bio-data col-print-8" style="text-transform: uppercase;">
                    <b><?php echo $prints['spouse']; ?></b>
                </div>

            </div>

            <br>

            <div class="row">
                <div class="col-md-4 bio-data col-print-4">05. PRESENT ADDRESS (WITH SUPPORTING DOCUMENTS) :</div>
                <div class="col-md-8 bio-data col-print-8" style="text-transform: uppercase;">
                    <b><?php echo $prints['custAddress']; ?></b>
                </div>
                

            </div>
            <br>

            <div class="row">
                <div class="col-md-4 bio-data col-print-4">06. PERMANENT ADDRESS :</div>

                <div class="col-md-8 bio-data col-print-8" style="text-transform: uppercase;">
                    <b><?php echo $prints['custpAddress']; ?></b>
                </div>

            </div>

            <br>

            <div class="row">
                <div class="col-md-4 bio-data col-print-4">07. SEX :</div>
                <div class="col-md-8 bio-data col-print-8" style="text-transform: uppercase;"><b><?php echo $prints['custGender']; ?></b></div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-4 bio-data col-print-4">08. CELL PHONE NO :</div>
                <div class="col-md-8 bio-data col-print-8"><b><?php echo $prints['custMobile']; ?></b></div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-4 bio-data col-print-4">09. NATIONALITY :</div>
                <div class="col-md-8 bio-data col-print-8" style="text-transform: uppercase;"><b><?php echo $prints['custNation']; ?></b></div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-4 bio-data col-print-4">10. DATE OF BIRTH :</div>
                <div class="col-md-8 bio-data col-print-8" style="text-transform: uppercase;"><b><?php echo $prints['custDob']; ?></b></div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-4 bio-data col-print-4">11. NID NO. (WITH COPY) :</div>
                <div class="col-md-8 bio-data col-print-8">
                    <b><?php echo $prints['custNid']; ?></b>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-4 bio-data col-print-4">12. e-TIN NO (WITH COPY) :</div>
                <div class="col-md-8 bio-data col-print-8"></div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-4 bio-data col-print-4">13. GUARDIAN'S NAME :</div>
                <div class="col-md-8 bio-data col-print-8"></div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-4 bio-data col-print-4">14. VEHICLE REGISTRATION NO<br>(In case of ownership
                    transfer) :</div>
                <div class="col-md-8 bio-data col-print-8"></div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-4 bio-data col-print-4">15. CHASSIS NO :</div>
                <div class="col-md-8 bio-data col-print-8" style="text-transform: uppercase;"><b><?php echo $salesp['pChassis']; ?></b>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-4 bio-data col-print-4">16. ENGINE NO : </div>
                <div class="col-md-8 bio-data col-print-8" style="text-transform: uppercase;"><b><?php echo $salesp['pEngine']; ?></b></div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-4 bio-data col-print-4">17. YEAR OF MFG of Vehicle :</div>
                <div class="col-md-8 bio-data col-print-8"><b><?php echo $salesp['manufacture']; ?></b></div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-4 bio-data col-print-4">18. PREV REGN NO (If any)(In case of Reconditioned
                    vehicle/Special Registration) :</div>
                <div class="col-md-8 bio-data col-print-8"><?php echo $salesp['prNumber']; ?></div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-4 bio-data col-print-4">19. BANK NAME FOR Fee/Tax deposit :</div>
                <div class="col-md-8 bio-data col-print-8"></div>
            </div>

            <br>
            <br>

            <div class="row">
                <div class="col-md-4 bio-data col-print-4"><strong>SPECIMEN SIGNATURE</strong></div>
                <div class="col-md-8 bio-data col-print-8"></div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-1 bio-data col-print-1">1.</div>
                <div class="col-md-5 bio-data bio-data-box col-print-5"></div>
                <div class="col-md-1 bio-data col-print-1">2.</div>
                <div class="col-md-5 bio-data bio-data-box col-print-5"></div>
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <div class="row">
                <div class="col-md-1 bio-data col-print-1">3.</div>
                <div class="col-md-5 bio-data bio-data-box col-print-5"></div>
                <div class="col-md-1 bio-data col-print-1">4.</div>
                <div class="col-md-5 bio-data bio-data-box col-print-5"></div>
            </div>

        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <div class="col-md-2" col-print-12=""></div>
    </div>
    <br>
</div>

<?php $this->load->view('footer/regFooter'); ?>