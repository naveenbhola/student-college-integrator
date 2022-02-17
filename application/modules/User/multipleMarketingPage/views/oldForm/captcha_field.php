                                <div style="padding:0 10px 0 20px">
                                	<div>Type in the characters you see in the picture below</div>
                                    <div class="lineSpace_5">&nbsp;</div>

                                    <div>
                                        <img align = "absmiddle" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&randomkey=<?php echo rand(); ?>&secvariable=seccodehome" width="100" height="34"  id = "<?php echo $prefix; ?>secureCode"/>
                                        <input type="text" style="margin-left:20px;width:135px;font-size:12px" name = "homesecurityCode" id = "homesecurityCode" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code"/>
                                    </div>
                                    <div>
                                        <div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
                                            <div  style="margin-left:125px" class="errorMsg" id= "<?php echo $prefix; ?>homesecurityCode_error"></div>
                                        </div>
                                    </div>
                                    <div class="lineSpace_5">&nbsp;</div>

                                    <?php
                                        if($logged !== "Yes" ) {
                                        ?>
                                    <div>
                                        <input type="checkbox" name="cAgree" id="cAgree" />
                                        I agree to the <a href="javascript:" onclick="popitup('/shikshaHelp/ShikshaHelp/termCondition');">terms of services</a> and <a href="javascript:"
onclick="popitup('/shikshaHelp/ShikshaHelp/privacyPolicy');">privacy policy</a>
                                        <div class="lineSpace_10">&nbsp;</div>
                                        <div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
                                            <div class="errorMsg" id= "cAgree_error" style="padding-left:24px"></div>
                                        </div>
                                   </div>
                                   <?php
                                    }
                                    ?>
                               </div>
