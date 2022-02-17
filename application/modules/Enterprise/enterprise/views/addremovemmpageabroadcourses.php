<title>Edit Courses (PageID=<?php echo $page_id;?>)</title>
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
<script type="text/javascript">
var original_page_type = '<?php echo $original_page_type?>';
var page_type = '<?php echo $page_type?>';
var count_courses = <?php if(!empty($count_courses)): echo $count_courses; else: echo 0; endif;?>;
</script>
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

?>
<div class="orangeColor fontSize_14p bld"  style="padding-left:25px;font-size:13px;width:930px;"><b>Edit Courses</b>
<div class="grayLine_1" style="margin-top:5px;margin-bottom:5px;">&nbsp;</div>
</div>
<div style="width:430px;padding-left:25px;font-size:13px;">

	<span> Study Abroad </span>	
	<!-- <?php if($page_type=='indianpage'):?><span>Study in India <span style="color:#b6b6b6; margin:0 3px">|</span><a onclick="disableLink(this)" href="<?php echo '/enterprise/MultipleMarketingPage/addRemoveMMPageCourses/'.$page_id.'/abroadpage'?>" id="abroadpage">Study Abroad </a> <span style="color:#b6b6b6; margin:0 3px">|</span><a onclick="disableLink(this)" href="<?php echo '/enterprise/MultipleMarketingPage/addRemoveMMPageCourses/'.$page_id.'/testpreppage'?>" id="testpreppage"> Test Prep</a>
    <?php elseif($page_type=='abroadpage'):?><span><a id="indianpage" onclick="disableLink(this)" href="<?php echo '/enterprise/MultipleMarketingPage/addRemoveMMPageCourses/'.$page_id.'/indianpage'?>">Study in India </a>|</span><span> Study Abroad |</span><span><a id="testpreppage" onclick="disableLink(this)" href="<?php echo '/enterprise/MultipleMarketingPage/addRemoveMMPageCourses/'.$page_id.'/testpreppage'?>"> Test Prep</a></span>
     <?php elseif($page_type=='testpreppage'):?><span><a id="indianpage" onclick="disableLink(this)" href="<?php echo '/enterprise/MultipleMarketingPage/addRemoveMMPageCourses/'.$page_id.'/indianpage'?>">Study in India </a>|</span><span><a onclick="disableLink(this)" href="<?php echo '/enterprise/MultipleMarketingPage/addRemoveMMPageCourses/'.$page_id.'/abroadpage'?>" id="abroadpage"> Study Abroad </a>|</span><span> Test Prep</span><?php endif;?> -->
</div>
<table cellspacing="0" cellpadding="10" border="0" width="960">
	<tbody>
		<tr>
			<td align="right" width="430" valign="top">
			<div style="width: 440px;font-size:14px;" class="lineSpace_25 bld txt_align_l"><span class="pl10">All Courses</span></div>
			<div style="height: 200px; padding: 10px;overflow: none;"class="txt_align_l">
			<div class="mb18">
			<select id="available"
				style="height: 200px; width: 420px;" name="available[]" size="10">
                <!--
                dont know how to handle such requirments
                Hide testprep category for selection
                -->
				<?php foreach($courses_list as $key => $course) { ?>
                    <?php if ($key != 14) { ?>
					<option value="<?php echo $key;?>" id="<?php echo $key;?>"><?php echo $course?></option>
					<?php } } ?>
			</select>
			</div></div>
			<div style="padding:15px 0 0 10px;float:left;">
			<form
				action="/enterprise/MultipleMarketingPage/saveMMPageCourses/<?php echo $page_id?>/<?php echo $page_type?>"
				method="post"><input type="hidden" value="<?php echo str_replace(array('T','D'),'',$course_ids);?>"
				name="courses_ids" id="courses_ids" />
			<input type="submit" name="savebutton" class="orange-button" value="Save And Customize Form" />&nbsp;
                        <?php if(count($saved_courses_lists)>0) {?>
            <input type="submit" name="skipbutton" value="Skip" class="orange-button" />&nbsp;
            <?php } ?>
			<a href="javascript:void()" style="font-size:16px" onclick="location.replace('/enterprise/MultipleMarketingPage/marketingPageDetails')">Cancel</a>

			</form>
			</div>
			</td>
			<td align="center" width="100" valign="middle">
			<div style="margin-bottom: 20px;"></div>
			<div style="float: left; height: 50px; padding-top: 30px;">
			<button type="button" value="" class="btn-submit5"
				style="width:50px;" onclick="swapElement('moveFrom','available')">
			<div class="btn-submit5">
			<p class="btn-submit6">&lt;</p>
			</div>
			</button>
			<br/>
			<button style="width:50px;" type="button" value="" class="btn-submit5"
				onclick="swapElement('available','moveFrom')">
			<div class="btn-submit5">
			<p class="btn-submit6">&gt;</p>
			</div>
			</button>
			</div>
			</td>
			<td width="430" valign="top">
			<div style="width: 430px;font-size:14px;" class="lineSpace_25 bld txt_align_l pl10">
            <span><?php echo "Courses in this form"?></span><span style="font-size:12px;"></span></div>
			<div
				style="height: 200px; padding: 10px;overflow: none;"
				class="">
			<div class="mb18">
			<?php if($count_courses==0 || ($original_page_type==$page_type)):?>
			<select id="moveFrom" name="moveFrom[]" size="10"  style="height:200px;width:400px;">
				<?php foreach($saved_courses_lists as $key => $course):?>
					<option value="<?php echo $key;?>" id="<?php echo $key;?>"><?php echo $course?></option>
					<?php endforeach;?>
			</select>
			<?php endif;?>
			</div>
			</div>
			</td>
		</tr>
	</tbody>
</table>
<div class="clear_L withClear">&nbsp;</div>
  <div class="clear_L withClear">&nbsp;</div>
 <div class="clear_L withClear">&nbsp;</div>
  <div class="clear_L withClear">&nbsp;</div>
</div>
<?php $this->load->view('common/footer');?>
<script type="text/javascript">
function swapElement(fromList, toList) {
	var selectOptions = document.getElementById(fromList);
	var addOptions = document.getElementById(toList);
	for ( var i = 0; i < selectOptions.length; i++) {
		if (selectOptions[i].selected) {
			if(selectOptions.id == 'available') {
				document.getElementById('courses_ids').value = document.getElementById('courses_ids').value + ' ' + selectOptions[i].id;

			} else if(selectOptions.id == 'moveFrom') {
				if(document.getElementById('courses_ids').value.split(' ').length>2){
					if(document.getElementById('courses_ids').value.split(' ')['0']!= selectOptions[i].id) {
					document.getElementById('courses_ids').value = document.getElementById('courses_ids').value.replace(' '+selectOptions[i].id,'');
					} else {
						document.getElementById('courses_ids').value = document.getElementById('courses_ids').value.replace(selectOptions[i].id,'');
						}
				} else {
					document.getElementById('courses_ids').value = document.getElementById('courses_ids').value.replace(selectOptions[i].id,'');
					}
			}
			var exist = childrenExist(addOptions, selectOptions[i]);
			if (!exist) {
				addOptions.appendChild(selectOptions[i]);
			} else if(exist) {
				exist.appendChild(selectOptions[i]);
			}
		}
	}
}
function childrenExist(parent, children, type) {
	var ischild =null;
	for ( var j = 0; j < parent.childNodes.length; j++) {
		if (parent.childNodes[j].id == children.id) {
			ischild = parent.childNodes[j];
			break;
		}
	}
	return ischild;
}
function disableLink(element) {
	if((count_courses!=0)) {
        alert("You can't mix courses from Study in India, Study Abroad & Test Prep on one page. Please remove all existing courses");
        element.removeAttribute('href');
        element.onclick = '';
}
}
</script>
