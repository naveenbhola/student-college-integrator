<div class="course-title" style="width:100%; margin-bottom:10px;">
    <h1><?php echo htmlentities($consultantObj->getName());?>
        <span class="shiksha-verified-info" style="position: relative;">
            <span style="font-size:14px; color:#ccc;">|</span>
            <i class="consultant-sprite verified-icon" onmouseover="showToolTipVerifiedConsultant(this)" onmouseout="showToolTipVerifiedConsultant(this)">  
            </i> Shiksha verified
            <div style="left: -57px !important; top:27px; display:none;" class="tooltip-info">
            <i class="common-sprite verified-up-pointer"></i>
                All Consultant information available on Shiksha is independently verified by internal audit team.
        </div>
        </span>
    </h1>
    <span class="establishd-year">
        Established in <?php echo $consultantObj->getEstablishmentYear();?>
    </span>
    <span style="font-size:14px; color:#ccc;">|</span>
    <span class="establishd-year">No. of employees: <?php echo $consultantObj->getEmployeeCount();?></span>
    
</div>
 <script>
    function showToolTipVerifiedConsultant(obj)
    {
        $j(obj).closest('span').find('.tooltip-info').toggle();
    }
</script>