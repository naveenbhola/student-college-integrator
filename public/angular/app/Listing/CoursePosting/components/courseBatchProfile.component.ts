import { Component, Input, OnInit, AfterViewInit } from '@angular/core';
import { Posting } from '../../../Common/Classes/Posting';

import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators } from '@angular/forms';
import { AddColumnTableComponent } from '../../../reusables/addColumnTableComponent';


@Component({
	selector: "courseBatchProfile",
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/courseBatchProfile.template.html',
	directives: [REACTIVE_FORM_DIRECTIVES, AddColumnTableComponent]
})

export class CourseBatchProfileComponent extends Posting implements OnInit {
	@Input('group')
    public courseBatchProfile: FormGroup;



	@Input() courseStaticData;

  	 _addMore = [];
	_default = [];

	_genderDefault = [
		{ 'label': '% Male', 'type': 'default', 'value': 'male' },
		{ 'label': '% Female', 'type': 'default', 'value': 'female' },
		{ 'label': '% Transgender', 'type': 'default', 'value': 'transgender' }
	];
	initialized = false;

	ngOnInit() {
		//setTimeout(()=> {console.log(this.courseStaticData);},100);
	}


	ngOnChanges(changes) {
		for (let propName in changes) {
			if (propName == 'courseStaticData' || propName == 'courseBatchProfile') {
				if (this.courseStaticData && this.courseBatchProfile) {
					// for (let i of this.courseStaticData.categories) {
					// 	if (i.type == 'default') {
					// 		this._default.push(i);

					// 		//this.colItemShow.push(i);
					// 	} else {
					// 		this._addMore.push(i);
					// 		//this.addMoreColItem.push(i);
					// 	}
					// }
					//console.log(this._addMore);
					this.generateFormControls();
					this.initialized = true;
				}
			}
		}
	}

	generateFormControls() {
		this.courseBatchProfile.addControl('gender', new FormGroup({}));
		this.courseBatchProfile.addControl('avg_work_ex', new FormControl());
		this.courseBatchProfile.addControl('per_international_students', new FormControl());
	}
}