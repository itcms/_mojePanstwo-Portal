(function (e, t) {
    "use strict";
    var n = e.History = e.History || {}, r = e.jQuery;
    if (typeof n.Adapter != "undefined")throw new Error("History.js Adapter has already been loaded...");
    n.Adapter = {bind: function (e, t, n) {
        r(e).bind(t, n)
    }, trigger: function (e, t, n) {
        r(e).trigger(t, n)
    }, extractEventData: function (e, n, r) {
        var i = n && n.originalEvent && n.originalEvent[e] || r && r[e] || t;
        return i
    }, onDomLoad: function (e) {
        r(e)
    }}, typeof n.init != "undefined" && n.init()
})(window)