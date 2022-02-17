import { Component, Input, Output, OnInit, AfterViewInit, EventEmitter, OnChanges } from '@angular/core';
import { Posting } from '../../../Common/Classes/Posting';
import { InstituteParentHierarchyComponent } from '../../ListingReusables/components/instituteParentHierarchy.component';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators } from '@angular/forms';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector: "courseParentEntitySection",
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseParentEntitySection.component.html'),
	directives: [InstituteParentHierarchyComponent, REACTIVE_FORM_DIRECTIVES]
})

export class CourseParentEntitySectionComponent extends Posting implements OnInit {
	@Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
	@Input() mode;

	@Input('group')
	public hierarchyForm: FormGroup;
	@Input() set extraData(val) {
		if (typeof val != 'undefined' && val) {
			let extraDataFormat = [];
			for (let i in val) {
				extraDataFormat.push(val[i])
			}
			this.selectedHierarchyObj = extraDataFormat;
			this.primaryHierarchyOption = [];
			for (let item of this.selectedHierarchyObj.reverse()) {
				if (item.is_dummy != '1') {
					this.primaryHierarchyOption.push(item.id);
					if(item.type == 'university' || item.type == 'college'){
						break;
					}
				}
			}
			this.selectedHierarchyObj.reverse();
		}
	}

	showEditConfirmation = true;
	subscribeParentCourseHierarchy;
	primaryHierarchyOption = [];

	ngOnInit() {
		this.hierarchyForm.addControl('parent_course_hierarchy', new FormControl('', Validators.required));
		this.hierarchyForm.addControl('primary_course_hierarchy', new FormControl('', Validators.required));
		this.hierarchyForm.addControl('primary_course_hierarchy_name', new FormControl(''));
		this.hierarchyForm.addControl('parent_course_hierarchy_name', new FormControl(''));
	}

	selectedHierarchyObj: any = [];

	selectedHierarchy(res) {
		if (this.mode == 'edit' && this.showEditConfirmation) {
			let response = confirm('Category sponsor/MIL marked with the earlier primary institute will be lost if the primary institute is changed. Continue?');
			if (response) {
				this.showEditConfirmation = false;
				this.processSelectedHierarchy(res['selectedParentEntity'], res['selectedParentObj']);
			}
		}
		else {
			this.processSelectedHierarchy(res['selectedParentEntity'], res['selectedParentObj']);
		}
	}

	processSelectedHierarchy(val, obj) {
		this.primaryHierarchyOption = [];
		let primaryHierarchyOptionObj;
		this.selectedHierarchyObj = [];
		(<FormControl>this.hierarchyForm.controls['parent_course_hierarchy']).updateValue(val);
		this.selectedHierarchyObj = obj;
		for (let item of this.selectedHierarchyObj.reverse()) {
			if (item.is_dummy != '1') {
				this.primaryHierarchyOption.push(item.id);
				if ((item.type == 'college' || item.type == 'university')) {
					primaryHierarchyOptionObj = item;
					break;
				}
			}
		}
		(<FormControl>this.hierarchyForm.controls['parent_course_hierarchy_name']).updateValue(obj[0].name);
		if (obj[0]['disabled_url']) {
			(<FormControl>(<FormGroup>this.hierarchyForm.root).controls['isDisabled']).updateValue(obj[0]['disabled_url'], { emitEvent: true });
		}
		else{
			(<FormControl>(<FormGroup>this.hierarchyForm.root).controls['isDisabled']).updateValue(null, { emitEvent: true });
		}
		if (this.hierarchyForm.contains('primary_course_hierarchy')) {
			(<FormControl>this.hierarchyForm.controls['primary_course_hierarchy']).updateValue('');
		} else {
			this.hierarchyForm.addControl('primary_course_hierarchy', new FormControl('', Validators.required));
		}

		if (this.primaryHierarchyOption.length > 0) {
			// (<FormControl>this.hierarchyForm.controls['primary_course_hierarchy']).updateValue(this.primaryHierarchyOption[0]);
			if (primaryHierarchyOptionObj) {
				(<FormControl>this.hierarchyForm.controls['primary_course_hierarchy_name']).updateValue(primaryHierarchyOptionObj.name);
				(<FormControl>this.hierarchyForm.controls['primary_course_hierarchy']).updateValue(primaryHierarchyOptionObj.id);
			}
			else {
				(<FormControl>this.hierarchyForm.controls['primary_course_hierarchy']).updateValue(this.primaryHierarchyOption[0]);
				this.primaryHierarchyOption.forEach((val) => {
					if (val['id'] == this.primaryHierarchyOption[0]) {
						(<FormControl>this.hierarchyForm.controls['primary_course_hierarchy_name']).updateValue(val['name']);
					}
				});
			}
		}

		this.hierarchyForm.markAsDirty();

		this.selectedHierarchyObj.reverse();
	}

	removeCourseHierarchy() {
		if (this.mode == 'edit' && this.showEditConfirmation) {
			let res = confirm('Category sponsor/MIL marked with the earlier primary institute will be lost if the primary institute is changed. Continue?');
			if (res) {
				this.showEditConfirmation = false;
				this.removeCourseHierarchyProcess();
			}
		}
		else {
			this.removeCourseHierarchyProcess();
		}

	}
	removeCourseHierarchyProcess() {
		this.selectedHierarchyObj = [];
		this.primaryHierarchyOption = [];
		//update dummy value to run subscribe
		(<FormControl>this.hierarchyForm.controls['primary_course_hierarchy']).updateValue('abc');
		(<FormControl>this.hierarchyForm.controls['parent_course_hierarchy']).updateValue('');
		(<FormControl>this.hierarchyForm.controls['primary_course_hierarchy']).updateValue('');
		(<FormControl>(<FormGroup>this.hierarchyForm.root).controls['isDisabled']).updateValue(null, { emitEvent: true });
		this.hierarchyForm.markAsDirty();
	}

	toolTipModalParentShow(event: any) {
		this.toolTipEvent.emit(event);
	}
}