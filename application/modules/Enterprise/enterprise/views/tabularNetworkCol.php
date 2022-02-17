<div style="height:45px;background-image:url(/public/images/enterpriceBg.gif); background-repeat:repeat-x;padding:0 10px;">
Filter by: 
           <select name="countryFilter">
                <option value="">Country</option>
                <option value="2">India</option>
                <option value="3">USA</option>
           </select>
           <select name="cityFilter">
                <option value="">City</option>
           </select>
           <select name="alphaFilter">
                <option value="">Starting Alphabet</option>
                <option value="A">A</option>
                <option value="B">B</option>
           </select>
</div>
</div>
                            <div class="lineSpace_10">&nbsp;</div>
                            <div class="row normaltxt_11p_blk">	
                                <input type="hidden" id="methodName" value="getPopularNetworkCMSajax"/>             
                                <div id="paginataionPlace1" style="display:none;"></div>
                                <div class="boxcontent_lgraynoBG bld">
                                    <div style="background-color:#EFEFEF; border-right:1px solid #B0AFB4;padding-left:2px;width:100%">
                                        <div class="float_L" style="background-color:#D1D1D3; width:40%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;College Name</div>
                                        <div class="float_L" style="background-color:#EFEFEF; width:30%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;City</div>
                                        <div class="float_L" style="background-color:#EFEFEF; width:28%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF;">&nbsp; &nbsp;Networked-Users</div>
                                        <div class="clear_L"></div>
                                    </div>
                                </div>
                            </div>
							<div id="cmsNetworkTable" name="cmsNetworkTable" class="row normaltxt_11p_blk boxcontent_lgraynoBG" style="height:240px; overflow:auto">
                                                        </div>
						<!-- code for pagination start -->
                                <div class="pagingID" id="paginataionPlace2"></div>
							<div class="lineSpace_5">&nbsp;</div>
						<!-- code for pagination ends -->
<script>
getPopularNetworkCMSajax();
</script>
