<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("bootstrap-multiselect",'nationalMIS'); ?>" type="text/css">
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("bootstrap-multiselect","nationalMIS"); ?>"></script>            

<?php 

    $class = 'col-md-2';
    $width = 'fixed_width_155';
    $size = sizeof($topFilters);
    for ($i=0; $i < 3-$size; $i++) {
        echo '<div class="col-md-2 <?php echo $width; ?>"></div>';
    }
    
    foreach ($topFilters as $key => $value) {  ?>
    <?php
        switch($value){
            case 'category':
                if($teamName == 'Study Abroad'){
    ?>
                    <div class="<?php echo $class; ?>">
                        <div class="dropdown">
                            <button title = "All Category" class="btn btn-default dropdown-toggle white_space_normal_overwrite <?php echo $width; ?>" type="button" id="category"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">All Category
                                <?php // listing-type is the name given to the url query string equivalent to 'type' ?>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="category" id="categoryOne">
                                <li data-dropdown="0">
                                        <a href="javascript:void(0)" title="All Category">All Category</a>
                                </li>
                                <li data-dropdown="0" style="background-color: khaki;" class="disabled">
                                        <a href="javascript:void(0)" title="Choose a popular course" >Choose a popular course</a>
                                </li>
                                <?php foreach ($desiredCourses as $key => $value) { ?>
                                    <li data-dropdown="<?php echo $value['SpecializationId']; ?>">
                                        <a href="javascript:void(0)" title="<?php echo $value['CourseName']; ?>">
                                            <?php echo $value['CourseName']; ?>
                                        </a>
                                    </li>
                                <?php } ?>
                                <li data-dropdown="0" style="background-color: khaki" class="disabled">
                                        <a href="javascript:void(0)" title="Or Choose a stream">Or Choose a stream</a>
                                </li>
                                <?php foreach ($abroadCategories as $key => $value) { ?>
                                    <li data-dropdown="<?php echo $value['id']; ?>">
                                        <a href="javascript:void(0)" title="<?php echo $value['name']; ?>">
                                            <?php echo $value['name']; ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <input type="hidden" name="category" value=0 id="hidden_category"/>
                    </div>
    <?php       }else if($teamName == 'Domestic'){?>
                    <div class="<?php echo $class; ?>">
                        <div class="dropdown">
                            <button title="All Category" class="btn btn-default dropdown-toggle white_space_normal_overwrite <?php echo $width; ?>" type="button" id="category"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">All Category
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="category">
                                <li data-dropdown="0">
                                        <a href="javascript:void(0)" title="All Category">All Category</a>
                                </li>

                                <?php foreach ($domesticCategories as $categories) { ?>
                                    <li data-dropdown="<?php echo $categories->CategoryId; ?>">
                                        <a href="javascript:void(0)" title="<?php echo $categories->CategoryName;?>">
                                            <?php echo $categories->CategoryName; ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <input type="hidden" name="category" value=0 id="hidden_category"/>
                    </div>
    <?php       } ?>
    <?php       break;
    
            case 'courseLevel':  ?>
                <div class="<?php echo $class; ?>">
                    <div class="dropdown">
                        <button title="All Course Levels" class="btn btn-default dropdown-toggle white_space_normal_overwrite <?php echo $width; ?>" type="button" id="courseLevel"
                                data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="true">All Course Levels<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="courseLevel" id="courseLevelOne" >
                            <li data-dropdown="0">
                                    <a href="javascript:void(0)" title="All Course Levels">All Course Levels</a>
                            </li>
                            <?php
                                $i=1;
                                foreach ($courseType as $key => $value) { ?>
                                <li data-dropdown="<?php echo $value['CourseName'];?>">
                                    <a href="javascript:void(0)" title="<?php echo $value['CourseName']; ?>">
                                        <?php echo $value['CourseName']; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="hidden" name="courseLevel" value='0' id="hidden_courseLevel" />
                </div>
    <?php       break;

            case 'country': ?>
                <div class="<?php echo $class; ?>">
                    <div class="dropdown">
                        <button title="All Countries" class="btn btn-default dropdown-toggle white_space_normal_overwrite <?php echo $width; ?>" type="button" id="country"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">All Countries<span
                                class="caret"></span>
                        </button>
                        <ul class="dropdown-menu overflow_autoSA" aria-labelledby="country" >
                            <li data-dropdown="0">
                                    <a href="javascript:void(0)" title="All Countries">All Countries</a>
                            </li>
                            <?php foreach ($abroadCountries as $key => $value) { ?>
                                <li data-dropdown="<?php echo $value->getId(); ?>">
                                    <a href="javascript:void(0)" title="<?php echo $value->getName(); ?>">
                                        <?php echo $value->getName(); ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="hidden" name="country" value="0"/>
                </div>
    <?php       break;

            case 'rankingPageType': ?>
                <div class="col-md-2">
                    <div class="dropdown">
                        <button title="All(Page Type)" class="btn btn-default dropdown-toggle white_space_normal_overwrite <?php echo $width; ?>" type="button" id="pageType"
                                data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="true">All(Page Type)<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="pageType" id="pageTypeOne" >
                            <li data-dropdown="0">
                                    <a href="javascript:void(0)" title="All Course Levels">All(Page Type)</a>
                            </li>
                            <li data-dropdown="1">
                                    <a href="javascript:void(0)" title="All Course Levels">university</a>
                            </li>
                            <li data-dropdown="2">
                                    <a href="javascript:void(0)" title="All Course Levels">course</a>
                            </li>
                        </ul>
                    </div>
                    <input type="hidden" name="pageType" value='0'/>
                </div>
    <?php       break;

            case 'sourceApplication' : ?>
                <div class="col-md-2">
                    <div class="dropdown">
                        <button title="Source Application" class="btn btn-default dropdown-toggle white_space_normal_overwrite fixed_width_155 " type="button" id="sourceApplication"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Source Application<span
                                class="caret"></span>
                        </button>
                        <ul class="dropdown-menu " aria-labelledby="sourceApplication" >
                            <li data-dropdown="all">
                                    <a href="javascript:void(0)" title="All Source Applications">All Source Applications</a>
                            </li>
                            <?php foreach ($sourceApplication as $key => $value) { ?>
                                <li data-dropdown="<?php echo $value['id']; ?>">
                                    <a href="javascript:void(0)" title="<?php echo $value['name']; ?>">
                                        <?php echo $value['name']; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="hidden" name="sourceApplication" value="all" id="hidden_sourceApplication"/>
                </div>
    <?php       break;

            case 'sourceApplicationType': ?>
                <div class="col-md-2">
                    <div class="dropdown">
                        <button title="Source Application Type" class="btn btn-default dropdown-toggle white_space_normal_overwrite fixed_width_155 " type="button" id="sourceApplicationType"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Source Application Type<span
                                class="caret"></span>
                        </button>
                        <ul class="dropdown-menu " aria-labelledby="sourceApplicationType" >
                            <li data-dropdown="all">
                                    <a href="javascript:void(0)" title="All Source Application Type">All Source Application Type</a>
                            </li>
                            <?php foreach ($sourceApplicationType as $key => $value) { ?>
                                <li data-dropdown="<?php echo $value['id']; ?>">
                                    <a href="javascript:void(0)" title="<?php echo $value['name']; ?>">
                                        <?php echo $value['name']; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="hidden" name="sourceApplicationType" value="all" id="hidden_sourceApplicationType"/>
                </div>

    <?php       break;

            case 'subCategory': ?>
                <div class="col-md-2 cropper-hidden">
                    <div class="dropdown">
                        <button
                            title="All Subcategories"
                            class="btn btn-default dropdown-toggle white_space_normal_overwrite fixed_width_155"
                            type="button" id="subCategoryId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">All Subcategories<span
                            class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="subid">
                        </ul>
                    </div>
                    <input type="hidden" name="subCategoryId" value="0"/>
                </div>
    <?php       break;

            case 'mainExam': ?>
                <div class="col-md-2 cropper-hidden">
                    <div class="row">
                        <button
                            title="All Exam"
                            class="col-md-11 col-sm-11 col-xs-11 btn btn-default dropdown-toggle white_space_normal_overwrite"
                            type="button" id="examId"
                            data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="true">'All Exam'<span
                            class="caret margin-right-2"></span>
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="examId">

                        </ul>
                    </div>
                    <input type="hidden" name="examId" value="0"/>
                </div>
    <?php       break;

            case 'subExam' : ?>
                <div class="col-md-2 cropper-hidden">
                    <div class="row">
                        <button
                            title="All Sub Exams"
                            class="col-md-11 col-sm-11 col-xs-11 btn btn-default dropdown-toggle white_space_normal_overwrite"
                            type="button" id="subExamId"
                            data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="true">All Sub Exams<span
                            class="caret margin-right-2"></span>
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="subExamId">

                        </ul>
                    </div>
                    <input type="hidden" name="subExamId" value='0'>
                </div>
    <?php       break;

            case 'user' : ?>
                <div class="col-md-2">
                    <div class="dropdown">
                        <button title="All Users" class="btn btn-default dropdown-toggle white_space_normal_overwrite fixed_width_155 " type="button" id="userFilter"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">All Users<span
                                class="caret"></span>
                        </button>
                        <ul class="dropdown-menu " aria-labelledby="userFilter" >
                            <li data-dropdown="all">
                                    <a href="javascript:void(0)" title="All Users">All Users</a>
                            </li>
                            <?php foreach ($userFilter as $key => $value) { ?>
                                <li data-dropdown="<?php echo $value['id']; ?>">
                                    <a href="javascript:void(0)" title="<?php echo $value['name']; ?>">
                                        <?php echo $value['name']; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="hidden" name="user" value="all"/>
                </div>
    <?php       break;

            case 'abroadExam': ?>
                <div class="col-md-2">
                    <div class="dropdown">
                        <button title="All (default)" class="btn btn-default dropdown-toggle white_space_normal_overwrite fixed_width_155 " type="button" id="abroadExam"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">All (default)
                                <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu " aria-labelledby="abroadExam" >
                            <li data-dropdown="all">
                                    <a href="javascript:void(0)" title="All (default)">All (default)</a>
                            </li>
                            <?php foreach ($abroadExam as $key => $value) { ?>
                                <li data-dropdown="<?php echo $value['id']; ?>">
                                    <a href="javascript:void(0)" title="<?php echo $value['name']; ?>">
                                        <?php echo $value['name']; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="hidden" name="abroadExam" value="all"/>
                </div>
    <?php       break;

            case 'abroadExamList':
                ?>
                <div class="col-md-2">
                    <div class="dropdown">
                        <button title="Abroad Exam List" class="btn btn-default dropdown-toggle white_space_normal_overwrite fixed_width_155" type="button"
                                id="abroadExamList" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Abroad Exam List<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu " aria-labelledby="abroadExamList">
                            <li data-dropdown="0">
                                <a href="javascript:void(0)" title="All Abroad Exam">All Abroad Exam</a>
                            </li>
                            <?php foreach ($abroadExamList as $key => $value) { ?>
                                <li data-dropdown="<?php echo $value['id']; ?>">
                                    <a href="javascript:void(0)" title="<?php echo $value['name']; ?>"><?php echo $value['name']; ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="hidden" name="abroadExamList" value="0">
                </div>
    <?php       break;

            case 'stream': ?>
                <div class="col-md-2 ">
                    <div class="dropdown">
                        <button title="All Streams" class="btn btn-default btn-block dropdown-toggle white_space_normal_overwrite " type="button" id="stream"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="caret"></span>All Streams
                            
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="stream">
                            <li data-dropdown="0">
                                    <a href="javascript:void(0)" title="All Streams">All Streams</a>
                            </li>

                            <?php foreach ($streams as $streamId => $streamName) { ?>
                                <li data-dropdown="<?php echo $streamId; ?>">
                                    <a href="javascript:void(0)" title="<?php echo $streamName;?>">
                                        <?php echo $streamName; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="hidden" name="stream" value=0 id="hidden_stream"/>
                </div>
    <?php       break;

            case 'substream': ?>
                <div class="col-md-2 ">
                    <div class="dropdown">
                        <button title="All Sub Streams" class="btn btn-default btn-block dropdown-toggle white_space_normal_overwrite " type="button" id="substream"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="caret"></span>All Sub Streams
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="substream">
                        </ul>
                    </div>
                    <input type="hidden" name="substream" value=0 id="hidden_substream"/>
                </div>
    <?php       break;

            case 'specialization': ?>
                <div class="col-md-2 ">
                    <div class="dropdown">
                        <button title="All Specializations" class="btn btn-default btn-block dropdown-toggle white_space_normal_overwrite " type="button" id="specialization"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="caret"></span>All Specializations
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="specialization">
                        </ul>
                    </div>
                    <input type="hidden" name="specialization" value=0 id="hidden_specialization"/>
                </div>
    <?php       break;

            case 'baseCourse': ?>
                <div class="col-md-2 ">
                    <div class="dropdown">
                        <button title="All Base Courses" class="btn btn-default btn-block dropdown-toggle white_space_normal_overwrite " type="button" id="baseCourse"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="caret"></span>All Base Courses
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="baseCourse">
                        </ul>
                    </div>
                    <input type="hidden" name="baseCourse" value=0 id="hidden_baseCourse"/>
                </div>
    <?php       break;

            case "baseCourseLevel": ?>
                <div class="col-md-2 ">
                    <div class="dropdown">
                        <button title="All Course Level" class="btn btn-default btn-block dropdown-toggle white_space_normal_overwrite " type="button" id="baseCourseLevel"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="caret"></span>All Course Level
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="baseCourseLevel">
                        </ul>
                    </div>
                    <input type="hidden" name="baseCourseLevel" value=0 id="hidden_baseCourseLevel"/>
                </div>
    <?php       break;

            case "credential" : ?>
                <div class="col-md-2 ">
                    <div class="dropdown">
                        <button title="All Credential" class="btn btn-default btn-block dropdown-toggle white_space_normal_overwrite " type="button" id="credential"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="caret"></span>All Credential
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="credential">
                        </ul>
                    </div>
                    <input type="hidden" name="credential" value=0 id="hidden_credential"/>
                </div>
    <?php       break;

            case "educationType": ?>
                <div class="col-md-2 ">
                    <div class="dropdown">
                        <button title="All Education Type" class="btn btn-default btn-block dropdown-toggle white_space_normal_overwrite " type="button" id="educationType"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="caret"></span>All Education Type
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="educationType">
                            <li data-dropdown="0">
                                    <a href="javascript:void(0)" title="All Education Type">All Education Type</a>
                            </li>
                            <?php foreach ($educationType as $educationTypeId => $educationTypeName) { ?>
                                <li data-dropdown="<?php echo $educationTypeId; ?>">
                                    <a href="javascript:void(0)" title="<?php echo $educationTypeName;?>">
                                        <?php echo $educationTypeName; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="hidden" name="educationType" value=0 id="hidden_educationType"/>
                </div>
    <?php       break;

            case "deliveryMethod" ?>
                <div class="col-md-2 ">
                    <div class="dropdown">
                        <button title="All Delivery Method" class="btn btn-default btn-block dropdown-toggle white_space_normal_overwrite " type="button" id="deliveryMethod"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="caret"></span>All Delivery Method
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="deliveryMethod">
                        </ul>
                    </div>
                    <input type="hidden" name="deliveryMethod" value=0 id="hidden_deliveryMethod"/>
                </div>
    <?php       break; 

            case 'shikshaPages': ?>
                <div class="col-md-2 ">
                    <div class="dropdown" id ="shikshaPagesId">
                        <select id="shikshaPages" multiple="multiple">
                            <option value="all" selected>All Pages</option>
                            <?php foreach ($shikshaPages as $groupName => $groupDetails) { 
                                foreach ($groupDetails as $pageId => $pageName) {
                            ?>
                                <option value="<?php echo $pageId;?>" ><?php echo $pageName."[ Page Group : ".$groupName." ]";?></option>
                            <?php } }?>
                            
                        </select>
                    </div>              
                </div>

    <?php       break;

            case 'shikshaPageGroups': ?>
                <div class="col-md-2 ">
                    <div class="dropdown">
                        <select id="shikshaPageGroups" multiple="multiple">
                            <option value="all" selected>All Page Groups</option>
                            <?php foreach ($shikshaPageGroups as $groupId => $groupName) { ?>
                                <option value="<?php echo $groupId;?>" ><?php echo $groupName;?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>   

    <?php       break;

            case 'mode': ?>
                <div class="col-md-2 ">
                    <div class="dropdown">
                        <button title="Mode of study" class="btn btn-default btn-block dropdown-toggle  white_space_normal_overwrite fixed_width_155" type="button" id="mode"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="caret"></span>Mode of study
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="mode">
                            <li data-dropdown="0">
                                    <a href="javascript:void(0)" title="Mode of study">Mode of study</a>
                            </li>

                            <?php foreach ($mode as $modeId => $modeValue) { ?>
                                <li data-dropdown="<?php echo $modeId; ?>">
                                    <a href="javascript:void(0)" title="<?php echo $modeValue;?>">
                                        <?php echo $modeValue; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="hidden" name="mode" value='0' id="hidden_mode"/>
                </div>
     <?php      break;

            case 'responseType': ?>
                <div class="col-md-2 ">
                    <div class="dropdown">
                        <button title="All Response Type" class="btn btn-default btn-block dropdown-toggle  white_space_normal_overwrite fixed_width_155" type="button" id="responseType"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="caret"></span>All Response Type
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="responseType">
                            <li data-dropdown="all">
                                    <a href="javascript:void(0)" title="All Response Type">All Response Type</a>
                            </li>

                            <?php foreach ($responseType as $key => $value) { ?>
                                <li data-dropdown="<?php echo $value; ?>">
                                    <a href="javascript:void(0)" title="<?php echo $value;?>">
                                        <?php echo $key; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="hidden" name="responseType" value='all' id="hidden_responseType"/>
                </div>
    <?php        break;

            case 'responseListingType' : ?>
                <div class="col-md-2 ">
                    <div class="dropdown">
                        <button title="All Response Listing Type" class="btn btn-default btn-block dropdown-toggle  white_space_normal_overwrite fixed_width_155" type="button" id="responseListingType"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="caret"></span>All Response Listing Type
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="responseListingType">
                            <li data-dropdown="all">
                                    <a href="javascript:void(0)" title="All Response Listing Type">All Response Listing Type</a>
                            </li>

                            <?php foreach ($responseListingType as $key => $value) { ?>
                                <li data-dropdown="<?php echo $value['id']; ?>">
                                    <a href="javascript:void(0)" title="<?php echo $value['name'];?>">
                                        <?php echo $value['name']; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="hidden" name="responseListingType" value='all' id="hidden_responseListingType"/>
                </div>
    <?php       break;

            case 'trafficSourceType' : ?>
                <div class="col-md-2 ">
                    <div class="dropdown">
                        <button title="All Traffic Sources" class="btn btn-default btn-block dropdown-toggle  white_space_normal_overwrite fixed_width_155" type="button" id="trafficSourceType"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="caret"></span>All Traffic Sources
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="trafficSourceType">
                            <li data-dropdown="all">
                                    <a href="javascript:void(0)" title="All Traffic Sources">All Traffic Sources</a>
                            </li>

                            <?php foreach ($trafficSourceType as $key => $value) { ?>
                                <li data-dropdown="<?php echo $value['id']; ?>">
                                    <a href="javascript:void(0)" title="<?php echo $value['name'];?>">
                                        <?php echo $value['name']; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="hidden" name="trafficSourceType" value='all' id="hidden_trafficSourceType"/>
                </div>

    <?php       break;    
            case 'responseWarmth': ?>
                <div class="col-md-2 ">
                    <div class="dropdown">
                        <button title="All Response Warmth" class="btn btn-default btn-block dropdown-toggle  white_space_normal_overwrite fixed_width_155" type="button" id="responseWarmth"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="caret"></span>All Response Warmth
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="responseWarmth">
                            <?php foreach ($responseWarmth as $key => $value) { ?>
                                <li data-dropdown="<?php echo $key; ?>">
                                    <a href="javascript:void(0)" title="<?php echo $value;?>">
                                        <?php echo $value; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="hidden" name="responseWarmth" value='all' id="hidden_responseWarmth"/>
                </div>
    <?php       break;
            case 'clientList': ?>
                <div class="col-md-2 ">
                    <div class="dropdown">
                        <button title="All Clients" class="btn btn-default btn-block dropdown-toggle  white_space_normal_overwrite fixed_width_155" type="button" id="clientList"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="caret"></span>All Clients
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="clientList">
                            <li data-dropdown="all">
                                    <a href="javascript:void(0)" title="All Clients">All Clients</a>
                            </li>
                            <?php foreach ($clientList as $clientId => $clientDetails) { ?>
                                <li data-dropdown="<?php echo $clientId; ?>">
                                    <a href="javascript:void(0)" title="<?php echo $clientDetails['displayName']." (".$clientDetails['email'].")"; ?>">
                                        <?php echo $clientDetails['displayName']." (".$clientDetails['email'].")"; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="hidden" name="clientList" value='all' id="hidden_clientList"/>
                </div>
    <?php       break;

            case 'groupPageList': ?>
                <div class="col-md-2 ">
                    <div class="dropdown">
                        <select id="groupPageList" multiple="multiple">
                            <option value="all" selected>All Group Pages</option>
                            <?php foreach ($groupPageList as $pagePageKey => $pagePageName) { ?>
                            <option value="<?php echo $pagePageKey;?>" ><?php echo $pagePageName;?></option>
                            <?php } ?>
                        </select>
                    </div>              
                </div>
    <?php       break;

            case 'courseListings': ?>
                <div class="col-md-2 hide">
                    <div class="dropdown" id="<?php echo $value;?>_div">
                        <!-- <select id="<?php echo $value;?>" multiple="multiple">
                        </select> -->
                    </div>              
                </div>
    <?php       break;

            case 'sessionFilter': ?>
                <div class="col-md-2 ">
                    <div class="dropdown">
                        <button title="Internal Source" class="btn btn-default btn-block dropdown-toggle  white_space_normal_overwrite fixed_width_155" type="button" id="sessionFilter"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="caret"></span>Internal Source
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="sessionFilter">
                            <?php foreach ($sessionFilter as $key => $value) { ?>
                                <li data-dropdown="<?php echo $key; ?>">
                                    <a href="javascript:void(0)" title="<?php echo $value;?>">
                                        <?php echo $value; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="hidden" name="sessionFilter" value='all' id="hidden_sessionFilter"/>
                </div>
        <?php   break;

            case 'userUsedSassistant': ?>
                <div class="col-md-2 ">
                    <div class="dropdown">
                        <button title="Miscellaneous" class="btn btn-default btn-block dropdown-toggle  white_space_normal_overwrite fixed_width_155" type="button" id="userUsedSassistant"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="caret"></span>Miscellaneous
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="userUsedSassistant">
                            <?php foreach ($userUsedSassistant as $key => $value) { ?>
                                <li data-dropdown="<?php echo $key; ?>">
                                    <a href="javascript:void(0)" title="<?php echo $value;?>">
                                        <?php echo $value; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="hidden" name="userUsedSassistant" value='all' id="hidden_userUsedSassistant"/>
                </div>
        <?php   break;

            case 'isourceFilter': ?>
                <div class="col-md-2 ">
                    <div class="dropdown">
                        <button title="Miscellaneous" class="btn btn-default btn-block dropdown-toggle  white_space_normal_overwrite fixed_width_155" type="button" id="isourceFilter"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="caret"></span>Internal Source Filter
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="isourceFilter">
                            <?php foreach ($isourceFilter as $name => $id) { ?>
                                <li data-dropdown="<?php echo $id; ?>">
                                    <a href="javascript:void(0)" title="<?php echo $name;?>">
                                        <?php echo $name; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="hidden" name="isourceFilter" value='all' id="hidden_isourceFilter"/>
                </div>
        <?php   break;

        }
    } 

    $addBlankDiv = ($size ==5)?3:(($size ==6)?2:(($size ==7)?1:0));

    for ($i=0; $i < $addBlankDiv; $i++) { 
        echo '<div class="col-md-2 <?php echo $width; ?>"></div>';
    }
?>

<div class="col-md-2">
    <div class="dropdown">
        <button id="reportrange" class="btn btn-default col-md-11 col-sm-11 col-xs-11 white_space_normal_overwrite"
                style="background: #fff;"><b class="caret margin-right-2"></b>
            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
            <span></span>
        </button>
    </div>
</div>

<div class="col-md-2 pull-right">
    <button type="button" id="submit" class="btn btn-primary">Apply Filters</button>
</div>
