import { Directive, ElementRef, Input, OnInit } from '@angular/core';
import { NgModel, NgForm, FormControl } from '@angular/forms';

@Directive({
    selector: '[registerForm]'
})
export class RegisterFormModelDirective implements OnInit {
    private el: HTMLInputElement;

    @Input('registerForm') public form: NgForm;
    @Input('registerModel') public model: NgModel;

    constructor(el: ElementRef) {
        this.el = el.nativeElement;
    }

    ngOnInit() {        
        if (this.form && this.model) {
            this.form.form.registerControl(this.model.name, this.model.control);
            if(this.form.controls[this.model.name]) {
                (<FormControl>this.form.controls[this.model.name]).updateValue(this.model.model);
            } 
            else {
                this.form.addControl(this.model);
            }
        }
    }
}
