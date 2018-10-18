var path = require("path");

module.exports = {
  module: {
    rules: [
      {
        test: /\.modernizrrc(\.json)?$/,
        use: ["modernizr-loader", "json-loader"]
      }
    ]
  },
  resolve: {
    alias: {
      modernizr$: path.resolve(__dirname, "../.modernizrrc")
    }
  }
};
