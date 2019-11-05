module.exports = {
  siteMetadata: {
    title: 'Gatsby Drupal Starter',
  },
  pathPrefix: `/gatsby-drupal-starter`,
  plugins: [
    'gatsby-plugin-react-helmet',
    {
      resolve: `gatsby-source-drupal`,
      options: {
        baseUrl: `http://cashme.lndo.site:8000/`,
        apiBase: `api`,
      },
    },
  ],
}
