/**
 * React Google Analytics Module
 *
 * @package official react-ga
 * 
 */

/**
 * Utilities
 */
import loadGA from './loadGA';

let _debug = false;
let _testMode = false;


/* GA strings need to have leading/trailing whitespace trimmed, and not all
 browsers have String.prototoype.trim(). */

function trim(s) {
  return s.replace(/^\s+|\s+$/g, '');
}

const internalGa = (...args) => {
  if (_testMode) return;
  if (!window.ga) return console.warn('ReactGA.initialize must be called first or GoogleAnalytics should be loaded manually');
  return window.ga(...args);
};

function _gaCommand(trackerNames, ...args) {
  const command = args[0];
  if (typeof internalGa === 'function') {
    if (typeof command !== 'string') {
      console.warn('ga command must be a string');
      return;
    }

    internalGa(...args);
    if (Array.isArray(trackerNames)) {
      trackerNames.forEach((name) => {
        internalGa(...[`${name}.${command}`].concat(args.slice(1)));
      });
    }
  }
}

function _initialize(gaTrackingID, options) {
  if (!gaTrackingID) {
    console.warn('gaTrackingID is required in initialize()');
    return;
  }

  if (options) {
    if (options.debug && options.debug === true) {
      _debug = true;
    }
  }

  if (options && options.gaOptions) {
    internalGa('create', gaTrackingID, options.gaOptions);
  } else {
    internalGa('create', gaTrackingID, 'auto');
  }
}


export function initialize(configsOrTrackingId, options) {
  if (options && options.testMode === true) {
    _testMode = true;
  } else {
    if (typeof window === 'undefined') {
      return false;
    }

    // loadGA(options);
  }

  if (Array.isArray(configsOrTrackingId)) {
    configsOrTrackingId.forEach((config) => {
      if (typeof config !== 'object') {
        console.warn('All configs must be an object');
        return;
      }
      _initialize(config.trackingId, config);
    });
  } else {
    _initialize(configsOrTrackingId, options);
  }
  return true;
}

/**
 * ga:
 * Returns the original GA object.
 */
export function ga(...args) {
  if (args.length > 0) {
    internalGa(...args);
    if (_debug) {
      console.log('called ga(\'arguments\');');
      console.log(`with arguments: ${JSON.stringify(args)}`);
    }
  }

  return window.ga;
}

/**
 * set:
 * GA tracker set method
 * @param {Object} fieldsObject - a field/value pair or a group of field/value pairs on the tracker
 * @param {Array} trackerNames - (optional) a list of extra trackers to run the command on
 */
export function set(fieldsObject, trackerNames) {
  if (!fieldsObject) {
    console.warn('`fieldsObject` is required in .set()');
    return;
  }

  if (typeof fieldsObject !== 'object') {
    console.warn('Expected `fieldsObject` arg to be an Object');
    return;
  }

  if (Object.keys(fieldsObject).length === 0) {
    console.warn('empty `fieldsObject` given to .set()');
  }

  _gaCommand(trackerNames, 'set', fieldsObject);

  if (_debug) {
    console.log('called ga(\'set\', fieldsObject);');
    console.log(`with fieldsObject: ${JSON.stringify(fieldsObject)}`);
  }
}

/**
 * send:
 * Clone of the low level `ga.send` method
 * console.warnING: No validations will be applied to this
 * @param  {Object} fieldObject - field object for tracking different analytics
 * @param  {Array} trackerNames - trackers to send the command to
 * @param {Array} trackerNames - (optional) a list of extra trackers to run the command on
 */
export function send(fieldObject, trackerNames) {
  _gaCommand(trackerNames, 'send', fieldObject);
  if (_debug) {
    console.log('called ga(\'send\', fieldObject);');
    console.log(`with fieldObject: ${JSON.stringify(fieldObject)}`);
    console.log(`with trackers: ${JSON.stringify(trackerNames)}`);
  }
}

/**
 * pageview:
 * Basic GA pageview tracking
 * @param  {String} path - the current page page e.g. '/about'
 * @param {Array} trackerNames - (optional) a list of extra trackers to run the command on
 * @param {String} title - (optional) the page title e. g. 'My Website'
 */
export function pageview(rawPath, trackerNames, title) {
  if (!rawPath) {
    console.warn('path is required in .pageview()');
    return;
  }

  const path = trim(rawPath);
  if (path === '') {
    console.warn('path cannot be an empty string in .pageview()');
    return;
  }

  const extraFields = {};
  if (title) {
    extraFields.title = title;
  }

  if (typeof ga === 'function') {
    _gaCommand(trackerNames, 'send', {
      hitType: 'pageview',
      page: path,
      ...extraFields
    });

    if (_debug) {
      console.log('called ga(\'send\', \'pageview\', path);');
      let extralog = '';
      if (title) {
        extralog = ` and title: ${title}`;
      }
      console.log(`with path: ${path}${extralog}`);
    }
  }
}
/**
 * timing:
 * GA timing
 * @param args.category {String} required
 * @param args.variable {String} required
 * @param args.value  {Int}  required
 * @param args.label  {String} required
 * @param {Array} trackerNames - (optional) a list of extra trackers to run the command on
 */
export function timing({ category, variable, value, label } = {}, trackerNames) {
  if (typeof ga === 'function') {
    if (!category || !variable || !value || typeof value !== 'number') {
      console.warn('args.category, args.variable ' +
            'AND args.value are required in timing() ' +
            'AND args.value has to be a number');
      return;
    }

    // Required Fields
    const fieldObject = {
      hitType: 'timing',
      timingCategory: category,
      timingVar: variable,
      timingValue: value
    };

    if (label) {
      fieldObject.timingLabel = label;
    }

    send(fieldObject, trackerNames);
  }
}

/**
 * event:
 * GA event tracking
 * @param args.category {String} required
 * @param args.action {String} required
 * @param args.label {String} optional
 * @param args.value {Int} optional
 * @param args.nonInteraction {boolean} optional
 * @param {Array} trackerNames - (optional) a list of extra trackers to run the command on
 */
export function event({ category, action, label, value, nonInteraction, transport, ...args } = {}, trackerNames) {
  if (typeof ga === 'function') {
    // Simple Validation
    if (!category || !action) {
      console.warn('args.category AND args.action are required in event()');
      return;
    }

    // Required Fields
    const fieldObject = {
      hitType: 'event',
      eventCategory: category,
      eventAction: action
    };

    // Optional Fields
    if (label) {
      fieldObject.eventLabel = label;
    }

    if (typeof value !== 'undefined') {
      if (typeof value !== 'number') {
        console.warn('Expected `args.value` arg to be a Number.');
      } else {
        fieldObject.eventValue = value;
      }
    }

    if (typeof nonInteraction !== 'undefined') {
      if (typeof nonInteraction !== 'boolean') {
        console.warn('`args.nonInteraction` must be a boolean.');
      } else {
        fieldObject.nonInteraction = nonInteraction;
      }
    }

    if (typeof transport !== 'undefined') {
      if (typeof transport !== 'string') {
        console.warn('`args.transport` must be a string.');
      } else {
        if (['beacon', 'xhr', 'image'].indexOf(transport) === -1) {
          console.warn('`args.transport` must be either one of these values: `beacon`, `xhr` or `image`');
        }

        fieldObject.transport = transport;
      }
    }

    Object.keys(args)
      .filter(key => key.substr(0, 'dimension'.length) === 'dimension')
      .forEach((key) => {
        fieldObject[key] = args[key];
      });

    Object.keys(args)
      .filter(key => key.substr(0, 'metric'.length) === 'metric')
      .forEach((key) => {
        fieldObject[key] = args[key];
      });

    // Send to GA
    send(fieldObject, trackerNames);
  }
}


export default {
  initialize,
  ga,
  set,
  send,
  pageview,
  timing,
  event
};
