<div id="courseSectionCountrySelectionLayer" data-role="page" data-enchance="false" style="width:100% !important;height:100% !important;">
    <div class="layer-header">
            <a href="#wrapper" data-rel="back" data-transition="slide" class="back-box" onclick = "setTimeout(function(){resizeCourseSelectionWidget();},200);"><i class="sprite back-icn"></i></a>
            <p>Study Destinations For <?=str_replace("Certificate - Diploma","Certificate / Diploma",$courseSelectionWidgetData['countrySelectionLayerTitle'])?></p>
    </div>
    <section class="layer-wrap clearfix">
        <article class="content-inner">
                <div class="filter-search-outer"  data-enhance="false">
                        <div style="top:3px; left:2px" class="sprite search-icn flLt"></div>
                        <div style="margin-left:22px;">
                                <input id="filterCountriesBox" type="text" class="universal-txt search-field">
                        </div>
                </div>
                <ul id="countryListSearchList" data-filter="true" data-input="#filterCountriesBox" data-filter-placeholder="Search Countries" data-filter-class="universal-txt search-field" class="list-contents" style="padding:0 8px">
                        <?php foreach($courseSelectionWidgetData['countryList'] as $country){?>
                                <li><a id="csl_<?=$country['name']?>" href="<?=$country['url']?>" rel="external"><?=$country['name']?><i class="sprite rt-arr"></i></a></li>
                        <?php } ?>
                </ul>
                <div style="display:none;left:5px;padding-top: 7px;text-align: center;width: 100%;padding-bottom: 7px;" id="countryListNoResult">No Countries Matched your Search String</div>
        </article>
        
    </section>
</div>