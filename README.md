# RRZESearch

WordPress-Plugin für zentrale Einrichtungen der Friedrich-Alexander-Universität Erlangen-Nürnberg (FAU)

Implements additional `Search Engine` resources in the form of an Adapter Class, which extends Foundational Classes. 
That is to say, that the Google Foundational class enabled the use of the Google Customer Search feature and the 
Adapter classes hold information in utilization, i.e. Name, Labels and API Keys and IDs.


## Add Additional Search Engine Resources
- Duplicate `SearchEngine-Class.php` from `RRZESearch\Infrastructure\Engines\Templates`
- Refactor the `ClassName`
- Duplicate `SearchEngine-Adapter.php` from the same directory.
- Refactor the `AdapterClassName`
- Update `query` function according to its Search Engine's requirements.
