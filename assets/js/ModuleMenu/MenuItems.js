'use strict'

import React from "react"
import PropTypes from 'prop-types'

export default function MenuItems(props) {
    const {
        items,
    } = props

    const itemsReturn = items.map((item, key) => {
        return (<li className="p-0 leading-normal lg:leading-tight" key={key}>
            <a href={ item.url } className={item.active ? 'active' : '' }>{ item.name }</a>
        </li>)
    })
    return (<ul className="list-none m-0 mb-6">
            {itemsReturn}
        </ul>)
}

MenuItems.propTypes = {
    items: PropTypes.array.isRequired,
}