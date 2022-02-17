import config from './../../../../config/config';
module.exports = Object.freeze(
    {
        COLLEGE_BUCKET:      'College',
        UNIV_BUCKET:         'University',
        COURSE_BUCKET :      'Course',
        NO_URL:              'NA',
        TOPIC_BUCKET:        'Topic',
        CLOSED_SEARCH_URL:   config().SHIKSHA_HOME + '/msearch5/MsearchV3/pwaClosedSearchUrl',
        OPEN_SEARCH_URL:     config().SHIKSHA_HOME + '/msearch5/MsearchV3/pwaOpenSearchUrl',
        BUCKET_IGN_LIST: ['Question', 'Article', 'Topic'],
        typeUrlMapping : {'stream' : 's[]', 'base_course': 'bc[]', 'substream' : 'sb[]', 'specialization' : 'sp[]'},
        filterCHPMapping : {'stream' : 's[]', 'base_course': 'bc[]', 'substream' : 'sb[]', 'specialization' : 'sp[]','credential':'cr[]', 'delivery_method':'dm[]','education_type':'et[]'},
    });