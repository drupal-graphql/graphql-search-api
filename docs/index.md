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
* Apache Solr specific query syntax for advanced query options

## Getting started

In order to get started using this module you need to install the GraphQL Search API module as you would any Drupal 
module. The following dependencies are required:

* [Drupal GraphQL](https://github.com/drupal-graphql/graphql)
* [Search API Solr](https://www.drupal.org/project/search_api_solr)

After the module is installed, just configure Search API as you normally would, create an index and define some fields.
Once you have at least one index and some fields you can start querying your search backend via GraphQL:

![generic_query] (images/explorer_generic_query.png)

### Example query

This is an example query using this module. We are passing the only mandatory argument to our query (the search index) 
and then specifying our fields inside the document list.

It's important to node that inside the documents list you only have one generic field that is available on all indexes 
(the index id). If you want to specify index specific fields you need to specify fragments. In the below example we use 
the fragment `... on AnabranchConnectIndexDoc` to specify the fields in the `anabranch_connect_index`.

Visit the [Schema](schema.md) page for more information on the schema and the [Search Parameters](search-parameters.md) 
page to understand how to use search parameters and pass them to your query.

```
{
  searchAPISearch(index_id: "anabranch_connect_index") {
    documents {
      index_id
      ... on AnabranchConnectIndexDoc {
        title
        type
        url
      }
    }
  }
}
```