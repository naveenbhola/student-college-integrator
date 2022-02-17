<?php	
	$newsImage = isset($newsImage) && $newsImage != '' ? $newsImage : '/public/images/foreign-edu-calender.jpg';	
	$newsCaption = isset($newsCaption) && $newsCaption != '' ? $newsCaption : 'Featured Scholarships';		
	$newsPosition = isset($newsPosition) &&  $newsPosition!= '' ?  $newsPosition : 'left';
	$class = $newsPosition == 'left' ? 'float_L' : 'float_R';
    $newsCaption = 'Articles';
	$class='float_L';
?>
<div>
	<div class="careerOptionPanelBrd">
		<div class="careerOptionPanelHeaderBg">
			<h5><span class="blackFont fontSize_13p">Examinations for Studying in <?php echo $contentTitle; ?></span></h5>
		</div>
		<div style="line-height:5px">&nbsp;</div>		
		<div class="mar_full_10p" style="padding:10px 0px;display:block;<?php echo  isset($articlesPanelHeight)? 'height:'.$examStudyCountryPanelHeight .'px;' : '';?>" id="blogsPlace">
            <?php 
                foreach($exams as $exam) {
                    $examName = $exam['acronym'];
                    $examUrl = $exam['url'];
                    $examTitle = $exam['blogTitle'];
                    $examSnippet= ($exam['summary'] != '' ? $exam['summary'] : strip_tags($exam['blogText']));
                    $examSnippet= substr($examSnippet,0,45);
            ?>
				<div class="quesAnsBullets">
                <div><a href="<?php echo $examUrl; ?>" title="<?php echo $examTitle; ?>" class="bld"><?php echo $examName; ?></a></div>
                <div style="font-size:11px"><?php echo $examSnippet; ?>...</div>
				</div>
				<div class="lineSpace_10">&nbsp;</div>
            <?php
                }
            ?>
			</div>
		<div class="lineSpace_10">&nbsp;</div>
	</div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div style="display:none;">
    <div class="careerOptionPanelBrd">
        <div class="careerOptionPanelHeaderBg">
            <h5><span class="blackFont fontSize_13p">Financial Help Information for Studying in <?php echo $contentTitle; ?></span></h5>
        </div>
        <div style="line-height:5px">&nbsp;</div>		
        <div class="mar_full_10p" style="padding:10px 0px;display:block;<?php echo  isset($articlesPanelHeight)? 'height:'.$educationLoanPanelHeight .'px;' : '';?>" id="blogsPlace">
                <div>Following banks provide education loans:</div>
                <div class="lineSpace_10">&nbsp;</div>
                <div class="quesAnsBullets">
                    <div><a href="">HDFC Bank</a></div>
                </div>
                <div class="lineSpace_10">&nbsp;</div>
                <div class="quesAnsBullets">
                    <div><a href="">HSBC Bank</a></div>
                </div>
                <div class="lineSpace_10">&nbsp;</div>
                <div class="quesAnsBullets">
                    <div><a href="">ICICI Bank</a></div>
                </div>
        </div>
        <div class="lineSpace_10"></div>		
    </div>
</div>
