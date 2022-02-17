import { Component, Input, OnInit, AfterViewInit, OnChanges } from '@angular/core';
import { Posting } from '../../../Common/Classes/Posting';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';


@Component({
	selector: "courseSeo",
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseSeo.template.html'),
	directives: [REACTIVE_FORM_DIRECTIVES]
})

export class CourseSeo extends Posting implements OnInit {
	@Input('group') public courseSeo: FormGroup;

	@Input() editData;

	ngOnInit() {
		this.opened = false;
		this.generateFormControls();
	}


	ngOnChanges(changes) {
		for (let propName in changes) {
			if (propName == 'editData' && changes[propName]['currentValue']) {
				this.opened = typeof this.editData != 'undefined' && (typeof this.editData['title'] != 'undefined' || typeof this.editData['description'] != 'undefined');
				// this.resetSeoOnChange();
			}
		}
	}

	resetSeoOnChange(){
		setTimeout(()=>{
			let group = (<FormGroup>this.courseSeo.root);
			let o1 = (<FormGroup>group.controls['hierarchyForm']).controls['primary_course_hierarchy'].valueChanges;
			let o2 = (<FormGroup>group.controls['courseBasicInfoForm']).controls['course_name'].valueChanges;
			let o3 = (<FormGroup>group.controls['courseTypeForm']).controls['mapping_info'].valueChanges;
			let o4 = (<FormGroup>group.controls['courseLocations']).controls['locationsMain'].valueChanges;

			o1.merge(o2).merge(o3).merge(o4).debounceTime(500).subscribe(()=>{
				// if(this.courseSeo.controls['title'].value != this.editData['title']){
					super.fillFormGroup('',this.courseSeo.controls['title']);
				// }
				// if(this.courseSeo.controls['description'].value != this.editData['description']){
					super.fillFormGroup('',this.courseSeo.controls['description']);
				// }
			});

		},3000);
	}

	generateFormControls() {
		this.courseSeo.addControl('title', new FormControl(''));
		this.courseSeo.addControl('description', new FormControl());
	}
}