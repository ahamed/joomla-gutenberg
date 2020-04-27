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

$user 		= JFactory::getUser();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn 	= $this->escape($this->state->get('list.direction'));
$canOrder 	= $user->authorise('core.edit.state', 'com_gutenberg');
$saveOrder = ($listOrder === 'a.ordering');

if($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_gutenberg&task=editors.saveOrderAjax&tmpl=component';
	$html = JHtml::_('sortablelist.sortable', 'editorList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

JHtml::_('jquery.framework', false);
?>

<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById('sortTable');
		direction = document.getElementById('directionTable');
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_gutenberg&view=editors'); ?>" method="POST" name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)) { ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>

	<div id="j-main-container" class="span10" >
		<?php } else { ?>
			<div id="j-main-container"></div>
		<?php } ?>

		<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
		<div class="clearfix"></div>
		<?php if (!empty($this->items)) { ?>
			<table class="table table-striped" id="editorList">
				<thead>
					<tr>
						<th class="nowrap center hidden-phone" width="1%">
							<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
						</th>

						<th width="1%" class="hidden-phone">
							<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
						</th>

						<th width="1%" class="nowrap center">
							<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
						</th>

						<th>
							<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
						</th>
						
						<th>
							<?php echo JHtml::_('grid.sort','COM_GUTENBERG_CREATED_BY', 'a.created_by', $listDirn,  $listOrder); ?>
						</th>
						
						<th>
							<?php echo JHtml::_('grid.sort', 'COM_GUTENBERG_CREATED', 'a.created', $listDirn, $listOrder); ?>
						</th>
						
						<th>
							<?php echo JHtml::_('grid.sort', 'COM_GUTENBERG_ID', 'a.id', $listDirn, $listOrder); ?>
						</th>

					</tr>
				</thead>

				<tfoot>
					<tr>
						<td colspan="10">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>

				<tbody>
					<?php foreach($this->items as $i => $item): ?>

						<?php
						$canCheckin	= $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->get('id') || $item->checked_out == 0;
						$canChange		= $user->authorise('core.edit.state', 'com_gutenberg') && $canCheckin;
						$canEdit		= $user->authorise( 'core.edit', 'com_gutenberg' );
						?>

						<tr class="row<?php echo $i % 2; ?>" sortable-group-id="1">
							<td class="order nowrap center hidden-phone">
								<?php if($canChange) :
									$disableClassName = '';
									$disabledLabel = '';
									if(!$saveOrder) :
										$disabledLabel = JText::_('JORDERINGDISABLED');
										$disableClassName = 'inactive tip-top';
									endif;
									?>

									<span class="sortable-handler hasTooltip <?php echo $disableClassName; ?>" title="<?php echo $disabledLabel; ?>">
										<i class="icon-menu"></i>
									</span>
									<input type="text" style="display: none;" name="order[]" size="5" class="width-20 text-area-order " value="<?php echo $item->ordering; ?>" >
								<?php else: ?>
									<span class="sortable-handler inactive">
										<i class="icon-menu"></i>
									</span>
								<?php endif; ?>
							</td>

							<td class="center hidden-phone">
								<?php echo JHtml::_('grid.id', $i, $item->id); ?>
							</td>

							<td class="center">
								<div class="btn-group">
									<?php echo JHtml::_('jgrid.published', $item->published, $i, 'editors.', true, 'cb');?>
									<?php
										if ($canChange) {
											JHtml::_('actionsdropdown.' . ((int) $item->published === 2 ? 'un' : '') . 'archive', 'cb' . $i, 'editors');
											JHtml::_('actionsdropdown.' . ((int) $item->published === -2 ? 'un' : '') . 'trash', 'cb' . $i, 'editors');
											echo JHtml::_('actionsdropdown.render', $this->escape($item->title));
										}
									?>
								</div>
							</td>

							<td>
								<?php if ($item->checked_out) : ?>
									<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'editors.', $canCheckin); ?>
								<?php endif; ?>

								<?php if ($canEdit) : ?>
									<a class="title" href="<?php echo JRoute::_('index.php?option=com_gutenberg&task=editor.edit&id='. $item->id); ?>">
										<?php echo $this->escape($item->title); ?>
									</a>
								<?php else : ?>
									<?php echo $this->escape($item->title); ?>
								<?php endif; ?>

								<span class="small break-word">
									<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
								</span>
							</td>

							<td>
								<?php echo JFactory::getUser($item->created_by)->get('username', $item->created_by); ?>
							</td>

							<td>
								<?php echo JHtml::_('date', $item->created, 'd M, Y'); ?>
							</td>
							
							<td>
								<?php echo $item->id; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php } else { ?>
			<div class="no-record-found"><?php echo JText::_('COM_GUTENBERG_NO_RECORD_FOUND'); ?></div>
		<?php } ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $lilstDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
	
