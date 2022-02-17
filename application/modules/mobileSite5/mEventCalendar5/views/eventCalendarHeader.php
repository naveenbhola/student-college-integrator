<!-- Show the page Header -->    
<header id="page-header" class="header ui-header-fixed" data-role="header" data-tap-toggle="false" data-position="fixed">
    <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger=true);?>
    <section class="cale-header" id="cale-header">
        <span class="cl-txt">
            <h1 class="cl-h2"><?php echo $m_meta_title;?></h1>
        </span>
        <ul class="cl-topNavFltrs">
            <li class="cl-filtr1"><a href="#userSetAlert_layerContainer" id="userSetAlert_showLayer" data-transition="slide" data-rel="dialog" data-inline="true"><i class="cale-sprite cl-msgalert"></i></a></li>
			<li class="cl-filtr2"><a href="#eventFilter" data-transition="slide" data-rel="dialog" data-inline="true" onclick="setExamsOnFilter(eventExamId);"><i class="cale-sprite cl-filters"></i></a></li>
            <li class="cl-filtr3">
				<a id="showCalendar" href="javascript:void(0);" onclick="showCalendar();"><i class="cale-sprite cl-calendarWhte"></i></a>
			</li>
            <li class="cl-filtr4"><a id="addEventSection" href="#userAddEvent_layerContainer" onclick="prepareAddEventForm();" data-transition="slide" data-rel="dialog" data-inline="true"><i class="cale-sprite cl-plusicn"></i></a></li>
        </ul>
        <p class="clr"></p>
	<div class="calendarToSelectBox" style=" position: relative;">
                        <div id="calendarToSelectDate">

                </div></div>

    </section>
</header>
<!-- End the Header page -->
