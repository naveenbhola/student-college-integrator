<?php
$this->load->view('/mcommon/header');
$url_params = $dataobject->getUrlParams();
$location_facets = $dataobject->getLocationFacets();
$name_array = array();
foreach ($location_facets as $key=>$value) {
	$name_array[$key] = $value['name'];
	foreach ($value['cities'] as $key1=>$value1) {
		$name_array[$key1] = $value1['name'];
	}
}
?>
<div id="head-sep"></div>
<div id="head-title">
	<h4>Search Institute & Courses</h4>
	<span>&nbsp;</span>
</div>

<div id="content-wrap">

	<div class="refine-head">
	    <?php if($url_params['city_id'] >0 || $url_params['country_id']>0):
	          if($url_params['city_id'] >0) {
	          	$name = $name_array[$url_params['city_id']];
	          } else {
	          	$name = $name_array[$url_params['country_id']];
	          }
	    	  $val_text = "Change Location";	
	    ?>
		<span class="flLt" ><?php echo $name;?></span> 
		<?php else:
			  $val_text = "all locations";
		?>
		<span class="flLt" >Location</span>
		<?php endif;?>
		<span class="flRt">
		   <form action="/search/refineSearchLocation" method="post">
		   <input type="hidden" name="serialize_object" value="<?php echo base64_encode(gzcompress(serialize($dataobject)));?>" />
			<input type="submit" class="view-more right-arr" value="<?php echo $val_text?>"/>
		   </form>
		</span>
	</div>

	<div class="refine-content">
	<?php if(count($dataobject->getCoursetypeFacets())):?>
		<p>Mode of Learning</p>
		<ul>
		<?php foreach($dataobject->getCoursetypeFacets() as $course_type_facet):?>
		    <?php if($course_type_facet['count']>0):?>
			<li><a href="<?php echo $course_type_facet['url'];?>"><?php echo $course_type_facet['name'].' ('.$course_type_facet['count'].')';?></a></li>
			<?php else:?>
			<li><a href="<?php echo $course_type_facet['url'];?>"><?php echo $course_type_facet['name'];?></a></li>
			<?php endif;?>
			<?php endforeach;?>
		</ul>
		<div class="spacer15 clearFix"></div>
		<?php endif;?>
		<?php if(count($dataobject->getCourselevelFacets())):?>
		<p>Course Level</p>
		<ul>
		<?php foreach($dataobject->getCourselevelFacets() as $course_level_facet):?>
		    <?php if($course_level_facet['count']>0):?>
			<li><a href="<?php echo $course_level_facet['url'];?>"><?php echo $course_level_facet['name'].' ('.$course_level_facet['count'].')';?></a></li>
			<?php else:?>
			<li><a href="<?php echo $course_level_facet['url'];?>"><?php echo $course_level_facet['name'];?></a></li>
			<?php endif;?>
			<?php endforeach;?>
		</ul>
       <?php endif;?>
		<div class="spacer15 clearFix"></div>
		<a href="/search/showSearchHome" class="gray-button2 gray-btn-style" >Reset/New Search</a>
		<div class="spacer8 clearFix"></div>
		<a href="<?php echo $referral_url;?>" class="gray-button2 gray-btn-style" >Cancel</a>
	</div>
<?php $this->load->view('msearch/common/topsearches')?>	
<?php $this->load->view('/mcommon/footer');?>
