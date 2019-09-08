'use strict'

let Applications = {};

Applications['DepartmentEdit'] = require('../Department/DepartmentEditApp').default;
Applications['ThirdParty'] = require('../SystemAdmin/ThirdPartyApp').default;

export default Applications