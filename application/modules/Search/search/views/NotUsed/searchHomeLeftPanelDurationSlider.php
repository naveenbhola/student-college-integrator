<div class="float_L bgsearchHeight" style="width:20%">
	<div class="OrgangeFont bld pd_left_10p">Course Duration</div>
	<div class="lineSpace_10">&nbsp;</div>
		<div class="mar_full_20p">
				<div  style="width:155px">
				<div class="float_L" style="width:75px" id="demo_min">Min</div>
				<div class="float_L txt_align_r" style="width:80px" id="demo_max">Max</div>
				<div class="clear_L"></div>
			</div>
<div id="demo_bg">
    <div id="demo_min_thumb"><img src="/public/images/sliderPoint.gif"></div>
    <div id="demo_max_thumb"><img src="/public/images/sliderPoint.gif"></div>
</div>
<div  style="width:155px">
	<div class="float_L" style="width:75px" id="demo_from"></div>
	<div class="float_L txt_align_r" style="width:80px" id="demo_to"></div>
	<div class="clear_L"></div>
</div>
			<div class="lineSpace_10">&nbsp;</div>
			<div style="width:155px">
				<div class="txt_align_c" id="durationDisplay" style="font-size:11px"></div>
			</div>
<!--p> 
<label>Min: <input type="text" id="demo_from" size="10" maxlength="10" value=""></label>

<label>Max: <input type="text" id="demo_to" size="10" maxlength="10" value=""></label>
</p-->
<!--button id="demo_btn">Update Slider</button>

<h3>Converted values:</h3>
<p id="demo_info"></p-->

<script type="text/javascript">
(function () {
    YAHOO.namespace('example');

    var Dom = YAHOO.util.Dom;

    // Slider has a range of 200 pixels
    var range = 150;

    // No ticks for this example
    var tickSize = 0;

    // We'll set a minimum distance the thumbs can be from one another
    var minThumbDistance = 0;
	<?php if($requestDurationMin!=-1){?>
    var cf = <?php echo ($durationData['maxCount']-min($durationData['minCount'],$requestDurationMin))?>/(range - 10);
	<?php } else {?>
    var cf = <?php echo ($durationData['maxCount']-$durationData['minCount'])?>/(range - 10);
	<?php }?>
	<?php if($requestDurationMin!=-1){?>
    var reconvert = function (val){
	return ((val - <?php echo min($durationData['minCount'],$requestDurationMin)?>)/cf);
	}
	<?php } else {?>
    var reconvert = function (val){
	return ((val - <?php echo $durationData['minCount']?>)/cf);
	}
	<?php }?>
    
    // Initial values for the thumbs
    var initValues = [reconvert(<?php if ($requestDurationMin!=-1) {echo $requestDurationMin;} else {echo $durationData['minCount'];} ?>),reconvert(<?php if ($requestDurationMax!=-1) {echo $requestDurationMax;} else { echo $durationData['maxCount'];} ?>)+10];

    // Conversion factor from 0-200 pixels to 100-1000
    // Note 20 pixels are subtracted from the range to account for the
    // thumb values calculated from their center point (10 pixels from
    // the center of the left thumb + 10 pixels from the center of the
    // right thumb)

    // Set up a function to convert the min and max values into something useful
    var convert = function (val) {
	<?php if($requestDurationMin!=-1){?>
	return (val * cf + <?php echo min($durationData['minCount'],$requestDurationMin)?>);
	<?php } else {?>
	return (val * cf + <?php echo $durationData['minCount']?>);
	<?php }?>
    };
   

    var normalize = function (val) {
	if(val>=240)
	{
		return (Math.round(val/240))*240;
	}	
	else if (val>=20)
	{
		return (Math.round(val/20))*20;
	} 
	else if (val>=5)
	{
		return (Math.round(val/5))*5;
	} 
	else if (val>=1)
	{
		return (Math.round(val/1))*1;
	} 
	else
	{
		return (Math.round(val*8))/8;
	}

	}
    var display = function (val) {
	if(val>=240)
	{
		return Math.round((val/240)) + " years";
	}	
	else if (val>=20)
	{
		return Math.round((val/20)) + " months";
	} 
	else if (val>=5)
	{
		return Math.round((val/5)) + " weeks";
	} 
	else if (val>=1)
	{
		return Math.round((val/1)) + " days";
	} 
	else
	{
		return Math.round((val*8)) + " hours";
	}
    }
    // Slider set up is done when the DOM is ready
    YAHOO.util.Event.onDOMReady(function () {
        var demo_bg = Dom.get("demo_bg"),
           // info    = Dom.get("demo_info"),
            from    = Dom.get("demo_from"),
            to      = Dom.get("demo_to");
            durationval      = Dom.get("duration");
	    minDuration = Dom.get("durationMin");
	    maxDuration = Dom.get("durationMax");
	    //durationDisplay = Dom.get("durationDisplay");
        // Create the DualSlider
        var slider = YAHOO.widget.Slider.getHorizDualSlider(demo_bg,
            "demo_min_thumb", "demo_max_thumb",
            range, tickSize, initValues);

        slider.minRange = minThumbDistance;
        
        // Custom function to update the text fields, the converted value
        // report and the slider's title attribute
        var updateUI = function () {
            from.innerHTML = display(convert(slider.minVal));
            to.innerHTML   = display(convert(slider.maxVal-10));
	    durationval.innerHTML=display(convert(slider.minVal))+' - '+display(convert(slider.maxVal-10));
try{	   document.getElementById('durationDisplay').innerHTML='Showing courses of duration from <span class="bld" style="color: rgb(253, 129, 3);">'+display(convert(slider.minVal))+'</span> to <span class="bld" style="color: rgb(253, 129, 3);">'+display(convert(slider.maxVal-10))+'</span>';} catch(e) {  }
            // Update the converted values and the slider's title.
            // Account for the thumb width offsetting the value range by
            // subtracting the thumb width from the max value.
            //var min = convert(slider.minVal),
              //  max = convert(slider.maxVal - 20);

            //info.innerHTML = "MIN: <strong>" + min + "</strong><br>" +
              //               "MAX: <strong>" + max + "</strong>";
            //demo_bg.title  = "Current range " + min + " - " + max;
        };
/*	
	var initializeUI = function () {
            from.innerHTML = display(<?php if ($requestDurationMin!=-1) {echo $requestDurationMin;} else {echo $durationData['minCount'];} ?>);
            to.innerHTML   = display(<?php if ($requestDurationMax!=-1) {echo $requestDurationMax;} else { echo $durationData['maxCount'];} ?>);

            // Update the converted values and the slider's title.
            // Account for the thumb width offsetting the value range by
            // subtracting the thumb width from the max value.
            //var min = convert(slider.minVal),
              //  max = convert(slider.maxVal - 20);

            //info.innerHTML = "MIN: <strong>" + min + "</strong><br>" +
              //               "MAX: <strong>" + max + "</strong>";
            demo_bg.title  = "Current range " + min + " - " + max;
        };
*/
	var refreshPage = function(){
		minDuration.value=normalize(convert(slider.minVal));
		maxDuration.value=normalize(convert(slider.maxVal-10));
    		Dom.get('subType').value = 'course';
		getAnotherPage(1,1,1);
	//	alert("mouseup encountered");
	}
        // Subscribe to the dual thumb slider's change and ready events to
        // report the state.
        slider.subscribe('ready', updateUI);
        slider.subscribe('change', updateUI);
        slider.subscribe('slideEnd', refreshPage);

        // Wire up the button to update the slider
        YAHOO.util.Event.on('demo_btn','click',function () {
            // Get the int values from the inputs
            var min = Math.abs(parseInt(from.value,10)|0),
                max = Math.abs(parseInt(to.value,10)|0);

            if (min > max) {
                var hold = min;
                min = max;
                max = hold;
            }

            // Verify the values are in range
            min = Math.min(min,range - 30);
            max = Math.max(Math.min(max,range),min + 10 + minThumbDistance);

            // Set the new values on the slider
            slider.setValues(min,max);
        });
        // Attach the slider to the YAHOO.example namespace for public probing
        YAHOO.example.slider = slider;
    });
})();
</script>

	</div>
</div>
