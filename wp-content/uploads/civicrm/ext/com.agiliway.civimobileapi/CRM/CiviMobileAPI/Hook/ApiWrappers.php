<?php

class CRM_CiviMobileAPI_Hook_ApiWrappers {

  public static function run(&$wrappers, $apiRequest) {
    if ($apiRequest['entity'] == 'Contact' && ($apiRequest['action'] == 'getsingle' || $apiRequest['action'] == 'get')) {
      $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Contact();
    } elseif ($apiRequest['entity'] == 'Address' && $apiRequest['action'] == 'get') {
      $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Address();
    } elseif ($apiRequest['entity'] == 'Activity') {
      if ($apiRequest['action'] == 'getsingle') {
        $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Activity_GetSingle();
      }

      if ($apiRequest['action'] == 'get') {
        $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Activity_Get();
      }
    } elseif ($apiRequest['entity'] == 'Case' && ($apiRequest['action'] == 'getsingle' || $apiRequest['action'] == 'get')) {
      $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Case();
    } elseif ($apiRequest['entity'] == 'Event' && ($apiRequest['action'] == 'getsingle' || $apiRequest['action'] == 'get')) {
      $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Event();
    } elseif ($apiRequest['entity'] == 'Note' && $apiRequest['action'] == 'get') {
      $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Note();
    } elseif ($apiRequest['entity'] == 'Contribution' && $apiRequest['action'] == 'get') {
      $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Contribution();
    } elseif ($apiRequest['entity'] == 'Membership') {
      if ($apiRequest['action'] == 'create') {
        $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Membership_Create();
      }

      if (CRM_CiviMobileAPI_Hook_Utils::is_mobile_request()) {
        if ($apiRequest['action'] == 'getsingle' || $apiRequest['action'] == 'get') {
          $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Membership_Get();
        }
      }
    } elseif ($apiRequest['entity'] == 'Relationship' && $apiRequest['action'] == 'get') {
      $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Relationship_Get();
    } elseif ($apiRequest['entity'] == 'Participant') {
      if ($apiRequest['action'] == 'create') {
        $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Participant_Create();
      } elseif ($apiRequest['action'] == 'get') {
        $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Participant_Get();
      }
    } elseif ($apiRequest['entity'] == 'GroupContact') {
      if ($apiRequest['action'] == 'get') {
        $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_GroupContact_Get();
      } elseif ($apiRequest['action'] == 'create') {
        $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_GroupContact_Create();
      }
    } elseif ($apiRequest['entity'] == 'EntityTag') {
      if ($apiRequest['action'] == 'get') {
        $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_EntityTag_Get();
      }
    } elseif ($apiRequest['entity'] == 'Survey') {
      if ($apiRequest['action'] == 'getsingle') {
        $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Survey_Getsingle();
      }
    } elseif ($apiRequest['entity'] == 'ContributionPage') {
      if ($apiRequest['action'] == 'get') {
        $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_ContributionPage();
      }
    }
  }
}
