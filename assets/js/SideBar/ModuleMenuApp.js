'use strict'

import React from "react"
import PropTypes from 'prop-types'
import MenuItems from "./MenuItems"

export default function ModuleMenu(props) {
    const {
        data,
        getContent
    } = props

    function menuGroups() {
        return Object.keys(data).map(key => {
            const title = data[key][0].category
            let classList = 'w-full absolute bg-white border list-none column-1 sm:column-2 md:column-3 m-0 pt-6 px-6 shadow-lg  lg:bg-transparent lg:border-0 lg:column-1 lg:shadow-none lg:p-0'
            classList = ''
            return (
                <ul key={key} className={classList} style={{maxWidth: '14rem', display: 'flex'}}>
                    <li className="column-no-break p-0 ">
                        <h5 className="m-0 mb-1 text-xs pb-0 ">{ title }</h5>
                        <MenuItems
                            items={data[key]}
                            getContent={getContent}
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
    getContent: PropTypes.func.isRequired,
}