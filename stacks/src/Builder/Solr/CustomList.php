<?php

namespace BWD\Stacks\Builder\Solr;


use BWConfig\SolrManager;
use BWPlaylist\Playlist as PlayListNode;

/**
 * @deprecated
 */
class CustomList extends Solr {

  /**
   * @var \BWPlaylist\Playlist
   */
  private $playlist;

  /**
   * CustomStack constructor.
   * @param \BWPlaylist\Playlist $playlist
   */
  public function __construct(PlayListNode $playlist) {
    $this->playlist = $playlist;
  }

  /**
   * Build out the Solr query necessary
   *
   * @param array $filters
   *
   * @return \BWConfig\SolrManager
   */
  public function buildQuery(array $filters = []): SolrManager {
    $solrManager = parent::buildQuery($filters);

    // Negative_boosts_tids from playlist object.
    $playlist_negative_onet_ref = $this->playlist->getNegativeBoostOnetRef();
    if (!empty($playlist_negative_onet_ref)) {
      foreach ($playlist_negative_onet_ref as $tid) {
        $solrManager->addToBQ($solrManager->buildBoostQuery('sm_field_job_onet_ref', "*:* -taxonomy_term:{$tid}", 999, FALSE));
      }
    }

    // Positive boosts tids from playlist object.
    $playlist_positive_onet_ref = $this->playlist->getPositiveBoostOnetRef();
    if (!empty($playlist_positive_onet_ref)) {
      foreach ($playlist_positive_onet_ref as $tid) {
        $solrManager->addToBQ($solrManager->buildBoostQuery('sm_field_job_onet_ref', "taxonomy_term:{$tid}", 1.001, FALSE));
      }
    }

    // Positive boosts terms from playlist object.
    $playlist_positive_terms = $this->playlist->getPositiveTextTerms();
    if (!empty($playlist_positive_terms)) {
      foreach ($playlist_positive_terms as $term) {
        $solrManager->addToBQ($solrManager->buildBoostQuery(NULL, "$term", 1.001));
      }
    }

    // Negative boosts terms from playlist object.
    $playlist_negative_terms = $this->playlist->getNegativeTextTerms();
    if (!empty($playlist_negative_terms)) {
      foreach ($playlist_negative_terms as $term) {
        $solrManager->addToBQ($solrManager->buildBoostQuery(NULL, "*:* -$term", 999));
      }
    }

    // Build the search phrases for job titles.
    $solrManager->addToQ($this->buildQueryForPlaylist());
    $solrManager->addToFq($this->buildFilterQueryForPlaylist());

    return $solrManager;
  }

  /**
   * Build title terms to 'q'.
   *
   * @return string
   */
  protected function buildQueryForPlaylist() {
    $titleSearch = $this->playlist->getTitleSearch();
    if ($titleSearch) {
      return implode(' ', array_map(function ($term) {
        return strtolower($term);
      }, $titleSearch));
    }

    return '';
  }

  /**
   * Build filter query for playlist.
   *
   * @return array
   */
  protected function buildFilterQueryForPlaylist() {
    $result = [];

    $descSearch = $this->playlist->getDescSearch();
    if ($descSearch) {
      $result['content'] = implode(' OR ', array_map(function ($term) {
        return '"' . $term . '"';
      }, $descSearch));
    }

    $companyFilter = $this->playlist->getCompanyFilter();
    if ($companyFilter) {
      $tids = [];
      foreach ($companyFilter as $company) {
        if (isset($company->tid)) {
          $tids[] = '"' . 'taxonomy_term:' . $company->tid . '"';
        }
      }

      if (!empty($tids)) {
        $result['sm_field_job_company_ref'] = implode(' OR ', $tids);
      }
    }
    return $result;
  }

  /**
   * Get source, type or machine name of the list
   *
   * This will be used when tracking how a item as added
   * to a list
   *
   * @return string
   */
  public function source(): string {
    return 'custom';
  }
}
