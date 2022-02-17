<title>view courses of the pageID=<?php echo $page_id;?></title>
<style>
.rowWDFcms {
  width: 988px!important;
}
.main {
  _margin-left: 150px!important;
}
.homeShik_footerBg {
   _position: relative;
   _left: 150px!important;
   _width: 1013px!important;
}
</style>
<div class="main">
<?php
$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>''
        );
        $this->load->view('enterprise/headerCMS', $headerComponents);
        $this->load->view('enterprise/cmsTabs',$headerTabs);           
        foreach($saved_courses_list as $course) {
        	foreach ($course as $course1) {
        		$saved_category_name[] = $course1['name'];
        	}
        }
        $saved_category_name = array_unique($saved_category_name);
        foreach ($saved_category_name as $name) {
        	$saved_category_names[] = $name;
        }   
$index1=0;        
?>
<table cellspacing="0" cellpadding="10" border="0" width="960">
	<tbody>
		<tr>
			<td width="430" valign="top">
			<div style="width: 500px; margin-bottom: 10px;"
				class="orangeColor fontSize_14p bld">
				<span>Course List</span><br/><br/></br>
				<span style="color:black;">Page URL: <?php echo $page_url?></span>
			<div class="grayLine_1" style="margin-bottom: 5px; margin-top: 5px;">&nbsp;</div>
			</div>
			<div class="mb18"><select id="selected" size="10" multiple="multiple"
				style="height: 200px; width: 500px;">
				<?php if((!empty($saved_management_courses[0]) || !empty($saved_management_courses[1]) || count($saved_management_courses)>2)&& is_array($saved_management_courses)):?>
				<optgroup id="ManagementParent" label="Management"
					style="background: none repeat scroll 0 0 #CCCCCC; line-height: 20px; width: 98%;"></optgroup>
				<optgroup label="All Courses" id="Management1">
					<?php foreach($saved_management_courses as $saved_mba_course):?>
					<?php if($saved_mba_course['SpecializationId']!=24):?>
					<option
						value="<?php echo 'Management1'.'-'.''.'-'.'ManagementParent';?>"
						id="<?php echo $saved_mba_course['SpecializationId']?>"><?php if(strtolower($saved_mba_course['SpecializationName'])=='all'): echo $saved_mba_course['CourseName']; else: echo $saved_mba_course['SpecializationName']; endif;?></option>
					<?php endif;?>
					<?php endforeach;?>
				</optgroup>
				<?php endif;?>
				<?php foreach($saved_courses_list as $course):?>
				<optgroup id="<?php echo $saved_category_names[$index1]?>"
					label="<?php echo $saved_category_names[$index1]?>"
					style="background: none repeat scroll 0 0 #CCCCCC; line-height: 20px; width: 98%;">
				</optgroup>
				<?php $count= count($course);for($i=0;$i<$count;$i++):?>
				<?php if((($i>=1) && ($course[$i-1]['groupId'] != $course[$i]['groupId'])) ||($i==0)): ?>
				<optgroup label="<?php if(empty($course[$i]['groupName'])):echo "All Courses"; else:echo $course[$i]['groupName']; endif;?>"
					id="<?php echo $course[$i]['groupId'];?>">
					<?php endif;?>
					<option
						value="<?php echo $course[$i]['groupId'].'-'.$saved_category_names[$index1];?>"
						id="<?php echo $course[$i]['SpecializationId']?>"><?php echo $course[$i]['CourseName']?></option>
					<?php endfor;?>
				</optgroup>
				<?php $index1++;endforeach;?>
			</select></div>
			<div style="padding-top: 10px; padding-bottom: 20px; top: 10px;">
			<button type="button" value="" class="btn-submit5 w12" onclick="location.replace('/enterprise/MultipleMarketingPage/marketingPageDetails')">
			<div class="btn-submit5">
			<p class="btn-submit6">Ok</p>
			</div>
			</button>
			</div>
			</td>
		</tr>
	</tbody>
</table>
</div>
<?php $this->load->view('common/footer');?>
