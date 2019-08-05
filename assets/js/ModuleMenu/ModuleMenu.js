'use strict'

import React, { Component } from 'react'
import MenuItems from "./MenuItems"

export default class ModuleMenu extends Component {
    constructor(props) {
        super(props)

        this.buttonClassShow = 'w-full flex justify-center items-center sm:w-48 bg-white border border-grey-600 border-solid p-2 mt-16 sm:mt-4 sm:absolute sm:top-0 sm:right-0 sm:mr-6 z-40 lg:hidden active'
        this.navigationClassShow = 'w-full absolute top-0 z-40 mt-24 sm:mt-12 -ml-6 px-6 lg:block lg:static lg:p-0 lg:my-6 lg:mx-0'
        this.buttonClass = 'w-full flex justify-center items-center sm:w-48 bg-white border border-grey-600 border-solid p-2 mt-16 sm:mt-4 sm:absolute sm:top-0 sm:right-0 sm:mr-6 z-40 lg:hidden'
        this.navigationClass = 'w-full hidden absolute top-0 z-40 mt-24 sm:mt-12 -ml-6 px-6 lg:block lg:static lg:p-0 lg:my-6 lg:mx-0'

        this.state = {
            buttonClass: this.buttonClass,
            navigationClass: this.navigationClass,
            show: false,
        }

        this.menuGroups = this.menuGroups.bind(this)
        this.toggleButton = this.toggleButton.bind(this)
    }

    menuGroups() {
        return Object.keys(this.props.data).map(key => {
            const title = this.props.data[key][0].category
            return (
                <ul key={key} className={'w-full bg-white border list-none column-1 sm:column-2 md:column-3 m-0 pt-6 px-6 shadow-lg lg:bg-transparent lg:border-0 lg:column-1 lg:shadow-none lg:p-0'}>
                    <li className="w-full column-no-break p-0 ">
                        <h5 className="w-full column-no-break p-0 ">{ title }</h5>
                        <MenuItems
                            items={this.props.data[key]}
                        />
                        { this.state.width }
                    </li>
                </ul>
            )
        })
    }

    toggleButton() {
        if (this.state.show) {
            this.setState({
                show: false,
                buttonClass: this.buttonClass,
                navigationClass: this.navigationClass,
            })
        } else {
            this.setState({
                show: true,
                buttonClass: this.buttonClassShow,
                navigationClass: this.navigationClassShow,
            })
        }
    }

    render () {
        return (<section>
            <button className={ this.state.buttonClass } onClick={this.toggleButton}>
                <span className="text-gray-600 text-sm sm:text-xs font-bold uppercase" title={ this.props.trans_module_menu }>{ this.props.trans_module_menu }&nbsp;<span className="fas fa-bars fa-fw"></span></span>
            </button>

            <nav id="navigation" className={ this.state.navigationClass }>
                { this.menuGroups() }
            </nav>
        </section>)
    }
}
