<!--  FILTER -->    
<div class="row x_title">
    <?php
        $size = sizeof($topFilter);
        $topT = json_encode($topFilter);
        $class = 'col-md-2';
        
        $width = 'fixed_width_155';
    ?>
        <!--   pageName  -->  
        <div class="col-md-2">
                    <div style="font-size: 18px">
                        <?php 
                            if($pageName == 'compareCoursesPage'){
                                echo 'Compare Page ';
                            }else if($pageName == 'recommendationPage'){
                                echo 'Reco Page ';
                            }else{
                                echo ucwords($pageName).' '; 
                            }
                        ?>
                    </div>
                    <div style="font-size: 14px">
                        <span class="" style="color: #73879c" id="eng_heading">
                            <?php 
                                switch ($responseType) {
                                    case 'Traffic':
                                    case 'Registrations':
                                        echo ucwords($responseType);
                                        break;

                                    case 'guide':
                                        echo 'Downloads';
                                        break;

                                    case 'rmc':
                                        echo strtoupper($responseType).' Responses';
                                        break;

                                    case 'CPEnquiry':
                                        echo 'Consultant Enquiries';
                                        break;

                                    case 'compare':
                                        echo 'Final Compared Courses';
                                        break;

                                    case 'commentReply':
                                        echo 'Comments - Replies';
                                        break;

                                    case 'Leads':
                                        echo 'Leads';
                                        break;

                                    default :
                                        echo 'Responses';
                                        break;                                
                                }
                            ?>
                        </span>
                    </div>
        </div>
        
        <?php
            for ($i=0; $i < 3-$size; $i++) { 
                echo '<div class="col-md-2 <?php echo $width; ?>"></div>';
            }
        ?>

        <?php
        foreach ($topFilter as $key => $value) {  ?>
            <?php 
                switch($value){
                    case 'category':   ?>
                        <!--   category  -->     
                        <div class="<?php echo $class; ?>">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle white_space_normal_overwrite <?php echo $width; ?>" type="button" id="category"
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
            <?php       break;

                    case 'courseLevel':  ?>
                        <!--   courseLevel  -->  
                        <div class="<?php echo $class; ?>">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle white_space_normal_overwrite <?php echo $width; ?>" type="button" id="courseLevel"
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

                    case 'consultants': ?>
                        <!--   consultant  -->  
                        <div class="<?php echo $class; ?>">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle white_space_normal_overwrite  <?php echo $width; ?>" type="button" id="consultant"
                                        data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="true">All Consultants<span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu overflow_autoSA" aria-labelledby="courseLevel" id="courseLevelOne" >
                                    <li data-dropdown="0">
                                            <a href="javascript:void(0)" title="All Course Levels">All Consultants</a>
                                    </li>
                                    <?php 
                                        $i=1;
                                        foreach ($consultants as $key => $value) { ?>
                                        <li data-dropdown="<?php echo $value['consultantId'];?>">
                                            <a href="javascript:void(0)" title="<?php echo $value['name']; ?>">
                                                <?php echo $value['name']; ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <input type="hidden" name="consultant" value='0' id="hidden_consultant" />    
                        </div>
            <?php       break;

                    case 'consultantLocationRegions': ?>  
                        <!--   region  -->  
                        <div class="<?php echo $class; ?>">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle white_space_normal_overwrite <?php echo $width; ?>" type="button" id="region"
                                        data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="true">All Regions<span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu overflow_autoSA" aria-labelledby="courseLevel" id="courseLevelOne" >
                                    <li data-dropdown="0">
                                            <a href="javascript:void(0)" title="All Course Levels">All Regions</a>
                                    </li>
                                    <?php 
                                        $i=1;
                                        foreach ($consultantLocationRegions as $key => $value) { ?>
                                        <li data-dropdown="<?php echo $value['id'];?>">
                                            <a href="javascript:void(0)" title="<?php echo $value['regionName']; ?>">
                                                <?php echo $value['regionName']; ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <input type="hidden" name="region" value='0' id="hidden_region" />    
                        </div>
            <?php       break;

                    case 'country':
                        if($metric == 'TRAFFIC' && $pageName == 'categoryPage'){
                            ?>  
                            <script src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("bootstrap-multiselect","nationalMIS"); ?>"></script>
                            <link href="//<?php echo CSSURL; ?>/public/css/trackingMIS/<?php echo getCSSWithVersion("bootstrap-multiselect",'nationalMIS'); ?>" rel="stylesheet">
                            <div class="<?php echo $class; ?>">
                                <div class="dropdown">
                                    <select id="example-getting-started" multiple="multiple" class="<?php echo $width; ?>" style="overflow:auto;height:300px">
                                        <option value="0" selected>All Countries</option>
                                        <?php foreach ($abroadCountries as $key => $value) { ?>
                                            <option value="<?php echo $value->getId(); ?>"><?php echo $value->getName(); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <input type="hidden" name="country" value="0"/>
                            </div>
            <?php       }else {  ?>
                            <div class="<?php echo $class; ?>">
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle white_space_normal_overwrite <?php echo $width; ?>" type="button" id="country"
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
            <?php       } 
                        break;
                    case 'rankingPageType': ?>
                        <!--   rankingType filter  -->  
                        <div class="col-md-2">
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle white_space_normal_overwrite <?php echo $width; ?>" type="button" id="pageType"
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

                    case 'engagements': 
                        if($pageName != 'Study Abroad') { 
                            $list = '<li data-dropdown="pageviews"><a href="javascript:void(0)">Page Views</a></li>'.
                                    '<li data-dropdown="pgpersess"><a href="javascript:void(0)">Pages Per Session</a></li>'.
                                    '<li data-dropdown="avgsessdur"><a href="javascript:void(0)">Avg Session Duration</a></li>'.
                                    '<li data-dropdown="exit"><a href="javascript:void(0)">Exit Rate</a></li>'.
                                    '<li data-dropdown="bounce"><a href="javascript:void(0)">Bounce Rate</a></li>';
                        }else{
                            $list = '<li data-dropdown="pageviews"><a href="javascript:void(0)">Page Views</a></li>'.
                                    '<li data-dropdown="pgpersess"><a href="javascript:void(0)">Pages Per Session</a></li>'.
                                    '<li data-dropdown="avgsessdur"><a href="javascript:void(0)">Avg Session Duration</a></li>'.
                                    '<li data-dropdown="bounce"><a href="javascript:void(0)">Bounce Rate</a></li>';   
                        }
                        ?> 
                        <div class="col-md-2" >
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle white_space_normal_overwrite <?php echo $width; ?>" type="button" id="type"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Page Views
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="listing-type">
                                    <?php echo $list;?>
                                </ul>
                            </div>
                            <input type="hidden" name="engagementType" value='pageviews' id="engagementTypes" />
                        </div>
            <?php       
                        break;
                    case 'consultantLocationRegions': ?>
                        <!--   region  -->  
                        <div class="col-md-3">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle white_space_normal_overwrite <?php echo $width; ?>" type="button" id="region"
                                        data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="true">All Region<span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu overflow_autoSA" aria-labelledby="courseLevel" id="courseLevelOne" >
                                    <li data-dropdown="0">
                                            <a href="javascript:void(0)" title="All Course Levels">All Region</a>
                                    </li>
                                    <?php 
                                        $i=1;
                                        foreach ($consultantLocationRegions as $key => $value) { ?>
                                        <li data-dropdown="<?php echo $value['id'];?>">
                                            <a href="javascript:void(0)" title="<?php echo $value['regionName']; ?>">
                                                <?php echo $value['regionName']; ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <input type="hidden" name="region" value='0' id="hidden_region" />
                        </div>
            <?php       break;

                    case 'courseComparedFilter': ?>
                        <div class="col-md-2">
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle white_space_normal_overwrite <?php echo $width; ?> " type="button" id="courseCompared"
                                            data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="true">Final Compared Courses<span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="courseCompared" id="courseComparedOne" >
                                        <li data-dropdown="0">
                                                <a href="javascript:void(0)" title="Finally Course Compared">Final Compared Courses</a>
                                        </li>
                                        <li data-dropdown="1">
                                                <a href="javascript:void(0)" title="Courses Added To Compare List">Course Added To Compare List</a>
                                        </li>
                                    </ul>
                                </div>
                                <input type="hidden" name="courseCompared" value='0'/>
                        </div>
            <?php       break;


                }
            ?>  
        <?php } ?>
        <!-- <div class="col-md-6"></div> -->
        <!--   calender  -->  
        <div class="col-md-2" >
                    <div id="reportrange" class="pull-right"
                         style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        <span></span> <b class="caret"></b>
                    </div>
        </div>

        <!--   apply button  -->  
        <div class="col-md-2" style="float:right">
            <button type="button" id="submit" class="btn btn-primary <?php echo $width; ?>">Apply</button>
        </div>  
</div>
<!--  TOP TILES --> 
<div class="row x_title" style="margin-left:0px !important">
    <div class="row tile_count">
        <?php 
            $topTiles = $pageDetails['SA_TOP_Tiles'];
            $i=1;
            foreach ($topTiles as $key => $value) {
                if($responseType == 'Traffic'){
                        if($value != '% New Sessions') {
                            $class = 'cursor_pointer border_1px_solid_eee border_radius_6 ';
                            if($key == 'Users' || $key == 'Page Views')
                                $class .= 'defaultColor';
                            else $class .= 'bgColor';
                        } else {
                            $class = 'bgColor';
                        }    
                }else if($responseType == 'ENGAGEMENT'){
                            $class = 'cursor_pointer border_1px_solid_eee border_radius_6 ';
                            if($key == 'Users' || $key == 'Page Views')
                                $class .= 'defaultColor';
                            else $class .= 'bgColor';
                }
                        
        ?>
        <div class="animated flipInY col-md-2 col-sm-4 col-xs-4  tile_stats_count <?php echo $class; ?>" id="<?php echo 'topHeading_'.$i; ?>" data-text="<?php echo $value; ?>">
            <div class="left"></div>
            <div class="right">
                <span class="count_top" style="color: #73879c !important;"><i class="fa fa-user"></i> <?php echo $value; ?></span>
                <div class="count topTiles_size" id="<?php echo 'topTiles_'.$i; ?>">0</div>
                <span class="count_bottom"><i id="<?php echo 'bottom_'.$i; ?>"><i class="fa"></i></i></span>
                <?php if(strtolower($metric) == 'response' || strtolower($metric) == 'rmc'){ ?>
                    <div class="loader_small_overlay"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>
                <?php } ?>
            </div>
        </div>
        <?php $i++;  } ?>
    </div>
</div>
