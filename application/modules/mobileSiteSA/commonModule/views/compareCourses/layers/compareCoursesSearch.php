<div id="compareSearchContainer" data-role= "page" data-enhance="false" class="search-layer-container">
	<div class="src-cont">
		<div class="src-head">
			<p class="src-title">Find a college to compare</p>
			<a  href="javaScript:void(0);" id="closeCompareSearch" data-rel="back" class="src-rmv"><i class="search-sprite src-crss"></i></a>
		</div>
		
		<div class="src-pCont">
			<ul>
				<li class="src-hmp">
					<div class="src-fList pd0">
							<p  class="dst-block"><i class="search-sprite src-icn"></i></p>
							<input autofocus id="univSearchBox" name="univSearchBox" class="src-fld src-disableWht main-search-box" type="text" placeholder="Find a college" value="">
							<input type="hidden" id="autoSelectedUniv" value="">
							<a class="clrSearchBox hide" id="clearUnivSearchBox" href="Javascript:void(0);" ><i class="search-sprite rmvSml-icn"></i></a>
					</div>
					<div class="src-suggLst hide" id="univSuggs">
							<?php $this->load->view('compareCourses/layers/searchLayerWidgets/compareCoursesSearchSuggestions'); ?>
					</div>
				</li>
			</ul>  
		</div>
	</div> 
</div>