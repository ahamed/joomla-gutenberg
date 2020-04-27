import { registerCoreBlocks } from '@wordpress/block-library';
import { unregisterBlockType, getBlockTypes } from '@wordpress/blocks';

const options = Joomla.getOptions('data') || {};
const previewData = Joomla.getOptions('previewData') || {};

const attachCoreBlocks = () => {
    registerCoreBlocks();

    const allowedBlocks = [
        'core/paragraph',
        'core/image',
        'core/heading',
        'core/gallery',
        'core/list',
        'core/quote',
        'core/button',
        'core/buttons',
        'core/code',
        'core/columns',
        'core/column',
        'core/cover',
        'core/group',
        'core/html',
        'core/media-text',
        'core/more',
        'core/preformatted',
        'core/pullquote',
        'core/separator',
        'core/block',
        'core/spacer',
        'core/table',
        'core/text-columns',
        'core/verse',
    ];

    getBlockTypes().forEach(block => {
        if (allowedBlocks.indexOf(block.name) === -1) {
            unregisterBlockType(block.name);
        }
    });

}

const htmlEntities = {
    /**
     * Converts a string to its html characters completely.
     *
     * @param {String} str String with unescaped HTML characters
     **/
    encode : function(str) {
        var buf = [];
        
        for (var i=str.length-1;i>=0;i--) {
            buf.unshift(['&#', str[i].charCodeAt(), ';'].join(''));
        }
        
        return buf.join('');
    },
    /**
     * Converts an html characterSet into its original character.
     *
     * @param {String} str htmlSet entities
     **/
    decode : function(str) {
        return str.replace(/&#(\d+);/g, function(match, dec) {
            return String.fromCharCode(dec);
        });
    }
};

export {options, previewData, attachCoreBlocks, htmlEntities};