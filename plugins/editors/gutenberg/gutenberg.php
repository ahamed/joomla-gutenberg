<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Editors.Gutenberg
 *
 * @copyright   Copyright (C) 2020 JoomShaper
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

/**
 * Gutenberg Editor Plugin.
 *
 * @since  1.0
 */
class PlgEditorGutenberg extends JPlugin
{
	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 * @since  3.1.4
	 */
	protected $autoloadLanguage = true;

	/**
	 * Mapping of syntax to CodeMirror modes.
	 *
	 * @var array
	 */
	protected $modeAlias = array();

	/**
	 * Initializes the Editor.
	 *
	 * @return  void
	 */
	public function onInit()
	{
		HTMLHelper::_('stylesheet',
			Uri::root() . 'plugins/editors/gutenberg/assets/editor/dist/css/editor.bundle.css',
			array('version' => 'auto')
		);

		HTMLHelper::_(
			'script', Uri::root() . 'plugins/editors/gutenberg/assets/editor/dist/js/editor.bundle.js',
			array('version' => 'auto'),
			array('defer' => true)
		);
	}

	/**
	 * Copy editor content to form field.
	 *
	 * @param   string  $id  The id of the editor field.
	 *
	 * @return  string  Javascript
	 *
	 * @deprecated 4.0 Code executes directly on submit
	 */
	public function onSave($id)
	{
		return sprintf('document.getElementById(%1$s).value = Joomla.editors.instances[%1$s].getValue();', json_encode((string) $id));
	}

	/**
	 * Get the editor content.
	 *
	 * @param   string  $id  The id of the editor field.
	 *
	 * @return  string  Javascript
	 *
	 * @deprecated 4.0 Use directly the returned code
	 */
	public function onGetContent($id)
	{
		return sprintf('Joomla.editors.instances[%1$s].getValue();', $id);
	}

	/**
	 * Set the editor content.
	 *
	 * @param   string  $id       The id of the editor field.
	 * @param   string  $content  The content to set.
	 *
	 * @return  string  Javascript
	 *
	 * @deprecated 4.0 Use directly the returned code
	 */
	public function onSetContent($id, $content)
	{
		return sprintf('Joomla.editors.instances[%1$s].setValue(%2$s);', json_encode((string) $id), json_encode((string) $content));
	}

	/**
	 * Adds the editor specific insert method.
	 *
	 * @return  void
	 *
	 * @deprecated 4.0 Code is loaded in the init script
	 */
	public function onGetInsertMethod()
	{
		static $done = false;

		// Do this only once.
		if ($done)
		{
			return true;
		}

		$done = true;

		JFactory::getDocument()->addScriptDeclaration(
			"
			;function jInsertEditorText(text, editor) {
				Joomla.editors.instances[editor].replaceSelection(text);
			}
			"
		);

		return true;
	}

	/**
	 * Display the editor area.
	 *
	 * @param   string   $name     The control name.
	 * @param   string   $content  The contents of the text area.
	 * @param   string   $width    The width of the text area (px or %).
	 * @param   string   $height   The height of the text area (px or %).
	 * @param   int      $col      The number of columns for the textarea.
	 * @param   int      $row      The number of rows for the textarea.
	 * @param   boolean  $buttons  True and the editor buttons will be displayed.
	 * @param   string   $id       An optional ID for the textarea (note: since 1.6). If not supplied the name is used.
	 * @param   string   $asset    Not used.
	 * @param   object   $author   Not used.
	 * @param   array    $params   Associative array of editor parameters.
	 *
	 * @return  string  HTML
	 */
	public function onDisplay($name, $content, $width, $height, $col, $row, $buttons = true, $id = null, $asset = null, $author = null, $params = array())
	{
		$doc = Factory::getDocument();
		$data = array(
			'id' => $id,
			'name' => $name,
			'blocks' => $content
		);

		$doc->addScriptOptions('data', $data);

		$output = "<div id='editor-wrapper'>";
		$output .= "<div id='joomla-gutenberg-editor'>Loading...</div>";
		$output .= "<input type='hidden' name='{$name}' id='{$id}' value='{$value}' />";
		$output .= "</div>";

		return $output;
	}
}
