<?php

class CRM_CiviMobileAPI_Api_CiviMobileTagStructure_Get extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * Entity Tag items
   */
  private $entityTags;

  /**
   * Api tag map
   */
  private $tagMap = [
    'Contacts' => 'civicrm_contact',
    'Activities' => 'civicrm_activity',
    'Cases' => 'civicrm_case',
    'Attachements' => 'civicrm_file',
  ];

  /**
   * Returns results to api
   *
   * @return array
   */
  public function getResult() {
    $this->entityTags = $this->getentityTags($this->tagMap[$this->validParams['entity']], $this->validParams['entity_id']);
    $tagSets = $this->getTagSetsItems();
    $tagTree = $this->getTagTreeItems();

    if ($this->validParams['is_tag_tree_show_in_two_level']) {
      $tagTree = $this->transformTagTreeItems($tagTree);
    }

    return [['tag_tree' => $tagTree, 'tag_sets' => $tagSets]];
  }

  /**
   * Returns validated params
   *
   * @param $params
   *
   * @return array
   * @throws \api_Exception
   */
  public function getValidParams($params) {
    $availableEntities = array_keys($this->tagMap);
    if (!in_array($params['entity'], $availableEntities)) {
      throw new api_Exception('Invalid entity. Available values: (' . implode(', ', $availableEntities) . ')', 'used_for_invalid_value');
    }

    if (empty($params['entity_id'])) {
      throw new api_Exception('"entity_id" is required field.', 'required_filed');
    }

    return [
      'is_tag_tree_show_in_two_level' => (bool) $params['is_tag_tree_show_in_two_level'],
      'entity' => $params['entity'],
      'entity_id' => $params['entity_id'],
    ];
  }

  /**
   * @param $parentTag
   *
   * @return mixed
   */
  public function setChildTags($parentTag) {
    $parentTag['child_tags'] = [];

    $childItems = civicrm_api4('Tag', 'get', [
      'where' => [
        ['parent_id', '=', $parentTag['id']],
        ['used_for', '=', $this->tagMap[$this->validParams['entity']]],
      ],
      'checkPermissions' => FALSE,
    ])->getArrayCopy();

    foreach ($childItems as $childItem) {
      $childItem = $this->setIsTagChecked($childItem);
      $parentTag['child_tags'][] = $this->setChildTags($childItem);
    }

    $parentTag['used_for'] = reset($parentTag['used_for']);
    return $parentTag;
  }

  /**
   * Gets entity tags by 'entity table' and 'entity id'
   *
   * @param $entityTable
   * @param $entityId
   *
   * @return array
   */
  private function getEntityTags($entityTable, $entityId) {
    $entityTags = civicrm_api4('EntityTag', 'get', [
      'where' => [
        ['entity_id', '=', $entityId],
        ['entity_table', '=', $entityTable],
      ],
      'checkPermissions' => FALSE,
    ])->getArrayCopy();

    $entityTagsFormatted = [];

    foreach ($entityTags as $entityTagItem) {
      $entityTagsFormatted[$entityTagItem['tag_id']] = $entityTagItem;
    }

    return $entityTagsFormatted;
  }

  /**
   * Gets tag sets items
   *
   * @return array
   */
  private function getTagSetsItems() {
    $tagSets = [];

    $tagSetItems = civicrm_api4('Tag', 'get', [
      'where' => [
        ['is_tagset', '=', TRUE],
        ['used_for', '=', $this->tagMap[$this->validParams['entity']]],
      ],
      'checkPermissions' => FALSE,
    ])->getArrayCopy();

    foreach ($tagSetItems as $tagSetItem) {
      $tagSetItem = $this->setIsTagChecked($tagSetItem);
      $tagSetItem['used_for'] = reset($tagSetItem['used_for']);

      $childTags = civicrm_api4('Tag', 'get', [
        'where' => [
          ['is_tagset', '=', FALSE],
          ['used_for', '=', $this->tagMap[$this->validParams['entity']]],
          ['parent_id', '=', $tagSetItem['id']],
        ],
        'checkPermissions' => FALSE,
      ])->getArrayCopy();

      $tagSetItem['child_tags'] = [];
      foreach ($childTags as $childTag) {
        $childTag['used_for'] = reset($childTag['used_for']);
        $tagSetItem['child_tags'][] = $this->setIsTagChecked($childTag);
      }

      $tagSets[] = $tagSetItem;
    }

    return $tagSets;
  }

  /**
   * Gets tag tree items
   *
   * @return array
   */
  private function getTagTreeItems() {
    $tagTree = [];

    $tagTreeItems = civicrm_api4('Tag', 'get', [
      'where' => [
        ['is_tagset', '=', FALSE],
        ['used_for', '=', $this->tagMap[$this->validParams['entity']]],
        ['parent_id', 'IS NULL'],
      ],
      'checkPermissions' => FALSE,
    ])->getArrayCopy();

    foreach ($tagTreeItems as $tagTreeItem) {
      $tagTreeItem = $this->setIsTagChecked($tagTreeItem);
      $tagTree[] = $this->setChildTags($tagTreeItem);
    }

    return $tagTree;
  }

  /**
   * Sets 'is_tag_checked' and 'entity_tag_id' filed for tag
   *
   * @param $tag
   *
   * @return mixed
   */
  private function setIsTagChecked($tag) {
    if (empty($tag['id'])) {
      return $tag;
    }

    if (!empty(($this->entityTags[$tag['id']]))) {
      $tag['is_tag_checked'] = 1;
      $tag['entity_tag_id'] = ($this->entityTags[$tag['id']]['id']);
    } else {
      $tag['is_tag_checked'] = 0;
      $tag['entity_tag_id'] = 'NULL';
    }

    return $tag;
  }

  /**
   * Transform tag tree items in two level
   *
   * @param $tagTreeItems
   *
   * @return mixed
   */
  private function transformTagTreeItems($tagTreeItems) {
    if (empty($tagTreeItems)) {
      return [];
    }

    $transformedTags = [];
    foreach ($tagTreeItems as $key => $tag) {
      $childTags = [];
      foreach ($tag['child_tags'] as $childTag) {
        $collection = $this->collectChildTags($childTag, []);
        $childTags = array_merge($childTags, $collection);
      }
      $tagTreeItems[$key]['child_tags'] = $childTags;
      $transformedTags[] = $tagTreeItems[$key];
    }

    return $transformedTags;
  }

  /**
   * Collect child tags
   *
   * @param $childTag
   * @param $collection
   *
   * @return array
   */
  private function collectChildTags($childTag, $collection) {
    if (empty($childTag)) {
      return $collection;
    }

    foreach ($childTag['child_tags'] as $key => $tag) {
      $childTags = $this->collectChildTags($tag, []);
      $collection = array_merge($childTags, $collection);
    }

    unset($childTag['child_tags']);
    $collection[] = $childTag;

    return $collection;
  }

}
