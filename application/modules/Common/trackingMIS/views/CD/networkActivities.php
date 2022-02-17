<div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="dashboard_graph">

                            <div class="row x_title">
                              <div class = "col-md-2">
                                 <input type="text" name="instituteId" id="instituteId" placeholder="Enter Institute Id" onblur="getCoursesInfo()">
                         <input type="checkbox" name="selectsimilar" value="checked"> Similar Institute<br>
                              </div>
                        <div class="col-md-2">
                        
                        <div class="dropdown">
                                    
                        <button class="btn btn-default dropdown-toggle fixed_width_155" type="button" id="CourseIdButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Course ID
                            <?php // listing-type is the name given to the url query string equivalent to 'type' ?>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" id="courseIdList" aria-labelledby="CourseIdButton-type">
            
                        </ul>
                    </div>
                    <input type="text" name="courseName" id="courseName" value=""/ disabled>
                    <input type="hidden" name="courseId" id="courseId" value=""/>
                    </div>
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
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Response Type
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
                     
                                <div class="col-md-6">
                                    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                        <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
                                <div style="width: 100%;">
                                    <div id="canvas_dahs" class="demo-placeholder" style="width: 100%; height:270px;"></div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12 bg-white">
                                <!-- <div class="x_title">
                                    <h2>Top Campaign Performance</h2>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div>
                                        <p>Facebook Campaign</p>
                                        <div class="">
                                            <div class="progress progress_sm" style="width: 76%;">
                                                <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="80"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <p>Twitter Campaign</p>
                                        <div class="">
                                            <div class="progress progress_sm" style="width: 76%;">
                                                <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="60"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-6">
                                    <div>
                                        <p>Conventional Media</p>
                                        <div class="">
                                            <div class="progress progress_sm" style="width: 76%;">
                                                <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="40"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <p>Bill boards</p>
                                        <div class="">
                                            <div class="progress progress_sm" style="width: 76%;">
                                                <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="50"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>

</div>

<script type="text/javascript">
    preparePageDataCD();
    var bootstrapDropdownHandler = new BootstrapDropdownHandlerCD();
    bootstrapDropdownHandler.generateInput();

    $('#submit').on('click', function(){
        bootstrapDropdownHandler.submitInput();
    });
</script>