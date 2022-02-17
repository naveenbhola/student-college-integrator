<div class="right_col" role="main">          
    <!-- top tiles -->
    <div class='row'>
    <?php
    if(strtolower($metric) != 'response' && strtolower($metric) != 'rmc'){
        $this->load->view('trackingMIS/studyAbroad/topTiles');
        $this->load->view('trackingMIS/studyAbroad/lineChart',$data);
    }
    ?>
    </div>
    </br>
    <!--     FOR DONUT CHART   --> 
    <div class="row">
       <?php 
            $i =1;
          
            $size = sizeof($pageDetails['PIE_CHART']['data']);
            $increment = $size;
            foreach ($pageDetails['PIE_CHART']['data'] as $key => $value) 
            {
              //_p($value['heading']);
              $data = array('number'=>$i,
                            'value'=>$value);
              $this->load->view('trackingMIS/studyAbroad/pieChart',$data);
              $data ='';
              $data = array('number'=>($i+$increment),
                            'value'=>$value);
              $this->load->view('trackingMIS/studyAbroad/pieChart',$data);
              $i++;
              $metricArray = array('REGISTRATION', 'LEADS');
              if(
                  (in_array($metric,$metricArray)   && $key == 'TRAFFIC_SOURCE' ) ||
                  ($metric =='ENGAGEMENT' && $key == 'TRAFFIC_SOURCE') ||
                  ($metric =='TRAFFIC' && $key == 'TRAFFIC_SOURCE') ||
                  ($metric =='LEADS' && $key == 'TRAFFIC_SOURCE')
              ){ ?>
                <div class="col-md-12 col-sm-12 col-xs-12" id="SAUTMFilter" >
                    <div class="loader_utm_overlay" id="UTMFilterLoader"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/loader_MIS.gif"></div>
                    <div class="x_panel" >
                        <div class="x_title" >
                            <div class="pieHeadingSA" >Traffic Source Split</div>
                            <div id="dateRange_1" class="dateSA"></div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                <ul id="trafficSourceFilter" class="nav nav-tabs bar_tabs" role="tablist">
                                </ul>
                                <div id="myTabContent" class="tab-content">    
                                    
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            <?php }
            } 

            $heading = array('Source Application','Sourcewise Usage','Userwise','Pagewise');
            if($metric == 'ENGAGEMENT')
            {
              $size = sizeof($heading);
              for ($i=0; $i < $size; $i++) { 
                $data = array('number'=>($i+1),
                              'heading' => $heading[$i]);
                $this->load->view('trackingMIS/studyAbroad/barGraph',$data);

                $data = array('number'=>($i+$size+1),
                              'heading' => $heading[$i]);
                $this->load->view('trackingMIS/studyAbroad/barGraph',$data);
                if($heading[$i] == 'Sourcewise Usage'){ ?>
                  <div class="col-md-12 col-sm-12 col-xs-12" id="SAUTMFilterForOtherEng" >
                    <div class="loader_utm_overlay" id="UTMFilterLoader"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/loader_MIS.gif" style="display:block"></div>
                    <div class="x_panel" >
                        <div class="x_title" >
                            <div class="pieHeadingSA" >Traffic Source Split</div>
                            <div id="dateRange_1" class="dateSA"></div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                <ul id="trafficSourceFilterForOther" class="nav nav-tabs bar_tabs" role="tablist">
                                </ul>
                                <div id="myTabContent1" class="tab-content">    
                              
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <?php }
              }
            }
       else if ($metric == 'RESPONSE'){
           $this->load->view('trackingMIS/studyAbroad/topTiles');
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
       else if ($metric == 'RMC'){
        if($pageName == "Study Abroad"){
            $this->load->view('trackingMIS/studyAbroad/topTiles');
             $this->load->view('trackingMIS/lineChart');
             $this->load->view('trackingMIS/donutChart', array(
                 'id' => 'device',
                 'title' => 'RMC Responses - Source Application',
             ));
             $this->load->view('trackingMIS/donutChart', array(
                 'id' => 'session',
                 'title' => 'RMC Responses - Traffic Source',));
             $this->load->view('trackingMIS/tabbedBarGraph', array(
                 'id' => 'trafficSourceDrillDown',
                 'title' => 'Responses - Traffic Source',));
             $this->load->view('trackingMIS/donutChart', array(
                 'id' => 'pivotType',
                 'title' => 'Responses - Type',));
             $this->load->view('trackingMIS/donutChart', array(
                 'id' => 'page',
                 'title' => 'Responses - Page',));
        }else{
            $this->load->view('trackingMIS/studyAbroad/topTiles');
             $this->load->view('trackingMIS/lineChart');
             $this->load->view('trackingMIS/donutChart', array(
                 'id' => 'device',
                 'title' => 'RMC Responses - Source Application',
             ));
             $this->load->view('trackingMIS/donutChart', array(
                 'id' => 'session',
                 'title' => 'RMC Responses - Traffic Source',));
             $this->load->view('trackingMIS/tabbedBarGraph', array(
                 'id' => 'trafficSourceDrillDown',
                 'title' => 'Responses - Traffic Source',));
             $this->load->view('trackingMIS/donutChart', array(
                 'id' => 'pivotType',
                 'title' => 'Responses - Type',));    
        }
           
       }
        ?> 
    </div>
    <!--     FOR DATA TABLE   -->

    <?php if(($metric == 'RESPONSE' )||($metric == 'RMC' )){ ?>
    <div class="row"><?php $this->load->view('trackingMIS/nationalListings/showTable'); ?></div>
    <?php } else { ?>
    <div class="row" id="SADataTable"><?php $this->load->view('trackingMIS/studyAbroad/dataTable'); ?></div>
    <?php } ?>
</div>