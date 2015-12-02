<?php

#################################################
### @NOTE Instead of changing values in this  ###
### file, you should use the 'sf_config'      ###
### filter to change values.                  ###
#################################################

/**
 * Admin User ID
 *
 * there is no need for a complex
 * permissions system here, so we
 * simply do a check to see if
 * the current user can make certain
 * actions based on whether they
 * the main user or not.
 */
$sf_config['admin_user_id'] = 1;

/**
 * User Login Page
 *
 * CMS uses the same session
 * as the rest of your site, so
 * if you want users to login
 * using your own login page,
 * simply change the path using
 * the sf_config filter.
 *
 * Bare in mind though, there is
 * certain data CMS needs from the
 * session...
 *
 * id = User ID
 * email = Email address
 * isactive = User status
 */
$sf_config['login_path'] = 'admin/login';
$sf_config['login_handler'] = 'admin/post/login';
$sf_config['logout_path'] = 'admin/logout';

/**
 * User Registration Page
 *
 * As above, this can be overridden
 * with your own, just bare in mind
 * that the data required must be
 * present.
 */
$sf_config['register_path'] = 'admin/register';
$sf_config['register_handler'] = 'admin/post/register';

/**
 * Media Uploads Folder
 *
 * This is where any uploaded media
 * files are stored.
 *
 * @NOTE: Make sure the permissions
 * are set to at least 775!
 */
$sf_config['media_dir'] = 'assets/cms/media';

#################################################
### No need to edit below this line           ###
#################################################

# Apply Filters
$sf_config = apply_filters( 'sf_config', $sf_config );

# Define Media Uploads Folder as constant
define('CMS_MEDIA_DIR', $sf_config['media_dir']);
