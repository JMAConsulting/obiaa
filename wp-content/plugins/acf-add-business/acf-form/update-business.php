<?php

acfe_register_form(array(
    'name' => 'update-business',
    'title' => 'Update Business',
    'active' => true,
    'field_groups' => array(
        'group_689381532632e',
    ),
    'settings' => array(
        'location' => false,
        'honeypot' => true,
        'kses' => true,
        'uploader' => 'default',
    ),
    'attributes' => array(
        'form' => array(
            'element' => 'form',
            'class' => '',
            'id' => '',
        ),
        'fields' => array(
            'element' => 'div',
            'wrapper_class' => '',
            'class' => '',
            'label' => 'top',
            'instruction' => 'label',
        ),
        'submit' => array(
            'value' => 'Submit',
            'button' => '<input type="submit" class="acf-button button button-primary button-large" value="%s" />',
            'spinner' => '<span class="acf-spinner"></span>',
        ),
    ),
    'validation' => array(
        'hide_error' => false,
        'hide_revalidation' => false,
        'hide_unload' => false,
        'errors_position' => 'above',
        'errors_class' => '',
        'messages' => array(
            'failure' => 'Validation failed',
            'success' => 'Validation successful',
            'error' => '1 field requires attention',
            'errors' => '%d fields require attention',
        ),
    ),
    'success' => array(
        'hide_form' => true,
        'scroll' => true,
        'message' => 'Form updated',
        'wrapper' => '<div id="message" class="updated">%s</div>',
    ),
    'actions' => array(
        array(
            'action' => 'custom',
            'name' => 'update-contact-detail',
        ),
    ),
    'render' => '',
));

error_log('acf-add-business: registered form update-business');
