<?php
/**
 * Created by PhpStorm.
 * User: LOGAN
 * Date: 9/10/2018
 * Time: 12:22 PM
 */

defined("ABSPATH") || exit();


class Gform_apply
{

    public function __construct()
    {

        // add reporter to gravity form menu :)
        add_filter('gform_form_settings_menu', [$this, 'add_menu']);

        // show special content in reporter tab in setting gravity form
        add_action('gform_form_settings_page_gravity_form_reporter_page', [$this, 'add_preview_form']);

        // send purch to third-part app and show result to customer
        add_filter('gform_confirmation', [$this, 'send_and_preview'], 10, 4);

        // add style to header
        add_action('wp_enqueue_scripts', [$this, 'theme_name_scripts']);

    }

    function add_menu($menu_items)
    {
        $menu_items[] = array(
            'name' => 'gravity_form_reporter_page',
            'label' => __('گزارشگر گراویتی فرم')
        );

        return $menu_items;
    }


    function add_preview_form()
    {

        GFFormSettings::page_header();

        echo 'پنل مدیریت گزارشگر گراویتی فرم.';

        GFFormSettings::page_footer();

    }

    function theme_name_scripts()
    {
        wp_enqueue_style('gform_reporter_style', GREPORTER_URL . 'style/style.css');
    }


    function send_and_preview($confirmation, $form, $entry, $ajax)
    {

        $forms = $form['fields'];

        $api = 'xqXWLr4t5KQw3zG8';
        $quantity = 0;
        $link = null;
        $service = null;


        $result = $entry['payment_status'];
        $view = '';

        switch ($result) {

            case 'Paid':

                foreach ($forms as $adminLabel) {
                    $lable2 = $adminLabel->adminLabel;
                    $id2 = $adminLabel->id;
                    if ($lable2 === 'link') {
                        $link = $entry[$id2];
                        break;
                    }
                }

                foreach ($forms as $adminLabel) {
                    $lable3 = $adminLabel->label;
                    $id3 = $adminLabel->id;
                    if ($lable3 === 'service') {
                        $service = $entry[$id3];
                        break;
                    }
                }


                foreach ($forms as $adminLabel) {
                    $lable3 = $adminLabel->label;
                    $id4 = $adminLabel->id;
                    if ($lable3 === 'quantity') {
                        $quantity = $entry[$id4];
                        break;
                    }
                }

                $response = wp_remote_post('https://irsocialapi.com/apiResellers', array(
                        'method' => 'POST',
                        'body' => array(
                            'api' => $api,
                            'link' => $link,
                            'quantity' => $quantity,
                            'service' => $service
                        ),
                    )
                );
                $body = wp_remote_retrieve_body($response);


                $val = json_decode($body);


                $message = null;
                $error = $val->error;
                if (!$error) {
                    $purch_id = $val->result->TrackingCode;


                    $view = "<div class=\"grav-container\"> <h3 class=\"grav-header\">  خرید موفق  </h3>";
                    if (!is_null($purch_id) && isset($purch_id)) {
                        $view .= " <p class=\"grav-msg\">شناسه خرید: $purch_id</p>";
                    }
                    $view .= " </div>" . $confirmation;


                } else {
                    $message = $val->message;

                    $view = "<div class=\"grav-container grav-container-error\">
                          <h3 class=\"grav-header\">  خرید ناموفق  </h3>";
                    if (strpos($view, 'api') || strpos($view, 'link')) {
                        $view .= "<p class=\"grav-msg\"> در ثبت سفارش اختلالی رخ داده است</p>";
                        $view .= "<p class=\"grav-msg\"> با پشتیبانی تماس حاصل نمایید</p>";
                    } else {

                        $view .= "<p class=\"grav-msg\"> $message</p>";
                    }
                    $view .= "</div> 
                           <p>
                 
                 در صورت مواجه با خطا پس از پرداخت وجه ، با پشتیبانی تماس حاصل نمایید.
                           </p> ";
                }

                break;

            case 'Cancelled':

                $view = "<div class=\"grav-container grav-container-cancel\">
                          <h3 class=\"grav-header\">  خرید لغو شد  </h3>";
                $view .= "<p class=\"grav-msg\"> خرید توسط کاربر لغو شد.</p>";
                $view .= "</div> 
                           <p>
                 
                 در صورت مواجه با خطا پس از پرداخت وجه ، با پشتیبانی تماس حاصل نمایید.
                           </p> ";


                break;


            case 'Failed':

                $view = "<div class=\"grav-container grav-container-error\">
                          <h3 class=\"grav-header\">  خرید ناموفق  </h3>";
                $view .= "<p class=\"grav-msg\"> خرید با شکست مواجه شد.</p>";
                $view .= "</div> 
                           <p>
                 
                 در صورت مواجه با خطا پس از پرداخت وجه ، با پشتیبانی تماس حاصل نمایید.
                           </p> ";

                break;


            case 'Processing':
                $view = "<div class=\"grav-container grav-container-cancel\">
                          <h3 class=\"grav-header\">  خرید درحال پردازش  </h3>";
                $view .= "<p class=\"grav-msg\"> خرید در حال پردازش است.</p>";
                $view .= "</div> 
                           <p>
                 
                 در صورت مواجه با خطا پس از پرداخت وجه ، با پشتیبانی تماس حاصل نمایید.
                           </p> ";

                break;

            default:

                $view .= "<p>عملیات ثبت سفارش به درستی انجام نشد.</p>    <p>در صورت پرداخت مبلغ 0 تومان این پیام را نادیده بگیرید.</p> <br> <br> " . $confirmation;

                break;
        }


        return $view;

    }

}