'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import Row from "./Row"

export default class SectionRows extends Component {
    constructor (props) {
        super(props)
        this.rows = props.rows
    }

    render() {
        const content = Object.keys(this.rows).map(key => {
            const row = this.rows[key]

            return <Row row={row} key={key} />
        })

        return (
            {content}
        )
    }
}

SectionRows.propTypes = {
    rows: PropTypes.object.isRequired,
}
