<?php 
    $count = 0 ; // widget count per row
    // loop against all the widgets available on the page & render their views
    foreach($widgetConfigData as $k=>$widget){
        
        // check if widget will occupy full row?
        if($widget['occupyMultipleColumns']){
            $rowCompleted = TRUE; // indicate that a row has been completed
            $addClearfix = TRUE;
            $count = 0; // reset widget count per row
        }
        //.. 
        else{
            // check if a row has just finished & current widget count per row is 0
            if($rowCompleted)
            {
                $rowCompleted = FALSE; // row is not complete yet
            }
            else{
                if($count >0)
                {
                    $rowCompleted = TRUE; // there was already one widget in the row & we are adding one more to this
                }
            }
            $count++; // there is one more widget in current row
        }
        //echo "key:".$k.",rowC:".$rowCompleted.",count:".$count;
        // if row had finished or count has been reset
        if($count%2==0 && $rowCompleted)
        {
            // load that widget's view
            $this->load->view('widgets/'.$k,array('floatClass'=>'flRt'));
            echo '<div class="clearfix"></div>';
            $count = 0;
        }
        else{
            // load that widget's view
            $this->load->view('widgets/'.$k,array('floatClass'=>'flLt'));
        }
    }
    /* 
    // Popular Universities
    $this->load->view('widgets/countryHomePopularUniversity');
    
    // Popular Courses
    $this->load->view('widgets/countryHomePopularCourses');
    
    // Featured Colleges
    $this->load->view('widgets/countryHomeFeaturedColleges');
    
    // Guide Downloads
    $this->load->view('widgets/countryHomeGuideDownloads');
    
    // Filtered Colleges
    $this->load->view('widgets/countryHomeFilteredColleges');
    
    // Popular Articles
    $this->load->view('widgets/countryHomePopularArticles');
    */
?>
