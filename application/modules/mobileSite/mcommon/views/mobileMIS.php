<table align="center"  bgcolor="#fff" border="1" cellpadding="8" cellspacing="0" width="600" style="font:normal 15px Arial,Helvetica,sans-serif; color:#000; border:1px solid #cacaca; border-collapse:collapse">
  <tbody>
    <tr>
    <th colspan="3" bgcolor="#e7e7e7" style="padding:18px 0; font-size:14px">Shiksha Mobile Report <?php echo $date;?></th>
    </tr>
    <tr>
    <td width="350"></td>
    <td><strong>HTML 4</strong></td>
    <td><strong>HTML 5</strong></td>
    </tr>
    <tr>
    <td width="350">Total Registration made:</td>
    <td><strong><?php if($siteDate['mobileresgcount']){echo $siteDate['mobileresgcount'];} ?></strong></td>
    <td><strong><?php if($siteDate['mobileresgcount5']){echo $siteDate['mobileresgcount5'];} ?></strong></td>
    </tr>
    <tr>
    <td>Total PAID Responses made:</td>
    <td><strong><?php if($siteDate['mobilerespcount']){echo $siteDate['mobilerespcount'];} ?></strong></td>
    <td><strong><?php if($siteDate['mobilerespcount5']){echo $siteDate['mobilerespcount5'];} ?></strong>
    , getEB = <strong><?php if($siteDate['mobilerespcount5_geteb']){echo $siteDate['mobilerespcount5_geteb'];} ?></strong>
    </td>
    </tr>
    <tr>
    <td>Total FREE Responses made:</td>
    <td>-</td>
    <td><strong><?php if($siteDate['mobilerespcount5_free']){echo $siteDate['mobilerespcount5_free'];} ?></strong>
    </td>
    </tr>
    <tr>
    <td>Total Page views:</td>
    <td><strong><?php if($siteDate['visits']){echo $siteDate['visits'];} ?></strong></td>
    <td><strong><?php if($siteDate['visits5']){echo $siteDate['visits5'];} ?></strong></td>
    </tr>
    <tr>
    <td>Total Questions posted:</td>
    <td><strong><?php if($siteDate['question_post']){echo $siteDate['question_post'];} ?></strong></td>
    <td><strong> - </strong></td>
    </tr>
    <tr>
    <td>Total Answers Posted:</td>
    <td><strong><?php if($siteDate['ans_post']){echo $siteDate['ans_post'];} ?></strong></td>
    <td><strong> - </strong></td>
    </tr>
    <tr>
    <th colspan="3" bgcolor="#e7e7e7" style="padding:12px 0; font-size:14px">Pagewise Details:</th>
    </tr>
    <tr>
    <td colspan="3" style="font:normal 15px Arial,Helvetica,sans-serif;">
	<table border="0" cellpadding="8" cellspacing="0" width="100%" style="font:normal 15px Arial,Helvetica,sans-serif;">
	<tbody>
	    <tr>
	    <td><strong style="font-size:14px">Homepage:</strong> </td>
	    </tr>
	    
	    <tr>
	    <td></td>
            <td><strong>HTML 4</strong></td>
            <td><strong>HTML 5</strong></td>
	    </tr>
	    
	    <tr bgcolor="f0f0f0">
	    <td>Server Processing Time:</td>
	    <td><strong><?php if($performanceData['home']['avg(server_p_time)']){echo round($performanceData['home']['avg(server_p_time)'],2).' ms';} ?></strong></td>
	    <td><strong><?php if($performanceData['home5']['avg(server_p_time)']){echo round($performanceData['home5']['avg(server_p_time)'],2).' ms';} ?></strong></td>
	    </tr>
	    
	    <tr>
	    <td>Head Received Time:</td>
	    <td><strong><?php if($performanceData['home']['avg(time_head_page_first_byte)']){echo round($performanceData['home']['avg(time_head_page_first_byte)'],2).' ms';} ?></strong></td>
	    <td><strong><?php if($performanceData['home5']['avg(time_head_page_first_byte)']){echo round($performanceData['home5']['avg(time_head_page_first_byte)'],2).' ms';} ?></strong></td>
	    </tr>
	    
	    <tr bgcolor="f0f0f0">
	    <td>Total Page Rendering Time:</td>
	    <td><strong><?php if($performanceData['home']['avg(perceived_loadtime_page)']){echo round($performanceData['home']['avg(perceived_loadtime_page)'],2).' ms';} ?></strong></td>
	    <td><strong><?php if($performanceData['home5']['avg(perceived_loadtime_page)']){echo round($performanceData['home5']['avg(perceived_loadtime_page)'],2).' ms';} ?></strong></td>
	    </tr>
	    
	    <tr>
	    <td>Homepage views:</td>
	    <td><strong><?php if($performanceData['home']['calls']){echo $performanceData['home']['calls'];} ?></strong></td>
	    <td><strong><?php if($performanceData['home5']['calls']){echo $performanceData['home5']['calls'];} ?></strong></td>
	    </tr>
	    
	</tbody>
	</table>

	<table border="0" cellpadding="8" cellspacing="0" width="100%" style="font:normal 15px Arial,Helvetica,sans-serif;border-top:2px solid #cacaca; padding-top:12px">
	<tbody>
	    <tr>
	    <td><strong style="font-size:14px">Category Page:</strong> </td>
	    </tr>

	    <tr>
	    <td></td>
            <td><strong>HTML 4</strong></td>
            <td><strong>HTML 5</strong></td>
	    </tr>

	    <tr bgcolor="f0f0f0">
	    <td>Server Processing Time:</td>
	    <td><strong><?php if($performanceData['category_listing']['avg(server_p_time)']){echo round($performanceData['category_listing']['avg(server_p_time)'],2).' ms';} ?></strong></td>
	    <td><strong><?php if($performanceData['category_listing5']['avg(server_p_time)']){echo round($performanceData['category_listing5']['avg(server_p_time)'],2).' ms';} ?></strong></td>
	    </tr>

	    <tr>
	    <td>Head Received Time:</td>
	    <td><strong><?php if($performanceData['category_listing']['avg(time_head_page_first_byte)']){echo round($performanceData['category_listing']['avg(time_head_page_first_byte)'],2).' ms';} ?></strong></td>
	    <td><strong><?php if($performanceData['category_listing5']['avg(time_head_page_first_byte)']){echo round($performanceData['category_listing5']['avg(time_head_page_first_byte)'],2).' ms';} ?></strong></td>
	    </tr>

	    <tr bgcolor="f0f0f0">
	    <td>Total Page Rendering Time:</td>
	    <td><strong><?php if($performanceData['category_listing']['avg(perceived_loadtime_page)']){echo round($performanceData['category_listing']['avg(perceived_loadtime_page)'],2).' ms';} ?></strong></td>
	    <td><strong><?php if($performanceData['category_listing5']['avg(perceived_loadtime_page)']){echo round($performanceData['category_listing5']['avg(perceived_loadtime_page)'],2).' ms';} ?></strong></td>
	    </tr>
	    
	    <tr>
	    <td>Category page views:</td>
	    <td><strong><?php if($performanceData['category_listing']['calls']){echo $performanceData['category_listing']['calls'];} ?></strong></td>
	    <td><strong><?php if($performanceData['category_listing5']['calls']){echo $performanceData['category_listing5']['calls'];} ?></strong></td>
	    </tr>
	    
	</tbody>
        </table>

	<table border="0" cellpadding="8" cellspacing="0" width="100%" style="font:normal 15px Arial,Helvetica,sans-serif;border-top:2px solid #cacaca; padding-top:12px">
	<tbody>
	    <tr>
	    <td><strong style="font-size:14px">Listing Detail Page:</strong> </td>
	    </tr>

	    <tr>
	    <td></td>
            <td><strong>HTML 4</strong></td>
            <td><strong>HTML 5</strong></td>
	    </tr>
	    
	    <tr bgcolor="f0f0f0">
	    <td>Server Processing Time:</td>
	    <td><strong><?php if($performanceData['listing_detail']['avg(server_p_time)']){echo round($performanceData['listing_detail']['avg(server_p_time)'],2).' ms';} ?></strong></td>
	    <td><strong><?php if($performanceData['listing_detail5']['avg(server_p_time)']){echo round($performanceData['listing_detail5']['avg(server_p_time)'],2).' ms';} ?></strong></td>
	    </tr>
	    
	    <tr>
	    <td>Head Received Time:</td>
	    <td><strong><?php if($performanceData['listing_detail']['avg(time_head_page_first_byte)']){echo round($performanceData['listing_detail']['avg(time_head_page_first_byte)'],2).' ms';} ?></strong></td>
	    <td><strong><?php if($performanceData['listing_detail5']['avg(time_head_page_first_byte)']){echo round($performanceData['listing_detail5']['avg(time_head_page_first_byte)'],2).' ms';} ?></strong></td>
	    </tr>
	    
	    <tr bgcolor="f0f0f0">
	    <td>Total Page Rendering Time:</td>
	    <td><strong><?php if($performanceData['listing_detail']['avg(perceived_loadtime_page)']){echo round($performanceData['listing_detail']['avg(perceived_loadtime_page)'],2).' ms';} ?></strong></td>
	    <td><strong><?php if($performanceData['listing_detail5']['avg(perceived_loadtime_page)']){echo round($performanceData['listing_detail5']['avg(perceived_loadtime_page)'],2).' ms';} ?></strong></td>
	    </tr>
	    
	    <tr>
	    <td>Listing detail page views:</td>
	    <td><strong><?php if($performanceData['listing_detail']['calls']){echo $performanceData['listing_detail']['calls'];} ?></strong></td>
	    <td><strong><?php if($performanceData['listing_detail5']['calls']){echo $performanceData['listing_detail5']['calls'];} ?></strong></td>
	    </tr>
	    
	</tbody>
        </table>

	<table border="0" cellpadding="8" cellspacing="0" width="100%" style="font:normal 15px Arial,Helvetica,sans-serif;border-top:2px solid #cacaca; padding-top:12px">
	<tbody>
	    <tr>
	    <td><strong style="font-size:14px">ANA Question Listing Page:</strong> </td>
	    </tr>
	    <tr bgcolor="f0f0f0">
	    <td>Server Processing Time:</td>
	    <td><strong><?php if($performanceData['ana_listing']['avg(server_p_time)']){echo round($performanceData['ana_listing']['avg(server_p_time)'],2).' ms';} ?></strong></td>
	    </tr>
	    <tr>
	    <td>Head Received Time:</td>
	    <td><strong><?php if($performanceData['ana_listing']['avg(time_head_page_first_byte)']){echo round($performanceData['ana_listing']['avg(time_head_page_first_byte)'],2).' ms';} ?></strong></td>
	    </tr>
	    <tr bgcolor="f0f0f0">
	    <td>Total Page Rendering Time:</td>
	    <td><strong><?php if($performanceData['ana_listing']['avg(perceived_loadtime_page)']){echo round($performanceData['ana_listing']['avg(perceived_loadtime_page)'],2).' ms';} ?></strong></td>
	    </tr>
	    <tr>
	    <td>ANA Listing page views:</td>
	    <td><strong><?php if($performanceData['ana_listing']['calls']){echo $performanceData['ana_listing']['calls'];} ?></strong></td>
	    </tr>
	</tbody>
        </table>

	<table border="0" cellpadding="8" cellspacing="0" width="100%" style="font:normal 15px Arial,Helvetica,sans-serif;border-top:2px solid #cacaca; padding-top:12px">
	<tbody>
	    <tr>
	    <td><strong style="font-size:14px">ANA Detail Page:</strong> </td>
	    </tr>
	    <tr bgcolor="f0f0f0">
	    <td>Server Processing Time:</td>
	    <td><strong><?php if($performanceData['ana_detail']['avg(server_p_time)']){echo round($performanceData['ana_detail']['avg(server_p_time)'],2).' ms';} ?></strong></td>
	    </tr>
	    <tr>
	    <td>Head Received Time:</td>
	    <td><strong><?php if($performanceData['ana_detail']['avg(time_head_page_first_byte)']){echo round($performanceData['ana_detail']['avg(time_head_page_first_byte)'],2).' ms';} ?></strong></td>
	    </tr>
	    <tr bgcolor="f0f0f0">
	    <td>Total Page Rendering Time:</td>
	    <td><strong><?php if($performanceData['ana_detail']['avg(perceived_loadtime_page)']){echo round($performanceData['ana_detail']['avg(perceived_loadtime_page)'],2).' ms';} ?></strong></td>
	    </tr>
	    <tr>
	    <td>ANA Detail page views:</td>
	    <td><strong><?php if($performanceData['ana_detail']['calls']){echo $performanceData['ana_detail']['calls'];} ?></strong></td>
	    </tr>
	</tbody>
        </table>


    <table border="0" cellpadding="8" cellspacing="0" width="100%" style="font:normal 15px Arial,Helvetica,sans-serif;border-top:2px solid #cacaca; padding-top:12px">
        <tbody>
            <tr>
            <td><strong style="font-size:14px">Ranking Page:</strong> </td>
            </tr>

            <tr>
            <td></td>
            <td><strong>HTML 4</strong></td>
            <td><strong>HTML 5</strong></td>
            </tr>

            <tr bgcolor="f0f0f0">
            <td>Server Processing Time:</td>
            <td><strong>-</strong></td>
            <td><strong><?php if($performanceData['ranking5']['avg(server_p_time)']){echo round($performanceData['ranking5']['avg(server_p_time)'],2).' ms';} ?></strong></td>
            </tr>

            <tr>
            <td>Head Received Time:</td>
            <td><strong>-</strong></td>
            <td><strong><?php if($performanceData['ranking5']['avg(time_head_page_first_byte)']){echo round($performanceData['ranking5']['avg(time_head_page_first_byte)'],2).' ms';} ?></strong></td>
            </tr>

            <tr bgcolor="f0f0f0">
            <td>Total Page Rendering Time:</td>
            <td><strong>-</strong></td>
            <td><strong><?php if($performanceData['ranking5']['avg(perceived_loadtime_page)']){echo round($performanceData['ranking5']['avg(perceived_loadtime_page)'],2).' ms';} ?></strong></td>
            </tr>

            <tr>
            <td>Ranking page views:</td>
            <td><strong>-</strong></td>
            <td><strong><?php if($performanceData['ranking5']['calls']){echo $performanceData['ranking5']['calls'];} ?></strong></td>
            </tr>

        </tbody>
    </table>
	
	<!--<table border="0" cellpadding="8" cellspacing="0" width="100%" style="border-top:2px solid #cacaca; padding-top:12px">
	<tbody>
	<tr>
	<td><strong style="font-size:14px">Top 20 browsers details:</strong> </td>
	</tr>
	<tr>
	<td>
	<ul style="margin:0 0 0 25px; padding:0; list-style-type:disc">
	<li style="margin-bottom:7px">Details - 1 </li><li style="margin-bottom:7px">Details - 2 </li><li style="margin-bottom:7px">Details - 3 </li><li style="margin-bottom:7px">Details - 4 </li><li style="margin-bottom:7px">Details - 5 </li><li style="margin-bottom:7px">Details - 6 </li><li style="margin-bottom:7px">Details - 7 </li></ul>
	</td>
	</tr>
	</tbody>
	</table>-->
  </td>
  </tr>
  </tbody>  
</table>

<table align="center"  bgcolor="#fff" border="1" cellpadding="8" cellspacing="0" width="600" style="font:normal 14px Arial,Helvetica,sans-serif; color:#000; border:1px solid #cacaca; border-collapse:collapse">
<tr>
  <td>**Note: This report is getting generated for JS enabled mobile phones only. We are logging all the Mobile requests made on Shiksha mobile site in the table mobile_activity_log (using a Beacon image). The data is then extracted from this table for the current Date.</td>
</tr>
</table>

