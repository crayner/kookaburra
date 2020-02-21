'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import SideBarControl from "./SidebarControl"

const mql = window.matchMedia(`(min-width: 1024px)`);

export default class SideBar extends Component {
    constructor(props) {
        super(props)

        this.content = props.content
        this.minimised = props.minimised

        this.state = {
            sidebarDocked: mql.matches && ! this.minimised,
            sidebarOpen: false,
            screenWidth: window.innerWidth,
        };

        this.functions = {
            onSetSidebarOpen: this.onSetSidebarOpen.bind(this),
        }

        this.mediaQueryChanged = this.mediaQueryChanged.bind(this);
        this.updateWindowDimensions = this.updateWindowDimensions.bind(this);
        this.handleClick = this.handleClick.bind(this)
    }

    componentWillMount() {
        mql.addEventListener('change',this.mediaQueryChanged);
        window.addEventListener('resize', this.updateWindowDimensions);
        document.addEventListener('mousedown', this.handleClick, false)
    }

    componentWillUnmount() {
        mql.removeEventListener('change', this.mediaQueryChanged);
        window.removeEventListener('resize', this.updateWindowDimensions);
        document.removeEventListener('mousedown', this.handleClick, false)
    }

    onSetSidebarOpen(open) {

        this.setState({
            sidebarOpen: open,
            screenWidth: window.innerWidth
        });
    }

    updateWindowDimensions() {
        let state = {...this.state}
        state.screenWidth = window.innerWidth
        this.setState(state)
    }

    mediaQueryChanged() {
        let state = {
            sidebarDocked: mql.matches && !this.minimised,
            sidebarOpen: this.state.sidebarOpen,
            screenWidth: window.innerWidth
        }
        this.setState(state)
    }

    handleClick(e)
    {
        if (this.node.contains(e.target))
            return
        this.onSetSidebarOpen(false)
    }

    render () {
        return (
            <div id={'sidebar'} ref={node => this.node = node} className={'w-full lg:w-sidebar px-6 pb-6 lg:border-l'}>
                <SideBarControl content={this.content} state={this.state} functions={this.functions} />
            </div>
        )
    }
}

SideBar.propTypes = {
    minimised: PropTypes.bool.isRequired,
    content: PropTypes.object.isRequired,
}


