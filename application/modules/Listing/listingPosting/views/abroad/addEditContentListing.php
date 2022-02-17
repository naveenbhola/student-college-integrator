<?php if($action == 'add') {
	$content['country_info'][0]['country_id'] = "";
	$content['university_info'][0]['country_id'] = "";
	$content['ldbCourse_info'][0]['ldb_course_id'] = "";
	$content['tag_info'][0]['ldb_course_id'] = "";
	$content['courseMapping_info'][0]['course_type'] = "";
	$content['basic_info']['seo_title'] = "";
	$content['basic_info']['seo_description'] = "";
	$content['basic_info']['seo_keywords'] = "";
	$content['basic_info']['is_downloadable'] = "no";
	$content['basic_info']['download_link'] == "";
	$content['basic_info']['type'] = "";
	$content['basic_info']['exam_type'] = "";
	$checked = "";
	$examChecked ='';
	$examSectionCount =1;
} else {
	$disabled = "disabled";
	if($content['basic_info']['type'] == "article"){
		$checked = "";
		$examChecked = "";
	}
	elseif($content['basic_info']['type'] == "guide"){
		$checked = "checked";
		$examChecked = "";
	}
	elseif($content['basic_info']['type'] == "examPage"){ 
		$checked = "";
		$examChecked = "checked";
	}
	elseif($content['basic_info']['type'] == "applyContent"){ 
		$checked = $examChecked = "";
		$applyChecked = "checked";
	}
	elseif($content['basic_info']['type'] == "examContent"){ 
		$checked = $examChecked = $applyChecked="";
		$examContentChecked = "checked";
	}
	$examSectionCount = count($content['contentSection_info']);
	$content['basic_info']['contentImageURL'] = isset($content['basic_info']['contentImageURL']) ? $content['basic_info']['contentImageURL'] : '';
	$userLifecycleTags = $content['lifecycle_tags'];
	if($content["basic_info"]["type"] == "applyContent")
	{ 
		$contentURL = str_replace(SHIKSHA_STUDYABROAD_HOME.'/','',$content["basic_info"]["contentURL"]);
		$contentURL = str_replace('-applycontent'.$content["applyContentDetails"]["applyContentType"].$content["basic_info"]["content_id"],'',$contentURL);
		$contentURL = trim($contentURL,'/');
	}
	if($content["basic_info"]["type"] == "examContent")
	{ 
		$contentURL = explode("/",$content["basic_info"]["contentURL"]);
		$contentURL = $contentURL[count($contentURL)-1];
	}
}
$showLifecycleTags = (in_array($content['basic_info']['type'],array('article','guide')) || $action == "add");
?>
<script>
	var lifecycleTags = JSON.parse('<?=(json_encode($lifecycleTags))?>');
	var userLifecycleTags = JSON.parse('<?=(json_encode($userLifecycleTags))?>');
</script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('imageUpload'); ?>"></script>
<div class="abroad-cms-rt-box">
	<?php $this->load->view("listingPosting/abroad/widgets/content/addEditContentHeader");?>
	<div class="content-type-head clear-width">
		<div class="cms-form-wrap" style="margin:0;">
			<ul>
				<li>
					<label>Content Type* : </label>
					<div class="cms-fields" style="margin-top:6px; font-size:15px;">
						<input type="radio" onchange="loadNewContentType('article'); setImageContainer()" id="articleRadio" name="contentTypeRadio" value="article" checked="checked" <?=$disabled?>/> Article
						<input type="radio" onchange="loadNewContentType('guide');setImageContainer()" id="guideRadio" name="contentTypeRadio" value="guide" <?=$checked?> <?=$disabled?>/> Guide
						<input type="radio" onchange="loadNewContentType('examPage');setImageContainer()" id="examPageRadio" name="contentTypeRadio" value="examPage" <?=$examChecked?> <?=$disabled?> style="display:none;"/><!-- Exam Page -->
						<input type="radio" onchange="loadNewContentType('applyContent');setImageContainer()" id="applyContentRadio" name="contentTypeRadio" value="applyContent" <?=$applyChecked?> <?=$disabled?>/> Apply Content
						<input type="radio" onchange="loadNewContentType('examContent');setImageContainer()" id="examContentRadio" name="contentTypeRadio" value="examContent" <?=$examContentChecked?> <?=$disabled?>/> Exam Content
					</div>
				</li>
			</ul>
		</div>
	</div>
	<?php $this->load->view("listingPosting/abroad/widgets/content/addEditContentImageSection");?>		
	
	<form name="form_content" id="form_content" action="<?=ENT_SA_CMS_PATH?>saveContentListing" enctype="multipart/form-data" method="post">	

		<input type="hidden" name="blogImageUrl" id="blogThumbnail" value="<?php echo $content['basic_info']['contentImageURL']; ?>"/>
		
		<input type="hidden" name="commentCount" id="commentCount" value="<?php echo $content['basic_info']['commentCount']; ?>"/>
		<input type="hidden" name="viewCount" id="viewCount" value="<?php echo $content['basic_info']['viewCount']; ?>"/>
		<input type="hidden" name="contentURL" id="contentURL" value="<?php echo $content['basic_info']['contentURL']; ?>"/>	
		
		<div class="cms-form-wrapper clear-width">
		<?php $this->load->view("listingPosting/abroad/widgets/content/addEditContentMappingSection");?>
		<?php $this->load->view("listingPosting/abroad/widgets/content/addEditContentExamDetail");?>
		<?php $this->load->view("listingPosting/abroad/widgets/content/addEditContentSummaryTitleDescription",array('showLifecycleTags'=>$showLifecycleTags,'userLifecycleTags'=>$userLifecycleTags));?>
		<?php $this->load->view("listingPosting/abroad/widgets/content/addEditContentRelatedSection");?>		
		<?php $this->load->view("listingPosting/abroad/widgets/content/addEditContentSEODetails",array('contentURL'=>$contentURL));?>	
		<div style="display: none" class="errorMsg" id="contentForm_error"></div>
		</div>
		
		<div class="button-wrap">
			<input style="display: none" name="status" id="status" value="draft"></input>
			<a href="javascript:void(0);" onclick="showHideError('draft');" class="gray-btn">Save as Draft</a>
			<?php if($previewLinkFlag){?><a target="_blank" href="<?=SHIKSHA_STUDYABROAD_HOME.$content['basic_info']['contentURL']?>" class="gray-btn">Preview</a><?php }?>
			<a href="javascript:void(0);" onclick="showHideError('live');" class="orange-btn">Save & Publish</a>
			<a href="javascript:void(0);" onclick="cancelAction('<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CONTENT?>');" class="cancel-btn">Cancel</a>
		</div>
		<input type="hidden" name="contentType" id="contentType" value="<?=$content['basic_info']['type']?>"></input>
		<input type="hidden" name="actionType" id="actionType" value="<?=$action?>"></input>
		<input type="hidden" name="contentTypeId" id="contentTypeId" value="<?=$content['basic_info']['content_id']?>"></input>
	</form>
</div>
<div class="clearFix"></div>
<script>
	var MEDIAHOSTURL = '<?php echo MEDIAHOSTURL; ?>';
	var thumbNailElementSelected = null;
	var pageTypeText = 'article';
	var examSectionCount = <?= $examSectionCount;?>;
	<?php if ($action == 'edit') { ?>
		var contentPageAction = 'edit';
		var contentPageType = "<?=$content['basic_info']['type']?>";
		<?php if($content['basic_info']['type']=='examPage'){?>
		pageTypeText = "exam page";
		<?php }else{?>
		pageTypeText = "<?=$content['basic_info']['type']?>";
		<?php }?>
		var selectedUniversityIds = [];
		<?php foreach($content['university_info'] as $info) { ?>
			selectedUniversityIds.push(<?=$info['university_id']?>);
		<?php } ?>
		
		var enableCategories = false;
		
		<?php if(!empty($content['courseMapping_info'][0])) { ?>
			enableCategories = true;
			var selectedSubCatIds = [];
			<?php foreach($content['courseMapping_info'] as $course) { ?>
				selectedSubCatIds.push("<?=$course['subcategory_id']?>");
			<?php } ?>
		<?php } ?>
		
		var downloadChecked = false;
		<?php if($content['basic_info']['is_downloadable'] == "yes") { ?>
			downloadChecked = true;
		<?php } ?>
		
	<?php } ?>
	
	var categoryDetails = eval(<?php echo json_encode($abroadCategories); ?>);
	
	function startCallback() {
        return true;
    }
	
	var saveInitiated = false;
    function completeCallback(response) {
		var responseData = eval('(' + response + ')');
		//console.log(responseData);
		if(typeof responseData["error_type"] != "undefined" && responseData["error_type"] == "disallowedaccess") {
			window.location.href = "/enterprise/Enterprise/disallowedAccess";
			return;
		}
		if(typeof responseData["error_type"] != "undefined" && responseData["error_type"] == "notloggedin") {
			window.location.href = "/enterprise/Enterprise/loginEnterprise";
			return;
		}
        if (typeof responseData['error'] != 'undefined' && typeof responseData['error']['Fail'] != 'undefined') {
            for (var prop in responseData['error'].Fail) {
                switch (prop) {
                    case "uploadFile":
						var str = responseData['error'].Fail[prop].replace("brochure","guide");
                        $j("#uploadFile_error").html(str).show();
						postContentForm = true;
                        break;
		    case "file_size_exceeded":
						$j("#uploadFile_error").html(responseData['error'].Fail[prop]).show();
						postContentForm = true;
                        break;
			case "guide_mandatory":
						$j("#uploadFile_error").html("Downloadable guide is mandatory for homepage").show();
						postContentForm = true;
                        break;
			case "homePage":
						alert('Home page for this exam content already exists!');
						$j('#setHomepageDiv_error').html("Home Page already exists").show();
						postContentForm = true;
                        break;
                }	
            }
        }
        else{
			if($j("#status").val() == 'draft') {
				alert("Content successfully saved in the draft state.");
			}
			else {
				alert("Content successfully published.");
			}
			window.location.href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CONTENT?>";
        }
    }
	
    function initFormPosting() {
        AIM.submit(document.getElementById('form_content'), {'onStart' : startCallback, 'onComplete' : completeCallback});
    }
    
    if(document.all) {
        document.body.onload = initFormPosting;
    } else {
        initFormPosting();
    }

	window.onload = function () {

	    <?php

	    			if(!empty($blogImages)) {  
	    				foreach($blogImages as $blogImage) {
		                $imageUrl = explode('.',$blogImage['imageUrl']);
		                $thumbNailUrl = '_s.'. end($imageUrl);
		                unset($imageUrl[count($imageUrl) -1]);
		                $thumbNailUrl = implode('.',$imageUrl) . $thumbNailUrl;
		                $imageDetails = array(
		                                    'mediaid' => $blogImage['mediaId'],
		                                    'imageurl' => $blogImage['imageUrl'],
		                                    'thumburl' => $thumbNailUrl 
		                                    );
		                echo 'showUploadImageResponseForBlog(\''. json_encode($imageDetails)  .'\');';
	    			}
	    			}
	        ?>		
		setImageContainer();	
		AIM.submit(document.getElementById('blogImageForm'), {'onStart' : startCallback, 'onComplete' : showUploadImageResponseForBlog});		
	}

    function showUploadImageResponseForBlog(response) {
        try{
            var mediaDetails = eval('eval('+response+')');
        } catch(e) {
            alert(response);
            return false;
        }
        var mediaId = mediaDetails.mediaid;
        var mediaUrl = mediaDetails.imageurl;
        var mediaThumbUrl = mediaDetails.thumburl;
        var pickThumbnailText = 'Pick it as thumbnail of the '+pageTypeText;
        var mediaPickFlag = false;
        if(document.getElementById('blogThumbnail').value.replace(':80','') == mediaThumbUrl.replace(':80','')) {
            pickThumbnailText = 'Remove it as thumbnail for '+pageTypeText;
            mediaPickFlag = true;
        }
        imagePlaceHolderId ="imageContainer_" + mediaId;
        imagePlaceHolderInnerHTML = '<div id="'+ imagePlaceHolderId +'" style="height:55px;border-bottom:solid 1px #acacac;"><a href="'+MEDIAHOSTURL+ mediaUrl +'" target="_blank">\
                                        <img src="'+ MEDIAHOSTURL+mediaThumbUrl +'" border="0" align="absmiddle" id="blogThumbnail_'+ mediaId +'"/>\
                                    </a>&nbsp;\
                                    <label class="fontSize_12p bld">'+ insertWbr(MEDIAHOSTURL+mediaUrl,30) +'\
                                    &nbsp;<br/>\
                                    &nbsp;<a href="javascript:void(0)" id="mediaPick_'+ mediaId +'" onclick="return toggleThumbnail(this,'+ mediaId +')">'+ pickThumbnailText +'</a>\
                                    </div><div class="lineSpace_10">&nbsp;</div>';
        document.getElementById('blogImages').innerHTML += imagePlaceHolderInnerHTML;
        document.getElementById('fakeImageContainer').style.height = (document.getElementById('blogImagesContainer').offsetHeight + 30) + 'px';
        var blogMedia = document.createElement('input');
        blogMedia.type = 'hidden';
        blogMedia.name = 'blogImage[]';
        blogMedia.id = 'blogImage_'+ mediaId;
        blogMedia.value = mediaId +'#'+ mediaUrl;
        document.getElementById('form_content').appendChild(blogMedia);
        document.getElementById('blogImageForm').reset();
        if(mediaPickFlag) {
            thumbNailElementSelected = document.getElementById('mediaPick_'+ mediaId);
        }
    }
    
    function setImageContainer() {
        var fakeImageContainer = document.getElementById('fakeImageContainer');
        var mainImageContainer = document.getElementById('blogImagesContainer');
        var xPos = obtainPostitionX(fakeImageContainer);
        var yPos = obtainPostitionY(fakeImageContainer);
        mainImageContainer.style.left = 172 +'px';
        mainImageContainer.style.top = yPos + 'px';
        mainImageContainer.style.display = '';
        fakeImageContainer.style.height = mainImageContainer.offsetHeight + 30 + 'px';
        return true;
    }

    function toggleThumbnail(thumbnailElement, mediaId) {
        if(thumbnailElement.innerHTML == 'Pick it as thumbnail of the '+pageTypeText) {
            var toggleFlag = confirm('Picking this image as thumbnail for the '+pageTypeText+' will change any other previous images picked for thumbnail to this image! Are you sure to continue?');
            if(toggleFlag) {
                thumbnailElement.innerHTML = 'Remove it as thumbnail of the '+pageTypeText;
                document.getElementById('blogThumbnail').value = document.getElementById('blogThumbnail_'+ mediaId).src;// thumbnail image shown in the image section
                if(thumbNailElementSelected != null) {
                    document.getElementById(thumbNailElementSelected.id).innerHTML = 'Pick it as thumbnail of the '+pageTypeText;
                }
                thumbNailElementSelected = thumbnailElement;
            }
        } else {
            var toggleFlag = confirm('This will remove this image as thumbnail for the '+pageTypeText+'! Are you sure to continue?');
            if(toggleFlag) {
                thumbnailElement.innerHTML = 'Pick it as thumbnail of the '+pageTypeText;
                document.getElementById('blogThumbnail').value = '';
                thumbNailElementSelected = null;
            }
        }
    }

    function deleteBlogImage(mediaId) {
        var flag = confirm('You are going to delete an image of this '+pageTypeText+'. Press Ok to proceed!');
        if(flag) {
            document.getElementById('imageContainer_'+mediaId).parentNode.removeChild(document.getElementById('imageContainer_'+mediaId));
            document.getElementById('blogImage_'+ mediaId).parentNode.removeChild(document.getElementById('blogImage_'+ mediaId));
            document.getElementById('fakeImageContainer').style.height = (document.getElementById('blogImagesContainer').offsetHeight + 30) + 'px';
            alert("The image is successfully deleted.");


            var childNod = $('blogImages').childNodes;
            var childLens = childNod.length;
            if(childLens == 2)
            document.getElementById('blogThumbnail').value = '';
            setImageContainer();
        }
    }
</script>
