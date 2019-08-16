'use strict';

import React from 'react'
import { render } from 'react-dom'
import CollectionApp from "./collection/CollectionApp"

if (window.COLLECTIONS_PROPS !== 'undefined') {
    const collections = window.COLLECTIONS_PROPS

    for (const key in collections)
    {
        var collection = collections[key]

        var target = document.getElementById(collection.target)

        if (target !== null) {
            render(
                <CollectionApp
                    {...collection.params}
                />,
                target
            )
        }
    }
}