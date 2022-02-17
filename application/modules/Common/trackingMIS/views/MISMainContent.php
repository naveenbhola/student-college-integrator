<?php 

if(
    ($metric == "registration") || 
    ($metric == "traffic")      || 
    ($metric == "engagement")   || 
    ($metric == "response")     || 
    ($metric == "RMC")          || 
    ($pageName == "searchPage" && $teamName =="Study Abroad" && $metric == "home") || 
    ($teamName =="Study Abroad" && $metric == "exam_upload")
){

    if($metric == "exam_upload"){
        $pageNameToShow = str_replace(' ','',$pageNameToShow);
        $metricNameToShow = "Exam Uploaded Docs";
    }
    else{
        $metricNameToShow = $metric;
    }

?>
    <div class="right_col" role="main">
        <!-- top tiles -->
        <?php
            $height = '';
            if(count($topFilters) >9){
                $height = 'fixed_height_100';
            }
         if($teamName == 'Domestic'){$domesticClass = "domestic-filter";}?>
        <div class="row x_title <?php echo $domesticClass;?>">
            <div class="col-md-2 <?php echo $height;?>">
                <div style="font-size: 18px">
                    <?php if(!$pageNameToShow){?>
                        <div><span id="teamName" style="font-size: 18px;color: #73879C;"><?php echo $teamName;?></span></div>
                    <?php }?>
                </div>
                <!-- <div> -->
                <h4><span id="teamName" style="font-size: 18px;color: #73879C;"><?php echo $pageNameToShow;?></span><!-- </div> -->
                <div> <div style="font-size:14px"><?php echo ucfirst($metricNameToShow);?></div></div></h4>
            </div>
            <?php if ($metric == 'engagement') { ?>
                <input type="hidden" name="engagementType" value="pageview">
            <?php } else if($metric == 'traffic'){ ?>
                <input type="hidden" name="trafficAspects" value="users">
            <?php } ?>
            <?php $this->load->view("trackingMIS/filters"); ?>
        </div>
        <div class='row'>
            <?php
                $this->load->view('trackingMIS/topTiles'); 
                if($teamName =="Study Abroad" && $metric == "exam_upload"){
                    $this->load->view('trackingMIS/lineChart',$data);
                    $this->load->view('trackingMIS/donutChart', array(
                        'id' => 'device',
                        'fieldName' => 'sourceApplication',
                        'title' => 'Source Application',
                    ));
                    $this->load->view('trackingMIS/donutChart', array(
                        'id' => 'widget',
                        'fieldName' => 'widget',
                        'title' => 'Widget',));
                    $dataTable =    $pageDetails['DATA_TABLE'];
                    if(count($dataTable) >0){
                        ?>
                        <div class="row" id="<?php echo $dataTable['id']?>">
                            <?php
                            $this->load->view('trackingMIS/nationalListings/showTable',array("metric"=>$metric,"pageName"=>$pageName,"teamName"=>$teamName));
                            ?>
                        </div>
                    <?php }

                }else{
                    $this->load->view('trackingMIS/lineChart',$data);
                    $dountCharts =  $pageDetails['PIE_CHART'];
                    foreach ($dountCharts as $key => $value) {
                        $this->load->view('trackingMIS/donutChart', array(
                            'id' => $value['id'],
                            'title' => $value['title'],
                            'addExtraDataToTitle' => $value['addExtraDataToTitle']
                        ));
                        if($key == 'Traffic Source'){ 
                            $this->load->view('trackingMIS/tabbedBarGraph', array(
                                'id' => 'trafficSourceDrillDown',
                                'title' => 'Traffic Source Split',
                            ));
                        }
                    }
                    $dataTable = $pageDetails['DATA_TABLE'];
                    if(count($dataTable) >0){
                        ?>
                        <div class="row" id="<?php echo $dataTable['id']?>">
                            <?php
                            $this->load->view('trackingMIS/nationalListings/showTable',array("metric"=>$metric,"pageName"=>$pageName,"teamName"=>$teamName,"dataTable" => $dataTable['fields']));
                            ?>
                        </div>
                    <?php }
                }
            ?>
        </div>
        </br>
    </div>
<?php  } ?>                             
