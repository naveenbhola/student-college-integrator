<?php
	$displayflag = '';
	if($request->getCategoryId() != 1){
		$displayflag = 'none';
	}
	
	if($displayflag == '')
	{
		$mouseover = '';
		$mouseout = '';
		$js = "$('dim_bg').style.display = 'inline'; $('dim_bg').style.height = '8000px';var divX = parseInt(screen.width/2)-200;var divY = parseInt(screen.height/2)-200; $('choosecatgory').style.left = (divX) +  'px';$('choosecatgory').style.top = (divY) + 'px';";
		// $js = "$('dim_bg').style.display = 'inline'; $('dim_bg').style.height = '5000px';";
	}
	else
	{
		$mouseover = "this.style.display=''; overlayHackLayerForIE('choosecatgory', document.getElementById('choosecatgory'));" ;
		$mouseout = "dissolveOverlayHackForIE();this.style.display='none'";
	}
	$categories = $categoryRepository->getSubCategories(1,'');
	$height['category'] = 10;
	$height['subcategory'] = 10;
	
?>
    <?php
	if($request->getCategoryId() == 1){
	?>
	<div id = 'choosecatgory' style = 'display:<?php echo $displayflag;?>;z-index:1000000;position:absolute;width:400px;' onmouseover="<?php echo $mouseover;?>" onmouseout="<?php echo $mouseout;?>">
	<div class="blkRound">
		<div class="bluRound">
                <div class="layer-title"><h4>Please choose your Field of Interest</h4></div>
        </div>
		<div class="whtRound"><ul>
			<?php
			foreach($categories as $row){
				if(in_array($row->getId(),array(14,11))){
					continue;
				}
				$urlRequest = clone $request;
				$urlRequest->setData(array('categoryId'=>$row->getId(),'subCategoryId'=>1,'LDBCourseId'=>1));
				$url = $urlRequest->getURL();
			?>
			<li style="width: 100%;margin-top:5px">
				<b style="background: url(/public/images/shikIcons.png) no-repeat scroll left -674px transparent; font-weight:normal;">
				<span style="color:#0065DC;font-size:12px;padding-left:10px;">
					<a href="<?=$url?>"><?=$row->getName()?></a>
				</span>
				</b>
			</li>
			<?php
			}
			?>
		</ul></div>
	</div>
	</div>
	<?php
	}else{
		$categories = $categoryRepository->getSubCategories(1,'');
		$subCategories = $categoryRepository->getSubCategories($request->getCategoryId(),'studyabroad');
		$urlRequest = clone $request;
		foreach($subCategories as $row){
			if(!in_array($row->getId(),$dynamicCategoryList)){
				continue;
			}
			$height['subcategory'] += 20;
			$urlRequest->setData(array('subCategoryId'=>$row->getId(),'LDBCourseId'=>1));
			$style = '';
			
			if($request->getSubCategoryId() == $row->getId()){
				$subCatHTML1 .= '<b class="activeLink1">'.$row->getName().'</b><br/>';
			}else{
				$subCatHTML .= '<a href="'.$urlRequest->getURL().'">'.$row->getName().'</a><br/>';
			}
		}
		$urlRequest = clone $request;
		foreach($categories as $row){
			if(in_array($row->getId(),array(14,11))){
				continue;
			}
			$height['category'] += 20;
			$urlRequest->setData(array('categoryId'=>$row->getId(),'subCategoryId'=>1,'LDBCourseId'=>1));
			$style = '';
			if($request->getCategoryId() != $row->getId()){
				$catHTML .= '<a '.$style.' href="'.$urlRequest->getURL().'">'.$row->getName().'</a><br/>';
			}
		}
		if($request->getSubCategoryId() > 1) {
			$divWidth = "670px";
		}
		$layerHeight = min(max($height['category'],$height['subcategory']),220);
	?>
	<div id = 'choosecatgory' style = 'display:none;background:#fff;border:1px solid #aaa;width:<?=$divWidth?>;z-index:1000000;position:absolute;' onmouseover="<?php echo $mouseover;?>" onmouseout="<?php echo $mouseout;?>">
	<?php
		if($request->getSubCategoryId() > 1) {
			$height['subcategory'] += 20;
			$layerHeight = min(max($height['category'],$height['subcategory']),220);
	?>
	<div class="float_L">
			<div style="padding:10px">
					<h2 style="color:black">
						<?=$categoryPage->getCategory()->getName()?>
					</h2>
					<div style="line-height:20px;padding-left:10px">
						<div style="height:<?=($layerHeight-20)?>px;overflow-y:auto;width:300px;font-weight:normal" id = "cityli1">
							<?php
								echo $subCatHTML1.$subCatHTML;
							?>
						</div>
					</div>
			</div>
	</div>
	<?php
	}
	?>
	<div class="float_L">
		<div style="padding:10px">
				<?php
					if($request->getSubCategoryId() > 1) {
				?>
				<h2 style="color:black">
						Other Courses
				</h2>
				<?php
					}
				?>
				<div style="line-height:20px;padding-left:10px">
					<div style="height:<?=($layerHeight-20)?>px;overflow-y:auto;width:300px;font-weight:normal" id = "cityli1">
						<?php
							echo $catHTML;
						?>
					</div>
				</div>
		</div>
	</div>
	</div>
<style>
/*#choosecatgory{height:<?=$layerHeight?>px !important}*/
</style>
<?php
	}
?>
<script>
	function showChangeCategorySA(elementDiv){
		var objElementTop = $j(elementDiv).offset().top;
		var objElementLeft = $j(elementDiv).offset().left;
		$('choosecatgory').style.left = (objElementLeft-25)+"px";
		$('choosecatgory').style.top = (objElementTop+15)+"px";
		$('choosecatgory').style.display='';
		overlayHackLayerForIE('choosecatgory', document.getElementById('choosecatgory'));
	}
	<?=$js?>
</script>
