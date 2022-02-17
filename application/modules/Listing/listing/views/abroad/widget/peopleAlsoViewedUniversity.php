<?php $noOfSlides = (int)(count($alsoViewedUniversityData)/5);
      if(count($alsoViewedUniversityData)%5!=0){$noOfSlides++;}
?>

<div id="alsoViewedCoursesWidget">
        <div class="widget-wrap clearwidth" style="margin-top:10px;">
    <div class="institute-head clearwidth">
        <h3 class="font-14 flLt">Students who viewed this institute also viewed</h3>
        <?php if($noOfSlides>1){ ?>
        <div class="next-prev flRt">
            <span id="recoSlideYJxeAJ" class="flLt slider-caption">1 of <?=$noOfSlides;?></span>
            <a id="prevYJxeAJ" href="javascript:void(0)" class="prev-box" onclick="slideRecoLeft('YJxeAJ', true, true);"><i id="prevIconYJxeAJ" class="common-sprite prev-icon"></i></a>
            <a id="nextYJxeAJ" href="javascript:void(0)" class="next-box active" onclick="slideRecoRight('YJxeAJ', true, true);"><i id="nextIconYJxeAJ" class="common-sprite next-icon-active"></i></a>
        </div>
        <?php } ?>
            </div>
            
            
            <div class="vw-slider">
            <?php if($noOfSlides>1){ ?>
            <div class="prevArrow disable" id="prevArrowYJxeAJ" >
                <div class="common-sprite slide-prev-active flLt prev" onclick="slideRecoLeft('YJxeAJ', true, true);" ></div>
            </div>
            <?php } ?>
                        <div class="sldr-cont" style="width:924px; overflow: hidden;">
                            <ul id="slideContainerYJxeAJ"  style="width:12000px; overflow: hidden; position: relative;">
                                <?php foreach($alsoViewedUniversityData as $key=>$tupleData){ ?>
                                <li>
                                    <div class="slde-inf">
                                        <div class="det-img">
                                            <a href = "<?= $tupleData['url'];?>"><img src="<?= $tupleData['univImg'];?>" width="172" height="114"/></a>
                                        </div>
                                        <div class="vw-det">
                                            <a class="univ-cd" href="<?= $tupleData['url'];?>"><?= htmlentities(formatArticleTitle($tupleData['name'],35));?></a>
                                            <p><?= $tupleData['cityName'];?>, <?= $tupleData['countryName'];?></p>
                                            
                                            <a href="<?= $tupleData['url'];?>" class="vw-det-btn">View all <?php echo $tupleData['courseCount'] ?> <?php if($tupleData['courseCount']>1){echo 'courses';}else {echo 'course';}?></a>
                                        </div>
                                        </div>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
            <?php if($noOfSlides>1){?>
            <div class="nextArrow" id="nextArrowYJxeAJ">
                <div class="common-sprite slide-next-active flLt next" onclick="slideRecoRight('YJxeAJ', true,true);"></div>
            </div>
            <?php } ?>
            </div>
            
            
            <div class="clearwidth">
			    <ol class="carausel-bullets">
				<li id="recoSliderButton1YJxeAJ" onclick="changeRecoSlide(1, 'YJxeAJ', true,true);" class="active" style="cursor:pointer;"></li>
                                <?php for($i=0;$i<$noOfSlides-1;$i++){ ?>
                                <li id="recoSliderButton<?php echo ($i+2)?>YJxeAJ" onclick="changeRecoSlide(<?php echo ($i+2)?>, 'YJxeAJ', true,true);" style="cursor:pointer;"></li>
                                <?php } ?>
			    </ol>
			</div>
        </div>
    </div>

<script>
var slideWidth = 936;
var uniqueId = 'YJxeAJ';

if (typeof(numSlides) == 'undefined') {
	numSlides = {};
	currentSlide = {};
	firstSlide = {};
	lastSlide = {};
}

numSlides[uniqueId] = '<?php echo $noOfSlides;?>';
currentSlide[uniqueId] = 0;
firstSlide[uniqueId] = 0;
lastSlide[uniqueId] = (numSlides[uniqueId] - 1) * (-1);
</script>
