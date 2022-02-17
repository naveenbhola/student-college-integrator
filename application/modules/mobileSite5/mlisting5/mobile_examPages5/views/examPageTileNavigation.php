<?php
$sectionNamesMapping = $this->config->item("sectionNamesMapping");

$classMappingWithTile = array('home'             => 'exam-mini-sprite exam-icon-large',
                              'syllabus'         => 'exam-mini-sprite syllabus-icon-large',
                              'imp_dates'        => 'exam-mini-sprite date-icon-large',
                              'colleges'         => 'exam-mini-sprite college-icon-large',
                              'article'          => 'exam-mini-sprite article-icon-large',
                              'preptips'         => 'exam-mini-sprite preptips-icon-large',
                              'results'          => 'exam-mini-sprite result-icon-large'
                              );

//$backgroundColor = array('#47bdbe','#69d0d9','#3aa587','#4bbc8f','#80daa3','#b1a721','#c6b800','#ded27f');
$backgroundColor = array('#69d0d9','#c6b800','#e1918a','#1fa29d','#ded280','#c3626b','#47bdbd','#b1a721');

?>
<div class="grids clearfix">
        <ul>
            <?php
            $index = 0;
            foreach ($activeTileDetails as $activeTileDetail){
	        // don't show the nav link if its flag is off
        	if($activeTileDetail['show_link_in_menu'] == 0 ){
                    continue;
	        }
                if($activeTileDetail['name'] == 'discussion'){
                    continue;
                }
                //if($index == 0){
                  //  $background = "style='cursor:pointer;'";
                //}
                //else if($index >= 1){
                    $background = "style='cursor:pointer;background:".$backgroundColor[$index]."'";
                //}
                if($sectionNamesMapping[$activeTileDetail['name']] == 'Top Colleges') {
                    $sectionNamesMapping[$activeTileDetail['name']] = 'Colleges Accepting '.$examPageData->getExamName();
                }
            ?>
            <li <?=$background?>>
		<a <?php if($activeTileDetail['name'] == 'home'){?> href="javascript:void(0);" onclick="redirectAndScrollPage('<?php echo ${$activeTileDetail['name']}['url'];?>','<?php echo $activeTileDetail['name'];?>');trackEventByGAMobile('HTML5_TILE_<?php echo $activeTileDetail['name'];?>');"<?php }else{?> href="<?php echo ${$activeTileDetail['name']}['url'];?>" onclick="gaTrackEventCustom('HTML5_TILE_<?php echo $activeTileDetail['name'];?>', 'click', '', event, '<?php echo ${$activeTileDetail['name']}['url'];?>')" <?php }?>>
		    <h2><?=$sectionNamesMapping[$activeTileDetail['name']]?></h2>
		    <i class="<?php echo $classMappingWithTile[$activeTileDetail['name']];?>"></i>
		    <p><?=$activeTileDetail['description']?>

			<?php if($sectionNamesMapping[$activeTileDetail['name']]=='About Exam' && $examPageData->getExamName()=='SNAP'){?><br/><br/><span style="font-size:16px;">Registrations open for SNAP 2017 - </span><span onclick="window.open('http://www.snaptest.org/?utm_source=shiksha&utm_medium=exampageone&utm_campaign=shikshaep');" style="color:#0038a2;font-size:16px;">Apply Now</span><?php } ?>

			<?php if($sectionNamesMapping[$activeTileDetail['name']]=='About Exam' && $examPageData->getExamName()=='IBSAT'){?><br/><br/><span style="font-size:16px;">Registrations open for IBSAT 2017 - </span><span onclick="window.open('https://bit.ly/IBSAT2k17');" style="color:#0038a2;font-size:16px;">Apply Now</span><?php } ?>

                        <?php if($sectionNamesMapping[$activeTileDetail['name']]=='About Exam' && $examPageData->getExamName()=='XAT'){?><br/><br/><span style="font-size:16px;">XAT 2018 registrations open - </span><span onclick="window.open('http://bit.ly/2wW04JC');" style="color:#0038a2;font-size:16px;">Apply Now</span><?php } ?>

		    </p>
		</a>	
            </li>
            <?php
            $index++;
            } ?>
        </ul>
</div>
<div id="lastTile"></div>    
<script>
    function redirectAndScrollPage(url,pageType) {
      if (pageType=='home') {
        $('html,body').animate({ scrollTop: $('#lastTile').offset().top }, 500);
      }/*else{
        window.location = url;
      }*/
    }

</script>
              
