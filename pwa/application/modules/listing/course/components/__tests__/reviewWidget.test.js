import React from 'react';
import { shallow } from 'enzyme';
import ReviewWidget from  "./../ReviewWidget";
import config from './../../../../../../config/config';
import {MockCourseData} from "./../MockData";


const setup = propOverrides => {
	const props = Object.assign({
		reviewWidgetData : MockCourseData.reviewWidget,
		isPaid: MockCourseData.coursePaid,
		aggregateReviewWidgetData : MockCourseData.aggregateReviewWidget
	},propOverrides)

	const spyGenrateLayer = jest.spyOn(ReviewWidget.prototype,'generateReviewWidgetHtml');
	const spyGenerateReviewRatingLayer = jest.spyOn(ReviewWidget.prototype,'generateReviewRatingLayer1');
	const markSelected = jest.spyOn(ReviewWidget.prototype,'markSelected');


	const wrapper = shallow(<ReviewWidget {...props} config={config()} />);
	return {
		props,
		wrapper,
		review: wrapper.find('#review'),
		spyGenrateLayer,
		spyGenerateReviewRatingLayer,
		markSelected
	}
}

describe('Review Data Testing with All Data', () => {
	test('snapshot test' , () => {
		const {wrapper} = setup({});
		expect(wrapper).toMatchSnapshot();
	})

	test('check for child component and function calls', () => {
		const { wrapper,review,spyGenrateLayer} = setup({});
		expect(spyGenrateLayer).toHaveBeenCalled();
		expect(review.length).toEqual(1);
		expect(wrapper.state().activeReview).toBe('');
		expect(wrapper.find('OverallRatingWidget').length).toEqual(1);
	})


	test('onclick state change testing ', () => {
		const {wrapper,markSelected} = setup();
		wrapper.find('#rating-block_0').simulate('click', 'using prototype');
		expect(markSelected).toHaveBeenCalled();
		expect(wrapper.state().activeReview).toBe('rating-col_233095');
		wrapper.update();
		expect(wrapper).toMatchSnapshot();
	})

});

describe('Test for aggregateReviewWidget', () => {

	test('when aggregateReviewWidget data is null', () => {
		const { wrapper,review,spyGenrateLayer} = setup({'aggregateReviewWidgetData': null});
		expect(spyGenrateLayer).toHaveBeenCalled();
		expect(review.length).toEqual(1);
		expect(wrapper.state().activeReview).toBe('');
		expect(wrapper.find('OverallRatingWidget').length).toEqual(0);
	})

	test('when aggregateReviewWidget<3.5 and paid true', () => {
		var aggregateReviewWidgetData = Object.assign(MockCourseData.aggregateReviewWidget);
		aggregateReviewWidgetData.aggregateReviewData.aggregateRating.averageRating.mean = 2.5;

		const { wrapper} = setup({'aggregateReviewWidgetData': aggregateReviewWidgetData ,'isPaid': true });
		expect(wrapper.find('OverallRatingWidget').length).toEqual(0);
	})

});

describe('Test for reviewWidgetData', () => {

	test('when number of reviews less than 4' ,() =>{
		var reviewWidgetData = Object.assign(MockCourseData.reviewWidget);
		reviewWidgetData.reviewData.totalReviews =  3;
		const { wrapper} = setup({'reviewWidgetData': reviewWidgetData ,'isPaid': true });
		expect(wrapper.find('.trnBtn.rippleefect.dark').length).toEqual(0);

	})

});