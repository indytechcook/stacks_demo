<?php

use BWD\Stacks\ContentList\ContentListCollection;
use BWD\Stacks\ContentList\ContentListCollectionAdapter;
use BWD\Stacks\NewStackInterface;
use BWD\Stacks\Result\ResultCollection;
use BWDisplay\Input\IntegerInput;
use BWJob\LocationTaxonomy;
use BWJob\Stacks\ListOfDPJobsForUser;
use BWONET\Stacks\ListOfOnets;
use BWStacks\Alter\DrupalSelect\Taxonomy\CIPToOnets;
use BWStacks\ContentList\FeedStackFactory;
use BWStacks\Filter\FilterBuilder;
use BWStacks\Report\ReportStackFactory;
use BWStacks\StacksConfig;
use BWUser\User;

/**
 * Inputs to get from URL.
 * @return array
 */
function bw_stacks_inputs() {
  return [
    new IntegerInput('user_id'),
    new IntegerInput('run_feed_user'),
    new IntegerInput('show_feed_debug'),
  ];
}

/**
 * Debug tool form.
 *
 * @param $form
 *   Drupal variable.
 * @param $form_state
 *   Drupal variable.
 * @param \BWUser\User|NULL $user
 *   User object.
 * @param int $debug
 *   Debug value.
 *
 * @return mixed
 *   Form.
 */
function bw_stacks_admin_user_form($form, &$form_state, User $user = NULL, int $debug = NULL) {
  $form['user_id'] = [
    '#type' => 'textfield',
    '#title' => t('User Id'),
    '#default_value' => $user ? $user->getId() : NULL,
    '#prefix' => bw_config_form_prefix(),
    '#suffix' => bw_config_form_suffix(),
    '#required' => TRUE,
    '#attributes' => array(
      'class' => array('form-control'),
    ),
  ];

  $form['run_feed_user'] = [
    '#type' => 'checkbox',
    '#title' => t('Run feed for user'),
    '#default_value' => '1',
  ];

  $form['show_feed_debug'] = [
    '#type' => 'checkbox',
    '#title' => t('Show how feed was built'),
    '#default_value' => $debug,
  ];

  $form['submit'] = [
    '#type' => 'submit',
    '#value' => t('Submit'),
  ];

  return $form;
}

function bw_stacks_admin_user_form_submit($form, &$form_state) {
  $query = [
    'user_id' => $form_state['values']['user_id'] ?? NULL,
    'run_feed_user' => $form_state['values']['run_feed_user'] ?? NULL,
    'show_feed_debug' => $form_state['values']['show_feed_debug'] ?? NULL
  ];
  drupal_goto('admin/reports/stacks-user', ['query' => $query]);
}

/**
 * Page callback.
 *
 * @param null $user_id
 *   User Id.
 *
 * @return array
 *   Page.
 */
function bw_stacks_admin_user_page_callback() {
  // Get inputs.
  $process_inputs = new \BWDisplay\ProcessInputs();
  foreach (bw_stacks_inputs() as $input) {
    $process_inputs->addInput($input);
  }
  $input_values = $process_inputs->processInput(INPUT_GET);

  // Load User.
  $user = $input_values["user_id"] ? \BWUser\UserFactory::getUser($input_values["user_id"]) : NULL;

  $form = drupal_get_form('bw_stacks_admin_user_form', $user, $input_values["show_feed_debug"]);
  $return = [
    '#theme' => 'bw_collection_page',
    '#filter' => [
      '#theme' => 'bw_collection_filter',
      '#form' => drupal_render($form),
    ],
    '#table' => [
      '#theme' => 'bw_collection_table',
      '#items' => [],
      '#title' => 'Feed Jobs',
      '#table_id' => 'bw-stacks-report-user-table',
      '#header' => theme('bw_stacks_user_report_title'),
    ],
  ];

  // If user not loaded.
  if (!$user) {
    return $return;
  }

  // Get logger.
  $logger = bw_config_get_logger();
  if ($input_values["show_feed_debug"]) {
    $logger->setDebug(TRUE);
  }

  $userBusinessLogic = bw_stacks_admin_user_get_user_business_logic($user, $logger);
  $filteredBy = bw_stacks_admin_user_get_filtered_by($user);

  $return['#top'] = [
    '#theme' => 'bw_stacks_user_report_data',
    '#logentries_link' => $input_values["show_feed_debug"] ? l("Logentries", "https://logentries.com/app/b675a98a#/logs?last=Last%2020%20Minutes&log_q=where(%2Fstacks%2Fi)&now=" . time(), [
      'attributes' => [
        'target' => '_blank',
      ],
      'external' => TRUE,
      'absolute' => TRUE,
      'https' => TRUE,
    ]) : "",
    '#business_logic' => $userBusinessLogic,
    '#filtered_by' => $filteredBy,
  ];

  // Store items.
  $items = [];

  // Get feed stack result list and stats.
  if ($input_values["run_feed_user"]) {
    $results = bw_stacks_admin_user_build_stack($user, $logger);
    $job_stat_collection = bw_stacks_admin_user_get_job_stats($user, $logger);
    $article_stat_collection = bw_stacks_admin_user_get_article_stats($user, $logger);

    // Get result loader.
    $result_loader = StacksConfig::getResultItemLoader();

    /** @var \BWD\Stacks\Result\ResultItem $result */
    foreach ($results as $result) {
      $object = $result_loader->load($result);

      if (!$object || (!$object instanceof \BWJob\Job && !$object instanceof \BWArticle\Article)) {
        continue;
      }

      $stats = [];
      switch ($result->getType()) {
        case 'job':
          /** @var \BWStat\Stat $job_stat */
          foreach ($job_stat_collection as $job_stat) {
            if ($job_stat->getRefEntity()->getIdentifier() == $result->getId()) {
              $stats[$job_stat->getStatType()] = $job_stat->getStatType();
            }

          }
          break;

        case 'article':
          /** @var \BWStat\Stat $article_stat */
          foreach ($article_stat_collection as $article_stat) {
            if ($article_stat->getRefEntity()->getIdentifier() == $result->getId()) {
              $stats[$article_stat->getStatType()] = $article_stat->getStatType();
            }

          }
          break;
      }

      $items[] = [
        '#theme' => 'bw_stacks_user_report_row',
        '#id' => $result->getId(),
        '#content' => $object,
        '#result' => $result,
        '#stats' => $stats,
      ];
    }

    $return['#table']['#items'] = $items;
  }

  $return['#attached'] = bw_collection_set_datatable_assets('bw-stacks-report-user-table');
  $return['#attached']['css'][] = [
    'data' => drupal_get_path('module', 'bw_stacks') . '/css/bw_stacks.css',
    'group' => CSS_THEME,
  ];

  return $return;
}

/**
 * Build out the stack.
 *
 * @param \BWUser\User $user
 *   User object.
 * @param \BWMonolog\BWDLogger $logger
 *   Logger.
 *
 * @return \BWD\Stacks\Result\ResultCollection
 *   Result Collection.
 */
function bw_stacks_admin_user_build_stack(User $user, \BWMonolog\BWDLogger $logger) {
  $filters = new FilterBuilder($user);
  $factory = new FeedStackFactory($filters);
  $factory->setUser($user);
  $factory->setUseResultAlters(FALSE);
  $factory->setLogger($logger);

  $report_factory = new ReportStackFactory();
  $report_factory->setFeedStackFactory($factory);
  $report_factory->setUser($user);
  $stack = $report_factory->build();

  $results = new ResultCollection(StacksConfig::getResultItemLoader());
  $results->setReturnLoadedResults(FALSE);
  return $stack->list($results);
}

/**
 * Get all of the stats for this user's jobs.
 *
 * @param \BWUser\User $user
 *   User object.
 * @param \BWMonolog\BWDLogger $logger
 *   Logger.
 *
 * @return \BWD\Stacks\Result\ResultCollection
 *   Result Collection.
 */
function bw_stacks_admin_user_get_job_stats(User $user, \BWMonolog\BWDLogger $logger) : ResultCollection{

  $stats = [
    'vote',
    'viewedjob',
    'detail',
    'appliedjob',
    'interestedapplying',
    'viewedarticle',
    'accept_offer',
    'reject_offer',
    'favoritejob',
    'playlist',
  ];

  $listBuilder = new \BWStat\Stacks\ListOfStats();
  $listBuilder->setLogger($logger);
  /** @var \BWStacks\ContentList\DrupalSelect\NodeList $list */
  $list = $listBuilder->getList();
  $list->addAlter(new \BWStat\Stacks\Alter\DrupalSelect\StatTypeAlter($stats));
  $list->addAlter(new \BWStacks\Alter\DrupalSelect\Author($user));

  $results = new ResultCollection(StacksConfig::getResultItemLoader());
  $results->setReturnLoadedResults(TRUE);
  $results = $list->list($results);

  return $results;
}

/**
 * Get all of the stats for this user's jobs.
 *
 * @param \BWUser\User $user
 *   User object.
 * @param \BWMonolog\BWDLogger $logger
 *   Logger.
 *
 * @return \BWD\Stacks\Result\ResultCollection
 *   Result Collection.
 */
function bw_stacks_admin_user_get_article_stats(User $user, \BWMonolog\BWDLogger $logger) : ResultCollection{

  $stats = [
    'viewedarticle',
    'previewedarticle',
    'favoritearticle',
    'passedarticle',
  ];

  $listBuilder = new \BWStat\Stacks\ListOfStats();
  $listBuilder->setLogger($logger);
  /** @var \BWStacks\ContentList\DrupalSelect\NodeList $list */
  $list = $listBuilder->getList();
  $list->addAlter(new \BWStacks\Alter\DrupalSelect\Published());
  $list->addAlter(new \BWStat\Stacks\Alter\DrupalSelect\StatTypeAlter($stats));
  $list->addAlter(new \BWStacks\Alter\DrupalSelect\Author($user));

  $results = new ResultCollection(StacksConfig::getResultItemLoader());
  $results->setReturnLoadedResults(TRUE);
  $results = $list->list($results);

  return $results;
}

/**
 * Builds user business logic.
 *
 * @param \BWUser\User $user
 *   User object.
 * @param \BWMonolog\BWDLogger $logger
 *   Logger.
 *
 * @return array
 *   Array with values for user business logic.
 */
function bw_stacks_admin_user_get_user_business_logic(User $user, \BWMonolog\BWDLogger $logger): array {
  // Get user cips.
  $cipIds = $user->getCIPIds();

  $onet_list_builder = new ListOfOnets();
  $onet_list_builder->setSource($onet_list_builder->source());
  $onet_list_builder->setLogger($logger);
  $onet_list = $onet_list_builder->getList();
  $onet_list->addAlter(new CIPToOnets($cipIds));

  // Get results.
  $onets = new ResultCollection(StacksConfig::getResultItemLoader());
  $onets = $onet_list->list($onets);
  $onet_ids = count($onets) ? $onets->getIds() : [];

  // Get DP terms.
  $phrases = [];
  $dps = $user->getDegreePrograms();
  if (!empty($dps)) {
    foreach($dps as $dp) {
      foreach ($dp->field_taxonomy_search_phrases->value() as $phrase) {
        $phrases[$dp->getIdentifier()][] = $phrase;
      }
    }
  }

  $list_builder = new ListOfDPJobsForUser();
  $list_builder->setLogger($logger);
  $list_builder->setUser($user);
  $list_builder->setFilters(new FilterBuilder($user));
  $dp_list = $list_builder->getList();

  $results = new ResultCollection(StacksConfig::getResultItemLoader());
  $results->setReturnLoadedResults(TRUE);
  $results = $dp_list->list($results);
  $dp_ids = $results->getIds();

  $userBusinessLogic = [
    [
      'label' => 'User id',
      'value' => [
        '#theme' => 'bw_stacks_user_report_single_value',
        '#value' => l($user->getId(), "/user/{$user->getId()}/edit", [
          '#attributes' => [
            'target' => '_blank',
          ],
        ]),
      ],
    ],
    [
      'label' => 'Field of study',
      'value' => [
        '#theme' => 'bw_stacks_user_report_list',
        '#list' => $cipIds,
      ],
    ],
    [
      'label' => 'Field of study mapped to ONETs',
      'value' => [
        '#theme' => 'bw_stacks_user_report_list',
        '#list' => $onet_ids,
      ],
    ],
    [
      'label' => 'Field of study mapped to degree / program',
      'value' => [
        '#theme' => 'bw_stacks_user_report_list',
        '#list' => $dp_ids,
        '#list_class' => 'horizontal-list',
      ],
    ],
    [
      'label' => 'Search terms for degree / program',
      'value' => [
        '#theme' => 'bw_stacks_user_report_dp_phrases',
        '#phrases' => $phrases,
      ],
    ],
  ];

  return $userBusinessLogic;
}

/**
 * Builds filtered by for user.
 *
 * @param \BWUser\User $user
 *   User object.
 *
 * @return array
 *   Filtered by parameters builded.
 */
function bw_stacks_admin_user_get_filtered_by(User $user): array {
  // Get positively and negative Boosted ONETs.
  $dps = $user->getDegreePrograms();
  $positive_onets = [];
  $negative_onets = [];

  if (!empty($dps)) {
    foreach($dps as $dp) {

      $positive_onet_ref = $dp->field_dp_boost_onet_ref_positive->value();
      if (!empty($positive_onet_ref)) {
        foreach ($positive_onet_ref as $term) {
          $tid = $term->tid ?? NULL;
          if ($tid) {
            $positive_onets[] = $tid;
          }
        }
      }

      $negative_onet_ref = $dp->field_dp_boost_onet_ref_negative->value();
      if (!empty($negative_onet_ref)) {
        foreach ($negative_onet_ref as $term) {
          $tid = $term->tid ?? NULL;
          if ($tid) {
            $negative_onets[] = $tid;
          }
        }
      }

    }
  }

  $filteredBy = [
    [
      'label' => 'Location chosen',
      'value' => [
        '#theme' => 'bw_stacks_user_report_list',
        '#list' => $user->getLocationIds(),
      ],
    ],
    [
      'label' => 'Expanded Locations',
      'value' => [
        '#theme' => 'bw_stacks_user_report_list',
        '#list' => LocationTaxonomy::expandLocations($user->getLocationIds()),
        '#list_class' => 'horizontal-list',
      ],
    ],
    [
      'label' => 'Goal chosen',
      'value' => [
        '#theme' => 'bw_stacks_user_report_single_value',
        '#list' => $user->getGoalLabel(),
      ],
    ],
    [
      'label' => 'Minimum years of experience range',
      'value' => [
        '#theme' => 'bw_stacks_user_report_single_value',
        '#list' => $user->getExperienceLabel(),
      ],
    ],
    [
      'label' => 'Exluded ONETs',
      'value' => [
        '#theme' => 'bw_stacks_user_report_single_value',
        '#list' => $user->getExperienceLabel(),
      ],
    ],
    [
      'label' => 'Positively Boosted ONETs',
      'value' => [
        '#theme' => 'bw_stacks_user_report_list',
        '#list' => $positive_onets,
      ],
    ],
    [
      'label' => 'Negatively Boosted ONETs',
      'value' => [
        '#theme' => 'bw_stacks_user_report_list',
        '#list' => $negative_onets,
      ],
    ],
  ];

  return $filteredBy;
}
