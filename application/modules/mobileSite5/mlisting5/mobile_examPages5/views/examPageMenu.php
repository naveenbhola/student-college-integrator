<?php
$sectionNamesMapping = $this->config->item("sectionNamesMapping");

$classMappingWithTile = array('home'             =>'exam-mini-sprite exam-icon',
                              'syllabus'         =>'exam-mini-sprite syllabus-icon',
                              'imp_dates'        =>'exam-mini-sprite date-icon',
                              'colleges'         =>'exam-mini-sprite college-icon',
                              'article'          => 'exam-mini-sprite news-article-icon',
                              'preptips'         => 'exam-mini-sprite prep-tip-icon',
                              'results'          => 'exam-mini-sprite result-icon'
                              );

$shortNames 	      = array('home'             =>'About Exam',
                              'syllabus'         =>'Syllabus',
                              'imp_dates'        =>'Imp Dates',
                              'colleges'         =>'Colleges Accepting '.$examPageData->getExamName(),
                              'article'          => 'Articles',
                              'preptips'         => 'Prep Tips',
                              'results'          => 'Results'
                              );

?>

<div class="exam-nav-wrap clearfix" id="menuExamBar" style="position:fixed;bottom:0px;left:0px;margin-bottom:0px; margin-top:10px;z-index: 9999">
<ul class="exam-nav">

    <?php
    $index = 0;
    foreach ($activeTileDetails as $activeTileDetail){

        // don't show the nav link if its flag is off
        if($activeTileDetail['show_link_in_menu'] == 0 ){
            continue;
	}
        if($activeTileDetail['name'] == 'discussion'){
            continue;
        }
        if($index>=3){
            continue;
        }
    ?>
    <li <?php if($activeTileDetail['name']==$pageType){ echo 'class="active"';} ?> onclick="trackEventByGAMobile('HTML5_NAVIGATION_<?php echo $activeTileDetail['name'];?>');">
        <a href="<?php echo ${$activeTileDetail['name']}['url'];?>">
            <i class="<?php echo $classMappingWithTile[$activeTileDetail['name']];?>"></i><br>
            <?=$shortNames[$activeTileDetail['name']]?>
        </a>
    </li>
    <?php
    $index++;
    } ?>

    <!-- Show More Link if Menu contains more than 3 Links -->
    <?php
        if($index>=3){ ?>
        
            <li onclick="trackEventByGAMobile('HTML5_NAVIGATION_MORE');">
                <a href="#navigationLayer" data-position-to="window" data-inline="true" data-rel="popup" id="navigationLayerLink" data-transition="slideup" >
                    <i class="exam-mini-sprite more-exams"></i><br>
                    More
                </a>
            </li>
            
        <?php
        }
    ?>
</ul>
</div>

<style>
/*#navigationLayer-popup{width:88%; left: 10px !important; right: 10px !important;}*/
</style>
<div data-role="popup" class="exam-nav-layer" id="navigationLayer" data-theme="d">
    <ul>
    
        <?php
        foreach ($activeTileDetails as $activeTileDetail){
	    // don't show the nav link if its flag is off
            if($activeTileDetail['show_link_in_menu'] == 0 ){
	        continue;
            }
            if($activeTileDetail['name'] == 'discussion'){
                continue;
            }
            if($sectionNamesMapping[$activeTileDetail['name']] == 'Top Colleges') {
                $sectionNamesMapping[$activeTileDetail['name']] = 'Colleges Accepting '.$examPageData->getExamName();
            }
        ?>

        <li onclick="trackEventByGAMobile('HTML5_NAVIGATION_MENU_LAYER_<?php echo $activeTileDetail['name'];?>');">
                <a href="<?php echo ${$activeTileDetail['name']}['url'];?>">
                    <i class="<?php echo $classMappingWithTile[$activeTileDetail['name']];?> flLt"></i>
                    <label><?=$sectionNamesMapping[$activeTileDetail['name']]?></label>
                </a>
        </li>

        <?php
        } ?>

        <li class="last"> <a href="javascript:void(0)" data-rel="back" class="close-layer">&times;</a></li>
        
    </ul>
</div>

