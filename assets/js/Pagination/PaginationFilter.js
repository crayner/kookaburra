'use strict'

import React from "react"
import PropTypes from 'prop-types'

export default function PaginationFilter(props) {
    const {
        changeFilter,
        filters,
        filter,
        messages,
    } = props

    if (Object.keys(filters).length === 0)
        return ('')

    let key = 0
    let filterOptions = Object.keys(filters).map(name => {
        let value = filters[name]
        return (<option value={value.name}>{value.label}</option>)
    })
    filterOptions.unshift(<option value={''}>{messages['Filter']}</option>)

    return (<select id={'filter_select'} style={{float: 'left', margin: '-3px 20px 5px 0', maxHeight: '20px'}} value={filter} onChange={(e) => changeFilter(e)}>{filterOptions}</select>)
}

PaginationFilter.propTypes = {
    filter: PropTypes.string.isRequired,
    filters: PropTypes.object.isRequired,
    changeFilter: PropTypes.func.isRequired,
    messages: PropTypes.object.isRequired,
}

