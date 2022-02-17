<div class="compare-widget">
	<h2>Popular <?=$baseEntityName?> college comparisons on Shiksha</h2>
	
	<ul>
        <?php
        $commonURLString = SHIKSHA_HOME.'/'.'resources'.'/'.'college-comparison'.'-';
		foreach( $popularList as $popular)
		{
			if($popular['course1_location'] !=''){
				$course1Location = '-'.str_replace(' ','-',strtolower($popular['course1_location'])); $course1_Location= ','.' '.$popular['course1_location'];}else{$course1Location = '';$course1_Location=''; }
			if($popular['course2_location'] !=''){$course2Location = '-'.str_replace(' ','-',strtolower($popular['course2_location'])); $course2_Location= ','.' '.$popular['course2_location'];}else{$course2Location = '';$course2_Location=''; }
			if($popular['course3_location'] !=''){$course3Location = '-'.str_replace(' ','-',strtolower($popular['course3_location'])); $course3_Location= ','.' '.$popular['course3_location'];}else{$course3Location = '';$course3_Location=''; }
			if($popular['course4_location'] !=''){$course4Location = '-'.str_replace(' ','-',strtolower($popular['course4_location'])); $course4_Location= ','.' '.$popular['course4_location'];}else{$course4Location = '';$course4_Location=''; }
			
			if ($popular['course3_id']!=0  &&  $popular['course4_id']!=0){
				echo '<li>' . '<a href="'.$commonURLString.str_replace(' ','-',strtolower($popular['course1_name'])).$course1Location.'-'.'vs'.'-'.str_replace(' ','-',strtolower($popular['course2_name'])).$course2Location.'-'.'vs'.'-'.str_replace(' ','-',strtolower($popular['course3_name'])).$course3Location.'-'.'vs'.'-'.str_replace(' ','-',strtolower($popular['course4_name'])).$course4Location.'-'.$popular['course1_id'].'-'.$popular['course2_id'].'-'.$popular['course3_id'].'-'.$popular['course4_id'].'">' . $popular['course1_name'] .$course1_Location.' '.'vs'.' '.$popular['course2_name'].$course2_Location.' '.'vs'.' '.$popular['course3_name'].$course3_Location.' '.'vs'.' '.$popular['course4_name'].$course4_Location.'</a>' . '</li>';
			}	
			else if( $popular['course3_id']==0 && $popular['course4_id']==0){		
				echo '<li>' . '<a href="'.$commonURLString.str_replace(' ','-',strtolower($popular['course1_name'])).$course1Location.'-'.'vs'.'-'.str_replace(' ','-',strtolower($popular['course2_name'])).$course2Location.'-'.$popular['course1_id'].'-'.$popular['course2_id'].'">' . $popular['course1_name'] .$course1_Location.' '.'vs'.' '.$popular['course2_name'].$course2_Location.'</a>' . '</li>';
			 }
			else{
				echo '<li>' . '<a href="'.$commonURLString.str_replace(' ','-',strtolower($popular['course1_name'])).$course1Location.'-'.'vs'.'-'.str_replace(' ','-',strtolower($popular['course2_name'])).$course2Location.'-'.'vs'.'-'.str_replace(' ','-',strtolower($popular['course3_name'])).$course3Location.'-'.$popular['course1_id'].'-'.$popular['course2_id'].'-'.$popular['course3_id'].'">' . $popular['course1_name'] .$course1_Location.' '.'vs'.' '.$popular['course2_name'].$course2_Location.' '.'vs'.' '.$popular['course3_name'].$course3_Location.'</a>' . '</li>';
		  
			}
		}
        ?>
        </ul>
</div>
		
                
            
