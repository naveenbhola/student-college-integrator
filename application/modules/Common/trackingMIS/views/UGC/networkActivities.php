
<h2 style=''><?php echo $reportHeading;?></h2>
<div class="row">

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="row x_title">
                            <?php if($page == 'byInstitute') { ?>
                              <div class = "col-md-2">
                                
                                 <input class="form-control" type="text" name="instituteId" id="instituteId" placeholder="Enter Institute Id" onblur="getCoursesInfo()">
                                 
                              </div>
                              
                        <div class="col-md-2">
                        
                        <div class="dropdown" id="courseDiv">
                                <select id="courseSelect" multiple="multiple" class="fixed_width_155">
                                    
                                </select>
                             <!-- <ul class="dropdown-menu scrollable-menu courseIdSelect" id="courseIdList" aria-labelledby="CourseIdButton-type" style="
            height: auto;width:auto; max-height: 200px;overflow-x: hidden">
                        </ul>  -->
                    </div>
                         <input type="checkbox" name="simi_larflag_check"  id="similar_flag_check" >Similar Courses Report <br>
                    <!-- <input type="text" name="courseName" id="courseName" value=""/ disabled> -->
                    <input type="hidden" name="courseId" id="courseId" value=""/>
                    </div>
                    <?php } else if($page == 'bySubcatId' || $page == 'content-bySubcatId' || $page == 'SA-Content-bySubcatId'){ ?>
                                <div class = "col-md-2">
                                 <input class="form-control" type="text" name="subCategoryId" id="subCategoryId" placeholder="Enter SubCatId" onblur="">
                             </div>
                                 <?} else if($page == 'byArticle' || $page == 'SA-Content-byArticleId') {?>
                                     <div class = "col-md-2">
                                 <input class="form-control" type="text" name="articleId" id="articleId" placeholder="Enter Article Id" onblur="">
                             </div>   
                                 <?php }?>
                                 <?php if(isset($authorNames)) { ?>
                    <div class="col-md-2">
                        <div class="dropdown">
                                    
                        <button class="btn btn-default dropdown-toggle fixed_width_155" type="button" id="authorNameButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">author Name
                            <?php // listing-type is the name given to the url query string equivalent to 'type' ?>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu scrollable-menu" aria-labelledby="authorName-type" style="
    height: auto;width:auto;
                        
                max-height: 200px;
                overflow-x: hidden">
                            <?php foreach ($authorNames as $key => $value) {?>
                            <li data-dropdown="<?php echo $value['firstName']?>"><a href="#" onclick="setAuthorUserId('<?php echo $value['userid']?>')"><?php echo $value['firstName'].' '.$value['lastName']?></a></li>
                                <?php }?>           
                        </ul>
                    </div>
                    <input type="hidden" name="authorId" id="authorId" value=""/>
                    </div>
                   
                    <?php } ?>
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
                                    
                                </select>
                             <!-- <ul class="dropdown-menu scrollable-menu courseIdSelect" id="courseIdList" aria-labelledby="CourseIdButton-type" style="
            height: auto;width:auto; max-height: 200px;overflow-x: hidden">
                        </ul>  -->
                    </div>
                </div>
                <?php }?>
                    <!--end-->
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
                     
                                <div class="col-md-2">
                                    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding:10px 5px 0px; border: 1px solid #ccc; text-align: center;
    width: 168px;height:40px" align='right' >
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar" style="float:left"></i>
                                        <span style ="float:left">December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary source" id="apply-button" onclick="applyFilters()">Apply Filters</button>
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
                            <div class="col-md-5" style="float:right">
                                    <button class="btn btn-default" id="daily" style="width:100px;" type="button" onclick="viewWiseData('1')">Daily</button>
                                    <button class="btn btn-default" id="weekly" style="width:100px;" type="button" onclick="viewWiseData('2')">Weekly</button>
                                    <button class="btn btn-default" id="monthly" style="width:100px;" type="button" onclick="viewWiseData('3')">Monthly</button>
                                    <input type="hidden" name="viewWise" id="viewWise" value="1"/>
                            </div>
                    </div>

</div>
<script type="text/javascript">
var courseButtonExists = document.getElementById('courseId');

if(typeof courseButtonExists != 'undefined')
    $('#courseSelect').multiselect();
var stateDiv = document.getElementById('stateDiv');
if(typeof stateDiv != 'undefined')
    {
          $('#stateSelect').multiselect();
                               $('.multiselect-container').addClass('overflow_autoCD');
                                $('#courseSelect').multiselect({
                                    buttonWidth: '150px'
                                });
    }
var cityDiv = document.getElementById('cityDiv');
if(typeof cityDiv != 'undefined')
    $('#citySelect').multiselect();

/*{
     $('#courseSelect').multiselect();
                               $('.multiselect-container').addClass('courseIdSelect');
                                $('#courseSelect').multiselect({
                                    buttonWidth: '150px'
                                });
}*/

  $(function () {
        $('#courseSelect').multiselect({
            includeSelectAllOption: true
        });
        $('#stateSelect').multiselect({
            includeSelectAllOption: true
        });
    });

$(document).on('click','#courseSelect li a',function(){
   
   var str = $(this).text();
var courseId = str.substring(0,str.indexOf('-'));
alert(courseId);
  $(this).parents(".dropdown").find('#courseSelect').html( courseId+ '&nbsp&nbsp <span class="caret"></span>');
  //$(this).parents(".dropdown").find('#CourseIdButton').val($(this).data('value'));
});

    var bootstrapDropdownHandler = new BootstrapDropdownHandlerCD();
    bootstrapDropdownHandler.generateInput();

    $('#submit').on('click', function(){
        bootstrapDropdownHandler.submitInput();
    });
</script>