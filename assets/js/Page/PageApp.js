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
        this.height = -3
        this.width = -3
        this.translations = props.translations

        this.functions = {
            getContent: this.getContentFromServer.bind(this),
            handleAddClick: this.getContentFromServer.bind(this),
            onSetSidebarOpen: this.onSetSidebarOpen.bind(this),
            getContentSize: this.getContentSize.bind(this),
            translate: this.translate.bind(this)
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
            title: this.action.name,
            messages: []
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

    translate(id) {
        return id
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
                title += ' - ' + this.state.title
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
        setTimeout(this.functions.getContentSize, 150)
    }

    handleClickOffSidebar(e)
    {
        let node = document.getElementById('sidebar')
        if (node && node.contains(e.target) || e.target.classList.contains('ignore-mouse-down') || e.target.classList.contains('fa-fw'))
            return

        if (e.target.tagName === 'HTML')
            return

        if (e.target.tagName === 'A')
            return

        if (e.target.type === 'button')
            return
        if (e.target.id === 'filter_select')
            return
        console.log(e.target)
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
                <Content {...this.state} action={this.action} url={this.url} functions={this.functions} messages={this.state.messages} />
            </div>
            <Footer details={this.footer} />
        </div>)

        return content
    }

    getContentFromServer(url, options) {
        if (typeof options !== 'object')
            options = {}
        let content = []
        content.push(<div key={'loading'} className={'w-full min-h-full'}><img src={'/build/static/ajax-loader.gif'} className={'object-none object-center bg-transparent w-full h-40'}/><h4 className={'text-center'} style={{marginTop: '-60px'}}>{this.translations.Loading + '...'}</h4> </div> )
        this.setState({
            content: content,
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
                title: data.title,
                messages: this.state.messages.concat(data.messages),
            })
            window.history.pushState('', data.title, data.url ? data.url : url);
            setTimeout(this.functions.getContentSize,100)
        })
    }

    render () {
        if (this.state.contentHeight !== this.height) {
            this.height = this.state.contentHeight
            setTimeout(this.functions.getContentSize, 150)
        }

        if (this.state.contentWidth !== this.width) {
            this.width = this.state.contentWidth
            setTimeout(this.functions.getContentSize, 150)
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

