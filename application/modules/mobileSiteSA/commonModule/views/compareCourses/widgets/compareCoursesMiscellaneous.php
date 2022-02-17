<table border="0" cellpadding="0" cellspacing="0">
	<tr>
    	<th colspan="2" class="heading-bg">
        	<div class="compare-detail-content">
       	    	<h2><strong>Miscellaneous</strong></h2>
            </div>
        </th>  
    </tr>
    <?php if($classProfileFlag){?>
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>Students profile</strong>
            </div>
        </td>
    </tr>
    <tr>
         <?php foreach ($courseDataObjs as $courseObj) { 
                        $classProfileObject = $courseObj->getClassProfile();
                        $readMoreFlag = 0;
                        $nonEmptyCheckflag =0;
                        $emptyCheckflag =0; ?>
    	<td>
        	<div class="compare-detail-content smallData<?php echo $courseObj->getId(); ?>">
        		<?php       $getAverageWorkExperience= $classProfileObject->getAverageWorkExperience();
                            if(!empty($getAverageWorkExperience))
                            {
                                echo "<p>"."Average Work Experience "."</p>".$getAverageWorkExperience."</br>";
                                $readMoreFlag++;
                            }
                            else{ $emptyCheckflag++;}

                            $getAverageGPA= $classProfileObject->getAverageGPA();                                                                
                            if(!empty($getAverageGPA))
                            {
                                echo "<p>"."Average GPA "."</p>".$getAverageGPA."</br>";
                                $readMoreFlag++;
                            }
                            else{ $emptyCheckflag++;}

                            $getAverageXIIPercentage= $classProfileObject->getAverageXIIPercentage();                                                                
                            if(!empty($getAverageXIIPercentage))
                            {
                                echo "<p>"."Average XII Percentage"."</p>".$getAverageXIIPercentage."</br>";
                                $readMoreFlag++;
                            }
                            else{ $emptyCheckflag++;}

                            $getAverageGMATScore= $classProfileObject->getAverageGMATScore();                                                                
                            if(!empty($getAverageGMATScore) && $readMoreFlag <3)
                            {
                                echo "<p>"."Average GMAT Score"."</p>".$getAverageGMATScore."</br>";
                                $readMoreFlag++;
                            }
                            else{ $emptyCheckflag++;}

                            $getAverageAge= $classProfileObject->getAverageAge();                                                                
                            if(!empty($getAverageAge) && $readMoreFlag<3)
                            {
                                    echo "<p>"."Average Age"."</p>".$getAverageAge."</br>";
                                    $readMoreFlag++;
                            }
                            else{ $emptyCheckflag++;}

                            $getPercenatgeInternationalStudents= $classProfileObject->getPercenatgeInternationalStudents();                                                                
                            if(!empty($getPercenatgeInternationalStudents) && $readMoreFlag<3)
                            {
                                echo "<p>"."Percenatge International Students"."</p>".$getPercenatgeInternationalStudents."%"."</br>";
                                $readMoreFlag++;
                            }
                            else{ $emptyCheckflag++;}
                            if($emptyCheckflag==6)
                            { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php }
                            if($readMoreFlag >=3) {?><a href="javaScript:void(0);" onclick="showFullText(this,'<?php echo $courseObj->getId();?>');">+Read More<?php } ?></a>
            </div>
            <?php if($readMoreFlag >=3)  {?>
            <div class="compare-detail-content fullData<?php echo $courseObj->getId(); ?>" style="display:none;">
                <?php       $getAverageWorkExperience= $classProfileObject->getAverageWorkExperience();
                                                                if(!empty($getAverageWorkExperience))echo "<p>"."Average Work Experience "."</p>".$getAverageWorkExperience."</br>";
                                                                
                            $getAverageGPA= $classProfileObject->getAverageGPA();                                                                
                                                                if(!empty($getAverageGPA))echo "<p>"."Average GPA "."</p>".$getAverageGPA."</br>";
                                                                
                            $getAverageXIIPercentage= $classProfileObject->getAverageXIIPercentage();                                                                
                                                                                if(!empty($getAverageXIIPercentage))echo "<p>"."Average XII Percentage"."</p>".$getAverageXIIPercentage."</br>";
                                                                                
                            $getAverageGMATScore= $classProfileObject->getAverageGMATScore();                                                                
                                                                                if(!empty($getAverageGMATScore))echo "<p>"."Average GMAT Score"."</p>".$getAverageGMATScore."</br>";
                                                                                
                            $getAverageAge= $classProfileObject->getAverageAge();                                                                
                                                                                if(!empty($getAverageAge))echo "<p>"."Average Age"."</p>".$getAverageAge."</br>";
                                                                                
                            $getPercenatgeInternationalStudents= $classProfileObject->getPercenatgeInternationalStudents();                                                                
                                                                                if(!empty($getPercenatgeInternationalStudents))echo "<p>"."Percenatge International Students"."</p>".$getPercenatgeInternationalStudents."%"."</br>"; ?>
            </div>
            <?php } ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php } ?>
    <?php if($rankFlag){?>
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>Rank</strong>
            </div>
        </td>
    </tr>

    <tr>
        <?php foreach ($courseDataObjs as $courseObj) { ?>
    	<td>
            <?php if(!empty($courseRankDetails[$courseObj->getId()]['rank'])) { ?>
        	<div class="compare-detail-content">
        		<p>Ranked <?php echo $courseRankDetails[$courseObj->getId()]['rank']; ?> in </p>
				<a href="<?php echo $courseRankDetails[$courseObj->getId()]['rankURL']; ?>"><?php echo htmlentities($courseRankDetails[$courseObj->getId()]['rankName']); ?></a>
            </div>
            <?php } ?>  
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
     
    <?php } ?>   
    <!--<tr>
        <td colspan="2">
            <div class="compare-detail-content">
                <strong>Photos & Videos</strong>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="compare-detail-content">
                <p><a href="#"><i class="sprite photo-icn"></i>Photos (18)</a></p>
                <p><a href="#"><i class="sprite video-icn"></i>Videos (1)</a></p>                                    
            </div>
        </td>
    </tr>
    
     <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>Popularity Index</strong>
            </div>
        </td>
    </tr>
    <tr>
    	<td>
        	<div class="compare-detail-content">
        		<p>3.5</p>
            </div>
        </td>
        <td>
        	<div class="compare-detail-content">
        		<p>3.5</p>
            </div>
        </td>
    </tr> -->
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>University Website</strong>
            </div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) {  //check this
                            $univId   = $courseObj->getUniversityId(); ?>
    	<td>
            <?php if(!empty($universityContactDetails[$univId]['universityWebsite'])) { ?>
        	<div class="compare-detail-content">
                    <p><a target="_blank" rel="nofollow" href="<?php echo $universityContactDetails[$univId]['universityWebsite']; ?>"><?php echo formatArticleTitle($universityContactDetails[$univId]['universityWebsite'],20); ?></a></p>
            </div>
            <?php } else { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php } ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>    
    <?php if($univEmailFlag){ ?> 
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>University Email</strong>
            </div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) {
                            $univId   = $courseObj->getUniversityId(); ?>
    	<td>
            <?php if(!empty($universityContactDetails[$univId]['universityEmail'])) { ?>
        	<div class="compare-detail-content">
        		<p><a href="mailto:<?php echo $universityContactDetails[$univId]['universityEmail']; ?>"><?php echo formatArticleTitle($universityContactDetails[$univId]['universityEmail'],20); ?></a></p>
            </div>
            <?php } else { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php } ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php } ?>
    <?php if($intlStudentsFlag){ ?>
    <tr>
    	<td colspan="2" class="width100">
        	<div class="compare-detail-content">
        		<strong>International Students Website</strong>
            </div>
        </td>
    </tr>
    <tr>
    	<?php foreach ($courseDataObjs as $courseObj) {
                            $univId   = $courseObj->getUniversityId();
                            $universityObject  = $univDataObjs[$univId];
                            $internationalPageLink = $universityObject->getInternationalStudentsPageLink(); ?>
        <td>
            <?php if(!empty($internationalPageLink)) { ?>
        	<div class="compare-detail-content">
        		<p><a target="_blank" rel="nofollow" href="<?php echo $universityObject->getInternationalStudentsPageLink(); ?>"><?php echo formatArticleTitle($universityObject->getInternationalStudentsPageLink(),20); ?></a></p>
            </div>
            <?php } else { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php }?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
     <?php } ?>
    <?php if($fbLinkFlag){?>
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>Facebook Page</strong>
            </div>
        </td>
    </tr>
    <tr>
     <?php foreach ($courseDataObjs as $courseObj) {
                        $univId   = $courseObj->getUniversityId();
                        $universityObject  = $univDataObjs[$univId];
                        $facebooklink = $universityObject->getFacebookPage(); ?>
    	<td>
            <?php if(!empty($facebooklink)) { ?>
        	<div class="compare-detail-content">
        		<p><a target="_blank" rel="nofollow" href="<?php echo $universityObject->getFacebookPage(); ?>"><?php echo formatArticleTitle($universityObject->getFacebookPage(),20); ?></a></p>
            </div>
            <?php } else { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php }?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php } ?>
    <!-- <tr>
    	<td style="text-align:center">
        	<a href="#"><i class="sprite sml-shrtlist-icn"></i>Shortlist this Course</a>
        	<a style="margin-top:7px" class="btn btn-primary btn-full">
        	<i class="sprite bro-icn"></i> <span class="vam">Email Brochure</span></a>
            <div>
				<a class="rate-change-button text-center" style="padding:8px !important;" href="javascript:void(0);">Rate my chances</a>
</div>
        </td>
        <td style="text-align:center">
        	<a href="#"><i class="sprite sml-shrtlist-icn"></i>Shortlist this Course</a>
        	<a style="margin-top:7px" class="btn btn-primary btn-full">
        	<i class="sprite bro-icn"></i> <span class="vam">Email Brochure</span></a>
            <div>
				<a class="rate-change-button text-center" style="padding:8px !important;" href="javascript:void(0);">Rate my chances</a>
</div>
        </td>
    </tr> -->
</table>