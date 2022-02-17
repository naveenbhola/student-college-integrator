/*import React from 'react';
import { shallow } from 'enzyme';
import Admission from  "./../AdmissionComponent";
import config from './../../../../../../config/config';



const setup = propOverrides => {
  const props = Object.assign({
	admissionDetails: "<p>The Indian Institute of Management, Ahmedabad (IIM A) is India&rsquo;s premier management institute.&nbsp; The institute was set up in 1961 and ever since it has been imparting world-class business and management education.</p> ",
	showAdmissionFlag: true,
	url: "/college/indian-institute-of-management-ahmedabad-vastrapur-307/admission",
	examList: [ 
		{
		name: "DU JAT",
		url: "/university/university-of-delhi-north-campus/du-jat-exam",
		fullName: "Delhi University Joint Admission Test",
		year: 2018,
		},
		{
		name: "DU LLB Entrance Exam",
		url: "/university/university-of-delhi-north-campus/du-llb-entrance-exam",
		fullName: "Delhi University LLB Entrance Exam",
		year: 2019,
		},
		{
		name: "DUET",
		url: "/university/university-of-delhi-north-campus/duet-exam",
		fullName: "Delhi University Entrance Test",
		year: 2018,
		},
	]
  }, propOverrides)

  const wrapper = shallow(<Admission config={config()} admissionData = {props} page='institute' />);

  return {
    props,
    wrapper,
    listingTuple: wrapper.find('.listingTuple'),
    admissionSection: wrapper.find('.adm-DivSec'),
    viewAllLink: wrapper.find('.find-schlrSec .find-schlrSec-inr'),
    ExamData : wrapper.find(".examsoffered a")
  }
}

describe('Main tuple Test', () => {
  test('when All Data present', () => {
    const { listingTuple,admissionSection,viewAllLink,ExamData} = setup({ })
    expect(listingTuple.length).toEqual(1);
    expect(admissionSection.length).toEqual(1);
    expect(viewAllLink.length).toEqual(1);
    expect(ExamData.length).toEqual(3);
  })

  test('when showAdmissionFlag Data false ', () => {
    const { listingTuple,admissionSection,viewAllLink,ExamData} = setup({showAdmissionFlag: false})
    expect(listingTuple.length).toEqual(1);
    expect(admissionSection.length).toEqual(1);
    expect(viewAllLink.length).toEqual(0);
    expect(ExamData.length).toEqual(3);
  })

  test('when admissionDetails Data null', () => {
    const { listingTuple,admissionSection,viewAllLink,ExamData} = setup({admissionDetails: null})
    expect(listingTuple.length).toEqual(1);
    expect(admissionSection.length).toEqual(0);
    expect(viewAllLink.length).toEqual(1);
    expect(ExamData.length).toEqual(3);
  })

  test('when examData is empty', () => {
    const { listingTuple,admissionSection,viewAllLink,ExamData} = setup({examList: [] })
    expect(listingTuple.length).toEqual(1);
    expect(admissionSection.length).toEqual(1);
    expect(viewAllLink.length).toEqual(1);
    expect(ExamData.length).toEqual(0);
  })

});









*/