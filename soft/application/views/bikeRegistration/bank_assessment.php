<?php $this->load->view('header/regHeader'); ?>

    <style type="text/css">
        .fm_bg {
            border: 1px solid #ddd;
            font-size: 13px;
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
            border: 1px solid #000 !important;
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
            font-size: 11px !important;
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


        .fm_bg {
            border: 1px solid #ddd;
            font-size: 11px;
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
            padding: 0px 3px !important;

        }

        ul {
            padding-left: 10px !important;
        }

        .text_font {
            margin: 0px 0px !important;
            font-size: 11x;
            padding: 0px 0px !important;
        }

        .text_font tr,
        th,
        td {
            font-size: 11px !important;
        }
    </style>

    
    <div class="container fm_bg page">

        <h4 class="text-center text_font" style="font-size: 11px;font-weight: bold; padding: 1px 0px;">Govemment of the
            People's Republic of Bangladesh</h4>
        <h4 class="text-center text_font" style="font-size: 16px;font-weight: bold; padding: 1px 0px;">Bangladesh Road
            Transport Authority</h4>
        <h4 class="text-center text_font" style="font-size: 11px;font-weight: bold; padding: 1px 0px;">Allenbury,
            Tejgaon,
            Dhaka-1215</h4>
        <h4 class="text-center text_font" style="font-size: 11px;font-weight: bold; padding: 1px 0px;">(Assessment Form)
        </h4>
        <br>

        <table style="width:100%">
            <tbody>
                <tr>
                    <td style="width:20%;font-weight: bold; background:#ddd;">Name of Circle/Zone</td>
                    <td></td>
                    <td style="width:20%;font-weight: bold; background:#ddd;">District</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Branch Name</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <!--First Table-->
        <br>
        <table style="width:100%">
            <tbody>
                <tr>
                    <td style="font-weight: bold; background:#ddd;">Vehicle Information</td>
                    <td></td>
                    <td style="font-weight: bold; background:#ddd;">Permit Information</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Registration No</td>
                    <td>NEW</td>
                    <td>No. of Districts</td>
                    <td></td>
                </tr> <!-- Registration End -->

                <tr>
                    <td>Chassis No.</td>
                    <td style="font-weight: bold"><?php echo $salesp['pChassis']; ?></td>
                    <td>No. of Routes</td>
                    <td></td>
                </tr> <!-- Chassis No End -->

                <tr>
                    <td>Engine No.</td>
                    <td style="font-weight: bold"><?php echo $salesp['pEngine']; ?></td>
                    <td style="font-weight: bold; background:#ddd;">Driving License/Information</td>
                    <td></td>
                </tr> <!-- Engine No. End -->

                <tr>
                    <td>Vechicle Type</td>
                    <td>Motorcycle</td>
                    <td>License No.</td>
                    <td><?php echo $prints['custDriving']; ?></td>
                </tr> <!-- Vechicle Type -->

                <tr>
                    <td>Owner Name</td>
                    <td style="text-transform: uppercase;font-weight: bold"><?php echo $prints['custName']; ?></td>
                    <td>License Type</td>
                    <td></td>
                </tr> <!-- Owner Name -->

                <tr>
                    <td>Father's Name &amp; Address</td>
                    <td style="text-transform: uppercase;font-weight: bold"><?php echo $prints['custfName'].' & '.$prints['custAddress']; ?></td>
                    <td>Person Name</td>
                    <td></td>
                </tr> <!-- Father's Name & Address -->

                <tr>
                    <td>Ower Type</td>
                    <td></td>
                    <td>Father's Name &amp; Address</td>
                    <td></td>
                </tr> <!-- Ower Type -->

                <tr>
                    <td>Cubic Capacity/Weight</td>
                    <td style="font-weight: bold"><?php echo $salesp['capacity'].' CC / '.$salesp['uWeight'].' KG'; ?></td>
                    <td style="font-weight: bold"></td>
                    <td></td>
                </tr> <!-- Seating Capacity/Weight -->

                <tr>
                    <td>Depositor Name &amp; Address</td>
                    <td></td>
                    <td>Mobile</td>
                    <td><?php echo $prints['custMobile']; ?></td>
                </tr>
                <tr>
                    <td>Cylinder Capacity</td>
                    <td></td>
                    <td>No. of Districts</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <!--Vehicle Information Table-->

        <!-- Fee Assessment -->

        <br>
        <h4 class="text-center">Fee Assessment</h4>

        <table style="width:100%">
            <tbody>
                <tr>
                    <td style="width:2%; font-weight: bold">Sl No</td>
                    <td style="font-weight: bold">Fee Perticulars</td>
                    <td style="font-weight: bold">Main Fee</td>
                    <td style="font-weight: bold">Inspection Fee</td>
                    <td style="font-weight: bold">Late Fee</td>
                    <td style="font-weight: bold">Application Fee</td>
                    <td style="font-weight: bold">Late Fee</td>
                    <td style="font-weight: bold">Other Fee</td>
                    <td style="font-weight: bold">Total Fee</td>
                </tr>

                <tr>
                    <td rowspan="2">1</td>
                    <td rowspan="2">
                        a) New Registration <span style="font-size: 17px;font-weight: bold;">✓</span> <br>
                        b) Conversion of registration
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><input type="text" style="border:none;padding:0px"></td>
                </tr>
                <!-- 2 number section -->
                <tr>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td>2</td>
                    <td style="font-weight: bold">
                        Transfer of Ownership (TO)</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td rowspan="2">3</td>
                    <td rowspan="2">
                        a) Issue of Tax Token <br>
                        b) Renewal Certificate of Fitness
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td rowspan="2">4</td>
                    <td rowspan="2">
                        a) Issue of Certificate of Fitness (CF) <br>
                        b) Renewal Certificate of Fitness
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td rowspan="2">5</td>
                    <td rowspan="2">
                        a) Issue of Certificate (RP) Conlract<br>
                        (Camace.. Temporary of Permanent) <br>
                        b) Renewal of Route Permit
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td rowspan="8">6</td>
                    <td rowspan="8">
                        a) Issue of Driving License (DE) <br> Professional/Non-professional<br>
                        b) Renewal of Driving License <br>
                        Professional/Non-professional <br>
                        c) Issue/Renewal of Conductor License <br>
                        d) Issue/Renewal of Conductor Licens <br>
                        e) Registration of Driving School <br>
                        f) Issue of instructor License <br>
                        g) Others
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <!-- RowSpan Start -->
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- RowSpan End -->

                <!-- RowSpan Start -->
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- RowSpan End -->

                <!-- RowSpan Start -->
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- RowSpan End -->

                <!-- RowSpan Start -->
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- RowSpan End -->

                <!-- RowSpan Start -->
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- RowSpan End -->

                <!-- RowSpan Start -->
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- RowSpan End -->

                <!-- RowSpan Start -->
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- RowSpan End -->

                <tr>
                    <td>7</td>
                    <td style="font-weight: bold;">Miscellaneous</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                </tr>

                <tr>
                    <td rowspan="7"></td>

                    <td rowspan="7">
                        a) Engine Change<br>
                        b) Colour/Address Change <br>
                        c) Endorsement<br>
                        d) Any Correction (Specily) <br>
                        e) i) Issue of New Trade Certificate (Garage Number) <br>
                        ii) Renewal of Trade Certificate (Garage Number) <br>
                        iii) Issue of Duplicate Registration Certificate Duplicate DL/Duplicate C.F. <br>
                        Duplicate R.P/Duplicate Tax Token <br>
                        g) Others (Khat Name................)
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <!-- RowSpan Start -->
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- RowSpan End -->

                <!-- RowSpan Start -->
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- RowSpan End -->

                <!-- RowSpan Start -->
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- RowSpan End -->

                <!-- RowSpan Start -->
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- RowSpan End -->

                <!-- RowSpan Start -->
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- RowSpan End -->

                <!-- RowSpan Start -->
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- RowSpan End -->

            </tbody>
        </table>
        <!--Vehicle Information Table-->
        <br>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"
        integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous">
    </script>
</body>

</html>