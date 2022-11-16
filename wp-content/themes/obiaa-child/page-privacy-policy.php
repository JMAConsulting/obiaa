<?php

civicrm_initialize();

$biaName = Civi\Api4\Contact::get(FALSE)
  ->addSelect('display_name')
  ->addWhere('id', '=', 1)
  ->execute()->first();

$group = \Civi\Api4\Group::get(FALSE)
  ->addWhere('name', '=', 'Privacy Officers')
  ->execute()->first()['id'];

$privacyOfficer = \Civi\Api4\Contact::get(FALSE)
  ->addSelect('first_name', 'last_name', 'email.email', 'phone.phone', 'job_title', 'address.*')
  ->addJoin('Email AS email', 'LEFT')
  ->addJoin('Phone AS phone', 'LEFT')
  ->addJoin('Address AS address', 'LEFT')
  ->addJoin('GroupContact AS group_contact', 'LEFT')
  ->addWhere('group_contact.group_id', '=', $group)
  ->addWhere('group_contact.status', '=', 'Added')
  ->execute()->first();

  get_header();

  $biaName = $biaName['display_name'];


?>

<section class="container">
    <div class="main-content">
        <h1 class="main-title"><?php the_title(); ?></h1>
        <br><br>
        <div class="obiaa-content">
            <h2>Introduction</h2>
        

        At <?php echo esc_html($biaName); ?>, we understand the importance of the security of confidential information of all types including the protection of the privacy of personal information. This document sets out our policy regarding the security of confidential information and the collection, use and disclosure of personal information.
        <br><br>
        As a Local Board of the local Municipality under the Municipal Act or the Toronto Act (as applicable), <?php echo esc_html($biaName); ?> may have obligations under the Municipal Freedom of Information and Protection of Privacy Act., including in relation to the collection, use and disclosure of personal information.
        <br><br>
        Purposes of Collection, Use, Retention and Disclosure of Personal Information
        <br><br>
        <?php echo esc_html($biaName); ?> collects, uses, retains and discloses personal information of individuals who interact with the <?php echo esc_html($biaName); ?> (“Individuals”) as necessary to operate <?php echo esc_html($biaName); ?>, and to provide services and programs to <?php echo esc_html($biaName); ?>‘s stakeholders.
        <br><br>
        <h3>Privacy Officer</h3>
        
        <?php echo esc_html($biaName); ?> has designated a Privacy Officer to whom inquiries regarding personal information should be directed. <?php echo esc_html($biaName); ?>‘s Privacy Officer (please see below).
        <br><br>
        <h3>Disclosure of Personal Information</h3>

        <?php echo esc_html($biaName); ?> will disclose an Individual’s personal information only if consent is given by the relevant Individual or:
            <br><br>
        1.	for purposes for which the information was obtained or for a consistent purpose (including without limitation to businesses that assist <?php echo esc_html($biaName); ?> in operating <?php echo esc_html($biaName); ?> and in providing services to <?php echo esc_html($biaName); ?>’s stakeholders including Individuals); and
        <br>
        2.	when permitted or required by law.
        <br><br>
        <h3>Protecting Confidential Information, including Personal Information </h3>

        <?php echo esc_html($biaName); ?> protects confidential information of all types, including personal information, with organizational, physical, mechanical and electronic safeguards appropriate to the sensitivity of the information.
        <br><br>
        <h3>Requests</h3>

        An Individual may submit a written request to access their personal information retained by <?php echo esc_html($biaName); ?>.
        <br><br>
        All correspondence should be sent to the following address:
        <br>
		<?php echo esc_html($privacyOfficer['first_name']);?> <?php echo esc_html($privacyOfficer['last_name']);?>, <?php echo esc_html($privacyOfficer['job_title']);?> <?php echo esc_html($biaName); ?>
        <br>
        Privacy Officer
		<br>
        <?php echo esc_html($privacyOfficer['address.street_address']);?>,
		<br>
		<?php echo esc_html($privacyOfficer['address.city']);?>,
		<br>
		<?php echo esc_html($privacyOfficer['address.postal_code_suffix']);?> 
		<?php echo esc_html($privacyOfficer['address.postal_code']);?>
        <br>
        Email: <?php echo esc_html($privacyOfficer['email.email']);?>

        </div>
      
    <?php the_content(); ?>
    </div>
</section>


<?php get_footer(); ?>
