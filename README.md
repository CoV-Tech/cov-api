# CoV API

Get the javascript client [cov-api-client-js](https://github.com/CoV-Tech/cov-api-client-js) <br>
<br>
If you have any suggestions and/or found a bug, please share by creating an issue, that way this might become an useful framework in the future<br>
<br>
This api is based on [GraphQL](https://graphql.org/) and [REST](https://restfulapi.net/) <br>
The utilisation is ```[base_url]/[node](/{id}(/[edge]))?http={true|false}(&fields={fields})``` <br>
the **node** is the object you want to do the operation on <br>
the **id** is the node id you want to get <br>
the **edge** is when you want a subset of the node (an edge is a link between two nodes) <br>
if **http** is set to *true*, it will set the http status code, if set to *false*, it will always respond with 200 OK <br>
in the **fields** parameter you can choice the fields of the node you want to get <br>
special urls : <br>
- ```[base_url]/``` will return the version of the api and a timestamp <br>
- ```[base_url]/dev``` will return the definition of the api (availeble nodes, fields, and edges) <br>

the response will always be in the format : <br>
```
{
  status : {
    http : {
      code : int, 
      message : string
    },
    api : {
      code : int,
      message : string
    }
  },
  response : {
  
  },
  response_time : double (secondes),
  version : string
}
```
