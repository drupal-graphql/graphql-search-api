# Graphql search api

[Drupal GraphQL](https://github.com/drupal-graphql/graphql) [Search API Solr](https://www.drupal.org/project/search_api_solr) Integration.

This module provides a wrapper for making queries against a solr search api backend using search api module.

## Why? 

The best reason for wrapping search api in a graphql endpoint is to get rid of any coupling between search api rendering (either using views or facet api) and have it fully decoupled so you can have many consumers doing their own querying / filtering / pagination or sorting independently of what is configured in drupal. A.K.A Decoupled drupal.

## How the module works

The first thing that the module does is create a graphql query field for each index that you have configured in Drupal. Because it can access Drupal and whatever is configured in Search API the module is zero-config which means you dont have to setup anything special (except any settings you would already do in Search API).

The module will also read the fields created for the indexes that you have setup so that it analyze the types and return values expected by each field, providing a good documentation out of the box for all your indexes and what can be retrieve by them.

## Getting stated

The first thing in order to use the module, is to upload it to you own site and enable it. After that if you have already indexes created you can head over to graphql explorer in "/graphql/explorer" and start doing a query. Lets assume your index is called "jobs" the module will create a graphq query called **"jobsSolrSearch"**

In the explorer you can also click the query and it will show you what kind of arguments you can pass, what the return type is and so on so you have full self documentation of your API just like you have with any other query in graphql.

### Quering for documents (Solr documents)

Here is an example of a simple query for the jobs index : 

```
{
  jobsSolrSearch(range: {start: 0, end: 100}, language: ["en"]) {
    solrDocs {
      title
      url
      status
    }
  }
}

```

This will return a list of Solr Documents (results or jobs in this case) like so : 

```
{
  "data": {
    "jobsSolrSearch": {
      "solrDocs": [
        {
          "title": "Hello world graphql metatag",
          "url": "/job/hello-world-redirected",
          "status": true
        },
        {
          "title": "Article name",
          "url": "/job/article-name",
          "status": true
        },
        ...
      ]
    }
  }
}

```

You can currently pass the following arguments to a query : 

*  fulltext - Full text search and keys to perform a full text search to solr
*  language - A set of languages to query against
*  conditions - A set of conditions for the query
*  range - A range for the query for handling pagination
*  range - A limit of the number of results being returned
*  sort - Sorting criteria
*  facets - Facet information to retrieve with the query

### Facets

Solr has support for facets, grouping results in categorie / buckets that can be retrieved together with a query and provide a way for the user to see how many results exist within a specific group set and provide a better user experience.

The module supports facets by providing the facets argument (see above) and also specifying the fields which should be retrieved with the query. You can retrieve multiple facets at once in a query (you should even). lets look at it in an example : 

```
{
  default_solr_indexSolrSearch(
  range: {start: 0, end: 100}, 
  language: ["en"], 
  facets: [
    {field: "job_ocupational_fields_name", limit: 20, operator: "AND", min_count: 0, missing: true},
    {field: "job_employment_type_name", limit: 20, operator: "AND", min_count: 0, missing: true}
  ]
  ) {
    solrDocs {
      title
      url
      status
      job_ocupational_fields_name
      job_employment_type_name
    }
    solrFacets {
      solrFacetName
      solrFacetValues {
        solrFacetFilter
        solrFacetCount
      }
    }
  }
}
```

here I am asking for two facets to be returned with the query. One for occupational fields name (a custom field in a job content type in this case) and another one for a simillar field called "job_employment_type_name". In the response information about these two facets will be provided : 

```
{
  "data": {
    "default_solr_indexSolrSearch": {
      "solrDocs": [
        {
          "title": "Hello world graphql metatag",
          "url": "/job/hello-world-redirected",
          "status": true,
          "job_ocupational_fields_name": [
            "Marketing",
            "Public relations"
          ],
          "job_employment_type_name": [
            "Apprenticeship",
            "Full time"
          ]
        },
        {
          "title": "Article name",
          "url": "/job/article-name",
          "status": true,
          "job_ocupational_fields_name": [
            "Informatics",
            "Marketing",
            "Language"
          ],
          "job_employment_type_name": [
            "Part time",
            "Internship"
          ]
        }
      ],
      "solrFacets": [
        {
          "solrFacetName": "job_ocupational_fields_name",
          "solrFacetValues": [
            {
              "solrFacetFilter": "\"marketing\"",
              "solrFacetCount": 2
            },
            {
              "solrFacetFilter": "\"informatics\"",
              "solrFacetCount": 1
            },
            {
              "solrFacetFilter": "\"language\"",
              "solrFacetCount": 1
            },
            {
              "solrFacetFilter": "\"public relations\"",
              "solrFacetCount": 1
            },
            {
              "solrFacetFilter": "!",
              "solrFacetCount": 0
            }
          ]
        },
        {
          "solrFacetName": "job_employment_type_name",
          "solrFacetValues": [
            {
              "solrFacetFilter": "\"apprenticeship\"",
              "solrFacetCount": 1
            },
            {
              "solrFacetFilter": "\"full time\"",
              "solrFacetCount": 1
            },
            {
              "solrFacetFilter": "\"internship\"",
              "solrFacetCount": 1
            },
            {
              "solrFacetFilter": "\"part time\"",
              "solrFacetCount": 1
            },
            {
              "solrFacetFilter": "!",
              "solrFacetCount": 0
            }
          ]
        }
      ]
    }
  }
}
```

We can see that the results in the facets tells us about the number of results in the query so we can see we have "2" items for "marketing" in the occupational fields name, which is correct. This is especially usefull when you return a list that is very long (maybe paginated) but want to give the user a full "glimpse" of the whole result set, so he doesn't have to click around in the results and filter to unnecessary results.