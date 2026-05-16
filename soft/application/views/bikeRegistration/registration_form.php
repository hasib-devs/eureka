
<?php $this->load->view('header/regHeader'); ?>
    <style type="text/css">
    .fm_bg {
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
</style>
<div class="container fm_bg page" style="page-break-after: always;">

    <h4 class="text-center text_font" style="font-size: 15px;font-weight: bold; padding: 1px 0px;">FORM OF
        APPLICATION FOR THE REGISTRATION OF MOTOR VEHICLE</h4>
    <p class="text-center text_font">To be filled in by the Office</p>
    <p class="text-center text_font">Section-I</p>
    <br>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <table class="table cus_tbl">
                <tbody>
                    <tr>
                        <td class="">Reg. No :</td>
                        <td></td>
                        <td class="">Date :</td>
                        <td></td>
                        <td class="">Prev. Regn. No. :</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="">Issue No :</td>
                        <td></td>
                        <td class="">Data :</td>
                        <td></td>
                        <td class="">Issu by :</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="">Diary No :</td>
                        <td></td>
                        <td class="">Data :</td>
                        <td></td>
                        <td class="">Received by :</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="">Customer ID :</td>
                        <td></td>
                        <td class="">District :</td>
                        <td></td>
                        <td class="">Vehicle ID :</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="" colspan="4">Veh. Description :</td>
                        <td class="">Call non Data :</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="">Refusal Data :</td>
                        <td></td>
                        <td class="">Refusal Code :</td>
                        <td></td>
                        <td class="">Refusal by :</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="" colspan="4">P.O./Bank :</td>
                        <td class="">Index :</td>
                        <td class=""></td>

                    </tr>
                    <tr>
                        <td class="" colspan="4">Remarks :</td>
                        <td class="">Index No :</td>
                        <td class=""></td>

                    </tr>
                </tbody>
            </table>

        </div>
        <div class="col-md-2"></div>
    </div>

    <p class="text-center text_font">To be filled in by the Owner</p>
    <p class="text-center text_font">Section-II</p>
    <p class="text-center text_font" style="font-size: 10px;">(Owner Information)</p>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
        <!-- <?php var_dump($prints); ?> -->

            <table class="table cus_tbl">
                <tbody>
                    <tr>
                        <td class="">01. Name of Owner :</td>
                        <td style="text-transform: uppercase;"><b><?php echo $prints['custName']; ?></b></td>
                        <td class="">02. Date of Birth :</td>
                        <td><b><?php echo $prints['custDob']; ?></b></td>
                    </tr>
                    <tr>
                        <td class="">03. Father/Husband :</td>
                        <td style="text-transform: uppercase;"><b><?php echo $prints['custfName']; ?></b></td>
                        <td class="">04. Nationality :</td>
                        <td style="text-transform: uppercase;"><b><?php echo $prints['custNation']; ?></b></td>
                    </tr>
                    <tr>
                        <td class="">05. Sex :</td>
                        <td style="text-transform: uppercase;"><b><?php echo $prints['custGender']; ?></b></td>
                        <td class="">06. Guardian's Name :</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="">07. Owner's Address :</td>
                        <td colspan="3" style="text-transform: uppercase;"><b><?php echo $prints['custAddress']; ?></b></td>
                    </tr>
                    <tr>
                        <td class="">08. Phone No :</td>
                        <td style="text-transform: uppercase;"><b><?php echo $prints['custMobile']; ?></b></td>
                        <td class="">09. PO/Bank :</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="">10. Joint Owner :</td>
                        <td></td>
                        <td class="">11. Owner Type :</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="">12. Hire :</td>
                        <td></td>
                        <td class="">13. Hire Purchase :</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

        </div>
        <div class="col-md-2"></div>
    </div>


    <p class="text-center text_font">Section-III</p>
    <p class="text-center text_font" style="font-size: 10px;">(Vehicle Information)</p>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">

            <table class="table cus_tbl">
                <tbody>
                    <tr>
                        <td class="">14. Vehicle or Trailer :</td>
                        <td class=""><b><?php echo $salesp['pName']; ?></b></td>
                        <td class="">15. Prev. Regn. No :</td>
                        <td><?php echo $salesp['prNumber']; ?></td>
                    </tr>
                    <tr>
                        <td class="">14a. Class of vehiele :</td>
                        <td class=""> <b> M/C</b> </td>
                        <td class="">15a. Maker's Name :</td>
                        <td style="font-size: 10px !important;"> <b><?php echo $salesp['mkName']; ?></b> </td>
                    </tr>
                    <tr>
                        <td class="">16. Type of Body :</td>
                        <td class=""> <b>MOTOR CYCLE</b> </td>
                        <td class="">17. Maker's Country :</td>
                        <td style="text-transform: uppercase;"> <b><?php echo $salesp['mkCountry']; ?></b> </td>
                    </tr>

                    <tr>
                        <td class="">18. Color(cabin/body) :</td>
                        <td class=""> <b><?php echo $salesp['pColor']; ?></b> </td>
                        <td class="">19. Year of Manufacture :</td>
                        <td> <b><?php echo $salesp['manufacture']; ?></b> </td>
                    </tr>
                    <tr>
                        <td class="">20. Number of Cylinders :</td>
                        <td class=""> <b><?php echo $salesp['nCylinder']; ?></b> </td>
                        <td class="">21. Chassis Number :</td>
                        <td> <b><?php echo $salesp['pChassis']; ?></b> </td>
                    </tr>
                    <tr>
                        <td class="">22. Engine Number :</td>
                        <td class=""> <b><?php echo $salesp['pEngine']; ?></b> </td>
                        <td class="">23. Fuel Used :</td>
                        <td> <b><?php echo $salesp['fUsed']; ?></b> </td>
                    </tr>

                    <tr>
                        <td class="">24. Horse Power :</td>
                        <td class="" style="text-transform: uppercase;"> <b><?php echo $salesp['power']; ?></b> </td>
                        <td class="">25. RPM :</td>
                        <td> <b></b> </td>
                    </tr>

                    <tr>
                        <td class="">26. Fuel Tank Capacity :</td>
                        <td class="" style="text-transform: uppercase;"> <b><?php echo $salesp['tCapacity']; ?></b> </td>
                        <td class="">27. Seats (incl. driver) :</td>
                        <td> <b><?php echo $salesp['seats']; ?></b> </td>
                    </tr>

                    <tr>
                        <td class="">28. Cylinder Capacity :</td>
                        <td class=""> <b><?php echo $salesp['capacity']; ?></b> </td>
                        <td class="">29. Wheel Base :</td>
                        <td> <b><?php echo $salesp['wBase']; ?></b> </td>
                    </tr>
                    <tr>
                        <td class="">30. Unladen Weight(kg) :</td>
                        <td class=""> <b><?php echo $salesp['uWeight']; ?></b> </td>
                        <td class="">31. Maximum laden/train weight(kg):</td>
                        <td> <b> </b> </td>
                    </tr>

                </tbody>
            </table>

        </div>
    </div>

    <p class="text-center text_font">Section-IV</p>
    <p class="text-center text_font" style="font-size: 10px;">(Additional information for transport vehicle)</p>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">

            <table class="table cus_tbl" style="margin-bottom: -1px;">
                <tbody>
                    <tr>
                        <td class="" style="width:25%">32. No. of Tyres :</td>
                        <td class="" style="width:25%"><b>02</b></td>
                        <td class="" style="width:25%">33. Tyres Size :</td>
                        <td colspan="3" style="width:25%"><b><?php echo $salesp['sTire']; ?></b></td>
                    </tr>
                    <tr>
                        <td class="" rowspan="3">34. No. of Axle :</td>
                        <td class="" rowspan="3"> <b></b> </td>
                        <td class="" rowspan="3">35. Maximum axle weight :
                            <ul type="none">
                                <li>a) Front axle</li>
                                <li>b) Front axle</li>
                                <li>c) Front axle</li>
                            </ul>
                        </td>
                        <td rowspan="3">(1)<br><br> (1) <br><br> (1)</td>
                        <td rowspan="3">(2)<br><br> (2) <br><br> (2)</td>
                        <td rowspan="3">(3)<br><br> (3) <br><br> (3)</td>

                    </tr>

                    <tr>

                    </tr>
                    <tr>

                    </tr>

                    <tr>
                        <td colspan="6">36. Dimensions (mm) :</td>

                    </tr>

                </tbody>
            </table>

            <table class="table cus_tbl text_font">
                <tbody>
                    <tr>

                        <td colspan="2">a) Overall length :</td>
                        <td colspan="2">b) Overall Width :</td>
                        <td colspan="2">c) Overall height :</td>

                    </tr>
                    <tr>
                        <td colspan="6">37. Overhangs(%) :</td>
                    </tr>

                    <tr>
                        <td colspan="2">a) Front :</td>
                        <td colspan="2">b) Rear :</td>
                        <td colspan="2">c) Other :</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <p style="text-align: justify; line-height:2.0em;">38. A copy of the drawing showing the vehicle dimension
                specifications of the body and of the seating arrangement approved by
                .....................................................................of...............................................................is
                attached herewith.</p>

        </div>
    </div>

</div>

<div class="container fm_bg page">
    <p class="text-center text_font">Section-V</p>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8 col-print-12">
            <p>39. Hire purchase/hypothecation information : <br>
                The vehicle is subject to hire purchase/hypothecation with :
            </p>
            <!--  table colam -->
            <div class="row">
                <div class="col-md-2 col-print-6">a) Name :</div>
                <div class="col-md-8 out"></div>
                <div class="col-md-2 col-print-6">b) Date</div>
            </div>
            <div class="row">

                <div class="col-md-12 col-print-12">Address :</div>

            </div>
            <!-- table colam end -->
        </div>
        <div class="col-md-2"></div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <p>40. Insurance information :</p>
            <!--  table colam -->
            <div class="row">
                <div class="col-md-6 col-print-6">a) Policy no :</div>
                <div class="col-md-6 col-print-6">b) Type of policy :</div>

            </div>
            <div class="row">
                <div class="col-md-6 col-print-6">c) Insurer's name &amp; address :</div>
                <div class="col-md-6 col-print-6">d) Date of expiry :</div>

            </div>
            <!-- table colam end -->
        </div>
        <div class="col-md-2"></div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <p>41. Joint owner information :</p>

            <div class="row">
                <div class="col-md-6 col-print-6">a) Name :</div>
                <div class="col-md-6 col-print-6">b) Name :</div>

            </div>
            <div class="row">
                <div class="col-md-6 col-print-6">Father/Husband :</div>
                <div class="col-md-6 col-print-6">Father/Husband :</div>

            </div>

        </div>
        <div class="col-md-2"></div>
    </div>
    <br>

    <p class="text-center text_font">Section-VI</p>
    <p class="text-center text_font" style="font-size: 10px;">(Declaration, Certificates and Documents)</p>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <p>42. Declaration by owner :</p>

            <div class="row">
                <div class="col-md-12 col-print-12 text-justify">a) I the undersigned do hereby declare that to the best
                    of my knowledge and belief, the information given and the documents enclosed (as per list attached)
                    are true. I also declare that in case the papers/documents and information furnished are fonund to
                    be incorrect at any later stage, I shall be liable for legal action.</div>
            </div>

        </div>
        <div class="col-md-2"></div>
    </div>

    <br>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-3 col-print-3">Date :</div>
                <div class="col-md-6 col-print-6"></div>
                <div class="col-md-3 col-print-3">Signature of owner </div>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12 col-print-12">Encl : list of documents</div>

            </div>
        </div>
        <div class="col-md-2"></div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <p>43. Registered dealer's certificate :</p>

            <div class="row">
                <div class="col-md-12 col-print-12 text-justify">I the undersigned do hereby certify that the vehicle in
                    question has been sold by me/my firm and the ownership documents attached with the application for
                    registration are true. The information/specifications pertaining to the vehicle are correct and the
                    vehicle complies with all the requirements of the registration.</div>
            </div>

        </div>
        <div class="col-md-2"></div>
    </div>

    <br>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-3 col-print-3">Date :</div>
                <div class="col-md-6 col-print-6"></div>
                <div class="col-md-3 col-print-3">Signature of dealer <br>
                    <p style="text-align:center;margin-right: 30%;">seal</p>
                </div>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12 col-print-12">Encl : list of documents</div>

            </div>
        </div>
        <div class="col-md-2"></div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <p>44. Certificate by the Inspector of Motor Vehicles :</p>

            <div class="row">
                <div class="col-md-12 col-print-12 text-justify">Certificate that the particulars pertaining to the
                    owner and the vehicle ( Chassis No <u><b><?php echo $salesp['pChassis']; ?></b></u> Engine No
                    <u><b><?php echo $salesp['pEngine']; ?></b></u> ) give in the application match with the ownership documents attached to
                    this application. It is further certified that the vehicle complies with the rehistration
                    requirements specified in the MV Act and Rules and/or Regulation made thereunder and the vechicle is
                    not mechanically defective. the necessary documents/paper are available as per list enclosed.</div>
            </div>

        </div>
        <div class="col-md-2"></div>
    </div>

    <br>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-4 col-print-4">Date :</div>
                <div class="col-md-3 col-print-3"></div>
                <div class="col-md-5 col-print-5 text-center">Signature of Inspector of Motor Vehicles Office <br>
                    <p>seal</p>
                </div>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12 col-print-12">Encl : list of documents</div>

            </div>
        </div>
        <div class="col-md-2"></div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <p>45. Registration Status :</p>
            <!-- 
			<div class="row">
				<div class="col-md-12 col-print-12"></div>
			</div> -->

        </div>
        <div class="col-md-2"></div>
    </div>

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-4 col-print-6">Registration allowed/not allowed</div>
                <div class="col-md-4"></div>
                <div class="col-md-4 col-print-6 text-center">Signature of Registration Authority seal</div>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <p>46. Fees and Tax Accounts :</p>

            <div class="row">
                <div class="col-md-12 col-print-12 text-justify" style="line-height:2.0em;">
                    <p>Necessary fees and taxes amounting to taka
                        ....................................................................................... has been
                        paid to PO/Bank ..............................................................................
                        vide vouchers and receipts enclosed.</p>
                </div>
            </div>

        </div>
        <div class="col-md-2"></div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-3 col-print-6 text-center">Signatute of owner of his representative</div>
                <div class="col-md-6"></div>
                <div class="col-md-3 col-print-6 text-center">Signatute of dealing assistant</div>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>

</div>

<?php $this->load->view('footer/regFooter'); ?>