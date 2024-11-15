<?php
/*
Plugin Name: Frontend WooCommerce Product Submission
Description: A plugin to allow adding WooCommerce products from the frontend with additional product details and multiple images.
Version: 1.1
Author: Asim
Author URI: https://workwithasim.com
Update URI: https://workwithasim.com/plugins/frontend-woocommerce-product-submission/
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Shortcode for frontend product form
function frontend_product_submission_form() {
    // Display form
    ob_start(); 
    ?>
    <form id="frontend-product-form" method="POST" enctype="multipart/form-data">
        <?php wp_nonce_field('submit_product_nonce_action', 'submit_product_nonce_field'); ?>

        <label for="product_name">Product Name</label>
        <input type="text" name="product_name" required>

        <label for="product_description">Description</label>
        <textarea name="product_description" required></textarea>

        <label for="product_price">Regular Price</label>
        <input type="number" name="product_price" step="0.01" required>

        <label for="sale_price">Sale Price</label>
        <input type="number" name="sale_price" step="0.01">

        <label for="product_virtual">Virtual</label>
        <input type="checkbox" name="product_virtual" value="yes">

        <label for="product_downloadable">Downloadable</label>
        <input type="checkbox" name="product_downloadable" value="yes">

        <label for="product_images">Product Images</label>
        <input type="file" name="product_images[]" accept="image/*" multiple required>

        <input type="submit" name="submit_product" value="Add Product">
    </form>
    <?php

    // Handle form submission
    if (isset($_POST['submit_product'])) {
        frontend_process_product_submission();
    }

    return ob_get_clean();
}
add_shortcode('frontend_product_form', 'frontend_product_submission_form');

// Process the form submission
function frontend_process_product_submission() {
    // Security check
    if (!isset($_POST['submit_product_nonce_field']) || !wp_verify_nonce($_POST['submit_product_nonce_field'], 'submit_product_nonce_action')) {
        echo '<p>Security check failed. Please try again.</p>';
        return;
    }

    // Ensure WooCommerce is active
    if (!function_exists('wc_get_product')) {
        echo '<p>WooCommerce is not active.</p>';
        return;
    }

    // Sanitize inputs
    $product_name = sanitize_text_field($_POST['product_name']);
    $product_description = sanitize_textarea_field($_POST['product_description']);
    $product_price = sanitize_text_field($_POST['product_price']);
    $sale_price = sanitize_text_field($_POST['sale_price']);
    $is_virtual = isset($_POST['product_virtual']) ? 'yes' : 'no';
    $is_downloadable = isset($_POST['product_downloadable']) ? 'yes' : 'no';

    // Create a new WooCommerce product
    $product = new WC_Product_Simple();
    $product->set_name($product_name);
    $product->set_description($product_description);
    $product->set_regular_price($product_price);
    $product->set_sale_price($sale_price);
    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    $product->set_virtual($is_virtual === 'yes');
    $product->set_downloadable($is_downloadable === 'yes');

    // Handle multiple product image uploads
    if (!empty($_FILES['product_images']['name'][0])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $image_ids = [];
        foreach ($_FILES['product_images']['name'] as $key => $value) {
            $file = [
                'name'     => $_FILES['product_images']['name'][$key],
                'type'     => $_FILES['product_images']['type'][$key],
                'tmp_name' => $_FILES['product_images']['tmp_name'][$key],
                'error'    => $_FILES['product_images']['error'][$key],
                'size'     => $_FILES['product_images']['size'][$key],
            ];
            $_FILES['single_product_image'] = $file;

            $attachment_id = media_handle_upload('single_product_image', 0);
            if (!is_wp_error($attachment_id)) {
                $image_ids[] = $attachment_id;
            }
        }
        // Setting the first image as the primary product image
        if (!empty($image_ids)) {
            $product->set_image_id($image_ids[0]);
            if (count($image_ids) > 1) {
                $product->set_gallery_image_ids(array_slice($image_ids, 1));
            }
        }
    }

    $product_id = $product->save();

    if ($product_id) {
        echo "<p>Product added successfully! Product ID: " . $product_id . "</p>";
    } else {
        echo "<p>There was an error adding the product.</p>";
    }
}
?>
