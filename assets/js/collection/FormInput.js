'use strict'

import React from "react"
import PropTypes from 'prop-types'

export default function FormInput(props) {
    const {
        template,
        form,
        defaults,
        onChange,
        onClick,
        formData,
    } = props

    var value = formData.value

    if (template.type === 'file') {
        var existingValue = value
        value = ''
    }

    var style = {}
    if (form.attr.style !== undefined && typeof(form.attr.style) === 'object') {
        style = form.attr.style
    }

    var classValue = defaults.input.class
    if (formData.class !== null) {
        classValue = formData.class
    }

    var input = (<input type={template.type} className={classValue} defaultValue={value} id={formData.id} name={formData.full_name} style={style} />)

    if (formData.onChange) {
        input = (<input type={template.type} className={classValue} value={value} id={formData.id} name={formData.full_name} onChange={(e) => onChange(e)} style={style} />)
    }
    if (formData.onClick) {
        input = (<input type={template.type} className={classValue} value={value} id={formData.id} name={formData.full_name} onClick={(e) => onClick(e)} style={style} />)
    }
    if (formData.onClick && formData.onChange) {
        input = (<input type={template.type} className={classValue} value={value} id={formData.id} name={formData.full_name} onClick={(e) => onClick(e)} onChange={(e) => onChange(e)} style={style} />)
    }

    if (template.wrapper === false) {
        return input
    }

    var classValueWrapper = defaults.wrapper.class
    if (template.wrapper !== undefined && template.wrapper.class !== undefined) {
        classValueWrapper = template.wrapper.class
    }

    return (
        <div className={classValueWrapper}>
            {input}{template.type === 'file' && existingValue !== undefined ? <span className={'fileName text-gray-900'}><br/>{template.existingFile}: {existingValue}</span>: ''}
        </div>
    )
}

FormInput.propTypes = {
    template: PropTypes.object.isRequired,
    form: PropTypes.object.isRequired,
    defaults: PropTypes.object.isRequired,
    formData: PropTypes.object.isRequired,
    onChange: PropTypes.func.isRequired,
    onClick: PropTypes.func.isRequired,
}
