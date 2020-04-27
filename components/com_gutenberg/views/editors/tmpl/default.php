<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_gutenberg
 * @author      JoomShaper <support@joomshaper.com>
 * @copyright   Copyright (c) 2010 - 2020 JoomShaper
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No Direct Access
defined('_JEXEC') or die('Resticted Aceess');
?>

<?php if (!empty($this->items)) { ?>
    <?php foreach ($this->items as $item) { ?>
        <a href="<?php echo $item->url; ?>"><?php echo $item->title; ?></a>
    <?php } ?>
<?php } ?>