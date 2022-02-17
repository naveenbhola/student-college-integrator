<?php
if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
	foreach ($headerContentaarray as $content) {
		echo $content;
	}
}

if($salesUser == 'Admin') {
	$message = "You can check All detailed performance reports here.";
}
elseif($salesUser == 'Manager') {
	$message = "You can check your Sales Executive's and Client's detailed performance reports here.";
}
elseif($salesUser == 'Executive') {
	$message = "You can check your Client's detailed performance reports here.";
}
else {
	$message = "You can check your detailed performance reports here.";
}
?>
<script>isSmartAbroad = <?php echo $_COOKIE['SMARTInterfaceMode'] == 'Abroad' ? '1' : '0'; ?>;</script>

<div id="content-child-wrap">
	<div id="smart-content">
		
		<?php if($_COOKIE['SMARTDualInterface'] == 'Yes') { ?>
		
		<h2 style='float:left; width:600px; line-height: 120%;'>
			Welcome <span style="color: blue;"><?php echo trim($dynamicTitle); ?></span>. <br /><span style='font-size:14px; color:#555;'><?php echo $message; ?> </span>
		</h2>
		
		<div style='float:right; margin-right: 0px;'>
			<?php if($_COOKIE['SMARTInterfaceMode'] == 'National') { ?>
				<a href='/smart/SmartMis/changeSMARTInterfaceMode/Abroad/report' style='display: block; float:right; padding:10px; background:#f0f0f0; border:0px solid #ccc; border-left:none;'>Abroad</a>
				<span style='display: block; float:right; padding:10px; background:#4175F0; color:#fff; border:0px solid #ccc;'>
					National
				</span>
			<?php } else { ?>		
				<span style='display: block; float:right; padding:10px; background:#4175F0; color:#fff; border:0px solid #ccc;'>
					Abroad
				</span>
				<a href='/smart/SmartMis/changeSMARTInterfaceMode/National/report' style='display: block; float:right; padding:10px; background:#f0f0f0; border:0px solid #ccc; border-left:none;'>National</a>
			<?php } ?>
			
		</div>
		<div style='clear:both;'></div>
		<?php } else { ?>
		<h2>
			Welcome <span style="color: blue;"><?php echo trim($dynamicTitle); ?></span>. <br /><span style='font-size:14px; color:#555;'><?php echo $message; ?> </span>
		</h2>
		<?php } ?>
		
		
		<h5>Quick Report</h5>
		<form id="report_Form" action = "/smart/SmartMis/renderReport" method="POST" autocomplete="off" onsubmit="return false;" >
			<div id="smart-child-cont">
				<ul class="smart-form">
					<li>
						<label>Report Type:</label>
						<div class="form-filed">
							<select id="reporttype" name="reporttype" onchange="showHideReportFields();showHideReportTypes();">
								<option value="response" selected="selected">Listing & Response Report</option>
								<option value="leads">Leads Report</option>
								<option value="credit">Credit Report</option>
								<option value="login">Login & Session Report</option>
								<option value="porting">Lead Porting Report</option>
								<option value="response_porting">Response Porting Report</option>
								<option value="responselocation">Response - Location Report</option>
							</select>
						</div>
					</li>
				</ul>
				<?php echo $reportDropdowns; ?>
				<ul class="smart-form">
					
					<li>
						<label>Select Duration:</label>
						<div class="form-filed">
                                                    <input type="radio" name="duration" value="fixed" checked="checked" onclick="timeTxtBoxChange(this)" />
							<select id="fixedduration" name="fixedduration" style="width: 170px">
								<option value="7" selected="selected">Last 7 Days</option>
								<option value="15">Last 15 Days</option>
								<option value="30">Last 30 Days</option>
								<option value="90">Last 90 Days</option>
								<option value="thisMonth">This Month (<?php echo date("M"); ?>)</option>
								<option value="lastMonth">Last Month (<?php echo date("M",strtotime("-1 month",time())); ?>)</option>
							</select>

							<div class="spacer15 clearFix"></div>
							<input type="radio" id="range" name="duration" value="range" onclick="timeTxtBoxChange(this)" /> <strong>Enter Date Range</strong>
							<div class="spacer8 clearFix"></div>
							<div class="date-range">
								<label>From</label>
								<input type="text" value="yyyy-mm-dd" readonly="true" disabled="true" name="timefilter[from]" id="timerange_from" />
								<i class="icon-cal" id="timerange_from_img" onclick="timerangeFrom();" style="cursor: pointer;"></i>
							</div>

							<div class="date-range">
								<label>To</label>
								<input type="text" value="yyyy-mm-dd" readonly="true" disabled="true" name="timefilter[to]" id="timerange_to" />
								<i class="icon-cal" id="timerange_to_img" onclick="timerangeTo();" style="cursor: pointer;"></i>
							</div>
						</div>
					</li>
					
					<li>
						<label>View By:</label>
						<div class="form-filed">
							<select name="timePeriod" id="timePeriod">
								<option value="summarybyinstitute" >Summary By <?php echo $isSmartAbroad ? "University" : "Institute"; ?></option>
								<option value="summary">Summary By Course</option>
								<option value="daily">Daily</option>
								<option value="weekly">Weekly</option>
								<option value="monthly">Monthly</option>
								<option value="quarterly">Quarterly</option>
								<option value="yearly">Yearly</option>
							</select>
						</div>
					</li>
					
					<li>
                                            <div id="check"> 
						<label>Select Report Format:</label>
						<div id ="reportFormat" class="form-filed" style="padding-top: 7px">
						<input type="radio" id ="html" name="reportFormat" checked="checked" value="html" /> HTML 
						<input type="radio" id ="csv" name="reportFormat" value="csv" /> CSV 
                                                </div> <div class="clearFix spacer10"></div>
							
						
                                                </div>
					</li>
                                        
                                        <li>
						<div>
                                                    <label></label>
							<input type="button" value="Generate" uniqueattr="SmartForm/GenerateReport" class="formbutton orange-button" disabled="disabled" onclick="generateReport();" />
							
							<span id="ajaxLoader" style="margin-left: 10px; display: none">
							<img src="/public/images/ldb_ajax-loader.gif" />
							</span>
						</div>
					</li>
				
				</ul>
				<div class="clearFix"></div>
			</div>
			<input type='hidden' name='isSmartAbroad' value='<?php echo $isSmartAbroad; ?>' />
		</form>
	</div>
</div>

<div id="reportHolder"></div>	
<?php
if(is_array($footerContentaarray) && count($footerContentaarray)>0) {
	foreach ($footerContentaarray as $content) {
		echo $content;
	}
}
?>

<script>
$(function(){
    $("table").stickyTableHeaders();
});

/*! Copyright (c) 2011 by Jonas Mosbech - https://github.com/jmosbech/StickyTableHeaders
    MIT license info: https://github.com/jmosbech/StickyTableHeaders/blob/master/license.txt */

;(function ($, window, undefined) {
    'use strict';

    var pluginName = 'stickyTableHeaders';
    var defaults = {
            fixedOffset: 0
        };

    function Plugin (el, options) {
        // To avoid scope issues, use 'base' instead of 'this'
        // to reference this class from internal events and functions.
        var base = this;

        // Access to jQuery and DOM versions of element
        base.$el = $(el);
        base.el = el;

        // Cache DOM refs for performance reasons
        base.$window = $(window);
        base.$clonedHeader = null;
        base.$originalHeader = null;

        // Keep track of state
        base.isCloneVisible = false;
        base.leftOffset = null;
        base.topOffset = null;

        base.init = function () {
            base.options = $.extend({}, defaults, options);

            base.$el.each(function () {
                var $this = $(this);

                // remove padding on <table> to fix issue #7
                $this.css('padding', 0);

                $this.wrap('<div class="divTableWithFloatingHeader"></div>');

                base.$originalHeader = $('thead:first', this);
                base.$clonedHeader = base.$originalHeader.clone();

                base.$clonedHeader.addClass('tableFloatingHeader');
                base.$clonedHeader.css({
                    'position': 'fixed',
                    'top': 0,
                    'z-index': 1, // #18: opacity bug
                    'display': 'none'
                });

                base.$originalHeader.addClass('tableFloatingHeaderOriginal');

                base.$originalHeader.after(base.$clonedHeader);

                // enabling support for jquery.tablesorter plugin
                // forward clicks on clone to original
                $('th', base.$clonedHeader).click(function (e) {
                    var index = $('th', base.$clonedHeader).index(this);
                    $('th', base.$originalHeader).eq(index).click();
                });
                $this.bind('sortEnd', base.updateWidth);
            });

            base.updateWidth();
            base.toggleHeaders();

            base.$window.scroll(base.toggleHeaders);
            base.$window.resize(base.toggleHeaders);
            base.$window.resize(base.updateWidth);
        };

        base.toggleHeaders = function () {
            base.$el.each(function () {
                var $this = $(this);

                var newTopOffset = isNaN(base.options.fixedOffset) ?
                    base.options.fixedOffset.height() : base.options.fixedOffset;

                var offset = $this.offset();
                var scrollTop = base.$window.scrollTop() + newTopOffset;
                var scrollLeft = base.$window.scrollLeft();

                if ((scrollTop > offset.top) && (scrollTop < offset.top + $this.height()-base.$clonedHeader.height())) {
                    var newLeft = offset.left - scrollLeft;
                    if (base.isCloneVisible && (newLeft === base.leftOffset) && (newTopOffset === base.topOffset)) {
                        return;
                    }

                    base.$clonedHeader.css({
                        'top': newTopOffset,
                        'margin-top': 0,
                        'left': newLeft,
                        'display': 'block'
                    });
                    base.$originalHeader.css('visibility', 'hidden');
                    base.isCloneVisible = true;
                    base.leftOffset = newLeft;
                    base.topOffset = newTopOffset;
                }
                else if (base.isCloneVisible) {
                    base.$clonedHeader.css('display', 'none');
                    base.$originalHeader.css('visibility', 'visible');
                    base.isCloneVisible = false;
                }
            });
        };

        base.updateWidth = function () {
            // Copy cell widths and classes from original header
            $('th', base.$clonedHeader).each(function (index) {
                var $this = $(this);
                var $origCell = $('th', base.$originalHeader).eq(index);
                this.className = $origCell.attr('class') || '';
                $this.css('width', $origCell.width());
            });

            // Copy row width from whole table
            base.$clonedHeader.css('width', base.$originalHeader.width());
        };

        // Run initializer
        base.init();
    }

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName, new Plugin( this, options ));
            }
        });
    };

})(jQuery, window);
</script>
