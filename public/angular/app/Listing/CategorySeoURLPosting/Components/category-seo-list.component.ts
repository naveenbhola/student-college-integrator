import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ArraySearchPipe, LimitArrayPipe } from '../../../pipes/arraypipes.pipe';
import { ListingBaseClass, ListingBaseService } from '../../../services/listingbase.service';
import { CategorySeoService  }       from '../../../services/category-seo.service';

@Component({
    selector: 'categorySeoList',
    templateUrl: '/public/angular/app/Listing/CategorySeoURLPosting/Views/category-seo-list.component.html',
    providers: [CategorySeoService],
    pipes: [ArraySearchPipe, LimitArrayPipe]
})

export class CategorySeoListComponent implements OnInit {

    categoryURLData:any = [];

    constructor(private router:Router, public categorySeoService:CategorySeoService) {
    }

    ngOnInit() {

        this.categorySeoService.getCategoryURLs('').subscribe(
                data => {
                if (data.found == 'yes') {
                    this.categoryURLData = data.result;
                } else {
                    this.categoryURLData = [];
                }
            },
                error => {
                    console.log(error);
                alert('No URLs found');
            }
        );

    }

    newCategoryPage() {
        this.router.navigate(['/nationalCategoryList/CategoryPageSeoEnterprise/create']);
    }
}

