; $Id: README.txt,v 1.1.4.5 2010/08/05 05:07:18 jamesmarks Exp $

README.txt file for ProfilePlus module.

CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Description
 * Installation
 * Configuration
 * Limitations


INTRODUCTION
------------

Original author: Mike Carter <mike@ixis.co.uk>
Previous author and maintainer: Dave Myburgh <dave@mybesinformatik.com>
Current author and maintainer: James Marks <james.a.marks@mac.com>

ProfilePlus is being actively developed for Drupal 6 and maintained for Drupal
5. It is no longer being maintained for Drupal 4.7. Development for Drupal 7 is
planned but not yet scheduled.

The ProfilePlus module allows you to perform regular or advanced searches of
user profiles using Drupal's built-in search capabilities without the need for
any other modules besides Profile and Search.

Regular profile search returns any active user profiles that contain any of the
profile search keywords in the username field (OR email field for searches by
administrators) OR any of the public profile fields.

Advanced profile search returns only those active user profiles that contain any
of the profile search keywords in the username field (OR the email field for
searches by administrators) AND any of the advanced profile search keywords in
each of their respective profile fields.


INSTALLATION
------------

Install as usual, see http://drupal.org/node/70151 for further information.

Optional but recommended: Add the following override code to your theme's
template.php file to remove the 'Users' tab from the search form. ProfilePlus
duplicates and extends the functionality of the 'Users' tab rendering it
unnecessary and redundant:

// -- BEGIN OVERRIDE CODE -----------------------------------------------------
/**
 * Override or insert PHPTemplate variables into the templates.
 */
function phptemplate_preprocess_page(&$vars) {
  // ProfilePlus module: remove the Users tab from the search page
  if (module_exists('profileplus')) {
    _removetab('Users', $vars);
  }
}

/**
 * Removes a tab from the $tabs array.
 * ProfilePlus uses this function to remove the 'Users' tab 
 * from the search page.
 */
function _removetab($label, &$vars) {
  $tabs = explode("\n", $vars['tabs']);
  $vars['tabs'] = '';

  foreach($tabs as $tab) {
    if(strpos($tab, '>' . $label . '<') === FALSE) {
      $vars['tabs'] .= $tab . "\n";
    }
  }
}
// -- END OVERRIDE CODE -------------------------------------------------------


CONFIGURATION
-------------

Options:
- Display advanced profile search fieldset on profile search form
- Leave advanced profile search fieldset uncollapsed if fields contain values
- Select public profile fields to include in advanced profile search fieldset


LIMITATIONS
-----------

ProfilePlus does not currently support OR, AND, or NOT modifiers on search terms
in keyword or profile fields.