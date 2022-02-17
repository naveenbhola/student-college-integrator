<?php
    $settingActive = ($subscrSetting!==""?'class="active"':'');
    $profileActive = ($subscrSetting!==""?'':'class="active"');
?>
<div class="tb-area" data-enhance="false">
    <ul class="profil-tb">
        <li <?php echo $profileActive; ?>><a href="Javascript:void(0);" class="profileTab">Profile</a></li>
        <li <?php echo $settingActive; ?>><a href="Javascript:void(0);" class="settingTab">Settings</a></li>
    </ul>
</div>