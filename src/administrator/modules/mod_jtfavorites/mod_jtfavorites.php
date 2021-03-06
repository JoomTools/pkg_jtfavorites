<?php
/**
 * @package      Joomla.Administrator
 * @subpackage   mod_jtfavorites
 *
 * @author       Guido De Gobbis <support@joomtools.de>
 * @copyright    2020 JoomTools.de - All rights reserved.
 * @license      GNU General Public License version 3 or later
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\Registry\Registry;

defined('_JEXEC') or die;

/**
 * @var   object    $module  Module objekt
 * @var   Registry  $params  Module params
 */

// Include dependencies.
JLoader::register('ModJtFavoritesHelper', __DIR__ . '/helper.php');

// Check if plugin ist enabled
$isEnabledPlugin = ModJtFavoritesHelper::isEnabledPlugin();

if (!$isEnabledPlugin)
{
	return;
}

$moduleclass_sfx  = $params->get('moduleclass_sfx', '');

// Get the list of favorites from database
$items = ModJtFavoritesHelper::getList($params);

if (!count($items))
{
	return;
}

$position = $module->position;
$layout   = $params->get('layout', 'default');

//Set the layout automatically by position if no individual layout is selected
$layout = $layout !== '_:default' ? $layout : '_:' . $position;

$layoutRenderer = ModJtFavoritesHelper::getLayoutRenderer($layout);

$displayData = array(
	'moduleTitle'     => $module->title,
	'moduleclass_sfx' => $moduleclass_sfx,
	'task'            => $position . 'JtFavForm',
	'view'            => $params->get('show_items_tabbed', 'list'),
	'items'           => $items,
);

if (ModJtFavoritesHelper::$loadJs)
{
	HTMLHelper::_('behavior.keepalive');
	HTMLHelper::_('script', 'mod_jtfavorites/jtfavoritesClickAction.min.js', array('version' => 'auto', 'relative' => true));
}

echo $layoutRenderer->render($displayData);
