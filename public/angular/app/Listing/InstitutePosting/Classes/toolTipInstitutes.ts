export class toolTipInstitutes
{
	toolTipArray : Object  = {'institute_type':'&nbsp;This field is relevant mainly for institutes that come under a Universities (including IITs). It identifies whether an institution is of the following type - College, School, Department, Center, Academy or a Faculty. <br/><br/> Note: Strictly go by the nomenclature of university while populating this field (no subjective call). E.g. Faculty of Management Studies (FMS), Delhi is a "Faculty" and not a "College" type entity',
                               'add_parent_entity':'&nbsp;<b>Institue Posting:&nbsp;&nbsp;</b><br/><br/>Parent (University, College, Department, Academy, etc.) which directly governs/manages this institute needs to be added.<br/><br/>University should be added as a parent entity ONLY for constituent colleges and NOT for affiliated colleges.<br/><br/> E.g. - 1) Faculty of Arts, DU will have DU as a parent. <br/> 2) Department of English, DU will have Faculty of Arts, DU as a parent.<br/> 3) St. Stephen\'s College, Delhi will have DU as a parent <br/><br/>Satellite Campus- <br/>A satellite campus or branch campus is a campus of a college or university that is physically at a distance from the original university or college area. This branch campus may be located in a different city, state, or country, and is often smaller than the main campus of an institution. <br/><br/> E.g.- <br/> 1) IIM Lucknow has a satellite campus in Noida. <br/> 2) IIM Indore has a satellite campus in Mumbai <br/><br/> Note: Only one parent can be added at a time. If a college is part of a university and also a group (e.g. Amity Business School comes under Amity University which is part of Amity Group), map it to only the University, which in turn should be mapped to the Group. <br/> Similarly, for a satellite campus, only one parent (it\'s parent institute) needs to be added<br/><br/><b>University Posting:</b>&nbsp;&nbsp;This field needs to be populated while creating a campus of a university.<br/>For e.g. – Amity University, Noida has a campus in Lucknow. So, while creating Amity University, Lucknow listing, Amity University, Noida will have to be marked as a parent entity. ',
                                 'institute_hierarchy':'Note:Please make sure, you add a parent which is the direct parent of this institute. <br/> For e.g. : DU has a "Faculty of Arts" which has a "Department of English". So, for Department of English, Faculty of Arts has to be mapped as a parent, and not DU itself.',
                                   'location':'In case of a multi-location listing, the head-office location needs to be entered here. <br/> Locality to be entered only for Hyper-local colleges/institutions',
                                   'short_name':'This field needs to be filled to filled for departments/faculty/center/academy/schools that comes under a university. <br/> &nbsp; Use case - Showing on the university page.<br/> For E.g: <br/>1) In case of Faculty of Management Studies, Delhi University, Short name will be "Faculty of Management Studies".<br/> 2) For Alliance Business School, Alliance University, Short name will be "Alliance Business School".',
                                   'abbreviation':'- This strictly has to be an abbreviation by which a college is known as.<br/>- Only one entry is allowed.<br/>- This is an optional field and only those abbreviations that are actually popular need to be filled here.<br/>- Popularity of abbreviations need to be considered either via search volumes or via college site (where it is denoted by its abbreviation).<br/> E.g. - <br/>1) SP Jain<br/> 2) IIT Delhi <br/>3) IIM Ahmedabad <br/> &nbsp;In cases where are multiple colleges available with a same abbreviation (especially applicable mainly for popular groups like IITs, NIDs, NIFTs, etc.) <br/>4) NID Ahmedabad <br/> 5) NIFT Delhi',
                                   'synonymn':'Only those synonyms by which a college is known should be added. <br/> For E.g :- <br/>1) Indian Institute of Technology, Delhi - IIT Delhi, IIT-D. <br/>2) SP Jain Institute of Management and Research, Mumbai - SP Jain, SPJIMR, SP Jain Mumbai, SPJIMR Mumbai. <br/>3) Arena Animation, Connaught Place, Delhi - Arena Animation CP <br/> What should not be added?. <br/> Text like - "Best college" , "Top MBA college", "IIT Btech", etc. <br/> Note - Abbreviations which are already entered in the abbreviations field need not be entered here again',
                                   'autonomous':'There could be 2 types of autonomous institutes. <br/> Type 1 - Colleges like IIMs, IITs, NITs, etc. For complete list, refer to https://en.wikipedia.org/wiki/List_of_autonomous_higher_education_institutes_in_India.  <br/>  Type 2 - Colleges approved by UGC as autonomous. For complete list, refer to http://www.ugc.ac.in/oldpdf/colleges/autonomous_colleges-list.pdf',
                                   'naac_accreditation':'The National Assessment and Accreditation Council (NAAC) is an organisation that assesses and accredits institutions of higher education in India. <br/>  Grading System: <br/> Grade A++: 3.76 – 4.00<br/> Grade A+: 3.51 – 3.75<br/> Grade A: 3.01 - 3.50<br/> Grade B++: 2.76 – 3.00<br/> Grade B+: 2.51 – 2.75<br/> Grade B: 2.01 – 2.50<br/> Grade C: 1.51 - 2.00<br/> Grade D: <=1.50',
                                   'faculty_high':'This section is optional and should be populated only when there is something worth mentioning about the faculty of the college. Some sample points:<br/>- Arena Animation gets vising faculty from prestigious colleges like NID, Ahmedabad and Pearl, Mumbai.<br/>- The faculties of Arena Animation, Mumbai has published over 123 research papers in international journals.<br/>- Top faculties of Arena Animation have authored/co-authored more than 250 books on Design Animation.<br/>- More than 50% of the faculties at Arena Animation are PhD holders. 50% of the (non-PhD) professors are currently undergoing a doctoral program and will be honored with a PhD degree soon.',
                                   'scholarship':'Only those scholarships/financial assistance that are mentioned on the college site need to be entered here. <br/><br/>Format:<br/> {Scholarship Name - If available}: {Scholarship Description} <br/> Note: In no case, should we enter just the scholarship name. There HAS to be some description attached to it. Even if the description is as small as following, it should be entered: <br/>"ABC Scholarship will be awarded to students who score well in the first year results". <br/> An ideal description would be something like the following: "IIMA Special Need Based Scholarships: All students admitted to the post-graduate programs (PGP & PGP-FABM), whose gross annual family income is below Rs.15,00,000 during the previous financial year are eligible to apply for this scholarship, subject to proof of family income, amount of educational loan taken, and candidate’s past earnings, etc. Family income includes total income of parent(s)/guardian(s) and spouse if married. The income before tax deduction will be considered. IIM-A shall determine the financial assistance required for each applicant based on its assessment of the applicant’s economic status evaluated through criteria like movable and immovable family assets, the number of dependents on the family income, and any other criteria it may deem necessary.',
                                   'events':'Only recurring events should be added here. One-time events should not be added. <br/> &nbsp;&nbsp; Name - Should be the official name of the event. In case the event doesn\'t have a specific name, the name should be derived from its type. For e.g. - <br/>1) Graphic Design Workshop. <br/>2) Hacking Workshop. <br/>3) Cultural Fest. <br/>4) Wine Tasting Tour. <br/>Description - It should be descriptive enough to explain what the event is. However, it should not be verbose and should not have information that doesn\'t help users much. E.g :<br/>College - IIM-A,<br/> Type - Cultural Fest,<br/> Name - Chaos <br/>Description - In a short span of just 6 years, Chaos has become the country’s biggest and best cultural festival. The USP of the event is 80 hours of non-stop ‘entire-tainment,’ It gives students a chance to engage in a wild and wacky weekend of revelries.<br/> The event draws participants not only from within Ahmedabad but also colleges from all over India. The event features a gamut of cultural and fine arts events, ranging from choreographed dance displays and fashion parades to numerous exciting management games to tickle the wits of the best and the brightest who visit the campus. Plays, pop and rock shows, fusion and fashion shows, debates, quizzes, and Mr and Ms Chaos ensure that the atmosphere is always electric and there’s never a dull moment.',
                                   'usp':'Only following categories can be covered in this section:<br/> <b>1) Rewards and Recognition</b> - International accreditation by Accreditation Council for Business Schools anc Programs (ACBSP)<br/> Note - Rankings for which we are already capturing should not be mentioned directly here. E.g. - MBA Course ranked 8 by Times of India. However, we can have entries like following:-<br/> Ranked among top 100 B-schools in the FT International Annual B-School Survey. <br/><b>2) Faculty</b> - Arena Animation gets vising faculty from prestigious colleges like NID, Ahmedabad and Pearl, Mumbai.<br/><b>3) Infrastructure </b>- The Olin College of Engineering campus encompasses more than 300,000 sq. ft of area <br/>- Velammal Engineering College is spread over 37 acres<br/><b> 4) Winning in contests/seminars/events/etc.</b> - Winner of IIT-Bombay National Entrepreneurship Challenge 2015 <br/><b>5) Hosting of events/fests/etc.</b> - NID Ahmedabad hosts an international design workshop every year that attracts more than 10,000 students from over 15 countries <br/><b> 6) Founders</b> - XYZ college is founded by the prestigious Times of India Group <br/>- ABC college is founded by alumnus of prestigious Harvard University <br/>- First and Flagship Institute of Higher Education of Amity Group established in 1995<br/><b> 7) Batch strength, batch distribution, placements</b> - 25K students from 29 states & 50 countries <br/>- Many companies, from CISCO to Cadence, have established their centres of excellence at LPU to enhance student employability <br/><b>8) Study methods, collaboration, laboratories </b>- At LPU, students can attend classes in industry collaborated programmes and gain access to laboratories in centres of excellence established by companies <br/> Note: <br/>1) If any new types of USP are observed being promoted by colleges, please get in touch with the product team to see if they need to be included in the guidelines <br/> 2) This is an optional field and should be filled only when something relevant is found.<br/> 3) Maximum 8 USPs can be shown, so a proper call has to be taken in order to decide the USPs to be entered. In case of any doubt, please get in touch with the product team.',
                                   'brochure':'Only the college\'s official brochure needs to be uploaded here. Please note that a brochure which is more than a year old cannot be uploaded.',
                                   'contact_details':'Enter only the address of actual college premises. In "admission related queries" contact, enter only that contact which responds to the "admission related queries". If this is not available, it should be left empty.',
                                   'Photos':'Only the actual photographs of the college premises should be added. The first photograph will be used and shown everywhere (category pages, search results page, recommendation widgets, etc.), so make sure that it is a better image of all. It should show college campus, and should not have images that are unclear or have text in them.',
                                   'Videos':'Only the actual photographs of the college premises should be added. The first photograph will be used and shown everywhere (category pages, search results page, recommendation widgets, etc.), so make sure that it is a better image of all. It should show college campus, and should not have images that are unclear or have text in them.',
                                   'institute_name':'<b>Institute Name (College Type Entity)</b>:<br/>Format for name:<br/>{Complete Name}<br/><br/> E.g :1) Indian Institute of Management, Ahmedabad<br/>2) National Institute of Design, Ahmedabad<br/>3) Indian School of Business<br/>4) Birla Institute of Science, Pilani<br/><br/>Note 1 - City should be added in the name only when it is part of the actual name (e.g. for all IITs, IIMs, etc.) <br/>Note 2 - In case of a multi-locality listing (where we are creating separate listings), locality name should also be added in the <Complete Name>. E.g. - Arena Animation, Whitefield and Arena Animation, GK<br/><br/><b>Institute Name (Satellite Campus Case)</b><br/>{Parent Complete Name} - {Location} Campus<br/>E.g. -<br/>1) Indian Institute of Management Lucknow - Noida Campus<br/>2) Birla Institute of Science, Pilani - Goa Campus<br/>Note 1 - In case of satellite campus, if the name is becoming too long, only the abbreviation/short-name of parent college can be used. Even in this case, the abbreviation should be a popular one (to be checked bases search volumes). In case of any confusion, please reach out to product team.<br/>E.g. - If "SP Jain Institute of Management and Research (SPJIMR), Mumbai" had a campus in "Pune", it could have been named - "SP JAin, Mumbai - Pune Campus" (Note - SP Jain was used instead of SPJIMR, as SP Jain has more searches than SPJIMR)<br/><br/><b>Institute Name (Department/Faculty/Center/Academy Type Entity)</b><br/>Format for name:<br/>{Complete Name}, {Parent College/University Name}<br/>E.g. -<br/>1) Faculty of Management Studies, Delhi University<br/>2) Department of Commerce, Delhi University<br/>3. Lok Kala Academy, Mumbai University<br/>Note 1 - In case the parent name is becoming too long, only the abbreviation/short-name of parent college can be used. Even in this case, the abbreviation should be a popular one (to be checked bases search volumes). In case of any confusion, please reach out to product team.<br/>E.g. - Department of Management Studies, IIT Delhi<br/><br/><b>Institute Name (School Type Entity)</b><br/>In this case, subjective call will have to be taken bases popularity of the school.<br/>Case 1 - The school is popular and known in itself<br/>Format - {Complete Name} ({Abbreviation})<br/>E.g. - Amity Business School<br/>Case 2 - The school is not very popular<br/>Format - {Complete Name}, {Parent College/University Name}<br/>E.g. - Kusuma School of Biological Sciences, IIT Delhi<br/>Note 1 - To know if the school is popular or not, look at the search volumes. In case of any confusion, please reach out to product team.<br/> <br/> <b>University Name(Main University)</b><br/>Format for name:{Complete name}<br/>This should be the registered name of the university and should not include location in it, unless the location is part of the name. <br/>E.g:<br/>1) University of Delhi<br/>2) Indira Gandhi National Open University<br/>3) Amity University, Noida<br/>Note – You can refer to the UGC’s university list to find the actual name of the university<br/><br/><b>University Name (Campus)</b><br/>Format for name:<br/>{Parent Complete Name} - {Location} Campus<br/>E.g. -<br/>Amity University, Noida – Lucknow Campus<br/><br/>Note - In case the name of parent university is becoming big, we can use its abbreviation/short name. Even in this case, this abbreviation/short name should be a popular one (to be checked bases search volumes). In case of any confusion, please reach out to product team.<br/>E.g. –<br/>SRM University, Sonepat – Ghaziabad Campus',
                                   'infra_facilities':'Only those infrastructure/facilities which are mentioned on the college site or which substantial proof is available, should be added here.<br/>Note - There is no need to fill this field, if there is nothing worth mentioning.<br/><br/>USPs:<br/><br/><b>Hostel:</b>The Residential Facilities includes Hostels for all the students and Quarters for staff and faculty.<br/><br/>Single-seated/twin-sharing accommodation facilities are available for all the students in the campus premises with separate blocks/floors for men and women. Aesthetically designed, the hostel blocks are spread around the canteen, on the sides of the hillock. The hostels are interconnected, mostly in blocks of three.<br/><br/>Each of the blocks has a square in the center where basketball/badminton courts with artificial turf are maintained, with some blocks also having lawns in the square. Each hostel block has a common room where facilities such as wall-mounted televisions with DTH connection, table-tennis tables, dart game and carrom and chess games are available. It is the common room that provides the scene of action for get-togethers, parties and informal discussions. Each block is also equipped with water coolers and water purifiers.<br/><br/>The individual rooms are furnished with teak cots and mattress, a computer table, chairs, closets and shelves. All rooms have full-time internet connectivity with direct lines to the main LAN. The housekeeping staff is available throughout the week to take care of cleaning of rooms, corridors and rest rooms on a daily basis.<br/><br/><b>Library:</b>The IIM Indore Learning Centre (LC) with its modern collection of knowledge resources and innovative information services; fills an essential role for students, faculty, and the surrounding community in their intellectual pursuits. It is a hybrid library with the state-of-the-art technological applications, holding knowledge resources predominantly related to management and allied subjects. The entire LC collection including the CD-ROM databases and the online databases are made available through Institute’s network. The LC offers a range of information services to support the learning process set to the highest professional standards. IIMI Library is an active member of IIM Consortia & INDEST Consortia.<br/><br/><b>Cafeteria:</b>Dedicated mess facilities and round the clock canteen facilities with vegetarian and non-vegetarian food are also available to the students. Various other snacks, biscuits, chocolates, ice cream and soft drinks are available in the mess itself. Apart from the regular mess, a night canteen is run from 11 p.m. to 5 a.m. for students to order anything from noodles to parathas. Apart from this, JAM— students’ initiative that provides fresh fruits and juices etc. are also available in the hostel premises.<br/>The students also run a self-managed goods store— PI Shop that stocks anything from stationery and snacks to self-care products and daily need items.<br/><br/><b>Sports Complex: </b>Delhi College of Engineering is having 450 m. track, ground for Football, Hockey, Cricket, two courts for Volley ball, two courts for basketball, three courts for Tennis Iand five courts for Badminton, Table Tennis rooms, Chess Rooms, Carrom Rooms and Gyms are also available in the each hostel of the college campus.<br/><br/>With the view to recognize upcoming talented sportsman and sportswoman in the college, the Sports Council organizes sports festival ARENA. The festival witnesses the large participation of boys and girls which included athletics, badminton, table tennis, basketball, carrom, chess, cricket, tennis and volleyball. Prizes and certificates were awarded to the winners.<br/><br/>The sports year also involves the organization of the Inter Hostel Tournaments in various games i.e. badminton, basket ball, carrom, chess, cricket, football, table tennis, volleyball and tennis.<br/><br/><b>Gym: </b>VIT\'s newest facilities for students include four gymnasium, two exclusively for men, one exclusively for women and one shared by both. A central multi-facility piece of equipment for the men enables several enthusiasts to work out at the same time in one of the gymnasium for men. The other men\'s gym today has 16 stations with the latest equipment. Women students have a 12-station multi-gym of their own. All the gyms are spacious and well equipped with modern facilities. Trendset, a state-of-the art gym with modern, imported equipment was inaugurated in 2004. It has centralised A/C and Audio/Video facilities, comfortable dressing rooms and ample locker facilities. Two qualified gym instructors are available round-the-clock to train students specifically for their respective sports. This gym has separate timings for men and women.<br/><br/><b>Labs:</b>There are 82 laboratories at UPES for domain specific practical learning as well as research and development activities. These include 7 innovative outdoor labs for sector centric learning and practice. The labs cater to pure sciences, engineering, advanced engineering as well as sector specific requirements.The university provides modern infrastructural & instructional facilities to the students and faculty to create a modern learning environment.  As new courses keep on adding to the list of program offerings, the infrastructure is also upgraded and new labs are added to accommodate the learning requirements.<br/><br/><b>Shuttle Service:</b>Manav Rachna International University has a fleet of luxury AC Buses providing transport facility to the students of Delhi-NCR covering various locations of Delhi, Noida and Gurgaon. It has also the fleet of Non-AC Deluxe buses for the local students from Faridabad, Ballabgarh and Palwal. MRIU buses are equipped with all safety devices i.e. GPS, to track the bus location, Route, Speed etc. and Speed Governor to restrict the speed of the buses as per the Supreme Court guidelines, Fire Extinguisher and First Aid Kit. From the safety point of view, Buses of MRIU board (at the time of departure from Campus) and de-board (after arrival at campus) the students inside the campus premises only. Students are allowed to board or de-board the bus at the scheduled stops only. During end-semester exam days, apart from the regular routes, we also provide the shuttle service for Tughlakabad Metro Station, Delhi, Mathura Road (Badkhal Chowk), Faridabad and Pali Chowk (at Surajkund-Faridabad Road for Gurgaon Students) from the campus after examination is over so that the student’s precious time can be saved. We also allot seat numbers to every transport user on the basis of first-come-first-serve (the student deposit the transport fee first, gets the seat first). Transport ID Card is valid for one Academic Session of the respective Institute. Transport fee is collected one time in advance for the whole academic session at the time of availing transport facility. MRIU is also providing FREE transport facility for the students who avail the Off-Campus Hostel Facility of MRIU. The complete transport information i.e. Routes, Fee Structure, Transport Refund Policy, Transport Guidelines etc. is also available at the website of MRIU.<br/><br/><b>Note:</b> In the others section, fill only the relevant entries. In case of any doubt, please get in touch with the product team.',
									'longitude_img' : 'To enter the coordinates of a college location, please search on google like this: <college name> latitude and longitude. We should avoid right clicking on the college in google maps and looking at “what’s here?” as it might not get exactly accurate results.'};
}