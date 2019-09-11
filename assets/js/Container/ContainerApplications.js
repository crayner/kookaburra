'use strict'

let Applications = {};

Applications['DepartmentEdit'] = require('../Department/DepartmentEditApp').default;
Applications['ThirdParty'] = require('../SystemAdmin/ThirdPartyApp').default;
Applications['NotificationEvent'] = require('../SystemAdmin/NotificationEventApp').default;

export default Applications