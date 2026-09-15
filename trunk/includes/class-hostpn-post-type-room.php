<?php
/**
 * Room creator.
 *
 * This class defines Room options, menus and templates.
 * Rooms are linked to Accommodations and represent individual rooms within them.
 *
 * @link       padresenlanube.com/
 * @since      1.0.83
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Post_Type_Room
{
    public function hostpn_room_get_fields($room_id = 0)
    {
        $hostpn_fields = [];
        $hostpn_fields['hostpn_room_title'] = [
            'id' => 'hostpn_room_title',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'hidden',
            'value' => __('Room', 'hostpn') . ' - ' . gmdate('Y-m-d H:i:s', current_time('timestamp')) . ' - ' . bin2hex(openssl_random_pseudo_bytes(4)),
        ];
        $hostpn_fields['hostpn_room_description'] = [
            'id' => 'hostpn_room_description',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'hidden',
            'value' => __('Room description', 'hostpn'),
        ];
        return $hostpn_fields;
    }

    public function hostpn_room_get_fields_meta($room_id = 0)
    {
        $hostpn_fields_meta = [];

        // Get accommodations for select
        $accommodations = get_posts([
            'post_type' => 'hostpn_accommodation',
            'post_status' => 'any',
            'numberposts' => -1,
            'fields' => 'ids',
        ]);
        $accommodation_options = [];
        foreach ($accommodations as $acc_id) {
            $accommodation_options[$acc_id] = get_the_title($acc_id);
        }

        $hostpn_fields_meta['hostpn_room_accommodation_id'] = [
            'id' => 'hostpn_room_accommodation_id',
            'class' => 'hostpn-select hostpn-width-100-percent',
            'input' => 'select',
            'required' => true,
            'options' => $accommodation_options,
            'label' => __('Accommodation', 'hostpn'),
            'placeholder' => __('Select accommodation', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_room_number'] = [
            'id' => 'hostpn_room_number',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'required' => true,
            'label' => __('Room number / identifier', 'hostpn'),
            'placeholder' => __('E.g.: 101, A, Suite 1...', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_room_floor'] = [
            'id' => 'hostpn_room_floor',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'label' => __('Floor', 'hostpn'),
            'placeholder' => __('E.g.: 1, Ground, Basement...', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_room_capacity'] = [
            'id' => 'hostpn_room_capacity',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'number',
            'min' => 1,
            'label' => __('Maximum capacity', 'hostpn'),
            'placeholder' => __('Number of guests', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_room_status'] = [
            'id' => 'hostpn_room_status',
            'class' => 'hostpn-select hostpn-width-100-percent',
            'input' => 'select',
            'options' => [
                'available' => __('Available', 'hostpn'),
                'occupied' => __('Occupied', 'hostpn'),
                'maintenance' => __('Maintenance', 'hostpn'),
            ],
            'label' => __('Status', 'hostpn'),
            'placeholder' => __('Select status', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_room_form'] = [
            'id' => 'hostpn_room_form',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'hidden',
            'value' => 'hostpn_room_form',
        ];
        $hostpn_fields_meta['hostpn_ajax_nonce'] = [
            'id' => 'hostpn_ajax_nonce',
            'input' => 'input',
            'type' => 'nonce',
        ];

        return $hostpn_fields_meta;
    }

    /**
     * Register Room post type.
     *
     * @since    1.0.83
     */
    public function hostpn_room_register_post_type()
    {
        $labels = [
            'name' => _x('Room', 'Post Type general name', 'hostpn'),
            'singular_name' => _x('Room', 'Post Type singular name', 'hostpn'),
            'menu_name' => __('Rooms', 'hostpn'),
            'parent_item_colon' => __('Parent Room', 'hostpn'),
            'all_items' => __('All Rooms', 'hostpn'),
            'view_item' => __('View Room', 'hostpn'),
            'add_new_item' => __('Add new Room', 'hostpn'),
            'add_new' => __('Add new Room', 'hostpn'),
            'edit_item' => __('Edit Room', 'hostpn'),
            'update_item' => __('Update Room', 'hostpn'),
            'search_items' => __('Search Rooms', 'hostpn'),
            'not_found' => __('No Rooms found', 'hostpn'),
            'not_found_in_trash' => __('No Rooms found in Trash', 'hostpn'),
        ];

        $args = [
            'labels' => $labels,
            'label' => __('Room', 'hostpn'),
            'description' => __('Rooms within accommodations', 'hostpn'),
            'supports' => ['title', 'author'],
            'hierarchical' => false,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => false,
            'show_in_nav_menus' => false,
            'show_in_admin_bar' => false,
            'menu_position' => 5,
            'can_export' => false,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability_type' => 'page',
            'show_in_rest' => false,
        ];

        register_post_type('hostpn_room', $args);
    }

    /**
     * Add Room dashboard metabox.
     *
     * @since    1.0.83
     */
    public function hostpn_room_add_meta_box()
    {
        add_meta_box(
            'hostpn_room_meta_box',
            __('Room details', 'hostpn'),
            [$this, 'hostpn_room_meta_box_function'],
            'hostpn_room',
            'normal',
            'high',
            ['__block_editor_compatible_meta_box' => true]
        );
    }

    /**
     * Defines Room dashboard contents.
     *
     * @since    1.0.83
     */
    public function hostpn_room_meta_box_function($post)
    {
        foreach (self::hostpn_room_get_fields() as $hostpn_field) {
            HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post', $post->ID);
        }

        foreach (self::hostpn_room_get_fields_meta() as $hostpn_field_meta) {
            HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field_meta, 'post', $post->ID);
        }
    }

    public function hostpn_room_save_post($post_id, $cpt, $update)
    {
        if ($cpt->post_type == 'hostpn_room' && array_key_exists('hostpn_room_form', $_POST)) {
            // Skip if AJAX form save handles it
            if (array_key_exists('hostpn_form_subtype', $_POST)) {
                return;
            }

            if (!array_key_exists('hostpn_ajax_nonce', $_POST)) {
                return;
            }

            if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['hostpn_ajax_nonce'])), 'hostpn-nonce')) {
                return;
            }

            // Capture previous status before saving
            $previous_status = get_post_meta($post_id, 'hostpn_room_status', true);

            foreach (array_merge(self::hostpn_room_get_fields(), self::hostpn_room_get_fields_meta()) as $hostpn_field) {
                $hostpn_input = array_key_exists('input', $hostpn_field) ? $hostpn_field['input'] : '';

                if (array_key_exists($hostpn_field['id'], $_POST)) {
                    $hostpn_value = HOSTPN_Forms::hostpn_sanitizer(
                        wp_unslash($_POST[$hostpn_field['id']]),
                        $hostpn_field['input'],
                        !empty($hostpn_field['type']) ? $hostpn_field['type'] : '',
                        $hostpn_field
                    );

                    if (!empty($hostpn_input)) {
                        switch ($hostpn_input) {
                            case 'input':
                                if (array_key_exists('type', $hostpn_field) && $hostpn_field['type'] == 'checkbox') {
                                    if (isset($_POST[$hostpn_field['id']])) {
                                        update_post_meta($post_id, $hostpn_field['id'], $hostpn_value);
                                    } else {
                                        update_post_meta($post_id, $hostpn_field['id'], '');
                                    }
                                } else {
                                    update_post_meta($post_id, $hostpn_field['id'], $hostpn_value);
                                }
                                break;
                            case 'select':
                                update_post_meta($post_id, $hostpn_field['id'], $hostpn_value);
                                break;
                            default:
                                update_post_meta($post_id, $hostpn_field['id'], $hostpn_value);
                                break;
                        }
                    }
                } else {
                    update_post_meta($post_id, $hostpn_field['id'], '');
                }
            }

            clean_post_cache($post_id);

            // Notify waitlist if status changed to available from occupied
            $new_status = get_post_meta($post_id, 'hostpn_room_status', true);
            if ($previous_status === 'occupied' && $new_status === 'available') {
                HOSTPN_Notifications::hostpn_notify_room_available($post_id);
            }
        }
    }

    public function hostpn_room_form_save($element_id, $key_value, $hostpn_form_type, $hostpn_form_subtype, $post_type)
    {
        if ($post_type == 'hostpn_room') {
            switch ($hostpn_form_type) {
                case 'post':
                    switch ($hostpn_form_subtype) {
                        case 'post_new':
                            if (!empty($key_value)) {
                                foreach ($key_value as $key => $value) {
                                    if (strpos($key, 'hostpn_') !== false) {
                                        ${$key} = $value;
                                    }
                                }
                            }

                            $room_user_id = get_current_user_id();

                            if (!empty($element_id) && get_post($element_id)) {
                                $room_id = $element_id;
                            } else {
                                $post_functions = new HOSTPN_Functions_Post();
                                $hostpn_room_title = !empty($hostpn_room_title) ? $hostpn_room_title : __('Room', 'hostpn') . ' - ' . gmdate('Y-m-d H:i:s', current_time('timestamp'));
                                $hostpn_room_description = !empty($hostpn_room_description) ? $hostpn_room_description : '';
                                $room_id = $post_functions->hostpn_insert_post(
                                    esc_html($hostpn_room_title),
                                    $hostpn_room_description,
                                    '',
                                    sanitize_title(esc_html($hostpn_room_title)),
                                    $post_type,
                                    'publish',
                                    $room_user_id
                                );
                            }

                            if (!empty($key_value)) {
                                foreach ($key_value as $key => $value) {
                                    update_post_meta($room_id, $key, $value);
                                }
                            }

                            // Build title from room number + accommodation
                            $room_number = !empty($key_value['hostpn_room_number']) ? $key_value['hostpn_room_number'] : '';
                            $acc_id = !empty($key_value['hostpn_room_accommodation_id']) ? $key_value['hostpn_room_accommodation_id'] : 0;
                            $acc_title = $acc_id ? get_the_title($acc_id) : '';
                            $title = trim($room_number . ($acc_title ? ' - ' . $acc_title : ''));
                            if (!empty($title)) {
                                wp_update_post(['ID' => $room_id, 'post_title' => $title, 'post_author' => $room_user_id]);
                            }

                            clean_post_cache($room_id);
                            break;

                        case 'post_edit':
                            if (!empty($key_value)) {
                                foreach ($key_value as $key => $value) {
                                    if (strpos($key, 'hostpn_') !== false) {
                                        ${$key} = $value;
                                    }
                                }
                            }

                            $room_id = $element_id;

                            // Capture previous status before saving
                            $previous_status = get_post_meta($room_id, 'hostpn_room_status', true);

                            if (!empty($key_value)) {
                                foreach ($key_value as $key => $value) {
                                    update_post_meta($room_id, $key, $value);
                                }
                            }

                            // Update title
                            $room_number = !empty($key_value['hostpn_room_number']) ? $key_value['hostpn_room_number'] : '';
                            $acc_id = !empty($key_value['hostpn_room_accommodation_id']) ? $key_value['hostpn_room_accommodation_id'] : 0;
                            $acc_title = $acc_id ? get_the_title($acc_id) : '';
                            $title = trim($room_number . ($acc_title ? ' - ' . $acc_title : ''));
                            if (!empty($title)) {
                                wp_update_post(['ID' => $room_id, 'post_title' => $title]);
                            }

                            // Notify waitlist if status changed to available from occupied
                            $new_status = !empty($key_value['hostpn_room_status']) ? $key_value['hostpn_room_status'] : '';
                            if ($previous_status === 'occupied' && $new_status === 'available') {
                                HOSTPN_Notifications::hostpn_notify_room_available($room_id);
                            }
                            break;
                    }
            }
        }
    }

    /**
     * Get rooms by accommodation ID.
     *
     * @param int $accommodation_id
     * @return array Array of room post IDs.
     */
    public static function hostpn_get_rooms_by_accommodation($accommodation_id)
    {
        return get_posts([
            'post_type' => 'hostpn_room',
            'post_status' => 'any',
            'numberposts' => -1,
            'fields' => 'ids',
            'meta_key' => 'hostpn_room_accommodation_id',
            'meta_value' => $accommodation_id,
        ]);
    }

    /**
     * Get rooms as options for select fields.
     *
     * @param int $accommodation_id Optional. Filter by accommodation.
     * @return array
     */
    public static function hostpn_get_rooms_options($accommodation_id = 0)
    {
        $args = [
            'post_type' => 'hostpn_room',
            'post_status' => 'any',
            'numberposts' => -1,
            'fields' => 'ids',
        ];

        if (!empty($accommodation_id)) {
            $args['meta_key'] = 'hostpn_room_accommodation_id';
            $args['meta_value'] = $accommodation_id;
        }

        $rooms = get_posts($args);
        $options = [];
        foreach ($rooms as $room_id) {
            $room_number = get_post_meta($room_id, 'hostpn_room_number', true);
            $room_floor = get_post_meta($room_id, 'hostpn_room_floor', true);
            $label = $room_number;
            if (!empty($room_floor)) {
                $label .= ' (' . __('Floor', 'hostpn') . ' ' . $room_floor . ')';
            }
            $options[$room_id] = $label;
        }
        return $options;
    }

    public function hostpn_room_custom_columns($columns)
    {
        $new_columns = [];
        $new_columns['cb'] = $columns['cb'];
        $new_columns['room_info'] = __('Room', 'hostpn');
        $new_columns['room_accommodation'] = __('Accommodation', 'hostpn');
        $new_columns['room_capacity'] = __('Capacity', 'hostpn');
        $new_columns['room_status'] = __('Status', 'hostpn');
        $new_columns['creation_date'] = __('Date', 'hostpn');
        return $new_columns;
    }

    public function hostpn_room_sortable_columns($columns)
    {
        $columns['room_info'] = 'title';
        $columns['creation_date'] = 'date';
        return $columns;
    }

    public function hostpn_room_custom_column_content($column, $post_id)
    {
        switch ($column) {
            case 'room_info':
                $room_number = get_post_meta($post_id, 'hostpn_room_number', true);
                $room_floor = get_post_meta($post_id, 'hostpn_room_floor', true);
                $edit_link = get_edit_post_link($post_id);
                echo '<p><a href="' . esc_url($edit_link) . '">';
                echo '<strong>' . esc_html($room_number) . '</strong>';
                if ($room_floor) {
                    echo ' - ' . esc_html(__('Floor', 'hostpn')) . ' ' . esc_html($room_floor);
                }
                echo '</a></p>';
                break;

            case 'room_accommodation':
                $acc_id = get_post_meta($post_id, 'hostpn_room_accommodation_id', true);
                if ($acc_id) {
                    $edit_link = get_edit_post_link($acc_id);
                    echo '<p><a href="' . esc_url($edit_link) . '">' . esc_html(get_the_title($acc_id)) . '</a></p>';
                }
                break;

            case 'room_capacity':
                $capacity = get_post_meta($post_id, 'hostpn_room_capacity', true);
                if ($capacity) {
                    echo '<p>' . esc_html($capacity) . '</p>';
                }
                break;

            case 'room_status':
                $status = get_post_meta($post_id, 'hostpn_room_status', true);
                $status_labels = [
                    'available' => __('Available', 'hostpn'),
                    'occupied' => __('Occupied', 'hostpn'),
                    'maintenance' => __('Maintenance', 'hostpn'),
                ];
                $label = isset($status_labels[$status]) ? $status_labels[$status] : $status;
                echo '<p>' . esc_html($label) . '</p>';
                break;

            case 'creation_date':
                $post = get_post($post_id);
                echo '<p>' . esc_html(date_i18n(get_option('date_format'), strtotime($post->post_date))) . '</p>';
                break;
        }
    }

    // AJAX popup methods

    public function hostpn_room_list()
    {
        $room_atts = [
            'fields' => 'ids',
            'numberposts' => -1,
            'post_type' => 'hostpn_room',
            'post_status' => 'any',
            'orderby' => 'date',
            'order' => 'DESC',
        ];

        if (!HOSTPN_Functions_User::is_user_admin(get_current_user_id())) {
            $room_atts['author'] = get_current_user_id();
        }

        $rooms = get_posts($room_atts);

        ob_start();
        ?>
        <ul class="hostpn-rooms hostpn-list-style-none hostpn-margin-auto">
            <?php if (!empty($rooms)): ?>
                <?php foreach ($rooms as $room_id): ?>
                    <?php
                    $room_number = get_post_meta($room_id, 'hostpn_room_number', true);
                    $acc_id = get_post_meta($room_id, 'hostpn_room_accommodation_id', true);
                    $acc_title = $acc_id ? get_the_title($acc_id) : '';
                    $room_status = get_post_meta($room_id, 'hostpn_room_status', true);
                    ?>
                    <li class="hostpn-room hostpn-mb-10" data-hostpn_room-id="<?php echo esc_attr($room_id); ?>"
                        data-hostpn-sort-name="<?php echo esc_attr(strtolower($room_number)); ?>"
                        data-hostpn-sort-date="<?php echo esc_attr(get_post_field('post_date', $room_id)); ?>">
                        <div class="hostpn-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-60-percent">
                                <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none"
                                    data-hostpn-popup-id="hostpn-popup-room-view" data-hostpn-ajax-type="hostpn_room_view">
                                    <span><?php echo esc_html($room_number); ?></span>
                                    <?php if ($acc_title): ?>
                                        <small> - <?php echo esc_html($acc_title); ?></small>
                                    <?php endif; ?>
                                </a>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-right hostpn-position-relative">
                                <i class="material-icons-outlined hostpn-menu-more-btn hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-font-size-30">more_vert</i>
                                <div class="hostpn-menu-more hostpn-z-index-99 hostpn-display-none-soft">
                                    <ul class="hostpn-list-style-none">
                                        <li>
                                            <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none"
                                                data-hostpn-popup-id="hostpn-popup-room-view" data-hostpn-ajax-type="hostpn_room_view">
                                                <div class="hostpn-display-table hostpn-width-100-percent">
                                                    <div class="hostpn-display-inline-table hostpn-width-70-percent"><p><?php esc_html_e('View Room', 'hostpn'); ?></p></div>
                                                    <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-right"><i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">visibility</i></div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none"
                                                data-hostpn-popup-id="hostpn-popup-room-edit" data-hostpn-ajax-type="hostpn_room_edit">
                                                <div class="hostpn-display-table hostpn-width-100-percent">
                                                    <div class="hostpn-display-inline-table hostpn-width-70-percent"><p><?php esc_html_e('Edit Room', 'hostpn'); ?></p></div>
                                                    <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-right"><i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">edit</i></div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="hostpn-popup-open" data-hostpn-popup-id="hostpn-popup-room-remove">
                                                <div class="hostpn-display-table hostpn-width-100-percent">
                                                    <div class="hostpn-display-inline-table hostpn-width-70-percent"><p><?php esc_html_e('Remove Room', 'hostpn'); ?></p></div>
                                                    <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-right"><i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">delete</i></div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach ?>
            <?php endif ?>
        </ul>
        <?php
        $hostpn_return_string = ob_get_contents();
        ob_end_clean();
        return $hostpn_return_string;
    }

    public function hostpn_room_view($room_id)
    {
        ob_start();
        ?>
        <div class="room-view hostpn-p-30" data-hostpn_room-id="<?php echo esc_attr($room_id); ?>">
            <h4 class="hostpn-text-align-center">
                <?php echo esc_html(get_post_meta($room_id, 'hostpn_room_number', true)); ?>
            </h4>

            <div class="room-view hostpn-mt-30">
                <?php foreach (self::hostpn_room_get_fields_meta() as $hostpn_field): ?>
                    <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post', $room_id, 1), HOSTPN_KSES); ?>
                <?php endforeach ?>

                <div class="hostpn-text-align-right hostpn-room" data-hostpn_room-id="<?php echo esc_attr($room_id); ?>">
                    <a href="#" class="hostpn-btn hostpn-btn-mini hostpn-popup-open-ajax"
                        data-hostpn-popup-id="hostpn-popup-room-edit"
                        data-hostpn-ajax-type="hostpn_room_edit"><?php esc_html_e('Edit Room', 'hostpn'); ?></a>
                </div>
            </div>
        </div>
        <?php
        $hostpn_return_string = ob_get_contents();
        ob_end_clean();
        return $hostpn_return_string;
    }

    public function hostpn_room_new()
    {
        ob_start();
        ?>
        <div class="room-new hostpn-p-30">
            <h4 class="hostpn-mb-30"><?php esc_html_e('Add new Room', 'hostpn'); ?></h4>

            <form action="" method="post" id="hostpn-form" class="hostpn-form">
                <?php foreach (self::hostpn_room_get_fields() as $hostpn_field): ?>
                    <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post'), HOSTPN_KSES); ?>
                <?php endforeach ?>

                <?php foreach (self::hostpn_room_get_fields_meta() as $hostpn_field_meta): ?>
                    <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field_meta, 'post'), HOSTPN_KSES); ?>
                <?php endforeach ?>

                <div class="hostpn-text-align-right">
                    <input class="hostpn-btn" data-hostpn-type="post" data-hostpn-subtype="post_new"
                        data-hostpn-post-type="hostpn_room" type="submit"
                        value="<?php esc_attr_e('Create Room', 'hostpn'); ?>" />
                </div>
            </form>
        </div>
        <?php
        $hostpn_return_string = ob_get_contents();
        ob_end_clean();
        return $hostpn_return_string;
    }

    public function hostpn_room_edit($room_id)
    {
        ob_start();
        ?>
        <div class="room-edit hostpn-p-30">
            <p class="hostpn-text-align-center hostpn-mb-0"><?php esc_html_e('Editing', 'hostpn'); ?></p>
            <h4 class="hostpn-text-align-center hostpn-mb-30"><?php echo esc_html(get_the_title($room_id)); ?></h4>

            <form action="" method="post" id="hostpn-form" class="hostpn-form">
                <?php foreach (self::hostpn_room_get_fields($room_id) as $hostpn_field): ?>
                    <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post', $room_id), HOSTPN_KSES); ?>
                <?php endforeach ?>

                <?php foreach (self::hostpn_room_get_fields_meta() as $hostpn_field_meta): ?>
                    <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field_meta, 'post', $room_id), HOSTPN_KSES); ?>
                <?php endforeach ?>

                <div class="hostpn-text-align-right">
                    <input class="hostpn-btn" type="submit" data-hostpn-type="post" data-hostpn-subtype="post_edit"
                        data-hostpn-post-type="hostpn_room" data-hostpn-post-id="<?php echo esc_attr($room_id); ?>"
                        value="<?php esc_attr_e('Save Room', 'hostpn'); ?>" />
                </div>
            </form>
        </div>
        <?php
        $hostpn_return_string = ob_get_contents();
        ob_end_clean();
        return $hostpn_return_string;
    }

    /**
     * AJAX: Subscribe email to room availability waitlist.
     * Registered for both wp_ajax and wp_ajax_nopriv.
     */
    public static function hostpn_room_availability_subscribe()
    {
        if (empty($_POST['hostpn_ajax_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['hostpn_ajax_nonce'])), 'hostpn-nonce')) {
            echo wp_json_encode([
                'error_key' => 'nonce_error',
                'error_content' => esc_html(__('Security check failed.', 'hostpn')),
            ]);
            wp_die();
        }

        $room_id = !empty($_POST['hostpn_room_id']) ? absint($_POST['hostpn_room_id']) : 0;
        $email = !empty($_POST['hostpn_notify_email']) ? sanitize_email(wp_unslash($_POST['hostpn_notify_email'])) : '';

        if (empty($room_id) || !get_post($room_id) || get_post_type($room_id) !== 'hostpn_room') {
            echo wp_json_encode([
                'error_key' => 'invalid_room',
                'error_content' => esc_html(__('Room not found.', 'hostpn')),
            ]);
            wp_die();
        }

        if (!is_email($email)) {
            echo wp_json_encode([
                'error_key' => 'invalid_email',
                'error_content' => esc_html(__('Please enter a valid email.', 'hostpn')),
            ]);
            wp_die();
        }

        $room_status = get_post_meta($room_id, 'hostpn_room_status', true);
        if ($room_status !== 'occupied') {
            echo wp_json_encode([
                'error_key' => 'not_occupied',
                'error_content' => esc_html(__('This room is not currently occupied.', 'hostpn')),
            ]);
            wp_die();
        }

        $waitlist = get_post_meta($room_id, 'hostpn_room_waitlist', true);
        if (!is_array($waitlist)) {
            $waitlist = [];
        }

        if (in_array($email, $waitlist, true)) {
            echo wp_json_encode([
                'error_key' => 'already_subscribed',
                'error_content' => esc_html(__('You are already on the waiting list.', 'hostpn')),
            ]);
            wp_die();
        }

        $waitlist[] = $email;
        update_post_meta($room_id, 'hostpn_room_waitlist', $waitlist);

        echo wp_json_encode([
            'error_key' => '',
            'message' => esc_html(__('We will notify you when this room becomes available.', 'hostpn')),
        ]);
        wp_die();
    }

    /**
     * AJAX: List rooms filtered by accommodation ID.
     */
    public function hostpn_room_list_by_accommodation()
    {
        if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['hostpn_ajax_nonce'])), 'hostpn-nonce')) {
            echo wp_json_encode(['error_key' => 'nonce_error']);
            exit;
        }

        $accommodation_id = !empty($_POST['hostpn_accommodation_id']) ? absint($_POST['hostpn_accommodation_id']) : 0;
        $rooms = self::hostpn_get_rooms_options($accommodation_id);

        echo wp_json_encode([
            'error_key' => '',
            'rooms' => $rooms,
        ]);
        exit;
    }
}
