import React from "react";
import ApolloClient from "apollo-boost";
import { gql } from "apollo-boost";
import { ApolloProvider } from "@apollo/react-hooks";

const client = new ApolloClient({
  uri: "http://cashme.lndo.site:8000/graphql"
});

console.log(client);

client
  .query({
    query: gql`
      {
        categoryQuery {
          entities {
            entityId
            entityLabel
          }
        }
      }
    `
  })
  .then(result => console.log(result));

// const withQuery = graphql(query, {
//   props: ({ data }) => {
//     console.log(data);
//   }
// });

function App() {
  return (
    <ApolloProvider client={client}>
      <div>
        <h2>My first Apollo app</h2>
      </div>
    </ApolloProvider>
  );
}

export default App;
