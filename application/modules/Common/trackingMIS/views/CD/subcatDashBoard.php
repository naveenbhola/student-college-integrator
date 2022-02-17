 <?php 
        $this->load->config('cdTrackingMISConfig');     
        $headings = $this->config->item("charts");
        $helperText = array(
          'byInstitute' => 'Directly Landing on Institute Detail Page or CourseDetail Page',
          'bySubcatId'  => 'Directly Landing on Institute Detail Page or CourseDetail Page',
          'byUniversity' => 'Directly Landing on University Detail Page or CourseDetail Page',
          'bySubcatId_SA' => 'Directly Landing on University Detail Page or CourseDetail Page',
          'content-bySubcatId' => 'Directly Landing on Article Detail Page',
          'byArticle' => 'Directly Landing on Article Detail Page',
          'SA-Content-bySubcatId' => 'Directly Landing on Article Detail Page',
          'SA-Content-byArtcileId' => 'Directly Landing on Article Detail Page',
          'NationalDiscussions' => 'Directly Landing on Discussion Detail Page',
          'byDiscussionId' => 'Directly Landing on Discussion Detail Page',
          );
?>
<div class ="row">
  <?php 
  foreach ($headings[$page]['Top_LINE'] as $key => $value) {
        $dataLine = array('key' => $key,'value' => $value);
        $this->load->view('trackingMIS/CD/topLineChart',$dataLine);
  }
  ?>
  </div>
<div class ="row">
  <?php 
  foreach ($headings[$page]['LINE_CHART'] as $key => $value) {
        $dataLinechart = array('key' => $key,'value' => $value);
        $this->load->view('trackingMIS/CD/linechartCount',$dataLinechart);
  }
  ?>
  </div>
 <div class="row">
       <?php 
            $i =1;
          
            $size = sizeof($headings[$page]['PIE_CHART']);
            $increment = $size;
            foreach ($headings[$page]['PIE_CHART'] as $key => $value) 
            {
              $data = array('number'=>$i,
                            'key' => $key,
                            'value'=>$value,
                            'helperText' => $helperText[$page]);
              $this->load->view('trackingMIS/CD/piechart',$data);
              $data ='';
              $data = array('number'=>($i+$increment),
                            'key' => $key,
                            'value'=>$value,
                            'helperText' => $helperText[$page]);
              $this->load->view('trackingMIS/CD/piechart',$data);
              $i++;  
            }
            //---------------------
        ?> 
    </div>
    <div class="row" id="dataTableDiv" style="display:none">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" id="dataTable">
                <div class="x_title">
                    <h2 id="dataTableHeading">Actual Delivery To Clients</h2>
                    
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table id="example" class="table table-striped responsive-utilities jambo_table" aria-describedby="example_info">


                    </table>
              </div>
            </div>  
</div>
</div>
      <div class ="row" id ="mapdiv" style="">
        
        <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Unique Users <small>Top 20 Cities geo-presentation</small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="loader_small_overlay" style="" id="mapdivLoader">
                <img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif">
            </div>
                                    <div class="x_content">
                                        <div class="dashboard-widget-content">
                                            <div id="world-map-gdp" class="col-md-8 col-sm-12 col-xs-12" style="height:500px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
          
</div>