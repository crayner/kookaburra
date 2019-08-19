'use strict'

import React from "react"
import PropTypes from 'prop-types'
import { Tab, Tabs, TabList, TabPanel } from 'react-tabs';
import '../../css/react-tabs.scss';
import Parser from "html-react-parser"

export default function Panels(props) {
    const {
        panels,
    } = props

    var selectedPanel = props.selectedPanel


    const tabTags = Object.keys(panels).map(name => {
        const tab = panels[name]
        if (null === selectedPanel) {
            selectedPanel = name
        }
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
        <Tabs defaultIndex={selectedPanel}>
            <TabList>
                {tabTags}
            </TabList>
            {content}
        </Tabs>
    )

}

Panels.propTypes = {
    panels: PropTypes.object.isRequired,
    selectedPanel: PropTypes.string,
}

function renderPanelContent(panel){

    if (null !== panel.content){
        return Parser(panel.content)
    }
    console.log(panel)
}

