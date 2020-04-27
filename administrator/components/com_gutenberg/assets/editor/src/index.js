import React from 'react';
import ReactDOM from 'react-dom';

import {attachCoreBlocks} from './utils';
import { settings } from './helpers';

import Editor from './editor';
import './styles.scss';

attachCoreBlocks();

console.log(settings);

ReactDOM.render(
    <Editor settings={settings} />
, document.getElementById('joomla-gutenberg-editor'));
