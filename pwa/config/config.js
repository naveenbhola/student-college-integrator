var config = require("./" + (process.env.NODE_ENV || "development") + "_config.js");

export default function() {
	return config;
}