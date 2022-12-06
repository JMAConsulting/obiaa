<?php
use CRM_Obiaatemplate_ExtensionUtil as E;

/**
 * Job.Updatebiatemplate API
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @see civicrm_api3_create_success
 *
 * @throws API_Exception
 */
function civicrm_api3_job_Updatebiatemplate($params) {

  // Fetch the Mosaico template from the database.
  $mosaicoTemplate = civicrm_api3('MosaicoTemplate', 'get', [
    'sequential' => 1,
    'base' => "obiaa-newsletter",
    'title' => "BIA Newsletter",
  ])['count'];

  if ($mosaicoTemplate) {
    // We know we have the template, so fetch the site logo
    $logo = get_theme_mod( 'custom_logo' );
    $image = wp_get_attachment_image_src( $logo , 'full' );
    $image_url = $image[0];
    
    $config = CRM_Core_Config::singleton();
    $destLogo = $config->imageUploadDir . '/images/uploads/bia_logo.png';
    $footerLogo = $config->imageUploadDir . '/images/uploads/obiaa_footer.png';
    // Move the site's logo to the appropriate directory
    if (!empty($image_url)) {
      file_put_contents($destLogo, file_get_contents($image_url));
      file_put_contents($footerLogo, file_get_contents($image_url));
    }

    // Now that we have our images in the right place, we replace URLs in the template
    $returnValues = civicrm_api3('MosaicoTemplate', 'replaceurls', [
      'from_url' => "http://wpmaster.localhost/",
      'to_url' => \Civi::paths()->getVariable('cms.root', 'url'),
    ])['values'];
    CRM_Core_DAO::executeQuery("UPDATE civicrm_mosaico_template SET content = replace(content, 'http://wpmaster.localhost/', %1) WHERE title = 'BIA Newsletter'", [
      1 => [\Civi::paths()->getVariable('cms.root', 'url') . '/sites/' . str_replace('https://', '', \Civi::paths()->getVariable('cms.root', 'url')) . '/', 'String'],
    ]);
  }

  return civicrm_api3_create_success($returnValues, $params, 'Job', 'Updatebiatemplate');
}
