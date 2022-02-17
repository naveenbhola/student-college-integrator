<!--Start_Pagewrpper-->
<?php
  $recentTabNumbs = array(1,3);
?>

<?php if( $styleWrap =  ($questionDetailPage)?"width:960px !important;" : "" )?>
<div class="wrapperFxd" style="<?php echo $styleWrap;?>">
<!--Start_AnA_Tab_+_Search-->
    <div class="wdh100" style="position:relative">
	    <div id="ana_sTbBg" <?php if($tab_required_course_page):?>style="display:none;"<?php endif;?>>
		<div class="float_L" style="width:500px;">
			<?php if(!$questionDetailPage && !$collegePredictor) :?>
		    <?php if(isset($_REQUEST) && isset($_REQUEST['cx']) && isset($_REQUEST['cof'])){ echo "<a href=\"".SHIKSHA_ASK_HOME."\" class=\"selected\">Ask & Answer buzz</a>"; }
		    elseif(strpos($_SERVER['SCRIPT_URL'],'postQuestionFromCafeForm')){echo "<a href=\"".SHIKSHA_ASK_HOME."\" class=\"selected\">Ask & Answer buzz</a>";}
                    else if($tabselected==0) echo "<a href=\"#\" class=\"selected\">Ask & Answer buzz</a>"; else  echo  "<a href=\"".SHIKSHA_ASK_HOME."\">Ask & Answer buzz</a>";  ?>
		    <?php  if(in_array($tabselected,$recentTabNumbs)) echo "<a href=\"".SHIKSHA_ASK_HOME_URL."/questions\" class=\"selected\">Q &amp; A</a>"; else  echo  "<a href=\"".SHIKSHA_ASK_HOME_URL."/questions\">Q &amp; A</a>";  ?>
		    <?php  if($tabselected==6) echo "<a href=\"".SHIKSHA_ASK_HOME_URL."/discussions\" class=\"selected\">Discussions</a>"; else  echo  "<a href=\"".SHIKSHA_ASK_HOME_URL."/discussions\">Discussions</a>";  ?>
		    <?php  if($tabselected==7) echo "<a href=\"".str_replace("@tab@",7,$commonTabURL)."\" class=\"selected\">Announcements</a>"; else  echo  "<a href=\"".str_replace("@tab@",7,$commonTabURL)."\">Announcements</a>";  ?>
		    <?php if(is_array($validateuser) && ($validateuser != "false") && ($userGroup !== 'cms')) { ?>
			    <?php if($tabselected == 4) echo "<a href=\"#\" class=\"selected\" presentKey=\"".$myKey."\">My Q &amp; A</a>"; else  echo  "<a href=\"".str_replace("@tab@",4,$commonTabURL)."\" presentKey=\"".$myKey."\">My Q &amp; A</a>";  ?>
		    <?php } if($userGroup == 'cms'){
			      if($tabselected == 5)
				  echo "<a href=\"#\" class=\"selected\" >Editor's Pick</a>"; 
			      else  
				  echo  "<a href=\"".str_replace("@tab@",5,$commonTabURL)."\" >Editor's Pick</a>";
			  }
		      ?>

		      <?php endif;?>
		</div>
		<div class="clear_B">&nbsp;</div>
		
	   	</div>
		<?php 
			if(!$questionDetailPage && !$collegePredictor) {
			    //$this->load->view('home/homePageRightSearchPanel_quesDetail'); 
				if($_COOKIE['TYPEOFSEARCH'] == 'QER' || $typeOfSearch == "QER")
				{
					$this->load->view('common/qerSearchBar');
				}
				else
				{
					$this->load->view('common/googleSearchBar');
				}
			}
			
	      /*if(isset($tab_required_course_page) && $tab_required_course_page) {
	      	?>
	      	<?php if( $style =  ($questionDetailPage)?"padding: 15px 0px 0px 0px" : "padding: 15px 10px 0px 10px" )?>
		  <div style="<?php echo $style;?>">
		  <?php
		  if(isset($showCustomizedGNB) && $showCustomizedGNB === true) echo '<div id="cutomized-gnb">';
		  ?>
		  <?php
		       $cpgs_backLinkArray['AUTOSCROLL'] = 1;
		       echo Modules::run('coursepages/CoursePage/loadCoursePageTabsHeader', $subcat_id_course_page, $course_pages_tabselected, TRUE,$cpgs_backLinkArray ,TRUE); ?>
		 </div><?php
		 if(isset($showCustomizedGNB) && $showCustomizedGNB === true) echo '</div>';
	      }*/
	    ?>
	     	     
    </div>
</div>
<?php if($tab_required_course_page):?>
<script>if($('keyword')){ $('keyword').value = 'Search QnA'; $('keyword').setAttribute('default','Search QnA'); }</script>
<?php endif;?>
<!--End_AnA_Tab_+_Search-->
<div class="lh10"></div>
