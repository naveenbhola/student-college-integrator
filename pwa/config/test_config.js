module.exports = {
	PROTOCOL : 'https://',
	EXAM_SERVICE_PORT : 9005,
	LISTING_SERVICE_PORT : 9002,
	PRED_SERVICE_PORT : 9012,
	DEFAULT_PORT: 9014,
	API_SERVER : 'http://172.16.3.107',
	API_SERVER_CALL : false,
	CLP_PRODUCT_ID : '0010004',
	get SHIKSHA_HOME() { return this.PROTOCOL+'localshiksha.com';},
	get SHIKSHA_STUDYABROAD_HOME() { return this.PROTOCOL+'studyabroad.shikshatest01.infoedge.com'},
	get SHIKSHA_ASK_HOME() { return this.PROTOCOL+'ask.shikshatest01.infoedge.com'},
	get BEACON_TRACK_URL() {return this.SHIKSHA_HOME+"/beacon/Beacon/track"},
	get BEACON_INDEX_TRACK_URL() {return this.SHIKSHA_HOME+"/beacon/Beacon/index"},
	get IMAGES_SHIKSHA() {return this.PROTOCOL+'images.shiksha.com'},
	get COOKIE_DOMAIN() {return ''},
	get JS_DOMAIN() {return ''},
	get CSS_DOMAIN() {return ''},
	get IMG_DOMAIN() {return ''}

}