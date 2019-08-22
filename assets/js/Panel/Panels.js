'use strict'

import React from "react"
import PropTypes from 'prop-types'
import { Tab, Tabs, TabList, TabPanel } from 'react-tabs';
import '../../css/react-tabs.scss';
import Parser from "html-react-parser"
import FormApp from "../Form/FormApp"

export default function Panels(props) {
    const {
        panels,
        onSelectTab,
        selectedIndex,
    } = props


    const tabTags = Object.keys(panels).map(name => {
        const tab = panels[name]
        return (
            <Tab
                key={tab.name}
                disabled={tab.disabled}>
                {tab.label}
            </Tab>
        )
    })

    const content = Object.keys(panels).map(name => {
        const panelContent = renderPanelContent(panels[name])

        return (
            <TabPanel key={name}>
                {panelContent}
            </TabPanel>
        )
    })

    return (
        <Tabs selectedIndex={selectedIndex} onSelect={tabIndex => onSelectTab(tabIndex)}>
            <TabList>
                {tabTags}
            </TabList>
            {content}
        </Tabs>
    )

}

Panels.propTypes = {
    panels: PropTypes.object.isRequired,
    selectedIndex: PropTypes.number.isRequired,
    onSelectTab: PropTypes.func.isRequired,
}

function renderPanelContent(panel){

    if (null !== panel.content){
        return Parser(panel.content)
    }

    if (null !== panel.form){
        return <FormApp form={panel.form} />
    }
}

