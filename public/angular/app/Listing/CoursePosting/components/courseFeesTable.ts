import { Component, OnInit, Input, OnChanges } from '@angular/core';
import { Router } from '@angular/router';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { My_Custom_Validators } from '../../../reusables/Validators';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
    selector: 'courseFeesTable',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseFeesTable.template.html'),
	directives: [REACTIVE_FORM_DIRECTIVES]
})

export class CourseFeesTable extends Posting implements OnInit, OnChanges {
	@Input() courseStaticData;
	@Input('group') courseFeesForm: FormGroup;
	@Input() tableCaption;
	@Input() controlName;
	@Input() editData;
	@Input() showAddMore = 1;
	colItemShow = [];
	addMoreColItem = [];
	_addMore = [];
	_default = [];
	initialized = false;

	ngOnInit() {
		//console.log(this.courseFeesForm);
	}

	ngOnChanges(changes) {
		for (let propName in changes) {
			if (propName == 'courseStaticData') {
				if (this.courseStaticData) {
					for (let i of this.courseStaticData.categories) {
						if (i.type == 'default') {
							this._default.push(i);
							this.colItemShow.push(i);
						} else {
							this._addMore.push(i);
							this.addMoreColItem.push(i);
						}
					}

					setTimeout(() => {
						this.createTotalFeesFormGroup(this.controlName);
						if (this.controlName != 'hostel_fees') {
							this.createTotalFeesFormGroup(this.controlName + '_total');
							this.createTotalFeesFormGroup(this.controlName + '_one_time_payment');
						}
						this.initialized = true;
					}, 0);

				}
			}
			if (propName == 'editData' || propName == 'courseStaticData') {
				if (this.courseStaticData && this.editData) {
					setTimeout(() => {
						this.fillFormGroupData();
					}, 0);
				}
			}
		}
	}


	private fillFormGroupData(): void {
		if (this.editData.length == 0) {
			return;
		}
		for (let key in this.editData) {
			switch (key) {
				case 'total_fees_total':
				case 'total_fees_one_time_payment':
					for (let name in this.editData[key]) {
						for (let val of this._addMore) {
							if (val['value'] == name) {
								this.updateFeeCol(name);
							}
						}
					}
					super.fillFormGroup(this.editData[key], this.courseFeesForm.controls[key]);
					break;
				case 'total_fees':
					this.emptyFormArray((<FormArray>this.courseFeesForm.controls[key]));
					for (let name in this.editData[key]) {
						for (let val of this._addMore) {
							if (val['value'] == name) {
								this.updateFeeCol(name);
							}
						}
					}
					for (var index in this.editData[key]) {
						this.addTotalFeesRow();
						super.fillFormGroup(this.editData[key][index], (<FormArray>this.courseFeesForm.controls[key]).controls[index]);
					}
					break;
				default:
					break;
			}
		}
	}


	createTotalFeesFormGroup(type) {
		if (type == this.controlName) {
			let totalFeesFormGroup = new FormGroup({});
			for (let i of this.colItemShow) {
				totalFeesFormGroup.addControl(i.value, new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
			}
			(<FormArray>this.courseFeesForm.controls[type]).push(totalFeesFormGroup);
		} else {
			for (let i of this.colItemShow) {
				(<FormGroup>this.courseFeesForm.controls[type]).addControl(i.value, new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
			}
		}
	}

	addTotalFeesRow() {
		this.createTotalFeesFormGroup(this.controlName);
	}

	removeTotalFeesRow(index) {
		(<FormArray>this.courseFeesForm.controls[this.controlName]).removeAt(index);
		this.courseFeesForm.controls[this.controlName].markAsDirty();
	}

	updateFeeCol(item) {
		setTimeout(() => {
			let removeIndex: number;
			this.addMoreColItem.forEach((val, index) => {
				if (val['value'] == item) {
					for (let i in (<FormArray>this.courseFeesForm.controls[this.controlName]).controls) {
						(<FormGroup>(<FormArray>this.courseFeesForm.controls[this.controlName]).controls[i]).addControl(item, new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
					}

					(<FormGroup>this.courseFeesForm.controls[this.controlName + '_total']).addControl(item, new FormControl('', My_Custom_Validators.validateWholeNumber(0)));
					(<FormGroup>this.courseFeesForm.controls[this.controlName + '_one_time_payment']).addControl(item, new FormControl('', My_Custom_Validators.validateWholeNumber(0)));

					this.colItemShow.push(val);
					removeIndex = index;
				}
			});

			this.addMoreColItem.splice(removeIndex, 1);
		}, 0);
	}

}