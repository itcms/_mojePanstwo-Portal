/*
 Highcharts JS v4.0.1 (2014-04-24)

 (c) 2011-2014 Torstein Honsi

 License: www.highcharts.com/license
 */
(function (g) {
    var j = g.Axis, x = g.Chart, o = g.Color, y = g.Legend, s = g.LegendSymbolMixin, t = g.Series, u = g.getOptions(), k = g.each, p = g.extend, z = g.extendClass, l = g.merge, q = g.pick, v = g.numberFormat, m = g.seriesTypes, w = g.wrap, n = function () {
    }, r = g.ColorAxis = function () {
        this.isColorAxis = !0;
        this.init.apply(this, arguments)
    };
    p(r.prototype, j.prototype);
    p(r.prototype, {
        defaultColorAxisOptions: {
            lineWidth: 0,
            gridLineWidth: 1,
            tickPixelInterval: 72,
            startOnTick: !0,
            endOnTick: !0,
            offset: 0,
            marker: {animation: {duration: 50}, color: "gray", width: 0.01},
            labels: {overflow: "justify"},
            minColor: "#EFEFFF",
            maxColor: "#003875",
            tickLength: 5
        }, init: function (b, a) {
            var d = b.options.legend.layout !== "vertical", c;
            c = l(this.defaultColorAxisOptions, {side: d ? 2 : 1, reversed: !d}, a, {
                isX: d,
                opposite: !d,
                showEmpty: !1,
                title: null,
                isColor: !0
            });
            j.prototype.init.call(this, b, c);
            a.dataClasses && this.initDataClasses(a);
            this.initStops(a);
            this.isXAxis = !0;
            this.horiz = d;
            this.zoomEnabled = !1
        }, tweenColors: function (b, a, d) {
            var c = a.rgba[3] !== 1 || b.rgba[3] !== 1;
            return (c ? "rgba(" : "rgb(") + Math.round(a.rgba[0] +
            (b.rgba[0] - a.rgba[0]) * (1 - d)) + "," + Math.round(a.rgba[1] + (b.rgba[1] - a.rgba[1]) * (1 - d)) + "," + Math.round(a.rgba[2] + (b.rgba[2] - a.rgba[2]) * (1 - d)) + (c ? "," + (a.rgba[3] + (b.rgba[3] - a.rgba[3]) * (1 - d)) : "") + ")"
        }, initDataClasses: function (b) {
            var a = this, d = this.chart, c, e = 0, h = this.options;
            this.dataClasses = c = [];
            k(b.dataClasses, function (f, i) {
                var g, f = l(f);
                c.push(f);
                if (!f.color)h.dataClassColor === "category" ? (g = d.options.colors, f.color = g[e++], e === g.length && (e = 0)) : f.color = a.tweenColors(o(h.minColor), o(h.maxColor), i / (b.dataClasses.length -
                1))
            })
        }, initStops: function (b) {
            this.stops = b.stops || [[0, this.options.minColor], [1, this.options.maxColor]];
            k(this.stops, function (a) {
                a.color = o(a[1])
            })
        }, setOptions: function (b) {
            j.prototype.setOptions.call(this, b);
            this.options.crosshair = this.options.marker;
            this.coll = "colorAxis"
        }, setAxisSize: function () {
            var b = this.legendSymbol, a = this.chart, d, c, e;
            if (b)this.left = d = b.attr("x"), this.top = c = b.attr("y"), this.width = e = b.attr("width"), this.height = b = b.attr("height"), this.right = a.chartWidth - d - e, this.bottom = a.chartHeight -
            c - b, this.len = this.horiz ? e : b, this.pos = this.horiz ? d : c
        }, toColor: function (b, a) {
            var d, c = this.stops, e, h = this.dataClasses, f, i;
            if (h)for (i = h.length; i--;) {
                if (f = h[i], e = f.from, c = f.to, (e === void 0 || b >= e) && (c === void 0 || b <= c)) {
                    d = f.color;
                    if (a)a.dataClass = i;
                    break
                }
            } else {
                this.isLog && (b = this.val2lin(b));
                d = 1 - (this.max - b) / (this.max - this.min);
                for (i = c.length; i--;)if (d > c[i][0])break;
                e = c[i] || c[i + 1];
                c = c[i + 1] || e;
                d = 1 - (c[0] - d) / (c[0] - e[0] || 1);
                d = this.tweenColors(e.color, c.color, d)
            }
            return d
        }, getOffset: function () {
            var b = this.legendGroup;
            if (b && (j.prototype.getOffset.call(this), !this.axisGroup.parentGroup))this.axisGroup.add(b), this.gridGroup.add(b), this.labelGroup.add(b), this.added = !0
        }, setLegendColor: function () {
            var b, a = this.options;
            b = this.horiz ? [0, 0, 1, 0] : [0, 0, 0, 1];
            this.legendColor = {
                linearGradient: {x1: b[0], y1: b[1], x2: b[2], y2: b[3]},
                stops: a.stops || [[0, a.minColor], [1, a.maxColor]]
            }
        }, drawLegendSymbol: function (b, a) {
            var d = b.padding, c = b.options, e = this.horiz, h = q(c.symbolWidth, e ? 200 : 12), f = q(c.symbolHeight, e ? 12 : 200), c = q(c.labelPadding, e ? 10 : 30);
            this.setLegendColor();
            a.legendSymbol = this.chart.renderer.rect(0, b.baseline - 11, h, f).attr({zIndex: 1}).add(a.legendGroup);
            a.legendSymbol.getBBox();
            this.legendItemWidth = h + d + (e ? 0 : c);
            this.legendItemHeight = f + d + (e ? c : 0)
        }, setState: n, visible: !0, setVisible: n, getSeriesExtremes: function () {
            var b;
            if (this.series.length)b = this.series[0], this.dataMin = b.valueMin, this.dataMax = b.valueMax
        }, drawCrosshair: function (b, a) {
            var d = !this.cross, c = a && a.plotX, e = a && a.plotY, h, f = this.pos, i = this.len;
            if (a)h = this.toPixels(a.value), h < f ? h =
                f - 2 : h > f + i && (h = f + i + 2), a.plotX = h, a.plotY = this.len - h, j.prototype.drawCrosshair.call(this, b, a), a.plotX = c, a.plotY = e, !d && this.cross && this.cross.attr({fill: this.crosshair.color}).add(this.labelGroup)
        }, getPlotLinePath: function (b, a, d, c, e) {
            return e ? this.horiz ? ["M", e - 4, this.top - 6, "L", e + 4, this.top - 6, e, this.top, "Z"] : ["M", this.left, e, "L", this.left - 6, e + 6, this.left - 6, e - 6, "Z"] : j.prototype.getPlotLinePath.call(this, b, a, d, c)
        }, update: function (b, a) {
            k(this.series, function (a) {
                a.isDirtyData = !0
            });
            j.prototype.update.call(this,
                b, a);
            this.legendItem && (this.setLegendColor(), this.chart.legend.colorizeItem(this, !0))
        }, getDataClassLegendSymbols: function () {
            var b = this, a = this.chart, d = [], c = a.options.legend, e = c.valueDecimals, h = c.valueSuffix || "", f;
            k(this.dataClasses, function (c, g) {
                var j = !0, l = c.from, m = c.to;
                f = "";
                l === void 0 ? f = "< " : m === void 0 && (f = "> ");
                l !== void 0 && (f += v(l, e) + h);
                l !== void 0 && m !== void 0 && (f += " - ");
                m !== void 0 && (f += v(m, e) + h);
                d.push(p({
                    chart: a,
                    name: f,
                    options: {},
                    drawLegendSymbol: s.drawRectangle,
                    visible: !0,
                    setState: n,
                    setVisible: function () {
                        j =
                            this.visible = !j;
                        k(b.series, function (a) {
                            k(a.points, function (a) {
                                a.dataClass === g && a.setVisible(j)
                            })
                        });
                        a.legend.colorizeItem(this, j)
                    }
                }, c))
            });
            return d
        }, name: ""
    });
    w(x.prototype, "getAxes", function (b) {
        var a = this.options.colorAxis;
        b.call(this);
        this.colorAxis = [];
        a && new r(this, a)
    });
    w(y.prototype, "getAllItems", function (b) {
        var a = [], d = this.chart.colorAxis[0];
        d && (d.options.dataClasses ? a = a.concat(d.getDataClassLegendSymbols()) : a.push(d), k(d.series, function (a) {
            a.options.showInLegend = !1
        }));
        return a.concat(b.call(this))
    });
    g = {
        pointAttrToOptions: {
            stroke: "borderColor",
            "stroke-width": "borderWidth",
            fill: "color",
            dashstyle: "dashStyle"
        },
        pointArrayMap: ["value"],
        axisTypes: ["xAxis", "yAxis", "colorAxis"],
        optionalAxis: "colorAxis",
        trackerGroups: ["group", "markerGroup", "dataLabelsGroup"],
        getSymbol: n,
        parallelArrays: ["x", "y", "value"],
        translateColors: function () {
            var b = this, a = this.options.nullColor, d = this.colorAxis;
            k(this.data, function (c) {
                var e = c.value;
                if (e = e === null ? a : d ? d.toColor(e, c) : c.color || b.color)c.color = e
            })
        }
    };
    u.plotOptions.heatmap =
        l(u.plotOptions.scatter, {
            animation: !1,
            borderWidth: 0,
            nullColor: "#F8F8F8",
            dataLabels: {
                format: "{point.value}",
                verticalAlign: "middle",
                crop: !1,
                overflow: !1,
                style: {color: "white", fontWeight: "bold", textShadow: "0 0 5px black"}
            },
            marker: null,
            tooltip: {pointFormat: "{point.x}, {point.y}: {point.value}<br/>"},
            states: {normal: {animation: !0}, hover: {brightness: 0.2}}
        });
    m.heatmap = z(m.scatter, l(g, {
        type: "heatmap",
        pointArrayMap: ["y", "value"],
        hasPointSpecificOptions: !0,
        supportsDrilldown: !0,
        getExtremesFromAll: !0,
        init: function () {
            m.scatter.prototype.init.apply(this,
                arguments);
            this.pointRange = this.options.colsize || 1;
            this.yAxis.axisPointRange = this.options.rowsize || 1
        },
        translate: function () {
            var b = this.options, a = this.xAxis, d = this.yAxis;
            this.generatePoints();
            k(this.points, function (c) {
                var e = (b.colsize || 1) / 2, h = (b.rowsize || 1) / 2, f = Math.round(a.len - a.translate(c.x - e, 0, 1, 0, 1)), e = Math.round(a.len - a.translate(c.x + e, 0, 1, 0, 1)), g = Math.round(d.translate(c.y - h, 0, 1, 0, 1)), h = Math.round(d.translate(c.y + h, 0, 1, 0, 1));
                c.plotX = (f + e) / 2;
                c.plotY = (g + h) / 2;
                c.shapeType = "rect";
                c.shapeArgs = {
                    x: Math.min(f,
                        e), y: Math.min(g, h), width: Math.abs(e - f), height: Math.abs(h - g)
                }
            });
            this.translateColors()
        },
        drawPoints: m.column.prototype.drawPoints,
        animate: n,
        getBox: n,
        drawLegendSymbol: s.drawRectangle,
        getExtremes: function () {
            t.prototype.getExtremes.call(this, this.valueData);
            this.valueMin = this.dataMin;
            this.valueMax = this.dataMax;
            t.prototype.getExtremes.call(this)
        }
    }))
})(Highcharts);
