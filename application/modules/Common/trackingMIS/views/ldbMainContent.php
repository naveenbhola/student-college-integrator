<!-- page content -->
<div class="right_col" role="main">          
    <!-- top tiles -->
    <?php
        $this->load->view('trackingMIS/LDB/topTiles');
        $this->load->view('trackingMIS/LDB/lineChart');
    ?>
    <br/>
    <div class="row">
       <?php
          //  $this->load->view('trackingMIS/LDB/appVersion');
            $this->load->view('trackingMIS/LDB/pieChart1');
            $this->load->view('trackingMIS/LDB/pieChart2');
          //  $this->load->view('trackingMIS/LDB/quickSetting');
        ?> 
    </div>
    <div class="row">
       <?php
            $this->load->view('trackingMIS/LDB/dataTable');

            
        ?> 
    </div>
    <div class="row">
        <?php
          //  $this->load->view('trackingMIS/LDB/recentActivities');
        ?>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <div class="row">
                <?php
                  //  $this->load->view('trackingMIS/LDB/visitorsLocation');
                ?>
            </div>
            <div class="row">
                <?php
                  //  $this->load->view('trackingMIS/LDB/toDoList');
                   // $this->load->view('trackingMIS/LDB/weatherWidget');
                ?>                      
            </div>
        </div>
    </div>              