<?php

class CRM_CiviMobileAPI_BAO_LocationVenue extends CRM_CiviMobileAPI_DAO_LocationVenue {

  /**
   * Adds params
   *
   * @param $params
   *
   * @return \CRM_Core_DAO
   */
  public static function add(&$params) {
    $entity = new CRM_CiviMobileAPI_DAO_LocationVenue();
    $entity->copyValues($params);
    return $entity->save();
  }

  /**
   * Creates new row
   *
   * @param $params
   *
   * @return \CRM_Core_DAO
   */
  public static function &create($params) {
    $transaction = new self();

    if (!empty($params['id'])) {
      CRM_Utils_Hook::pre('edit', self::getEntityName(), $params['id'], $params);

      try {
        $venue = CRM_CiviMobileAPI_DAO_LocationVenue::findById($params['id']);
      } catch (Exception $e) {
        $transaction->rollback();
        throw new CRM_Core_Exception('Venue not found!');
      }

      $fieldValues = ['location_id' => $venue->location_id];
      if (!empty($params['weight'])) {
        $params['weight'] = CRM_Utils_Weight::updateOtherWeights('CRM_CiviMobileAPI_DAO_LocationVenue', $venue->weight, $params['weight'], $fieldValues);
      }

    } else {
      CRM_Utils_Hook::pre('create', self::getEntityName(), NULL, $params);

      $fieldValues = ['location_id' => CRM_Utils_Array::value('location_id', $params)];
      $weight = !empty($params['weight']) ? CRM_Utils_Weight::updateOtherWeights('CRM_CiviMobileAPI_DAO_LocationVenue', NULL, $params['weight'], $fieldValues) : CRM_Utils_Weight::getMax('CRM_CiviMobileAPI_DAO_LocationVenue', $fieldValues, 'weight') + 1;
      $params['weight'] = CRM_Utils_Weight::updateOtherWeights('CRM_CiviMobileAPI_DAO_LocationVenue', NULL, $weight, $fieldValues);
    }

    $entityData = self::add($params);

    if (is_a($entityData, 'CRM_Core_Error')) {
      $transaction->rollback();
      return $entityData;
    }

    $transaction->commit();

    if (!empty($params['id'])) {
      CRM_Utils_Hook::post('edit', self::getEntityName(), $entityData->id, $entityData);
    } else {
      CRM_Utils_Hook::post('create', self::getEntityName(), $entityData->id, $entityData);
    }

    return $entityData;
  }

  /**
   * Deletes row
   *
   * @param $id
   */
  public static function del($id) {
    $entity = new CRM_CiviMobileAPI_DAO_LocationVenue();
    $entity->id = $id;
    $params = [];
    if ($entity->find(TRUE)) {
      CRM_Utils_Hook::pre('delete', self::getEntityName(), $entity->id, $params);

      $fieldValues = ['location_id' => $entity->location_id];
      CRM_Utils_Weight::delWeight('CRM_CiviMobileAPI_DAO_LocationVenue', $entity->id, $fieldValues);

      $entity->delete();

      CRM_Utils_Hook::post('delete', self::getEntityName(), $entity->id, $entity);
    }
  }

  /**
   * Builds query for receiving data
   *
   * @param string $returnValue
   *
   * @return \CRM_Utils_SQL_Select
   */
  private static function buildSelectQuery($returnValue = 'rows') {
    $query = CRM_Utils_SQL_Select::from(CRM_CiviMobileAPI_DAO_LocationVenue::getTableName());

    if ($returnValue == 'rows') {
      $query->select('
        id,
        name,
        description,
        address_description,
        address,
        longitude,
        latitude,
        is_active,
        location_id,
        weight,
        background_color,
        border_color
      ');
    } else {
      if ($returnValue == 'count') {
        $query->select('COUNT(id)');
      }
    }

    return $query;
  }

  /**
   * Builds 'where' condition for query
   *
   * @param $query
   * @param array $params
   *
   * @return mixed
   */
  private static function buildWhereQuery($query, $params = []) {
    if (!empty($params['id'])) {
      $query->where('id = #id', ['id' => $params['id']]);
    }
    if (!empty($params['name'])) {
      if (isset($params['name']['LIKE'])) {
        $query->where('name LIKE @name', ['name' => '%' . $params['name']['LIKE'] . '%']);
      } else {
        $query->where('name = @name', ['name' => $params['name']]);
      }
    }
    if (!empty($params['description'])) {
      $query->where('description = @description', ['description' => $params['description']]);
    }
    if (!empty($params['address_description'])) {
      $query->where('address_description = @address_description', ['address_description' => $params['address_description']]);
    }
    if (!empty($params['address'])) {
      $query->where('address = @address', ['address' => $params['address']]);
    }
    if (!empty($params['latitude'])) {
      $query->where('latitude = @latitude', ['latitude' => $params['latitude']]);
    }
    if (!empty($params['longitude'])) {
      $query->where('longitude = @longitude', ['longitude' => $params['longitude']]);
    }
    if (isset($params['is_active'])) {
      $query->where('is_active = #is_active', ['is_active' => $params['is_active']]);
    }
    if (!empty($params['location_id'])) {
      $query->where('location_id = #location_id', ['location_id' => $params['location_id']]);
    }
    return $query;
  }

  /**
   * Adds order params to query
   *
   * @param $query
   * @param array $params
   * @return mixed
   */
  private static function buildOrderQuery($query, $params = []) {
    if (!empty($params['options']['sort'])) {
      $sortParams = explode(' ', strtolower($params['options']['sort']));
      $availableFieldsToSort = ['weight'];
      $order = '';

      if (!empty($sortParams[1]) && ($sortParams[1] == 'desc' || $sortParams[1] == 'asc')) {
        $order = $sortParams[1];
      }
      if (in_array($sortParams[0], $availableFieldsToSort)) {
        $query->orderBy($sortParams[0] . ' ' . $order);
      }
    }

    return $query;
  }

  /**
   * Gets all data
   *
   * @param array $params
   *
   * @return array
   */
  public static function getAll($params = []) {
    $query = self::buildOrderQuery(self::buildWhereQuery(self::buildSelectQuery(), $params), $params);
    return CRM_Core_DAO::executeQuery($query->toSQL())->fetchAll();
  }

  /**
   * Deletes all lost venues
   */
  public static function deleteVenuesWithoutLocation() {
    $query = 'DELETE ' . self::getTableName() . '
      FROM ' . self::getTableName() . '
      LEFT JOIN civicrm_loc_block ON ' . self::getTableName() . '.location_id=civicrm_loc_block.id
      WHERE civicrm_loc_block.id IS NULL';
    CRM_Core_DAO::executeQuery($query);
  }

}
