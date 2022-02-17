<div id="userStageData" class="col-md-12 col-sm-12 col-xs-12" style="display:none;">
  <div class="x_panel mt">
  <div class="container">
  <h2 class="f18 ctgray fbold">Number of Students in a stage <small class="dateRangeTxt"></small></h2>
      <div class="row">
      <div class=" col-sm-4 col-xs-12 studentCountTile" linkFor="shortlistStdDtl" hideClass="stgdtl" >
        <div class="x_panel ct act">
		        <p class="card-title f18 ctgray mt">Shortlist Sent</p>
            <p class="f48 ctgray" id="barShortlistCount"></p>
        </div>
      </div>
      <div class=" col-sm-4 col-xs-12 studentCountTile" linkFor="finalizedStdDtl" hideClass="stgdtl">
        <div class="x_panel ct">
		 
             <p class="card-title f18 ctgray mt">University finalized</p>
             <p class="f48 ctgray" id="barFinalizedCount"></p>
             <p class="f14 ctgray" id="barFinalizedPercentage">(80% percentage)</p>
          </div>
      </div>
      <div class=" col-sm-4 col-xs-12 studentCountTile" linkFor="applicationStdDtl" hideClass="stgdtl">
        <div class="x_panel ct">
		 
             <p class="card-title f18 ctgray mt">Application</p>
             <p class="f48 ctgray" id="barApplicationCount">10</p>
             <p class="f14 ctgray" id="barApplicationPercentage">(84% percentage)</p>
         </div>
      </div>
      <div class="saSalesLoader loader_small_overlay" id = "loader_shortlistbar"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
    </div> 
    </div> 

  <!--profilecasr-->
    <div class="col-md-12 col-xs-12 bg-col stgdtl" id="shortlistStdDtl" style="display: none;"></div>
    <div class="col-md-12 col-xs-12 bg-col stgdtl" id="finalizedStdDtl" style="display: none;"></div>
    <div class="col-md-12 col-xs-12 bg-col stgdtl" id="applicationStdDtl" style="display: none;"></div>
  <!--d-->
  </div>
</div>
<!--panels-->