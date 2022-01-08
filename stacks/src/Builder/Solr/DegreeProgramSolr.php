<?php

namespace BWD\Stacks\Builder\Solr;


use BWConfig\SolrManager;
use EntityListWrapper;

/**
 * @deprecated
 */
class DegreeProgramSolr extends Solr {
  const JOB_SOURCE_SOLR = 1;

  /**
   * @var EntityListWrapper
   */
  private $degree_programs;

  /**
   * DegreeProgramSolr constructor.
   *
   * @param \EntityListWrapper $degree_programs
   */
  public function __construct(EntityListWrapper $degree_programs) {
    $this->degree_programs = $degree_programs;
  }

  /**
   * Build out the Solr query necessary
   *
   * @param array $filters
   * @return \BWConfig\SolrManager
   */
  public function buildQuery(array $filters = []): SolrManager {
    $solrManager = parent::buildQuery($filters);

    // Add the Degree Program boosts
    // Merge filters of degreeprograms chosen by user.
    foreach ($this->degree_programs as $program) {
      if ($program->field_taxonomy_job_source->value() == self::JOB_SOURCE_SOLR) {
        // Build the search phrases for job titles.
        $solrManager->addToQ($this->buildQueryForProgram($program));
        // Build the search phrases for job descriptions.
        $solrManager->addToFq($this->buildAdditionalFilterQueryForProgram($program));
      }

      // Positive ONET tids from degreeprogram.
      $positive_onet_ref = $program->field_dp_boost_onet_ref_positive->value();
      if (!empty($positive_onet_ref)) {
        foreach ($positive_onet_ref as $term) {
          $tid = $term->tid ?? NULL;
          if ($tid) {
            $solrManager->addToBQ($solrManager->buildBoostQuery('sm_field_job_onet_ref', "taxonomy_term:{$tid}", 1.001, FALSE));
          }
        }
      }

      // Negative ONET tids from degreeprogram.
      $negative_onet_ref = $program->field_dp_boost_onet_ref_negative->value();
      if (!empty($negative_onet_ref)) {
        foreach ($negative_onet_ref as $term) {
          $tid = $term->tid ?? NULL;
          if ($tid) {
            $solrManager->addToBQ($solrManager->buildBoostQuery('sm_field_job_onet_ref', "*:* -taxonomy_term:{$tid}", 999, FALSE));
          }
        }
      }

      // Positive boosts terms from degreeprogram.
      $dp_positive_terms = $program->field_taxonomy_dp_text_positive->value();
      if (!empty($dp_positive_terms)) {
        foreach ($dp_positive_terms as $term) {
          $solrManager->addToBQ($solrManager->buildBoostQuery(NULL, "$term", 1.001));
        }
      }

      // Negative boosts terms from degreeprogram.
      $dp_negative_terms = $program->field_taxonomy_dp_text_negative->value();
      if (!empty($dp_negative_terms)) {
        foreach ($dp_negative_terms as $term) {
          $solrManager->addToBQ($solrManager->buildBoostQuery(NULL, "*:* -$term", 999));
        }
      }
    }

    return $solrManager;
  }

  /**
   * Builds query string for program on job title, search strings are ORed.
   *
   * @param $program
   *
   * @return string
   */
  protected function buildQueryForProgram($program) {
    $phrases = [];
    foreach ($program->field_taxonomy_search_phrases->value() as $phrase) {
      $phrases[] = $phrase;
    }

    if (!$phrases) {
      $phrases[] = strtolower($program->name->value());
    }

    // Reduce the number of terms on the degree program.
    if (count($phrases) >= 5) {
      $phrases = array_slice($phrases, 0, 5);

    }

    $q = implode(' ', $phrases);

    return $q;
  }

  /**
   * Builds query string for program on job description, search strings are ORed.
   *
   * @param $program
   *   DegreeProgram.
   *
   * @return string
   *    Filter query.
   */
  protected function buildAdditionalFilterQueryForProgram($program) {
    $phrases = [];
    foreach ($program->field_taxonomy_search_desc->value() as $phrase) {
      $phrases[] = '"' . $phrase . '"';
    }

    if ($phrases) {
      return ['content' => implode(' OR ', $phrases)];
    }
    else {
      return [];
    }
  }

  /**
   * Get source, type or machine name of the stack
   *
   * This will be used when tracking how a item as added
   * to a playlist
   *
   * @return string
   */
  public function source(): string {
    return 'degreeprogram';
  }


}