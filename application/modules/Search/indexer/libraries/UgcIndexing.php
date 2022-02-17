<?php 

class UgcIndexing {
    
    public function __construct(){
        $this->_CI = & get_instance();
        
        $this->_CI->load->model("indexer/NationalIndexingModel");
        $this->nationalIndexingModel = new NationalIndexingModel();

        $this->_CI->load->library('indexer/SolrServerLib');
        $this->solrServerLib = new SolrServerLib;

        $this->_CI->load->builder('listingBase/ListingBaseBuilder');
        $listingBase = new ListingBaseBuilder();
        $this->hierarchyRepo = $listingBase->getHierarchyRepository();
        $this->baseCourseRepo = $listingBase->getBaseCourseRepository();

        $this->_CI->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $this->instituteRepository = $instituteBuilder->getInstituteRepository();

        $this->_CI->load->library('examPages/ExamMainLib');
        $this->examMainLib = new ExamMainLib;

        $this->_CI->load->builder("Careers/CareerBuilder");
        $careerBuilder = new CareerBuilder();
        $this->careerRepository = $careerBuilder->getCareerRepository();
        
        $this->logFileName = 'log_data_ugc_indexing_solr_'.date('y-m-d');
        $this->logFilePath = '/tmp/'.$this->logFileName;
    }

    public function indexQuestions($questionId) {
        $batchSize = 100;
        
        $time_start = microtime_float(); $start_memory = memory_get_usage();

        $questionData = $this->nationalIndexingModel->getAllQuestionsToIndex($questionId);
        error_log("Fetched data from db. \n", 3, $this->logFilePath);

        $questionDataChunks = array_chunk($questionData, $batchSize);

        $removed = 0; $indexed = 0;
        foreach ($questionDataChunks as $round => $questionData) {
            //sleep(1);
            $time_start_1 = microtime_float(); $start_memory_1 = memory_get_usage();

            $questionIds = array();
            foreach ($questionData as $key => $value) {
                $questionIds[] = $value['id'];
            }
            $questionTags = $this->nationalIndexingModel->getTagsForQuestion($questionIds);
            
            //index this batch
            $indexData = array();
            foreach ($questionData as $key => $value) {
                if(empty($value['qualityScore'])) {
                    $value['qualityScore'] = 0;
                }

                //not indexing unanswered closed questions
                // if($value['answerCount'] == 0 && $value['status'] == 'closed') {
                //     continue;
                // }

                $value['synonyms'] = $questionTags[$value['id']];
                $value['url'] = getSeoUrl($value['id'], 'question', $value['text'], array('withoutDomain'=>1));
                $value['moderated'] = 1;
                
                $indexData[$key] = $this->getFormattedDocumentData('question', $value);

                $indexed++;
                // error_log("\tIndexing Question - ".$value['id']." ".$value['url']."\n", 3, $this->logFilePath);
                // error_log("Section: Indexing Question Data - ".print_r($indexData[$key], true)."\n", 3, $this->logFilePath);
            }

            //_p($indexData); //die;
            $indexResponse = $this->solrServerLib->indexFinalData($indexData);
            if($indexResponse[0] == 1) {
                $status = 'Success';
            } else {
                $status = 'Failed';
            }
            
            error_log("Section: Question indexing round - ".$round.". Status - ".$status." | ".getLogTimeMemStr($time_start_1, $start_memory_1)."\n", 3, $this->logFilePath);
        }
        error_log("Section: Cron ended, questions indexed: ".$indexed." at ".date("Y-m-d h:i:sa")." | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, $this->logFilePath);
        
        _p($status);
        return $indexResponse;
    }

    public function indexArticles($articleId) {
        $batchSize = 500;

        $time_start = microtime_float(); $start_memory = memory_get_usage();

        $articleData = $this->nationalIndexingModel->getArticlesData($articleId);
        error_log("Fetched data from db. \n", 3, $this->logFilePath);

        if(empty($articleData)) {
            _p('Data not found');
            return;
        }

        $articleDataChunks = array_chunk($articleData, $batchSize);
        //_p($articleDataChunks); die;

        foreach ($articleDataChunks as $round => $articleData) {
            //sleep(1);
            $time_start_1 = microtime_float(); $start_memory_1 = memory_get_usage();

            $articleIds = array();
            foreach ($articleData as $key => $value) {
                $articleIds[] = $value['id'];
            }
            $articleSynonyms = $this->getSynonymsForArticle($articleIds);
            
            //index this batch
            $indexData = array();
            foreach ($articleData as $key => $value) {
                //_p($value);
                $value['synonyms'] = $articleSynonyms[$value['id']];
                $value['moderated'] = 1;
                $value['qualityScore'] = 1;
                
                $indexData[$key] = $this->getFormattedDocumentData('article', $value);

                $indexed++;
                // error_log("\tIndexing Article - ".$value['id']." ".$value['url']."\n", 3, $this->logFilePath);
                // error_log("Indexing Article Data - ".print_r($indexData[$key], true)."\n", 3, $this->logFilePath);
            }

            //_p($indexData); //die;
            $indexResponse = $this->solrServerLib->indexFinalData($indexData);
            if($indexResponse[0] == 1) {
                $status = 'Success';
            } else {
                $status = 'Failed';
            }
            
            error_log("Article indexing round - ".$round.". Status - ".$status." | ".getLogTimeMemStr($time_start_1, $start_memory_1)."\n", 3, $this->logFilePath);
        }
        error_log("Section: Cron ended, articles indexed: ".$indexed." at ".date("Y-m-d h:i:sa")." | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, $this->logFilePath);
        
        _p($status);
        return $indexResponse;
    }

    private function getSynonymsForArticle($articleIds) {
        if(!is_array($articleIds)) {
            $articleIds = array($articleIds);
        }

        $articleEntities = $this->nationalIndexingModel->getEntitiesMappedToArticle($articleIds);

        foreach ($articleEntities as $key => $value) {
            $entitiesMap[$value['entityType']][$value['entityId']] = $value['entityId'];
            $articleEntitiesMap[$value['entityType']][$value['entityId']][$value['articleId']] = $value['articleId'];
        }
        
        $articleSynonyms = array();
        foreach ($entitiesMap as $entityType => $ids) {
            switch ($entityType) {
                case 'primaryHierarchy':
                    $baseHierarchies = $this->hierarchyRepo->getBaseEntitiesByHierarchyId($ids, 1, 'array');
                    
                    foreach ($baseHierarchies as $hierarchyId => $value) {
                        $articleIds = $articleEntitiesMap[$entityType][$hierarchyId];

                        foreach ($articleIds as $key => $articleId) {
                            if(!empty($value['stream'])) {
                                $name = $value['stream']['name'];
                                if(!in_array($name, $articleSynonyms[$articleId])) {
                                    $articleSynonyms[$articleId][] = $name;
                                }
                            }
                            if(!empty($value['substream'])) {
                                $name = $value['substream']['name'];
                                if(!in_array($name, $articleSynonyms[$articleId])) {
                                    $articleSynonyms[$articleId][] = $name;
                                }
                            }
                            if(!empty($value['specialization'])) {
                                $name = $value['specialization']['name'];
                                if(!in_array($name, $articleSynonyms[$articleId])) {
                                    $articleSynonyms[$articleId][] = $name;
                                }
                            }
                        }
                    }
                    break;
                
                case 'course':
                    $baseCourses = $this->baseCourseRepo->findMultiple($ids);

                    foreach ($baseCourses as $baseCourseId => $baseCourseObj) {
                        $articleIds = $articleEntitiesMap[$entityType][$baseCourseId];

                        foreach ($articleIds as $key => $articleId) {
                            $name = $baseCourseObj->getName();
                            if(!in_array($name, $articleSynonyms[$articleId])) {
                                $articleSynonyms[$articleId][] = $name;
                            }
                        }
                    }
                    break;
                
                case 'exam':
                    $exams = $this->examMainLib->getExamDetailsByIds($ids);
                    
                    foreach ($exams as $examId => $examDetails) {
                        $articleIds = $articleEntitiesMap[$entityType][$examId];

                        foreach ($articleIds as $key => $articleId) {
                            $name = $examDetails['examName'];
                            if(!in_array($name, $articleSynonyms[$articleId])) {
                                $articleSynonyms[$articleId][] = $name;
                            }
                        }
                    }
                    break;
                
                case 'university':
                case 'college':
                    $instituteObjects = $this->instituteRepository->findMultiple(array_keys($ids), array('basic'));
                    
                    foreach ($instituteObjects as $instituteId => $instituteObj) {
                        $articleIds = $articleEntitiesMap[$entityType][$instituteId];

                        foreach ($articleIds as $key => $articleId) {
                            $name = $instituteObj->getName();
                            if(!in_array($name, $articleSynonyms[$articleId])) {
                                $articleSynonyms[$articleId][] = $name;
                            }
                        }
                    }
                    break;
                
                case 'career':
                    $careerObjects = $this->careerRepository->findMultiple($ids);
                    
                    foreach ($careerObjects as $careerId => $careerObj) {
                        $articleIds = $articleEntitiesMap[$entityType][$careerId];

                        foreach ($articleIds as $key => $articleId) {
                            $name = $careerObj->getName();
                            if(!in_array($name, $articleSynonyms[$articleId])) {
                                $articleSynonyms[$articleId][] = $name;
                            }
                        }
                    }
                    break;
                
                case 'tag':
                    $tags = $this->nationalIndexingModel->getTagNameById(array_keys($ids));
                    
                    foreach ($tags as $key => $value) {
                        $articleIds = $articleEntitiesMap[$entityType][$value['tagId']];
                        
                        foreach ($articleIds as $key => $articleId) {
                            if(!in_array($value['tagName'], $articleSynonyms[$articleId])) {
                                $articleSynonyms[$articleId][] = $value['tagName'];
                            }
                        }
                    }
                    break;
                
                default:
                    # code...
                    break;
            }
        }
        
        return $articleSynonyms;
    }

    /*
     * To be used to index - question, discussion, articles, reviews
     */
    private function getFormattedDocumentData($entityType, $data) {
        if(empty($data)) {
            return;
        }

        $documentData = array();

        $documentData['nl_entity_type'] = $entityType;
        $documentData['nl_entity_id']   = $data['id'];
        $documentData['nl_entity_name'] = $data['text'];
        if(!empty($data['synonyms'])) {
            $documentData['nl_entity_synonyms'] = $data['synonyms'];
        }
        $documentData['nl_entity_moderated'] = $data['moderated'];
        $documentData['nl_entity_status'] = $data['status'];

        switch ($entityType) {
            case 'question':
                $documentData['nl_entity_answer_count'] = $data['answerCount'];
                break;

            case 'article':
                if(!empty($data['stream_id'])) {
                    $documentData['nl_entity_stream'][] = $data['stream_id'];
                }
                if(!empty($data['substream_id'])) {
                    $documentData['nl_entity_substream'][] = $data['substream_id'];
                }
                if(!empty($data['specialization_id'])) {
                    $documentData['nl_entity_specialization'][] = $data['specialization_id'];
                }
                break;
            
            default:
                # code...
                break;
        }
        $documentData['nl_entity_quality_factor'] = $data['qualityScore'];
        $documentData['nl_entity_quality_name_id_type_map'] = $data['qualityScore']."::".$documentData['nl_entity_name']."::".$documentData['nl_entity_id']."::".$documentData['nl_entity_type'];
        $documentData['nl_entity_url'] = $data['url'];
        $documentData['nl_entity_creation_date'] = solrDateFormater($data['creationDate']);
        if(!empty($data['lastModifiedDate'])) {
            $documentData['nl_entity_last_modified_date'] = solrDateFormater($data['lastModifiedDate']);
        }
        $documentData['facetype'] = 'ugc';
        $documentData['unique_id'] = $documentData['nl_entity_type'].'_'.$documentData['nl_entity_id'];

        return $documentData;
    }
} ?>