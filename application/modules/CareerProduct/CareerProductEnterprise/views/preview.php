<div class="spacer20 clearFix"></div>
<div class="spacer10 clearFix"></div>
<div id="preview"><div class="path-cont">
	<?php foreach($careerPathInformation as $key=>$value):?>
	<?php if($pathId == $value['pathId']):?>
	<?php $number = !empty($displayPathId)? $displayPathId:$pathId;?>
		<label>Path <?php echo $number;?>:</label>
		<div style="margin-left:65px">
		<div class="path-steps">
		    	<ul>
				<?php
				$j=1;
				$totalPathSteps = count($value['stepDetails']);
				foreach($value['stepDetails'] as $k=>$v):?>
				    <li <?php if($j==$totalPathSteps){ ?> class="last"<?php } ?>>
				    	<h5><?php echo truncate($v['stepTitle'],'15','...','true');?></h5>
				    	<p><?php echo truncate($v['stepDescription'],'30','...','true');?></p>
					<?php //if($j==$totalPathSteps && !empty($pathImage)){ ?> 					
					<!-- <div class="course-pic"><img src="< ?php echo $pathImage;?>"></div> -->
					<?php //} ?>
				    </li>
				<?php $j++;endforeach;?>
			</ul>
		</div>
		</div>
	<?php endif;?>
	<?php endforeach;?>
    
        
</div>
<div class="spacer10 clearFix"></div>
<input type="button" value="Save" class="orange-button" onClick="careerObj.savePathPreviewInformation('<?php echo $pathId;?>','<?php echo $careerId;?>');"/></div>
<div class="spacer10 clearFix"></div>
<div><div id="preview<?php echo $pathId;?>_error" class="errorMsg" style="display:none;">&nbsp;</div></div>
</div>

<div class="clearFix spacer20"></div>
