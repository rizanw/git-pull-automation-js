/**
 * Config Profile:
 */
const SECRET = "your_secret_here";
const REPO = "/var/www/project";
const PORT = 5000;

/*****************************************************/
let http = require("http");
let crypto = require("crypto");
const exec = require("child_process").exec;

http
  .createServer(function (req, res) {
    req.on("data", function (chunk) {
      let sig =
        "sha1=" +
        crypto
          .createHmac("sha1", SECRET)
          .update(chunk.toString())
          .digest("hex");

      if (req.headers["x-hub-signature"] == sig) {
        exec("cd " + REPO + " && git pull");
      }
    });
    res.end("Hello Git webhook!");
  })
  .listen(PORT, (err) => {
    if (err) {
      return console.log("error: ", err);
    }
    console.log(`server is listening on ${PORT}`);
  });
