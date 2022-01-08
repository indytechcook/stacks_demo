<?php

namespace BWD\Stacks\Factory;


use BWConfig\ConfigNodeException;
use BWConfig\ConfigNodeFactory;
use BWD\Stacks\StackInterface;
use BWUser\User;

/**
 * @deprecated
 */
class StackFactory {
  /**
   * @var array
   */
  private $filters;
  /**
   * @var \BWUser\User
   */
  private $user;

  /**
   * PlaylistFactory constructor.
   * @param array $filters
   * @param \BWUser\User $user
   */
  public function __construct(array $filters = [], User $user = NULL) {
    $this->filters = $filters;
    $this->user = $user;
  }

  /**
   * Load the playlist class
   *
   * @param $type
   *
   * @param bool $cache
   * @return \BWD\Stacks\StackInterface
   */
  public function build($type, bool $cache = FALSE): StackInterface {
    if (is_numeric($type)) {
      // Custom Playlist
      try {
        $playlist_node = ConfigNodeFactory::createByNid($type, 'playlist');
      }
      catch (ConfigNodeException $e) {
        watchdog('BW_JOB', $e->getMessage());
        drupal_set_message(t('Error loading Jobs'));

        return new EmptyStack();
      }

      $factory = new CustomPlaylistStackFactory($playlist_node, $this->user);
    }
    else {
      // If user is null then force search stack
      if (!$this->user) {
        $type = 'search';
      }

      switch ($type) {
        case 'suggest':
          $factory = new SuggestStackFactory($this->user, $cache);
          break;
        case 'search':
        default:
          $factory = new SearchStackFactory($this->user, $cache);
          break;
      }
    }

    return $factory->build($this->filters, $cache);
  }
}