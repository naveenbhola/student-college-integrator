import { Injectable, Output, EventEmitter }     from '@angular/core';
import { FormGroup, FormArray } from '@angular/forms';

export class Posting {
    opened: Boolean = true;
    @Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();


    rebindFormControl(str: string) {
        str = 'activate' + str.charAt(0).toUpperCase() + str.slice(1);
        this[str] = false;
        setTimeout(() => { this[str] = true; }, 0);
    }

    toggle() {
        this.opened = !this.opened;
    }

    emptyFormArray(mappingInfo: FormArray) {
        while (mappingInfo.length != 0) {
            mappingInfo.removeAt(mappingInfo.length - 1);
        }
    }

    fillFormGroup(groupData, form) {
        if (typeof groupData == 'object' && groupData != null) {
            for (let key in groupData) {
                if (typeof groupData[key] == 'object') {
                    if (Array === groupData[key].constructor) {
                        setTimeout(() => { form.controls[key].updateValue(groupData[key], { emitEvent: true }); }, 0);
                    }
                    else {
                        this.fillFormGroup(groupData[key], form.controls[key]);
                    }
                }
                else {
                    setTimeout(() => { /*console.log('key',key);*/form.controls[key].updateValue(groupData[key], { emitEvent: true });/*console.log('key',key);*/ }, 500);
                }
            }
        }
        else {
            setTimeout(() => { /*console.log('group',groupData);*/form.updateValue(groupData, { emitEvent: true });/*console.log('group',groupData);*/ }, 500);
        }
    }

    isEmptyObject(obj) {
        if (obj) {
            for (let i in obj) {
                return false;
            }
        }
        return true;
    }

    toolTipNotify(val: string, event) {
        var rect = document.getElementById(val).getBoundingClientRect();
        let obj = { 'val': val, 'x': (rect.top + window.pageYOffset), 'y': (rect.left) };
        this.toolTipEvent.emit(obj);
    }
}