'use strict';

import React, { Component } from 'react'
import PropTypes from 'prop-types'
import Autosuggest from 'react-autosuggest';

export default class FastFinderApp extends Component {
    constructor (props) {
        super(props)
        this.otherProps = {...props}
        this.state = {}
        console.log(this)
    }

    render () {
        return (
            <div>
                <button data-toggle="#fastFinder"
                        className="flex md:hidden items-center rounded bg-gray-300 mr-4 px-4 py-3 text-base active">
                    <span className="hidden sm:inline text-gray-600 text-xs font-bold uppercase pr-2">{ this.props.trans_fastFind }</span>
                    <img src={'http://gibbon.craigrayner.com/themes/' + this.props.themeName + '/img/search.png'} width="25" height="25"/>
                </button>
                <div id="fastFinder" className="md:block absolute md:static top-0 left-0 w-full md:max-w-md p-2 sm:p-4 hidden">
                    <div className="z-10 rounded border border-solid border-gray-300" style={{backgroundColor: '#fbfbfb'}}>

                        <a data-toggle="#fastFinder" className="p-2 pl-4 float-right text-xs underline md:hidden"
                           href="#">close</a>

                        <div className="py-2 md:py-1 px-2 border-solid border-0 border-b border-gray-300 md:text-right text-gray-700 text-xxs font-bold uppercase">
                            { this.props.trans_fastFind }: { this.props.trans_fastFindActions }
                        </div>

                        <div className="w-full px-2 sm:py-2">
                            <form className={'blank fullWidth'} id={'fastFinder'}>
                            <table className="blank fullWidth relative" cellSpacing="0">
                                <tbody>
                                    <tr>


                                        <td className="px-2 border-b-0 sm:border-b border-t-0 w-full text-white">
                                            <div className="flex-1 relative">
                                                <ul className="token-input-list-facebook">
                                                    <li className="token-input-input-token-facebook">
                                                        <Autosuggest
                                                            id={'token-input-fastFinderSearch'}
                                                            style={{border: 'none', outline: 'none', width: '100%'}}
                                                            suggestions={[]}
                                                        />
                                                    </li>
                                                </ul>
                                                <input type="text" id="fastFinderSearch" name="fastFinderSearch"
                                                       className="w-full text-white finderInput" style={{"display": "none"}} />
                                                    </div>


                                        </td>


                                        <td className=" px-2 border-b-0 sm:border-b border-t-0 right">
                                            <input type="submit" value="Go" />

                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                            </form>
                        </div>

                        {this.props.roleCategory === 'Staff' ?
                            <div className="py-1 px-2 text-right text-gray-500 text-xxs font-normal italic">
                                { this.props.trans_enrolmentCount }
                            </div>
                            :''}
                    </div>
                </div>
            </div>
        )
    }
}