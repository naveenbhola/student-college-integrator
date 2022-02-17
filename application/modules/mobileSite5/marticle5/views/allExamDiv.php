		<header id="page-header" class="clearfix">
		    <div data-role="header" data-position="fixed" class="head-group ui-header-fixed">
			<a id="engineeringExamOverlayClose" href="javascript:void(0);" data-rel="back" onClick="showEmailResultBar();"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>   	
			<div class="title-text" id="engineeringExamOverlayHeading">Select Entrance Exam</div>
		    </div>
		</header>
		
		<section class="layer-wrap fixed-wrap" style="height: 100%">
		    <ul class="layer-list">
				<?php
				global $engineeringExamPageLinks;
				foreach ($engineeringExamPageLinks as $examGrade=>$examList){ foreach($examList as $exam){?>
					<li><a href="<?=$exam['url']?>"><?=$exam['name']?></a></li>
				<?php }}?>
		    </ul>
		</section>
		<a href="javascript:void(0);" onclick="$('#engineeringExamOverlayClose').click();" class="cancel-btn">Cancel</a>
		
