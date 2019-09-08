'use strict'

import {fetchJson} from "./fetchJson"

export function deleteFile(form) {
    let route = '/resource/' + btoa(form.value) + '/' + this.actionRoute + '/delete/'
    if (typeof form.delete_security !== 'undefined' && form.delete_security !== false)
        route = '/resource/' + btoa(form.value) + '/' + form.delete_security + '/delete/'
    fetchJson(
        route,
        {},
        false)
        .then(data => {
            return data
        }).catch(error => {
            let errors = []
            errors.push({'class': 'error', 'message': error})
            return {
                status: 'error',
                errors: errors,
            }
    })
}
