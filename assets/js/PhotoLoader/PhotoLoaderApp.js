'use strict'

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import RenderPeople from "./RenderPeople"
import {fetchJson} from "../component/fetchJson"
import {buildState, changeFormValue, getParentFormName, mergeParentForm} from "../Container/ContainerFunctions"

export default class PhotoLoaderApp extends Component {
    constructor (props) {
        super(props)
        this.people = props.people
        this.messages = props.messages
        this.absolute_url = props.absolute_url

        this.selectPerson = this.selectPerson.bind(this)
        this.addMessage = this.addMessage.bind(this)
        this.validateImage = this.validateImage.bind(this)
        this.replacePerson = this.replacePerson.bind(this)
        this.removePhoto = this.removePhoto.bind(this)

        this.state = {
            chosen: {},
            messages: [],
        }
    }

    addMessage(message, status)
    {
        message = {
            status: status,
            message: message
        }
        let messages = this.state.messages
        messages.push(message)
        this.setState({
            messages: messages,
        })
        setTimeout(() => {
            this.setState({
                messages: []
            })
        }, 5000)
    }

    selectPerson({target} = e) {
        let person = {}
        let found = false
        const value = parseInt(target.value, 10) > 0 ? parseInt(target.value, 10) : 0
        Object.keys(this.people).map(group => {
            if (found) return
            const groupData = this.people[group]
            Object.keys(groupData).map(name => {
                if (found) return
                const x = groupData[name]
                if(x.id === value) {
                    found = true
                    person = {...x}
                }
            })
        })

        this.setState({
            chosen: person,
            messages: [],
        })
    }

    validateImage({meta} = filexhrmeta) {
        if (meta.height > 480 || meta.height < 240 || meta.width > 360)
            return true
        const ratio = meta.width / meta.height
        if (ratio < 0.7 || ratio > 0.84)
            return true
        return false
    }

    replacePerson(person){
        let found = false
        const value =person.id
        Object.keys(this.people).map(group => {
            if (found) return
            const groupData = this.people[group]
            Object.keys(groupData).map(name => {
                if (found) return
                const x = groupData[name]
                if(x.id === value) {
                    found = true
                    this.people[group][name] = {...person}
                }
            })
        })

        this.setState({
            chosen: person,
        })
    }

    removePhoto(person){
        let url = this.absolute_url + '/user/admin/personal/photo/{person}/remove/'
        url = url.replace('{person}', person.id)
        fetchJson(url,
            {},
            false
            ).then(data => {
                console.log(data)
                if (data.status === 'success') {
                    this.replacePerson(data.person)
                } else if (data.status === 'error') {
                    this.addMessage(data.message, 'error')
                }
            }).catch(error => {
                this.addMessage(error, 'error')
            })

    }

    render () {
        let x = 0
        const messages = this.state.messages.map(message => {
            x = x + 1
            return <div className={message.status} key={x}>{message.message}</div>
        })

        return (
            <div>
                {messages}
                <RenderPeople people={this.people} chosen={this.state.chosen} selectPerson={this.selectPerson} addMessage={this.addMessage} validateImage={this.validateImage} replacePerson={this.replacePerson} removePhoto={this.removePhoto} messages={this.messages} absolute_url={this.absolute_url} />
            </div>
        )
    }
}

PhotoLoaderApp.propTypes = {
    people: PropTypes.object.isRequired,
    messages: PropTypes.object.isRequired,
    absolute_url: PropTypes.string.isRequired,
}
