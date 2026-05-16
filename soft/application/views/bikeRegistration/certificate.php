<?php $this->load->view('header/regHeader'); ?>
<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Baloo+Da+2:wght@400;500;600;700;800&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap');

    .fm_bg {
        font-size: 13px;
    }

    img {
        width: 100%;
    }


    .ongi {
        font-family: 'Baloo Da 2', cursive;
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
        padding: 15px 5px !important;
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

    p {
        margin: 0 0 15.5px;
        line-height: 30px;
        font-size: 18px;
    }

    .border {
        text-transform: uppercase;
        width: 70% !important;
        border-bottom: 1px dotted #000;
        display: block;
        float: left;
        text-align: center;
    }
</style>

<div class="container fm_bg page ongi">
    <!-- <img src="" alt=""> -->
    <div style="margin-top:70px"></div>
    <br>
    <br>
    <br>
    <br>
    <p class="text-center text_font"><b style="border-bottom:1px solid #000;font-size: 30px;">প্রত্যায়ন পত্র</b></p>
    <br>
    <br>
    <br>
    <h4 class="text-center text_font" style="font-size: 11px;font-weight: bold; padding: 1px 0px;"></h4>
    <div class="row">

        <p>
    
            বরাবর,<br>
            সহকারী পরিচালক (ইঞ্জিঃ)<br>
            বিআরটিএ, খুলনা।<br>
        </p>
        <br>
       
        <p>
            এই মর্মে প্রত্যায়ন করা যাচ্ছে যে, নাজরান মটরস কর্তৃক বিক্রয় কৃত মোটরসাইকেল যাহার চেসিস নংঃ <span style="font-weight: bold;"><?php echo $salesp['pChassis']; ?></span>
           , ইঞ্জিন নংঃ <span style="font-weight: bold;"><?php echo $salesp['pEngine']; ?></span> এবং রংঃ <span style="font-weight: bold;"><?php echo $salesp['pColor']; ?></span>
            উল্লেখিত মোটরসাইকেলটির নিম্নে বর্নিত জনাব...................................................................................................
            পিতা -..........................................................................................
            ঠিকানা......................................................................................................................................
            এর নিকট বিক্রয় করি, এবং তাকে মোটরসাইকেলের রেজিস্ট্রেশনের
            জন্য বৈধ কাগজপত্র প্রদান করি। <br>
            অতএব নাজরান মটরস কর্তৃক প্রদত্ত কাগজপত্রে কোন ভুল পরিলক্ষিত হলে সে জন্য BRTA কর্তৃপক্ষ কোন দায়বহন করিবেনা। বরং সকল দায়-দায়িত্ব নাজরান মটরস বহন করবে।
        </p>
    </div>
    <br>
    <br>
    <br>
    <br>

    <div class="row" style="margin-top: 40px;">
        <div class="row">
            <div class="col-md-6 col-print-6">
                <h5></h5>
            </div>
            <div class="col-md-6 col-print-6 text-right">
                <p>বিনীত নিবেদক</p>
                <p>নাজরান মটরস</p>
            </div>
        </div>
    </div>


</div>

<?php $this->load->view('footer/regFooter'); ?>