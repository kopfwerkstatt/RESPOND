/*
 The code below will read
 and write device information
 from and to a cookie:
 */

// Namespace
COOKIE = {};

// Reload Page if Window is resized (deliver optimized images)?
COOKIE.reloadActive = true;
//max Difference (Pixel) between old and new Resolution to trigger Reload
const resolutionResize = 200;

// Reload Page Components (Mobile / Tablet / Desktop) if required?
COOKIE.autoSwitchActive = true;
// max Resolution to switch View / reload Page Components
const resolutionMobile = 480;
const resolutionTablet = 992;

/*
 Default Cookie Helper Methods:
 */

// Store Information in Cookie
COOKIE.setCookie = function (cname, cvalue) {
    var date = new Date();
    var expires = date.setFullYear(date.getFullYear() + 1);
    document.cookie = cname + '=' + cvalue + '; ' + expires;
};

// Read Information from Cookie
COOKIE.getCookie = function (cname) {
    var name = cname + '=';
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1);
        if (c.indexOf(name) != -1) return c.substring(name.length, c.length);
    }
    return '';
};

/*
 Custom Methods to store Device Information
 and to resize and reload window if required:
 */

// Test if browser supports Cookies
COOKIE.cookieEnabled = function () {
    var cookie = !!(navigator.cookieEnabled);
    if (typeof navigator.cookieEnabled == 'undefined' && !cookieEnabled) {
        document.cookie = 'testCookieSupport';
        cookie = (document.cookie.indexOf('testCookieSupport') != -1);
    }
    return cookie;
};

// If Cookie-Support not enabled set log message
COOKIE.noCookie = function () {
    console.log('Sorry - no Cookie-Support enabled');
};

// Store Device Information in Cookie
COOKIE.storeDeviceInformation = function (view) {
    // Get Device Class
    var device = '';

    // Switch To man. selected View
    if (typeof(view) === 'undefined') {
        view = COOKIE.getSelectedView();
    }
    switch (view) {
        case 'tablet':
            device = 'tablet';
            break;
        case 'mobile':
            device = 'mobile';
            break;
        case 'desktop':
            device = 'desktop';
            break;
        default:
            device = COOKIE.getDeviceClass();
    }

    // Get current Screen Width
    var width = window.innerWidth;

    COOKIE.setCookie('DeviceInformation', 'screen_width.' + width + '.device.' + device + '.selected_view.' + view);
};

// Call Method on Page Load
window.onload = function () {
    if (COOKIE.cookieEnabled()) {
        // get Scroll Information from Session Cookie
        loadP();

        if (COOKIE.getCookie('DeviceInformation') == '') {
            COOKIE.storeDeviceInformation();
        }

    } else {
        COOKIE.noCookie();
    }
};

// Call Method on Page Unload
window.onunload = function () {
    if (COOKIE.cookieEnabled()) {
        // store Scroll Information into Session Cookie
        unloadP();
    }
};

// if reloadActive is true
if (COOKIE.reloadActive) {
    // Listen for Window Resize Event
    COOKIE.Resize = false;
    window.onresize = function () {
        if (COOKIE.cookieEnabled()) {
            if (!COOKIE.Resize) {
                var oldResolution = COOKIE.getWidthFromCookie();
                COOKIE.Resize = true;
                window.setTimeout(function () {
                    COOKIE.storeDeviceInformation();
                    COOKIE.doReload(oldResolution);
                    COOKIE.Resize = false;
                }, 1000);
            }
        }
    };
}

// Switch View (Desktop/Tablet/Mobile) automatically if window is resized
COOKIE.autoSwitchView = function (Resolution) {
    if (Resolution <= resolutionMobile) {
        switchView('mobile');
    } else if (Resolution <= resolutionTablet) {
        switchView('tablet');
    } else {
        switchView('desktop');
    }
};

// Get Width Information stored in Cookie
COOKIE.getWidthFromCookie = function () {
    var cookieInformation = COOKIE.getCookie('DeviceInformation').split('.');
    return cookieInformation[1];
};

// Check if window resize/Screen Size would affect image version or interchangeable Page Components - force a reload to serve updated markup
COOKIE.doReload = function (oldResolution) {
    // determine when to perform a reload (only on a Desktop Browser -> window resize possible)
    if (COOKIE.autoSwitchActive && COOKIE.getDeviceClass() == 'desktop') {
        //console.log('old Resolution: ' + oldResolution + ' | new Resolution: ' + window.innerWidth);
        if (oldResolution > resolutionMobile && window.innerWidth <= resolutionMobile || oldResolution > resolutionTablet && window.innerWidth <= resolutionTablet
            || oldResolution <= resolutionMobile && window.innerWidth > resolutionMobile || oldResolution <= resolutionTablet && window.innerWidth > resolutionTablet) {
            // switch Page Components (Mobile / Tablet Desktop) if User Agent is 'Desktop' and autoSwitchActive is true
            COOKIE.autoSwitchView(window.innerWidth);
        }

    } else if (COOKIE.reloadActive && COOKIE.getDeviceClass() == 'desktop') {
        var difference = oldResolution - window.innerWidth;
        if (difference < 0) {
            difference = difference * -1;
        }
        //console.log('old Resolution: ' + oldResolution + ' | new Resolution: ' + window.innerWidth + ' | Difference: ' + difference);
        if (difference > resolutionResize) {
            // if difference of resize event is big enough - reload window to deliver optimized images
            window.location.reload(true);
        }
    }
    return false;
};

/*
 Custom Methods to select View manually:
 */

// Switch Site Version to assigned View
switchView = function (device) {
    if (COOKIE.cookieEnabled()) {
        if (device == 'desktop' || device == 'tablet' || device == 'mobile' || device == 'detected') {
            COOKIE.storeDeviceInformation(device);
            window.location.reload(true);
        } else {
            console.log('Error: Wrong device class assigned for switchView() - Please use detected, desktop, tablet, or mobile')
        }
    }
};

// Return current man. selected View stored in Cookie
COOKIE.getSelectedView = function () {
    if (COOKIE.getCookie('DeviceInformation') != '') {
        var cookieInformation = COOKIE.getCookie('DeviceInformation').split('.');
        return cookieInformation[5];
    }
    return 'detected';
};

/*
 WURFL.js Methods:
 */

// Return detected Device Class (from User Agent)
COOKIE.getDeviceClass = function () {
    if (WURFL.is_mobile && WURFL.form_factor == 'Tablet') {
        return 'tablet';
    } else if (WURFL.is_mobile) {
        return 'mobile';
    } else if (!WURFL.is_mobile && WURFL.form_factor == 'Desktop') {
        return 'desktop';
    } else {
        return 'unknown';
    }
};

/*
 Custom Methods to remember scroll position after reload
 Call loadP and unloadP when body loads/unloads and scroll
 position will not move!
 */

// get current Scroll position
function getScrollXY() {
    var x = 0, y = 0;
    if (typeof( window.pageYOffset ) == 'number') {
        // Netscape
        x = window.pageXOffset;
        y = window.pageYOffset;
    } else if (document.body && ( document.body.scrollLeft || document.body.scrollTop )) {
        // DOM
        x = document.body.scrollLeft;
        y = document.body.scrollTop;
    } else if (document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop )) {
        // IE6 standards compliant mode
        x = document.documentElement.scrollLeft;
        y = document.documentElement.scrollTop;
    }
    return [x, y];
}

// set current scroll Position
function setScrollXY(x, y) {
    window.scrollTo(x, y);
}

// load last Scroll Position from Cookie
function loadP() {
    var sPath = window.location.pathname;
    var sPage = sPath.substring(sPath.lastIndexOf('/'));
    var x = COOKIE.getCookie(sPage + 'x');
    var y = COOKIE.getCookie(sPage + 'y');
    setScrollXY(x, y);
}

// store current Scroll Position in Cookie
function unloadP() {
    var sPath = window.location.pathname;
    var sPage = sPath.substring(sPath.lastIndexOf('/'));
    var s = getScrollXY();
    COOKIE.setCookie(sPage + 'x', s[0], 0.1);
    COOKIE.setCookie(sPage + 'y', s[1], 0.1);
}