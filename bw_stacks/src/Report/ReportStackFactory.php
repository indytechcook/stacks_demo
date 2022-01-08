<?php

namespace BWStacks\Report;

use BWD\Stacks\NewStackInterface;
use BWD\Stacks\Sequence\RepeatSequence;
use BWD\Stacks\Sequence\SequenceAdapter;
use BWStacks\ContentList\FeedStackFactory;
use BWStacks\Sequence\FibSequenceFactory;
use BWStacks\Sequence\FibSequenceUserGoalFactory;
use BWUser\User;

class ReportStackFactory {

  /**
   * @var FeedStackFactory
   */
  private $feed_stack_factory;

  /**
   * @var \BWUser\User
   */
  private $user;

  public function build() : NewStackInterface{

    // Get all items without alter
    $jobs_list = $this->feed_stack_factory->buildJobList();
    $article_list = $this->feed_stack_factory->buildArticleList();

    // Build sequence list
    $seq_builder = new FibSequenceUserGoalFactory();
    $seq_builder->setArticles($article_list);
    $seq_builder->setJobs($jobs_list);
    $sequence = $seq_builder->build($this->user);

    return new SequenceAdapter($sequence);

  }

  /**
   * @param mixed $feed_stack_factory
   */
  public function setFeedStackFactory(FeedStackFactory $feed_stack_factory) {
    $this->feed_stack_factory = $feed_stack_factory;
  }

  /**
   * @param User $user
   */
  public function setUser(User $user) {
    $this->user = $user;
  }
}