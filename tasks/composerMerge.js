var fs = require("fs"),
  colors = require("colors/safe"),
  path = require("path");

function genJSON(args) {
  return {
    repositories: [
      {
        type: "package",
        package: {
          name: "advanced-custom-fields/advanced-custom-fields-pro",
          version: args.acfVersion,
          type: "wordpress-plugin",
          dist: {
            type: "zip",
            url:
              "https://connect.advancedcustomfields.com/index.php?t=" +
              args.acfVersion +
              "&p=pro&a=download&k=" +
              args.acfKey
          }
        }
      }
    ],
    require: {
      "advanced-custom-fields/advanced-custom-fields-pro": args.acfVersion
    }
  };
}

function writeJSON(acfRepo, callback) {
  fs.writeFile(
    path.resolve(__dirname, "../composer-merge/acf.json"),
    JSON.stringify(acfRepo, null, 2),
    function(err) {
      if (err) {
        console.log(err);
        if (callback) {
          return callback();
        }
      }

      console.log(
        colors.yellow("Your ACF configuration is complete, just run ") +
          colors.red.underline("composer install") +
          colors.yellow(" to finish setting up WoodChippr!")
      );

      if (callback) {
        return callback();
      }
    }
  );
}

module.exports = function(args, callback) {
  var acfRepo = genJSON(args);

  fs.stat(path.resolve(__dirname, "../composer-merge/"), function(err, stats) {
    if (err) {
      fs.mkdir(path.resolve(__dirname, "../composer-merge/"), function() {
        return writeJSON(genJSON(args));
      });
    } else {
      return writeJSON(genJSON(args));
    }
  });
};
