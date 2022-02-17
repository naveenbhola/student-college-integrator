<div class="clg-predctr">
    <div class="filter-container-div">
        <div class="rnk-rslt">
            <div class="rnk-headv1">
              <p class='rslt-count'><b><?=$total?> Results Found</b></p>
              <?php if($examName=='jee-mains'){?>
                <p class="rnk-mobile-txt"><?=$text?></p>
              <?php }?>
            </div>
            <div class="modify-src"><a href="javascript:void(0);" onclick="gaTrackEventCustom(GA_currentPage,'Modify','Response');showSearchForm1('<?php echo $examinationName;?>');">Modify</a></div>
        </div>
    <?php if($total > 0) { ?>
    <div class="table filterShare">
        <div class="table-cell">
            <a href ='javascript:void(0)' class="rslt-fltr">FILTERS</a>         
        </div>
        <div class="table-cell">
            <div class="addthis_inline_share_toolbox"></div>
        </div>
    </div>
    

    </div> <?php ?>
    <div class="rslt-dv">
        <?php $maxRoundNumbers = count($roundData); ?>
        <div class="prp-txt">
	    <ul>
	    <?php if($examName=='jee-mains'){?>
                <li><p>For IITs, NITs and IIITs the data for General Category is updated as per JoSAA <?php echo $examYear;?> Counselling.</p></li>
            <?php } ?>
	    <?php if($maxRoundNumbers > 1) { ?>
                <li><p>Rounds that you may crack are denoted by a <span class="tick-mrk"></span></p></li>
                <li><p>The earliest cleared round is denoted by a <span class="thumb-mrkBlk"></span></p></li>
            <?php } ?>
	    <li><p>Colleges that come under home state quota are denoted by <label class="nblink">Home State</label></p></li>
	    <?php if($examName!='jee-mains'){?>
		<li><p>Download application details, read reviews and compare colleges for courses you like.</p></li>
	    <?php } ?>
            </ul>        
        </div>
            <?php $this->load->view('V2/collegePredictorInner');?>
    <?php } else{ ?>
    </div> <?php //filter container closed ?>
    <div class="noRslt-Dv">
    <p class="noRslt-txt"> No results found. Your inputs did not match any college cutoff <?php echo (!empty($invertLogic) && $invertLogic==1)?"Score":"rank";?>.</p>
    <p> Suggestions: </p>
    <p>Try Relevent <?php echo (!empty($invertLogic) && $invertLogic==1)?"Score":"Rank";?> input by clicking "Modify Search" button.</p>  
    </div>
    <?php } ?>
</div>
