# Graphql Search API

This Drupal module provides an integration between [Drupal GraphQL](https://github.com/drupal-graphql/graphql) and 
[Search API Solr](https://www.drupal.org/project/search_api_solr) modules.

It allows developers to perform queries directly to a Search API index by leveraging the Search API module's API.

Since it uses Search API it's not backend specific and can work with any engine such as Apache Solr or Elastic Search. 
Check the [Development status](development-status.md) page to understand where this module is at in terms of development 
and how you can help make it better.

## Why? 

This module was designed specifically for decoupled architectures.

For frontend applications that want to leverage search engines like Apache Solr to perform searches, the only current 
viable approaches are either to use the Views module or write your own little wrapper to expose the search engine's 
functionality.

While Views has GraphQL support, it puts a lot of constraints in the frontend since it defines in the backend things like 
filtering, pagination and other things that should in theory belong to the frontend. On the other hand writing your own 
wrapper means you don't have GraphQL support (unless you extend the schema) and it puts a higher degree of coupling 
between your app and the search engine.

By using GraphQL Search API you can maintain a single query language across all your frontend consumers, giving them 
total flexibility and since its not tied to any search backend you could replace the search engine in the future and 
still maintain the same API.

## Main features

This module provides the following features:

* Perform queries to Search API server backends like Apache Solr or ElasticSearch.
* Dynamic fields on your GraphQL query based on the fields defined in your Search API indexes.
* Apache Solr specific query syntax for advanced query options.

## More information

* Documentation: http://graphql-search-api.readthedocs.io
