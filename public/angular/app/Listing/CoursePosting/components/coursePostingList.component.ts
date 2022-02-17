import { Component, OnInit, ViewChild } from '@angular/core';
import { SidebarService } from '../../../services/sidebar-service';
import { MODAL_DIRECTIVES, BS_VIEW_PROVIDERS } from 'ng2-bootstrap/ng2-bootstrap';
import { ModalDirective } from '../../../Common/Components/modal/modal.component';
import { Router } from '@angular/router';
import { pagintionHTML } from '../../../reusables/pagination.component';
import { paginationService } from '../../../reusables/pagination.service';
import { CoursePostingService } from '../services/coursePosting.service';
import { ShikshaSelectComponent } from '../../../Common/Components/select/shiksha-select-component';
import { Subject } from 'rxjs/Subject';
import { CourseListTable } from './courseListTable';
import { Posting } from '../../../Common/Classes/Posting';
import { InstituteListingService } from '../../../services/institute-listing-search.service';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
    selector: 'coursePostingList',
    templateUrl: '/public/angular/app/Listing/CoursePosting/views/' + getHtmlVersion('coursePostingList.component.html'),
    directives: [ShikshaSelectComponent, MODAL_DIRECTIVES, pagintionHTML, CourseListTable],
    providers: [BS_VIEW_PROVIDERS, paginationService, CoursePostingService, InstituteListingService]
})
export class CoursePostingListComponent extends Posting implements OnInit {
    courseStatusList = ["Live", "Draft", "Disabled"];
    selectCourseType: number = 1;
    public totalResultCount: number = 0;
    public paginationNum: number;
    //number of results to show per every pagination
    public paginationResultsShow: number = 25;
    //using for autosuggestor
    autosuggestorSource;
    autosuggestorObservable;
    public instituteAutoSuggestorItems: Array<Object> = [];
    public instituteResultsTable: Array<Object> = [];
    private instituteAutoSuggestorSuggestedItem: any = {};

    public courseFiltersObj: any = {};
    public courseResultsTable: Array<Object> = [];
    public totalPages: number;
    public paginationLimit: number = 5; //number of pagination 
    public startPage: number = 1;//startPage number in pagination
    public endPage: number;//endPage number in pagination
    public paginationArray: Array<Object> = [];
    public checkedFlag: boolean = true;
    public paginationHTML: any;
    filterUniversityId = "";
    filterInstituteId = "";
    filterCourseId = "";
    selectedStatus = "0";
    resetAutosuggestorData = true;
    showLoader = 0;


    constructor(
        private router: Router,
        public sidebarService: SidebarService,
        public coursePostingService: CoursePostingService,
        private paginationService: paginationService,
        public instituteListingService: InstituteListingService
    ) { super(); }

    resetFilters() {
        this.showLoader = 1;
        this.filterUniversityId = "";
        this.filterInstituteId = "";
        this.filterCourseId = "";
        this.selectedStatus = "0";
        this.courseFiltersObj['institute'] = "";
        this.courseFiltersObj['courseId'] = "";
        this.courseFiltersObj['instituteId'] = "";
        this.courseFiltersObj['openSearch'] = "";
        this.courseFiltersObj['university'] = "";
        this.courseFiltersObj['universityId'] = "";
        this.courseFiltersObj['status'] = "";
        this.resetAutosuggestorData = false;
        setTimeout(() => { this.resetAutosuggestorData = true; }, 0);
        this.populateCoursesBasedOnFilters();
    }

    resetInputFilters(except) {
        if (except == 'course') {
            this.filterUniversityId = "";
            this.filterInstituteId = "";
        } else if (except == 'institute') {
            this.filterUniversityId = "";
            this.filterCourseId = "";
        } else if (except == 'university') {
            this.filterCourseId = "";
            this.filterInstituteId = "";
        }

    }

    ngOnInit() {
        this.sidebarService.updateLinks({ 'activeLink': 'coursePosting', 'subLink': 'list' });
        this.autosuggestorSource = new Subject();
        this.autosuggestorObservable = this.autosuggestorSource.asObservable();
        this.autosuggestorObservable.debounceTime(400).map((data) => { if (typeof data == 'string') { data = data.trim(); } if (data) { return data; } }).subscribe(typedText => { if (typedText) { this.getInstituteParentSuggestion(typedText); } });
        this.populateCoursesBasedOnFilters();
    }
    populateCoursesBasedOnFilters(pageNumber: number = 1) {
        this.courseFiltersObj['pageNumber'] = pageNumber;
        this.coursePostingService.populateCoursesBasedOnFilters(this.courseFiltersObj).subscribe(data => this.fillResultTableResults(data));
    }
    fillResultTableResults(data) {
        this.showLoader = 0;
        this.courseResultsTable = [];
        this.totalResultCount = data['totalCount'] ? data['totalCount'] : 0;
        this.paginationNum = +data['paginationNum'];
        for (let i in data['data']) {
            data['data'][i]['showStatus'] = (data['data'][i]['is_dummy'] == 1 ? 'dummy' : data['data'][i]['status']);
            data['data'][i]['showStatus'] = (data['data'][i]['disabled_url'] ? 'disabled' : data['data'][i]['showStatus']);
            this.courseResultsTable.push(data['data'][i]);
        }
        console.log(this.courseResultsTable);
        this.paginationArray = this.paginationService.paginationLogic(this.totalResultCount, this.paginationResultsShow, this.paginationNum, this.paginationLimit);
        this.totalPages = this.paginationArray['totalPages'];
        this.startPage = this.paginationArray['startPage'];
        this.endPage = this.paginationArray['endPage'];
        this.changeDetected();
    }
    paginationRequest(pageNumber: number) {
        if (pageNumber)
            this.populateCoursesBasedOnFilters(pageNumber);
    }
    //below function is used for refresh content of pagination html on view page
    changeDetected() {
        this.checkedFlag = false;
        setTimeout(() => { this.checkedFlag = true; }, 20);
    }

    newCourse() {
        this.router.navigate(['/nationalCourse/CoursePosting/create']);
    }
    courseListFilters(id: any, typeOfValue: string) {
        this.showLoader = 1;
        if (typeOfValue == 'universityId') {
            this.courseFiltersObj[typeOfValue] = id;
            this.courseFiltersObj['instituteId'] = '';
            this.courseFiltersObj['courseId'] = '';
            this.courseFiltersObj['openSearch'] = '';
            this.courseFiltersObj['institute'] = '';
            this.courseFiltersObj['university'] = '';
        }
        else if (typeOfValue == 'instituteId') {
            this.courseFiltersObj[typeOfValue] = id;
            this.courseFiltersObj['universityId'] = '';
            this.courseFiltersObj['courseId'] = '';
            this.courseFiltersObj['openSearch'] = '';
            this.courseFiltersObj['institute'] = '';
            this.courseFiltersObj['university'] = '';
        }
        else if (typeOfValue == 'courseId') {
            this.courseFiltersObj[typeOfValue] = id;
            this.courseFiltersObj['universityId'] = '';
            this.courseFiltersObj['instituteId'] = '';
            this.courseFiltersObj['openSearch'] = '';
            this.courseFiltersObj['institute'] = '';
            this.courseFiltersObj['university'] = '';
        }
        else
            this.courseFiltersObj[typeOfValue] = id;


        this.populateCoursesBasedOnFilters();
        console.log(id);
        console.log(typeOfValue);
    }

    getInstituteParentSuggestion(typedText) {
        let suggestionType: any;
        this.instituteListingService.getInstituteAutosuggestor(typedText).subscribe(data => this.fillInstituteItems(data));
    }

    fillInstituteItems(data) {
        this.instituteAutoSuggestorItems = [];
        for (let i in data) {
            for (let y in data[i]) {
                this.instituteAutoSuggestorItems.push({ 'text': data[i][y], 'id': i + "_" + y });
            }
        }
    }
    public selected(value: any): void {
        if (typeof value == "string") {
            if(value.trim()){
                this.courseFiltersObj['openSearch'] = value;
                this.courseFiltersObj['instituteId'] = '';
                this.courseFiltersObj['universityId'] = '';
                this.showLoader = 1;
                this.populateCoursesBasedOnFilters();
            }
        }
        else {
            let instituteString = value.id.split("_");
            if (instituteString.length > 0)
                this.courseFiltersObj[instituteString[0]] = instituteString[1];
            if (instituteString[0] == 'institute') {
                this.courseFiltersObj['university'] = '';
            }
            else {
                this.courseFiltersObj['institute'] = '';
            }
            this.courseFiltersObj['openSearch'] = '';
            this.courseFiltersObj['instituteId'] = '';
            this.courseFiltersObj['universityId'] = '';
            this.showLoader = 1;
            this.populateCoursesBasedOnFilters();
        }
    }
    public removed(value: any): void {
        console.log('Removed value is: ', value);
        this.courseFiltersObj['university'] = '';
        this.courseFiltersObj['institute'] = '';
        this.instituteAutoSuggestorItems = [];
    }
    public typed(value: any): void {
        if (!value) {
            setTimeout(() => { this.instituteAutoSuggestorItems = []; }, 0);
        } else {
            this.autosuggestorSource.next(value);
        }
    }
    public refreshValue(value: any): void {
        this.instituteAutoSuggestorSuggestedItem = value;
    }
}