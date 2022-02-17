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
        foreach($courses_list as $course) {
        	foreach ($course as $course1) {
        		$category_name[] = $course1['name'];
        	}
        }
        $category_name = array_unique($category_name);
        foreach ($category_name as $name) {
        	$category_names[] = $name;
        }           
        foreach($saved_courses_list as $course) {
        	foreach ($course as $course1) {
        		$saved_category_name[] = $course1['name'];
        	}
        }
        $saved_category_name = array_unique($saved_category_name);
        foreach ($saved_category_name as $name) {
        	$saved_category_names[] = $name;
        } 
$index=0;
$index1=0;        
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
			<select id="available" 
				style="height: 200px; width: 420px;" name="available[]" size="10">
				<?php if(is_array($mba_courses)):?>
				<optgroup id="Management" label="Management" style="background: none repeat scroll 0 0 #CCCCCC; line-height: 20px; width: 98%;" childCount="<?php echo count($mba_courses)?>"></optgroup>
				<optgroup label="All Courses" id="Management1">
                <?php foreach($mba_courses as $mba_course):?>
                <option value="<?php echo 'Management1'.'-'.''.'-'.'Management';?>" id="<?php echo
                $mba_course['SpecializationId']?>"><?php if($mba_course['ParentId']== 1): echo
$mba_course['CourseName']; else: echo 'Distance/Correspondence MBA-' .
$mba_course['SpecializationName']; endif;?></option>
				<?php endforeach;?>
				</optgroup>
				<?php endif;?>
				<?php foreach($courses_list as $course):?>
				<optgroup id="<?php echo $category_names[$index]?>"
				childCount="<?php echo count($course);?>" label="<?php echo $category_names[$index]?>" id="<?php echo $category_names[$index]?>"
					style="background: none repeat scroll 0 0 #CCCCCC; line-height: 20px; width: 98%;"></optgroup>
				<?php $count= count($course);for($i=0;$i<$count;$i++):?>
				<?php if((($i>=1) && ($course[$i-1]['groupId'] != $course[$i]['groupId'])) ||($i==0)): ?>
				<optgroup label="<?php if(empty($course[$i]['groupName'])): echo "All Courses"; else: echo $course[$i]['groupName'];endif;?>" id="<?php echo $course[$i]['groupId']?>">
					<?php endif;?>
					<option value="<?php echo
$course[$i]['groupId'].'-'.$course[$i]['groupName'].'-'.$category_names[$index];?>" id="<?php echo
$course[$i]['SpecializationId']?>"><?php echo $course[$i]['CourseName']; ?></option>
					<?php endfor;?>
					</optgroup>
				<?php $index++;endforeach;?>
			</select>
			</div></div>
			<div style="padding-top:10px;float:left;"><form action="/customizedmmp/mmp/saveMMPageCourses/<?php echo $page_id?>/<?php echo $page_type?>" method="post">
			<input type="hidden" value="<?php echo str_replace(array('T','D'),'',$course_ids);?>" name="courses_ids" id="courses_ids"/>
			<input type="hidden" value="<?php echo $management_courseids;?>" name="management_courses_ids" id="management_courses_ids"/>
			 <button type="submit" value="" class="btn-submit5 w12" style="padding-left:10px;">
			<div class="btn-submit5">
			<p class="btn-submit6">Save</p>
			</div>
			</button>
			<button  style="padding-left:10px;" type="button" onclick="location.replace('/customizedmmp/mmp/')"
				value="" class="btn-submit5 w12">
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
			<div style="height: 200px; padding: 10px;overflow: none;">
			<div class="mb18">
			<?php if($count_courses==0|| ($original_page_type==$page_type)):?>
			<select id="moveFrom" name="moveFrom[]" size="10"  style="height:200px;width:400px;" size="10">
			<?php if((!empty($saved_management_courses[0]) || !empty($saved_management_courses[1]) || count($saved_management_courses)>2)&& is_array($saved_management_courses)):?>
			<optgroup  childCount="<?php echo count($saved_management_courses);?>" id="Managementdest" label="Management" style="background: none repeat scroll 0 0 #CCCCCC; line-height: 20px; width: 98%;"></optgroup>
				<optgroup label="All Courses" id="Management1dest">
                <?php foreach($saved_management_courses as $saved_mba_course):?>
                <?php if(!empty($saved_mba_course)) :?><option value="<?php echo
'Management1'.'-'.''.'-'.'Management';?>" id="<?php echo $saved_mba_course['SpecializationId']?>"><?php
if(strtolower($saved_mba_course['SpecializationName']) == 'all'): echo $saved_mba_course['CourseName']; else: echo
'Distance/Correspondence MBA-' .$saved_mba_course['SpecializationName']; endif;?></option><?php endif;?>
				<?php endforeach;?>
				</optgroup>
				<?php endif;?>
				<?php foreach($saved_courses_list as $course):?>
				<optgroup childCount="<?php echo count($course);?>" label="<?php echo $saved_category_names[$index1]?>" id="<?php echo $saved_category_names[$index1].'dest'?>"
					style="background: none repeat scroll 0 0 #CCCCCC; line-height: 20px; width: 98%;"></optgroup>
				<?php $count= count($course);for($i=0;$i<$count;$i++):?>
				<?php if((($i>=1) && ($course[$i-1]['groupId'] != $course[$i]['groupId'])) ||($i==0)): ?>
				<optgroup label="<?php if(empty($course[$i]['groupName'])): echo "All Courses"; else: echo $course[$i]['groupName'];endif;?>" id="<?php echo $course[$i]['groupId'].'dest'?>">
					<?php endif;?>
					<option value="<?php echo $course[$i]['groupId'].'-'.$course[$i]['groupName'].'-'.$saved_category_names[$index1].'-'?>" id="<?php echo $course[$i]['SpecializationId']?>"><?php echo $course[$i]['CourseName']?></option>			
					<?php endfor;?>
				<?php $index1++;endforeach;?>
				</optgroup>
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
				if(selectOptions[i].parentNode.id=='Management1') {
					document.getElementById('management_courses_ids').value = document.getElementById('management_courses_ids').value + ' ' + selectOptions[i].id;
                	} else {
				document.getElementById('courses_ids').value = document.getElementById('courses_ids').value + ' ' + selectOptions[i].id;
				}
			} else if(selectOptions.id == 'moveFrom') {
				if(selectOptions[i].parentNode.id!='Management1dest') {
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
				if(selectOptions[i].parentNode.id=='Management1dest') {
					if(document.getElementById('management_courses_ids').value.split(' ').length>=2){
					if(document.getElementById('management_courses_ids').value.split(' ')['0']!= selectOptions[i].id) {
						document.getElementById('management_courses_ids').value = document.getElementById('management_courses_ids').value.replace(' '+selectOptions[i].id,''); 
					} else {
							document.getElementById('management_courses_ids').value = document.getElementById('management_courses_ids').value.replace(selectOptions[i].id,''); 
						}
					} else {
						document.getElementById('management_courses_ids').value = document.getElementById('management_courses_ids').value.replace(selectOptions[i].id,''); 
						}
			}
			}
			if(selectOptions.id == 'available') {
			      var grand_parent_exist = childrenExist(addOptions,document.getElementById(parrent_array['2']),'parent');
			} else if(selectOptions.id == 'moveFrom') {
				  var grand_parent_exist = childrenExist(addOptions,document.getElementById(parrent_array['2']+'dest'),'parent');
			}
			var exist = childrenExist(addOptions, selectOptions[i].parentNode);
			if (!exist) {
				if(!grand_parent_exist) {
					parent_optgroup.setAttribute('label',parrent_array['2']);
					if(selectOptions.id == 'available') {
					    parent_optgroup.setAttribute('id',parrent_array['2']+'dest');
					} else if(selectOptions.id == 'moveFrom') {
						parent_optgroup.setAttribute('id',parrent_array['2']); 
					}
					parent_optgroup.setAttribute('childCount',0);
					if(browserName=='Microsoft Internet Explorer') {
						parent_optgroup.style.backgroundColor ="#CCCCCC";
						parent_optgroup.style.lineHeight ="20px";
						parent_optgroup.style.width = "98%";
					} else {	
					    parent_optgroup.setAttribute('style',"background: none repeat scroll 0 0 #CCCCCC; line-height: 20px; width: 98%;");
					}
					addOptions.appendChild(parent_optgroup);
				}
				if(selectOptions.id == 'available') {
					document.getElementById(parrent_array['2']+'dest').setAttribute('childCount',parseInt(document.getElementById(parrent_array['2']+'dest').getAttribute('childCount'),10)+1);
					document.getElementById(parrent_array['2']).setAttribute('childCount',parseInt(document.getElementById(parrent_array['2']).getAttribute('childCount'),10)-1);
	           
				} else if(selectOptions.id == 'moveFrom') {
					document.getElementById(parrent_array['2']+'dest').setAttribute('childCount',parseInt(document.getElementById(parrent_array['2']+'dest').getAttribute('childCount'),10)-1);
					document.getElementById(parrent_array['2']).setAttribute('childCount',parseInt(document.getElementById(parrent_array['2']).getAttribute('childCount'),10)+1);
	           	}
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
				if(grand_parent_exist) {
					grand_parent_exist.parentNode.insertBefore(optgroup,grand_parent_exist.nextSibling);
				} else {
				addOptions.appendChild(optgroup);
				}  
			} else if(exist) {
				if(!grand_parent_exist) {
					parent_optgroup.setAttribute('label',parrent_array['2']);
					if(selectOptions.id == 'available') {
					    parent_optgroup.setAttribute('id',parrent_array['2']+'dest');
					} else if(selectOptions.id == 'moveFrom') {
						parent_optgroup.setAttribute('id',parrent_array['2']); 
					}
					parent_optgroup.setAttribute('childCount',0);
					if(browserName=='Microsoft Internet Explorer') {
						parent_optgroup.style.backgroundColor ="#CCCCCC";
						parent_optgroup.style.lineHeight ="20px";
						parent_optgroup.style.width = "98%";
					} else {	
					    parent_optgroup.setAttribute('style',"background: none repeat scroll 0 0 #CCCCCC; line-height: 20px; width: 98%;");
					}
					addOptions.appendChild(parent_optgroup);
				}
				if(selectOptions.id == 'available') {
					document.getElementById(parrent_array['2']+'dest').setAttribute('childCount',parseInt(document.getElementById(parrent_array['2']+'dest').getAttribute('childCount'),10)+1);
					document.getElementById(parrent_array['2']).setAttribute('childCount',parseInt(document.getElementById(parrent_array['2']).getAttribute('childCount'),10)-1);
	           
				} else if(selectOptions.id == 'moveFrom') {
					document.getElementById(parrent_array['2']+'dest').setAttribute('childCount',parseInt(document.getElementById(parrent_array['2']+'dest').getAttribute('childCount'),10)-1);
					document.getElementById(parrent_array['2']).setAttribute('childCount',parseInt(document.getElementById(parrent_array['2']).getAttribute('childCount'),10)+1);
	           	}
				if(grand_parent_exist && !exist) {
					grand_parent_exist.parentNode.insertBefore(optgroup,grand_parent_exist.nextSibling);
				} else {
					exist.appendChild(selectOptions[i]);
				}  
			}
			if(!(document.getElementById(parrent_array['0']).innerHTML.replace(/^\s*|\s*$/g,'')))
            {  
	       		selectOptions.removeChild(document.getElementById(parrent_array['0']));
             }
            if(!(document.getElementById(parrent_array['0']+'dest').innerHTML.replace(/^\s*|\s*$/g,'')))
            {
            	selectOptions.removeChild(document.getElementById(parrent_array['0']+'dest'));
            }
            if(document.getElementById(parrent_array['2']+'dest').getAttribute('childCount')==0) {
            	selectOptions.removeChild(document.getElementById(parrent_array['2']+'dest'));
			} else if(document.getElementById(parrent_array['2']).getAttribute('childCount')==0) {
				selectOptions.removeChild(document.getElementById(parrent_array['2']));
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
		} else if ((parent.childNodes[j].id == children.id+'dest') || (parent.childNodes[j].id+'dest' == children.id)) {
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
