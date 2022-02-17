<?php
$id = 'topCitiesMap';
?>
<div id = "topCitiesMapContainer" class="col-md-12 row" style="display:none;">
    <div class="x_panel" style="position:relative;">
        <div class="x_title" style="position:relative;">
            <h2>Top 10 cities 
            <small>Based on number of responses generated city wise</small>
            <small class="dateRangeTxt"></small></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content col-md-12">
            <div id="ggltopCitiesMap" style="width: 49%; height: 480px; display:inline-block;" ></div>
            <div id="ggltopCitiesTable" style="width: 49%; height: 480px; display:inline-block;float:right;" >
                <table class="table">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>City Name</th>
                          <th>Responses &#8593;</th>
                        </tr>
                      </thead>
                      <tbody>
                        
                      </tbody>
                    </table>
            </div>
        </div>
    </div>
    <div class="saSalesLoader loader_small_overlay" id = "<?php echo 'loader_'.$id; ?>"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
</div>