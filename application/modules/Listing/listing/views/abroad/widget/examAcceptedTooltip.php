<?php
    if($shortVersion===TRUE) $supportStyle = "width:154px !important;top:-48px !important;";
    if($coursepageWidget === TRUE) $supportStyle = "top:-63px !important;left:-70px !important; width: 154px !important;";
?>
<span class="accepted-tooltip" style="display:none;z-index:1;<?php echo $supportStyle ?>"><?=$examName?> is accepted but exact exam score is not published by the college</span>