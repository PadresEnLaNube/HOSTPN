<?php
/**
 * Single template for accommodation post type
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    hostpn
 * @subpackage hostpn/templates/public
 */

get_header(); ?>

<div class="hostpn-accommodation-single-wrapper">
    <div class="hostpn-container">
        <?php while (have_posts()) : the_post(); ?>
            <?php
            $accommodation_id = get_the_ID();
            
            // Use global features constant
            $accommodation_features = HOSTPN_ACCOMMODATION_FEATURES;
            
            $accommodation_code = get_post_meta($accommodation_id, 'hostpn_accommodation_code', true);
            $accommodation_type = get_post_meta($accommodation_id, 'hostpn_accommodation_type', true);
            $accommodation_country = get_post_meta($accommodation_id, 'hostpn_accommodation_country', true);
            $accommodation_postal_code = get_post_meta($accommodation_id, 'hostpn_accommodation_postal_code', true);
            $accommodation_city = get_post_meta($accommodation_id, 'hostpn_accommodation_city', true);
            $thumbnail = get_the_post_thumbnail($accommodation_id, 'large');
            ?>
            
            <article class="hostpn-accommodation-single" data-accommodation-id="<?php echo esc_attr($accommodation_id); ?>">
                <!-- Header Section -->
                <header class="hostpn-accommodation-header">
                    <div class="hostpn-accommodation-header-content">
                        <h1 class="hostpn-accommodation-title"><?php the_title(); ?></h1>
                        
                        <?php if ($accommodation_code || $accommodation_type) : ?>
                            <div class="hostpn-accommodation-meta">
                                <?php if ($accommodation_code) : ?>
                                    <span class="hostpn-meta-item">
                                        <i class="material-icons-outlined hostpn-icon-small">tag</i>
                                        <span class="hostpn-label"><?php esc_html_e('Code:', 'hostpn'); ?></span>
                                        <span class="hostpn-value"><?php echo esc_html($accommodation_code); ?></span>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($accommodation_type) : ?>
                                    <span class="hostpn-meta-item">
                                        <i class="material-icons-outlined hostpn-icon-small">category</i>
                                        <span class="hostpn-label"><?php esc_html_e('Type:', 'hostpn'); ?></span>
                                        <span class="hostpn-value"><?php echo esc_html($accommodation_type); ?></span>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($accommodation_city || $accommodation_country) : ?>
                            <div class="hostpn-accommodation-location">
                                <i class="material-icons-outlined hostpn-icon-medium">location_on</i>
                                <div class="hostpn-location-details">
                                    <div class="hostpn-city-country">
                                        <?php
                                        $location_parts = array_filter([$accommodation_postal_code, $accommodation_city, $accommodation_country]);
                                        echo esc_html(implode(', ', $location_parts));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($thumbnail) : ?>
                        <div class="hostpn-accommodation-hero-image">
                            <?php echo wp_kses_post($thumbnail); ?>
                        </div>
                    <?php endif; ?>
                </header>
                
                <!-- Main Content -->
                <div class="hostpn-accommodation-main">
                    <div class="hostpn-accommodation-content">
                        <div class="hostpn-accommodation-description">
                            <h2><?php esc_html_e('Description', 'hostpn'); ?></h2>
                            <?php the_content(); ?>
                        </div>
                        
                        <!-- Rules and Conditions -->
                        <?php
                        $accommodation_rules = get_post_meta($accommodation_id, 'hostpn_accommodation_rules', true);
                        $checkin_conditions = get_post_meta($accommodation_id, 'hostpn_checkin_conditions', true);
                        $checkout_conditions = get_post_meta($accommodation_id, 'hostpn_checkout_conditions', true);
                        ?>
                        
                        <?php if ($accommodation_rules || $checkin_conditions || $checkout_conditions) : ?>
                            <div class="hostpn-accommodation-rules-conditions">
                                <h2><?php esc_html_e('Rules & Conditions', 'hostpn'); ?></h2>
                                
                                <?php if ($accommodation_rules) : ?>
                                    <div class="hostpn-rules-section">
                                        <h3><?php esc_html_e('Accommodation Rules', 'hostpn'); ?></h3>
                                        <div class="hostpn-rules-content">
                                            <?php echo wp_kses_post($accommodation_rules); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($checkin_conditions) : ?>
                                    <div class="hostpn-checkin-section">
                                        <h3><?php esc_html_e('Check-in Conditions', 'hostpn'); ?></h3>
                                        <div class="hostpn-checkin-content">
                                            <?php echo wp_kses_post($checkin_conditions); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($checkout_conditions) : ?>
                                    <div class="hostpn-checkout-section">
                                        <h3><?php esc_html_e('Check-out Conditions', 'hostpn'); ?></h3>
                                        <div class="hostpn-checkout-content">
                                            <?php echo wp_kses_post($checkout_conditions); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Parking and Internet -->
                        <?php
                        $parking_description = get_post_meta($accommodation_id, 'hostpn_parking_description', true);
                        $internet_description = get_post_meta($accommodation_id, 'hostpn_internet_description', true);
                        ?>
                        
                        <?php if ($parking_description || $internet_description) : ?>
                            <div class="hostpn-accommodation-services">
                                <h2><?php esc_html_e('Services & Amenities', 'hostpn'); ?></h2>
                                
                                <?php if ($parking_description) : ?>
                                    <div class="hostpn-parking-section">
                                        <h3>
                                            <i class="material-icons-outlined hostpn-icon-small">local_parking</i>
                                            <?php esc_html_e('Parking', 'hostpn'); ?>
                                        </h3>
                                        <div class="hostpn-parking-content">
                                            <?php echo wp_kses_post($parking_description); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($internet_description) : ?>
                                    <div class="hostpn-internet-section">
                                        <h3>
                                            <i class="material-icons-outlined hostpn-icon-small">wifi</i>
                                            <?php esc_html_e('Internet', 'hostpn'); ?>
                                        </h3>
                                        <div class="hostpn-internet-content">
                                            <?php echo wp_kses_post($internet_description); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Sidebar with Features -->
                    <aside class="hostpn-accommodation-sidebar">
                        <div class="hostpn-accommodation-features">
                            <h3><?php esc_html_e('Accommodation Features', 'hostpn'); ?></h3>
                            
                            <?php
                            // Generate features using centralized array
                            foreach ($accommodation_features as $category_key => $category_data) {
                                $active_features = [];
                                
                                // Get active features for this category
                                foreach ($category_data['features'] as $meta_key => $description) {
                                    if (get_post_meta($accommodation_id, $meta_key, true)) {
                                        $active_features[$meta_key] = $description;
                                    }
                                }
                                
                                // Get custom features for this category
                                $custom_features_meta_key = 'hostpn_' . $category_key . '_additional_features';
                                $custom_features_field_key = 'hostpn_' . $category_key . '_custom_name';
                                $custom_features = get_post_meta($accommodation_id, $custom_features_field_key, true);
                                
                                // Only show categories that have active or custom features
                                if (!empty($active_features) || (!empty($custom_features) && is_array($custom_features))) :
                            ?>
                                <div class="hostpn-feature-category">
                                    <div class="hostpn-toggle-header" data-toggle="<?php echo esc_attr($category_key); ?>-features">
                                        <h4>
                                            <i class="material-icons-outlined hostpn-icon-small"><?php echo esc_attr($category_data['icon']); ?></i>
                                            <?php echo esc_html($category_data['title']); ?>
                                        </h4>
                                        <i class="material-icons-outlined hostpn-toggle-icon">expand_more</i>
                                    </div>
                                    <div class="hostpn-toggle-content hostpn-toggle-<?php echo esc_attr($category_key); ?>-features">
                                        <ul class="hostpn-feature-list">
                                            <?php 
                                            // Show standard features first
                                            foreach ($active_features as $meta_key => $description) : ?>
                                                <li class="hostpn-feature-item">
                                                    <i class="material-icons-outlined hostpn-icon-check">check</i>
                                                    <?php echo esc_html($description); ?>
                                                </li>
                                            <?php endforeach; 
                                            
                                            // Show custom features after
                                            if (!empty($custom_features) && is_array($custom_features)) :
                                                foreach ($custom_features as $feature) :
                                                    if (!empty($feature)) : ?>
                                                        <li class="hostpn-feature-item hostpn-custom-feature">
                                                            <i class="material-icons-outlined hostpn-icon-check">check</i>
                                                            <?php echo esc_html($feature); ?>
                                                        </li>
                                                    <?php endif;
                                                endforeach;
                                            endif;
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php 
                                endif;
                            }
                            ?>
                        </div>
                    </aside>
                </div>
                
                <!-- Navigation -->
                <nav class="hostpn-accommodation-navigation">
                    <div class="hostpn-nav-links">
                        <?php
                        $prev_post = get_previous_post();
                        $next_post = get_next_post();
                        ?>
                        
                        <?php if ($prev_post) : ?>
                            <div class="hostpn-nav-previous">
                                <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>">
                                    <i class="material-icons-outlined">arrow_back</i>
                                    <span><?php echo esc_html(get_the_title($prev_post->ID)); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($next_post) : ?>
                            <div class="hostpn-nav-next">
                                <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>">
                                    <span><?php echo esc_html(get_the_title($next_post->ID)); ?></span>
                                    <i class="material-icons-outlined">arrow_forward</i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="hostpn-back-to-archive">
                        <a href="<?php echo esc_url(get_post_type_archive_link('hostpn_accommodation')); ?>" class="hostpn-btn hostpn-btn-secondary">
                            <i class="material-icons-outlined">list</i>
                            <?php esc_html_e('Back to Accommodations', 'hostpn'); ?>
                        </a>
                    </div>
                </nav>
            </article>
        <?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>
