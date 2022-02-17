<style>
.examsectionheading{width: 546px !important;}		
</style>
<?php
$examSectionArray = array();
foreach($content['contentSection_info'] as $secContent)
{
     $examSectionArray[floor($secContent['indexes'])][$secContent['indexes']] = $secContent;
}
?>
<div id="moresectiontemplate" style="display: none;">
<ul class="moreSection">
	<li class="examPage">
		<label>Section #sectionNo#.<span class="secheadingcnt">#count#</span> Heading* </label>
		<div class="cms-fields">
			<input class="universal-txt-field cms-text-field examsectionheading" name="exam-heading[]" caption="heading" id="exam-heading-#sectionName##secID#" onblur="showErrorMessage(this);" validationType="html" required="true" />
			<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-heading-#sectionName##secID#_error"></div>
		</div>
	</li>
	<li class="examPage">
		<label>Section #sectionNo#.<span class="secheadingcnt">#count#</span> Details* </label>
		<div class="cms-fields">
			<textarea class="cms-textarea checkIfEmpty" name="exam-detail[]" caption="details" id="exam-detail-#sectionName##secID#" onblur="showErrorMessage(this);" validationType="html" required="true"></textarea>
			<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-detail-#sectionName##secID#_error"></div>
		</div>
	</li>
	<li>
	<input type="hidden" name="sectionIndex[]" value="#sectionNo#.#count#" />		
	<a onclick="removeExamSection(this,'#sectionName#','#sectionNo#');" style="display: inline;" class="remove-link" href="JavaScript:void(0);"><i class="abroad-cms-sprite remove-icon"></i>Remove Section</a>	
	</li>
</ul>
</div>


<div class="clear-width examPage">
	<h3 class="section-title examPage" style="">About <span class="examName">Exam</span></h3>
	<div class="cms-form-wrap" id="examabout">
		<ul>
			<li style="display: none">
				<textarea class="" name="exam-heading[]" caption="heading" id="exam-heading0" required="true">about section</textarea>
			</li>
			<li class="examPage">
				<label>Overview* </label>
				<div class="cms-fields">
					<textarea class="cms-textarea tinymce-textarea draft checkIfEmpty" name="exam-detail[]" caption="overview detail" id="exam-detail-about0" onblur="showErrorMessage(this);" validationType="html" required="true"><?= $examSectionArray[1]['1.00']['details']; ?></textarea>
					<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-detail-about0_error"></div>
				</div>
			<input type="hidden" name="sectionIndex[]" value="1.0" />		
			</li>
		</ul>
		<?php
		$count =0;
		if(count($examSectionArray[1]) >1){
			foreach($examSectionArray[1] as $firstSection)
			{
			     if($count !=0){
			?>
			<ul class="moreSection">
				<li class="examPage">
					<label>Section 1.<span class="secheadingcnt"><?= $count?></span> Heading* </label>
					<div class="cms-fields">
						<input class="universal-txt-field cms-text-field examsectionheading" name="exam-heading[]" caption="heading" id="exam-heading-about<?= $count?>" onblur="showErrorMessage(this);" validationType="html" required="true" value="<?= html_escape($firstSection['heading']);?>" />
						<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-heading-about<?= $count?>_error"></div>
					</div>
				</li>
				<li class="examPage">
					<label>Section 1.<span class="secheadingcnt"><?= $count?></span> Details* </label>
					<div class="cms-fields">
						<textarea class="cms-textarea tinymce-textarea draft checkIfEmpty" name="exam-detail[]" caption="details" id="exam-detail-about<?= $count?>" onblur="showErrorMessage(this);" validationType="html" required="true"><?= $firstSection['details'];?></textarea>
						<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-detail-about<?= $count?>_error"></div>
					</div>
				</li>
				<li>
				<input type="hidden" name="sectionIndex[]" value="1.<?= $count?>" />		
				<a onclick="removeExamSection(this,'about','1');" style="display: inline;" class="remove-link" href="JavaScript:void(0);"><i class="abroad-cms-sprite remove-icon"></i>Remove Section</a>	
				</li>
			</ul>	
			
		<?php 		}
		$count++;
		}}
		$addMoreLinkDisplay ='';
		if($count >5){
		$addMoreLinkDisplay = "display:none";	
		}
		?>
		
	<a class="add-more-link" style="<?= $addMoreLinkDisplay; ?>" href="JavaScript:void(0);" onclick = "addExamSection(this,1,'about');">[+] Add Another Section</a>
	
	</div>
</div>

<div class="clear-width examPage">
	<h3 class="section-title examPage" style="">Exam Pattern</h3>
	<div class="cms-form-wrap" id="exampattern">
		<ul>
			<li style="display: none">
				<textarea class="" name="exam-heading[]" caption="heading" id="exam-heading2" required="true">exam pattern</textarea>
			</li>
			<li class="examPage">
				<label>Overview* </label>
				<div class="cms-fields">
					<textarea class="cms-textarea tinymce-textarea draft checkIfEmpty" name="exam-detail[]" caption="overview detail" id="exam-detail-pattern0" onblur="showErrorMessage(this);" validationType="html" required="true"><?= $examSectionArray[2]['2.00']['details'];?></textarea>
					<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-detail-pattern0_error"></div>
				</div>
			<input type="hidden" name="sectionIndex[]" value="2.0" />	
			</li>
		</ul>
		<?php
		$count =0;
		if(count($examSectionArray[2]) >1){
			foreach($examSectionArray[2] as $firstSection)
			{
			     if($count !=0){
			?>
			<ul class="moreSection">
				<li class="examPage">
					<label>Section 2.<span class="secheadingcnt"><?= $count?></span> Heading* </label>
					<div class="cms-fields">
						<input class="universal-txt-field cms-text-field examsectionheading" name="exam-heading[]" caption="heading" id="exam-heading-pattern<?= $count?>" onblur="showErrorMessage(this);" validationType="html" required="true" value="<?= html_escape($firstSection['heading']);?>" />
						<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-heading-pattern<?= $count?>_error"></div>
					</div>
				</li>
				<li class="examPage">
					<label>Section 2.<span class="secheadingcnt"><?= $count?></span> Details* </label>
					<div class="cms-fields">
						<textarea class="cms-textarea tinymce-textarea draft checkIfEmpty" name="exam-detail[]" caption="details" id="exam-detail-pattern<?= $count?>" onblur="showErrorMessage(this);" validationType="html" required="true"><?= $firstSection['details'];?></textarea>
						<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-detail-pattern<?= $count?>_error"></div>
					</div>
				</li>
				<li>
				<input type="hidden" name="sectionIndex[]" value="2.<?= $count?>" />		
				<a onclick="removeExamSection(this,'pattern','2');" style="display: inline;" class="remove-link" href="JavaScript:void(0);"><i class="abroad-cms-sprite remove-icon"></i>Remove Section</a>	
				</li>
			</ul>	
			
		<?php 		}
		$count++;
		}}
		$addMoreLinkDisplay ='';
		if($count >5){
		$addMoreLinkDisplay = "display:none";	
		}
		?>
		<a class="add-more-link" style="<?= $addMoreLinkDisplay; ?>" href="JavaScript:void(0);" onclick = "addExamSection(this,2,'pattern');">[+] Add Another Section</a>
	</div>
</div>


<div class="clear-width examPage">
	<h3 class="section-title examPage" style="">Scoring In <span class="examName">Exam</span></h3>
	<div class="cms-form-wrap" id="examscoring">
		<ul>
			<li style="display:none;">
				<textarea class="" name="exam-heading[]" caption="heading" id="exam-heading3>" required="true">scoring section</textarea>
			</li>
			<li class="examPage">
				<label>Overview* </label>
				<div class="cms-fields">
					<textarea class="cms-textarea tinymce-textarea draft checkIfEmpty" name="exam-detail[]" caption="overview detail" id="exam-detail-scoring0" onblur="showErrorMessage(this);" validationType="html" required="true"><?= $examSectionArray[3]['3.00']['details'];?></textarea>
					<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-detail-scoring0_error"></div>
				</div>
			<input type="hidden" name="sectionIndex[]" value="3.0" />	
			</li>
		</ul>
		<?php
		$count =0;
		if(count($examSectionArray[3]) >1){
			foreach($examSectionArray[3] as $firstSection)
			{
			     if($count !=0){
			?>
			<ul class="moreSection">
				<li class="examPage">
					<label>Section 3.<span class="secheadingcnt"><?= $count?></span> Heading* </label>
					<div class="cms-fields">
						<input class="universal-txt-field cms-text-field examsectionheading" name="exam-heading[]" caption="heading" id="exam-heading-scoring<?= $count?>" onblur="showErrorMessage(this);" validationType="html" required="true" value="<?= html_escape($firstSection['heading']);?>" />
						<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-heading-scoring<?= $count?>_error"></div>
					</div>
				</li>
				<li class="examPage">
					<label>Section 3.<span class="secheadingcnt"><?= $count?></span> Details* </label>
					<div class="cms-fields">
						<textarea class="cms-textarea tinymce-textarea draft checkIfEmpty" name="exam-detail[]" caption="details" id="exam-detail-scoring<?= $count?>" onblur="showErrorMessage(this);" validationType="html" required="true"><?= $firstSection['details'];?></textarea>
						<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-detail-scoring<?= $count?>_error"></div>
					</div>
				</li>
				<li>
				<input type="hidden" name="sectionIndex[]" value="3.<?= $count?>" />		
				<a onclick="removeExamSection(this,'scoring','3');" style="display: inline;" class="remove-link" href="JavaScript:void(0);"><i class="abroad-cms-sprite remove-icon"></i>Remove Section</a>	
				</li>
			</ul>	
			
		<?php 		}
		$count++;
		}}
		$addMoreLinkDisplay ='';
		if($count >5){
		$addMoreLinkDisplay = "display:none";	
		}
		?>
		<a class="add-more-link" style="<?= $addMoreLinkDisplay; ?>" href="JavaScript:void(0);" onclick = "addExamSection(this,3,'scoring');">[+] Add Another Section</a>
	</div>
</div>

<div class="clear-width examPage">
	<h3 class="section-title examPage" style="">Important Dates</h3>
	<div class="cms-form-wrap" id="examdates">
		<ul>
			<li style="display: none">
				<textarea class="" name="exam-heading[]" caption="heading" id="exam-heading4 required="true">important dates</textarea>
			</li>
			<li class="examPage">
				<label>Overview* </label>
				<div class="cms-fields">
					<textarea class="cms-textarea tinymce-textarea draft checkIfEmpty" name="exam-detail[]" caption="overview detail" id="exam-detail-dates0" onblur="showErrorMessage(this);" validationType="html" required="true"><?= $examSectionArray[4]['4.00']['details'];?></textarea>
					<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-detail-dates0_error"></div>
				</div>
			<input type="hidden" name="sectionIndex[]" value="4.0" />	
			</li>
		</ul>
		<?php
		$count =0;
		if(count($examSectionArray[4]) >1){
			foreach($examSectionArray[4] as $firstSection)
			{
			     if($count !=0){
			?>
			<ul class="moreSection">
				<li class="examPage">
					<label>Section 4.<span class="secheadingcnt"><?= $count?></span> Heading* </label>
					<div class="cms-fields">
						<input class="universal-txt-field cms-text-field examsectionheading" name="exam-heading[]" caption="heading" id="exam-heading-dates<?= $count?>" onblur="showErrorMessage(this);" validationType="html" required="true" value="<?= html_escape($firstSection['heading']);?>" />
						<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-heading-dates<?= $count?>_error"></div>
					</div>
				</li>
				<li class="examPage">
					<label>Section 4.<span class="secheadingcnt"><?= $count?></span> Details* </label>
					<div class="cms-fields">
						<textarea class="cms-textarea tinymce-textarea draft checkIfEmpty" name="exam-detail[]" caption="details" id="exam-detail-dates<?= $count?>" onblur="showErrorMessage(this);" validationType="html" required="true"><?= $firstSection['details'];?></textarea>
						<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-detail-dates<?= $count?>_error"></div>
					</div>
				</li>
				<li>
				<input type="hidden" name="sectionIndex[]" value="4.<?= $count?>" />		
				<a onclick="removeExamSection(this,'dates','4');" style="display: inline;" class="remove-link" href="JavaScript:void(0);"><i class="abroad-cms-sprite remove-icon"></i>Remove Section</a>	
				</li>
			</ul>	
			
		<?php 		}
		$count++;
		}}
		$addMoreLinkDisplay ='';
		if($count >5){
		$addMoreLinkDisplay = "display:none";	
		}
		?>
		<a class="add-more-link" style="<?= $addMoreLinkDisplay; ?>" href="JavaScript:void(0);" onclick = "addExamSection(this,4,'dates');">[+] Add Another Section</a>
	</div>
</div>

<div class="clear-width examPage">
	<h3 class="section-title examPage" style="">Prepration Tips</h3>
	<div class="cms-form-wrap" id="examtips">
		<ul>
			<li style="display:none;">
				<textarea class="" name="exam-heading[]" caption="heading" id="exam-heading5" required="true">prepration tips</textarea>
			</li>
			<li class="examPage">
				<label>Overview* </label>
				<div class="cms-fields">
					<textarea class="cms-textarea tinymce-textarea draft checkIfEmpty" name="exam-detail[]" caption="overview detail" id="exam-detail-tips0" onblur="showErrorMessage(this);" validationType="html" required="true"><?= $examSectionArray[5]['5.00']['details'];?></textarea>
					<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-detail-tips0_error"></div>
				</div>
			<input type="hidden" name="sectionIndex[]" value="5.0" />	
			</li>
		</ul>
		<?php
		    $count =0;
		if(count($examSectionArray[5]) >1){
			foreach($examSectionArray[5] as $firstSection)
			{
			     if($count !=0){
			?>
			<ul class="moreSection">
				<li class="examPage">
					<label>Section 5.<span class="secheadingcnt"><?= $count?></span> Heading* </label>
					<div class="cms-fields">
						<input class="universal-txt-field cms-text-field examsectionheading" name="exam-heading[]" caption="heading" id="exam-heading-tips<?= $count?>" onblur="showErrorMessage(this);" validationType="html" required="true" value="<?= html_escape($firstSection['heading']);?>" />
						<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-heading-tips<?= $count?>_error"></div>
					</div>
				</li>
				<li class="examPage">
					<label>Section 5.<span class="secheadingcnt"><?= $count?></span> Details* </label>
					<div class="cms-fields">
						<textarea class="cms-textarea tinymce-textarea draft checkIfEmpty" name="exam-detail[]" caption="details" id="exam-detail-tips<?= $count?>" onblur="showErrorMessage(this);" validationType="html" required="true"><?= $firstSection['details'];?></textarea>
						<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-detail-tips<?= $count?>_error"></div>
					</div>
				</li>
				<li>
				<input type="hidden" name="sectionIndex[]" value="5.<?= $count?>" />		
				<a onclick="removeExamSection(this,'tips','5');" style="display: inline;" class="remove-link" href="JavaScript:void(0);"><i class="abroad-cms-sprite remove-icon"></i>Remove Section</a>	
				</li>
			</ul>	
			
		<?php 		}
		$count++;
		}}
		$addMoreLinkDisplay ='';
		if($count >5){
		$addMoreLinkDisplay = "display:none";	
		}
		?>
		<a class="add-more-link" style="<?= $addMoreLinkDisplay; ?>" href="JavaScript:void(0);" onclick = "addExamSection(this,5,'tips');">[+] Add Another Section</a>
	</div>
</div>

<div class="clear-width examPage">
	<h3 class="section-title examPage">Practice & Sample Paper</h3>
	<div class="cms-form-wrap" id="exampapers">
		<ul>
			<li style="display: none">
				<textarea class="" name="exam-heading[]" caption="heading" id="exam-heading6" required="true">practice and sample paper</textarea>
			</li>
			<li class="examPage">
				<label>Overview* </label>
				<div class="cms-fields">
					<textarea class="cms-textarea tinymce-textarea draft checkIfEmpty" name="exam-detail[]" caption="overview detail" id="exam-detail-paper0" onblur="showErrorMessage(this);" validationType="html" required="true"><?= $examSectionArray[6]['6.00']['details'];?></textarea>
					<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-detail-paper0_error"></div>
				</div>
			<input type="hidden" name="sectionIndex[]" value="6.0" />	
			</li>
		</ul>
		<?php
		  $count =0;
		if(count($examSectionArray[6]) >1){
			foreach($examSectionArray[6] as $firstSection)
			{
			     if($count !=0){
			?>
			<ul class="moreSection">
				<li class="examPage">
					<label>Section 6.<span class="secheadingcnt"><?= $count?></span> Heading* </label>
					<div class="cms-fields">
						<input class="universal-txt-field cms-text-field examsectionheading" name="exam-heading[]" caption="heading" id="exam-heading-paper<?= $count?>" onblur="showErrorMessage(this);" validationType="html" required="true" value="<?= html_escape($firstSection['heading']);?>" />
						<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-heading-paper<?= $count?>_error"></div>
					</div>
				</li>
				<li class="examPage">
					<label>Section 6.<span class="secheadingcnt"><?= $count?></span> Details* </label>
					<div class="cms-fields">
						<textarea class="cms-textarea tinymce-textarea draft checkIfEmpty" name="exam-detail[]" caption="details" id="exam-detail-paper<?= $count?>" onblur="showErrorMessage(this);" validationType="html" required="true"><?= $firstSection['details'];?></textarea>
						<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-detail-paper<?= $count?>_error"></div>
					</div>
				</li>
				<li>
				<input type="hidden" name="sectionIndex[]" value="6.<?= $count?>" />		
				<a onclick="removeExamSection(this,'papers','6');" style="display: inline;" class="remove-link" href="JavaScript:void(0);"><i class="abroad-cms-sprite remove-icon"></i>Remove Section</a>	
				</li>
			</ul>	
			
		<?php 		}
		$count++;
		}}
		$addMoreLinkDisplay ='';
		if($count >5){
		$addMoreLinkDisplay = "display:none";	
		}
		?>
		<a class="add-more-link" style="<?= $addMoreLinkDisplay; ?>" href="JavaScript:void(0);" onclick = "addExamSection(this,6,'papers');">[+] Add Another Section</a>
	</div>
</div>

<div class="clear-width examPage">
	<h3 class="section-title examPage"><span class="examName">Exam</span> Syllabus</h3>
	<div class="cms-form-wrap" id="examsyllabus">
		<ul>
			<li style="display: none">
				<textarea class="" name="exam-heading[]" caption="heading" id="exam-heading7" required="true">syllabus</textarea>
			</li>
			<li class="examPage">
				<label>Overview* </label>
				<div class="cms-fields">
					<textarea class="cms-textarea tinymce-textarea draft checkIfEmpty" name="exam-detail[]" caption="overview detail" id="exam-detail-syllabus0" onblur="showErrorMessage(this);" validationType="html" required="true"><?= $examSectionArray[7]['7.00']['details'];?></textarea>
					<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-detail-syllabus0_error"></div>
				</div>
			<input type="hidden" name="sectionIndex[]" value="7.0" />	
			</li>
		</ul>
		<?php
		  $count =0;
		if(count($examSectionArray[7]) >1){
			foreach($examSectionArray[7] as $firstSection)
			{
			     if($count !=0){
			?>
			<ul class="moreSection">
				<li class="examPage">
					<label>Section 7.<span class="secheadingcnt"><?= $count?></span> Heading* </label>
					<div class="cms-fields">
						<input class="universal-txt-field cms-text-field examsectionheading" name="exam-heading[]" caption="heading" id="exam-heading-syllabus<?= $count?>" onblur="showErrorMessage(this);" validationType="html" required="true" value="<?= html_escape($firstSection['heading']);?>" />
						<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-heading-syllabus<?= $count?>_error"></div>
					</div>
				</li>
				<li class="examPage">
					<label>Section 7.<span class="secheadingcnt"><?= $count?></span> Details* </label>
					<div class="cms-fields">
						<textarea class="cms-textarea tinymce-textarea draft checkIfEmpty" name="exam-detail[]" caption="details" id="exam-detail-syllabus<?= $count?>" onblur="showErrorMessage(this);" validationType="html" required="true"><?= $firstSection['details'];?></textarea>
						<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam-detail-syllabus<?= $count?>_error"></div>
					</div>
				</li>
				<li>
				<input type="hidden" name="sectionIndex[]" value="7.<?= $count?>" />		
				<a onclick="removeExamSection(this,'syllabus','7');" style="display: inline;" class="remove-link" href="JavaScript:void(0);"><i class="abroad-cms-sprite remove-icon"></i>Remove Section</a>	
				</li>
			</ul>	
			
		<?php 		}
		$count++;
		}}
		$addMoreLinkDisplay ='';
		if($count >5){
		$addMoreLinkDisplay = "display:none";	
		}
		?>
		<a class="add-more-link" style="<?= $addMoreLinkDisplay; ?>" href="JavaScript:void(0);" onclick = "addExamSection(this,7,'syllabus');">[+] Add Another Section</a>
	</div>
</div>