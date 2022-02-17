<?php 
class listingcommonmodel extends MY_Model{
	public $dbHandle = '';
    public $dbHandleMode = '';
    
    function __construct() {
		parent::__construct('Listing');
    }
    
    private function initiateModel($mode = "write") {
		if($this->dbHandle && $this->dbHandleMode == 'write')
		    return;
		
		$this->dbHandleMode = $mode;
		$this->dbHandle = NULL;
		if($mode == 'read') {
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
    }

    public function fetchListingViewCount($listingIds=array(),$type=array(),$durationInDays="365"){
        if(empty($listingIds) || empty($type)) return array();
        $this->initiateModel('read');
        $sql = "SELECT SUM(no_Of_Views)  as view_count,
                listing_id
                FROM view_Count_Details 
                WHERE DATE_SUB(CURDATE(),INTERVAL ? DAY) <= view_Date
                AND listing_id IN (?)
                AND listingType IN (?)
                GROUP BY listing_id ORDER by view_count desc";


        $query = $this->db->query($sql,array($durationInDays,$listingIds,$type));
        $result = $query->result_array();
        $finalResult = array();
        foreach ($result as $key => $value) {
            $finalResult[$value['listing_id']] = $value['view_count'];
        }
        return $finalResult;
    }

    public function fetchAllListingViewCount($type=array(),$durationInDays="365"){
        if(empty($type)) 
            return array();

        $this->initiateModel('read');
        $sql = "SELECT listing_id, SUM( no_Of_Views ) AS view_count
                FROM view_Count_Details
                WHERE DATE_SUB( CURDATE() , INTERVAL ? DAY ) <= view_Date
                AND listingType IN (?)
                GROUP BY listing_id
                ORDER BY view_count DESC ";


        $query = $this->db->query($sql,array($durationInDays,$type));
        $result = $query->result_array();
        
        $finalResult = array();
        foreach ($result as $key => $value) {
            $finalResult[$value['listing_id']] = $value['view_count'];
        }
        return $finalResult;
    }

    public function getLastModificationDate($listingType='',$listingIds=array()){
        if(empty($listingIds) || empty($listingType)){
            return array();
        }

        $this->load->config('nationalInstitute/instituteSectionConfig');
        $listingMainStatus = $this->config->item("listingMainStatus");

        $sql = "SELECT last_modify_date,
                listing_type_id
                FROM listings_main
                WHERE listing_type_id IN (?)
                AND listing_type = ?
                AND status = ? ";

        $query = $this->db->query($sql,array($listingIds,$listingType,$listingMainStatus['live']));

        $result = $query->result_array();

        $finalResult = array();
        foreach ($result as $key => $value) {
            $finalResult[$value['listing_type_id']] = $value['last_modify_date'];
        }
        return $finalResult;
    }

    public function getActiveListingsForClients($clientIds=array()) {
        
        if(empty($clientIds)) { 
            return array();
        }
        $this->initiateModel('read');
        $this->load->config('nationalInstitute/instituteSectionConfig');
        $listingMainStatus = $this->config->item("listingMainStatus");

        $sql = "select listing_type_id, listing_type, pack_type, listing_title FROM listings_main, tuser 
                WHERE status = ? AND username IN (?)
                AND listing_type IN ('university','institute','course','university_national') AND username=userid ";

        return $this->dbHandle->query($sql,array($listingMainStatus['live'],$clientIds))->result_array();

    }

    function getInstitutePhotosForVariantsCron($limit = 1000){

        $this->initiateModel('read');

        $sql = "SELECT id, media_id, media_url FROM  shiksha_institutes_medias WHERE media_thumb_url NOT LIKE  '%_68x54.%'
                AND STATUS IN ('live','draft') AND  `media_type` =  'photo' LIMIT 0 , ? ";

        $rs = $this->dbHandle->query($sql,array((int)$limit))->result_array();

        return $rs;
    }

    function updateInstituteMediaThumbnailImage($primaryId, $mediaId, $smallThumbnailImage, $mediumThumbnailImage){

        $this->initiateModel('write');

        $sql = "UPDATE shiksha_institutes_medias SET media_thumb_url = ? WHERE id = ?";
        $rs  = $this->dbHandle->query($sql, array($smallThumbnailImage, $primaryId));

        $sql = "UPDATE tImageData SET thumburl = ?, thumburl_s = ? WHERE mediaid = ?";
        $rs  = $this->dbHandle->query($sql, array($mediumThumbnailImage, $smallThumbnailImage, $mediaId));
    }

    function getAllLiveInstitutes(){

        $this->initiateModel('read');

        $sql    = "SELECT distinct listing_id FROM shiksha_institutes WHERE status='live'";
        $rs     = $this->dbHandle->query($sql);
        $result = $rs->result_array();

        $finalResult = array();
        foreach ($result as $key => $value) {
            $finalResult[] = $value['listing_id'];
        }

        return $finalResult;
    }

   function fetchCourses(){
        $this->initiateModel('read');
        $sql = "select distinct course_id from shiksha_courses where status = 'live'";
        $query = $this->dbHandle->query($sql);
        $result = $query->result_array();
        $finalResult = array();
        foreach ($result as $key => $value) {
            $finalResult[] = $value['course_id'];
        }
        return $finalResult;
    }

	function getInstituteMediaForMimeTypeScript(){

        $this->initiateModel('read');

        $sql = "SELECT id, media_id, media_url FROM  shiksha_institutes_medias WHERE media_thumb_url LIKE '%.png%'
                AND STATUS IN ('live','draft') AND  `media_type` =  'photo'";

        $rs = $this->dbHandle->query($sql)->result_array();

        return $rs;
    }

    function getListingUpdatedAfterDate($date){

        $this->initiateModel('read');

        $sql = "select distinct listing_type_id as ids from listings_main where listing_type in ('institute', 'university_national') and DATE(last_modify_date) > ? and status in ('live','draft') ";

        $rs = $this->dbHandle->query($sql,array($date))->result_array();

        $ids = array();
        foreach ($rs as $key => $value) {
            $ids[] = $value['ids'];
        }
        
        return $ids;
    }

    function getListingHavingMultipleUsername($instituteIds){

        $result = array();

        if(empty($instituteIds))
            return $result;

        $this->initiateModel('read');

        $sql = "select listing_type_id, count(distinct username) as cc from listings_main where listing_type in( 'institute', 'university_national') and listing_type_id in (?) group by listing_type_id having cc > 1 order by cc desc";

        $rs = $this->dbHandle->query($sql,array($instituteIds))->result_array();

        foreach ($rs as $key => $value) {
            $result[] = $value['listing_type_id'];
        }

        return $result;
    }

    function getCoursesUsername($courses){

        $result = array();

        if(empty($courses))
            return $result;

        $this->initiateModel('read');

        $sql = "select username, pack_type from listings_main where listing_type='course' and status in ('live','draft') and listing_type_id in (?)";

        $rs = $this->dbHandle->query($sql,array($courses))->result_array();

        foreach ($rs as $key => $value) {
            $result['username'][] = $value['username'];
            $result['pack_type'][] = $value['pack_type'];
        }

        return $result;   
    }

    function getInstitutesUsername($possibleCorruptInstituteIds){

        $result = array();

        if(empty($possibleCorruptInstituteIds))
            return $result;

        $this->initiateModel('read');

        $sql = "select listing_type_id, username from listings_main where listing_type in( 'institute', 'university_national') and status in ('live','draft') and listing_type_id in (?)";

        $rs = $this->dbHandle->query($sql,array($possibleCorruptInstituteIds))->result_array();

        foreach ($rs as $key => $value) {
            $result[$value['listing_type_id']] = $value['username'];
        }

        return $result;      
    }

    function getListingUsernameBeforeDate($instituteId, $date){

        $this->initiateModel('read');

        $sql = "select distinct username from listings_main where listing_type in ('institute', 'university_national') and DATE(last_modify_date) <= ? and status in ('history') and listing_type_id=?";

        $rs = $this->dbHandle->query($sql,array($date,$instituteId))->result_array();

        $username = array();
        foreach ($rs as $key => $value) {
            $username[] = $value['username'];
        }
        
        return $username;
    }

    function getUsernameOfNonInstitute($instituteId){

        $this->initiateModel('read');

        $sql = "select distinct username from listings_main where listing_type not in ('institute', 'university_national') and listing_type_id=?";

        $rs = $this->dbHandle->query($sql,array($instituteId))->result_array();

        $username = array();
        foreach ($rs as $key => $value) {
            $username[] = $value['username'];
        }
        
        return $username;
    }

	function fixAllCorruptedMsgIdsInMessageTable($courseIds){
        $this->initiateModel('write');
        $sql = "select distinct msgId from messageTable m JOIN shiksha_institutes b on b.listing_id = m.listingTypeId 
 where listingTypeId  in (203,187,212,226,334,497,2836,2840,2856,2878,3165,3174,3191,3193,3200,3220,2893,3234,3245,3273,4198,4200,4240,4263,4266,4301,3531,1230,3891,4001,3901,3435,3922,3847,856,4026,4330,4420,4449,4437,4488,2864,4493,4517,4529,13632,13645,13671,13682,19261,19334,19498,19522,21924,21282,22268,20676,20284,20108,20405,20586,20548,20593,20660,20813,20844,20917,21005,21100,21130,21589,22088,21774,21796,21823,21850,21852,21877,21883,21934,21960,22020,22033,22044,22046,22149,22227,22262,22294,22307,23605,22431,22504,23336,23656,22815,22826,22898,22928,23133,23151,23157,23347,23350,23379,23423,23465,23539,23568,23603,23764,23657,19368,22664,20559,20726,21324,23124,23689,23637,23311,21979,23525,23069,23276,23374,22000,23587,22329,23307,19678,22537,22127,21430,1705,23405,22191,23660,23881,23897,23960,23977,23978,23992,24007,24066,24072,24108,24115,24117,24214,24231,24256,24331,24433,24451,24468,24494,24582,24639,24642,24673,24748,24762,24764,24765,24766,24779,24782,24792,24794,24799,1227,24848,24888,24914,24932,24941,24942,24958,24975,25070,25075,25089,25129,25138,25172,25196,25224,25249,11506,25332,25353,25359,25397,25400,25402,12589,25703,25714,24658,22301,25660,25028,26013,3030,19437,3082,26077,433,23158,1494,26199,21647,21870,3701,2060,4427,22350,26273,1178,24259,23679,7475,3306,1207,25277,3036,22492,26370,26375,3723,26396,3874,903,8509,21454,9428,10878,25386,23718,13272,1276,2184,2195,1626,4998,26573,26580,26826,27001,27002,27166,27168,27214,27439,27707,27721,27733,27752,25004,22358,28075,28077,24644,2102,24674,28252,1857,28279,28281,24826,1274,28387,6159,22265,7699,28532,5228,23625,25012,905,1952,28603,9233,28614,23628,28762,962,22128,28918,20531,5216,25378,24889,20056,29370,29387,29393,29398,29402,29403,29424,29456,29589,29606,25175,24020,467,29725,29756,29757,29761,20782,4225,29851,29919,24666,29940,30043,30077,30314,30427,25792,30664,19725,30871,31026,21107,31445,31450,7912,31569,31701,31794,32113,24536,32607,32610,32646,26919,32706,32710,32709,32722,32806,32849,32899,32900,32901,32904,24508,33058,33072,26141,33138,33172,27163,33120,33329,28200,32282,33350,33363,33376,33391,33514,33527,33531,27004,33843,32008,29693,33440,33967,33977,33982,29819,31008,24195,34282,34303,34344,33348,34376,25137,34407,34468,34489,34492,34477,34538,34609,24802,34620,34614,34718,2946,32548,34766,34844,34911,33066,35001,35034,35051,35057,35117,35144,35165,35166,35167,35177,35178,35179,35239,35278,35279,35280,35306,35307,35305,35304,35303,35302,32823,35454,35463,35554,35592,35661,35682,4381,35766,23054,1844,35823,35849,35890,35891,35897,23593,35845,35967,32583,5527,36016,27883,36021,29735,36055,3851,27722,30454,34721,36115,35171,33015,23138,36219,29709,29220,20038,22680,26480,24878,25061,24105,2245,34388,31004,24327,28297,36346,25297,36423,36390,3429,25787,30617,36486,27649,23583,36536,29852,25222,36558,36559,25104,29003,36573,4348,36590,25716,986,27927,4217,22026,20576,4268,36643,1474,21349,3098,28456,22999,3817,36753,36765,36771,24926,27039,4321,25207,26443,23421,24135,26990,25375,23466,36784,28779,36976,36992,21892,37061,37071,37072,37081,37093,37103,37105,31257,24078,23086,37151,37181,37209,37210,37245,37265,4322,35111,3054,22540,25013,30805,37282,4350,37380,25154,37432,32371,24751,25197,23008,37493,28461,36791,21542,22221,24788,4536,31514,37545,37574,21517,37583,37590,37635,21997,37592,31270,29767,3123,24423,37725,3356,29008,37820,19927,6104,28162,28374,24773,36312,22288,35990,36163,35898,29832,33330,38061,38095,37985,38106,24046,38114,24175,28163,38138,38139,22091,38152,38245,24812,20060,38295,38314,24017,38348,32768,38650,36041,38678,38732,38726,39003,37214,1585,39354,39370,479,2837,23701,25410,39750,39746,3378,21288,26486,19365,34345,2311,20605,40229,40353,21262,40380,23733,40440,38089,19630,40375,22893,40509,40569,29564,19962,40714,36761,19408,40818,40873,40932,41114,41202,38054,41350,41314,41500,35740,33182,38064,42032,42023,42730,42940,42967,9573,24432,38105,38919,25770,20039,36479,26247,22226,19961,44353,44367,25537,44631,22506,33037,38994,22799,21618,45799,31912,4186,46254,42903,46631,46698,36091,22946,41011,36470,46842,35861,46893,38084,38082,31420,46908,38047,46902,46929,46944,35161,38361,33442,46926,46973,4279,34796,47087,34240,42930,47126,42540,47141,47180,22178,40381,47298,21244,47339,47364,47363,47365,40916,47398,47406,32007,20841,20050,42934,37430,42525,26534,47550,31157,22115,35889,35888,47488,47627,34295,47718,28444,36179,34208,4295,47896,27818,25364,23772,35924,48035,48036,48037,48041,48040,48039,48042,48138,30822,40416,29947,33987,36093,46282,20356,20384,48420,25194,31422,48464,47379,36071,21148,48575,48602,48622,36557,40379,37899,23769,31624,28706,28552,36313,22136,48799,48823,47403,4351,48862,38634,22612,48877,33260,36922,48893,37848,48926,32347,275,963,23494,48984,31403,24660,20615,35958,47791,34192,2954,49145,20800,12223,49157,21940,20462,32359,4269,30525,26513,4390,22347,23766,37876,38131,20967,4376,21795,1568,25008,25191,38132,31069,25096,28632,36970,48472,30663,38206,38252,26382,496,42902,28120,42891,21953,47389,32725,24435,38039,24024,474,487,1571,3084,36321,24747,23588,47146,20834,4383,46909,3055,3155) 
and b.status = 'live' AND m.status = 'deleted' AND creationDate < '2013-07-10 11:50:26' AND fromOthers = 'user' AND m.parentId = 0";

        $query = $this->dbHandle->query($sql);

        $result = $query->result_array();
        $finalMsgIds = array();
        foreach ($result as $key => $value) {
            $finalMsgIds[] = $value['msgId'];
        }

        $finalMessageList = array_chunk($finalMsgIds, 1000);

        foreach($finalMessageList as $finalMsgIds){

        if(!empty($finalMsgIds)) {
            $sql = "UPDATE messageTable SET status = 'live' where threadId IN (?) AND msgId NOT IN (1311560,1090604,1090610,1143295,1500266,2059053,2059071,2364510,2042974,2063680,2442275,1063531,1144274,1160161,1225583,1609318,1750613,2304774,1702308,1706976,1801718,2092344,1272888,2396334,2580847,1529346,1458390,1064620,1085741,1127918,1158269,2513761,1448475,1448528,1449145,1449393,1461008,1463381,1463382,1463748,1464132,1467048,1467049,1467680,1471835,1471836,1472123,1472763,1481100,1481125,1481374,1481943,1492547,1493284,1493669,1520063,1520743,1521211,1594388,1453381,1700296,1191297,1191299,1093730,1690269,1195064,1273630,2396038,2412071,2468787,2599709,1164019,1609650,1677906,2032238,1141186,2408580,1781822,2601972,1934573,2057697,2300468,2578389,2601900,1161745,1453394,1528559,2283111,2045517,1296392,1407347,1512712,1821023,2176466,2421756,2601919,1294065,1610234,2515949,1213304,1675531,1938603,1938605,2194040,1632649,2537241,2598508,2391377,1132602,2503121,2503130,2589620,1294638,1469881,1504793,1545133,1771848,1082566,1082607,2010061,2050260,1117506,1126045,1132598,1138366,1334562,1119248,2490290,1481750,2322739,1446324,1459961,1471879,2339442,1770001,2581154,1109705,1529350,1394336,1527695,2543597,1136780,1852487,2454719,1461975,1700345,2562916,2585920,1141190,2421080,2421082,2421102,2421104,2043053,1115614,2561089,2261076,2352533,1326617,1336887,1416317,1419461,1464883,1799136,1892060,2213790,2249595,2540803,1314442,2526355,2195414,2599355,1790428,2478242,2568205,2427848,2584827,1880998,1174101,1175516,1438838,2193529,2576241,1258707,1328172,2282773,1182084,2046778,2074704,2515335,2515337,2515338,2515339,2515340,2515341,2515342,2515343,2515344,2515345,2515346,2515347,2515349,2515350,2515351,2515352,2515353,2515355,2515356,2515357,2515359,2515360,2515361,2515362,2515363,1249304,2578883,2200844,1271114,1442732,1842247,2010423,2257144,1446777,1608184,1504029,1507980,1516969,1518822,1526150,1526456,1527379,2330167,2408095,2451830,2157861,2350695,1333664,1925733,2083914,2321614,2395931,2469560,2397511,2457632,2514206,1376884,2426664,2597239,1649216,1700390,2489654,2601930,2160509,2111862,2118382,1287685,2046278,1456667,2399942,1290490,1383825,1569616,2340126,1305196,2149220,2284939,2509381,1141707,1124562,1526531,2215511,2280053,1295829,1359574,1397564,1397619,1927591,2269990,2270017,2212774,2234478,1311421,1361997,1453388,1457409,1464683,1465535,1466171,1485852,1487365,1487536,1489120,1490001,1506866,1785706,1792631,1886447,2044114,2072791,2505013,2553371,1494169,1209052,1475839,2293585,2455587,1592292,1163786,1163988,1189424,1213135,1345061,1440675,1745995,2207147,2228990,1514748,1170594,1802873,2446163,2502417,2502422,1653650,2372308,2470926,2492963,2500279,2503506,2542926,2546135,2560476,2567117,1182039,2357499,2357505,1264332,1302829,1304779,1309217,1366143,1608014,1608015,2520415,2450437,2201184,2542112,2120304,1674578,2336085,2378252,2561636,2585151,2597774,1277974,2466226,2482289,2577973,1371497,1163358,2504122,1130916,2391436,2498139,2601887,1188509,1188512,1188513,1188514,1188515,1354295,1784843,2140080,1308399,1183060,2515616,2579929,2236202,2356408,1374418,1389616,1422831,1486324,1684236,1740125,2503389,1192055,2314307,1192090,1191849,2533453,1643753,2254947,1667856,1871995,2234705,2020822,1353647,2211282,1359070,2517333,1316494,1317977,1343371,1408875,2361274,2490422,2469464,2510441,1733726,2240924,2551635,2443635,2184587,2390983,2390984,2469638,2188813,2542405,2601381,1705357,2068880,2068927,2533473,2512913,2494527,1787026,2596562,2596563,2385856,2042657,2077688,2156162,2192450,2255455,2376010,2377113,2378541,2407054,2455529,2488456,2527778,2571518,2444149,2513387,2185736,2345639,2175220,2240313,2360824,2176531,2361750,2496734,2570472,2544690,2544699,2447514,2401854,1770552,2522192,2261742,2227215,2496459,2200602,2200603,2517009,2430441,2461596,2429711,2402536,2543978,2409177,1142415,2533915,2562118,2402638,2585739,2495902,2572730,2481394,2528828)";
            $query = $this->dbHandle->query($sql,array($finalMsgIds));
            if($this->dbHandle->affected_rows() > 0) {
                echo "updated successfully";
            } else {
                echo "no data to update";
            }
        }
        }
        return;

    }

    function getAdmissionWikiContent($instituteId) {
        $this->initiateModel('write');

        $sql = "select description, listing_id from shiksha_institutes_additional_attributes where description_type = 'admission_info' and listing_type in ('institute', 'university') and description is not null and status = 'live' ";
        if(!empty($instituteId)) {
            $sql = $sql." and listing_id = ?";
            $params[] = $instituteId;
        }

        $rs = $this->dbHandle->query($sql, $params)->result_array();

        foreach ($rs as $key => $value) {
            $result[$value['listing_id']] = $value['description'];
        }
        
        return $result;
    }

    function updateAndInsertAdmissionWikiData($listingId, $wikiConvertedValue) {
        if(empty($wikiConvertedValue) || empty($listingId))
            return;

        $this->initiateModel('write');
        $this->dbHandle->trans_start();

        $sql = "select * from shiksha_institutes_additional_attributes where description_type = 'admission_info' and status = 'live' and listing_id = ? ";
        $params[] = $listingId;

        $result = $this->dbHandle->query($sql, $params)->row();
        
        //mark row history
        $params = array();
        $sql = "UPDATE shiksha_institutes_additional_attributes SET status = 'history' WHERE id = ? ";
        $params[] = $result->id;
        $this->dbHandle->query($sql, $params);
        
        //insert new row
        $wikiData['listing_id'] = $result->listing_id;
        $wikiData['listing_type'] = $result->listing_type;
        
        $wikiData['page_h1'] = $result->page_h1;
        $wikiData['page_title'] = $result->page_title;
        $wikiData['page_description'] = $result->page_description;

        $wikiData['description'] = $wikiConvertedValue['html'];
        $wikiData['description_type'] = "admission_info";
        $wikiData['toc'] = $wikiConvertedValue['tocContent'];
        $wikiData['posted_on'] = $result->posted_on;
        $wikiData['status'] = "live";
        $wikiData['updated_by'] = -1;
        
        $this->dbHandle->insert('shiksha_institutes_additional_attributes', $wikiData);

        $this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
            throw new Exception('Transaction Failed');
        }
    }
}