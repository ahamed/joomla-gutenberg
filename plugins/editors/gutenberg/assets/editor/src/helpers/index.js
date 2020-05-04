import {SETTINGS_DEFAULTS} from '@wordpress/block-editor';
import { settings as editorSettings } from '../utils';

const settings = {
    ...SETTINGS_DEFAULTS,
    maxWidth: editorSettings.width,
    allowedBlockTypes: editorSettings.blocks,
    maxUploadFileSize: editorSettings.filesize,
    allowedMimeTypes: editorSettings.allowedMimeTypes,

};

export { settings };