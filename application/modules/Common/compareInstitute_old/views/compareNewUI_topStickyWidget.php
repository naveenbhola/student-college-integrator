<div class="sticky-comprae" id="comparePageStickyTop">
	<table width="100%" class="cmpre-table" cellpadding="0" cellspacing="0">
	<tbody>
	<tr>
		<td style="padding-top:13px"><div class="cmpre-head">&nbsp;</div></td>
	<?php 
	$j = 0;
	foreach($institutes as $institute){
		$j++;
		$course = $institute->getFlagshipCourse();
		$course->setCurrentLocations($request);
		if(strlen($institute->getName()) > 35){
			$instStr  = substr(html_escape($institute->getName()), 0, 32); //preg_replace('/\s+?(\S+)?$/', '',html_escape($institute->getName()));
			$instStr .= "...";
		}else{
			$instStr = html_escape($institute->getName());
		}
	?>
			
		 <td style="padding-top:13px">
		    <div class="cmpre-head">
		       <a class="close-sec" href="javascript:;"><i class="cmpre-sprite ic-cls" onclick="removeCollege('<?=$j?>','COMPARE_DESK_REMOVE_STICKY');"></i></a>
		               <div class="cmpre-inst-title">
		                	<a href="<?=$course->getURL()?>" title="<?php echo htmlspecialchars($institute->getName())?>"><?php echo $instStr?></a>
		                    <p class="loc-of-clg"><i class="cmpre-sprite ic-gloc"></i><?php echo $course->getCurrentMainLocation()->getCity()->getName()?></p>
		                </div>
		     </div>
		 </td>
	<?php 
	}
	$allowAutoSuggestForSticky = true;
    if($j<4){
    	while ($j < 4){
    		$j++;
    		?>
    		<td style="padding-top:13px;"<?php if($allowAutoSuggestForSticky){?>id="newInstituteSectionSticky"<?php }?>>
			<div class="cmpre-head">
				<div class="add-simlar-clgs addSimilarCollegeSticky" id="addSimilarCollegeSticky<?=$j?>">
				<div id="keywordSuggest_stickyDiv">
					<input type="text" class="find-txtfield <?php echo !$allowAutoSuggestForSticky?'diable-click':''?>" name="add-college" placeholder="Find College" id="keywordSuggest1" onfocus="toggleBoxText(this, 'focus'); this.hasFocus=true; checkTextElementOnTransition(this,'focus');" onblur="toggleBoxText(this, 'blur'); this.hasFocus=false; checkTextElementOnTransition(this,'blur');"/>
				</div>
				<span id="<?php echo $allowAutoSuggestForSticky?'searchIconInBoxSticky':''?>">
               		<i class="cmpre-sprite ic-srch opacity"></i>
              	</span>
				<div class="cmpre-sugstr-box" id="suggestions_container_stickyDiv">
					<ul id="suggestions_container1"></ul>
				</div>
				<?php 
				if(isset($institutesRecommended) && count($institutesRecommended)>0 ){
					if($allowAutoSuggestForSticky){
                  		$classButton = "new-gray-btn";
            		}
                	else{
                  		$classButton = "new-dis-btn";
                	}
				?>
					<p class="or-txt">Or</p>
					<a href="javascript:;" onclick="<?php if($allowAutoSuggestForSticky){?>showCompareRecommendation('<?php echo $j; ?>', 'sticky');<?php }?>" sticky="yes" class="<?php echo $classButton;?>">Add Similar Colleges</a>
				<?php 
					//$this->load->view('receommendations',array('keyVal'=>$j));
				}
				?>
					<div id="addSimilarCollegeStickyDiv<?=$j?>"></div>
				</div>
			</div>
			</td>
    		<?php 
    		$allowAutoSuggestForSticky = false;
    	}
    }
	?>
	</tr>
	</tbody>
	</table>
</div>