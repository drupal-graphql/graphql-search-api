# Schema
This module exposes a completely custom schema that translates the Search API into a dynamic, self documented GraphQL 
API. This page focuses on explaining that schema.

## Interfaces

### Search API Document
The Search API Document interface provides a generic interface to represent a document stored in a Search API index. In 
the GraphQL Search API module we consider documents (set of fields for a given index) for different indexes to be 
different document types all of which extend this interface.

## Types

### Search API Document Type
The Search API Document Type type represents a Search API document and extends the Search API Document interface. Because we 
have an unlimited amount of document types (one for each index) this Type uses a [Document Type deriver](#document-type-deriver) to generate the 
various document types dynamically. A Document can have multiple fields, as many as the specified index has configured 
in Search API.

### Search API Facet
The Search API Facet type represents a Search API facet as a GraphQL object type. The structure of a facet is:

* name - The name of the facet field to be returned (e.g Location).
* values - The values of the facet field to be returned.


### Search API Facet value
The Search API facet value represents the value returned by a given facet. Each value has:

* filter - The facet filter returned (e.g 'Australia' or 'Austria').
* count - The facet count for the filter (e.g 10 for 'Australia' or 5 for 'Austria').

### Search API Result
The Search API Result type represents the response object returned by Search API.

## Inputs

### Condition
The Condition input provides an object to encapsulate a condition that can be passed to the search query as an argument.
Here is an example condition: 

`conditions:[{name:"location",operator:"=",value:"Australia"}]`

The structure of a condition can be broken down in:

* operator - The operator to be used in the this condition (e.g '='').
* name - The name of the condition (e.g 'location').
* value - The value of to be evaluated in this condition (e.g 'Australia').

For more details about the options that can be used see the [Search parameters](search-parameters.md) page.

### Facet
The Facet input allows us to specify as arguments which facets we want returned in our query. Here is an example facet 
argument:

`facets:{field:"location",operator:"=",missing:false,min_count:1,limit:0}`

The structure of the facet input is:

* operator - The operator for the facet query (e.g '=').
* field - The field facet to be specified (e.g 'location').
* missing - If true a count of all results that match the query but have no value for the field should be returned. 
* min_count - A minimum count for the facet in order for it to be returned.
* limit - A limit on the number of values to be returned.

For more details about the options that can be used see the [Search parameters](search-parameters.md) page.

### Fulltext
The Fulltext input allows us to specify fulltext arguments in our search query. Here is an example fulltext argument:

`fulltext:{keys:"my keyword", fields:"body"}`

The structure of a Fulltext input is:

* keys - The keywords to be used in the fulltext search.
* fields - An optional argument that allows you to limit the fulltext search to a set of fields.

For more details about the options that can be used see the [Search parameters](search-parameters.md) page.

### Range
The Range input allows us to specify a range in our query, which is useful for pagination purposes. An example of range 
being used in a query:

`range:{start:0,end:10}`

The above can obtain the first page of results (assuming each page has 10 results). If we wanted to return the second 
page we could use `start:10, end: 20`. The structure of the range input:

* start - The start of the range we want returned from the result set.
* end - The end of the range we want returned from the result set. 

For more details about the options that can be used see the [Search parameters](search-parameters.md) page.

### Solr Parameter
The Solr Parameter input allows us to specify *any* raw Solr parameters directly in Solr Query language instead of 
using Search API. Here is an example:

`solr_params:{parameter:"q",value:"ss_location:Australia"}`

This is the equivalent of using a condition in our search parameter.

**Please note** that since this syntax is direct to 
Apache Solr you need to specify the name of the field as appears in Solr (with the prefix).

For more details about the options that can be used see the [Search parameters](search-parameters.md) page.

### Sort
TODO

## Fields
TODO

### Search
TODO

### Documents
TODO

### Search API Field
TODO

### Facet
TODO

#### Facet Name
TODO

#### Facet Value
TODO

#### Facet Filter
TODO

#### Facet Count
TODO

## Derivers
TODO

### Document Type Deriver
TODO

### Field Deriver
TODO