'use strict'

import React from "react"
import PropTypes from 'prop-types'
import { Tab, Tabs, TabList, TabPanel } from 'react-tabs';
import '../../css/react-tabs.scss';
import Parser from "html-react-parser"
import FormApp from "../Form/FormApp"
import PaginationApp from "../Pagination/PaginationApp"

export default function Panels(props) {
    const {
        panels,
        selectedIndex,
        functions,
        externalContent,
        singleForm,
        translations,
        panelErrors,
    } = props

    const tabTags = Object.keys(panels).map(name => {
        let tab = panels[name]
        let showError = panelErrors[name] !== undefined ? 'text-red-400' : ''
        let title = panelErrors[name] !== undefined ? translations['Errors on Tab'] : ''
        return (
            <Tab
                key={tab.name}
                disabled={tab.disabled}>
                <span className={showError} title={title}>{tab.label}</span>
            </Tab>
        )
    })

    const content = Object.keys(panels).map(name => {
        const panel = panels[name]
        const panelContent = renderPanelContent(panel, props)
        let preContent = []
        let postContent = []
        if (panel.preContent !== null) {
            preContent = panel.preContent.map(name => {
                if (typeof externalContent[name] !== 'undefined')
                    return renderExternalContent(externalContent[name])
                return ''
            })
        }
        if (panel.postContent !== null) {
            postContent = panel.postContent.map(name => {
                if (typeof externalContent[name] !== 'undefined')
                    return renderExternalContent(externalContent[name])
                return ''
            })
        }
        return (
            <TabPanel key={name}>
                {preContent}
                {panelContent}
                {postContent}
            </TabPanel>
        )
    })

    if (singleForm) {
        let form = props.forms[Object.keys(props.forms)[0]]
        return (
            <form
                action={form.action}
                id={form.id}
                {...form.attr}
                method={form.method !== undefined ? form.method : 'POST'}>
                <div className={'info'}>{translations['All fields on all panels are saved together.']}</div>
                <Tabs selectedIndex={selectedIndex} onSelect={tabIndex => functions.onSelectTab(tabIndex)}>
                    <TabList>
                        {tabTags}
                    </TabList>
                    {content}
                </Tabs>
            </form>
        )
    }

    return (
        <Tabs selectedIndex={selectedIndex} onSelect={tabIndex => functions.onSelectTab(tabIndex)}>
            <TabList>
                {tabTags}
            </TabList>
            {content}
        </Tabs>
    )

}

Panels.propTypes = {
    panels: PropTypes.object.isRequired,
    forms: PropTypes.object.isRequired,
    externalContent: PropTypes.object.isRequired,
    functions: PropTypes.object.isRequired,
    translations: PropTypes.object.isRequired,
    panelErrors: PropTypes.object.isRequired,
    selectedIndex: PropTypes.number.isRequired,
    singleForm: PropTypes.bool.isRequired,
}

function renderPanelContent(panel, props){
    if (null !== panel.content){
        return Parser(panel.content)
    }

    let form = props.forms[panel.name]
    if (typeof form === 'undefined')
        form = props.forms[Object.keys(props.forms)[0]]

    return <FormApp
        {...props}
        formName={panel.name}
        form={form} />
}

function renderExternalContent(data){
    if (data.loader.type === 'pagination') {
        return (<PaginationApp {...data.content} key={data.loader.target} />)
    }
    if (data.loader.type === 'text') {
        return (<section key={data.loader.target}>{Parser(data.content)}</section>)
    }
    console.log(data)
}

