import { Component, OnInit,EventEmitter,OnDestroy,ViewChild } from '@angular/core';
import { Router,ActivatedRoute } from '@angular/router';
import { NgForm}    from '@angular/forms';
import {InstitutePostingCreateComponent} from '../../InstitutePosting/Components/institutePostingCreate.component';

@Component({
    selector: 'universityPostingCreate',
	template: '<institutePostingCreate [postingType]="postingType"></institutePostingCreate>',
  directives:[InstitutePostingCreateComponent],

})

export class UniversityPostingCreateComponent  extends OnInit{
  
  postingType:string = 'University';
  ngOnInit()
  {

  }
}