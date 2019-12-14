'use strict'

import React from "react"
import PropTypes from 'prop-types'
import {widgetAttr} from "../../buildAttr"
import CollectionHeaderRow from "./CollectionHeaderRow"
import RowTemplate from "./Row"
import Widget from "../../Widget"
import Messages from "../../../component/Messages"

export default function CollectionRows(props) {
    const {
        form,
        functions,
        columnCount,
    } = props

    let table_attr = widgetAttr(form, 'leftIndent smallIntBorder standardForm striped', {})
    delete table_attr.name
    let errors = form.errors
    const header = (<CollectionHeaderRow
        form={form}
        functions={functions} />
        )


    let rows = []
    if (Object.keys(errors).length > 0) {
        rows.push(<tr key={'errors'}><td colSpan={columnCount}><div className={'errors flex-1 relative'}>{errors}</div></td></tr>)
    }

    if (typeof form.children !== "undefined" && Object.keys(form.children).length > 0) {
        Object.keys(form.children).map(rowKey => {
            let row = form.children[rowKey]
            let columns = []
            let hidden = []
            if (typeof row.children === 'undefined') {
                if (row.type !== 'hidden') {
                    columns.push(<RowTemplate form={{...row}} functions={functions} columns={columnCount}/>)
                } else {
                    hidden.push(<Widget form={{...row}} functions={functions} key={rowKey}/>)
                }

            } else {
                Object.keys(row.children).map(childKey => {
                    let child = row.children[childKey]
                    if (child.type !== 'hidden') {
                        columns.push(<td key={childKey}><Widget form={{...child}} functions={functions}/></td>)
                    } else {
                        hidden.push(<Widget form={{...child}} functions={functions} key={childKey}/>)
                    }
                })
            }
            let buttons = []
            if (form.allow_delete) {
                buttons.push(<button title={functions.translate('Delete')} onClick={() => functions.deleteElement(row)} className={'button text-gray-800'} type={'button'} key={'one'}><span className={'far fa-trash-alt fa-fw'}></span></button>)
            }

            columns.push(<td key={'actions'}>{hidden}
                <div className={'text-center'}>{buttons}</div>
            </td>)

            rows.push(<tr key={rowKey}>{columns}</tr>)
        })
    } else {
        rows.push(<tr key={'emptyWarning'}>
            <td colSpan={columnCount}>
                <div className={'warning'}>{functions.translate('There are no records to display.')}</div>
            </td>
        </tr>)
    }
    if (form.allow_add) {
        rows.push(<tr key={'addRow'}>
            <td colSpan={columnCount - 1}></td>
            <td>
                <div className={'text-center'}>
                    <button title={functions.translate('Add')} onClick={() => functions.addElement(form)}
                            className={'button text-gray-800'} type={'button'} key={'one'}><span
                        className={'fas fa-plus-circle fa-fw'}></span></button>
                </div>
            </td>
        </tr>)
    }

    return (
        <div className={'collection'}>
            <Messages messages={errors} translate={functions.translate} />
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
    columnCount: PropTypes.number.isRequired,
}

CollectionRows.defaultProps = {
    errors: [],
}