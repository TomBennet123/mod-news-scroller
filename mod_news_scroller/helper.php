<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_news_scroller
 * @copyright   Copyright (C) 2025. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\CMS\Language\Text;

class ModNewsScrollerHelper
{
    public static function getArticles(&$params)
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);

        // A szükséges mezők kiválasztása
        $query->select($db->quoteName(
            ['a.id', 'a.title', 'a.alias', 'a.catid', 'a.images', 'c.title', 'c.alias'],
            ['id', 'title', 'alias', 'catid', 'images', 'category_title', 'category_alias']
        ));
        
        $query->from($db->quoteName('#__content', 'a'));
        
        // Csatlakozás a kategóriák táblához
        $query->join('LEFT', $db->quoteName('#__categories', 'c') . ' ON ' . $db->quoteName('a.catid') . ' = ' . $db->quoteName('c.id'));

        // Szűrés publikált állapotra
        $query->where($db->quoteName('a.state') . ' = 1');
        $query->where($db->quoteName('c.published') . ' = 1');

        // Kategória szűrés
        $categoryIds = $params->get('category');
        if (!empty($categoryIds)) {
            $query->where($db->quoteName('a.catid') . ' IN (' . implode(',', array_map('intval', (array) $categoryIds)) . ')');
        }

        // Kiemelt cikkek szűrése
        if ($params->get('featured', 0)) {
            $query->where($db->quoteName('a.featured') . ' = 1');
        }
        
        // Rendezés (pl. létrehozás dátuma szerint csökkenő)
        $query->order($db->quoteName('a.created') . ' DESC');
        
        // Cikkek számának limitálása
        $count = (int) $params->get('count', 6);
        $query->setLimit($count);
        
        $db->setQuery($query);
        
        $articles = [];
        try {
            $results = $db->loadObjectList();
            if ($results) {
                foreach ($results as $result) {
                    // Bevezető kép kinyerése
                    $images = json_decode($result->images);
                    $result->image_src = isset($images->image_intro) && !empty($images->image_intro) ? $images->image_intro : '';

                    // URL generálása
                    $result->link = Route::_(RouteHelper::getArticleRoute($result->id . ':' . $result->alias, $result->catid . ':' . $result->category_alias));
                    $result->category_link = Route::_(RouteHelper::getCategoryRoute($result->catid . ':' . $result->category_alias));
                    
                    $articles[] = $result;
                }
            }
        } catch (\RuntimeException $e) {
            // Hiba esetén naplózás vagy üzenet küldése
            return [];
        }
        
        return $articles;
    }
}