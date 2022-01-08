<?php

namespace BWStacks\Sequence;


use BWD\Stacks\ContentList\ContentListInterface;
use BWD\Stacks\Sequence\SequenceInterface;
use BWD\Stacks\Sequence\SimpleSequence;

class FibSequenceFactory {
  /**
   * @var ContentListInterface
   */
  private $jobs;
  /**
   * @var ContentListInterface
   */
  private $articles;

  /**
   * @param ContentListInterface $jobs
   */
  public function setJobs(ContentListInterface $jobs) {
    $this->jobs = $jobs;
  }

  /**
   * @param mixed $articles
   */
  public function setArticles(ContentListInterface $articles) {
    $this->articles = $articles;
  }

  /**
   * Build the Sequence Object between articles and jobs
   */
  public function build() : SequenceInterface {
    $sequence = new SimpleSequence();

    //1, 2, 3, 5, 8, 13, 21, 34
    $sequence->addList($this->jobs, 1);
    $sequence->addList($this->articles, 1);
    $sequence->addList($this->jobs, 2);
    $sequence->addList($this->articles, 1);
    $sequence->addList($this->jobs, 3);
    $sequence->addList($this->articles, 1);
    $sequence->addList($this->jobs, 5);
    $sequence->addList($this->articles, 1);
    $sequence->addList($this->jobs, 8);
    $sequence->addList($this->articles, 1);
    $sequence->addList($this->jobs, 13);
    $sequence->addList($this->articles, 1);
    $sequence->addList($this->jobs, 21);
    $sequence->addList($this->articles, 1);
    $sequence->addList($this->jobs, 34);
    $sequence->addList($this->articles, 1);
    $sequence->addList($this->jobs);

    return $sequence;
  }

}