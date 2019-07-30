'use strict';

import React from 'react'
import { render } from 'react-dom'
import FastFinderApp from "./fastFinder/FastFinderApp";

window.onload = function () {
    const fastFinderWrapper = document.getElementById('fastFinderWrapper')

    if (fastFinderWrapper === null)
        render(<div>&nbsp;</div>, document.getElementById('dumpStuff'))
    else
        render(
            <FastFinderApp
                {...window.FASTFINDER_PROPS}
            />,
            fastFinderWrapper
        )
};
