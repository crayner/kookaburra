'use strict';

import React from 'react'
import { render } from 'react-dom'
import PaginationApp from "./Pagination/PaginationApp";

window.onload = function () {
    const paginationContent = document.getElementById('paginationContent')

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
