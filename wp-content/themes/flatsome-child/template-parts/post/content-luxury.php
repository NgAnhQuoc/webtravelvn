<div class="cs-post-card">
    <a href="<?php the_permalink(); ?>">
        <div class="cs-card-img"
            style="background-image: url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>');">
        </div>
        <div class="cs-card-overlay"></div>
        <div class="cs-card-info">
            <h3 class="cs-card-title"><?php the_title(); ?></h3>
            <div class="cs-card-meta-bottom">
                <?php
                $regions = get_the_terms(get_the_ID(), 'region');
                if (!empty($regions) && !is_wp_error($regions)): ?>
                    <div class='cs-card-location'>
                        <i class='icon-map-pin-fill'></i>
                        <?php echo esc_html($regions[0]->name); ?>
                    </div>
                <?php endif; ?>
                <div class="cs-card-date">
                    <i class="icon-clock"></i> <?php echo get_the_date('d/m/Y'); ?>
                </div>
            </div>
        </div>
    </a>
</div>
