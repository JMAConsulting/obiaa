<?php

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Mail\MailerFactoryInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\User\UserFactoryInterface;
use Joomla\CMS\User\UserHelper;
use Joomla\Database\DatabaseInterface;

class CRM_CiviMobileAPI_Utils_Cms_ResetPassword_Joomla {
  
  /**
   * @return bool
   */
  public static function resetPassword($email) {
    $app = Factory::getApplication();
    $db = Factory::getContainer()->get(DatabaseInterface::class);
    
    $query = $db->getQuery(TRUE)
      ->select($db->quoteName('id'))
      ->from($db->quoteName('#__users'))
      ->where('LOWER(' . $db->quoteName('email') . ') = LOWER(:email)')
      ->bind(':email', $email);
    $db->setQuery($query);
    $userId = $db->loadResult();
    
    if (empty($userId)) {
      return FALSE;
    }
    
    $user = Factory::getContainer()->get(UserFactoryInterface::class)->loadUserById($userId);
    
    if ($user->id) {
      $token       = ApplicationHelper::getHash(UserHelper::genRandomPassword());
      $hashedToken = UserHelper::hashPassword($token);
      
      $user->activation = $hashedToken;
      $user->save();
      
      $link = JUri::base() . 'index.php?option=com_users&view=reset&layout=confirm&id=' . $user->id . '&token=' . $token;
      $fixedLink = str_replace('administrator/', '', $link);
      $cmsVersion = CRM_CiviMobileAPI_Utils_Cms::getCMSVersion();
      
      if (version_compare($cmsVersion, "4.4.0", "<")) {
        $mailer = Factory::getMailer();
      } else {
        $mailer = Factory::getContainer()->get(MailerFactoryInterface::class)->createMailer();
      }
      
      $sender = [$app->get('mailfrom'), $app->get('fromname')];
      $mailer->setSender($sender);
      $mailer->addRecipient($email);
      $mailer->setSubject('Your '. $app->get('sitename') .' password reset request');
      $mailer->setBody("Hello,<br><br>A request has been made to reset your " .$app->get('sitename') . " account password.<br> To reset your password, you will need to submit this verification code to verify that the request was legitimate.<br>The verification code is $token<br>Select the URL below and proceed with resetting your password.<br>$fixedLink <br><br>Thank you.");
      $mailer->isHTML(TRUE);
      $result = $mailer->send();
    } else {
      return FALSE;
    }
    
    return $result;
  }
}