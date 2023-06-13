<?php

/**
 * Class provide Contribution filter methods
 */
class CRM_CiviMobileAPI_Utils_ContactFieldsFilter {

  /**
   * List of contact Id
   *
   * @var array
   */
  private $contactsId = [];

  /**
   * Get filter contacts Id
   *
   * @param $params
   * @return array
   */
  public function filterContacts($params) {
    if (!empty($params['is_membership'])) {
      if (!empty($params['membership_contact_id']) && !empty($params['membership_contact_id']['IN'])) {
        $listOfContactsId = $params['membership_contact_id']['IN'];
      } elseif (!empty($params['membership_contact_id']) && !empty((int)$params['membership_contact_id'])) {
        $listOfContactsId = [$params['membership_contact_id']];
      } else {
        $listOfContactsId = CRM_CiviMobileAPI_Utils_Statistic_Utils::getListOfMembershipContactIds();
      }

      $listOfContactsId = CRM_Contact_BAO_Contact_Permission::allowList($listOfContactsId);
    } else {
      $listOfContactsId = $this->getListOfContributionContactsId();
    }

    $this->filterContactByNameOrTypes($params['contact_display_name'], $params['contact_type'], $listOfContactsId);
    $this->filterContactByTags($params['contact_tags']);
    $this->filterContactByGroup($params['contact_groups']["IN"]);

    return $this->contactsId;
  }

  /**
   * Get contacts Id filter by tags
   *
   * @param $selectedContactTagsId
   * @return array
   */
  public function filterContactByTags($selectedContactTagsId) {
    if (!empty($this->contactsId) && !empty($selectedContactTagsId)) {
      $selectedTagNames = $this->getSelectedTagsNames($selectedContactTagsId);

      try {
        $entityTags = civicrm_api3('EntityTag', 'get', [
          'sequential' => 1,
          'entity_id' => ['IN' => $this->contactsId],
          'tag_id' => ['IN' => $selectedTagNames],
          'options' => ['limit' => 0],
        ])['values'];
      } catch (CiviCRM_API3_Exception $e) {
        return [];
      }

      $this->contactsId = [];
      if (!empty($entityTags)) {
        foreach ($entityTags as $entityTag) {
          if (!in_array($entityTag['entity_id'], $this->contactsId)) {
            $this->contactsId[] = $entityTag['entity_id'];
          }
        }
      }
    }
  }

  /**
   * Get tags names by tags Id
   *
   * @param $selectedTagsId
   * @return array
   */
  public function getSelectedTagsNames($selectedTagsId) {
    $tagsNames = [];

    try {
      $tagsName = civicrm_api3('Tag', 'get', [
        'sequential' => 1,
        'return' => ["name"],
        'id' => $selectedTagsId,
      ])['values'];
    } catch (CiviCRM_API3_Exception $e) {
      return [];
    }

    if ($tagsName) {
      foreach ($tagsName as $tagName) {
        $tagsNames[] = $tagName['name'];
      }
    }

    return $tagsNames;
  }

  /**
   * Get contact's Id filter by display name or types
   *
   * @param $contactDisplayName
   * @return array
   */
  public function filterContactByNameOrTypes($contactDisplayName, $contactTypes, $listOfContributionContactsId) {
    if ((!empty($contactDisplayName) || !empty($contactTypes)) && !empty($listOfContributionContactsId)) {
      $contactDisplayNameParam = !empty($contactDisplayName) ? ['LIKE' => $contactDisplayName] : NULL;
      $contactTypesParam = !empty($contactTypes) ? $contactTypes : NULL;

      try {
        $contacts = civicrm_api3('Contact', 'get', [
          'sequential' => 1,
          'display_name' => $contactDisplayNameParam,
          'contact_id' => ["IN" => $listOfContributionContactsId],
          'contact_is_deleted' => 0,
          'contact_type' => $contactTypesParam,
          'options' => ['limit' => 0],
          'return' => ["id"]
        ])['values'];
      } catch (CiviCRM_API3_Exception $e) {
        return [];
      }

      $this->contactsId = [];
      if (!empty($contacts)) {
        foreach ($contacts as $contact) {
          $this->contactsId[] = $contact['id'];
        }
      }
    } else {
      $this->contactsId = $listOfContributionContactsId;
    }
  }

  /**
   * Get contacts Id filtered by groups
   *
   * @param $selectedContactGroupsId
   */
  public function filterContactByGroup($selectedContactGroupsId) {
    if (!empty($this->contactsId) && !empty($selectedContactGroupsId)) {
      $prepareContactId = implode(",", $this->contactsId);
      $prepareSelectedGroupId = implode(",", $selectedContactGroupsId);
      CRM_Contact_BAO_GroupContactCache::loadAll();

      $select = "SELECT DISTINCT(`contact_id`)";
      $fromGroupContact = " FROM civicrm_group_contact";
      $fromGroupContactCache = " FROM civicrm_group_contact_cache";
      $where = " WHERE contact_id IN ( $prepareContactId ) AND group_id IN ( $prepareSelectedGroupId ) ";
      $and = " AND status = 'Added' ";
      $sql = $select . $fromGroupContact . $where . $and . " UNION " . $select . $fromGroupContactCache . $where;

      $contactGroupsRelationList = [];
      try {
        $dao = CRM_Core_DAO::executeQuery($sql);
        while ($dao->fetch()) {
          $contactGroupsRelationList[] = [
            'contact_id' => $dao->contact_id
          ];
        }
      } catch (Exception $e) {
        $contactGroupsRelationList = [];
      }

      $this->contactsId = [];

      if (!empty($contactGroupsRelationList)) {
        foreach ($contactGroupsRelationList as $contactGroupsRelation) {
          $this->contactsId[] = (int)$contactGroupsRelation['contact_id'];
        }
      }

    }
  }

  /**
   * Get contribution contacts Id
   *
   * @return array
   */
  public static function getListOfContributionContactsId() {
    $contributionTable = CRM_Contribute_DAO_Contribution::getTableName();
    $contactsId = [];

    try {
      $contributionContactsId = CRM_Core_DAO::executeQuery("SELECT DISTINCT(contact_id) FROM $contributionTable")->fetchAll();
    } catch (Exception $e) {
      return [];
    }

    if (!empty($contributionContactsId)) {
      foreach ($contributionContactsId as $contributionContactId) {
        $contactsId[] = $contributionContactId['contact_id'];
      }
    }

    return $contactsId;
  }

}
