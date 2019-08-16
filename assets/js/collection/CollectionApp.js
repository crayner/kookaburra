'use strict';

import React, { Component } from 'react'
import CollectionRender from "./CollectionRender"

export default class CollectionApp extends Component {
    constructor(props) {
        super(props)
        this.otherProps = {...props}
    }

    render () {
        return (<CollectionRender {...this.otherProps} />
        )
    }

}

