<?php

/**
 * Class for importing a template.
 *
 * @package Layouts
 */

namespace Elementor\TemplateLibrary;

use LFE\API\Layouts_Remote;
use Elementor\TemplateLibrary\Source_Remote;
use Elementor\TemplateLibrary\Classes\Images;
use Elementor\Api;
use Elementor\Plugin;

/**
 * Class for importing a template.
 *
 */
class Layouts_Importer extends Source_Remote {

    public function __construct() {
        if (!function_exists('wp_crop_image')) {
            include ABSPATH . 'wp-admin/includes/image.php';
        }
        $this->hooks();
    }

    /**
     * Initialize
     */
    public function hooks() {
        add_action('wp_ajax_handle_import', array($this, 'handle_import'));
        add_action('wp_ajax_nopriv_handle_import', array($this, 'handle_import'));
    }

    /**
     * Import template ajax action
     */
    public function handle_import() {
        $template_id = $_POST['template_id'];
        $with_page = $_POST['with_page'];
        $method = $with_page ? 'page' : 'library';

        $template = Layouts_Remote::lfe_get_instance()->get_template_content($template_id);

        if (is_wp_error($template)) {
            return $template;
        }

        // Finally create the page.
        $page_id = $this->create_page($template, $with_page);

        echo $page_id;
        exit;
    }

    private function create_page($template, $with_page = false) {
        if (!$template) {
            return _e('Invalid Template ID.', LFE_TEXTDOMAIN);
        }

        $content = json_decode($template['content'], true);
        $template['content'] = $this->replace_elements_ids($content);
        $template['content'] = $this->process_export_import_content($content, 'on_import');

        $args = [
            'post_type' => $with_page ? 'page' : 'elementor_library',
            'post_status' => $with_page ? 'draft' : 'publish',
            'post_title' => $with_page ? $with_page : 'LFE: ' . $template['title'],
            'post_content' => '',
        ];

        $new_post_id = wp_insert_post($args);

        if ($new_post_id && !is_wp_error($new_post_id)) {
            update_post_meta($new_post_id, '_elementor_data', $template['content']);
            update_post_meta($new_post_id, '_elementor_template_type', $template['type']);
            update_post_meta($new_post_id, '_elementor_edit_mode', 'builder');
            update_post_meta($new_post_id, '_lfe_import_type', $with_page ? 'page' : 'library' );
            update_post_meta($new_post_id, '_lfe_template_id', $template['id']);
            update_post_meta($new_post_id, '_wp_page_template', !empty($template['page_template']) ? $template['page_template'] : 'elementor_canvas' );

            if (!$with_page) {
                wp_set_object_terms($new_post_id, !empty($template['elementor_library_type']) ? $template['elementor_library_type'] : 'page', 'elementor_library_type');
            }

            return $new_post_id;
        }

        return new \WP_Error('import_error', 'Unable to create page.');
    }

}

new Layouts_Importer();
