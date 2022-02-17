<?php

$config[] = array();

define('INSTITUTE_ONLY','listingOnly');
define('INSTITUTE_VIEW_COUNT_SECTION_DATA','instituteViewCount');

define('INSTITUTE_COURSE_STATUS','universityTypeChange');

$config['INSTITUTE_SECTIONS'] = array(INSTITUTE_ONLY,INSTITUTE_VIEW_COUNT_SECTION_DATA);


define('COURSE_BASIC_SECTION_DATA','courseBasicSectionData');
define('COURSE_REVIEWS_SECTION_DATA','courseReviewsSectionData');
define("COURSE_VIEW_COUNT_SECTION_DATA",'courseViewCountSectionData');
define("COURSE_LOCATION_SECTION_DATA",'courseLocationSectionData');
define("COURSE_FEES_SECTION_DATA",'courseFeesSectionData');
define("COURSE_HIERARCHY_SECTION_DATA",'courseHierarchySectionData');
define("COURSE_EXAMS_SECTION_DATA",'courseExamsSectionData');
define("COURSE_ORDER_SECTION_DATA",'courseOrderSection');

define("COURSE_CAPROFILE_SECTION_DATA", 'courseCAProfileSection');
define("COURSE_PRIMARY_ID_SECTION","coursePrimaryIdSection");

define("POPULARITY_SECTION",'popularitySectionData');

$config['COURSE_SECTIONS'] = array(COURSE_BASIC_SECTION_DATA, COURSE_APPROVALS_SECTION_DATA, COURSE_REVIEWS_SECTION_DATA, COURSE_VIEW_COUNT_SECTION_DATA, COURSE_LOCATION_SECTION_DATA, COURSE_FEES_SECTION_DATA, COURSE_HIERARCHY_SECTION_DATA, COURSE_EXAMS_SECTION_DATA,POPULARITY_SECTION,COURSE_ORDER_SECTION_DATA,INSTITUTE_COURSE_STATUS);

define("INSTTIUE_SYN_DEL",';');
define("STREAM_SYN_DEL",',');
define("SUBSTREAM_SYN_DEL",',');
define("SPEC_SYN_DEL",',');
define("BASE_COURSE_SYN_DEL",',');
define("POP_GRP_SYN_DEL",',');
define("CERT_PROV_SYN_DEL",',');
$config['entityToDelMap'] = array(
									'stream' => STREAM_SYN_DEL,
									'substream' => SUBSTREAM_SYN_DEL,
									'specialization' => SPEC_SYN_DEL,
									'base_course' => BASE_COURSE_SYN_DEL,
									'certificate_provider' => CERT_PROV_SYN_DEL,
									'popular_group' => POP_GRP_SYN_DEL
							);

define("INDEXING_BATCH_SIZE",10);
define("INDEXING_BATCH_SIZE_AUTOSUGGESTOR",50);

$config['FULL_INDEXING_SECTIONS'] = array(COURSE_HIERARCHY_SECTION_DATA, COURSE_LOCATION_SECTION_DATA,COURSE_PRIMARY_ID_SECTION);

$config['CourseSectionMappings'] = array(
								COURSE_BASIC_SECTION_DATA => 'basic',
								COURSE_REVIEWS_SECTION_DATA => 'review_count',
								COURSE_VIEW_COUNT_SECTION_DATA => 'view_count',
								COURSE_LOCATION_SECTION_DATA => 'location',
								COURSE_FEES_SECTION_DATA => 'fees',
								COURSE_HIERARCHY_SECTION_DATA => 'course_type_information',
								COURSE_EXAMS_SECTION_DATA => 'eligibility',
								COURSE_CAPROFILE_SECTION_DATA => 'course_cr_exist',
								'LAST_MODIFY' => 'last_modify_date',
								COURSE_ORDER_SECTION_DATA => 'course_order',
								INSTITUTE_COURSE_STATUS => 'course_status'
							);
$config['InstituteSectionMappng'] = array(
								INSTITUTE_ONLY => array('basic','facility','last_modify_date'),
								POPULARITY_SECTION => 'popularity',
								INSTITUTE_VIEW_COUNT_SECTION_DATA => 'view_count'
							);

$config['PCW_INSTITUTES'] = array(48765,45586,20462,28252,43993,44019,49178,19830,49383,49450,47503,49455,2501,50574,50796,49180,51550,51568,51532,51746,51579,51747,51772,51535,51552,50617,50787,50788,50797,50800,50801,50802,50804,50803,50807,50808,51816,51817,51813,51825,51837,51842,51845,38237,25040,44096,44122,49100,29030,51603,43199,13443,1705,22294,49398,43934,51602,51650,51671,51688,51690,51692,51764,47658,51820,49378,22660,11080,4940,7818,38510,36684,46040,23697,47663,21313,20284,19601,40917,13584,20926,8545,44150,44125,21107,24298,48591,21003,38300,4285,22872,47536,25008,42413,3042,20813,20844,21130,22431,22504,29761,37872,29722,45481,1734,23576,4245,51892,22080,21908,19383,24758,38266,48004,36800,48342,48491,47677,46843,19927,21872,23062,48162,48178,20273,19751,20065,38234,20801,37152,21142,21138,24067,40887,37422,20515,23595,21392,52406,21439,48764,36132,22526,3336,3391,40955,22527,34650,50532,40933,39575,43166,40252,47409,4049,2131,1207,4476,43191,47651,48325,48290,49430,52511,52518,52535,52536,38258,52544,52537,52560,52570,52578,52592,52596,52600,52605,52610,50242,46872,52733,7475,52945,52952,52956,3378,27352,53067,53076,53073,53079,53081,53092,53102,53107,53115,52940,51697,53139,53188,53241,36789,53245,53268,51555,53292,53318,53349,53367,53390,53391,53395,53420,53424,53430,53418,53433,53435,53436,53243,23628,53480,52721,53661,53666,53677,24435,52783,51679,23939,45491,53671,53481,53425,53084,53255,51677,51678,52859,53434,51676,51684,51685,51682,51686,51681,28573,51444,51442,23914,19678,23932,53574,49141,51448,51449,1765,24080,25334,27351,27352,27355,27993,27354,45451,45466,45496,50219,51558,53569,23957,21883,23964,51451,51452,53880,49098,196,53887,53889,53879,51454,51456,53892,53898,51457,23938,921,51675,51674,51673,53388,51672,4235,50696,49343,50760,3046,53847,49137,50758,50755,23868,51458,50776,51459,50656,52590,53557,53558,53578,53579,53582,53587,53595,53596,52265,49459,51055,23974,50708,53701,49851,53704,53703,53804,53705,28150,50747,50812,53702,53792,36935,54009,50785,54015,54043,50557,50810,54077,53793,54078,23941,54080,49139,50698,30895,50806,50805,24321,54010,54052,54076,54079,54123,22999,23977,52279,23942,49128,23949,50573,51453,28048,23873,53870,49114,41860,51236,28324,49106,23955,25091,23960,49127,53714,54221,49147,54113,53706,50809,25063,53008,22172,54263,19349,54267,54268,50665,54269,54274,54276,13723,45501,54343,49168,49169,2311,52194,52526,26464,53486,54277,51298,49370,51551,50366,43025,48793,771,24319,23727,46534,49390,28056,54457,54467,28504,47509,35000,54518,54519,54520,4378,22175,44136,5516,10303,19836,23387,24272,24544,43960,25867,30946,44076,47754,49133,42938,21446,20615,53906,25278,54609,5948,54600,51556,51557,36879,54582,54726,27353,54646,54704,54753,48061,48762,51053,51058,51078,51796,51255,49374,24658,3068,46536,55239,51324,51840,55355,25241,55381,51537,54895,51307,51313,51052,47813,55637,55357,37230,37899,48679,49348,49297,21177,50998,55093,56817,23289,23934,23979,21840,23921,23975,51560,23966,1363,42965,49461,49382,55443,29580,2465,52192,21587,38291,51567,53682,53075,54006,54136,54071,54132,25064,24338,24345,19735,38160,52744,24330,25156,46868,23956,38165,54327,48265,52533,25803,19798,57555,28939,919,37974,21884,53790,57535,19408,49136,50604,33410,31769,29575,49458,50786,53784,28274,58133,51059,58293,58305,58269,58325,58251,36135,58279,58229,58343,58339,58295,58267,43137,23863,58319,58347,58367,58329,58373,58379,58407,58303,58397,58419,58433,58371,58391,58447,58443,58441,58425,58417,58489,23900,58377,58503,58243,23763,58507,58517,58505,21598,58485,38035,23953,23865,58557,58625,58639,13614,58701,58721,58727,58705,58729,58271,58715,58741,58747,58749,58751,58771,58763,53530,56129,58523,58743,47650,58809,23897,21441,1163,58853,59029,58009,59095,58587,52563,59107,59117,59121,59103,23936,48338,20896,49035,54084,57585,20473,20010,51531,34764,51797,29767,50759,48335,43994,25038,51790,40931,51549,48346,37961,59307,59321,59327,59337,59333,59339,59455,59417,59811,49112,23907,3090,49181,60045,2633,60295,60477,60680,60549,60728,60737,61261,61299,61087,61133,51533,51539,60967,61435,61443,61463,61485,49429,36076,3075,29738,50351,22064,49135,25083,23940,5946,3066,54845,19398,38238,49124,21548,20605,21844,51648,37215,3067,39601,23931,23867,31349,61977,61969,61971,61975,32492,49142,62003,62007,50358,62097,29707,52509,2319,2376,23892,23925,48057,49143,57303,62307,3062,49116,58733,13416,28228,917,23895,26151,42952,21460,2825,20397,6587,51583,23948,20367,48333,52968,27356,19300,19322,49023,28361,32740,47712,47703,36080,47711,47709,36085,49314,23700,32736,54086,25189,49125,23927,28834,4247,25268,36766,47529,23969,4288,53200,53298,51546,23147,39238,25425,35298,32739,39201,53834,13669,53208,42457,24372,24366,24264,2974,20188,333,36786,51909,24247,24399,32742,59093,37509,23909,26227,26486,49131,3692,29623,48065,21639,39180,39335,33322,3031,23526,24094,20190,49227,42517,42458,42454,29260,20096,2999,20038,19301,23887,32728,3051,53488,23911,35998,54089,53874,54005,54109,54099,54122,54110,54107,54121,54120,53864,54112,54111,54108,53862,53886,53882,53873,53863,53858,53856,53854,1855,318,54241,54223,54227,54209,54226,54237,54239,54240,54238,54225,54224,28285,54228,54213,54205,54184,53945,53922,53893,53969,53968,53936,53943,53953,53959,53975,53981,53978,53944,53960,42933,53988,54139,53942,53935,53930,53927,53926,53924,53823,53809,53811,53813,53815,53822,53814,53810,53808,53812,53816,53817,53818,53819,53820,53821,53963,53966,53958,53991,53976,54007,53961,54017,54014,54011,54004,53996,53794,53787,32712,3065,32726,3011,32705,38240,29885,54229,54222,23930,32717,53791,94,3429,1227,32693,2694,23872,26718,46857,49179,891,25116,20747,28061,57967,2954,26826,307,48056,3057,32697,53829,53833,27350,22559,25255,23645,38584,20726,22611,39060,23159,23933,39397,49182,26494,38697,24187,25123,25254,31438,38286,43907,51576,25246,43870,52577,22301,35661,52100,52914,52934,38178,52096,53654,24842,23733,52258,22950,47622,51901,51913,51900,52502,51418,55033,51181,55975,52890,37071,4350,25252,32725,46862,57013,57037,57101,57063,57159,50986,51542,1475,21454,25276,22358,23071,3701,843,29742,3084,24725,19961,23086,25207,41579,22928,25175,2948,31514,3891,1230,25297,25100,52114,4225,50956,23227,3574,25770,25136,38374,32711,31157,53766,32713,50664,20056,53955,53951,53950,32767,28013,26443,19992,1626,23421,23568,25270,57625,20977,23069,29839,24865,22026,32704,20616,25287,22122,2878,32702,43347,53606,51414,32738,23083,19953,23593,23336,54423,52422,51693,51691,21430,32716,51196,24140,52398,52400,43026,52395,24117,4217,55311,52420,52233,20508,57789,54387,22077,55045,38180,24702,53518,23639,37125,55383,24108,55425,51415,23660,19556,22263,4001,51530,52875,52201,52004,25033,26456,25295,23532,21408,51562,26448,25269,24932,3273,56785,51540,32017,32706,26281,24799,36793,53656,903,50182,25236,31110,23525,2102,52421,51378,24694,22226,23876,4026,51529,41368,52891,57627,25097,42983,52618,24587,51325,47550,21940,24889,24105,52275,25212,25285,47485,24674,52803,269,22685,905,51659,49616,25125,43914,58055,23122,53604,54625,25787,58073,52865,52573,20980,4194,58117,55963,40939,58179,22104,52514,58255,37240,51545,25093,58101,48862,58199,58423,24259,20578,51403,25089,23466,58521,58135,4371,23065,58515,58385,58567,54338,52954,26165,58601,58645,58583,58561,58509,58699,44078,58731,58283,32709,58739,58745,52955,5040,24948,32735,49879,58393,58779,58777,58795,986,24253,58553,59075,29811,37065,21456,23679,19694,51570,856,3851,54272,20576,37093,29852,4198,25139,19437,51899,54963,51910,10826,51351,23625,23072,48203,25211,3030,21452,25147,22416,23417,25004,43891,23402,60281,53246,53187,47284,24763,58563,20436,29860,61239,61245,61509,61799,54150,21074,1276,24093,22450,22128,23920,61961,57197,19965,20039,25197,58253,309,49764,909,1610,483,24666,51320,24782,25028,22783,58431,57071,25222,38229,51572,51559,51421,58525,24395,32722,53385,27704,21570,54402,58093,58297,28603,25196,19392,25277,8509,22540,51598,36944,24886,50562,51534,24642,20593,29851,25380,63313,37081,55541,57293,57843,57835,58473,58789,59051,59227,57649,59593,59665,59869,59943,60217,60353,60596,60720,60614,61833,62311,62377,62507,62749,4420,51822,22474,52958,57737,55109,49402,42884,61241,56199,53859,39384,38111,39366,46852,51894,53876,39392,53841,53843,53806,53802,53868,53869,53849,46856,53865,59105,53872,54218,2996,28530,54019,28583,24357,54212,53938);

?>


