<?php
$out = array('success' => false);

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  $_message = "";
  $fields = isset($scriptProperties['fields']) ? $scriptProperties['fields'] : null;

  $fieldsItems = explode(",", $fields);
  $out['fi'] = count($fieldsItems);
  if (count($fieldsItems) > 0) {
        
        $_message .= "<hr noshade />";
        
        foreach ($fieldsItems as $_item) {
            list  ($_title, $_key) = explode('=', $_item);
            
            if (isset($_POST[$_key]) && strlen($_POST[$_key]) > 0) {
                $_message .= "<b>" . $_title . ":</b><br />" . htmlspecialchars($_POST[$_key]) . "<br />";
            }
        }
        
        $_message .= "<hr noshade />";
        
        $_out['m'] = $_message;
        
        $modx->getService('mail', 'mail.modPHPMailer');
        $modx->mail->set(modMail::MAIL_BODY, $_message);
        $modx->mail->set(modMail::MAIL_FROM, $scriptProperties['from']);
        $modx->mail->set(modMail::MAIL_FROM_NAME, 'Mail robot: ' . $scriptProperties['site']);
        $modx->mail->set(modMail::MAIL_SUBJECT, 'Request from site');
        $modx->mail->setHTML(true);   
        
        $toEmails = explode(',', $scriptProperties['to']);
        
        if (count($toEmails) > 0) {
            $_out['toEm'] = $toEmails;
            foreach ($toEmails as $_toEmail) {
                $modx->mail->address('to', $_toEmail);
            }
        }
        
        if (!$modx->mail->send()) {
            $modx->log(modX::LOG_LEVEL_ERROR, 'An error occurred while trying to send the email: ' . $modx->mail->mailer->ErrorInfo);
            $out['error'] = $modx->mail->mailer->ErrorInfo;
        } else {
            $out['success'] = true;
        }  
  
        $modx->mail->reset();        
    }    
}

return json_encode($out);
