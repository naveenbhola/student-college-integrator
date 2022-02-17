<div class="head-group" data-enhance="false">
<h1>
    <div class="left-align" style="margin-right:50px; margin-left: 12px;">
	<?=displayTextAsPerMobileResolution(html_escape($searchurlparams['keyword']),2,true)?><br />
	<?php if($solr_institute_data['total_institute_groups']>0):?>
		<p>Total <?php echo getPlural($total_results, 'Institute');?> and <?php echo getPlural($solr_institute_data['numfound_course_documents'], 'Course');?> found</p>
	<?php endif;?>
    </div>
</h1>
<div class="head-filter" id="showFilterButton">
		<?php if($refine_action == '/search/refineSearch'):?>
		<a id="refineOverlayOpen" href="#refineDiv" data-inline="true" data-rel="dialog" data-transition="slide" >
			<i class="icon-busy" aria-hidden="true"></i>
			<p>Filter</p>
		</a>
		<?php else:?>
		<form method="post" action="<?php echo $refine_action;?>" id="refineForm">
			<input type="hidden" name="keyword" value="<?php echo url_base64_encode($searchurlparams['keyword']);?>"/>
			<input type="hidden" name="city_id" value="<?php echo $searchurlparams['city_id'];?>"/>	 
			<input type="hidden" name="course_type" value="<?php echo $searchurlparams['course_type'];?>"/>
			<input type="hidden" name="course_level" value="<?php echo $searchurlparams['course_level'];?>"/>
			<a href="javascript:void(0);" onClick="$('#refineForm').submit();">
				<i class="icon-busy" aria-hidden="true"></i>
				<p>Filter</p>
			</a>
		</form>
		<?php endif;?>
</div>
</div>