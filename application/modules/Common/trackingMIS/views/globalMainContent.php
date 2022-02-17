<?php
if($metric == "exam_upload")
{
    $pageNameToShow = str_replace(' ','',$pageNameToShow);
    $metricNameToShow = "Exam Uploaded Docs";
}
else
{
    $metricNameToShow = $metric;
}
?>
<div class="right_col" role="main">
    <!-- top tiles -->
    <?php if($teamName == 'Domestic'){$domesticClass = "domestic-filter";}?>
    <div class="row x_title <?php echo $domesticClass;?>">
        <div class="col-md-2">
            <?php if(
                    ($metric == "registration") || 
                    ($metric == "traffic")      || 
                    ($metric == "engagement")   || 
                    ($metric == "response")     || 
                    ($metric == "RMC")          || 
                    ($pageName == "searchPage" && $teamName =="Study Abroad" && $metric == "home") || 
                    ($teamName =="Study Abroad" && $metric == "exam_upload")
                ){?>
                <div style="font-size: 18px">
                <?php if(!$pageNameToShow){?>
                    <div><span id="teamName" style="font-size: 18px;color: #73879C;"><?php echo $teamName;?></span></div>
                <?php }?>
                </div>
                    <div><span id="teamName" style="font-size: 18px;color: #73879C;"><?php echo $pageNameToShow;?></span></div>
                    <div> <div style="font-size:14px"><?php echo ucfirst($metricNameToShow);?></div></div></h4>
            <?php }else{?>
                <h4><span id="teamName"></span><div> <div style="font-size:14px"><?php echo ucfirst($metric);?></div></div></h4>
            <?php }?>
        </div>

        <?php
        if ($metric == 'engagement') { ?>
            <input type="hidden" name="engagementType" value="pageview">
        <?php } else if($metric == 'traffic'){ ?>
            <input type="hidden" name="trafficAspects" value="users">
        <?php } else if ($metric == 'leads') {
            if($source == 'national') { ?>
                <div class="col-md-2">
                    <div class="row">
                        <button class="col-md-11 col-sm-11 col-xs-11 btn btn-default dropdown-toggle white_space_normal_overwrite" type="button" id="id"
                                data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="true"><span class="caret margin-right-2"></span>Category
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="id">
                            <?php
                            foreach($categories as $oneCategory){ ?>
                                <li data-dropdown="<?php echo $oneCategory->CategoryId ; ?>"><a href="javascript: void(0)"><?php echo $oneCategory->CategoryName; ?></a></li>
                            <?php } ?>
                            <li data-dropdown="all"><a href="javascript: void(0)">All Categories</a></li>
                        </ul>
                    </div>
                    <input type="hidden" name="id" value="all"/>
                </div>

                <div class="col-md-2 cropper-hidden">
                    <div class="row">
                        <button class="col-md-11 col-sm-11 col-xs-11 btn btn-default dropdown-toggle white_space_normal_overwrite" type="button" id="subid"
                                data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="true"><span class="caret margin-right-2"></span>Subcategory
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="subid">
                            <li data-dropdown="desktop"><a href="javascript: void(0)">Desktop</a></li>
                            <li data-dropdown="mobile"><a href="javascript: void(0)">Mobile</a></li>
                            <li data-dropdown="all"><a href="javascript: void(0)">All Source Applications</a></li>
                        </ul>
                    </div>
                    <input type="hidden" name="subid" value="all"/>
                </div>
            <?php }
            else if($source == 'abroad'){ ?>

            <?php }?>
        <?php } else if(
                ($metric != "registration") && 
                ($metric != "traffic") && 
                ($metric != "engagement") && 
                ($metric != "response") && 
                ($metric != "RMC") && 
                ($metric != "home") && 
                ($metric != "exam_upload")
            ){ ?>
            <div class="col-md-3"></div>
        <?php }?>

        <?php if(
                    ($metric == "registration") || 
                    ($metric == "traffic")      || 
                    ($metric == "engagement")   || 
                    ($metric == "response")     || 
                    ($metric == "RMC")          || 
                    ($pageName == "searchPage" && $teamName =="Study Abroad" && $metric == "home") ||
                    ($teamName =="Study Abroad" && $metric == "exam_upload")
                ){?>
        <?php $this->load->view("trackingMIS/filters"); ?>

        <?php }
        
        if(
            ($metric != "registration") && 
            ($metric != "traffic") && 
            ($metric != "engagement") && 
            ($metric != "response") && 
            ($metric != "RMC") &&  
            !(($pageName == "searchPage" && $teamName =="Study Abroad" && $metric == "home")) && 
            !(($$teamName =="Study Abroad" && $metric == "exam_upload"))
        ){ ?>
            <div class="col-md-2">
                <div class="row">
                    <button class="col-md-11 col-sm-11 col-xs-11 btn btn-default dropdown-toggle white_space_normal_overwrite" type="button" id="source"
                            data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="true"><span class="caret margin-right-2"></span>Source Application
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="source">
                        <li data-dropdown="desktop"><a href="javascript: void(0)">Desktop</a></li>
                        <li data-dropdown="mobile"><a href="javascript: void(0)">Mobile</a></li>
                        <li data-dropdown="all"><a href="javascript: void(0)">All Source Applications</a></li>
                    </ul>
                </div>
                <input type="hidden" name="source" value="all"/>
            </div>

            <div class="col-md-2">
                <div class="row">
                    <button id="reportrange" class="btn btn-default col-md-11 col-sm-11 col-xs-11 white_space_normal_overwrite"
                            style="background: #fff;"><b class="caret margin-right-2"></b>
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        <span></span>
                    </button>
                </div>
            </div>

            <div class="col-md-2">
                <button type="button" id="submit" class="btn btn-primary">Apply Filters</button>
            </div>
        <?php } ?>
    </div>
    <div class='row'>
    <?php
    $this->load->view('trackingMIS/topTiles');
    if($metric == 'overview'){
        $this->load->view('trackingMIS/donutChart', array(
            'id' => 'trafficSource',
            'title' => 'Traffic - Source',
        ));
        $this->load->view('trackingMIS/donutChart', array(
            'id' => 'trafficDevice',
            'title' => 'Traffic - Source Application',
        ));
        $this->load->view('trackingMIS/donutChart', array(
            'id' => 'registrationSource',
            'title' => 'Registrations - Source',
        ));
        $this->load->view('trackingMIS/donutChart', array(
            'id' => 'registrationDevice',
            'title' => 'Registrations - Source Application',
        ));
        $this->load->view('trackingMIS/donutChart', array(
            'id' => 'paidFree',
            'title' => 'Registrations - Paid Free',
        ));
        $this->load->view('trackingMIS/donutChart', array(
            'id' => 'session',
            'title' => 'Responses - Traffic Source',
        ));
        $this->load->view('trackingMIS/donutChart', array(
            'id' => 'device',
            'title' => 'Responses - Source Application',
        ));
        $this->load->view('trackingMIS/donutChart', array(
            'id' => 'pivotType',
            'title' => 'All Responses - Type',
        ));
        if($teamName != "Domestic"){
            $this->load->view('trackingMIS/donutChart', array(
                'id' => 'rmcResponseType',
                'title' => 'RMC Responses - Type',
            ));
        }
            
    }
    else if(
        $metric == 'registrations'  || 
        $metric == 'traffic'        || 
        $metric == "engagement"     || 
        $metric == "response"       || 
        $metric == "RMC"            || 
        ($pageName == "searchPage" && $teamName =="Study Abroad" && $metric == "home")
    ){
   
    }
    else if( preg_match('/^response(s)?$/i', $metric) == 1){

        $this->load->view('trackingMIS/lineChart');

        $this->load->view('trackingMIS/donutChart', array(
            'id' => 'device',
            'title' => 'Responses - Source Application',
        ));
        $this->load->view('trackingMIS/donutChart', array(
            'id' => 'session',
            'title' => 'Responses - Traffic Source',));
        $this->load->view('trackingMIS/tabbedBarGraph', array(
            'id' => 'trafficSourceDrillDown',
            'title' => 'Responses - Traffic Source',));
        $this->load->view('trackingMIS/donutChart', array(
            'id' => 'widget',
            'title' => 'Responses - Widget',));
        $this->load->view('trackingMIS/donutChart', array(
            'id' => 'pivotType',
            'title' => 'Responses - Type',));
        $this->load->view('trackingMIS/donutChart', array(
            'id' => 'page',
            'title' => 'Responses - Page',));
    }
    ?>
    </div>
    </br>

    <?php  if($metric == 'overview'){ ?>
        <!--     FOR DONUT CHART AND BAR GRAPH   -->
        <div class="row">
                <?php $i=1;?>
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="x_panel" >
                        <div class="x_title" >
                            <div class="pieHeadingSA" >Traffic Stats</div>
                            <div id="dateRange_1" class="dateSA"></div>
                            <div class="clearfix"></div>
                        </div>
                        <?php
                            //_p($pageDetails);die;
                            $barGraphForResponses = $pageDetails['BAR_GRAPH']['TRAFFIC'];
                            foreach ($barGraphForResponses as $key => $value) {
                                if($key == "TOP_SUBCATEGORY" || $key == "TOP_COUNTRY"){
                                    $class = "col-md-12 col-sm-12 col-xs-12";
                                }else{
                                    $class = "col-md-6 col-sm-6 col-xs-12";
                                }
                                $data = array('number'  =>$i,
                                              'heading' => $value['heading'],
                                              'class'   => $class,
                                              'misMetric'  => "traffic"
                                              );

                                $this->load->view('trackingMIS/barGraphHorizental',$data); 
                                $i++;
                            }   
                        ?>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="x_panel" >

                        <div class="x_title">
                            <div class="pieHeadingSA">Registrations Stats</div>
                            <div id="dateRange_2" class="dateSA"></div>
                            <div class="clearfix"></div>
                        </div>
                        <?php
                            //_p($pageDetails);die;
                            $barGraphForResponses = $pageDetails['BAR_GRAPH']['REGISTRATION'];
                            foreach ($barGraphForResponses as $key => $value) {
                                if($key == "TOP_SUBCATEGORY"){
                                    $class = "col-md-12 col-sm-12 col-xs-12";
                                }else{
                                    $class = "col-md-6 col-sm-6 col-xs-12";
                                }
                                $data = array('number'=>$i,
                                              'heading' => $value['heading'],
                                              'class'   => $class,
                                              'misMetric'  => "registration"
                                              );

                                $this->load->view('trackingMIS/barGraphHorizental',$data); 
                                $i++;
                            }   
                        ?>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="x_panel" >

                        <div class="x_title">
                            <div class="pieHeadingSA">Responses Stats</div>
                            <div id="dateRange_3" class="dateSA"></div>
                            <div class="clearfix"></div>
                        </div> 
                        <?php
                            //_p($pageDetails);die;
                            $barGraphForResponses = $pageDetails['BAR_GRAPH']['RESPONSES'];
                            foreach ($barGraphForResponses as $key => $value) {
                                if($source == 'abroad'){
                                    if($key == "TOP_SUBCATEGORY" || $key == "TOP_LISTINGS"){
                                        $class = "col-md-12 col-sm-12 col-xs-12";
                                    }else{
                                        $class = "col-md-6 col-sm-6 col-xs-12";
                                    }
                                }else{
                                    if($key == "TOP_LISTINGS"){
                                        $class = "col-md-12 col-sm-12 col-xs-12";
                                    }else{
                                        $class = "col-md-6 col-sm-6 col-xs-12";
                                    }
                                }
                                $data = array('number'=>$i,
                                              'heading' => $value['heading'],
                                              'class'   => $class,
                                              'misMetric'  => "response"
                                              );

                                $this->load->view('trackingMIS/barGraphHorizental',$data); 
                                $i++;
                            }  
                        ?>
                    </div>
                </div>   
        </div>
    <?php 
    }
    else if(
        ($metric == "registration") || 
        ($metric == "traffic")      || 
        ($metric == "engagement")   || 
        ($metric == "response")     || 
        ($metric == "RMC")          || 
        ($pageName == "searchPage" && $teamName =="Study Abroad" && $metric == "home")
    ){
        //_p($pageDetails['DATA_TABLE']);die;
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
        $dataTable =    $pageDetails['DATA_TABLE'];
        if(count($dataTable) >0){
        ?>
        <div class="row" id="<?php echo $dataTable['id']?>">
        <?php 
            $this->load->view('trackingMIS/nationalListings/showTable',array("metric"=>$metric,"pageName"=>$pageName,"teamName"=>$teamName)); ?></div>
        <?php }
    }
    else if($metric == 'leads'){

        $this->load->view('trackingMIS/lineChart',$data);
        $this->load->view('trackingMIS/donutChart', array(
            'id' => 'sourceApplication',
            'title' => 'Leads - Source Application',
        ));
        $this->load->view('trackingMIS/donutChart', array(
            'id' => 'leadsTrafficSource',
            'title' => 'Leads - Traffic Source',));

        $this->load->view('trackingMIS/tabbedBarGraph', array(
            'id' => 'trafficSourceDrillDown',
            'title' => 'Leads - Traffic Source',));
        $this->load->view('trackingMIS/donutChart', array(
            'id' => 'page',
            'title' => 'Leads - Page',));
        $this->load->view('trackingMIS/donutChart', array(
            'id' => 'leadType',
            'title' => 'Leads - Type',));
    }
    else if($teamName =="Study Abroad" && $metric == "exam_upload")
    {
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
                $this->load->view('trackingMIS/nationalListings/showTable');
                ?>
            </div>
        <?php }
    }
    ?>
              
</div>