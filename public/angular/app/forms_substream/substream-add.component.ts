import { Component, OnInit, OnDestroy } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup,FormBuilder, Validators }    from '@angular/forms';
import { SubstreamService }       from '../services/substream.service';
import { SortArrayPipe } from '../pipes/arraypipes.pipe';
import { ListingBaseClass, ListingBaseService } from '../services/listingbase.service';
import { Router,ActivatedRoute } from '@angular/router';


@Component({
	selector: 'substreamAdd',
	templateUrl: '/public/angular/app/forms_substream/substream-add.component.html',
	providers:[SubstreamService],
	directives: [REACTIVE_FORM_DIRECTIVES],
	pipes: [SortArrayPipe],
	styles: [`
        .ng-invalid {
            border: 1px solid #a94442;
        }
        .ng-pristine{
        	border: 1px solid #ccc;
        }
    `]
})

export class SubstreamAddComponent extends ListingBaseClass implements OnInit, OnDestroy {
	substreamForm: any;
	submitPending = false;
	changeFlag:boolean = true;
	errorMessage: string;
	substreamData: any[];
	mode:string = 'add';
	substreamId:number = 0;
	formGroup: any = {};
	private sub: any;
	constructor(public fb: FormBuilder, private substreamService: SubstreamService, private router: Router, private route: ActivatedRoute, public listingBaseService: ListingBaseService) { super(listingBaseService); }

	ngOnInit() {
		this.getStreamList();
		this.formGroup = {
			substreamName: ["", Validators.compose([Validators.required,Validators.maxLength(100)])],
			substreamAlias: ["", Validators.maxLength(100)],
			substreamSynonym: ["", Validators.maxLength(150)],
			substreamPrimaryStream: ["", Validators.required],
			substreamDisplayOrder: ["", Validators.required]
		}
		this.sub = this.route.params.subscribe(params => {
			let id = +params['id'];
			if(id){
				this.mode = 'edit';
				this.substreamService.getSubstream(id).subscribe(data => { this.substreamData = data; this.substreamId = id; this.formGroup.substreamPrimaryStream = [""]; this.changeDetected(); }, error => { this.errorMessage = error; alert('No such substream exists'); this.backToHome(); });
			}
		});
		
		this.substreamForm = this.fb.group(this.formGroup);
	}
	
	backToHome(){
		this.router.navigate(['/cmsPosting/substreamPosting/viewList']);	
	}
	submitSubstream() {
		if (this.substreamForm.dirty) {
			if (this.substreamForm.valid){
				this.submitPending = true;
				this.substreamService.addSubstream(this.substreamForm.value,this.mode,this.substreamId)
					.subscribe(
						data => { alert(data['message']);if(data.status == 'success'){this.submitPending = false; this.backToHome();}else{this.submitPending = false;} },
						error => { alert('Internal error'); this.backToHome(); }
					);
			}
		}
		else{
			this.backToHome();
		}
	}

	ngOnDestroy(){
		this.sub.unsubscribe();
	}

	changeDetected(){
		this.changeFlag = false;
		this.substreamForm = this.fb.group(this.formGroup);
		setTimeout(() => this.changeFlag = true,20);
	}
}