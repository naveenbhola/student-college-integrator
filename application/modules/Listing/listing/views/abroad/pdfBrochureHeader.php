<html>
<head>
<script>
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
                document.getElementById("pdfHeader").style.display = "none";
            }    
        }
    }
</script> 
<link rel="stylesheet" type="text/css" href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('ebrochureAbroad'); ?>" />
</head>
<body style="margin: 0; padding: 0;" onload="subst()">
<div class="header" id="pdfHeader">
        	<table cellpadding="0" cellspacing="0" class="grid-table">
        	<tr>
            	<td style="background-color:#a0779f">&nbsp;</td>
                <td style="background-color:#ae9bae">&nbsp;</td>
                <td style="background-color:#b2b0bd">&nbsp;</td>
                <td style="background-color:#b4aec6">&nbsp;</td>
                <td style="background-color:#bcbbb7">&nbsp;</td>
                <td style="background-color:#c0cda1">&nbsp;</td>
                <td style="background-color:#e8bd55">&nbsp;</td>
                <td style="background-color:#fab746">&nbsp;</td>
                <td style="background-color:#ecc65b">&nbsp;</td>
                <td style="background-color:#d0cc75">&nbsp;</td>
                <td style="background-color:#bacac0">&nbsp;</td>
                <td style="background-color:#f3c9cd">&nbsp;</td>
                <td style="background-color:#deb0b0">&nbsp;</td>
                <td style="background-color:#e0b19f">&nbsp;</td>
                <td style="background-color:#f8a88f">&nbsp;</td>
                <td style="background-color:#cf7b8a">&nbsp;</td>
                <td style="background-color:#a0779f">&nbsp;</td>
                <td style="background-color:#ae9bae">&nbsp;</td>
                <td style="background-color:#b2b0bd">&nbsp;</td>
                <td style="background-color:#b4aec6">&nbsp;</td>
                <td style="background-color:#bcbbb7">&nbsp;</td>
            </tr>
            <tr>
            	<td style="background-color:#e4d8bd">&nbsp;</td>
                <td style="background-color:#fcdbd1">&nbsp;</td>
                <td style="background-color:#f2ded6">&nbsp;</td>
                <td style="background-color:#f7eded">&nbsp;</td>
                <td style="background-color:#f3c9cd">&nbsp;</td>
                <td style="background-color:#e1e8e4">&nbsp;</td>
                <td style="background-color:#d0cc75">&nbsp;</td>
                <td style="background-color:#fcf5e2">&nbsp;</td>
                <td style="background-color:#f48188">&nbsp;</td>
                <td style="background-color:#fdc8c2">&nbsp;</td>
                <td style="background-color:#ffdabd">&nbsp;</td>
                <td style="background-color:#ffe8d0">&nbsp;</td>
                <td style="background-color:#f6edeb">&nbsp;</td>
                <td style="background-color:#e8f3ef">&nbsp;</td>
                <td style="background-color:#d5f1e3">&nbsp;</td>
                <td style="background-color:#e1d697">&nbsp;</td>
                <td style="background-color:#cf7b8a">&nbsp;</td>
                <td style="background-color:#fcded5">&nbsp;</td>
                <td style="background-color:#eff5cd">&nbsp;</td>
                <td style="background-color:#deb0b0">&nbsp;</td>
                <td style="background-color:#f7dbde">&nbsp;</td>
            </tr>
             
        </table>
        </div>
</body>
</html>
