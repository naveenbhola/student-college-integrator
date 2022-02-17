<div class="src-cont" id ="sortLayer" data-role="page" data-enhance="false">
<div class="src-head">
<p class="src-title">Sort by</p>
<a href="#sortLayer" class="src-rmv" data-transition="slide" data-rel="back"><i class="search-sprite src-crss"></i></a>
</div>
<div class="sort-byList">
<ul class="a-ul">
<li>
<div class="e-col">
<input type="radio" id="pop" name="sort-opt" value="populairity_desc" checked="checked" data-enhance="false">
<label for="pop"> Popularity</label>
</div>  
</li>
<li>
<div class="e-col">
<input type="radio" id="lth1" name="sort-opt" value="fees_asc" <?php if($sortParam=='fees_asc'){ echo 'checked="checked"';}?> data-enhance="false">
<label for="lth1"> Low to high 1st year total fees</label>
</div>  
</li>
<li>
<div class="e-col">
<input type="radio" id="htl1" name="sort-opt" value="fees_desc" <?php if($sortParam=='fees_desc'){ echo 'checked="checked"';}?> data-enhance="false">
<label for="htl1"> High to low 1st year total fees</label>
</div>  
</li><?php
if(isset($pageData['appliedFilter']['exams']) && $pageData['appliedFilter']['exams']!=''){
$sorterExamName = $pageData['appliedFilter']['exams'][0];
}else{
$sorterExam = reset($filters['exam']);  
if(isset($sorterExam) &&  count($sorterExam)>0){
$sorterExamName = $sorterExam['name'];
}
}
if($sorterExamName!=''){
$sorterASCValue = 'exam_asc_'.strtolower($sorterExamName);
$sorterDESCValue = 'exam_desc_'.strtolower($sorterExamName);
?><li>
<div class="e-col">
<input type="radio" id="lthg" name="sort-opt" value="<?php echo $sorterASCValue;?>" <?php if($sortParam==$sorterASCValue){ echo 'checked="checked"';}?> data-enhance="false">
<label for="lthg"> Low to high <?php echo $sorterExamName;?> exam score</label>
</div>  
</li>
<li>
<div class="e-col">
<input type="radio" id="htlg" name="sort-opt" value="<?php echo $sorterDESCValue;?>" <?php if($sortParam==$sorterDESCValue){ echo 'checked="checked"';}?> data-enhance="false">
<label for="htlg"> High to low <?php echo $sorterExamName;?> exam score</label>
</div>  
</li>
<?php } ?>
</ul>
</div>
</div>