<?php

namespace Drupal\bank_consult_queue\Plugin\QueueWorker;

/**
 * A Cron Taxonomy Worker that process terms on CRON run.
 *
 * @QueueWorker(
 *   id = "cron_taxonomy_worker",
 *   title = @Translation("Cron Taxonomy Worker"),
 *   cron = {"time" = 10}
 * )
 */
class CronTaxonomyWorker extends BankConsultQueueBasePlugin {}