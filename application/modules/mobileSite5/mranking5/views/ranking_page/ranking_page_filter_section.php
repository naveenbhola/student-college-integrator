<?php 
	$publisher = reset($rankingPage->getPublisherData());
?>
<div class="ranking__filtrs table__fit">
	<div class="filter-container-div">
		<div class="fit__in__cell rank__by ">
			<a class="fit__block" id="ranked__by"  href="javascript:void(0);">
				<span class="f11__normal clr__6 fit__block">RANKED BY</span>
				<span class="txt__rslt f14__semi clr__1" data-year="<?=$publisher['year']?>">
				<em class="hide__txt"><?=$filters['selectedPublisherFilter']->getName().' '.$publisher['year'];?></em>
				<i class="ranking__sprite rank__find"></i></span>
			</a>
		</div>
		<div class="fit__in__cell filtr__by ">
			<a class="fit__block fit__to__cntr" href="javascript:void(0);" id="filter__by">
				<span class="f11__normal clr__6 fit__block">FILTERS</span>
				<i class="ranking__sprite filtr__find"></i>
			</a>
		</div>
		<div class="fit__in__cell srch__by">
			<a class="fit__block fit__to__cntr" id="srch__by" ga-attr="SEARCH">
				<span class="f11__normal clr__6 fit__block">SEARCH</span>
				<i class="ranking__sprite srch__find"></i>
			</a>
		</div>
		<input type="text" name="" placeholder="Search a College within this ranking" class="srch__hide">
		<i class="ranking__sprite srch__cross"></i>
	</div>
</div>