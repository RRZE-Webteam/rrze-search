# RRZESearch

WordPress-Plugin für zentrale Einrichtungen der Friedrich-Alexander-Universität Erlangen-Nürnberg (FAU)

Implements additional `Search Engine` resources.

TODO:
- Documentation
- Author
- Copyright
- Feedback


## Appendix

Special instructions and other cataloged information

`Adding Search Engine`
- Duplicate `SearchEngine-template.php` from `RRZESearch\Ports\Engines\`
- Refactor the `Classname`
- Define the Search Engine's Parameters and Filters
- Recommended use of `cURL` to make request
- Return `json` \stdClass

 
---


`Designating Disclaimer Page`
- From the `WordPress Admin` go to `Pages`.
- Enter `Edit Mode` and expand the `Screen Options` Panel.
- Ensure `Custom Fields` is `selected`.
- Close `Screen Options` Panel. **__*Optional*__
- Add Custom Field `rrze_search_resource_disclaimer`.
- Set Custom Field value to `true`.
- `Return to Pages` and `Repeat Process`.

 
---
