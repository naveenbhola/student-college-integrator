import React from 'react';
import { shallow,mount } from 'enzyme';
import NaukriAlumniComponent from  "./../NaukriAlumniComponent";
import {MockPlacementData} from "./../PlacementMockData";


const setup = propOverrides => {
  const props = Object.assign({
    gaCategory:'Placement_Page_Mobile',
    pageData:MockPlacementData,
    deviceType:'mobile'
  },propOverrides)  


  const wrapper = shallow(<NaukriAlumniComponent {...props} />);
  return {
    props,
    wrapper
  }
}

//  const yearList = jest.spyOn(NaukriAlumniComponent.prototype,'yearList');
  //const baseCourseList = jest.spyOn(NaukriAlumniComponent.prototype,'baseCourseList');


describe('Naukri Alumni Component with All Data present', () => {
  test('snapshot test' , () => {
  	const {wrapper} = setup({});
  	expect(wrapper).toMatchSnapshot();
  })
});

describe('Naukri Alumni Component with differnt Data', () => {
  test('when device type desktop' , () => {
    const {wrapper} = setup({deviceType:'desktop'});
    expect(wrapper.find('.naukri-logo-txt').length).toEqual(1);
  })

  test('when selectedyear and selectedbasecourse not -1' , () => {
    let data  = Object.assign({},MockPlacementData);
    data.selectedBaseCourseId=1;
    data.selectedBaseCourseName="B.A.";
    data.selectedYear=2018;
    const {wrapper} = setup({data:data});
    //expect(wrapper.find('.salry_rslts')).stringContaining('2018 B.A. alumni are employed')
  })

});
