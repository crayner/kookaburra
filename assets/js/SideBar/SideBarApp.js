'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import SideBarContent from "./SideBarContent"
import Sidebar from "react-sidebar"

const mql = window.matchMedia(`(min-width: 1024px)`);

export default class SideBar extends Component {
    constructor(props) {
        super(props)

        this.content = props.content
        this.minimised = props.minimised

        this.state = {
            sidebarDocked: mql.matches,
            sidebarOpen: false,
            screenWidth: window.innerWidth,
        };

        this.mediaQueryChanged = this.mediaQueryChanged.bind(this);
        this.onSetSidebarOpen = this.onSetSidebarOpen.bind(this);
    }

    componentWillMount() {
        mql.addEventListener('change',this.mediaQueryChanged);
    }

    componentWillUnmount() {
        mql.removeEventListener('change', this.mediaQueryChanged);
    }

    onSetSidebarOpen(open) {
        this.setState({ sidebarOpen: open });
    }

    mediaQueryChanged() {
        let state = {
            sidebarDocked: mql.matches,
            sidebarOpen: false,
            screenWidth: window.innerWidth
        }
        this.setButtonStyle(state)
        this.setState(state)
    }

    setButtonStyle(state) {
        console.log(state)
        let x = {
            float: 'right',
            display: state.sidebarOpen ||state.sidebarDocked ? 'none' : 'flex',
        }
        let e = document.getElementById('content')
        console.log(e)
        if (x.display === 'flex') {
            e.style.paddingRight = '24px'
        } else {
            e.style.paddingRight = '274px'
        }
        return x
    }

    setSideBarStyle() {
        let x = {
            border: '0 solid #d2d0d0',
            backgroundColor: 'hsla(0,0%,100%,0.9)',
            width: '250px',
            paddingLeft: '15px',
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
                    sidebar: this.setSideBarStyle()
                }}
            ><button style={this.setButtonStyle(this.state)} onClick={() => this.onSetSidebarOpen(true)}>
               <span className={'fas fa-bars fa-fw fa-2x'}/>
            </button></Sidebar>
        )
    }
}

SideBar.propTypes = {
    minimised: PropTypes.bool.isRequired,
    content: PropTypes.object.isRequired,
}


