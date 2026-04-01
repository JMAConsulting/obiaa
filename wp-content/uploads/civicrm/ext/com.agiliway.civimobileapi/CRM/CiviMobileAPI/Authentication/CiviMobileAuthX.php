<?php

use Civi\Authx\Authenticator;
use Civi\Authx\AuthenticatorTarget;

class CRM_CiviMobileAPI_Authentication_CiviMobileAuthX extends Authenticator {

  public function authenticate($login, $password) {
    $this->setRejectMode('exception');

    $tgt = AuthenticatorTarget::create([
      'flow' => 'script',
      'cred' => 'Basic ' . base64_encode($login . ':' . $password),
      'siteKey' => NULL,
      'useSession' => FALSE,
    ]);

    if ($principal = $this->checkCredential($tgt)) {
      $tgt->setPrincipal($principal);
    }

    if (empty($principal)) {
      return NULL;
    }

    $contact = new CRM_Contact_BAO_Contact();
    $contact->get('id', $tgt->createRedacted()['contactId']);

    return $contact;
  }

}
