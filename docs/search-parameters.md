# Search Parameters
This page documents the multiple search parameters you can pass to the search query using this module.

## Index ID
This is the only mandatory parameter and allows you to specify which index to perform the search on.

### Example
Returns all documents in the index.

```
{
  searchAPISearch(index_id: "index_name") {
    documents {
      index_id
      ... on IndexNameDoc {
        title
        body
      }
    }
  }
}
```

## Fulltext

The Fulltext parameters allow you to perform a fulltext search on the given index.

| Argument | Required | Type     | Values                                                                                                                          |
|----------|----------|----------|---------------------------------------------------------------------------------------------------------------------------------|
| `keys`     | yes      | `String`   | A string with the keywords to be used on the fulltext search.                                                                   |
| `fields`   | no       | `[String]` | A list of fields that the fulltext search is to be performed on. If left blank the search will be done on all fulltext fields. |

### Example
Returns all results that match the fulltext search `my keywords` on the title and body field of all indexed documents.

```
{
  searchAPISearch(index_id: "index_name", fulltext: {keys: "my keywords", fields: ["title", "body"]}) {
    documents {
      index_id
      ... on IndexNameDoc {
        title
        body
      }
    }
  }
}
```

## Language

This argument allows us to specify which language to use in our search query. The language corresponds to the language 
codes supported in Drupal such as `en` or `pt`.

### Example
Returns all results with the english language.

```
{
  searchAPISearch(index_id: "index_name", language: "en") {
    documents {
      index_id
      ... on IndexNameDoc {
        title
        body
      }
    }
  }
}
```

## Range

This argument allows us to specify a range in the query result set so that it can be used in things like pagination.

| Argument | Required | Type | Values                        |
|----------|----------|------|-------------------------------|
| `start`    | yes      | `Int`  | The lower limit of the range. |
| `end`      | yes      | `Int` | The upper limit of the range. |

### Example
Returns the first 10 results of the query.

```
{
  searchAPISearch(index_id: "index_name", range: {start: 0, end: 10}) {
    documents {
      index_id
      ... on IndexNameDoc {
        title
        body
      }
    }
  }
}
```

## Conditions
Conditions represent the *de facto* query part of the search. It allows us to specify multiple query conditions 
evaluated against the search backend.

| Argument | Required | Type   | Values                                                                                                                  |
|----------|----------|--------|-------------------------------------------------------------------------------------------------------------------------|
| `operator` | no       | `String` | The operator allows us to specify the evaluation expression. All Search API conditions are supported. Some common examples: `=`, `<>`, `>`, `<`, `>=`, `<=`. |
| `field`    | yes      | `String` | The field defines what field in the index to be evaluated against this condition.                                       |
| `value`    | yes      | `String` | The value to be valuated against the specified field and operator.                                                      |                                    |

### Example
Returns the all results with type of course.

```
{
  searchAPISearch(index_id: "index_name", conditions: [{operator: "=", field: "type", value: "course"}]) {
    documents {
      index_id
      ... on IndexNameDoc {
        title
        body
      }
    }
  }
}
```

## Sort
Sorting parameters allow you to sort the result set.

| Argument | Required | Type   | Values                                                                                       |
|----------|----------|--------|----------------------------------------------------------------------------------------------|
| `field`    | yes      | `String` | The which for which to sort the result set.                                                  |
| `value`    | yes      | `String` | The sorting order to choose from. Options are `asc` for ascending and `desc` for descending. |

### Example
Returns the all documents in the index sorted by date ascending.

```
{
  searchAPISearch(index_id: "index_name", sort: {field: "date", value: "asc"}) {
    documents {
      index_id
      ... on IndexNameDoc {
        title
        body
      }
    }
  }
}
```

## Facets
The facets argument allow us to specify multiple facets into the search query.

**Please note** that for the actual facet results to be returned you need to add the facet fields to your schema 
(See [Schema](schema.md) for more details).

| Argument  | Required | Type    | Values                                                                                                                                                                              |
|-----------|----------|---------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `operator`  | yes      | `String`  | The operator allows us to specify the evaluation expression to match to our facets. All Search API conditions are supported. Some common examples: `=`, `<>`, `>`, `<`, `>=`, `<=`. |
| `field`     | yes      | `String`  | The field defines what field in the index to be evaluated as a facet against the operator specified.                                                                                |
| `limit`     | yes      | `Int`     | A limit on the number of values to be returned.                                                                                                                                     |
| `min_count` | yes      | `Int`     | A minimum count for the facet in order for it to be returned.                                                                                                                       |
| `missing`   | yes      | `Boolean` | If true a count of all results that match the query but have no value for the field should be returned.                                                                             |


### Example
Returns the all documents in the index and the facet values (and counts) for the type field.

```
{
  searchAPISearch(index_id: "index_name", facets: {operator: "=", field: "type", limit: 0, min_count: 1, missing: false}) {
    documents {
      index_id
      ... on IndexNameDoc {
        title
        body
      }
    }
    facets{
      name
      values{
        count
        filter
      }
    }
  }
}
```

## Solr Parameters (Apache Solr Only)
This special type parameter is only applicable to Apache Solr. It allows us to specify raw Solr query parameters such as
 `q`, `facet.query`, `facet.range` or any parameter supported by Apache Solr in its raw syntax.
 
 | Argument  | Required | Type   | Values                                                                                                                                                                                                                              |
 |-----------|----------|--------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
 | `parameter` | yes      | `String` | The name of the parameter as expected by Apache Solr. See [Apache Solr Documentation](https://lucene.apache.org/solr/guide/6_6/common-query-parameters.html) for some examples.                                                     |
 | `value`     | yes      | `String` | The value of the parameter as expected by Apache Solr. Please note that fields specified here need to be in the same format as in the index, so they should *include* prefixes ommited in the Search API fields configuration page. |
 
### Example 
Returns the all documents in the index with the type course using Apache Solr query syntax.

```
{
  searchAPISearch(index_id: "index_name", solr_params:[{parameter:"q",value:"ss_type:course"}]) {
    documents {
      index_id
      ... on IndexNameDoc {
        title
        body
      }
    }
  }
}
```
