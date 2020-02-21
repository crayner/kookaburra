'use strict'

import React, { Component } from "react"
import PropTypes from 'prop-types'
import {fetchJson} from "../component/fetchJson"
import Parser from "html-react-parser"
import Sidebar from "../SideBar/SideBarApp"

export default class Content extends Component {
    constructor (props) {
        super(props)
        this.action = props.action
        this.url = props.url

        this.state = {
            content: [],
            minimised: false,
            sidebar: {},
        }
    }

    componentDidMount() {
        this.getContent()
    }

    getContent() {
        fetchJson(
            this.url,
            [],
            false
        ).then(data => {
            console.log(data)
            this.setState({
                content: Parser(data.content),
                sidebar: data.sidebar,
                minimised: data.minimised,
            })
        })
    }

    buildContent() {
        let content = []
        if (Object.keys(this.state.sidebar).length > 0)
            content.push(<Sidebar key={'sidebar'} minimised={this.state.minimised} content={this.state.sidebar} />)
        content.push(<div key={'content'} id="content" className="w-full lg:flex-1 p-6 lg:pt-0 overflow-x-scroll sm:overflow-x-auto min-h-full">
            {this.state.content}
            </div>)
        return content
    }

    render () {
        return (this.buildContent())
    }
}

Content.propTypes = {
    action: PropTypes.oneOfType([
        PropTypes.object,
        PropTypes.array,
    ]),
    url: PropTypes.string.isRequired,
}

Content.defaultProps = {
    action: {},
}

