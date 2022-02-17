<div id="tuples">
<?php foreach($tupleData as $scholarshipId=>$tuple) { ?> 
<div class="card" id="tuple<?php echo $scholarshipId;?>">
    <div class="bg-col">
      <div class="titl-blck">
            <a class="schlr-link" target="_blank" href="<?php echo $tuple['url']; ?>"><?php echo htmlentities($tuple['name']); ?></a>
      </div>
    </div>
    <div class="dtls-div">
        <div class="dtls-bar">
                <div class="n-col-1">
                        <p class="f12-clr3">Applicable for</p>
                        <p class="f12-clr3 fnt-sbold"><?php echo htmlentities($tuple['applicableForStr']); ?> <?php echo ($tuple['category']=='external' && $tuple['applicableForStr2'] != ''?'<a class="lnk-clr ctry" href="Javascript:void(0);">'.$tuple['applicableForStr2'].'</a>':''); ?></p>
                        <input type="hidden" class="moreList" value="<?php echo base64_encode(json_encode($tuple['allApplicableCountries']));?>">
                </div>
                <div class="n-col-2">
                        <p class="f12-clr3">Course stream applicable</p>
                        <p class="f12-clr3 fnt-sbold"><?php echo $tuple['streamStr']; ?> <?php echo ($tuple['streamStr2'] != ''?'<a class="lnk-clr strm" href="Javascript:void(0);">'.$tuple['streamStr2'].'</a>':''); ?></p>
                        <input type="hidden" class="moreList" value="<?php echo base64_encode(json_encode($tuple['allStreams']));?>">
                </div>
                <div class="n-col-3">
                        <p class="f12-clr3">Scholarship type</p>
                        <p class="f12-clr3 fnt-sbold"><?php echo $tuple['type']; ?></p>
                </div>
                <div class="n-col-1">
                        <p class="f12-clr3">Max scholarship per student</p>
                        <p class="f12-clr3 fnt-sbold"><?php echo $tuple['amountStr1']; ?>
                        <?php if($tuple['amountStr2'] != ""){ ?>
                        <span class="f12-clr3 fnt-sbold"><?php echo $tuple['amountStr2']; ?></span>
                        <?php } ?>
                        </p>
                </div>
                <div class="n-col-2">
                        <p class="f12-clr3">No. of awards</p>
                        <p class="f12-clr3 fnt-sbold"><?php echo $tuple['awards']; ?></p>
                </div>
                <div class="n-col-3">
                        <p class="f12-clr3">Final Deadline</p>
                        <p class="f12-clr3 fnt-sbold"><?php echo $tuple['finalDeadline']; ?></p>
                </div>
        </div>

        <div class="right-btm">
            <?php if($tuple['restriction'] != ''){ ?>
            <div class="left-div">
                <p class="f12-clr3">Special restriction</p>
                <p class="f12-clr3 fnt-sbold">
                    <?php echo $tuple['restriction']; ?> <?php echo ($tuple['restriction2'] != ''?'<a class="lnk-clr restrict" href="Javascript:void(0);">'.$tuple['restriction2'].'</a>':''); ?>
                </p>
                <input type="hidden" class="moreList" value="<?php echo base64_encode(json_encode($tuple['allRestrictions']));?>">
            </div>
            <?php } if($tuple['brochureAvailable']){ ?>
            <div class="actn-btns"> 
            <a class="btns btn-prime n-btn-width schlrs-db" schrId="<?php echo $scholarshipId;?>" tKey="<?php echo $tupleDownloadBrochureTrackingId;?>" uAction="schr_db" actionType="SCP_DOWNLOAD_BROCHURE_TUPLE">Download Brochure</a>
            <a class="btns btn-trans n-btn-width" target="_blank" href="<?php echo $tuple['url']; ?>" >View & Apply <i class="arr__new"></i></a>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php }
    if(count($tupleData)==0){ ?>
    <p class="f18__sb__3 maax__rsult">Your search refinement resulted in zero results</p>
    <p class="f18__sb__3">Please <a class="resetLnk" href="Javascript:void(0);">reset filters</a> and try again</p>
<?php } ?>
    <div id="moreContainer" style="display: none;">
    <div class="dv-pad">
        <div class="inrAplCountries dv-ht">
            <!--<p class="cnt-dv" ><b> - </b><span></span></p>-->
            <p>-<span></span></p>
        </div>
    </div>
    </div>
</div>