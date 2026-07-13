<?php
/**
 * Debug template for accommodation post type
 * This file helps diagnose issues with the accommodation post type
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<div class="hostpn-debug-wrapper" style="padding: 40px; max-width: 1200px; margin: 0 auto; font-family: monospace;">
    <h1>HOSTPN Accommodation Debug Information</h1>
    
    <h2>Post Type Information</h2>
    <?php
    $post_type_obj = get_post_type_object('hostpn_accommodation');
    if ($post_type_obj) {
        echo '<p><strong>Post Type:</strong> ' . $post_type_obj->name . '</p>';
        echo '<p><strong>Label:</strong> ' . $post_type_obj->label . '</p>';
        echo '<p><strong>Public:</strong> ' . ($post_type_obj->public ? 'true' : 'false') . '</p>';
        echo '<p><strong>Has Archive:</strong> ' . ($post_type_obj->has_archive ? 'true' : 'false') . '</p>';
        echo '<p><strong>Publicly Queryable:</strong> ' . ($post_type_obj->publicly_queryable ? 'true' : 'false') . '</p>';
        echo '<p><strong>Rewrite Slug:</strong> ' . $post_type_obj->rewrite['slug'] . '</p>';
        echo '<p><strong>Rewrite With Front:</strong> ' . ($post_type_obj->rewrite['with_front'] ? 'true' : 'false') . '</p>';
    } else {
        echo '<p style="color: red;"><strong>ERROR:</strong> Post type "hostpn_accommodation" is not registered!</p>';
    }
    ?>
    
    <h2>Current Page Information</h2>
    <?php
    echo '<p><strong>Current URL:</strong> ' . $_SERVER['REQUEST_URI'] . '</p>';
    echo '<p><strong>Post Type:</strong> ' . get_post_type() . '</p>';
    echo '<p><strong>Is Single:</strong> ' . (is_single() ? 'true' : 'false') . '</p>';
    echo '<p><strong>Is Archive:</strong> ' . (is_archive() ? 'true' : 'false') . '</p>';
    echo '<p><strong>Is Post Type Archive:</strong> ' . (is_post_type_archive('hostpn_accommodation') ? 'true' : 'false') . '</p>';
    echo '<p><strong>Is Singular:</strong> ' . (is_singular('hostpn_accommodation') ? 'true' : 'false') . '</p>';
    
    global $wp_query;
    echo '<p><strong>Query Vars:</strong> ' . print_r($wp_query->query_vars, true) . '</p>';
    ?>
    
    <h2>Accommodation Posts</h2>
    <?php
    $accommodations = get_posts([
        'post_type' => 'hostpn_accommodation',
        'post_status' => 'publish',
        'numberposts' => -1
    ]);
    
    if (!empty($accommodations)) {
        echo '<p><strong>Found ' . count($accommodations) . ' accommodation(s):</strong></p>';
        echo '<ul>';
        foreach ($accommodations as $accommodation) {
            $permalink = get_permalink($accommodation->ID);
            echo '<li>';
            echo '<strong>' . $accommodation->post_title . '</strong> (ID: ' . $accommodation->ID . ')';
            echo '<br>Permalink: <a href="' . $permalink . '">' . $permalink . '</a>';
            echo '<br>Slug: ' . $accommodation->post_name;
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p style="color: orange;"><strong>WARNING:</strong> No accommodation posts found!</p>';
    }
    ?>
    
    <h2>Rewrite Rules</h2>
    <?php
    global $wp_rewrite;
    $rules = $wp_rewrite->rewrite_rules();
    
    // Filter rules related to accommodation
    $accommodation_rules = [];
    foreach ($rules as $rule => $rewrite) {
        if (strpos($rule, 'accommodations') !== false || strpos($rewrite, 'hostpn_accommodation') !== false) {
            $accommodation_rules[$rule] = $rewrite;
        }
    }
    
    if (!empty($accommodation_rules)) {
        echo '<p><strong>Accommodation-related rewrite rules:</strong></p>';
        echo '<ul>';
        foreach ($accommodation_rules as $rule => $rewrite) {
            echo '<li><strong>' . $rule . '</strong> → ' . $rewrite . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p style="color: orange;"><strong>WARNING:</strong> No accommodation rewrite rules found!</p>';
    }
    ?>
    
    <h2>Template Files</h2>
    <?php
    $template_files = [
        'single-hostpn_accommodation.php' => defined('HOSTPN_DIR') ? HOSTPN_DIR . 'templates/public/single-hostpn_accommodation.php' : plugin_dir_path(dirname(dirname(__FILE__))) . 'templates/public/single-hostpn_accommodation.php',
        'archive-hostpn_accommodation.php' => defined('HOSTPN_DIR') ? HOSTPN_DIR . 'templates/public/archive-hostpn_accommodation.php' : plugin_dir_path(dirname(dirname(__FILE__))) . 'templates/public/archive-hostpn_accommodation.php'
    ];
    
    foreach ($template_files as $filename => $filepath) {
        if (file_exists($filepath)) {
            echo '<p style="color: green;">✓ ' . $filename . ' exists at: ' . $filepath . '</p>';
        } else {
            echo '<p style="color: red;">✗ ' . $filename . ' NOT found at: ' . $filepath . '</p>';
        }
    }
    ?>
    
    <h2>Actions & Filters</h2>
    <?php
    global $wp_filter;
    
    $template_filters = [
        'single_template' => isset($wp_filter['single_template']) ? 'Registered' : 'Not Registered',
        'archive_template' => isset($wp_filter['archive_template']) ? 'Registered' : 'Not Registered'
    ];
    
    foreach ($template_filters as $filter => $status) {
        echo '<p><strong>' . $filter . ':</strong> ' . $status . '</p>';
    }
    ?>
    
    <h2>Recommendations</h2>
    <ul>
        <li>Make sure to flush rewrite rules by going to Settings > Permalinks and clicking "Save Changes"</li>
        <li>Check that the accommodation post type is registered before the template filters are applied</li>
        <li>Verify that the template files are in the correct location</li>
        <li>Check for any conflicting rewrite rules from other plugins or themes</li>
    </ul>
</div>

<?php get_footer(); ?>
