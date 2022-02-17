<?php 
if($page == 'byArticle' || $page == 'SA-Content-byArticleId')
{
    $enableInputField = 1;
    $width = '150px';
    $placeHolderText = 'Enter Article Id';
}
else if($page == 'byDiscussionId')
{
    $width = '150px';
    $enableInputField = 1;
    $placeHolderText = 'Enter Discussion Id';
}
else if($page == 'ActualDelivery')
{
    $width = '150px';
    $enableInputField = 1;
    $placeHolderText = 'Enter Email Id';
}
?>

<div class="row">
     <div class="col-md-12 col-sm-12 col-xs-12">
           <div class="row x_title">
                  <?php if($page == 'byInstitute') { ?>
                         <div class = "col-md-2">
                              <input class="form-control" type="text" name="instituteId" id="instituteId" placeholder="Enter Institute Id" onblur="getCoursesInfo()">                  

                         <input type="checkbox" name="responsesCourseWise"  id="responsesCourseWise" > Show Responses Course Wise<br>
                   <!--  <input type="hidden" name="courseId" id="courseId" value=""/> -->
                         </div>
                 <?php }else if($page == 'byUniversity'){?>
                          <div class = "col-md-2">
                                 <input class="form-control" type="text" name="universityId" id="universityId" placeholder="Enter University Id" onblur="getCoursesInUniversity()">
                                 <input type="checkbox" name="responsesCourseWise"  id="responsesCourseWise" > Show Responses Course Wise<br>
                              </div>
                <?php } if($page == 'byInstitute' || $page == 'byUniversity') { ?>
                        <div class="col-md-2">
                        
                        <div class="dropdown" id="courseDiv">
                                <select id="courseSelect" multiple="multiple" class="fixed_width_155">
                                    
                                </select>
                    </div>
                    <?php if($page == 'byInstitute') {?>
                         <input type="checkbox" name="simi_larflag_check"  id="similar_flag_check" > Similar Courses Report <br>
                   <!--  <input type="hidden" name="courseId" id="courseId" value=""/> -->
                    <?php }?>
                    </div>
                   <?php } else if($page == 'bySubcatId' || $page == 'content-bySubcatId' || $page == 'SA-Content-bySubcatId' || $page == 'NationalDiscussions' || $page == 'bySubcatId_SA'){ ?>
                             <div class="col-md-2">
                        
                        <div class="dropdown" id="subcatDiv">
                                <select id="subcatSelect" multiple="multiple" class="fixed_width_155">    
                        <?php foreach ($subcatId as $key => $value) { ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php } ?>
                                </select>
                    </div>
                    <?php if($page == 'bySubcatId' || $page == 'bySubcatId_SA') {?>
                        <input type="checkbox" name="responsesCourseWise"  id="responsesCourseWise" > Show Responses Course Wise<br>
                        <?php }?>
                </div>
                <?php } if(isset($enableInputField)) {?>
                        <div class = "col-md-2">
                                 <input class="form-control" type="text" name="inputId" id="inputId" placeholder="<?php echo $placeHolderText;?>" onblur="" style="width:<?php echo $width;?>">
                        </div>  
                <?php }?>
              <?php if(isset($authorNames)) { ?>
                        <div class="col-md-2">
                        
                        <div class="dropdown" id="authorDiv">
                                <select id="authorSelect" multiple="multiple" class="fixed_width_155">    
                        <?php foreach ($authorNames as $key => $value) { ?>
                            <option value="<?php echo $value['userid']; ?>"><?php echo $value['firstName'].' '.$value['lastName']; ?></option>
                        <?php } ?>
                                </select>
                             <!-- <ul class="dropdown-menu scrollable-menu courseIdSelect" id="courseIdList" aria-labelledby="CourseIdButton-type" style="
            height: auto;width:auto; max-height: 200px;overflow-x: hidden">
                        </ul>  -->
                    </div>
                </div>
                   
                    <?php } ?>
                    <?php if(isset($countryNames)) {?>
                      <div class="col-md-2">
                        
                        <div class="dropdown" id="countryDiv">
                                <select id="countrySelect" multiple="multiple" class="fixed_width_155">
                                  <?php foreach ($countryNames as $key => $value) { ?>
                            <option value="<?php echo $value['countryId']; ?>"><?php echo $value['countryName']; ?></option>
                        <?php } ?>  
                                </select>
                             <!-- <ul class="dropdown-menu scrollable-menu courseIdSelect" id="courseIdList" aria-labelledby="CourseIdButton-type" style="
            height: auto;width:auto; max-height: 200px;overflow-x: hidden">
                        </ul>  -->
                    </div>
                </div>
                    <?php }?>
                    <!--start-->
                    <?php if( isset($stateNames)) {?>
                    <!-- <div class="col-md-2">
                        <div class="dropdown">
                                    
                        <button class="btn btn-default dropdown-toggle fixed_width_155" type="button" id="countryNameButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Country
                            <?php // listing-type is the name given to the url query string equivalent to 'type' ?>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu scrollable-menu" aria-labelledby="countryName-type" style="
    height: auto;width:auto;
                        
                max-height: 200px;
                overflow-x: hidden">
                            <?php foreach ($countryNames as $key => $value) {?>
                            <li data-dropdown="<?php echo $value['countryName']?>"><a href="#" onclick="getStateNames('<?php echo $value['countryId']?>')"><?php echo $value['countryName'];?></a></li>
                                <?php }?>           
                        </ul>
                    </div>
                    <input type="hidden" name="countryId" id="countryId" value=""/>
                    </div> -->
                    <div class="col-md-2">
                        
                        <div class="dropdown" id="stateDiv">
                                <select id="stateSelect" multiple="multiple" class="fixed_width_155">    
                        <?php foreach ($stateNames as $key => $value) { ?>
                            <option value="<?php echo $value['stateId']; ?>"><?php echo $value['stateName']; ?></option>
                        <?php } ?>
                                </select>
                             <!-- <ul class="dropdown-menu scrollable-menu courseIdSelect" id="courseIdList" aria-labelledby="CourseIdButton-type" style="
            height: auto;width:auto; max-height: 200px;overflow-x: hidden">
                        </ul>  -->
                    </div>
                </div>
                 <div class="col-md-2">
                        
                        <div class="dropdown" id="cityDiv">
                                <select id="citySelect" multiple="multiple" class="fixed_width_155">
                                  <?php foreach ($cityNames as $key => $value) { ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php } ?>  
                                </select>
                             <!-- <ul class="dropdown-menu scrollable-menu courseIdSelect" id="courseIdList" aria-labelledby="CourseIdButton-type" style="
            height: auto;width:auto; max-height: 200px;overflow-x: hidden">
                        </ul>  -->
                    </div>
                </div>
                <?php }?>
                    <!--end-->
                    <?php if(isset($overview) || $page == 'ActualDelivery') {?>
                    <?php } else {?>
                    <div class="col-md-2">
                        <div class="dropdown">
                                    
                        <button class="btn btn-default dropdown-toggle fixed_width_155" type="button" id="DeviceSourceButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Device
                            <?php // listing-type is the name given to the url query string equivalent to 'type' ?>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="DeviceSource-type">
                            <li data-dropdown="Desktop and Mobile"><a href="#" onclick="setSource('')">Desktop and Mobile</a></li>
                            <li data-dropdown="Desktop"><a href="#" onclick="setSource('Desktop')">Desktop</a></li>
                            <li data-dropdown="Mobile"><a href="#" onclick="setSource('Mobile')">Mobile</a></li>
                        </ul>
                    </div>
                    <input type="hidden" name="sourceType" id="sourceType" value=""/>
                    </div>
                    <div class="col-md-2">
                        <div class="dropdown">
                                    
                        <button class="btn btn-default dropdown-toggle fixed_width_155" type="button" id="ResponseTypeButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php echo $paidHeading;?>
                            <?php // listing-type is the name given to the url query string equivalent to 'type' ?>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu"  aria-labelledby="Response-type">
                           <li data-dropdown="Paid and Free"><a href="#" onclick="setResponseType('')">Paid and Free</a></li>
                            <li data-dropdown="Paid"><a href="#" onclick="setResponseType('paid')">Paid</a></li>
                            <li data-dropdown="Free"><a href="#" onclick="setResponseType('free')">Free</a></li>
                        </ul>
                    </div>
                    <input type="hidden" name="responseType" id="responseType" value=""/>
                    </div>
                    <?php }?>
                     <?php if(isset($overview) || $page == 'ActualDelivery') {?>
                                 <div class="col-md-6">
                                    
                                </div>
                                <?php }?>
                                <?php if($page == 'ActualDelivery') {?>
                                        <!-- <div class="col-md-2">
                                        </div> -->
                                <?php }?>
                                <div class="col-md-2">
                                    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding:5px 10px; border: 1px solid #ccc;
    height:40px align='left'" >
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar" style="float:left"></i>
                                        <span ></span> <b class="caret"></b>
                                    </div>
                                </div>
                                <?php if($page == "bySubcatId") {?>
                                 <div class="col-md-8">
                                    
                                </div>
                                <?php }?>
                                <div class="col-md-2" style = "padding-top:5px">
                                    <button class="btn btn-primary source" id="apply-button" onclick="applyFilters(1)">Apply Filters</button>
                                </div>
                            </div>

                            <!-- <div class="col-md-9 col-sm-9 col-xs-12">
                                <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
                                <div style="width: 100%;">
                                    <div id="canvas_dahs" class="demo-placeholder" style="width: 100%; height:270px;"></div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12 bg-white">
                                
                            </div> -->

                            <div class="clearfix"></div>
                            
                            <div class = "col-md-5" style = "float:left">
                                <h6>
                                    <div id = "lineChartRep" style ="display:none;"></div>
                                    <div id = "lineChartRep1" style ="display:none"></div>
                                </h6>
                            </div>
                            <?php if( !isset($overview)){?>
                            
                <div class="" style="float:right;display:none" id="representationWise">
                    <div class="col-md-3">
                    <div class="dropdown">
                        <button class="btn btn-default fixed_width_100 bgcolor" type="button" id="daily" onclick="viewWiseData('1')">
                            Day
                        </button>
                    </div> 
                </div>
                <div class="col-md-3">
                    <div class="dropdown">
                        <button class="btn btn-default fixed_width_100 bgcolor" type="button" id="weekly" onclick="viewWiseData('2')">
                            Week
                        </button>
                    </div> 
                </div>
                <div class="col-md-3">
                    <div class="dropdown">
                        <button class="btn btn-default fixed_width_100 bgcolor" type="button" id="monthly" onclick="viewWiseData('3')">
                            Month
                        </button>
                    </div> 
                </div>
                            </div>
                            <?php }?>
                            <input type="hidden" name="viewWise" id="viewWise" value="1"/>
                    </div>

</div>
<!--start-->
 
<!--end-->
<script type="text/javascript">
var courseButtonExists = document.getElementById('courseId');

if(typeof courseButtonExists != 'undefined')
{
    $('#courseSelect').multiselect();
    var courseChangeText = $('#courseSelect').next('div').find('button').find('span');
            if(courseChangeText.text() == 'choose Options')
            {
                courseChangeText.text('Choose CourseId');
            }
}
var stateDiv = document.getElementById('stateDiv');
if(typeof stateDiv != 'undefined')
    {
          $('#stateSelect').multiselect();
            var stateChangeText = $('#stateSelect').next('div').find('button').find('span');
            if(stateChangeText.text() == 'choose Options')
            {
                stateChangeText.text('Choose StateName');
            }
                               $('.multiselect-container').addClass('overflow_autoCD');
                                $('#stateSelect').multiselect({
                                    buttonWidth: '150px'
                                });
            //alert(123);
    }
var cityDiv = document.getElementById('cityDiv');
if(typeof cityDiv != 'undefined')
{
    $('#citySelect').multiselect();
    var cityChangeText = $('#citySelect').next('div').find('button').find('span');
            if(cityChangeText.text() == 'choose Options')
            {
                cityChangeText.text('Choose CityName');
            }
                               $('.multiselect-container').addClass('overflow_autoCD');
                               //$('.multiselect-container').attr('id','Choose-City');

                                $('#citySelect').multiselect({
                                    buttonWidth: '150px'
                                });
}

var authorDiv = document.getElementById('authorDiv');
if(typeof authorDiv != 'undefined')
    {
          $('#authorSelect').multiselect();
          var authorChangeText = $('#authorSelect').next('div').find('button').find('span');
            if(authorChangeText.text() == 'choose Options')
            {
                authorChangeText.text('Choose Author');
            }
                               $('.multiselect-container').addClass('overflow_autoCD');
                                $('#authorSelect').multiselect({
                                    buttonWidth: '150px'
                                });
    }

var subcatDiv = document.getElementById('subcatDiv');
if(typeof subcatDiv != 'undefined')
    {
          $('#subcatSelect').multiselect();
          var subcatChangeText = $('#subcatSelect').next('div').find('button').find('span');
            if(subcatChangeText.text() == 'choose Options')
            {
                subcatChangeText.text('Choose Subcat');
            }
                               $('.multiselect-container').addClass('overflow_autoCD');
                                $('#subcatSelect').multiselect({
                                    buttonWidth: '150px'
                                });
    }

    var countryDiv = document.getElementById('countryDiv');
if(typeof countryDiv != 'undefined')
    {
          $('#countrySelect').multiselect();
          var countryChangeText = $('#countrySelect').next('div').find('button').find('span');
            if(countryChangeText.text() == 'choose Options')
            {
                countryChangeText.text('Choose Country');
            }
                               $('.multiselect-container').addClass('overflow_autoCD');
                                $('#countryChangeText').multiselect({
                                    buttonWidth: '150px'
                                });
    }
/*{
     $('#courseSelect').multiselect();
                               $('.multiselect-container').addClass('courseIdSelect');
                                $('#courseSelect').multiselect({
                                    buttonWidth: '150px'
                                });
}*/

  $(function () {
        //$('#courseSelect').multiselect({
          //  includeSelectAllOption: true
        //});
        $('#stateSelect').multiselect({
            includeSelectAllOption: true
        });
    });

$(document).on('click','#courseSelect li a',function(){
   
   var str = $(this).text();
var courseId = str.substring(0,str.indexOf('-'));
  $(this).parents(".dropdown").find('#courseSelect').html( courseId+ '&nbsp&nbsp <span class="caret"></span>');
  //$(this).parents(".dropdown").find('#CourseIdButton').val($(this).data('value'));
});

    var bootstrapDropdownHandler = new BootstrapDropdownHandlerCD();
    bootstrapDropdownHandler.generateInput();

    $('#submit').on('click', function(){
        bootstrapDropdownHandler.submitInput();
    });
</script>