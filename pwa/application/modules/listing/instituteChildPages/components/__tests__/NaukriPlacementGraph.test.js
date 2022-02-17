import React from 'react';
import { shallow,mount } from 'enzyme';
import NaukriCompanyList from  "./../NaukriPlacementGraph";
import {MockPlacementData} from "./../PlacementMockData";

const setup = propOverrides => {
	const props = Object.assign({
		salaryData : MockPlacementData.naukriData.salaryData

	},propOverrides)	


  const wrapper = shallow(<NaukriCompanyList {...props} />);
  return {
    props,
    wrapper
  }
}


describe('Naukri graph with All Data present', () => {
  test('snapshot test' , () => {
  	const {wrapper} = setup({});
  	expect(wrapper).toMatchSnapshot();
  })

  test('minimum salary null' , () => {
    let salaryData = Object.assign({},MockPlacementData.naukriData.salaryData);
    salaryData.minSalary = null;

    const {wrapper} = setup({salaryData});
    expect(wrapper.state().data.length).toEqual(3);
  })
  test('avg salary null' , () => {
    let salaryData = Object.assign({},MockPlacementData.naukriData.salaryData);
    salaryData.avgSalary = null;

    const {wrapper} = setup({salaryData});
    expect(wrapper.state().data.length).toEqual(3);
  })
  test('max salary null' , () => {
    let salaryData = Object.assign({},MockPlacementData.naukriData.salaryData);
    salaryData.maxSalary = null;

    const {wrapper} = setup({salaryData});
    expect(wrapper.state().data.length).toEqual(3);
  })
  test('min and avg both salary null' , () => {
    let salaryData = Object.assign({},MockPlacementData.naukriData.salaryData);
    salaryData.minSalary = null;
    salaryData.avgSalary = null;

    const {wrapper} = setup({salaryData});
    expect(wrapper.state().data.length).toEqual(2);
  })
  test('min and avg and max all salary null' , () => {
    let salaryData = Object.assign({},MockPlacementData.naukriData.salaryData);
    salaryData.minSalary = null;
    salaryData.avgSalary = null;
    salaryData.maxSalary = null;
    const {wrapper} = setup({salaryData});
    expect(wrapper.state().data.length).toEqual(1);
  })

});
