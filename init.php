<?php

function joapp_api_post_like_init() {
    if (!is_admin()) {
        wp_die();
    }

    $plugin_match = FALSE;
    if (!class_exists("JOAPP_API") || JOAPP_API_VERSION < 400) {
        ?>
        <br>
        <div class="notice inline notice-error notice-alt">
            <p><b>خطا: افزونه JOAPP API نصب نیست و یا نسخه ای کمتر از نسخه 4.0.0 دارد. لطفا JoApp API را به روز رسانی نمایید.</b></p>
        </div>
        <?php
        return;
    }

    if (isset($_POST['joapp_api_like_post_icon'])) {
        update_option("joapp_api_like_post_icon", $_POST['joapp_api_like_post_icon']);
        ?>
        <div class="notice inline notice-info notice-alt">
            <p>با موفقیت ذخیره شد</p>
        </div>
        <?php
    }

    wp_enqueue_media();
    $like_normal = WP_PLUGIN_URL . '/joapp-api-post-like/assets/like.png';
    $like = get_option("joapp_api_like_post_icon", $like_normal);
    ?>
    <div class="wrap">
        <h3>تنظیمات لایک پست JoApp API JoApp API</h3>
        <div>
            <form method="post">
                <script>
                    var mediaUploader;
                    function select_image() {
                        if (mediaUploader) {
                            mediaUploader.open();
                            return;
                        }

                        mediaUploader = wp.media.frames.file_frame = wp.media({
                            title: 'انتخاب تصویر لایک ',
                            button: {
                                text: 'انتخاب تصویر'
                            }, multiple: false});

                        mediaUploader.on('select', function () {
                            var attachment = mediaUploader.state().get('selection').first().toJSON();
                            if (attachment.height !== 64 || attachment.width !== 64) {
                                alert("خطا : تصویر انتخاب شده در ابعاد 64x64 پیکسل نیست !!!\n\nابعاد تصویر انتخاب شده:" + attachment.width + "x" + attachment.height);
                                return;
                            }
                            jQuery("#joapp_api_like_post_icon").val(attachment.url);
                            jQuery("#joapp_api_like_post_icon_img").attr("src", attachment.url);
                        });
                        mediaUploader.open();
                    }

                    function select_image_normal() {
                        jQuery("#joapp_api_like_post_icon").val("<?php echo $like_normal ?>");
                        jQuery("#joapp_api_like_post_icon_img").attr("src", "<?php echo $like_normal ?>");
                    }
                </script>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">انتخاب آیکون لایک نمایشی 64x64</th>
                        <td>
                            <div class="postbox">
                                <center>
                                    <br/>
                                    <img id="joapp_api_like_post_icon_img" style="width: 50px; height: 50px" src="<?php echo $like ?>">
                                    <br/><br/>
                                    <a onclick="select_image()" class="button button-primary">تغییر تصویر</a>
                                    <a onclick="select_image_normal()" class="button">پیش فرض</a>
                                    <br/><br/>
                                </center>
                            </div>
                        </td>
                    </tr>
                </table>
                <hr/>
                <input type="hidden" id="joapp_api_like_post_icon" name='joapp_api_like_post_icon' value="<?php echo $like ?>">
                <input type="submit" class="button button-primary" value="ذخیره" />
            </form>
        </div>
        <hr/>
        <div class="notice inline notice-info notice-alt">
            <p>برای نمایش تعداد لایک در زیر پست ها به صورت فیلد های اختیاری ، باید فیلدی با کلید joapp_api_post_like_count در تنظیمات فیلد های اختیاری JoApp Api قرار دهید</p>
        </div>
        <hr/>
    </div>
    <?php
}
?>