<aside class="ranking-section">
    <div class="aside col-lg-3 pL0">
        <div class="left_nav"> 
            <div class="college-widget" id="rightWidget">
                <p class="bnr-hd">Featured College</p>
                <div class="college-banner">
                <?php 
                if(in_array($bannerTier, array(1,2,3))){
                    $bannerProperties = array('pageId'=>'IIMCP', 'pageZone'=>'TIER'.$bannerTier.'_DESKTOP_RIGHT');
                    $this->load->view('common/banner', $bannerProperties);
                }
                ?>
                </div>
                <div class="college-ranking">
                    <div class="-head">
                        <span>
                            <img align="left" src='<?php echo SHIKSHA_HOME."/public/images/rankings_mobile.png"; ?>' />
                            View <strong>Top MBA</strong> Colleges in India</span>
                        </span>
                    </div>
                    <div class="-foot">
                        <a target="_blank" onclick="trackEventByGA('IIMPredictorClick','ICP_Desktop_Ranking_Widget');" href='<?php echo SHIKSHA_HOME."/mba/ranking/top-mba-colleges-india/2-2-0-0-0"; ?>'>MBA Rankings</a>
                    </div>
                </div>
                <!--div class="college-locations">
                    <div class="-head">
                        <span>
                            <img src='<?php //echo SHIKSHA_HOME."/public/images/college-by-location_mobile.png"; ?>' />
                            View MBA Colleges by <strong>Location</strong>
                        </span>
                    </div>
                    <div class="-body">
                        <ul>
                            <li><a target="_blank" onclick="trackEventByGA('IIMPredictorClick','ICP_Desktop_Category_Bangalore_Widget');" href='<?php //echo SHIKSHA_HOME."/mba/colleges/mba-colleges-in-bangalore"; ?>'>Bangalore</a></li>
                            <li><a target="_blank" onclick="trackEventByGA('IIMPredictorClick','ICP_Desktop_Category_Chandigarh_Widget');" href='<?php //echo SHIKSHA_HOME."/mba/colleges/mba-colleges-in-chandigarh"; ?>'>Chandigarh</a></li>
                            <li><a target="_blank" onclick="trackEventByGA('IIMPredictorClick','ICP_Desktop_Category_Chennai_Widget');" href='<?php //echo SHIKSHA_HOME."/mba/colleges/mba-colleges-in-chennai"; ?>'>Chennai</a></li>
                            <li><a target="_blank" onclick="trackEventByGA('IIMPredictorClick','ICP_Desktop_Category_Delhi_Widget');" href='<?php //echo SHIKSHA_HOME."/mba/colleges/mba-colleges-in-delhi-ncr"; ?>'>Delhi</a></li>
                            <li><a target="_blank" onclick="trackEventByGA('IIMPredictorClick','ICP_Desktop_Category_Hyderabad_Widget');" href='<?php //echo SHIKSHA_HOME."/mba/colleges/mba-colleges-in-hyderabad"; ?>'>Hyderabad</a></li>
                            <li><a target="_blank" onclick="trackEventByGA('IIMPredictorClick','ICP_Desktop_Category_Kolkata_Widget');" href='<?php //echo SHIKSHA_HOME."/mba/colleges/mba-colleges-in-kolkata"; ?>'>Kolkata</a></li>
                            <li><a target="_blank" onclick="trackEventByGA('IIMPredictorClick','ICP_Desktop_Category_Mumbai_Widget');" href='<?php //echo SHIKSHA_HOME."/mba/colleges/mba-colleges-in-mumbai-all"; ?>'>Mumbai</a></li>
                            <li><a target="_blank" onclick="trackEventByGA('IIMPredictorClick','ICP_Desktop_Category_Pune_Widget');" href='<?php //echo SHIKSHA_HOME."/mba/colleges/mba-colleges-in-pune"; ?>'>Pune</a></li>
                        </ul>                           
                    </div>
                    <div class="-foot">
                        <a target="_blank" onclick="trackEventByGA('IIMPredictorClick','ICP_Desktop_Category_India_Widget');" href='<?php //echo SHIKSHA_HOME."/mba/colleges/mba-colleges-in-india"; ?>'>More Locations</a>
                    </div>
                </div-->
            </div>
        </div>
    </div>
</aside>