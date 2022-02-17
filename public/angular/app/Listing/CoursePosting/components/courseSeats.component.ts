import { Component, Input, OnInit, AfterViewInit, OnChanges } from '@angular/core';
import { Posting } from '../../../Common/Classes/Posting';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { AddColumnTableComponent } from '../../../reusables/addColumnTableComponent';
import { My_Custom_Validators } from '../../../reusables/Validators';


@Component({
	selector: "courseSeats",
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseSeats.template.html'),
	directives: [REACTIVE_FORM_DIRECTIVES, AddColumnTableComponent]
})

export class CourseSeatsComponent extends Posting implements OnInit, OnChanges {
	@Input() courseStaticData;
	@Input('group') public courseSeats: FormGroup;
	@Input() mainForm;
	@Input() locationData;
	@Input() editData;
  	 _addMore = [];
	_default = [];
	examLookupArr = {};
	_examDefault = [{ 'label': 'Cat', 'type': 'default', 'value': 'cat' }, { 'label': 'Mat', 'type': 'default', 'value': 'mat' }];
	_domicileDefault = [{ 'label': 'Home State', 'type': 'default', 'value': 'home' }, { 'label': 'Related States', 'type': 'default', 'value': 'related_states' }, { 'label': 'Others', 'type': 'default', 'value': 'others' }];
	initialized = false;
	validExam = [];

	ngOnInit() {
		this.opened = false;
		// console.log('in child init');
		for (let i of this.courseStaticData.categories) {
			if (i.type == 'default') {
				this._default.push(i);

				//this.colItemShow.push(i);
			} else {
				this._addMore.push(i);
				//this.addMoreColItem.push(i);
			}
		}

		for (let item of this.courseStaticData['exam_list']) {
			this.examLookupArr[item.value] = item.label;
		}
		this.locationData = this.courseStaticData.locationData;
		this.generateFormControls();
		this.initialized = true;
	}


	ngOnChanges(changes) {
		for (let propName in changes) {
			if (propName == 'editData' && changes[propName]['currentValue']) {
				// console.log('in child changes');
				this.opened = typeof this.editData != 'undefined' && (typeof this.editData['total_seats'] != 'undefined' || typeof this.editData['seats_by_category'] != 'undefined' || typeof this.editData['seats_by_domicile'] != 'undefined' || typeof this.editData['seats_by_entrance_exam'] != 'undefined');
				this.fillFormGroupData();
			}
		}
	}

	// ngAfterViewInit() {
		// console.log('in child view init');
		// if (this.editData) {
		// 	this.opened = typeof this.editData != 'undefined' && (typeof this.editData['total_seats'] != 'undefined' || typeof this.editData['seats_by_category'] != 'undefined' || typeof this.editData['seats_by_domicile'] != 'undefined');
		// 	this.fillFormGroupData();
		// }
	// }

	generateFormControls() {
		this.courseSeats.addControl('total_seats', new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
		this.courseSeats.addControl('seats_by_category', new FormGroup({}));
		this.courseSeats.addControl('seats_by_entrance_exam', new FormGroup({}));
		this.courseSeats.addControl('seats_by_domicile', new FormGroup({}));
		(<FormGroup>this.courseSeats.controls['seats_by_domicile']).addControl('home_state', new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
		(<FormGroup>this.courseSeats.controls['seats_by_domicile']).addControl('related_state', new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
		(<FormGroup>this.courseSeats.controls['seats_by_domicile']).addControl('related_state_list', new FormControl());
		(<FormGroup>this.courseSeats.controls['seats_by_domicile']).addControl('others_state', new FormControl('', My_Custom_Validators.validateWholeNumber(0)));

		(<FormArray>(<FormGroup>this.courseSeats.root).controls['courseEligibilityForm']).controls['exams_accepted'].valueChanges.subscribe((res) => {
			this.validExam = [];
			for (let eligibilityExams of res) {
				if (eligibilityExams['exam_name'] == 'other') {
					this.validExam.push('Others$$' + eligibilityExams['custom_exam']);
				} else {
					this.validExam.push(eligibilityExams['exam_name']);
				}
				if (eligibilityExams['exam_name']) {
					if (!(<FormGroup>this.courseSeats.controls['seats_by_entrance_exam']).contains(eligibilityExams['exam_name'])) {
						if (eligibilityExams['exam_name'] == 'other') {

							(<FormGroup>this.courseSeats.controls['seats_by_entrance_exam']).addControl('Others$$' + eligibilityExams['custom_exam'], new FormControl('', My_Custom_Validators.validateNumber(0)));
						} else {

							(<FormGroup>this.courseSeats.controls['seats_by_entrance_exam']).addControl(eligibilityExams['exam_name'], new FormControl('', My_Custom_Validators.validateNumber(0)));
						}
					}
				}
			}

			for (let examId in (<FormGroup>this.courseSeats.controls['seats_by_entrance_exam']).controls) {
				if (this.validExam.indexOf(examId) == -1) {
					if((<FormGroup>this.courseSeats.controls['seats_by_entrance_exam']).controls[examId].value){
						this.courseSeats.markAsDirty();
					}
					(<FormGroup>this.courseSeats.controls['seats_by_entrance_exam']).removeControl(examId);
				}
			}

		});
	}

	fillFormGroupData() {
		for (let i in this.editData) {
			switch (i) {
				case 'seats_by_category':
					break;
				default:
					super.fillFormGroup(this.editData[i], this.courseSeats.controls[i]);
					break;
			}
		}
	}
}