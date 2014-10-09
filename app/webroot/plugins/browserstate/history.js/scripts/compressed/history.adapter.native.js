(function (e, t) {
    "use strict";
    var n = e.History = e.History || {};
    if (typeof n.Adapter != "undefined")throw new Error("History.js Adapter has already been loaded...");
    n.Adapter = {
        handlers: {}, _uid: 1, uid: function (e) {
            return e._uid || (e._uid = n.Adapter._uid++)
        }, bind: function (e, t, r) {
            var i = n.Adapter.uid(e);
            n.Adapter.handlers[i] = n.Adapter.handlers[i] || {}, n.Adapter.handlers[i][t] = n.Adapter.handlers[i][t] || [], n.Adapter.handlers[i][t].push(r), e["on" + t] = function (e, t) {
                return function (r) {
                    n.Adapter.trigger(e, t, r)
                }
            }(e, t)
        }, trigger: function (e, t, r) {
            r = r || {};
            var i = n.Adapter.uid(e), s, o;
            n.Adapter.handlers[i] = n.Adapter.handlers[i] || {}, n.Adapter.handlers[i][t] = n.Adapter.handlers[i][t] || [];
            for (s = 0, o = n.Adapter.handlers[i][t].length; s < o; ++s)n.Adapter.handlers[i][t][s].apply(this, [r])
        }, extractEventData: function (e, n) {
            var r = n && n[e] || t;
            return r
        }, onDomLoad: function (t) {
            var n = e.setTimeout(function () {
                t()
            }, 2e3);
            e.onload = function () {
                clearTimeout(n), t()
            }
        }
    }, typeof n.init != "undefined" && n.init()
})(window)