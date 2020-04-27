<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_guten
 * @author      JoomShaper <support@joomshaper.com>
 * @copyright   Copyright (c) 2010 - 2020 JoomShaper
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No Direct Access
defined('_JEXEC') or die('Resticted Aceess');

if (!empty($this->item->description))
{
	$content = preg_match("#^<!-- wp:#", $this->item->description) ? $this->item->description : base64_decode($this->item->description);
}
else
{
	$content = "<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->";
}

JFactory::getDocument()->addStyleDeclaration("
	.wp-block-group.b {
		width: 66%;
		margin: auto;
		font-size: 18px;
		font-family: Open Sans, sans-serif;
	}
");
?>

<div class="gutenberg-view">
	<?php echo $this->parseBeforeRender($content); ?>
</div>
