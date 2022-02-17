<div id="subcategoryDiv" onmouseover="displaySubCategory();" onmouseout="hideSubcategotyOverlay();" class="subCategoryOverlay" style="z-index:1100;">
	<div class="inline-l subOptionArrow" style="position:relative;top:0px;left:-16px;"></div>
	<div  class="inline-l" id="subcategoryDivContent" style="padding:5px 5px 5px 0px;"></div>
	<div class="clear_L">&nbsp;</div>
</div>
<script>
var previousParentCategorySelected = 1;
function changeHeadingForCategoryPanel(categoryId)
{
	if(document.getElementById('mainHeadingForCategoryPanel')){
		var parentCategoryId = completeCategoryTree[categoryId][1];
		if(parentCategoryId > 1){
			showCategorySelected(parentCategoryId);
			document.getElementById('mainHeadingForCategoryPanel').innerHTML = completeCategoryTree[parentCategoryId][0]+' : '+completeCategoryTree[categoryId][0];
		}else{
			document.getElementById('mainHeadingForCategoryPanel').innerHTML = completeCategoryTree[categoryId][0];
		}
	}
}
function showCategorySelected(categoryId)
{
	if(document.getElementById('parentCat'+categoryId)){	
		document.getElementById('parentCat'+previousParentCategorySelected).className="";
		document.getElementById('parentCat'+previousParentCategorySelected).parentNode.className="";
		previousParentCategorySelected = categoryId;
		document.getElementById('parentCat'+categoryId).className="selected";
		document.getElementById('parentCat'+categoryId).parentNode.className="selected";
	}
}
function displaySubCategory(objElement, categoryId){
	if(categoryId == 1)
		return;
	
	var overlayElement = document.getElementById('subcategoryDiv');
	overlayElement.style.display = 'block';
	if(objElement) {
		objElement = document.getElementById(objElement);
		overlayElement.style.top = obtainPostitionY(objElement) -5 +'px';
		var leftPosition = obtainPostitionX(objElement);
		if((document.body.offsetWidth-300) < leftPosition)
		{
			overlayElement.style.left = (leftPosition+100) +'px';
		}else{
			overlayElement.style.left = leftPosition+ (objElement.offsetWidth+15) +'px';
		}
		if(categoryId) {
			document.getElementById('subcategoryDivContent').innerHTML = getSubCategoryOverlayContent(categoryId);
		}
	}
	document.getElementById('subcategoryDiv').style.width = document.getElementById('subcategoryDivContent').offsetWidth +'px';
	overlayHackLayerForIE('subcategoryDiv', overlayElement);
}

function hideSubcategotyOverlay(objElement){
	objElement = 'subcategoryDiv';
	document.getElementById(objElement).style.display = 'none';
	dissolveOverlayHackForIE();
}
function getSubCategoryOverlayContent(parentCategoryId)
{
	var subCategoryHtml = '';
	var catergoryHtml = '';
	for(catId in completeCategoryTree)
	{
		if(parentCategoryId == completeCategoryTree[catId][1]){
			var categoryRow =  '<div style="margin-bottom:5px;"><a style="white-space:nowrap;display:block;padding:0 5px" href="#" title="'+completeCategoryTree[catId][0]+'" onClick="categoryChanged(\''+completeCategoryTree[catId][0]+'\','+catId+');changeHeadingForCategoryPanel('+catId+');return false;">'+completeCategoryTree[catId][0]+'</a></div>';
			if(completeCategoryTree[catId][0].toLowerCase().indexOf('other') == 0) {
				catergoryHtml += categoryRow;
			} else {
				subCategoryHtml += categoryRow;
			}
		}
	}
	catergoryHtml = subCategoryHtml + catergoryHtml;
	return catergoryHtml;
}

function showHideOption(){
	var getClassName=document.getElementById('refineCategoryValueId').className;
	if(getClassName=='bgBrowseByCategory displayNone'){
		document.getElementById('refineCategoryValueId').className='bgBrowseByCategory displayBlock';
		document.getElementById('linkChange').innerHTML='<a href="javascript:void(0);" onclick="showHideOption()" style="text-decoration:none;">Close <img id="showCloseImg" src="/public/images/searchRefineOpen.gif" border="0" align="absmiddle"></a>';
	} else {
		document.getElementById('refineCategoryValueId').className='bgBrowseByCategory displayNone';
		document.getElementById('linkChange').innerHTML='<a href="javascript:void(0);" onclick="showHideOption()" style="text-decoration:none;">Refine <img id="showCloseImg" src="/public/images/searchRefineClose.gif" border="0" align="absmiddle"></a>';
	}
}
</script>
<!-- start of browse by category section -->
<div class="lineSpace_10">&nbsp;</div>
<div class="bgBrowseByAskDiscussion displayBlock"  style="margin:0 10px">
				<div>
					<div class="lineSpace_5">&nbsp;</div>
					<div style="width:75%;line-height:20px" class="bld float_L">&nbsp; &nbsp;Category: <span class="OrgangeFont"><span id="mainHeadingForCategoryPanel">All</span></span></div>
					<div style="width:24%;" class="float_L txt_align_r" id="linkChange"><a href="javascript:void(0);" onclick="showHideOption()" style="text-decoration:none;">Close <img id="showCloseImg" src="/public/images/searchRefineOpen.gif" border="0" align="absmiddle"></a></div>
					<div class="clear_L"></div>
				</div>
				<div class="lineSpace_5">&nbsp;</div>
				<div style="margin:0 17px;" id="refineCategoryValueId" class="bgBrowseByCategory displayBlock">
                        <ul class = "browseCitiesAskDiscussion">
							<li  class="selected" onmouseover="displaySubCategory('parentCat1',1);" onmouseout="hideSubcategotyOverlay();"><a href="#" id="parentCat1" onClick="categoryChanged('All',1);showCategorySelected(1);changeHeadingForCategoryPanel(1);return false;" class="selected">All</a></li>
							<li onmouseover="displaySubCategory('parentCat12',12);" onmouseout="hideSubcategotyOverlay();"><a href="#" id="parentCat12" onClick="categoryChanged('Animation, Multimedia',12);showCategorySelected(12);changeHeadingForCategoryPanel(12);return false;" >Animation, Multimedia</a></li>
							<li onmouseover="displaySubCategory('parentCat9',9);" onmouseout="hideSubcategotyOverlay();"><a href="#" id = "parentCat9" onClick="categoryChanged('Arts, Law and Languages',9);showCategorySelected(9);changeHeadingForCategoryPanel(9);return false;">Arts, Law and Languages</a></li>
							<li onmouseover="displaySubCategory('parentCat4',4);" onmouseout="hideSubcategotyOverlay();"><a href="#" id = "parentCat4" onClick="categoryChanged('Banking and Finance',4);showCategorySelected(4);changeHeadingForCategoryPanel(4);return false;">Banking and Finance</a></li>
							<li onmouseover="displaySubCategory('parentCat6',6);" onmouseout="hideSubcategotyOverlay();"><a href="#" id = "parentCat6" onClick="categoryChanged('Hospitality, Tourism and Aviation',6);showCategorySelected(6);changeHeadingForCategoryPanel(6);return false;">Hospitality,Tourism and Aviation</a></li>
							<li onmouseover="displaySubCategory('parentCat10',10);" onmouseout="hideSubcategotyOverlay();"><a href="#" id = "parentCat10" onClick="categoryChanged('Information Technology',10);showCategorySelected(10);changeHeadingForCategoryPanel(10);return false;">Information Technology</a></li>
							<li onmouseover="displaySubCategory('parentCat3',3);" onmouseout="hideSubcategotyOverlay();"><a href="#" id = "parentCat3" onClick="categoryChanged('Management and Business',3);showCategorySelected(3);changeHeadingForCategoryPanel(3);return false;">Management and Business</a></li>
							<li onmouseover="displaySubCategory('parentCat7',7);" onmouseout="hideSubcategotyOverlay();"><a href="#" id = "parentCat7" onClick="categoryChanged('Media, Films, Mass Communications',7);showCategorySelected(7);changeHeadingForCategoryPanel(7);return false;">Media,Films,Mass Comm</a></li>
							<li onmouseover="displaySubCategory('parentCat5',5);" onmouseout="hideSubcategotyOverlay();"><a href="#" id = "parentCat5" onClick="categoryChanged('Medicine and Health Care',5);showCategorySelected(5);changeHeadingForCategoryPanel(5);return false;">Medicine and Health Care</a></li>
							<li onmouseover="displaySubCategory('parentCat8',8);" onmouseout="hideSubcategotyOverlay();"><a href="#" id = "parentCat8" onClick="categoryChanged('Professional Courses',8);showCategorySelected(8);changeHeadingForCategoryPanel(8);return false;">Professional Courses</a></li>
							<li onmouseover="displaySubCategory('parentCat11',11);" onmouseout="hideSubcategotyOverlay();"><a href="#" id = "parentCat11" onClick="categoryChanged('Retail',11);showCategorySelected(11);changeHeadingForCategoryPanel(11);return false;">Retail</a></li>
							<li onmouseover="displaySubCategory('parentCat2',2);" onmouseout="hideSubcategotyOverlay();"><a href="#" id = "parentCat2" onClick="categoryChanged('Science and Engineering',2);showCategorySelected(2);changeHeadingForCategoryPanel(2);return false;">Science and Engineering</a></li>
							<li onmouseover="displaySubCategory('parentCat149',149);" onmouseout="hideSubcategotyOverlay();"><a href="#" id = "parentCat149" onClick="categoryChanged('Miscellaneous',149);showCategorySelected(149);changeHeadingForCategoryPanel(149);return false;">Miscellaneous</a></li>
						</ul>
						<div class="clear_L lineSpace_5">&nbsp;</div>
				</div>
</div>

<script>
<?php if($categoryId != 1){ ?>
function showCategorySelectedOnPageLoad(){
	var parentCategoryId = completeCategoryTree[<?php echo $categoryId; ?>][1];
	var categoryId = <?php echo $categoryId; ?>;
	if(parentCategoryId == 1){
		document.getElementById('mainHeadingForCategoryPanel').innerHTML = completeCategoryTree[categoryId][0];
		showCategorySelected(categoryId);
	}else{
		document.getElementById('mainHeadingForCategoryPanel').innerHTML = completeCategoryTree[parentCategoryId][0]+' : '+completeCategoryTree[categoryId][0];
		showCategorySelected(parentCategoryId);
	}
}
showCategorySelectedOnPageLoad();
<?php } ?>
</script>
