<?php

use IgnorableObservers\IgnorableObservers;
use Illuminate\Database\Eloquent\Model;

/**
 * Dummy class to test the trait
 */
class IgnorableObserversDummyClass extends Model {
  use IgnorableObservers;
}

class IgnorableObserversTest extends \PHPUnit_Framework_TestCase {

  /**
   * Test ignoring and unignoring all observable model events
   */
  public function testIgnoringAllEvents() {
    // should start empty
    $this->assertCount(0, IgnorableObserversDummyClass::ignoredObservableEvents());

    // ignore all events
    IgnorableObserversDummyClass::ignoreObservableEvents();

    $events = (new IgnorableObserversDummyClass())->getObservableEvents();

    // should add all observable events to ignored observable events
    $this->assertEquals($events, IgnorableObserversDummyClass::ignoredObservableEvents());

    // reset all ignored events
    IgnorableObserversDummyClass::unignoreObservableEvents();

    // should return to empty
    $this->assertCount(0, IgnorableObserversDummyClass::ignoredObservableEvents());
  }

  /**
   * Test ignoring a subset of model events
   */
  public function testIgnoringSubset() {
    // should start empty
    $this->assertCount(0, IgnorableObserversDummyClass::ignoredObservableEvents());

    // ignore two events
    IgnorableObserversDummyClass::ignoreObservableEvents(['created', 'saved']);

    // should add both events to ignored observable events
    $this->assertEquals(['created', 'saved'], IgnorableObserversDummyClass::ignoredObservableEvents());

    // reset all ignored events
    IgnorableObserversDummyClass::unignoreObservableEvents();

    // should return to empty
    $this->assertCount(0, IgnorableObserversDummyClass::ignoredObservableEvents());
  }

  /**
   * Test ignoring the same observable event multiple times
   */
  public function testIgnoringDuplicates() {
    // should start empty
    $this->assertCount(0, IgnorableObserversDummyClass::ignoredObservableEvents());

    // ignore the same event multiple times
    IgnorableObserversDummyClass::ignoreObservableEvents(['created']);
    IgnorableObserversDummyClass::ignoreObservableEvents(['created']);
    IgnorableObserversDummyClass::ignoreObservableEvents(['created']);
    IgnorableObserversDummyClass::ignoreObservableEvents(['created']);
    IgnorableObserversDummyClass::ignoreObservableEvents(['created']);

    // should add created event to ignored observable events only once
    $this->assertEquals(['created'], IgnorableObserversDummyClass::ignoredObservableEvents());

    // reset all ignored events
    IgnorableObserversDummyClass::unignoreObservableEvents();

    // should return to empty
    $this->assertCount(0, IgnorableObserversDummyClass::ignoredObservableEvents());
  }

  /**
   * Test unignoring a subset of ignorable events
   */
  public function testUnignoringSubset() {
    // should start empty
    $this->assertCount(0, IgnorableObserversDummyClass::ignoredObservableEvents());

    // ignore all events
    IgnorableObserversDummyClass::ignoreObservableEvents();

    $events = (new IgnorableObserversDummyClass())->getObservableEvents();

    // should add all observable events to ignored observable events
    $this->assertEquals($events, IgnorableObserversDummyClass::ignoredObservableEvents());

    // unignore one event
    $ignored = array_pop($events);

    IgnorableObserversDummyClass::unignoreObservableEvents($ignored);

    // should return the list of events, minus one
    $this->assertEquals($events, IgnorableObserversDummyClass::ignoredObservableEvents());

    // reset all ignored events
    IgnorableObserversDummyClass::unignoreObservableEvents();

    // should return to empty
    $this->assertCount(0, IgnorableObserversDummyClass::ignoredObservableEvents());
  }

  /**
   * Test method for determining if an observer is currently ignored
   */
  public function testIsIgnoredCheck() {
    // should start empty
    $this->assertCount(0, IgnorableObserversDummyClass::ignoredObservableEvents());

    // should return false
    $this->assertFalse(IgnorableObserversDummyClass::isIgnoredObservableEvent('created'));

    // ignore created event
    IgnorableObserversDummyClass::ignoreObservableEvents('created');

    // should return true
    $this->assertTrue(IgnorableObserversDummyClass::isIgnoredObservableEvent('created'));

    // reset all ignored events
    IgnorableObserversDummyClass::unignoreObservableEvents();

    // should return to empty
    $this->assertCount(0, IgnorableObserversDummyClass::ignoredObservableEvents());
  }
}
