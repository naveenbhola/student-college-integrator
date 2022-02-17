<?php
	global $pageName;
	$countryNameSelected = str_replace(" ","",strtolower($pageName));
?>
	<div class="abroad-title-cont">
        <!--Start_CategoryPage_Title-->
		<h1>Study in <?php echo $pageName; ?></h1>
        <span class = "shik_allFlagSpirit1 f<?php echo str_replace(' ','',$countryNameSelected);?>" style="height:32px;padding:0 0 0px 48px; display:inline-block; margin-top:0">&nbsp;	</span>
        <span class="Fnt12 fcGya" style="vertical-align:middle">
        	<b>[</b> <a href="#" onClick = "showLocationLayerSA(this); return false;">Change Location <span class="orangeColor">&#9660;</span></a> <b>]</b>
            <?php $this->load->view('categoryList/locationlayerSA');?> 
        </span>                                        
        <!--End_CategoryPage_Title-->
	</div>