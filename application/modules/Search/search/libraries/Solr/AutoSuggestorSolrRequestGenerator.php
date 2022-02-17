<?php
/*
 * Author - Nikita Jain
 * Purpose - Solr queries used in autosuggestor
 */
require_once (APPPATH.'modules/Search/search/libraries/Solr/SolrRequestGenerator.php');
define("EXAM_SEARCH_SUGGESTIONS_COUNT",8);

class AutoSuggestorSolrRequestGenerator extends SolrRequestGenerator {
    private $filters;

	function __construct() {
        parent::__construct();
    }

    public function generateUrlToGetAllLocations($solrRequestData) {
        $urlComponents = array();
        $urlComponents[] = 'q=*:*';
        $urlComponents[] = 'wt=phps';

        //Filters to be applied
        $urlComponents[] = 'fq=facetype:course';
        
        //Facets
        $urlComponents[] = 'facet=true';
        $urlComponents[] = 'facet.mincount=1';
        $urlComponents[] = 'facet.limit=-1';
        
        $urlComponents[] = 'rows=0';

        //city
        $prefixQuery = "{!ex=floc%20key=".$this->FIELD_ALIAS['city']."}";
        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_city_name_id_map';

        //state
        $prefixQuery = "{!ex=floc%20key=".$this->FIELD_ALIAS['state']."}";
        $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_state_name_id_map';

        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
        return $solrUrl;
    }

    public function generateInsttAutoSuggestionUrl($solrRequestData) {
        $urlComponents = array();
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'defType=edismax';

        $solrRequestData['text'] = htmlentities(strip_tags($solrRequestData['text']));
        $urlComponents[] = 'q="'.urlencode($solrRequestData['text']).'"';
        
        $urlComponents[] = 'fq=facetype:course';
        $urlFqComponents = $this->getFieldQueryComponents($solrRequestData);
        $urlComponents = array_merge($urlComponents, $urlFqComponents);
        
        $urlComponents[] = 'qf=nl_institute_name_edgeNGram+'.
                            'nl_institute_name_keywordEdgeNGram^50+'.
                            'nl_institute_name_autosuggest^30+'.
                            'nl_institute_synonyms_autosuggest^1000+'.
                            'nl_institute_synonyms_keywordEdgeNGram^100+'.
                            'nl_institute_synonyms_spkeywordEdgeNGram^100';

        //aliasing fl fields
        $fieldsToFetch = array('nl_institute_name', 'nl_institute_id', 'nl_institute_type');
        foreach($fieldsToFetch as $field) {
            $fieldsToFetchWithAlias[] = $this->FIELD_ALIAS[$field].":".$field;
        }
        
        $urlComponents[] = 'fl='.implode(',',$fieldsToFetchWithAlias);
        
        $urlComponents[] = 'group=true';
        $urlComponents[] = 'group.main=true';
        $urlComponents[] = 'group.field=nl_institute_id';

        $urlComponents[] = 'bq=nl_institute_id:24642^=100000'; //hack to rank Delhi University higher(when it comes in suggestions)

        if($solrRequestData['suggestionsFor'] == 'reviews') {
            $urlComponents[] = 'stats=true';
            $urlComponents[] = 'stats.field=nl_course_review_count';
            $urlComponents[] = 'stats.facet=nl_insttId_courseId';
            //$urlComponents[] = 'stats.facet=nl_institute_id';
        }

        switch ($solrRequestData['orderBy']) {
            case 'relevance':
                $urlComponents[] = 'sort=score%20DESC,nl_institute_view_count_year%20DESC';
                break;

            case 'institute_view_count':
                $urlComponents[] = 'sort=nl_institute_view_count_year%20desc';
                break;
        }

        $urlComponents[] = 'rows='.$solrRequestData['maxResultCount'];
        
        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
        
        return $solrUrl;
    }

    public function getExamSearchSuggestionUrl($solrRequestData){
        $urlComponents = array();
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'defType=edismax';

        $solrRequestData['text'] = htmlentities(strip_tags($solrRequestData['text']));
        $urlComponents[] = 'q="'.urlencode($solrRequestData['text']).'"';
        
        $urlComponents[] = 'fq=facetype:autosuggestor';
        $examTypes = array('exam','allexam');
        if(isMobileRequest()) {
            array_pop($examTypes);
        }
        $urlComponents[] = 'fq=nl_entity_type:('.implode('%20OR%20',$examTypes).')';

        $urlComponents[] = 'qf=nl_entity_name_edgeNGram^2+'.
                            'nl_entity_name_keywordEdgeNGram^4+'.
                            'nl_entity_name_en_keywordEdgeNGram^4+'.
                            'nl_entity_name_autosuggest^3+'.
                            // 'nl_entity_name_en_edgeNGram+'.
                            'nl_entity_synonyms_autosuggest^1.2+'.
                            'nl_entity_synonyms_keywordEdgeNGram^1.2+'.
                            'nl_entity_synonyms_edgeNGram+'.
                            'nl_entity_synonyms_spkeywordEdgeNGram^1.2+';
        $fieldsToFetch = array('nl_entity_count_name_id_type_map','nl_entity_url');
        foreach($fieldsToFetch as $field) {
            $fieldsToFetchWithAlias[] = $this->FIELD_ALIAS[$field].":".$field;
        }
        
        $urlComponents[] = 'fl='.implode(',',$fieldsToFetchWithAlias);
        $urlComponents[] = 'group=true';
        $urlComponents[] = 'group.field=nl_entity_type';
        $urlComponents[] = 'group.limit='.EXAM_SEARCH_SUGGESTIONS_COUNT;

        $urlComponents[] = 'group.sort=nl_entity_result_count%20desc,score%20desc';

        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
        
        return $solrUrl;
    }

    public function getQuestionSuggestionUrl($solrRequestData){
        $urlComponents = array();
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'defType=edismax';

        /*
         * The number of other words permitted between words in query phrase is called “Slop“. 
         * We can use the tilde, “~”, symbol at the end of our Phrase for this. 
         * The lesser the distance between two terms the higher the score will be. 
         * A sloppy phrase query specifies a maximum “slop”, or the number of positions tokens need to be moved to get a match. 
         * The slop is zero by default, requiring exact matches.
         */
        $solrRequestData['text'] = htmlentities(strip_tags($solrRequestData['text']));
        //$urlComponents[] = 'q='.urlencode($solrRequestData['text']).'+OR+"'.urlencode($solrRequestData['text']).'"^20';
        $urlComponents[] = 'q='.urlencode($solrRequestData['text']);
        
        $urlComponents[] = 'fq=facetype:ugc';
        $urlComponents[] = 'fq=nl_entity_type:question';
        $urlComponents[] = 'fq=nl_entity_moderated:1';
        $urlComponents[] = 'fq=-(nl_entity_answer_count:0)'; //answered questions only

        $urlComponents[] = 'qf=nl_entity_name_edgeNGram+'.
                            'nl_entity_name_keywordEdgeNGram+'.
                            'nl_entity_name_autosuggest+'.
                            'nl_entity_synonyms_autosuggest+'.
                            'nl_entity_synonyms_keywordEdgeNGram+';

        //q with pf, uses positional information of the term that is stored in an index
        $urlComponents[] = 'ps=100';
        $urlComponents[] = 'pf=nl_entity_name_edgeNGram+'.
                            'nl_entity_name_keywordEdgeNGram+'.
                            'nl_entity_name_autosuggest+'.
                            'nl_entity_synonyms_autosuggest+'.
                            'nl_entity_synonyms_keywordEdgeNGram+';

        $fieldsToFetch = array('nl_entity_quality_name_id_type_map','nl_entity_url');
        foreach($fieldsToFetch as $field) {
            $fieldsToFetchWithAlias[] = $this->FIELD_ALIAS[$field].":".$field;
        }
        
        $urlComponents[] = 'fl='.implode(',', $fieldsToFetchWithAlias);
        
        // AND all the tokens given in the query i.e don't fetch the results that don't contain all the tokens given in the query
        // $urlComponents[] = 'q.op=AND';
        // $urlComponents[] = 'tie=0.2';

        //$urlComponents[] = 'bf=nl_entity_quality_factor^3';

        $urlComponents[] = 'sort=score%20desc,nl_entity_quality_factor%20desc'; //sort within group
        //$urlComponents[] = 'sort=nl_entity_quality_factor%20desc,score%20desc'; //sort within group

        $urlComponents[] = 'rows='.$solrRequestData['maxResultCount'];

        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
        
        return $solrUrl;
    }

    public function getQuestionTopicSuggestionUrl($solrRequestData){
        $urlComponents = array();
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'defType=edismax';

        $solrRequestData['text'] = htmlentities(strip_tags($solrRequestData['text']));
        $urlComponents[] = 'q="'.urlencode($solrRequestData['text']).'"';
        
        $urlComponents[] = 'fq=facetype:autosuggestor';
        $urlComponents[] = 'fq=nl_entity_type:question_tag';

        $urlComponents[] = 'qf=nl_entity_name_edgeNGram+'.
                            'nl_entity_name_keywordEdgeNGram+'.
                            'nl_entity_name_en_keywordEdgeNGram+'.
                            'nl_entity_name_autosuggest+'.
                            // 'nl_entity_name_en_edgeNGram+'.
                            'nl_entity_synonyms_autosuggest+'.
                            'nl_entity_synonyms_keywordEdgeNGram+'.
                            'nl_entity_synonyms_spkeywordEdgeNGram';

        $fieldsToFetch = array('nl_entity_quality_name_id_type_map', 'nl_entity_tag_qna_count', 'nl_entity_url');
        foreach($fieldsToFetch as $field) {
            $fieldsToFetchWithAlias[] = $this->FIELD_ALIAS[$field].":".$field;
        }
        
        $urlComponents[] = 'fl='.implode(',',$fieldsToFetchWithAlias);

        $urlComponents[] = 'sort=nl_entity_quality_factor%20desc'; //sort groups wrt each other
        $urlComponents[] = 'rows='.$solrRequestData['maxResultCount'];
        
        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
        
        return $solrUrl;
    }

    public function generateBaseEntitiesAutoSuggestionUrl($solrRequestData) {
        $urlComponents = array();
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'defType=edismax';

        $solrRequestData['text'] = htmlentities(strip_tags($solrRequestData['text']));
        $urlComponents[] = 'q="'.urlencode($solrRequestData['text']).'"';
        
        $urlComponents[] = 'fq=facetype:autosuggestor';
        $urlComponents[] = 'fq=nl_entity_type:('.implode('%20OR%20',array('stream','substream','specialization','base_course','certificate_provider','popular_group')).')';
        $urlComponents[] = 'fq=-nl_entity_result_count:0';
        
        $urlComponents[] = 'qf=nl_entity_name_edgeNGram+'.
                            'nl_entity_name_keywordEdgeNGram+'.
                            'nl_entity_name_en_keywordEdgeNGram+'.
                            'nl_entity_name_en_edgeNGram+'.
                            'nl_entity_synonyms_autosuggest+'.
                            'nl_entity_synonyms_keywordEdgeNGram+'.
                            'nl_entity_synonyms_spkeywordEdgeNGram+';

        //aliasing fl fields
        $fieldsToFetch = array('nl_entity_count_name_id_type_map');
        foreach($fieldsToFetch as $field) {
            $fieldsToFetchWithAlias[] = $this->FIELD_ALIAS[$field].":".$field;
        }
        
        $urlComponents[] = 'fl='.implode(',',$fieldsToFetchWithAlias);

        $urlComponents[] = 'sort=nl_entity_result_count%20desc';

        $urlComponents[] = 'rows='.$solrRequestData['maxResultCount'];
        
        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
        
        return $solrUrl;
    }

    public function generateInsttAdvancedFilterUrl($solrRequestData) {
        $urlComponents = array();
        $urlComponents[] = 'q=*:*';
        $urlComponents[] = 'wt=phps';
        
        //Filters to be applied
        $urlComponents[] = 'fq=facetype:course';
        if(!empty($solrRequestData['filters'])) {
            $urlFqComponents = array();
            $urlFqComponents = $this->getFieldQueryComponents($solrRequestData);
            $urlComponents = array_merge($urlComponents, $urlFqComponents);
        }
        
        //Rows to fetch
        $urlComponents[] = 'rows=10000000';

        //aliasing fl fields
        if($solrRequestData['type'] == 'university') {
            $fieldsToFetch = array('nl_course_name', 'nl_course_id', 'nl_stream_id', 'nl_stream_name', 'nl_course_offered_by');
        } else { //if institute
            $fieldsToFetch = array('nl_course_name', 'nl_course_id', 'nl_stream_id', 'nl_stream_name');
        }
        foreach($fieldsToFetch as $field) {
            $fieldsToFetchWithAlias[] = $this->FIELD_ALIAS[$field].":".$field;
        }
        
        $urlComponents[] = 'fl='.implode(',',$fieldsToFetchWithAlias);

        //Facets
        $urlComponents[] = 'facet=true';
        $urlComponents[] = 'facet.mincount=1';
        $urlComponents[] = 'facet.limit=-1';
        if($solrRequestData['getLocations']) {
            //city
            $prefixQuery = "{!ex=floc%20key=".$this->FIELD_ALIAS['city']."}";
            $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_city_name_id_map';
        }
        
        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
        return $solrUrl;
    }

    public function generateMultipleInsttLocationUrl($solrRequestData) {
        $urlComponents = array();
        $urlComponents[] = 'q=*:*';
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'rows=0';

        //Filters to be applied
        $urlComponents[] = 'fq=facetype:course';
        $urlComponents[] = 'fq=nl_institute_name_exact_string:"'.urlencode($solrRequestData['selectedWordName']).'"';

        //Facets
        $urlComponents[] = 'facet=true';
        $urlComponents[] = 'facet.mincount=1';
        $urlComponents[] = 'facet.limit=-1';
        if($solrRequestData['getLocations']) {
            //city
            $prefixQuery = "{!ex=floc%20key=".$this->FIELD_ALIAS['city']."}";
            $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_city_name_id_map';
        }

        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
        return $solrUrl;
    }

    public function generateEntityAdvancedFilterUrl($solrRequestData) {
        $urlComponents = array();
        $urlComponents[] = 'q=*:*';
        $urlComponents[] = 'wt=phps';

        //Filters to apply
        $urlComponents[] = 'fq=facetype:course';
        $urlFqComponents = $this->getFieldQueryComponents($solrRequestData);
        $urlComponents = array_merge($urlComponents, $urlFqComponents);

        //Facets
        $urlFacetComponents = $this->getFacetComponents($solrRequestData);
        if($solrRequestData['getLocations']) {
            //city
            $prefixQuery = "{!ex=floc%20key=".$this->FIELD_ALIAS['city']."}";
            $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_city_name_id_map';

            //state
            $prefixQuery = "{!ex=floc%20key=".$this->FIELD_ALIAS['state']."}";
            $urlComponents[] = 'facet.field='.$prefixQuery.'nl_course_state_name_id_map';
        }
        $urlComponents = array_merge($urlComponents, $urlFacetComponents);
        
        //Rows to fetch
        $urlComponents[] = 'rows=0';
        
        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
        return $solrUrl;
    }

    public function generateUrlToRecognizeEntity($solrRequestData) {
        $urlComponents = array(); $urlFqComponents = array();
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'defType=edismax';
        $urlComponents[] = 'fq=facetype:course';
        $urlComponents[] = 'q=*:*';

        $keyword = trim($solrRequestData['searchKeyword']);
        
        $urlFqComponents[] = 'institute_synonyms_exact:"'.urlencode($keyword).'"';
        $urlFqComponents[] = 'institute_accronyms_exact:"'.urlencode($keyword).'"';
        $urlComponents[] = 'fq=('.implode('%20OR%20',$urlFqComponents).')';

        //group by institute id
        $urlComponents[] = 'group=true';
        $urlComponents[] = 'group.main=true';
        $urlComponents[] = 'group.field=institute_id';
        $urlComponents[] = 'fl=institute_id';

        $urlComponents[] = 'rows=-1';
        
        $solrUrl = SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
        
        return $solrUrl;
    }

    public function getSpellCheckSuggestions($solrRequestData) {
        $urlComponents = array();
        $urlComponents[] = 'wt=phps';
        
        $keyword = trim($solrRequestData['keyword']);
        $urlComponents[] = 'spellcheck.q='.urlencode($keyword);
        
        $solrUrl = SOLR_SPELL_URL.implode('&',$urlComponents);
        
        return $solrUrl;
    }
}
