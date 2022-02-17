<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="dashboard_graph">
            <div class="row x_title">
                <div class="col-md-2">
                    <?php echo ucfirst($pageName).' '; ?><span class="small"><?php echo ucfirst($responseType).' Responses'; ?></span>
                </div>
                <div class="col-md-2">
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle fixed_width_155" type="button" id="category"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">All Category
                            <?php // listing-type is the name given to the url query string equivalent to 'type' ?>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="category" id="categoryOne">
                            <li data-dropdown="0">
                                    <a href="#" title="All Category">All Category</a>
                            </li>
                            <li data-dropdown="0" style="background-color: khaki;" class="disabled">
                                    <a href="#" title="Choose a popular course" >Choose a popular course</a>
                            </li>
                            <?php foreach ($desiredCourses as $key => $value) { ?>
                                <li data-dropdown="<?php echo $value['SpecializationId']; ?>">
                                    <a href="#" title="<?php echo $value['CourseName']; ?>">
                                        <?php echo $value['CourseName']; ?>
                                    </a>
                                </li>
                            <?php } ?>
                            <li data-dropdown="0" style="background-color: khaki" class="disabled">
                                    <a href="#" title="Or Choose a stream">Or Choose a stream</a>
                            </li>
                            <?php foreach ($abroadCategories as $key => $value) { ?>
                                <li data-dropdown="<?php echo $value['id']; ?>">
                                    <a href="#" title="<?php echo $value['name']; ?>">
                                        <?php echo $value['name']; ?>
                                    </a>
                                </li>
                            <?php } ?>    
                        </ul>
                    </div>
                    <input type="hidden" name="category" value="0"/>
                </div>

                <div class="col-md-2">
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle fixed_width_155" type="button" id="country"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">All Countries<span
                                class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="country" style="height: 300px; overflow: auto;">
                            <li data-dropdown="0">
                                    <a href="#" title="All Countries">All Countries</a>
                            </li>
                            <?php foreach ($abroadCountries as $key => $value) { ?>
                                <li data-dropdown="<?php echo $value->getId(); ?>">
                                    <a href="#" title="<?php echo $value->getName(); ?>">
                                        <?php echo $value->getName(); ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="hidden" name="country" value="0"/>
                </div>

                <div class="col-md-2">
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle fixed_width_155" type="button" id="courseLevel"
                                data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="true">All Course Levels<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="courseLevel" id="courseLevelOne" >
                            <li data-dropdown="0">
                                    <a href="#" title="All Course Levels">All Course Levels</a>
                            </li>
                            <?php 
                                $i=1;
                                foreach ($courseType as $key => $value) { ?>
                                <li data-dropdown="<?php echo $value['CourseName'];?>">
                                    <a href="#" title="<?php echo $value['CourseName']; ?>">
                                        <?php echo $value['CourseName']; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <input type="hidden" name="courseLevel" value="0"/>
                </div>

                <div class="col-md-2">
                    <div id="reportrange" class="pull-right"
                         style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        <span></span> <b class="caret"></b>
                    </div>
                </div>
                <!-- <div class="col-md-10"></div> -->
                <div class="col-md-2">
                    <button type="button" id="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </div>

            <div class="col-md-9 col-sm-9 col-xs-12">
                <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
                <div style="width: 100%;">
                    <div id="canvas_dahs" class="demo-placeholder" style="width: 100%; height:270px;"j></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12 bg-white">
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div id="message" class="message" >
<h2>No record found for selected criteria.</h2>
</div>

<script type="text/javascript">
    var bootstrapDropdownHandler = new BootstrapDropdownHandlerSA();
    var BootstrapDropdownHandlerForComparesion= new BootstrapDropdownHandlerSAForComparesion();
    bootstrapDropdownHandler.generateInput();

    $('#submit').on('click', function(){
        var compareDate= $("input[name=daterangepicker_start1]").val();
        getDateArray(startDate);
                if($('#compareTo').is(':checked'))
                {
                    if(compareDate)
                    {
                        startDateOne = moment($("input[name=daterangepicker_start1]").val()).format('YYYY-MM-DD');
                        endDateOne = moment($("input[name=daterangepicker_end1]").val()).format('YYYY-MM-DD');
                        BootstrapDropdownHandlerForComparesion.getDateArrayForComparision(startDate,startDateOne);
                        BootstrapDropdownHandlerForComparesion.submitInput(Common.ajaxUrlForCompare,startDate,endDate,startDateOne,endDateOne);
                    }

                }
                else
                {
                    bootstrapDropdownHandler.submitInput(Common.ajaxUrl,startDate,endDate);  
                }

                $('.first').hide();
                $('.second').hide();
    });
</script>
