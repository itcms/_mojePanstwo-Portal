(function (e, t) {
    "use strict";
    var n = e.History = e.History || {}, r = e.Ext;
    e.JSON = {stringify: r.JSON.encode, parse: r.JSON.decode};
    if (typeof n.Adapter != "undefined")throw new Error("History.js Adapter has already been loaded...");
    n.Adapter = {observables: {}, bind: function (e, t, n, i) {
        r.EventManager.addListener(e, t, n, i);
        var s = r.id(e, "history-"), o = this.observables[s];
        o || (o = r.create("Ext.util.Observable"), this.observables[s] = o), o.on(t, n, i)
    }, trigger: function (e, t, n) {
        var i = r.id(e, "history-"), s = this.observables[i];
        s && s.fireEvent(t, n)
    }, extractEventData: function (e, n, r) {
        var i = n && n.browserEvent && n.browserEvent[e] || r && r[e] || t;
        return i
    }, onDomLoad: function (e) {
        r.onReady(e)
    }}, typeof n.init != "undefined" && n.init()
})(window)