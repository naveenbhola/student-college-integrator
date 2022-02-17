<?php
    $currentSourceId=current($rankingPageSource)['source_id'];
    if($previousRankFlag){
        $previousSourceId=next($rankingPageSource)['source_id'];
    }
?>
<div class="cmn-seoTbl">
<div class="fix-admsn hid">
    <div class="prcs-col">
        <div class="head-fix">
           <a  href= 'javascript:void(0);' id="closeSeoTable">&times;</a>
           <h1 class="f14__semi clr__0" id="layerTitle"><?php echo $meta_details['h1']; ?></h1>
        </div>
    </div>
    <div class="new-layer-mask tbl-seo">
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