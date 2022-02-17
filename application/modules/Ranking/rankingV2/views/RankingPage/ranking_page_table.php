<?php
    $currentSourceId=current($rankingPageSource)['source_id'];
    if($previousRankFlag){
        $previousSourceId=next($rankingPageSource)['source_id'];
    }
?>
<input type='hidden' id='RankingPageId' value='<?php echo $rankingPageId; ?>' >
<div class="cnt_bdr">
    <div class="rnk_contnr">
        <div class="noRslt-dv" style="display: none;">
            <p class="noRslt-mhd">Sorry no colleges found for &quot;<span class="searchterm"></span>&quot;</p>
            <p class="noRslt-hd">Here is what you can do :</p>
            <ul>
                <li>Check your Spelling</li>
                <li>Try more general words</li>
            </ul>
        </div>
        <table border="0" cellpadding="0" cellspacing="0" class="rnk_tbl">
        <thead>
            <tr class="hd-crl">
                <th width="7%" class="tac fnt__sb">Rank'17</th>
                <th width="7%" class="tac fnt__sb">Rank'16</th>
                <th width="31%" class="fnt__sb p-Lft">Colleges</th>
                <th width="22%">&nbsp;</th>
                <th width="33%">&nbsp;</th>
            </tr>
        </thead>  
            <?php 
            if(empty($rankingPage)){
               echo "<tr><td colspan='5'><p><i style='color:#FD8103;'>Sorry, no results were found matching your selection. Please alter your refinement options above.</i></p></td></tr>";
            }
            $chunkedRankingpageData = array_chunk($rankingPage->getRankingPageData(),$tuplesPerPage,true);
            //_p($chunkedRankingpageData);die;

            foreach ($chunkedRankingpageData as $pageNumber => $rankingPageData) {
                $count = 1;
                foreach($rankingPageData as $courseId => $pageData){
                    $this->load->view(RANKING_PAGE_MODULE.'/RankingPage/ranking_page_tuple',array('pageData'=>$pageData,'currentSourceId'=>$currentSourceId,'previousSourceId'=>$previousSourceId,'tupleNumber' => $count, 'pageNumber' => $pageNumber));
                    // _P($rankingPageOf['FullTimeMba']);
                    if($count == 20 && $rankingPageOf['FullTimeMba']==1){
                        ?>
                        <tr pn="<?php echo $pageNumber+1; ?>" class="<?php echo $pageNumber ? 'hid' : 'initial'; ?>">
                            <td colspan="5" class="noPdng">
                                <?php $this->load->view(RANKING_PAGE_MODULE.'/RankingPage/ranking_page_inline_banner'); ?>
                            </td>
                        </tr>
                        <?php
                    }
                    if($count == 10 && $rankingPageOf['FullTimeMba']==1){
                        ?>
                        <tr pn="<?php echo $pageNumber+1; ?>" class="<?php echo $pageNumber ? 'hid' : 'initial'; ?>">
                            <td colspan="5" class="i-block" width="100%" style="padding-left:83px;">
                                <?php $this->load->view('common/IIMCallPredictorBanner',array('productType'=>'rankingPage')); ?>
                            </td>
                        </tr>
                        <?php
                    }

                    if( 0 && $count == 10 && $rankingPageOf['Engineering'] == 1){ //disabled
                        ?>
                        <tr pn="<?php echo $pageNumber+1; ?>" class="<?php echo $pageNumber ? 'hid' : 'initial'; ?> engbanner">
                            <?php 
                                if($pageNumber == 0){
                                    ?>
                                    <td style="padding:5px 5px 10px 5px;" width="100%" colspan="7" class="">
                                    <!-- <p style='font-size:18px; margin:10px 0 12px;'>Engineering College Reviews</p> -->
                                        <?php
                                            $bannerProperties1 = array('pageId'=>'RANKING', 'pageZone'=>'MIDDLE');
                                            $this->load->view('common/banner',$bannerProperties1);
                                        ?>
                                    </td>
                                    <?php
                                }
                            ?>
                        </tr>
                        <?php
                    }
                    $count++;
                }
                if($count>10 && $count<=20 && $rankingPageOf['FullTimeMba']==1)
                { ?>
                    <tr pn="<?php echo $pageNumber+1; ?>" class="<?php echo $pageNumber ? 'hid' : 'initial'; ?>">
                        <td colspan="5" class="noPdng">
                            <?php $this->load->view(RANKING_PAGE_MODULE.'/RankingPage/ranking_page_inline_banner'); ?>
                        </td>
                    </tr>
                <?php }
                if($count<=10 && $rankingPageOf['FullTimeMba']==1)
                {  ?>
                    <tr pn="<?php echo $pageNumber+1; ?>" class="<?php echo $pageNumber ? 'hid' : 'initial'; ?>">
                        <td colspan="5" class="i-block" width="100%" style="padding-left:83px;">
                            <?php $this->load->view('common/IIMCallPredictorBanner',array('productType'=>'rankingPage')); ?>
                        </td>
                    </tr>
                    <tr pn="<?php echo $pageNumber+1; ?>" class="<?php echo $pageNumber ? 'hid' : 'initial'; ?>">
                        <td colspan="5" class="noPdng">
                            <?php $this->load->view(RANKING_PAGE_MODULE.'/RankingPage/ranking_page_inline_banner'); ?>
                        </td>
                    </tr>
                <?php }
                if(0 && $count<=10 && $rankingPageOf['Engineering'] == 1)
                {  ?>
                        <tr pn="<?php echo $pageNumber+1; ?>" class="<?php echo $pageNumber ? 'hid' : 'initial'; ?> engbanner">
                            <?php 
                                if($pageNumber == 0){
                                    ?>
                                    <td style="padding:5px 5px 10px 5px;" width="100%" colspan="7" class="">
                                    <!-- <p style='font-size:18px; margin:10px 0 12px;'>Engineering College Reviews</p> -->
                                        <?php
                                            $bannerProperties1 = array('pageId'=>'RANKING', 'pageZone'=>'MIDDLE');
                                            $this->load->view('common/banner',$bannerProperties1);
                                        ?>
                                    </td>
                                    <?php
                                }
                            ?>
                        </tr>
                <?php }
                } 
                ?>   
            
            
       </table>
    <?php    
        $data['totalPages'] = count($chunkedRankingpageData);
        $data['maxPageOnPaginationDisplay'] = 10;
        if($data['totalPages']>1)
     $this->load->view(RANKING_PAGE_MODULE.'/RankingPage/ranking_page_pagination',$data); ?>    
     <a href="javascript:void(0)" class="shwDat-link" id="rnkTbl-btn">Show Data in Table</a>
     <div class="rnkgTbl-shdwLyr"></div>   
    </div>
</div>
<script>
    isCompareEnable=true;
    var instituteDataMapping = JSON.parse(JSON.stringify(<?php echo json_encode($instituteDataMapping); ?>));
    // for courseList for CTA dropdown
    <?php if($tupleType == 'institute') { ?>
        var rankingCriteriaCourses = JSON.parse(JSON.stringify(<?php echo json_encode($rankingCriteriaCourses); ?>));
    <?php } ?>
</script>