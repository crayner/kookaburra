'use strict'

import React from "react"
import PropTypes from 'prop-types'
import Select from 'react-select';

export default function FormSelect(props) {
    const {
        template,
        defaults,
        onChange,
        formData,
    } = props

    var classValue = defaults.select.class
    if (formData.class !== null) {
        classValue = formData.class
    }

    var options = formData.choices.map(prompt => {
        return {value: prompt.value, label: prompt.label, id: formData.id}
    })

    const customStyles = {
        container: (provided, state) => ({
            // none of react-select's styles are passed to <Control />
            ...provided,
            maxHeight: '28px',
            fontSize: '13px',
            color: 'black'
        }),
        control: () => ({
            // none of react-select's styles are passed to <Control />
            maxHeight: '28px',
            fontSize: '13px',
            color: 'black'
        }),
    }

    var select = (<Select
            styles={customStyles}
            onChange={onChange}
            name={formData.full_name}
            options={options}
            className={classValue}
            defaultValue={formData.value}
        />)

    var classValueWrapper = defaults.wrapper.class
    if (template.wrapper !== undefined && template.wrapper.class !== undefined) {
        classValueWrapper = template.wrapper.class
    }

    return (
        <div className={classValueWrapper}>
            {select}
        </div>
    )
}

FormSelect.propTypes = {
    template: PropTypes.object.isRequired,
    defaults: PropTypes.object.isRequired,
    formData: PropTypes.object.isRequired,
    onChange: PropTypes.func.isRequired,
}
