<?php

    // This is the function for mailing with sendgrid. Since most of our emails are triggered through Auth0 and then sent by SendGrid, this function is not in use at the moment.
    function sendmail($to_email, $to_name, $subject, $content, $email_template = 'cms_email.tpl', $from_email = '', $from_name = '', $debug = false)
    {
        global $settings, $translate;

        if (!$from_email) {
            $from_email = $settings['mail_sender'];
        }
        if (!$from_name) {
            $from_name = $settings['mail_sender_name'];
        }

        if (is_array($to_email)) {
            foreach ($to_email as $key => $to) {
                $rcpt[] = ['email' => $to, 'name' => $to_name[$key]];
                $merge[] = ['rcpt' => $to, 'vars' => [['name' => 'naam', 'content' => $to_name[$key]], ['name' => 'email', 'content' => $to]]];
            }
        } else {
            $rcpt[] = ['email' => $to_email, 'name' => $to_name];
            $merge[] = ['rcpt' => $to_email, 'vars' => [['name' => 'naam', 'content' => $to_name], ['name' => 'email', 'content' => $to_email]]];
        }

        $template = new Zmarty();
        $template->assign('content', $content);
        $boilerPlateContent = $translate['cms_email_boilerplate'];
        $boilerPlateContent = str_ireplace('{orgname}', $_SESSION['organisation']['label'], $boilerPlateContent);
        $template->assign('boilerPlateContent', $boilerPlateContent);
        $template->assign('domain', $_SERVER['HTTP_HOST']);

        if ($debug) {
            echo $template->fetch($email_template);

            return;
        }
        $mail = $template->fetch($email_template);

        foreach ($rcpt as $to) {
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom($from_email, $from_name);
            $email->setSubject($subject);
            $email->addTo($to['email']);
            $email->addContent(
                'text/html',
                str_replace('*|NAAM|*', $to['name'], $mail)
            );
            $sendgrid = new \SendGrid($settings['sendgrid_key']);
            $response = $sendgrid->send($email);
        }
    }
