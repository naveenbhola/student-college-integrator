<?php
$product = 'browseSeo';
$headerComponents = array(
	'js' => array(),
	'css'=>array('common','category-styles','browse_seo'),                                                
	'jsFooter' =>array('common','lazyload'),
	'product'=> $product,
	'taburl' =>  site_url(),
	'bannerProperties' => array(
				'pageId'=>'CATEGORY',
				'pageZone'=>'TOP',
				'shikshaCriteria' => $criteriaArray
				),
	'title'	=>	'Study in India-Study Abroad',
	'searchEnable' => true,
	'canonicalURL' => $canonicalurl,
	'metaDescription' => 'Study in India-Study Abroad',
	'metaKeywords'	=> ''
	);
$this->load->view('common/header', $headerComponents);
?>
    <!--Browse HTML STARTS HERE-->
    <div id="browse-contents">
    	<div id="browse-left-col">
        	<div class="box-shadow" style="padding-bottom:13px;">
            	<div class="contents2" id="left_browse">
                	<h2>
                    	<span class="study-ind"></span>
                        <strong>Study in India</strong>
                    </h2>
                    <ul>
                        <?php $k=0;foreach($categories as $category):?>
                    	<li <?php if($k % 2 !=0) {echo "class='alt-row'";}?>><a href="/categoryList/Browse/page2/<?php echo $category->getId().'/'.$category->getName().'/India'?>"><?php echo $category->getName();?></a></li>
                        <?php $k++;endforeach;?>
                    </ul>
                    <div class="clearFix"></div>
                </div>
            </div>
        </div>
        <?php
			global $configData;
			$bachelors	    	= $configData['dataForHeaderFooter']['bachelors'];
			$masters            = $configData['dataForHeaderFooter']['masters'];
			$countryInformation = $configData['dataForHeaderFooter']['countryInformation'];
		?>
        <div id="browse-right-col">
        	<div class="box-shadow" style="padding-bottom:13px;">
            	<div class="contents2" id="right_browse">
                	<h2>
                    	<span class="study-abr"></span>
                        <strong>Study Abroad</strong>
                    </h2>
                    
					<ul>
						<li style="color:#666">Explore By Course</li>
						<?php
						$k = 0;
						foreach($masters['browseInsByCourse'] as $value){ ?>
							<li <?php if($k % 2 ==0) {echo "class='alt-row'";}?>><a href="<?php echo $value['url']?>"><?php echo $value['title']?></a></li>
						<?php $k++; } ?>
						
						<?php foreach($bachelors['browseInsByCourse'] as $value) { ?>
							<li <?php if($k % 2 ==0) {echo "class='alt-row'";}?>><a href="<?php echo $value['url']?>"><?php echo $value['title']?></a></li>
						<?php $k++; } ?>
						
					</ul>
					
					<ul style="margin-top:20px;">
						<li style="color:#666">Explore by Country</li>
						<?php
						$k = 0;
						foreach($countryInformation['studyDestinations'] as $key=>$value){ ?>
							<li <?php if($k % 2 ==0) {echo "class='alt-row'";}?>><a href="<?php echo $value['url']?>"><?php echo $value['title']?></a></li>
						<?php $k++; } ?>
					</ul>
                    
					<div class="clearFix"></div>
                </div>
            </div>
        </div>
        <div class="clearFix"></div>
    </div>
    <!--Browse HTML ENDS HERE-->
<div class="spacer20 clearFix"></div>	
<?php 
$this->load->view('common/footerNew');
?>
<script>
$j(document).ready(function($) {
$('#right_browse').height($('#left_browse').height());
});
</script>
