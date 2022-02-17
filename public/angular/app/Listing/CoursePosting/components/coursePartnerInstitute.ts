import { Component, OnInit, OnChanges, Input, Output, EventEmitter } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, FormArray, Validators, ValidatorFn } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { ShikshaInstituteAutosuggestorComponent } from '../../ListingReusables/components/shikshaInstituteAutosuggestor.component';
import { MY_VALIDATORS } from '../../../reusables/Validators';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';
import { COURSE_VARIANT } from '../classes/CourseConst';
import { CoursePartnerSubSectionComponent } from './coursePartnerSubSection.component'

@Component({
	selector: 'course-partner-institute',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/' + getHtmlVersion('coursePartnerInstitute.html'),
	directives: [REACTIVE_FORM_DIRECTIVES, ShikshaInstituteAutosuggestorComponent, MY_VALIDATORS, CoursePartnerSubSectionComponent]
})
export class CoursePartnerInstitute extends Posting implements OnInit, OnChanges {

	@Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
	@Input() set courseStaticData(val) {
		if (val) {
			this.staticData = val;
		}
	}

	@Input('group') public coursePartnerForm: FormGroup;

	@Input() mainForm;
	@Input() editData;

	staticData;
	reset = false;
	CVCONST = COURSE_VARIANT;
	scope = "domestic";
	duration_value;
	duration_unit = 'years';
	activateSuggestor = true;
	autosuggestorSelected: boolean = false;


	ngOnInit() {
		this.opened = false;
		this.coursePartnerForm.addControl('course_partner_institute_flag', new FormControl('0'));
		this.coursePartnerForm.controls['course_partner_institute_flag'].valueChanges.subscribe((res) => {
			this.emptyPartnerForm();
			if (res == '1') {
				this.addControlArrayBasedOnVarient();
			}
		});

		this.mainForm.controls['courseTypeForm'].controls['course_variant'].valueChanges.subscribe((data) => {
			if (+this.coursePartnerForm.controls['course_partner_institute_flag'].value) {
				(<FormControl>this.coursePartnerForm.controls['course_partner_institute_flag']).updateValue('1', { emitEvent: true });
			}
		});
	}

	emptyPartnerForm() {
		if (this.coursePartnerForm.contains('partnerInstituteFormArr')) {
			this.coursePartnerForm.removeControl('partnerInstituteFormArr');
		}
		if (this.coursePartnerForm.contains('partnerInstituteFormArrExit')) {
			this.coursePartnerForm.removeControl('partnerInstituteFormArrExit');
		}
	}

	ngOnChanges(changes) {
		for (let propName in changes) {
			if (propName == 'editData' && changes[propName]['currentValue']) {
				this.fillFormGroupData();
				this.opened = this.editData['course_partner_institute_flag'] == 1;
			}
		}
	}

	addControlArrayBasedOnVarient() {
		this.reset = true;
		if (this.mainForm.controls['courseTypeForm'].controls['course_variant'].value == this.CVCONST.single || this.mainForm.controls['courseTypeForm'].controls['course_variant'].value == this.CVCONST.none) {
			this.coursePartnerForm.addControl("partnerInstituteFormArr", new FormArray([]));
		} else {
			this.coursePartnerForm.addControl("partnerInstituteFormArr", new FormArray([]));
			this.coursePartnerForm.addControl("partnerInstituteFormArrExit", new FormArray([]));
		}
		setTimeout(()=>{this.reset = false;},0);
	}

	resetSuggestionData() {
		this.autosuggestorSelected = false;
	}

	removePartnerInstitute(index) {
		(<FormArray>this.coursePartnerForm.controls['partnerInstituteFormArr']).removeAt(index);
		this.coursePartnerForm.markAsDirty();
	}

	fillFormGroupData() {
		if (this.editData['course_partner_institute_flag']) {
			(<FormControl>this.coursePartnerForm.controls['course_partner_institute_flag']).updateValue(this.editData['course_partner_institute_flag']);
		}
		setTimeout(()=>{this.editData = null;},3000);
	}
}