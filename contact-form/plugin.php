<?php

/**
 * @package Contact Form
 *
 * Plugin for handling
 * simple contact form.
 */

/**
 * Check current theme supports Contact form
 */
if ( !in_array('contact-form', $variables->theme['supports']) ) {
    throw new Exception('Warning: You have not declared support for Contact Form in your theme, some features may not work as intended.');
}

/**
 * Include ContactForm class
 */
require_once __DIR__.'/ContactForm/ContactForm.php';

/**
 * Include Settings
 */
require_once __DIR__.'/config.php';

/**
 * Check ContactForm class is available
 */
if ( !class_exists('ContactForm\ContactForm') ) {
    throw new Exception('ContactForm class is required for the contact form plugin to function.');
}

/**
 * Check Gump class is available
 */
if ( !class_exists('GUMP') ) {
    throw new Exception('GUMP is required for the contact form plugin to function. Hint: <code>composer require wixel/gump</code>');
}

/**
 * Run Form Submission
 */
if ( is_path( FORM_HANDLER ) && is_form_data() ) {

    /**
     * New GUMP instance
     */
    $gump = new GUMP();

    /**
     * Sanitize Input
     */
    $fields = $gump->sanitize( form_data() );

    /**
     * Filter Input
     */
    $fields = $gump->filter($fields, array(
        'name'      => 'trim|sanitize_string',
        'email'     => 'trim|sanitize_email',
        'phone'     => 'trim|sanitize_numbers',
        'message'   => 'trim|sanitize_string',
        'validate'  => 'trim'
    ));

    /**
     * Validate input
     */
    $valid = $gump->validate($fields, array(
        'name'      => 'required|alpha_numeric',
        'email'     => 'required|valid_email',
        'phone'     => 'required|numeric',
        'message'   => 'required',
        'validate'  => 'required|numeric|exact_len,1'
    ));

    if ( $valid === true ) {

        # Include Spam Filter
        require_once __DIR__.'/inc/spam-filter.php';

        # New ContactForm instance
        $contact = new ContactForm\ContactForm();

        # Check for spam then submit
        if ( !is_spam($fields['message']) ) {
            $contact->submit($fields);
        } else {
            $contact->setResponse('error', 'Your message has been marked as spam and not sent. If you believe this is a mistake, please call us.');
        }

        # Get Response
        $response = $contact->getResponse();
        
        # Apply Filters
        $response = apply_filters( 'contact_response', $response );
        
        # Pass to theme
        $variables->add('contact_response', $response );

    } else {
        $variables->add('contact_response', $gump->get_readable_errors(true));
    }

}
