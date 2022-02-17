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
			<div style="width:500px;margin-bottom:10px;" class="orangeColor fontSize_14p bld">
			<span>Course List</span><br/><br/></br>
			<span style="color:black;">Page URL: <?php echo $page_url?></span>
			<div class="grayLine_1" style="margin-bottom:5px;margin-top:5px;">&nbsp;</div>
			</div>
			<div class="mb18">
			<select id="moveFrom" name="moveFrom[]" size="10"  style="height:200px;width:500px;" size="10">
				<?php foreach($saved_courses_list as $key => $course):?>
				<optgroup label="<?php echo $course['0']['title'];?>" id="<?php echo $key?>" >
				  <?php foreach($course as $course1):?>
					<option value="<?php echo $key.'-'.$course['0']['title'];?>" id="<?php echo $course1['child']['blogId']?>"><?php echo $course1['child']['acronym']?></option>
					<?php endforeach;?>
					</optgroup>
				<?php endforeach;?>
			</select>
			</div>
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