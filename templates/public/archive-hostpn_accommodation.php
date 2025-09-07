<?php
/**
 * Archive template for accommodation post type
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    hostpn
 * @subpackage hostpn/templates/public
 */

get_header(); ?>

<div class="hostpn-accommodation-archive-wrapper">
    <div class="hostpn-container">
        <header class="hostpn-archive-header">
            <h1 class="hostpn-archive-title"><?php esc_html_e('Accommodations', 'hostpn'); ?></h1>
        </header>

        <div class="hostpn-accommodation-grid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    $accommodation_id = get_the_ID();
                    $accommodation_code = get_post_meta($accommodation_id, 'hostpn_accommodation_code', true);
                    $accommodation_type = get_post_meta($accommodation_id, 'hostpn_accommodation_type', true);
                    $accommodation_city = get_post_meta($accommodation_id, 'hostpn_accommodation_city', true);
                    $accommodation_country = get_post_meta($accommodation_id, 'hostpn_accommodation_country', true);
                    $thumbnail = get_the_post_thumbnail($accommodation_id, 'medium');
                    ?>
                    
                    <article class="hostpn-accommodation-card" data-accommodation-id="<?php echo esc_attr($accommodation_id); ?>">
                        <div class="hostpn-accommodation-image">
                            <?php if ($thumbnail) : ?>
                                <?php echo wp_kses_post($thumbnail); ?>
                            <?php else : ?>
                                <div class="hostpn-accommodation-placeholder">
                                    <i class="material-icons-outlined">home</i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="hostpn-accommodation-content">
                            <h2 class="hostpn-accommodation-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            
                            <?php if ($accommodation_code) : ?>
                                <div class="hostpn-accommodation-code">
                                    <span class="hostpn-label"><?php esc_html_e('Code:', 'hostpn'); ?></span>
                                    <span class="hostpn-value"><?php echo esc_html($accommodation_code); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($accommodation_type) : ?>
                                <div class="hostpn-accommodation-type">
                                    <span class="hostpn-label"><?php esc_html_e('Type:', 'hostpn'); ?></span>
                                    <span class="hostpn-value"><?php echo esc_html($accommodation_type); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($accommodation_city || $accommodation_country) : ?>
                                <div class="hostpn-accommodation-location">
                                    <i class="material-icons-outlined hostpn-icon-small">location_on</i>
                                    <span class="hostpn-location-text">
                                        <?php
                                        $location_parts = array_filter([$accommodation_city, $accommodation_country]);
                                        echo esc_html(implode(', ', $location_parts));
                                        ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="hostpn-accommodation-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <div class="hostpn-accommodation-features-preview">
                                <?php
                                // Use global features constant
                                $accommodation_features = HOSTPN_ACCOMMODATION_FEATURES;
                                
                                // Show featured features by category
                                foreach ($accommodation_features as $category_key => $category_data) {
                                    $active_features = [];
                                    
                                    // Get active features for this category
                                    foreach ($category_data['features'] as $meta_key => $description) {
                                        if (get_post_meta($accommodation_id, $meta_key, true)) {
                                            $active_features[] = $meta_key;
                                        }
                                    }
                                    
                                    if (!empty($active_features)) {
                                        echo '<div class="hostpn-feature-group hostpn-feature-group-' . esc_attr($category_key) . '">';
                                        echo '<span class="hostpn-feature-group-label">' . esc_html($category_data['title']) . ':</span>';
                                        echo '<span class="hostpn-feature-count">' . count($active_features) . '</span>';
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </div>
                            
                            <div class="hostpn-accommodation-actions">
                                <a href="<?php the_permalink(); ?>" class="hostpn-btn hostpn-btn-primary">
                                    <?php esc_html_e('View Details', 'hostpn'); ?>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
                
                <div class="hostpn-pagination">
                    <?php
                    echo paginate_links([
                        'prev_text' => '<i class="material-icons-outlined">chevron_left</i>',
                        'next_text' => '<i class="material-icons-outlined">chevron_right</i>',
                        'class' => 'hostpn-pagination-links'
                    ]);
                    ?>
                </div>
                
            <?php else : ?>
                <div class="hostpn-no-accommodations">
                    <div class="hostpn-no-accommodations-content">
                        <i class="material-icons-outlined hostpn-icon-large">home</i>
                        <h3><?php esc_html_e('No accommodations found', 'hostpn'); ?></h3>
                        <p><?php esc_html_e('Sorry, no accommodations match your criteria. Please try again later.', 'hostpn'); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
