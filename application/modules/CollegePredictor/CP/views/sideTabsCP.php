<?php
$examNameNew = strtoupper($examName);
$baseUrl = $this->settingArray['CPEXAMS'][strtoupper($examName)]['directoryName'];
?>
<div id="predictor-tab"  <?php if(!$defaultView):?> style="display: none;" <?php endif;?> >
<ul>
   
 <li id="tab-1" <?php if($tab=='1'){ echo 'class="active"'; } ?> >
<a href="javascript:void(0);"  onclick="setCookie('collegepredictor_search_desktop_<?php echo $examinationName;?>','', 0,'seconds'); location.href= '<?=$baseUrl."/".$examName?>-college-predictor'">
<i class="predictor-sprite rank-icon"></i>
<h2 class="tab-title">Find College and <br />Branch</h2>
</a>
 </li>
 
 <li id="tab-2" <?php if($tab=='2'){ echo 'class="active"'; } ?> >
<a  href="javascript:void(0);"  onclick="setCookie('collegepredictor_search_desktop_<?php echo $examinationName;?>','', 0,'seconds'); location.href= '<?=$baseUrl."/".$examName?>-cut-off-predictor'">
<i class="predictor-sprite cutoff-icon"></i>
<h2 class="tab-title"> Know College Cut-offs</h2>
</a>
 </li>
  <li id="tab-3" <?php if($tab=='3'){ echo 'class="active"'; } ?> style="border-bottom:0">
<a  href="javascript:void(0);"  onclick="setCookie('collegepredictor_search_desktop_<?php echo $examinationName;?>','', 0,'seconds'); location.href= '<?=$baseUrl."/".$examName?>-branch-predictor'">
<i class="predictor-sprite branch-icon"></i>
<h2 class="tab-title">Find College<br />for a Branch</h2>
</a>
  </li>
</ul>
</div>


<script>
tabId = '<?php echo $tab;?>';
if(tabId != 'undefined'){
	if(tabId == 1){
		$("tab-2").style.borderBottom="2px solid #fff";
	}
	if(tabId == 3)
		$("tab-1").style.borderBottom="2px solid #fff";
		
	if(tabId != 1){
		$("tab-2").style.borderBottom="";
	}
}
</script>
