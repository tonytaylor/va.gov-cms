<?php

namespace Drupal\va_gov_build_trigger\EventSubscriber;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\State\StateInterface;
use Drupal\va_gov_build_trigger\Event\ReleaseStateTransitionEvent;
use Drupal\va_gov_build_trigger\Service\ReleaseStateManager;
use Drupal\va_gov_build_trigger\Traits\RunsDuringBusinessHours;
use Drupal\va_gov_content_release\Request\RequestInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Runs content release continuously during business hours.
 */
class ContinuousReleaseSubscriber implements EventSubscriberInterface {
  use RunsDuringBusinessHours;

  public const CONTINUOUS_RELEASE_ENABLED = 'va_gov_build_trigger.continuous_release_enabled';

  /**
   * The state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * The content release request service.
   *
   * @var \Drupal\va_gov_content_release\Request\RequestInterface
   */
  protected $requestService;

  /**
   * Constructs a new ContentReleaseIntervalSubscriber object.
   *
   * @param \Drupal\Core\State\StateInterface $state
   *   The state service.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   * @param \Drupal\va_gov_content_release\Request\RequestInterface $requestService
   *   The build requester service.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $dateFormatter
   *   The date formatter service.
   */
  public function __construct(StateInterface $state, TimeInterface $time, RequestInterface $requestService, DateFormatterInterface $dateFormatter) {
    $this->state = $state;
    $this->time = $time;
    $this->requestService = $requestService;
    $this->dateFormatter = $dateFormatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[ReleaseStateTransitionEvent::NAME] = 'releaseContinuously';
    return $events;
  }

  /**
   * Queue a new release during business hours and when enabled.
   *
   * @param \Drupal\va_gov_build_trigger\Event\ReleaseStateTransitionEvent $event
   *   The release state transition event.
   */
  public function releaseContinuously(ReleaseStateTransitionEvent $event) {
    $is_complete = ($event->getNewReleaseState() === ReleaseStateManager::STATE_COMPLETE);
    $is_enabled = $this->state->get(self::CONTINUOUS_RELEASE_ENABLED, FALSE);

    if ($is_complete && $is_enabled) {
      $this->runDuringBusinessHours(function () {
        $this->requestService->submitRequest('Continuous release');
      });
    }
  }

}
