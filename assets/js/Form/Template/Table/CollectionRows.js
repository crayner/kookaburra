'use strict'

import React from "react"
import PropTypes from 'prop-types'
import {widgetAttr} from "../../buildAttr"
import CollectionHeaderRow from "./CollectionHeaderRow"
import Widget from "../../Widget"
import Messages from "../../../component/Messages"

export default function CollectionRows(props) {
    const {
        form,
        functions,
        errors,
    } = props

    let table_attr = widgetAttr(form, 'fullWidth formTable fullWidth colorOddEven')
    delete table_attr.name

    const header = (<CollectionHeaderRow
        form={form}
        functions={functions} />
        )

    let rows = []
    form.children.map((row, rowKey) => {
        let columns = []
        let hidden = []
        Object.keys(row.children).map(childKey => {
            let child = {...row.children[childKey]}
            if (child.type !== 'hidden') {
                columns.push(<td key={childKey}><Widget form={{...child}} functions={functions}/></td>)
            } else {
                hidden.push(<Widget form={{...child}} functions={functions} key={childKey}/>)
            }
        })

        let buttons = []
        if (form.allow_delete) {
            buttons.push(<button onClick={() => functions.deleteElement(row)} className={'button text-gray-800'} type={'button'} key={'one'}><span className={'far fa-trash-alt fa-fw'}></span></button>)
        }

        columns.push(<td key={'actions'}>{hidden}<div>{buttons}</div></td>)

        rows.push(<tr key={rowKey}>{columns}</tr>)
    })

    return (
        <div className={'collection'}>
            <Messages messages={errors} />
            <table {...table_attr}>
                {header}
                <tbody>
                    {rows}
                </tbody>
            </table>
        </div>)

}

CollectionRows.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
    errors: PropTypes.array,
}

CollectionRows.defaultProps = {
    errors: [],
}