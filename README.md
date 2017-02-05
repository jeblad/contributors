# contributors

These is the readme file for the Contributors extension.

Extension page on mediawiki.org: https://www.mediawiki.org/wiki/Extension:Contributors
Latest version of the readme file: https://gerrit.wikimedia.org/r/gitweb?p=mediawiki/extensions/Contributors.git;a=blob;f=README

== About ==

Library containing a PHP implementation of the Contributors extension

This extension analyzes the individual contributions according to a few factors.
* Number of added, removed and changed characters
* Number of added, removed and changed triplets
* Number of added, removed and changed entrophy

Individual entries are spread over a vector before before being accumulated in bins. The values in those bins
are then used for calculating total additions, removals and changes.
