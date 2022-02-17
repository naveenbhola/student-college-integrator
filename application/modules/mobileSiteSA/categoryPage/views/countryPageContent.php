<?php
    if($totalCount == 1){
        $pageHeading = "Top University in ".$countryName;
    }else{
        $pageHeading = "Top Universities in $countryName";
    }
    $pageHeading = str_replace("in Abroad","Abroad",$pageHeading);
?>

<div class="header-unfixed">
    <div class="_topHeadingCont">
        <h1 class="_topHeading"><?=$pageHeading?>&nbsp;(<?= $totalCount ?>)</h1>
    </div>
</div>
<ol class="all-univ-widget dyanamic-content">
    <ol id="countryTupleList" start="<?= (($pageNumber - 1) * $pageSize) + 1 ?>" class="mCountry-tpleList">
        <?php
        $tupleCounter = 0;
        foreach ($tupleData as $data) {
            $this->load->view("categoryPage/widgets/countryPageTuple", $data);
            // if ($tupleCounter == 2 || ($tupleCounter==$totalCount-1 && $tupleCounter<2)) {
            //     $this->load->view("categoryPage/widgets/countryPageQuickLinks", $popularCourseData);
            // }

            if ($tupleCounter == 9 || ($tupleCounter==$totalCount-1 && $tupleCounter<9)) {?>
                <div class="rnkDisclmr">
                    The ranking for top universities in <?php echo $countryName ?> is based on university popularity on <a href="<?php echo SHIKSHA_HOME; ?>">Shiksha.com</a>
                </div>
            <?php }

            $tupleCounter++;
        }
        ?>
    </ol>
</ol>


    <?php if($pageNumber*$pageSize < $totalCount){?>
        <p id="loadMoreUnivButton" class="loading-txt"></p>
       <!-- <a id="loadMoreUnivButton" style="background:#566ec2;font-size:10px; margin-top:20px;" class="btn btn-default btn-full" onclick="loadMoreUniversities()">View <?php echo min($totalCount-$pageNumber*$pageSize,$pageSize)?> more universit<?php echo min($totalCount-$pageNumber*$pageSize,$pageSize)==1?'y':'ies'?></a> -->
    <?php } ?>
    <noscript>
        <?php if($relNext){ ?>
            <a href="<?php echo $relNext; ?>">Next</a>
        <?php } ?>
        <?php if($relPrev){ ?>
            <a href="<?php echo $relPrev; ?>">Previous</a>
        <?php } ?>
    </noscript>
</section>
<script>
    var pageSize = <?php echo $pageSize ?>;
    var pageNumber = <?php echo $pageNumber?>;
    var totalCountTuple = <?php echo $totalCount?>;
    var countryPageUrl = "/<?php echo seo_url_lowercase(strtolower($countryName)); ?>/universities";
</script>
