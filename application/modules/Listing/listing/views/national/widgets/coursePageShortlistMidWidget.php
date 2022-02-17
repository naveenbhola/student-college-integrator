		<!--Shortlist - widget starts---->

						<div class="shortlist-widget" style="margin:0;">
                        	<div class="shortlist-widget-head">
                                <div class="flLt">
                                    <strong>Shortlist to make an informed decision about</strong>
                                    <p><?=html_escape($institute->getName())?></p>
                                </div>
							<!--<a href="#" class="flRt shortlist-remove-icon">&times;</a> -->
                                <div class="clearFix"></div>
                            </div>
                            <div class="shortlist-list clear-width">
                                	<ul>
                                    	<li>
                                        	<div class="shrtlist-icon-box"><i class="common-sprite placmnt-sml-icn"></i></div>
                                        	<strong>Find Placement Data </strong>
                                            <p>See where students end up after graduating</p>
                                        </li>
										<li>
	                                        <div class="shrtlist-icon-box"><i class="common-sprite readRvw-sml-icn"></i></div>
                                        	<strong>Read Reviews </strong>
                                            <p>See how alumni rate their college</p>
                                        </li>
										<li>
                                        	<div class="shrtlist-icon-box"><i class="common-sprite askCurrent-sml-icn"></i></div>
                                        	<strong>Ask Current Students </strong>
                                            <p>Get Answers from current students </p>
                                        </li>
										<li class="last">
                                        	<div class="shrtlist-icon-box"><i class="common-sprite getAlert-sml-icn"></i></div>
                                        	<strong>Get Alerts </strong>
                                            <p>Never miss a deadline of your target colleges</p>
                                        </li>
									</ul>
                                    <a href="javascript:void(0);" onclick="<?=($courseShortlistedStatus == 1 ? 'return false;' : '')?> gaTrackEventCustom('NATIONAL_COURSE_PAGE', 'shortlist', 'middle'); checkIfCourseIsAlreadyShortlisted(); globalShortlistParams = {courseId: <?=$typeId?>, pageType: 'ND_CourseListing', buttonId: this.id, shortlistCallback: 'shortlistCallbackForCoursePages',tracking_keyid :'<?=DESKTOP_NL_LP_COURSE_MID_SHORTLIST?>'}; checkIfCourseIsAlreadyShortlisted( function() { shikshaUserRegistration.showShortlistRegistrationLayer({courseId: <?=$typeId?>, source: 'ND_CourseListing'})});" id="shortListButtonMid" class="shrtlist-btn flRt <?=($courseShortlistedStatus == 1 ? 'shortlist-disable' : '')?>" style="padding:8px 28px; width:97px;"><i class="common-sprite shrtlist-star-icon"></i><span><?=($courseShortlistedStatus == 1 ? 'Shortlisted' : 'Shortlist')?></span>
                                    <span style="display: none;" id="shrtListCount"></span>
                                    </a>
                                </div>
                                <div class="clearFix"></div>
                        </div>
						<a target="_blank" id="midShortlistlink" href="<?=SHIKSHA_HOME.'/my-shortlist-home'?>" class="flRt mt5" style="margin-bottom: 20px;visibility:<?=($courseShortlistedStatus == 1 ? 'visible' : 'hidden')?>;">View Shortlist</a>
						<!--Shortlist - widget ends---->


