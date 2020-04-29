import React from 'react';
import ReactDOM from 'react-dom';

import {options, attachCoreBlocks} from './utils';
import { settings } from './helpers';

import Editor from './editor';
import './styles.scss';

attachCoreBlocks();

ReactDOM.render(
    <Editor settings={settings} />
, document.getElementById(options.editorId));
