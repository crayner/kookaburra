'use strict';

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import {Helmet} from "react-helmet/lib/Helmet"
import MinorLinks from "./MinorLinks"
import Header from "./Header"
import Content from "./Content"
import Footer from "./Footer"
import SideBar from "../SideBar/SideBarApp"

export default class PageApp extends Component {
    constructor (props) {
        super(props)
        this.locale = props.locale
        this.rtl = props.rtl
        this.bodyImage = props.bodyImage
        this.headerDetails = props.headerDetails
        this.action = props.action
        this.module = props.module
        this.url = props.url
        this.route = props.route
        this.footer = props.footer
        this.minorLinks = props.minorLinks

        console.log(this)
    }

    getTitle() {
        const translations = this.headerDetails.translations
        let title = translations.Kookaburra
        if (this.headerDetails.organisationName.length > 0 ) {
            title += ' - ' + this.headerDetails.organisationName
            if (Object.keys(this.action).length > 0)
            {
                title += ' - ' + this.action.name
            }
        }

        return title
    }

    getContent() {
        let content = []
        content.push(<Helmet key={'helmet'}>
            <title>{this.getTitle()}</title>
            <meta http-equiv="content-language" content={this.locale} />
            {this.rtl ? <head dir={'rtl'}></head> : <head/>}
            <body style={"background: url('" + this.bodyImage + "') repeat fixed center top olivedrab!important"}></body>
        </Helmet>)
        content.push(<MinorLinks links={this.minorLinks} key={'minorLinks'} />)
        content.push(<div id={'wrap'} className={'max-w-6xl mx-auto m-2 shadow rounded min-h-screen'} key={'wrap'}>
            <Header details={this.headerDetails} />
            <div id={'content-wrap'} className={'relative w-full min-h-1/2 flex content-start flex-wrap lg:flex-no-wrap lg:flex-row-reverse bg-transparent-100 clearfix min-h-full'}>
                <Content action={this.action} url={this.url} />
            </div>
            <Footer details={this.footer} />
        </div>)

        return content
    }

    render () {
        return (this.getContent())
    }
}

PageApp.propTypes = {
    locale: PropTypes.string,
    rtl: PropTypes.bool,
    action: PropTypes.oneOfType([
        PropTypes.object,
        PropTypes.array,
    ]),
    module: PropTypes.oneOfType([
        PropTypes.object,
        PropTypes.array,
    ]),
    bodyImage: PropTypes.string.isRequired,
    minorLinks: PropTypes.array.isRequired,
    footer: PropTypes.object.isRequired,
    headerDetails: PropTypes.object.isRequired,
    url: PropTypes.string.isRequired,
    minimised: PropTypes.bool.isRequired,
    sidebar: PropTypes.oneOfType([
        PropTypes.object,
        PropTypes.array,
    ]).isRequired,
}

PageApp.defaultProps = {
    locale: 'en_GB',
    rtl: false,
    action: {},
    module: {},
    sidebar: {},
    minimised: true,
}

