<div style="font-family: Trebuchet MS,Arial,Helvetica">
	<div  style="font-size:18px">
		Find the best institute for yourself
	</div>
	<div style="margin-top:10px;margin-bottom:10px;font-size:14px;">
		We need a few details from you to suggest you relevant institutes
	</div>
	<div style="font-size:15px;margin-bottom:10px;font-weight:bold;">
            <!--
		<Study Preference
		<input type="radio" name="studyInput" id="studyInputIndia" onClick="$('studyAbroadForm').style.display='none'; $('studyIndiaForm').style.display='';" <?php //if($form!='studyAbroad') echo "checked"; ?>/>India
		<input type="radio" name="studyInput" id="studyInputAbroad"  onClick="$('studyIndiaForm').style.display='none'; $('studyAbroadForm').style.display='';" <?php //if($form=='studyAbroad') echo "checked"; ?>/>Abroad
            -->
	</div>
	<div id="studyIndiaForm" style="<?php if($form=='studyAbroad') echo "display:none"; ?>">
           <script>
                 //addWidgetToAjaxList('/registration/Forms/LDB','studyIndiaForm',Array());
                 addWidgetToAjaxList('/blogs/shikshaBlog/LDBWidget/0/<?php echo $regTrackingPageKeyId;?>','studyIndiaForm',Array());
           </script>
	</div>
	<!--div id="studyAbroadForm" style="<?php //if($form!='studyAbroad') echo "display:none"; ?>">
           <script>
                 //addWidgetToAjaxList('/registration/Forms/LDB/studyAbroad','studyAbroadForm',Array());                 
                 addWidgetToAjaxList('/blogs/shikshaBlog/LDBWidget/1','studyAbroadForm',Array());
           </script>
	</div-->
</div>
