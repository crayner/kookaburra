'use strict';

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import {Helmet} from "react-helmet/es/Helmet"

export default class PageApp extends Component {
    constructor (props) {
        super(props)
        this.locale = props.locale
        this.rtl = props.rtl
        this.bodyImage = props.bodyImage
        console.log(this.bodyImage)
    }

    render () {
        return (
            <div id={'wrap'} className={'max-w-6xl mx-auto m-2 shadow rounded'}>
                <Helmet>
                    <title>My Plain Title or {`dynamic`} title</title>
                    <meta http-equiv="content-language" content={'en_US'} />
                    {this.rtl ? <head dir={'rtl'}></head> : <head></head>}
                    <body style={"background: url('" + this.bodyImage + "') repeat fixed center top olivedrab!important"}></body>
                </Helmet>
                Nothin' to look at here
            </div>
        )
    }
}

PageApp.propTypes = {
    locale: PropTypes.string,
    rtl: PropTypes.bool,
    bodyImage: PropTypes.string.isRequired,
}

PageApp.defaultProps = {
    locale: 'en_GB',
    rtl: false,
}

