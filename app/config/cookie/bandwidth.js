/*
 * Implementation of the Network Information API
 */

// default bandwidth
var highBandwidth = false;

// bandwidth change handler
function BandwidthChange() {
    highBandwidth = (!connection.metered && connection.bandwidth > 2);
    console.log(
        "switching to " +
        (highBandwidth ? "high" : "low") +
        " bandwidth mode"
    );
    // store Bandwidth Information in Cookie
    COOKIE.setCookie('Bandwidth', highBandwidth ? "high" : "low");

}

// Network Information object
var connection = window.navigator.connection || window.navigator.mozConnection || window.navigator.webkitConnection || window.navigator.msConnection;

// initialize
if (connection) {
    connection.addEventListener("change", BandwidthChange);
    BandwidthChange();
} else {
    console.log("Network Information API not supported - using Fallback");
    FallbackBandwidthChange();
}

// Fallback Implementation
function FallbackBandwidthChange() {
    var arrTimes = [];
    var i = 0; // start
    var timesToTest = 5;
    var tThreshold = 150; //ms
    var testImage = "../app/config/cookie/bandwidth-test.jpg"; // small image on your server
    var dummyImage = new Image();

    testLatency(function (avg) {
        highBandwidth = (avg <= tThreshold);
        console.log("Time: " + (avg.toFixed(2)) +
            "ms - switching to " +
            (highBandwidth ? "high" : "low") +
            " bandwidth mode"
        );
        // store Bandwidth Information in Cookie
        COOKIE.setCookie('Bandwidth', highBandwidth ? "high" : "low");
    });

    // test and average time took to download image from server, called recursively timesToTest times
    // cb for callback function call
    function testLatency(cb) {
        var tStart = new Date().getTime();
        if (i < timesToTest - 1) {
            dummyImage.src = testImage + '?t=' + tStart;
            dummyImage.onload = function () {
                var tEnd = new Date().getTime();
                var tTimeTook = tEnd - tStart;
                arrTimes[i] = tTimeTook;
                // recursive call
                testLatency(cb);
                i++;
            };
        } else {
            // calculate average of array items then callback
            var sum = arrTimes.reduce(function (a, b) {
                return a + b;
            });
            var avg = sum / arrTimes.length;
            //call callback function (testLatency) with average time value
            cb(avg);
        }
    }
}