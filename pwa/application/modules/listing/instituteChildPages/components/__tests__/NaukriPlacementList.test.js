import React from 'react';
import { shallow,mount } from 'enzyme';
import NaukriCompanyList from  "./../NaukriCompanyList";
import {MockPlacementData} from "./../PlacementMockData";

const setup = propOverrides => {
	const props = Object.assign({
		companyData : MockPlacementData.naukriData.companyData,
    totalCompanies: MockPlacementData.naukriData.totalCompanies

	},propOverrides)	

  const spyViewAllCompany = jest.spyOn(NaukriCompanyList.prototype,'viewAllCompany');

  const wrapper = shallow(<NaukriCompanyList {...props} />);
  return {
    props,
    wrapper,
    spyViewAllCompany
  }
}


describe('Naukri List with All Data', () => {
  test('snapshot test' , () => {
  	const {wrapper} = setup({});
  	expect(wrapper).toMatchSnapshot();
  })

  test('total company 0 ',() => {
    const {wrapper} = setup({'totalCompanies': 0});
    expect(wrapper.find('#companyList').length).toEqual(0);
  })

  test('button click',() => {

    const{wrapper,spyViewAllCompany} = setup();
    wrapper.find('#view_all_company').simulate('click', 'using prototype');
    expect(spyViewAllCompany).toHaveBeenCalled();
    expect(wrapper.state().numberOfCompanyList).toEqual(25);
  })


  test('track event',() => {
    const{wrapper} = setup({'gaTrackingCategory':'placemenetPage'});
    
  })


});
