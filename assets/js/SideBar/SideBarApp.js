'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import SideBarContent from "./SideBarContent"
import Sidebar from "react-sidebar"

const mql = window.matchMedia(`(min-width: 1600px)`);

export default class SideBar extends Component {
    constructor(props) {
        super(props)

        this.content = props.content
        this.minimised = props.minimised

        this.state = {
            sidebarDocked: mql.matches,
            sidebarOpen: false
        };

        this.mediaQueryChanged = this.mediaQueryChanged.bind(this);
        this.onSetSidebarOpen = this.onSetSidebarOpen.bind(this);
    }

    componentWillMount() {
        mql.addListener(this.mediaQueryChanged);
    }

    componentWillUnmount() {
        this.state.mql.removeListener(this.mediaQueryChanged);
    }

    onSetSidebarOpen(open) {
        this.setState({ sidebarOpen: open });
    }

    mediaQueryChanged() {
        this.setState({ sidebarDocked: mql.matches, sidebarOpen: false });
    }

    setButtonStyle() {
        let x = {
            float: 'right',
            display: this.state.sidebarOpen || this.state.sidebarDocked ? 'none' : 'flex',
        }

        return x
    }

    render () {
        return (
            <Sidebar
                sidebar={<SideBarContent content={this.content}/>}
                open={this.state.sidebarOpen}
                docked={this.state.sidebarDocked}
                onSetOpen={this.onSetSidebarOpen}
                pullRight={true}
                styles={{
                    sidebar: {
                        border: '0 solid #d2d0d0',
                        backgroundColor: 'hsla(0,0%,100%,0.9)',
                        width: '280px',
                        paddingLeft: '30px',
                    }
                }}
            ><button style={this.setButtonStyle()} onClick={() => this.onSetSidebarOpen(true)}>
               <span className={'fas fa-bars fa-fw fa-2x'}/>
            </button></Sidebar>
        )
    }
}

SideBar.propTypes = {
    minimised: PropTypes.bool.isRequired,
    content: PropTypes.object.isRequired,
}


