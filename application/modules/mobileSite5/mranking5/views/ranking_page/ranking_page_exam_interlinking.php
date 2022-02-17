<?php if(is_array($examWidgetData) && count($examWidgetData) > 0){ ?>
    <section class="top__26 more__about__exams">
        <h2 class="f16__semi clr__1">Popular <?php echo $rankingPage->getName();?> Exams</h2>
        <div class="rank__slider">
            <div class="slide__data clear__float">
                <?php foreach ($examWidgetData as $id => $data) { 
			$year = ($data['year'] != '')?' '.$data['year']:'';
			$name = $data['name'].$year;
			?>
                    <a class="exam__cards" href="<?=$data['url']; ?>" ga-attr="EXAMINTERLINK">
                        <p class="f12__semi clr__0 btm__5"><?php echo cutString($name, 27);?></p>
                        <!--<p class="f12__normal clr__6 btm__5 hid" ><?php echo cutString($data['fullName'], 110);?></p>-->
                        <span class="f12__semi fit__in__block dtls__txt">View Exam Details </span>
                    </a>
                <?php } ?>
            </div>
        </div>
    </section>
<?php } ?>
<script>
    var examWidgetCount = <?php echo count($examWidgetData); ?>;
    var rankingPage     = '<?php echo $rankingPage->getName(); ?>';
</script>
