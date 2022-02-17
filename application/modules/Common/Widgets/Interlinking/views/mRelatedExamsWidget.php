<?php 
if(!empty($recommendedExams['data'])){
    ?>
    <div class="art-sldr">
        <h2 class="art-head"><?php echo $recommendedExams['widgetHeading'];?></h2>
        <div class="art-carousel">
            <ul>
                <?php 
                foreach ($recommendedExams['data'] as $data) {
			$year = ($data['year']!='')?' '.$data['year']:'';
			$displayTitle = $data['exam_name'].$year;
                    ?>
                    <li>
                        <div class="crs-bx">
                            <p>
				<a style="color:#333;font-size: 12px;" href="<?php echo $data['exam_url']; ?>" ga-page="<?php echo $GA_PageType.'_Similar_Exams';?>" ga-attr="Similar_Exams" ga-optlabel="<?php echo $GA_Device.'_Similar_Exams'; ?>"> 
					<?php echo substr(htmlentities($displayTitle),0,14); ?><?php if(strlen(htmlentities($displayTitle))>14){echo '...'; } ?>
				</a>
			    </p>
                            <span class="exam_name" ><?php echo substr(htmlentities($data['full_name']),0,27); ?><?php if(strlen(htmlentities($data['full_name']))>27){echo '...'; } ?></span>
                            <a href="<?php echo $data['exam_url']; ?>" ga-page="<?php echo $GA_PageType.'_Similar_Exams';?>" ga-attr="Similar_Exams" ga-optlabel="<?php echo $GA_Device.'_Similar_Exams'; ?>">View Exam Details</a>
                        </div>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
    <?php
}
?>
