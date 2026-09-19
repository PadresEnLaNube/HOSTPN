<?php
/**
 * Private storage manager.
 *
 * Handles private file storage for contract PDFs, with .htaccess protection
 * and PHP-mediated access control.
 *
 * @link       padresenlanube.com/
 * @since      1.0.83
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Private_Storage
{
    /**
     * Base directory name for private storage.
     */
    const PRIVATE_DIR_NAME = 'hostpn-private';

    /**
     * Contracts subdirectory.
     */
    const CONTRACTS_DIR = 'contracts';

    /**
     * Expenses subdirectory.
     */
    const EXPENSES_DIR = 'expenses';

    /**
     * Get the base private directory path.
     *
     * @return string Full path to the private directory.
     */
    public static function hostpn_get_private_dir()
    {
        $upload_dir = wp_upload_dir();
        $private_dir = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . self::PRIVATE_DIR_NAME;

        if (!file_exists($private_dir)) {
            wp_mkdir_p($private_dir);
            self::hostpn_protect_directory($private_dir);
        }

        return $private_dir;
    }

    /**
     * Get the contract-specific directory path.
     *
     * @param int $contract_id Contract post ID.
     * @return string Full path to the contract directory.
     */
    public static function hostpn_get_contract_dir($contract_id)
    {
        $base = self::hostpn_get_private_dir();
        $contract_dir = $base . DIRECTORY_SEPARATOR . self::CONTRACTS_DIR . DIRECTORY_SEPARATOR . absint($contract_id);

        if (!file_exists($contract_dir)) {
            wp_mkdir_p($contract_dir);
        }

        return $contract_dir;
    }

    /**
     * Get the accommodation expense directory path.
     *
     * @param int $accommodation_id Accommodation post ID.
     * @return string Full path to the expense directory.
     */
    public static function hostpn_get_expense_dir($accommodation_id)
    {
        $base = self::hostpn_get_private_dir();
        $expense_dir = $base . DIRECTORY_SEPARATOR . self::EXPENSES_DIR . DIRECTORY_SEPARATOR . absint($accommodation_id);

        if (!file_exists($expense_dir)) {
            wp_mkdir_p($expense_dir);
        }

        return $expense_dir;
    }

    /**
     * Protect a directory with .htaccess and index.php.
     *
     * @param string $dir Directory path to protect.
     */
    private static function hostpn_protect_directory($dir)
    {
        // .htaccess
        $htaccess_file = $dir . DIRECTORY_SEPARATOR . '.htaccess';
        if (!file_exists($htaccess_file)) {
            $htaccess_content = "Order deny,allow\nDeny from all\n";
            file_put_contents($htaccess_file, $htaccess_content);
        }

        // index.php
        $index_file = $dir . DIRECTORY_SEPARATOR . 'index.php';
        if (!file_exists($index_file)) {
            file_put_contents($index_file, '<?php // Silence is golden.');
        }
    }

    /**
     * Store a generated contract PDF.
     *
     * @param int    $contract_id Contract post ID.
     * @param string $pdf_content Raw PDF content.
     * @param string $filename    Filename for the PDF.
     * @return string|false Full path to stored file, or false on failure.
     */
    public static function hostpn_store_contract_pdf($contract_id, $pdf_content, $filename)
    {
        $dir = self::hostpn_get_contract_dir($contract_id);
        $filepath = $dir . DIRECTORY_SEPARATOR . sanitize_file_name($filename);

        $result = file_put_contents($filepath, $pdf_content);
        if ($result === false) {
            return false;
        }

        return $filepath;
    }

    /**
     * Store a signed PDF uploaded by a guest.
     *
     * @param int   $contract_id Contract post ID.
     * @param array $file        $_FILES array entry.
     * @return string|WP_Error Full path to stored file, or WP_Error on failure.
     */
    public static function hostpn_store_signed_pdf($contract_id, $file)
    {
        // Validate file type
        $allowed_types = ['application/pdf'];
        $file_type = wp_check_filetype($file['name']);

        if (empty($file_type['ext']) || $file_type['ext'] !== 'pdf') {
            return new WP_Error('invalid_type', __('Only PDF files are allowed.', 'hostpn'));
        }

        if (!in_array($file['type'], $allowed_types, true)) {
            return new WP_Error('invalid_type', __('Only PDF files are allowed.', 'hostpn'));
        }

        // Check for upload errors
        if (!empty($file['error'])) {
            return new WP_Error('upload_error', __('File upload failed.', 'hostpn'));
        }

        $dir = self::hostpn_get_contract_dir($contract_id);
        $version = get_post_meta($contract_id, 'hostpn_contract_version', true) ?: 1;
        $filename = 'signed-v' . absint($version) . '.pdf';
        $filepath = $dir . DIRECTORY_SEPARATOR . $filename;

        $moved = move_uploaded_file($file['tmp_name'], $filepath);
        if (!$moved) {
            return new WP_Error('move_failed', __('Could not save the uploaded file.', 'hostpn'));
        }

        // Update contract meta
        update_post_meta($contract_id, 'hostpn_contract_signed_pdf_attachment_id', $filename);
        update_post_meta($contract_id, 'hostpn_contract_signed_date', current_time('Y-m-d'));
        update_post_meta($contract_id, 'hostpn_contract_status', 'signed');

        return $filepath;
    }

    /**
     * Check if user has access to a contract's files.
     *
     * @param int $contract_id Contract post ID.
     * @param int $user_id     WordPress user ID.
     * @return bool
     */
    public static function hostpn_user_can_access_contract($contract_id, $user_id)
    {
        // Admin can access all
        if (HOSTPN_Functions_User::is_user_admin($user_id)) {
            return true;
        }

        // Check if user is the contract owner (post_author)
        $contract_post = get_post($contract_id);
        if ($contract_post && (int) $contract_post->post_author === (int) $user_id) {
            return true;
        }

        // Check if user has a guest linked to this contract
        $contract_guest_id = get_post_meta($contract_id, 'hostpn_contract_guest_id', true);
        if (!$contract_guest_id) {
            return false;
        }

        $user_guest_id = HOSTPN_Post_Type_Contract::hostpn_get_guest_id_for_user($user_id);

        return $user_guest_id && (int) $user_guest_id === (int) $contract_guest_id;
    }

    /**
     * Serve a private file with proper headers.
     *
     * @param int    $contract_id Contract post ID.
     * @param string $filename    Filename to serve.
     * @return void Outputs file and exits, or returns on error.
     */
    public static function hostpn_serve_private_file($contract_id, $filename)
    {
        $user_id = get_current_user_id();

        if (!self::hostpn_user_can_access_contract($contract_id, $user_id)) {
            wp_die(esc_html__('You do not have permission to access this file.', 'hostpn'), 403);
        }

        $dir = self::hostpn_get_contract_dir($contract_id);
        $filepath = $dir . DIRECTORY_SEPARATOR . sanitize_file_name($filename);

        if (!file_exists($filepath)) {
            wp_die(esc_html__('File not found.', 'hostpn'), 404);
        }

        // Serve file
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . sanitize_file_name($filename) . '"');
        header('Content-Length: ' . filesize($filepath));
        header('Cache-Control: private, no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');

        readfile($filepath);
        exit;
    }

    /**
     * AJAX handler: Download contract PDF.
     */
    public static function hostpn_contract_download_pdf()
    {
        if (!is_user_logged_in()) {
            wp_die(esc_html__('You must be logged in.', 'hostpn'), 403);
        }

        $nonce = !empty($_REQUEST['hostpn_ajax_nonce']) ? sanitize_text_field(wp_unslash($_REQUEST['hostpn_ajax_nonce'])) : '';
        if (!wp_verify_nonce($nonce, 'hostpn-nonce')) {
            wp_die(esc_html__('Security check failed.', 'hostpn'), 403);
        }

        $contract_id = !empty($_REQUEST['contract_id']) ? absint($_REQUEST['contract_id']) : 0;
        $file_type = !empty($_REQUEST['file_type']) ? sanitize_text_field(wp_unslash($_REQUEST['file_type'])) : 'pdf';

        if (!$contract_id) {
            wp_die(esc_html__('Invalid contract.', 'hostpn'), 400);
        }

        if ($file_type === 'signed') {
            $filename = get_post_meta($contract_id, 'hostpn_contract_signed_pdf_attachment_id', true);
        } else {
            $filename = get_post_meta($contract_id, 'hostpn_contract_pdf_attachment_id', true);
        }

        if (empty($filename)) {
            wp_die(esc_html__('No file available.', 'hostpn'), 404);
        }

        self::hostpn_serve_private_file($contract_id, $filename);
    }

    /**
     * AJAX handler: Upload signed contract PDF.
     */
    public static function hostpn_contract_upload_signed()
    {
        if (!is_user_logged_in()) {
            echo wp_json_encode(['error_key' => 'not_logged_in', 'error_content' => __('You must be logged in.', 'hostpn')]);
            exit;
        }

        $nonce = !empty($_POST['hostpn_ajax_nonce']) ? sanitize_text_field(wp_unslash($_POST['hostpn_ajax_nonce'])) : '';
        if (!wp_verify_nonce($nonce, 'hostpn-nonce')) {
            echo wp_json_encode(['error_key' => 'nonce_error', 'error_content' => __('Security check failed.', 'hostpn')]);
            exit;
        }

        $contract_id = !empty($_POST['contract_id']) ? absint($_POST['contract_id']) : 0;

        if (!$contract_id) {
            echo wp_json_encode(['error_key' => 'invalid_contract', 'error_content' => __('Invalid contract.', 'hostpn')]);
            exit;
        }

        // Permission check
        if (!self::hostpn_user_can_access_contract($contract_id, get_current_user_id())) {
            echo wp_json_encode(['error_key' => 'no_permission', 'error_content' => __('You do not have permission.', 'hostpn')]);
            exit;
        }

        if (empty($_FILES['signed_pdf'])) {
            echo wp_json_encode(['error_key' => 'no_file', 'error_content' => __('No file uploaded.', 'hostpn')]);
            exit;
        }

        $result = self::hostpn_store_signed_pdf($contract_id, $_FILES['signed_pdf']);

        if (is_wp_error($result)) {
            echo wp_json_encode(['error_key' => 'upload_error', 'error_content' => $result->get_error_message()]);
            exit;
        }

        echo wp_json_encode([
            'error_key' => '',
            'message' => __('Signed contract uploaded successfully.', 'hostpn'),
            'status' => 'signed',
        ]);
        exit;
    }

    /**
     * Store an expense attachment file in protected directory.
     *
     * @param int   $accommodation_id Accommodation ID.
     * @param array $file             $_FILES array entry.
     * @return array|WP_Error Array with 'filename' and 'filepath' or WP_Error.
     */
    public static function hostpn_store_expense_attachment($accommodation_id, $file)
    {
        if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return new WP_Error('no_file', __('No valid file uploaded.', 'hostpn'));
        }

        $allowed_exts = ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'doc', 'docx'];
        $file_type = wp_check_filetype($file['name']);
        $ext = strtolower($file_type['ext']);

        if (!in_array($ext, $allowed_exts, true)) {
            return new WP_Error('invalid_type', __('File type not allowed.', 'hostpn'));
        }

        $dir = self::hostpn_get_expense_dir($accommodation_id);
        $filename = time() . '_' . sanitize_file_name($file['name']);
        $filepath = $dir . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            return new WP_Error('upload_failed', __('Could not save attachment.', 'hostpn'));
        }

        return [
            'filename' => $filename,
            'filepath' => $filepath,
            'original_name' => sanitize_file_name($file['name']),
        ];
    }

    /**
     * Serve an expense attachment with permission checks.
     *
     * @param int    $accommodation_id Accommodation ID.
     * @param string $filename         Filename to serve.
     */
    public static function hostpn_serve_expense_file($accommodation_id, $filename)
    {
        if (!current_user_can('edit_posts')) {
            wp_die(esc_html__('Permission denied.', 'hostpn'), 403);
        }

        $dir = self::hostpn_get_expense_dir($accommodation_id);
        $filepath = $dir . DIRECTORY_SEPARATOR . sanitize_file_name($filename);

        if (!file_exists($filepath)) {
            wp_die(esc_html__('File not found.', 'hostpn'), 404);
        }

        $file_type = wp_check_filetype($filename);
        $mime = !empty($file_type['type']) ? $file_type['type'] : 'application/octet-stream';

        header('Content-Type: ' . $mime);
        header('Content-Disposition: inline; filename="' . sanitize_file_name($filename) . '"');
        header('Content-Length: ' . filesize($filepath));
        header('Cache-Control: private, max-age=3600');
        header('Pragma: private');

        readfile($filepath);
        exit;
    }

    /**
     * AJAX handler: Download/View expense attachment file.
     */
    public static function hostpn_expense_download_attachment()
    {
        if (!is_user_logged_in()) {
            wp_die(esc_html__('You must be logged in.', 'hostpn'), 403);
        }

        $nonce = !empty($_REQUEST['hostpn_ajax_nonce']) ? sanitize_text_field(wp_unslash($_REQUEST['hostpn_ajax_nonce'])) : '';
        if (!wp_verify_nonce($nonce, 'hostpn-nonce')) {
            wp_die(esc_html__('Security check failed.', 'hostpn'), 403);
        }

        $accommodation_id = !empty($_REQUEST['accommodation_id']) ? absint($_REQUEST['accommodation_id']) : 0;
        $filename = !empty($_REQUEST['filename']) ? sanitize_file_name(wp_unslash($_REQUEST['filename'])) : '';

        if (!$accommodation_id || empty($filename)) {
            wp_die(esc_html__('Invalid request parameters.', 'hostpn'), 400);
        }

        self::hostpn_serve_expense_file($accommodation_id, $filename);
    }
}
