<!--Find Consultant Form Starts Here-->
<div class="findConsultantForm" id="findConsultantForm" <?php echo ($loggedIn=="true")?"style=\"display:none\"":"";?>>
    <h3>Let us find a Consultant for you</h3>
    <p>Fill this form to get personalized advice from our partner Consultants</p>
		<div id="studyAbroad" uniqueattr="StudyAbroadPage/studyAbroadreg">
			<script>
				addWidgetToAjaxList('user/Register_StudyAbroad/loadStudyAbroadRegistrationWidget','studyAbroad',Array());
			</script>
        </div>
    <div class="spacer20 clearFix"></div>
</div>
<!--Find Consultant Form Ends Here-->
<?php if($loggedIn=="true"){ ?>
<div style="float:left;"><div class="fb-like-box" data-href="https://www.facebook.com/shikshacafe" data-width="340" data-show-faces="true" data-border-color="#C8D6E8" data-stream="false" data-header="true"></div></div>
<?php } ?>
