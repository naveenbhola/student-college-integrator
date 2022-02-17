module.exports = Object.freeze(
  {
    "menuLayer1Groups": {
        "grp1": "COURSES / COLLEGES",
        "grp2": "EXAMS",
        "grp3": "TOOLS",
        "grp4": "EXPERT GUIDANCE",
        "grp5": "RESOURCES",
        "grp6": "ABOUT SHIKSHA"
    },

    "menuLayer1Subtitles":
    [
        {'name':'Find Colleges by Specialization', 'group':'grp1', 'apiCall':'getFindCollegesHtml'},      //0
        {'name':'Find Colleges by Course', 'group':'grp1', 'apiCall':'getFindCollegeByCourseHtml'},       //1
        {'name':'College Rankings', 'group':'grp1', 'apiCall':'getRankingMenuHtml'},                      //2
        {'name':'Read {name} Student Reviews', 'group':'grp1', 'apiCall':'_getReviewsHtml'},              //3
        {'name':'Compare Colleges', 'group':'grp1', 'apiCall':'getCompareCollegesHtml'},                  //4
        {'name':'View {name} Exam Details', 'group':'grp2', 'apiCall':'getViewExamDetailsHtml'},          //5
        {'name':'Check {name} exam dates', 'group':'grp2', 'apiCall':'getExamImportantDatesHtml'},        //6
        {'name':'Predict your Exam Rank', 'group':'grp3', 'apiCall':'getRankPredictorHtml'},              //7
        {'name':'Predict college basis rank/score', 'group':'grp3', 'apiCall':'getCollegePredictorHtml'}, //8
        {'name':'IIM & Non IIM Call Predictor', 'group':'grp3', 'apiCall':'getIIMPredictorHtml'},         //9
        {'name':'Check Alumni Salary Data', 'group':'grp3', 'apiCall':'getAlumniHtml'},                   //10
        {'name':'Ask Shiksha Experts', 'group':'grp4', 'apiCall':'getAnaLayerHtml'},                      //11
        {'name':'Ask Current {name} Students', 'group':'grp4', 'apiCall':'getCampusRepPrograms'},         //12
        
        {'name':'News & Articles', 'group':'grp5', 'apiCall':'getNewsHtml'},                              //13
        {'name':'Apply to {name} colleges', 'group':'grp5', 'apiCall':'getApplyCollegesHtml'},            //14
        {'name':'Student Discussions', 'group':'grp5', 'apiCall':'getDiscussionsHtml'},                   //15
        {'name':'View Student Questions', 'group':'grp5', 'apiCall':'getQuestionsHtml'},                  //16
        
        {'name':'Learn About Us', 'group':'grp6', 'apiCall':'getAboutHtml'},                              //17
        {'name':'Student Helpline', 'group':'grp6', 'apiCall':'getHelpHtml'}                              //18
        
    ],
    
    "streamWiseMenuIds":
    {
        0:[0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
        1:[0, 1, 2, 3, 4, 5, 6, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
        2:[0, 1, 2, 3, 4, 5, 6, 7, 8, 11, 12, 13, 14, 15, 16, 17, 18],
        3:[0, 1, 2, 3, 4, 5, 8, 11, 12, 13, 15, 16, 17, 18],
        4:[0, 1, 2, 3, 4, 5, 8, 11, 12, 13, 15, 16, 17, 18],
        5:[0, 1, 2, 3, 4, 5, 11, 12, 13, 15, 16, 17, 18],
        6:[0, 1, 2, 3, 4, 5, 11, 12, 13, 15, 16, 17, 18],
        7:[0, 1, 2, 3, 4, 5, 11, 12, 13, 15, 16, 17, 18],
        8:[0, 1, 2, 3, 4, 5, 11, 12, 13, 15, 16, 17, 18],
        9:[0, 1, 2, 3, 4, 5, 11, 12, 13, 15, 16, 17, 18],
       10:[0, 1, 2, 3, 4, 5, 11, 12, 13, 15, 16, 17, 18],
       11:[0, 1, 2, 3, 4, 5, 11, 12, 13, 15, 16, 17, 18],
       12:[0, 1, 2, 3, 4, 5, 11, 12, 13, 15, 16, 17, 18],
       13:[0, 1, 2, 3, 4, 5, 11, 12, 13, 15, 16, 17, 18],
       14:[0, 1, 2, 3, 4, 5, 11, 12, 13, 15, 16, 17, 18],
       15:[0, 1, 2, 3, 4, 5, 11, 12, 13, 15, 16, 17, 18],
       16:[0, 1, 2, 3, 4, 5, 11, 12, 13, 15, 16, 17, 18],
       17:[0, 1, 2, 3, 4, 5, 11, 12, 13, 15, 16, 17, 18],
       18:[0, 1, 2, 3, 4, 5, 11, 12, 13, 15, 16, 17, 18],
       19:[0, 1, 2, 3, 4, 5, 11, 12, 13, 15, 16, 17, 18]
    },

    'streamToTagsMapping':
    {
      1:{'id':17, 'name': 'Business Management Studies'},
      2:{'id':20, 'name': 'Engineering'},
      3:{'id':10, 'name': 'Design'},
      4:{'id':22, 'name': 'Hotel Management'},
      5:{'id':15, 'name': 'Law'},
      6:{'id':365,'name': 'Animation'},
      7:{'id':118,'name': 'Mass Communication'},
      8:{'id':266,'name': 'Information Technology'},
      9:{'id':19, 'name': 'Arts & Humanities'},
      10:{'id':19,'name': 'Arts & Humanities'},
      11:{'id':18,'name': 'Science'},
      12:{'id':21,'name': 'Architecture'},
      13:{'id':55,'name': 'Accounting'},
      14:{'id':8, 'name': 'Banking,Finance & Insurance'},
      15:{'id':24,'name': 'Aviation'},
      16:{'id':16,'name': 'Teaching & Education'},
      17:{'id':83,'name': 'Nursing'},
      18:{'id':12,'name': 'Medical'},
      19:{'id':114,'name': 'Beauty'}
    },

    'askCurrentStudents':
    {
      1:['MBA','BBA'],
      2:['Engineering','B.Tech'],
      3:['Design'],
      5:['Law'],
      7:['Mass Communication']
    },

    'collegeReviewsUrl':
    {
      1:{'name': 'MBA','url':'/mba/resources/college-reviews'},
      2:{'name': 'B.Tech','url':'/btech/resources/college-reviews/1'}
    },

    'applyColleges':
    {
      1:{'name': 'MBA','url':'/mba/resources/application-forms'},
      2:{'name': 'Engineering','url':'/engineering/resources/application-forms'}
    },

    'examImportantDatesUrl':
    {
      1:{'name': 'MBA','url':'/mba/resources/exam-calendar'},
      2:{'name': 'Engineering','url':'/engineering-exams-dates'}
    },

    'aboutusUrl'      : '/mcommon5/MobileSiteStatic/aboutus',
    'articleUrl'      : '/articles-all',
    'helplineUrl'     : '/mcommon5/MobileSiteStatic/studentHelpLine',
    'contactusUrl'    : '/mcommon5/MobileSiteStatic/contactUs',
    'iimPredictorUrl' : '/mba/resources/iim-call-predictor',
    'profileUrl'      : '/userprofile/edit',
    'discussionsHome' : '/discussions',
    'HAMBURGER_UPDATE_KEY' :  1523441445008    /*Add in the formate of Unix timestamp, ex: Wed 11 April 2018
                                                 15:40:45 => 1523441445008 (milliseconds)*/
});
