<?php

namespace Civi\Api4\Action\CiviMobileGroupContact;

use Civi\Api4\Generic\AbstractAction;
use Civi\Api4\Generic\Result;
use CRM_Contact_BAO_GroupNesting;

class GetContacts extends AbstractAction {

  /**
   * Group Id
   *
   * @required
   * @var int
   */
  protected $groupId;

  /**
   * Status
   *
   * @var array
   */
  protected $statuses;

  /**
   * Tags
   *
   * @var array
   */
  protected $tags;

  /**
   * Name or Email
   *
   * @var string
   */
  protected $nameOrEmail;

  /**
   * Contact Type
   *
   * @var string
   */
  protected $contactType;

  /**
   * Order
   *
   * @var array
   */
  protected $order;

  /**
   * Limit
   *
   * @var int
   */
  protected $limit;

  /**
   * Offset
   *
   * @var int
   */
  protected $offset;

  /**
   * @required
   * @return array
   */
  public static function fields() {
    return [
      ['name' => 'groupId', 'data_type' => 'Int'],
      ['name' => 'statuses', 'data_type' => 'Array'],
      ['name' => 'tags', 'data_type' => 'Array'],
      ['name' => 'nameOrEmail', 'data_type' => 'String'],
      ['name' => 'contactType', 'data_type' => 'String'],
      ['name' => 'order', 'data_type' => 'Array'],
      ['name' => 'limit', 'data_type' => 'Int'],
      ['name' => 'offset', 'data_type' => 'Int'],
    ];
  }

  public function _run(Result $result) {
    $formattedContacts = [];

    if (empty($this->statuses) || (count($this->statuses) === 1 && $this->statuses[0] === 'Added')) {
      $whereConditions = [
        ['groups', 'IN', [$this->groupId]],
      ];

      if (!empty($this->tags)) {
        $whereConditions[] = ['tags', 'IN', $this->tags];
      }
      if (!empty($this->nameOrEmail)) {
        $whereConditions[] = [
          'OR',
          [
            ['display_name', 'LIKE', '%' . $this->nameOrEmail . '%'],
            ['email_primary.email', 'LIKE', '%' . $this->nameOrEmail . '%'],
          ],
        ];
      }
      if (!empty($this->contactType)) {
        $whereConditions[] = [
          'OR',
          [
            ['contact_type', '=', $this->contactType],
            ['contact_sub_type', '=', $this->contactType],
          ],
        ];
      }

      $allContacts = civicrm_api4('Contact', 'get', [
        'select' => [
          'id',
          'contact_type',
          'display_name',
          'image_URL',
          'group_contact.status:label',
        ],
        'join' => [
          [
            'GroupContact AS group_contact',
            'LEFT',
            ['group_contact.contact_id', '=', 'id'],
            ['group_contact.group_id', 'IN', [$this->groupId]],
          ],
        ],
        'where' => $whereConditions,
        'orderBy' => $this->order,
        'limit' => $this->limit,
        'offset' => $this->offset,
        'checkPermissions' => FALSE,
      ])->getArrayCopy();

      foreach ($allContacts as $contact) {
        $formattedContacts[$contact['id']] = [
          'contact_id' => $contact['id'],
          'display_name' => $contact['display_name'],
          'image_URL' => $contact['image_URL'],
          'contact_type' => $contact['contact_type'],
          'status' => $contact['group_contact.status:label'],
        ];
      }
    }
    else {
      $allGroupIds = CRM_Contact_BAO_GroupNesting::getDescendentGroupIds([$this->groupId]);

      $contactIds = [];

      $whereConditions = [
        ['group_id', 'IN', $allGroupIds],
      ];

      if (!empty($this->tags)) {
        $whereConditions[] = ['contact_id.tags', 'IN', $this->tags];
      }
      if (!empty($this->nameOrEmail)) {
        $whereConditions[] = [
          'OR',
          [
            ['contact_id.display_name', 'LIKE', '%' . $this->nameOrEmail . '%'],
            ['email_id.email', 'LIKE', '%' . $this->nameOrEmail . '%'],
          ],
        ];
      }
      if (!empty($this->contactType)) {
        $whereConditions[] = [
          'OR',
          [
            ['contact_id.contact_type', '=', $this->contactType],
            ['contact_id.contact_sub_type', '=', $this->contactType],
          ],
        ];
      }

      $statusesFormatted = [];

      foreach ($this->statuses as $status) {
        $statusesFormatted[] = ['status', '=', $status];
      }

      $whereConditions[] = [
        'OR',
        $statusesFormatted,
      ];

      $allContacts = civicrm_api4('GroupContact', 'get', [
        'select' => [
          'contact_id.contact_type',
          'contact_id',
          'contact_id.display_name',
          'contact_id.image_URL',
          'status:label',
        ],
        'where' => $whereConditions,
        'groupBy' => [
          'contact_id',
        ],
        'orderBy' => $this->order,
        'limit' => $this->limit,
        'offset' => $this->offset,
        'checkPermissions' => FALSE,
      ])->getArrayCopy();

      foreach ($allContacts as $contact) {
        $formattedContacts[$contact['contact_id']] = [
          'contact_id' => $contact['contact_id'],
          'display_name' => $contact['contact_id.display_name'],
          'image_URL' => $contact['contact_id.image_URL'],
          'contact_type' => $contact['contact_id.contact_type'],
          'status' => $contact['status:label'],
        ];

        $contactIds[] = $contact['contact_id'];
      }

      $parentContacts = civicrm_api4('GroupContact', 'get', [
        'select' => [
          'status:label',
          'contact_id',
        ],
        'where' => [
          ['group_id', '=', $this->groupId],
          ['contact_id', 'IN', $contactIds],
        ],
        'checkPermissions' => FALSE,
      ])->getArrayCopy();

      foreach ($parentContacts as $parentContact) {
        $formattedContacts[$parentContact['contact_id']]['status'] = $parentContact['status:label'];
      }
    }

    $result->exchangeArray(array_values($formattedContacts));
  }

}
