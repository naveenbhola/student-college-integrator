import { Component, OnInit, OnChanges, OnDestroy, Input, Output, EventEmitter} from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { My_Custom_Validators } from '../../../reusables/Validators';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector: 'course-exams-table-component',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseExamsCutOffTable.html'),
	directives: [REACTIVE_FORM_DIRECTIVES],
	styles: [`
		.table tbody>tr>td{
		    text-align: center;
		}
		.table tbody>tr>td{
			line-height:1em;
		}
	`]
})
export class CourseExamsCutOffTable extends Posting implements OnInit, OnChanges {

	@Input() array: FormArray;
	@Input() default = [];
	@Input() addmore = [];
	@Input() tableHeading;
	@Input() showCrossPosition;
	@Input() totalLength;
	@Input() arrayKeyName;
	@Input() editData;
	@Input() courseExamCutOffIndex;
	@Output() removeClicked = new EventEmitter();
	typeToNameMapping = {
		all_india: "All India",
		home_state: "Home State",
		other_state: "Other State",
		related_states: "Related State",
		cutOff12th: "12th",
		science: "Science",
		commerce: "Commerce",
		humanities: "Humanities",
		pcm: "PCM",
		pmbbt: "PMB/BT",
	}
	colsToShow = [];
	_addmore;
	initialized: boolean = false;

	ngOnInit() {}

	ngOnChanges(changes) {
		for (let propName in changes) {
			if (propName == 'default' && changes[propName]['currentValue']) {
				this.colsToShow = changes[propName]['currentValue'].filter(() => { return true; });
				setTimeout(() => {
					this.colsToShow.forEach((val) => {
						for (let group in this.array.controls) {
							if (this.arrayKeyName == 'course12thCutOff') {
								(<FormGroup>this.array.controls[group]).addControl(val['value'], new FormControl('', [My_Custom_Validators.validateNumber(0),My_Custom_Validators.validateCourseExamTable(this.courseExamCutOffIndex, this.arrayKeyName)]));
							}
							else {
								(<FormGroup>this.array.controls[group]).addControl(val['value'], new FormControl('', My_Custom_Validators.validateCourseExamTable(this.courseExamCutOffIndex, this.arrayKeyName)));
							}
						}
					});

					this.initialized = true;
				}, 10);
			}
			if (propName == 'courseExamCutOffIndex') {
				setTimeout(() => {
					this.colsToShow.forEach((val) => {
						for (let group in this.array.controls) {
							if (this.arrayKeyName == 'course12thCutOff') {
								(<FormGroup>this.array.controls[group]).controls[val['value']].setValidators([My_Custom_Validators.validateNumber(0),My_Custom_Validators.validateCourseExamTable(this.courseExamCutOffIndex, this.arrayKeyName)]);
							}
							else {
								(<FormGroup>this.array.controls[group]).controls[val['value']].setValidators(My_Custom_Validators.validateCourseExamTable(this.courseExamCutOffIndex, this.arrayKeyName));
							}
						}
					});
				}, 100);
			}
			if (propName == 'addmore' && changes[propName]['currentValue']) {
				this._addmore = changes[propName]['currentValue'].filter(() => { return true; });
			}

			if (propName == 'editData' && changes[propName]['currentValue']) {
				if (this.default && this.default.length && this._addmore && this._addmore.length && this.editData && this.editData.length > 0) {
					setTimeout(() => {
						this.fillFormGroupData();
					}, 200);
				}
			}
		}
	}

	addColumn(item) {
		if (item) {
			let removeIndex: number;
			this._addmore.forEach((val, index) => {
				if (val['value'] == item) {
					this.colsToShow.push(val);
					for (let group in this.array.controls) {
						if (this.arrayKeyName == 'course12thCutOff') {
							(<FormGroup>this.array.controls[group]).addControl(val['value'], new FormControl('', [My_Custom_Validators.validateNumber(0),My_Custom_Validators.validateCourseExamTable(this.courseExamCutOffIndex, this.arrayKeyName)]));
						}
						else {
							(<FormGroup>this.array.controls[group]).addControl(val['value'], new FormControl('', My_Custom_Validators.validateCourseExamTable(this.courseExamCutOffIndex, this.arrayKeyName)));
						}
					}
					removeIndex = index;
				}
			});
			if (+removeIndex == removeIndex) {
				this._addmore.splice(removeIndex, 1);
			}
		}
	}

	fillFormGroupData() {
		this.editData.forEach((group, index) => {
			for (let key in group) {
				for (let val of this._addmore) {
					if (val['value'] == key) {
						setTimeout(() => { this.addColumn(key); }, 0);
					}
				}
			}
			this.array.controls.forEach((control,controlIndex)=>{
				if(control.value['type'] == group['type']){
					super.fillFormGroup(group, this.array.controls[controlIndex]);
				}
			});
		});
	}

	removeTable() {
		this.removeClicked.emit({ isRemoved: true });
	}
}