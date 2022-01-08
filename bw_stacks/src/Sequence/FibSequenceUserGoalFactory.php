<?php

namespace BWStacks\Sequence;


use BWD\Stacks\ContentList\ContentListInterface;
use BWD\Stacks\Sequence\RepeatSequence;
use BWD\Stacks\Sequence\SequenceInterface;
use BWUser\User;

class FibSequenceUserGoalFactory  {
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
  public function build(User $user) : SequenceInterface {
    $sequence = new RepeatSequence();

    // Sequence based upon the user goal
    // internship: 1, 1, 2, 3, repeat
    // first job: 1, 1, 2, 3, 5, 8, repeat
    // next job: 1, 1, 2, 3, 5, 8, 13, 21, repeat

    $goal = $user->getGoal();

    //1, 2, 3, 5, 8, 13, 21, 34
    switch ($goal) {
      case 'summerinternship':
      case 'fallinternship':
        $sequence->addList($this->jobs, 1);
        $sequence->addList($this->articles, 1);
        $sequence->addList($this->jobs, 1);
        $sequence->addList($this->articles, 1);
        $sequence->addList($this->jobs, 2);
        $sequence->addList($this->articles, 1);
        $sequence->addList($this->jobs, 3);
        $sequence->addList($this->articles, 1);
        $sequence->setIterations(10);
        break;
      case 'nextjob':
        $sequence->addList($this->jobs, 1);
        $sequence->addList($this->articles, 1);
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
        $sequence->setIterations(2);
        break;
      case 'firstjob':
      default:
        $sequence->addList($this->jobs, 1);
        $sequence->addList($this->articles, 1);
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
        $sequence->setIterations(5);
    }

    return $sequence;
  }

}