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

    let table_attr = widgetAttr(form, 'leftIndent smallIntBorder standardForm striped', {})
    delete table_attr.name

    const header = (<CollectionHeaderRow
        form={form}
        functions={functions} />
        )

    let rows = []
    Object.keys(form.children).map(rowKey => {
        let row = form.children[rowKey]
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

        columns.push(<td key={'actions'}>{hidden}<div className={'text-center'}>{buttons}</div></td>)

        rows.push(<tr key={rowKey}>{columns}</tr>)
    })

    rows.push(<tr key={'addRow'}>
        <td colSpan={functions.getColumnCount() - 1}></td>
        <td><div className={'text-center'}>
            <button onClick={() => functions.addElement(form)} className={'button text-gray-800'} type={'button'} key={'one'}><span className={'fas fa-plus-circle fa-fw'}></span></button>
        </div></td>
    </tr>)

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