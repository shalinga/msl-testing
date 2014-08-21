<?php
/*
Plugin Name: Language Switcher
Plugin URI: http://www.poplarware.com/languageplugin.html
Description: Lets you set up a bilingual or multi-lingual blog, where you write all blog content, categories, etc. in multiple languages, and your readers choose which language to view. Plugin home page has lots of information on how to set it up. Based on <a href="http://fredfred.net/skriker/index.php/polyglot">Polyglot</a>, by Martin Chlupac.
Version: 1.21
Author: Jennifer Hodgdon, Poplar ProductivityWare
Author URI: http://poplarware.com
Text Domain: langswitch

See the Language Switcher home page for more information on how to set
up a bilingual or multi-lingual blog.

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.
*/


// --- A few global variables  ---

/* Define text domain for localization */
$langSwitchTextDomain = "langswitch";

// Name of the cookie
$langSwitchCookie = 'wordpress_langswitch_lang' . $cookiehash;

if( !defined('WP_CONTENT_URL')) {
  define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
}

// path to the directory with the flags in it
$langSwitchFlagsPath = WP_CONTENT_URL . '/plugins/langswitch_flags/';

// names of the options
$langSwitchDefLangOpt = "langswitch_default_lang";
$langSwitchNumLangsOpt = "langswitch_num_langs";
$langSwitchLangInfoOptPrefix = "langswitch_lang_info";
$langSwitchLangInfoNumOpts = 6;
$langSwitchLangInfoOptSuffixes = array( "code", "language", "flag", 
                                        "time_format", "date_format", 
                                        "missing_msg" );
$langSwitchRewriteVer = 3;
$langSwitchRewriteOpt = "langswitch_rewrite_version";
$langSwitchRewriteWPOpt = "langswitch_rewrite_wp_version";
$langSwitchNeverPermOpt = "langswitch_never_perm";
$langSwitchForceSuffixOpt = "langswitch_force_suffix";
$langSwitchGetVarOpt = "langswitch_get_var";
$langSwitchGetVar = get_option( $langSwitchGetVarOpt );
if( !$langSwitchGetVar ) {
  $langSwitchGetVar = 'langswitch_lang';
}

// A few function calls to do at load time

//  ---  Figure out what language to use and set it up
langswitch_find_pref_lang();
langswitch_setup_pref_lang();

// set up options and the admin menu
langswitch_default_options();
add_action('admin_menu', 'langswitch_admin_menu');
add_action('sidemenu', 'langswitch_admin_list_langs' );
add_filter('favorite_actions', 'langswitch_admin_list_lang_favorites' );
add_action( 'after_plugin_row', 'langswitch_plugin_row', 10, 2 );

//  ---  Filters and actions that use langswitch functions to remake various text

// time and date filters
add_filter( 'the_time', 'langswitch_the_time', 10, 2 );
add_filter( 'get_comment_time','langswitch_comment_time', 10, 3 );
add_filter( 'the_date', 'langswitch_the_date', 10, 2 );
add_filter( 'get_comment_date', 'langswitch_comment_date', 10 );

// overall blog title filter
add_filter( 'bloginfo', 'langswitch_filter_langs', 1 );
add_filter( 'bloginfo_rss', 'langswitch_filter_langs', 1 );
add_filter( 'get_bloginfo_rss', 'langswitch_filter_langs', 1 );

// language for headers
add_filter( 'pre_option_rss_language', 'langswitch_current_lang', 1 );

// post content and title filters
add_filter( 'the_content', 'langswitch_filter_langs_with_message', 1);
add_filter( 'the_content_rss', 'langswitch_filter_langs', 1);
add_filter( 'the_excerpt', 'langswitch_filter_langs_with_message', 1);
add_filter( 'the_excerpt_rss', 'langswitch_filter_langs', 1);
add_filter( 'wp_title', 'langswitch_filter_langs', 1);
add_filter( 'the_title', 'langswitch_filter_langs_with_message', 1);
add_filter( 'the_title_rss', 'langswitch_filter_langs_with_message', 1);
add_filter( 'single_post_title', 'langswitch_filter_langs_with_message', 1);
add_filter( 'wp_list_pages', 'langswitch_filter_langs_with_entities', 1);
add_filter( 'wp_dropdown_pages', 'langswitch_filter_langs_with_entities', 1);
add_filter( 'post_title', 'langswitch_filter_langs_with_message', 1);

// meta and slug filters
add_filter( 'the_meta_key', 'langswitch_filter_langs', 1 );
add_filter( 'sanitize_title', 'langswitch_sanitize_slug', 1 );

// comment filters
add_filter( 'comment_text', 'langswitch_filter_langs', 1 );
add_filter( 'comment_text_rss', 'langswitch_filter_langs', 1 );

// category filters
add_filter( 'the_category', 'langswitch_filter_langs_with_entities', 1 );
add_filter( 'the_category', 'langswitch_gettext_the_category', 2 );
add_filter( 'the_category_rss', 'langswitch_filter_langs_with_entities', 1 );
add_filter( 'the_category_rss', 'langswitch_gettext_the_category', 2 );
add_filter( 'single_cat_title', 'langswitch_filter_langs_with_entities', 1 );
add_filter( 'single_cat_title', 'langswitch_gettext_the_category', 2 );
add_filter( 'list_cats', 'langswitch_filter_langs_with_entities', 1 );
add_filter( 'list_cats', 'langswitch_gettext_the_category', 2 );
add_filter( 'category_description', 'langswitch_filter_langs_with_entities', 1 );
add_filter( 'cat_rows', 'langswitch_filter_langs_with_entities', 1 );
add_filter( 'tag_rows', 'langswitch_filter_langs_with_entities', 1 );
add_filter( 'term_name', 'langswitch_filter_langs_with_entities', 1 );
add_filter( 'term_name', 'langswitch_gettext_the_category', 2 );
add_filter( 'category_name', 'langswitch_filter_langs_with_entities', 1 );
add_filter( 'category_name', 'langswitch_gettext_the_category', 2 );

add_filter( 'get_terms_fields', 'langswitch_get_terms_fields', 1, 2 );
add_filter( 'get_terms_orderby', 'langswitch_get_terms_orderby', 1, 2 );
add_filter( 'tag_cloud_sort', 'langswitch_tag_cloud_sort', 1, 2 );

// tag filters
add_filter( 'the_tags', 'langswitch_filter_langs_with_entities', 1 );
add_filter( 'wp_generate_tag_cloud', 'langswitch_filter_langs_with_entities', 1 );

// link array filters
add_filter( 'term_links-post_tag', 'langswitch_filter_langs_array', 1 );

// blogroll filters 
add_filter( 'link_category', 'langswitch_filter_langs_with_entities', 1 );
add_filter( 'link_category', 'langswitch_gettext_the_category', 2 );
add_filter( 'link_title', 'langswitch_filter_langs_with_entities', 1 );
add_filter( 'link_description', 'langswitch_filter_langs_with_entities', 1 );
add_filter( 'link_name', 'langswitch_filter_langs_with_entities', 1 );
add_filter( 'link_category_name', 'langswitch_filter_langs_with_entities', 1 );
add_filter( 'link_category_name', 'langswitch_gettext_the_category', 2 );

// Add language to links for articles, RSS, categories, and archives
// in case user does not have cookies enabled
add_filter( 'the_permalink', 'langswitch_add_language_url', 999 );
add_filter( 'post_link', 'langswitch_add_language_url', 999 );
add_filter( 'page_link', 'langswitch_add_language_url', 999 );
add_filter( 'day_link', 'langswitch_add_language_url', 999 );
add_filter( 'month_link', 'langswitch_add_language_url', 999 );
add_filter( 'year_link', 'langswitch_add_language_url', 999 );
add_filter( 'category_link', 'langswitch_add_language_url', 999 );
add_filter( 'tag_link', 'langswitch_add_language_url', 999 );
add_filter( 'trackback_url', 'langswitch_add_language_url', 999 );
add_filter( 'comment_post_redirect', 'langswitch_add_language_url', 999 );
add_filter( 'feed_link', 'langswitch_add_language_url_force', 999 );
add_filter( 'post_comments_feed_link', 'langswitch_add_language_url_force', 999 );

// permalinks and rewrite parsing
add_action( 'init', 'langswitch_init' );
add_filter( 'query_vars', 'langswitch_add_queryvar' );
add_filter( 'request', 'langswitch_remove_queryvar' );
add_action( 'generate_rewrite_rules', 'langswitch_add_rewrite_rules' );

// notification email text filters
add_filter( 'comment_notification_subject', 'langswitch_filter_langs_with_entities', 1 );
add_filter( 'comment_notification_text', 'langswitch_filter_langs_with_entities', 1 );
add_filter( 'comment_moderation_subject', 'langswitch_filter_langs_with_entities', 1 );
add_filter( 'comment_moderation_text', 'langswitch_filter_langs_with_entities', 1 );

// widget filters
add_filter( 'widget_text', 'langswitch_filter_langs_with_entities', 1 );
add_filter( 'widget_title', 'langswitch_filter_langs_with_entities', 1 );

//  --- Functions

// Forces rewrite rules to be re-created, loads translation domain,
// and registers sidebar widget
function langswitch_init() {
   global $langSwitchTextDomain;
   global $langSwitchRewriteVer, $langSwitchRewriteOpt, $langSwitchRewriteWPOpt, $langSwitchFlagsAbsPath;
   global $wp_rewrite;
   global $wp_version;

   load_plugin_textdomain( $langSwitchTextDomain, 'wp-content/plugins/langswitch_flags', 'langswitch_flags' );

   // make sure rewrite rules are flushed if this is a new version of
   // Language Switcher's rules, or a new version of WP since the last
   // time we forced a flush
   if( ( $langSwitchRewriteVer != get_option( $langSwitchRewriteOpt )) ||
       ( $wp_version != get_option( $langSwitchRewriteWPOpt ))) {
     $wp_rewrite->flush_rules();
     update_option( $langSwitchRewriteOpt, $langSwitchRewriteVer );
     update_option( $langSwitchRewriteWPOpt, $wp_version );
   }

   if( function_exists( 'wp_register_sidebar_widget' )) {
     wp_register_sidebar_widget( 'langswitch-lang-list', 
                                 __( 'Language List', $langSwitchTextDomain ),
                                 'langswitch_widget_listlangs' );
   }

   if( function_exists( 'wp_register_widget_control' )) {
     wp_register_widget_control( 'langswitch-lang-list', 
                                 __( 'Language List', $langSwitchTextDomain ),
                                 'langswitch_widget_listlangs_control' );
   }
}

/* Adds a query var for the language */
function langswitch_add_queryvar( $vars )
{
  global $langSwitchGetVar;

  $vars[] = $langSwitchGetVar;
  return $vars;
}

/* Removes any language tags from query after parsing */
function langswitch_remove_queryvar( $vars )
{
  global $langSwitchGetVar;

  unset( $vars[ $langSwitchGetVar ] );
  return $vars;
}

// adds rewrite rules for language switcher
function langswitch_add_rewrite_rules( $wp_rewrite ) 
{
  global $langSwitchGetVar;

  $langmatch = $langSwitchGetVar . '/(..)/';
  // rule for home page with language switch suffix

  $new_rules = array(
          $langmatch . '?$' => 
              'index.php?' . $langSwitchGetVar . '=' . 
               $wp_rewrite->preg_index(1) 
          );

  // Add rules for feeds and pages

  // Basically, any time there is a feed rule (2 forms possible) 
  // or a page rule, we'll add language tags before and after
  // the feed/page stuff, 
  // because the language portion could be in either position.
  // And for other rules, we'll add langswitch as an endpoint
  // Don't want to use the generic endpoint rules, because they are
  // too permissive about matching in some cases

  foreach( $wp_rewrite->rules as $rule => $def ) {

    // see if this is a feed or page, and add rules
    
    if( ($pos = strpos( $rule, 'feed/(feed|rdf' )) !== false ) {
      // feeds, version 1
      $new_rules[ substr( $rule, 0, $pos ) . $langmatch . 
                  substr( $rule, $pos ) ] = 
        langswitch_rewrite_def( $rule, $def, $pos );
      $new_rules[ substr( $rule, 0, strlen( $rule ) - 2 ) . $langmatch . 
                  substr( $rule, -2 ) ] = 
        langswitch_rewrite_def( $rule, $def, strlen( $rule ));
    } else if( ($pos = strpos( $rule, '(feed|rdf' )) !== false ) {
      // feeds, version 2
      $new_rules[ substr( $rule, 0, $pos ) . $langmatch . 
                  substr( $rule, $pos ) ] = 
        langswitch_rewrite_def( $rule, $def, $pos );
      $new_rules[ substr( $rule, 0, strlen( $rule ) - 2 ) . $langmatch . 
                  substr( $rule, -2 ) ] = 
        langswitch_rewrite_def( $rule, $def, strlen( $rule ));
    } else if( ($pos = strpos( $rule, 'page/?([0-9]{1,})/' )) !== false ) {
      // subsequent pages in general
      $new_rules[ substr( $rule, 0, $pos ) . $langmatch . 
                  substr( $rule, $pos ) ] = 
        langswitch_rewrite_def( $rule, $def, $pos );
      $new_rules[ substr( $rule, 0, strlen( $rule ) - 2 ) . $langmatch . 
                  substr( $rule, -2 ) ] = 
        langswitch_rewrite_def( $rule, $def, strlen( $rule ));
    } else if( ($pos = strpos( $rule, '(/[0-9]+)' )) !== false ) {
      // special rewrite rules for post permalink subsequent pages
      $new_rules[ substr( $rule, 0, $pos ) . "/" . 
                  $langSwitchGetVar . '/(..)' .
                  substr( $rule, $pos ) ] = 
        langswitch_rewrite_def( $rule, $def, $pos );
      $new_rules[ substr( $rule, 0, strlen( $rule ) - 2 ) . 
                  $langmatch . substr( $rule, -2 ) ] = 
        langswitch_rewrite_def( $rule, $def, strlen( $rule ));
    } else if( substr( $rule, -3 ) == "/?$" ) {
      // generic rule ending in /?$, just add at end
      $new_rules[ substr( $rule, 0, strlen( $rule ) - 2 ) . $langmatch . 
                  substr( $rule, -2 ) ] = 
        langswitch_rewrite_def( $rule, $def, strlen( $rule ));
    }

    // add the old rule now, to preserve matching order

    $new_rules[ $rule ] = $def;
  }

  if( 0 ) { // debug printout of rules 

    echo "Old:\n";
    foreach( $wp_rewrite->rules as $rule => $def ) {
      echo $rule . " : " . $def . "\n";
    }

    echo "New:\n";
    foreach( $new_rules as $rule => $def ) {
      echo $rule . " : " . $def . "\n";
    }
  }

  $wp_rewrite->rules = $new_rules;
}

// utility function: returns the correct rewrite definition,
// assuming that the langswitch tags are inserted into rule
// at position pos
function langswitch_rewrite_def( $rule, $def, $pos )
{
  global $langSwitchGetVar;
  global $wp_rewrite;

  // How many match tokens are before our insertion?

  $dum = array();
  $num_toks_before = preg_match_all( '|\(|', substr( $rule, 0, $pos), $dum );

  // Build up the new def string -- fixing all $matches[##] entries after where
  // we are inserting
  
  $newdef = '';
  $remain = $def;
  $tok = 0;

  while( ($pos = strpos( $remain, '$matches[' )) !== false ) {
    preg_match( '|\d+|', substr( $remain, $pos ), $dum );
    $num = (int) $dum[0];
    if( $num > $num_toks_before ) {
      $num++;
    }
    $newdef .= substr( $remain, 0, $pos ) . '$matches[' . $num . ']';
    $remain = substr( $remain, $pos + 10 + strlen( $dum[0] ));
  }

  // add remains and the language info

  $newdef .= $remain . '&' . $langSwitchGetVar . 
    '=' . $wp_rewrite->preg_index( $num_toks_before + 1 );

  return $newdef;
}

/* Sets up default options for this plugin */
function langswitch_default_options()
{
  global $langSwitchDefLangOpt, $langSwitchNumLangsOpt,
    $langSwitchLangInfoOptPrefix, $langSwitchLangInfoNumOpts,
    $langSwitchLangInfoOptSuffixes;
  
  $num_langs = get_option( $langSwitchNumLangsOpt );
  if( !$num_langs ) {
    
      // doesn't look like they have any languages yet, so install some defaults

      add_option( $langSwitchDefLangOpt, 'en', 
                "Option for Language Switcher plugin", 'yes' );

      add_option( $langSwitchNumLangsOpt, 2, 
                "Option for Language Switcher plugin", 'yes' );

      $lang_array = array( "en", "english", "us.png", "g:i A",
         "j F Y", "Sorry, but this post is not available in English" );

      for( $i = 0; $i < $langSwitchLangInfoNumOpts; $i++ ) {
         add_option( $langSwitchLangInfoOptPrefix . "0" . 
                     $langSwitchLangInfoOptSuffixes[ $i ],
                     $lang_array[ $i ], 
                "Option for Language Switcher plugin", 'yes' );
      }

      $lang_array = array( "es", "espa&ntilde;ol", "es.png", "G:i",
         "j F Y", "Lo siento, este art&iacute;culo no est&aacute; disponible en espa&ntilde;ol" );

      for( $i = 0; $i < $langSwitchLangInfoNumOpts; $i++ ) {
         add_option( $langSwitchLangInfoOptPrefix . "1" . 
                     $langSwitchLangInfoOptSuffixes[ $i ],
                     $lang_array[ $i ], 
                "Option for Language Switcher plugin", 'yes' );
      }

   }
}

// Add options to the options section of the menus
function langswitch_admin_menu()
{
    global $langSwitchTextDomain;

    add_options_page( __('Language Switcher Options', $langSwitchTextDomain), 
                      __('Language Switcher', $langSwitchTextDomain), 6, 
                      basename(__FILE__), 
                      'langswitch_options_page' );
}

// Generate the HTML page for the options settings,
// and respond when the user updates the settings
function langswitch_options_page()
{       
    global $langSwitchTextDomain;
    global $langSwitchDefLangOpt, $langSwitchNumLangsOpt,
      $langSwitchLangInfoOptPrefix, $langSwitchLangInfoNumOpts,
      $langSwitchLangInfoOptSuffixes;
    global $langSwitchNeverPermOpt, $langSwitchGetVarOpt, $langSwitchGetVar;
    global $langSwitchForceSuffixOpt;
    global $wp_rewrite;

    $langSwitchLangInfoOptDisplay = array( __("ISO Code", $langSwitchTextDomain), 
           __("Language", $langSwitchTextDomain), __("Flag File", $langSwitchTextDomain), 
               __("Time Format", $langSwitchTextDomain), __("Date Format", $langSwitchTextDomain), 
               __("Text Missing Message", $langSwitchTextDomain), __("Delete", $langSwitchTextDomain) );
    $langSwitchLangInfoOptWidths = array( 5, 12, 10, 10, 10, 40 );

    
    // Read in previously saved options

    $default_lang = get_option( $langSwitchDefLangOpt );
    $num_langs = get_option( $langSwitchNumLangsOpt );
    $lang_info = langswitch_get_lang_info();
    $langSwitchNeverPerm = get_option( $langSwitchNeverPermOpt );
    $langSwitchForceSuffix = get_option( $langSwitchForceSuffixOpt );

    /* See if the user has posted us some information */ 

    if( $_POST['langswitch_hidden'] == 'Y' ) {

      // Overwrite above info with posted info

      $default_lang = $_POST[$langSwitchDefLangOpt];

      if( $_POST[$langSwitchNeverPermOpt ] == 'Y' ) {
        $langSwitchNeverPerm = 1;
      } else {
        $langSwitchNeverPerm = 0;
      }

      if( $_POST[$langSwitchForceSuffixOpt ] == 'Y' ) {
        $langSwitchForceSuffix = 1;
      } else {
        $langSwitchForceSuffix = 0;
      }

      $langSwitchGetVar = $_POST[$langSwitchGetVarOpt];

      $num_langs = 0;
      $lang_info = array();
      $i = 0;

      while( $_POST[ 'langswitch_info_' . $i . '_0' ]) {
        if( !$_POST[ 'langswitch_delete' . $i ]) {
          // user didn't check DELETE, so read info
          $arr = array();
          for( $j = 0; $j < $langSwitchLangInfoNumOpts; $j++ ) {
            $arr[ $j ] = stripslashes( $_POST[ 'langswitch_info_' . $i . '_' . $j ]);
          }
          // save in array
          $lang_info[ $num_langs ] = $arr;
          $num_langs++;
        }

        $i++;
      }

      /* Save the values */ 
      /* Note: Delete added here to fix autoload on old installs */
      delete_option( $langSwitchDefLangOpt );
      update_option( $langSwitchDefLangOpt, $default_lang );
      delete_option( $langSwitchNumLangsOpt );
      update_option( $langSwitchNumLangsOpt, $num_langs );
      update_option( $langSwitchNeverPermOpt, $langSwitchNeverPerm );
      update_option( $langSwitchForceSuffixOpt, $langSwitchForceSuffix );

      // if we have saved things here, we might need to flush rewrite rules
      update_option( $langSwitchGetVarOpt, $langSwitchGetVar );
      $wp_rewrite->flush_rules();

      for( $i = 0; $i < $num_langs; $i++ ) {
        for( $j = 0; $j < $langSwitchLangInfoNumOpts; $j++ ) {
          $opt = $langSwitchLangInfoOptPrefix . $i . 
            $langSwitchLangInfoOptSuffixes[ $j ];
          delete_option( $opt );
          update_option( $opt, $lang_info[ $i ][ $j ] );
        }
      }

          ?>
           <div class="updated"><p><strong><?php _e('Options saved.', $langSwitchTextDomain ); ?></strong></p></div>
           <?php
    }
    
    /* Now print out the settings form */ 
    
      ?>
      <div class="wrap">
         <h2><?php _e('Language Switcher Settings', $langSwitchTextDomain ); ?></h2>

         <p><?php _e('Visit the <a href="http://www.poplarware.com/languageplugin.html">Language Switcher Plugin</a> page for complete information on the plugin and these settings.', $langSwitchTextDomain ); ?></p>

         <p><?php _e('A list of the two-letter ISO language codes can be found on <a href="http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes">this Wikipedia site</a>.', $langSwitchTextDomain ); ?></p>

         <p><?php _e('A list of the WordPress/PHP date and time formatting codes can be found on <a href="http://us3.php.net/date">this PHP documentation page</a>.', $langSwitchTextDomain ); ?></p>

     <hr />


<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">


         <input type="hidden" name="langswitch_hidden" value="Y">

         <p><?php _e("Default language ISO code:", $langSwitchTextDomain); ?> 
         <input type="text" name="langswitch_default_lang" value="<?php echo $default_lang; ?>" size="5">
          </p><hr />

<?php
    echo "<h3>" . __( "Blog Languages", $langSwitchTextDomain ) . "</h3>\n";

    echo "<table border=0>\n";
    echo "<tr>\n";

    for( $i = 0; $i < $langSwitchLangInfoNumOpts; $i++ ) {
      echo '<th align="left">' . __( $langSwitchLangInfoOptDisplay[$i], 
                                     $langSwitchTextDomain ) . "</th>\n";
    }

    // extra text for "Delete" cell
    echo '<th align="left">' . __( $langSwitchLangInfoOptDisplay[$langSwitchLangInfoNumOpts], 
                                     $langSwitchTextDomain ) . "</th>\n";

    echo "</tr>\n";

    // print current language info on scren
    for( $i = 0; $i < $num_langs; $i++ ) {
      echo "<tr>\n";
      for( $j = 0; $j < $langSwitchLangInfoNumOpts; $j++ ) {
        echo '<td><input type="text" name="langswitch_info_' . $i . "_" . $j . 
          '" value="' . $lang_info[$i][$j] . '" size="' . 
          $langSwitchLangInfoOptWidths[$j] . 
          '"></td>' . "\n";
      }
      // checkbox for delete it
      echo '<td><input type="checkbox" name="langswitch_delete' . $i . 
        '"></td>' . "\n";

      echo "</tr>\n";
    }

    // print a few blank input lines

    for( $i = $num_langs; $i < $num_langs + 3; $i++ ) {
      echo "<tr>\n";
      for( $j = 0; $j < $langSwitchLangInfoNumOpts; $j++ ) {
        echo '<td><input type="text" name="langswitch_info_' . 
          $i . "_" . $j . '" size="' . $langSwitchLangInfoOptWidths[$j] . 
          '"></td>' . "\n";
      }
      echo "<td>(" . __("Add New", $langSwitchTextDomain ) . ")</td></tr>\n";
    }

?>
   </table>

<?php
    echo '<hr /><p><input type="checkbox" name="langswitch_never_perm" value="Y"';
    if( $langSwitchNeverPerm ) {
      echo ' checked="checked"';
    }
    echo '> ';
    _e( 'Check this box to force Language Switcher URL suffixes to use ? or &amp;, if you are having trouble with permalink-style URL suffixes' );  
    echo "</p>\n";

?>    
<?php
    echo '<p><input type="checkbox" name="langswitch_force_suffix" value="Y"';
    if( $langSwitchForceSuffix ) {
      echo ' checked="checked"';
    }
    echo '> ';
    _e( 'Check this box to force Language Switcher to put a language URL suffix on every link (by default, Language Switcher omits URL suffixes for your default language in some cases)' );  
    echo "</p>\n";

?>    
    <p><?php _e("URL slug for Language Switcher:", $langSwitchTextDomain); ?> 
         <input type="text" name="langswitch_get_var" value="<?php echo $langSwitchGetVar; ?>" size="15">
          </p><hr />

          <p class="submit">
          <input type="submit" name="Submit" value="<?php _e('Update Options', $langSwitchTextDomain) ?>" />
          </p>
         </form>
        </div>
        <?php
}

/* Makes a list of languages for the admin menu */
function langswitch_admin_list_langs() {
  global $wp_query, $langswitch_lang_pref;
  global $langSwitchGetVar;
        
  $lang_info_all = langswitch_get_lang_info();
  $clean_uri = langswitch_uri_cleaner( $_SERVER['REQUEST_URI'] );

  foreach( $lang_info_all as  $lang_info ) {
    $lang = $lang_info[0];
    if ( $lang == $langswitch_lang_pref ) {
      continue;
    }
                                                
    echo '<li><a href="' . htmlspecialchars( $clean_uri, ENT_QUOTES );

    // always use GET format here, because some URLs don't work...

    if( strpos( $clean_uri, "?" ) !== false ) {
      // already have a GET in the URL, so add as a new GET parameter
      echo "&amp;";
    }  else {
      echo "?";
    }

    echo $langSwitchGetVar . "=" . $lang . '">' .
      $lang_info[1] . "</a></li>";

  } // end of loop over all languages
}

/* Makes a list of languages for the admin favorites menu */
function langswitch_admin_list_lang_favorites( $arr ) {
  global $wp_query, $langswitch_lang_pref;
  global $langSwitchGetVar;
        
  $lang_info_all = langswitch_get_lang_info();
  $clean_uri = langswitch_uri_cleaner( $_SERVER['REQUEST_URI'] );
  $clean_uri = substr( $clean_uri, strpos( $clean_uri, 'wp-admin' ) + 9 );

  foreach( $lang_info_all as  $lang_info ) {
    $lang = $lang_info[0];
    if ( $lang == $langswitch_lang_pref ) {
      continue;
    }
                                                
    $url = $clean_uri;

    // always use GET format here, because some URLs don't work...

    if( strpos( $clean_uri, "?" ) !== false ) {
      // already have a GET in the URL, so add as a new GET parameter
      $url .= "&amp;";
    }  else {
      $url .=  "?";
    }

    $url .= $langSwitchGetVar . "=" . $lang;
    $label = $lang_info[1];

    $arr[ $url ] = array( $label, '0' );

  } // end of loop over all languages

  return $arr;
}

/* Puts a message on the screen that update info is available elsewhere */
function langswitch_plugin_row( $file, $plugin_data ) {
  global $langSwitchTextDomain;
  
  $fName = plugin_basename(__FILE__);

  if( $file == $fName ) {
    echo '<tr><td colspan="5" class="plugin-update">';
    _e( "No information on Language Switcher updates. Check the plugin home page, or subscribe to its RSS feed.", $langSwitchTextDomain );
    echo "</td></tr>\n";
  }
}


/* Finds the user's currently chosen language */
function langswitch_find_pref_lang() 
{
  global $langswitch_lang_pref;
  global $langSwitchCookie, $langSwitchGetVar, $langSwitchDefLangOpt;

  $langswitch_lang_pref = FALSE;
  $info = langswitch_get_lang_info();
  
  // try browser setting
  if ( isset( $_SERVER["HTTP_ACCEPT_LANGUAGE"] )) {
    $tmp = strtolower( substr( $_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2 ));
    if( langswitch_lang_index( $info, $tmp, -1 ) != -1 ) {
    	$langswitch_lang_pref = $tmp;
    }
  }
  
  // Try to get setting from cookie 

  if( isset( $_COOKIE[ $langSwitchCookie ] )) {
    $tmp = trim($_COOKIE[ $langSwitchCookie ]);
    if( langswitch_lang_index( $info, $tmp, -1 ) != -1 ) {
    	$langswitch_lang_pref = $tmp;
    }
  }

  // override with GET or permalink URL suffix

  if( $_GET[ $langSwitchGetVar ] ) {
    $tmp = $_GET[ $langSwitchGetVar ];
    if( langswitch_lang_index( $info, $tmp, -1 ) != -1 ) {
    	$langswitch_lang_pref = $tmp;
    }
  }

  $req_url = $_SERVER['REQUEST_URI'];
  $pos = strpos( $req_url, $langSwitchGetVar );
  if( $pos !== false ) {
    $tmp = substr( $req_url, $pos + strlen( $langSwitchGetVar ) + 1, 2 );
    if( langswitch_lang_index( $info, $tmp, -1 ) != -1 ) {
    	$langswitch_lang_pref = $tmp;
    }    
  }
  
  // use default language if user did not set

  if( !$langswitch_lang_pref ) {
    $langswitch_lang_pref = get_option( $langSwitchDefLangOpt );
  }
  
}

/* Sets up WordPress to use user's language for everything */
function langswitch_setup_pref_lang()
{
  global $langswitch_lang_pref, $locale;
  global $langSwitchCookie;

  wp_cache_set( "rss_language", $langswitch_lang_pref, 'options' );
  setcookie( $langSwitchCookie, $langswitch_lang_pref, 
             time() + 30000000, COOKIEPATH );
  define( 'WPLANG', $langswitch_lang_pref );

  if( $locale != $langswitch_lang_pref ) {
    $locale = $langswitch_lang_pref;
    load_default_textdomain();
  }

}

/* Returns the user's chosen language ISO code */
function langswitch_current_lang( $dummy )
{
  global $langswitch_lang_pref;

  return $langswitch_lang_pref;
}

/* Reads the list of language info from options, and returns as a double
  array */
function langswitch_get_lang_info()
{
  global $langSwitchLangInfoOptPrefix, $langSwitchLangInfoNumOpts,
    $langSwitchLangInfoOptSuffixes, $langSwitchNumLangsOpt;

  $num_langs = get_option( $langSwitchNumLangsOpt );
  $lang_info = array();
  for( $i = 0; $i < $num_langs; $i++ ) {
      $lang_info[$i] = array();
      for( $j = 0; $j < $langSwitchLangInfoNumOpts; $j++ ) {
        $lang_info[$i][$j] = get_option( $langSwitchLangInfoOptPrefix . $i . 
                                          $langSwitchLangInfoOptSuffixes[ $j ] );
      }
    }

    return $lang_info;
}

/* Returns the correct index for a language into the lang info array */
function langswitch_lang_index( $lang_info, $lang, $def_ret = 0 ) 
{
  for( $i = 0; $i < count( $lang_info ); $i++ ) {
    if( $lang_info[ $i ][0] == $lang ) {
      return $i;
    }
  }

  return $def_ret;
}

/* Returns the language info for the current user-preferred language */
function langswitch_get_pref_lang_info()
{
  global $langswitch_lang_pref;

  $lang_info = langswitch_get_lang_info();
  $idx = langswitch_lang_index( $lang_info, $langswitch_lang_pref );

  return $lang_info[ $idx ];
}

/* Fixes category/tag lists so they are alphabetical in current language */
function langswitch_get_terms_fields( $fields, $args = array() ) {
  global $langswitch_lang_pref;

  $langtag = '[lang_' . $langswitch_lang_pref . ']';

  if( is_array( $fields )) {
    // 2.8 and later
    $fields[] = 'SUBSTRING( t.name, ' .
      'IF( INSTR( t.name, "' . $langtag . '" ),' .
      'INSTR( t.name, "' . $langtag . '" ) + 9, 1 )) AS langswname';
  } else {
    // my version of the filter
    $fields .= ', SUBSTRING( t.name, ' .
      'IF( INSTR( t.name, "' . $langtag . '" ),' .
      'INSTR( t.name, "' . $langtag . '" ) + 9, 1 )) AS langswname';
  }

  return $fields;
}

/* Fixes category/tag lists so they are alphabetical in current language */
function langswitch_get_terms_orderby( $orderby, $args = array() ) {
    if( $args['orderby'] == 'name' ) {
      $orderby = 'langswname, ' . $orderby;
    }

    return $orderby;
}

/* Fixes category/tag lists so they are alphabetical in current language */
function langswitch_tag_cloud_sort( $tags, $args = array() ) {
  if( $args['orderby'] == 'name' ) {
    usort( $tags, create_function('$a, $b', 'return strnatcasecmp($a->langswname, $b->langswname);') );
  }

  return $tags;

}  
/* internal function to format a date or time */
function langswitch_internal_format( $format, $time ) {

  //trick to find you if we've got "mysql time" or unix timestamp
  if(strpos($time, '-')){
    $time = mysql2date($format, $time);
  } else {
    $time = date($format, $time);
  }
        
  return $time;
}

/* Returns the right format to use, by seeing if the 
 original format is date, tiem, or both */
function langswitch_get_right_format( $orig_format, $default ) {
  $lang_info = langswitch_get_pref_lang_info();

  // see if orig format has month or year or hour codes in it

  $hasdate = preg_match( '|[FmMnYy]|', $orig_format );
  $hastime = preg_match( '|[gGhH]|', $orig_format );

  if( $hasdate && $hastime ) {
    return $lang_info[4] . " " . $lang_info[3];
  }

  if( $hasdate ) {
    return $lang_info[4];
  }

  if( $hastime ) {
    return $lang_info[3];
  }

  // If not, use default format

  if( strlen( $default )) {
    return $default;
  }

  return $orig_format;
}

/* Returns a time as a string in an appropriate format */
function langswitch_time( $format = '', $time = ''){
  $lang_info = langswitch_get_pref_lang_info();
  $format = langswitch_get_right_format( $format, $lang_info[3] );

  return langswitch_internal_format( $format, $time );
}

/* Returns a date as a string in an appropriate format */
function langswitch_date( $format = '', $time = ''){

  $lang_info = langswitch_get_pref_lang_info();
  $format = langswitch_get_right_format( $format, $lang_info[4] );

  return langswitch_internal_format( $format, $time );
}

/* Returns the current comment's time as a string in an appropriate format */
function langswitch_comment_time($tmstr, $fmt = '', $gmt = false) {
  global $comment;

  // Special case: sometimes this is used to get a MySQL formatted date
  if( $fmt == 'Y-m-d H:i:s' ) {
  	return $tmstr;
  }
  
  $comment_date = $gmt? $comment->comment_date_gmt : $comment->comment_date;

  $lang_info = langswitch_get_pref_lang_info();
  if( strlen( $lang_info[3] )) {
    $time = langswitch_internal_format( $lang_info[3], $comment_date );
  }
 
  return $time;
}

/* Returns the current comment's date as a string in an appropriate format */
function langswitch_comment_date($tmstr ) {
  global $comment;

  $lang_info = langswitch_get_pref_lang_info();
  if( strlen( $lang_info[4] )) {
    $time = langswitch_internal_format( $lang_info[4], $comment->comment_date );
  }
 
  return $time;
}

/* Returns the current post's time in an appropriate format */
function langswitch_the_time( $time, $d ){
  global $post;
        
  $lang_info = langswitch_get_pref_lang_info();
  $format = langswitch_get_right_format( $d, $lang_info[3] );

  if( strlen( $format )) {
    $time = langswitch_internal_format( $format, $post->post_date );
  }

  return $time;
}

/* Returns the current post's date in an appropriate format */
function langswitch_the_date($the_date, $d, $before = '', $after = ''){
  global $post;
        
  $lang_info = langswitch_get_pref_lang_info();
  $format = langswitch_get_right_format( $d, $lang_info[4] );
  if( strlen( $format )) {
    $the_date = langswitch_filter_langs( $before ) . 
      langswitch_internal_format( $format, $post->post_date ) .
      langswitch_filter_langs( $after );
  }

  return $the_date;
}

/* Internal function that does the filtering for languages: 
   picks out the user-preferred language version of some 
   text with special language tags in it. */
function langswitch_internal_lang_filter( $content, $default_blank = false, $override_lang = '' ) {
    global $langswitch_lang_pref, $langSwitchDefLangOpt;

    // See if there are any language tags at all

    if( preg_match_all ( '/<lang_/', $content , $match, 
                         PREG_PATTERN_ORDER )) {

      $lang_to_use = $langswitch_lang_pref;
      if( strlen( $override_lang ) == 2 ) {
        $lang_to_use = $override_lang;
      }

      if( !langswitch_content_has_lang( $langswitch_lang_pref, $content )) {
         if( $default_blank ) {
         	$lang_to_use = 'all';
         } else {
	        $lang_to_use = get_option( $langSwitchDefLangOpt );
         }	
      }

      // pick out proper language text, plus all languages text

      $content = str_replace( "<lang_all>", "<lang_" . $lang_to_use . ">",
                            $content );
      $content = str_replace( "</lang_all>", "</lang_" . $lang_to_use . ">",
                            $content );

      $find = "/(?s)<lang_" . $lang_to_use . 
          ">(.*?)<\/lang_" . $lang_to_use. ">/";

      preg_match_all ( $find, $content , $match, PREG_PATTERN_ORDER);
      $content = implode( '', $match[1] );
      
    }      

    return $content;
}

/* Makes a better default slug from the title - picks out default
 language version of title */
function langswitch_sanitize_slug( $title ) {
  global $langSwitchDefLangOpt;
  return langswitch_filter_langs( $title, false, $langSwitchDefLangOpt );
}

/* Picks out the correct version of some text with special 
   language tags in it, doing the right thing with
   [] instead of <> for tags and some other minor fixing
*/
function langswitch_filter_langs( $text, $default_blank = false, $override_lang = '' ) {
  global $langswitch_lang_pref;

  //fix for [lang_xx]
  $text = preg_replace("/\[(\/){0,1}lang_(..)\]/i", "<$1lang_$2>", $text);
  $text = preg_replace("/\[(\/){0,1}lang_all\]/i", "<$1lang_all>", $text);
        
  //fix for <p><lang_xx></p>
  $text = preg_replace ('/<p>(<(\/){0,1}lang_..>)<\/p>/i',"$1",$text);
  $text = preg_replace ('/<p>(<(\/){0,1}lang_all>)<\/p>/i',"$1",$text);
        
  //adds lang_all to all other stuff
  $text = preg_replace ( '/(<lang_..>)/i', "</lang_all>\\1", $text);
  $text = preg_replace ( '/(<\/lang_..>)/i', "\\1<lang_all>", $text);
  $text = '<lang_all>' . $text . '</lang_all>';

  return langswitch_internal_lang_filter( $text, $default_blank, $override_lang );
}

/* Filters an array of items */
function langswitch_filter_langs_array( $arr ) {

  foreach( $arr as $item ) {
    $newarr[] = langswitch_filter_langs_with_entities( $item );
  }
  return $newarr;
}


/* Filters text as in langswitch_filter_langs, but works with 
   &lt; and &gt; entities if they are around language tags. */
function langswitch_filter_langs_with_entities($content, $category = null){

  $content = preg_replace( "/&lt;(\/){0,1}lang_(..)&gt;/i", 
                           "<$1lang_$2>", $content );
  $content = preg_replace( "/&lt;(\/){0,1}lang_all&gt;/i", 
                           "<$1lang_all>", $content );
                
  return langswitch_filter_langs( $content );
}


/* Filters text as in langswitch_filter_langs, 
   but puts in message saying text is missing if it is 
   empty after filtering -- unless it was empty before filtering */
function langswitch_filter_langs_with_message( $text ){

  $text = trim( $text );
  if( !strlen( $text )) {
    return '';
  }

  $text = trim( langswitch_filter_langs($text, true) );
        
  if($text == '') {
    $info = langswitch_get_pref_lang_info();
    $text = $info[5];
  }

  return $text;
}

/* internal callback function for translating categories */ 
function langswitch_gettext_callback($matches)
{
  return $matches[1] . __( $matches[2] ) . $matches[3];
}

/* Translates a category using gettext */
function langswitch_gettext_the_category( $text )
{
  return preg_replace_callback( '/(<a[^>]*>)(.*?)(<\/a>)/i', 
                                'langswitch_gettext_callback', $text );     
}

/* Returns true if the given content contains the given language, 
   false otherwise */
function langswitch_content_has_lang( $lang, $content )
{
    return !( strpos( $content, "<lang_$lang>" ) === false );
}

/* Cleans the language tags and XSS issues out of a URI and returns it */
function langswitch_uri_cleaner( $uriStr )
{
  global $langSwitchGetVar;

  // Clean up the URL string, and remove any HTML tags

  $uriStr = urldecode( $uriStr );
  $uriStr = strip_tags( $uriStr );
  $uriStr = trim( $uriStr );

  // clean all variations of language tags from URL
  
  $uriStr = preg_replace ( "/" . $langSwitchGetVar . "=..&amp;/i", 
                           '', $uriStr );
  $uriStr = preg_replace ( "/" . $langSwitchGetVar . "=..&/i", 
                           '', $uriStr );
  $uriStr = preg_replace ( "/\?" . $langSwitchGetVar . "=..\/?/i", 
                           '', $uriStr );
  $uriStr = preg_replace ( "/&" . $langSwitchGetVar . "=..\/?/i", 
                           '', $uriStr );
  $uriStr = preg_replace ( "/&amp;" . $langSwitchGetVar . "=..\/?/i", 
                           '', $uriStr );
  $uriStr = preg_replace ( "/\/" . $langSwitchGetVar . "\/../i", 
                           '', $uriStr );
  
  // clean empty searches from URL, if there are no other parameters,
  // as they can screw things up for static home pages
  
  $uriStr = preg_replace( "/\?s=$/", '', $uriStr );
  
  return $uriStr;
}

/* Fixes language switcher URLs with "page/N" in them so the page part
 is at the end of the URL.
*/
function langswitch_fix_page_url( $urlstr ) {

  $pos = strpos( $urlstr, '/page/' );
  if( $pos === false ) {
    return $urlstr;
  }

  $pos2 = strpos( $urlstr, '/', $pos + 6 );
  if( !$pos2 ) {
    return $urlstr;
  }

  return langswitch_trailingslashit( 
                                    substr( $urlstr, 0, $pos ) . 
                                    substr( $urlstr, $pos2 )) .
    "page/" . substr( $urlstr, $pos + 6, $pos2 - $pos - 6 ) . "/";

}


/* Calls langswitch_add_language_url with force = true */
function langswitch_add_language_url_force( $urlstr, $lang = '' ) {
  return langswitch_add_language_url( $urlstr, $lang, true );
}

/* Adds a suffix to a URL for a language (default is user's chosen
   language) and returns the new URL. Note that the URL returned is
   HTML-encoded (i.e. parts added by Language Switcher are URL-encoded). */
function langswitch_add_language_url( $urlstr, $lang = '', $force = false ) 
{
  global $langswitch_lang_pref, $langSwitchGetVar;
  global $langSwitchNeverPermOpt, $langSwitchForceSuffixOpt;

  $langSwitchNeverPerm = get_option( $langSwitchNeverPermOpt, 0 );
  if( get_option( $langSwitchForceSuffixOpt, 0 )) {
    $force = true;
  }
        
  if( !strlen( $lang )) {
    $lang = $langswitch_lang_pref;
  }

  /* clean language out of URL */ 
  $urlstr = langswitch_uri_cleaner( $urlstr );

  /* If the desired language is the current language, do nothing,
     unless force is true. */

  if( !$force && $lang == $langswitch_lang_pref ) {
    return $urlstr;
  }


  /* Make sure the link doesn't have an anchor in it, or if it does,
   that we go before the # */

  $parts = explode( '#', $urlstr, 2 );
  $anchor = '';
  if( count( $parts ) > 0 && strlen( $parts[1] )) {
    $anchor = '#' . $parts[1];
    $urlstr = $parts[0];
  }
  
  /* Add param for language */ 

  if( strpos( $urlstr, "?" ) !== false ) {
    // already have a GET in the URL, so add as a new GET parameter
        return $urlstr . "&amp;" . $langSwitchGetVar . "=" . $lang . $anchor;
  } 
  
  $permalink = get_option('permalink_structure');

  if( $permalink != '' && !$langSwitchNeverPerm ) {
    // we have a permalink structure, add a new param

    return langswitch_fix_page_url( langswitch_trailingslashit( $urlstr ) . $langSwitchGetVar . "/" . $lang . "/" ) . $anchor;
  }
  
  // if we get here, they do not have permalinks or don't want to use them, 
  // so we want to add a language GET to the URL

  return langswitch_trailingslashit( $urlstr ) . "?" . 
          $langSwitchGetVar . "=" . $lang . $anchor;
}

/* TEMPLATE FUNCTION: langswitch_list_langs
 * Makes a list of languages for the sidebar, using either flags or text or both */
function langswitch_list_langs( $useflags=false, $usetext=true, $html_tag = 'li' ) {
  global $wp_query, $langswitch_lang_pref;
  global $langSwitchFlagsPath;
        
  $lang_info_all = langswitch_get_lang_info();

  $clean_uri = htmlspecialchars( langswitch_uri_cleaner( $_SERVER['REQUEST_URI'], ENT_QUOTES ));
                                
  foreach( $lang_info_all as  $lang_info ) {
                                                
    if ( $lang_info[0] == $langswitch_lang_pref ) {
      $highlight = "language_item current_language_item";
    } else {
      $highlight = "language_item";
    }
                                                
    echo "<$html_tag class=\"$highlight\"><a href=\"";

    echo langswitch_add_language_url( $clean_uri, $lang_info[0], true );
                                                
    echo "\">";

    if( $useflags ) {
      echo "<img src=\"" . $langSwitchFlagsPath . 
        $lang_info[2] . "\" alt=\"" . $lang_info[1] . 
        "\" title=\"" . $lang_info[1] . "\" />" ;
      if( $usetext ) {
                echo " ";
      }
    }
    if( $usetext ) {
      echo $lang_info[1];
    }

    echo "</a></$html_tag>";
  } // end of loop over all languages
}

/* TEMPLATE FUNCTION: langswitch_post_other_langs 
 * Makes a list of the other languages for a post or page */
function langswitch_post_other_langs( $useflags = false, $usetext = true,
        $none=' ', $before='<ul>', $after='</ul>', $pre='<li>', $suf='</li>' ) {    
    global $page, $pages, $langswitch_lang_pref, $post;
    global $langSwitchFlagsPath;

    $content = $post->post_content;
    $link = get_permalink();
    $link = htmlentities( langswitch_uri_cleaner( $link ), ENT_QUOTES );

    // translate/filter the text we are given 

    $before = langswitch_filter_langs($before);
    $after = langswitch_filter_langs($after);
    $pre = langswitch_filter_langs($pre);
    $suf = langswitch_filter_langs($suf);
    $other_langs = langswitch_filter_langs($none);

    $lang_info = langswitch_get_lang_info();

    if( preg_match_all ( '/[<|\[]lang_(..)[>|\]]/', $content , $match, PREG_PATTERN_ORDER )) {
        $match = array_unique($match[1]);
        $other_langs = $before;
        
         foreach( $match as $lang ) {
            if( $langswitch_lang_pref != $lang ) {

              $other_langs .= "$pre<a href='" . 
                langswitch_add_language_url( $link, $lang, true ) . 
                "'>";

              $indx = langswitch_lang_index( $lang_info, $lang );
              if( $useflags ) {
                $other_langs .= "<img src=\"" . $langSwitchFlagsPath . 
                  $lang_info[ $indx ][2] . "\" alt=\"" . 
                  $lang_info[ $indx ][1] . "\" title=\"" . 
                  $lang_info[ $indx ][1] . "\" />";
                  if( $usetext ) {
                        $other_langs .= " ";
                  }
              } 
              if( $usetext ) {
                $other_langs .= $lang_info[ $indx ][1];
              }
              
              $other_langs .= "</a>" . $suf;
            }
         }
        
        if($other_langs != $before) { //to avoid empty <ul></ul>
            $other_langs .= $after;
        } else {
            $other_langs = $none;
        }
    }

    echo $other_langs;
}

/* Adds a trailing slash to a URL, if it's needed */
function langswitch_trailingslashit( $uri ){
  if( $uri{ strlen($uri) - 1 } != '/' ) {
    return $uri . '/';
  } else {
    return $uri;
  }
}

// Widget for language list
function langswitch_widget_listlangs($args) {
  global $langSwitchTextDomain;

  extract($args);

  $use_names = get_option('langswitch_widget_lang_names' );
  $use_flags = get_option('langswitch_widget_lang_flags' );
  if( !$use_names && !$use_flags ) {
    // no options saved yet, probably 
    $use_names = true;
  }

  echo $before_widget;
  echo $before_title;
  _e( 'Language', $langSwitchTextDomain );
  echo $after_title;

  echo "<ul>\n";
  langswitch_list_langs( $use_flags, $use_names );
  echo "</ul>\n";

  echo $after_widget;
}

// Options screen for widget
function langswitch_widget_listlangs_control() {
  global $langSwitchTextDomain;

  $use_names = get_option('langswitch_widget_lang_names' );
  $use_flags = get_option('langswitch_widget_lang_flags' );
  if( !$use_names && !$use_flags ) {
    // no options saved yet, probably 
    $use_names = true;
  }

  if ( $_POST["listlangs_options_submit"] ) {
    $use_names = ( $_POST["langswitch_listlangs_use_names"] == 'Y' );
    $use_flags = ( $_POST["langswitch_listlangs_use_flags"] == 'Y' );

    update_option('langswitch_widget_lang_names', $use_names );
    update_option('langswitch_widget_lang_flags', $use_flags );
  }

?>
			<p><label for="langswitch_listlangs_use_names"><?php _e('Show Names?', $langSwitchTextDomain); ?></label> <input name="langswitch_listlangs_use_names" type="checkbox" value="Y" <?php if( $use_names ) echo "checked"; ?> /></p>

			<p><label for="langswitch_listlangs_use_flags"><?php _e('Show Flags?', $langSwitchTextDomain); ?></label> <input name="langswitch_listlangs_use_flags" type="checkbox" value="Y" <?php if( $use_flags ) echo "checked"; ?> /></p>

			<input type="hidden" name="listlangs_options_submit" value="1" />
<?php
}

?>
