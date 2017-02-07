<?php namespace IgnorableObservers;

trait IgnorableObservers {

  /**
   * List of observable events to ignore
   *
   * @var array
   */
  static protected $_ignored_observables = [];

  /**
   * Set observable events to ignore (i.e. 'saved', 'saving', etc)
   *
   * @param  array $observables Events
   */
  static public function ignoreObservableEvents($observables = []) {
    $observables = is_array($observables) ? $observables : func_get_args();

    // if empty, just ignore everything
    if ( empty($observables) ) {
      self::$_ignored_observables = array_unique(array_merge(self::$_ignored_observables, (new static())->getObservableEvents()));
    } else {
      self::$_ignored_observables = array_unique(array_merge(self::$_ignored_observables, $observables));
    }
  }

  /**
   * Check if an event is ignored
   *
   * @param  string  $event Event
   * @return boolean
   */
  static public function isIgnoredObservableEvent($event = '') {
    return array_search($event, self::$_ignored_observables) !== FALSE;
  }

  /**
   * Get the list of ignored observable events
   *
   * @return array
   */
  static public function ignoredObservableEvents() {
    return self::$_ignored_observables;
  }

  /**
   * Unignore previously ignored observable events, if empty then unignore all
   * of them
   *
   * @param  array $observables Events
   */
  static public function unignoreObservableEvents($observables = []) {
    $observables = is_array($observables) ? $observables : func_get_args();

    if ( empty($observables) ) {
      self::$_ignored_observables = [];
    } else {
      self::$_ignored_observables = array_diff(self::$_ignored_observables, $observables);
    }
  }

  /**
   * Override the fireModelEvent method and skip the "firing" part if the
   * designated event is marked as "ignored"
   *
   * @param  string  $event Event
   * @param  boolean $halt  Call until or fire method
   * @return mixed
   */
  protected function fireModelEvent($event, $halt = true) {
    if ( !static::isIgnoredObservableEvent($event) ) {
      return parent::fireModelEvent($event, $halt);
    }
  }
}
