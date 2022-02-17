<aside>
    <div class="aside col-lg-3 pL0">
        <!-- load subcategory filter -->
        <?php if(!empty($filters['subCat'])) {
            $this->load->view('nationalV2/filters/subcategory',array('data'=>$filters['subCat']));
        }else if($request->getTwoStepClosedSearch() == true){
           $this->load->view('nationalV2/filters/backToOpenSearch',array('data'=>$filters['subCat']));
        } ?>

        <!-- load other filters -->
        <div class="left_nav">
            <label class="nav_main_head">Filter your search </label>
            <ul class="menu">
                <?php foreach ($filters as $filterType => $filter) {
                    switch ($filterType) {
                        case 'exams':
                            $this->load->view('nationalV2/filters/exams',array('data'=>$filters['exams']));
                            break;
                        case 'locations':
                            $this->load->view('nationalV2/filters/locations',array('data'=>$filters['locations']));
                            break;
                        case 'specialization':
                            $this->load->view('nationalV2/filters/specializations',array('data'=>$filters['specialization']));
                            break;
                        case 'fees':
                            if($filters['fees']){
                                $this->load->view('nationalV2/filters/fees',array('data'=>$filters['fees']));                                
                            }
                            break;
                        case 'courseLevel':
                            $this->load->view('nationalV2/filters/courseLevel',array('data'=>$filters['courseLevel']));
                            break;
                        case 'mode':
                            $this->load->view('nationalV2/filters/mode',array('data'=>$filters['mode']));
                            break;
                        case 'degreePref':
                            $this->load->view('nationalV2/filters/degreePref',array('data'=>$filters['degreePref']));
                            break;
                        case 'affiliation':
                            $this->load->view('nationalV2/filters/affiliation',array('data'=>$filters['affiliation']));
                            break;
                        case 'facilities':
                            $this->load->view('nationalV2/filters/facilities',array('data'=>$filters['facilities']));
							break;
                        case 'classTimings':
                            $this->load->view('nationalV2/filters/classTimings',array('data'=>$filters['classTimings']));
                            break;
                        default:
                            # code...
                            break;
                    }
                }?>
            </ul>
        </div>
    </div>
</aside>