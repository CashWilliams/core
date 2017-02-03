<?php

namespace Drupal\dblog\Tests;

use Drupal\filter\Entity\FilterFormat;

/**
 * Generate events and verify dblog entries; verify user access to log reports
 * based on permissions. Using the dblog UI generated by a View.
 *
 * @see Drupal\dblog\Tests\DbLogTest
 *
 * @group dblog
 */
class DbLogViewsTest extends DbLogTest {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('dblog', 'node', 'forum', 'help', 'block', 'views');

  /**
   * {@inheritdoc}
   */
  protected function getLogsEntriesTable() {
    return $this->xpath('.//table[contains(@class, "views-view-table")]');
  }

  /**
   * {@inheritdoc}
   */
  protected function filterLogsEntries($type = NULL, $severity = NULL) {
    $query = array();
    if (!is_null($type)) {
      $query['type[]'] = $type;
    }
    if (!is_null($severity)) {
      $query['severity[]'] = $severity;
    }

    $this->drupalGet('admin/reports/dblog', array('query' => $query));
  }

  /**
   * {@inheritdoc}
   */
  public function testDBLogAddAndClear() {
    // Is necesary to create the basic_html format because if absent after
    // delete the logs, a new log entry is created indicating that basic_html
    // format do not exists.
    $basic_html_format = FilterFormat::create(array(
      'format' => 'basic_html',
      'name' => 'Basic HTML',
      'filters' => array(
        'filter_html' => array(
          'status' => 1,
          'settings' => array(
            'allowed_html' => '<p> <br> <strong> <a> <em>',
          ),
        ),
      ),
    ));
    $basic_html_format->save();

    parent::testDBLogAddAndClear();
  }

}
