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
    <p class="text-center text_font"><b style="border-bottom:1px solid #000;font-size: 30px;">অঙ্গীকার নামা</b></p>
    <br>
    <br>
    <br>
    <h4 class="text-center text_font" style="font-size: 11px;font-weight: bold; padding: 1px 0px;"></h4>
    <p>

        বরাবর,<br>
        রেজিষ্ট্রেশন কর্তৃপক্ষ<br>
        বিআরটিএ, সমগ্র বাংলাদেশ|<br>
    </p>
    <br>
    <p>
        বিষয়ঃ চেচিস নং<b style="font-family:'Arial,sans-serif'"> <?php echo $salesp['pChassis']; ?></b> ইঞ্জিন নং.<b
            style="font-family:'Arial,sans-serif'">  <?php echo $salesp['pEngine']; ?></b> সম্বলিত মোটর সাইকেল রেজিষ্ট্রেশন প্রসঙ্গে |<br>
        উপযুক্ত বিষয়ের প্রেক্ষিতে আপনার সদয় অবগতির জন্য জানানো যাচ্ছে যে, বিষয়ে উল্লেখিত ও ইঞ্জিন নম্বরের মোটর সাইকেলটি
        <span style="font-weight: bold;">NAZRAN MOTORS </span> কর্তৃক
    </p>
    <br>
    <p>
        জনাব : <b style="text-transform: uppercase;font-family:'Arial,sans-serif';font-size: 17px;"><?php echo $prints['custName']; ?></b><br>
        পিতাঃ <b style="text-transform: uppercase;font-family:'Arial,sans-serif';font-size: 17px;"><?php echo $prints['custfName']; ?></b><br>
        ঠিকানা : <b style="text-transform: uppercase;font-family:'Arial,sans-serif';font-size: 17px;"><?php echo $prints['custAddress']; ?></b>
    </p>

    <p>
        এর নিকট আমাদের মাধ্যমে বিক্রয় করা হয়েছে। আমরা <span style="font-weight: bold;">NAZRAN MOTORS </span>
        এর স্থানীয় ডিলার/এজেন্ট মোটর সাইকেলটি রেজিষ্ট্রেশন নিমিত্তে যে সকল কাগজ পএ ক্রেতা বরাবর প্রদান করা হয়েছে উহা 
        আমাদের মাধ্যমে সরবরাহ করা হয়েছে। সরবরাহকৃত কাগজ পএ গুলি সঠিক।<br>
        উক্ত মোটর সাইকেল টির রেজিষ্ট্রেশন বিষয়ে ভবিষ্যতে কোন প্রকার জটিলতার সৃষ্টি হইলে তার দায়-দায়িত্ব আমরা বহন করব।
        বিআরটিএ কর্তৃপক্ষ কোন প্রকার দায়-দায়িত্ব বহন করবেনা।
    </p>
    <br>
    <br>

    <div class="row" style="margin-top: 40px;">
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

<?php $this->load->view('footer/regFooter'); ?>