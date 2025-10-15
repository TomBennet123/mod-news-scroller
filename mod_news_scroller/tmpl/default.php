<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_news_scroller
 * @copyright   Copyright (C) 2025. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

// CSS hozzáadása a dokumentumhoz
$doc = Factory::getApplication()->getDocument();
$doc->addStyleSheet(JUri::base(true) . '/modules/mod_news_scroller/tmpl/css/style.css');

// Dinamikus CSS változók az animációhoz
$itemCount = count($articles);
$itemWidth = $imageWidth; // Kártya szélessége
$itemMargin = 20; // Kártya jobb oldali margója
$totalScrollWidth = ($itemWidth + $itemMargin) * $itemCount;
$animationDuration = $itemCount * 4; // 4 másodperc/elem sebesség

$dynamicStyles = "
:root {
    --card-width: {$itemWidth}px;
    --card-margin: {$itemMargin}px;
    --total-scroll-width: {$totalScrollWidth}px;
    --animation-duration: {$animationDuration}s;
}
";
$doc->addStyleDeclaration($dynamicStyles);

?>
<div class="scroller-container <?php echo $moduleclass_sfx; ?>">
    <div class="scroller">
        <?php foreach ($articles as $article) : ?>
            <div class="news-card">
                <?php if (!empty($article->image_src)) : ?>
                    <img src="<?php echo htmlspecialchars($article->image_src, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8'); ?>">
                <?php else: ?>
                    <img src="https://placehold.co/<?php echo $itemWidth; ?>x<?php echo round($itemWidth / 16 * 9); ?>/cccccc/ffffff?text=<?php echo Text::_('MOD_NEWS_SCROLLER_NO_IMAGE'); ?>" alt="<?php echo htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8'); ?>">
                <?php endif; ?>
                <div class="card-overlay">
                    <h3 class="card-title"><?php echo htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8'); ?></h3>
                    <a href="<?php echo $article->category_link; ?>" class="card-category"><?php echo htmlspecialchars($article->category_title, ENT_QUOTES, 'UTF-8'); ?></a>
                </div>
                <a href="<?php echo $article->link; ?>" class="more-button"><?php echo Text::_('MOD_NEWS_SCROLLER_READ_MORE'); ?></a>
            </div>
        <?php endforeach; ?>
        
        <?php foreach ($articles as $article) : ?>
            <div class="news-card">
                 <?php if (!empty($article->image_src)) : ?>
                    <img src="<?php echo htmlspecialchars($article->image_src, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8'); ?>">
                <?php else: ?>
                    <img src="https://placehold.co/<?php echo $itemWidth; ?>x<?php echo round($itemWidth / 16 * 9); ?>/cccccc/ffffff?text=<?php echo Text::_('MOD_NEWS_SCROLLER_NO_IMAGE'); ?>" alt="<?php echo htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8'); ?>">
                <?php endif; ?>
                <div class="card-overlay">
                    <h3 class="card-title"><?php echo htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8'); ?></h3>
                    <a href="<?php echo $article->category_link; ?>" class="card-category"><?php echo htmlspecialchars($article->category_title, ENT_QUOTES, 'UTF-8'); ?></a>
                </div>
                <a href="<?php echo $article->link; ?>" class="more-button"><?php echo Text::_('MOD_NEWS_SCROLLER_READ_MORE'); ?></a>
            </div>
        <?php endforeach; ?>
    </div>
</div>