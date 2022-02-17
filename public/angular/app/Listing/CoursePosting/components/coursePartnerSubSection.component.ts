import { Component, OnInit, Input, OnChanges } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, FormArray, Validators } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';
import { ShikshaInstituteAutosuggestorComponent } from '../../ListingReusables/components/shikshaInstituteAutosuggestor.component';
import { MY_VALIDATORS, My_Custom_Validators } from '../../../reusables/Validators';
import { COURSE_VARIANT } from '../classes/CourseConst';

@Component({
	selector: 'coursePartnerSubSection',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/' + getHtmlVersion('coursePartnerSubSection.template.html'),
	directives: [REACTIVE_FORM_DIRECTIVES, ShikshaInstituteAutosuggestorComponent, MY_VALIDATORS]
})
export class CoursePartnerSubSectionComponent extends Posting implements OnInit, OnChanges {

	@Input('group') coursePartnerForm: FormGroup;
	@Input() courseStaticData;
	@Input() controlArrayName;
	@Input() mainForm;
	@Input() caption;
	@Input() editData;
	initialized = false;
	invalidIdError;


	scope = "domestic";
	duration_value;
	duration_unit = 'years';
	activateSuggestor = true;
	autosuggestorSelected: boolean = false;
	maxCollegeAddition = 5;
	collegeAdditionCount = 0;
	CVCONST = COURSE_VARIANT;
	processingEdit = false;

	ngOnInit() {
		this.mainForm.controls['courseBasicInfoForm'].controls['duration_value'].valueChanges.subscribe((data) => {
			this.coursePartnerForm.updateValueAndValidity();
		});
		this.mainForm.controls['courseBasicInfoForm'].controls['duration_unit'].valueChanges.subscribe((data) => {
			this.coursePartnerForm.updateValueAndValidity();
		});
	}

	ngOnChanges(changes) {
		for (let propName in changes) {
			if (propName == 'courseStaticData' && changes[propName]['currentValue']) {
				setTimeout(() => { this.generateCoursePartnerControls(); this.initialized = true; }, 10);
			}
			if (propName == 'editData' && changes[propName]['currentValue']) {
				this.fillFormGroupData(this.editData);
			}
		}
	}

	generateCoursePartnerControls() {
		if (this.controlArrayName != 'partnerInstituteFormArrExit') {
			this.addPartnerInstitute();
			this.updateDefaultPartner();
		}

		this.mainForm.controls['hierarchyForm'].controls['parent_course_hierarchy_name'].valueChanges.subscribe((data) => {
			if (this.coursePartnerForm.contains(this.controlArrayName) && (<FormArray>this.coursePartnerForm.controls[this.controlArrayName]).controls.length > 0) {
				this.updateDefaultPartner();
			} else {
				(<FormControl>this.coursePartnerForm.controls['course_partner_institute_flag']).updateValue('0');
				setTimeout(() => { (<FormControl>this.coursePartnerForm.controls['course_partner_institute_flag']).updateValue('1'); }, 10);
			}
		});
	}

	addPartnerInstitute() {
		let partner_id = '', scope = 'domestic', partner_name = '', partner_type = '';
		if (this.selectedSuggestion) {
			if (this.selectedSuggestion.selectedValue.id) {
				partner_id = this.selectedSuggestion.selectedValue.id;
			}
			
			if (this.selectedSuggestion.selectedValue.type) {
				partner_type = this.selectedSuggestion.selectedValue.type;
			}

			if (this.scope) {
				scope = this.scope;
				if(scope == 'studyAbroad'){
					partner_type = 'university';
				}
			}

			if (this.selectedSuggestion.selectedValue.name) {
				partner_name = this.selectedSuggestion.selectedValue.name;
			}
		}


		this.collegeAdditionCount++;
		(<FormArray>this.coursePartnerForm.controls[this.controlArrayName]).push(new FormGroup({
			'duration_value': new FormControl('', My_Custom_Validators.validatePartnerFormDuration),
			'duration_unit': new FormControl('years'),
			'partner_id': new FormControl(partner_id),
			'partner_type': new FormControl(partner_type),
			'scope': new FormControl(scope),
			'partner_name': new FormControl(partner_name)

		}));
		this.activateSuggestor = false;
		this.autosuggestorSelected = false;
		setTimeout(() => { this.activateSuggestor = true; }, 0);
	}

	updateDefaultPartner() {
		if (this.controlArrayName == 'partnerInstituteFormArrExit') {
			return false;
		}
		if(!this.processingEdit){
			this.coursePartnerForm.markAsDirty();
		}
		let partnerFormGroupArray = (<FormGroup>(<FormArray>this.coursePartnerForm.controls[this.controlArrayName]).controls[0]);
		(<FormControl>partnerFormGroupArray.controls['partner_name']).updateValue(this.mainForm.controls['hierarchyForm'].controls['parent_course_hierarchy_name'].value);
		(<FormControl>partnerFormGroupArray.controls['scope']).updateValue('domestic');
		let data = this.mainForm.controls['hierarchyForm'].controls['parent_course_hierarchy'].value.split('_');
		(<FormControl>partnerFormGroupArray.controls['partner_id']).updateValue(data[1]);
		(<FormControl>partnerFormGroupArray.controls['partner_type']).updateValue(data[0]);
	}

	selectedSuggestion;
	handleSuggestionChange(suggestionData, b) {
		this.selectedSuggestion = {};
		if (typeof suggestionData['selectedValue']['error'] != 'undefined') {
			this.invalidIdError = suggestionData['selectedValue']['error'];
			console.log(this.invalidIdError);
			this.autosuggestorSelected = false;
		}
		else{
			this.invalidIdError = null;
			if (suggestionData.selectedValue.id > 0) {
				this.selectedSuggestion = suggestionData;
				this.autosuggestorSelected = true;
				this.addPartnerInstitute();
			}
			else {
				this.autosuggestorSelected = false;
			}
		}
	}

	resetSuggestionData() {
		this.autosuggestorSelected = false;
	}

	removePartnerInstitute(index) {
		this.collegeAdditionCount--;
		(<FormArray>this.coursePartnerForm.controls[this.controlArrayName]).removeAt(index);
		this.coursePartnerForm.markAsDirty();
	}

	fillFormGroupData(editData) {
		this.processingEdit = true;
		editData.forEach((value, index) => {
			if (index == 0 && this.controlArrayName == 'partnerInstituteFormArr') {
				setTimeout(() => {
					(<FormControl>(<FormGroup>(<FormArray>this.coursePartnerForm.controls[this.controlArrayName]).controls[index]).controls['duration_value']).updateValue(+value['duration_value']);
					(<FormControl>(<FormGroup>(<FormArray>this.coursePartnerForm.controls[this.controlArrayName]).controls[index]).controls['duration_unit']).updateValue(value['duration_unit']);
				}, 100);
			}
			else {
				this.collegeAdditionCount++;
				setTimeout(() => {
					(<FormArray>this.coursePartnerForm.controls[this.controlArrayName]).push(new FormGroup({
						'duration_value': new FormControl(+value['duration_value'], My_Custom_Validators.validatePartnerFormDuration),
						'duration_unit': new FormControl(value['duration_unit']),
						'partner_id': new FormControl(value['partner_id']),
						'partner_type': new FormControl(value['partner_type']),
						'scope': new FormControl(value['scope']),
						'partner_name': new FormControl(value['partner_name'])
					}));
				}, 100);
			}
		});
		setTimeout(()=>{this.processingEdit = false;},1000);
	}

}