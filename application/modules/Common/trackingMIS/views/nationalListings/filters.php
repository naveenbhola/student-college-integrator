<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row x_title">
            <div class="row">
                <div class="col-md-2">
                    <div><h4>Domestic</h4></div>
                    <div><h5><?php echo ucfirst($actionName); ?></h5></div>
                </div>
                <?php if ($metricName == 'home' || $metricName == 'institute') { ?>
                    <input type="hidden" name="id" value="all"/>
                <?php } else { ?>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                        <div class="row">
                            <button
                                class="col-md-11 col-sm-11 col-xs-11 btn btn-default dropdown-toggle white_space_normal_overwrite"
                                type="button" id="id"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span
                                    class="caret margin-right-2"></span><?php
                                $name = 'Category';
                                foreach ($categories as $oneCategory) {
                                    if ($oneCategory->CategoryId == $id) {
                                        $name = $oneCategory->CategoryName;
                                        break;
                                    }
                                }
                                echo $name;
                                ?>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="id">
                                <?php
                                foreach ($categories as $categoryValue) { ?>
                                <li data-dropdown="<?php echo $categoryValue->CategoryId; ?>"><a
                                        href="javascript: void(0)" title="<?php
                                    $categoryName = $categoryValue->CategoryName;
                                    echo $categoryName; ?>"><?php
                                        echo $categoryName;
                                        ?></a></li><?php } ?>
                                <li data-dropdown="all"><a
                                        href="javascript: void(0)" title="All Categories">All Categories</a></li>
                            </ul>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    </div>
                <?php } ?>


                <?php
                if($metricName == 'home' || $metricName == 'institute') { ?>
                    <input type="hidden" name="subid" value="all"/>
                <?php } else {
                if (isset($subCategories)) {
                    $class = "";
                } else {
                    $class = "cropper-hidden";
                } ?>
                <div class="col-md-2 <?php echo $class; ?>">
                    <div class="row">
                        <button
                            class="col-md-11 col-sm-11 col-xs-11 btn btn-default dropdown-toggle white_space_normal_overwrite"
                            type="button" id="subid"
                            data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="true"><span
                                class="caret margin-right-2"></span><?php
                            $name = 'All Subcategories';
                            foreach ($subCategories as $oneSubcategory) {
                                if ($oneSubcategory->SubCategoryId == $subid) {
                                    $name = $oneSubcategory->SubCategoryName;
                                    break;
                                }
                            }
                            echo $name;
                            ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="subid">
                            <?php
                            foreach ($subCategories as $categoryValue) { ?>
                            <li data-dropdown="<?php echo $categoryValue->SubCategoryId; ?>"><a
                                    href="javascript: void(0)" title="<?php
                                $subcategoryName = $categoryValue->SubCategoryName;
                                echo $subcategoryName; ?>"><?php
                                    echo $subcategoryName;
                                    ?></a></li><?php } ?>
                            <li data-dropdown="<?php if(strlen($subid) == 0) echo 'all'; else echo $subid; ?>"><a
                                    href="javascript: void(0)" title="All Categories">All Subcategories</a></li>
                        </ul>
                    </div>
                    <input type="hidden" name="subid" value="<?php echo $subid; ?>"/>
                </div><?php } ?>


                <!-- Main Exam  -->
                <div class="col-md-2 cropper-hidden">
                    <div class="row">
                        <button
                            class="col-md-11 col-sm-11 col-xs-11 btn btn-default dropdown-toggle white_space_normal_overwrite"
                            type="button" id="examId"
                            data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="true"><span
                                class="caret margin-right-2"></span><?php
                            $name = 'All Exam';
                            foreach ($subCategories as $oneSubcategory) {
                                if ($oneSubcategory->SubCategoryId == $subid) {
                                    $name = $oneSubcategory->SubCategoryName;
                                    break;
                                }
                            }
                            echo $name;
                            ?>
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="examId">
                            
                        </ul>
                    </div>
                    <input type="hidden" name="examId" value="<?php echo $examId; ?>"/>
                </div>

                <!-- Main Exam Sub Exam  -->
                <div class="col-md-2 cropper-hidden">
                    <div class="row">
                        <button
                            class="col-md-11 col-sm-11 col-xs-11 btn btn-default dropdown-toggle white_space_normal_overwrite"
                            type="button" id="subExamId"
                            data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="true"><span
                                class="caret margin-right-2"></span><?php
                            $name = 'All Subcategories';
                            foreach ($subCategories as $oneSubcategory) {
                                if ($oneSubcategory->SubCategoryId == $subid) {
                                    $name = $oneSubcategory->SubCategoryName;
                                    break;
                                }
                            }
                            echo $name;
                            ?>
                        </button>
                        <ul class="dropdown-menu max_height_300 overflow_y" aria-labelledby="subExamId">
                            
                        </ul>
                    </div>
                    <input type="hidden" name="subExamId" value="<?php echo $subExamId; ?>"/>
                </div>

                <div class="col-md-2">
                    <div class="row">
                        <button
                            class="col-md-11 col-sm-11 col-xs-11 btn btn-default dropdown-toggle white_space_normal_overwrite"
                            type="button" id="source"
                            data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="true"><span
                                class="caret margin-right-2"></span><?php echo ($source != '' && $source != 'all') ? ucfirst($source) : 'Source Application'; ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="source">
                            <li data-dropdown="desktop"><a href="javascript: void(0)">Desktop</a></li>
                            <li data-dropdown="mobile"><a href="javascript: void(0)">Mobile</a></li>
                            <li data-dropdown="all"><a href="javascript: void(0)">All Source Applications</a></li>
                        </ul>
                    </div>
                    <input type="hidden" name="source" value="<?php echo $source; ?>"/>
                </div>
                <?php if ($actionName != 'engagement' && $actionName != 'registration' && $actionName != 'traffic') { ?>
                    <div class="col-md-2">
                        <div class="row">
                            <button
                                class="col-md-11 col-sm-11 col-xs-11 btn btn-default dropdown-toggle white_space_normal_overwrite"
                                type="button" id="type"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span
                                    class="caret margin-right-2"></span>Response Type
                                <?php // listing-type is the name given to the url query string equivalent to 'type' which assumes either of the values paid / free?>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="type">
                                <li data-dropdown="free"><a href="javascript: void(0)">Free</a></li>
                                <li data-dropdown="paid"><a href="javascript: void(0)">Paid</a></li>
                                <li data-dropdown="all"><a href="javascript: void(0)">All (Free and Paid)</a></li>
                            </ul>
                        </div>
                        <input type="hidden" name="type" value="all"/>
                    </div>
                <?php }
                if (isset($metricName) && $metricName != 'all' && $actionName != 'engagement' && $actionName != 'traffic') { ?>
                    <div class="col-md-2 cropper-hidden">
                        <div class="row">
                            <button
                                class="col-md-11 col-sm-11 col-xs-11 btn btn-default dropdown-toggle white_space_normal_overwrite"
                                type="button"
                                id="widgetname"
                                data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="true"><span class="caret margin-right-2"></span>Widget
                                Name
                            </button>
                            <!--                        <ul class="dropdown-menu fixed_width_175" aria-labelledby="widget">-->
                            <ul class="dropdown-menu white_space_normal_overwrite max_height_300 overflow_y"
                                aria-labelledby="widgetname">
                                <?php
                                foreach ($widgets as $widget) { ?>
                                <li data-dropdown="<?php echo $widget->id; ?>"><a href="javascript: void(0)"
                                                                                  class="white_space_normal_overwrite"><?php echo ucfirst(preg_replace('/([a-z])(_){0,}([A-Z])/', '$1 $3', $widget->WidgetName)); ?></a>
                                    </li><?php } ?>
                                <li data-dropdown="all"><a
                                        href="javascript: void(0)" title="All Widgets">All Widgets</a></li>
                            </ul>
                        </div>
                        <input type="hidden" name="widgetname" value=""/>
                    </div>
                <?php } ?>

                <input name="aspect" type="hidden" value=""/>
                <div class="col-md-2">
                    <div class="row">
                        <button id="reportrange"
                                class="btn btn-default col-md-11 col-sm-11 col-xs-11 white_space_normal_overwrite"
                                style="background: #fff;"><b class="caret margin-right-2"></b>
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                            <span></span>
                        </button>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" id="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </div>
        </div>
    </div>

</div>

