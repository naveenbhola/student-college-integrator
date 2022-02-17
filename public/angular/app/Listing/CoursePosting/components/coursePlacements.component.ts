import { Component, OnChanges, Input, Output, EventEmitter, OnDestroy, OnInit } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { UploadMedia } from '../../../reusables/uploadMedia';
import { CoursePostingService } from '../services/coursePosting.service';
import { My_Custom_Validators } from '../../../reusables/Validators';
import { ListingBaseService } from '../../../services/listingbase.service';
import { SortArrayPipe } from '../../../pipes/arraypipes.pipe';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector: 'coursePlacements',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/' + getHtmlVersion('coursePlacements.template.html'),
	directives: [REACTIVE_FORM_DIRECTIVES, UploadMedia],
	pipes: [SortArrayPipe]
})
export class CoursePlacementsComponent extends Posting implements OnInit, OnChanges, OnDestroy {

	@Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
	@Input('group') coursePlacements: FormGroup;
	@Input() courseStaticData;
	@Input() courseTypeForm: FormGroup;
	@Input() hierarchyTree;
	@Input() editData;
	showLoader = 0;


	yearArr = [];
	initialized = false;
	showPlacementReport = false;
	leftSelectedValues = [];
	rightSelectedValues = [];
	rightBox = [];
	leftBox = [];
	rcData = [];
	recruitingCompanies;
	showRCWidget = false;
	courseOptions = [];
	subscribeArray = {};
	processingEdit = false;
	constructor(
        private coursePosting: CoursePostingService,
        public listingBaseService: ListingBaseService
    ) {
		super();
    }

    ngOnInit() {
		this.opened = false;
		this.generateFormControls();
		this.subscribeArray['valueChange'] = (<FormArray>(<FormGroup>this.coursePlacements.root).controls['hierarchyForm']).controls['primary_course_hierarchy'].valueChanges
			.distinctUntilChanged()
			.subscribe((res) => {
				if (res) {
					let instituteString = res.split('_');
					let instituteId = instituteString[1];
					this.subscribeArray['companies'] = this.coursePosting.getRecruitingCompaniesMappedToInstitute(instituteId).subscribe(data => {
						if((<FormArray>this.coursePlacements.controls['recruitingCompanies']).length > 0){
							if(!this.processingEdit){
								this.coursePlacements.markAsDirty();
							}
						}
						if (data.length == 0) {
							console.log('Recruiting Companies not exist in institute');
							this.showRCWidget = false;
							this.emptyFormArray(<FormArray>this.coursePlacements.controls['recruitingCompanies']);
						}
						else{
							this.showRCWidget = true;
						}
						this.recruitingCompanies = data;
						this.updateRCData(data);
					});
				}
			});
		this.subscribeArray['courseOptions1'] = (<FormGroup>(<FormGroup>this.coursePlacements.root).controls['courseTypeForm']).controls['mapping_info'].valueChanges.map((val) => 'hemanth').debounceTime(500).subscribe((data) => { this.populateCourseOptions(); });
		this.subscribeArray['courseOptions2'] = (<FormGroup>(<FormGroup>this.coursePlacements.root).controls['courseBasicInfoForm']).controls['course_name'].valueChanges.debounceTime(500).subscribe((data) => {
			this.populateCourseOptions();
		});
		let date = new Date();
		let year = date.getFullYear();
		this.yearArr.push(year - 1);
		this.yearArr.push(year);
		this.yearArr.push(year + 1);

		// this.populateCourseOptions();
		this.initialized = true;
	}

	ngOnChanges(changes) {
		for (let propName in changes) {
			if (propName == 'editData' && changes[propName]['currentValue']) {
				this.opened = typeof this.editData != 'undefined' && ((typeof this.editData['batch'] != 'undefined' || typeof this.editData['course'] != 'undefined') || (typeof this.editData['recruitingCompanies'] != 'undefined'));
				this.fillFormGroupData();
			}
		}
	}

	fillFormGroupData() {
		this.processingEdit = true;
		for (let i in this.editData) {
			switch (i) {
				case "recruitingCompanies":
					for (let item in this.editData[i]) {
						this.leftSelectedValues.push(this.editData[i][item]);
					}
					setTimeout(() => { this.addItem() }, 2000);
					break;
				default:
					super.fillFormGroup(this.editData[i], this.coursePlacements.controls[i]);
					break;
			}
		}
		setTimeout(() => { setTimeout(()=>{this.processingEdit = false;},4000); }, 500);
		// setTimeout(()=>{this.processingEdit = false;},2200);
	}

	uploadPlacementCallback(res) {
		if (res == 'start') {
            this.showLoader = 1;
        }
		if (typeof res === "string") {
			if (res == 'no file') {
                this.clearBrochureData();
            } else if (res != '') {
				(<FormControl>this.coursePlacements.controls['report_url']).updateValue(res);
				this.coursePlacements.markAsDirty();
			}
			if(res !== 'start'){
				this.showLoader = 0;
			}
		}
		else {
			(<FormControl>this.coursePlacements.controls['report_url']).updateValue(null);
			this.showLoader = 0;
		}
	}

	populateCourseOptions() {
		this.courseOptions = [];

		if (this.hierarchyTree && this.courseStaticData) {
			let currentValue = this.coursePlacements.controls['course'].value;

			let options = {};
			let group = (<FormGroup>(<FormArray>(<FormGroup>(<FormGroup>this.coursePlacements.root).controls['courseTypeForm']).controls['mapping_info']).controls[0]);
			if (group && group.controls['hierarchyMapping'] && (<FormArray>group.controls['hierarchyMapping']).length > 0) {
				(<FormArray>group.controls['hierarchyMapping']).controls.forEach((hierarchy) => {
					for (let control in (<FormGroup>hierarchy).controls) {
						if ((<FormGroup>hierarchy).controls[control].valid) {
							let value = (<FormGroup>hierarchy).controls[control].value;
							if (control == 'streamId') {
								options['streamId' + value] = { 'value': 'streamId_' + value, 'streamId': value, 'type': 'streamId' };
							}
							else if (control == 'substreamId' && value != 'none') {
								options['substreamId' + value] = { 'value': 'substreamId_' + value, 'type': 'substreamId', 'streamId': (<FormGroup>hierarchy).controls['streamId'].value, 'substreamId': value };
							}
							else if (control == 'specializationId' && value != 'none') {
								options['specializationId' + value] = { 'value': 'specializationId_' + value, 'type': 'specializationId', 'streamId': (<FormGroup>hierarchy).controls['streamId'].value, 'substreamId': (<FormGroup>hierarchy).controls['substreamId'].value, 'specializationId': value };
							}
						}
					}
					this.courseOptions = Object.values(options);
					this.populateLabelsForCourseOptions();
				});
				
				let courseName = (<FormGroup>(<FormGroup>this.coursePlacements.root).controls['courseBasicInfoForm']).controls['course_name'].value;
				if (courseName) {
					this.courseOptions.push({ 'label': courseName, 'value': 'clientCourse' });
				}

				let value = group.controls['courseMapping'].value;
				if (value) {
					this.subscribeArray['baseCourse'] = this.listingBaseService.getBasecourse(value).subscribe((data) => {
						this.courseOptions.push({ 'label': data['name'], 'value': 'baseCourse_' + value });
						this.subscribeArray['baseCourse'].unsubscribe();

						let found = false;
						this.courseOptions.forEach((val, index) => {
							if (currentValue && val['value'] == currentValue) {
								found = true;
							}
						});
						if (!found && !this.processingEdit) {
							(<FormControl>this.coursePlacements.controls['course']).updateValue('', { emitEvent: true });
						}
					});
				}
				else{
					let found = false;
					this.courseOptions.forEach((val, index) => {
						if (currentValue && val['value'] == currentValue) {
							found = true;
						}
					});
					if (!found  && !this.processingEdit) {
						(<FormControl>this.coursePlacements.controls['course']).updateValue('', { emitEvent: true });
					}
				}
			}
		}
	}

	populateLabelsForCourseOptions() {
		if (this.hierarchyTree) {
			this.courseOptions.forEach((val, index) => {
				let key = this.hierarchyTree[val['streamId']];

				if (val['type'] == 'substreamId') {
					key = key['substreams'][val['substreamId']];
				}
				else if (val['type'] == 'specializationId') {
					if (val['substreamId'] == 'none') {
						key = key['specializations'][val['specializationId']];
					}
					else {
						key = key['substreams'][val['substreamId']]['specializations'][val['specializationId']];
					}
				}
				this.courseOptions[index]['label'] = key['name'];
			});
		}
	}

	generateFormControls() {
		this.coursePlacements.addControl('batch', new FormControl(''));
		this.coursePlacements.addControl('course', new FormControl(''));
		this.coursePlacements.addControl('batch_percentage', new FormControl('', My_Custom_Validators.validateNumber(0, 100)));
		this.coursePlacements.addControl('batch_unit', new FormControl('1'));
		this.coursePlacements.addControl('batch_min_salary', new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
		this.coursePlacements.addControl('batch_median_salary', new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
		this.coursePlacements.addControl('batch_average_salary', new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
		this.coursePlacements.addControl('batch_max_salary', new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
		this.coursePlacements.addControl('international_offers', new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
		this.coursePlacements.addControl('max_salary', new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
		this.coursePlacements.addControl('max_salary_unit', new FormControl('1'));
		this.coursePlacements.addControl('report_url', new FormControl());
		this.coursePlacements.addControl('recruitingCompanies', new FormArray([]));
		this.coursePlacements.setValidators([
			My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': ['batch', 'course'], 'myoptional': [] }),
			My_Custom_Validators.ValidateMinMaxInFormGroup({ 'min_control': 'batch_min_salary', 'max_control': 'batch_max_salary', 'allowequal': true }),
			My_Custom_Validators.ValidateMinMaxInFormGroup({ 'min_control': 'batch_min_salary', 'max_control': 'batch_median_salary', 'allowequal': true }),
			My_Custom_Validators.ValidateMinMaxInFormGroup({ 'min_control': 'batch_min_salary', 'max_control': 'batch_average_salary', 'allowequal': true }),
			My_Custom_Validators.ValidateMinMaxInFormGroup({ 'min_control': 'batch_median_salary', 'max_control': 'batch_max_salary', 'allowequal': true }),
			My_Custom_Validators.ValidateMinMaxInFormGroup({ 'min_control': 'batch_average_salary', 'max_control': 'batch_max_salary', 'allowequal': true }),
		]);
	}

	clearBrochureData() {
		this.coursePlacements.markAsDirty();
        (<FormControl>this.coursePlacements.controls['report_url']).updateValue(null);
        this.showPlacementReport = true;

        setTimeout(() => {
			this.showPlacementReport = false;
        }, 100);

    }

    changeLeft(options) {
		this.leftSelectedValues = Array.apply(null, options)
			.filter(option => option.selected)
			.map(option => option.value);
	}

	changeRight(options) {
		this.rightSelectedValues = Array.apply(null, options)
			.filter(option => option.selected)
			.map(option => option.value);
	}

	updateRCData(data) {
		this.leftBox = [];
		this.rightBox = [];
		this.rcData = [];

		for (let cp in data) {
			let temp = [];
			temp['company_name'] = data[cp]['company_name'];
			temp['company_id'] = cp;
			this.rcData.push(cp);
			this.leftBox.push(temp);
		}

	}


    addItem() {
		for (let item of this.leftSelectedValues) {
			let temp = [];
			temp['company_name'] = this.recruitingCompanies[item]['company_name'];
			temp['company_id'] = item;
			this.rightBox.push(temp);
			if(!this.processingEdit){
				this.coursePlacements.markAsDirty();
			}
			(<FormArray>this.coursePlacements.controls['recruitingCompanies']).push(new FormControl(item));


			for (let x in this.leftBox) {
				if (this.leftBox[x]['company_id'] == item) {
					this.leftBox.splice(+x, 1);
				}
			}
		}

		this.leftSelectedValues = [];
    }

    removeItem() {
		for (let item of this.rightSelectedValues) {
			let temp = [];
			temp['company_name'] = this.recruitingCompanies[item]['company_name'];
			temp['company_id'] = item;
			this.leftBox.push(temp);


			let index;
			for (let x in (<FormArray>this.coursePlacements.controls['recruitingCompanies']).controls) {
				if ((<FormArray>this.coursePlacements.controls['recruitingCompanies']).controls[x].value == item) {
					index = x;
				}
			}
			if (index) {
				(<FormArray>this.coursePlacements.controls['recruitingCompanies']).removeAt(index);
				this.coursePlacements.markAsDirty();
			}

			for (let x in this.rightBox) {
				if (this.rightBox[x]['company_id'] == item) {
					this.rightBox.splice(+x, 1);
				}
			}
		}
		this.rightSelectedValues = [];
	}

	ngOnDestroy() {
		for (let name in this.subscribeArray) {
			this.subscribeArray[name].unsubscribe();
		}
	}
}
