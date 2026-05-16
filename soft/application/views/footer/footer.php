  <footer class="main-footer">
    <strong>Copyright &copy; 2025 <a href="<?php echo base_url(); ?>">Eureka Avenue</a> .</strong> All rights reserved.
  </footer>
</div>

    <!-- jQuery -->
    <script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="<?php echo base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

    <!-- date-picker -->
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

    <!-- AdminLTE App -->
    <script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo base_url(); ?>assets/plugins/chart.js/Chart.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/dist/js/demo.js"></script>
    <script src="<?php echo base_url(); ?>assets/dist/js/pages/dashboard3.js"></script>
    <script src="<?php echo base_url(); ?>assets/dist/js/canvasjs.js"></script>
    <!-- page script -->
    
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/chosen/chosen.min.css">
    <script src="<?php echo base_url(); ?>assets/chosen/chosen.jquery.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    
    <script src="<?php echo base_url(); ?>assets/plugins/select2/js/select2.min.js"></script>
    
    

    <script>
        $(document).ready(function(){
          $('#example1').DataTable({
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            dom: 'lBfrtip',
            buttons: [
              {
                extend: 'copyHtml5',
                text: '<i class="fas fa-copy"></i> Copy',
                className: 'btn btn-default'
              },
              {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel1',
                className: 'btn btn-default'
              },
              {
                extend: 'csvHtml5',
                text: '<i class="fas fa-file-csv"></i> CSV',
                className: 'btn btn-default'
              },
              {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-default'
              },
              {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-default'
              }
            ]
          });
        });
    </script>





    <script type="text/javascript">
      function printDiv(divName){
        $('#header').show();
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        window.location.reload();
        }
    </script>

    <script type="text/javascript">
      $(function(){
        $("#example").DataTable({
          "responsive": true,
          "autoWidth": false,
          });
        });
    </script>

    <script type="text/javascript">
      function isNumberKey(evt)
        {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
        return true;
        }
    </script>
    
    <script type="text/javascript">
      $(function(){
        $('.datepicker').datepicker({
          autoclose: true,
          todayHighlight: true
          });
        });
    </script>
    
    <script type="text/javascript">
      $(function(){
        $(".select2").select2();
      });
    </script>
    
    <script type="text/javascript" >
      (function(){
        $(".chosen").chosen({
          width: "100%"
          });
        });
    </script>

  </body>
</html>