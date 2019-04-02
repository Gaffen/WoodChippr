var path = require("path");

module.exports = {
  resolve: {
    alias: {
      modernizr$: path.resolve(__dirname, "..", "dev", "js", "modernizr.js")
    }
  }
};
