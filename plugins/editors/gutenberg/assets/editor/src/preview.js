import React, {useState, useEffect} from 'react';

import {
    BlockPreview
} from '@wordpress/block-editor';

const Preview = ({blocks = [], width = 700}) => {
    const [previewBlocks, updateBlocks] = useState([]);

    useEffect(() => {
        if (blocks.length > 0) {
            updateBlocks(blocks);
        }
    }, []);

    return <BlockPreview blocks={previewBlocks} viewportWidth={width} />
}


export default Preview;