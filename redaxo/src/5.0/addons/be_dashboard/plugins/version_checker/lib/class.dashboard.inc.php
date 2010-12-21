<?php

/**
 * REDAXO Version Checker Addon
 *
 * @author markus[dot]staab[at]redaxo[dot]de Markus Staab
 * @author <a href="http://www.redaxo.de">www.redaxo.de</a>
 *
 * @package redaxo4
 * @version svn:$Id$
 */

class rex_version_checker_notification extends rex_dashboard_notification
{
  public function __construct()
  {
    // default cache lifetime in seconds
    $cache_options['lifetime'] = 3600;

    parent::__construct('version-checker', $cache_options);
  }

  public function checkPermission()
  {
    global $REX;

    return $REX['USER']->isAdmin();
  }

  protected function prepare()
  {
    global $REX;

    $versionCheck = rex_a657_check_version();

    if($versionCheck)
    {
      $this->setMessage($versionCheck);
    }
    else
    {
      $this->setMessage(rex_warning('Version-Checker: '. $REX['I18N']->msg('vchecker_connection_error')));
    }
  }
}