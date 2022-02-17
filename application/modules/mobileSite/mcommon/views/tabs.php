    <div id="tab" style="display:none;">
        <ul>
        <li <?php if
        ($HomePageType == 'india') { echo ' class="active" ';} else {echo "";} ?> ><a href="<?php echo SHIKSHA_HOME;?>" >Study in India</a>
    </li>
    <li <?php if
    ($HomePageType == 'abroad') { echo ' class="active" ';} else {echo "";} ?> ><a href="<?php echo SHIKSHA_HOME . '/mcommon/MobileSiteHome/renderHomePage/abroad'; ?>" >Study Abroad</a>
    </li>
        </ul>
    </div>
