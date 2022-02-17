import { Component,OnInit,Input,ViewChild } from '@angular/core';
import {Posting} from '../../../Common/Classes/Posting';
import { MY_VALIDATORS } from '../../../reusables/Validators';
import { RegisterFormModelDirective } from '../../../reusables/registerForm';
import {REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, NgForm} from "@angular/forms";
import { InstitutePostingService } from '../../../services/institute-posting.service';
import { MapToIterable, SortArrayPipe } from '../../../pipes/arraypipes.pipe';
import {MODAL_DIRECTIVES,BS_VIEW_PROVIDERS} from 'ng2-bootstrap/ng2-bootstrap';
import {ModalDirective} from '../../../Common/Components/modal/modal.component';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector : "comment-posting",
	templateUrl: '/public/angular/app/Listing/InstitutePosting/Views/'+getHtmlVersion('commentPosting.component.html'),
	directives:[MY_VALIDATORS,REACTIVE_FORM_DIRECTIVES, RegisterFormModelDirective,MODAL_DIRECTIVES],
	providers:[InstitutePostingService,BS_VIEW_PROVIDERS],
})
 
export class CommentPostingComponent extends Posting{
	@Input() instituteObj;
    @Input('form') form: NgForm;
    commentHistoryData;

	constructor(private institutePosting: InstitutePostingService){
		super();
	}

	viewComments(){
		this.institutePosting.getPostingComments(this.instituteObj.institute_id,this.instituteObj.postingListingType).subscribe(
              data => {this.commentHistoryData = data;this.showCommentModal();},
               error => alert('Failed to get comment history data')
          );
	}
	
	@ViewChild('commentModal') public commentModal: ModalDirective;
    public showCommentModal():void {
      this.commentModal.show();
    }

    public hideCommentModal():void {
      this.commentModal.hide();
    }
}

