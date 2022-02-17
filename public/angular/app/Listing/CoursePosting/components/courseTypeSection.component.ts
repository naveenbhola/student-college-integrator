import { Component, OnInit, OnChanges, OnDestroy, Input, Output, EventEmitter } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { COURSE_VARIANT } from '../classes/CourseConst';
import {CourseTypeSubComponent} from './courseTypeSubsection.component';
import { ListingBaseService } from '../../../services/listingbase.service';
import { CourseDependencyService } from '../services/course-dependencies.service';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector: 'courseTypeInfo',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseTypeSection.component.html'),
	directives: [REACTIVE_FORM_DIRECTIVES, CourseTypeSubComponent]
})
export class CourseTypeComponent extends Posting implements OnInit, OnDestroy, OnChanges {

	@Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
	@Input() courseStaticData;
	@Input('group') courseTypeForm: FormGroup;
	@Input() mainForm;
	@Input() mode;
	@Input() hierarchyTree;
	@Input() hierarchyArray;
	@Input() isCloneMode;

	subscribeArray = {};
	CVCONST = COURSE_VARIANT;
	courseVariantOptions = [];
	course_type;
	processingEdit = false;

	constructor(public dependencyService: CourseDependencyService) {
		super();
	}

	@Input() editData;

	ngOnInit() {
		this.courseTypeForm.addControl('course_variant', new FormControl('', Validators.required));
		this.courseTypeForm.addControl('mapping_info', new FormArray([]));
		this.courseTypeForm.addControl('course_tags', new FormGroup({
			'is_executive': new FormControl(),
			'is_lateral': new FormControl()
		}));

		this.course_type = this.mainForm.controls['course_type'].value + '';

		this.subscribeArray['course_variant'] = this.courseTypeForm.controls['course_variant'].valueChanges.subscribe((data) => {
			this.handleCourseVariantChange(data);
		});

		this.populateCourseVariantOptions();
		if (this.mode == 'add' && !this.isCloneMode) {
			super.fillFormGroup(this.CVCONST.single, this.courseTypeForm.controls['course_variant']);
		}
	}

	ngOnChanges(changes) {
		for (let propName in changes) {
			if (propName == 'editData' && changes[propName]['currentValue']) {
				this.fillFormGroupData();
			}
		}
	}

	fillFormGroupData() {
		this.processingEdit = true;
		for (let name in this.editData) {
			switch (name) {
				case 'course_variant':
					setTimeout(() => { /*console.log('aaa');*/(<FormControl>this.courseTypeForm.controls[name]).updateValue(this.editData[name], { emitEvent: true }); setTimeout(()=>{this.processingEdit = false;},1000);/*console.log('aaa');*/ }, 500);
					break;
			}
		}
	}

	populateCourseVariantOptions() {
		this.courseVariantOptions = [];
		let dependencies = this.courseStaticData['attribute_dependencies'];
		this.courseStaticData['course_variant'].forEach((val) => {
			if (dependencies[val['value']]['parent_value_id'].indexOf(this.course_type) != -1) {
				this.courseVariantOptions.push(val);
			}
		});
	}

	initOtherControls(type: "entry" | "exit") {
		let group = new FormGroup({
			'credential': new FormControl('', Validators.required),
			'course_level': new FormControl('', Validators.required),
			'hierarchyMapping': new FormArray([]),
			'courseMapping': new FormControl(''),
			'type': new FormControl(type, Validators.required)
		});
		if (type == 'entry') {
			this.subscribeArray['course_level'] = group.controls['course_level'].valueChanges.subscribe((data) => {
				let reason = 'action';
				if (this.processingEdit) {
					reason = 'edit';
				}
				this.dependencyService.updateCourseLevel({ 'data': data, 'reason': reason });
			});
		}
		return group;
	}

	initOtherCourseTags(variant) {
		let formGroup = <FormGroup>this.courseTypeForm.controls['course_tags'];
		let controlNames = ['is_twinning', 'is_dual', 'is_integrated'];

		if (variant == this.CVCONST.double) {
			for (let control of controlNames) {
				formGroup.addControl(control, new FormControl());
			}
		}
		else {
			for (let control of controlNames) {
				formGroup.removeControl(control);
			}
		}
	}

	handleCourseVariantChange(variant) {
		let mappingInfo = <FormArray>this.courseTypeForm.controls['mapping_info'];

		if (mappingInfo.length > 0) {
			this.emptyFormArray(mappingInfo);

			if (this.editData) {
				let mapping = [{ 'hierarchyMapping': [{ 'stream_id': '', 'substream_id': '', 'specialization_id': '', 'is_primary': '1' }] }];
				if (variant == this.CVCONST.double) {
					mapping.push({ 'hierarchyMapping': [{ 'stream_id': '', 'substream_id': '', 'specialization_id': '', 'is_primary': '0' }] });
				}
				this.editData = {};
				this.editData['mapping_info'] = mapping;
			}
		}

		if (variant == this.CVCONST.single || variant == this.CVCONST.none) {
			mappingInfo.push(this.initOtherControls('entry'));
		}
		else if (variant == this.CVCONST.double) {
			mappingInfo.push(this.initOtherControls('entry'));
			mappingInfo.push(this.initOtherControls('exit'));
		}
		this.initOtherCourseTags(variant);

		if (this.editData) {
			for (let name in this.editData) {
				switch (name) {
					case 'course_tags':
						super.fillFormGroup(this.editData[name], this.courseTypeForm.controls[name]);
						break;
				}
			}
		}
	}

	toolTipModalParentShow(event: any) {
		this.toolTipEvent.emit(event);
	}


	ngOnDestroy() {
		for (let name in this.subscribeArray) {
			this.subscribeArray[name].unsubscribe();
		}
	}
}