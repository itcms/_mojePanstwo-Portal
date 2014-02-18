(function (e, t) {
    "use strict";
    var n = e.History = e.History || {}, r = e.Zepto;
    if (typeof n.Adapter != "undefined")throw new Error("History.js Adapter has already been loaded...");
    n.Adapter = {bind: function (e, t, n) {
        (new r(e)).bind(t, n)
    }, trigger: function (e, t) {
        (new r(e)).trigger(t)
    }, extractEventData: function (e, n) {
        var r = n && n[e] || t;
        return r
    }, onDomLoad: function (e) {
        new r(e)
    }}, typeof n.init != "undefined" && n.init()
})(window)