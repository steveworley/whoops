<?php

namespace Drupal\whoops\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Whoops\Run as Whoops;
use Whoops\Handler\PrettyPageHandler;

/**
 * Class Subscriber.
 *
 * @package Drupal\whoops\Subscriber
 */
class Subscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events = [];
    $events[KernelEvents::REQUEST][] = ['onRequest'];
    return $events;
  }

  /**
   * Register the Whoops! error handler on request.
   */
  public function onRequest() {
    require __DIR__ . '/../../vendor/autoload.php';

    $whoops = new Whoops;
    $whoops->pushHandler(new PrettyPageHandler);
    $whoops->register();

    // Ensure that Drupal registers the shutdown function.
    drupal_register_shutdown_function([$whoops, $whoops::SHUTDOWN_HANDLER]);
  }
}