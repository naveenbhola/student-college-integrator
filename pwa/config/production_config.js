module.exports = {
	PROTOCOL : 'https://',
	EXAM_SERVICE_PORT : 9005,
	LISTING_SERVICE_PORT : 9002,
	PRED_SERVICE_PORT : 9012,
	DEFAULT_PORT: 9014,
	API_SERVER : 'apis.shiksha.jsb9.net',
	API_SERVER_CALL : true,
	CLP_PRODUCT_ID : '0010004',
	get SHIKSHA_HOME() { return this.PROTOCOL+'www.shiksha.com'},
	get BEACON_SHIKSHA_HOME() { return this.PROTOCOL+'track.shiksha.com';},
	get SHIKSHA_STUDYABROAD_HOME() { return this.PROTOCOL+'studyabroad.shiksha.com'},
	get SHIKSHA_ASK_HOME() { return this.PROTOCOL+'ask.shiksha.com'},
	get BEACON_TRACK_URL() {return this.BEACON_SHIKSHA_HOME+"/beacon/Beacon/track"},
	get BEACON_INDEX_TRACK_URL() {return this.BEACON_SHIKSHA_HOME+"/beacon/Beacon/index"},
	get IMAGES_SHIKSHA() {return this.PROTOCOL+'images.shiksha.com'},
	get COOKIE_DOMAIN() {return '.shiksha.com'},
        get JS_DOMAIN() {return this.PROTOCOL+'js.shiksha.ws'},
        get CSS_DOMAIN() {return this.PROTOCOL+'css.shiksha.ws'},
	get IMG_DOMAIN() {return this.PROTOCOL+'images.shiksha.ws'}
}



