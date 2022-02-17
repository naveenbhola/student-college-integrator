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
.nav{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;padding-left:0;margin-bottom:0;list-style:none}.nav-pills .nav-link{border-radius:.25rem}.nav-pills .nav-item.show .nav-link,.nav-pills .nav-link.active{color:#fff;cursor:default;background-color:#0275d8}.nav-pills>li.active>a,.nav-pills>li.active>a:focus,.nav-pills>li.active>a:hover{color:#fff;background-color:#337ab7}.nav-pills>li{float:left}.nav>li{position:relative;display:block}.nav-link{padding:10px;text-decoration:none !important}nav-link:hover{text-decoration:none !important}.h3,h3{font-size:1.25rem !important}h2,h3,p{orphans:3;widows:3}h2,h3{page-break-after:avoid}h1,h2,h3,h4,h5,h6{margin-top:0;margin-bottom:.5rem}
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
<div class="orangeColor fontSize_14p bld"  style="padding-left:25px;width:930px;"><h3>Edit Courses/Customize Form</h3>
<div class="grayLine_1" style="margin-top:5px;margin-bottom:5px;">&nbsp;</div>
	
</div>
<p style="padding-left:25px;width:930px;margin-bottom: 20px;">Kindly select any option to proceed further.</p>
<div style="width:430px;padding-left:25px;font-size:13px;">

		<ul class="nav nav-pills" style="margin: 19px 0px 15px; visibility: visible;">
			  <li class="nav-item">
			    <a class="nav-link active" href="<?php echo '/enterprise/MultipleMarketingPage/addRemoveMMPageCourses/'.$page_id.'/abroadpage'?>" id="abroadpage">Study Abroad </a>
			  </li>
			  <li class="nav-item">
			    <a class="nav-link" href="<?php echo '/enterprise/MultipleMarketingPage/addRemoveMMPageCourses/'.$page_id.'/indianpage'?>">Domestic</a>
			  </li>
		</ul>
</div>

<div class="clear_L withClear">&nbsp;</div>
  <div class="clear_L withClear">&nbsp;</div>
 <div class="clear_L withClear">&nbsp;</div>
  <div class="clear_L withClear">&nbsp;</div>
</div>
<?php die; ?>
<?php $this->load->view('common/footer');?>
<script type="text/javascript">
// function swapElement(fromList, toList) {
// 	var browserName = navigator.appName;
// 	var version = parseInt(navigator.appVersion,10);
// 	var selectOptions = document.getElementById(fromList);
// 	var addOptions = document.getElementById(toList);
// 	var optgroup = document.createElement("optgroup");
// 	var parent_optgroup = document.createElement("optgroup");
// 	var length = null;
// 	for ( var i = 0; i < selectOptions.length; i++) {
// 		if (selectOptions[i].selected) {
// 			var parrent_array =  selectOptions[i].value.split('-');
// 			if(selectOptions.id == 'available') {
// 				if(selectOptions[i].parentNode.id=='Management1') {
// 					document.getElementById('management_courses_ids').value = document.getElementById('management_courses_ids').value + ' ' + selectOptions[i].id;
//                 	} else {
// 				document.getElementById('courses_ids').value = document.getElementById('courses_ids').value + ' ' + selectOptions[i].id;
// 				}
// 			} else if(selectOptions.id == 'moveFrom') {
// 				if(selectOptions[i].parentNode.id!='Management1dest') {
// 				if(document.getElementById('courses_ids').value.split(' ').length>2){
// 					if(document.getElementById('courses_ids').value.split(' ')['0']!= selectOptions[i].id) {
// 					document.getElementById('courses_ids').value = document.getElementById('courses_ids').value.replace(' '+selectOptions[i].id,'');
// 					} else {
// 						document.getElementById('courses_ids').value = document.getElementById('courses_ids').value.replace(selectOptions[i].id,'');
// 						}
// 				} else {
// 					document.getElementById('courses_ids').value = document.getElementById('courses_ids').value.replace(selectOptions[i].id,'');
// 					}
// 			}
// 				if(selectOptions[i].parentNode.id=='Management1dest') {
// 					if(document.getElementById('management_courses_ids').value.split(' ').length>=2){
// 					if(document.getElementById('management_courses_ids').value.split(' ')['0']!= selectOptions[i].id) {
// 						document.getElementById('management_courses_ids').value = document.getElementById('management_courses_ids').value.replace(' '+selectOptions[i].id,'');
// 					} else {
// 							document.getElementById('management_courses_ids').value = document.getElementById('management_courses_ids').value.replace(selectOptions[i].id,'');
// 						}
// 					} else {
// 						document.getElementById('management_courses_ids').value = document.getElementById('management_courses_ids').value.replace(selectOptions[i].id,'');
// 						}
// 			}
// 			}
// 			if(selectOptions.id == 'available') {
// 			      var grand_parent_exist = childrenExist(addOptions,document.getElementById(parrent_array['2']),'parent');
// 			} else if(selectOptions.id == 'moveFrom') {
// 				  var grand_parent_exist = childrenExist(addOptions,document.getElementById(parrent_array['2']+'dest'),'parent');
// 			}
// 			var exist = childrenExist(addOptions, selectOptions[i].parentNode);
// 			if (!exist) {
// 				if(!grand_parent_exist) {
// 					parent_optgroup.setAttribute('label',parrent_array['2']);
// 					if(selectOptions.id == 'available') {
// 					    parent_optgroup.setAttribute('id',parrent_array['2']+'dest');
// 					} else if(selectOptions.id == 'moveFrom') {
// 						parent_optgroup.setAttribute('id',parrent_array['2']);
// 					}
// 					parent_optgroup.setAttribute('childCount',0);
// 					if(browserName=='Microsoft Internet Explorer') {
// 						parent_optgroup.style.backgroundColor ="#CCCCCC";
// 						parent_optgroup.style.lineHeight ="20px";
// 						parent_optgroup.style.width = "98%";
// 					} else {
// 					    parent_optgroup.setAttribute('style',"background: none repeat scroll 0 0 #CCCCCC; line-height: 20px; width: 98%;");
// 					}
// 					addOptions.appendChild(parent_optgroup);
// 				}
// 				if(selectOptions.id == 'available') {
// 					document.getElementById(parrent_array['2']+'dest').setAttribute('childCount',parseInt(document.getElementById(parrent_array['2']+'dest').getAttribute('childCount'),10)+1);
// 					document.getElementById(parrent_array['2']).setAttribute('childCount',parseInt(document.getElementById(parrent_array['2']).getAttribute('childCount'),10)-1);

// 				} else if(selectOptions.id == 'moveFrom') {
// 					document.getElementById(parrent_array['2']+'dest').setAttribute('childCount',parseInt(document.getElementById(parrent_array['2']+'dest').getAttribute('childCount'),10)-1);
// 					document.getElementById(parrent_array['2']).setAttribute('childCount',parseInt(document.getElementById(parrent_array['2']).getAttribute('childCount'),10)+1);
// 	           	}
// 				optgroup.setAttribute("label", selectOptions[i].parentNode
// 						.getAttribute('label'));
// 				if(selectOptions.id=='available') {
// 					optgroup.setAttribute("id", selectOptions[i].parentNode
// 							.getAttribute('id')+'dest');
// 					}
// 					if(selectOptions.id=='moveFrom') {
// 						optgroup.setAttribute("id", selectOptions[i].parentNode
// 								.getAttribute('id').replace('dest',''));
// 						}
// 				optgroup.appendChild(selectOptions[i]);
// 				if(grand_parent_exist) {
// 					grand_parent_exist.parentNode.insertBefore(optgroup,grand_parent_exist.nextSibling);
// 				} else {
// 				addOptions.appendChild(optgroup);
// 				}
// 			} else if(exist) {
// 				if(!grand_parent_exist) {
// 					parent_optgroup.setAttribute('label',parrent_array['2']);
// 					if(selectOptions.id == 'available') {
// 					    parent_optgroup.setAttribute('id',parrent_array['2']+'dest');
// 					} else if(selectOptions.id == 'moveFrom') {
// 						parent_optgroup.setAttribute('id',parrent_array['2']);
// 					}
// 					parent_optgroup.setAttribute('childCount',0);
// 					if(browserName=='Microsoft Internet Explorer') {
// 						parent_optgroup.style.backgroundColor ="#CCCCCC";
// 						parent_optgroup.style.lineHeight ="20px";
// 						parent_optgroup.style.width = "98%";
// 					} else {
// 					    parent_optgroup.setAttribute('style',"background: none repeat scroll 0 0 #CCCCCC; line-height: 20px; width: 98%;");
// 					}
// 					addOptions.appendChild(parent_optgroup);
// 				}
// 				if(selectOptions.id == 'available') {
// 					document.getElementById(parrent_array['2']+'dest').setAttribute('childCount',parseInt(document.getElementById(parrent_array['2']+'dest').getAttribute('childCount'),10)+1);
// 					document.getElementById(parrent_array['2']).setAttribute('childCount',parseInt(document.getElementById(parrent_array['2']).getAttribute('childCount'),10)-1);

// 				} else if(selectOptions.id == 'moveFrom') {
// 					document.getElementById(parrent_array['2']+'dest').setAttribute('childCount',parseInt(document.getElementById(parrent_array['2']+'dest').getAttribute('childCount'),10)-1);
// 					document.getElementById(parrent_array['2']).setAttribute('childCount',parseInt(document.getElementById(parrent_array['2']).getAttribute('childCount'),10)+1);
// 	           	}
// 				if(grand_parent_exist && !exist) {
// 					grand_parent_exist.parentNode.insertBefore(optgroup,grand_parent_exist.nextSibling);
// 				} else {
// 					exist.appendChild(selectOptions[i]);
// 				}
// 			}
// 			if(!(document.getElementById(parrent_array['0']).innerHTML.replace(/^\s*|\s*$/g,'')))
//             {
// 	       		selectOptions.removeChild(document.getElementById(parrent_array['0']));
//              }
//             if(!(document.getElementById(parrent_array['0']+'dest').innerHTML.replace(/^\s*|\s*$/g,'')))
//             {
//             	selectOptions.removeChild(document.getElementById(parrent_array['0']+'dest'));
//             }
//             if(document.getElementById(parrent_array['2']+'dest').getAttribute('childCount')==0) {
//             	selectOptions.removeChild(document.getElementById(parrent_array['2']+'dest'));
// 			} else if(document.getElementById(parrent_array['2']).getAttribute('childCount')==0) {
// 				selectOptions.removeChild(document.getElementById(parrent_array['2']));
// 			}
// 		}
// 	}
// }
// function childrenExist(parent, children, type) {
// 	var ischild =null;
// 	for ( var j = 0; j < parent.childNodes.length; j++) {
// 		if(type=='parent' && parent.childNodes[j].label == children.label) {
// 			ischild = parent.childNodes[j];
// 			break;
// 		} else if ((parent.childNodes[j].id == children.id+'dest') || (parent.childNodes[j].id+'dest' == children.id)) {
// 			ischild = parent.childNodes[j];
// 			break;
// 		}
// 	}
// 	return ischild;
// }
// function disableLink(element) {
// 	if((count_courses!=0)) {
//         alert("You can't mix courses from Study in India, Study Abroad & Test Prep on one page. Please remove all existing courses");
//         element.removeAttribute('href');
//         element.onclick = '';
// }
// }
</script>
