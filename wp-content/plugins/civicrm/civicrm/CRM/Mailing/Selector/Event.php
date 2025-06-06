<?php
/*
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC. All rights reserved.                        |
 |                                                                    |
 | This work is published under the GNU AGPLv3 license with some      |
 | permitted exceptions and without any warranty. For full license    |
 | and copyright information, see https://civicrm.org/licensing       |
 +--------------------------------------------------------------------+
 */

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 */

/**
 * This class is used to retrieve and display a range of
 * contacts that match the given criteria (specifically for
 * results of advanced search options.
 */
class CRM_Mailing_Selector_Event extends CRM_Core_Selector_Base implements CRM_Core_Selector_API {

  /**
   * Array of supported links, currently null
   *
   * @var array
   */
  public static $_links = NULL;

  /**
   * What event type are we browsing?
   * @var string
   */
  private $_event_type;

  /**
   * Should we only count distinct contacts?
   * @var bool
   */
  private $_is_distinct;

  /**
   * Which mailing are we browsing events from?
   * @var int
   */
  private $_mailing_id;

  /**
   * Do we want events tied to a specific job?
   * @var int
   */
  private $_job_id;

  /**
   * For click-through events, do we only want those from a specific url?
   * @var int
   */
  private $_url_id;

  /**
   * We use desc to remind us what that column is, name is used in the tpl
   *
   * @var array
   */
  public $_columnHeaders;

  /**
   * Class constructor.
   *
   * @param string $event
   *   The event type (queue/delivered/open...).
   * @param bool $distinct
   *   Count only distinct contact events?.
   * @param int $mailing
   *   ID of the mailing to query.
   * @param int $job
   *   ID of the job to query. If null, all jobs from $mailing are queried.
   * @param int $url
   *   If the event type is a click-through, do we want only those from a specific url?.
   *
   * @return \CRM_Mailing_Selector_Event
   */
  public function __construct($event, $distinct, $mailing, $job = NULL, $url = NULL) {
    $this->_event_type = $event;
    $this->_is_distinct = $distinct;
    $this->_mailing_id = $mailing;
    $this->_job_id = $job;
    $this->_url_id = $url;
  }

  /**
   * This method returns the links that are given for each search row.
   *
   * @return array
   */
  public static function &links() {
    return self::$_links;
  }

  /**
   * Getter for array of the parameters required for creating pager.
   *
   * @param $action
   * @param array $params
   */
  public function getPagerParams($action, &$params) {
    $params['csvString'] = NULL;
    $params['rowCount'] = Civi::settings()->get('default_pager_size');
    $params['status'] = ts('%1 %%StatusMessage%%', [1 => $this->eventToTitle()]);
    $params['buttonTop'] = 'PagerTopButton';
    $params['buttonBottom'] = 'PagerBottomButton';
  }

  /**
   * Returns the column headers as an array of tuples:
   * (name, sortName (key to the sort array))
   *
   * @param string $action
   *   The action being performed.
   * @param string $output
   *   What should the result set include (web/email/csv).
   *
   * @return array
   *   the column headers that need to be displayed
   */
  public function &getColumnHeaders($action = NULL, $output = NULL) {
    $mailing = CRM_Mailing_BAO_Mailing::getTableName();

    $contact = CRM_Contact_BAO_Contact::getTableName();

    $email = CRM_Core_BAO_Email::getTableName();

    $job = CRM_Mailing_BAO_MailingJob::getTableName();
    if (!isset($this->_columnHeaders)) {

      $this->_columnHeaders = [
        'sort_name' => [
          'name' => ts('Contact'),
          'sort' => $contact . '.sort_name',
          'direction' => CRM_Utils_Sort::ASCENDING,
        ],
        'email' => [
          'name' => ts('Email Address'),
          'sort' => $email . '.email',
          'direction' => CRM_Utils_Sort::DONTCARE,
        ],
      ];

      switch ($this->_event_type) {
        case 'queue':
          $dateSort = $job . '.start_date';
          break;

        case 'delivered':
          $this->_columnHeaders = [
            'contact_id' => [
              'name' => ts('Internal Contact ID'),
              'sort' => $contact . '.id',
              'direction' => CRM_Utils_Sort::ASCENDING,
            ],
          ] + $this->_columnHeaders;
          $dateSort = CRM_Mailing_Event_BAO_MailingEventDelivered::getTableName() . '.time_stamp';
          break;

        case 'opened':
          $dateSort = 'civicrm_mailing_event_opened.time_stamp';
          break;

        case 'bounce':
          $dateSort = CRM_Mailing_Event_BAO_MailingEventBounce::getTableName() . '.time_stamp';
          $this->_columnHeaders = array_merge($this->_columnHeaders,
            [
              [
                'name' => ts('Bounce Type'),
              ],
              [
                'name' => ts('Bounce Reason'),
              ],
            ]
          );
          break;

        case 'reply':
          $dateSort = CRM_Mailing_Event_BAO_MailingEventReply::getTableName() . '.time_stamp';
          break;

        case 'unsubscribe':
          $dateSort = CRM_Mailing_Event_BAO_MailingEventUnsubscribe::getTableName() . '.time_stamp';
          $this->_columnHeaders = array_merge($this->_columnHeaders, [
            [
              'name' => ts('Unsubscribe'),
            ],
          ]);
          break;

        case 'optout':
          $dateSort = CRM_Mailing_Event_BAO_MailingEventUnsubscribe::getTableName() . '.time_stamp';
          $this->_columnHeaders = array_merge($this->_columnHeaders, [
            [
              'name' => ts('Opt-Out'),
            ],
          ]);
          break;

        case 'click':
          $dateSort = CRM_Mailing_Event_BAO_MailingEventTrackableURLOpen::getTableName() . '.time_stamp';
          $this->_columnHeaders = array_merge($this->_columnHeaders, [
            [
              'name' => ts('URL'),
            ],
          ]);
          break;

        default:
          return 0;
      }

      $this->_columnHeaders = array_merge($this->_columnHeaders, [
        'date' => [
          'name' => ts('Date'),
          'sort' => $dateSort,
          'direction' => CRM_Utils_Sort::DESCENDING,
        ],
      ]);
    }
    return $this->_columnHeaders;
  }

  /**
   * Returns total number of rows for the query.
   *
   * @param string $action
   *
   * @return int
   *   Total number of rows
   */
  public function getTotalCount($action) {
    switch ($this->_event_type) {
      case 'queue':
        $event = new CRM_Mailing_Event_BAO_MailingEventQueue();
        $result = $event->getTotalCount($this->_mailing_id,
          $this->_job_id
        );
        return $result;

      case 'delivered':
        $event = new CRM_Mailing_Event_BAO_MailingEventDelivered();
        $result = $event->getTotalCount($this->_mailing_id,
          $this->_job_id,
          $this->_is_distinct
        );
        return $result;

      case 'opened':
        $event = new CRM_Mailing_Event_BAO_MailingEventOpened();
        $result = $event->getTotalCount($this->_mailing_id,
          $this->_job_id,
          $this->_is_distinct
        );
        return $result;

      case 'bounce':
        $event = new CRM_Mailing_Event_BAO_MailingEventBounce();
        $result = $event->getTotalCount($this->_mailing_id,
          $this->_job_id,
          $this->_is_distinct
        );
        return $result;

      case 'reply':
        $event = new CRM_Mailing_Event_BAO_MailingEventReply();
        $result = $event->getTotalCount($this->_mailing_id,
          $this->_job_id,
          $this->_is_distinct
        );
        return $result;

      case 'unsubscribe':
        $event = new CRM_Mailing_Event_BAO_MailingEventUnsubscribe();
        $result = $event->getTotalCount($this->_mailing_id,
          $this->_job_id,
          $this->_is_distinct
        );
        return $result;

      case 'optout':
        $event = new CRM_Mailing_Event_BAO_MailingEventUnsubscribe();
        $result = $event->getTotalCount($this->_mailing_id,
          $this->_job_id,
          $this->_is_distinct,
          FALSE
        );
        return $result;

      case 'click':
        $event = new CRM_Mailing_Event_BAO_MailingEventTrackableURLOpen();
        $result = $event->getTotalCount($this->_mailing_id,
          $this->_job_id,
          $this->_is_distinct,
          $this->_url_id
        );
        return $result;

      default:
        return 0;
    }
  }

  /**
   * Returns all the rows in the given offset and rowCount.
   *
   * @param string $action
   *   The action being performed.
   * @param int $offset
   *   The row number to start from.
   * @param int $rowCount
   *   The number of rows to return.
   * @param string $sort
   *   The sql string that describes the sort order.
   * @param string $output
   *   What should the result set include (web/email/csv).
   *
   * @return int
   *   the total number of rows for this action
   */
  public function &getRows($action, $offset, $rowCount, $sort, $output = NULL) {
    switch ($this->_event_type) {
      case 'queue':
        $rows = CRM_Mailing_Event_BAO_MailingEventQueue::getRows($this->_mailing_id,
          $this->_job_id, $offset, $rowCount, $sort
        );
        return $rows;

      case 'delivered':
        $rows = CRM_Mailing_Event_BAO_MailingEventDelivered::getRows($this->_mailing_id,
          $this->_job_id, $this->_is_distinct,
          $offset, $rowCount, $sort
        );
        return $rows;

      case 'opened':
        $rows = CRM_Mailing_Event_BAO_MailingEventOpened::getRows($this->_mailing_id,
          $this->_job_id, $this->_is_distinct,
          $offset, $rowCount, $sort
        );
        return $rows;

      case 'bounce':
        $rows = CRM_Mailing_Event_BAO_MailingEventBounce::getRows($this->_mailing_id,
          $this->_job_id, $this->_is_distinct,
          $offset, $rowCount, $sort
        );
        return $rows;

      case 'reply':
        $rows = CRM_Mailing_Event_BAO_MailingEventReply::getRows($this->_mailing_id,
          $this->_job_id, $this->_is_distinct,
          $offset, $rowCount, $sort
        );
        return $rows;

      case 'unsubscribe':
        $rows = CRM_Mailing_Event_BAO_MailingEventUnsubscribe::getRows($this->_mailing_id,
          $this->_job_id, $this->_is_distinct,
          $offset, $rowCount, $sort, TRUE
        );
        return $rows;

      case 'optout':
        $rows = CRM_Mailing_Event_BAO_MailingEventUnsubscribe::getRows($this->_mailing_id,
          $this->_job_id, $this->_is_distinct,
          $offset, $rowCount, $sort, FALSE
        );
        return $rows;

      case 'click':
        $rows = CRM_Mailing_Event_BAO_MailingEventTrackableURLOpen::getRows(
          $this->_mailing_id, $this->_job_id,
          $this->_is_distinct, $this->_url_id,
          $offset, $rowCount, $sort
        );
        return $rows;

      default:
        return NULL;
    }
  }

  /**
   * Name of export file.
   *
   * @param string $output
   *   Type of output.
   *
   * @return string|NULL
   *   name of the file
   */
  public function getExportFileName($output = 'csv') {
    return NULL;
  }

  /**
   * Get the title for the mailing event type.
   *
   * @return string
   */
  public function eventToTitle() {
    static $events = NULL;

    if (empty($events)) {
      $events = [
        'queue' => ts('Intended Recipients'),
        'delivered' => ts('Successful Deliveries'),
        'bounce' => ts('Bounces'),
        'reply' => $this->_is_distinct ? ts('Unique Replies') : ts('Replies'),
        'unsubscribe' => ts('Unsubscribe Requests'),
        'optout' => ts('Opt-out Requests'),
        'click' => $this->_is_distinct ? ts('Unique Click-throughs') : ts('Click-throughs'),
        'opened' => $this->_is_distinct ? ts('Unique Tracked Opens') : ts('Total Tracked Opens'),
      ];
    }
    return $events[$this->_event_type];
  }

  /**
   * Get the title of the event.
   *
   * @return string
   */
  public function getTitle() {
    return $this->eventToTitle();
  }

}
