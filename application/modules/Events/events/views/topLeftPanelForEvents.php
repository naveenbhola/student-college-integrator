<div class="float_L" style="width:375px">
                <div class="bgAandAnsCorner">
                    <div style="width:280px;padding:5px 0 0 10px" class="fcGya">
			 <?php
                                                if(!(is_array($validateuser) && $validateuser != "false")) {
                                $onRedirect = base64_encode('/events/Events/showAddEvent');
                                $onClick = 'showuserLoginOverLay(this,\'EVENTS_EVENTSDETAIL_TOP_ADDEVENT\',\'redirect\',\''.$onRedirect.'\');return false;';
                                                }else {
                                                        if($validateuser['quicksignuser']==1) {
                                                        $base64url = base64_encode($_SERVER['REQUEST_URI']);
                                                        $onClick = 'javascript:location.replace(\'/user/Userregistration/index/<?php
                                                echo $base64url?>/1\');return false;';
                                                        } else {
                                                                $onClick = '';
                                                        }
                                                }
                                        ?>
                    	Are you aware of any education related important date? Let the world know about it. &nbsp;<input type="button" onClick="<?php echo $onClick?>;location.replace('/events/Events/showAddEvent');" class="adEnt" value="&nbsp;" />
                    </div>
                </div>
            </div>
