<?php

class CRM_CiviMobileAPI_Hook_SelectWhereClause_Note {

  public static function run($entity, &$clauses) {
    if ($entity == 'Note') {
      if ($json = CRM_Utils_Request::retrieve('json', 'String')) {
        $params = json_decode($json, TRUE);

        if (!empty($params['entity_table']) && $params['entity_table'] == 'civicrm_note') {
          unset($clauses['id']);
        }
      }
    }
  }
}
