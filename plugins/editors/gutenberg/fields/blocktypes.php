<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Editors.gutenberg
 *
 * @copyright   Copyright (C) 2020 JoomShaper.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Uri\Uri;

/**
 * Form Field class for the Gutenberg editor.
 *
 * @package     Joomla.Plugin
 * @subpackage  Editors.gutenberg
 * @since       1.0.0
 */
class JFormFieldBlockTypes extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $type = 'blocktypes';

	private function getCoreBlocks()
	{
		return array('core/paragraph', 'core/image', 'core/heading', 'core/gallery', 'core/list', 'core/quote', 'core/button', 'core/buttons', 'core/code', 'core/columns', 'core/column', 'core/cover', 'core/group', 'core/html', 'core/media-text', 'core/more', 'core/preformatted', 'core/pullquote', 'core/separator', 'core/block', 'core/spacer', 'core/table', 'core/text-columns', 'core/verse');
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.0.0
	 */
	protected function getOptions()
	{
		$blocks = $this->getCoreBlocks();
		$options = array();

		foreach ($blocks as $block)
		{
			$options[] = HTMLHelper::_('select.option', $block, $this->parseBlockName($block));
		}

		return $options;
	}

	/**
	 * Parse block name for render as select option
	 *
	 * @param	string	$name	Block name.
	 *
	 * @return	string			Parsed block name.
	 */
	private function parseBlockName($name)
	{
		$chunks = explode('/', $name);
		array_splice($chunks, 0, 1);

		return implode('/', $chunks);
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.7.0
	 */
	protected function getInput()
	{
		if (!empty($this->value))
		{
			if ($this->value === 'all')
			{
				$this->value = $this->getCoreBlocks();
			}
		}

		return HTMLHelper::_(
			'select.genericlist', $this->getOptions(), $this->name, $this->generateAttributes(), 'value', 'text', $this->value, $this->id
		);
	}

	/**
	 * Generate field attributes.
	 *
	 * @return	string
	 * @since	1.0.0
	 */
	private function generateAttributes()
	{
		$attr = '';

		if ($this->class)
		{
			$attr .= ' class="' . $this->class . '"';
		}

		if ($this->multiple)
		{
			$attr .= ' multiple';
		}

		return trim($attr);
	}
}
