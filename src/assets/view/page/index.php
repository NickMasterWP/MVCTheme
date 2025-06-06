<?php
/**
 * @var bool $isElementor
 */?>
<style>
    .mvc-page-container {
        min-height: calc(100vh - var(--height-header));
    }
</style>
<article class="page">
    <div class="<?= !$isElementor ? 'container ' : '';?>">
        <?php while ( have_posts() ) {
            the_post();?>
            <div class="content page__content">
                <?php the_content();?>
            </div>
        <?php } ?>
    </div>
</article>

