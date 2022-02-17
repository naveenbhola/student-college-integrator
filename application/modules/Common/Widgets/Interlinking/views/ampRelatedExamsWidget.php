

<?php if(!empty($recommendedExams['data'])){ ?>
<section>
    <div class="tool-crd color-1">
        <h2 class="color-1 f16 heading-gap font-w6"><?php echo $recommendedExams['widgetHeading'];?></h2>
        <div class="pad-lft">
            <amp-carousel height="88" layout="fixed-height" type="carousel">
            <?php 
            foreach ($recommendedExams['data'] as $data) {
		$year = ($data['year']!='')?' '.$data['year']:'';
		$displayTitle = $data['exam_name'].$year;
                ?>
                <figure class="color-w paddng5 dv-wdt n-mg">
                    <figcaption class="f12 color-1 wh-sp">
                        <strong><?php echo substr(htmlentities($displayTitle),0,14); ?><?php if(strlen(htmlentities($displayTitle))>14){echo '...'; } ?></strong>
                        <p class="exam-nm"><?php echo substr(htmlentities($data['full_name']),0,27); ?><?php if(strlen(htmlentities($data['full_name']))>27){echo '...'; } ?></p>
                        <a href="<?php echo getAmpPageURL('exam',$data['exam_url']); ?>" class='ga-analytic' data-vars-event-name="Similar_Exams">View Exam Details</a>
                    </figcaption>
                </figure>
            <?php } ?>    
            </amp-carousel>
        </div>
    </div>
</section>
<?php } ?>    
