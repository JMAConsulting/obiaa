<?php

// AUTO-GENERATED FILE -- Civix may overwrite any changes made to this file

/**
 * The ExtensionUtil class provides small stubs for accessing resources of this
 * extension.
 */
class CRM_Biaproperty_ExtensionUtil {
  const SHORT_NAME = 'biaproperty';
  const LONG_NAME = 'biaproperty';
  const CLASS_PREFIX = 'CRM_Biaproperty';

  /**
   * Translate a string using the extension's domain.
   *
   * If the extension doesn't have a specific translation
   * for the string, fallback to the default translations.
   *
   * @param string $text
   *   Canonical message text (generally en_US).
   * @param array $params
   * @return string
   *   Translated text.
   * @see ts
   */
  public static function ts($text, $params = []) {
    if (!array_key_exists('domain', $params)) {
      $params['domain'] = [self::LONG_NAME, NULL];
    }
    return ts($text, $params);
  }

  public static function closeProperty() {
    $id = CRM_Utils_Type::escape($_GET['id'], 'Positive');
    if (!$id) {
      CRM_Core_Error::statusBounce(ts('Missing property ID'));
    }

    $property = CRM_Core_DAO::executeQuery("SELECT name, street_address FROM civicrm_property p INNER JOIN civicrm_address a ON a.id = p.address_id WHERE p.id = " . $id)->fetchAll()[0];
    $title = (!empty($property['name'])) ? $property['name'] . ' - ' . $property['street_address'] : $property['street_address'];

    $propertyOwners = \Civi\Api4\PropertyOwner::get(FALSE)
      ->addWhere('property_id', '=', $id)
      ->execute();
    foreach ($propertyOwners as $propertyOwner) {
      \Civi\Api4\PropertyOwner::delete(FALSE)
        ->addWhere('id', '=', $propertyOwner['id'])
	->execute();
    $cid = $propertyOwner['owner_id'];
    \Civi\Api4\Activity::create(FALSE)
      ->addValue('activity_type_id:name', 'Property closed')
      ->addValue('target_contact_id', $cid)
      ->addValue('assignee_contact_id', $cid)
      ->addValue('source_contact_id', $cid)
      ->addValue('source_record_id', $id)
      ->addValue('status_id:name', 'Completed')
      ->addValue('subject', 'Closed property - ' . $title)
      ->execute();
    }
    CRM_Utils_System::redirect(CRM_Core_Session::singleton()->readUserContext());
  }

  public static function closeBusiness() {
    $bid = CRM_Utils_Type::escape($_GET['bid'], 'Positive');
    if (!$bid) {
      CRM_Core_Error::statusBounce(ts('Missing contact ID'));
    }
    $entries = \Civi\Api4\UnitBusiness::get(FALSE)
        ->addSelect('id')
        ->addWhere('business_id', '=', $bid)
        ->execute();
    foreach ($entries as $entry) {
      \Civi\Api4\UnitBusiness::delete(FALSE)
      ->addWhere('id', '=', $entry['id'])
      ->execute();
    }
    $cts = \Civi\Api4\Contact::get(FALSE)
      ->addSelect('contact_sub_type:name')
      ->addWhere('id', '=', $cid)
      ->execute()->first()['contact_sub_type:name'];
    unset($cts[array_search('Members_Businesses_', $ct)]);
    \Civi\Api4\Contact::update(FALSE)
      ->addValue('id', $bid)
      ->addValue('contact_sub_type', $cts)
      ->execute();
    \Civi\Api4\Activity::create(FALSE)
      ->addValue('activity_type_id:name', 'Close Business')
      ->addValue('target_contact_id', $bid)
      ->addValue('assignee_contact_id', $bid)
      ->addValue('source_contact_id', $bid)
      ->addValue('subject', 'Close Business')
      ->execute();
   CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/contact/view', 'cid=' . $bid));
  }

  public static function assignVote() {
   $oid =  CRM_Utils_Type::escape($_GET['oid'], 'Positive');
   $pid =  CRM_Utils_Type::escape($_GET['pid'], 'Positive');
   $title = CRM_Utils_Type::escape($_GET['title'], 'String');
    if (!$pid || !$oid) {
      CRM_Core_Session::setStatus('', ts('Missing essential property id and/or owner id.'), 'error');
      CRM_Utils_System::civiExit(1);
    }
    \Civi\Api4\PropertyOwner::update(FALSE)
      ->addValue('is_voter', 0)
      ->addWhere('is_voter', '=', 1)
      ->addWhere('property_id', '=', $pid)
      ->execute();
    $id = \Civi\Api4\PropertyOwner::get(FALSE)
      ->addSelect('id')
      ->addWhere('property_id', '=', $pid)
      ->addWhere('owner_id', '=', $oid)
      ->execute()->first()['id'];
    \Civi\Api4\PropertyOwner::update(FALSE)
      ->addValue('is_voter', 1)
      ->addWhere('id', '=', $id)
      ->execute();
    CRM_Utils_System::redirect(CRM_Utils_System::url('/civicrm/biaunits#?pid=' . $pid . '&title=' . $title ));
  }

  /**
   * Get the URL of a resource file (in this extension).
   *
   * @param string|NULL $file
   *   Ex: NULL.
   *   Ex: 'css/foo.css'.
   * @return string
   *   Ex: 'http://example.org/sites/default/ext/org.example.foo'.
   *   Ex: 'http://example.org/sites/default/ext/org.example.foo/css/foo.css'.
   */
  public static function url($file = NULL) {
    if ($file === NULL) {
      return rtrim(CRM_Core_Resources::singleton()->getUrl(self::LONG_NAME), '/');
    }
    return CRM_Core_Resources::singleton()->getUrl(self::LONG_NAME, $file);
  }

  /**
   * Get the path of a resource file (in this extension).
   *
   * @param string|NULL $file
   *   Ex: NULL.
   *   Ex: 'css/foo.css'.
   * @return string
   *   Ex: '/var/www/example.org/sites/default/ext/org.example.foo'.
   *   Ex: '/var/www/example.org/sites/default/ext/org.example.foo/css/foo.css'.
   */
  public static function path($file = NULL) {
    // return CRM_Core_Resources::singleton()->getPath(self::LONG_NAME, $file);
    return __DIR__ . ($file === NULL ? '' : (DIRECTORY_SEPARATOR . $file));
  }

  /**
   * Get the name of a class within this extension.
   *
   * @param string $suffix
   *   Ex: 'Page_HelloWorld' or 'Page\\HelloWorld'.
   * @return string
   *   Ex: 'CRM_Foo_Page_HelloWorld'.
   */
  public static function findClass($suffix) {
    return self::CLASS_PREFIX . '_' . str_replace('\\', '_', $suffix);
  }

}

use CRM_Biaproperty_ExtensionUtil as E;

function _biaproperty_civix_mixin_polyfill() {
  if (!class_exists('CRM_Extension_MixInfo')) {
    $polyfill = __DIR__ . '/mixin/polyfill.php';
    (require $polyfill)(E::LONG_NAME, E::SHORT_NAME, E::path());
  }
}

/**
 * (Delegated) Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config
 */
function _biaproperty_civix_civicrm_config(&$config = NULL) {
  static $configured = FALSE;
  if ($configured) {
    return;
  }
  $configured = TRUE;

  $template = CRM_Core_Smarty::singleton();

  $extRoot = __DIR__ . DIRECTORY_SEPARATOR;
  $extDir = $extRoot . 'templates';

  if (is_array($template->template_dir)) {
    array_unshift($template->template_dir, $extDir);
  }
  else {
    $template->template_dir = [$extDir, $template->template_dir];
  }

  $include_path = $extRoot . PATH_SEPARATOR . get_include_path();
  set_include_path($include_path);
  _biaproperty_civix_mixin_polyfill();
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function _biaproperty_civix_civicrm_install() {
  _biaproperty_civix_civicrm_config();
  if ($upgrader = _biaproperty_civix_upgrader()) {
    $upgrader->onInstall();
  }
  _biaproperty_civix_mixin_polyfill();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function _biaproperty_civix_civicrm_postInstall() {
  _biaproperty_civix_civicrm_config();
  if ($upgrader = _biaproperty_civix_upgrader()) {
    if (is_callable([$upgrader, 'onPostInstall'])) {
      $upgrader->onPostInstall();
    }
  }
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function _biaproperty_civix_civicrm_uninstall() {
  _biaproperty_civix_civicrm_config();
  if ($upgrader = _biaproperty_civix_upgrader()) {
    $upgrader->onUninstall();
  }
}

/**
 * (Delegated) Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function _biaproperty_civix_civicrm_enable() {
  _biaproperty_civix_civicrm_config();
  if ($upgrader = _biaproperty_civix_upgrader()) {
    if (is_callable([$upgrader, 'onEnable'])) {
      $upgrader->onEnable();
    }
  }
  _biaproperty_civix_mixin_polyfill();
}

/**
 * (Delegated) Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 * @return mixed
 */
function _biaproperty_civix_civicrm_disable() {
  _biaproperty_civix_civicrm_config();
  if ($upgrader = _biaproperty_civix_upgrader()) {
    if (is_callable([$upgrader, 'onDisable'])) {
      $upgrader->onDisable();
    }
  }
}

/**
 * (Delegated) Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *   for 'enqueue', returns void
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function _biaproperty_civix_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  if ($upgrader = _biaproperty_civix_upgrader()) {
    return $upgrader->onUpgrade($op, $queue);
  }
}

/**
 * @return CRM_Biaproperty_Upgrader
 */
function _biaproperty_civix_upgrader() {
  if (!file_exists(__DIR__ . '/CRM/Biaproperty/Upgrader.php')) {
    return NULL;
  }
  else {
    return CRM_Biaproperty_Upgrader_Base::instance();
  }
}

/**
 * Inserts a navigation menu item at a given place in the hierarchy.
 *
 * @param array $menu - menu hierarchy
 * @param string $path - path to parent of this item, e.g. 'my_extension/submenu'
 *    'Mailing', or 'Administer/System Settings'
 * @param array $item - the item to insert (parent/child attributes will be
 *    filled for you)
 *
 * @return bool
 */
function _biaproperty_civix_insert_navigation_menu(&$menu, $path, $item) {
  // If we are done going down the path, insert menu
  if (empty($path)) {
    $menu[] = [
      'attributes' => array_merge([
        'label'      => CRM_Utils_Array::value('name', $item),
        'active'     => 1,
      ], $item),
    ];
    return TRUE;
  }
  else {
    // Find an recurse into the next level down
    $found = FALSE;
    $path = explode('/', $path);
    $first = array_shift($path);
    foreach ($menu as $key => &$entry) {
      if ($entry['attributes']['name'] == $first) {
        if (!isset($entry['child'])) {
          $entry['child'] = [];
        }
        $found = _biaproperty_civix_insert_navigation_menu($entry['child'], implode('/', $path), $item);
      }
    }
    return $found;
  }
}

/**
 * (Delegated) Implements hook_civicrm_navigationMenu().
 */
function _biaproperty_civix_navigationMenu(&$nodes) {
  if (!is_callable(['CRM_Core_BAO_Navigation', 'fixNavigationMenu'])) {
    _biaproperty_civix_fixNavigationMenu($nodes);
  }
}

/**
 * Given a navigation menu, generate navIDs for any items which are
 * missing them.
 */
function _biaproperty_civix_fixNavigationMenu(&$nodes) {
  $maxNavID = 1;
  array_walk_recursive($nodes, function($item, $key) use (&$maxNavID) {
    if ($key === 'navID') {
      $maxNavID = max($maxNavID, $item);
    }
  });
  _biaproperty_civix_fixNavigationMenuItems($nodes, $maxNavID, NULL);
}

function _biaproperty_civix_fixNavigationMenuItems(&$nodes, &$maxNavID, $parentID) {
  $origKeys = array_keys($nodes);
  foreach ($origKeys as $origKey) {
    if (!isset($nodes[$origKey]['attributes']['parentID']) && $parentID !== NULL) {
      $nodes[$origKey]['attributes']['parentID'] = $parentID;
    }
    // If no navID, then assign navID and fix key.
    if (!isset($nodes[$origKey]['attributes']['navID'])) {
      $newKey = ++$maxNavID;
      $nodes[$origKey]['attributes']['navID'] = $newKey;
      $nodes[$newKey] = $nodes[$origKey];
      unset($nodes[$origKey]);
      $origKey = $newKey;
    }
    if (isset($nodes[$origKey]['child']) && is_array($nodes[$origKey]['child'])) {
      _biaproperty_civix_fixNavigationMenuItems($nodes[$origKey]['child'], $maxNavID, $nodes[$origKey]['attributes']['navID']);
    }
  }
}

/**
 * (Delegated) Implements hook_civicrm_entityTypes().
 *
 * Find any *.entityType.php files, merge their content, and return.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function _biaproperty_civix_civicrm_entityTypes(&$entityTypes) {
  $entityTypes = array_merge($entityTypes, [
    'CRM_Biaproperty_DAO_Property' => [
      'name' => 'Property',
      'class' => 'CRM_Biaproperty_DAO_Property',
      'table' => 'civicrm_property',
    ],
    'CRM_Biaproperty_DAO_PropertyOwner' => [
      'name' => 'PropertyOwner',
      'class' => 'CRM_Biaproperty_DAO_PropertyOwner',
      'table' => 'civicrm_property_owner',
    ],
    'CRM_Biaproperty_DAO_PropertyUnit' => [
      'name' => 'PropertyUnit',
      'class' => 'CRM_Biaproperty_DAO_PropertyUnit',
      'table' => 'civicrm_property_unit',
    ],
    'CRM_Biaproperty_DAO_Unit' => [
      'name' => 'Unit',
      'class' => 'CRM_Biaproperty_DAO_Unit',
      'table' => 'civicrm_unit',
    ],
    'CRM_Biaproperty_DAO_UnitBusiness' => [
      'name' => 'UnitBusiness',
      'class' => 'CRM_Biaproperty_DAO_UnitBusiness',
      'table' => 'civicrm_unit_business',
    ],
  ]);
}
