'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import SideBarContent from "./SideBarContent"

export default class SideBar extends Component {
    constructor(props) {
        super(props)

        this.content = props.content
        this.minimised = props.minimised

        this.buttonClassShow = 'w-full flex justify-center items-center sm:w-48 bg-white border border-grey-600 border-solid p-2 mt-16 sm:mt-4 sm:absolute sm:top-0 sm:right-0 sm:mr-6 z-40 active'
        this.buttonClassShow = this.buttonClassShow + (!this.minimised ? ' lg:hidden' : '')
        this.sideBarClassShow = 'w-full absolute top-0 z-40 mt-24 sm:mt-12 -ml-6 px-6'
        this.sideBarClassShow = this.sideBarClassShow + (!this.minimised ? ' lg:block lg:static lg:p-0 lg:my-6 lg:mx-0' : '')
        this.buttonClass = 'w-full flex justify-center items-center sm:w-48 bg-white border border-grey-600 border-solid p-2 mt-16 sm:mt-4 sm:absolute sm:top-0 sm:right-0 sm:mr-6 z-40'
        this.buttonClass = this.buttonClass + (!this.minimised ? ' lg:hidden' : '')
        this.sideBarClass = 'w-full absolute top-0 z-40 mt-24 sm:mt-12 -ml-6 px-6 hidden lg:block lg:static lg:p-0 lg:my-6 lg:mx-0'

        this.state = {
            minimised: this.minimised,
            buttonClass: this.buttonClass,
            sideBarClass: this.sideBarClass,
        }
    }

    componentDidMount() {
        if (this.state.minimised) {
            this.setState({
                minimised: false,
                buttonClass: this.buttonClassShow,
                sideBarClass: this.sideBarClassShow,
            })
        } else {
            this.setState({
                minimised: true,
                buttonClass: this.buttonClass,
                sideBarClass: this.sideBarClass,
            })
        }
    }

    toggleButton() {
        if (!this.state.minimised) {
            this.setState({
                minimised: true,
                buttonClass: this.buttonClass,
                sideBarClass: this.sideBarClass,
            })
        } else {
            this.setState({
                minimised: false,
                buttonClass: this.buttonClassShow,
                sideBarClass: this.sideBarClassShow,
            })
        }
        console.log(this)
    }
    
    getSideBarWidth()
    {

        if (this.state.minimised)
            return 'widthZero'
        return ''
    }

    render () {
        return (
            <div id={'reactSideBar'}>
                <button className={ this.state.buttonClass } onClick={() => this.toggleButton()}>
                    <span className="text-gray-600 text-sm sm:text-xs font-bold uppercase" title={'Side Bar'}>{' Side Bar '}&nbsp;<span className="fas fa-bars fa-fw"></span></span>
                </button>
                <SideBarContent content={this.content} showComponent={this.state.sideBarClass}/>
            </div>
        )
    }
}

SideBar.propTypes = {
    minimised: PropTypes.bool.isRequired,
    content: PropTypes.object.isRequired,
}


