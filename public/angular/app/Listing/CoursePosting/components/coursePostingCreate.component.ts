import { Component, OnInit, ViewChild, OnDestroy, AfterViewInit, OnChanges } from '@angular/core';
import { SidebarService } from '../../../services/sidebar-service';
import { Router, ActivatedRoute, ROUTER_DIRECTIVES } from '@angular/router';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormArray, FormControl, Validator, ValidatorFn } from '@angular/forms';
import { ListingBaseService } from '../../../services/listingbase.service';
import { COURSE_POSTING_CHILD_DIRECTIVES } from './coursePostingAutoloader';
import { CoursePostingService } from '../services/coursePosting.service';
import { CourseDependencyService } from '../services/course-dependencies.service';
import { InstituteLocationService } from '../../../services/institute-locations.service';
import {MODAL_DIRECTIVES, BS_VIEW_PROVIDERS} from 'ng2-bootstrap/ng2-bootstrap';
import { Posting } from '../../../Common/Classes/Posting';
import { Observable }     from 'rxjs/Rx';
import { COURSE_SECTIONS } from '../classes/CourseConst';
import {ModalDirective} from '../../../Common/Components/modal/modal.component';
import {UserService} from '../../../Common/services/userservice';
import {toolTipComponent} from '../../../reusables/toolTip.component';
import {toolTipCourses} from '../classes/toolTipCourses';
import {CanComponentDeactivate} from '../services/coursePostingGuards';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';
import { My_Custom_Validators } from '../../../reusables/Validators';

@Component({
    selector: 'coursePostingCreate',
    templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('coursePostingCreate.component.html'),
    directives: [COURSE_POSTING_CHILD_DIRECTIVES, REACTIVE_FORM_DIRECTIVES, MODAL_DIRECTIVES, ROUTER_DIRECTIVES, toolTipComponent],
    providers: [InstituteLocationService, BS_VIEW_PROVIDERS, toolTipCourses]
})

export class CoursePostingCreateComponent extends Posting implements OnInit, OnDestroy, AfterViewInit,CanComponentDeactivate {
    courseStaticData;
    submitPending = false;
    courseForm: FormGroup;
    cloneForm: FormGroup = new FormGroup({});
    courseId = 0;
    courseData: any;
    courseDataCopy: any;
    subscribeArray = {};
    course_type = 1;
    mode = 'add';
    extraData = [];
    hierarchyTree;
    hierarchyArray;
    isCloneMode = false;
    submitSuccess = false;
    userId;

    isModalOpened: boolean = false;
    toolTipMsg: string = "";
    tooltipPositionX: any = 0;
    tooltipPositionY: any = 0;
    checkedFlag: boolean = true;
    sections = COURSE_SECTIONS;

    constructor(
        public route: ActivatedRoute,
        public router: Router,
        public sidebarService: SidebarService,
        public coursePosting: CoursePostingService,
        public dependencyService: CourseDependencyService,
        public listingBaseService: ListingBaseService,
        private toolTip: toolTipCourses,
        public userService: UserService
    ) {
        super();
        this.hierarchyTree = this.route.snapshot.data['hierarchyTree'];
        this.hierarchyArray = this.route.snapshot.data['hierarchies'];
        this.courseStaticData = this.route.snapshot.data['staticData']['static_data'];

        let id = +this.route.snapshot.params['id'];
        if (id) {
            this.mode = 'edit';
        }

        let cloneData = this.route.snapshot.data['cloneData'];
        if(cloneData && typeof cloneData == 'object'){
            this.isCloneMode = true;
        }

        this.userService.getUserData().subscribe((data) => {
            if(data){
                this.userId = data['userId'];
            }
        });
    }

    ngOnInit() {
        this.sidebarService.updateLinks({ 'activeLink': 'coursePosting', 'subLink': 'create' });
        this.generateFormControls();
    }

    generateFormControls() {
        this.courseForm = new FormGroup({
            'courseId': new FormControl(this.courseId),
            'course_type': new FormControl(this.course_type),
            'isDisabled': new FormControl(),
            'packType' : new FormControl('7'),
            'clientId' : new FormControl(this.userId),
            'subscriptionId' : new FormControl('0'),
            hierarchyForm: new FormGroup({}),
            scheduleForm: new FormGroup({}),
            courseBasicInfoForm: new FormGroup({}),
            courseTypeForm: new FormGroup({}),
            coursePartnerForm: new FormGroup({}),
            courseFeesForm: new FormGroup({}),
            courseEligibilityForm: new FormGroup({}),
            courseAdmissionProcess: new FormGroup({}),
            courseSeats: new FormGroup({}),
            courseBatchProfile: new FormGroup({}),
            courseNotableAlumni: new FormGroup({}),
            courseStudentExchange: new FormGroup({}),
            courseExamCutOff: new FormArray([]),
            course12thCutOff: new FormArray([]),
            courseUsp: new FormGroup({}),
            coursePlacements: new FormGroup({}),
            courseInternship: new FormGroup({}),
            courseBrochure: new FormGroup({}),
            courseStructureForm: new FormGroup({}),
            importantDatesForm: new FormGroup({}),
            courseLocations: new FormGroup({}),
            courseMedia: new FormGroup({}),
            courseSeo: new FormGroup({}),
            courseComments: new FormGroup({}),
        });

        this.courseForm.controls['coursePartnerForm'].setValidators(My_Custom_Validators.validatePartnerForm(this.courseForm.controls['courseBasicInfoForm'], this.courseForm.controls['coursePartnerForm']));
        this.courseForm.controls['courseFeesForm'].setValidators(My_Custom_Validators.validateCourseFeesForm(this.courseForm.controls['coursePartnerForm']));
        // this.courseForm.controls['courseExamCutOff'].setValidators(My_Custom_Validators.validateCourseExamForm(this.courseForm));
    }

    ngAfterViewInit() {
        if (this.mode == 'edit') {
            let data = this.route.snapshot.data['courseData'];
            if (data['status'] == 'success') {
                if (data['course_data'] == 'NO_SUCH_LISTING_FOUND_IN_DB') {
                    alert('No such course exists'); this.backToHome();
                }
                else {
                    this.extraData = data['course_data']['extraData'];
                    delete data['course_data']['extraData'];

                    this.courseData = data['course_data'];
                    this.courseDataCopy = JSON.parse(JSON.stringify(this.courseData));
                    this.courseId = +this.route.snapshot.params['id']; this.fillFormGroupData(this.courseData, this.courseForm);
                }
            }
            else {
                alert(data['message']); this.backToHome();
            }
        }

        if (this.mode == 'add') {
            let cloneData = this.route.snapshot.data['cloneData'];
            if(cloneData && typeof cloneData == 'object'){
                this.extraData = cloneData['extraData'];
                delete cloneData['extraData'];

                this.courseData = cloneData;
                this.courseDataCopy = JSON.parse(JSON.stringify(this.courseData));
                this.fillFormGroupData(this.courseData, this.courseForm);
                this.dependencyService.cloneSections([], null);
            }
        }
    }

    fillFormGroupData(groupData, form) {
        for (let key in groupData) {
            switch (key) {
                case 'courseTypeForm':
                case 'courseEligibilityForm':
                case 'courseStructureForm':
                case 'importantDatesForm':
                case 'courseLocations':
                case 'courseMedia':
                case 'coursePlacements':
                case 'courseAdmissionProcess':
                case 'courseUsp':
                case 'courseSeats':
                case 'course12thCutOff':
                case 'coursePartnerForm':
                case 'courseExamCutOff':
                case 'courseFeesForm':
                case 'coursePartnerForm':
                    break;
                default:
                    if (typeof groupData[key] == 'object' && groupData[key] != null) {
                        if (Array === groupData[key].constructor) {
                            form.controls[key].updateValue(groupData[key], { emitEvent: true });
                        }
                        else {
                            this.fillFormGroupData(groupData[key], form.controls[key]);
                        }
                    }
                    else {
                        // console.log(key);
                        form.controls[key].updateValue(groupData[key], { emitEvent: true });
                        // console.log(key);
                    }
                    break;
            }
        }
    }

    getUpdatedFields() {
        let fields = {};
        let extraData = { 'sectionsToIndex': {}, 'extraData': {} }; let group;
        for (let control in this.courseForm.controls) {
            if (this.courseForm.controls[control].dirty) {
                switch (control) {
                    case 'courseComments':
                        break;
                    case 'hierarchyForm':
                        let hierarchyForm = (<FormGroup>this.courseForm.controls[control]);
                        let parentValue = hierarchyForm.controls['parent_course_hierarchy'].value;
                        let primaryValue = hierarchyForm.controls['primary_course_hierarchy'].value;
                        if (this.courseDataCopy.hierarchyForm.parent_course_hierarchy != parentValue) {
                            extraData['sectionsToIndex']['basicSection'] = 1;
                        }
                        if (this.courseDataCopy.hierarchyForm.primary_course_hierarchy != primaryValue) {
                            extraData['sectionsToIndex']['hierarchySection'] = 1;
                            extraData['extraData']['hierarchySection'] = { 'oldId': this.courseDataCopy.hierarchyForm.primary_course_hierarchy };
                        }
                        fields[control] = 1;
                        break;

                    case 'scheduleForm':
                        extraData['sectionsToIndex']['basicSection'] = 1;
                        fields[control] = 1;
                        break;

                    case 'courseBasicInfoForm':
                        for (let subcontrol in (<FormGroup>this.courseForm.controls[control]).controls) {
                            if ((<FormGroup>this.courseForm.controls[control]).controls[subcontrol].dirty) {
                                switch (subcontrol) {
                                    case 'affiliated_university_scope':
                                    case 'affiliated_university_id':
                                    case 'affiliated_university_name':
                                        extraData['sectionsToIndex']['affiliationSection'] = 1;
                                        break;
                                    default:
                                        extraData['sectionsToIndex']['basicSection'] = 1;
                                        break;
                                }
                            }
                        }
                        fields[control] = 1;
                        break;

                    case 'courseTypeForm':
                        let otherTagsChanged = false; let controlsChanged = false;
                        group = (<FormGroup>this.courseForm.controls[control]);
                        for (let subcontrol in group.controls) {
                            if (group.controls[subcontrol].dirty) {
                                switch (subcontrol) {
                                    case 'course_tags':
                                        otherTagsChanged = true;
                                        break;
                                    default:
                                        controlsChanged = true;
                                        break;
                                }
                            }
                        }
                        extraData['sectionsToIndex']['courseTypeSection'] = 1;
                        if (otherTagsChanged && !controlsChanged) {
                            extraData['extraData']['courseTypeSection'] = { 'courseType': 1 };
                        }
                        fields[control] = 1;
                        break;

                    case 'courseFeesForm':
                        extraData['sectionsToIndex']['feesSection'] = 1;
                        fields[control] = 1;
                        break;

                    case 'courseEligibilityForm':
                        group = (<FormGroup>this.courseForm.controls[control]);
                        for (let subcontrol in group.controls) {
                            if (group.controls[subcontrol].dirty && subcontrol == 'exams_accepted') {
                                extraData['sectionsToIndex']['eligibleExamsSection'] = 1;
                            }
                        }
                        fields[control] = 1;
                        break;

                    case 'courseLocations':
                        extraData['sectionsToIndex']['locationSection'] = 1;
                        group = (<FormGroup>this.courseForm.controls[control]).controls['locations'];
                        for (let subcontrol of group.controls) {
                            if (subcontrol.controls['fees'].dirty) {
                                extraData['sectionsToIndex']['feesSection'] = 1;
                            }
                        }
                        fields[control] = 1;
                        break;

                    default:
                        fields[control] = 1;
                        break;
                }
            }
        }
        let data = { 'sections': Object.keys(fields), 'extraData': extraData };
        if(data['sections'].length > 0 && Object.keys(data['extraData']['sectionsToIndex']).length == 0){
            data['extraData']['sectionsToIndex']['lastModify'] = 1;
        }
        return data;
    }

    submitCourse(saveAs: 'draft' | 'live') {
        if (this.courseForm.dirty) {
            if (this.courseForm.valid) {
                this.submitPending = true;
                let data = {};
                data['formData'] = this.courseForm.value;
                if (this.mode == 'edit') {
                    data['sectionsChanged'] = this.getUpdatedFields();
                }
                data['saveAs'] = saveAs;

                this.subscribeArray['saveCourse'] = this.coursePosting.saveCourseData(data).subscribe(
                    data => {
                        if (data.status == 'success') {
                            this.courseId = data['course_id'];
                            (<FormControl>this.courseForm.controls['courseId']).updateValue(data['course_id'], { emitEvent: true });
                            this.showCourseSavedModal();
                            this.submitSuccess = true;
                            this.submitPending = false;
                        }
                        else {
                            alert(data['message']);
                            this.submitPending = false;
                        }
                    },
                    error => { alert('Internal error'); this.backToHome(); }
                );
            }
        }
    }

    backToHome() {
        this.router.navigate(['/nationalCourse/CoursePosting/viewList']);
    }

    @ViewChild('courseSavedModal') public courseSavedModal: ModalDirective;
    @ViewChild('courseCloneModal') public courseCloneModal: ModalDirective;
    showCourseSavedModal() {
        this.courseSavedModal.show();
    }

    hideCourseSavedModal() {
        this.courseSavedModal.hide();
    }

    navigateToAdd() {
        this.hideCourseSavedModal();
        setTimeout(() => {
            this.router.navigate(['/nationalCourse/CoursePosting/createTemp']);
        }, 500);
        //this.backToHome();
    }

    navigateToList() {
        this.hideCourseSavedModal();
        setTimeout(() => {
            this.router.navigate(['/nationalCourse/CoursePosting/viewList']);
        }, 500);
        //this.backToHome();
    }

    showCloneModal() {
        this.sections.forEach((item) => {
            this.cloneForm.addControl(item.value, new FormControl(false));
        });
        this.hideCourseSavedModal();
        this.courseCloneModal.show();
    }

    hideCourseCloneModal() {
        this.courseCloneModal.hide();
    }

    cloneCourse() {
        this.hideCourseCloneModal();
        let sections = ['hierarchyForm', 'courseTypeForm'];
        for (let control in this.cloneForm.controls) {
            if (this.cloneForm.controls[control].value) {
                sections.push(control);
            }
        }
        if (sections.length > 0) {
            this.dependencyService.cloneSections(sections, this.courseForm.controls['courseId'].value);
        }
        if(this.mode == 'add'){
            this.router.navigate(['/nationalCourse/CoursePosting/createTemp']);
        }
        else if(this.mode == 'edit'){
            this.router.navigate(['/nationalCourse/CoursePosting/create']);
        }
    }

    toolTipModalShow(val: any) {
        this.isModalOpened = true;
        this.toolTipMsg = this.toolTip.toolTipArray[val['val']];
        // console.log(this.toolTipMsg);
        this.tooltipPositionX = val['x'];
        this.tooltipPositionY = val['y'];
        this.changeDetected();
    }
    changeDetected() {
        this.checkedFlag = false;
        setTimeout(() => { this.checkedFlag = true; }, 20);
    }

    canDeactivate(){
        if(this.courseForm.dirty && !this.submitSuccess){
            let temp = window.confirm('Are you sure you want to leave this page');
            if(temp){
                return this.coursePosting.releaseLockOnCourse(this.courseId).map((data)=>{return true;});
            }
            else{
                return Observable.of(false);
            }
        }
        else{
            return this.coursePosting.releaseLockOnCourse(this.courseId).map((data)=>{return true;});
        }
    }

    ngOnDestroy() {
        for (let name in this.subscribeArray) {
            this.subscribeArray[name].unsubscribe();
        }
    }

    get errors(){
        let errors = [];
        this.getErrors(this.courseForm,errors);
        return errors;
    }

    getErrors(formControl,errors){
        if(formControl instanceof FormControl){
            if(formControl.errors){
                errors.push(formControl.errors);
                // return errors;
            }
        }
        else if(formControl instanceof FormGroup){
            for(let control in formControl.controls){
                this.getErrors(formControl.controls[control],errors);
            }
        }
        else if(formControl instanceof FormArray){
            for(let control of formControl.controls){
                this.getErrors(control,errors);
            }
        }
        return errors;
    }
}