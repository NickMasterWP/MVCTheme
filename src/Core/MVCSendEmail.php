<?php

namespace MVCTheme\Core;

class MVCSendEmail {

    public static function subscribe_from_name() {
        global $MVCTheme;
        return $MVCTheme->getOption("from_name");
    }

    public static function subscribe_from_email() {
        global $MVCTheme;
        return $MVCTheme->getOption("from_email");
    }

    public static function send( $from_email, $from_name, $to_email, $subject, $message, $attachments = [], $is_html = false ) {
/*
        var_dump("from_email: ".$from_email);
        var_dump("from_name: ".$from_name);
        var_dump("to_email: ".$to_email);
*/
        if (!class_exists("PHPMailer\PHPMailer\PHPMailer")) {
            require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
            require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
            require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
        }


        $mailer = new PHPMailer\PHPMailer\PHPMailer();
        $mailer->SetFrom($from_email, $from_name); //Name is optional
        $mailer->Subject   = $subject;
        $mailer->Body      = $message;
        $mailer->AddAddress( $to_email );
        $mailer->isHTML($is_html);
        $mailer->CharSet = 'UTF-8';

        if ( count($attachments)) {
            foreach ($attachments as $attachment) {
                $mailer->AddAttachment( $attachment["path"] , $attachment["name"] );
            }
        }

        $res = $mailer->Send();

        if (!$res) {
            throw new Exception($mailer->ErrorInfo);
        }

		return $res;
	}

	public static function getTemplate($nameTemplate, $params, $is_html) {

        global $MVCTheme;

        $params = array_merge([
            'site_url' => home_url(),
            'theme_url' => $MVCTheme->getThemeChildFileURL(""),
            'theme_parent_url' => $MVCTheme->getThemeParentFileURL(""),
        ], $params);

        $text = View::email($nameTemplate, $params);
        $text = apply_filters("mvc_get_email_template_text", $text, $nameTemplate, $params, $is_html);

        $params["text"] = $text;
        $params["setting"] = $MVCTheme->getSetting();

        if ( $is_html ) {
            $html = View::email("index_html", $params);
        } else {
            $html = View::email("index", $params);
        }
        return $html;

	}

    public static function sendFromSite($email, $subject, $body, $attachment = [], $is_html = true) {
        global $MVCTheme;
        $fromEmail = $MVCTheme->getOption("from_email");
        $fromName = $MVCTheme->getOption("from_name");

        return SendEmail::send( $fromEmail, $fromName, $email, $subject, $body, $attachment, $is_html);
    }

    /**
     * @throws Exception
     */
    public static function sendTemplate($template, $params, $email, $subject, $attachment = [], $is_html = true) {
        global $MVCTheme;

        $params["emailLogo"] = $MVCTheme->getOption("email_logo");
        $params["emailColorButton"] = $MVCTheme->getOption("email_color_button");
        $params["emailContact"] = $MVCTheme->getOption("email_contact");
        $params["emailAddress"] = $MVCTheme->getOption("email_address");

		$html = SendEmail::getTemplate($template, $params, $is_html);

		return SendEmail::sendFromSite( $email, $subject, $html, $attachment, $is_html);
	}


    /**
     * @throws Exception
     */
    public static function sendSubscribeTemplate($template, $params, $from_email, $from_name, $email, $subject, $attachment = [], $is_html = true) {
		return SendEmail::sendTemplate("subscribe/".$template, $params,  $from_email, $from_name, $email, $subject, $attachment, $is_html );
	}

    static function sendEmailToRole($role, $template, $params, $subject, $attachment = [], $isHtml = true)
    {
        $args = [
            'role' => $role,
            'fields' => ['user_email'],
        ];
        $users = get_users($args);

        foreach ($users as $user) {
            try {
                SendEmail::sendTemplate(
                    $template,
                    $params,
                    $user->user_email,
                    $subject,
                    $attachment,
                    $isHtml
                );
            } catch (Exception $e) {
                error_log("Error sending email to {$user->user_email}: " . $e->getMessage());
            }
        }
    }

    static function sendEmailToAdministrators($template, $params, $subject, $attachment = [], $isHtml = true)
    {
        SendEmail::sendEmailToRole("administrator", $template, $params, $subject, $attachment = [], $isHtml);
    }

}