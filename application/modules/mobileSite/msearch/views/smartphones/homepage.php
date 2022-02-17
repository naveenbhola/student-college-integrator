<?php
$this->load->view('/mcommon/header');
?>
<div id="head-sep"></div>
<div id="head-title">
	<h4 style="padding: 5px 0">Search Institute & Courses</h4>
	<span>&nbsp;</span>
</div>
<div id="content-wrap">
	<div id="login-cont">
	<?php
	
	$hidden = array(
		'start'=>0,
		'institute_rows'=>-1,
		'content_rows'=>0,
		'country_id'=>'',
		'zone_id'=>'',
		'locality_id'=>'',
		'search_type'=>'institute',
		'search_data_type'=>'institute',
		'sort_type'=>'',
		'utm_campaign'=>'',
		'utm_medium'=>'',
		'utm_source'=>'',
		'from_page'=>'mobilesearchhome',
		'show_sponsored_results'=>1	
	);
	
	$attributes = array(
		"autocomplete" => "off", 
		'accept-charset' => 'utf-8',
		'method'=>'get'
	);
	
	?>
	
	<?=form_open('search/index',$attributes,$hidden)?>
		<ul>
			<li><?php 
			$attributes = array( 'name'=> 'keyword','type'=>"search",'value'=>$posted_keyword,'class'=>"login-field",'minlength'=>"1");
			?> 
			<label>
			<?=form_label('Enter Institute or Course Name', 'keyword')?>
			</label>
				<div class="field-cont">
				<?=form_input($attributes)?>
					<div style="color: red; font-size: 13px;">
					<?php echo $error_msg; ?>
					</div>
				</div>
			</li>
			
			<!--li>
			<label><?=form_label('Location', 'city_id')?> </label>
			<?php echo form_dropdown('city_id', $locations , array($posted_location),"class='select-field'");?>
			<div class="clearFix"></div>
			<a style="font-size:80%; margin-left:2px" href="/msearch/Msearch/showMoreCities">More Cities</a>
			</li-->
			
			<!--li>
			<label><?=form_label('Mode of Learning', 'course_type')?> </label>
			<?php echo form_dropdown('course_type', $course_type , array($posted_course_type),"class='select-field'");?>
			</li-->
			
			<!--li>
			<label><?=form_label('Course Level', 'course_level')?> </label>
			<?php echo form_dropdown('course_level', $course_level , array($posted_course_level),"class='select-field'");?>
			</li-->
			
			<?php $attributes = array( 'name'=> 'search','value'=>'Search','class' => 'orange-button');?>
			<li style="padding-top: 5px"><?=form_submit($attributes)?>
			</li>

		</ul>
		<div class="clearFix"></div>
	</div>
	<?php $this->load->view('msearch/common/topsearches')?>	
	<?php $this->load->view('/mcommon/footer');?>
