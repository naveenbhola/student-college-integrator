<div id="searchLayerContainer" class="search-layer-container" data-role= "page" data-enhance="false">
<div class="src-cont">
    	<div class="src-head">
				<p class="src-title">Start your college search</p>
				<a href="javascript:void(0);" id="mobileSearchClose" data-rel="back" class="src-rmv"><i class="search-sprite src-crss"></i></a>
        </div>
        
        <div class="src-pCont">
				<ul>
						<li class="src-hmp">
								<div class="src-fList pd0">
										<p  class="dst-block"><i class="search-sprite src-icn"></i></p>
										<input id="mainSearchBox" name="mainSearchBox" class="src-fld src-disableWht main-search-box" type="text" placeholder="Search College, Course or Exam" value="">
										<input type="hidden" id="autoSelectedMain" value="">
										<a class="clrSearchBox hide" id="clearMainSearchBox" href="Javascript:void(0);" ><i class="search-sprite rmvSml-icn"></i></a>
								</div>
								<div class="src-suggLst hide" id="mainSuggs">

								</div>
						</li>
						<li class="src-location">
								<div class="src-fList src-disableFld pd0">  
										<p class="dst-block"><i class="search-sprite dst-icn"></i> </p>
										<ul class="auto-div">
												<li class="hide" id="firstLocLabel"><a class="sel-link" href="Javascript:void(0);"><span></span> <i class="search-sprite rmvSml-icn"></i></a></li>
												<li class="hide" id="locCountLabel"><a class="sel-link more-link" href="#">+<span></span> More</a></li>
												<li class="loc-input"><input id="locInput" disabled="disabled" type="text" class="src-fld width100Per" placeholder="Location" value=""></li>
												<li class="hide loc-input" id="univLocationLabel"><span class="cty-linhght">City, Country</span></li>
										</ul>
										<input type="hidden" id="autoSelectedLocation" value="">
										<a class="clrSearchBox hide" id="clearLocSearchBox" href="Javascript:void(0);" ><i class="search-sprite rmvSml-icn"></i></a>
								</div>
								<div class="src-suggLst hide" id="locSuggs">
										<?php //$this->load->view('commonModule/layers/searchLayerWidgets/locationSearchSuggestions'); ?>
								</div>
						</li>
						<?php $this->load->view("commonModule/layers/searchLayerWidgets/advancedSearchOptions"); ?>
                        <input type="hidden" id="fromPage" value="<?php echo $beaconTrackData['pageIdentifier']; ?>">
						<li>
								<a href="javaScript:void(0);" id="searchGoButton" class="aSrc-btn">Search</a>
						</li>
				</ul>  
        </div>
</div> 
</div>