<div class="flot_col auto_clear">
    <table class="edu">
        <tr>
            <td colspan="2">
                <h3 class="title_of">Personal Information</h3>
                <p class="text_p">Your email and mobile information is never shown to other users.</p>
            </td>
        </tr>
        <tr class="tab_format">
            <td>
                <div class="edu_info"><?php echo $cityFlag ?'City':'Country';?></div>
            </td>
            <td>
                <div class="edu_name"><?php echo empty($cityOrCountryName) ?'-':$cityOrCountryName;?></div>
            </td>
        </tr>
        <tr class="tab_format">
            <td>
                <div class="edu_info">Mobile No</div>
            </td>
            <td>
                <div class="edu_name"><?php echo empty($userMobile) ?'-':$userMobile;?></div>
            </td>
        </tr>
        <tr class="tab_format">
            <td>
                <div class="edu_info">Email ID</div>
            </td>
            <td>
                <div class="edu_name"><?php echo empty($userEmail) ?'-':$userEmail;?></div>
            </td>
        </tr>
        <tr class="tab_format">
            <td>
                <div class="edu_info">User ID</div>
            </td>
            <td>
                <div class="edu_name user_id"><?php echo empty($profileDisplayName) ?'-':strtolower($profileDisplayName);?></div>
            </td>
        </tr>
    </table>

</div>