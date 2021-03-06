<?php
// $Id: db_helper.php,v 1.1 2010/09/20 08:47:59 administrator Exp $

/**
 * @file
 * This is a library of useful database functions and methods.
 * please insert the link if you have a patch for core to introduce a function from this lib to core.
 */

/*
 * Include the appropriate db_result_array function.
 * Get an array of all the results of a query. This is very handy
 *  for building an option list for a form element.
 * This is a backport from 7.x.
 * Pending patch (7.x): http://drupal.org/node/206956.
 *
 * NOTE: This is open code - do not put a function declaration on it.
 */
  $db_types = array('mysql', 'mysqli', 'postgres');
  $dbtype = $GLOBALS['db_type'];

/**
 * Core functions.
 */

/**
 * Implementation of hook_help().
 */
function helpers_database_help($path, $args) {
  $output = null;
  switch ($path) {
    case 'admin/help#helpers_database':
      $output .= t('A development library for the database. Contains useful functions and methods for database queries.');
  }
  return $output;
}

/**
 * Database functions.
 */

// The following two functions are "borrowed" from D6 for those
// who want to implement more secure code in D5.

/**
 * Run an insert query on the active database.
 *
 * @param $table
 *   The database table on which to run the insert query.
 * @param $fields
 *   An associative array of the values to insert.  The keys are the
 *   fields, and the corresponding values are the values to insert.
 * @return
 *   A   database query result resource, or FALSE if the query was not
 * executed correctly.
 *
 */
function db_insert($table, $fields) {

  $insert_fields = array_keys($fields);
  $insert_values = array_values($fields);

  $params = array();
  foreach ($insert_values as $value) {
    switch (gettype($value)) {		
      case 'double':
        $escape = '%f';
        break;
      case 'integer':
        $escape = '%d';
        break;
      case 'string':
        $escape = "'%s'";
        break;
      case 'NULL':
        $escape = 'NULL';
        break;
      default:
        continue;
    }
    $params[] = $escape;
  }  
  $sql = 'INSERT INTO {'. $table .'} ('. implode(',', $insert_fields) .') VALUES ('. implode(',', $params) .')';
  return db_query($sql, $insert_values);
}

/**
 * Run an update query on the active database.
 *
 * @param $table
 *   The database table on which to run the update query.
 * @param $fields
 *   An associative array of the values to update.  The keys are the
 *   fields, and the corresponding values are the values to update to.
 * @param $where
 *   The where rules for this update query.
 * @param $where_type
 *   Whether to AND or OR the where rules together.
 * @return
 *   A database query result resource, or FALSE if the query was not
 *   executed correctly.
 *
 */
function db_update($table, $fields, $where, $where_type='AND') {

  $update_values = array_values($fields); 

  $flat_fields = array();
  foreach ($fields as $field => $value) {
    switch (gettype($value)) {
      case 'integer':
        $escape = '%d';
        break;
      case 'double':
        $escape = '%f';
        break;
      case 'string':
        $escape = "'%s'";
        break;
      case 'NULL':
        $escape = 'NULL';
        break;
      default:
        continue;
    }
    $flat_fields[] = $field .'='. $escape;
  }

  list($where_string, $where_values) = db_where_clause($where, $where_type);

  $sql = 'UPDATE {'. $table .'} SET '. implode(',', $flat_fields) . $where_string;

  return db_query($sql, array_merge($update_values, $where_values));
}

/**
 * Run a delete query on the active database.
 *
 * @param $table
 *   The database table on which to run the delete query.
 * @param $where
 *   The where rules for this delete query.
 * @param $where_type
 *   Whether to AND or OR the where rules together.
 * @return
 *   A database query result resource, or FALSE if the query was not
 *   executed correctly.
 *
 */
function db_delete($table, $where, $where_type='AND') {

  list($where_string, $where_values) = db_where_clause($where, $where_type);

  $sql = 'DELETE FROM {'. $table .'} '. $where_string;

  return db_query($sql, array_merge($where_string, $where_values));
}

/**
 * Build the WHERE portion of an SQL query, based on the specified values.
 *
 * @param $where
 *   Associative array of rules in the WHERE clause.  If a key in the array
 *   is numeric, the value is taken as a literal rule.  If it is non-numeric,
 *   then it is assumed to be a field name and the corresponding value is the
 *   value that it must hold.
 * @param $where_type
 *   Whether the values of the WHERE clause should be ANDed or ORed together.
 *
 *   As an example, this $where clause would be translated as follows:
 *   $where = array('name'=>'foo', 'type'=>'page', 'created < 1147567877')
 *
 *   WHERE (name='foo') AND ('type'='page') AND (created < 1147567877')
 * @return
 *   An array containing the where clause with sprintf() markers, and
 *   an array of values to substitute for them.
 */
function db_where_clause($where, $where_type='AND', $where_keyword=TRUE) {
  $params = array();
  $args = array();
  foreach ($where as $key => $value) {
    if (is_numeric($key)) {
      $params[] = ' ('. $value .') ';
    }
    else {
      switch (gettype($value)) {
        case 'double':
          $escape = '%f';
          break;
        case 'integer':
          $escape = '%d';
          break;
        case 'string':
          $escape = "'%s'";
          break;
        case 'NULL':
          $escape = 'NULL';
          break;
        default:
          continue;
      }
      $params[] = ' ('. $key .'='. $escape .') ';
      $args[] = $value;
    }
  }

  $return = '';
  if (sizeof($params)) {
    $return = ($where_keyword ? ' WHERE ' : ' ') . implode($where_type, $params);
  }

  return array($return, $args);
}

/**
 * Returns an array with all objects from a query
 * Does not do a rewrite sql for you!
 * Call similar to db_query.
 */
function db_fetch_all_as_objects($query) {
  $results = array();
  $args = func_get_args();
  array_shift($args);
  $res = db_query($query, $args);

  while ($row = db_fetch_object($res)) {
    $results[] = $row;
  }
  return $results;
}

/**
 * Returns an array with all arrays from a query
 * Does not do a rewrite sql for you!
 * Call similar to db_query.
 */
function db_fetch_all_as_arrays($query) {
  $results = array();
  $args = func_get_args();
  array_shift($args);
  $res = db_query($query, $args);

  while ($row = db_fetch_array($res)) {
    $results[] = $row;
  }
  return $results;
}