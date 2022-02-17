<?php
	$keyword = (!empty($solr_institute_data['raw_keyword']))  ? htmlspecialchars($solr_institute_data['raw_keyword']) : '';
	$title = 'Shiksha.com - Search Results – Education – College – University – Study Abroad – Scholarships – Education Events – Admissions - Notifications -'.htmlspecialchars($keyword);
	$metDescription = 'Search Shiksha.com for Colleges, University, Institutes, Foreign Education programs and information to study in India. Find course / program details, admissions, scholarships of universities of India and from countries all over the world -'.htmlspecialchars($keyword);
	$metaKeywords = 'Shiksha, Study, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships -'.htmlspecialchars($keyword);
	$headerComponents = array(
	'm_meta_title'=>$title,
	'm_meta_description'=>$metDescription,
	'm_meta_keywords'=>$metaKeywords
	);
	$this->load->view('/mcommon/header',$headerComponents);
?>
<div id="head-sep"></div>
<div class="inst-box" style="padding-bottom: 3px">
    <?php if($solr_institute_data['total_institute_groups']>0):?>
	<div class="search-title">
		Total <?php echo getPlural($total_results, 'institute');?> and <?php echo getPlural($solr_institute_data['numfound_course_documents'], 'course');?> for <span><?php echo '"'.html_escape($searchurlparams['keyword']).'"';?></span>
	</div>
	<?php endif;?>

	<div class="search-details-cont">
		<div class="refine-btn-col">
			<form method="post" action="<?php echo $refine_action;?>">
			<?php if($refine_action == '/search/refineSearch'):?>
			<input type="hidden" name="serialize_object" value="<?php echo base64_encode(gzcompress(serialize($search_lib_object)));?>"/>
			<?php else:?>
			<input type="hidden" name="keyword" value="<?php echo url_base64_encode($searchurlparams['keyword']);?>"/>
			<input type="hidden" name="city_id" value="<?php echo $searchurlparams['city_id'];?>"/>	 
			<input type="hidden" name="course_type" value="<?php echo $searchurlparams['course_type'];?>"/>
			<input type="hidden" name="course_level" value="<?php echo $searchurlparams['course_level'];?>"/>
			<?php endif;?>
			<input type="submit" class="orange-button" value="Refine" title="Search" style="font-size: 13px;" />
			</form>
		</div>

		<div class="search-details">
			<?php 
			if(!empty($searchurlparams['city_id']) || !empty($searchurlparams['country_id'])):
			$msearch_locations = json_decode(base64_decode($_COOKIE['msearch_locations']),true);
			$temp_location = $search_lib_object->getLocationFacets();
			foreach ($temp_location as $key=>$value) {
				$msearch_locations[$key] = $value['name'];
				foreach ($value['cities'] as $key1=>$value1) {
					$msearch_locations[$key1] = $value1['name'];
					
				}
			}
			$city_key = $searchurlparams['city_id']?$searchurlparams['city_id']:$searchurlparams['country_id'];
			?>
			<strong>Location: <label <?php echo $filters_picked_by_qer_data['city_id'];?>><?php echo $msearch_locations[$city_key];?></label> </strong>
			<?php endif;?>
			<?php if(!empty($searchurlparams['course_type'])):?> 
			<?php if(!empty($searchurlparams['city_id']) || !empty($searchurlparams['country_id'])) echo "<br/>";?>
			<strong>Mode of Learning: <label <?php echo $filters_picked_by_qer_data['course_type'];?>><?php echo msearchLib::$course_type_array[$searchurlparams['course_type']];?></label> </strong>
			<?php endif;?>
			<?php if(!empty($searchurlparams['course_level'])):?>
			<?php if(!empty($searchurlparams['course_type']) || (!empty($searchurlparams['city_id']) || !empty($searchurlparams['country_id']))) echo "<br/>";?> 
			<strong>Course Level: <label <?php echo $filters_picked_by_qer_data['course_level'];?>><?php echo msearchLib::$course_level_array[$searchurlparams['course_level']];?></label></strong>
			<?php endif;?>
		</div>
	</div>
	
	<div class="clearFix"></div>
	<span class="cloud-arr">&nbsp;</span>
</div>

<div id="content-wrap">
	<div id="contents">
		<?php if(empty($solr_institute_data['single_result']) && $solr_institute_data['total_institute_groups']>0):?>
		<div class="sorting">
			<?php if($searchurlparams['sort_type'] == "popular"):
				$searchurlparams['sort_type'] = "best";
			?>
			<strong>Sort by: </strong> <span><a href="<?php echo urldecode($search_lib_object->makeSearchURL($current_url,$searchurlparams));?>"> Best Match </a></span>| <span>Popular</span>
			<?php else :
			$searchurlparams['sort_type'] = "popular";
			?>
			<strong>Sort by: </strong> <span>Best Match</span>| <span><a href="<?php echo urldecode($search_lib_object->makeSearchURL($current_url,$searchurlparams));?>">Popular</a></span>
			<?php endif;?>
		</div>
		<?php endif;?>
		<?php if(empty($solr_institute_data['single_result'])): //Normal search results?>
		<?php if($solr_institute_data['total_institute_groups'] <= 0):?>
		<h5 style='margin-left:14px;margin-bottom: 5px !important;font-size: 20px !important;font:normal 20px "Trebuchet MS", Arial, Helvetica, sans-serif;'>No institute or course found for <span>&ldquo;<?php echo  htmlspecialchars($solr_institute_data['raw_keyword']);?>&rdquo;</span></h5>
		<?php else:
		?>
		<?php 
			$view_path  = $search_lib_object->renderView("normalsearchsnippet");
			$this->load->view($view_path);
		?>
      <?php endif;?>
      <?php elseif($solr_institute_data['single_result'] == 1): ?>
      <?php 
			$view_path  = $search_lib_object->renderView("singleinstituteresult");
			$this->load->view($view_path);
	?>
	<?php endif;?>
		<div class="search-btn-back">
			<a href="/search/showSearchHome" class="gray-button2">Search Institutes & Courses</a>
		</div>
	</div>
	<?php $this->load->view('/mcommon/footer');?>
