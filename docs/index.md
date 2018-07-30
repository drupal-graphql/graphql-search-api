# Graphql Search API

This Drupal module provides an integration between [Drupal GraphQL](https://github.com/drupal-graphql/graphql) and [Search API Solr](https://www.drupal.org/project/search_api_solr) modules.

It allows developers to perform queries directly to a Search API index by leveraging the Search API module's API.

Since it uses Search API it's not backend specific and can work with any engine such as Apache Solr or Elastic Search.

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

## How the module works

This module exposes a query field that takes several search query arguments and allows you to specify the document 
fields you want return from the index.

It's also capable of understanding which fields are available for each index.

## Getting started
Because it can access Drupal and whatever is configured in Search API the module is zero-config (except any settings 
you would already do in Search API).

### Example query

This is an example query using this module. We are passing the only mandatory argument to our query (the search index) and then specifying our fields inside the 
document list. By using fragments we can obtain a list of index specific fields.

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

## Help us make this better

TODO