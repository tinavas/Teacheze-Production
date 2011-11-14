<?php
// $Id: destinations.filesource.inc,v 1.4 2010/11/09 05:41:11 ronan Exp $


/**
 * @file
 * A destination type for saving locally to the server.
 */

/**
 * A destination type for saving locally to the server.
 *
 * @ingroup backup_migrate_destinations
 */

class backup_migrate_files_destination_archivesource extends backup_migrate_destination {
  var $supported_ops = array('source');

  function type_name() {
    return t("Site Archive Source");
  }

  /**
   * Declare the current files directory as a backup source..
   */
  function destinations() {
    $out  = array();
    $out['archive'] = backup_migrate_create_destination('archive', array('destination_id' => 'archive', 'location' => '.', 'name' => t('Site Archive')));
    return $out;
  }

  /**
   * Return a list of backup filetypes.
   */
  function file_types() {
    return array(
      "sitearchve" => array(
        "extension" => "sitearchive.tar",
        "filemime" => "application/x-tar",
        "backup" => TRUE,
        "restore" => FALSE,
      ),
    );
  }

  /**
   * Backup from this source.
   */
  function backup_to_file($file, $settings) {
    if ($this->check_libs()) {
      $exclude = array('settings.php');
      $files = $this->get_files_to_backup($this->get_location(), $settings, $exclude, realpath('.') . '/');
      if ($files) {
        $manifest = $this->generate_manifest();
        $db = $this->get_db();

        $file->push_type('sitearchve');
        $gz = new Archive_Tar($file->filepath(), false);

        $gz->addModify(array($manifest), $file->name .'/', dirname($manifest));
        $gz->addModify($files, $file->name .'/docroot', $this->get_location());
        $gz->addModify($db, $file->name . '/', dirname($db));

        unlink($manifest);
        rmdir(dirname($manifest));
        unlink($db);
        rmdir(dirname($db));

        return $file;
      }
      backup_migrate_backup_fail('No files available.', array(), $settings);
      return FALSE;
    }
    return FALSE;
  }

  /**
   * Generate a manifest file.
   */
  function generate_manifest() {
    $info = array(
      'datestamp' => time(),
      'formatversion' => '0.1',
      'generator' => 'Backup and Migrate Archive (http://github.com/ronan/backup_migrate_archive)',
      'generatorversion' => 'dev', 
      'sites' => array(
        '0' => array(
          'version' => VERSION,
          'name' => variable_get('site_name', ''),
          'docroot' => 'docroot',
          'sitedir' => 'docroot/' . conf_path(),
          'database' => array('default' => array('file' => 'database.sql', 'driver' => 'mysql')),
        ),
      ),
    );
    if (variable_get('file_downloads', FILE_DOWNLOADS_PUBLIC) == FILE_DOWNLOADS_PRIVATE) {
      $sites['files']['private'] = file_directory_path();
      $sites['files']['mode'] = 'private';
    }
    else {
      $sites['files']['public'] = file_directory_path();
      $sites['files']['mode'] = 'public';
    }
    $ini = $this->_array_to_ini($info);

    $tmpdir = realpath(file_directory_temp()) .'/'. uniqid('backup_migrate_');
    mkdir($tmpdir);
    $filepath = $tmpdir . '/MANIFEST.info';
    file_put_contents($filepath, $ini);
    return $filepath;
  }

  /**
   * Get a database dump to add to the archive.
   */
  function get_db() {
    backup_migrate_include('destinations', 'files', 'filters', 'profiles');

    $file = new backup_file();
    $settings = _backup_migrate_profile_saved_default_profile();
    $settings->source_id = 'db';
    $settings->filters['compression'] = 'none';
    $file = backup_migrate_filters_backup($file, $settings);

    // Generate a tmp file with the correct final title (because ArchiveTar doesn't seem to allow renaming).
    $tmpdir = realpath(file_directory_temp()) .'/'. uniqid('backup_migrate_');
    mkdir($tmpdir);
    $filepath = $tmpdir . '/database.sql';
    rename($file->filepath(), $filepath);

    return $filepath;
  }

  /**
   * Convert an associated array to an ini format string.
   */
  function _array_to_ini($data, $prefix = '') {
    $content = ""; 
    foreach ($data as $key => $val) {
      if ($prefix) {
        $key = $prefix . '[' . $key .']';
      }
      if (is_array($val)) {
        $content .= $this->_array_to_ini($val, $key);
      }
      else {
        $content .= $key . " = \"". $val ."\"\n";
      }
    }
    return $content;
  }


  /**
   * Get a list of files to backup from the given set if dirs. Exclude any that match the array $exclude.
   */
  function get_files_to_backup($dir, $settings, $exclude = array(), $base_dir = '') {
    $out = array();
    if (!file_exists($dir)) {
      backup_migrate_backup_fail('Directory %dir does not exist.', array('%dir' => $dir), $settings);
      return FALSE;
    }
    if ($handle = @opendir($dir)) {
      while (($file = readdir($handle)) !== FALSE) {
        if ($file != '.' && $file != '..' && !in_array($file, $exclude)) {
           $file = realpath($dir. '/'. $file);
           if (is_dir($file)) {
              $subdir = $this->get_files_to_backup($file, $settings, $exclude, $base_dir);
              // If there was an error reading the subdirectory then abort the backup.
              if ($subdir === FALSE) {
                closedir($handle);
                return FALSE;
              }
              $out = array_merge($out, $subdir);
           } else {
              if (is_readable($file)) {
                $out[] = str_replace($base_dir, '', $file);
              }
              else {
                backup_migrate_backup_fail('The directory cannot be backed up because the file %file cannot be read by the web server.', array('%file' => str_replace($base_dir, '', $file)), $settings);
                closedir($handle);
                return FALSE;
              }
           }
        }
      }
      closedir($handle);
    }
    else {
      backup_migrate_backup_fail('Could not open directory %dir', array('%dir' => $dir), $settings);
      return FALSE;
    }
    return $out;
  }

  /**
   * Break the excpluded paths string into a usable list of paths.
   */
  function get_excluded_paths($paths) {
    $out = explode("\n", $paths);
    foreach ($out as $key => $val) {
      $out[$key] = trim($val, "/ \t\r\n");
    }
    return $out;
  }

  /**
   * Check that the required libraries are installed.
   */
  function check_libs() {
    $result = true;

    // Extend inlcude path with path to this module's includes directory.
    $includes_directory = './' . drupal_get_path('module', 'backup_migrate_files') . '/includes';
    $include_path_old = set_include_path($includes_directory . ';' . get_include_path());

    // Check if PEAR.php is present in a non-fatal way and error gracefully if it isn't.
    include_once 'PEAR.php';
    if (!class_exists('PEAR')) {
      _backup_migrate_message('PEAR is not installed correctly. See the README.txt file in the backup_migrate_files module directory for help.', array(), 'error');
      $result = false;
    }

    // Check if Tar.php is present in a non-fatal way and error gracefully if it isn't.
    if ($result) {
      // Try to get version in this module's includes directory first, but prevent warning texts being output.
      if (file_exists($includes_directory . '/Tar.php')) {
        include_once $includes_directory . '/Tar.php';
      }
      if (!class_exists('Archive_Tar')) {
        // Try to get via PEAR directory structure.
        include_once 'Archive/Tar.php';
        if (!class_exists('Archive_Tar')) {
          _backup_migrate_message('Archive_Tar is not installed correctly. See the README.txt file in the backup_migrate_files module directory for help.', array(), 'error');
          $result = false;
        }
      }
    }

    // Restore include path.
    set_include_path($include_path_old);
    return $result;
  }
}

