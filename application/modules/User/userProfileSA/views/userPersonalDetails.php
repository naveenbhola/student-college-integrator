<div class="flot_col clear_max personal_inf">
    <h3 class="title_of">Personal Information</h3>
    <p class="text_p">Your email and mobile information is never shown to other users.</p>
    <div class="split-divs">
        <div class="divide_col">
            <div class="edu_info"><?php echo $cityFlag ?'City':'Country';?></div>
            <div class="edu_name"><?php echo empty($cityOrCountryName) ?'-':$cityOrCountryName;?></div>
        </div>
        <div class="divide_col">
            <div class="edu_info">Mobile No</div>
            <div class="edu_name"><?php echo empty($userMobile) ?'-':$userMobile;?></div>
        </div>
        <div class="divide_col">
            <div class="edu_info">Email ID</div>
            <div class="edu_name" style="word-wrap:break-word;"><?php echo empty($userEmail) ?'-':$userEmail;?></div>
        </div>
        <div class="divide_col">
            <div class="edu_info">User ID</div>
            <div class="edu_name"><?php echo empty($profileDisplayName) ?'-':$profileDisplayName;?></div>
        </div>
    </div>
</div>