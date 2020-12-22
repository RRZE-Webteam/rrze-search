# RRZE-Search

Adds additional search engines to the wordpress default search. 

## Download 

GitHub-Repo: https://github.com/RRZE-Webteam/rrze-search


## Autor 
RRZE-Webteam , http://www.rrze.fau.de

## Copyright

GNU General Public License (GPL) Version 3


## Purpuse 

Implements additional `Search Engine` resources in the form of an Adapter Class, which extends Foundational Classes. 
That is to say, that the Google Foundational class enabled the use of the Google Customer Search feature and the 
Adapter classes hold information in utilization, i.e. Name, Labels and API Keys and IDs.

## Dependencies

This plugin was optimized for the FAU Einrichtungen theme (https://github.com/RRZE-Webteam/FAU-Einrichtungen).


## Add Additional Search Engine Resources
- Duplicate `SearchEngine-Class.php` from `RRZESearch\Infrastructure\Engines\Templates`
- Refactor the `ClassName`
- Duplicate `SearchEngine-Adapter.php` from the same directory.
- Refactor the `AdapterClassName`
- Update `query` function according to its Search Engine's requirements.
