(function (e, t) {
    "use strict";
    var n = e.History = e.History || {}, r = e.document, i = e.RightJS, s = i.$;
    if (typeof n.Adapter != "undefined")throw new Error("History.js Adapter has already been loaded...");
    n.Adapter = {bind: function (e, t, n) {
        s(e).on(t, n)
    }, trigger: function (e, t, n) {
        s(e).fire(t, n)
    }, extractEventData: function (e, n) {
        var r = n && n._ && n._[e] || t;
        return r
    }, onDomLoad: function (e) {
        s(r).onReady(e)
    }}, typeof n.init != "undefined" && n.init()
})(window)