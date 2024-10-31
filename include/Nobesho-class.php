<?php
/**
 * Primary class file for Nobesho Store Plugin
 */
defined('ABSPATH') || exit;
use Automattic\WooCommerce\Client;
if (!class_exists('Nobesho_Store')):
    class Nobesho_Store {
        public function __construct() {
            $this->init();
        }
        public function init() {
            add_filter('manage_edit-product_columns', [$this, 'nbs_woo_product_weight_column'], 20);
            add_filter('manage_edit-product_sortable_columns', [$this, 'nbs_my_set_sortable_columns']);
            add_action('manage_product_posts_custom_column', [$this, 'nbs_woo_product_weight_column_data'], 2);
            add_action('admin_head', [$this, 'nbs_my_column_width']);
            add_action('pre_get_posts', [$this, 'nbs_my_sort_custom_column_query']);
            add_action('admin_menu', [$this, 'nbs_registerAbusAdminMenu']);
            add_action('init', [$this, 'nobesho_getstart']);
            add_action('wp_ajax_nobesho_get_product', [$this, 'nbs_get_products']);
            add_action('wp_ajax_post_products', [$this, 'nbs_post_products']);
            add_action('wp_ajax_single_post_products', [$this, 'nbs_single_post_products']);
            add_action('admin_footer', [$this, 'nbs_nobesho_send_single']);
        }
        public function nbs_woo_product_weight_column($columns) {
            $columns['nobesho_status'] = esc_html__('وضعیت در نوبشو', 'woocommerce');
            return $columns;
        }
        public function nbs_woo_product_weight_column_data($column) {
            global $post;
            if ($column == 'nobesho_status') {
                $meta_value = get_post_meta($post->ID, '_nobesho_status', true);
                if ($meta_value == 1) include('template/resend.php');
                else include('template/send.php');
            }
        }
        public function nbs_my_column_width() {
            include('template/nobesho_status_style.php');
        }
        public function nbs_my_set_sortable_columns($columns) {
            $columns['nobesho_status'] = 'nobesho_status';
            return $columns;
        }
        public function nbs_my_sort_custom_column_query($query) {
            $orderby = $query->get('orderby');
            if ('nobesho_status' == $orderby) {
                $meta_query = array(
                    'relation' => 'OR',
                    array(
                        'key' => '_nobesho_status',
                        'compare' => '1',
                    ) ,
                    array(
                        'key' => '_nobesho_status',
                    ) ,
                );
                $query->set('meta_query', $meta_query);
                $query->set('orderby', 'meta_value');
            }
        }
        public function nbs_registerAbusAdminMenu() {
            add_menu_page('نوبشو', 'نوبشو', 'manage_options', 'Nobesho', [$this, 'Nobesho_dashboard'], NOBESHO_PLUGIN_URL . "assets/img/nobesho.png");
        }
        public function Nobesho_dashboard() {
            $option_name = 'nobesho-secret';
            if (isset($_POST[$option_name])) {
				$value = sanitize_text_field($_POST[$option_name]);
				update_option($option_name, $value);
				$this->redirect($value);
            }
            $secret = get_option($option_name);
            include('template/dashboard_main.php');
            $this->nbs_view_console();
        }
        public function redirect($secret) {
            $store_url = get_site_url();
            $endpoint = '/wc-auth/v1/authorize';
            $params = ['app_name' => 'پلاگین نوبشو', 'scope' => 'read_write', 'user_id' => $secret, 'return_url' => get_site_url() . '/?nobesho_getstart=1', 'callback_url' => get_site_url(null, null, 'https') . '/?nobesho_getstart=1'];
            $query_string = http_build_query($params);
			wp_redirect($store_url . $endpoint . '?' . $query_string);
			exit;
        }
        public function nobesho_getstart() {
            if (isset($_REQUEST['nobesho_getstart'])) {
                $secret = get_option('nobesho-secret');
                $raw = file_get_contents('php://input');
                if (!empty($raw)) {
                    $data = json_decode($raw, true);
                    if ($secret == $data['user_id']) {
                        update_option('nobesho_ck', $data['consumer_key']);
                        update_option('nobesho_cs', $data['consumer_secret']);
						$this->nbs_install();
                    }
					exit;
                }
                wp_redirect(home_url('/wp-admin/admin.php?page=Nobesho'));
                exit;
            }
        }
        public function nbs_install() {
            $woocommerce = new Client(get_site_url(null, null, 'https'),get_option('nobesho_ck'),get_option('nobesho_cs'),['wp_api' => true, 'version' => 'wc/v3', 'verify_ssl' => true, 'timeout' => 10]);
            $delete = $this->nbs_delete_old_wc_hooks($woocommerce);
            $data = ['create' => [['name' => 'nobesho_product_created', 'topic' => 'product.created', 'delivery_url' => NOBESHO_SITE_WEBHOOK, 'secret' => get_option('nobesho-secret') ], ['name' => 'nobesho_product_updated', 'topic' => 'product.updated', 'delivery_url' => NOBESHO_SITE_WEBHOOK, 'secret' => get_option('nobesho-secret') ],], 'delete' => $delete];
            $woocommerce->post('webhooks/batch', $data);
        }
        public function nbs_delete_old_wc_hooks($woocommerce) {
            $delete = [];
            $result = $woocommerce->get('webhooks');
            foreach ($result as $item) {
                if (strpos($item->name, 'nobesho') !== false) {
                    array_push($delete, $item->id);
                }
            }
            return $delete;
        }
        public function nbs_view_console() {
            include('template/console.php');
        }
        public function nbs_get_products() {
            if (isset($_REQUEST['page'])) {
				$page = sanitize_text_field($_REQUEST['page']);
                $woocommerce = new Client(get_site_url(null, null, 'https') , get_option('nobesho_ck') , get_option('nobesho_cs') , ['wp_api' => true, 'version' => 'wc/v3', 'verify_ssl' => true,'timeout' => 10]);
                $result = $woocommerce->get("products?per_page=100&page=" . $page);
                header('Content-Type: application/json');
                echo json_encode($result);
            }
            die();
        }
        public function nbs_post_products() {
            if (isset($_REQUEST['id'])) {
				$pid = sanitize_text_field($_REQUEST['id']);
				$data = (is_array($_REQUEST['data']) && !empty($_REQUEST['data'])) ? $_REQUEST['data'] : false;
				if( $pid && $data ) :
                update_post_meta($pid, '_nobesho_status', '1');
                //send product to nobesho
                $jsonData = array(
                    'NOBESHO_WC_SIGNATURE' => base64_encode(hash_hmac('sha256', explode("/", get_site_url()) [2], get_option('nobesho-secret') , true)) ,
                    'product' => base64_encode(json_encode($data))
                );
                $data = json_encode($jsonData);
                $response = wp_remote_post(NOBESHO_SITE_WEBHOOK, array(
                    'headers' => array(
                        'Content-Type' => 'application/json; charset=utf-8'
                    ) ,
                    'body' => $data,
                    'method' => 'POST',
                    'data_format' => 'body',
                ));
                print_r($response);
				endif;
            }
            die();
        }
        public function nbs_single_post_products() {
            if (isset($_REQUEST['id'])) {
				$pid = sanitize_text_field($_REQUEST['id']);
				if( $pid ) :
                update_post_meta($pid, '_nobesho_status', '1');
                //send product to nobesho
                $woocommerce = new Client(get_site_url(null, null, 'https') , get_option('nobesho_ck') , get_option('nobesho_cs') , ['wp_api' => true, 'version' => 'wc/v3', 'verify_ssl' => true, 'timeout' => 10]);
                $result = $woocommerce->get('products/' . $pid);
                $jsonData = array(
                    'NOBESHO_WC_SIGNATURE' => base64_encode(hash_hmac('sha256', explode("/", get_site_url())[2],get_option('nobesho-secret') , true)) ,
                    'product' => $result
                );
                $data = json_encode($jsonData);
                $response = wp_remote_post(NOBESHO_SITE_WEBHOOK, array(
                    'headers' => array(
                        'Content-Type' => 'application/json; charset=utf-8'
                    ) ,
                    'body' => $data,
                    'method' => 'POST',
                    'data_format' => 'body',
                ));
                print_r($response);
				endif;
            }
            die();
        }
        public function nbs_nobesho_send_single() {
            include('template/send_single.php');
        }
    }
endif;