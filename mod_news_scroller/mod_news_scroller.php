<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_news_scroller
 * @copyright   Copyright (C) 2025. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

// Betöltjük a helper fájlt
require_once __DIR__ . '/helper.php';

// Lekérjük a cikkeket a helper segítségével
$articles = ModNewsScrollerHelper::getArticles($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
$imageWidth = (int) $params->get('image_width', 400);

// Ha nincsenek cikkek, nem csinálunk semmit
if (empty($articles)) {
    return;
}

// Betöltjük a modul sablonját
require ModuleHelper::getLayoutPath('mod_news_scroller', $params->get('layout', 'default'));