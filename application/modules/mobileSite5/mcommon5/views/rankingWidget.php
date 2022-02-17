<?php

	if($rankingDataArray[0]['course_name']=='FULL-TIME MBA'){
	  $positionLayer1 = 1;
	}else if($rankingDataArray[0]['course_name']=='Part-Time MBA'){
	  $positionLayer1 = 2;
	}else if($rankingDataArray[0]['course_name']=='Executive MBA'){
	  $positionLayer1 = 3;
	}else if($rankingDataArray[0]['course_name']=='BE/B.Tech'){
	  $positionLayer1 = 4;
	}else{
	  $positionLayer1 = 5;
	}
	
	if($rankingDataArray[1]['course_name']=='FULL-TIME MBA'){
	  $positionLayer2 = 1;
	}else if($rankingDataArray[1]['course_name']=='Part-Time MBA'){
	  $positionLayer2 = 2;
	}else if($rankingDataArray[1]['course_name']=='Executive MBA'){
	  $positionLayer2 = 3;
	}else if($rankingDataArray[1]['course_name']=='BE/B.Tech'){
	  $positionLayer2 = 4;
	}else{
	  $positionLayer2 = 5;
	}


?>
 
 
            <section class="clearfix content-wrap">
            	<header class="content-inner content-header">
                	<h2 class="title-txt">Top Ranked Colleges in India</h2>
                </header>
                <article class="clearfix content-inner">
                	<div class="top-colleges">
			    <?php if(isset($rankingDataArray[0]) && is_array($rankingDataArray[0])){ ?>
			    <a href="<?=$rankingDataArray[0]['link']?>" onClick="trackEventByGAMobile('MOBILE_RANKING1_LINK_CLICK_FROM_HOMEPAGE');" >
				<div class="col-details">
				    <h3><?=$rankingDataArray[0]['course_title']?></h3>
				    <?=$rankingDataArray[0]['course_description']?>
				</div>
				<span><i class="msprite rt-arr"></i></span>
			    </a>
			    <?php } ?>
				
			    <?php if(isset($rankingDataArray[1]) && is_array($rankingDataArray[1])){ ?>				
			    <a href="<?=$rankingDataArray[1]['link']?>" style="background:#9e78c8" onClick="trackEventByGAMobile('MOBILE_RANKING2_LINK_CLICK_FROM_HOMEPAGE');" >
				<div class="col-details">
				    <h3><?=$rankingDataArray[1]['course_title']?></h3>
				    <?=$rankingDataArray[1]['course_description']?>
				</div>
				<span><i class="msprite rt-arr"></i></span>
			    </a>
			    <?php } ?>
    
			    <?php if(isset($rankingDataArray[2]) && is_array($rankingDataArray[2])){ ?>
			    <a href="#rankingCourseHamburgerDiv" style="background:#b598d5"  data-inline="true" data-rel="dialog" data-transition="slide" onClick="trackEventByGAMobile('MOBILE_RANKING3_LINK_CLICK_FROM_HOMEPAGE');" >
				<div class="col-details">
				    <h3><?=$rankingDataArray[2]['course_title']?></h3>
				    <?=$rankingDataArray[2]['course_description']?>
				</div>
				<span><i class="msprite rt-arr"></i></span>
			    </a>
			    <?php } ?>

			</div>    
    		</article>
            </section>

<script>
function hideShownRankingPages(id1,id2) {
    $('#rankingLinks'+id1).hide();
    $('#rankingLinks'+id2).hide();
}

$(document).ready(function() {
    hideShownRankingPages('<?=$positionLayer1?>','<?=$positionLayer2?>');
});

</script>
