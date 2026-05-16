<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title;?></title>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/dist/img/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style type="text/css" media="print">
        @page {
            size: A4;
        }

        @media print {


            .col-print-1 {
                width: 8%;
                float: left;
            }

            .col-print-2 {
                width: 16%;
                float: left;
            }

            .col-print-3 {
                width: 25%;
                float: left;
            }

            .col-print-4 {
                width: 33%;
                float: left;
            }

            .col-print-5 {
                width: 42%;
                float: left;
            }

            .col-print-6 {
                width: 50%;
                float: left;
            }

            .col-print-7 {
                width: 58%;
                float: left;
            }

            .col-print-8 {
                width: 66%;
                float: left;
            }

            .col-print-9 {
                width: 75%;
                float: left;
            }

            .col-print-10 {
                width: 83%;
                float: left;
            }

            .col-print-11 {
                width: 92%;
                float: left;
            }

            .col-print-12 {
                width: 100%;
                float: left;
            }


            .print_hide {
                display: none;
            }

            .btn-info {
                margin-bottom: 10px;

            }

            body {
                margin: 5mm;
            }

        }
    </style>
</head>

<body>
    <script type="text/javascript">
        function printdoc() {
            // install firefox addon in order to use this plugin
            if (window.jsPrintSetup) {
                // set top margins in millimeters
                jsPrintSetup.setOption('marginTop', '0');
                jsPrintSetup.setOption('marginLeft', '0');
                jsPrintSetup.setOption('marginBottom', '0');
                jsPrintSetup.setOption('marginRight', '0');
                // set page header
                jsPrintSetup.setOption('headerStrLeft', '');
                jsPrintSetup.setOption('headerStrCenter', '');
                jsPrintSetup.setOption('headerStrRight', '');
                // set empty page footer
                jsPrintSetup.setOption('footerStrLeft', '');
                jsPrintSetup.setOption('footerStrCenter', '');
                jsPrintSetup.setOption('footerStrRight', '');
                var printers = jsPrintSetup.getPrintersList().split(',');
                // get right printer here..
                for (var index in printers) {
                    var default_ticket_printer = window.localStorage && localStorage['invoice_printer'];
                    var selected_printer = printers[index];
                    if (selected_printer == default_ticket_printer) {
                        // select epson label printer
                        jsPrintSetup.setPrinter(selected_printer);
                        // clears user preferences always silent print value
                        // to enable using 'printSilent' option
                        jsPrintSetup.clearSilentPrint();
                        // Do Print
                        // When print is submitted it is executed asynchronous and
                        // script flow continues after print independently of completetion of print process!
                        jsPrintSetup.print();
                    }
                }
            } else {
                window.print();
            }
        }
    </script>

    <div class="print_hide" id="control_buttons" style="text-align:right">
        <a href="javascript:printdoc();">
            <div class="btn btn-primary" id="show_print_button"><span
                    class="glyphicon glyphicon-print">&nbsp;Print</span>
            </div>
            <div id="show_print_button">
                  <a href="<?php echo base_url(); ?>saleDList" class="btn btn-danger glyphicon glyphicon-arrow-left" > Back</a>
                </div>
        </a>
    </div>
    <div style="width:80%;margin: 0 auto;">
        <a class="btn btn-info print_hide" style="margin-left: 5px; margin-bottom:10px;"
            href="<?php echo base_url().'regForm/'.$prints['said']; ?>">Registration Form</a>
        <a class="btn btn-info print_hide" style="margin-left: 5px; margin-bottom:10px;"
        href="<?php echo base_url().'ownerForm/'.$prints['said']; ?>">Owner Form</a>
        <a class="btn btn-info print_hide" style="margin-left: 5px; margin-bottom:10px;"
            href="<?php echo base_url().'challanForm/'.$prints['said']; ?>">Challan Form</a>
        <!--<a class="btn btn-info print_hide" style="margin-left: 5px; margin-bottom:10px;"-->
        <!--    href="<?php echo base_url().'concernForm/'.$prints['said']; ?>">TO WHOM IT MAY CONCERN</a>-->
        <!--<a class="btn btn-info print_hide" style="margin-left: 5px; margin-bottom:10px;"-->
        <!--    href="<?php echo base_url().'ongikarnama/'.$prints['said']; ?>">অঙ্গীকার নামা</a>-->

        <a class="btn btn-info print_hide" style="margin-left: 5px; margin-bottom:10px;"
            href="<?php echo site_url().'salesReceipt/'.$prints['said']; ?>">Sales Receipt</a>
        <a class="btn btn-info print_hide" style="margin-left: 5px; margin-bottom:10px;"
            href="<?php echo base_url().'gatePass/'.$prints['said']; ?>">Gate Pass</a>
        <a class="btn btn-info print_hide" style="margin-left: 5px; margin-bottom:10px;"
            href="<?php echo base_url().'bankForm/'.$prints['said']; ?>">Bank Assessment</a>
        <a class="btn btn-info print_hide" style="margin-left: 5px; margin-bottom:10px;"
            href="<?php echo base_url().'brtaApplication/'.$prints['said']; ?>">BRTA Application</a>

        <a class="btn btn-info print_hide" style="margin-left: 5px; margin-bottom:10px;"
            href="<?php echo base_url().'certificate/'.$prints['said']; ?>">প্রত্যায়ন পত্র</a>

        <!--<a class="btn btn-info print_hide" style="margin-left: 5px; margin-bottom:10px;"-->
        <!--    href="<?php echo site_url().'viewUSale/'.$prints['said']; ?>">Delivery Challan</a>-->

        <!-- <a class="btn btn-info btn-sm print_hide" href="<?php echo base_url('ownerForm'); ?>">Customer Invoice</a>
	<a class="btn btn-info btn-sm print_hide" href="<?php echo base_url('ownerForm'); ?>">Money Receipt</a> -->
    </div>

    