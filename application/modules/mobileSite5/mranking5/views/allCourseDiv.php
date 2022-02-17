<?php
		$rankingPageList = array (
			array('title' => 'Full time MBA/PGDM', 'url' => SHIKSHA_HOME . '/' . trim('top-mba-colleges-in-india-rankingpage-2-2-0-0-0', '/')),
			array('title' => 'Part-time MBA',  'url' => SHIKSHA_HOME . '/' . trim('top-part-time-mba-colleges-in-india-rankingpage-26-2-0-0-0', '/')),
			array('title' => 'Executive MBA', 'url' => SHIKSHA_HOME . '/' . trim('top-executive-mba-colleges-in-india-rankingpage-18-2-0-0-0', '/')),
		    array('title' => 'BE/BTech',  'url' => SHIKSHA_HOME . '/' . trim('top-engineering-colleges-in-india-rankingpage-44-2-0-0-0', '/')),
		    array('title' => 'LLB',  'url' => SHIKSHA_HOME . '/' . trim('top-llb-colleges-in-india-rankingpage-56-2-0-0-0', '/')),
		
		);
?>
		<header id="page-header" class="clearfix">
		    <div data-role="header" data-position="fixed" class="head-group ui-header-fixed">
			<a id="rankingsCourseOverlayClose" href="javascript:void(0);" data-rel="back" onClick="showEmailResultBar();"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>   	
			<div class="title-text" id="rankingsCourseOverlayHeading">View Rankings for</div>
		    </div>
		</header>
		
		<section class="layer-wrap fixed-wrap" style="height: 100%">
		    <ul class="layer-list">
				<?php
				$totalRankingPages = count($rankingPageList);
				for($count=0; $count < $totalRankingPages; $count++){
						$rankingPage = $rankingPageList[$count];
						$rankingPageTitle = $rankingPage['title'];
						$rankingPageURL   = $rankingPage['url'];
						?>
						<li id="rankingLinks<?=$count+1?>"><a href="<?=$rankingPageURL?>"><?=$rankingPageTitle?></a></li>
						<?php
				}
				?>
		    </ul>
		</section>
		<a href="javascript:void(0);" onclick="$('#rankingsCourseOverlayClose').click();" class="cancel-btn">Cancel</a>
		
