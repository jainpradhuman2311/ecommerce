<?php

namespace Drupal\advancedqueue\Entity;

use Drupal\advancedqueue\BackendPluginCollection;
use Drupal\advancedqueue\Exception\InvalidBackendException;
use Drupal\advancedqueue\Job;
use Drupal\advancedqueue\Plugin\AdvancedQueue\Backend\SupportsDetectingDuplicateJobsInterface;
use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Defines the queue entity class.
 *
 * @ConfigEntityType(
 *   id = "advancedqueue_queue",
 *   label = @Translation("Queue"),
 *   label_collection = @Translation("Queues"),
 *   label_singular = @Translation("queue"),
 *   label_plural = @Translation("queues"),
 *   label_count = @PluralTranslation(
 *     singular = "@count queue",
 *     plural = "@count queues",
 *   ),
 *   handlers = {
 *     "access" = "Drupal\advancedqueue\QueueAccessControlHandler",
 *     "list_builder" = "Drupal\advancedqueue\QueueListBuilder",
 *     "form" = {
 *       "add" = "Drupal\advancedqueue\Form\QueueForm",
 *       "edit" = "Drupal\advancedqueue\Form\QueueForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "advancedqueue_queue",
 *   admin_permission = "administer advancedqueue",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "backend",
 *     "backend_configuration",
 *     "processor",
 *     "processing_time",
 *     "threshold",
 *     "locked",
 *     "stop_when_empty",
 *   },
 *   links = {
 *     "add-form" = "/admin/config/system/queues/add",
 *     "edit-form" = "/admin/config/system/queues/manage/{advancedqueue_queue}",
 *     "delete-form" = "/admin/config/system/queues/manage/{advancedqueue_queue}/delete",
 *     "collection" =  "/admin/config/system/queues"
 *   }
 * )
 */
class Queue extends ConfigEntityBase implements QueueInterface {

  /**
   * The queue ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The queue label.
   *
   * @var string
   */
  protected $label;

  /**
   * The queue backend plugin ID.
   *
   * @var string
   */
  protected $backend;

  /**
   * The queue backend plugin configuration.
   *
   * @var array
   */
  protected $backend_configuration = [];

  /**
   * The selected processor.
   *
   * One of the QueueInterface::PROCESSOR_ constants.
   *
   * @var string
   */
  protected $processor = self::PROCESSOR_CRON;

  /**
   * The processing time, in seconds.
   *
   * @var int
   */
  protected $processing_time = 90;

  /**
   * Determine the type of queue cleanup threshold.
   *
   * @var array
   */
  protected $threshold = [];

  /**
   * Whether the queue is locked, indicating that it cannot be deleted.
   *
   * @var bool
   */
  protected $locked = FALSE;

  /**
   * The plugin collection that holds the backend plugin.
   *
   * @var \Drupal\advancedqueue\BackendPluginCollection
   */
  protected $pluginCollection;

  /**
   * Whether the processor should stop when the queue is empty.
   *
   * @var bool
   */
  protected $stop_when_empty = TRUE;

  /**
   * {@inheritdoc}
   */
  public function enqueueJob(Job $job, $delay = 0) {
    $job = $this->prepareJob($job);
    return $this->getBackend()->enqueueJob($job, $delay);
  }

  /**
   * {@inheritdoc}
   */
  public function enqueueJobs(array $jobs, $delay = 0) {
    $prepared_jobs = [];
    foreach ($jobs as $job) {
      $prepared_jobs[] = $this->prepareJob($job);
    }
    return $this->getBackend()->enqueueJobs($prepared_jobs, $delay);
  }

  /**
   * Prepare a job for enqueueing.
   *
   * @param \Drupal\advancedqueue\Job $job
   *   The job to prepare.
   *
   * @return \Drupal\advancedqueue\Job
   *   A job to enqueue. This may or may not be the $job object supplied.
   *
   * @throws \Drupal\advancedqueue\Exception\InvalidBackendException
   *   Throws an exception if the queue's backend cannot handle the given job.
   */
  protected function prepareJob(Job $job): Job {
    $job->setQueueId($this->id());
    $job_type_manager = \Drupal::service('plugin.manager.advancedqueue_job_type');
    if (!$job_type_manager->getDefinition($job->getType())['allow_duplicates']) {
      if (!$this->getBackend() instanceof SupportsDetectingDuplicateJobsInterface) {
        throw new InvalidBackendException(strtr("Backend :type doesn't support detecting duplicate jobs", [':type' => $this->getBackend()->getLabel()]));
      }

      $job_type_plugin = $job_type_manager->createInstance($job->getType());
      if (empty($job->getFingerprint())) {
        $job->setFingerprint($job_type_plugin->createJobFingerprint($job));
      }

      if ($duplicates = $this->getBackend()->getDuplicateJobs($job)) {
        $job = $job_type_plugin->handleDuplicateJobs($job, $duplicates, $this->getBackend());
      }
    }
    return $job;
  }

  /**
   * {@inheritdoc}
   */
  public function getBackend() {
    return $this->getBackendCollection()->get($this->backend);
  }

  /**
   * {@inheritdoc}
   */
  public function getBackendId() {
    return $this->backend;
  }

  /**
   * {@inheritdoc}
   */
  public function setBackendId($backend_id) {
    $this->backend = $backend_id;
    $this->backend_configuration = [];
    $this->pluginCollection = NULL;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getBackendConfiguration() {
    return $this->backend_configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function setBackendConfiguration(array $configuration) {
    $this->backend_configuration = $configuration;
    $this->pluginCollection = NULL;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getProcessor() {
    return $this->processor;
  }

  /**
   * {@inheritdoc}
   */
  public function setProcessor($processor) {
    $this->processor = $processor;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getProcessingTime() {
    return $this->processing_time;
  }

  /**
   * {@inheritdoc}
   */
  public function setProcessingTime($processing_time) {
    $this->processing_time = $processing_time;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getThreshold() {
    return $this->threshold;
  }

  /**
   * {@inheritdoc}
   */
  public function setThreshold(array $threshold) {
    $this->threshold = $threshold;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isLocked() {
    return (bool) $this->locked;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginCollections() {
    return [
      'backend_configuration' => $this->getBackendCollection(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function set($property_name, $value) {
    // Invoke the setters to clear related properties.
    if ($property_name == 'backend') {
      $this->setBackendId($value);
      return $this;
    }
    elseif ($property_name == 'backend_configuration') {
      $this->setBackendConfiguration($value);
      return $this;
    }
    else {
      return parent::set($property_name, $value);
    }
  }

  /**
   * Gets the backend plugin collection.
   *
   * Ensures the plugin collection is initialized before returning it.
   *
   * @return \Drupal\advancedqueue\BackendPluginCollection
   *   The backend plugin collection.
   */
  protected function getBackendCollection() {
    if (!$this->pluginCollection) {
      $plugin_manager = \Drupal::service('plugin.manager.advancedqueue_backend');
      $this->pluginCollection = new BackendPluginCollection($plugin_manager, $this->backend, $this->backend_configuration, $this->id);
    }
    return $this->pluginCollection;
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(EntityStorageInterface $storage, $update = TRUE) {
    parent::postSave($storage, $update);

    if (!$update) {
      $this->getBackend()->createQueue();
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function postDelete(EntityStorageInterface $storage, array $entities) {
    parent::postDelete($storage, $entities);

    foreach ($entities as $entity) {
      $entity->getBackend()->deleteQueue();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getStopWhenEmpty() {
    return $this->stop_when_empty;
  }

  /**
   * {@inheritdoc}
   */
  public function setStopWhenEmpty(bool $stop_when_empty) {
    $this->stop_when_empty = $stop_when_empty;
    return $this;
  }

}
