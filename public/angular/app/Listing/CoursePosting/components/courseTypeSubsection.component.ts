import { Component, OnInit, OnChanges, OnDestroy, Input, Output, EventEmitter } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { HierarchyFormGroup } from '../../../reusables/hierarchyFormGroup';
import { ListingBaseService } from '../../../services/listingbase.service';
import { COURSE_VARIANT, CREDENTIAL, COURSE_LEVEL } from '../classes/CourseConst';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';
import { Observable }     from 'rxjs/Rx';

@Component({
	selector: 'courseTypeSubInfo',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseTypeSubsection.component.html'),
	directives: [REACTIVE_FORM_DIRECTIVES, HierarchyFormGroup]
})

export class CourseTypeSubComponent extends Posting implements OnInit, OnDestroy, OnChanges {

	@Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
	@Input() group: FormGroup;
	@Input() courseStaticData;
	@Input() parentForm: FormGroup;
	@Input() mode;
	@Input() hierarchyTree;
	@Input() hierarchyArray;
	@Input() editData;

	processingEdit = false;
	credentialOptions = [];
	courseLevelOptions = [];
	baseCourseOptions = [];
	showPrimary = true;
	subscribeArray = {};
	selectedHierarchies;
	CVCONST = COURSE_VARIANT;
	CCONST = CREDENTIAL;
	CLCONST = COURSE_LEVEL;

	constructor(public listingBaseService: ListingBaseService) {
		super();
	}

	ngOnInit() {
		if (this.group.controls['type'].value == 'exit') {
			this.showPrimary = false;
		}
		this.populateCredentialOptions(this.parentForm.controls['course_variant'].value);

		let o1 = this.group.controls['credential'].valueChanges;
		let o2 = this.group.controls['hierarchyMapping'].valueChanges;
		let o3 = this.group.controls['course_level'].valueChanges;

		this.subscribeArray['base_course'] = o1.merge(o2).merge(o3).debounceTime(200).subscribe(()=>{this.populateBaseCourseOptions()});

		this.subscribeArray['credential'] = this.group.controls['credential'].valueChanges.subscribe((credential) => {
			this.populateCourseLevelOption(credential);
			// this.populateBaseCourseOptions();
		});

		this.subscribeArray['course_level'] = this.group.controls['course_level'].valueChanges.subscribe((data) => {
			if (this.group.controls['type'].value == 'entry') {
				if (!this.processingEdit && (this.parentForm.controls['course_variant'].value == this.CVCONST.double)) {
					this.updateExitCourseLevel();
				}
			}
			// this.populateBaseCourseOptions();
		});
	}

	ngOnChanges(changes) {
		for (let propName in changes) {
			if (propName == 'editData' && changes[propName]['currentValue']) {
				this.fillFormGroupData();
			}
		}
	}

	populateCredentialOptions(variant) {
		this.credentialOptions = [];
		if (variant == this.CVCONST.none) {
			setTimeout(() => { (<FormControl>this.group.controls['credential']).updateValue(this.CCONST.none, { emitEvent: true }); }, 0);
		}
		else {
			setTimeout(() => { (<FormControl>this.group.controls['credential']).updateValue('', { emitEvent: true }); }, 0);
			let dependencies = this.courseStaticData['attribute_dependencies'];
			this.courseStaticData['credential'].forEach((val) => {
				if (dependencies[val['value']] && dependencies[val['value']]['parent_value_id'].indexOf(variant) != -1) {
					this.credentialOptions.push(val);
				}
			});
		}
	}

	populateCourseLevelOption(credential) {
		this.courseLevelOptions = [];
		if (credential) {
			if ((credential == this.CCONST.none) || (credential == this.CCONST.certificate)) {
				(<FormControl>this.group.controls['course_level']).updateValue(this.CLCONST.none, { emitEvent: true });
			}
			else {
				let currentValue = this.group.controls['course_level'].value;
				let dependencies = this.courseStaticData['attribute_dependencies']; let courseLevelOptions = [];
				this.courseStaticData['course_level'].forEach((val) => {
					if (this.group.controls['type'].value) {
						if (dependencies[val['value']] && dependencies[val['value']]['parent_value_id'].indexOf(credential) != -1) {
							courseLevelOptions.push(val);
						}
					}
				});
				if (this.group.controls['type'].value == 'exit') {
					let value = (<FormGroup>(<FormArray>this.parentForm.controls['mapping_info']).controls[0]).controls['course_level'].value;
					let currentIndex;
					courseLevelOptions.forEach((val, index) => {
						if (val['value'] == value) {
							currentIndex = index;
						}
					});
					courseLevelOptions.forEach((val, index) => {
						if (index >= currentIndex) {
							this.courseLevelOptions.push(val);
						}
					});
				}
				else {
					this.courseLevelOptions = courseLevelOptions;
				}
				if (currentValue) {
					let found = false;
					this.courseLevelOptions.forEach((val) => {
						if (val['value'] == currentValue) {
							found = true;
						}
					});
					if (!found) {
						(<FormControl>this.group.controls['course_level']).updateValue('', { emitEvent: true });
					}
				}
			}
		}
		else {
			(<FormControl>this.group.controls['course_level']).updateValue('', { emitEvent: true });
		}
	}

	updateExitCourseLevel() {
		let group = (<FormGroup>(<FormArray>this.parentForm.controls['mapping_info']).controls[1]);
		let currentValue = group.controls['credential'].value;
		setTimeout(() => { (<FormControl>group.controls['credential']).updateValue(currentValue, { emitEvent: true }); }, 0);
	}

	populateBaseCourseOptions() {
		let hierarchies = this.group.controls['hierarchyMapping'].value.filter((val)=>{if(val['streamId']){return true;}});
		if (hierarchies.length > 0) {
			this.listingBaseService.getBasecoursesByHirarchyWithFilter(hierarchies, this.group.controls['course_level'].value, this.group.controls['credential'].value).subscribe((data) => {
				this.baseCourseOptions = data;
				let found = false;
				this.baseCourseOptions.forEach((val) => {
					if (val['value'] == this.group.controls['courseMapping'].value) {
						found = true;
					}
				});
				if (!this.baseCourseOptions.length || !found) {
					if(this.group.controls['courseMapping'].value){
						(<FormControl>this.group.controls['courseMapping']).updateValue('', { emitEvent: true });
					}
				}
			});
		}
		else {
			if(this.group.controls['courseMapping'].value){
				(<FormControl>this.group.controls['courseMapping']).updateValue('', { emitEvent: true });
			}
			this.baseCourseOptions = [];
		}
	}

	fillFormGroupData() {
		this.processingEdit = true;
		for (let name in this.editData) {
			switch (name) {
				case 'credential':
				case 'course_level':
					setTimeout(() => { this.processingEdit = true; }, 500);
				case 'courseMapping':
					super.fillFormGroup(this.editData[name], this.group.controls[name]);
					break;
				case 'hierarchyMapping':
					this.selectedHierarchies = this.editData[name];
					break;
			}
		}
		setTimeout(() => { this.processingEdit = false; }, 500);
	}

	ngOnDestroy() {
		this.emptyFormArray(<FormArray>this.group.controls['hierarchyMapping']);
		for (let name in this.subscribeArray) {
			this.subscribeArray[name].unsubscribe();
		}
	}
}