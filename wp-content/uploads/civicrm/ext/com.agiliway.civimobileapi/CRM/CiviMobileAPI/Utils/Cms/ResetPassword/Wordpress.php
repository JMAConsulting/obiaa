<?php

class CRM_CiviMobileAPI_Utils_Cms_ResetPassword_Wordpress {
  
  /**
   * @return bool
   */
  public static function resetPassword($email) {
    if (function_exists('retrieve_password')) {
      $result = retrieve_password($email);
    } else {
      $result = self::retrievePassword($email);
    }
    
    if (!empty($result->errors)) {
      return FALSE;
    }
    
    return $result;
  }
  
  public static function retrievePassword($email) {
    $errors    = new WP_Error();
    $user_data = FALSE;
    
    if ( empty( $email ) || ! is_string( $email ) ) {
      $errors->add( 'empty_username', __( '<strong>ERROR</strong>: Enter a username or email address.' ) );
    } elseif ( strpos( $email, '@' ) ) {
      $user_data = get_user_by( 'email', trim( wp_unslash( $email ) ) );
      if ( empty( $user_data ) ) {
        $errors->add( 'invalid_email', __( '<strong>ERROR</strong>: There is no account with that username or email address.' ) );
      }
    } else {
      $login     = trim( wp_unslash( $email ) );
      $user_data = get_user_by( 'login', $login );
    }
    
    do_action( 'lostpassword_post', $errors, $user_data );
    
    if ( $errors->has_errors() ) {
      return $errors;
    }
    
    if ( ! $user_data ) {
      $errors->add( 'invalidcombo', __( '<strong>ERROR</strong>: There is no account with that username or email address.' ) );
      return $errors;
    }

    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;
    $key        = get_password_reset_key( $user_data );
    
    if ( is_wp_error( $key ) ) {
      return $key;
    }
    
    if ( is_multisite() ) {
      $site_name = get_network()->site_name;
    } else {
      $site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
    }
    
    $message = __( 'Someone has requested a password reset for the following account:' ) . "\r\n\r\n";
    $message .= sprintf( __( 'Site Name: %s' ), $site_name ) . "\r\n\r\n";
    $message .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n\r\n";
    $message .= __( 'If this was a mistake, just ignore this email and nothing will happen.' ) . "\r\n\r\n";
    $message .= __( 'To reset your password, visit the following address:' ) . "\r\n\r\n";
    $message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n";
    
    $title = sprintf( __( '[%s] Password Reset' ), $site_name );
    $title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );
    $message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );
    
    if ( $message && ! wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {
      $errors->add(
        'retrieve_password_email_failure',
        sprintf(
          __( '<strong>ERROR</strong>: The email could not be sent. Your site may not be correctly configured to send emails. <a href="%s">Get support for resetting your password</a>.' ),
          esc_url( __( 'https://wordpress.org/support/article/resetting-your-password/' ) )
        )
      );
      return $errors;
    }
    
    return TRUE;
  }
}