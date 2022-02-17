<!-- Show the page Header -->    
<header id="page-header" class="header ui-header-fixed" data-role="header" data-tap-toggle="false"  data-position="fixed">
    <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger=true);?>
    <div id="selectionToolHeader">
      <div class="clearfix naukri-inner-wrap" id="defualtSelection">
	     <h1 class="career-title">MBA CAREER COMPASS</p> 
      </div>
      <div class="clearfix naukri-inner-wrap" id="customSelection" style="display: none;">
        <div class="flLt selection-criteria" id="customSelectionWidget">
          <span class="selected-field"><a href="#" class="selected-text">Finance</a><a href="#" class="remove-field">&times;</a></span>
        </div>
        <a href="javascript:void(0);" class="flRt modify-search-btn" onclick="NaukriToolComponent.scrollToContainer('criteria-cards', 90, 300);">Modify Search</a>
        <div class="clearfix"></div>
      </div>
    </div>
</header> 
<!-- End the Header page -->