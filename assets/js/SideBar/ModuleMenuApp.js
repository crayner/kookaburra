'use strict'

import React from "react"
import PropTypes from 'prop-types'
import MenuItems from "./MenuItems"

export default function ModuleMenu(props) {
    const {
        data,
    } = props

    function menuGroups() {
        return Object.keys(data).map(key => {
            const title = data[key][0].category
            let classList = 'w-full absolute bg-white border list-none column-1 sm:column-2 md:column-3 m-0 pt-6 px-6 shadow-lg  lg:bg-transparent lg:border-0 lg:column-1 lg:shadow-none lg:p-0'
            return (
                <ul key={key} className={classList} style={{maxWidth: '220px', display: 'contents'}}>
                    <li className="column-no-break p-0 ">
                        <h5 className="column-no-break p-0 ">{ title }</h5>
                        <MenuItems
                            items={data[key]}
                        />
                    </li>
                </ul>
            )
        })
    }

    return (<nav id="navigation">
            {menuGroups()}
        </nav>
    )
}

ModuleMenu.propTypes = {
    data: PropTypes.object.isRequired,
}