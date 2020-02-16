'use strict'

import React from "react"
import PropTypes from 'prop-types'
import Parser from "html-react-parser"
import Login from "./LoginApp"

export default function SideBarContent(props) {
    const {
        content,
    } = props

    let result = []
    Object.keys(content).map(name => {
        let item = content[name]

        if (item.name === 'Login') {
            result.push(<Login login={item.login} googleOAuth={item.googleOAuth} translations={item.translations} key={'Login'} />)
        } else if (item.name !== 'Module Menu' && item.content !== '') {
            let x = Parser(item.content)
            if (typeof x._owner === 'object') {
                result.push(<div className="column-no-break" key={item.name}>x</div>)
                return
            }
            let y = x.filter(item => {
                if (typeof item === 'object')
                    return item
            })

            result.push(<div className="column-no-break" key={item.name}>{y}</div>)
        }
    })

    return (<section>{result}</section>)
}

SideBarContent.propTypes = {
    content: PropTypes.object.isRequired,
}