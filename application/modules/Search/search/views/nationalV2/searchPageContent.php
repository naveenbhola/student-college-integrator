<?php 
if($isAjax) {
    if($totalInstituteCount){ 
    $this->load->view('nationalV2/headingAndSelectedFilters'); ?>    
    <!-- search body starts -->
    <section>
        <div class="container pLR0">
            <?php $this->load->view('nationalV2/filters'); ?>
            <?php $this->load->view('nationalV2/tupleList'); ?>
            <?php $this->load->view('nationalV2/pagination'); ?>
        </div>
        </section>
        <script>
            $j("img.lazy").lazyload({
                effect : "fadeIn",
                threshold : 100
            });
        </script>
        <?php } else { 
                if(array_key_exists('exams', $appliedFilters)){
                    echo 'noresult';exit; 
                }else{?>
                    <div class="container no-result-cont">
                        <?php  $this->load->view('nationalV2/serpNoResultPage'); ?>
                    </div>
                <?php }
            } ?>
    <?php } else { ?>
    <?php $this->load->view('nationalV2/searchPageHeader'); ?>
    <body>
        <div class="srpOverlay"><div class="three-quarters-loader">Loading…</div></div>
        <div class="localitiy-over-layer" style="display:none"></div>
        <?php foreach ($filters['locations']['city'] as $key => $city) {
            $cityId = $city['id'];
            $populateLocLayer = false;
            if(!empty($filters['locations']['locality']['cityWiseLocality'][$cityId])) {
                $locIds = $filters['locations']['locality']['cityWiseLocality'][$cityId];
                foreach ($appliedFilters['locality'] as $key => $appliedLocId) {
                    if(in_array($appliedLocId, $locIds)) {
                        $populateLocLayer = true;
                        break;
                    }
                }
                if($populateLocLayer) {
                    $data = array();
                    $data['localityFilterValues'] = $filters['locations']['locality']['cityWiseLocality'][$cityId];
                    $data['appliedLocality'] = $appliedFilters['locality'];
                    $data['cityId'] = $cityId; ?>
                    <div id='localityLayerContDiv_<?=$cityId?>' class="localityLayerContDiv" style='display:none'><?php $this->load->view('nationalV2/filters/defaultLocalities', $data); ?></div>
                <?php } else { ?>
                    <div id='localityLayerContDiv_<?=$cityId?>' class="localityLayerContDiv" style='display:none'></div>
                <?php }
            }
        } ?>
  
        <div class="wrapper" id="search-container-wrapper">
            <?php 
            if($totalInstituteCount){
            $this->load->view('nationalV2/headingAndSelectedFilters'); ?>
            
            <!-- search body starts -->
            <section>
                <div class="container pLR0">
                    <?php $this->load->view('nationalV2/filters'); ?>
                    <?php $this->load->view('nationalV2/tupleList'); ?>
                    <?php $this->load->view('nationalV2/pagination'); ?>
                </div>
            </section>
            <?php } else{ ?>
                <div class="container no-result-cont">
                    <?php $this->load->view('nationalV2/serpNoResultPage'); ?>
                </div>
            <?php } ?>
            <!-- search body ends -->
        </div>
        <!-- google ad banner -->

        <?php 
        if(strlen(trim($request->getSearchKeyword()))> 0){
            $this->load->view('search/SERP_google_banner',array('keyword'=>$request->getSearchKeyword(),'channelId'=>'home_page','search_type'=>'institute')); 
        }
        ?>
        <?php
            $this->load->view('nationalV2/searchPageFooter');
} ?>