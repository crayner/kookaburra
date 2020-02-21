'use strict'

import React from "react"
import PropTypes from 'prop-types'

export default function Header(props) {
    const {
        details,
    } = props

    let menu = []
    if (Object.keys(details.menu).length > 0) {
        Object.keys(details.menu).map(categoryName => {
            const items = details.menu[categoryName]

            const itemContent = Object.keys(items).map(key => {
                const item = items[key]
                return (<li className="hover:bg-purple-700">
                    <a className="block text-sm text-white focus:text-purple-200 text-left no-underline px-1 py-2 md:py-1 leading-normal"
                       href="{{ checkURL(item) }}">{details.translations[item.name]}</a>
                </li>)
            })

            menu.push(<li className="sm:relative group mt-1">
                    <a className="block uppercase font-bold text-sm text-gray-800 hover:text-purple-500 no-underline px-2 py-3"
                       href="#">{details.translations[categoryName]}</a>
                    <ul className="list-none bg-transparent-900 absolute hidden group-hover:block w-full sm:w-48 left-0 m-0 -mt-1 py-1 sm:p-1 z-50">
                        {itemContent}
                    </ul>
                </li>
            )
        })
    }

    return (
        <div id="headerWrapper">
            <div id="header" className="relative flex bg-white rounded-t items-center justify-between h-24 sm:h-32">
                <a id="header-logo" href={details.homeURL}>
                    <img alt={details.organisationName}
                         src={details.organisationLogo}
                         style={{width: '400px'}} />
                </a>
                <div id="fastFinderWrapper"></div>
            </div>

            <nav id="header-menu" className="relative flex bg-gray-200">
                <ul className="list-none">
                    <li className="pl-2 mt-1" key={'home'}>
                        <a className="block uppercase font-bold text-sm text-gray-800 hover:text-purple-500 no-underline px-2 py-3"
                           href={details.homeURL}>{details.translations.Home}</a>
                    </li>
                    {menu}
                    <li className="notificationTray self-end flex-grow" id="notificationTray" key={'notificationTray'}>

                    </li>
                </ul>
            </nav>
        </div>
    )
}

Header.propTypes = {
    details: PropTypes.object.isRequired,
}
