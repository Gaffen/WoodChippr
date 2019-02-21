var fs = require("fs"),
  dotenv = require("dotenv"),
  path = require("path"),
  request = require("axios"),
  composerMerge = require("./composerMerge");

var salts = {
  AUTH_KEY: "",
  SECURE_AUTH_KEY: "",
  LOGGED_IN_KEY: "",
  NONCE_KEY: "",
  AUTH_SALT: "",
  SECURE_AUTH_SALT: "",
  LOGGED_IN_SALT: "",
  NONCE_SALT: ""
};

request
  .get("https://api.wordpress.org/secret-key/1.1/salt/")
  .then(function(response) {
    for (var key in salts) {
      var regString = "'" + key + "', *('.*')";
      var regEx = new RegExp(regString, "gm");
      salts[key] = regEx.exec(response.data)[1];
    }
    generateConfig();
  });

function generateConfig() {
  var fileLocation = path.resolve(__dirname, "../sample.env");

  var env = dotenv.parse(fs.readFileSync(fileLocation));

  for (var key in env) {
    if (process.env.hasOwnProperty(key)) {
      env[key] = process.env[key];
    }

    if (!env[key] && !salts[key]) {
      console.error(
        "\x1b[31m",
        "//// WARNING: Please ensure values are defined for all .env fields ////"
      );
      console.error("\x1b[31m", "//// INAVID VALUE FOUND AT: " + key + " ////");
      return;
    } else if (!env[key]) {
      env[key] = salts[key];
    }

    if (key == "AUTOPREFIX_ARGS") {
      env[key] = "'" + env[key] + "'";
    }
  }

  try {
    if (
      fs.existsSync(path.resolve(__dirname, "../.env")) &&
      !process.env.FORCE
    ) {
      console.error("\x1b[31m", "//// WARNING: .env file already exists. ////");
      console.error(
        "\x1b[31m",
        "//// Please specify FORCE=1 if you want to override this ////"
      );
    } else {
      fs.truncate(path.resolve(__dirname, "../.env"), 0, function() {
        var stream = fs.createWriteStream(path.resolve(__dirname, "../.env"));
        for (var key in env) {
          stream.write(key + "=" + env[key] + "\r\n");
        }
      });

      if (
        process.env.hasOwnProperty("ACF_KEY") &&
        process.env.ACF_KEY &&
        process.env.hasOwnProperty("ACF_VER") &&
        process.env.ACF_VER
      ) {
        composerMerge({
          acfVersion: process.env.ACF_VER,
          acfKey: process.env.ACF_KEY
        });
      }
    }
  } catch (err) {
    console.error(err);
  }
}

// envfile.parseFile(path.resolve(__dirname, '../sample.env'), function(err, obj){
//   console.log('test');
//   console.log(err, obj);
// });
