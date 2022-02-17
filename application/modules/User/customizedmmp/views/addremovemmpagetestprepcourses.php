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
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style', 'marketing'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>''
        );
        $this->load->view('enterprise/headerCMS', $headerComponents);
        $this->load->view('enterprise/cmsTabs',$headerTabs);
?>
<div class="orangeColor fontSize_14p bld"  style="padding-left:25px;font-size:13px;width:930px;">
  <div style="margin-bottom: 10px; font-size: 13px; width: 100%;" class="orangeColor fontSize_14p bld"><b>Add/Edit courses for MMP ID = <?php echo $page_id;?></b>
	<div style="margin-top: 5px;" class="grayLine_1">&nbsp;</div>
  </div>
</div>
<div style="width:430px;padding-left:25px;font-size:13px;">
    <?php if($page_type=='indianpage'):?><span>Study in India |</span><span><a onclick="disableLink(this)" href="<?php echo '/customizedmmp/mmp/addRemoveMMPageCourses/'.$page_id.'/abroadpage'?>" id="abroadpage"> Study Abroad </a>|</span><span><a onclick="disableLink(this)" href="<?php echo '/customizedmmp/mmp/addRemoveMMPageCourses/'.$page_id.'/testpreppage'?>" id="testpreppage"> Test Prep</a></span>
    <?php elseif($page_type=='abroadpage'):?><span><a id="indianpage" onclick="disableLink(this)" href="<?php echo '/customizedmmp/mmp/addRemoveMMPageCourses/'.$page_id.'/indianpage'?>">Study in India </a>|</span><span> Study Abroad |</span><span><a id="testpreppage" onclick="disableLink(this)" href="<?php echo '/customizedmmp/mmp/addRemoveMMPageCourses/'.$page_id.'/testpreppage'?>"> Test Prep</a></span>
    <?php elseif($page_type=='testpreppage'):?><span><a id="indianpage" onclick="disableLink(this)" href="<?php echo '/customizedmmp/mmp/addRemoveMMPageCourses/'.$page_id.'/indianpage'?>">Study in India </a>|</span><span><a onclick="disableLink(this)" href="<?php echo '/customizedmmp/mmp/addRemoveMMPageCourses/'.$page_id.'/abroadpage'?>" id="abroadpage"> Study Abroad </a>|</span><span> Test Prep</span><?php endif;?>
	<div class="grayLine_1" style="margin-top:5px;margin-bottom:5px;">&nbsp;</div>
</div>

<table cellspacing="0" cellpadding="10" border="0" width="960">
	<tbody>
		<tr>
			<td align="right" width="430" valign="top">
			<div style="width: 440px;font-size:14px;" class="lineSpace_25 bld txt_align_l"><span class="pl10">All Courses</span></div>
			<div style="height: 200px; padding: 10px;overflow: none;"class="txt_align_l">
			<div class="mb18">
			<select id="available" style="height: 200px; width: 420px;" name="available[]" size="10">
			   <?php foreach($courses_list as $key => $course):?>
				<optgroup label="<?php echo $course['0']['title'];?>" id="<?php echo $key?>" >
				  <?php foreach($course as $course1):?>
					<option value="<?php echo $key.'-'.$course['0']['title'];?>" id="<?php echo $course1['child']['blogId']?>"><?php echo $course1['child']['acronym']?></option>
					<?php endforeach;?>
					</optgroup>
				<?php endforeach;?>
			</select>
			</div></div>
			<div style="padding-top:10px;float:left;"><form action="/customizedmmp/mmp/saveMMPageCourses/<?php echo $page_id?>/<?php echo $page_type?>" method="post">
			<input type="hidden" value="<?php echo str_replace(array('T','D'),'',$course_ids);?>" name="courses_ids" id="courses_ids"/>
			  <button type="submit" value="" class="btn-submit5 w12" style="padding-left:10px;">
			<div class="btn-submit5">
			<p class="btn-submit6">Save</p>
			</div>
			</button>
			<button  style="padding-left:10px;" type="button" onclick="location.replace('/customizedmmp/mmp/')" value="" class="btn-submit5 w12">
			<div class="btn-submit5">
			<p class="btn-submit6">Cancel</p>
			</div>
			</button>
			</form></div>
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
			<span><?php echo "Page URL: "?></span><span style="font-size:12px;"><?php echo 'https://'.$_SERVER['HTTP_HOST'].$page_url?></span></div>
			<div
				style="height: 200px; padding: 10px;overflow: none;"
				class="">
			<div class="mb18">
			<?php if($count_courses==0||($original_page_type==$page_type)):?>
			<select id="moveFrom" name="moveFrom[]" size="10"  style="height:200px;width:400px;" size="10">
				<?php foreach($saved_courses_list as $key => $course):?>
				<optgroup label="<?php echo $course['0']['title'];?>" id="<?php echo $key.'dest'?>" >
				  <?php foreach($course as $course1):?>
					<option value="<?php echo $key.'-'.$course['0']['title'];?>" id="<?php echo $course1['child']['blogId']?>"><?php echo $course1['child']['acronym']?></option>
					<?php endforeach;?>
					</optgroup>
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
	var browserName = navigator.appName;
	var version = parseInt(navigator.appVersion,10);
	var selectOptions = document.getElementById(fromList);
	var addOptions = document.getElementById(toList);
	var optgroup = document.createElement("optgroup");
	var parent_optgroup = document.createElement("optgroup");
	var length = null;
	for ( var i = 0; i < selectOptions.length; i++) {
		if (selectOptions[i].selected) {
			var parrent_array =  selectOptions[i].value.split('-');
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
			var exist = childrenExist(addOptions, selectOptions[i].parentNode,'parent');
			if (!exist) {
				optgroup.setAttribute("label", selectOptions[i].parentNode
						.getAttribute('label'));
				if(selectOptions.id=='available') {
				optgroup.setAttribute("id", selectOptions[i].parentNode
						.getAttribute('id')+'dest');
				}
				if(selectOptions.id=='moveFrom') {
					optgroup.setAttribute("id", selectOptions[i].parentNode
							.getAttribute('id').replace('dest',''));
					}
				optgroup.appendChild(selectOptions[i]);
				addOptions.appendChild(optgroup);  
			} else if(exist) {
				exist.appendChild(selectOptions[i]);
			}
			if(!(document.getElementById(parrent_array['0']).innerHTML.replace(/^\s*|\s*$/g,'')))
            {  
	       		selectOptions.removeChild(document.getElementById(parrent_array['0']));
             }
            if(!(document.getElementById(parrent_array['0']+'dest').innerHTML.replace(/^\s*|\s*$/g,'')))
            {
            	selectOptions.removeChild(document.getElementById(parrent_array['0']+'dest'));
            }
		}
	}
}
function childrenExist(parent, children, type) {
	var ischild =null;
	for ( var j = 0; j < parent.childNodes.length; j++) {
		if(type=='parent' && parent.childNodes[j].label == children.label) {
			ischild = parent.childNodes[j];
			break;
		} else if (parent.childNodes[j].id == children.id) {
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
