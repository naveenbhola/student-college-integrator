<section class="crse-rnkng-list">
<ul id="rankingpage-tuple-count">
<?php
    if($rankingPageObject->getType()=='course')
    {
        $this->load->view('widgets/courseRankingPageTuples');
    }
    else
    {
        $this->load->view('widgets/universityRankingPageTuples');
    } 
?>
</ul>
</section>
<div class="clearfix"></div>
<?php if($totalRankingTuplesCount > ($rankingTuplesCount)){  ?>
<div id="loadMoreRankingPageTupleButton" class="load-more"><a style="margin-bottom:20px;">Load More</a></div>
<?php } ?>
<script>
var rankingId ='<?php echo $rankingId;?>';
isCompareEnable = true;
var totalRankingTuplesCount ='<?php echo $totalRankingTuplesCount;?>';
</script>