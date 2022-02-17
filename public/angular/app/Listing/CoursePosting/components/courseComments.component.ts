import { Component, Input, OnInit, AfterViewInit, ViewChild, OnChanges } from '@angular/core';
import { Posting } from '../../../Common/Classes/Posting';

import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import {MODAL_DIRECTIVES, BS_VIEW_PROVIDERS} from 'ng2-bootstrap/ng2-bootstrap';
import {ModalDirective} from '../../../Common/Components/modal/modal.component';
import { CoursePostingService } from '../services/coursePosting.service';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';


@Component({
	selector: "courseComments",
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseComments.template.html'),
	directives: [REACTIVE_FORM_DIRECTIVES, MODAL_DIRECTIVES],
	providers: [CoursePostingService, BS_VIEW_PROVIDERS],
})

export class courseComments extends Posting implements OnInit, OnChanges {
	@Input('group') public courseComments: FormGroup;
	@Input() editData;

	constructor(private coursePosting: CoursePostingService) {
		super();
	}
	commentHistoryData;
	courseId = 0;

	ngOnInit() {
		this.generateFormControls();
	}

	ngOnChanges(changes) {
		for (let propName in changes) {
			if (propName == 'editData') {
				this.courseId = this.courseComments.root.value['courseId'];
			}
		}
	}


	viewComments() {
		this.coursePosting.getCoursePostingComments(this.courseId).subscribe(
			data => {
				this.commentHistoryData = data;
				this.showCommentModal();
			},
			error => alert('Failed to get comment history data')
		);
	}

	generateFormControls() {
		this.courseComments.addControl('comment', new FormControl('', Validators.compose([Validators.maxLength(250), Validators.required])));
	}

	@ViewChild('commentModal') public commentModal: ModalDirective;
    public showCommentModal(): void {
		this.commentModal.show();
    }

    public hideCommentModal(): void {
		this.commentModal.hide();
    }
}