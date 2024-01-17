<?php
/**
 * @file
 * Contains \Drupal\bank_consult_queue\Form\BankConsultQueueForm.
 */

namespace Drupal\bank_consult_queue\Form;

use Drupal\bank_consult_queue\demo\Phone;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Queue\QueueFactory;
use Drupal\Core\Queue\QueueInterface;
use Drupal\Core\Queue\QueueWorkerInterface;
use Drupal\Core\Queue\QueueWorkerManagerInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BankConsultQueueForm extends FormBase {

  /**
   * @var QueueFactory
   */
  protected QueueFactory $queueFactory;

  /**
   * @var QueueWorkerManagerInterface
   */
  protected QueueWorkerManagerInterface $queueManager;


  /**
   * {@inheritdoc}
   */
  public function __construct(QueueFactory $queue, QueueWorkerManagerInterface $queue_manager) {
    $this->queueFactory = $queue;
    $this->queueManager = $queue_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('queue'),
      $container->get('plugin.manager.queue_worker')
    );
  }

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'demo_form';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $phone = Phone::create(['1,2,3,4']);
    /** @var \Drupal\Core\DependencyInjection\ClassResolver $class_resolver */
    $class_resolver = \Drupal::service('class_resolver');
    $instance =  $class_resolver->getInstanceFromDefinition(Phone::class);

    $queue = $this->queueFactory->get('cron_node_publisher');

    $form['help'] = array(
      '#type' => 'markup',
      '#markup' => $this->t('Submitting this form will process the Manual Queue which contains @number items.', array('@number' => $queue->numberOfItems())),
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Process queue'),
      '#button_type' => 'primary',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** @var QueueInterface $queue */

    /** @var \Drupal\node\NodeInterface $node */
    $node = Node::load(1);
    $node->
    $queue = $this->queueFactory->get('manual_node_publisher');
    /** @var QueueWorkerInterface $queue_worker */
    $queue_worker = $this->queueManager->createInstance('manual_node_publisher');

    while($item = $queue->claimItem()) {
      try {
        $queue_worker->processItem($item->data);
//        $queue->deleteItem($item);
      }
      catch (\Exception $e) {
        $queue->releaseItem($item);
        break;
      }
      catch (\Exception $e) {
//        watchdog_exception('npq', $e);
      }
    }
  }
}