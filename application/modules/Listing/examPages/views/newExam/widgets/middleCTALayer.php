     <div class="dflt__card mt__15 examTuple ps__rl no__pad get-Detwdget" id="">
            <?php if(array_key_exists('samplepapers', $snippetUrl)){?>
            <h2 class="mt__10 f20__clr3 cntr-txt">Get prep tips, practice papers, exam details and important updates</h2>
            <?php }else{ ?>
            <h2 class="mt__10 f20__clr3 cntr-txt">Get all exam details, important updates and more.</h2>
            <?php } ?>

            <div class="f14__clr3 pad__16 btns__col">
                <?php if(array_key_exists('samplepapers', $snippetUrl)){?>
                <a class="blue__brdr__btn dwn-esmpr" data-trackingKey="<?php echo $trackingKeyList['download_sample_paper_middle'];?>" title="Download previous question papers to read offline" ga-attr="DOWNLOAD_SAMPLE_PAPERS_MID_BUTTON">Get Question Papers</a>
                <?php }?>
                <a class="prime__btn mlt__5 dwn-eguide <?php if(isset($guideDownloaded) && $guideDownloaded){?> disable-btn <?php }?>" data-trackingKey="<?php echo $trackingKeyList['download_guide_middle'];?>" title="Download exam information to read offline" ga-attr="DOWNLOAD_MID_GUIDE">Download Guide Now</a>
            </div>
        </div>

