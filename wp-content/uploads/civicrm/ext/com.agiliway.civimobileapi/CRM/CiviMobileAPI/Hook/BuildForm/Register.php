<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Hook_BuildForm_Register {

  /**
   * @param $formName
   * @param $form
   * @throws CRM_Core_Exception
   * @throws api_Exception
   */
  public function run($formName, &$form) {
    // remove $cmbHash if we are not using call from mobile application
    if (($formName != 'CRM_Event_Form_Registration_Confirm' && $formName != 'CRM_Event_Form_Registration_Register' && $formName != 'CRM_Financial_Form_Payment' && $formName != 'CRM_Event_Form_Registration_AdditionalParticipant')
      || $formName == 'CRM_Event_Form_Registration_ThankYou') {
      $session = CRM_Core_Session::singleton();
      $cmbHash = $session->get('cmbHash');

      // check if set $cmbHash (if we are using call from mobile application)
      if ($cmbHash) {
        CRM_CiviMobileAPI_BAO_CivimobileEventPaymentInfo::deleteByHash($cmbHash);
      }
    }

    if ($formName == 'CRM_Event_Form_Registration_Register') {
      $session = CRM_Core_Session::singleton();
      $reqHash = CRM_Utils_Request::retrieve('cmbHash', 'String');
      $cmbHash = ($session->get('cmbHash')) ? $session->get('cmbHash') : $reqHash;

      if ($reqHash && $reqHash != $cmbHash) {
        $cmbHash = CRM_Utils_Request::retrieve('cmbHash', 'String');
        $session->set('cmbHash', $cmbHash);
      }

      // check if set $cmbHash (if we are using call from mobile application)
      if ($cmbHash) {
        CRM_CiviMobileAPI_Utils_Extension::hideCiviMobileQrPopup();
        if ($tmpData = CRM_CiviMobileAPI_BAO_CivimobileEventPaymentInfo::getByHash($cmbHash)) {
          if (!empty($tmpData['contact_id'])) {
            CRM_Core_Session::singleton()->set('userID', $tmpData['contact_id']);
          }

          CRM_Core_Smarty::singleton()->assign('showCMS', FALSE);

          $priceSet = json_decode($tmpData['price_set'], true);
          $personalFields = $this->findPersonalFields($tmpData);
          $billingFields = $this->findBillingFields($tmpData);
          $billingLocationID = CRM_Core_BAO_LocationType::getBilling();

          if ($form->elementExists('first_name')) {
            $element = $form->getElement('first_name');
            $element->setValue($personalFields['first_name']);
          }

          if ($form->elementExists('last_name')) {
            $element = $form->getElement('last_name');
            $element->setValue($personalFields['last_name']);
          }

          if ($form->elementExists('email-Primary')) {
            $element = $form->getElement('email-Primary');
            $element->setValue('');
            if (!empty($personalFields['email'])) {
              $element->setValue($personalFields['email']);
            }
          }

          if (!empty($billingFields)) {
            if ($form->elementExists('billing_first_name')) {
              $element = $form->getElement('billing_first_name');
              $element->setValue($personalFields['first_name']);
            }

            if ($form->elementExists('billing_middle_name')) {
              $element = $form->getElement('billing_middle_name');
              $element->setValue($personalFields['middle_name']);
            }

            if ($form->elementExists('billing_last_name')) {
              $element = $form->getElement('billing_last_name');
              $element->setValue($personalFields['last_name']);
            }

            if ($form->elementExists("billing_street_address-{$billingLocationID}")) {
              $element = $form->getElement("billing_street_address-{$billingLocationID}");
              $element->setValue($billingFields['street_address']);
            }

            if ($form->elementExists("billing_city-{$billingLocationID}")) {
              $element = $form->getElement("billing_city-{$billingLocationID}");
              $element->setValue($billingFields['city']);
            }

            if ($form->elementExists("billing_country_id-{$billingLocationID}")) {
              $element = $form->getElement("billing_country_id-{$billingLocationID}");
              $element->setValue($billingFields['country_id']);
            }

            if ($form->elementExists("billing_state_province_id-{$billingLocationID}")) {
              $element = $form->getElement("billing_state_province_id-{$billingLocationID}");
              $element->setValue($billingFields['state_province_id']);
            }

            if ($form->elementExists("billing_postal_code-{$billingLocationID}")) {
              $element = $form->getElement("billing_postal_code-{$billingLocationID}");
              $element->setValue($billingFields['state_province_id']);
            }
          }

          $this->setValuesToPriceSet($priceSet, $form);
          $session->set('cmbHash', $cmbHash);
        } else {
          $session->set('cmbHash', NULL);
        }
      }
    }

    $customizeForms = [
      'CRM_Event_Form_Registration_Confirm',
      'CRM_Event_Form_Registration_Register',
      'CRM_Financial_Form_Payment',
      'CRM_Event_Form_Registration_AdditionalParticipant',
      'CRM_Event_Form_Registration_ThankYou',
    ];

    if (in_array($formName, $customizeForms)) {
      self::customizeEventRegistration();
    }
  }

  /**
   * Include scripts and styles to Event Registrations
   *
   * @throws CRM_Core_Exception
   */
  public static function customizeEventRegistration() {
    $session = CRM_Core_Session::singleton();
    $cmbHash = ($session->get('cmbHash')) ? $session->get('cmbHash') : CRM_Utils_Request::retrieve('cmbHash', 'String');

    // check if set $cmbHash (if we are using call from mobile application)
    if ($cmbHash) {
      $template = CRM_Core_Smarty::singleton();
      $currentCMS = CRM_CiviMobileAPI_Utils_CmsUser::getInstance()->getSystem();

      $absURL = Civi::paths()->getUrl('[civicrm.root]/', 'absolute');

      $template->assign('absURL', $absURL);
      $template->assign('isDrupal7', $currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_DRUPAL7);
      $template->assign('isDrupal6', $currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_DRUPAL6);
      $template->assign('isWordpress', $currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_WORDPRESS);
      $template->assign('isJoomla', $currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_JOOMLA);
      $template->assign('eventButtonColor', (!empty(Civi::settings()->get('civimobile_event_registration_button_color'))) ? Civi::settings()->get('civimobile_event_registration_button_color') : "#5589B7");

      CRM_Core_Region::instance('page-body')->add([
        'template' => 'CRM/CiviMobileAPI/CustomizeEventRegistration.tpl',
      ]);
    }
  }

  /**
   * @param $priceSet
   * @param $form
   */
  private function setValuesToPriceSet($priceSet, &$form) {
    foreach ($priceSet as $psID => $psFieldIds) {
      foreach ($psFieldIds[0] as $psFieldId => $items) {
        foreach ($items as $item => $psFieldValueId) {
          $priceFieldName = 'price_' . $psFieldId;
          if ($form->elementExists($priceFieldName)) {
            $element = $form->getElement($priceFieldName);
            if ($element->getType() == 'select') {
              $element->setValue(key($psFieldValueId));
            } else if ($element->getAttribute('type') == 'text' && !empty($psFieldValueId[key($psFieldValueId)])) {
              $element = $form->getElement($priceFieldName);
              $element->setValue($psFieldValueId[key($psFieldValueId)]);
            } else {
              $elements = $element->getElements();
              foreach ($elements as $el) {
                if ($el->getAttribute('type') == 'checkbox' && $el->getAttribute('name') == key($psFieldValueId)) {
                  $el->setAttribute('checked', 'checked');
                } else if ($el->getAttribute('type') == 'radio' && $el->getAttribute('value') == key($psFieldValueId)) {
                  $el->setAttribute('checked', 'checked');
                }
              }
            }
          }
          $priceFieldName = 'price_' . $psFieldId . '_' . key($psFieldValueId);
          if ($form->elementExists($priceFieldName)) {
            $element = $form->getElement($priceFieldName);
            if ($element->getAttribute('type') == 'select') {
              $element->setValue(key($psFieldValueId));
            }
          }
        }
      }
    }
  }

  /**
   * @param $tmpData
   * @return array
   */
  private function findPersonalFields($tmpData) {
    $contactId = $tmpData['contact_id'];
    $email = null;

    if ($contactId) {
      try {
        $contact = civicrm_api3('Contact', 'getsingle', [
          'return' => ["first_name", "last_name", "middle_name"],
          'sequential' => 1,
          'id' => $contactId
        ]);
      } catch (CiviCRM_API3_Exception $e) {
        throw new api_Exception(E::ts('Contact (id = %1) User can not be registered because contact do not exist', [1 => $contactId]), 'contact_does_not_exist');
      }

      try {
        $contactsEmail = civicrm_api3('Email', 'getsingle', [
          'return' => ["email"],
          'sequential' => 1,
          'contact_id' => $contactId,
          'is_primary' => 1,
        ]);
      } catch (CiviCRM_API3_Exception $e) {
        throw new api_Exception(E::ts('User was not registered.'), 'contact_cannot_be_registered');
      }

      $firstName = $contact['first_name'];
      $lastName = $contact['last_name'];
      $middleName = $contact['middle_name'];
      if (isset($contactsEmail['email'])) {
        $email = $contactsEmail['email'];
      }
    } else {
      $firstName = $tmpData['first_name'];
      $lastName = $tmpData['last_name'];
      $middleName = '';
      $email = $tmpData['email'];
    }

    return [
      'first_name' => $firstName,
      'last_name' => $lastName,
      'middle_name' => $middleName,
      'email' => $email,
    ];
  }

  /**
   * @param $tmpData
   * @return array
   */
  private function findBillingFields($tmpData) {
    $contactId = $tmpData['contact_id'];

    if ($contactId) {
      try {
        $contactsAddress = civicrm_api3('Address', 'getsingle', [
          'sequential' => 1,
          'contact_id' => $contactId,
          'is_billing' => 1,
        ]);
      } catch (CiviCRM_API3_Exception $e) {
        return [];
      }

      $streetAddress = isset($contactsAddress['street_address']) ? $contactsAddress['street_address'] : '';
      $city = isset($contactsAddress['city']) ? $contactsAddress['city'] : '';
      $countryId = isset($contactsAddress['country_id']) ? $contactsAddress['country_id'] : NULL;
      $stateProvinceId = isset($contactsAddress['state_province_id']) ? $contactsAddress['state_province_id'] : NULL;
      $postalCode = isset($contactsAddress['postal_code']) ? $contactsAddress['postal_code'] : NULL;

      return [
        'street_address' => $streetAddress,
        'city' => $city,
        'country_id' => $countryId,
        'state_province_id' => $stateProvinceId,
        'postal_code' => $postalCode,
      ];
    }
  }

}
