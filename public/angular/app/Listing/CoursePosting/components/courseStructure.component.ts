import { Component, OnInit, Input, Output, EventEmitter, OnChanges, OnDestroy } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { My_Custom_Validators } from '../../../reusables/Validators';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector: 'courseStructure',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseStructure.template.html'),
	directives: [REACTIVE_FORM_DIRECTIVES]
})

export class CourseStructureComponent extends Posting implements OnChanges {

	@Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
	@Input('group') courseStructureForm: FormGroup;
	@Input() courseStaticData;
	@Input() mode;
	@Input() editData;

	initialized = false;

	ngOnChanges(changes) {
		for (let propName in changes) {
			if (propName == 'courseStaticData' || propName == 'courseStructureForm') {
				if (this.courseStaticData && this.courseStructureForm) {

					this.generateFormControls();

					this.courseStructureForm.controls['group_by'].valueChanges.subscribe((data) => {
						this.resetFormArray();
					});

					if (this.mode == 'add') {
						(<FormControl>this.courseStructureForm.controls['group_by']).updateValue('program', { emitEvent: true });
					}
					this.initialized = true;
				}
			}

			if (propName == 'editData' || propName == 'courseStaticData') {
				if (this.courseStaticData && this.editData) {
					this.fillFormGroupData();
				}
			}
		}
	}

	resetFormArray() {
		this.emptyFormArray(<FormArray>this.courseStructureForm.controls['group_courses']);
		this.addCourseGroup();
	}

	generateFormControls() {
		this.courseStructureForm.addControl('group_by', new FormControl('', Validators.required));
		this.courseStructureForm.addControl('group_courses', new FormArray([]));
	}

	addCourse(index) {
		(<FormArray>(<FormArray>this.courseStructureForm.controls['group_courses']).controls[index]).push(new FormControl('', My_Custom_Validators.validateMaxLength(100)));
	}

	addCourseGroup() {
		let length = (<FormArray>this.courseStructureForm.controls['group_courses']).length;
		(<FormArray>this.courseStructureForm.controls['group_courses']).push(new FormArray([]));
		let array = [0, 0, 0, 0, 0];
		array.forEach(() => {
			this.addCourse(length);
		});
	}

	fillFormGroupData() {
		if (this.editData['group_by']) {
			for (let key in this.editData) {
				switch (key) {

					case 'group_by':
						(<FormControl>this.courseStructureForm.controls['group_by']).updateValue(this.editData[key], { emitEvent: true });
						break;

					case 'group_courses':
						this.editData[key].forEach((group, index) => {
							if (index) {
								this.addCourseGroup();
							}
							group.forEach((val, j) => {
								if (j > 4) {
									this.addCourse(index);
								}
								super.fillFormGroup(val, (<FormArray>(<FormArray>this.courseStructureForm.controls[key]).controls[index]).controls[j]);
							});
						});
						break;
				}
			}
		}
		else {
			(<FormControl>this.courseStructureForm.controls['group_by']).updateValue('program', { emitEvent: true });
		}
	}
}