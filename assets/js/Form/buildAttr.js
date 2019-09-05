'use strict'

export function rowAttr(form, defaultClass) {

    let row_attr = {}

    row_attr.className = defaultClass
    if (form.row_class !== null) row_attr.className = form.row_class
    if (form.row_id !== false) row_attr.id = form.row_id

    return row_attr
}

export function columnAttr(form, defaultClass) {

    let column_attr = form.column_attr === false ? {} : form.column_attr

    if (typeof column_attr.class !== 'undefined') {
        column_attr.className = column_attr.class
        delete column_attr.class
    }
    if (typeof column_attr.className === 'undefined')  column_attr.className = defaultClass

    return column_attr
}

export function widgetAttr(form, defaultClass, functions) {

    let widget_attr = typeof form.attr !== 'object' ? {} : form.attr

    if (typeof widget_attr.class !== 'undefined') {
        widget_attr.className = widget_attr.class
        delete widget_attr.class
    }

    if (typeof widget_attr.className === 'undefined') widget_attr.className = defaultClass

    widget_attr.id = form.id
    widget_attr.name = form.full_name
    widget_attr.onChange = null
    if (form.on_change === false && typeof functions.onElementChange === 'function') {
        widget_attr.onChange = (e) => functions.onElementChange(e,form)
    } else if (typeof functions[form.on_change] === 'function') {
        widget_attr.onChange = (e) => functions[form.on_change](e,form)
    }
    widget_attr.onClick = null
    if (form.on_click === false && typeof functions.onElementClick === 'function') {
        widget_attr.onClick = (e) => functions.onElementClick(e,form)
    } else if (typeof functions[form.on_click] === 'function') {
        widget_attr.onClick = (e) => functions[form.on_click](e,form)
    }
    if (form.multiple !== false) widget_attr.multiple = true

    if (typeof widget_attr.inputmode === 'string') {
        widget_attr.inputMode = widget_attr.inputmode
        delete widget_attr.inputmode
    }

    widget_attr['aria-describedby'] = form.id + '_help'

    return widget_attr
}


export function wrapperAttr(form, defaultClass) {

    let wrapper_attr = {}

    wrapper_attr.className = defaultClass
    if (form.wrapper_class !== null) wrapper_attr.className = form.wrapper_class

    return wrapper_attr
}

export function labelAttr(form, defaultClass) {
    let label_attr = {}
    label_attr.className = defaultClass
    return label_attr
}
