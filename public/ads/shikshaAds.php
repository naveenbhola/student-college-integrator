<html>
	<head>
    	<title>Shiksha Ads</title>
	</head>
	<body>
        <script language="JavaScript">
        <!--
        function google_ad_request_done(google_ads) {
            var s = '';
            var i;
            if (google_ads.length == 0) {
                return;
            }
            if (google_ads[0].type == "flash") {
                s += '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"' +' codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"' +
                        ' WIDTH="' + google_ad.image_width +
                        '" HEIGHT="' + google_ad.image_height + '">' +
                        '<PARAM NAME="movie" VALUE="' + google_ad.image_url + '">'
                        '<PARAM NAME="quality" VALUE="high">'
                        '<PARAM NAME="AllowScriptAccess" VALUE="never">'
                        '<EMBED src="' + google_ad.image_url +
                        '" WIDTH="' + google_ad.image_width +
                        '" HEIGHT="' + google_ad.image_height +
                        '" TYPE="application/x-shockwave-flash"' +
                        ' AllowScriptAccess="never" ' +
                        ' PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED></OBJECT>';
            } else if (google_ads[0].type == "image") {
                s += '<a href="' + google_ads[0].url +
                        '" target="_top" title="go to ' + google_ads[0].visible_url +
                        '"><img border="0" src="' + google_ads[0].image_url +
                        '"width="' + google_ads[0].image_width +
                        '"height="' + google_ads[0].image_height + '"></a>';
            } else {
                if (google_ads.length == 1) {
                	s += '<a href="' + google_ads[0].url + '" ' +
                                'onmouseout="window.status=\'\'" ' +
                                'onmouseover="window.status=\'go to ' +
                                google_ads[0].visible_url + '\'" ' +
                                'style="text-decoration:none">' +
                                '<span style="text-decoration:underline;font-size:20pt">' +
                                '<b>' + google_ads[0].line1 + '</b><br></span>' +
                                '<span style="color:#000000;font-size:16pt">' +
                                google_ads[0].line2 + '&nbsp;' +
                                google_ads[0].line3 + '<br/></span>' +
                                '<span style="color:#008000;font-size:14pt">' +
                                google_ads[0].visible_url + '</span></a><br>';
                } else if (google_ads.length > 1) {
                	if(google_ad_layout != "" && google_ad_layout != 'h'){
	               		s = showVerticalAds (google_ads, s);
	                } else {
		             	s = showHorizontalAds (google_ads, s);	                
		            }
                }
                
                //s = '<div style="border:solid 1px #F2F2F2;padding:5px;">'+ s +'</div>';
            }
            document.write(s);
            return;
        }
            
        function showVerticalAds(google_ads, s){
        	for(i=0; i < google_ads.length; ++i) {
            	var adBorder = 'border-bottom:dotted 1px #F2F2F2;padding-bottom:5px;margin-bottom:5px;';
            	if(i == google_ads.length-1){
            		adBorder = '';
            	}
                s += '<div style="'+adBorder+'"><a href="' + google_ads[i].url + '" ' +
                                'onmouseout="window.status=\'\'" ' +
                                'onmouseover="window.status=\'go to ' +
                                google_ads[i].visible_url + '\'" ' +
                                'style="text-decoration:none">' +
                                '<span style="text-decoration:underline;color:#FD8103;font-family:Arial,Helvetica,sans-serif;font-size:11px;">' +
                                '<b>' + google_ads[i].line1 + '</b><br/></span>' +
                                '<span style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-size:11px;">' +
                                google_ads[i].line2 + '<br/>' +
                                google_ads[i].line3 + '<br></span>' +
                                '<span style="color:#0066DD;font-family:Arial,Helvetica,sans-serif;font-size:11px;">' +
                                google_ads[i].visible_url + '</span></a></div>';
            }
            s += '<div style="text-align:left;"><img src="/public/images/ads2.gif"/></div>';
            return s;
                
        }
        
        function showHorizontalAds (google_ads, s) {
        	for(i=0; i < google_ads.length; ++i) {
            	var adBorder = 'border-right:dotted 1px #F2F2F2;padding-right:8px;margin-right:8px;';
            	if(i == google_ads.length-1){
            		adBorder = '';
            	}
                s += '<div style="display:inline;float:left;'+adBorder+'"><a href="' + google_ads[i].url + '" ' +
                                'onmouseout="window.status=\'\'" ' +
                                'onmouseover="window.status=\'go to ' +
                                google_ads[i].visible_url + '\'" ' +
                                'style="text-decoration:none">' +
                                '<span style="text-decoration:underline;color:#FD8103;font-family:Arial,Helvetica,sans-serif;font-size:11px;">' +
                                '<b>' + google_ads[i].line1 + '</b><br/></span>' +
                                '<span style="color:#000000;font-family:Arial,Helvetica,sans-serif;font-size:11px;">' +
                                google_ads[i].line2 + '<br/>' +
                                google_ads[i].line3 + '<br></span>' +
                                '<span style="color:#0066DD;font-family:Arial,Helvetica,sans-serif;font-size:11px;">' +
                                google_ads[i].visible_url + '</span></a></div>';
            }
            
            s += '<div style="clear:left"></div><div style="text-align:right;"><img src="/public/images/ads2.gif"/></div>';
            return s;
        
        }
            
                google_ad_client = 'partner_js'; // substitute your client_id
                google_ad_channel = 'sports';
                google_ad_output = 'js';
                google_max_num_ads = '<?php echo $_GET['numAds']; ?>';
                google_page_url = 'https://www.shiksha.com';
                google_language = 'it';
                google_encoding = 'utf8';
                google_safe = 'high';
                google_adtest = 'on';
                google_hints = '';
                google_kw = window.parent.document.getElementById('keyword').value;//'education india events courses colleges scholarships';
                google_kw_type = 'broad';
                google_contents = '';
                google_ad_section = 'default';
                google_ad_layout = '<?php echo $_GET['adsLayout']; ?>';
            // -->
        </script>
        <script language="JavaScript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
    </body>
</html>
