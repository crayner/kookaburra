'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import IdleTimer from 'react-idle-timer'
import {openPage} from "../component/openPage"

export default class IdleTimeoutApp extends Component {
    constructor (props) {
        super(props)
        this.idleTimer = null
        this.state = {
            timeout: 1000 * props.duration,
            remaining: null,
            lastActive: null,
            elapsed: null,
            display: false,
        }
        // Bind event handlers and methods
        this.onActive = this._onActive.bind(this)
        this.onIdle = this._onIdle.bind(this)
        this.reset = this._reset.bind(this)
        this.changeTimeout = this._changeTimeout.bind(this)
        this.route = props.route
    }

    componentDidMount () {
        if (this.idleTimer !== null) {
            this.setState({
                remaining: this.idleTimer.getRemainingTime(),
                lastActive: this.idleTimer.getLastActiveTime(),
                elapsed: this.idleTimer.getElapsedTime(),
            })

            setInterval(() => {
                this.setState({
                    remaining: this.idleTimer.getRemainingTime(),
                    lastActive: this.idleTimer.getLastActiveTime(),
                    elapsed: this.idleTimer.getElapsedTime(),
                    display: this.state.timeout - this.idleTimer.getElapsedTime() > 30000 ? false : true,
                })
                if (this.wasLastActive !== this.idleTimer.getLastActiveTime())
                    this.refreshPage()
                this.wasLastActive = this.idleTimer.getLastActiveTime()
                if (this.state.elapsed > this.state.timeout)
                    this.logout()
            }, 1000)
        }
    }

    render () {
        return (
            <div>
                <IdleTimer
                    ref={ref => { this.idleTimer = ref }}
                    onActive={this.onActive}
                    onIdle={this.onIdle}
                    timeout={this.state.timeout}
                    throttle={50}
                    startOnLoad
                />
                { this.state.display ?
                    <div style={{position: 'absolute', width: '100%', top: 0, left: 0, height: '100%', background: 'rgba(230, 230, 255, 0.5)', zIndex: 99999 }}>
                        <div style={{position: 'relative', width: '100%', top: 0, left: 0, height: '100%' }}>
                            <div className="" style={{background: "peachpuff", maxWidth: '325px', position: 'absolute',top: '50%', left: '50%', transform: 'translate(-50%,-50%)', borderRadius: "5px" }}>
                                <div className={'w-full'} style={{padding: '2rem'}}>
                                    <div className={''} style={{borderRadius: "5px"}}>
                                        <img style={{float: 'right', marginTop: '-40px'}} src={'/build/static/kookaburra.png'} height={50} />
                                        <h3>Kookaburra</h3>
                                        <span className={'warning'}>{ this.props.trans_sessionExpire }</span>
                                    </div>
                                </div>
                                <div className={'w-full'} style={{paddingBottom: '2rem'}}>
                                    <div className={'w-full max-w-full sm:max-w-xs flex justify-end items-center px-2 border-b-0 sm:border-b border-t-0 content-centre'}>
                                        <input className={'btn-gibbon'} type={'button'} onClick={() => this.reset} value={ this.props.trans_stayConnected } />&nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    :  '' }
            </div>
        )
    }


    refreshPage(){
        if (this.state.elapsed > this.state.timeout)
            this.logout()
        this.reset()
    }

    _onActive () {
        this.refreshPage()
    }

    _onIdle () {
    }

    _changeTimeout () {
        this.setState({
            timeout: this.refs.timeoutInput.state.value(),
        })
    }

    _reset () {
        this.idleTimer.reset()
        this.setState({
            display: false,
            lastActive: Date.now(),
        })
    }

    logout () {
        openPage(this.route, {method: 'GET'}, false)
    }
}



IdleTimeoutApp.propTypes = {
    route: PropTypes.string.isRequired,
    duration: PropTypes.number.isRequired,
}