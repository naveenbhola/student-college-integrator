<div data-enhance="false">
	<div class="head-group" data-enhance="false">
	    <?php 
	    if($tab=='overview'  || $tab=='courseTabs'){
		$name = displayTextAsPerMobileResolution(html_escape($institute->getName()),2,true).',' ;
		$titleName = html_escape($institute->getName());
	    }
	    else{
		$name = displayTextAsPerMobileResolution(html_escape($course->getName()),2,true);
		$titleName = html_escape($course->getName());
	   }
	   if($tab=='overview' || $tab=='courseTabs'){
		$location =  (($currentLocation->getLocality()&&$currentLocation->getLocality()->getName())?$currentLocation->getLocality()->getName().", ":"");
		$locationCity =  $currentLocation->getCity()->getName() ;
	   }
	    ?>
	    <h1 itemprop="name" title="<?php echo strip_tags($titleName).' '.strip_tags($location).strip_tags($locationCity);?>" >
		<div class="left-align" style="margin-right:98px;margin-left: 12px;">
			<?php echo $name;?>
		<p>
			<?php echo $location.$locationCity;?>
		 </p>
		</div>
	       <?php //if($tab=='courses'){ ?><!---span class="badge" --></span---> <?php //this will be used for notification }?>
	    </h1>
	    <?php
	    if(isset($_COOKIE['current_cat_page']) && $_COOKIE['current_cat_page']!=''){
		$url = urldecode($_COOKIE['current_cat_page']);
	    }
	    else{
		$url = SHIKSHA_HOME;
	    }
	    ?>
	    <!---------------mylists----------------->
	
	    <?php //$this->load->view('/mcommon5/mobileMyList');?>
	    
	    <!------------end-mylists---------------->
	    <span class="head-icon-r" onClick="window.location='<?=$url?>';" style="cursor: pointer;"><i class="icon-home"></i></span>
	</div>
</div>