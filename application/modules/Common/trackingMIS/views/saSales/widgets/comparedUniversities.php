<?php
$id = 'comparedUniversities';
?>
<div id = "comparedUniversitiesContainer" class="col-md-12 row" style="display:none;">
    <div class="x_panel" style="position:relative;">
        <div class="x_title" style="position:relative;">
            <h2>Top <span id="comparedUniversitiesCount" style="color:#73879c !important;"> universities</span> compared with <small class="dateRangeTxt"></small></h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content col-md-12">
                <table class="table" id="compareUnivTable" style="margin-bottom: 0px !important">
                      <thead>
                        <tr>
                          <th style="width:10%;">Sl. No.</th>
                          <th style="width:70%;">University Name</th>
                          <th style="width:20%;">Comparisons &#8593;</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr style="display: none;">
                            <td class="sl"></td>
                            <td class="univName">
                                <div class="img-sec"><img src=''></img></div>
                                <div class="img-desc"></div>
                            </td>
                            <td class="compCount"></td>
                        </tr>
                      </tbody>
                </table>
        </div>
    </div>
    <div class="saSalesLoader loader_small_overlay" id = "<?php echo 'loader_'.$id; ?>"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
</div>