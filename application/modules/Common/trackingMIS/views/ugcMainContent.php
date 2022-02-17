<!-- page content -->
<div class="right_col" role="main">          
    <!-- top tiles -->
    <?php
        $this->load->view('trackingMIS/UGC/topTiles');
        $this->load->view('trackingMIS/UGC/networkActivities');
    ?>
    <br/>
    <div class="row">
       <?php
            $this->load->view('trackingMIS/UGC/appVersion');
            $this->load->view('trackingMIS/UGC/deviceUsage');
            $this->load->view('trackingMIS/UGC/quickSetting');
        ?> 
    </div>
    <div class="row">
        <?php
            $this->load->view('trackingMIS/UGC/recentActivities');
        ?>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <div class="row">
                <?php
                    $this->load->view('trackingMIS/UGC/visitorsLocation');
                ?>
            </div>
            <div class="row">
                <?php
                    $this->load->view('trackingMIS/UGC/toDoList');
                    $this->load->view('trackingMIS/UGC/weatherWidget');
                ?>                      
            </div>
        </div>
    </div>              