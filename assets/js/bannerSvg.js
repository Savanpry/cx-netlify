const fs = require("fs");
const path = require("path");

module.exports = function () {
    const svgPath = "../img/bannerabout.svg";
    return fs.readFileSync(path.resolve(svgPath), "utf8");
    
};