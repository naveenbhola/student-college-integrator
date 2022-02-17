<html>
<head>
<script>
/*     
    function subst() {
        var vars = {};
        var valuePairs = document.location.search.substring(1).split('&');
        for (var i in valuePairs) {
            var valuePair = valuePairs[i].split('=', 2);
            vars[valuePair[0]] = decodeURIComponent(valuePair[1]);
        }

        var replaceClasses = [
            'frompage',
            'topage',
            'page',
            'webpage',
            'section',
            'subsection',
            'subsubsection',
        ];

        for (var i in replaceClasses) {
            var hits = document.getElementsByClassName(replaceClasses[i]);

            if (replaceClasses[i] == 'page' && vars[replaceClasses[i]] == 1) {
                document.getElementById("pdfFooter").style.display = "none";
            }    
        }
    }
*/    
</script>
<link rel="stylesheet" type="text/css" href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('ebrochureAbroad'); ?>" />
</head>
<body style="margin: 0; padding: 0">
<div class="footer" id="pdfFooter">
        	<table cellpadding="0" cellspacing="0" class="grid-table">
        	<tr>
            	<td style="background-color:#a0779f"> </td>
                <td style="background-color:#ae9bae"> </td>
                <td style="background-color:#b2b0bd"> </td>
                <td style="background-color:#b4aec6"> </td>
                <td style="background-color:#bcbbb7"> </td>
                <td style="background-color:#c0cda1"> </td>
                <td style="background-color:#e8bd55"> </td>
                <td style="background-color:#fab746"> </td>
                <td style="background-color:#ecc65b"> </td>
                <td style="background-color:#d0cc75"> </td>
                <td style="background-color:#bacac0"> </td>
                <td style="background-color:#f3c9cd"> </td>
                <td style="background-color:#deb0b0"> </td>
                <td style="background-color:#e0b19f"> </td>
                <td style="background-color:#f8a88f"> </td>
                <td style="background-color:#cf7b8a"> </td>
                <td style="background-color:#a0779f"> </td>
                <td style="background-color:#ae9bae"> </td>
                <td style="background-color:#b2b0bd"> </td>
                <td style="background-color:#b4aec6"> </td>
                <td style="background-color:#bcbbb7"> </td>
            </tr>
            </table>
            <div class="footer-child">
                <a href="https://studyabroad.shiksha.com/?utm_source=brochure-pdf&utm_medium=pdf&utm_campaign=pdf-footer-logo" style="background:none; text-indent:0; float:left"><img src="<?=SHIKSHA_HOME?>/public/images/footer-logo.gif" alt="" style="text-align:left"/></a>
                <div class="footer-course-name"><?=$course->getName()?></div>
            </div>
        </div>
</body>
</html>