<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_config
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\Utilities\ArrayHelper;

/**
 * Text Filters form field.
 *
 * @since  3.7.0
 */
class JFormFieldGutenberg extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	3.7.0
	 */
	public $type = 'gutenberg';

	public function getInput()
	{
		$name = $this->name;
		$id = $this->id;

		/**
		 * For empty value set default empty paragraph block
		 * as value.
		 */
		if (empty($this->value))
		{
			$this->value = "<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->";
		}

		/**
		 * Base64_encode the value if it starts with `<!-- wp:`
		 *
		 */
		if (preg_match("#^<!-- wp:#", $this->value))
		{
			$value = base64_encode($this->value);
		}

		// Gutenberg serialized string.
		if (!empty($this->value))
		{
			// If blocks are base64_encoded then decode them.
			$blocks = preg_match("#^<!-- wp:#", $this->value) ? $this->value : base64_decode($this->value);
		}
		else
		{
			// Set an empty paragraph block if not block contents found.
			$blocks = "<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->";
		}

		HTMLHelper::_('stylesheet', Uri::root() . 'administrator/components/com_gutenberg/assets/editor/dist/css/editor.bundle.css', ['version' => 'auto']);
		HTMLHelper::_('script', Uri::root() . 'administrator/components/com_gutenberg/assets/editor/dist/js/editor.bundle.js', ['version' => 'auto'], ['defer' => true]);

		$doc = Factory::getDocument();
		$data = array(
			'id' => $this->id,
			'name' => $this->name,
			'blocks' => $blocks
		);

		$doc->addScriptOptions('data', $data);

		$output = "<div id='editor-wrapper'>";
		$output .= "<div id='joomla-gutenberg-editor'>Loading...</div>";
		$output .= "<input type='hidden' name='{$name}' id='{$id}' value='{$value}' />";
		$output .= "</div>";

		return $output;
	}
}
