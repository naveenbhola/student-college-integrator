<?php

		$headerComponents = array(
						'css' =>array('common','category-styles','browse_seo','common_new'),
                                                'jsFooter'=>    array('common'),
						'title'	=>	'careers-after-12th-list',
						'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
					);
		$this->load->view('common/header', $headerComponents);
	
?>

    <div id="browse-contents">
    	<div id="browse-left-col">
        	<div class="box-shadow">
            	<div class="contents2" id="left_browse">
                    <ul>
		    
                        <?php $k=0; $flag=true; foreach($arrayOfCareersSeoUrl as $key=>$careerUrl){ if($k< count($arrayOfCareersSeoUrl)/2) { ?>
                    	<li <?php if($flag==false) {echo "class='alt-row'";}?>><a href="<?php echo $careerUrl['url']; ?>"><?php echo $careerUrl['name']; ?></a></li>
                        <?php $k++; $flag==false?$flag=true:$flag=false;} } ?>
                    </ul>
                    <div class="clearFix"></div>
                </div>
            </div>
        </div>
        
        <div id="browse-right-col">
        	<div class="box-shadow">
            	<div class="contents2" id="right_browse">
                    <ul>
                    	 <?php $k=0; $flag=true; foreach($arrayOfCareersSeoUrl as $key=>$careerUrl){ if($k >= count($arrayOfCareersSeoUrl)/2) {?>
                  	<li <?php if($flag==false) {echo "class='alt-row'";}?>><a href="<?php echo $careerUrl['url']; ?>"><?php echo $careerUrl['name']; ?></a></li>
                        <?php $flag==false?$flag=true:$flag=false; } $k++; } ?>
                    </ul>
                    <div class="clearFix"></div>
                </div>
            </div>
        </div>
 <div class="clearFix spacer20"></div>
		<div class="pagingID lineSpace_28" ><?php echo $paginationHTML; ?></div>
		<div class="lineSpace_28">&nbsp;</div>
	 <div class="clearFix"></div>
	 
    </div>


 <div class="clearFix"></div>

<?php
	$this->load->view('common/footer'); 
?>
