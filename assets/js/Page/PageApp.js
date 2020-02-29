'use strict';

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import {Helmet} from "react-helmet/lib/Helmet"
import MinorLinks from "./MinorLinks"
import Header from "./Header"
import Content from "./Content"
import Footer from "./Footer"
import {fetchJson} from "../component/fetchJson"
import Parser from "html-react-parser"

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
        this.height = 0
        this.references = {
            sideBarContentRef: React.createRef('sideBarContent'),
        }
        this.functions = {
            getContent: this.getContentFromServer.bind(this),
            handleAddClick: this.getContentFromServer.bind(this),
            onSetSidebarOpen: this.onSetSidebarOpen.bind(this),
            getContentSize: this.getContentSize.bind(this)
        }
        this.onSetSidebarOpen = this.onSetSidebarOpen.bind(this)
        this.handleClickOffSidebar = this.handleClickOffSidebar.bind(this)
        this.getContentFromServer = this.getContentFromServer.bind(this)

        this.state = {
            contentWidth: 0,
            content: [],
            sidebar: {},
            sidebarOpen: '',
            contentHeight: 0,
        }
    }

    componentDidMount() {
        this.getContentFromServer(this.url)
        window.addEventListener('resize', this.functions.getContentSize, false);
        document.addEventListener('mousedown', this.handleClickOffSidebar, false)

    }

    componentWillUnmount() {
        window.removeEventListener('resize', this.functions.getContentSize, false);
        document.removeEventListener('mousedown', this.handleClickOffSideBar, false)
    }

    getContentSize() {
        let width = document.getElementById('content-wrap')
        width = width ? width.offsetWidth : 0
        let el = document.getElementById('sideBarContent')
        let height = el ? el.offsetHeight + 42 : 0
        let x = 0
        while (height > 800 && x < 10) {
            height = el ? el.offsetHeight + 42 : 0
            x++
        }
        this.setState({
            contentWidth: width,
            contentHeight: height
        })
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

    setNode(e){
        this.node = e
    }

    onSetSidebarOpen(open) {
        this.setState({
            sidebarOpen: open,
        });
        setTimeout(this.functions.getContentSize, 100)
    }

    handleClickOffSidebar(e)
    {
        let node = document.getElementById('sidebar')
        if (node && node.contains(e.target) || e.target.classList.contains('ignore-mouse-down') || e.target.classList.contains('fa-fw'))
            return

        if (e.target.tagName === 'HTML')
            return

        if (e.target.type === 'button')
            return
        console.log(e.target.type)
        this.setState({
            sidebarOpen: 'closed',
        });
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
        content.push(<div id={'wrap'} className={'max-w-6xl mx-auto m-2 shadow rounded'} key={'wrap'}>
            <Header details={this.headerDetails} />
            <div id={'content-wrap'} ref={e => (this.contentRef = e)} className={'relative w-full block content-start flex-wrap lg:flex-no-wrap lg:flex-row-reverse bg-transparent-100 clearfix'}>
                <Content action={this.action} url={this.url} functions={this.functions} {...this.state} references={this.references} />
            </div>
            <Footer details={this.footer} />
        </div>)

        return content
    }

    getContentFromServer(url, options) {
        if (typeof options !== 'object')
            options = {}
        this.setState({
            content: [],
            pagination: {},
            containers: {},
        })
        fetchJson(
            url,
            options,
            false
        ).then(data => {
            this.setState({
                content: Parser(data.content),
                pagination: data.pagination,
                sidebar: data.sidebar,
                breadCrumbs: data.breadCrumbs,
                containers: data.containers,
            })
            window.history.pushState('page2', 'Title', url);
            setTimeout(this.functions.getContentSize,50)
        })
    }


    render () {
        if (this.state.height !== this.height) {
            this.height = this.state.height
            setTimeout(this.functions.getContentSize, 50)
        }

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

