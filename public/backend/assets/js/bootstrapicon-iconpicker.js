/*!
 * Bootstrap Icons
 * https://github.com/pixel-s-lab/bootstrapicon-iconpicker
 * This script is based on fontawesome-iconpicker by Javi Aguilar
 * @author Madalin Marius Stan, pixel.com.ro
 * @license MIT License
 * @see https://github.com/pixel-s-lab/bootstrapicon-iconpicker/blob/main/LICENSE
 */


(function (e) {
    if (typeof define === "function" && define.amd) {
        define(["jquery"], e);
    } else {
        e(jQuery);
    }
})(function (j) {
    j.ui = j.ui || {};
    var e = j.ui.version = "1.12.1";
    (function () {
        var r, y = Math.max, x = Math.abs, s = /left|center|right/, i = /top|center|bottom/,
            f = /[\+\-]\d+(\.[\d]+)?%?/, l = /^\w+/, c = /%$/, a = j.fn.pos;

        function q(e, a, t) {
            return [parseFloat(e[0]) * (c.test(e[0]) ? a / 100 : 1), parseFloat(e[1]) * (c.test(e[1]) ? t / 100 : 1)];
        }

        function C(e, a) {
            return parseInt(j.css(e, a), 10) || 0;
        }

        function t(e) {
            var a = e[0];
            if (a.nodeType === 9) {
                return {
                    width: e.width(),
                    height: e.height(),
                    offset: {
                        top: 0,
                        left: 0
                    }
                };
            }
            if (j.isWindow(a)) {
                return {
                    width: e.width(),
                    height: e.height(),
                    offset: {
                        top: e.scrollTop(),
                        left: e.scrollLeft()
                    }
                };
            }
            if (a.preventDefault) {
                return {
                    width: 0,
                    height: 0,
                    offset: {
                        top: a.pageY,
                        left: a.pageX
                    }
                };
            }
            return {
                width: e.outerWidth(),
                height: e.outerHeight(),
                offset: e.offset()
            };
        }

        j.pos = {
            scrollbarWidth: function () {
                if (r !== undefined) {
                    return r;
                }
                var e, a,
                    t = j("<div " + "style='display:block;position:absolute;width:50px;height:50px;overflow:hidden;'>" + "<div style='height:100px;width:auto;'></div></div>"),
                    s = t.children()[0];
                j("body").append(t);
                e = s.offsetWidth;
                t.css("overflow", "scroll");
                a = s.offsetWidth;
                if (e === a) {
                    a = t[0].clientWidth;
                }
                t.remove();
                return r = e - a;
            },
            getScrollInfo: function (e) {
                var a = e.isWindow || e.isDocument ? "" : e.element.css("overflow-x"),
                    t = e.isWindow || e.isDocument ? "" : e.element.css("overflow-y"),
                    s = a === "scroll" || a === "auto" && e.width < e.element[0].scrollWidth,
                    r = t === "scroll" || t === "auto" && e.height < e.element[0].scrollHeight;
                return {
                    width: r ? j.pos.scrollbarWidth() : 0,
                    height: s ? j.pos.scrollbarWidth() : 0
                };
            },
            getWithinInfo: function (e) {
                var a = j(e || window), t = j.isWindow(a[0]), s = !!a[0] && a[0].nodeType === 9, r = !t && !s;
                return {
                    element: a,
                    isWindow: t,
                    isDocument: s,
                    offset: r ? j(e).offset() : {
                        left: 0,
                        top: 0
                    },
                    scrollLeft: a.scrollLeft(),
                    scrollTop: a.scrollTop(),
                    width: a.outerWidth(),
                    height: a.outerHeight()
                };
            }
        };
        j.fn.pos = function (h) {
            if (!h || !h.of) {
                return a.apply(this, arguments);
            }
            h = j.extend({}, h);
            var m, p, d, u, T, e, g = j(h.of), b = j.pos.getWithinInfo(h.within), k = j.pos.getScrollInfo(b),
                w = (h.collision || "flip").split(" "), v = {};
            e = t(g);
            if (g[0].preventDefault) {
                h.at = "left top";
            }
            p = e.width;
            d = e.height;
            u = e.offset;
            T = j.extend({}, u);
            j.each(["my", "at"], function () {
                var e = (h[this] || "").split(" "), a, t;
                if (e.length === 1) {
                    e = s.test(e[0]) ? e.concat(["center"]) : i.test(e[0]) ? ["center"].concat(e) : ["center", "center"];
                }
                e[0] = s.test(e[0]) ? e[0] : "center";
                e[1] = i.test(e[1]) ? e[1] : "center";
                a = f.exec(e[0]);
                t = f.exec(e[1]);
                v[this] = [a ? a[0] : 0, t ? t[0] : 0];
                h[this] = [l.exec(e[0])[0], l.exec(e[1])[0]];
            });
            if (w.length === 1) {
                w[1] = w[0];
            }
            if (h.at[0] === "right") {
                T.left += p;
            } else if (h.at[0] === "center") {
                T.left += p / 2;
            }
            if (h.at[1] === "bottom") {
                T.top += d;
            } else if (h.at[1] === "center") {
                T.top += d / 2;
            }
            m = q(v.at, p, d);
            T.left += m[0];
            T.top += m[1];
            return this.each(function () {
                var t, e, f = j(this), l = f.outerWidth(), c = f.outerHeight(), a = C(this, "marginLeft"),
                    s = C(this, "marginTop"), r = l + a + C(this, "marginRight") + k.width,
                    i = c + s + C(this, "marginBottom") + k.height, o = j.extend({}, T),
                    n = q(v.my, f.outerWidth(), f.outerHeight());
                if (h.my[0] === "right") {
                    o.left -= l;
                } else if (h.my[0] === "center") {
                    o.left -= l / 2;
                }
                if (h.my[1] === "bottom") {
                    o.top -= c;
                } else if (h.my[1] === "center") {
                    o.top -= c / 2;
                }
                o.left += n[0];
                o.top += n[1];
                t = {
                    marginLeft: a,
                    marginTop: s
                };
                j.each(["left", "top"], function (e, a) {
                    if (j.ui.pos[w[e]]) {
                        j.ui.pos[w[e]][a](o, {
                            targetWidth: p,
                            targetHeight: d,
                            elemWidth: l,
                            elemHeight: c,
                            collisionPosition: t,
                            collisionWidth: r,
                            collisionHeight: i,
                            offset: [m[0] + n[0], m[1] + n[1]],
                            my: h.my,
                            at: h.at,
                            within: b,
                            elem: f
                        });
                    }
                });
                if (h.using) {
                    e = function (e) {
                        var a = u.left - o.left, t = a + p - l, s = u.top - o.top, r = s + d - c, i = {
                            target: {
                                element: g,
                                left: u.left,
                                top: u.top,
                                width: p,
                                height: d
                            },
                            element: {
                                element: f,
                                left: o.left,
                                top: o.top,
                                width: l,
                                height: c
                            },
                            horizontal: t < 0 ? "left" : a > 0 ? "right" : "center",
                            vertical: r < 0 ? "top" : s > 0 ? "bottom" : "middle"
                        };
                        if (p < l && x(a + t) < p) {
                            i.horizontal = "center";
                        }
                        if (d < c && x(s + r) < d) {
                            i.vertical = "middle";
                        }
                        if (y(x(a), x(t)) > y(x(s), x(r))) {
                            i.important = "horizontal";
                        } else {
                            i.important = "vertical";
                        }
                        h.using.call(this, e, i);
                    };
                }
                f.offset(j.extend(o, {
                    using: e
                }));
            });
        };
        j.ui.pos = {
            _trigger: function (e, a, t, s) {
                if (a.elem) {
                    a.elem.trigger({
                        type: t,
                        position: e,
                        positionData: a,
                        triggered: s
                    });
                }
            },
            fit: {
                left: function (e, a) {
                    j.ui.pos._trigger(e, a, "posCollide", "fitLeft");
                    var t = a.within, s = t.isWindow ? t.scrollLeft : t.offset.left, r = t.width,
                        i = e.left - a.collisionPosition.marginLeft, f = s - i, l = i + a.collisionWidth - r - s, c;
                    if (a.collisionWidth > r) {
                        if (f > 0 && l <= 0) {
                            c = e.left + f + a.collisionWidth - r - s;
                            e.left += f - c;
                        } else if (l > 0 && f <= 0) {
                            e.left = s;
                        } else {
                            if (f > l) {
                                e.left = s + r - a.collisionWidth;
                            } else {
                                e.left = s;
                            }
                        }
                    } else if (f > 0) {
                        e.left += f;
                    } else if (l > 0) {
                        e.left -= l;
                    } else {
                        e.left = y(e.left - i, e.left);
                    }
                    j.ui.pos._trigger(e, a, "posCollided", "fitLeft");
                },
                top: function (e, a) {
                    j.ui.pos._trigger(e, a, "posCollide", "fitTop");
                    var t = a.within, s = t.isWindow ? t.scrollTop : t.offset.top, r = a.within.height,
                        i = e.top - a.collisionPosition.marginTop, f = s - i, l = i + a.collisionHeight - r - s, c;
                    if (a.collisionHeight > r) {
                        if (f > 0 && l <= 0) {
                            c = e.top + f + a.collisionHeight - r - s;
                            e.top += f - c;
                        } else if (l > 0 && f <= 0) {
                            e.top = s;
                        } else {
                            if (f > l) {
                                e.top = s + r - a.collisionHeight;
                            } else {
                                e.top = s;
                            }
                        }
                    } else if (f > 0) {
                        e.top += f;
                    } else if (l > 0) {
                        e.top -= l;
                    } else {
                        e.top = y(e.top - i, e.top);
                    }
                    j.ui.pos._trigger(e, a, "posCollided", "fitTop");
                }
            },
            flip: {
                left: function (e, a) {
                    j.ui.pos._trigger(e, a, "posCollide", "flipLeft");
                    var t = a.within, s = t.offset.left + t.scrollLeft, r = t.width,
                        i = t.isWindow ? t.scrollLeft : t.offset.left, f = e.left - a.collisionPosition.marginLeft,
                        l = f - i, c = f + a.collisionWidth - r - i,
                        o = a.my[0] === "left" ? -a.elemWidth : a.my[0] === "right" ? a.elemWidth : 0,
                        n = a.at[0] === "left" ? a.targetWidth : a.at[0] === "right" ? -a.targetWidth : 0,
                        h = -2 * a.offset[0], m, p;
                    if (l < 0) {
                        m = e.left + o + n + h + a.collisionWidth - r - s;
                        if (m < 0 || m < x(l)) {
                            e.left += o + n + h;
                        }
                    } else if (c > 0) {
                        p = e.left - a.collisionPosition.marginLeft + o + n + h - i;
                        if (p > 0 || x(p) < c) {
                            e.left += o + n + h;
                        }
                    }
                    j.ui.pos._trigger(e, a, "posCollided", "flipLeft");
                },
                top: function (e, a) {
                    j.ui.pos._trigger(e, a, "posCollide", "flipTop");
                    var t = a.within, s = t.offset.top + t.scrollTop, r = t.height,
                        i = t.isWindow ? t.scrollTop : t.offset.top, f = e.top - a.collisionPosition.marginTop,
                        l = f - i, c = f + a.collisionHeight - r - i, o = a.my[1] === "top",
                        n = o ? -a.elemHeight : a.my[1] === "bottom" ? a.elemHeight : 0,
                        h = a.at[1] === "top" ? a.targetHeight : a.at[1] === "bottom" ? -a.targetHeight : 0,
                        m = -2 * a.offset[1], p, d;
                    if (l < 0) {
                        d = e.top + n + h + m + a.collisionHeight - r - s;
                        if (d < 0 || d < x(l)) {
                            e.top += n + h + m;
                        }
                    } else if (c > 0) {
                        p = e.top - a.collisionPosition.marginTop + n + h + m - i;
                        if (p > 0 || x(p) < c) {
                            e.top += n + h + m;
                        }
                    }
                    j.ui.pos._trigger(e, a, "posCollided", "flipTop");
                }
            },
            flipfit: {
                left: function () {
                    j.ui.pos.flip.left.apply(this, arguments);
                    j.ui.pos.fit.left.apply(this, arguments);
                },
                top: function () {
                    j.ui.pos.flip.top.apply(this, arguments);
                    j.ui.pos.fit.top.apply(this, arguments);
                }
            }
        };
        (function () {
            var e, a, t, s, r, i = document.getElementsByTagName("body")[0], f = document.createElement("div");
            e = document.createElement(i ? "div" : "body");
            t = {
                visibility: "hidden",
                width: 0,
                height: 0,
                border: 0,
                margin: 0,
                background: "none"
            };
            if (i) {
                j.extend(t, {
                    position: "absolute",
                    left: "-1000px",
                    top: "-1000px"
                });
            }
            for (r in t) {
                e.style[r] = t[r];
            }
            e.appendChild(f);
            a = i || document.documentElement;
            a.insertBefore(e, a.firstChild);
            f.style.cssText = "position: absolute; left: 10.7432222px;";
            s = j(f).offset().left;
            j.support.offsetFractions = s > 10 && s < 11;
            e.innerHTML = "";
            a.removeChild(e);
        })();
    })();
    var a = j.ui.position;
});

(function (e) {
    "use strict";
    if (typeof define === "function" && define.amd) {
        define(["jquery"], e);
    } else if (window.jQuery && !window.jQuery.fn.iconpicker) {
        e(window.jQuery);
    }
})(function (c) {
    "use strict";
    var f = {
        isEmpty: function (e) {
            return e === false || e === "" || e === null || e === undefined;
        },
        isEmptyObject: function (e) {
            return this.isEmpty(e) === true || e.length === 0;
        },
        isElement: function (e) {
            return c(e).length > 0;
        },
        isString: function (e) {
            return typeof e === "string" || e instanceof String;
        },
        isArray: function (e) {
            return c.isArray(e);
        },
        inArray: function (e, a) {
            return c.inArray(e, a) !== -1;
        },
        throwError: function (e) {
            throw "Font Awesome Icon Picker Exception: " + e;
        }
    };
    var t = function (e, a) {
        this._id = t._idCounter++;
        this.element = c(e).addClass("iconpicker-element");
        this._trigger("iconpickerCreate", {
            iconpickerValue: this.iconpickerValue
        });
        this.options = c.extend({}, t.defaultOptions, this.element.data(), a);
        this.options.templates = c.extend({}, t.defaultOptions.templates, this.options.templates);
        this.options.originalPlacement = this.options.placement;
        this.container = f.isElement(this.options.container) ? c(this.options.container) : false;
        if (this.container === false) {
            if (this.element.is(".dropdown-toggle")) {
                this.container = c("~ .dropdown-menu:first", this.element);
            } else {
                this.container = this.element.is("input,textarea,button,.btn") ? this.element.parent() : this.element;
            }
        }
        this.container.addClass("iconpicker-container");
        if (this.isDropdownMenu()) {
            this.options.placement = "inline";
        }
        this.input = this.element.is("input,textarea") ? this.element.addClass("iconpicker-input") : false;
        if (this.input === false) {
            this.input = this.container.find(this.options.input);
            if (!this.input.is("input,textarea")) {
                this.input = false;
            }
        }
        this.component = this.isDropdownMenu() ? this.container.parent().find(this.options.component) : this.container.find(this.options.component);
        if (this.component.length === 0) {
            this.component = false;
        } else {
            this.component.find("i").addClass("iconpicker-component");
        }
        this._createPopover();
        this._createIconpicker();
        if (this.getAcceptButton().length === 0) {
            this.options.mustAccept = false;
        }
        if (this.isInputGroup()) {
            this.container.parent().append(this.popover);
        } else {
            this.container.append(this.popover);
        }
        this._bindElementEvents();
        this._bindWindowEvents();
        this.update(this.options.selected);
        if (this.isInline()) {
            this.show();
        }
        this._trigger("iconpickerCreated", {
            iconpickerValue: this.iconpickerValue
        });
    };
    t._idCounter = 0;
    t.defaultOptions = {
        title: false,
        selected: false,
        defaultValue: false,
        placement: "bottom",
        collision: "none",
        animation: true,
        hideOnSelect: false,
        showFooter: false,
        searchInFooter: false,
        mustAccept: false,
        selectedCustomClass: "bg-primary",
        icons: [],
        fullClassFormatter: function (e) {
            return e;
        },
        input: "input,.iconpicker-input",
        inputSearch: false,
        container: false,
        component: ".input-group-addon,.iconpicker-component",
        templates: {
            popover: '<div class="iconpicker-popover popover" role="tooltip"><div class="arrow"></div>' + '<div class="popover-title"></div><div class="popover-content"></div></div>',
            footer: '<div class="popover-footer"></div>',
            buttons: '<button class="iconpicker-btn iconpicker-btn-cancel btn btn-default btn-sm">Cancel</button>' + ' <button class="iconpicker-btn iconpicker-btn-accept btn btn-primary btn-sm">Accept</button>',
            search: '<input type="search" class="form-control iconpicker-search" placeholder="Type to filter" />',
            iconpicker: '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
            iconpickerItem: '<a role="button" href="javascript:;" class="iconpicker-item"><i></i></a>'
        }
    };
    t.batch = function (e, a) {
        var t = Array.prototype.slice.call(arguments, 2);
        return c(e).each(function () {
            var e = c(this).data("iconpicker");
            if (!!e) {
                e[a].apply(e, t);
            }
        });
    };
    t.prototype = {
        constructor: t,
        options: {},
        _id: 0,
        _trigger: function (e, a) {
            a = a || {};
            this.element.trigger(c.extend({
                type: e,
                iconpickerInstance: this
            }, a));
        },
        _createPopover: function () {
            this.popover = c(this.options.templates.popover);
            var e = this.popover.find(".popover-title");
            if (!!this.options.title) {
                e.append(c('<div class="popover-title-text">' + this.options.title + "</div>"));
            }
            if (this.hasSeparatedSearchInput() && !this.options.searchInFooter) {
                e.append(this.options.templates.search);
            } else if (!this.options.title) {
                e.remove();
            }
            if (this.options.showFooter && !f.isEmpty(this.options.templates.footer)) {
                var a = c(this.options.templates.footer);
                if (this.hasSeparatedSearchInput() && this.options.searchInFooter) {
                    a.append(c(this.options.templates.search));
                }
                if (!f.isEmpty(this.options.templates.buttons)) {
                    a.append(c(this.options.templates.buttons));
                }
                this.popover.append(a);
            }
            if (this.options.animation === true) {
                this.popover.addClass("fade show");
            }
            return this.popover;
        },
        _createIconpicker: function () {
            var t = this;
            this.iconpicker = c(this.options.templates.iconpicker);
            var e = function (e) {
                var a = c(this);
                if (a.is("i")) {
                    a = a.parent();
                }
                t._trigger("iconpickerSelect", {
                    iconpickerItem: a,
                    iconpickerValue: t.iconpickerValue
                });
                if (t.options.mustAccept === false) {
                    t.update(a.data("iconpickerValue"));
                    t._trigger("iconpickerSelected", {
                        iconpickerItem: this,
                        iconpickerValue: t.iconpickerValue
                    });
                } else {
                    t.update(a.data("iconpickerValue"), true);
                }
                if (t.options.hideOnSelect && t.options.mustAccept === false) {
                    t.hide();
                }
            };
            var a = c(this.options.templates.iconpickerItem);
            var s = [];
            for (var r in this.options.icons) {
                if (typeof this.options.icons[r].title === "string") {
                    var i = a.clone();
                    i.find("i").addClass(this.options.fullClassFormatter(this.options.icons[r].title));
                    i.data("iconpickerValue", this.options.icons[r].title).on("click.iconpicker", e);
                    i.attr("title", "." + this.options.icons[r].title);
                    if (this.options.icons[r].searchTerms.length > 0) {
                        var f = "";
                        for (var l = 0; l < this.options.icons[r].searchTerms.length; l++) {
                            f = f + this.options.icons[r].searchTerms[l] + " ";
                        }
                        i.attr("data-search-terms", f);
                    }
                    s.push(i);
                }
            }
            this.iconpicker.find(".iconpicker-items").append(s);
            this.popover.find(".popover-content").append(this.iconpicker);
            return this.iconpicker;
        },
        _isEventInsideIconpicker: function (e) {
            var a = c(e.target);
            if ((!a.hasClass("iconpicker-element") || a.hasClass("iconpicker-element") && !a.is(this.element)) && a.parents(".iconpicker-popover").length === 0) {
                return false;
            }
            return true;
        },
        _bindElementEvents: function () {
            var a = this;
            this.getSearchInput().on("keyup.iconpicker", function () {
                a.filter(c(this).val().toLowerCase());
            });
            this.getAcceptButton().on("click.iconpicker", function () {
                var e = a.iconpicker.find(".iconpicker-selected").get(0);
                a.update(a.iconpickerValue);
                a._trigger("iconpickerSelected", {
                    iconpickerItem: e,
                    iconpickerValue: a.iconpickerValue
                });
                if (!a.isInline()) {
                    a.hide();
                }
            });
            this.getCancelButton().on("click.iconpicker", function () {
                if (!a.isInline()) {
                    a.hide();
                }
            });
            this.element.on("focus.iconpicker", function (e) {
                a.show();
                e.stopPropagation();
            });
            if (this.hasComponent()) {
                this.component.on("click.iconpicker", function () {
                    a.toggle();
                });
            }
            if (this.hasInput()) {
                this.input.on("keyup.iconpicker", function (e) {
                    if (!f.inArray(e.keyCode, [38, 40, 37, 39, 16, 17, 18, 9, 8, 91, 93, 20, 46, 186, 190, 46, 78, 188, 44, 86])) {
                        a.update();
                    } else {
                        a._updateFormGroupStatus(a.getValid(this.value) !== false);
                    }
                    if (a.options.inputSearch === true) {
                        a.filter(c(this).val().toLowerCase());
                    }
                });
            }
        },
        _bindWindowEvents: function () {
            var e = c(window.document);
            var a = this;
            var t = ".iconpicker.inst" + this._id;
            c(window).on("resize.iconpicker" + t + " orientationchange.iconpicker" + t, function (e) {
                if (a.popover.hasClass("in")) {
                    a.updatePlacement();
                }
            });
            if (!a.isInline()) {
                e.on("mouseup" + t, function (e) {
                    if (!a._isEventInsideIconpicker(e) && !a.isInline()) {
                        a.hide();
                    }
                });
            }
        },
        _unbindElementEvents: function () {
            this.popover.off(".iconpicker");
            this.element.off(".iconpicker");
            if (this.hasInput()) {
                this.input.off(".iconpicker");
            }
            if (this.hasComponent()) {
                this.component.off(".iconpicker");
            }
            if (this.hasContainer()) {
                this.container.off(".iconpicker");
            }
        },
        _unbindWindowEvents: function () {
            c(window).off(".iconpicker.inst" + this._id);
            c(window.document).off(".iconpicker.inst" + this._id);
        },
        updatePlacement: function (e, a) {
            e = e || this.options.placement;
            this.options.placement = e;
            a = a || this.options.collision;
            a = a === true ? "flip" : a;
            var t = {
                at: "right bottom",
                my: "right top",
                of: this.hasInput() && !this.isInputGroup() ? this.input : this.container,
                collision: a === true ? "flip" : a,
                within: window
            };
            this.popover.removeClass("inline topLeftCorner topLeft top topRight topRightCorner " + "rightTop right rightBottom bottomRight bottomRightCorner " + "bottom bottomLeft bottomLeftCorner leftBottom left leftTop");
            if (typeof e === "object") {
                return this.popover.pos(c.extend({}, t, e));
            }
            switch (e) {
                case "inline": {
                    t = false;
                }
                    break;

                case "topLeftCorner": {
                    t.my = "right bottom";
                    t.at = "left top";
                }
                    break;

                case "topLeft": {
                    t.my = "left bottom";
                    t.at = "left top";
                }
                    break;

                case "top": {
                    t.my = "center bottom";
                    t.at = "center top";
                }
                    break;

                case "topRight": {
                    t.my = "right bottom";
                    t.at = "right top";
                }
                    break;

                case "topRightCorner": {
                    t.my = "left bottom";
                    t.at = "right top";
                }
                    break;

                case "rightTop": {
                    t.my = "left bottom";
                    t.at = "right center";
                }
                    break;

                case "right": {
                    t.my = "left center";
                    t.at = "right center";
                }
                    break;

                case "rightBottom": {
                    t.my = "left top";
                    t.at = "right center";
                }
                    break;

                case "bottomRightCorner": {
                    t.my = "left top";
                    t.at = "right bottom";
                }
                    break;

                case "bottomRight": {
                    t.my = "right top";
                    t.at = "right bottom";
                }
                    break;

                case "bottom": {
                    t.my = "center top";
                    t.at = "center bottom";
                }
                    break;

                case "bottomLeft": {
                    t.my = "left top";
                    t.at = "left bottom";
                }
                    break;

                case "bottomLeftCorner": {
                    t.my = "right top";
                    t.at = "left bottom";
                }
                    break;

                case "leftBottom": {
                    t.my = "right top";
                    t.at = "left center";
                }
                    break;

                case "left": {
                    t.my = "right center";
                    t.at = "left center";
                }
                    break;

                case "leftTop": {
                    t.my = "right bottom";
                    t.at = "left center";
                }
                    break;

                default: {
                    return false;
                }
                    break;
            }
            this.popover.css({
                display: this.options.placement === "inline" ? "" : "block"
            });
            if (t !== false) {
                this.popover.pos(t).css("maxWidth", c(window).width() - this.container.offset().left - 5);
            } else {
                this.popover.css({
                    top: "auto",
                    right: "auto",
                    bottom: "auto",
                    left: "auto",
                    maxWidth: "none"
                });
            }
            this.popover.addClass(this.options.placement);
            return true;
        },
        _updateComponents: function () {
            this.iconpicker.find(".iconpicker-item.iconpicker-selected").removeClass("iconpicker-selected " + this.options.selectedCustomClass);
            if (this.iconpickerValue) {
                this.iconpicker.find("." + this.options.fullClassFormatter(this.iconpickerValue).replace(/ /g, ".")).parent().addClass("iconpicker-selected " + this.options.selectedCustomClass);
            }
            if (this.hasComponent()) {
                var e = this.component.find("i");
                if (e.length > 0) {
                    e.attr("class", this.options.fullClassFormatter(this.iconpickerValue));
                } else {
                    this.component.html(this.getHtml());
                }
            }
        },
        _updateFormGroupStatus: function (e) {
            if (this.hasInput()) {
                if (e !== false) {
                    this.input.parents(".form-group:first").removeClass("has-error");
                } else {
                    this.input.parents(".form-group:first").addClass("has-error");
                }
                return true;
            }
            return false;
        },
        getValid: function (e) {
            if (!f.isString(e)) {
                e = "";
            }
            var a = e === "";
            e = c.trim(e);
            var t = false;
            for (var s = 0; s < this.options.icons.length; s++) {
                if (this.options.icons[s].title === e) {
                    t = true;
                    break;
                }
            }
            if (t || a) {
                return e;
            }
            return false;
        },
        setValue: function (e) {
            var a = this.getValid(e);
            if (a !== false) {
                this.iconpickerValue = a;
                this._trigger("iconpickerSetValue", {
                    iconpickerValue: a
                });
                return this.iconpickerValue;
            } else {
                this._trigger("iconpickerInvalid", {
                    iconpickerValue: e
                });
                return false;
            }
        },
        getHtml: function () {
            return '<i class="' + this.options.fullClassFormatter(this.iconpickerValue) + '"></i>';
        },
        setSourceValue: function (e) {
            e = this.setValue(e);
            if (e !== false && e !== "") {
                if (this.hasInput()) {
                    this.input.val(this.iconpickerValue);
                } else {
                    this.element.data("iconpickerValue", this.iconpickerValue);
                }
                this._trigger("iconpickerSetSourceValue", {
                    iconpickerValue: e
                });
            }
            return e;
        },
        getSourceValue: function (e) {
            e = e || this.options.defaultValue;
            var a = e;
            if (this.hasInput()) {
                a = this.input.val();
            } else {
                a = this.element.data("iconpickerValue");
            }
            if (a === undefined || a === "" || a === null || a === false) {
                a = e;
            }
            return a;
        },
        hasInput: function () {
            return this.input !== false;
        },
        isInputSearch: function () {
            return this.hasInput() && this.options.inputSearch === true;
        },
        isInputGroup: function () {
            return this.container.is(".input-group");
        },
        isDropdownMenu: function () {
            return this.container.is(".dropdown-menu");
        },
        hasSeparatedSearchInput: function () {
            return this.options.templates.search !== false && !this.isInputSearch();
        },
        hasComponent: function () {
            return this.component !== false;
        },
        hasContainer: function () {
            return this.container !== false;
        },
        getAcceptButton: function () {
            return this.popover.find(".iconpicker-btn-accept");
        },
        getCancelButton: function () {
            return this.popover.find(".iconpicker-btn-cancel");
        },
        getSearchInput: function () {
            return this.popover.find(".iconpicker-search");
        },
        filter: function (r) {
            if (f.isEmpty(r)) {
                this.iconpicker.find(".iconpicker-item").show();
                return c(false);
            } else {
                var i = [];
                this.iconpicker.find(".iconpicker-item").each(function () {
                    var e = c(this);
                    var a = e.attr("title").toLowerCase();
                    var t = e.attr("data-search-terms") ? e.attr("data-search-terms").toLowerCase() : "";
                    a = a + " " + t;
                    var s = false;
                    try {
                        s = new RegExp("(^|\\W)" + r, "g");
                    } catch (e) {
                        s = false;
                    }
                    if (s !== false && a.match(s)) {
                        i.push(e);
                        e.show();
                    } else {
                        e.hide();
                    }
                });
                return i;
            }
        },
        show: function () {
            if (this.popover.hasClass("in")) {
                return false;
            }
            c.iconpicker.batch(c(".iconpicker-popover.in:not(.inline)").not(this.popover), "hide");
            this._trigger("iconpickerShow", {
                iconpickerValue: this.iconpickerValue
            });
            this.updatePlacement();
            this.popover.addClass("in");
            setTimeout(c.proxy(function () {
                this.popover.css("display", this.isInline() ? "" : "block");
                this._trigger("iconpickerShown", {
                    iconpickerValue: this.iconpickerValue
                });
            }, this), this.options.animation ? 300 : 1);
        },
        hide: function () {
            if (!this.popover.hasClass("in")) {
                return false;
            }
            this._trigger("iconpickerHide", {
                iconpickerValue: this.iconpickerValue
            });
            this.popover.removeClass("in");
            setTimeout(c.proxy(function () {
                this.popover.css("display", "none");
                this.getSearchInput().val("");
                this.filter("");
                this._trigger("iconpickerHidden", {
                    iconpickerValue: this.iconpickerValue
                });
            }, this), this.options.animation ? 300 : 1);
        },
        toggle: function () {
            if (this.popover.is(":visible")) {
                this.hide();
            } else {
                this.show(true);
            }
        },
        update: function (e, a) {
            e = e ? e : this.getSourceValue(this.iconpickerValue);
            this._trigger("iconpickerUpdate", {
                iconpickerValue: this.iconpickerValue
            });
            if (a === true) {
                e = this.setValue(e);
            } else {
                e = this.setSourceValue(e);
                this._updateFormGroupStatus(e !== false);
            }
            if (e !== false) {
                this._updateComponents();
            }
            this._trigger("iconpickerUpdated", {
                iconpickerValue: this.iconpickerValue
            });
            return e;
        },
        destroy: function () {
            this._trigger("iconpickerDestroy", {
                iconpickerValue: this.iconpickerValue
            });
            this.element.removeData("iconpicker").removeData("iconpickerValue").removeClass("iconpicker-element");
            this._unbindElementEvents();
            this._unbindWindowEvents();
            c(this.popover).remove();
            this._trigger("iconpickerDestroyed", {
                iconpickerValue: this.iconpickerValue
            });
        },
        disable: function () {
            if (this.hasInput()) {
                this.input.prop("disabled", true);
                return true;
            }
            return false;
        },
        enable: function () {
            if (this.hasInput()) {
                this.input.prop("disabled", false);
                return true;
            }
            return false;
        },
        isDisabled: function () {
            if (this.hasInput()) {
                return this.input.prop("disabled") === true;
            }
            return false;
        },
        isInline: function () {
            return this.options.placement === "inline" || this.popover.hasClass("inline");
        }
    };
    c.iconpicker = t;
    c.fn.iconpicker = function (a) {
        return this.each(function () {
            var e = c(this);
            if (!e.data("iconpicker")) {
                e.data("iconpicker", new t(this, typeof a === "object" ? a : {}));
            }
        });
    };
    t.defaultOptions = c.extend(t.defaultOptions, {
        icons: [
            {
                title: "fi-rr-0",
                searchTerms: []
            },
            {
                title: "fi-rr-00s-music-disc",
                searchTerms: []
            },
            {
                title: "fi-rr-1",
                searchTerms: []
            },
            {
                title: "fi-rr-2",
                searchTerms: []
            },
            {
                title: "fi-rr-3",
                searchTerms: []
            },
            {
                title: "fi-rr-360-degrees",
                searchTerms: []
            },
            {
                title: "fi-rr-4",
                searchTerms: []
            },
            {
                title: "fi-rr-404",
                searchTerms: []
            },
            {
                title: "fi-rr-5",
                searchTerms: []
            },
            {
                title: "fi-rr-500px",
                searchTerms: []
            },
            {
                title: "fi-rr-6",
                searchTerms: []
            },
            {
                title: "fi-rr-60s-music-disc",
                searchTerms: []
            },
            {
                title: "fi-rr-7",
                searchTerms: []
            },
            {
                title: "fi-rr-70s-music-disc",
                searchTerms: []
            },
            {
                title: "fi-rr-8",
                searchTerms: []
            },
            {
                title: "fi-rr-80s-music-disc",
                searchTerms: []
            },
            {
                title: "fi-rr-9",
                searchTerms: []
            },
            {
                title: "fi-rr-90s-music-disc",
                searchTerms: []
            },
            {
                title: "fi-rr-a",
                searchTerms: []
            },
            {
                title: "fi-rr-abacus",
                searchTerms: []
            },
            {
                title: "fi-rr-accident",
                searchTerms: []
            },
            {
                title: "fi-rr-acorn",
                searchTerms: []
            },
            {
                title: "fi-rr-ad",
                searchTerms: []
            },
            {
                title: "fi-rr-ad-forbidden",
                searchTerms: []
            },
            {
                title: "fi-rr-ad-paid",
                searchTerms: []
            },
            {
                title: "fi-rr-add",
                searchTerms: []
            },
            {
                title: "fi-rr-add-document",
                searchTerms: []
            },
            {
                title: "fi-rr-add-folder",
                searchTerms: []
            },
            {
                title: "fi-rr-add-image",
                searchTerms: []
            },
            {
                title: "fi-rr-address-book",
                searchTerms: []
            },
            {
                title: "fi-rr-address-card",
                searchTerms: []
            },
            {
                title: "fi-rr-admin",
                searchTerms: []
            },
            {
                title: "fi-rr-admin-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-age",
                searchTerms: []
            },
            {
                title: "fi-rr-age-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-age-restriction-eighteen",
                searchTerms: []
            },
            {
                title: "fi-rr-age-restriction-seven",
                searchTerms: []
            },
            {
                title: "fi-rr-age-restriction-six",
                searchTerms: []
            },
            {
                title: "fi-rr-age-restriction-sixteen",
                searchTerms: []
            },
            {
                title: "fi-rr-age-restriction-thirteen",
                searchTerms: []
            },
            {
                title: "fi-rr-age-restriction-three",
                searchTerms: []
            },
            {
                title: "fi-rr-age-restriction-twelve",
                searchTerms: []
            },
            {
                title: "fi-rr-age-restriction-twenty-one",
                searchTerms: []
            },
            {
                title: "fi-rr-age-restriction-zero",
                searchTerms: []
            },
            {
                title: "fi-rr-air-conditioner",
                searchTerms: []
            },
            {
                title: "fi-rr-air-freshener",
                searchTerms: []
            },
            {
                title: "fi-rr-air-pollution",
                searchTerms: []
            },
            {
                title: "fi-rr-airplane-journey",
                searchTerms: []
            },
            {
                title: "fi-rr-airplane-window-open",
                searchTerms: []
            },
            {
                title: "fi-rr-airplay",
                searchTerms: []
            },
            {
                title: "fi-rr-alarm-clock",
                searchTerms: []
            },
            {
                title: "fi-rr-alarm-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-alarm-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-alarm-snooze",
                searchTerms: []
            },
            {
                title: "fi-rr-album",
                searchTerms: []
            },
            {
                title: "fi-rr-album-circle-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-album-circle-user",
                searchTerms: []
            },
            {
                title: "fi-rr-album-collection",
                searchTerms: []
            },
            {
                title: "fi-rr-algorithm",
                searchTerms: []
            },
            {
                title: "fi-rr-alicorn",
                searchTerms: []
            },
            {
                title: "fi-rr-alien",
                searchTerms: []
            },
            {
                title: "fi-rr-align-center",
                searchTerms: []
            },
            {
                title: "fi-rr-align-justify",
                searchTerms: []
            },
            {
                title: "fi-rr-align-left",
                searchTerms: []
            },
            {
                title: "fi-rr-align-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-ambulance",
                searchTerms: []
            },
            {
                title: "fi-rr-amp-guitar",
                searchTerms: []
            },
            {
                title: "fi-rr-analyse",
                searchTerms: []
            },
            {
                title: "fi-rr-analyse-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-anatomical-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-anchor",
                searchTerms: []
            },
            {
                title: "fi-rr-angel",
                searchTerms: []
            },
            {
                title: "fi-rr-angle",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-90",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-circle-down",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-circle-left",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-circle-right",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-circle-up",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-double-left",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-double-right",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-double-small-down",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-double-small-left",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-double-small-right",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-double-small-up",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-down",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-left",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-right",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-small-down",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-small-left",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-small-right",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-small-up",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-square-down",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-square-left",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-square-right",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-square-up",
                searchTerms: []
            },
            {
                title: "fi-rr-angle-up",
                searchTerms: []
            },
            {
                title: "fi-rr-angles-up-down",
                searchTerms: []
            },
            {
                title: "fi-rr-angry",
                searchTerms: []
            },
            {
                title: "fi-rr-animated-icon",
                searchTerms: []
            },
            {
                title: "fi-rr-ankh",
                searchTerms: []
            },
            {
                title: "fi-rr-answer",
                searchTerms: []
            },
            {
                title: "fi-rr-answer-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-apartment",
                searchTerms: []
            },
            {
                title: "fi-rr-aperture",
                searchTerms: []
            },
            {
                title: "fi-rr-api",
                searchTerms: []
            },
            {
                title: "fi-rr-app-notification",
                searchTerms: []
            },
            {
                title: "fi-rr-apple-books",
                searchTerms: []
            },
            {
                title: "fi-rr-apple-core",
                searchTerms: []
            },
            {
                title: "fi-rr-apple-crate",
                searchTerms: []
            },
            {
                title: "fi-rr-apple-whole",
                searchTerms: []
            },
            {
                title: "fi-rr-apps",
                searchTerms: []
            },
            {
                title: "fi-rr-apps-add",
                searchTerms: []
            },
            {
                title: "fi-rr-apps-delete",
                searchTerms: []
            },
            {
                title: "fi-rr-apps-sort",
                searchTerms: []
            },
            {
                title: "fi-rr-archive",
                searchTerms: []
            },
            {
                title: "fi-rr-archway",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-alt-circle-down",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-alt-circle-left",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-alt-circle-right",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-alt-circle-up",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-alt-down",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-alt-from-bottom",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-alt-from-left",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-alt-from-right",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-alt-from-top",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-alt-left",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-alt-right",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-alt-square-down",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-alt-square-left",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-alt-square-right",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-alt-square-up",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-alt-to-bottom",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-alt-to-left",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-alt-to-right",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-alt-to-top",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-alt-up",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-circle-down",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-circle-left",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-circle-right",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-circle-up",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-comparison",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-down",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-down-from-arc",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-down-from-dotted-line",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-down-left",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-down-small-big",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-down-strenght",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-down-to-dotted-line",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-down-to-square",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-down-triangle-square",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-from-bottom",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-from-left",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-from-right",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-from-top",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-left",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-left-from-arc",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-left-from-line",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-left-to-arc",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-progress",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-progress-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-right",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-right-to-bracket",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-right-to-city",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-small-down",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-small-left",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-small-right",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-small-up",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-square-down",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-square-left",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-square-right",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-square-up",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-to-bottom",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-to-left",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-to-right",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-to-top",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-trend-down",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-trend-up",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-turn-down-left",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-turn-down-right",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-turn-left-up",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-up",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-up-from-dotted-line",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-up-from-ground-water",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-up-from-square",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-up-left",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-up-left-from-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-up-right",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-up-right-and-arrow-down-left-from-center",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-up-right-from-square",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-up-small-big",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-up-square-triangle",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-up-to-arc",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-up-to-dotted-line",
                searchTerms: []
            },
            {
                title: "fi-rr-arrow-upward-growth-crypto",
                searchTerms: []
            },
            {
                title: "fi-rr-arrows",
                searchTerms: []
            },
            {
                title: "fi-rr-arrows-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-arrows-alt-h",
                searchTerms: []
            },
            {
                title: "fi-rr-arrows-alt-v",
                searchTerms: []
            },
            {
                title: "fi-rr-arrows-cross",
                searchTerms: []
            },
            {
                title: "fi-rr-arrows-down-curve",
                searchTerms: []
            },
            {
                title: "fi-rr-arrows-down-to-people",
                searchTerms: []
            },
            {
                title: "fi-rr-arrows-from-dotted-line",
                searchTerms: []
            },
            {
                title: "fi-rr-arrows-from-line",
                searchTerms: []
            },
            {
                title: "fi-rr-arrows-h",
                searchTerms: []
            },
            {
                title: "fi-rr-arrows-h-copy",
                searchTerms: []
            },
            {
                title: "fi-rr-arrows-repeat",
                searchTerms: []
            },
            {
                title: "fi-rr-arrows-repeat-1",
                searchTerms: []
            },
            {
                title: "fi-rr-arrows-retweet",
                searchTerms: []
            },
            {
                title: "fi-rr-arrows-split-right-and-left",
                searchTerms: []
            },
            {
                title: "fi-rr-arrows-split-up-and-left",
                searchTerms: []
            },
            {
                title: "fi-rr-arrows-to-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-arrows-to-dotted-line",
                searchTerms: []
            },
            {
                title: "fi-rr-arrows-to-eye",
                searchTerms: []
            },
            {
                title: "fi-rr-arrows-to-line",
                searchTerms: []
            },
            {
                title: "fi-rr-artificial-intelligence",
                searchTerms: []
            },
            {
                title: "fi-rr-assept-document",
                searchTerms: []
            },
            {
                title: "fi-rr-assessment",
                searchTerms: []
            },
            {
                title: "fi-rr-assessment-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-assign",
                searchTerms: []
            },
            {
                title: "fi-rr-assistive-listening-systems",
                searchTerms: []
            },
            {
                title: "fi-rr-asterik",
                searchTerms: []
            },
            {
                title: "fi-rr-at",
                searchTerms: []
            },
            {
                title: "fi-rr-attention-detail",
                searchTerms: []
            },
            {
                title: "fi-rr-attribution-pen",
                searchTerms: []
            },
            {
                title: "fi-rr-attribution-pencil",
                searchTerms: []
            },
            {
                title: "fi-rr-aubergine",
                searchTerms: []
            },
            {
                title: "fi-rr-auction",
                searchTerms: []
            },
            {
                title: "fi-rr-audience-megaphone",
                searchTerms: []
            },
            {
                title: "fi-rr-audio-description-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-audit",
                searchTerms: []
            },
            {
                title: "fi-rr-audit-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-austral-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-australia",
                searchTerms: []
            },
            {
                title: "fi-rr-australia-country-code",
                searchTerms: []
            },
            {
                title: "fi-rr-australia-flag",
                searchTerms: []
            },
            {
                title: "fi-rr-auto-pilot",
                searchTerms: []
            },
            {
                title: "fi-rr-auto-reply",
                searchTerms: []
            },
            {
                title: "fi-rr-auto-sync",
                searchTerms: []
            },
            {
                title: "fi-rr-avocado",
                searchTerms: []
            },
            {
                title: "fi-rr-award",
                searchTerms: []
            },
            {
                title: "fi-rr-axe",
                searchTerms: []
            },
            {
                title: "fi-rr-axe-battle",
                searchTerms: []
            },
            {
                title: "fi-rr-b",
                searchTerms: []
            },
            {
                title: "fi-rr-baby",
                searchTerms: []
            },
            {
                title: "fi-rr-baby-carriage",
                searchTerms: []
            },
            {
                title: "fi-rr-back-up",
                searchTerms: []
            },
            {
                title: "fi-rr-background",
                searchTerms: []
            },
            {
                title: "fi-rr-backpack",
                searchTerms: []
            },
            {
                title: "fi-rr-bacon",
                searchTerms: []
            },
            {
                title: "fi-rr-bacteria",
                searchTerms: []
            },
            {
                title: "fi-rr-bacterium",
                searchTerms: []
            },
            {
                title: "fi-rr-badge",
                searchTerms: []
            },
            {
                title: "fi-rr-badge-check",
                searchTerms: []
            },
            {
                title: "fi-rr-badge-dollar",
                searchTerms: []
            },
            {
                title: "fi-rr-badge-leaf",
                searchTerms: []
            },
            {
                title: "fi-rr-badge-percent",
                searchTerms: []
            },
            {
                title: "fi-rr-badge-sheriff",
                searchTerms: []
            },
            {
                title: "fi-rr-badger-honey",
                searchTerms: []
            },
            {
                title: "fi-rr-badget-check-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-badminton",
                searchTerms: []
            },
            {
                title: "fi-rr-bag-map-pin",
                searchTerms: []
            },
            {
                title: "fi-rr-bag-seedling",
                searchTerms: []
            },
            {
                title: "fi-rr-bag-shopping-minus",
                searchTerms: []
            },
            {
                title: "fi-rr-bags-shopping",
                searchTerms: []
            },
            {
                title: "fi-rr-baguette",
                searchTerms: []
            },
            {
                title: "fi-rr-bahai",
                searchTerms: []
            },
            {
                title: "fi-rr-baht-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-balance-scale-left",
                searchTerms: []
            },
            {
                title: "fi-rr-balance-scale-right",
                searchTerms: []
            },
            {
                title: "fi-rr-balcony",
                searchTerms: []
            },
            {
                title: "fi-rr-ball",
                searchTerms: []
            },
            {
                title: "fi-rr-ball-pile",
                searchTerms: []
            },
            {
                title: "fi-rr-ballet-dance",
                searchTerms: []
            },
            {
                title: "fi-rr-balloon",
                searchTerms: []
            },
            {
                title: "fi-rr-balloons",
                searchTerms: []
            },
            {
                title: "fi-rr-ballot",
                searchTerms: []
            },
            {
                title: "fi-rr-ballot-check",
                searchTerms: []
            },
            {
                title: "fi-rr-ban",
                searchTerms: []
            },
            {
                title: "fi-rr-ban-bug",
                searchTerms: []
            },
            {
                title: "fi-rr-banana",
                searchTerms: []
            },
            {
                title: "fi-rr-band-aid",
                searchTerms: []
            },
            {
                title: "fi-rr-bandage-wound",
                searchTerms: []
            },
            {
                title: "fi-rr-bangladeshi-taka-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-banjo",
                searchTerms: []
            },
            {
                title: "fi-rr-bank",
                searchTerms: []
            },
            {
                title: "fi-rr-bank-app",
                searchTerms: []
            },
            {
                title: "fi-rr-banner",
                searchTerms: []
            },
            {
                title: "fi-rr-banner-2",
                searchTerms: []
            },
            {
                title: "fi-rr-banner-3",
                searchTerms: []
            },
            {
                title: "fi-rr-banner-4",
                searchTerms: []
            },
            {
                title: "fi-rr-banner-5",
                searchTerms: []
            },
            {
                title: "fi-rr-barber-pole",
                searchTerms: []
            },
            {
                title: "fi-rr-barber-shop",
                searchTerms: []
            },
            {
                title: "fi-rr-barcode",
                searchTerms: []
            },
            {
                title: "fi-rr-barcode-read",
                searchTerms: []
            },
            {
                title: "fi-rr-barcode-scan",
                searchTerms: []
            },
            {
                title: "fi-rr-barefoot",
                searchTerms: []
            },
            {
                title: "fi-rr-bars-filter",
                searchTerms: []
            },
            {
                title: "fi-rr-bars-progress",
                searchTerms: []
            },
            {
                title: "fi-rr-bars-sort",
                searchTerms: []
            },
            {
                title: "fi-rr-bars-staggered",
                searchTerms: []
            },
            {
                title: "fi-rr-baseball",
                searchTerms: []
            },
            {
                title: "fi-rr-baseball-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-basket",
                searchTerms: []
            },
            {
                title: "fi-rr-basket-shopping-minus",
                searchTerms: []
            },
            {
                title: "fi-rr-basket-shopping-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-basket-shopping-simple",
                searchTerms: []
            },
            {
                title: "fi-rr-basketball",
                searchTerms: []
            },
            {
                title: "fi-rr-bat",
                searchTerms: []
            },
            {
                title: "fi-rr-bath",
                searchTerms: []
            },
            {
                title: "fi-rr-battery-100",
                searchTerms: []
            },
            {
                title: "fi-rr-battery-bolt",
                searchTerms: []
            },
            {
                title: "fi-rr-battery-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-battery-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-battery-full",
                searchTerms: []
            },
            {
                title: "fi-rr-battery-half",
                searchTerms: []
            },
            {
                title: "fi-rr-battery-quarter",
                searchTerms: []
            },
            {
                title: "fi-rr-battery-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-battery-three-quarters",
                searchTerms: []
            },
            {
                title: "fi-rr-beacon",
                searchTerms: []
            },
            {
                title: "fi-rr-beauty-mask",
                searchTerms: []
            },
            {
                title: "fi-rr-bed",
                searchTerms: []
            },
            {
                title: "fi-rr-bed-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-bed-bunk",
                searchTerms: []
            },
            {
                title: "fi-rr-bed-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-bed-pulse",
                searchTerms: []
            },
            {
                title: "fi-rr-bee",
                searchTerms: []
            },
            {
                title: "fi-rr-beer",
                searchTerms: []
            },
            {
                title: "fi-rr-beer-mug-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-bell",
                searchTerms: []
            },
            {
                title: "fi-rr-bell-concierge",
                searchTerms: []
            },
            {
                title: "fi-rr-bell-notification-call",
                searchTerms: []
            },
            {
                title: "fi-rr-bell-notification-social-media",
                searchTerms: []
            },
            {
                title: "fi-rr-bell-ring",
                searchTerms: []
            },
            {
                title: "fi-rr-bell-school",
                searchTerms: []
            },
            {
                title: "fi-rr-bell-school-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-bell-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-bells",
                searchTerms: []
            },
            {
                title: "fi-rr-bench-tree",
                searchTerms: []
            },
            {
                title: "fi-rr-benefit",
                searchTerms: []
            },
            {
                title: "fi-rr-benefit-diamond",
                searchTerms: []
            },
            {
                title: "fi-rr-benefit-diamond-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-benefit-hand",
                searchTerms: []
            },
            {
                title: "fi-rr-benefit-increase",
                searchTerms: []
            },
            {
                title: "fi-rr-benefit-porcent",
                searchTerms: []
            },
            {
                title: "fi-rr-betamax",
                searchTerms: []
            },
            {
                title: "fi-rr-bible",
                searchTerms: []
            },
            {
                title: "fi-rr-bicycle-journey",
                searchTerms: []
            },
            {
                title: "fi-rr-big-drop",
                searchTerms: []
            },
            {
                title: "fi-rr-bike",
                searchTerms: []
            },
            {
                title: "fi-rr-bike-moving",
                searchTerms: []
            },
            {
                title: "fi-rr-bike-path",
                searchTerms: []
            },
            {
                title: "fi-rr-biking",
                searchTerms: []
            },
            {
                title: "fi-rr-biking-mountain",
                searchTerms: []
            },
            {
                title: "fi-rr-bill-arrow",
                searchTerms: []
            },
            {
                title: "fi-rr-billiard",
                searchTerms: []
            },
            {
                title: "fi-rr-bin-bottles",
                searchTerms: []
            },
            {
                title: "fi-rr-binary",
                searchTerms: []
            },
            {
                title: "fi-rr-binary-circle-check",
                searchTerms: []
            },
            {
                title: "fi-rr-binary-cloud",
                searchTerms: []
            },
            {
                title: "fi-rr-binary-lock",
                searchTerms: []
            },
            {
                title: "fi-rr-binary-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-binoculars",
                searchTerms: []
            },
            {
                title: "fi-rr-bio",
                searchTerms: []
            },
            {
                title: "fi-rr-bio-leaves",
                searchTerms: []
            },
            {
                title: "fi-rr-biohazard",
                searchTerms: []
            },
            {
                title: "fi-rr-bird",
                searchTerms: []
            },
            {
                title: "fi-rr-bitcoin-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-blanket",
                searchTerms: []
            },
            {
                title: "fi-rr-blender",
                searchTerms: []
            },
            {
                title: "fi-rr-blender-phone",
                searchTerms: []
            },
            {
                title: "fi-rr-blinds",
                searchTerms: []
            },
            {
                title: "fi-rr-blinds-open",
                searchTerms: []
            },
            {
                title: "fi-rr-blinds-raised",
                searchTerms: []
            },
            {
                title: "fi-rr-block",
                searchTerms: []
            },
            {
                title: "fi-rr-block-brick",
                searchTerms: []
            },
            {
                title: "fi-rr-block-brick-fire",
                searchTerms: []
            },
            {
                title: "fi-rr-block-microphone",
                searchTerms: []
            },
            {
                title: "fi-rr-block-question",
                searchTerms: []
            },
            {
                title: "fi-rr-block-quote",
                searchTerms: []
            },
            {
                title: "fi-rr-blockchain-3",
                searchTerms: []
            },
            {
                title: "fi-rr-blog-pencil",
                searchTerms: []
            },
            {
                title: "fi-rr-blog-text",
                searchTerms: []
            },
            {
                title: "fi-rr-blood",
                searchTerms: []
            },
            {
                title: "fi-rr-blood-dropper",
                searchTerms: []
            },
            {
                title: "fi-rr-blood-test-tube",
                searchTerms: []
            },
            {
                title: "fi-rr-blood-test-tube-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-blueberries",
                searchTerms: []
            },
            {
                title: "fi-rr-blueprint",
                searchTerms: []
            },
            {
                title: "fi-rr-bluetooth-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-bluetooth-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-bold",
                searchTerms: []
            },
            {
                title: "fi-rr-bolt",
                searchTerms: []
            },
            {
                title: "fi-rr-bolt-auto",
                searchTerms: []
            },
            {
                title: "fi-rr-bolt-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-bomb",
                searchTerms: []
            },
            {
                title: "fi-rr-bone",
                searchTerms: []
            },
            {
                title: "fi-rr-bone-break",
                searchTerms: []
            },
            {
                title: "fi-rr-bong",
                searchTerms: []
            },
            {
                title: "fi-rr-bonus",
                searchTerms: []
            },
            {
                title: "fi-rr-bonus-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-bonus-star",
                searchTerms: []
            },
            {
                title: "fi-rr-book",
                searchTerms: []
            },
            {
                title: "fi-rr-book-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-book-arrow-right",
                searchTerms: []
            },
            {
                title: "fi-rr-book-arrow-up",
                searchTerms: []
            },
            {
                title: "fi-rr-book-atlas",
                searchTerms: []
            },
            {
                title: "fi-rr-book-bookmark",
                searchTerms: []
            },
            {
                title: "fi-rr-book-circle-arrow-right",
                searchTerms: []
            },
            {
                title: "fi-rr-book-circle-arrow-up",
                searchTerms: []
            },
            {
                title: "fi-rr-book-copy",
                searchTerms: []
            },
            {
                title: "fi-rr-book-dead",
                searchTerms: []
            },
            {
                title: "fi-rr-book-font",
                searchTerms: []
            },
            {
                title: "fi-rr-book-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-book-medical",
                searchTerms: []
            },
            {
                title: "fi-rr-book-open-cover",
                searchTerms: []
            },
            {
                title: "fi-rr-book-open-reader",
                searchTerms: []
            },
            {
                title: "fi-rr-book-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-book-quran",
                searchTerms: []
            },
            {
                title: "fi-rr-book-section",
                searchTerms: []
            },
            {
                title: "fi-rr-book-spells",
                searchTerms: []
            },
            {
                title: "fi-rr-book-tanakh",
                searchTerms: []
            },
            {
                title: "fi-rr-book-user",
                searchTerms: []
            },
            {
                title: "fi-rr-book-world",
                searchTerms: []
            },
            {
                title: "fi-rr-Booking",
                searchTerms: []
            },
            {
                title: "fi-rr-bookmark",
                searchTerms: []
            },
            {
                title: "fi-rr-bookmark-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-books",
                searchTerms: []
            },
            {
                title: "fi-rr-books-medical",
                searchTerms: []
            },
            {
                title: "fi-rr-boot",
                searchTerms: []
            },
            {
                title: "fi-rr-boot-heeled",
                searchTerms: []
            },
            {
                title: "fi-rr-booth-curtain",
                searchTerms: []
            },
            {
                title: "fi-rr-border-all",
                searchTerms: []
            },
            {
                title: "fi-rr-border-bottom",
                searchTerms: []
            },
            {
                title: "fi-rr-border-center-h",
                searchTerms: []
            },
            {
                title: "fi-rr-border-center-v",
                searchTerms: []
            },
            {
                title: "fi-rr-border-inner",
                searchTerms: []
            },
            {
                title: "fi-rr-border-left",
                searchTerms: []
            },
            {
                title: "fi-rr-border-none",
                searchTerms: []
            },
            {
                title: "fi-rr-border-outer",
                searchTerms: []
            },
            {
                title: "fi-rr-border-right",
                searchTerms: []
            },
            {
                title: "fi-rr-border-style",
                searchTerms: []
            },
            {
                title: "fi-rr-border-style-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-border-top",
                searchTerms: []
            },
            {
                title: "fi-rr-boss",
                searchTerms: []
            },
            {
                title: "fi-rr-bottle",
                searchTerms: []
            },
            {
                title: "fi-rr-bottle-droplet",
                searchTerms: []
            },
            {
                title: "fi-rr-bow-arrow",
                searchTerms: []
            },
            {
                title: "fi-rr-bowl-chopsticks",
                searchTerms: []
            },
            {
                title: "fi-rr-bowl-chopsticks-noodles",
                searchTerms: []
            },
            {
                title: "fi-rr-bowl-rice",
                searchTerms: []
            },
            {
                title: "fi-rr-bowl-scoop",
                searchTerms: []
            },
            {
                title: "fi-rr-bowl-scoops",
                searchTerms: []
            },
            {
                title: "fi-rr-bowl-soft-serve",
                searchTerms: []
            },
            {
                title: "fi-rr-bowl-spoon",
                searchTerms: []
            },
            {
                title: "fi-rr-bowling",
                searchTerms: []
            },
            {
                title: "fi-rr-bowling-ball",
                searchTerms: []
            },
            {
                title: "fi-rr-bowling-pins",
                searchTerms: []
            },
            {
                title: "fi-rr-box",
                searchTerms: []
            },
            {
                title: "fi-rr-box-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-box-ballot",
                searchTerms: []
            },
            {
                title: "fi-rr-box-check",
                searchTerms: []
            },
            {
                title: "fi-rr-box-circle-check",
                searchTerms: []
            },
            {
                title: "fi-rr-box-dollar",
                searchTerms: []
            },
            {
                title: "fi-rr-box-fragile",
                searchTerms: []
            },
            {
                title: "fi-rr-box-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-box-open",
                searchTerms: []
            },
            {
                title: "fi-rr-box-open-full",
                searchTerms: []
            },
            {
                title: "fi-rr-box-tissue",
                searchTerms: []
            },
            {
                title: "fi-rr-box-up",
                searchTerms: []
            },
            {
                title: "fi-rr-boxes",
                searchTerms: []
            },
            {
                title: "fi-rr-boxing-glove",
                searchTerms: []
            },
            {
                title: "fi-rr-bracket-curly",
                searchTerms: []
            },
            {
                title: "fi-rr-bracket-curly-right",
                searchTerms: []
            },
            {
                title: "fi-rr-bracket-round",
                searchTerms: []
            },
            {
                title: "fi-rr-bracket-round-right",
                searchTerms: []
            },
            {
                title: "fi-rr-bracket-square",
                searchTerms: []
            },
            {
                title: "fi-rr-bracket-square-right",
                searchTerms: []
            },
            {
                title: "fi-rr-brackets-curly",
                searchTerms: []
            },
            {
                title: "fi-rr-brackets-round",
                searchTerms: []
            },
            {
                title: "fi-rr-brackets-square",
                searchTerms: []
            },
            {
                title: "fi-rr-braille",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-a",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-b",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-c",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-d",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-e",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-g",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-h",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-i",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-j",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-k",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-l",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-m",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-n",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-n-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-o",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-p",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-q",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-r",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-s",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-t",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-u",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-v",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-w",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-x",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-y",
                searchTerms: []
            },
            {
                title: "fi-rr-braille-z",
                searchTerms: []
            },
            {
                title: "fi-rr-brain",
                searchTerms: []
            },
            {
                title: "fi-rr-brain-bulb",
                searchTerms: []
            },
            {
                title: "fi-rr-brain-circuit",
                searchTerms: []
            },
            {
                title: "fi-rr-brain-lightning",
                searchTerms: []
            },
            {
                title: "fi-rr-brain-stress",
                searchTerms: []
            },
            {
                title: "fi-rr-brake-warning",
                searchTerms: []
            },
            {
                title: "fi-rr-branching",
                searchTerms: []
            },
            {
                title: "fi-rr-brand",
                searchTerms: []
            },
            {
                title: "fi-rr-branding",
                searchTerms: []
            },
            {
                title: "fi-rr-brazil",
                searchTerms: []
            },
            {
                title: "fi-rr-brazil-flag",
                searchTerms: []
            },
            {
                title: "fi-rr-brazil-flag-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-bread",
                searchTerms: []
            },
            {
                title: "fi-rr-bread-loaf",
                searchTerms: []
            },
            {
                title: "fi-rr-bread-slice",
                searchTerms: []
            },
            {
                title: "fi-rr-bread-slice-butter",
                searchTerms: []
            },
            {
                title: "fi-rr-bridge",
                searchTerms: []
            },
            {
                title: "fi-rr-bridge-circle-check",
                searchTerms: []
            },
            {
                title: "fi-rr-bridge-collapse",
                searchTerms: []
            },
            {
                title: "fi-rr-bridge-suspension",
                searchTerms: []
            },
            {
                title: "fi-rr-bridge-water",
                searchTerms: []
            },
            {
                title: "fi-rr-briefcase",
                searchTerms: []
            },
            {
                title: "fi-rr-briefcase-arrow-right",
                searchTerms: []
            },
            {
                title: "fi-rr-briefcase-blank",
                searchTerms: []
            },
            {
                title: "fi-rr-brightness",
                searchTerms: []
            },
            {
                title: "fi-rr-brightness-low",
                searchTerms: []
            },
            {
                title: "fi-rr-bring-forward",
                searchTerms: []
            },
            {
                title: "fi-rr-bring-front",
                searchTerms: []
            },
            {
                title: "fi-rr-broadcast-tower",
                searchTerms: []
            },
            {
                title: "fi-rr-broccoli",
                searchTerms: []
            },
            {
                title: "fi-rr-broken-arm",
                searchTerms: []
            },
            {
                title: "fi-rr-broken-chain-link-wrong",
                searchTerms: []
            },
            {
                title: "fi-rr-broken-image",
                searchTerms: []
            },
            {
                title: "fi-rr-broken-leg",
                searchTerms: []
            },
            {
                title: "fi-rr-broken-nail",
                searchTerms: []
            },
            {
                title: "fi-rr-broom",
                searchTerms: []
            },
            {
                title: "fi-rr-broom-ball",
                searchTerms: []
            },
            {
                title: "fi-rr-browser",
                searchTerms: []
            },
            {
                title: "fi-rr-browser-ui",
                searchTerms: []
            },
            {
                title: "fi-rr-browsers",
                searchTerms: []
            },
            {
                title: "fi-rr-brush",
                searchTerms: []
            },
            {
                title: "fi-rr-bubble-discussion",
                searchTerms: []
            },
            {
                title: "fi-rr-bucket",
                searchTerms: []
            },
            {
                title: "fi-rr-budget",
                searchTerms: []
            },
            {
                title: "fi-rr-budget-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-bug",
                searchTerms: []
            },
            {
                title: "fi-rr-bug-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-bugs",
                searchTerms: []
            },
            {
                title: "fi-rr-build",
                searchTerms: []
            },
            {
                title: "fi-rr-build-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-builder",
                searchTerms: []
            },
            {
                title: "fi-rr-building",
                searchTerms: []
            },
            {
                title: "fi-rr-building-circle-arrow-right",
                searchTerms: []
            },
            {
                title: "fi-rr-building-ngo",
                searchTerms: []
            },
            {
                title: "fi-rr-bulb",
                searchTerms: []
            },
            {
                title: "fi-rr-bulb-speech-bubble",
                searchTerms: []
            },
            {
                title: "fi-rr-bullet",
                searchTerms: []
            },
            {
                title: "fi-rr-bullhorn",
                searchTerms: []
            },
            {
                title: "fi-rr-bullseye",
                searchTerms: []
            },
            {
                title: "fi-rr-bullseye-arrow",
                searchTerms: []
            },
            {
                title: "fi-rr-bullseye-pointer",
                searchTerms: []
            },
            {
                title: "fi-rr-burger-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-burger-fries",
                searchTerms: []
            },
            {
                title: "fi-rr-burger-glass",
                searchTerms: []
            },
            {
                title: "fi-rr-burrito",
                searchTerms: []
            },
            {
                title: "fi-rr-burst",
                searchTerms: []
            },
            {
                title: "fi-rr-bus",
                searchTerms: []
            },
            {
                title: "fi-rr-bus-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-business-time",
                searchTerms: []
            },
            {
                title: "fi-rr-Butter",
                searchTerms: []
            },
            {
                title: "fi-rr-butterfly",
                searchTerms: []
            },
            {
                title: "fi-rr-c",
                searchTerms: []
            },
            {
                title: "fi-rr-cabin",
                searchTerms: []
            },
            {
                title: "fi-rr-cactus",
                searchTerms: []
            },
            {
                title: "fi-rr-cage-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-cake-birthday",
                searchTerms: []
            },
            {
                title: "fi-rr-cake-slice",
                searchTerms: []
            },
            {
                title: "fi-rr-cake-wedding",
                searchTerms: []
            },
            {
                title: "fi-rr-calculator",
                searchTerms: []
            },
            {
                title: "fi-rr-calculator-bill",
                searchTerms: []
            },
            {
                title: "fi-rr-calculator-math-tax",
                searchTerms: []
            },
            {
                title: "fi-rr-calculator-money",
                searchTerms: []
            },
            {
                title: "fi-rr-calculator-simple",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-arrow-down",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-arrow-up",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-birhtday-cake",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-call",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-check",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-clock",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-day",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-days",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-event-tax",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-gavel-legal",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-image",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-lines",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-lines-pen",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-minus",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-payment-loan",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-pen",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-salary",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-shift-swap",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-star",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-swap",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-update",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-week",
                searchTerms: []
            },
            {
                title: "fi-rr-calendar-xmark",
                searchTerms: []
            },
            {
                title: "fi-rr-calendars",
                searchTerms: []
            },
            {
                title: "fi-rr-call-duration",
                searchTerms: []
            },
            {
                title: "fi-rr-call-forbidden",
                searchTerms: []
            },
            {
                title: "fi-rr-call-history",
                searchTerms: []
            },
            {
                title: "fi-rr-call-incoming",
                searchTerms: []
            },
            {
                title: "fi-rr-call-missed",
                searchTerms: []
            },
            {
                title: "fi-rr-call-outgoing",
                searchTerms: []
            },
            {
                title: "fi-rr-camcorder",
                searchTerms: []
            },
            {
                title: "fi-rr-camera",
                searchTerms: []
            },
            {
                title: "fi-rr-camera-cctv",
                searchTerms: []
            },
            {
                title: "fi-rr-camera-movie",
                searchTerms: []
            },
            {
                title: "fi-rr-camera-retro",
                searchTerms: []
            },
            {
                title: "fi-rr-camera-rotate",
                searchTerms: []
            },
            {
                title: "fi-rr-camera-security",
                searchTerms: []
            },
            {
                title: "fi-rr-camera-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-camera-viewfinder",
                searchTerms: []
            },
            {
                title: "fi-rr-campfire",
                searchTerms: []
            },
            {
                title: "fi-rr-camping",
                searchTerms: []
            },
            {
                title: "fi-rr-can-food",
                searchTerms: []
            },
            {
                title: "fi-rr-candle-holder",
                searchTerms: []
            },
            {
                title: "fi-rr-candle-lotus-yoga",
                searchTerms: []
            },
            {
                title: "fi-rr-candle-pose-yoga",
                searchTerms: []
            },
            {
                title: "fi-rr-candy",
                searchTerms: []
            },
            {
                title: "fi-rr-candy-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-candy-bar",
                searchTerms: []
            },
            {
                title: "fi-rr-candy-cane",
                searchTerms: []
            },
            {
                title: "fi-rr-candy-corn",
                searchTerms: []
            },
            {
                title: "fi-rr-candy-sweet",
                searchTerms: []
            },
            {
                title: "fi-rr-Cannabis",
                searchTerms: []
            },
            {
                title: "fi-rr-canned-food",
                searchTerms: []
            },
            {
                title: "fi-rr-cannon",
                searchTerms: []
            },
            {
                title: "fi-rr-capsules",
                searchTerms: []
            },
            {
                title: "fi-rr-car",
                searchTerms: []
            },
            {
                title: "fi-rr-car-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-car-battery",
                searchTerms: []
            },
            {
                title: "fi-rr-car-bolt",
                searchTerms: []
            },
            {
                title: "fi-rr-car-building",
                searchTerms: []
            },
            {
                title: "fi-rr-car-bump",
                searchTerms: []
            },
            {
                title: "fi-rr-car-bus",
                searchTerms: []
            },
            {
                title: "fi-rr-car-circle-bolt",
                searchTerms: []
            },
            {
                title: "fi-rr-car-crash",
                searchTerms: []
            },
            {
                title: "fi-rr-car-garage",
                searchTerms: []
            },
            {
                title: "fi-rr-car-journey",
                searchTerms: []
            },
            {
                title: "fi-rr-car-mechanic",
                searchTerms: []
            },
            {
                title: "fi-rr-car-rear",
                searchTerms: []
            },
            {
                title: "fi-rr-car-side",
                searchTerms: []
            },
            {
                title: "fi-rr-car-side-bolt",
                searchTerms: []
            },
            {
                title: "fi-rr-car-tilt",
                searchTerms: []
            },
            {
                title: "fi-rr-car-tunnel",
                searchTerms: []
            },
            {
                title: "fi-rr-car-wash",
                searchTerms: []
            },
            {
                title: "fi-rr-caravan",
                searchTerms: []
            },
            {
                title: "fi-rr-caravan-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-carbon-cloud",
                searchTerms: []
            },
            {
                title: "fi-rr-carbon-cloud-arrow-down",
                searchTerms: []
            },
            {
                title: "fi-rr-carbon-footprint",
                searchTerms: []
            },
            {
                title: "fi-rr-card-club",
                searchTerms: []
            },
            {
                title: "fi-rr-card-diamond",
                searchTerms: []
            },
            {
                title: "fi-rr-card-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-card-spade",
                searchTerms: []
            },
            {
                title: "fi-rr-cardinal-compass",
                searchTerms: []
            },
            {
                title: "fi-rr-cards-blank",
                searchTerms: []
            },
            {
                title: "fi-rr-career-growth",
                searchTerms: []
            },
            {
                title: "fi-rr-career-path",
                searchTerms: []
            },
            {
                title: "fi-rr-caret-circle-down",
                searchTerms: []
            },
            {
                title: "fi-rr-caret-circle-right",
                searchTerms: []
            },
            {
                title: "fi-rr-caret-circle-up",
                searchTerms: []
            },
            {
                title: "fi-rr-caret-down",
                searchTerms: []
            },
            {
                title: "fi-rr-caret-left",
                searchTerms: []
            },
            {
                title: "fi-rr-caret-quare-up",
                searchTerms: []
            },
            {
                title: "fi-rr-caret-right",
                searchTerms: []
            },
            {
                title: "fi-rr-caret-square-down",
                searchTerms: []
            },
            {
                title: "fi-rr-caret-square-left",
                searchTerms: []
            },
            {
                title: "fi-rr-caret-square-left_1",
                searchTerms: []
            },
            {
                title: "fi-rr-caret-square-right",
                searchTerms: []
            },
            {
                title: "fi-rr-caret-up",
                searchTerms: []
            },
            {
                title: "fi-rr-carrot",
                searchTerms: []
            },
            {
                title: "fi-rr-cars",
                searchTerms: []
            },
            {
                title: "fi-rr-cars-crash",
                searchTerms: []
            },
            {
                title: "fi-rr-cart-arrow-down",
                searchTerms: []
            },
            {
                title: "fi-rr-cart-minus",
                searchTerms: []
            },
            {
                title: "fi-rr-cart-shopping-fast",
                searchTerms: []
            },
            {
                title: "fi-rr-case-study",
                searchTerms: []
            },
            {
                title: "fi-rr-cash-register",
                searchTerms: []
            },
            {
                title: "fi-rr-cassette-tape",
                searchTerms: []
            },
            {
                title: "fi-rr-cassette-vhs",
                searchTerms: []
            },
            {
                title: "fi-rr-castle",
                searchTerms: []
            },
            {
                title: "fi-rr-cat",
                searchTerms: []
            },
            {
                title: "fi-rr-cat-dog",
                searchTerms: []
            },
            {
                title: "fi-rr-cat-head",
                searchTerms: []
            },
            {
                title: "fi-rr-cat-space",
                searchTerms: []
            },
            {
                title: "fi-rr-catalog",
                searchTerms: []
            },
            {
                title: "fi-rr-catalog-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-catalog-magazine",
                searchTerms: []
            },
            {
                title: "fi-rr-category",
                searchTerms: []
            },
            {
                title: "fi-rr-category-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-cauldron",
                searchTerms: []
            },
            {
                title: "fi-rr-cedi-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-cello",
                searchTerms: []
            },
            {
                title: "fi-rr-cent-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-chair",
                searchTerms: []
            },
            {
                title: "fi-rr-chair-director",
                searchTerms: []
            },
            {
                title: "fi-rr-chair-office",
                searchTerms: []
            },
            {
                title: "fi-rr-chalkboard",
                searchTerms: []
            },
            {
                title: "fi-rr-chalkboard-user",
                searchTerms: []
            },
            {
                title: "fi-rr-challenge",
                searchTerms: []
            },
            {
                title: "fi-rr-challenge-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-channel",
                searchTerms: []
            },
            {
                title: "fi-rr-charging-station",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-area",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-bar-holding-hand",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-bullet",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-candlestick",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-connected",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-gantt",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-histogram",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-kanban",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-line-up",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-line-up-down",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-mixed",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-mixed-up-circle-currency",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-mixed-up-circle-dollar",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-network",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-pie",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-pie-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-pie-simple-circle-currency",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-pie-simple-circle-dollar",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-pyramid",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-radar",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-scatter",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-scatter-3d",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-scatter-bubble",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-set-theory",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-simple",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-simple-horizontal",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-tree",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-tree-map",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-user",
                searchTerms: []
            },
            {
                title: "fi-rr-chart-waterfall",
                searchTerms: []
            },
            {
                title: "fi-rr-chat-arrow-down",
                searchTerms: []
            },
            {
                title: "fi-rr-chat-arrow-grow",
                searchTerms: []
            },
            {
                title: "fi-rr-chat-bubble-call",
                searchTerms: []
            },
            {
                title: "fi-rr-chatbot",
                searchTerms: []
            },
            {
                title: "fi-rr-chatbot-speech-bubble",
                searchTerms: []
            },
            {
                title: "fi-rr-cheap",
                searchTerms: []
            },
            {
                title: "fi-rr-cheap-bill",
                searchTerms: []
            },
            {
                title: "fi-rr-cheap-dollar",
                searchTerms: []
            },
            {
                title: "fi-rr-cheap-stack",
                searchTerms: []
            },
            {
                title: "fi-rr-cheap-stack-dollar",
                searchTerms: []
            },
            {
                title: "fi-rr-check",
                searchTerms: []
            },
            {
                title: "fi-rr-check-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-check-double",
                searchTerms: []
            },
            {
                title: "fi-rr-check-in-calendar",
                searchTerms: []
            },
            {
                title: "fi-rr-check-out-calendar",
                searchTerms: []
            },
            {
                title: "fi-rr-checkbox",
                searchTerms: []
            },
            {
                title: "fi-rr-checklist-task-budget",
                searchTerms: []
            },
            {
                title: "fi-rr-cheese",
                searchTerms: []
            },
            {
                title: "fi-rr-cheese-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-cheeseburger",
                searchTerms: []
            },
            {
                title: "fi-rr-cherry",
                searchTerms: []
            },
            {
                title: "fi-rr-chess",
                searchTerms: []
            },
            {
                title: "fi-rr-chess-bishop",
                searchTerms: []
            },
            {
                title: "fi-rr-chess-board",
                searchTerms: []
            },
            {
                title: "fi-rr-chess-clock",
                searchTerms: []
            },
            {
                title: "fi-rr-chess-clock-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-chess-king",
                searchTerms: []
            },
            {
                title: "fi-rr-chess-king-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-chess-knight",
                searchTerms: []
            },
            {
                title: "fi-rr-chess-knight-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-chess-pawn-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-chess-piece",
                searchTerms: []
            },
            {
                title: "fi-rr-chess-queen",
                searchTerms: []
            },
            {
                title: "fi-rr-chess-queen-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-chess-rook",
                searchTerms: []
            },
            {
                title: "fi-rr-chess-rook-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-chevron-double-down",
                searchTerms: []
            },
            {
                title: "fi-rr-chevron-double-up",
                searchTerms: []
            },
            {
                title: "fi-rr-child",
                searchTerms: []
            },
            {
                title: "fi-rr-child-head",
                searchTerms: []
            },
            {
                title: "fi-rr-chimney",
                searchTerms: []
            },
            {
                title: "fi-rr-chip",
                searchTerms: []
            },
            {
                title: "fi-rr-chocolate",
                searchTerms: []
            },
            {
                title: "fi-rr-choir-singing",
                searchTerms: []
            },
            {
                title: "fi-rr-choose",
                searchTerms: []
            },
            {
                title: "fi-rr-choose-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-church",
                searchTerms: []
            },
            {
                title: "fi-rr-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-0",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-1",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-2",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-3",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-4",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-5",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-6",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-7",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-8",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-9",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-a",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-b",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-bolt",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-book-open",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-bookmark",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-c",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-calendar",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-camera",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-d",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-dashed",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-divide",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-e",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-ellipsis",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-ellipsis-vertical",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-envelope",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-exclamation-check",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-f",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-g",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-h",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-half",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-half-stroke",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-i",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-j",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-k",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-l",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-m",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-microphone",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-microphone-lines",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-n",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-nodes",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-o",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-overlap",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-p",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-phone",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-phone-flip",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-phone-hangup",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-q",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-quarter",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-quarters-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-r",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-s",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-small",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-star",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-t",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-three-quarters",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-trash",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-u",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-user",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-v",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-video",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-w",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-waveform-lines",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-wifi-circle-wifi",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-x",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-xmark",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-y",
                searchTerms: []
            },
            {
                title: "fi-rr-circle-z",
                searchTerms: []
            },
            {
                title: "fi-rr-citrus",
                searchTerms: []
            },
            {
                title: "fi-rr-citrus-slice",
                searchTerms: []
            },
            {
                title: "fi-rr-city",
                searchTerms: []
            },
            {
                title: "fi-rr-clapper-open",
                searchTerms: []
            },
            {
                title: "fi-rr-clapperboard",
                searchTerms: []
            },
            {
                title: "fi-rr-clapperboard-play",
                searchTerms: []
            },
            {
                title: "fi-rr-clarinet",
                searchTerms: []
            },
            {
                title: "fi-rr-claw-marks",
                searchTerms: []
            },
            {
                title: "fi-rr-clear-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-clip",
                searchTerms: []
            },
            {
                title: "fi-rr-clip-file",
                searchTerms: []
            },
            {
                title: "fi-rr-clip-mail",
                searchTerms: []
            },
            {
                title: "fi-rr-clipboard",
                searchTerms: []
            },
            {
                title: "fi-rr-clipboard-check",
                searchTerms: []
            },
            {
                title: "fi-rr-clipboard-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-clipboard-list",
                searchTerms: []
            },
            {
                title: "fi-rr-clipboard-list-check",
                searchTerms: []
            },
            {
                title: "fi-rr-clipboard-prescription",
                searchTerms: []
            },
            {
                title: "fi-rr-clipboard-question",
                searchTerms: []
            },
            {
                title: "fi-rr-clipboard-user",
                searchTerms: []
            },
            {
                title: "fi-rr-clipoard-wrong",
                searchTerms: []
            },
            {
                title: "fi-rr-clock",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-desk",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-eight-thirty",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-eleven",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-eleven-thirty",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-five",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-five-thirty",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-four-thirty",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-future-past",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-nine",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-nine-thirty",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-one",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-one-thirty",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-seven",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-seven-thirty",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-six",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-six-thirty",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-ten",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-ten-thirty",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-three",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-three-thirty",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-time-tracking",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-twelve",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-twelve-thirty",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-two",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-two-thirty",
                searchTerms: []
            },
            {
                title: "fi-rr-clock-up-arrow",
                searchTerms: []
            },
            {
                title: "fi-rr-clone",
                searchTerms: []
            },
            {
                title: "fi-rr-closed-captioning-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-clothes-hanger",
                searchTerms: []
            },
            {
                title: "fi-rr-clothing-rack",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-back-up",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-back-up-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-check",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-code",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-data",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-disabled",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-download",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-download-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-download-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-drizzle",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-gear-automation",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-hail",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-hail-mixed",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-lock",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-meatball",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-moon",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-moon-rain",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-question",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-rain",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-rainbow",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-share",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-showers",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-showers-heavy",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-showers-water",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-sleet",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-snow",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-sun",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-sun-rain",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-upload",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-upload-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-cloud-upload-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-clouds",
                searchTerms: []
            },
            {
                title: "fi-rr-clouds-moon",
                searchTerms: []
            },
            {
                title: "fi-rr-clouds-sun",
                searchTerms: []
            },
            {
                title: "fi-rr-clover-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-club",
                searchTerms: []
            },
            {
                title: "fi-rr-cocktail",
                searchTerms: []
            },
            {
                title: "fi-rr-cocktail-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-coconut",
                searchTerms: []
            },
            {
                title: "fi-rr-code-branch",
                searchTerms: []
            },
            {
                title: "fi-rr-code-commit",
                searchTerms: []
            },
            {
                title: "fi-rr-code-compare",
                searchTerms: []
            },
            {
                title: "fi-rr-code-fork",
                searchTerms: []
            },
            {
                title: "fi-rr-code-merge",
                searchTerms: []
            },
            {
                title: "fi-rr-code-pull-request",
                searchTerms: []
            },
            {
                title: "fi-rr-code-pull-request-closed",
                searchTerms: []
            },
            {
                title: "fi-rr-code-pull-request-draft",
                searchTerms: []
            },
            {
                title: "fi-rr-code-simple",
                searchTerms: []
            },
            {
                title: "fi-rr-coffee",
                searchTerms: []
            },
            {
                title: "fi-rr-coffee-bean",
                searchTerms: []
            },
            {
                title: "fi-rr-coffee-beans",
                searchTerms: []
            },
            {
                title: "fi-rr-coffee-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-coffee-maker",
                searchTerms: []
            },
            {
                title: "fi-rr-coffee-pot",
                searchTerms: []
            },
            {
                title: "fi-rr-coffin",
                searchTerms: []
            },
            {
                title: "fi-rr-coffin-cross",
                searchTerms: []
            },
            {
                title: "fi-rr-coin",
                searchTerms: []
            },
            {
                title: "fi-rr-coin-up-arrow",
                searchTerms: []
            },
            {
                title: "fi-rr-coins",
                searchTerms: []
            },
            {
                title: "fi-rr-colon-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-columns-3",
                searchTerms: []
            },
            {
                title: "fi-rr-comet",
                searchTerms: []
            },
            {
                title: "fi-rr-command",
                searchTerms: []
            },
            {
                title: "fi-rr-comment",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-alt-check",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-alt-dots",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-alt-edit",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-alt-medical",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-alt-middle",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-alt-middle-top",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-alt-minus",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-alt-music",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-arrow-down",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-arrow-up",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-arrow-up-right",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-check",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-code",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-dollar",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-dots",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-image",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-info",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-medical",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-minus",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-pen",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-question",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-quote",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-smile",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-sms",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-text",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-user",
                searchTerms: []
            },
            {
                title: "fi-rr-comment-xmark",
                searchTerms: []
            },
            {
                title: "fi-rr-comments",
                searchTerms: []
            },
            {
                title: "fi-rr-comments-dollar",
                searchTerms: []
            },
            {
                title: "fi-rr-comments-question",
                searchTerms: []
            },
            {
                title: "fi-rr-comments-question-check",
                searchTerms: []
            },
            {
                title: "fi-rr-compass-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-compass-east",
                searchTerms: []
            },
            {
                title: "fi-rr-compass-north",
                searchTerms: []
            },
            {
                title: "fi-rr-compass-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-compass-south",
                searchTerms: []
            },
            {
                title: "fi-rr-compass-west",
                searchTerms: []
            },
            {
                title: "fi-rr-completed",
                searchTerms: []
            },
            {
                title: "fi-rr-compliance",
                searchTerms: []
            },
            {
                title: "fi-rr-compliance-clipboard",
                searchTerms: []
            },
            {
                title: "fi-rr-compliance-document",
                searchTerms: []
            },
            {
                title: "fi-rr-compress",
                searchTerms: []
            },
            {
                title: "fi-rr-compress-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-computer",
                searchTerms: []
            },
            {
                title: "fi-rr-computer-classic",
                searchTerms: []
            },
            {
                title: "fi-rr-computer-mouse",
                searchTerms: []
            },
            {
                title: "fi-rr-computer-speaker",
                searchTerms: []
            },
            {
                title: "fi-rr-concierge-bell",
                searchTerms: []
            },
            {
                title: "fi-rr-condition",
                searchTerms: []
            },
            {
                title: "fi-rr-condition-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-confetti",
                searchTerms: []
            },
            {
                title: "fi-rr-confidential-discussion",
                searchTerms: []
            },
            {
                title: "fi-rr-console-controller",
                searchTerms: []
            },
            {
                title: "fi-rr-constellation",
                searchTerms: []
            },
            {
                title: "fi-rr-contact-lens-eyes",
                searchTerms: []
            },
            {
                title: "fi-rr-container-storage",
                searchTerms: []
            },
            {
                title: "fi-rr-convert-document",
                searchTerms: []
            },
            {
                title: "fi-rr-convert-shapes",
                searchTerms: []
            },
            {
                title: "fi-rr-conveyor-belt",
                searchTerms: []
            },
            {
                title: "fi-rr-conveyor-belt-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-conveyor-belt-arm",
                searchTerms: []
            },
            {
                title: "fi-rr-conveyor-belt-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-cookie",
                searchTerms: []
            },
            {
                title: "fi-rr-cookie-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-copy",
                searchTerms: []
            },
            {
                title: "fi-rr-copy-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-copy-image",
                searchTerms: []
            },
            {
                title: "fi-rr-copyright",
                searchTerms: []
            },
            {
                title: "fi-rr-corn",
                searchTerms: []
            },
            {
                title: "fi-rr-corporate",
                searchTerms: []
            },
            {
                title: "fi-rr-corporate-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-couch",
                searchTerms: []
            },
            {
                title: "fi-rr-court-sport",
                searchTerms: []
            },
            {
                title: "fi-rr-cow",
                searchTerms: []
            },
            {
                title: "fi-rr-cow-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-cowbell",
                searchTerms: []
            },
            {
                title: "fi-rr-cowbell-circle-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-cowbell-more",
                searchTerms: []
            },
            {
                title: "fi-rr-crab",
                searchTerms: []
            },
            {
                title: "fi-rr-crate-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-cream",
                searchTerms: []
            },
            {
                title: "fi-rr-credit-card",
                searchTerms: []
            },
            {
                title: "fi-rr-credit-card-buyer",
                searchTerms: []
            },
            {
                title: "fi-rr-credit-card-eye",
                searchTerms: []
            },
            {
                title: "fi-rr-cricket",
                searchTerms: []
            },
            {
                title: "fi-rr-CRM",
                searchTerms: []
            },
            {
                title: "fi-rr-crm-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-crm-computer",
                searchTerms: []
            },
            {
                title: "fi-rr-croissant",
                searchTerms: []
            },
            {
                title: "fi-rr-cross",
                searchTerms: []
            },
            {
                title: "fi-rr-cross-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-cross-religion",
                searchTerms: []
            },
            {
                title: "fi-rr-cross-small",
                searchTerms: []
            },
            {
                title: "fi-rr-crow",
                searchTerms: []
            },
            {
                title: "fi-rr-crown",
                searchTerms: []
            },
            {
                title: "fi-rr-crutch",
                searchTerms: []
            },
            {
                title: "fi-rr-crutches",
                searchTerms: []
            },
            {
                title: "fi-rr-cruzeiro-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-crypto-calendar",
                searchTerms: []
            },
            {
                title: "fi-rr-cryptocurrency",
                searchTerms: []
            },
            {
                title: "fi-rr-crystal-ball",
                searchTerms: []
            },
            {
                title: "fi-rr-cube",
                searchTerms: []
            },
            {
                title: "fi-rr-cubes",
                searchTerms: []
            },
            {
                title: "fi-rr-cubes-stacked",
                searchTerms: []
            },
            {
                title: "fi-rr-cucumber",
                searchTerms: []
            },
            {
                title: "fi-rr-cup-straw",
                searchTerms: []
            },
            {
                title: "fi-rr-cup-straw-swoosh",
                searchTerms: []
            },
            {
                title: "fi-rr-cup-togo",
                searchTerms: []
            },
            {
                title: "fi-rr-cupcake",
                searchTerms: []
            },
            {
                title: "fi-rr-cupcake-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-curling",
                searchTerms: []
            },
            {
                title: "fi-rr-cursor",
                searchTerms: []
            },
            {
                title: "fi-rr-cursor-finger",
                searchTerms: []
            },
            {
                title: "fi-rr-cursor-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-cursor-text",
                searchTerms: []
            },
            {
                title: "fi-rr-cursor-text-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-curve",
                searchTerms: []
            },
            {
                title: "fi-rr-curve-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-curve-arrow",
                searchTerms: []
            },
            {
                title: "fi-rr-custard",
                searchTerms: []
            },
            {
                title: "fi-rr-customer-care",
                searchTerms: []
            },
            {
                title: "fi-rr-customer-service",
                searchTerms: []
            },
            {
                title: "fi-rr-customization",
                searchTerms: []
            },
            {
                title: "fi-rr-customization-cogwheel",
                searchTerms: []
            },
            {
                title: "fi-rr-customize",
                searchTerms: []
            },
            {
                title: "fi-rr-customize-computer",
                searchTerms: []
            },
            {
                title: "fi-rr-customize-edit",
                searchTerms: []
            },
            {
                title: "fi-rr-CV",
                searchTerms: []
            },
            {
                title: "fi-rr-cvv-card",
                searchTerms: []
            },
            {
                title: "fi-rr-d",
                searchTerms: []
            },
            {
                title: "fi-rr-dagger",
                searchTerms: []
            },
            {
                title: "fi-rr-daily-calendar",
                searchTerms: []
            },
            {
                title: "fi-rr-daisy",
                searchTerms: []
            },
            {
                title: "fi-rr-daisy-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-damage",
                searchTerms: []
            },
            {
                title: "fi-rr-dark-mode",
                searchTerms: []
            },
            {
                title: "fi-rr-dark-mode-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-dart",
                searchTerms: []
            },
            {
                title: "fi-rr-dashboard",
                searchTerms: []
            },
            {
                title: "fi-rr-dashboard-monitor",
                searchTerms: []
            },
            {
                title: "fi-rr-dashboard-panel",
                searchTerms: []
            },
            {
                title: "fi-rr-data-transfer",
                searchTerms: []
            },
            {
                title: "fi-rr-database",
                searchTerms: []
            },
            {
                title: "fi-rr-database-cloud-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-debt",
                searchTerms: []
            },
            {
                title: "fi-rr-decision-choice",
                searchTerms: []
            },
            {
                title: "fi-rr-deer",
                searchTerms: []
            },
            {
                title: "fi-rr-deer-rudolph",
                searchTerms: []
            },
            {
                title: "fi-rr-degree-credential",
                searchTerms: []
            },
            {
                title: "fi-rr-delete",
                searchTerms: []
            },
            {
                title: "fi-rr-delete-document",
                searchTerms: []
            },
            {
                title: "fi-rr-delete-right",
                searchTerms: []
            },
            {
                title: "fi-rr-delete-user",
                searchTerms: []
            },
            {
                title: "fi-rr-democrat",
                searchTerms: []
            },
            {
                title: "fi-rr-department",
                searchTerms: []
            },
            {
                title: "fi-rr-department-structure",
                searchTerms: []
            },
            {
                title: "fi-rr-deposit",
                searchTerms: []
            },
            {
                title: "fi-rr-deposit-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-description",
                searchTerms: []
            },
            {
                title: "fi-rr-description-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-desk",
                searchTerms: []
            },
            {
                title: "fi-rr-desktop-arrow-down",
                searchTerms: []
            },
            {
                title: "fi-rr-desktop-wallpaper",
                searchTerms: []
            },
            {
                title: "fi-rr-detergent",
                searchTerms: []
            },
            {
                title: "fi-rr-devices",
                searchTerms: []
            },
            {
                title: "fi-rr-dewpoint",
                searchTerms: []
            },
            {
                title: "fi-rr-dharmachakra",
                searchTerms: []
            },
            {
                title: "fi-rr-diagram-cells",
                searchTerms: []
            },
            {
                title: "fi-rr-diagram-lean-canvas",
                searchTerms: []
            },
            {
                title: "fi-rr-diagram-nested",
                searchTerms: []
            },
            {
                title: "fi-rr-diagram-next",
                searchTerms: []
            },
            {
                title: "fi-rr-diagram-predecessor",
                searchTerms: []
            },
            {
                title: "fi-rr-diagram-previous",
                searchTerms: []
            },
            {
                title: "fi-rr-diagram-project",
                searchTerms: []
            },
            {
                title: "fi-rr-diagram-sankey",
                searchTerms: []
            },
            {
                title: "fi-rr-diagram-subtask",
                searchTerms: []
            },
            {
                title: "fi-rr-diagram-successor",
                searchTerms: []
            },
            {
                title: "fi-rr-diagram-venn",
                searchTerms: []
            },
            {
                title: "fi-rr-dial",
                searchTerms: []
            },
            {
                title: "fi-rr-dial-high",
                searchTerms: []
            },
            {
                title: "fi-rr-dial-low",
                searchTerms: []
            },
            {
                title: "fi-rr-dial-max",
                searchTerms: []
            },
            {
                title: "fi-rr-dial-med",
                searchTerms: []
            },
            {
                title: "fi-rr-dial-med-low",
                searchTerms: []
            },
            {
                title: "fi-rr-dial-min",
                searchTerms: []
            },
            {
                title: "fi-rr-dial-off",
                searchTerms: []
            },
            {
                title: "fi-rr-dialogue-exchange",
                searchTerms: []
            },
            {
                title: "fi-rr-diamond",
                searchTerms: []
            },
            {
                title: "fi-rr-diamond-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-diamond-turn-right",
                searchTerms: []
            },
            {
                title: "fi-rr-diary-bookmark-down",
                searchTerms: []
            },
            {
                title: "fi-rr-diary-bookmarks",
                searchTerms: []
            },
            {
                title: "fi-rr-diary-clasp",
                searchTerms: []
            },
            {
                title: "fi-rr-dice",
                searchTerms: []
            },
            {
                title: "fi-rr-dice-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-dice-d10",
                searchTerms: []
            },
            {
                title: "fi-rr-dice-d12",
                searchTerms: []
            },
            {
                title: "fi-rr-dice-d20",
                searchTerms: []
            },
            {
                title: "fi-rr-dice-d4",
                searchTerms: []
            },
            {
                title: "fi-rr-dice-d6",
                searchTerms: []
            },
            {
                title: "fi-rr-dice-d8",
                searchTerms: []
            },
            {
                title: "fi-rr-dice-four",
                searchTerms: []
            },
            {
                title: "fi-rr-dice-one",
                searchTerms: []
            },
            {
                title: "fi-rr-dice-six",
                searchTerms: []
            },
            {
                title: "fi-rr-dice-three",
                searchTerms: []
            },
            {
                title: "fi-rr-dice-two",
                searchTerms: []
            },
            {
                title: "fi-rr-dictionary",
                searchTerms: []
            },
            {
                title: "fi-rr-dictionary-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-dictionary-open",
                searchTerms: []
            },
            {
                title: "fi-rr-digging",
                searchTerms: []
            },
            {
                title: "fi-rr-digital-payment",
                searchTerms: []
            },
            {
                title: "fi-rr-digital-tachograph",
                searchTerms: []
            },
            {
                title: "fi-rr-digital-wallet",
                searchTerms: []
            },
            {
                title: "fi-rr-dinner",
                searchTerms: []
            },
            {
                title: "fi-rr-diploma",
                searchTerms: []
            },
            {
                title: "fi-rr-direction-signal",
                searchTerms: []
            },
            {
                title: "fi-rr-direction-signal-arrow",
                searchTerms: []
            },
            {
                title: "fi-rr-disc-drive",
                searchTerms: []
            },
            {
                title: "fi-rr-disco-ball",
                searchTerms: []
            },
            {
                title: "fi-rr-discover",
                searchTerms: []
            },
            {
                title: "fi-rr-discussion-group",
                searchTerms: []
            },
            {
                title: "fi-rr-discussion-idea",
                searchTerms: []
            },
            {
                title: "fi-rr-disease",
                searchTerms: []
            },
            {
                title: "fi-rr-disk",
                searchTerms: []
            },
            {
                title: "fi-rr-display-arrow-down",
                searchTerms: []
            },
            {
                title: "fi-rr-display-chart-up",
                searchTerms: []
            },
            {
                title: "fi-rr-display-code",
                searchTerms: []
            },
            {
                title: "fi-rr-display-medical",
                searchTerms: []
            },
            {
                title: "fi-rr-display-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-distribute-spacing-horizontal",
                searchTerms: []
            },
            {
                title: "fi-rr-distribute-spacing-vertical",
                searchTerms: []
            },
            {
                title: "fi-rr-divide",
                searchTerms: []
            },
            {
                title: "fi-rr-dizzy",
                searchTerms: []
            },
            {
                title: "fi-rr-dj-disc",
                searchTerms: []
            },
            {
                title: "fi-rr-dna",
                searchTerms: []
            },
            {
                title: "fi-rr-do-not-disturb",
                searchTerms: []
            },
            {
                title: "fi-rr-do-not-disturb-doorknob",
                searchTerms: []
            },
            {
                title: "fi-rr-do-not-enter",
                searchTerms: []
            },
            {
                title: "fi-rr-doctor",
                searchTerms: []
            },
            {
                title: "fi-rr-document",
                searchTerms: []
            },
            {
                title: "fi-rr-document-circle-wrong",
                searchTerms: []
            },
            {
                title: "fi-rr-document-folder-gear",
                searchTerms: []
            },
            {
                title: "fi-rr-document-gavel",
                searchTerms: []
            },
            {
                title: "fi-rr-document-gear",
                searchTerms: []
            },
            {
                title: "fi-rr-document-nft",
                searchTerms: []
            },
            {
                title: "fi-rr-document-paid",
                searchTerms: []
            },
            {
                title: "fi-rr-document-signed",
                searchTerms: []
            },
            {
                title: "fi-rr-dog",
                searchTerms: []
            },
            {
                title: "fi-rr-dog-bowl-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-dog-leashed",
                searchTerms: []
            },
            {
                title: "fi-rr-dollar",
                searchTerms: []
            },
            {
                title: "fi-rr-dolly-flatbed",
                searchTerms: []
            },
            {
                title: "fi-rr-dolly-flatbed-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-dolly-flatbed-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-dolphin",
                searchTerms: []
            },
            {
                title: "fi-rr-domino-effect",
                searchTerms: []
            },
            {
                title: "fi-rr-donate",
                searchTerms: []
            },
            {
                title: "fi-rr-dong-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-donut",
                searchTerms: []
            },
            {
                title: "fi-rr-door-closed",
                searchTerms: []
            },
            {
                title: "fi-rr-door-open",
                searchTerms: []
            },
            {
                title: "fi-rr-dorm-room",
                searchTerms: []
            },
            {
                title: "fi-rr-dot-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-dot-pending",
                searchTerms: []
            },
            {
                title: "fi-rr-dove",
                searchTerms: []
            },
            {
                title: "fi-rr-down",
                searchTerms: []
            },
            {
                title: "fi-rr-down-from-bracket",
                searchTerms: []
            },
            {
                title: "fi-rr-down-left",
                searchTerms: []
            },
            {
                title: "fi-rr-down-left-and-up-right-to-center",
                searchTerms: []
            },
            {
                title: "fi-rr-down-right",
                searchTerms: []
            },
            {
                title: "fi-rr-down-to-line",
                searchTerms: []
            },
            {
                title: "fi-rr-download",
                searchTerms: []
            },
            {
                title: "fi-rr-drafting-compass",
                searchTerms: []
            },
            {
                title: "fi-rr-dragon",
                searchTerms: []
            },
            {
                title: "fi-rr-draw-polygon",
                searchTerms: []
            },
            {
                title: "fi-rr-draw-square",
                searchTerms: []
            },
            {
                title: "fi-rr-drawer",
                searchTerms: []
            },
            {
                title: "fi-rr-drawer-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-drawer-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-dreidel",
                searchTerms: []
            },
            {
                title: "fi-rr-dress",
                searchTerms: []
            },
            {
                title: "fi-rr-drink-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-driver-man",
                searchTerms: []
            },
            {
                title: "fi-rr-driver-woman",
                searchTerms: []
            },
            {
                title: "fi-rr-drone",
                searchTerms: []
            },
            {
                title: "fi-rr-drone-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-drone-front",
                searchTerms: []
            },
            {
                title: "fi-rr-drop-down",
                searchTerms: []
            },
            {
                title: "fi-rr-dropdown",
                searchTerms: []
            },
            {
                title: "fi-rr-dropdown-bar",
                searchTerms: []
            },
            {
                title: "fi-rr-dropdown-select",
                searchTerms: []
            },
            {
                title: "fi-rr-drum",
                searchTerms: []
            },
            {
                title: "fi-rr-drum-steelpan",
                searchTerms: []
            },
            {
                title: "fi-rr-drumstick",
                searchTerms: []
            },
            {
                title: "fi-rr-drumstick-bite",
                searchTerms: []
            },
            {
                title: "fi-rr-dryer",
                searchTerms: []
            },
            {
                title: "fi-rr-dryer-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-duck",
                searchTerms: []
            },
            {
                title: "fi-rr-dumbbell-fitness",
                searchTerms: []
            },
            {
                title: "fi-rr-dumbbell-horizontal",
                searchTerms: []
            },
            {
                title: "fi-rr-dumbbell-ray",
                searchTerms: []
            },
            {
                title: "fi-rr-dumbbell-weightlifting",
                searchTerms: []
            },
            {
                title: "fi-rr-dumpster",
                searchTerms: []
            },
            {
                title: "fi-rr-dumpster-fire",
                searchTerms: []
            },
            {
                title: "fi-rr-dungeon",
                searchTerms: []
            },
            {
                title: "fi-rr-duplicate",
                searchTerms: []
            },
            {
                title: "fi-rr-duration",
                searchTerms: []
            },
            {
                title: "fi-rr-duration-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-e",
                searchTerms: []
            },
            {
                title: "fi-rr-e-learning",
                searchTerms: []
            },
            {
                title: "fi-rr-ear",
                searchTerms: []
            },
            {
                title: "fi-rr-ear-deaf",
                searchTerms: []
            },
            {
                title: "fi-rr-ear-muffs",
                searchTerms: []
            },
            {
                title: "fi-rr-ear-sound",
                searchTerms: []
            },
            {
                title: "fi-rr-earbuds",
                searchTerms: []
            },
            {
                title: "fi-rr-earnings",
                searchTerms: []
            },
            {
                title: "fi-rr-earth-africa",
                searchTerms: []
            },
            {
                title: "fi-rr-earth-americas",
                searchTerms: []
            },
            {
                title: "fi-rr-earth-asia",
                searchTerms: []
            },
            {
                title: "fi-rr-earth-europa",
                searchTerms: []
            },
            {
                title: "fi-rr-eclipse",
                searchTerms: []
            },
            {
                title: "fi-rr-eclipse-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-eco-electric",
                searchTerms: []
            },
            {
                title: "fi-rr-edit",
                searchTerms: []
            },
            {
                title: "fi-rr-edit-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-edit-message",
                searchTerms: []
            },
            {
                title: "fi-rr-effect",
                searchTerms: []
            },
            {
                title: "fi-rr-egg",
                searchTerms: []
            },
            {
                title: "fi-rr-egg-fried",
                searchTerms: []
            },
            {
                title: "fi-rr-eiffel-tower",
                searchTerms: []
            },
            {
                title: "fi-rr-eject",
                searchTerms: []
            },
            {
                title: "fi-rr-elephant",
                searchTerms: []
            },
            {
                title: "fi-rr-elevator",
                searchTerms: []
            },
            {
                title: "fi-rr-email-pending",
                searchTerms: []
            },
            {
                title: "fi-rr-email-refresh",
                searchTerms: []
            },
            {
                title: "fi-rr-emergency-call",
                searchTerms: []
            },
            {
                title: "fi-rr-employee-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-employee-handbook",
                searchTerms: []
            },
            {
                title: "fi-rr-employee-man",
                searchTerms: []
            },
            {
                title: "fi-rr-employee-man-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-employees",
                searchTerms: []
            },
            {
                title: "fi-rr-employees-woman-man",
                searchTerms: []
            },
            {
                title: "fi-rr-empty-set",
                searchTerms: []
            },
            {
                title: "fi-rr-endless-loop",
                searchTerms: []
            },
            {
                title: "fi-rr-engine",
                searchTerms: []
            },
            {
                title: "fi-rr-engine-warning",
                searchTerms: []
            },
            {
                title: "fi-rr-english",
                searchTerms: []
            },
            {
                title: "fi-rr-enter",
                searchTerms: []
            },
            {
                title: "fi-rr-envelope",
                searchTerms: []
            },
            {
                title: "fi-rr-envelope-ban",
                searchTerms: []
            },
            {
                title: "fi-rr-envelope-bulk",
                searchTerms: []
            },
            {
                title: "fi-rr-envelope-dot",
                searchTerms: []
            },
            {
                title: "fi-rr-envelope-download",
                searchTerms: []
            },
            {
                title: "fi-rr-envelope-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-envelope-marker",
                searchTerms: []
            },
            {
                title: "fi-rr-envelope-open",
                searchTerms: []
            },
            {
                title: "fi-rr-envelope-open-dollar",
                searchTerms: []
            },
            {
                title: "fi-rr-envelope-open-text",
                searchTerms: []
            },
            {
                title: "fi-rr-envelope-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-envelopes",
                searchTerms: []
            },
            {
                title: "fi-rr-equality",
                searchTerms: []
            },
            {
                title: "fi-rr-equals",
                searchTerms: []
            },
            {
                title: "fi-rr-equestrian-statue",
                searchTerms: []
            },
            {
                title: "fi-rr-eraser",
                searchTerms: []
            },
            {
                title: "fi-rr-error-camera",
                searchTerms: []
            },
            {
                title: "fi-rr-escalator",
                searchTerms: []
            },
            {
                title: "fi-rr-ethernet",
                searchTerms: []
            },
            {
                title: "fi-rr-euro",
                searchTerms: []
            },
            {
                title: "fi-rr-europe-flag",
                searchTerms: []
            },
            {
                title: "fi-rr-europe-map",
                searchTerms: []
            },
            {
                title: "fi-rr-european-union",
                searchTerms: []
            },
            {
                title: "fi-rr-excavator",
                searchTerms: []
            },
            {
                title: "fi-rr-exchange",
                searchTerms: []
            },
            {
                title: "fi-rr-exchange-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-exchange-cryptocurrency",
                searchTerms: []
            },
            {
                title: "fi-rr-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-exit",
                searchTerms: []
            },
            {
                title: "fi-rr-exit-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-expand",
                searchTerms: []
            },
            {
                title: "fi-rr-expand-arrows",
                searchTerms: []
            },
            {
                title: "fi-rr-expand-arrows-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-expense",
                searchTerms: []
            },
            {
                title: "fi-rr-expense-bill",
                searchTerms: []
            },
            {
                title: "fi-rr-explosion",
                searchTerms: []
            },
            {
                title: "fi-rr-external-hard-drive",
                searchTerms: []
            },
            {
                title: "fi-rr-external-world",
                searchTerms: []
            },
            {
                title: "fi-rr-eye",
                searchTerms: []
            },
            {
                title: "fi-rr-eye-alert",
                searchTerms: []
            },
            {
                title: "fi-rr-eye-arrow-progress",
                searchTerms: []
            },
            {
                title: "fi-rr-eye-crossed",
                searchTerms: []
            },
            {
                title: "fi-rr-eye-dropper",
                searchTerms: []
            },
            {
                title: "fi-rr-eye-dropper-half",
                searchTerms: []
            },
            {
                title: "fi-rr-eyes",
                searchTerms: []
            },
            {
                title: "fi-rr-f",
                searchTerms: []
            },
            {
                title: "fi-rr-fabric",
                searchTerms: []
            },
            {
                title: "fi-rr-face-angry-horns",
                searchTerms: []
            },
            {
                title: "fi-rr-face-anguished",
                searchTerms: []
            },
            {
                title: "fi-rr-face-anxious-sweat",
                searchTerms: []
            },
            {
                title: "fi-rr-face-astonished",
                searchTerms: []
            },
            {
                title: "fi-rr-face-awesome",
                searchTerms: []
            },
            {
                title: "fi-rr-face-beam-hand-over-mouth",
                searchTerms: []
            },
            {
                title: "fi-rr-face-confounded",
                searchTerms: []
            },
            {
                title: "fi-rr-face-confused",
                searchTerms: []
            },
            {
                title: "fi-rr-face-cowboy-hat",
                searchTerms: []
            },
            {
                title: "fi-rr-face-disappointed",
                searchTerms: []
            },
            {
                title: "fi-rr-face-disguise",
                searchTerms: []
            },
            {
                title: "fi-rr-face-downcast-sweat",
                searchTerms: []
            },
            {
                title: "fi-rr-face-drooling",
                searchTerms: []
            },
            {
                title: "fi-rr-face-explode",
                searchTerms: []
            },
            {
                title: "fi-rr-face-expressionless",
                searchTerms: []
            },
            {
                title: "fi-rr-face-eyes-xmarks",
                searchTerms: []
            },
            {
                title: "fi-rr-face-fearful",
                searchTerms: []
            },
            {
                title: "fi-rr-face-glasses",
                searchTerms: []
            },
            {
                title: "fi-rr-face-grin-tongue-wink",
                searchTerms: []
            },
            {
                title: "fi-rr-face-hand-yawn",
                searchTerms: []
            },
            {
                title: "fi-rr-face-head-bandage",
                searchTerms: []
            },
            {
                title: "fi-rr-face-hushed",
                searchTerms: []
            },
            {
                title: "fi-rr-face-icicles",
                searchTerms: []
            },
            {
                title: "fi-rr-face-lying",
                searchTerms: []
            },
            {
                title: "fi-rr-face-mask",
                searchTerms: []
            },
            {
                title: "fi-rr-face-monocle",
                searchTerms: []
            },
            {
                title: "fi-rr-face-nauseated",
                searchTerms: []
            },
            {
                title: "fi-rr-face-nose-steam",
                searchTerms: []
            },
            {
                title: "fi-rr-face-party",
                searchTerms: []
            },
            {
                title: "fi-rr-face-pensive",
                searchTerms: []
            },
            {
                title: "fi-rr-face-persevering",
                searchTerms: []
            },
            {
                title: "fi-rr-face-pleading",
                searchTerms: []
            },
            {
                title: "fi-rr-face-raised-eyebrow",
                searchTerms: []
            },
            {
                title: "fi-rr-face-relieved",
                searchTerms: []
            },
            {
                title: "fi-rr-face-sad-sweat",
                searchTerms: []
            },
            {
                title: "fi-rr-face-scream",
                searchTerms: []
            },
            {
                title: "fi-rr-face-shush",
                searchTerms: []
            },
            {
                title: "fi-rr-face-sleeping",
                searchTerms: []
            },
            {
                title: "fi-rr-face-sleepy",
                searchTerms: []
            },
            {
                title: "fi-rr-face-smile-halo",
                searchTerms: []
            },
            {
                title: "fi-rr-face-smile-hearts",
                searchTerms: []
            },
            {
                title: "fi-rr-face-smile-horns",
                searchTerms: []
            },
            {
                title: "fi-rr-face-smile-tear",
                searchTerms: []
            },
            {
                title: "fi-rr-face-smile-tongue",
                searchTerms: []
            },
            {
                title: "fi-rr-face-smile-upside-down",
                searchTerms: []
            },
            {
                title: "fi-rr-face-smiling-hands",
                searchTerms: []
            },
            {
                title: "fi-rr-face-smirking",
                searchTerms: []
            },
            {
                title: "fi-rr-face-sunglasses",
                searchTerms: []
            },
            {
                title: "fi-rr-face-sunglasses-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-face-swear",
                searchTerms: []
            },
            {
                title: "fi-rr-face-thermometer",
                searchTerms: []
            },
            {
                title: "fi-rr-face-thinking",
                searchTerms: []
            },
            {
                title: "fi-rr-face-tissue",
                searchTerms: []
            },
            {
                title: "fi-rr-face-tongue-money",
                searchTerms: []
            },
            {
                title: "fi-rr-face-tongue-sweat",
                searchTerms: []
            },
            {
                title: "fi-rr-face-unamused",
                searchTerms: []
            },
            {
                title: "fi-rr-face-viewfinder",
                searchTerms: []
            },
            {
                title: "fi-rr-face-vomit",
                searchTerms: []
            },
            {
                title: "fi-rr-face-weary",
                searchTerms: []
            },
            {
                title: "fi-rr-face-woozy",
                searchTerms: []
            },
            {
                title: "fi-rr-face-worried",
                searchTerms: []
            },
            {
                title: "fi-rr-face-zany",
                searchTerms: []
            },
            {
                title: "fi-rr-face-zipper",
                searchTerms: []
            },
            {
                title: "fi-rr-facial-massage",
                searchTerms: []
            },
            {
                title: "fi-rr-fail",
                searchTerms: []
            },
            {
                title: "fi-rr-falafel",
                searchTerms: []
            },
            {
                title: "fi-rr-family",
                searchTerms: []
            },
            {
                title: "fi-rr-family-dress",
                searchTerms: []
            },
            {
                title: "fi-rr-family-pants",
                searchTerms: []
            },
            {
                title: "fi-rr-fan",
                searchTerms: []
            },
            {
                title: "fi-rr-fan-table",
                searchTerms: []
            },
            {
                title: "fi-rr-farfalle",
                searchTerms: []
            },
            {
                title: "fi-rr-farm",
                searchTerms: []
            },
            {
                title: "fi-rr-faucet",
                searchTerms: []
            },
            {
                title: "fi-rr-faucet-drip",
                searchTerms: []
            },
            {
                title: "fi-rr-fax",
                searchTerms: []
            },
            {
                title: "fi-rr-feather",
                searchTerms: []
            },
            {
                title: "fi-rr-feather-pointed",
                searchTerms: []
            },
            {
                title: "fi-rr-features",
                searchTerms: []
            },
            {
                title: "fi-rr-features-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-fee",
                searchTerms: []
            },
            {
                title: "fi-rr-fee-receipt",
                searchTerms: []
            },
            {
                title: "fi-rr-feedback",
                searchTerms: []
            },
            {
                title: "fi-rr-feedback-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-feedback-cycle-loop",
                searchTerms: []
            },
            {
                title: "fi-rr-feedback-hand",
                searchTerms: []
            },
            {
                title: "fi-rr-feedback-review",
                searchTerms: []
            },
            {
                title: "fi-rr-fence",
                searchTerms: []
            },
            {
                title: "fi-rr-ferris-wheel",
                searchTerms: []
            },
            {
                title: "fi-rr-fighter-jet",
                searchTerms: []
            },
            {
                title: "fi-rr-file",
                searchTerms: []
            },
            {
                title: "fi-rr-file-ai",
                searchTerms: []
            },
            {
                title: "fi-rr-file-audio",
                searchTerms: []
            },
            {
                title: "fi-rr-file-binary",
                searchTerms: []
            },
            {
                title: "fi-rr-file-chart-line",
                searchTerms: []
            },
            {
                title: "fi-rr-file-chart-pie",
                searchTerms: []
            },
            {
                title: "fi-rr-file-circle-info",
                searchTerms: []
            },
            {
                title: "fi-rr-file-cloud",
                searchTerms: []
            },
            {
                title: "fi-rr-file-code",
                searchTerms: []
            },
            {
                title: "fi-rr-file-csv",
                searchTerms: []
            },
            {
                title: "fi-rr-file-download",
                searchTerms: []
            },
            {
                title: "fi-rr-file-edit",
                searchTerms: []
            },
            {
                title: "fi-rr-file-eps",
                searchTerms: []
            },
            {
                title: "fi-rr-file-excel",
                searchTerms: []
            },
            {
                title: "fi-rr-file-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-file-export",
                searchTerms: []
            },
            {
                title: "fi-rr-file-image",
                searchTerms: []
            },
            {
                title: "fi-rr-file-import",
                searchTerms: []
            },
            {
                title: "fi-rr-file-invoice",
                searchTerms: []
            },
            {
                title: "fi-rr-file-invoice-dollar",
                searchTerms: []
            },
            {
                title: "fi-rr-file-loop",
                searchTerms: []
            },
            {
                title: "fi-rr-file-medical",
                searchTerms: []
            },
            {
                title: "fi-rr-file-medical-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-file-minus",
                searchTerms: []
            },
            {
                title: "fi-rr-file-pdf",
                searchTerms: []
            },
            {
                title: "fi-rr-file-powerpoint",
                searchTerms: []
            },
            {
                title: "fi-rr-file-prescription",
                searchTerms: []
            },
            {
                title: "fi-rr-file-psd",
                searchTerms: []
            },
            {
                title: "fi-rr-file-question",
                searchTerms: []
            },
            {
                title: "fi-rr-file-recycle",
                searchTerms: []
            },
            {
                title: "fi-rr-file-signature",
                searchTerms: []
            },
            {
                title: "fi-rr-file-spreadsheet",
                searchTerms: []
            },
            {
                title: "fi-rr-file-upload",
                searchTerms: []
            },
            {
                title: "fi-rr-file-user",
                searchTerms: []
            },
            {
                title: "fi-rr-file-video",
                searchTerms: []
            },
            {
                title: "fi-rr-file-word",
                searchTerms: []
            },
            {
                title: "fi-rr-file-xls",
                searchTerms: []
            },
            {
                title: "fi-rr-file-zip-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-file-zip-save",
                searchTerms: []
            },
            {
                title: "fi-rr-file-zipper",
                searchTerms: []
            },
            {
                title: "fi-rr-files-medical",
                searchTerms: []
            },
            {
                title: "fi-rr-fill",
                searchTerms: []
            },
            {
                title: "fi-rr-film",
                searchTerms: []
            },
            {
                title: "fi-rr-film-canister",
                searchTerms: []
            },
            {
                title: "fi-rr-film-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-films",
                searchTerms: []
            },
            {
                title: "fi-rr-filter",
                searchTerms: []
            },
            {
                title: "fi-rr-filter-list",
                searchTerms: []
            },
            {
                title: "fi-rr-filter-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-filters",
                searchTerms: []
            },
            {
                title: "fi-rr-finger-droplet",
                searchTerms: []
            },
            {
                title: "fi-rr-finger-nail",
                searchTerms: []
            },
            {
                title: "fi-rr-fingerprint",
                searchTerms: []
            },
            {
                title: "fi-rr-fingerprint-remove",
                searchTerms: []
            },
            {
                title: "fi-rr-fingerprint-security-risk",
                searchTerms: []
            },
            {
                title: "fi-rr-fingerprint-verified",
                searchTerms: []
            },
            {
                title: "fi-rr-fire-burner",
                searchTerms: []
            },
            {
                title: "fi-rr-fire-extinguisher",
                searchTerms: []
            },
            {
                title: "fi-rr-fire-flame-curved",
                searchTerms: []
            },
            {
                title: "fi-rr-fire-flame-simple",
                searchTerms: []
            },
            {
                title: "fi-rr-fire-hydrant",
                searchTerms: []
            },
            {
                title: "fi-rr-fire-smoke",
                searchTerms: []
            },
            {
                title: "fi-rr-fireplace",
                searchTerms: []
            },
            {
                title: "fi-rr-first",
                searchTerms: []
            },
            {
                title: "fi-rr-first-award",
                searchTerms: []
            },
            {
                title: "fi-rr-first-laurel",
                searchTerms: []
            },
            {
                title: "fi-rr-first-medal",
                searchTerms: []
            },
            {
                title: "fi-rr-fish",
                searchTerms: []
            },
            {
                title: "fi-rr-fish-bones",
                searchTerms: []
            },
            {
                title: "fi-rr-fish-cooked",
                searchTerms: []
            },
            {
                title: "fi-rr-fishing-rod",
                searchTerms: []
            },
            {
                title: "fi-rr-fist-move",
                searchTerms: []
            },
            {
                title: "fi-rr-fitness-watch",
                searchTerms: []
            },
            {
                title: "fi-rr-flag",
                searchTerms: []
            },
            {
                title: "fi-rr-flag-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-flag-checkered",
                searchTerms: []
            },
            {
                title: "fi-rr-flag-usa",
                searchTerms: []
            },
            {
                title: "fi-rr-flame",
                searchTerms: []
            },
            {
                title: "fi-rr-flashlight",
                searchTerms: []
            },
            {
                title: "fi-rr-flask",
                searchTerms: []
            },
            {
                title: "fi-rr-flask-gear",
                searchTerms: []
            },
            {
                title: "fi-rr-flask-poison",
                searchTerms: []
            },
            {
                title: "fi-rr-flask-potion",
                searchTerms: []
            },
            {
                title: "fi-rr-flatbread",
                searchTerms: []
            },
            {
                title: "fi-rr-flatbread-stuffed",
                searchTerms: []
            },
            {
                title: "fi-rr-flip-horizontal",
                searchTerms: []
            },
            {
                title: "fi-rr-floor",
                searchTerms: []
            },
            {
                title: "fi-rr-floor-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-floor-layer",
                searchTerms: []
            },
            {
                title: "fi-rr-floppy-disk-circle-arrow-right",
                searchTerms: []
            },
            {
                title: "fi-rr-floppy-disk-circle-xmark",
                searchTerms: []
            },
            {
                title: "fi-rr-floppy-disk-pen",
                searchTerms: []
            },
            {
                title: "fi-rr-floppy-disks",
                searchTerms: []
            },
            {
                title: "fi-rr-florin-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-flowchart",
                searchTerms: []
            },
            {
                title: "fi-rr-flower",
                searchTerms: []
            },
            {
                title: "fi-rr-flower-bouquet",
                searchTerms: []
            },
            {
                title: "fi-rr-flower-butterfly",
                searchTerms: []
            },
            {
                title: "fi-rr-flower-daffodil",
                searchTerms: []
            },
            {
                title: "fi-rr-flower-tulip",
                searchTerms: []
            },
            {
                title: "fi-rr-flushed",
                searchTerms: []
            },
            {
                title: "fi-rr-flute",
                searchTerms: []
            },
            {
                title: "fi-rr-flux-capacitor",
                searchTerms: []
            },
            {
                title: "fi-rr-fly-insect",
                searchTerms: []
            },
            {
                title: "fi-rr-flying-disc",
                searchTerms: []
            },
            {
                title: "fi-rr-fm-radio",
                searchTerms: []
            },
            {
                title: "fi-rr-fog",
                searchTerms: []
            },
            {
                title: "fi-rr-folder",
                searchTerms: []
            },
            {
                title: "fi-rr-folder-archive",
                searchTerms: []
            },
            {
                title: "fi-rr-folder-camera",
                searchTerms: []
            },
            {
                title: "fi-rr-folder-directory",
                searchTerms: []
            },
            {
                title: "fi-rr-folder-download",
                searchTerms: []
            },
            {
                title: "fi-rr-folder-fire",
                searchTerms: []
            },
            {
                title: "fi-rr-folder-gear",
                searchTerms: []
            },
            {
                title: "fi-rr-folder-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-folder-loop",
                searchTerms: []
            },
            {
                title: "fi-rr-folder-math",
                searchTerms: []
            },
            {
                title: "fi-rr-folder-minus",
                searchTerms: []
            },
            {
                title: "fi-rr-folder-music",
                searchTerms: []
            },
            {
                title: "fi-rr-folder-open",
                searchTerms: []
            },
            {
                title: "fi-rr-folder-plus-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-folder-times",
                searchTerms: []
            },
            {
                title: "fi-rr-folder-tree",
                searchTerms: []
            },
            {
                title: "fi-rr-folder-upload",
                searchTerms: []
            },
            {
                title: "fi-rr-folder-xmark",
                searchTerms: []
            },
            {
                title: "fi-rr-folder-xmark-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-folders",
                searchTerms: []
            },
            {
                title: "fi-rr-follow-folder",
                searchTerms: []
            },
            {
                title: "fi-rr-followcollection",
                searchTerms: []
            },
            {
                title: "fi-rr-following",
                searchTerms: []
            },
            {
                title: "fi-rr-fondue-pot",
                searchTerms: []
            },
            {
                title: "fi-rr-football",
                searchTerms: []
            },
            {
                title: "fi-rr-footprint",
                searchTerms: []
            },
            {
                title: "fi-rr-foreign-language-audio",
                searchTerms: []
            },
            {
                title: "fi-rr-forest",
                searchTerms: []
            },
            {
                title: "fi-rr-forest-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-fork",
                searchTerms: []
            },
            {
                title: "fi-rr-fork-spaghetti",
                searchTerms: []
            },
            {
                title: "fi-rr-forklift",
                searchTerms: []
            },
            {
                title: "fi-rr-form",
                searchTerms: []
            },
            {
                title: "fi-rr-fort",
                searchTerms: []
            },
            {
                title: "fi-rr-forward",
                searchTerms: []
            },
            {
                title: "fi-rr-forward-fast",
                searchTerms: []
            },
            {
                title: "fi-rr-fox",
                searchTerms: []
            },
            {
                title: "fi-rr-frame",
                searchTerms: []
            },
            {
                title: "fi-rr-franc-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-free",
                searchTerms: []
            },
            {
                title: "fi-rr-free-delivery",
                searchTerms: []
            },
            {
                title: "fi-rr-freemium",
                searchTerms: []
            },
            {
                title: "fi-rr-french",
                searchTerms: []
            },
            {
                title: "fi-rr-french-fries",
                searchTerms: []
            },
            {
                title: "fi-rr-friday",
                searchTerms: []
            },
            {
                title: "fi-rr-frog",
                searchTerms: []
            },
            {
                title: "fi-rr-frown",
                searchTerms: []
            },
            {
                title: "fi-rr-ftp",
                searchTerms: []
            },
            {
                title: "fi-rr-fuel-gauge",
                searchTerms: []
            },
            {
                title: "fi-rr-function",
                searchTerms: []
            },
            {
                title: "fi-rr-function-process",
                searchTerms: []
            },
            {
                title: "fi-rr-function-square",
                searchTerms: []
            },
            {
                title: "fi-rr-funnel-dollar",
                searchTerms: []
            },
            {
                title: "fi-rr-funnel-money",
                searchTerms: []
            },
            {
                title: "fi-rr-fusilli",
                searchTerms: []
            },
            {
                title: "fi-rr-g",
                searchTerms: []
            },
            {
                title: "fi-rr-galaxy",
                searchTerms: []
            },
            {
                title: "fi-rr-galaxy-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-galaxy-planet",
                searchTerms: []
            },
            {
                title: "fi-rr-galaxy-star",
                searchTerms: []
            },
            {
                title: "fi-rr-gallery",
                searchTerms: []
            },
            {
                title: "fi-rr-gallery-thumbnails",
                searchTerms: []
            },
            {
                title: "fi-rr-game-board-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-gamepad",
                searchTerms: []
            },
            {
                title: "fi-rr-garage",
                searchTerms: []
            },
            {
                title: "fi-rr-garage-car",
                searchTerms: []
            },
            {
                title: "fi-rr-garage-open",
                searchTerms: []
            },
            {
                title: "fi-rr-garlic",
                searchTerms: []
            },
            {
                title: "fi-rr-garlic-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-gas-pump",
                searchTerms: []
            },
            {
                title: "fi-rr-gas-pump-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-gas-pump-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-gauge-circle-bolt",
                searchTerms: []
            },
            {
                title: "fi-rr-gauge-circle-minus",
                searchTerms: []
            },
            {
                title: "fi-rr-gauge-circle-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-gauze-bandage",
                searchTerms: []
            },
            {
                title: "fi-rr-gavel",
                searchTerms: []
            },
            {
                title: "fi-rr-gay-couple",
                searchTerms: []
            },
            {
                title: "fi-rr-gears",
                searchTerms: []
            },
            {
                title: "fi-rr-gem",
                searchTerms: []
            },
            {
                title: "fi-rr-general",
                searchTerms: []
            },
            {
                title: "fi-rr-german",
                searchTerms: []
            },
            {
                title: "fi-rr-ghost",
                searchTerms: []
            },
            {
                title: "fi-rr-gif",
                searchTerms: []
            },
            {
                title: "fi-rr-gif-square",
                searchTerms: []
            },
            {
                title: "fi-rr-gift",
                searchTerms: []
            },
            {
                title: "fi-rr-gift-box-benefits",
                searchTerms: []
            },
            {
                title: "fi-rr-gift-card",
                searchTerms: []
            },
            {
                title: "fi-rr-gifts",
                searchTerms: []
            },
            {
                title: "fi-rr-gingerbread-man",
                searchTerms: []
            },
            {
                title: "fi-rr-glass",
                searchTerms: []
            },
            {
                title: "fi-rr-glass-champagne",
                searchTerms: []
            },
            {
                title: "fi-rr-glass-cheers",
                searchTerms: []
            },
            {
                title: "fi-rr-glass-citrus",
                searchTerms: []
            },
            {
                title: "fi-rr-glass-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-glass-half",
                searchTerms: []
            },
            {
                title: "fi-rr-glass-water-droplet",
                searchTerms: []
            },
            {
                title: "fi-rr-glass-whiskey",
                searchTerms: []
            },
            {
                title: "fi-rr-glass-whiskey-rocks",
                searchTerms: []
            },
            {
                title: "fi-rr-glasses",
                searchTerms: []
            },
            {
                title: "fi-rr-globe",
                searchTerms: []
            },
            {
                title: "fi-rr-globe-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-globe-pointer",
                searchTerms: []
            },
            {
                title: "fi-rr-globe-snow",
                searchTerms: []
            },
            {
                title: "fi-rr-globe-user",
                searchTerms: []
            },
            {
                title: "fi-rr-goal-net",
                searchTerms: []
            },
            {
                title: "fi-rr-golf",
                searchTerms: []
            },
            {
                title: "fi-rr-golf-ball",
                searchTerms: []
            },
            {
                title: "fi-rr-golf-hole",
                searchTerms: []
            },
            {
                title: "fi-rr-gopuram",
                searchTerms: []
            },
            {
                title: "fi-rr-government-budget",
                searchTerms: []
            },
            {
                title: "fi-rr-government-flag",
                searchTerms: []
            },
            {
                title: "fi-rr-government-user",
                searchTerms: []
            },
            {
                title: "fi-rr-gps-navigation",
                searchTerms: []
            },
            {
                title: "fi-rr-graduation-cap",
                searchTerms: []
            },
            {
                title: "fi-rr-gramophone",
                searchTerms: []
            },
            {
                title: "fi-rr-grape",
                searchTerms: []
            },
            {
                title: "fi-rr-graph-curve",
                searchTerms: []
            },
            {
                title: "fi-rr-graphic-style",
                searchTerms: []
            },
            {
                title: "fi-rr-graphic-tablet",
                searchTerms: []
            },
            {
                title: "fi-rr-grass",
                searchTerms: []
            },
            {
                title: "fi-rr-grate",
                searchTerms: []
            },
            {
                title: "fi-rr-grate-droplet",
                searchTerms: []
            },
            {
                title: "fi-rr-greater-than",
                searchTerms: []
            },
            {
                title: "fi-rr-greater-than-equal",
                searchTerms: []
            },
            {
                title: "fi-rr-greek-helmet",
                searchTerms: []
            },
            {
                title: "fi-rr-grid",
                searchTerms: []
            },
            {
                title: "fi-rr-grid-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-grid-dividers",
                searchTerms: []
            },
            {
                title: "fi-rr-grill",
                searchTerms: []
            },
            {
                title: "fi-rr-grill-hot-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-grimace",
                searchTerms: []
            },
            {
                title: "fi-rr-grin",
                searchTerms: []
            },
            {
                title: "fi-rr-grin-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-grin-beam",
                searchTerms: []
            },
            {
                title: "fi-rr-grin-beam-sweat",
                searchTerms: []
            },
            {
                title: "fi-rr-grin-hearts",
                searchTerms: []
            },
            {
                title: "fi-rr-grin-squint",
                searchTerms: []
            },
            {
                title: "fi-rr-grin-squint-tears",
                searchTerms: []
            },
            {
                title: "fi-rr-grin-stars",
                searchTerms: []
            },
            {
                title: "fi-rr-grin-tears",
                searchTerms: []
            },
            {
                title: "fi-rr-grin-tongue",
                searchTerms: []
            },
            {
                title: "fi-rr-grin-tongue-squint",
                searchTerms: []
            },
            {
                title: "fi-rr-grin-tongue-wink",
                searchTerms: []
            },
            {
                title: "fi-rr-grin-wink",
                searchTerms: []
            },
            {
                title: "fi-rr-grip-dots",
                searchTerms: []
            },
            {
                title: "fi-rr-grip-dots-vertical",
                searchTerms: []
            },
            {
                title: "fi-rr-grip-horizontal",
                searchTerms: []
            },
            {
                title: "fi-rr-grip-lines",
                searchTerms: []
            },
            {
                title: "fi-rr-grip-lines-vertical",
                searchTerms: []
            },
            {
                title: "fi-rr-grip-vertical",
                searchTerms: []
            },
            {
                title: "fi-rr-grocery-bag",
                searchTerms: []
            },
            {
                title: "fi-rr-grocery-basket",
                searchTerms: []
            },
            {
                title: "fi-rr-group-arrows-rotate",
                searchTerms: []
            },
            {
                title: "fi-rr-group-call",
                searchTerms: []
            },
            {
                title: "fi-rr-group-community-social-media",
                searchTerms: []
            },
            {
                title: "fi-rr-growth-chart-invest",
                searchTerms: []
            },
            {
                title: "fi-rr-guarani-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-guide",
                searchTerms: []
            },
            {
                title: "fi-rr-guide-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-guitar",
                searchTerms: []
            },
            {
                title: "fi-rr-guitar-electric",
                searchTerms: []
            },
            {
                title: "fi-rr-guitars",
                searchTerms: []
            },
            {
                title: "fi-rr-gun-squirt",
                searchTerms: []
            },
            {
                title: "fi-rr-gym",
                searchTerms: []
            },
            {
                title: "fi-rr-h",
                searchTerms: []
            },
            {
                title: "fi-rr-h-square",
                searchTerms: []
            },
            {
                title: "fi-rr-h1",
                searchTerms: []
            },
            {
                title: "fi-rr-h2",
                searchTerms: []
            },
            {
                title: "fi-rr-h3",
                searchTerms: []
            },
            {
                title: "fi-rr-h4",
                searchTerms: []
            },
            {
                title: "fi-rr-hair-clipper",
                searchTerms: []
            },
            {
                title: "fi-rr-hamburger",
                searchTerms: []
            },
            {
                title: "fi-rr-hamburger-soda",
                searchTerms: []
            },
            {
                title: "fi-rr-hammer",
                searchTerms: []
            },
            {
                title: "fi-rr-hammer-brush",
                searchTerms: []
            },
            {
                title: "fi-rr-hammer-crash",
                searchTerms: []
            },
            {
                title: "fi-rr-hammer-war",
                searchTerms: []
            },
            {
                title: "fi-rr-hamsa",
                searchTerms: []
            },
            {
                title: "fi-rr-hand",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-back-fist",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-back-point-down",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-back-point-left",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-back-point-ribbon",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-back-point-right",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-bandage-wound",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-bill",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-dots",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-fingers-crossed",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-fist",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-holding-box",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-holding-document",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-holding-droplet",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-holding-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-holding-magic",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-holding-medical",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-holding-seeding",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-holding-skull",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-holding-usd",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-holding-water",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-horns",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-key",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-lizard",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-love",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-middle-finger",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-paper",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-peace",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-point-ribbon",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-present",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-scissors",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-sparkles",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-spock",
                searchTerms: []
            },
            {
                title: "fi-rr-hand-wave",
                searchTerms: []
            },
            {
                title: "fi-rr-handcuffs",
                searchTerms: []
            },
            {
                title: "fi-rr-handmade",
                searchTerms: []
            },
            {
                title: "fi-rr-hands-brain",
                searchTerms: []
            },
            {
                title: "fi-rr-hands-bubbles",
                searchTerms: []
            },
            {
                title: "fi-rr-hands-clapping",
                searchTerms: []
            },
            {
                title: "fi-rr-hands-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-hands-holding",
                searchTerms: []
            },
            {
                title: "fi-rr-hands-holding-diamond",
                searchTerms: []
            },
            {
                title: "fi-rr-hands-together",
                searchTerms: []
            },
            {
                title: "fi-rr-hands-together-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-hands-usd",
                searchTerms: []
            },
            {
                title: "fi-rr-handshake",
                searchTerms: []
            },
            {
                title: "fi-rr-handshake-angle",
                searchTerms: []
            },
            {
                title: "fi-rr-handshake-deal-loan",
                searchTerms: []
            },
            {
                title: "fi-rr-handshake-house",
                searchTerms: []
            },
            {
                title: "fi-rr-handshake-simple-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-handshake-trust",
                searchTerms: []
            },
            {
                title: "fi-rr-hard-hat",
                searchTerms: []
            },
            {
                title: "fi-rr-hashtag-lock",
                searchTerms: []
            },
            {
                title: "fi-rr-hastag",
                searchTerms: []
            },
            {
                title: "fi-rr-hat-beach",
                searchTerms: []
            },
            {
                title: "fi-rr-hat-birthday",
                searchTerms: []
            },
            {
                title: "fi-rr-hat-chef",
                searchTerms: []
            },
            {
                title: "fi-rr-hat-cowboy",
                searchTerms: []
            },
            {
                title: "fi-rr-hat-cowboy-side",
                searchTerms: []
            },
            {
                title: "fi-rr-hat-santa",
                searchTerms: []
            },
            {
                title: "fi-rr-hat-winter",
                searchTerms: []
            },
            {
                title: "fi-rr-hat-witch",
                searchTerms: []
            },
            {
                title: "fi-rr-hat-wizard",
                searchTerms: []
            },
            {
                title: "fi-rr-hdd",
                searchTerms: []
            },
            {
                title: "fi-rr-head-side",
                searchTerms: []
            },
            {
                title: "fi-rr-head-side-brain",
                searchTerms: []
            },
            {
                title: "fi-rr-head-side-cough",
                searchTerms: []
            },
            {
                title: "fi-rr-head-side-cough-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-head-side-headphones",
                searchTerms: []
            },
            {
                title: "fi-rr-head-side-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-head-side-mask",
                searchTerms: []
            },
            {
                title: "fi-rr-head-side-medical",
                searchTerms: []
            },
            {
                title: "fi-rr-head-side-thinking",
                searchTerms: []
            },
            {
                title: "fi-rr-head-side-virus",
                searchTerms: []
            },
            {
                title: "fi-rr-head-vr",
                searchTerms: []
            },
            {
                title: "fi-rr-heading",
                searchTerms: []
            },
            {
                title: "fi-rr-headphones",
                searchTerms: []
            },
            {
                title: "fi-rr-headset",
                searchTerms: []
            },
            {
                title: "fi-rr-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-heart-arrow",
                searchTerms: []
            },
            {
                title: "fi-rr-heart-brain",
                searchTerms: []
            },
            {
                title: "fi-rr-heart-circle-user",
                searchTerms: []
            },
            {
                title: "fi-rr-heart-crack",
                searchTerms: []
            },
            {
                title: "fi-rr-heart-half",
                searchTerms: []
            },
            {
                title: "fi-rr-heart-half-stroke",
                searchTerms: []
            },
            {
                title: "fi-rr-heart-health-muscle",
                searchTerms: []
            },
            {
                title: "fi-rr-heart-lock",
                searchTerms: []
            },
            {
                title: "fi-rr-heart-partner-handshake",
                searchTerms: []
            },
            {
                title: "fi-rr-heart-rate",
                searchTerms: []
            },
            {
                title: "fi-rr-heart-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-heart-upside-down",
                searchTerms: []
            },
            {
                title: "fi-rr-heat",
                searchTerms: []
            },
            {
                title: "fi-rr-helicopter-side",
                searchTerms: []
            },
            {
                title: "fi-rr-helmet-battle",
                searchTerms: []
            },
            {
                title: "fi-rr-hexagon",
                searchTerms: []
            },
            {
                title: "fi-rr-hexagon-check",
                searchTerms: []
            },
            {
                title: "fi-rr-hexagon-divide",
                searchTerms: []
            },
            {
                title: "fi-rr-hexagon-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-high-definition",
                searchTerms: []
            },
            {
                title: "fi-rr-high-five",
                searchTerms: []
            },
            {
                title: "fi-rr-high-five-celebration-yes",
                searchTerms: []
            },
            {
                title: "fi-rr-highlighter",
                searchTerms: []
            },
            {
                title: "fi-rr-highlighter-line",
                searchTerms: []
            },
            {
                title: "fi-rr-hiking",
                searchTerms: []
            },
            {
                title: "fi-rr-hiking-boot",
                searchTerms: []
            },
            {
                title: "fi-rr-hippo",
                searchTerms: []
            },
            {
                title: "fi-rr-hockey-puck",
                searchTerms: []
            },
            {
                title: "fi-rr-hockey-stick-puck",
                searchTerms: []
            },
            {
                title: "fi-rr-hockey-sticks",
                searchTerms: []
            },
            {
                title: "fi-rr-holding-hand-brain",
                searchTerms: []
            },
            {
                title: "fi-rr-holding-hand-dinner",
                searchTerms: []
            },
            {
                title: "fi-rr-holding-hand-direction",
                searchTerms: []
            },
            {
                title: "fi-rr-holding-hand-gear",
                searchTerms: []
            },
            {
                title: "fi-rr-holding-hand-key",
                searchTerms: []
            },
            {
                title: "fi-rr-holding-hand-nft",
                searchTerms: []
            },
            {
                title: "fi-rr-holding-hand-revenue",
                searchTerms: []
            },
            {
                title: "fi-rr-holly-berry",
                searchTerms: []
            },
            {
                title: "fi-rr-home",
                searchTerms: []
            },
            {
                title: "fi-rr-home-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-home-location",
                searchTerms: []
            },
            {
                title: "fi-rr-home-location-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-honey-pot",
                searchTerms: []
            },
            {
                title: "fi-rr-hood-cloak",
                searchTerms: []
            },
            {
                title: "fi-rr-horizontal-rule",
                searchTerms: []
            },
            {
                title: "fi-rr-horse",
                searchTerms: []
            },
            {
                title: "fi-rr-horse-head",
                searchTerms: []
            },
            {
                title: "fi-rr-horse-saddle",
                searchTerms: []
            },
            {
                title: "fi-rr-horseshoe",
                searchTerms: []
            },
            {
                title: "fi-rr-horseshoe-broken",
                searchTerms: []
            },
            {
                title: "fi-rr-hose",
                searchTerms: []
            },
            {
                title: "fi-rr-hose-reel",
                searchTerms: []
            },
            {
                title: "fi-rr-hospital",
                searchTerms: []
            },
            {
                title: "fi-rr-hospital-symbol",
                searchTerms: []
            },
            {
                title: "fi-rr-hospital-user",
                searchTerms: []
            },
            {
                title: "fi-rr-hospitals",
                searchTerms: []
            },
            {
                title: "fi-rr-hot-tub",
                searchTerms: []
            },
            {
                title: "fi-rr-hotdog",
                searchTerms: []
            },
            {
                title: "fi-rr-hotel",
                searchTerms: []
            },
            {
                title: "fi-rr-hourglass",
                searchTerms: []
            },
            {
                title: "fi-rr-hourglass-end",
                searchTerms: []
            },
            {
                title: "fi-rr-hourglass-start",
                searchTerms: []
            },
            {
                title: "fi-rr-house-blank",
                searchTerms: []
            },
            {
                title: "fi-rr-house-building",
                searchTerms: []
            },
            {
                title: "fi-rr-house-chimney",
                searchTerms: []
            },
            {
                title: "fi-rr-house-chimney-blank",
                searchTerms: []
            },
            {
                title: "fi-rr-house-chimney-crack",
                searchTerms: []
            },
            {
                title: "fi-rr-house-chimney-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-house-chimney-medical",
                searchTerms: []
            },
            {
                title: "fi-rr-house-chimney-user",
                searchTerms: []
            },
            {
                title: "fi-rr-house-chimney-window",
                searchTerms: []
            },
            {
                title: "fi-rr-house-circle-check",
                searchTerms: []
            },
            {
                title: "fi-rr-house-circle-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-house-circle-xmark",
                searchTerms: []
            },
            {
                title: "fi-rr-house-crack",
                searchTerms: []
            },
            {
                title: "fi-rr-house-crack-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-house-day",
                searchTerms: []
            },
            {
                title: "fi-rr-house-fire",
                searchTerms: []
            },
            {
                title: "fi-rr-house-flag",
                searchTerms: []
            },
            {
                title: "fi-rr-house-flood",
                searchTerms: []
            },
            {
                title: "fi-rr-house-key",
                searchTerms: []
            },
            {
                title: "fi-rr-house-laptop",
                searchTerms: []
            },
            {
                title: "fi-rr-house-leave",
                searchTerms: []
            },
            {
                title: "fi-rr-house-lock",
                searchTerms: []
            },
            {
                title: "fi-rr-house-medical",
                searchTerms: []
            },
            {
                title: "fi-rr-house-night",
                searchTerms: []
            },
            {
                title: "fi-rr-house-return",
                searchTerms: []
            },
            {
                title: "fi-rr-house-signal",
                searchTerms: []
            },
            {
                title: "fi-rr-house-tree",
                searchTerms: []
            },
            {
                title: "fi-rr-house-tsunami",
                searchTerms: []
            },
            {
                title: "fi-rr-house-turret",
                searchTerms: []
            },
            {
                title: "fi-rr-house-user",
                searchTerms: []
            },
            {
                title: "fi-rr-house-window",
                searchTerms: []
            },
            {
                title: "fi-rr-hr",
                searchTerms: []
            },
            {
                title: "fi-rr-hr-group",
                searchTerms: []
            },
            {
                title: "fi-rr-hr-person",
                searchTerms: []
            },
            {
                title: "fi-rr-hryvnia",
                searchTerms: []
            },
            {
                title: "fi-rr-humanitarian-mission",
                searchTerms: []
            },
            {
                title: "fi-rr-humidity",
                searchTerms: []
            },
            {
                title: "fi-rr-hundred-points",
                searchTerms: []
            },
            {
                title: "fi-rr-hurricane",
                searchTerms: []
            },
            {
                title: "fi-rr-hyperloop",
                searchTerms: []
            },
            {
                title: "fi-rr-i",
                searchTerms: []
            },
            {
                title: "fi-rr-ice-cream",
                searchTerms: []
            },
            {
                title: "fi-rr-ice-skate",
                searchTerms: []
            },
            {
                title: "fi-rr-icicles",
                searchTerms: []
            },
            {
                title: "fi-rr-icon-star",
                searchTerms: []
            },
            {
                title: "fi-rr-id-badge",
                searchTerms: []
            },
            {
                title: "fi-rr-id-card-clip-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-idea-exchange",
                searchTerms: []
            },
            {
                title: "fi-rr-igloo",
                searchTerms: []
            },
            {
                title: "fi-rr-image-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-images",
                searchTerms: []
            },
            {
                title: "fi-rr-images-user",
                searchTerms: []
            },
            {
                title: "fi-rr-improve-user",
                searchTerms: []
            },
            {
                title: "fi-rr-inbox",
                searchTerms: []
            },
            {
                title: "fi-rr-inbox-full",
                searchTerms: []
            },
            {
                title: "fi-rr-inbox-in",
                searchTerms: []
            },
            {
                title: "fi-rr-inbox-out",
                searchTerms: []
            },
            {
                title: "fi-rr-inboxes",
                searchTerms: []
            },
            {
                title: "fi-rr-incense-sticks-yoga",
                searchTerms: []
            },
            {
                title: "fi-rr-incognito",
                searchTerms: []
            },
            {
                title: "fi-rr-indent",
                searchTerms: []
            },
            {
                title: "fi-rr-india-map",
                searchTerms: []
            },
            {
                title: "fi-rr-indian-rupee-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-industry-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-industry-windows",
                searchTerms: []
            },
            {
                title: "fi-rr-infinity",
                searchTerms: []
            },
            {
                title: "fi-rr-info",
                searchTerms: []
            },
            {
                title: "fi-rr-info-guide",
                searchTerms: []
            },
            {
                title: "fi-rr-information",
                searchTerms: []
            },
            {
                title: "fi-rr-inhaler",
                searchTerms: []
            },
            {
                title: "fi-rr-input-numeric",
                searchTerms: []
            },
            {
                title: "fi-rr-input-pipe",
                searchTerms: []
            },
            {
                title: "fi-rr-input-text",
                searchTerms: []
            },
            {
                title: "fi-rr-insert",
                searchTerms: []
            },
            {
                title: "fi-rr-insert-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-insert-arrows",
                searchTerms: []
            },
            {
                title: "fi-rr-insert-button-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-insert-credit-card",
                searchTerms: []
            },
            {
                title: "fi-rr-insert-square",
                searchTerms: []
            },
            {
                title: "fi-rr-insight",
                searchTerms: []
            },
            {
                title: "fi-rr-insight-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-insight-head",
                searchTerms: []
            },
            {
                title: "fi-rr-integral",
                searchTerms: []
            },
            {
                title: "fi-rr-interactive",
                searchTerms: []
            },
            {
                title: "fi-rr-interlining",
                searchTerms: []
            },
            {
                title: "fi-rr-internet-speed-wifi",
                searchTerms: []
            },
            {
                title: "fi-rr-interpersonal-skill",
                searchTerms: []
            },
            {
                title: "fi-rr-interrogation",
                searchTerms: []
            },
            {
                title: "fi-rr-intersection",
                searchTerms: []
            },
            {
                title: "fi-rr-introduction",
                searchTerms: []
            },
            {
                title: "fi-rr-introduction-handshake",
                searchTerms: []
            },
            {
                title: "fi-rr-inventory-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-invest",
                searchTerms: []
            },
            {
                title: "fi-rr-investment",
                searchTerms: []
            },
            {
                title: "fi-rr-invite",
                searchTerms: []
            },
            {
                title: "fi-rr-invite-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-iot",
                searchTerms: []
            },
            {
                title: "fi-rr-iot-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-ip-address",
                searchTerms: []
            },
            {
                title: "fi-rr-iron",
                searchTerms: []
            },
            {
                title: "fi-rr-island-tropical",
                searchTerms: []
            },
            {
                title: "fi-rr-issue-loupe",
                searchTerms: []
            },
            {
                title: "fi-rr-it",
                searchTerms: []
            },
            {
                title: "fi-rr-it-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-it-computer",
                searchTerms: []
            },
            {
                title: "fi-rr-italian",
                searchTerms: []
            },
            {
                title: "fi-rr-italian-coffee-maker",
                searchTerms: []
            },
            {
                title: "fi-rr-italian-lira-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-italic",
                searchTerms: []
            },
            {
                title: "fi-rr-j",
                searchTerms: []
            },
            {
                title: "fi-rr-jam",
                searchTerms: []
            },
            {
                title: "fi-rr-japanese",
                searchTerms: []
            },
            {
                title: "fi-rr-jar-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-jar-wheat",
                searchTerms: []
            },
            {
                title: "fi-rr-javascript",
                searchTerms: []
            },
            {
                title: "fi-rr-joint",
                searchTerms: []
            },
            {
                title: "fi-rr-joker",
                searchTerms: []
            },
            {
                title: "fi-rr-journal",
                searchTerms: []
            },
            {
                title: "fi-rr-journal-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-journey",
                searchTerms: []
            },
            {
                title: "fi-rr-joystick",
                searchTerms: []
            },
            {
                title: "fi-rr-jpg",
                searchTerms: []
            },
            {
                title: "fi-rr-jug",
                searchTerms: []
            },
            {
                title: "fi-rr-jug-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-jug-bottle",
                searchTerms: []
            },
            {
                title: "fi-rr-jumping-rope",
                searchTerms: []
            },
            {
                title: "fi-rr-k",
                searchTerms: []
            },
            {
                title: "fi-rr-kaaba",
                searchTerms: []
            },
            {
                title: "fi-rr-kangaroo",
                searchTerms: []
            },
            {
                title: "fi-rr-kayak",
                searchTerms: []
            },
            {
                title: "fi-rr-kazoo",
                searchTerms: []
            },
            {
                title: "fi-rr-kerning",
                searchTerms: []
            },
            {
                title: "fi-rr-key",
                searchTerms: []
            },
            {
                title: "fi-rr-key-car",
                searchTerms: []
            },
            {
                title: "fi-rr-key-hole",
                searchTerms: []
            },
            {
                title: "fi-rr-key-lock-crypto",
                searchTerms: []
            },
            {
                title: "fi-rr-key-skeleton-left-right",
                searchTerms: []
            },
            {
                title: "fi-rr-keyboard",
                searchTerms: []
            },
            {
                title: "fi-rr-keyboard-brightness",
                searchTerms: []
            },
            {
                title: "fi-rr-keyboard-brightness-low",
                searchTerms: []
            },
            {
                title: "fi-rr-keyboard-down",
                searchTerms: []
            },
            {
                title: "fi-rr-keyboard-left",
                searchTerms: []
            },
            {
                title: "fi-rr-keynote",
                searchTerms: []
            },
            {
                title: "fi-rr-kidneys",
                searchTerms: []
            },
            {
                title: "fi-rr-kip-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-kiss",
                searchTerms: []
            },
            {
                title: "fi-rr-kiss-beam",
                searchTerms: []
            },
            {
                title: "fi-rr-kiss-wink-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-kitchen-set",
                searchTerms: []
            },
            {
                title: "fi-rr-kite",
                searchTerms: []
            },
            {
                title: "fi-rr-kiwi-bird",
                searchTerms: []
            },
            {
                title: "fi-rr-kiwi-fruit",
                searchTerms: []
            },
            {
                title: "fi-rr-knife",
                searchTerms: []
            },
            {
                title: "fi-rr-knife-kitchen",
                searchTerms: []
            },
            {
                title: "fi-rr-knitting",
                searchTerms: []
            },
            {
                title: "fi-rr-knot-rope",
                searchTerms: []
            },
            {
                title: "fi-rr-korean",
                searchTerms: []
            },
            {
                title: "fi-rr-kpi",
                searchTerms: []
            },
            {
                title: "fi-rr-kpi-evaluation",
                searchTerms: []
            },
            {
                title: "fi-rr-l",
                searchTerms: []
            },
            {
                title: "fi-rr-lab-coat",
                searchTerms: []
            },
            {
                title: "fi-rr-label",
                searchTerms: []
            },
            {
                title: "fi-rr-lacrosse-stick",
                searchTerms: []
            },
            {
                title: "fi-rr-lacrosse-stick-ball",
                searchTerms: []
            },
            {
                title: "fi-rr-lambda",
                searchTerms: []
            },
            {
                title: "fi-rr-lamp",
                searchTerms: []
            },
            {
                title: "fi-rr-lamp-desk",
                searchTerms: []
            },
            {
                title: "fi-rr-lamp-floor",
                searchTerms: []
            },
            {
                title: "fi-rr-lamp-street",
                searchTerms: []
            },
            {
                title: "fi-rr-land-layer-location",
                searchTerms: []
            },
            {
                title: "fi-rr-land-layers",
                searchTerms: []
            },
            {
                title: "fi-rr-land-location",
                searchTerms: []
            },
            {
                title: "fi-rr-land-mine-on",
                searchTerms: []
            },
            {
                title: "fi-rr-landmark-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-language",
                searchTerms: []
            },
            {
                title: "fi-rr-language-exchange",
                searchTerms: []
            },
            {
                title: "fi-rr-laptop",
                searchTerms: []
            },
            {
                title: "fi-rr-laptop-arrow-down",
                searchTerms: []
            },
            {
                title: "fi-rr-laptop-binary",
                searchTerms: []
            },
            {
                title: "fi-rr-laptop-code",
                searchTerms: []
            },
            {
                title: "fi-rr-laptop-medical",
                searchTerms: []
            },
            {
                title: "fi-rr-laptop-mobile",
                searchTerms: []
            },
            {
                title: "fi-rr-laptop-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-lari-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-lasso",
                searchTerms: []
            },
            {
                title: "fi-rr-lasso-sparkles",
                searchTerms: []
            },
            {
                title: "fi-rr-last-square",
                searchTerms: []
            },
            {
                title: "fi-rr-laugh",
                searchTerms: []
            },
            {
                title: "fi-rr-laugh-beam",
                searchTerms: []
            },
            {
                title: "fi-rr-laugh-squint",
                searchTerms: []
            },
            {
                title: "fi-rr-laugh-wink",
                searchTerms: []
            },
            {
                title: "fi-rr-laurel-user",
                searchTerms: []
            },
            {
                title: "fi-rr-lawyer-man",
                searchTerms: []
            },
            {
                title: "fi-rr-lawyer-woman",
                searchTerms: []
            },
            {
                title: "fi-rr-layer-minus",
                searchTerms: []
            },
            {
                title: "fi-rr-layer-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-layers",
                searchTerms: []
            },
            {
                title: "fi-rr-layout-fluid",
                searchTerms: []
            },
            {
                title: "fi-rr-lead",
                searchTerms: []
            },
            {
                title: "fi-rr-lead-funnel",
                searchTerms: []
            },
            {
                title: "fi-rr-lead-management",
                searchTerms: []
            },
            {
                title: "fi-rr-leader",
                searchTerms: []
            },
            {
                title: "fi-rr-leader-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-leader-speech",
                searchTerms: []
            },
            {
                title: "fi-rr-leaderboard",
                searchTerms: []
            },
            {
                title: "fi-rr-leaderboard-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-leaderboard-trophy",
                searchTerms: []
            },
            {
                title: "fi-rr-leadership",
                searchTerms: []
            },
            {
                title: "fi-rr-leadership-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-leaf",
                searchTerms: []
            },
            {
                title: "fi-rr-leaf-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-leaf-maple",
                searchTerms: []
            },
            {
                title: "fi-rr-leaf-oak",
                searchTerms: []
            },
            {
                title: "fi-rr-leafy-green",
                searchTerms: []
            },
            {
                title: "fi-rr-leave",
                searchTerms: []
            },
            {
                title: "fi-rr-left",
                searchTerms: []
            },
            {
                title: "fi-rr-left-from-bracket",
                searchTerms: []
            },
            {
                title: "fi-rr-legal",
                searchTerms: []
            },
            {
                title: "fi-rr-legal-case",
                searchTerms: []
            },
            {
                title: "fi-rr-lemon",
                searchTerms: []
            },
            {
                title: "fi-rr-lesbian-couple",
                searchTerms: []
            },
            {
                title: "fi-rr-less-than",
                searchTerms: []
            },
            {
                title: "fi-rr-less-than-equal",
                searchTerms: []
            },
            {
                title: "fi-rr-lesson",
                searchTerms: []
            },
            {
                title: "fi-rr-lesson-class",
                searchTerms: []
            },
            {
                title: "fi-rr-letter-case",
                searchTerms: []
            },
            {
                title: "fi-rr-lettuce",
                searchTerms: []
            },
            {
                title: "fi-rr-level-down",
                searchTerms: []
            },
            {
                title: "fi-rr-level-down-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-level-up",
                searchTerms: []
            },
            {
                title: "fi-rr-level-up-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-license",
                searchTerms: []
            },
            {
                title: "fi-rr-life",
                searchTerms: []
            },
            {
                title: "fi-rr-life-ring",
                searchTerms: []
            },
            {
                title: "fi-rr-light-ceiling",
                searchTerms: []
            },
            {
                title: "fi-rr-light-emergency",
                searchTerms: []
            },
            {
                title: "fi-rr-light-emergency-on",
                searchTerms: []
            },
            {
                title: "fi-rr-light-switch",
                searchTerms: []
            },
            {
                title: "fi-rr-light-switch-off",
                searchTerms: []
            },
            {
                title: "fi-rr-light-switch-on",
                searchTerms: []
            },
            {
                title: "fi-rr-lightbulb-cfl",
                searchTerms: []
            },
            {
                title: "fi-rr-lightbulb-cfl-on",
                searchTerms: []
            },
            {
                title: "fi-rr-lightbulb-dollar",
                searchTerms: []
            },
            {
                title: "fi-rr-lightbulb-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-lightbulb-head",
                searchTerms: []
            },
            {
                title: "fi-rr-lightbulb-on",
                searchTerms: []
            },
            {
                title: "fi-rr-lightbulb-question",
                searchTerms: []
            },
            {
                title: "fi-rr-lightbulb-setting",
                searchTerms: []
            },
            {
                title: "fi-rr-lightbulb-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-lighthouse",
                searchTerms: []
            },
            {
                title: "fi-rr-lights-holiday",
                searchTerms: []
            },
            {
                title: "fi-rr-limit-hand",
                searchTerms: []
            },
            {
                title: "fi-rr-limit-speedometer",
                searchTerms: []
            },
            {
                title: "fi-rr-line-width",
                searchTerms: []
            },
            {
                title: "fi-rr-link",
                searchTerms: []
            },
            {
                title: "fi-rr-link-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-link-horizontal",
                searchTerms: []
            },
            {
                title: "fi-rr-link-horizontal-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-link-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-link-slash-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-lion",
                searchTerms: []
            },
            {
                title: "fi-rr-lion-head",
                searchTerms: []
            },
            {
                title: "fi-rr-lips",
                searchTerms: []
            },
            {
                title: "fi-rr-lips-silence",
                searchTerms: []
            },
            {
                title: "fi-rr-lipstick",
                searchTerms: []
            },
            {
                title: "fi-rr-lira-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-list",
                searchTerms: []
            },
            {
                title: "fi-rr-list-check",
                searchTerms: []
            },
            {
                title: "fi-rr-list-dropdown",
                searchTerms: []
            },
            {
                title: "fi-rr-list-music",
                searchTerms: []
            },
            {
                title: "fi-rr-list-timeline",
                searchTerms: []
            },
            {
                title: "fi-rr-litecoin-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-live",
                searchTerms: []
            },
            {
                title: "fi-rr-live-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-loading",
                searchTerms: []
            },
            {
                title: "fi-rr-loan",
                searchTerms: []
            },
            {
                title: "fi-rr-lobster",
                searchTerms: []
            },
            {
                title: "fi-rr-location-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-location-arrow",
                searchTerms: []
            },
            {
                title: "fi-rr-location-crosshairs",
                searchTerms: []
            },
            {
                title: "fi-rr-location-crosshairs-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-location-dot-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-location-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-location-pin-call",
                searchTerms: []
            },
            {
                title: "fi-rr-lock",
                searchTerms: []
            },
            {
                title: "fi-rr-lock-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-lock-hashtag",
                searchTerms: []
            },
            {
                title: "fi-rr-lock-open-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-locust",
                searchTerms: []
            },
            {
                title: "fi-rr-loop-square",
                searchTerms: []
            },
            {
                title: "fi-rr-loveseat",
                searchTerms: []
            },
            {
                title: "fi-rr-low-vision",
                searchTerms: []
            },
            {
                title: "fi-rr-luchador",
                searchTerms: []
            },
            {
                title: "fi-rr-luggage-cart",
                searchTerms: []
            },
            {
                title: "fi-rr-luggage-rolling",
                searchTerms: []
            },
            {
                title: "fi-rr-lungs",
                searchTerms: []
            },
            {
                title: "fi-rr-lungs-virus",
                searchTerms: []
            },
            {
                title: "fi-rr-m",
                searchTerms: []
            },
            {
                title: "fi-rr-mace",
                searchTerms: []
            },
            {
                title: "fi-rr-magic-wand",
                searchTerms: []
            },
            {
                title: "fi-rr-magnet",
                searchTerms: []
            },
            {
                title: "fi-rr-magnet-user",
                searchTerms: []
            },
            {
                title: "fi-rr-magnifying-glass-binary",
                searchTerms: []
            },
            {
                title: "fi-rr-magnifying-glass-eye",
                searchTerms: []
            },
            {
                title: "fi-rr-magnifying-glass-wave",
                searchTerms: []
            },
            {
                title: "fi-rr-mail-plus-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-mailbox",
                searchTerms: []
            },
            {
                title: "fi-rr-mailbox-envelope",
                searchTerms: []
            },
            {
                title: "fi-rr-mailbox-flag-up",
                searchTerms: []
            },
            {
                title: "fi-rr-makeup-brush",
                searchTerms: []
            },
            {
                title: "fi-rr-man-head",
                searchTerms: []
            },
            {
                title: "fi-rr-man-scientist",
                searchTerms: []
            },
            {
                title: "fi-rr-manat-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-mandolin",
                searchTerms: []
            },
            {
                title: "fi-rr-mango",
                searchTerms: []
            },
            {
                title: "fi-rr-manhole",
                searchTerms: []
            },
            {
                title: "fi-rr-map",
                searchTerms: []
            },
            {
                title: "fi-rr-map-location-track",
                searchTerms: []
            },
            {
                title: "fi-rr-map-marker",
                searchTerms: []
            },
            {
                title: "fi-rr-map-marker-check",
                searchTerms: []
            },
            {
                title: "fi-rr-map-marker-cross",
                searchTerms: []
            },
            {
                title: "fi-rr-map-marker-edit",
                searchTerms: []
            },
            {
                title: "fi-rr-map-marker-home",
                searchTerms: []
            },
            {
                title: "fi-rr-map-marker-minus",
                searchTerms: []
            },
            {
                title: "fi-rr-map-marker-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-map-marker-question",
                searchTerms: []
            },
            {
                title: "fi-rr-map-marker-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-map-marker-smile",
                searchTerms: []
            },
            {
                title: "fi-rr-map-pin",
                searchTerms: []
            },
            {
                title: "fi-rr-map-point",
                searchTerms: []
            },
            {
                title: "fi-rr-marker",
                searchTerms: []
            },
            {
                title: "fi-rr-marker-time",
                searchTerms: []
            },
            {
                title: "fi-rr-marketplace",
                searchTerms: []
            },
            {
                title: "fi-rr-marketplace-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-marketplace-store",
                searchTerms: []
            },
            {
                title: "fi-rr-marriage-proposal",
                searchTerms: []
            },
            {
                title: "fi-rr-mars",
                searchTerms: []
            },
            {
                title: "fi-rr-mars-double",
                searchTerms: []
            },
            {
                title: "fi-rr-mars-stroke-right",
                searchTerms: []
            },
            {
                title: "fi-rr-mars-stroke-up",
                searchTerms: []
            },
            {
                title: "fi-rr-martini-glass-citrus",
                searchTerms: []
            },
            {
                title: "fi-rr-martini-glass-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-mask",
                searchTerms: []
            },
            {
                title: "fi-rr-mask-carnival",
                searchTerms: []
            },
            {
                title: "fi-rr-mask-face",
                searchTerms: []
            },
            {
                title: "fi-rr-mask-snorkel",
                searchTerms: []
            },
            {
                title: "fi-rr-massage",
                searchTerms: []
            },
            {
                title: "fi-rr-master-plan",
                searchTerms: []
            },
            {
                title: "fi-rr-master-plan-integrate",
                searchTerms: []
            },
            {
                title: "fi-rr-match-fire",
                searchTerms: []
            },
            {
                title: "fi-rr-mattress-pillow",
                searchTerms: []
            },
            {
                title: "fi-rr-measuring-tape",
                searchTerms: []
            },
            {
                title: "fi-rr-meat",
                searchTerms: []
            },
            {
                title: "fi-rr-medal",
                searchTerms: []
            },
            {
                title: "fi-rr-medical-star",
                searchTerms: []
            },
            {
                title: "fi-rr-medicine",
                searchTerms: []
            },
            {
                title: "fi-rr-meditation",
                searchTerms: []
            },
            {
                title: "fi-rr-meeting",
                searchTerms: []
            },
            {
                title: "fi-rr-meeting-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-megaphone",
                searchTerms: []
            },
            {
                title: "fi-rr-megaphone-announcement-leader",
                searchTerms: []
            },
            {
                title: "fi-rr-megaphone-sound-waves",
                searchTerms: []
            },
            {
                title: "fi-rr-meh",
                searchTerms: []
            },
            {
                title: "fi-rr-meh-blank",
                searchTerms: []
            },
            {
                title: "fi-rr-meh-rolling-eyes",
                searchTerms: []
            },
            {
                title: "fi-rr-melon",
                searchTerms: []
            },
            {
                title: "fi-rr-melon-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-member-list",
                searchTerms: []
            },
            {
                title: "fi-rr-member-search",
                searchTerms: []
            },
            {
                title: "fi-rr-membership",
                searchTerms: []
            },
            {
                title: "fi-rr-membership-vip",
                searchTerms: []
            },
            {
                title: "fi-rr-memo",
                searchTerms: []
            },
            {
                title: "fi-rr-memo-circle-check",
                searchTerms: []
            },
            {
                title: "fi-rr-memo-pad",
                searchTerms: []
            },
            {
                title: "fi-rr-memory",
                searchTerms: []
            },
            {
                title: "fi-rr-menu-burger",
                searchTerms: []
            },
            {
                title: "fi-rr-menu-dots",
                searchTerms: []
            },
            {
                title: "fi-rr-menu-dots-vertical",
                searchTerms: []
            },
            {
                title: "fi-rr-mercury",
                searchTerms: []
            },
            {
                title: "fi-rr-message-alert",
                searchTerms: []
            },
            {
                title: "fi-rr-message-arrow-down",
                searchTerms: []
            },
            {
                title: "fi-rr-message-arrow-up",
                searchTerms: []
            },
            {
                title: "fi-rr-message-arrow-up-right",
                searchTerms: []
            },
            {
                title: "fi-rr-message-bot",
                searchTerms: []
            },
            {
                title: "fi-rr-message-code",
                searchTerms: []
            },
            {
                title: "fi-rr-message-dollar",
                searchTerms: []
            },
            {
                title: "fi-rr-message-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-message-image",
                searchTerms: []
            },
            {
                title: "fi-rr-message-question",
                searchTerms: []
            },
            {
                title: "fi-rr-message-quote",
                searchTerms: []
            },
            {
                title: "fi-rr-message-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-message-sms",
                searchTerms: []
            },
            {
                title: "fi-rr-message-star",
                searchTerms: []
            },
            {
                title: "fi-rr-message-text",
                searchTerms: []
            },
            {
                title: "fi-rr-message-xmark",
                searchTerms: []
            },
            {
                title: "fi-rr-messages",
                searchTerms: []
            },
            {
                title: "fi-rr-messages-dollar",
                searchTerms: []
            },
            {
                title: "fi-rr-messages-question",
                searchTerms: []
            },
            {
                title: "fi-rr-meteor",
                searchTerms: []
            },
            {
                title: "fi-rr-meter",
                searchTerms: []
            },
            {
                title: "fi-rr-meter-bolt",
                searchTerms: []
            },
            {
                title: "fi-rr-meter-droplet",
                searchTerms: []
            },
            {
                title: "fi-rr-meter-fire",
                searchTerms: []
            },
            {
                title: "fi-rr-method",
                searchTerms: []
            },
            {
                title: "fi-rr-microchip",
                searchTerms: []
            },
            {
                title: "fi-rr-microchip-ai",
                searchTerms: []
            },
            {
                title: "fi-rr-microphone",
                searchTerms: []
            },
            {
                title: "fi-rr-microphone-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-microphone-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-microscope",
                searchTerms: []
            },
            {
                title: "fi-rr-microwave",
                searchTerms: []
            },
            {
                title: "fi-rr-milk",
                searchTerms: []
            },
            {
                title: "fi-rr-milk-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-mill",
                searchTerms: []
            },
            {
                title: "fi-rr-mill-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-mind-share",
                searchTerms: []
            },
            {
                title: "fi-rr-minus",
                searchTerms: []
            },
            {
                title: "fi-rr-minus-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-minus-hexagon",
                searchTerms: []
            },
            {
                title: "fi-rr-minus-small",
                searchTerms: []
            },
            {
                title: "fi-rr-mirror",
                searchTerms: []
            },
            {
                title: "fi-rr-mirror-user",
                searchTerms: []
            },
            {
                title: "fi-rr-mistletoe",
                searchTerms: []
            },
            {
                title: "fi-rr-mix",
                searchTerms: []
            },
            {
                title: "fi-rr-mixer",
                searchTerms: []
            },
            {
                title: "fi-rr-mobile",
                searchTerms: []
            },
            {
                title: "fi-rr-mobile-4g",
                searchTerms: []
            },
            {
                title: "fi-rr-mobile-5g",
                searchTerms: []
            },
            {
                title: "fi-rr-mobile-button",
                searchTerms: []
            },
            {
                title: "fi-rr-mobile-hand",
                searchTerms: []
            },
            {
                title: "fi-rr-mobile-message",
                searchTerms: []
            },
            {
                title: "fi-rr-mobile-notch",
                searchTerms: []
            },
            {
                title: "fi-rr-mockup",
                searchTerms: []
            },
            {
                title: "fi-rr-mode",
                searchTerms: []
            },
            {
                title: "fi-rr-mode-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-mode-landscape",
                searchTerms: []
            },
            {
                title: "fi-rr-mode-portrait",
                searchTerms: []
            },
            {
                title: "fi-rr-model-cube",
                searchTerms: []
            },
            {
                title: "fi-rr-model-cube-arrows",
                searchTerms: []
            },
            {
                title: "fi-rr-model-cube-space",
                searchTerms: []
            },
            {
                title: "fi-rr-module",
                searchTerms: []
            },
            {
                title: "fi-rr-monday",
                searchTerms: []
            },
            {
                title: "fi-rr-money",
                searchTerms: []
            },
            {
                title: "fi-rr-money-bill-simple",
                searchTerms: []
            },
            {
                title: "fi-rr-money-bill-transfer",
                searchTerms: []
            },
            {
                title: "fi-rr-money-bill-wave",
                searchTerms: []
            },
            {
                title: "fi-rr-money-bill-wave-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-money-bills",
                searchTerms: []
            },
            {
                title: "fi-rr-money-bills-simple",
                searchTerms: []
            },
            {
                title: "fi-rr-money-check",
                searchTerms: []
            },
            {
                title: "fi-rr-money-check-edit",
                searchTerms: []
            },
            {
                title: "fi-rr-money-check-edit-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-money-coin-transfer",
                searchTerms: []
            },
            {
                title: "fi-rr-money-from-bracket",
                searchTerms: []
            },
            {
                title: "fi-rr-money-gears",
                searchTerms: []
            },
            {
                title: "fi-rr-money-income",
                searchTerms: []
            },
            {
                title: "fi-rr-money-simple-from-bracket",
                searchTerms: []
            },
            {
                title: "fi-rr-money-transfer-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-money-transfer-coin-arrow",
                searchTerms: []
            },
            {
                title: "fi-rr-money-transfer-smartphone",
                searchTerms: []
            },
            {
                title: "fi-rr-money-wings",
                searchTerms: []
            },
            {
                title: "fi-rr-monkey",
                searchTerms: []
            },
            {
                title: "fi-rr-monument",
                searchTerms: []
            },
            {
                title: "fi-rr-moon",
                searchTerms: []
            },
            {
                title: "fi-rr-moon-stars",
                searchTerms: []
            },
            {
                title: "fi-rr-moped",
                searchTerms: []
            },
            {
                title: "fi-rr-mortar-pestle",
                searchTerms: []
            },
            {
                title: "fi-rr-mortgage",
                searchTerms: []
            },
            {
                title: "fi-rr-mosque",
                searchTerms: []
            },
            {
                title: "fi-rr-mosque-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-mosque-moon",
                searchTerms: []
            },
            {
                title: "fi-rr-mosquito",
                searchTerms: []
            },
            {
                title: "fi-rr-mosquito-net",
                searchTerms: []
            },
            {
                title: "fi-rr-motorcycle",
                searchTerms: []
            },
            {
                title: "fi-rr-mound",
                searchTerms: []
            },
            {
                title: "fi-rr-mountain",
                searchTerms: []
            },
            {
                title: "fi-rr-mountain-city",
                searchTerms: []
            },
            {
                title: "fi-rr-mountains",
                searchTerms: []
            },
            {
                title: "fi-rr-mouse",
                searchTerms: []
            },
            {
                title: "fi-rr-mouse-field",
                searchTerms: []
            },
            {
                title: "fi-rr-mouse-pointer-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-mov-file",
                searchTerms: []
            },
            {
                title: "fi-rr-move-to-folder",
                searchTerms: []
            },
            {
                title: "fi-rr-move-to-folder-2",
                searchTerms: []
            },
            {
                title: "fi-rr-moving",
                searchTerms: []
            },
            {
                title: "fi-rr-mower",
                searchTerms: []
            },
            {
                title: "fi-rr-mp3-file",
                searchTerms: []
            },
            {
                title: "fi-rr-mp3-player",
                searchTerms: []
            },
            {
                title: "fi-rr-mp4-file",
                searchTerms: []
            },
            {
                title: "fi-rr-mug",
                searchTerms: []
            },
            {
                title: "fi-rr-mug-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-mug-hot",
                searchTerms: []
            },
            {
                title: "fi-rr-mug-hot-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-mug-marshmallows",
                searchTerms: []
            },
            {
                title: "fi-rr-mug-tea",
                searchTerms: []
            },
            {
                title: "fi-rr-mug-tea-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-mug-tea-saucer",
                searchTerms: []
            },
            {
                title: "fi-rr-multiple",
                searchTerms: []
            },
            {
                title: "fi-rr-multiple-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-multitasking",
                searchTerms: []
            },
            {
                title: "fi-rr-muscle",
                searchTerms: []
            },
            {
                title: "fi-rr-mushroom",
                searchTerms: []
            },
            {
                title: "fi-rr-mushroom-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-mushroom-cloud",
                searchTerms: []
            },
            {
                title: "fi-rr-music",
                searchTerms: []
            },
            {
                title: "fi-rr-music-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-music-file",
                searchTerms: []
            },
            {
                title: "fi-rr-music-magnifying-glass",
                searchTerms: []
            },
            {
                title: "fi-rr-music-note",
                searchTerms: []
            },
            {
                title: "fi-rr-music-note-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-music-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-n",
                searchTerms: []
            },
            {
                title: "fi-rr-naira-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-narwhal",
                searchTerms: []
            },
            {
                title: "fi-rr-navigation",
                searchTerms: []
            },
            {
                title: "fi-rr-nesting-dolls",
                searchTerms: []
            },
            {
                title: "fi-rr-network",
                searchTerms: []
            },
            {
                title: "fi-rr-network-analytic",
                searchTerms: []
            },
            {
                title: "fi-rr-network-cloud",
                searchTerms: []
            },
            {
                title: "fi-rr-network-cloud-computer",
                searchTerms: []
            },
            {
                title: "fi-rr-network-user",
                searchTerms: []
            },
            {
                title: "fi-rr-neuter",
                searchTerms: []
            },
            {
                title: "fi-rr-newsletter-subscribe",
                searchTerms: []
            },
            {
                title: "fi-rr-newspaper",
                searchTerms: []
            },
            {
                title: "fi-rr-newspaper-open",
                searchTerms: []
            },
            {
                title: "fi-rr-nfc",
                searchTerms: []
            },
            {
                title: "fi-rr-nfc-lock",
                searchTerms: []
            },
            {
                title: "fi-rr-nfc-magnifying-glass",
                searchTerms: []
            },
            {
                title: "fi-rr-nfc-pen",
                searchTerms: []
            },
            {
                title: "fi-rr-nfc-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-nfc-trash",
                searchTerms: []
            },
            {
                title: "fi-rr-nft-square",
                searchTerms: []
            },
            {
                title: "fi-rr-night-day",
                searchTerms: []
            },
            {
                title: "fi-rr-no-attention",
                searchTerms: []
            },
            {
                title: "fi-rr-no-fee",
                searchTerms: []
            },
            {
                title: "fi-rr-no-food",
                searchTerms: []
            },
            {
                title: "fi-rr-no-iron",
                searchTerms: []
            },
            {
                title: "fi-rr-no-people",
                searchTerms: []
            },
            {
                title: "fi-rr-no-smoking",
                searchTerms: []
            },
            {
                title: "fi-rr-noise-cancelling-headphones",
                searchTerms: []
            },
            {
                title: "fi-rr-noodles",
                searchTerms: []
            },
            {
                title: "fi-rr-nose",
                searchTerms: []
            },
            {
                title: "fi-rr-not-equal",
                searchTerms: []
            },
            {
                title: "fi-rr-not-found",
                searchTerms: []
            },
            {
                title: "fi-rr-not-found-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-not-found-magnifying-glass",
                searchTerms: []
            },
            {
                title: "fi-rr-notdef",
                searchTerms: []
            },
            {
                title: "fi-rr-note",
                searchTerms: []
            },
            {
                title: "fi-rr-note-medical",
                searchTerms: []
            },
            {
                title: "fi-rr-note-sticky",
                searchTerms: []
            },
            {
                title: "fi-rr-notebook",
                searchTerms: []
            },
            {
                title: "fi-rr-notebook-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-notes",
                searchTerms: []
            },
            {
                title: "fi-rr-notes-medical",
                searchTerms: []
            },
            {
                title: "fi-rr-o",
                searchTerms: []
            },
            {
                title: "fi-rr-object-exclude",
                searchTerms: []
            },
            {
                title: "fi-rr-object-group",
                searchTerms: []
            },
            {
                title: "fi-rr-object-intersect",
                searchTerms: []
            },
            {
                title: "fi-rr-object-subtract",
                searchTerms: []
            },
            {
                title: "fi-rr-object-ungroup",
                searchTerms: []
            },
            {
                title: "fi-rr-object-union",
                searchTerms: []
            },
            {
                title: "fi-rr-objects-column",
                searchTerms: []
            },
            {
                title: "fi-rr-octagon",
                searchTerms: []
            },
            {
                title: "fi-rr-octagon-check",
                searchTerms: []
            },
            {
                title: "fi-rr-octagon-divide",
                searchTerms: []
            },
            {
                title: "fi-rr-octagon-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-octagon-minus",
                searchTerms: []
            },
            {
                title: "fi-rr-octagon-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-octagon-xmark",
                searchTerms: []
            },
            {
                title: "fi-rr-office-chair",
                searchTerms: []
            },
            {
                title: "fi-rr-oil-can",
                searchTerms: []
            },
            {
                title: "fi-rr-oil-temp",
                searchTerms: []
            },
            {
                title: "fi-rr-old-people",
                searchTerms: []
            },
            {
                title: "fi-rr-olive",
                searchTerms: []
            },
            {
                title: "fi-rr-olive-branch-dove",
                searchTerms: []
            },
            {
                title: "fi-rr-olive-oil",
                searchTerms: []
            },
            {
                title: "fi-rr-olives",
                searchTerms: []
            },
            {
                title: "fi-rr-om",
                searchTerms: []
            },
            {
                title: "fi-rr-omega",
                searchTerms: []
            },
            {
                title: "fi-rr-on-air-square",
                searchTerms: []
            },
            {
                title: "fi-rr-onboarding",
                searchTerms: []
            },
            {
                title: "fi-rr-onion",
                searchTerms: []
            },
            {
                title: "fi-rr-opacity",
                searchTerms: []
            },
            {
                title: "fi-rr-open-mail-clip",
                searchTerms: []
            },
            {
                title: "fi-rr-operating-system-upgrade",
                searchTerms: []
            },
            {
                title: "fi-rr-operation",
                searchTerms: []
            },
            {
                title: "fi-rr-order-history",
                searchTerms: []
            },
            {
                title: "fi-rr-organization-chart",
                searchTerms: []
            },
            {
                title: "fi-rr-ornament",
                searchTerms: []
            },
            {
                title: "fi-rr-otp",
                searchTerms: []
            },
            {
                title: "fi-rr-otter",
                searchTerms: []
            },
            {
                title: "fi-rr-outdent",
                searchTerms: []
            },
            {
                title: "fi-rr-oval",
                searchTerms: []
            },
            {
                title: "fi-rr-oval-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-oven",
                searchTerms: []
            },
            {
                title: "fi-rr-overline",
                searchTerms: []
            },
            {
                title: "fi-rr-overview",
                searchTerms: []
            },
            {
                title: "fi-rr-p",
                searchTerms: []
            },
            {
                title: "fi-rr-package",
                searchTerms: []
            },
            {
                title: "fi-rr-padlock-check",
                searchTerms: []
            },
            {
                title: "fi-rr-page-break",
                searchTerms: []
            },
            {
                title: "fi-rr-pager",
                searchTerms: []
            },
            {
                title: "fi-rr-paid",
                searchTerms: []
            },
            {
                title: "fi-rr-paint-brush",
                searchTerms: []
            },
            {
                title: "fi-rr-paint-roller",
                searchTerms: []
            },
            {
                title: "fi-rr-paintbrush-pencil",
                searchTerms: []
            },
            {
                title: "fi-rr-palette",
                searchTerms: []
            },
            {
                title: "fi-rr-pallet",
                searchTerms: []
            },
            {
                title: "fi-rr-pallet-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-pan",
                searchTerms: []
            },
            {
                title: "fi-rr-pan-food",
                searchTerms: []
            },
            {
                title: "fi-rr-pan-frying",
                searchTerms: []
            },
            {
                title: "fi-rr-pancakes",
                searchTerms: []
            },
            {
                title: "fi-rr-panorama",
                searchTerms: []
            },
            {
                title: "fi-rr-paper-plane",
                searchTerms: []
            },
            {
                title: "fi-rr-paper-plane-launch",
                searchTerms: []
            },
            {
                title: "fi-rr-paper-plane-top",
                searchTerms: []
            },
            {
                title: "fi-rr-paperclip-vertical",
                searchTerms: []
            },
            {
                title: "fi-rr-parachute-box",
                searchTerms: []
            },
            {
                title: "fi-rr-paragraph",
                searchTerms: []
            },
            {
                title: "fi-rr-paragraph-left",
                searchTerms: []
            },
            {
                title: "fi-rr-parking",
                searchTerms: []
            },
            {
                title: "fi-rr-parking-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-parking-circle-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-parking-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-party-bell",
                searchTerms: []
            },
            {
                title: "fi-rr-party-horn",
                searchTerms: []
            },
            {
                title: "fi-rr-passenger-plane",
                searchTerms: []
            },
            {
                title: "fi-rr-passport",
                searchTerms: []
            },
            {
                title: "fi-rr-password",
                searchTerms: []
            },
            {
                title: "fi-rr-password-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-password-computer",
                searchTerms: []
            },
            {
                title: "fi-rr-password-email",
                searchTerms: []
            },
            {
                title: "fi-rr-password-lock",
                searchTerms: []
            },
            {
                title: "fi-rr-password-smartphone",
                searchTerms: []
            },
            {
                title: "fi-rr-paste",
                searchTerms: []
            },
            {
                title: "fi-rr-pattern",
                searchTerms: []
            },
            {
                title: "fi-rr-pause",
                searchTerms: []
            },
            {
                title: "fi-rr-pause-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-pause-square",
                searchTerms: []
            },
            {
                title: "fi-rr-paw",
                searchTerms: []
            },
            {
                title: "fi-rr-paw-claws",
                searchTerms: []
            },
            {
                title: "fi-rr-paw-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-payment-pos",
                searchTerms: []
            },
            {
                title: "fi-rr-payroll",
                searchTerms: []
            },
            {
                title: "fi-rr-payroll-calendar",
                searchTerms: []
            },
            {
                title: "fi-rr-payroll-check",
                searchTerms: []
            },
            {
                title: "fi-rr-peace",
                searchTerms: []
            },
            {
                title: "fi-rr-peach",
                searchTerms: []
            },
            {
                title: "fi-rr-peanut",
                searchTerms: []
            },
            {
                title: "fi-rr-peanuts",
                searchTerms: []
            },
            {
                title: "fi-rr-peapod",
                searchTerms: []
            },
            {
                title: "fi-rr-pear",
                searchTerms: []
            },
            {
                title: "fi-rr-pedestal",
                searchTerms: []
            },
            {
                title: "fi-rr-pen-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-pen-clip",
                searchTerms: []
            },
            {
                title: "fi-rr-pen-clip-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-pen-fancy",
                searchTerms: []
            },
            {
                title: "fi-rr-pen-fancy-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-pen-field",
                searchTerms: []
            },
            {
                title: "fi-rr-pen-nib",
                searchTerms: []
            },
            {
                title: "fi-rr-pen-nib-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-pen-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-pen-square",
                searchTerms: []
            },
            {
                title: "fi-rr-pen-swirl",
                searchTerms: []
            },
            {
                title: "fi-rr-pencil",
                searchTerms: []
            },
            {
                title: "fi-rr-pencil-paintbrush",
                searchTerms: []
            },
            {
                title: "fi-rr-pencil-ruler",
                searchTerms: []
            },
            {
                title: "fi-rr-pencil-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-pending",
                searchTerms: []
            },
            {
                title: "fi-rr-pennant",
                searchTerms: []
            },
            {
                title: "fi-rr-people",
                searchTerms: []
            },
            {
                title: "fi-rr-people-arrows-left-right",
                searchTerms: []
            },
            {
                title: "fi-rr-people-carry-box",
                searchTerms: []
            },
            {
                title: "fi-rr-people-dress",
                searchTerms: []
            },
            {
                title: "fi-rr-people-network-partner",
                searchTerms: []
            },
            {
                title: "fi-rr-people-pants",
                searchTerms: []
            },
            {
                title: "fi-rr-people-poll",
                searchTerms: []
            },
            {
                title: "fi-rr-people-pulling",
                searchTerms: []
            },
            {
                title: "fi-rr-people-roof",
                searchTerms: []
            },
            {
                title: "fi-rr-pepper",
                searchTerms: []
            },
            {
                title: "fi-rr-pepper-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-pepper-hot",
                searchTerms: []
            },
            {
                title: "fi-rr-percent-10",
                searchTerms: []
            },
            {
                title: "fi-rr-percent-100",
                searchTerms: []
            },
            {
                title: "fi-rr-percent-20",
                searchTerms: []
            },
            {
                title: "fi-rr-percent-25",
                searchTerms: []
            },
            {
                title: "fi-rr-percent-30",
                searchTerms: []
            },
            {
                title: "fi-rr-percent-40",
                searchTerms: []
            },
            {
                title: "fi-rr-percent-50",
                searchTerms: []
            },
            {
                title: "fi-rr-percent-60",
                searchTerms: []
            },
            {
                title: "fi-rr-percent-70",
                searchTerms: []
            },
            {
                title: "fi-rr-percent-75",
                searchTerms: []
            },
            {
                title: "fi-rr-percent-80",
                searchTerms: []
            },
            {
                title: "fi-rr-percent-90",
                searchTerms: []
            },
            {
                title: "fi-rr-percentage",
                searchTerms: []
            },
            {
                title: "fi-rr-person-battery",
                searchTerms: []
            },
            {
                title: "fi-rr-person-burst",
                searchTerms: []
            },
            {
                title: "fi-rr-person-carry-box",
                searchTerms: []
            },
            {
                title: "fi-rr-person-circle-check",
                searchTerms: []
            },
            {
                title: "fi-rr-person-circle-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-person-circle-minus",
                searchTerms: []
            },
            {
                title: "fi-rr-person-circle-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-person-circle-question",
                searchTerms: []
            },
            {
                title: "fi-rr-person-circle-xmark",
                searchTerms: []
            },
            {
                title: "fi-rr-person-cv",
                searchTerms: []
            },
            {
                title: "fi-rr-person-dolly",
                searchTerms: []
            },
            {
                title: "fi-rr-person-dolly-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-person-dragging-bag",
                searchTerms: []
            },
            {
                title: "fi-rr-person-dress",
                searchTerms: []
            },
            {
                title: "fi-rr-person-dress-simple",
                searchTerms: []
            },
            {
                title: "fi-rr-person-luggage",
                searchTerms: []
            },
            {
                title: "fi-rr-person-lunge",
                searchTerms: []
            },
            {
                title: "fi-rr-person-pilates",
                searchTerms: []
            },
            {
                title: "fi-rr-person-praying",
                searchTerms: []
            },
            {
                title: "fi-rr-person-pregnant",
                searchTerms: []
            },
            {
                title: "fi-rr-person-seat",
                searchTerms: []
            },
            {
                title: "fi-rr-person-seat-reclined",
                searchTerms: []
            },
            {
                title: "fi-rr-person-shelter",
                searchTerms: []
            },
            {
                title: "fi-rr-person-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-person-simple",
                searchTerms: []
            },
            {
                title: "fi-rr-person-stress",
                searchTerms: []
            },
            {
                title: "fi-rr-person-walking-with-cane",
                searchTerms: []
            },
            {
                title: "fi-rr-peseta-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-peso-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-pets",
                searchTerms: []
            },
            {
                title: "fi-rr-pharmacy",
                searchTerms: []
            },
            {
                title: "fi-rr-phone-call",
                searchTerms: []
            },
            {
                title: "fi-rr-phone-cross",
                searchTerms: []
            },
            {
                title: "fi-rr-phone-flip",
                searchTerms: []
            },
            {
                title: "fi-rr-phone-guide",
                searchTerms: []
            },
            {
                title: "fi-rr-phone-heart-message",
                searchTerms: []
            },
            {
                title: "fi-rr-phone-office",
                searchTerms: []
            },
            {
                title: "fi-rr-phone-pause",
                searchTerms: []
            },
            {
                title: "fi-rr-phone-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-phone-rotary",
                searchTerms: []
            },
            {
                title: "fi-rr-phone-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-photo-capture",
                searchTerms: []
            },
            {
                title: "fi-rr-photo-film-music",
                searchTerms: []
            },
            {
                title: "fi-rr-photo-video",
                searchTerms: []
            },
            {
                title: "fi-rr-physics",
                searchTerms: []
            },
            {
                title: "fi-rr-Pi",
                searchTerms: []
            },
            {
                title: "fi-rr-piano",
                searchTerms: []
            },
            {
                title: "fi-rr-piano-keyboard",
                searchTerms: []
            },
            {
                title: "fi-rr-pickaxe",
                searchTerms: []
            },
            {
                title: "fi-rr-picking",
                searchTerms: []
            },
            {
                title: "fi-rr-picking-box",
                searchTerms: []
            },
            {
                title: "fi-rr-picnic",
                searchTerms: []
            },
            {
                title: "fi-rr-picpeople",
                searchTerms: []
            },
            {
                title: "fi-rr-picpeople-filled",
                searchTerms: []
            },
            {
                title: "fi-rr-picture",
                searchTerms: []
            },
            {
                title: "fi-rr-pie",
                searchTerms: []
            },
            {
                title: "fi-rr-piece",
                searchTerms: []
            },
            {
                title: "fi-rr-pig",
                searchTerms: []
            },
            {
                title: "fi-rr-pig-bank-bulb",
                searchTerms: []
            },
            {
                title: "fi-rr-pig-face",
                searchTerms: []
            },
            {
                title: "fi-rr-piggy-bank",
                searchTerms: []
            },
            {
                title: "fi-rr-piggy-bank-budget",
                searchTerms: []
            },
            {
                title: "fi-rr-pills",
                searchTerms: []
            },
            {
                title: "fi-rr-pin-to-pin",
                searchTerms: []
            },
            {
                title: "fi-rr-piÃ±ata",
                searchTerms: []
            },
            {
                title: "fi-rr-pineapple",
                searchTerms: []
            },
            {
                title: "fi-rr-pineapple-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-ping-pong",
                searchTerms: []
            },
            {
                title: "fi-rr-pipe-smoking",
                searchTerms: []
            },
            {
                title: "fi-rr-pisa-tower",
                searchTerms: []
            },
            {
                title: "fi-rr-pizza-slice",
                searchTerms: []
            },
            {
                title: "fi-rr-place-of-worship",
                searchTerms: []
            },
            {
                title: "fi-rr-plagiarism",
                searchTerms: []
            },
            {
                title: "fi-rr-plan",
                searchTerms: []
            },
            {
                title: "fi-rr-plan-strategy",
                searchTerms: []
            },
            {
                title: "fi-rr-plane",
                searchTerms: []
            },
            {
                title: "fi-rr-plane-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-plane-arrival",
                searchTerms: []
            },
            {
                title: "fi-rr-plane-departure",
                searchTerms: []
            },
            {
                title: "fi-rr-plane-prop",
                searchTerms: []
            },
            {
                title: "fi-rr-plane-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-plane-tail",
                searchTerms: []
            },
            {
                title: "fi-rr-planet-moon",
                searchTerms: []
            },
            {
                title: "fi-rr-planet-ringed",
                searchTerms: []
            },
            {
                title: "fi-rr-plant-care",
                searchTerms: []
            },
            {
                title: "fi-rr-plant-growth",
                searchTerms: []
            },
            {
                title: "fi-rr-plant-seed-invest",
                searchTerms: []
            },
            {
                title: "fi-rr-plant-wilt",
                searchTerms: []
            },
            {
                title: "fi-rr-plate",
                searchTerms: []
            },
            {
                title: "fi-rr-plate-eating",
                searchTerms: []
            },
            {
                title: "fi-rr-plate-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-plate-utensils",
                searchTerms: []
            },
            {
                title: "fi-rr-plate-wheat",
                searchTerms: []
            },
            {
                title: "fi-rr-play",
                searchTerms: []
            },
            {
                title: "fi-rr-play-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-play-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-play-microphone",
                searchTerms: []
            },
            {
                title: "fi-rr-play-pause",
                searchTerms: []
            },
            {
                title: "fi-rr-playing-cards",
                searchTerms: []
            },
            {
                title: "fi-rr-plug",
                searchTerms: []
            },
            {
                title: "fi-rr-plug-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-plug-cable",
                searchTerms: []
            },
            {
                title: "fi-rr-plug-circle-bolt",
                searchTerms: []
            },
            {
                title: "fi-rr-plug-circle-check",
                searchTerms: []
            },
            {
                title: "fi-rr-plug-circle-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-plug-circle-minus",
                searchTerms: []
            },
            {
                title: "fi-rr-plug-circle-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-plug-connection",
                searchTerms: []
            },
            {
                title: "fi-rr-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-plus-hexagon",
                searchTerms: []
            },
            {
                title: "fi-rr-plus-minus",
                searchTerms: []
            },
            {
                title: "fi-rr-plus-small",
                searchTerms: []
            },
            {
                title: "fi-rr-png-file",
                searchTerms: []
            },
            {
                title: "fi-rr-podcast",
                searchTerms: []
            },
            {
                title: "fi-rr-podium",
                searchTerms: []
            },
            {
                title: "fi-rr-podium-star",
                searchTerms: []
            },
            {
                title: "fi-rr-podium-victory-leader",
                searchTerms: []
            },
            {
                title: "fi-rr-point-of-sale",
                searchTerms: []
            },
            {
                title: "fi-rr-point-of-sale-bill",
                searchTerms: []
            },
            {
                title: "fi-rr-point-of-sale-signal",
                searchTerms: []
            },
            {
                title: "fi-rr-pointer-loading",
                searchTerms: []
            },
            {
                title: "fi-rr-pointer-text",
                searchTerms: []
            },
            {
                title: "fi-rr-poker-chip",
                searchTerms: []
            },
            {
                title: "fi-rr-police-box",
                searchTerms: []
            },
            {
                title: "fi-rr-polish-bottle",
                searchTerms: []
            },
            {
                title: "fi-rr-polish-brush",
                searchTerms: []
            },
            {
                title: "fi-rr-poll-h",
                searchTerms: []
            },
            {
                title: "fi-rr-pollution",
                searchTerms: []
            },
            {
                title: "fi-rr-pompebled",
                searchTerms: []
            },
            {
                title: "fi-rr-poo",
                searchTerms: []
            },
            {
                title: "fi-rr-poo-bolt",
                searchTerms: []
            },
            {
                title: "fi-rr-pool-8-ball",
                searchTerms: []
            },
            {
                title: "fi-rr-poop",
                searchTerms: []
            },
            {
                title: "fi-rr-popcorn",
                searchTerms: []
            },
            {
                title: "fi-rr-popsicle",
                searchTerms: []
            },
            {
                title: "fi-rr-population",
                searchTerms: []
            },
            {
                title: "fi-rr-population-globe",
                searchTerms: []
            },
            {
                title: "fi-rr-portal-enter",
                searchTerms: []
            },
            {
                title: "fi-rr-portal-exit",
                searchTerms: []
            },
            {
                title: "fi-rr-portrait",
                searchTerms: []
            },
            {
                title: "fi-rr-portuguese",
                searchTerms: []
            },
            {
                title: "fi-rr-postal-address",
                searchTerms: []
            },
            {
                title: "fi-rr-pot",
                searchTerms: []
            },
            {
                title: "fi-rr-potato",
                searchTerms: []
            },
            {
                title: "fi-rr-pound",
                searchTerms: []
            },
            {
                title: "fi-rr-power",
                searchTerms: []
            },
            {
                title: "fi-rr-ppt-file",
                searchTerms: []
            },
            {
                title: "fi-rr-practice",
                searchTerms: []
            },
            {
                title: "fi-rr-praying-hands",
                searchTerms: []
            },
            {
                title: "fi-rr-prescription",
                searchTerms: []
            },
            {
                title: "fi-rr-prescription-bottle",
                searchTerms: []
            },
            {
                title: "fi-rr-prescription-bottle-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-prescription-bottle-pill",
                searchTerms: []
            },
            {
                title: "fi-rr-presentation",
                searchTerms: []
            },
            {
                title: "fi-rr-preview",
                searchTerms: []
            },
            {
                title: "fi-rr-previous-square",
                searchTerms: []
            },
            {
                title: "fi-rr-print",
                searchTerms: []
            },
            {
                title: "fi-rr-print-magnifying-glass",
                searchTerms: []
            },
            {
                title: "fi-rr-print-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-priority-arrow",
                searchTerms: []
            },
            {
                title: "fi-rr-priority-arrows",
                searchTerms: []
            },
            {
                title: "fi-rr-priority-importance",
                searchTerms: []
            },
            {
                title: "fi-rr-problem-solving",
                searchTerms: []
            },
            {
                title: "fi-rr-procedures",
                searchTerms: []
            },
            {
                title: "fi-rr-process",
                searchTerms: []
            },
            {
                title: "fi-rr-productivity",
                searchTerms: []
            },
            {
                title: "fi-rr-progress-bar-dotted-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-progress-bar-dotted-half",
                searchTerms: []
            },
            {
                title: "fi-rr-progress-bar-dotted-line-half",
                searchTerms: []
            },
            {
                title: "fi-rr-progress-bar-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-progress-bar-half",
                searchTerms: []
            },
            {
                title: "fi-rr-progress-bar-square-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-progress-bar-square-half",
                searchTerms: []
            },
            {
                title: "fi-rr-progress-complete",
                searchTerms: []
            },
            {
                title: "fi-rr-progress-download",
                searchTerms: []
            },
            {
                title: "fi-rr-progress-upload",
                searchTerms: []
            },
            {
                title: "fi-rr-projector",
                searchTerms: []
            },
            {
                title: "fi-rr-protractor",
                searchTerms: []
            },
            {
                title: "fi-rr-pulse",
                searchTerms: []
            },
            {
                title: "fi-rr-pump",
                searchTerms: []
            },
            {
                title: "fi-rr-pump-medical",
                searchTerms: []
            },
            {
                title: "fi-rr-pumpkin",
                searchTerms: []
            },
            {
                title: "fi-rr-pumpkin-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-pumpkin-alt-2",
                searchTerms: []
            },
            {
                title: "fi-rr-puzzle",
                searchTerms: []
            },
            {
                title: "fi-rr-puzzle-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-puzzle-piece-integration",
                searchTerms: []
            },
            {
                title: "fi-rr-puzzle-pieces",
                searchTerms: []
            },
            {
                title: "fi-rr-pyramid",
                searchTerms: []
            },
            {
                title: "fi-rr-q",
                searchTerms: []
            },
            {
                title: "fi-rr-QR",
                searchTerms: []
            },
            {
                title: "fi-rr-qr-scan",
                searchTerms: []
            },
            {
                title: "fi-rr-qrcode",
                searchTerms: []
            },
            {
                title: "fi-rr-question",
                searchTerms: []
            },
            {
                title: "fi-rr-question-square",
                searchTerms: []
            },
            {
                title: "fi-rr-queue",
                searchTerms: []
            },
            {
                title: "fi-rr-queue-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-queue-line",
                searchTerms: []
            },
            {
                title: "fi-rr-queue-signal",
                searchTerms: []
            },
            {
                title: "fi-rr-quill-pen-story",
                searchTerms: []
            },
            {
                title: "fi-rr-quiz",
                searchTerms: []
            },
            {
                title: "fi-rr-quiz-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-quote-right",
                searchTerms: []
            },
            {
                title: "fi-rr-r",
                searchTerms: []
            },
            {
                title: "fi-rr-rabbit",
                searchTerms: []
            },
            {
                title: "fi-rr-rabbit-fast",
                searchTerms: []
            },
            {
                title: "fi-rr-raccoon",
                searchTerms: []
            },
            {
                title: "fi-rr-racquet",
                searchTerms: []
            },
            {
                title: "fi-rr-radar",
                searchTerms: []
            },
            {
                title: "fi-rr-radar-monitoring-track",
                searchTerms: []
            },
            {
                title: "fi-rr-radiation",
                searchTerms: []
            },
            {
                title: "fi-rr-radiation-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-radio",
                searchTerms: []
            },
            {
                title: "fi-rr-radio-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-radio-button",
                searchTerms: []
            },
            {
                title: "fi-rr-radio-tower",
                searchTerms: []
            },
            {
                title: "fi-rr-radio-waves",
                searchTerms: []
            },
            {
                title: "fi-rr-radish",
                searchTerms: []
            },
            {
                title: "fi-rr-rainbow",
                searchTerms: []
            },
            {
                title: "fi-rr-raindrops",
                searchTerms: []
            },
            {
                title: "fi-rr-ram",
                searchTerms: []
            },
            {
                title: "fi-rr-ramp-loading",
                searchTerms: []
            },
            {
                title: "fi-rr-rank",
                searchTerms: []
            },
            {
                title: "fi-rr-ranking-podium",
                searchTerms: []
            },
            {
                title: "fi-rr-ranking-podium-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-ranking-star",
                searchTerms: []
            },
            {
                title: "fi-rr-ranking-stars",
                searchTerms: []
            },
            {
                title: "fi-rr-raygun",
                searchTerms: []
            },
            {
                title: "fi-rr-razor-barber",
                searchTerms: []
            },
            {
                title: "fi-rr-react",
                searchTerms: []
            },
            {
                title: "fi-rr-rec",
                searchTerms: []
            },
            {
                title: "fi-rr-receipt",
                searchTerms: []
            },
            {
                title: "fi-rr-recipe",
                searchTerms: []
            },
            {
                title: "fi-rr-recipe-book",
                searchTerms: []
            },
            {
                title: "fi-rr-record-vinyl",
                searchTerms: []
            },
            {
                title: "fi-rr-rectabgle-vertical",
                searchTerms: []
            },
            {
                title: "fi-rr-rectangle-barcode",
                searchTerms: []
            },
            {
                title: "fi-rr-rectangle-code",
                searchTerms: []
            },
            {
                title: "fi-rr-rectangle-history-circle-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-rectangle-horizontal",
                searchTerms: []
            },
            {
                title: "fi-rr-rectangle-list",
                searchTerms: []
            },
            {
                title: "fi-rr-rectangle-panoramic",
                searchTerms: []
            },
            {
                title: "fi-rr-rectangle-pro",
                searchTerms: []
            },
            {
                title: "fi-rr-rectangle-vertical",
                searchTerms: []
            },
            {
                title: "fi-rr-rectangle-vertical-history",
                searchTerms: []
            },
            {
                title: "fi-rr-rectangle-xmark",
                searchTerms: []
            },
            {
                title: "fi-rr-rectangles-mixed",
                searchTerms: []
            },
            {
                title: "fi-rr-recycle",
                searchTerms: []
            },
            {
                title: "fi-rr-recycle-bin",
                searchTerms: []
            },
            {
                title: "fi-rr-redo",
                searchTerms: []
            },
            {
                title: "fi-rr-redo-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-reel",
                searchTerms: []
            },
            {
                title: "fi-rr-refer",
                searchTerms: []
            },
            {
                title: "fi-rr-refer-arrow",
                searchTerms: []
            },
            {
                title: "fi-rr-referral",
                searchTerms: []
            },
            {
                title: "fi-rr-referral-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-referral-link-arrow",
                searchTerms: []
            },
            {
                title: "fi-rr-referral-user",
                searchTerms: []
            },
            {
                title: "fi-rr-reflect",
                searchTerms: []
            },
            {
                title: "fi-rr-reflect-horizontal",
                searchTerms: []
            },
            {
                title: "fi-rr-reflect-horizontal-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-reflect-vertical",
                searchTerms: []
            },
            {
                title: "fi-rr-refresh",
                searchTerms: []
            },
            {
                title: "fi-rr-refrigerator",
                searchTerms: []
            },
            {
                title: "fi-rr-refund",
                searchTerms: []
            },
            {
                title: "fi-rr-refund-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-region-pin",
                searchTerms: []
            },
            {
                title: "fi-rr-region-pin-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-registered",
                searchTerms: []
            },
            {
                title: "fi-rr-registration-paper",
                searchTerms: []
            },
            {
                title: "fi-rr-remote-control",
                searchTerms: []
            },
            {
                title: "fi-rr-remote-control-hand",
                searchTerms: []
            },
            {
                title: "fi-rr-remove-folder",
                searchTerms: []
            },
            {
                title: "fi-rr-remove-user",
                searchTerms: []
            },
            {
                title: "fi-rr-rent",
                searchTerms: []
            },
            {
                title: "fi-rr-rent-signal",
                searchTerms: []
            },
            {
                title: "fi-rr-replace",
                searchTerms: []
            },
            {
                title: "fi-rr-replay-10",
                searchTerms: []
            },
            {
                title: "fi-rr-replay-30",
                searchTerms: []
            },
            {
                title: "fi-rr-replay-5",
                searchTerms: []
            },
            {
                title: "fi-rr-reply-all",
                searchTerms: []
            },
            {
                title: "fi-rr-republican",
                searchTerms: []
            },
            {
                title: "fi-rr-research-arrows-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-reservation-smartphone",
                searchTerms: []
            },
            {
                title: "fi-rr-reservation-table",
                searchTerms: []
            },
            {
                title: "fi-rr-resistance-band",
                searchTerms: []
            },
            {
                title: "fi-rr-resize",
                searchTerms: []
            },
            {
                title: "fi-rr-resources",
                searchTerms: []
            },
            {
                title: "fi-rr-responsability",
                searchTerms: []
            },
            {
                title: "fi-rr-restaurant",
                searchTerms: []
            },
            {
                title: "fi-rr-restock",
                searchTerms: []
            },
            {
                title: "fi-rr-restroom-simple",
                searchTerms: []
            },
            {
                title: "fi-rr-resume",
                searchTerms: []
            },
            {
                title: "fi-rr-Revenue",
                searchTerms: []
            },
            {
                title: "fi-rr-revenue-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-revenue-euro",
                searchTerms: []
            },
            {
                title: "fi-rr-review",
                searchTerms: []
            },
            {
                title: "fi-rr-rewind",
                searchTerms: []
            },
            {
                title: "fi-rr-rewind-button-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-rhombus",
                searchTerms: []
            },
            {
                title: "fi-rr-ribbon",
                searchTerms: []
            },
            {
                title: "fi-rr-right",
                searchTerms: []
            },
            {
                title: "fi-rr-right-from-bracket",
                searchTerms: []
            },
            {
                title: "fi-rr-ring",
                searchTerms: []
            },
            {
                title: "fi-rr-ring-diamond",
                searchTerms: []
            },
            {
                title: "fi-rr-rings-wedding",
                searchTerms: []
            },
            {
                title: "fi-rr-risk",
                searchTerms: []
            },
            {
                title: "fi-rr-risk-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-road",
                searchTerms: []
            },
            {
                title: "fi-rr-road-barrier",
                searchTerms: []
            },
            {
                title: "fi-rr-road-map-pin",
                searchTerms: []
            },
            {
                title: "fi-rr-road-sign-left",
                searchTerms: []
            },
            {
                title: "fi-rr-roadmap",
                searchTerms: []
            },
            {
                title: "fi-rr-robot",
                searchTerms: []
            },
            {
                title: "fi-rr-robotic-arm",
                searchTerms: []
            },
            {
                title: "fi-rr-rocket",
                searchTerms: []
            },
            {
                title: "fi-rr-rocket-hand",
                searchTerms: []
            },
            {
                title: "fi-rr-rocket-holding-hand",
                searchTerms: []
            },
            {
                title: "fi-rr-rocket-lunch",
                searchTerms: []
            },
            {
                title: "fi-rr-roller-coaster",
                searchTerms: []
            },
            {
                title: "fi-rr-room-service",
                searchTerms: []
            },
            {
                title: "fi-rr-rose",
                searchTerms: []
            },
            {
                title: "fi-rr-rose-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-rotate-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-rotate-left",
                searchTerms: []
            },
            {
                title: "fi-rr-rotate-reverse",
                searchTerms: []
            },
            {
                title: "fi-rr-rotate-right",
                searchTerms: []
            },
            {
                title: "fi-rr-rotate-square",
                searchTerms: []
            },
            {
                title: "fi-rr-roulette",
                searchTerms: []
            },
            {
                title: "fi-rr-route",
                searchTerms: []
            },
            {
                title: "fi-rr-route-highway",
                searchTerms: []
            },
            {
                title: "fi-rr-route-interstate",
                searchTerms: []
            },
            {
                title: "fi-rr-router",
                searchTerms: []
            },
            {
                title: "fi-rr-router-wifi",
                searchTerms: []
            },
            {
                title: "fi-rr-router-wifi-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-rss",
                searchTerms: []
            },
            {
                title: "fi-rr-rss-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-ruble-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-rugby",
                searchTerms: []
            },
            {
                title: "fi-rr-ruler-combined",
                searchTerms: []
            },
            {
                title: "fi-rr-ruler-horizontal",
                searchTerms: []
            },
            {
                title: "fi-rr-ruler-triangle",
                searchTerms: []
            },
            {
                title: "fi-rr-ruler-vertical",
                searchTerms: []
            },
            {
                title: "fi-rr-rules",
                searchTerms: []
            },
            {
                title: "fi-rr-rules-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-running",
                searchTerms: []
            },
            {
                title: "fi-rr-running-track",
                searchTerms: []
            },
            {
                title: "fi-rr-rupee-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-rupiah-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-russian",
                searchTerms: []
            },
            {
                title: "fi-rr-rv",
                searchTerms: []
            },
            {
                title: "fi-rr-s",
                searchTerms: []
            },
            {
                title: "fi-rr-sack",
                searchTerms: []
            },
            {
                title: "fi-rr-sack-dollar",
                searchTerms: []
            },
            {
                title: "fi-rr-sad",
                searchTerms: []
            },
            {
                title: "fi-rr-sad-cry",
                searchTerms: []
            },
            {
                title: "fi-rr-sad-tear",
                searchTerms: []
            },
            {
                title: "fi-rr-safe-box",
                searchTerms: []
            },
            {
                title: "fi-rr-sailboat",
                searchTerms: []
            },
            {
                title: "fi-rr-salad",
                searchTerms: []
            },
            {
                title: "fi-rr-salary-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-salt-pepper",
                searchTerms: []
            },
            {
                title: "fi-rr-salt-shaker",
                searchTerms: []
            },
            {
                title: "fi-rr-sandwich",
                searchTerms: []
            },
            {
                title: "fi-rr-sandwich-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-satellite",
                searchTerms: []
            },
            {
                title: "fi-rr-satellite-dish",
                searchTerms: []
            },
            {
                title: "fi-rr-satisfaction-bar",
                searchTerms: []
            },
            {
                title: "fi-rr-saturday",
                searchTerms: []
            },
            {
                title: "fi-rr-sauce",
                searchTerms: []
            },
            {
                title: "fi-rr-sausage",
                searchTerms: []
            },
            {
                title: "fi-rr-sax-hot",
                searchTerms: []
            },
            {
                title: "fi-rr-saxophone",
                searchTerms: []
            },
            {
                title: "fi-rr-scale",
                searchTerms: []
            },
            {
                title: "fi-rr-scale-comparison",
                searchTerms: []
            },
            {
                title: "fi-rr-scale-comparison-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-scalpel",
                searchTerms: []
            },
            {
                title: "fi-rr-scalpel-path",
                searchTerms: []
            },
            {
                title: "fi-rr-scanner-gun",
                searchTerms: []
            },
            {
                title: "fi-rr-scanner-image",
                searchTerms: []
            },
            {
                title: "fi-rr-scanner-keyboard",
                searchTerms: []
            },
            {
                title: "fi-rr-scanner-touchscreen",
                searchTerms: []
            },
            {
                title: "fi-rr-scarecrow",
                searchTerms: []
            },
            {
                title: "fi-rr-scarf",
                searchTerms: []
            },
            {
                title: "fi-rr-school",
                searchTerms: []
            },
            {
                title: "fi-rr-school-bus",
                searchTerms: []
            },
            {
                title: "fi-rr-school-lock",
                searchTerms: []
            },
            {
                title: "fi-rr-scissors",
                searchTerms: []
            },
            {
                title: "fi-rr-scooter",
                searchTerms: []
            },
            {
                title: "fi-rr-screen",
                searchTerms: []
            },
            {
                title: "fi-rr-screen-share",
                searchTerms: []
            },
            {
                title: "fi-rr-screencast",
                searchTerms: []
            },
            {
                title: "fi-rr-screw",
                searchTerms: []
            },
            {
                title: "fi-rr-screw-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-screwdriver",
                searchTerms: []
            },
            {
                title: "fi-rr-scribble",
                searchTerms: []
            },
            {
                title: "fi-rr-script",
                searchTerms: []
            },
            {
                title: "fi-rr-scroll",
                searchTerms: []
            },
            {
                title: "fi-rr-scroll-document-story",
                searchTerms: []
            },
            {
                title: "fi-rr-scroll-old",
                searchTerms: []
            },
            {
                title: "fi-rr-scroll-torah",
                searchTerms: []
            },
            {
                title: "fi-rr-scrubber",
                searchTerms: []
            },
            {
                title: "fi-rr-sculpture",
                searchTerms: []
            },
            {
                title: "fi-rr-scythe",
                searchTerms: []
            },
            {
                title: "fi-rr-sd-card",
                searchTerms: []
            },
            {
                title: "fi-rr-sd-cards",
                searchTerms: []
            },
            {
                title: "fi-rr-seal",
                searchTerms: []
            },
            {
                title: "fi-rr-seal-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-seal-question",
                searchTerms: []
            },
            {
                title: "fi-rr-search",
                searchTerms: []
            },
            {
                title: "fi-rr-search-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-search-dollar",
                searchTerms: []
            },
            {
                title: "fi-rr-search-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-search-location",
                searchTerms: []
            },
            {
                title: "fi-rr-seat-airline",
                searchTerms: []
            },
            {
                title: "fi-rr-seatbelt-safety-driver",
                searchTerms: []
            },
            {
                title: "fi-rr-second",
                searchTerms: []
            },
            {
                title: "fi-rr-second-award",
                searchTerms: []
            },
            {
                title: "fi-rr-second-laurel",
                searchTerms: []
            },
            {
                title: "fi-rr-second-medal",
                searchTerms: []
            },
            {
                title: "fi-rr-security",
                searchTerms: []
            },
            {
                title: "fi-rr-seedling",
                searchTerms: []
            },
            {
                title: "fi-rr-selection",
                searchTerms: []
            },
            {
                title: "fi-rr-sell",
                searchTerms: []
            },
            {
                title: "fi-rr-seller",
                searchTerms: []
            },
            {
                title: "fi-rr-seller-store",
                searchTerms: []
            },
            {
                title: "fi-rr-selling",
                searchTerms: []
            },
            {
                title: "fi-rr-send-back",
                searchTerms: []
            },
            {
                title: "fi-rr-send-backward",
                searchTerms: []
            },
            {
                title: "fi-rr-send-money",
                searchTerms: []
            },
            {
                title: "fi-rr-send-money-smartphone",
                searchTerms: []
            },
            {
                title: "fi-rr-sensor",
                searchTerms: []
            },
            {
                title: "fi-rr-sensor-alert",
                searchTerms: []
            },
            {
                title: "fi-rr-sensor-fire",
                searchTerms: []
            },
            {
                title: "fi-rr-sensor-on",
                searchTerms: []
            },
            {
                title: "fi-rr-sensor-smoke",
                searchTerms: []
            },
            {
                title: "fi-rr-server-key",
                searchTerms: []
            },
            {
                title: "fi-rr-settings",
                searchTerms: []
            },
            {
                title: "fi-rr-settings-sliders",
                searchTerms: []
            },
            {
                title: "fi-rr-sewing-machine",
                searchTerms: []
            },
            {
                title: "fi-rr-sewing-machine-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-share",
                searchTerms: []
            },
            {
                title: "fi-rr-share-alt-square",
                searchTerms: []
            },
            {
                title: "fi-rr-share-square",
                searchTerms: []
            },
            {
                title: "fi-rr-sheep",
                searchTerms: []
            },
            {
                title: "fi-rr-shekel-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-shelves",
                searchTerms: []
            },
            {
                title: "fi-rr-shelves-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-shield",
                searchTerms: []
            },
            {
                title: "fi-rr-shield-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-shield-cat",
                searchTerms: []
            },
            {
                title: "fi-rr-shield-check",
                searchTerms: []
            },
            {
                title: "fi-rr-shield-cross",
                searchTerms: []
            },
            {
                title: "fi-rr-shield-divided-four",
                searchTerms: []
            },
            {
                title: "fi-rr-shield-dog",
                searchTerms: []
            },
            {
                title: "fi-rr-shield-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-shield-interrogation",
                searchTerms: []
            },
            {
                title: "fi-rr-shield-keyhole",
                searchTerms: []
            },
            {
                title: "fi-rr-shield-minus",
                searchTerms: []
            },
            {
                title: "fi-rr-shield-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-shield-security-risk",
                searchTerms: []
            },
            {
                title: "fi-rr-shield-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-shield-trust",
                searchTerms: []
            },
            {
                title: "fi-rr-shield-virus",
                searchTerms: []
            },
            {
                title: "fi-rr-shield-xmark",
                searchTerms: []
            },
            {
                title: "fi-rr-ship",
                searchTerms: []
            },
            {
                title: "fi-rr-ship-side",
                searchTerms: []
            },
            {
                title: "fi-rr-shipping-fast",
                searchTerms: []
            },
            {
                title: "fi-rr-shipping-timed",
                searchTerms: []
            },
            {
                title: "fi-rr-shirt",
                searchTerms: []
            },
            {
                title: "fi-rr-shirt-long-sleeve",
                searchTerms: []
            },
            {
                title: "fi-rr-shirt-running",
                searchTerms: []
            },
            {
                title: "fi-rr-shirt-tank-top",
                searchTerms: []
            },
            {
                title: "fi-rr-shish-kebab",
                searchTerms: []
            },
            {
                title: "fi-rr-shoe-prints",
                searchTerms: []
            },
            {
                title: "fi-rr-shop",
                searchTerms: []
            },
            {
                title: "fi-rr-shop-lock",
                searchTerms: []
            },
            {
                title: "fi-rr-shop-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-shopping-bag",
                searchTerms: []
            },
            {
                title: "fi-rr-shopping-bag-add",
                searchTerms: []
            },
            {
                title: "fi-rr-shopping-basket",
                searchTerms: []
            },
            {
                title: "fi-rr-shopping-cart",
                searchTerms: []
            },
            {
                title: "fi-rr-shopping-cart-add",
                searchTerms: []
            },
            {
                title: "fi-rr-shopping-cart-buyer",
                searchTerms: []
            },
            {
                title: "fi-rr-shopping-cart-check",
                searchTerms: []
            },
            {
                title: "fi-rr-shopping-cart-nft",
                searchTerms: []
            },
            {
                title: "fi-rr-shovel",
                searchTerms: []
            },
            {
                title: "fi-rr-shovel-snow",
                searchTerms: []
            },
            {
                title: "fi-rr-shower",
                searchTerms: []
            },
            {
                title: "fi-rr-shower-down",
                searchTerms: []
            },
            {
                title: "fi-rr-shredder",
                searchTerms: []
            },
            {
                title: "fi-rr-shrimp",
                searchTerms: []
            },
            {
                title: "fi-rr-shuffle",
                searchTerms: []
            },
            {
                title: "fi-rr-shuttle-van",
                searchTerms: []
            },
            {
                title: "fi-rr-shuttlecock",
                searchTerms: []
            },
            {
                title: "fi-rr-Sickle",
                searchTerms: []
            },
            {
                title: "fi-rr-sidebar",
                searchTerms: []
            },
            {
                title: "fi-rr-sidebar-flip",
                searchTerms: []
            },
            {
                title: "fi-rr-sigma",
                searchTerms: []
            },
            {
                title: "fi-rr-sign-hanging",
                searchTerms: []
            },
            {
                title: "fi-rr-sign-in-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-sign-out-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-sign-posts",
                searchTerms: []
            },
            {
                title: "fi-rr-sign-posts-wrench",
                searchTerms: []
            },
            {
                title: "fi-rr-sign-up",
                searchTerms: []
            },
            {
                title: "fi-rr-signal-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-signal-alt-1",
                searchTerms: []
            },
            {
                title: "fi-rr-signal-alt-2",
                searchTerms: []
            },
            {
                title: "fi-rr-signal-alt-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-signal-bars-fair",
                searchTerms: []
            },
            {
                title: "fi-rr-signal-bars-good",
                searchTerms: []
            },
            {
                title: "fi-rr-signal-bars-weak",
                searchTerms: []
            },
            {
                title: "fi-rr-signal-stream",
                searchTerms: []
            },
            {
                title: "fi-rr-signal-stream-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-signature",
                searchTerms: []
            },
            {
                title: "fi-rr-signature-lock",
                searchTerms: []
            },
            {
                title: "fi-rr-signature-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-sim-card",
                searchTerms: []
            },
            {
                title: "fi-rr-sim-cards",
                searchTerms: []
            },
            {
                title: "fi-rr-sink",
                searchTerms: []
            },
            {
                title: "fi-rr-Siren",
                searchTerms: []
            },
            {
                title: "fi-rr-siren-on",
                searchTerms: []
            },
            {
                title: "fi-rr-site",
                searchTerms: []
            },
            {
                title: "fi-rr-site-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-site-browser",
                searchTerms: []
            },
            {
                title: "fi-rr-sitemap",
                searchTerms: []
            },
            {
                title: "fi-rr-skateboard",
                searchTerms: []
            },
            {
                title: "fi-rr-skating",
                searchTerms: []
            },
            {
                title: "fi-rr-skeleton",
                searchTerms: []
            },
            {
                title: "fi-rr-skeleton-ribs",
                searchTerms: []
            },
            {
                title: "fi-rr-skewer",
                searchTerms: []
            },
            {
                title: "fi-rr-ski-boot-ski",
                searchTerms: []
            },
            {
                title: "fi-rr-ski-jump",
                searchTerms: []
            },
            {
                title: "fi-rr-ski-lift",
                searchTerms: []
            },
            {
                title: "fi-rr-skiing",
                searchTerms: []
            },
            {
                title: "fi-rr-skiing-nordic",
                searchTerms: []
            },
            {
                title: "fi-rr-skill",
                searchTerms: []
            },
            {
                title: "fi-rr-skill-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-skill-user",
                searchTerms: []
            },
            {
                title: "fi-rr-skin",
                searchTerms: []
            },
            {
                title: "fi-rr-skin-acne",
                searchTerms: []
            },
            {
                title: "fi-rr-skin-arrow",
                searchTerms: []
            },
            {
                title: "fi-rr-skin-drop",
                searchTerms: []
            },
            {
                title: "fi-rr-skin-hair",
                searchTerms: []
            },
            {
                title: "fi-rr-skin-laser",
                searchTerms: []
            },
            {
                title: "fi-rr-skin-uv",
                searchTerms: []
            },
            {
                title: "fi-rr-skip-15-seconds",
                searchTerms: []
            },
            {
                title: "fi-rr-skull",
                searchTerms: []
            },
            {
                title: "fi-rr-skull-cow",
                searchTerms: []
            },
            {
                title: "fi-rr-skull-crossbones",
                searchTerms: []
            },
            {
                title: "fi-rr-skull-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-sledding",
                searchTerms: []
            },
            {
                title: "fi-rr-sleeping-bag",
                searchTerms: []
            },
            {
                title: "fi-rr-sleeping-cat",
                searchTerms: []
            },
            {
                title: "fi-rr-sleigh",
                searchTerms: []
            },
            {
                title: "fi-rr-sliders-h-square",
                searchTerms: []
            },
            {
                title: "fi-rr-sliders-v",
                searchTerms: []
            },
            {
                title: "fi-rr-sliders-v-square",
                searchTerms: []
            },
            {
                title: "fi-rr-slot-machine",
                searchTerms: []
            },
            {
                title: "fi-rr-smart-home",
                searchTerms: []
            },
            {
                title: "fi-rr-smart-home-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-smartphone",
                searchTerms: []
            },
            {
                title: "fi-rr-smile",
                searchTerms: []
            },
            {
                title: "fi-rr-smile-beam",
                searchTerms: []
            },
            {
                title: "fi-rr-smile-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-smile-wink",
                searchTerms: []
            },
            {
                title: "fi-rr-smiley-comment-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-smog",
                searchTerms: []
            },
            {
                title: "fi-rr-smoke",
                searchTerms: []
            },
            {
                title: "fi-rr-smoking",
                searchTerms: []
            },
            {
                title: "fi-rr-smoking-ban",
                searchTerms: []
            },
            {
                title: "fi-rr-snake",
                searchTerms: []
            },
            {
                title: "fi-rr-snap",
                searchTerms: []
            },
            {
                title: "fi-rr-snooze",
                searchTerms: []
            },
            {
                title: "fi-rr-snow-blowing",
                searchTerms: []
            },
            {
                title: "fi-rr-snowboarding",
                searchTerms: []
            },
            {
                title: "fi-rr-snowflake",
                searchTerms: []
            },
            {
                title: "fi-rr-snowflake-droplets",
                searchTerms: []
            },
            {
                title: "fi-rr-snowflakes",
                searchTerms: []
            },
            {
                title: "fi-rr-snowman-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-snowman-head",
                searchTerms: []
            },
            {
                title: "fi-rr-snowmobile",
                searchTerms: []
            },
            {
                title: "fi-rr-snowplow",
                searchTerms: []
            },
            {
                title: "fi-rr-soap",
                searchTerms: []
            },
            {
                title: "fi-rr-soap-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-social-network",
                searchTerms: []
            },
            {
                title: "fi-rr-socks",
                searchTerms: []
            },
            {
                title: "fi-rr-sofa",
                searchTerms: []
            },
            {
                title: "fi-rr-sofa-size",
                searchTerms: []
            },
            {
                title: "fi-rr-solar-panel",
                searchTerms: []
            },
            {
                title: "fi-rr-solar-panel-sun",
                searchTerms: []
            },
            {
                title: "fi-rr-solar-system",
                searchTerms: []
            },
            {
                title: "fi-rr-sold-house",
                searchTerms: []
            },
            {
                title: "fi-rr-sold-signal",
                searchTerms: []
            },
            {
                title: "fi-rr-sort",
                searchTerms: []
            },
            {
                title: "fi-rr-sort-alpha-down",
                searchTerms: []
            },
            {
                title: "fi-rr-sort-alpha-down-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-sort-alpha-up",
                searchTerms: []
            },
            {
                title: "fi-rr-sort-alpha-up-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-sort-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-sort-amount-down",
                searchTerms: []
            },
            {
                title: "fi-rr-sort-amount-down-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-sort-amount-up",
                searchTerms: []
            },
            {
                title: "fi-rr-sort-amount-up-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-sort-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-sort-circle-down",
                searchTerms: []
            },
            {
                title: "fi-rr-sort-circle-up",
                searchTerms: []
            },
            {
                title: "fi-rr-sort-down",
                searchTerms: []
            },
            {
                title: "fi-rr-sort-numeric-down",
                searchTerms: []
            },
            {
                title: "fi-rr-sort-numeric-down-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-sort-shapes-down",
                searchTerms: []
            },
            {
                title: "fi-rr-sort-shapes-up",
                searchTerms: []
            },
            {
                title: "fi-rr-sort-size-down",
                searchTerms: []
            },
            {
                title: "fi-rr-sort-size-up",
                searchTerms: []
            },
            {
                title: "fi-rr-soup",
                searchTerms: []
            },
            {
                title: "fi-rr-source-data",
                searchTerms: []
            },
            {
                title: "fi-rr-source-document",
                searchTerms: []
            },
            {
                title: "fi-rr-source-document-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-spa",
                searchTerms: []
            },
            {
                title: "fi-rr-space-shuttle",
                searchTerms: []
            },
            {
                title: "fi-rr-space-station-moon",
                searchTerms: []
            },
            {
                title: "fi-rr-space-station-moon-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-spade",
                searchTerms: []
            },
            {
                title: "fi-rr-spaghetti-monster-flying",
                searchTerms: []
            },
            {
                title: "fi-rr-spain-map",
                searchTerms: []
            },
            {
                title: "fi-rr-spanish",
                searchTerms: []
            },
            {
                title: "fi-rr-sparkles",
                searchTerms: []
            },
            {
                title: "fi-rr-spartan-helmet",
                searchTerms: []
            },
            {
                title: "fi-rr-speaker",
                searchTerms: []
            },
            {
                title: "fi-rr-speakers",
                searchTerms: []
            },
            {
                title: "fi-rr-speech-bubble-story",
                searchTerms: []
            },
            {
                title: "fi-rr-speedometer-arrow",
                searchTerms: []
            },
            {
                title: "fi-rr-speedometer-kpi",
                searchTerms: []
            },
            {
                title: "fi-rr-sphere",
                searchTerms: []
            },
            {
                title: "fi-rr-spider",
                searchTerms: []
            },
            {
                title: "fi-rr-spider-black-widow",
                searchTerms: []
            },
            {
                title: "fi-rr-spider-web",
                searchTerms: []
            },
            {
                title: "fi-rr-spinner",
                searchTerms: []
            },
            {
                title: "fi-rr-split",
                searchTerms: []
            },
            {
                title: "fi-rr-split-up-relation",
                searchTerms: []
            },
            {
                title: "fi-rr-splotch",
                searchTerms: []
            },
            {
                title: "fi-rr-spoon",
                searchTerms: []
            },
            {
                title: "fi-rr-sport",
                searchTerms: []
            },
            {
                title: "fi-rr-spray-can",
                searchTerms: []
            },
            {
                title: "fi-rr-spray-can-sparkles",
                searchTerms: []
            },
            {
                title: "fi-rr-spring-calendar",
                searchTerms: []
            },
            {
                title: "fi-rr-sprinkler",
                searchTerms: []
            },
            {
                title: "fi-rr-spy",
                searchTerms: []
            },
            {
                title: "fi-rr-sql-file",
                searchTerms: []
            },
            {
                title: "fi-rr-sql-server",
                searchTerms: []
            },
            {
                title: "fi-rr-square",
                searchTerms: []
            },
            {
                title: "fi-rr-square-0",
                searchTerms: []
            },
            {
                title: "fi-rr-square-1",
                searchTerms: []
            },
            {
                title: "fi-rr-square-2",
                searchTerms: []
            },
            {
                title: "fi-rr-square-3",
                searchTerms: []
            },
            {
                title: "fi-rr-square-4",
                searchTerms: []
            },
            {
                title: "fi-rr-square-5",
                searchTerms: []
            },
            {
                title: "fi-rr-square-6",
                searchTerms: []
            },
            {
                title: "fi-rr-square-7",
                searchTerms: []
            },
            {
                title: "fi-rr-square-8",
                searchTerms: []
            },
            {
                title: "fi-rr-square-9",
                searchTerms: []
            },
            {
                title: "fi-rr-square-a",
                searchTerms: []
            },
            {
                title: "fi-rr-square-b",
                searchTerms: []
            },
            {
                title: "fi-rr-square-bolt",
                searchTerms: []
            },
            {
                title: "fi-rr-square-c",
                searchTerms: []
            },
            {
                title: "fi-rr-square-code",
                searchTerms: []
            },
            {
                title: "fi-rr-square-d",
                searchTerms: []
            },
            {
                title: "fi-rr-square-dashed",
                searchTerms: []
            },
            {
                title: "fi-rr-square-dashed-circle-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-square-divide",
                searchTerms: []
            },
            {
                title: "fi-rr-square-e",
                searchTerms: []
            },
            {
                title: "fi-rr-square-ellipsis",
                searchTerms: []
            },
            {
                title: "fi-rr-square-ellipsis-vertical",
                searchTerms: []
            },
            {
                title: "fi-rr-square-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-square-f",
                searchTerms: []
            },
            {
                title: "fi-rr-square-g",
                searchTerms: []
            },
            {
                title: "fi-rr-square-h",
                searchTerms: []
            },
            {
                title: "fi-rr-square-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-square-i",
                searchTerms: []
            },
            {
                title: "fi-rr-square-info",
                searchTerms: []
            },
            {
                title: "fi-rr-square-j",
                searchTerms: []
            },
            {
                title: "fi-rr-square-k",
                searchTerms: []
            },
            {
                title: "fi-rr-square-kanban",
                searchTerms: []
            },
            {
                title: "fi-rr-square-l",
                searchTerms: []
            },
            {
                title: "fi-rr-square-m",
                searchTerms: []
            },
            {
                title: "fi-rr-square-minus",
                searchTerms: []
            },
            {
                title: "fi-rr-square-n",
                searchTerms: []
            },
            {
                title: "fi-rr-square-o",
                searchTerms: []
            },
            {
                title: "fi-rr-square-p",
                searchTerms: []
            },
            {
                title: "fi-rr-square-phone-hangup",
                searchTerms: []
            },
            {
                title: "fi-rr-square-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-square-poll-horizontal",
                searchTerms: []
            },
            {
                title: "fi-rr-square-poll-vertical",
                searchTerms: []
            },
            {
                title: "fi-rr-square-q",
                searchTerms: []
            },
            {
                title: "fi-rr-square-quote",
                searchTerms: []
            },
            {
                title: "fi-rr-square-r",
                searchTerms: []
            },
            {
                title: "fi-rr-square-root",
                searchTerms: []
            },
            {
                title: "fi-rr-square-s",
                searchTerms: []
            },
            {
                title: "fi-rr-square-small",
                searchTerms: []
            },
            {
                title: "fi-rr-square-star",
                searchTerms: []
            },
            {
                title: "fi-rr-square-t",
                searchTerms: []
            },
            {
                title: "fi-rr-square-terminal",
                searchTerms: []
            },
            {
                title: "fi-rr-square-u",
                searchTerms: []
            },
            {
                title: "fi-rr-square-up-right",
                searchTerms: []
            },
            {
                title: "fi-rr-square-v",
                searchTerms: []
            },
            {
                title: "fi-rr-square-w",
                searchTerms: []
            },
            {
                title: "fi-rr-square-x",
                searchTerms: []
            },
            {
                title: "fi-rr-square-y",
                searchTerms: []
            },
            {
                title: "fi-rr-square-z",
                searchTerms: []
            },
            {
                title: "fi-rr-squid",
                searchTerms: []
            },
            {
                title: "fi-rr-squircle",
                searchTerms: []
            },
            {
                title: "fi-rr-squirrel",
                searchTerms: []
            },
            {
                title: "fi-rr-staff",
                searchTerms: []
            },
            {
                title: "fi-rr-stage",
                searchTerms: []
            },
            {
                title: "fi-rr-stage-concert",
                searchTerms: []
            },
            {
                title: "fi-rr-stage-theatre",
                searchTerms: []
            },
            {
                title: "fi-rr-stairs",
                searchTerms: []
            },
            {
                title: "fi-rr-stamp",
                searchTerms: []
            },
            {
                title: "fi-rr-standard-definition",
                searchTerms: []
            },
            {
                title: "fi-rr-star",
                searchTerms: []
            },
            {
                title: "fi-rr-star-and-crescent",
                searchTerms: []
            },
            {
                title: "fi-rr-star-christmas",
                searchTerms: []
            },
            {
                title: "fi-rr-star-comment-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-star-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-star-octogram",
                searchTerms: []
            },
            {
                title: "fi-rr-star-of-david",
                searchTerms: []
            },
            {
                title: "fi-rr-star-rating-call",
                searchTerms: []
            },
            {
                title: "fi-rr-star-sharp-half",
                searchTerms: []
            },
            {
                title: "fi-rr-star-sharp-half-stroke",
                searchTerms: []
            },
            {
                title: "fi-rr-star-shooting",
                searchTerms: []
            },
            {
                title: "fi-rr-starfighter",
                searchTerms: []
            },
            {
                title: "fi-rr-stars",
                searchTerms: []
            },
            {
                title: "fi-rr-state-country",
                searchTerms: []
            },
            {
                title: "fi-rr-stationary-bike",
                searchTerms: []
            },
            {
                title: "fi-rr-stats",
                searchTerms: []
            },
            {
                title: "fi-rr-steak",
                searchTerms: []
            },
            {
                title: "fi-rr-steam-iron",
                searchTerms: []
            },
            {
                title: "fi-rr-steering-wheel",
                searchTerms: []
            },
            {
                title: "fi-rr-step-backward",
                searchTerms: []
            },
            {
                title: "fi-rr-step-forward",
                searchTerms: []
            },
            {
                title: "fi-rr-steps-carreer",
                searchTerms: []
            },
            {
                title: "fi-rr-sterling-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-stethoscope",
                searchTerms: []
            },
            {
                title: "fi-rr-sticker",
                searchTerms: []
            },
            {
                title: "fi-rr-stocking",
                searchTerms: []
            },
            {
                title: "fi-rr-stomach",
                searchTerms: []
            },
            {
                title: "fi-rr-stop",
                searchTerms: []
            },
            {
                title: "fi-rr-stop-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-stop-square",
                searchTerms: []
            },
            {
                title: "fi-rr-stopwatch",
                searchTerms: []
            },
            {
                title: "fi-rr-store-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-store-buyer",
                searchTerms: []
            },
            {
                title: "fi-rr-store-lock",
                searchTerms: []
            },
            {
                title: "fi-rr-store-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-story-book",
                searchTerms: []
            },
            {
                title: "fi-rr-story-fairy-tale",
                searchTerms: []
            },
            {
                title: "fi-rr-story-fantasy",
                searchTerms: []
            },
            {
                title: "fi-rr-strategy-chess-risk",
                searchTerms: []
            },
            {
                title: "fi-rr-strawberry",
                searchTerms: []
            },
            {
                title: "fi-rr-street-view",
                searchTerms: []
            },
            {
                title: "fi-rr-stretcher",
                searchTerms: []
            },
            {
                title: "fi-rr-strikethrough",
                searchTerms: []
            },
            {
                title: "fi-rr-stroopwafel",
                searchTerms: []
            },
            {
                title: "fi-rr-student",
                searchTerms: []
            },
            {
                title: "fi-rr-student-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-subfolder",
                searchTerms: []
            },
            {
                title: "fi-rr-subscript",
                searchTerms: []
            },
            {
                title: "fi-rr-subscription",
                searchTerms: []
            },
            {
                title: "fi-rr-subscription-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-subscription-model",
                searchTerms: []
            },
            {
                title: "fi-rr-subscription-user",
                searchTerms: []
            },
            {
                title: "fi-rr-subtitles",
                searchTerms: []
            },
            {
                title: "fi-rr-subtitles-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-subway",
                searchTerms: []
            },
            {
                title: "fi-rr-suitcase-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-summary-check",
                searchTerms: []
            },
            {
                title: "fi-rr-summer",
                searchTerms: []
            },
            {
                title: "fi-rr-sun",
                searchTerms: []
            },
            {
                title: "fi-rr-sun-dust",
                searchTerms: []
            },
            {
                title: "fi-rr-sun-plant-wilt",
                searchTerms: []
            },
            {
                title: "fi-rr-sun-salutation-yoga",
                searchTerms: []
            },
            {
                title: "fi-rr-sunday",
                searchTerms: []
            },
            {
                title: "fi-rr-sunglasses",
                searchTerms: []
            },
            {
                title: "fi-rr-sunglasses-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-sunrise",
                searchTerms: []
            },
            {
                title: "fi-rr-sunrise-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-sunscreen",
                searchTerms: []
            },
            {
                title: "fi-rr-sunset",
                searchTerms: []
            },
            {
                title: "fi-rr-superscript",
                searchTerms: []
            },
            {
                title: "fi-rr-supplier",
                searchTerms: []
            },
            {
                title: "fi-rr-supplier-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-surfing",
                searchTerms: []
            },
            {
                title: "fi-rr-surprise",
                searchTerms: []
            },
            {
                title: "fi-rr-surveillance-camera",
                searchTerms: []
            },
            {
                title: "fi-rr-survey-xmark",
                searchTerms: []
            },
            {
                title: "fi-rr-sushi",
                searchTerms: []
            },
            {
                title: "fi-rr-sushi-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-sushi-roll",
                searchTerms: []
            },
            {
                title: "fi-rr-svg",
                searchTerms: []
            },
            {
                title: "fi-rr-swap",
                searchTerms: []
            },
            {
                title: "fi-rr-swatchbook",
                searchTerms: []
            },
            {
                title: "fi-rr-swimmer",
                searchTerms: []
            },
            {
                title: "fi-rr-swimming-pool",
                searchTerms: []
            },
            {
                title: "fi-rr-swing",
                searchTerms: []
            },
            {
                title: "fi-rr-swipe-down",
                searchTerms: []
            },
            {
                title: "fi-rr-swipe-left",
                searchTerms: []
            },
            {
                title: "fi-rr-swipe-right",
                searchTerms: []
            },
            {
                title: "fi-rr-swipe-up",
                searchTerms: []
            },
            {
                title: "fi-rr-sword",
                searchTerms: []
            },
            {
                title: "fi-rr-sword-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-symbol",
                searchTerms: []
            },
            {
                title: "fi-rr-symbols",
                searchTerms: []
            },
            {
                title: "fi-rr-synagogue",
                searchTerms: []
            },
            {
                title: "fi-rr-syringe",
                searchTerms: []
            },
            {
                title: "fi-rr-syringe-injection-blood",
                searchTerms: []
            },
            {
                title: "fi-rr-system-cloud",
                searchTerms: []
            },
            {
                title: "fi-rr-t",
                searchTerms: []
            },
            {
                title: "fi-rr-t-rex",
                searchTerms: []
            },
            {
                title: "fi-rr-tab-folder",
                searchTerms: []
            },
            {
                title: "fi-rr-table",
                searchTerms: []
            },
            {
                title: "fi-rr-table-columns",
                searchTerms: []
            },
            {
                title: "fi-rr-table-layout",
                searchTerms: []
            },
            {
                title: "fi-rr-table-list",
                searchTerms: []
            },
            {
                title: "fi-rr-table-picnic",
                searchTerms: []
            },
            {
                title: "fi-rr-table-pivot",
                searchTerms: []
            },
            {
                title: "fi-rr-table-rows",
                searchTerms: []
            },
            {
                title: "fi-rr-table-tree",
                searchTerms: []
            },
            {
                title: "fi-rr-tablet",
                searchTerms: []
            },
            {
                title: "fi-rr-tablet-android",
                searchTerms: []
            },
            {
                title: "fi-rr-tablet-android-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-tablet-rugged",
                searchTerms: []
            },
            {
                title: "fi-rr-tachometer",
                searchTerms: []
            },
            {
                title: "fi-rr-tachometer-alt-average",
                searchTerms: []
            },
            {
                title: "fi-rr-tachometer-alt-fastest",
                searchTerms: []
            },
            {
                title: "fi-rr-tachometer-alt-slow",
                searchTerms: []
            },
            {
                title: "fi-rr-tachometer-alt-slowest",
                searchTerms: []
            },
            {
                title: "fi-rr-tachometer-average",
                searchTerms: []
            },
            {
                title: "fi-rr-tachometer-fast",
                searchTerms: []
            },
            {
                title: "fi-rr-tachometer-fastest",
                searchTerms: []
            },
            {
                title: "fi-rr-tachometer-slow",
                searchTerms: []
            },
            {
                title: "fi-rr-tachometer-slowest",
                searchTerms: []
            },
            {
                title: "fi-rr-taco",
                searchTerms: []
            },
            {
                title: "fi-rr-tags",
                searchTerms: []
            },
            {
                title: "fi-rr-talent",
                searchTerms: []
            },
            {
                title: "fi-rr-talent-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-talent-hand",
                searchTerms: []
            },
            {
                title: "fi-rr-tally",
                searchTerms: []
            },
            {
                title: "fi-rr-tally-1",
                searchTerms: []
            },
            {
                title: "fi-rr-tally-2",
                searchTerms: []
            },
            {
                title: "fi-rr-tally-3",
                searchTerms: []
            },
            {
                title: "fi-rr-tally-4",
                searchTerms: []
            },
            {
                title: "fi-rr-tamale",
                searchTerms: []
            },
            {
                title: "fi-rr-tank-water",
                searchTerms: []
            },
            {
                title: "fi-rr-tap",
                searchTerms: []
            },
            {
                title: "fi-rr-tape",
                searchTerms: []
            },
            {
                title: "fi-rr-target",
                searchTerms: []
            },
            {
                title: "fi-rr-target-audience",
                searchTerms: []
            },
            {
                title: "fi-rr-tattoo-machine",
                searchTerms: []
            },
            {
                title: "fi-rr-tax",
                searchTerms: []
            },
            {
                title: "fi-rr-tax-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-taxi",
                searchTerms: []
            },
            {
                title: "fi-rr-taxi-bus",
                searchTerms: []
            },
            {
                title: "fi-rr-team-check",
                searchTerms: []
            },
            {
                title: "fi-rr-team-check-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-teddy-bear",
                searchTerms: []
            },
            {
                title: "fi-rr-teeth-open",
                searchTerms: []
            },
            {
                title: "fi-rr-telescope",
                searchTerms: []
            },
            {
                title: "fi-rr-temperature-down",
                searchTerms: []
            },
            {
                title: "fi-rr-temperature-frigid",
                searchTerms: []
            },
            {
                title: "fi-rr-temperature-high",
                searchTerms: []
            },
            {
                title: "fi-rr-temperature-list",
                searchTerms: []
            },
            {
                title: "fi-rr-temperature-low",
                searchTerms: []
            },
            {
                title: "fi-rr-temperature-up",
                searchTerms: []
            },
            {
                title: "fi-rr-template",
                searchTerms: []
            },
            {
                title: "fi-rr-template-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-tenge",
                searchTerms: []
            },
            {
                title: "fi-rr-tennis",
                searchTerms: []
            },
            {
                title: "fi-rr-tent-arrow-down-to-line",
                searchTerms: []
            },
            {
                title: "fi-rr-tent-arrow-left-right",
                searchTerms: []
            },
            {
                title: "fi-rr-tent-arrow-turn-left",
                searchTerms: []
            },
            {
                title: "fi-rr-tent-arrows-down",
                searchTerms: []
            },
            {
                title: "fi-rr-tents",
                searchTerms: []
            },
            {
                title: "fi-rr-terminal",
                searchTerms: []
            },
            {
                title: "fi-rr-terrace",
                searchTerms: []
            },
            {
                title: "fi-rr-test",
                searchTerms: []
            },
            {
                title: "fi-rr-test-tube",
                searchTerms: []
            },
            {
                title: "fi-rr-text",
                searchTerms: []
            },
            {
                title: "fi-rr-text-box",
                searchTerms: []
            },
            {
                title: "fi-rr-text-box-dots",
                searchTerms: []
            },
            {
                title: "fi-rr-text-box-edit",
                searchTerms: []
            },
            {
                title: "fi-rr-text-check",
                searchTerms: []
            },
            {
                title: "fi-rr-text-height",
                searchTerms: []
            },
            {
                title: "fi-rr-text-input-left",
                searchTerms: []
            },
            {
                title: "fi-rr-text-shadow",
                searchTerms: []
            },
            {
                title: "fi-rr-text-size",
                searchTerms: []
            },
            {
                title: "fi-rr-text-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-text-width",
                searchTerms: []
            },
            {
                title: "fi-rr-theater-masks",
                searchTerms: []
            },
            {
                title: "fi-rr-thermometer-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-thermometer-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-thermometer-full",
                searchTerms: []
            },
            {
                title: "fi-rr-thermometer-half",
                searchTerms: []
            },
            {
                title: "fi-rr-thermometer-quarter",
                searchTerms: []
            },
            {
                title: "fi-rr-thermometer-three-quarters",
                searchTerms: []
            },
            {
                title: "fi-rr-theta",
                searchTerms: []
            },
            {
                title: "fi-rr-third",
                searchTerms: []
            },
            {
                title: "fi-rr-third-award",
                searchTerms: []
            },
            {
                title: "fi-rr-third-laurel",
                searchTerms: []
            },
            {
                title: "fi-rr-third-medal",
                searchTerms: []
            },
            {
                title: "fi-rr-thought-bubble",
                searchTerms: []
            },
            {
                title: "fi-rr-three-direction",
                searchTerms: []
            },
            {
                title: "fi-rr-three-leaf-clover",
                searchTerms: []
            },
            {
                title: "fi-rr-thumbs-up-trust",
                searchTerms: []
            },
            {
                title: "fi-rr-thumbtack",
                searchTerms: []
            },
            {
                title: "fi-rr-thumbtack-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-thunderstorm",
                searchTerms: []
            },
            {
                title: "fi-rr-thunderstorm-moon",
                searchTerms: []
            },
            {
                title: "fi-rr-thunderstorm-risk",
                searchTerms: []
            },
            {
                title: "fi-rr-thunderstorm-sun",
                searchTerms: []
            },
            {
                title: "fi-rr-thursday",
                searchTerms: []
            },
            {
                title: "fi-rr-ticket",
                searchTerms: []
            },
            {
                title: "fi-rr-ticket-airline",
                searchTerms: []
            },
            {
                title: "fi-rr-ticket-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-tickets",
                searchTerms: []
            },
            {
                title: "fi-rr-tickets-airline",
                searchTerms: []
            },
            {
                title: "fi-rr-tie",
                searchTerms: []
            },
            {
                title: "fi-rr-tilde",
                searchTerms: []
            },
            {
                title: "fi-rr-time-add",
                searchTerms: []
            },
            {
                title: "fi-rr-time-check",
                searchTerms: []
            },
            {
                title: "fi-rr-time-delete",
                searchTerms: []
            },
            {
                title: "fi-rr-time-fast",
                searchTerms: []
            },
            {
                title: "fi-rr-time-forward",
                searchTerms: []
            },
            {
                title: "fi-rr-time-forward-sixty",
                searchTerms: []
            },
            {
                title: "fi-rr-time-forward-ten",
                searchTerms: []
            },
            {
                title: "fi-rr-time-half-past",
                searchTerms: []
            },
            {
                title: "fi-rr-time-oclock",
                searchTerms: []
            },
            {
                title: "fi-rr-time-past",
                searchTerms: []
            },
            {
                title: "fi-rr-time-quarter-past",
                searchTerms: []
            },
            {
                title: "fi-rr-time-quarter-to",
                searchTerms: []
            },
            {
                title: "fi-rr-time-twenty-four",
                searchTerms: []
            },
            {
                title: "fi-rr-time-watch-calendar",
                searchTerms: []
            },
            {
                title: "fi-rr-timer-clock-call",
                searchTerms: []
            },
            {
                title: "fi-rr-times-hexagon",
                searchTerms: []
            },
            {
                title: "fi-rr-tint-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-tip-button",
                searchTerms: []
            },
            {
                title: "fi-rr-tip-button-hand",
                searchTerms: []
            },
            {
                title: "fi-rr-tip-coin",
                searchTerms: []
            },
            {
                title: "fi-rr-tire",
                searchTerms: []
            },
            {
                title: "fi-rr-tire-flat",
                searchTerms: []
            },
            {
                title: "fi-rr-tire-pressure-warning",
                searchTerms: []
            },
            {
                title: "fi-rr-tire-rugged",
                searchTerms: []
            },
            {
                title: "fi-rr-tired",
                searchTerms: []
            },
            {
                title: "fi-rr-to-do",
                searchTerms: []
            },
            {
                title: "fi-rr-to-do-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-together-people",
                searchTerms: []
            },
            {
                title: "fi-rr-toggle-off",
                searchTerms: []
            },
            {
                title: "fi-rr-toggle-on",
                searchTerms: []
            },
            {
                title: "fi-rr-toilet",
                searchTerms: []
            },
            {
                title: "fi-rr-toilet-paper-blank",
                searchTerms: []
            },
            {
                title: "fi-rr-toilet-paper-blank-under",
                searchTerms: []
            },
            {
                title: "fi-rr-toilet-paper-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-toilet-paper-under",
                searchTerms: []
            },
            {
                title: "fi-rr-toilet-paper-under-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-toilet-paper-xmark",
                searchTerms: []
            },
            {
                title: "fi-rr-toilets-portable",
                searchTerms: []
            },
            {
                title: "fi-rr-token",
                searchTerms: []
            },
            {
                title: "fi-rr-tomato",
                searchTerms: []
            },
            {
                title: "fi-rr-tombstone",
                searchTerms: []
            },
            {
                title: "fi-rr-tombstone-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-tool-box",
                searchTerms: []
            },
            {
                title: "fi-rr-tool-crop",
                searchTerms: []
            },
            {
                title: "fi-rr-tool-marquee",
                searchTerms: []
            },
            {
                title: "fi-rr-tools",
                searchTerms: []
            },
            {
                title: "fi-rr-tooth",
                searchTerms: []
            },
            {
                title: "fi-rr-toothbrush",
                searchTerms: []
            },
            {
                title: "fi-rr-torch-inspiration-leader",
                searchTerms: []
            },
            {
                title: "fi-rr-torii-gate",
                searchTerms: []
            },
            {
                title: "fi-rr-tornado",
                searchTerms: []
            },
            {
                title: "fi-rr-total",
                searchTerms: []
            },
            {
                title: "fi-rr-tour-guide-people",
                searchTerms: []
            },
            {
                title: "fi-rr-tour-virtual",
                searchTerms: []
            },
            {
                title: "fi-rr-tower-control",
                searchTerms: []
            },
            {
                title: "fi-rr-track",
                searchTerms: []
            },
            {
                title: "fi-rr-tractor",
                searchTerms: []
            },
            {
                title: "fi-rr-trademark",
                searchTerms: []
            },
            {
                title: "fi-rr-traffic-cone",
                searchTerms: []
            },
            {
                title: "fi-rr-traffic-light",
                searchTerms: []
            },
            {
                title: "fi-rr-traffic-light-go",
                searchTerms: []
            },
            {
                title: "fi-rr-traffic-light-slow",
                searchTerms: []
            },
            {
                title: "fi-rr-traffic-light-stop",
                searchTerms: []
            },
            {
                title: "fi-rr-trailer",
                searchTerms: []
            },
            {
                title: "fi-rr-train",
                searchTerms: []
            },
            {
                title: "fi-rr-train-journey",
                searchTerms: []
            },
            {
                title: "fi-rr-train-side",
                searchTerms: []
            },
            {
                title: "fi-rr-train-station",
                searchTerms: []
            },
            {
                title: "fi-rr-train-station-building",
                searchTerms: []
            },
            {
                title: "fi-rr-train-subway-tunnel",
                searchTerms: []
            },
            {
                title: "fi-rr-train-track",
                searchTerms: []
            },
            {
                title: "fi-rr-train-tram",
                searchTerms: []
            },
            {
                title: "fi-rr-tram",
                searchTerms: []
            },
            {
                title: "fi-rr-transaction-euro",
                searchTerms: []
            },
            {
                title: "fi-rr-transaction-globe",
                searchTerms: []
            },
            {
                title: "fi-rr-transaction-yen",
                searchTerms: []
            },
            {
                title: "fi-rr-transform",
                searchTerms: []
            },
            {
                title: "fi-rr-transformation-block",
                searchTerms: []
            },
            {
                title: "fi-rr-transformation-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-transformation-design",
                searchTerms: []
            },
            {
                title: "fi-rr-transformation-shapes",
                searchTerms: []
            },
            {
                title: "fi-rr-transformer-bolt",
                searchTerms: []
            },
            {
                title: "fi-rr-transgender",
                searchTerms: []
            },
            {
                title: "fi-rr-translate",
                searchTerms: []
            },
            {
                title: "fi-rr-transparency",
                searchTerms: []
            },
            {
                title: "fi-rr-transporter",
                searchTerms: []
            },
            {
                title: "fi-rr-transporter-1",
                searchTerms: []
            },
            {
                title: "fi-rr-transporter-2",
                searchTerms: []
            },
            {
                title: "fi-rr-transporter-3",
                searchTerms: []
            },
            {
                title: "fi-rr-transporter-4",
                searchTerms: []
            },
            {
                title: "fi-rr-transporter-5",
                searchTerms: []
            },
            {
                title: "fi-rr-transporter-6",
                searchTerms: []
            },
            {
                title: "fi-rr-transporter-7",
                searchTerms: []
            },
            {
                title: "fi-rr-transporter-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-trash",
                searchTerms: []
            },
            {
                title: "fi-rr-trash-bag",
                searchTerms: []
            },
            {
                title: "fi-rr-trash-can-check",
                searchTerms: []
            },
            {
                title: "fi-rr-trash-can-clock",
                searchTerms: []
            },
            {
                title: "fi-rr-trash-can-list",
                searchTerms: []
            },
            {
                title: "fi-rr-trash-can-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-trash-can-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-trash-check",
                searchTerms: []
            },
            {
                title: "fi-rr-trash-clock",
                searchTerms: []
            },
            {
                title: "fi-rr-trash-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-trash-list",
                searchTerms: []
            },
            {
                title: "fi-rr-trash-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-trash-restore",
                searchTerms: []
            },
            {
                title: "fi-rr-trash-restore-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-trash-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-trash-undo",
                searchTerms: []
            },
            {
                title: "fi-rr-trash-undo-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-trash-xmark",
                searchTerms: []
            },
            {
                title: "fi-rr-treadmill",
                searchTerms: []
            },
            {
                title: "fi-rr-treasure-chest",
                searchTerms: []
            },
            {
                title: "fi-rr-treatment",
                searchTerms: []
            },
            {
                title: "fi-rr-tree",
                searchTerms: []
            },
            {
                title: "fi-rr-tree-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-tree-christmas",
                searchTerms: []
            },
            {
                title: "fi-rr-tree-deciduous",
                searchTerms: []
            },
            {
                title: "fi-rr-trees",
                searchTerms: []
            },
            {
                title: "fi-rr-trees-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-triangle",
                searchTerms: []
            },
            {
                title: "fi-rr-triangle-music",
                searchTerms: []
            },
            {
                title: "fi-rr-triangle-person-digging",
                searchTerms: []
            },
            {
                title: "fi-rr-triangle-warning",
                searchTerms: []
            },
            {
                title: "fi-rr-tricycle",
                searchTerms: []
            },
            {
                title: "fi-rr-trillium",
                searchTerms: []
            },
            {
                title: "fi-rr-troph-cap",
                searchTerms: []
            },
            {
                title: "fi-rr-trophy",
                searchTerms: []
            },
            {
                title: "fi-rr-trophy-achievement-skill",
                searchTerms: []
            },
            {
                title: "fi-rr-trophy-star",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-arrow-left",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-arrow-right",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-bolt",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-box",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-check",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-container",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-container-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-couch",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-droplet",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-fire",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-flatbed",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-front",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-ladder",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-loading",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-medical",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-monster",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-moving",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-pickup",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-plow",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-ramp",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-side",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-tow",
                searchTerms: []
            },
            {
                title: "fi-rr-truck-utensils",
                searchTerms: []
            },
            {
                title: "fi-rr-trumpet",
                searchTerms: []
            },
            {
                title: "fi-rr-trust",
                searchTerms: []
            },
            {
                title: "fi-rr-trust-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-tshirt",
                searchTerms: []
            },
            {
                title: "fi-rr-tty",
                searchTerms: []
            },
            {
                title: "fi-rr-tty-answer",
                searchTerms: []
            },
            {
                title: "fi-rr-tubes",
                searchTerms: []
            },
            {
                title: "fi-rr-tuesday",
                searchTerms: []
            },
            {
                title: "fi-rr-tugrik-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-turkey",
                searchTerms: []
            },
            {
                title: "fi-rr-turn-left",
                searchTerms: []
            },
            {
                title: "fi-rr-turn-left-down",
                searchTerms: []
            },
            {
                title: "fi-rr-turn-right",
                searchTerms: []
            },
            {
                title: "fi-rr-turntable",
                searchTerms: []
            },
            {
                title: "fi-rr-turtle",
                searchTerms: []
            },
            {
                title: "fi-rr-tv-music",
                searchTerms: []
            },
            {
                title: "fi-rr-tv-retro",
                searchTerms: []
            },
            {
                title: "fi-rr-two-nails",
                searchTerms: []
            },
            {
                title: "fi-rr-two-swords",
                searchTerms: []
            },
            {
                title: "fi-rr-typewriter",
                searchTerms: []
            },
            {
                title: "fi-rr-u",
                searchTerms: []
            },
            {
                title: "fi-rr-ufo",
                searchTerms: []
            },
            {
                title: "fi-rr-ufo-beam",
                searchTerms: []
            },
            {
                title: "fi-rr-ui-ux",
                searchTerms: []
            },
            {
                title: "fi-rr-umbrella",
                searchTerms: []
            },
            {
                title: "fi-rr-umbrella-beach",
                searchTerms: []
            },
            {
                title: "fi-rr-under-construction",
                searchTerms: []
            },
            {
                title: "fi-rr-underline",
                searchTerms: []
            },
            {
                title: "fi-rr-undo",
                searchTerms: []
            },
            {
                title: "fi-rr-undo-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-unicorn",
                searchTerms: []
            },
            {
                title: "fi-rr-uniform-martial-arts",
                searchTerms: []
            },
            {
                title: "fi-rr-universal-access",
                searchTerms: []
            },
            {
                title: "fi-rr-unlock",
                searchTerms: []
            },
            {
                title: "fi-rr-up",
                searchTerms: []
            },
            {
                title: "fi-rr-up-from-bracket",
                searchTerms: []
            },
            {
                title: "fi-rr-up-left",
                searchTerms: []
            },
            {
                title: "fi-rr-up-right",
                searchTerms: []
            },
            {
                title: "fi-rr-up-right-from-square",
                searchTerms: []
            },
            {
                title: "fi-rr-upload",
                searchTerms: []
            },
            {
                title: "fi-rr-url",
                searchTerms: []
            },
            {
                title: "fi-rr-usa-map",
                searchTerms: []
            },
            {
                title: "fi-rr-usa-map-pin",
                searchTerms: []
            },
            {
                title: "fi-rr-usb-pendrive",
                searchTerms: []
            },
            {
                title: "fi-rr-usd-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-usd-square",
                searchTerms: []
            },
            {
                title: "fi-rr-user",
                searchTerms: []
            },
            {
                title: "fi-rr-user-add",
                searchTerms: []
            },
            {
                title: "fi-rr-user-alien",
                searchTerms: []
            },
            {
                title: "fi-rr-user-astronaut",
                searchTerms: []
            },
            {
                title: "fi-rr-user-check",
                searchTerms: []
            },
            {
                title: "fi-rr-user-chef",
                searchTerms: []
            },
            {
                title: "fi-rr-user-coach",
                searchTerms: []
            },
            {
                title: "fi-rr-user-cowboy",
                searchTerms: []
            },
            {
                title: "fi-rr-user-crown",
                searchTerms: []
            },
            {
                title: "fi-rr-user-dj",
                searchTerms: []
            },
            {
                title: "fi-rr-user-eating",
                searchTerms: []
            },
            {
                title: "fi-rr-user-experience",
                searchTerms: []
            },
            {
                title: "fi-rr-user-fast-running",
                searchTerms: []
            },
            {
                title: "fi-rr-user-forbidden",
                searchTerms: []
            },
            {
                title: "fi-rr-user-forbidden-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-user-gear",
                searchTerms: []
            },
            {
                title: "fi-rr-user-graduate",
                searchTerms: []
            },
            {
                title: "fi-rr-user-hard-work",
                searchTerms: []
            },
            {
                title: "fi-rr-user-headset",
                searchTerms: []
            },
            {
                title: "fi-rr-user-helmet-safety",
                searchTerms: []
            },
            {
                title: "fi-rr-user-india",
                searchTerms: []
            },
            {
                title: "fi-rr-user-injured",
                searchTerms: []
            },
            {
                title: "fi-rr-user-interface-ui",
                searchTerms: []
            },
            {
                title: "fi-rr-user-key",
                searchTerms: []
            },
            {
                title: "fi-rr-user-language",
                searchTerms: []
            },
            {
                title: "fi-rr-user-lock",
                searchTerms: []
            },
            {
                title: "fi-rr-user-md",
                searchTerms: []
            },
            {
                title: "fi-rr-user-md-chat",
                searchTerms: []
            },
            {
                title: "fi-rr-user-minus",
                searchTerms: []
            },
            {
                title: "fi-rr-user-music",
                searchTerms: []
            },
            {
                title: "fi-rr-user-ninja",
                searchTerms: []
            },
            {
                title: "fi-rr-user-nurse",
                searchTerms: []
            },
            {
                title: "fi-rr-user-pen",
                searchTerms: []
            },
            {
                title: "fi-rr-user-pilot",
                searchTerms: []
            },
            {
                title: "fi-rr-user-pilot-tie",
                searchTerms: []
            },
            {
                title: "fi-rr-user-police",
                searchTerms: []
            },
            {
                title: "fi-rr-user-roadmap",
                searchTerms: []
            },
            {
                title: "fi-rr-user-robot",
                searchTerms: []
            },
            {
                title: "fi-rr-user-robot-xmarks",
                searchTerms: []
            },
            {
                title: "fi-rr-user-salary",
                searchTerms: []
            },
            {
                title: "fi-rr-user-shield",
                searchTerms: []
            },
            {
                title: "fi-rr-user-skill-gear",
                searchTerms: []
            },
            {
                title: "fi-rr-user-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-user-speaking",
                searchTerms: []
            },
            {
                title: "fi-rr-user-stress",
                searchTerms: []
            },
            {
                title: "fi-rr-user-suitcase",
                searchTerms: []
            },
            {
                title: "fi-rr-user-tag",
                searchTerms: []
            },
            {
                title: "fi-rr-user-time",
                searchTerms: []
            },
            {
                title: "fi-rr-user-trust",
                searchTerms: []
            },
            {
                title: "fi-rr-user-unlock",
                searchTerms: []
            },
            {
                title: "fi-rr-user-visor",
                searchTerms: []
            },
            {
                title: "fi-rr-user-volunteer",
                searchTerms: []
            },
            {
                title: "fi-rr-user-xmark",
                searchTerms: []
            },
            {
                title: "fi-rr-users",
                searchTerms: []
            },
            {
                title: "fi-rr-users-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-users-class",
                searchTerms: []
            },
            {
                title: "fi-rr-users-gear",
                searchTerms: []
            },
            {
                title: "fi-rr-users-loyalty",
                searchTerms: []
            },
            {
                title: "fi-rr-users-medical",
                searchTerms: []
            },
            {
                title: "fi-rr-users-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-utensils",
                searchTerms: []
            },
            {
                title: "fi-rr-utensils-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-utility-pole",
                searchTerms: []
            },
            {
                title: "fi-rr-utility-pole-double",
                searchTerms: []
            },
            {
                title: "fi-rr-ux",
                searchTerms: []
            },
            {
                title: "fi-rr-ux-browser",
                searchTerms: []
            },
            {
                title: "fi-rr-v",
                searchTerms: []
            },
            {
                title: "fi-rr-vacuum",
                searchTerms: []
            },
            {
                title: "fi-rr-vacuum-robot",
                searchTerms: []
            },
            {
                title: "fi-rr-value-absolute",
                searchTerms: []
            },
            {
                title: "fi-rr-vault",
                searchTerms: []
            },
            {
                title: "fi-rr-vector",
                searchTerms: []
            },
            {
                title: "fi-rr-vector-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-vector-circle",
                searchTerms: []
            },
            {
                title: "fi-rr-vector-polygon",
                searchTerms: []
            },
            {
                title: "fi-rr-venus",
                searchTerms: []
            },
            {
                title: "fi-rr-venus-double",
                searchTerms: []
            },
            {
                title: "fi-rr-venus-mars",
                searchTerms: []
            },
            {
                title: "fi-rr-vest",
                searchTerms: []
            },
            {
                title: "fi-rr-vest-patches",
                searchTerms: []
            },
            {
                title: "fi-rr-video-arrow-down-left",
                searchTerms: []
            },
            {
                title: "fi-rr-video-arrow-up-right",
                searchTerms: []
            },
            {
                title: "fi-rr-video-camera",
                searchTerms: []
            },
            {
                title: "fi-rr-video-camera-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-video-duration",
                searchTerms: []
            },
            {
                title: "fi-rr-video-plus",
                searchTerms: []
            },
            {
                title: "fi-rr-video-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-videoconference",
                searchTerms: []
            },
            {
                title: "fi-rr-vihara",
                searchTerms: []
            },
            {
                title: "fi-rr-violin",
                searchTerms: []
            },
            {
                title: "fi-rr-virus",
                searchTerms: []
            },
            {
                title: "fi-rr-virus-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-viruses",
                searchTerms: []
            },
            {
                title: "fi-rr-vision",
                searchTerms: []
            },
            {
                title: "fi-rr-vision-sense",
                searchTerms: []
            },
            {
                title: "fi-rr-vision-target",
                searchTerms: []
            },
            {
                title: "fi-rr-visit",
                searchTerms: []
            },
            {
                title: "fi-rr-voicemail",
                searchTerms: []
            },
            {
                title: "fi-rr-volcano",
                searchTerms: []
            },
            {
                title: "fi-rr-volleyball",
                searchTerms: []
            },
            {
                title: "fi-rr-volume",
                searchTerms: []
            },
            {
                title: "fi-rr-volume-down",
                searchTerms: []
            },
            {
                title: "fi-rr-volume-mute",
                searchTerms: []
            },
            {
                title: "fi-rr-volume-off",
                searchTerms: []
            },
            {
                title: "fi-rr-volume-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-volunteer-vest",
                searchTerms: []
            },
            {
                title: "fi-rr-vote-nay",
                searchTerms: []
            },
            {
                title: "fi-rr-vote-yea",
                searchTerms: []
            },
            {
                title: "fi-rr-vpn",
                searchTerms: []
            },
            {
                title: "fi-rr-vpn-globe",
                searchTerms: []
            },
            {
                title: "fi-rr-vpn-laptop",
                searchTerms: []
            },
            {
                title: "fi-rr-vpn-shield",
                searchTerms: []
            },
            {
                title: "fi-rr-vr-cardboard",
                searchTerms: []
            },
            {
                title: "fi-rr-w",
                searchTerms: []
            },
            {
                title: "fi-rr-waffle",
                searchTerms: []
            },
            {
                title: "fi-rr-wagon-covered",
                searchTerms: []
            },
            {
                title: "fi-rr-walker",
                searchTerms: []
            },
            {
                title: "fi-rr-walkie-talkie",
                searchTerms: []
            },
            {
                title: "fi-rr-walking",
                searchTerms: []
            },
            {
                title: "fi-rr-wallet",
                searchTerms: []
            },
            {
                title: "fi-rr-wallet-arrow",
                searchTerms: []
            },
            {
                title: "fi-rr-wallet-buyer",
                searchTerms: []
            },
            {
                title: "fi-rr-wallet-income",
                searchTerms: []
            },
            {
                title: "fi-rr-wallet-nft",
                searchTerms: []
            },
            {
                title: "fi-rr-warehouse-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-warranty",
                searchTerms: []
            },
            {
                title: "fi-rr-washer",
                searchTerms: []
            },
            {
                title: "fi-rr-waste",
                searchTerms: []
            },
            {
                title: "fi-rr-waste-pollution",
                searchTerms: []
            },
            {
                title: "fi-rr-watch",
                searchTerms: []
            },
            {
                title: "fi-rr-watch-calculator",
                searchTerms: []
            },
            {
                title: "fi-rr-watch-fitness",
                searchTerms: []
            },
            {
                title: "fi-rr-watch-smart",
                searchTerms: []
            },
            {
                title: "fi-rr-water",
                searchTerms: []
            },
            {
                title: "fi-rr-water-bottle",
                searchTerms: []
            },
            {
                title: "fi-rr-water-ladder",
                searchTerms: []
            },
            {
                title: "fi-rr-water-lower",
                searchTerms: []
            },
            {
                title: "fi-rr-water-rise",
                searchTerms: []
            },
            {
                title: "fi-rr-watermelon",
                searchTerms: []
            },
            {
                title: "fi-rr-wave",
                searchTerms: []
            },
            {
                title: "fi-rr-wave-sine",
                searchTerms: []
            },
            {
                title: "fi-rr-wave-square",
                searchTerms: []
            },
            {
                title: "fi-rr-wave-triangle",
                searchTerms: []
            },
            {
                title: "fi-rr-waveform",
                searchTerms: []
            },
            {
                title: "fi-rr-waveform-path",
                searchTerms: []
            },
            {
                title: "fi-rr-web-design",
                searchTerms: []
            },
            {
                title: "fi-rr-web-test",
                searchTerms: []
            },
            {
                title: "fi-rr-webcam",
                searchTerms: []
            },
            {
                title: "fi-rr-webcam-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-webhook",
                searchTerms: []
            },
            {
                title: "fi-rr-wednesday",
                searchTerms: []
            },
            {
                title: "fi-rr-whale",
                searchTerms: []
            },
            {
                title: "fi-rr-wheat",
                searchTerms: []
            },
            {
                title: "fi-rr-wheat-awn",
                searchTerms: []
            },
            {
                title: "fi-rr-wheat-awn-circle-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-wheat-awn-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-wheat-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-wheelchair",
                searchTerms: []
            },
            {
                title: "fi-rr-wheelchair-move",
                searchTerms: []
            },
            {
                title: "fi-rr-wheelchair-sleeping",
                searchTerms: []
            },
            {
                title: "fi-rr-whistle",
                searchTerms: []
            },
            {
                title: "fi-rr-white-space",
                searchTerms: []
            },
            {
                title: "fi-rr-wifi",
                searchTerms: []
            },
            {
                title: "fi-rr-wifi-1",
                searchTerms: []
            },
            {
                title: "fi-rr-wifi-2",
                searchTerms: []
            },
            {
                title: "fi-rr-wifi-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-wifi-exclamation",
                searchTerms: []
            },
            {
                title: "fi-rr-wifi-slash",
                searchTerms: []
            },
            {
                title: "fi-rr-wifi-xmark",
                searchTerms: []
            },
            {
                title: "fi-rr-wind",
                searchTerms: []
            },
            {
                title: "fi-rr-wind-turbine",
                searchTerms: []
            },
            {
                title: "fi-rr-wind-warning",
                searchTerms: []
            },
            {
                title: "fi-rr-window-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-window-frame",
                searchTerms: []
            },
            {
                title: "fi-rr-window-frame-open",
                searchTerms: []
            },
            {
                title: "fi-rr-window-maximize",
                searchTerms: []
            },
            {
                title: "fi-rr-window-minimize",
                searchTerms: []
            },
            {
                title: "fi-rr-window-restore",
                searchTerms: []
            },
            {
                title: "fi-rr-windsock",
                searchTerms: []
            },
            {
                title: "fi-rr-wine-bottle",
                searchTerms: []
            },
            {
                title: "fi-rr-wine-glass-crack",
                searchTerms: []
            },
            {
                title: "fi-rr-wine-glass-empty",
                searchTerms: []
            },
            {
                title: "fi-rr-wisdom",
                searchTerms: []
            },
            {
                title: "fi-rr-wishlist-heart",
                searchTerms: []
            },
            {
                title: "fi-rr-wishlist-star",
                searchTerms: []
            },
            {
                title: "fi-rr-woman-head",
                searchTerms: []
            },
            {
                title: "fi-rr-woman-scientist",
                searchTerms: []
            },
            {
                title: "fi-rr-won-sign",
                searchTerms: []
            },
            {
                title: "fi-rr-work-in-progress",
                searchTerms: []
            },
            {
                title: "fi-rr-workflow",
                searchTerms: []
            },
            {
                title: "fi-rr-workflow-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-workflow-setting",
                searchTerms: []
            },
            {
                title: "fi-rr-workflow-setting-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-workshop",
                searchTerms: []
            },
            {
                title: "fi-rr-world",
                searchTerms: []
            },
            {
                title: "fi-rr-worldwide-network",
                searchTerms: []
            },
            {
                title: "fi-rr-worm",
                searchTerms: []
            },
            {
                title: "fi-rr-wreath",
                searchTerms: []
            },
            {
                title: "fi-rr-wrench-alt",
                searchTerms: []
            },
            {
                title: "fi-rr-wrench-simple",
                searchTerms: []
            },
            {
                title: "fi-rr-x",
                searchTerms: []
            },
            {
                title: "fi-rr-x-ray",
                searchTerms: []
            },
            {
                title: "fi-rr-y",
                searchTerms: []
            },
            {
                title: "fi-rr-yen",
                searchTerms: []
            },
            {
                title: "fi-rr-yin-yang",
                searchTerms: []
            },
            {
                title: "fi-rr-yoga-mat",
                searchTerms: []
            },
            {
                title: "fi-rr-yoga-moon",
                searchTerms: []
            },
            {
                title: "fi-rr-yoga-posture",
                searchTerms: []
            },
            {
                title: "fi-rr-z",
                searchTerms: []
            },
            {
                title: "fi-rr-zero-percent",
                searchTerms: []
            },
            {
                title: "fi-rr-zip-file",
                searchTerms: []
            },
            {
                title: "fi-rr-zoom-in",
                searchTerms: []
            },
            {
                title: "fi-rr-zoom-out",
                searchTerms: []
            },
            {
                title: "fi-rr-it-computer",
                searchTerms: []
            },
            {
                title: "fi-rr-pinata",
                searchTerms: []
            },
            
        ]
    });
});