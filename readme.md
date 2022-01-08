This is a code example which implements "Stacks"

Stacks is a library to allow stacking of different types of content from different storages in a dynamic nature.

The `bw_stacks/README.md` contains high level documentation for the Stacks library along with an example implementation.
The `bw_stacks\src\ContentList\FeedStackFactory` file contains the main factory class which was used to generate the a main Stack for an API.  The method `FeedStackFactory::build` generates a complciated Stack from several sub stacks.  Each sub stack is focused around business need rather than technical implementation.
The `bw_stacks\src\Report\ReportStackFactory` class provided a way to test the `FeedStackFactory` in different scenarios.
The dynamic building of stacks allows for dynamically changing what content a user will see on their feed.  This allows for A/B testing of algorithms.

* `stacks`: Base Library designed to be open sourced and re-usable
* `bw_stacks` Base implementation of the Stack library with global Classes for the project.
* 
