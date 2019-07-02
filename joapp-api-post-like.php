<?php

/*
  Plugin Name: لایک پست JoApp API
  Plugin URI: http://joapp.ir/wordpress
  Description: افزونه ای جانبی برای JOAPP API که از طریق آن پست ها در اپلیکیشن امکان ثبت Like توسط کاربران فراهم میگردد
  Version: 1.0
  Author: SEPAHAN DATA TOOLS Co.
  Author URI: http://bejo.ir/
  Copyright: 2018 joapp.ir & bejo.ir
 */
//joapp_api_action_get_shipping_methods    JOAPP_RESULT     ----------
//joapp_api_action_get_posts               JOAPP_RESULT     ----------
//joapp_api_action_get_post                JOAPP_RESULT     ----------
//joapp_api_action_get_about               JOAPP_RESULT     ----------
//joapp_api_action_get_comment             JOAPP_RESULT     ----------
//joapp_api_action_get_woo_categories      JOAPP_RESULT     ----------
//joapp_api_action_get_user                JOAPP_RESULT     ----------
//joapp_api_action_get_user_shipping       JOAPP_RESULT     ----------
//joapp_api_action_posts_result            JOAPP_RESULT     ----------
//joapp_api_action_get_woo_filters         JOAPP_RESULT     ----------
//joapp_api_action_get_category_index      JOAPP_RESULT     ----------
//joapp_api_action_open_pay_woo            JOAPP_RESULT     ----------
//joapp_api_action_get_author_index        JOAPP_RESULT     ----------
//joapp_api_action_get_page_index          JOAPP_RESULT     ----------
//joapp_api_action_submit_comment          ------------     JOAPP_DATA
//joapp_api_action_register_user           JOAPP_RESULT     ----------
//
//------------------------------INITS(THIS)---------------------------
//joapp_api_action_author_init             THIS             ----------
//joapp_api_action_attachment_init         THIS             ----------
//joapp_api_action_category_init           THIS             ----------
//joapp_api_action_comment_init            THIS             ----------
//joapp_api_action_post_init               THIS             ----------
//joapp_api_action_tag_init                THIS             ----------
//
//------------------------------VIEW & SAVE for admin-----------------
//joapp_api_action_view_about && joapp_api_action_save_about
//joapp_api_action_view_admin_menu
//joapp_api_action_view_tab_connection && joapp_api_action_save_tab_connection
//joapp_api_action_view_tab_posts && joapp_api_action_save_tab_posts
//joapp_api_action_view_tab_store && joapp_api_action_save_tab_store
//joapp_api_action_view_tab_custom_field
//joapp_api_action_view_tab_pushe
//joapp_api_action_view_edit_menus
//joapp_api_action_view_edit_tags

add_action("joapp_api_action_view_admin_menu", "joapp_api_action_view_admin_menu_func_post_like");

function joapp_api_action_view_admin_menu_func_post_like() {
    add_submenu_page('joapp-api', "لایک پست JoApp API", "لایک پست JoApp API", 'manage_options', 'joapp_api_post_like_init', "joapp_api_post_like_init");
}

add_action("joapp_api_action_post_init", "joapp_api_action_post_init_post_like_func");

function joapp_api_action_post_init_post_like_func($joapp_result) {
    $like_normal = WP_PLUGIN_URL . '/joapp-api-post-like/assets/like.png';
    $NetJs = "<script type='text/javascript' src='" . WP_PLUGIN_URL . '/joapp-api-post-like/assets/Net.js' . "'></script><script type='text/javascript' src='" . WP_PLUGIN_URL . '/joapp-api-post-like/assets/PostLike.js' . "'></script>";
    $like = get_option("joapp_api_like_post_icon", $like_normal);
    $count_like = get_post_meta($joapp_result->id, "joapp_api_post_like_count", TRUE);
    $count_like = $count_like ? $count_like : 0;
    $site_url = get_site_url() . "/";
    $script = "<br/>$NetJs<div  style='display: table;'><img onclick='joapp_api_post_like(\"$site_url\",this,$joapp_result->id)' src='$like' style='vertical-align: middle;height:35px;width:35px'/><lable style='vertical-align: middle;display: table-cell;'>تعداد پسند شده: </lable><strong style='vertical-align: middle;display: table-cell;'>$count_like</strong></div>";

    $joapp_result->content .= $script;
}

if (isset($_REQUEST['joapp_api_post_like'])) {
    @ob_clean();
    if (FALSE === get_post_status($_REQUEST['joapp_api_post_like'])) {
        
    } else {
        $count_like = get_post_meta($_REQUEST['joapp_api_post_like'], "joapp_api_post_like_count", TRUE);
        $count_like = $count_like ? $count_like : 0;
        $ip = joapp_api_like_get_ip();
        $last_ip = get_post_meta($_REQUEST['joapp_api_post_like'], "joapp_api_post_like_last_ip", TRUE);
        $last_ip = $last_ip ? $last_ip : "0.0.0.0";

        if ($ip !== $last_ip) {
            $count_like++;
            update_post_meta($_REQUEST['joapp_api_post_like'], "joapp_api_post_like_count", $count_like);
            update_post_meta($_REQUEST['joapp_api_post_like'], "joapp_api_post_like_last_ip", $ip);
            exit("ثبت لایک شما با موفقیت انجام شد : <strong>$count_like</strong>");
        } else {
            exit("ثبت لایک تکراری ممکن نیست : <strong>$count_like</strong>");
        }
    }
}

function joapp_api_like_get_ip() {
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $ip;
                }
            }
        }
    }
    return "unknown";
}

include_once __DIR__ . '/init.php';
?>
