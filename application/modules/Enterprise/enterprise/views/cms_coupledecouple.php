<?php
$headerComponents = array(
        'css'	=>	array('headerCms','raised_all','mainStyle','footer','cal_style'),
        'js'	=>	array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog','header','cityList'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'	=>	'',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'	=>''
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
?>
   <div id="dataLoaderPanel" style="position:absolute;display:none">
      <img src="/public/images/loader.gif"/>
   </div>
<!--Start_Center-->
<div class="mar_full_10p">
       <?php $this->load->view('enterprise/cmsTabs'); ?>
       <?php $this->load->view('enterprise/subtabs'); ?>

       <div class="lineSpace_20">&nbsp;</div>
       <form id = "searchshoskeles" onsubmit = "showcoupleddecoupledlistings();return false;" novalidate>
       <div style="float:left; width:100%">
       <div>
        Enter Client Id:&nbsp; 
	    <input type = "text" id = "categorysponsorclientid" value = "<?php echo $clientId;?>" validate = "validateStr" required = "true" caption = "clientid" minlength = "1" maxlength = "10"/>

       </div>
			<div class="clear_L"></div>
			<div style="margin-top:2px;">
				<div class="errorMsg" style="padding-left:85px" id= "categorysponsorclientid_error"></div>
			</div>
       <div class="lineSpace_10">&nbsp;</div>
       <?php //global $countries;?>
       <div>
        Country Id:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <select id="countryofcoupledecouple" onChange = "showcitiesdd(this.value);" validate = "validateSelect" required = "true" caption = "country">
        <option value = "">Select</option>
											<?php
											foreach($countries as $countryObj){
											  if($countryObj['countryId']>1){ //1 is the id for "All"
										       ?> 
											<option value = "<?php echo $countryObj['countryId']?>" ><?php echo $countryObj['name']?></option>
										       <?php
											  }
											}
											?>
        </select>
       </div>
			<div class="clear_L"></div>
			<div style="margin-top:2px;">
				<div class="errorMsg" style="padding-left:85px" id= "countryofcoupledecouple_error"></div>
			</div>
                <div class="lineSpace_10">&nbsp;</div>
       <div style="display:none;" id="citiesdd">
        City Id:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <select id="citiesofcoupledecouple" onChange="document.getElementById('hdcityId').value=this.value;" caption = "city" style ="display:none">
											<option value = ''>Select</option>
                                                                                        <option value = '1'>All cities</option>
											<?php
                                            for($j = 0;$j < count($cities); $j++) {?> 
											<option value = "<?php echo $cities[$j]['cityId']?>" title="<?php echo $cities[$j]['cityName']?>"><?php echo $cities[$j]['cityName']?></option>				
											<?php } ?>
        </select>
            <div style="margin-top:2px;">
			   <div class="errorMsg" style="padding-left:85px" id= "citiesofcoupledecouple_error"></div>
            </div>
	   </div>
			   <div id="OR1" style="display:none;margin-top:10px;margin-left:120px;font-weight:bold;margin-bottom: 10px;">
				<div class="lineSpace_10" >OR</div>
				</div>
				<div class="row" id= "statesdd" style = "display:none">
                                    <div>
                    State:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <select id="statesofcoupledecouple" style = "display:none" name = "statesofcoupledecouple" caption = "state">
											<option value = ''>Select</option>
											<?php
                                            foreach($states as $state) {if($state['stateId']> 0){?>
											<option value = "<?php echo $state['stateId']?>" title="<?php echo $state['stateName']?>"><?php echo $state['stateName']?></option>
											<?php }} ?>
                    </select>
                    </div>
				</div>
			<div>
				<div id="statesofcoupledecouple_error" class="errorMsg" style="padding-left:92px"></div>
			</div>
				<div class="lineSpace_10">&nbsp;</div>

                <!--  /Category Type Selector -->
       <div id = "categorydiv">
        Category:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <select id = "nationalcategoryselector" caption = "category">
	    <?php// global $categoryParentMap;?>
	    <option value = "">Select</option>
	    <?php
	    foreach($nationalMainCategoryList as $nationalMainCategory) {
	       echo $nationalMainCategory['name'];
	       if($nationalMainCategory['id'] == $selectedcategoryId){ ?>
		   <option selected = 'true' value = "<?php echo $nationalMainCategory['id'];?>"><?php echo $nationalMainCategory['name'];?></option>
	       <?php
	       } else { ?>
		   <option value = "<?php echo $nationalMainCategory['id'];?>" ><?php echo $nationalMainCategory['name'];?></option>
	       <?php
	       }
	    } ?>
        </select>
	<select id = "studyabroadcategoryselector" caption = "category">
	    <?php// global $categoryParentMap;?>
	    <option value = "">Select</option>
	    <?php foreach($saMainCategoryList as $saMainCategory) { 
	    if($saMainCategory['id'] == $selectedcategoryId){ ?>
	       	<option selected = 'true' value = "<?php echo $saMainCategory['id'];?>"><?php echo $saMainCategory['name'];?></option>
	    <?php } else { ?>
		<option value = "<?php echo $saMainCategory['id']?>" ><?php echo $saMainCategory['name']?></option>
	    <?php } } ?>
        </select>
       </div>
       
       <div style="margin-top:2px;">
				<div class="errorMsg" style="padding-left:85px" id= "categoryselector_error"></div>
			</div>
       
       <div class="lineSpace_10">&nbsp;</div>
       <div id="courseLevelSelectorDiv" style="display: none">
	 Course Level:&nbsp;&nbsp;&nbsp;
	 <select onChange = "changeCoupleDecoupleId('courseLevelSelector','hdcourseLevelSelector')" id="courseLevelSelector" caption="course_level">
	    <option value="" >Select</option>
	    <?php foreach($courseLevels as $courseLevel){?>
	       <option <?php echo ($userSelectCourseLevel==$courseLevel['CourseName'])?"Selected='true'":""?> value="<?=$courseLevel['CourseName']?>"><?=$courseLevel['CourseName']?></option>
	    <?php }?>
	 </select>
       </div>
       <div class="errorMsg" style="padding-left:85px" id= "courseLevelSelectorDiv_error"></div>



		<div id="OR2" style="display:none;margin-top:10px;margin-left:120px;font-weight:bold;margin-bottom: 10px;">
				<div class="lineSpace_10" >OR</div>
		</div>
		<div class="row" id = "subcategoriesdiv" style="display:none">
				<div class="formInput" id="categoryPlace" style="display:inline">Sub Category:&nbsp;&nbsp;</div>
				<div class="formInput normaltxt_11p_blk_verdana mb5" id="c_categories_combo" style="display:inline"></div>
		</div>
		<div>
		<div id="subcategoryselector_error" class="errorMsg" style="padding-left:92px"></div>
		</div>
		<div class="lineSpace_10" >&nbsp;</div>
		<!-- category select end -->			
		<script>
		var completeCategoryTree = eval(<?php echo $categoryList; ?>);
		getCategories(true,"c_categories_combo","subcategoryselector","subcategoryselector",false,false,'forBanners');
		</script>


       <div class="lineSpace_10">&nbsp;</div>
        
       <input type = "hidden" value = "" id = "hdlistinglinkids"/>
       <input type = "hidden" value = "" id = "hdbannerlinkids"/>
       <input type = "hidden" value = "<?php echo $clientId;?>" id = "hdclientid"/>
       <input type = "hidden" value = "<?php echo $categoryId;?>" id = "hdcategoryId"/>
	   <input type = "hidden" value = "<?php echo $subcategoryId;?>" id = "hdsubcategoryId"/>
       <input type = "hidden" value = "<?php echo $countryId;?>" id = "hdcountryId"/>
       <input type = "hidden" value = "<?php echo $cityId;?>" id = "hdcityId"/>
	   <input type = "hidden" value = "<?php echo $stateId;?>" id = "hdstateId"/>
       <input type = "hidden" value = "<?php echo $cat_type;?>" id = "hdcattype"/>
       <input type = "hidden" value = "<?php echo $userSelectCourseLevel?>" id = "hdcourseLevelSelector"/>

        <input type = "hidden" value = "asc" id = "sortorder" name = "sortorder" value = "<?php echo $sortorder;?>"/>
        <button class="btn-submit7 w6" name="filterMedia" id="filterMedia" value="Filter_MediaData_CMS" type="button" style="margin-left:10px;width:250px" onClick = "showcoupleddecoupledlistings();">
              <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Search Shoshkeles &amp; Sticky Listing</p></div>
	   </button>
       </div>
       </form>


                            <div class="lineSpace_20">&nbsp;</div>
<?php 
$coupledarray = $arrofinstitutes['coupledarray'];
$listingsarray = $arrofinstitutes['listingsarray'];
$bannerarray = $arrofinstitutes['bannerarray'];
if(count($coupledarray) > 0)
{
?>

								<div class="boxcontent_lgraynoBG bld">
                Coupled:
                </div>
                            <div class="lineSpace_10">&nbsp;</div>
                                                        <div class="row normaltxt_11p_blk">	
                                    <input type="hidden" id="methodName" value="getPopularNetworkCMSajax"/>             
<div id="paginataionPlace1" style="display:none;"></div>
								<div class="boxcontent_lgraynoBG bld">
									<div class="float_L" style="background-color:#D1D1D3; width:33%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Shoshkele
                                    </div>
									<div class="float_L" style="background-color:#EFEFEF; width:33%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Sticky Listing Id</div>
									<div class="float_L" style="background-color:#EFEFEF; width:33%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;</div>
									<div class="clear_L"></div>
								</div>
								
                            <div class="lineSpace_10">&nbsp;</div>
            
<?php
for($i = 0;$i<count($coupledarray);$i++)
{
$couplingid = $coupledarray[$i]['couplingid'];
$bannername = $coupledarray[$i]['bannername'];
$listingId = $coupledarray[$i]['institute_id'];
$listingsubsid = $coupledarray[$i]['listingsubsid'];
$bannerlinkid = $coupledarray[$i]['bannerlinkid'];
?>
            <div class="normaltxt_11p_blk" style="border-bottom:1px solid #999999;cursor:pointer">
                <div class="float_L" style="width:33%;">
                    <div class="mar_full_10p">
                    <div class="lineSpace_10">&nbsp;</div>
                    <div><?php echo $bannername;?></div>
                        <div class="clear_L"></div>
                    </div>
                </div>
                <div class="float_L" style="width:33%;">
                    <div class="mar_full_10p">
                        <div class="lineSpace_10">&nbsp;</div>
                        <div><?php echo $listingId;?></div>
                        <div class="clear_L"></div>
                    </div>
                </div>
                <div class="float_L" style="width:34%;">
                    <div class="mar_full_10p">
                        <div class="lineSpace_10">&nbsp;</div>
                        <div><a href = "#" onClick = "document.getElementById('hdlistinglinkids').value = '<?php echo $listingsubsid;?>';document.getElementById('hdbannerlinkids').value = '<?php echo $bannerlinkid;?>';changethecouplingstatus('decouple');">Decouple</a></div>
                        <div class="clear_L"></div>
                    </div>
                </div>

                </div>
                            <div class="lineSpace_20">&nbsp;</div>
<?php 
} 
}
?>
								<div class="boxcontent_lgraynoBG bld">
			<div style="margin-top:2px;">
				<div class="errorMsg" style="padding-left:30px" id= "coupledecouple_error"></div>
			</div>
                Decoupled:
                </div>
                            <div class="lineSpace_10">&nbsp;</div>
                                <div class="boxcontent_lgraynoBG bld">
									<div class="float_L" style="background-color:#D1D1D3; width:33%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Shoshkele
                                    </div>
									<div class="float_L" style="background-color:#EFEFEF; width:33%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Sticky Listing Id</div>
									<div class="float_L" style="background-color:#EFEFEF; width:33%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;</div>
									<div class="clear_L"></div>
								</div>
							</div>

            <div class="normaltxt_11p_blk" style="border-bottom:1px solid #999999;cursor:pointer" onClick="" >
                <div class="float_L" style="width:33%;">
                    <div class="mar_full_10p">
                    <div class="lineSpace_10">&nbsp;</div>
                    <div><select onChange = "changeCoupleDecoupleId('bannerlinkids','hdbannerlinkids')" id = "bannerlinkids">
                    <option value = ''>
                    Shoshkele Name
                    </option>
                    <?php for($i = 0;$i<count($bannerarray);$i++)
                    {
                    $bannername = $bannerarray[$i]['bannername'];
                    $bannerlinkid = $bannerarray[$i]['bannerlinkid'];
		    ?>
                    <option value = "<?php echo $bannerlinkid;?>">
                    <?php echo $bannername; ?>
                    </option>
                    <?php } ?>
                    </select></div>
                    
                        <div class="clear_L"></div>
                    </div>
                </div>
                <div class="float_L" style="width:33%;">
                    <div class="mar_full_10p">
                        <div class="lineSpace_10">&nbsp;</div>
                    <div><select onChange = "changeCoupleDecoupleId('listinglinkids','hdlistinglinkids')" id = "listinglinkids"> 
                    <option value = ''>
                    Listing Id
                    </option>
                    <?php for($i = 0;$i<count($listingsarray);$i++)
                    {
                    $listingid = $listingsarray[$i]['listingsubsid'];
                    $institute_id = $listingsarray[$i]['institute_id'];
                    ?>
                    <option value = "<?php echo $listingid;?>">
                    <?php echo $institute_id; ?>
                    </option>
                    <?php } ?>
                    </select></div>
                        <div class="clear_L"></div>
                    </div>
                </div>
                <div class="float_L" style="width:34%;">
                    <div class="mar_full_10p">
                        <div class="lineSpace_10">&nbsp;</div>
                        <div><a href = "#" onClick = "changethecouplingstatus('couple')">Couple</a></div>
                        <div class="clear_L"></div>
                    </div>
                </div>

                </div>
                            <div class="lineSpace_20">&nbsp;</div>
						<!-- code for pagination start -->

<script>
document.getElementById('countryofcoupledecouple').value = '<?php echo $countryId;?>';
document.getElementById('nationalcategoryselector').value = '<?php echo $categoryId;?>';
document.getElementById('studyabroadcategoryselector').value = '<?php echo $categoryId;?>';
document.getElementById('categorydiv').style.display='none';
document.getElementById('nationalcategoryselector').style.display='none';
document.getElementById('studyabroadcategoryselector').style.display='none';
if (document.getElementById('countryofcoupledecouple').value == "") {
   document.getElementById('categorydiv').style.display='none';
   document.getElementById('nationalcategoryselector').style.display='none';
   document.getElementById('citiesdd').style.display = 'none';
   document.getElementById('citiesofcoupledecouple').style.display = 'none';
   document.getElementById('statesdd').style.display = 'none';
   document.getElementById('statesofcoupledecouple').style.display = 'none';
   document.getElementById('studyabroadcategoryselector').style.display = 'none';
   document.getElementById('courseLevelSelectorDiv').style.display='none';
   document.getElementById('subcategoriesdiv').style.display = 'none';
   document.getElementById('subcategoryselector').style.display = 'none';
   document.getElementById('OR1').style.display = 'none';
   document.getElementById('OR2').style.display = 'none';
   
}
else if(document.getElementById('countryofcoupledecouple').value == 2)
{
   document.getElementById('categorydiv').style.display='';
   document.getElementById('nationalcategoryselector').style.display='';
   document.getElementById('citiesdd').style.display = '';
   document.getElementById('citiesofcoupledecouple').style.display = '';
   document.getElementById('statesdd').style.display = '';
   document.getElementById('statesofcoupledecouple').style.display = '';
   document.getElementById('studyabroadcategoryselector').style.display = 'none';
   document.getElementById('courseLevelSelectorDiv').style.display='none';
   document.getElementById('subcategoriesdiv').style.display = '';
   document.getElementById('subcategoryselector').style.display = '';
   document.getElementById('OR1').style.display = '';
   document.getElementById('OR2').style.display = '';
   document.getElementById('nationalcategoryselector').value = '<?php echo $categoryId?$categoryId:'';?>';
   document.getElementById('subcategoryselector').value = '<?php echo $subcategoryId?$subcategoryId:'';?>';
   document.getElementById('citiesofcoupledecouple').value = '<?php echo $cityId?$cityId:'';?>';
   document.getElementById('statesofcoupledecouple').value = '<?php echo $stateId?$stateId:'';?>';
}
else{
   document.getElementById('categorydiv').style.display='';
   document.getElementById('nationalcategoryselector').style.display='none';
   document.getElementById('citiesdd').style.display = 'none';
   document.getElementById('citiesofcoupledecouple').style.display = 'none';
   document.getElementById('statesdd').style.display = 'none';
   document.getElementById('statesofcoupledecouple').style.display = 'none';
   document.getElementById('studyabroadcategoryselector').style.display = '';
   document.getElementById('courseLevelSelectorDiv').style.display='';
   document.getElementById('subcategoriesdiv').style.display = 'none';
   document.getElementById('subcategoryselector').style.display = 'none';
   document.getElementById('OR1').style.display = 'none';
   document.getElementById('OR2').style.display = 'none';
   document.getElementById('studyabroadcategoryselector').value = '<?php echo $categoryId?$categoryId:'';?>';   
}
function showcitiesdd(val)
{
   document.getElementById('nationalcategoryselector').value = '';
   document.getElementById('studyabroadcategoryselector').value = '';
   document.getElementById('subcategoryselector').value = '';
   document.getElementById('citiesofcoupledecouple').value = '';
   document.getElementById('statesofcoupledecouple').value = '';
   document.getElementById('courseLevelSelectorDiv').value='All';
   if (val == "") { //This is when the user has selected "Select" option
      document.getElementById('categorydiv').style.display='none';
	 document.getElementById('nationalcategoryselector').style.display='none';
	 document.getElementById('citiesdd').style.display = 'none';
	 document.getElementById('citiesofcoupledecouple').style.display = 'none';
	 document.getElementById('statesdd').style.display = 'none';
	 document.getElementById('statesofcoupledecouple').style.display = 'none';
	 document.getElementById('categorydiv').style.display = 'none';
	 document.getElementById('subcategoryselector').style.display = 'none';
	 document.getElementById('subcategoriesdiv').style.display = 'none';
	 document.getElementById('OR1').style.display = 'none';
	 document.getElementById('OR2').style.display = 'none';
	 document.getElementById('studyabroadcategoryselector').style.display='none';
	 document.getElementById('courseLevelSelectorDiv').style.display='none';
   }
   else if(val == 2){
	 document.getElementById('categorydiv').style.display='';
	 document.getElementById('nationalcategoryselector').style.display='';
	 document.getElementById('citiesdd').style.display = '';
	 document.getElementById('citiesofcoupledecouple').style.display = '';
	 document.getElementById('statesdd').style.display = '';
	 document.getElementById('statesofcoupledecouple').style.display = '';
	 document.getElementById('categorydiv').style.display = '';
	 document.getElementById('subcategoryselector').style.display = '';
	 document.getElementById('subcategoriesdiv').style.display = '';
	 document.getElementById('OR1').style.display = '';
	 document.getElementById('OR2').style.display = '';
	 document.getElementById('studyabroadcategoryselector').style.display='none';
	 document.getElementById('courseLevelSelectorDiv').style.display='none';
   }
   else
   {
	 document.getElementById('categorydiv').style.display='';
	 document.getElementById('studyabroadcategoryselector').style.display='';
	 document.getElementById('courseLevelSelectorDiv').style.display='';
	 document.getElementById('courseLevelSelector').value = '';
	 
	 document.getElementById('nationalcategoryselector').style.display='none';
	 document.getElementById('citiesdd').style.display = 'none';
	 document.getElementById('citiesofcoupledecouple').style.display = 'none';
	 document.getElementById('statesdd').style.display = 'none';
	 document.getElementById('statesofcoupledecouple').style.display = 'none';
	 document.getElementById('subcategoryselector').style.display = 'none';
	 document.getElementById('subcategoriesdiv').style.display = 'none';
	 document.getElementById('OR1').style.display = 'none';
	 document.getElementById('OR2').style.display = 'none';
   }
   document.getElementById('hdcountryId').value = val;
}

function selectanduse(bannerid)
{
    showOverlay(535,600,'Select & use shoshkele',document.getElementById('selectnduseshoshkele').innerHTML);
    document.getElementById('bannerIdsnu').value = bannerid; 
}

function changeshoshkele(headertext,bannerid)
{
    showOverlay(535,600,headertext,document.getElementById('uploadshoshkele').innerHTML);
    if(headertext == "Change Shoshkele")
    {
        document.getElementById('bannerId').value = bannerid; 
    }
    //alert(1); 
    //$j('#dim_bg').css('height','100% !important'); 
    //alert(2);
}

function changetheorder()
{
    if(document.getElementById('ordername').alt == 'asc')
    {
        document.getElementById('sortorder').value = 'desc';
        document.getElementById('ordername').setAttribute('alt','desc');
        document.getElementById('ordername').setAttribute('src','/public/images/arrow_down.png');
    }
    else
    {
        document.getElementById('sortorder').value = 'asc';
        document.getElementById('ordername').setAttribute('alt','asc');
        document.getElementById('ordername').setAttribute('src','/public/images/arrow_up.png');
    }
}

function changeCoupleDecoupleId(selectid,hiddenid)
{
    document.getElementById(hiddenid).value = document.getElementById(selectid).value;
}

function changethecouplingstatus(statusval)
{
if((document.getElementById('bannerlinkids').value == '' || document.getElementById('listinglinkids').value == '') && statusval == "couple")
{
document.getElementById('coupledecouple_error').innerText = 'Please select shoshkele and listing to be coupled';
document.getElementById('coupledecouple_error').style.display = '';
return false;
}
document.getElementById('coupledecouple_error').innerText = '';
document.getElementById('coupledecouple_error').style.display = 'none';
window.location = '/enterprise/Enterprise/cmscoupledecouple/' + document.getElementById('hdclientid').value + "/" + document.getElementById('hdcategoryId').value + "/" + document.getElementById('hdsubcategoryId').value + "/" + document.getElementById('hdcountryId').value + "/" + document.getElementById('hdcityId').value + "/" + document.getElementById('hdstateId').value + "/" + document.getElementById('hdcattype').value + "/" + document.getElementById('hdcourseLevelSelector').value + "/"+ statusval + "/" + document.getElementById('hdlistinglinkids').value + "/" + document.getElementById('hdbannerlinkids').value ;
}

function showcoupleddecoupledlistings()
{
   var varr = validateFields(document.getElementById('searchshoskeles'));
   if(document.getElementById('countryofcoupledecouple').value == 2){
	  if(document.getElementById('subcategoryselector').value && document.getElementById('nationalcategoryselector').value){
		  document.getElementById('subcategoryselector_error').parentNode.style.display = '';
		  document.getElementById('subcategoryselector_error').innerText = "Please select either a category or subcategory.";
		  varr = false
	  }
	  if(!(document.getElementById('subcategoryselector').value) && !(document.getElementById('nationalcategoryselector').value)){
		  document.getElementById('subcategoryselector_error').parentNode.style.display = '';
		  document.getElementById('subcategoryselector_error').innerText = "Please select a category or subcategory.";
		  varr = false
	  }
	  if(document.getElementById('statesofcoupledecouple').value && document.getElementById('citiesofcoupledecouple').value){
		  document.getElementById('statesofcoupledecouple_error').parentNode.style.display = '';
		  document.getElementById('statesofcoupledecouple_error').innerText = "Please select either a city or state.";
		  varr = false
	  }
	  if(!(document.getElementById('statesofcoupledecouple').value) && !(document.getElementById('citiesofcoupledecouple').value)){
		  document.getElementById('statesofcoupledecouple_error').parentNode.style.display = '';
		  document.getElementById('statesofcoupledecouple_error').innerText = "Please select a city or state.";
		  varr = false
	  }
   }else{ //we're in studyabroad, or default
	  if(!document.getElementById('studyabroadcategoryselector').value && document.getElementById('countryofcoupledecouple').value!= ""){
		  document.getElementById('categoryselector_error').parentNode.style.display = '';
		  document.getElementById('categoryselector_error').innerText = "Please select a category";
		  varr = false
	  }else{
	    document.getElementById('categoryselector_error').parentNode.style.display = 'none';
	    //document.getElementById('categoryselector_error').innerText = "Please select a category";
	  }
	  if (document.getElementById('courseLevelSelectorDiv').style.display=="" && document.getElementById('courseLevelSelector').value=="") {
	    document.getElementById('courseLevelSelectorDiv_error').style.display = '';
	    document.getElementById('courseLevelSelectorDiv_error').innerText = "Please select a course level";
	    varr = false;
	  }else{
	    document.getElementById('courseLevelSelectorDiv_error').style.display = 'none';
	  }
   }
    if(varr === false)
    return false;
    var countryIdcs = document.getElementById('countryofcoupledecouple').value;
    var citIdcs = 0;
	var stateIds = 0;
    if(countryIdcs == 2){
	  if(document.getElementById('citiesofcoupledecouple').style.display == "" && document.getElementById('citiesofcoupledecouple').value)
		 citIdcs = document.getElementById('citiesofcoupledecouple').value;
	  if(document.getElementById('statesofcoupledecouple').style.display == "" && document.getElementById('statesofcoupledecouple').value)
		 stateIds = document.getElementById('statesofcoupledecouple').value;
	}
  
   var catval = 0;
   var subcatval = 0;
   if (document.getElementById('countryofcoupledecouple').value == 2){
      if(document.getElementById('nationalcategoryselector').style.display == "" && document.getElementById('nationalcategoryselector').value)
      {
	  catval = document.getElementById('nationalcategoryselector').value;
      }
   }
   else{
      if(document.getElementById('studyabroadcategoryselector').style.display == "" && document.getElementById('studyabroadcategoryselector').value)
      {
	  catval = document.getElementById('studyabroadcategoryselector').value;
      }
   }
    
   if(document.getElementById('subcategoryselector').style.display == "" && document.getElementById('subcategoryselector').value)
    {
		subcatval = document.getElementById('subcategoryselector').value;
    }
	
	
   var courseLevel = '';
   if(document.getElementById('courseLevelSelector').style.display == "" && document.getElementById('courseLevelSelector').value)
    {
		courseLevel = document.getElementById('courseLevelSelector').value;
    }


    var cat_type = '';
    if (countryIdcs == 2) {cat_type='category';}
    else{cat_type='country';}
    var url = '/enterprise/Enterprise/cmscoupledecouple/' + document.getElementById('categorysponsorclientid').value + "/" + catval + "/" + subcatval + "/" + countryIdcs + "/" + citIdcs + "/" + stateIds + "/" + cat_type + "/" + courseLevel;
    window.location = url;
}

function changeCategory(cat_type)
{
    if(cat_type == 'category')
        {
            document.getElementById('categorydiv').style.display = '';
	    document.getElementById('countryofcoupledecouple')==2?document.getElementById('nationalcategoryselector').style.display = '':document.getElementById('studyabroadcategoryselector').style.display = '';
            document.getElementById('tp_categoriesdiv').style.display = 'none';
            document.getElementById('tp_categorynameasl').style.display = 'none';
        }
        else
            {
            document.getElementById('categorydiv').style.display = 'none';
            document.getElementById('nationalcategoryselector').style.display = 'none';
	    document.getElementById('studyabroadcategoryselector').style.display = 'none';
            document.getElementById('tp_categoriesdiv').style.display = '';
            document.getElementById('tp_categorynameasl').style.display = '';
            }
}

var citiesForCountry = cityList['2'];
var i = 0;
for(var city in citiesForCountry){
    var obj = document.getElementById('citiesofcoupledecouple').getElementsByTagName('option') ;
    var flag = 0;
    var i = 0;
    while(obj[i].value != 'undefined')
    {
        if(obj[i].value != city)
        {
            flag = 1;
            break;
        }
        i++;
    }
    if(flag == 1)
    {
        var optionElement = '';
        var optionElement = document.createElement('option');
        optionElement.value = city;
        optionElement.title = citiesForCountry[city];
        optionElement.innerHTML = citiesForCountry[city] ;
        document.getElementById('citiesofcoupledecouple').appendChild(optionElement);
    }
}
</script>
</div>
