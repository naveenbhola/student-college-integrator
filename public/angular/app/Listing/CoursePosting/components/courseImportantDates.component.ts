import { Component, OnInit, Input, Output, EventEmitter, OnChanges, OnDestroy } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { My_Custom_Validators } from '../../../reusables/Validators';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector: 'courseImportantDates',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseImportantDates.template.html'),
	directives: [REACTIVE_FORM_DIRECTIVES]
})

export class CourseImportantDates extends Posting implements OnChanges, OnInit {

	@Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
	@Input('group') importantDatesForm: FormGroup;
	@Input() mode;
	@Input() editData;

	initialized = false;
	months = [{ 'label': 'Jan', 'value': '1' }, { 'label': 'Feb', 'value': '2' }, { 'label': 'Mar', 'value': '3' }, { 'label': 'Apr', 'value': '4' }, { 'label': 'May', 'value': '5' }, { 'label': 'Jun', 'value': '6' }, { 'label': 'Jul', 'value': '7' }, { 'label': 'Aug', 'value': '8' }, { 'label': 'Sept', 'value': '9' }, { 'label': 'Oct', 'value': '10' }, { 'label': 'Nov', 'value': '11' }, { 'label': 'Dec', 'value': '12' }];
	dates = [];
	years = [];

	ngOnInit() {
		this.opened = false;
		let i = 1;
		while (i <= 31) {
			this.dates.push(i);
			i++;
		}
		let date = new Date();
		let year = date.getFullYear();
		this.years.push(year - 1);
		this.years.push(year);
		this.years.push(year + 1);
	}

	ngOnChanges(changes) {
		for (let propName in changes) {
			if (propName == 'importantDatesForm' && changes[propName]['currentValue']) {
				this.generateFormControls();
				this.initialized = true;
			}

			if (propName == 'editData' && changes[propName]['currentValue']) {
				this.fillFormGroupData();
				this.opened = (typeof this.editData != 'undefined' && typeof this.editData['events'] != 'undefined' && this.editData['events'].length > 0);
			}
		}
	}

	generateFormControls() {
		this.importantDatesForm.addControl('events', new FormArray([]));
		let group = (<FormArray>this.importantDatesForm.controls['events']);

		let labelsStart = ['Application Start Date', 'Application Submit Date', 'Course Commencement Date', 'Results Date'];

		labelsStart.forEach((val, index) => {
			let formgroup = new FormGroup({ 'start': this.getDateGroup(val, 'start'), 'end': this.getDateGroup('', 'end') });
			formgroup.setValidators(My_Custom_Validators.ValidateMinMaxInFormGroup({ 'type': 'date', 'mingroup': 'start', 'maxgroup': 'end' }));
			group.push(formgroup);
		});
	}

	getDateGroup(label, type) {
		let group = new FormGroup({
			'date': new FormControl(''),
			'month': new FormControl(''),
			'year': new FormControl(''),
			'type': new FormControl(type),
			'label': new FormControl(label, My_Custom_Validators.validateMaxLength(40))
		});
		let mymust = ['year','month'];
		if (type == 'others') {
			mymust.push('label');
		}
		let validators = [My_Custom_Validators.ValidateOneOrNoneFormGroup({ 'mymust': mymust, 'myoptional': ['date'] })];
		setTimeout(() => { group.setValidators(validators); }, 0);
		return group;
	}

	addOthers() {
		let group = new FormGroup({ 'start': this.getDateGroup('', 'others'), 'end': this.getDateGroup('', 'end') });
		group.setValidators(My_Custom_Validators.ValidateMinMaxInFormGroup({ 'type': 'date', 'mingroup': 'start', 'maxgroup': 'end' }));
		(<FormArray>this.importantDatesForm.controls['events']).push(group);
	}

	removeOthers(index) {
		(<FormArray>this.importantDatesForm.controls['events']).removeAt(index);
		this.importantDatesForm.markAsDirty();
	}

	fillFormGroupData() {
		this.editData['events'].forEach((val, i) => {
			let found = false;
			(<FormArray>this.importantDatesForm.controls['events']).controls.forEach((event, index) => {
				if ((<FormGroup>(<FormGroup>event).controls['start']).controls['label'].value == val['start']['label']) {
					super.fillFormGroup(val, event);
					found = true;
				}
			});
			if (!found) {
				let length = (<FormArray>this.importantDatesForm.controls['events']).length;
				this.addOthers();
				super.fillFormGroup(val, (<FormArray>this.importantDatesForm.controls['events']).controls[length]);
			}
		});
	}
}