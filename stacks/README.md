# Stacks

A Stack is a listing of content or group of listings of content.

## Definitions
* **Stack**: A list content ids.  A Stack can contain one or many "Lists".  A Stack is also a logical collection of ids.  A Stack itself is created by the StackFactory.  Stacks are the public accessible layer to the Stacks library.
* **ContentList**: A grouping of content ids.  A List itself is created within the StackFactory which uses a ListBuilder along with alters.  Example lists include "Matches from BG", "Similar to to Job Seeker Degree Programs" and CIP based jobs.  The lists also answers the questions "What was this job shown to the Job Seeker and why?"
* **Alter**:  An alter manipulates the response of the List.  They allow for user input to change the results.  There are three type of Alters:  SelectAlter (for databases), SolrAlter (for a solr query) and ResultAlter (to alter the finals result array).
* **ContentListCollection**:  The ListCollection class is a type of Iterator with combines different lists.  The ListCollection is used in the ListCollectionAdapter (which is a type of List).
* **ResultItem**: A result which represents a single piece of content.
* **ResultItemLoader**: Turns a ResultItem to an object using a `ResultFactory`
* **ResultFactory**: Loads the object from the system.
* **ResultCollection**: An iterator which allow collection of ResultItems to be stored and reordered.
* **ResultLoaderProvider**: Contains a registry of the `ResultFactory`.
* **Sequence**: Contains multiple `ContentLists` together in custom ways.  A sequence implments the `ContentListInterface` and can be used anywhere it is used.
* **ListOf**: A factory class to generate ContentLists for different types fo content.
* **Adapters**

* **CacheAdapters**:  Stacks uses the decorator pattern to add caching to different levels of the library.  Types of Caching adapaters include DrupalCacheAdapter (Stack/List) and DrupalCacheResultAlterAdapter (Result Alter)

## Sequences Example

How to get a list of jobs filtered by a goal with a list of articles.

SimpleSequence with 3 jobs, 1 article, 5 jobs 1 article.

```
// Jobs
$jobs_builder = new BWD\Stacks\ListOf\ListOfJobs('manual');
$list_of_jobs = $jobs_builder->getList();

$list_of_jobs->addAlter(new BWD\Stacks\Alter\DrupalSelect\Goal('firstjob'));

// Articles
$article_builder = new BWD\Stacks\ListOf\ListOfArticles('manual');
$list_of_articles = $article_builder->getList();

// Start a sequence
$sequence = new BWD\Stacks\SimpleSequence\SimpleSequence();
$sequence->addList($list_of_jobs, 3);
$sequence->addList($list_of_articles, 1);
$sequence->addList($list_of_jobs, 5);
$sequence->addList($list_of_articles, 1);

// Results
$results = new BWD\Stacks\Result\ResultCollection();

// Either iterate through the result nodes

$results = $sequence->buildResultCollection($results);
foreach ($results as $node) {
  //each node is an object which depends the list
}


// OR make it a "list" which is a stack itself.

$sequence_list = new BWD\Stacks\SimpleSequence\SequenceAdapter($sequence);
$results = $sequence_list->list($results);

foreach ($results as $node) {
  //each node is an object which depends the list
}

// Wrap the list in a cache
$cache = new DrupalCacheAdapter($sequence_list, 'cache_id');
$cache->list();

```
