<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_gutenberg
 * @author      JoomShaper <support@joomshaper.com>
 * @copyright   Copyright (c) 2010 - 2020 JoomShaper
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No Direct Access
defined('_JEXEC') or die('Resticted Aceess');

$doc = JFactory::getDocument();
JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select', null, array('disable_search_threshold' => 0));
?>

<form action="<?php echo JRoute::_('index.php?option=com_gutenberg&view=editor&layout=edit&id=' . (int) $this->item->id); ?>" name="adminForm" id="adminForm" method="post" class="form-validate">
	<?php if (!empty($this->sidebar)) { ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
    <div id="j-main-container" class="span10" >
		<?php } else { ?>
			<div id="j-main-container"></div>
		<?php } ?>
	<div class="form-horizontal">
		<div class="row-fluid">
			<div class="span12">
				<?php echo $this->form->renderFieldset('basic'); ?>
			</div>
		</div>
	</div>

	<input type="hidden" name="task" value="editor.edit" />
	<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

