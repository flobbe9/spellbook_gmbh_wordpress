<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use SpellbookGmbhTheme\Helpers\Utils;

/**
 * @since latest
 */

/**
 * Call in config init()
 */
function initMail(): void {
    // sets the "from" prop, necessary for "phpmailer_ini" hook to be fired
    add_filter('wp_mail_from', function() {
        return $_ENV["WORDPRESS_SMTP_USER"];
    });
}

/**
 * Log mailing error if is dev.
 * 
 * Call with "wp_mail_failed" hook
 * 
 * @param WP_Error $error 
 */
function handleMailingErrors(WP_Error $error): void {
    if ("development" === Utils::getEnv())
        Utils::log($error->get_error_message());
}

/**
 * Call with "phpmailer_init" hook
 */
function initMailEnv(PHPMailer $phpMailer): void {
    $phpMailer->isSMTP();
    $phpMailer->SMTPDebug = "development" === Utils::getEnv() ? SMTP::DEBUG_LOWLEVEL : SMTP::DEBUG_OFF;

    $phpMailer->Host = $_ENV["WORDPRESS_SMTP_HOST"];
    $phpMailer->SMTPAuth = "development" !== Utils::getEnv();
    $phpMailer->Port = $_ENV["WORDPRESS_SMTP_PORT"];   
    $phpMailer->Username = $_ENV["WORDPRESS_SMTP_USER"];
    $phpMailer->From = $_ENV["WORDPRESS_SMTP_USER"];
    $phpMailer->Password = $_ENV["WORDPRESS_SMTP_PASSWORD"];
    $phpMailer->SMTPSecure = "development" === Utils::getEnv() ? '' : PHPMailer::ENCRYPTION_STARTTLS;
}