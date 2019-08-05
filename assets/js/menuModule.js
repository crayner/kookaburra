'use strict';

import React from 'react'
import { render } from 'react-dom'
import ModuleMenu from "./ModuleMenu/ModuleMenu"

const sidebarNavigation = document.getElementById('sidebarNavigation')

if (sidebarNavigation === null)
    render(<div>&nbsp;</div>, document.getElementById('dumpStuff'))
else
    render(
        <ModuleMenu
            {...window.MENUMODULE_PROPS}
        />,
        sidebarNavigation
    )
