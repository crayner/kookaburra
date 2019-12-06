'use strict';

import React from 'react'
import { render } from 'react-dom'
import PaginationApp from "./Pagination/PaginationApp";

window.onload = function () {
    var target = window.PAGINATION_PROPS.targetElement
    const paginationContent = document.getElementById(target)

    if (paginationContent === null)
        render(<div>&nbsp;</div>, document.getElementById('dumpStuff'))
    else
        render(
            <PaginationApp
                {...window.PAGINATION_PROPS}
            />,
            paginationContent
        )
};
