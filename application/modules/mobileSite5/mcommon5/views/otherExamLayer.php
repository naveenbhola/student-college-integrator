        <header id="page-header" class="clearfix">
            <div data-role="header" data-position="fixed" class="head-group ui-header-fixed">
                <a id="engineeringExamOverlayClose" href="javascript:void(0);" data-rel="back" ><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>
                <div class="title-text" id="engineeringExamOverlayHeading">Select Entrance Exam</div>
            </div>
        </header>

        <section class="layer-wrap fixed-wrap" style="height: 100%">
            <ul class="layer-list">

                        <li style="font-size: 0.9em;font-weight: bold;" onClick="exam1Click();">
                            <a href="javascript:void(0)">Management Exams</a>
                            <span id="exam1Arrow"><i class="msprite footer-arr-dwn"></i></span>
                        </li>                
                        <?php

                        global $mbaExamPageLinks;
                        foreach ($mbaExamPageLinks as $examGrade=>$examList){ foreach($examList as $exam){?>
                                <li class="randomClass1" style="display:none;"><a href="<?=$exam['url']?>"><?=$exam['name']?></a></li>
                        <?php }}
                        ?>
            </ul>
        </section>

        <section class="layer-wrap fixed-wrap" style="height: 100%; margin-top:0px;">
            <ul class="layer-list">
                        <li style="font-size: 0.9em;font-weight: bold;" onClick="exam2Click();">
                            <a href="javascript:void(0)">Engineering Exams</a>
                            <span id="exam2Arrow"><i class="msprite footer-arr-dwn"></i></spam>
                        </li>                

                        <?php
                        global $engineeringExamPageLinks;
                        foreach ($engineeringExamPageLinks as $examGrade=>$examList){ foreach($examList as $exam){?>
                                <li class="randomClass2" style="display:none;"><a href="<?=$exam['url']?>"><?=$exam['name']?></a></li>
                        <?php }}?>
            </ul>
        </section>
        
        
        <a href="javascript:void(0);" onclick="$('#engineeringExamOverlayClose').click();" class="cancel-btn">Cancel</a>
        
<script>
    function exam1Click(){
        $('.randomClass2').each(function(i, obj) {
            $(this).hide();
        });
        $('#exam2Arrow').html('<i class="msprite footer-arr-dwn"></i>');
        
        $('.randomClass1').each(function(i, obj) {
            if ($(this).is(':visible')) {
                $(this).hide();
                $('#exam1Arrow').html('<i class="msprite footer-arr-dwn"></i>');
            }
            else{
                $(this).show();                
                $('#exam1Arrow').html('<i class="msprite footer-arr-up"></i>');
            }
        });        
    }
    function exam2Click(){
        $('.randomClass1').each(function(i, obj) {$(this).hide();});
        $('#exam1Arrow').html('<i class="msprite footer-arr-dwn"></i>');
        
        $('.randomClass2').each(function(i, obj) {
            if ($(this).is(':visible')) {
                $(this).hide();
                $('#exam2Arrow').html('<i class="msprite footer-arr-dwn"></i>');
            }
            else{
                $(this).show();                
                $('#exam2Arrow').html('<i class="msprite footer-arr-up"></i>');
            }
        });        
    }
    
</script>