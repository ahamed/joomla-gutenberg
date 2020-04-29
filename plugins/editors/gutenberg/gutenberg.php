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
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Uri\Uri;

/**
 * Gutenberg Editor Plugin.
 *
 * @since  1.0
 */
class PlgEditorGutenberg extends CMSPlugin
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
	 * Loads the application object
	 *
	 * @var    JApplicationCms
	 * @since  3.2
	 */
	protected $app = null;

	/**
	 * Initializes the Editor.
	 *
	 * @return  void
	 */
	public function onInit()
	{
		HTMLHelper::_('stylesheet',
			Uri::root() . 'plugins/editors/gutenberg/assets/editor/dist/css/joomla-gutenberg.css',
			array('version' => 'auto')
		);

		HTMLHelper::_(
			'script', Uri::root() . 'plugins/editors/gutenberg/assets/editor/dist/js/joomla-gutenberg.js',
			array('version' => 'auto'),
			array('defer' => true)
		);
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

		$editorId = 'joomla-gutenberg-editor-';

		if (preg_match("#jform\[(.+)\]#", $name))
		{
			$parsedName = preg_replace("#jform\[(.+)\]#", "$1", $name);
		}

		/**
		 * Prepare blocks for the editor
		 *
		 */
		if (!empty($content))
		{
			/**
			 * If the content is not in the gutenberg style
			 * then, make it as a HTML block.
			 * This is for B\C.
			 *
			 */
			$content = html_entity_decode($content);

			if (!preg_match("#^<!--\swp:.+\s+\{?.*\}?\s*-->#", $content))
			{
				$content = "<!-- wp:html -->\n" . $content . "\n<!-- /wp:html -->";
			}
		}

		$data = array(
			'id' => $id,
			'name' => $name,
			'blocks' => $content,
			'editorId' => 'joomla-gutenberg-editor-' . $parsedName
		);

		$displayData = array(
			'id' => $id,
			'name' => $name,
			'content' => $content,
			'width' => $width,
			'height' => $height,
			'col' => $col,
			'row' => $row,
			'buttons' => $buttons,
			'asset' => $asset,
			'author' => $author,
			'params' => $params,
			'parsedName' => $parsedName
		);

		$doc->addScriptOptions('data', $data);

		$output = array();
		$output[] = LayoutHelper::render('editors.gutenberg.element', $displayData, __DIR__ . '/layouts');

		return implode("\n", $output);
	}
}
