'use strict'

import React from 'react'
import PropTypes from 'prop-types'
import Row from "./Template/Table/Row"
import Messages from "../component/Messages"

export default function FormApp(props) {
    const {
       functions,
       form,
       formName,
    } = props

    if (form.template === 'table') {
        const rows = Object.keys(form.children).map(key => {
            const child = form.children[key]
            return (<Row key={key} form={child} functions={functions} columns={form.columns}/>)
        })

        let columns = []
        for (let i = 0; i < columns; i++) {
            columns.push(<td key={i}/>)
        }
        let dummyRow = (<tr style={{display: 'none'}}>{columns}</tr>)

        let table_attr = {}
        table_attr.className = 'smallIntBorder fullWidth standardForm relative'
        if (form.row_class !== null) table_attr.className = form.row_class

        return (<form
                    action={form.action}
                    id={form.id}
                    {...form.attr}
                    method={form.method !== undefined ? form.method : 'POST'}>
                    <Messages messages={form.errors} />
                    <table {...table_attr}>
                        <tbody>
                            {dummyRow}
                            {rows}
                        </tbody>
                    </table>
                </form>
        )
    }
    // Future Expansion for grid not table
    return (null)
}

FormApp.propTypes = {
    form: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
    formName: PropTypes.string.isRequired,
}
