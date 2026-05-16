<?php $this->load->view('header/header'); ?>
<?php $this->load->view('navbar/navbar'); ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <?php
    $exception = $this->session->userdata('exception');
    if(isset($exception))
    {
    echo $exception;
    $this->session->unset_userdata('exception');
    } ?>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Dashboard</h3>
              </div>

              <div class="card-body">
                <section class="content">
                  <div class="container-fluid">
                    <div class="box-header with-border">
                      <h2><b>Welcome To "<?php echo $_SESSION['compname']; ?>"</b></h2>
                    </div>
                    <div class="row">
                      <?php if($_SESSION['customers'] == 1){ ?>
                      <div class="col-lg-3 col-6">
                        <a href="<?php echo base_url('Customer'); ?>">
                        <div class="small-box bg-primary">
                          <div class="inner">
                            <h3><?php echo $customer; ?></h3>
                            <p>Total Customer</p>
                          </div>
                          <div class="icon">
                            <i class="fas fa-users"></i>
                          </div>
                        </div>
                        </a>
                      </div>
                      <?php } if($_SESSION['products'] == 1){ ?>
                      <div class="col-lg-3 col-6">
                        <a href="<?php echo base_url('Product'); ?>">
                        <div class="small-box bg-secondary">
                          <div class="inner">
                            <h3><?php echo $product; ?></h3>
                            <p>Total Product</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-bag"></i>
                          </div>
                        </div>
                        </a>
                      </div>
                      <?php } if($_SESSION['suppliers'] == 1){ ?>
                      <div class="col-lg-3 col-6">
                        <a href="<?php echo base_url('Supplier'); ?>">
                        <div class="small-box bg-info">
                          <div class="inner">
                            <h3><?php echo $supplier; ?></h3>
                            <p>Total Supplier</p>
                          </div>
                          <div class="icon">
                            <i class="fas fa-user"></i>
                          </div>
                        </div>
                        </a>
                      </div>
                      <?php } if($_SESSION['sales'] == 1){ ?>
                      <div class="col-lg-3 col-6">
                        <a href="<?php echo base_url('saleReport'); ?>">
                        <div class="small-box bg-success">
                          <div class="inner">
                            <h3><?php echo number_format($tsale->total, 2); ?></h3>
                            <p>Total Sales</p>
                          </div>
                          <div class="icon">
                            <i class="far fa-money-bill-alt"></i>
                          </div>
                        </div>
                        </a>
                      </div>
                      <?php } if($_SESSION['todaysales'] == 1){ ?>
                      <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                          <div class="inner">
                            <h3><?php echo number_format($sale->total, 2); ?></h3>
                            <p>Today Sales</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                          </div>
                        </div>
                      </div>
                      <?php } if($_SESSION['todaypurchase'] == 1){ ?>
                      <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                          <div class="inner">
                            <h3><?php echo number_format($purchase->total, 2); ?></h3>
                            <p>Todays Purchase</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-bag"></i>
                          </div>
                        </div>
                      </div>
                      <?php } if($_SESSION['todayexpense'] == 1){ ?>
                      <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                          <div class="inner">
                            <h3><?php echo number_format(($dvoucher->total+$svoucher->total), 2); ?></h3>
                            <p>Today Expense</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                          </div>
                        </div>
                      </div>
                      <?php } if($_SESSION['todayincome'] == 1){ ?>
                      <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                          <div class="inner">
                            <h3><?php echo number_format($cvoucher->total, 2); ?></h3>
                            <p>Todays Income</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-person-add"></i>
                          </div>
                        </div>
                      </div>
                      <?php } ?>
                    </div>
                    
                    <?php if($_SESSION['lastdSales'] == 1){ ?>
                    <div class="col-md-12 col-sm-12 col-12">
                      <div class="card">
                        <div id="chartContainer" style="height: 400px; width: 100%;"></div>
                      </div>
                    </div>
                    <?php } ?>
                  </div>
                </section>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<?php $this->load->view('footer/footer'); ?>

    <script type="text/javascript">
      window.onload = function() {

        CanvasJS.addColorSet("greenShades",
          [
          "#1382d6",
          "#1382d6",
          "#1382d6",
          "#1382d6",
          "#1382d6" ,               
          "#1382d6" ,               
          "#1382d6"                
          ]);
 
        var chart = new CanvasJS.Chart("chartContainer", {
          animationEnabled: true,
          theme: "light1",
          colorSet: "greenShades",
          title:{
            text: "Last 7 Days Sales"
            },
          axisY: {
            title: "Products sales amount"
            },
          data: [{
            type: "column",
            yValueFormatString: "#,##0.## Taka",
            dataPoints: <?php echo json_encode($this->pm->graph_data_point(), JSON_NUMERIC_CHECK); ?>
          }]
        });
      chart.render();
      }
    </script>