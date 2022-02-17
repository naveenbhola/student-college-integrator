import { Component, OnInit, OnDestroy } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup,FormBuilder, Validators }    from '@angular/forms';
import { SpecializationService }       from '../services/specialization.service';
import { SortArrayPipe } from '../pipes/arraypipes.pipe';
import { ListingBaseClass, ListingBaseService } from '../services/listingbase.service';
import { Router, ActivatedRoute } from '@angular/router';


@Component({
	selector: 'specializationAdd',
	templateUrl: '/public/angular/app/forms_specialization/specialization-add.component.html',
	providers: [SpecializationService],	
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

export class SpecializationAddComponent extends ListingBaseClass implements OnInit, OnDestroy {
	specializationForm: any;
	submitPending = false;
	changeFlag: boolean = true;
	specializationData: any[];
	substreamList = [];
	mode:string = 'add';
	specalizationId:number = 0;
	formGroup: any = {};
	private sub: any;

	constructor(public fb: FormBuilder, private specializationService: SpecializationService, private router: Router, private route: ActivatedRoute, public listingBaseService: ListingBaseService) { super(listingBaseService); }

	ngOnInit() {
		this.getStreamList();
		this.formGroup = {
			'specializationName': ["", Validators.compose([Validators.required, Validators.maxLength(100)])],
			'specializationAlias': ["", Validators.maxLength(100)],
			'specializationSynonym': ["", Validators.maxLength(150)],
			'specializationPrimaryStream': ["", Validators.required],
			'specializationPrimarySubstream': [""],
			'type': ["specialization", Validators.required],
		}

		this.sub = this.route.params.subscribe(params => {
			let id = +params['id'];
			if(id){
				this.mode = 'edit';
				this.specializationService.getSpecialization(id).subscribe(data => { this.specializationData = data; this.specalizationId = id; this.formGroup.specializationPrimaryStream = [""]; this.changeDetected(); }, error => { alert('No such specialization exists'); this.backToHome(); });
			}
		});

		this.specializationForm = this.fb.group(this.formGroup);

		this.specializationForm.controls['specializationPrimaryStream'].valueChanges.subscribe(streamId => this.fillSubstreams(streamId)); 
	}

	backToHome() {
		this.router.navigate(['/cmsPosting/specializationPosting/viewList']);
	}

	submitSpecialization() {
		if (this.specializationForm.dirty) {
			if (this.specializationForm.valid){
				this.submitPending = true;
				this.specializationService.addSpecialization(this.specializationForm.value,this.mode,this.specalizationId)
					.subscribe(
						data => { alert(data['message']); if (data.status == 'success') { this.submitPending =false; this.backToHome(); }else{this.submitPending = false;} },
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
		this.specializationForm = this.fb.group(this.formGroup);
		setTimeout(() => this.changeFlag = true,0);
	}

	fillSubstreams(streamId){
		let data = [];
		this.substreamList = [];
		this.listingBaseService.getSubstreamsByStreams(streamId)
		.subscribe(response => {
			data = response;
			data = data[streamId]['substreams'];
			if(data){
				for(let substreamId in data){
					this.substreamList.push({ 'substream_id': substreamId, 'name': data[substreamId]['name'] });
				}
			}
		});
	}
}