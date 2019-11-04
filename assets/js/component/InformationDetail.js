'use strict';

import React from "react"
import PropTypes from 'prop-types'
import Parser from "react-html-parser"

export default function InformationDetail(props) {
    const {
        messages,
        cancel,
        information,
    } = props

    if (information === false)
        return (<div id="informationDisplay" className={'overlay'}><div className="popup"></div></div>)

    return (
        <div id="informationDisplay" className={'overlay target'}>
            <div className="popup">
                <a className="close" onClick={() => cancel()} title={messages['Close']} href="#"><span className="far fa-times-circle fa-fw"></span></a>
                <h3>{information.header}</h3>
                <div className="content">
                    {Parser(information.content)}
                </div>
            </div>
        </div>
    )
}

InformationDetail.propTypes = {
    messages: PropTypes.object.isRequired,
    cancel: PropTypes.func.isRequired,
    information: PropTypes.oneOfType([
        PropTypes.bool,
        PropTypes.object,
    ]).isRequired,
}
