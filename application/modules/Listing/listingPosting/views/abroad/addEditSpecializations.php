<div class="abroad-cms-rt-box">
	<?php
        $displayData['breadCrumb'] = array(
                array('text' => 'All Course Specializations','url' => ENT_SA_CMS_PATH.ENT_SA_VIEW_SPECIALIZATIONS),
                array('text' => (($formData)?'Edit ':'Add New ').'Specialization','url' => '')
            );
        $displayData['pageTitle'] = ($formData)?'Edit Specialization':'Add Specialization';
        if($formData){
	        $displayData["lastUpdatedInfo"] = array(
	        	"title"    => "Last modified",
	        	"date"     => date("d-m-Y",  strtotime($formData['date'])),
				"username" => $validity[0]['firstname']." ".$validity[0]['lastname']
	        );
        }
        $this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
    ?>
    <div class="cms-form-wrapper clear-width">
    	<h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite minus-icon"></i>Create New Specializations</h3>
	    <div class="cms-form-wrap">
	    	<ul>
	    		<li>
	    			<label>Parent Category*:</label>
	    			<div class="cms-fields">
	    				<select class="universal-select cms-field" onchange="setSubcategory(this);" id="parentCategory">
	    					<option value=''>Select a parent category</option>
	    					<?php foreach($categorySubcategoryData as $key => $row){ ?>
	    						<option value="<?=$key?>"><?=$row['name']?></option>
	    					<?php } ?>
	    				</select>
	    			</div>
	    			<div style="display: none" class="errorMsg cms-fields" id="parentCategoryError"></div>
	    		</li>
	    		<li>
	    			<label>Sub Category*:</label>
	    			<div class="cms-fields">
	    				<select class="universal-select cms-field" id="subCategory">
	    					<option value=''>Select a sub category</option>
	    				</select>
	    			</div>
	    			<div style="display: none" class="errorMsg cms-fields" id="subCategoryError"></div>
	    		</li>
	    		<li>
	    			<label>Specialization Name*:</label>
	    			<div class="cms-fields">
	    				<input type="text" class="universal-txt-field cms-text-field" id="specializationName" maxlength="250" />
	    			</div>
	    			<div style="display: none" class="errorMsg cms-fields" id="specializationNameError"></div>
	    		</li>
	    		<li>
	    			<label>Specialization Description:</label>
	    			<div class="cms-fields">
	    				<textarea class="cms-textarea" id="specializationDescription" maxlength="255"></textarea>
	    			</div>
	    			<div style="display: none" class="errorMsg cms-fields" id="specializationDescriptionError"></div>
	    		</li>
	    	</ul>
	    	<?php if(!empty($formData)){ ?>
	    		<input type="hidden" value="<?=$formData['categoryId']?>" id="oldCategoryId" />
	    		<input type="hidden" value="<?=htmlentities($formData['name'])?>" id="oldName" />
	    		<input type="hidden" value="<?=$formData['subcategoryId']?>" id="oldSubcategoryId" />
	    	<?php } ?>
	    </div>
   	</div>
    <div class="button-wrap">
        <a href="javascript:void(0);" class="orange-btn" onclick="submitForm();">Save &amp; Publish</a>
        <a onclick="cancel();" href="javascript:void(0)" class="cancel-btn">Cancel</a>
    </div>
</div>
<script>
	var catSubcatMapping = <?=json_encode($categorySubcategoryData)?>;
	var formdata;

	function setSubcategory(elem){
		var subcatArray;
		if($j(elem).val() != ""){
			subcatArray = catSubcatMapping[$j(elem).val()]['subcategories'];
		}
		var options = '<option value="">Select a sub category</option>';
		for (var key in subcatArray){
			options+="<option value='"+key+"'>"+subcatArray[key]+"</option>";
		}
		$j("#subCategory").html(options);
	}

	function validateSpecializationForm(){
		var isValid = true;
		$j(".errorMsg").each(function(){$j(this).html("").hide();});
		if($j("#parentCategory").val() == ""){
			$j("#parentCategoryError").html("Please select a parent category").show();
			isValid = false;
		}
		if($j("#subCategory").val() == ""){
			$j("#subCategoryError").html("Please select a subcategory").show();
			isValid = false;
		}
		$j("#specializationName").val(trim($j("#specializationName").val()));
		if($j("#specializationName").val() == ""){
			$j("#specializationNameError").html("Please enter a name for this specialization").show();
			isValid = false;
		}
		var name = validateStr($j("#specializationName").val(),"Specialization Name",255,1);
		if(name !== true){
			$j("#specializationNameError").html(name).show();
			isValid = false;
		}
		$j("#specializationDescription").val(trim($j("#specializationDescription").val()));
		if($j("#specializationDescription").val()!=""){
			var description = validateStr($j("#specializationDescription").val(),"Specialization Description",255,0);
			if(description !== true){
				$j("#specializationDescriptionError").html(description).show();
				isValid = false;
			}
		}
		return isValid;
	}

	function prefillForm(){
		if(typeof(formdata) == "undefined"){
			return false;
		}
		$j("#parentCategory").val(formdata.categoryId).trigger("change").attr("disabled","disabled");
		$j("#subCategory").val(formdata.subcategoryId).attr("disabled","disabled");
		$j("#specializationName").val(formdata.name);
		$j("#specializationDescription").html(formdata.courseDetail);
		return true;
	}

	function submitForm(){
		var check = validateSpecializationForm();
		if(!check){
			return false;
		}
		var userData = {
			'oldName' : $j("#oldName").val(),
			'oldCategoryId' : $j("#oldCategoryId").val(),
			'categoryId' : $j("#parentCategory").val(),
			'subcategoryId': $j("#subCategory").val(),
			'name' : $j("#specializationName").val(),
			'description' : $j("#specializationDescription").val(),
			'oldSubcategoryId' : $j("#oldSubcategoryId").val()
		};
		$j.ajax({
			'url' : '/listingPosting/AbroadListingPosting/submitSpecializationForm',
			'type' : 'POST',
			'data' : userData,
			success: function(response){
				response = JSON.parse(response);
				if(response.error == 1){
					alert(response.errorMessage);
				}else{
					preventOnUnload = false;
					alert("Data Successfully Inserted!");
					window.location = "<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_SPECIALIZATIONS?>";
				}

			}
		});
		return true;
	}

	/* Code to handle the unloading and confirmation before unloading of the page. */
	var preventOnUnload = true;	
	var confirmExit = function (){
		if(preventOnUnload){
			return "Any unsaved changes will be lost!";
		}
	}
	window.onbeforeunload = confirmExit;
	function cancel(){
		window.location.href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_SPECIALIZATIONS?>";
	}
</script>