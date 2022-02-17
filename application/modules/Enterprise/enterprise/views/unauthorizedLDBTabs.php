
<div class="clear_L"></div>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="lineSpace_10">&nbsp;</div>
            
            <div class="clearFix"></div>

<div style="text-align: center; color:red; font-size: larger; font-style: Sans-serif;">
   <?php
      if($course_name == 'B.E./B.Tech (Full Time)' || $course_name == 'MBA (Full Time)') {
   ?>
         <h1> <?php echo $message; ?> </h1>
   <?php
      } else {
   ?>
   <h1>  Currently you do not have access to view leads <?php if(!empty($actual_course_name)) {echo 'for '.$actual_course_name; } ?>. Please get in touch with Shiksha sales </h1>
   <?php
      }
   ?>
</center>
</div>

<div class="clear_L"></div>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="clearFix"></div>
