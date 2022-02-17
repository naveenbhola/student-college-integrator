<section class="social-section">
    <ul class="social-links" style="margin: 0">
        <li style="width:100%">
            <a href = "javascript:void(0);" onclick="mngPage();$('#socialShareLayer').show(); var windowPos = $(window).scrollTop();var html = $('html');html.data('scroll-position', [0,windowPos]);html.data('previous-overflow', html.css('overflow'));html.css('overflow', 'hidden');window.scrollTo(0,windowPos); $('#page-header-container .header').show();return false;">
            <i class="icon-share"></i>Share
            </a>
		</li>
	</ul>
</section>

<script type="text/javascript">
function mngPage(){
	var browserType = window.navigator.userAgent;
    if((browserType.match(/Windows/g) != null || browserType.match(/Microsoft/g) != null)){
		var heightOfPanel = $(window).height();
        var headerHeight = $('#page-header').height();
        $('.ui-content').height(heightOfPanel - headerHeight + 1);
        $('.ui-content').css({'overflow':'hidden'});
        $('.ui-panel-dismiss').height(heightOfPanel - headerHeight + 1);
    }
}
</script>
