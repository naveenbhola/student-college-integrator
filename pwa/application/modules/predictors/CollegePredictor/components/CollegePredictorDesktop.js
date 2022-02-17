import CollegePredictorHOC from './CollegePredictorHOC';
import CollegePredictor from './CollegePredictorForms';
let desktopProps = {
	deviceType : 'desktop',
	gaTrackingCategory : 'College_Predictor_Desktop'
};
export default CollegePredictorHOC(CollegePredictor, {...desktopProps});