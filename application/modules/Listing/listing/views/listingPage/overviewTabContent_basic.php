<div class="desc-details-wrap">
	<h5 class="desc-title-box pointer-cursor">
		<span class="sprite-bg closed-arrow courseHeader" id="courseHeader" onclick="showDesc('more','course'); return false;"></span>
		<strong onclick="showDesc('more','course'); return false;" class="courseHeader" onclick="showDesc('more','course'); return false;">Course Description - </strong>
		<p>
		<?php
			$i = 1;
			foreach($courseComplete->getDescriptionAttributes() as $attribute){
				$i++;
				if($i > 5){
					break;
				}
		?>
		<a href="#" onclick="showDesc('more','course'); $j('body,html').animate({scrollTop:$j('#<?=preg_replace("/[^a-z0-9_]+/i", "-",$attribute->getName())?>').offset().top - 10},500);return false;"><?=$attribute->getName()?></a> &nbsp;|&nbsp;
		<?php
			}
		?>
		</p>
	</h5>
	<?php
			foreach($courseComplete->getDescriptionAttributes() as $attribute){
				if($attribute->getName() != "Course Description"){
					$class = "courseOthers";
				}else{
					$class = "courseDesc";
				}
		?>
	<div class="<?=$class?>">
		<h6 class="sub-title" id="<?=preg_replace("/[^a-z0-9_]+/i", "-",$attribute->getName())?>"><?=$attribute->getName()?></h6>
		<div class="user-content" style="padding:20px;overflow-y:auto">
			<?php
				$summary = new tidy();
				$summary->parseString($attribute->getValue(),array('show-body-only'=>true),'utf8');
				$summary->cleanRepair();
			?>
			<?=$summary?>
		</div>
		<div class="spacer10 clearFix"></div>
	</div>
	<?php
		}
	?>
		<div class="pL-7">
		<a href="#" class="see-all-link bld courseReadMore" onclick="showDesc('more','course'); return false;">Read More <span class="sprite-bg"></span></a>
		<a href="#" class="see-all-link bld courseReadLess" onclick="$j('body,html').animate({scrollTop:$j('#courseHeader').offset().top - 160},500);showDesc('less','course'); return false;">Less <span class="sprite-bg"></span></a>
		<div class="spacer10 clearFix"></div>
		<!--<button class="orange-button">I am interested in studying at JK Buisness School <span class="btn-arrow"></span></button>-->
	</div>
</div>

<div class="desc-details-wrap">
	<h5 class="desc-title-box pointer-cursor">
		<span class="sprite-bg closed-arrow instituteHeader" id="instituteHeader" onclick="showDesc('more','institute'); return false;"></span>
		<strong onclick="showDesc('more','institute'); return false;" class="instituteHeader" onclick="showDesc('more','institute'); return false;">College Description - </strong>
		<p>
		<?php
			$i = 1;
			foreach($instituteComplete->getDescriptionAttributes() as $attribute){
				$i++;
				if($i > 5){
					break;
				}
		?>
		<a href="#" onclick="showDesc('more','institute'); $j('body,html').animate({scrollTop:$j('#<?=preg_replace("/[^a-z0-9_]+/i", "-",$attribute->getName())?>').offset().top - 10},500);return false;"><?=$attribute->getName()?></a> &nbsp;|&nbsp;
		<?php
			}
		?>
		</p>
	</h5>
	<?php
			foreach($instituteComplete->getDescriptionAttributes() as $attribute){
				if($attribute->getName() != "Institute Description"){
					$class = "instituteOthers";
				}else{
					$class = "instituteDesc";
				}
		?>
	<div class="<?=$class?>">
		<h6 class="sub-title" id="<?=preg_replace("/[^a-z0-9_]+/i", "-",$attribute->getName())?>"><?=$attribute->getName()?></h6>
		<div class="user-content" style="padding:20px;overflow-y:auto">
			<?php
				$summary = new tidy();
				$summary->parseString($attribute->getValue(),array('show-body-only'=>true),'utf8');
				$summary->cleanRepair();
			?>
			<?=$summary?>
		</div>
		<div class="spacer10 clearFix"></div>
	</div>
	<?php
		}
	?>
		<div class="pL-7">
		<a href="#" class="see-all-link bld instituteReadMore" onclick="showDesc('more','institute'); return false;">Read More <span class="sprite-bg"></span></a>
		<a href="#" class="see-all-link bld instituteReadLess" onclick="$j('body,html').animate({scrollTop:$j('#instituteHeader').offset().top - 160},500);showDesc('less','institute'); return false;">Less <span class="sprite-bg"></span></a>
		<div class="spacer10 clearFix"></div>
		<!--<button class="orange-button">I am interested in studying at JK Buisness School <span class="btn-arrow"></span></button>-->
	</div>
</div>
		
