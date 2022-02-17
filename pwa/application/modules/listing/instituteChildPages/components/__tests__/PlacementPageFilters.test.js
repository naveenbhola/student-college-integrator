import React from 'react';
import { shallow,mount } from 'enzyme';
import PlacementPageFilters from  "./../PlacementPageFilters";
import {MockPlacementData} from "./../PlacementMockData";


const setup = propOverrides => {
  const props = Object.assign({
    salaryData : MockPlacementData.naukriData.salaryData,
    data:MockPlacementData.naukriData,
    selectedYear:MockPlacementData.selectedYear,
    seoUrl:MockPlacementData.seoUrl,
    selectedBaseCourseId:MockPlacementData.selectedBaseCourseId

  },propOverrides)  


  const wrapper = shallow(<PlacementPageFilters {...props} />);
  return {
    props,
    wrapper
  }
}

  const yearList = jest.spyOn(PlacementPageFilters.prototype,'yearList');
  const baseCourseList = jest.spyOn(PlacementPageFilters.prototype,'baseCourseList');


describe('Naukri graph with All Data present', () => {
  test('snapshot test' , () => {
  	const {wrapper} = setup({});
  	expect(wrapper).toMatchSnapshot();
  })

  test('year change' , () => {
    const {wrapper} = setup({selectedYear:2017});
    expect(wrapper).toMatchSnapshot();
    expect(yearList).toHaveBeenCalled();
    expect(baseCourseList).toHaveBeenCalled();
  })

});

describe('bip data or year data null case', () => {
  test('bip data is null' , () => {
    let data = Object.assign({},MockPlacementData.naukriData);
    data.baseCourseIds = null;
    const {wrapper} = setup({data:data});
    expect(baseCourseList).toHaveBeenCalledTimes(0);
  })

  test('year change' , () => {
    let data = Object.assign({},MockPlacementData.naukriData);
    data.completionYear = null;
    const {wrapper} = setup({data:data});
    expect(yearList).toHaveBeenCalledTimes(0);
  })

});
