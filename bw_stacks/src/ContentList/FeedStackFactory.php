<?php

namespace BWStacks\ContentList;


use BWArticle\Stacks\Alter\DrupalSelect\ArticleOrder;
use BWArticle\Stacks\ListOfArticles;
use BWArticle\Stacks\ListOfArticlesForUser;
use BWArticle\Stacks\ListOfCIPArticles;
use BWArticle\Stacks\ListOfCompanyArticles;
use BWArticle\Stacks\ListOfDPArticles;
use BWConfig\ConfigTaxonomyFactory;
use BWD\Stacks\ContentList\ContentListCollection;
use BWD\Stacks\ContentList\ContentListCollectionAdapter;
use BWD\Stacks\ContentList\ContentListInterface;
use BWD\Stacks\NewStackInterface;
use BWD\Stacks\Sequence\RepeatSequence;
use BWD\Stacks\Sequence\SequenceAdapter;
use BWD\Stacks\StacksLoggerTrait;
use BWJob\Stacks\ListOfCIPJobsForUser;
use BWJob\Stacks\ListOfDPJobsForUser;
use BWJob\Stacks\ListOfJobsCustomPlaylist;
use BWJob\Stacks\ListOfLensJobs;
use BWStacks\Alter\DrupalSelect\Range;
use BWStacks\Alter\Solr\Limit;
use BWStacks\ContentList\DrupalSelect\NodeList;
use BWStacks\DrupalCacheAdapter;
use BWStacks\Filter\FilterBuilder;
use BWStacks\Result\JobResultAlterTrait;
use BWStacks\Sequence\FibSequenceFactory;
use BWStacks\Sequence\FibSequenceUserGoalFactory;
use BWUser\User;
use Monolog\Logger;

class FeedStackFactory {
  use JobResultAlterTrait;
  use StacksLoggerTrait;

  /**
   * @var FilterBuilder
   */
  private $filter_builder;
  /**
   * @var User
   */
  private $user;

  /**
   * @var bool
   */
  private $use_result_alters = TRUE;


  /**
   * ContentListFactory constructor.
   * @param $filter_builder
   */
  public function __construct($filter_builder) {
    $this->filter_builder = $filter_builder;
  }

  /**
   * @param User $user
   */
  public function setUser(User $user) {
    $this->user = $user;
  }

  /**
   * Build out the Stack
   *
   * @return NewStackInterface
   */
  public function build() : NewStackInterface {
    // Build out Jobs list
    $jobs_list = $this->buildJobList();

    // Build out article list
    $article_list = $this->buildArticleList();

    // Build sequence list
    $seq_builder = new FibSequenceUserGoalFactory();
    $seq_builder->setArticles($article_list);
    $seq_builder->setJobs($jobs_list);
    $sequence = $seq_builder->build($this->user);

    return new SequenceAdapter($sequence);
  }

  /**
   * Build a ContentList
   */
  public function buildJobList() : NewStackInterface {
    // Custom playlist.
    $list_builder = new ListOfJobsCustomPlaylist($this->user);
    $list_builder->setLogger($this->getLogger());
    $list_builder->setFilters($this->filter_builder);
    $custom_list = $list_builder->getList();


    // Cip playlist.
    // @TODO Add filter layer to ListOf just like solr
    $list_builder = new ListOfCIPJobsForUser();
    $list_builder->setLogger($this->getLogger());
    $list_builder->setUser($this->user);
    $list_builder->setFilters($this->filter_builder);
    $list_builder->setIncludeStatAlters($this->useResultAlters());
    $cip_list = $list_builder->getList();

    // lens matches
    $list_builder = new ListOfLensJobs();
    $list_builder->setLogger($this->getLogger());
    $list_builder->setUser($this->user);
    $list_builder->setFilters($this->filter_builder);
    $list_builder->setIncludeStatAlters($this->useResultAlters());
    $lens_list = $list_builder->getList();

    // degree program
    $list_builder = new ListOfDPJobsForUser();
    $list_builder->setLogger($this->getLogger());
    $list_builder->setUser($this->user);
    $list_builder->setFilters($this->filter_builder);
    $dp_list = $list_builder->getList();

    $list_collection = new ContentListCollection();
    $list_collection->addList($custom_list);
    $list_collection->addList($cip_list);
    $list_collection->addList($lens_list);
    $list_collection->addList($dp_list);

    $list = new ContentListCollectionAdapter($list_collection);
    $list->setLogger($this->getLogger());

    //$list = new DrupalCacheAdapter($list, 'stacks_jobs_' . $this->user->getId());
    // Add result alters after the cache adapter
    if ($this->useResultAlters()) {
      $this->addJobResultAlters($list);
    }

    return $list;
  }

  /**
   * Build a list of Articles
   *
   * @return NewStackInterface
   */
  public function buildArticleList() : NewStackInterface {
    // Preferred Employer for Org
    $companies = [];
    $org = $this->user->getOrg();
    $company_ids = $org->getPreferredCompanyIds();
    foreach ($company_ids as $company_id) {
      $companies[$company_id] = ConfigTaxonomyFactory::createByTid($company_id, 'company');
    }

    // Preferred Employer for User
    $companies += $this->user->getPreferredCompanies();

    $list_builder = new ListOfCompanyArticles($companies);
    $list_builder->setLogger($this->getLogger());
    $list_builder->setIncludeStatAlters($this->useResultAlters());
    $list_builder->setUser($this->user);
    $company_list = $list_builder->getList();

    // Mapped to Users's Field of Study
    $list_builder = new ListOfCIPArticles($this->user->getCIPObjs());
    $list_builder->setLogger($this->getLogger());
    $list_builder->setIncludeStatAlters($this->useResultAlters());
    $list_builder->setUser($this->user);
    $cip_list = $list_builder->getList();

    // Mapped to the User's DP
    $dps = [];
    foreach ($this->user->getDegreeProgramTids() as $id) {
      $dps[$id] = ConfigTaxonomyFactory::createByTid($id);
    }

    $list_builder = new ListOfDPArticles($dps);
    $list_builder->setLogger($this->getLogger());
    $list_builder->setIncludeStatAlters($this->useResultAlters());
    $list_builder->setUser($this->user);
    $dp_list = $list_builder->getList();

    // All other articles
    // This would show duplicates of the above but this will be removed in another ticket
    $list_builder = new ListOfArticles();
    /** @var NodeList $all_list */
    $all_list = $list_builder->getList();
    $all_list->addAlter(new ArticleOrder(ArticleOrder::DESC));

    $list_collection = new ContentListCollection();
    $list_collection->addList($company_list);
    $list_collection->addList($cip_list);
    $list_collection->addList($cip_list);
    $list_collection->addList($dp_list);
    $list_collection->addList($all_list);

    $list = new ContentListCollectionAdapter($list_collection);
    $list->setLogger($this->getLogger());

    return $list;
  }

  /**
   * @return bool
   */
  public function useResultAlters() : bool {
    return $this->use_result_alters;
  }

  /**
   * @param bool $use_result_alters
   */
  public function setUseResultAlters(bool $use_result_alters) {
    $this->use_result_alters = $use_result_alters;
  }

}
