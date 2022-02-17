<?php
    $currentSourceId=current($rankingPageSource)['source_id'];
    if($previousRankFlag){
        $previousSourceId=next($rankingPageSource)['source_id'];
    }
?>
<div class="layer-common seo_table hid" >
    <div class="rnk_popup " >
        <div class="rnk_hed">
          <div class="rnk_tl titl-ellp"><h1 class="rnk_titl"><?php echo $meta_details['h1']; ?></h1></div>
          <div class="rnk_clos"><a href="javascript:void(0);" class="closeSeoTable">&times;</a></div>
       </div>
       <div class="rnk_Pdiv color-6 rnkgTbl-Cont tbl-seo">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <th width="60%">Institute Name</th>
                        <?php 
                            $publisherData = $rankingPage->getPublisherData();
                            foreach($publisherData as $sourceDetails) { ?>
                                <th width="20%" ><?=$filters['selectedPublisherFilter']->getName().' '.$sourceDetails['year'].' Rank';?></th>
                        <?php } ?>
                    </tr>
                 <?php foreach($rankingPage->getRankingPageData() as $courseId => $rankingPageData ) { 
                        $instituteId = $rankingPageData->getInstituteId();
                        $instituteObject = $instituteInfo[$instituteId]; 
                        if(!is_object($instituteObject)){
                          continue;
                        }
                        ?> 

                        <tr>
                            <td><?php echo htmlentities($instituteObject->getName());?> </td>
                            <td>
                                <?php 
                                $rank = $rankingPageData->getSourceWiseRank(); 
                                if($rank[$currentSourceId]['rank']>0){echo $rank[$currentSourceId]['rank'];} else{ echo "-";}?>
                            </td>    
                               <?php if($previousRankFlag){ ?>
                        <td >
                            <?php if($rank[$previousSourceId]['rank']>0){echo $rank[$previousSourceId]['rank'];} else{ echo "-";}?>
                        </td>
                        </tr>
                 <?php } } ?>   
                </table>        
       </div>
    </div>
</div>
