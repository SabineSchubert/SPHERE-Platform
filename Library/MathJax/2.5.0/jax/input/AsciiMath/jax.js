/*
 *  /MathJax/jax/input/AsciiMath/jax.js
 *
 *  Copyright (c) 2009-2015 The MathJax Consortium
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

(function( ac )
{
    var g;
    var X = MathJax.Object.Subclass( {
        firstChild: null, lastChild: null, Init: function()
        {
            this.childNodes = []
        }, appendChild: function( i )
        {
            if (i.parent) {
                i.parent.removeChild( i )
            }
            if (this.lastChild) {
                this.lastChild.nextSibling = i
            }
            if (!this.firstChild) {
                this.firstChild = i
            }
            this.childNodes.push( i );
            i.parent = this;
            this.lastChild = i;
            return i
        }, removeChild: function( af )
        {
            for (var ae = 0, ad = this.childNodes.length; ae < ad; ae++) {
                if (this.childNodes[ae] === af) {
                    break
                }
            }
            if (ae === ad) {
                return
            }
            this.childNodes.splice( ae, 1 );
            if (af === this.firstChild) {
                this.firstChild = af.nextSibling
            }
            if (af === this.lastChild) {
                if (!this.childNodes.length) {
                    this.lastChild = null
                } else {
                    this.lastChild = this.childNodes[this.childNodes.length - 1]
                }
            }
            if (ae) {
                this.childNodes[ae - 1].nextSibling = af.nextSibling
            }
            af.nextSibling = af.parent = null;
            return af
        }, replaceChild: function( ag, ae )
        {
            for (var af = 0, ad = this.childNodes.length; af < ad; af++) {
                if (this.childNodes[af] === ae) {
                    break
                }
            }
            if (af) {
                this.childNodes[af - 1].nextSibling = ag
            } else {
                this.firstChild = ag
            }
            if (af >= ad - 1) {
                this.lastChild = ag
            }
            this.childNodes[af] = ag;
            ag.nextSibling = ae.nextSibling;
            ae.nextSibling = ae.parent = null;
            return ae
        }, hasChildNodes: function( i )
        {
            return (this.childNodes.length > 0)
        }, toString: function()
        {
            return "{" + this.childNodes.join( "" ) + "}"
        }
    } );
    var x = function()
    {
        g = MathJax.ElementJax.mml;
        var i = g.mbase.prototype.Init;
        g.mbase.Augment( {
            firstChild: null, lastChild: null, nodeValue: null, nextSibling: null, Init: function()
            {
                var ad = i.apply( this, arguments ) || this;
                ad.childNodes = ad.data;
                ad.nodeName = ad.type;
                return ad
            }, appendChild: function( ag )
            {
                if (ag.parent) {
                    ag.parent.removeChild( ag )
                }
                var ae = arguments;
                if (ag.isa( X )) {
                    ae = ag.childNodes;
                    ag.data = ag.childNodes = [];
                    ag.firstChild = ag.lastChild = null
                }
                for (var af = 0, ad = ae.length; af < ad; af++) {
                    ag = ae[af];
                    if (this.lastChild) {
                        this.lastChild.nextSibling = ag
                    }
                    if (!this.firstChild) {
                        this.firstChild = ag
                    }
                    this.Append( ag );
                    this.lastChild = ag
                }
                return ag
            }, removeChild: function( af )
            {
                for (var ae = 0, ad = this.childNodes.length; ae < ad; ae++) {
                    if (this.childNodes[ae] === af) {
                        break
                    }
                }
                if (ae === ad) {
                    return
                }
                this.childNodes.splice( ae, 1 );
                if (af === this.firstChild) {
                    this.firstChild = af.nextSibling
                }
                if (af === this.lastChild) {
                    if (!this.childNodes.length) {
                        this.lastChild = null
                    } else {
                        this.lastChild = this.childNodes[this.childNodes.length - 1]
                    }
                }
                if (ae) {
                    this.childNodes[ae - 1].nextSibling = af.nextSibling
                }
                af.nextSibling = af.parent = null;
                return af
            }, replaceChild: function( ag, ae )
            {
                for (var af = 0, ad = this.childNodes.length; af < ad; af++) {
                    if (this.childNodes[af] === ae) {
                        break
                    }
                }
                if (af) {
                    this.childNodes[af - 1].nextSibling = ag
                } else {
                    this.firstChild = ag
                }
                if (af >= ad - 1) {
                    this.lastChild = ag
                }
                this.SetData( af, ag );
                ag.nextSibling = ae.nextSibling;
                ae.nextSibling = ae.parent = null;
                return ae
            }, hasChildNodes: function( ad )
            {
                return (this.childNodes.length > 0)
            }, setAttribute: function( ad, ae )
            {
                this[ad] = ae
            }
        } )
    };
    var Q = {};
    var e = {
        getElementById: true, createElementNS: function( ad, i )
        {
            var ae = g[i]();
            if (i === "mo" && ac.config.useMathMLspacing) {
                ae.useMMLspacing = 128
            }
            return ae
        }, createTextNode: function( i )
        {
            return g.chars( i ).With( {nodeValue: i} )
        }, createDocumentFragment: function()
        {
            return X()
        }
    };
    var J = {appName: "MathJax"};
    var Z;
    var C = "blue";
    var aa = "serif";
    var p = true;
    var v = true;
    var d = ".";
    var f = true;
    var m = (J.appName.slice( 0, 9 ) == "Microsoft");

    function E( i )
    {
        if (m) {
            return e.createElement( i )
        } else {
            return e.createElementNS( "http://www.w3.org/1999/xhtml", i )
        }
    }

    var W = "http://www.w3.org/1998/Math/MathML";

    function P( i )
    {
        if (m) {
            return e.createElement( "m:" + i )
        } else {
            return e.createElementNS( W, i )
        }
    }

    function O( i, ae )
    {
        var ad;
        if (m) {
            ad = e.createElement( "m:" + i )
        } else {
            ad = e.createElementNS( W, i )
        }
        if (ae) {
            ad.appendChild( ae )
        }
        return ad
    }

    function u( i, ad )
    {
        z = z.concat( [{input: i, tag: "mo", output: ad, tex: null, ttype: V}] );
        z.sort( T );
        for (Z = 0; Z < z.length; Z++) {
            S[Z] = z[Z].input
        }
    }

    var D = [
        "\uD835\uDC9C",
        "\u212C",
        "\uD835\uDC9E",
        "\uD835\uDC9F",
        "\u2130",
        "\u2131",
        "\uD835\uDCA2",
        "\u210B",
        "\u2110",
        "\uD835\uDCA5",
        "\uD835\uDCA6",
        "\u2112",
        "\u2133",
        "\uD835\uDCA9",
        "\uD835\uDCAA",
        "\uD835\uDCAB",
        "\uD835\uDCAC",
        "\u211B",
        "\uD835\uDCAE",
        "\uD835\uDCAF",
        "\uD835\uDCB0",
        "\uD835\uDCB1",
        "\uD835\uDCB2",
        "\uD835\uDCB3",
        "\uD835\uDCB4",
        "\uD835\uDCB5",
        "\uD835\uDCB6",
        "\uD835\uDCB7",
        "\uD835\uDCB8",
        "\uD835\uDCB9",
        "\u212F",
        "\uD835\uDCBB",
        "\u210A",
        "\uD835\uDCBD",
        "\uD835\uDCBE",
        "\uD835\uDCBF",
        "\uD835\uDCC0",
        "\uD835\uDCC1",
        "\uD835\uDCC2",
        "\uD835\uDCC3",
        "\u2134",
        "\uD835\uDCC5",
        "\uD835\uDCC6",
        "\uD835\uDCC7",
        "\uD835\uDCC8",
        "\uD835\uDCC9",
        "\uD835\uDCCA",
        "\uD835\uDCCB",
        "\uD835\uDCCC",
        "\uD835\uDCCD",
        "\uD835\uDCCE",
        "\uD835\uDCCF"
    ];
    var H = [
        "\uD835\uDD04",
        "\uD835\uDD05",
        "\u212D",
        "\uD835\uDD07",
        "\uD835\uDD08",
        "\uD835\uDD09",
        "\uD835\uDD0A",
        "\u210C",
        "\u2111",
        "\uD835\uDD0D",
        "\uD835\uDD0E",
        "\uD835\uDD0F",
        "\uD835\uDD10",
        "\uD835\uDD11",
        "\uD835\uDD12",
        "\uD835\uDD13",
        "\uD835\uDD14",
        "\u211C",
        "\uD835\uDD16",
        "\uD835\uDD17",
        "\uD835\uDD18",
        "\uD835\uDD19",
        "\uD835\uDD1A",
        "\uD835\uDD1B",
        "\uD835\uDD1C",
        "\u2128",
        "\uD835\uDD1E",
        "\uD835\uDD1F",
        "\uD835\uDD20",
        "\uD835\uDD21",
        "\uD835\uDD22",
        "\uD835\uDD23",
        "\uD835\uDD24",
        "\uD835\uDD25",
        "\uD835\uDD26",
        "\uD835\uDD27",
        "\uD835\uDD28",
        "\uD835\uDD29",
        "\uD835\uDD2A",
        "\uD835\uDD2B",
        "\uD835\uDD2C",
        "\uD835\uDD2D",
        "\uD835\uDD2E",
        "\uD835\uDD2F",
        "\uD835\uDD30",
        "\uD835\uDD31",
        "\uD835\uDD32",
        "\uD835\uDD33",
        "\uD835\uDD34",
        "\uD835\uDD35",
        "\uD835\uDD36",
        "\uD835\uDD37"
    ];
    var w = [
        "\uD835\uDD38",
        "\uD835\uDD39",
        "\u2102",
        "\uD835\uDD3B",
        "\uD835\uDD3C",
        "\uD835\uDD3D",
        "\uD835\uDD3E",
        "\u210D",
        "\uD835\uDD40",
        "\uD835\uDD41",
        "\uD835\uDD42",
        "\uD835\uDD43",
        "\uD835\uDD44",
        "\u2115",
        "\uD835\uDD46",
        "\u2119",
        "\u211A",
        "\u211D",
        "\uD835\uDD4A",
        "\uD835\uDD4B",
        "\uD835\uDD4C",
        "\uD835\uDD4D",
        "\uD835\uDD4E",
        "\uD835\uDD4F",
        "\uD835\uDD50",
        "\u2124",
        "\uD835\uDD52",
        "\uD835\uDD53",
        "\uD835\uDD54",
        "\uD835\uDD55",
        "\uD835\uDD56",
        "\uD835\uDD57",
        "\uD835\uDD58",
        "\uD835\uDD59",
        "\uD835\uDD5A",
        "\uD835\uDD5B",
        "\uD835\uDD5C",
        "\uD835\uDD5D",
        "\uD835\uDD5E",
        "\uD835\uDD5F",
        "\uD835\uDD60",
        "\uD835\uDD61",
        "\uD835\uDD62",
        "\uD835\uDD63",
        "\uD835\uDD64",
        "\uD835\uDD65",
        "\uD835\uDD66",
        "\uD835\uDD67",
        "\uD835\uDD68",
        "\uD835\uDD69",
        "\uD835\uDD6A",
        "\uD835\uDD6B"
    ];
    var c = 0, A = 1, U = 2, j = 3, b = 4, h = 5, a = 6, L = 7, V = 8, n = 9, Y = 10, K = 15;
    var l = {input: '"', tag: "mtext", output: "mbox", tex: null, ttype: Y};
    var z = [
        {
            input: "alpha",
            tag: "mi",
            output: "\u03B1",
            tex: null,
            ttype: c
        },
        {
            input: "beta",
            tag: "mi",
            output: "\u03B2",
            tex: null,
            ttype: c
        },
        {
            input: "chi",
            tag: "mi",
            output: "\u03C7",
            tex: null,
            ttype: c
        },
        {
            input: "delta",
            tag: "mi",
            output: "\u03B4",
            tex: null,
            ttype: c
        },
        {
            input: "Delta",
            tag: "mo",
            output: "\u0394",
            tex: null,
            ttype: c
        },
        {
            input: "epsi",
            tag: "mi",
            output: "\u03B5",
            tex: "epsilon",
            ttype: c
        },
        {
            input: "varepsilon",
            tag: "mi",
            output: "\u025B",
            tex: null,
            ttype: c
        },
        {
            input: "eta",
            tag: "mi",
            output: "\u03B7",
            tex: null,
            ttype: c
        },
        {
            input: "gamma",
            tag: "mi",
            output: "\u03B3",
            tex: null,
            ttype: c
        },
        {
            input: "Gamma",
            tag: "mo",
            output: "\u0393",
            tex: null,
            ttype: c
        },
        {
            input: "iota",
            tag: "mi",
            output: "\u03B9",
            tex: null,
            ttype: c
        },
        {
            input: "kappa",
            tag: "mi",
            output: "\u03BA",
            tex: null,
            ttype: c
        },
        {
            input: "lambda",
            tag: "mi",
            output: "\u03BB",
            tex: null,
            ttype: c
        },
        {
            input: "Lambda",
            tag: "mo",
            output: "\u039B",
            tex: null,
            ttype: c
        },
        {
            input: "lamda",
            tag: "mi",
            output: "\u03BB",
            tex: null,
            ttype: c
        },
        {
            input: "Lamda",
            tag: "mo",
            output: "\u039B",
            tex: null,
            ttype: c
        },
        {
            input: "mu",
            tag: "mi",
            output: "\u03BC",
            tex: null,
            ttype: c
        },
        {
            input: "nu",
            tag: "mi",
            output: "\u03BD",
            tex: null,
            ttype: c
        },
        {
            input: "omega",
            tag: "mi",
            output: "\u03C9",
            tex: null,
            ttype: c
        },
        {
            input: "Omega",
            tag: "mo",
            output: "\u03A9",
            tex: null,
            ttype: c
        },
        {
            input: "phi",
            tag: "mi",
            output: f ? "\u03D5" : "\u03C6",
            tex: null,
            ttype: c
        },
        {
            input: "varphi",
            tag: "mi",
            output: f ? "\u03C6" : "\u03D5",
            tex: null,
            ttype: c
        },
        {
            input: "Phi",
            tag: "mo",
            output: "\u03A6",
            tex: null,
            ttype: c
        },
        {
            input: "pi",
            tag: "mi",
            output: "\u03C0",
            tex: null,
            ttype: c
        },
        {
            input: "Pi",
            tag: "mo",
            output: "\u03A0",
            tex: null,
            ttype: c
        },
        {
            input: "psi",
            tag: "mi",
            output: "\u03C8",
            tex: null,
            ttype: c
        },
        {
            input: "Psi",
            tag: "mi",
            output: "\u03A8",
            tex: null,
            ttype: c
        },
        {
            input: "rho",
            tag: "mi",
            output: "\u03C1",
            tex: null,
            ttype: c
        },
        {
            input: "sigma",
            tag: "mi",
            output: "\u03C3",
            tex: null,
            ttype: c
        },
        {
            input: "Sigma",
            tag: "mo",
            output: "\u03A3",
            tex: null,
            ttype: c
        },
        {
            input: "tau",
            tag: "mi",
            output: "\u03C4",
            tex: null,
            ttype: c
        },
        {
            input: "theta",
            tag: "mi",
            output: "\u03B8",
            tex: null,
            ttype: c
        },
        {
            input: "vartheta",
            tag: "mi",
            output: "\u03D1",
            tex: null,
            ttype: c
        },
        {
            input: "Theta",
            tag: "mo",
            output: "\u0398",
            tex: null,
            ttype: c
        },
        {
            input: "upsilon",
            tag: "mi",
            output: "\u03C5",
            tex: null,
            ttype: c
        },
        {
            input: "xi",
            tag: "mi",
            output: "\u03BE",
            tex: null,
            ttype: c
        },
        {
            input: "Xi",
            tag: "mo",
            output: "\u039E",
            tex: null,
            ttype: c
        },
        {
            input: "zeta",
            tag: "mi",
            output: "\u03B6",
            tex: null,
            ttype: c
        },
        {
            input: "*",
            tag: "mo",
            output: "\u22C5",
            tex: "cdot",
            ttype: c
        },
        {
            input: "**",
            tag: "mo",
            output: "\u2217",
            tex: "ast",
            ttype: c
        },
        {
            input: "***",
            tag: "mo",
            output: "\u22C6",
            tex: "star",
            ttype: c
        },
        {
            input: "//",
            tag: "mo",
            output: "/",
            tex: null,
            ttype: c
        },
        {
            input: "\\\\",
            tag: "mo",
            output: "\\",
            tex: "backslash",
            ttype: c
        },
        {
            input: "setminus",
            tag: "mo",
            output: "\\",
            tex: null,
            ttype: c
        },
        {
            input: "xx",
            tag: "mo",
            output: "\u00D7",
            tex: "times",
            ttype: c
        },
        {
            input: "-:",
            tag: "mo",
            output: "\u00F7",
            tex: "div",
            ttype: c
        },
        {
            input: "divide",
            tag: "mo",
            output: "-:",
            tex: null,
            ttype: V
        },
        {
            input: "@",
            tag: "mo",
            output: "\u2218",
            tex: "circ",
            ttype: c
        },
        {
            input: "o+",
            tag: "mo",
            output: "\u2295",
            tex: "oplus",
            ttype: c
        },
        {
            input: "ox",
            tag: "mo",
            output: "\u2297",
            tex: "otimes",
            ttype: c
        },
        {
            input: "o.",
            tag: "mo",
            output: "\u2299",
            tex: "odot",
            ttype: c
        },
        {
            input: "sum",
            tag: "mo",
            output: "\u2211",
            tex: null,
            ttype: L
        },
        {
            input: "prod",
            tag: "mo",
            output: "\u220F",
            tex: null,
            ttype: L
        },
        {
            input: "^^",
            tag: "mo",
            output: "\u2227",
            tex: "wedge",
            ttype: c
        },
        {
            input: "^^^",
            tag: "mo",
            output: "\u22C0",
            tex: "bigwedge",
            ttype: L
        },
        {
            input: "vv",
            tag: "mo",
            output: "\u2228",
            tex: "vee",
            ttype: c
        },
        {
            input: "vvv",
            tag: "mo",
            output: "\u22C1",
            tex: "bigvee",
            ttype: L
        },
        {
            input: "nn",
            tag: "mo",
            output: "\u2229",
            tex: "cap",
            ttype: c
        },
        {
            input: "nnn",
            tag: "mo",
            output: "\u22C2",
            tex: "bigcap",
            ttype: L
        },
        {
            input: "uu",
            tag: "mo",
            output: "\u222A",
            tex: "cup",
            ttype: c
        },
        {
            input: "uuu",
            tag: "mo",
            output: "\u22C3",
            tex: "bigcup",
            ttype: L
        },
        {
            input: "!=",
            tag: "mo",
            output: "\u2260",
            tex: "ne",
            ttype: c
        },
        {
            input: ":=",
            tag: "mo",
            output: ":=",
            tex: null,
            ttype: c
        },
        {
            input: "lt",
            tag: "mo",
            output: "<",
            tex: null,
            ttype: c
        },
        {
            input: "<=",
            tag: "mo",
            output: "\u2264",
            tex: "le",
            ttype: c
        },
        {
            input: "lt=",
            tag: "mo",
            output: "\u2264",
            tex: "leq",
            ttype: c
        },
        {
            input: "gt",
            tag: "mo",
            output: ">",
            tex: null,
            ttype: c
        },
        {
            input: ">=",
            tag: "mo",
            output: "\u2265",
            tex: "ge",
            ttype: c
        },
        {
            input: "gt=",
            tag: "mo",
            output: "\u2265",
            tex: "geq",
            ttype: c
        },
        {
            input: "-<",
            tag: "mo",
            output: "\u227A",
            tex: "prec",
            ttype: c
        },
        {
            input: "-lt",
            tag: "mo",
            output: "\u227A",
            tex: null,
            ttype: c
        },
        {
            input: ">-",
            tag: "mo",
            output: "\u227B",
            tex: "succ",
            ttype: c
        },
        {
            input: "-<=",
            tag: "mo",
            output: "\u2AAF",
            tex: "preceq",
            ttype: c
        },
        {
            input: ">-=",
            tag: "mo",
            output: "\u2AB0",
            tex: "succeq",
            ttype: c
        },
        {
            input: "in",
            tag: "mo",
            output: "\u2208",
            tex: null,
            ttype: c
        },
        {
            input: "!in",
            tag: "mo",
            output: "\u2209",
            tex: "notin",
            ttype: c
        },
        {
            input: "sub",
            tag: "mo",
            output: "\u2282",
            tex: "subset",
            ttype: c
        },
        {
            input: "sup",
            tag: "mo",
            output: "\u2283",
            tex: "supset",
            ttype: c
        },
        {
            input: "sube",
            tag: "mo",
            output: "\u2286",
            tex: "subseteq",
            ttype: c
        },
        {
            input: "supe",
            tag: "mo",
            output: "\u2287",
            tex: "supseteq",
            ttype: c
        },
        {
            input: "-=",
            tag: "mo",
            output: "\u2261",
            tex: "equiv",
            ttype: c
        },
        {
            input: "~=",
            tag: "mo",
            output: "\u2245",
            tex: "cong",
            ttype: c
        },
        {
            input: "~~",
            tag: "mo",
            output: "\u2248",
            tex: "approx",
            ttype: c
        },
        {
            input: "prop",
            tag: "mo",
            output: "\u221D",
            tex: "propto",
            ttype: c
        },
        {
            input: "and",
            tag: "mtext",
            output: "and",
            tex: null,
            ttype: a
        },
        {
            input: "or",
            tag: "mtext",
            output: "or",
            tex: null,
            ttype: a
        },
        {
            input: "not",
            tag: "mo",
            output: "\u00AC",
            tex: "neg",
            ttype: c
        },
        {
            input: "=>",
            tag: "mo",
            output: "\u21D2",
            tex: "implies",
            ttype: c
        },
        {
            input: "if",
            tag: "mo",
            output: "if",
            tex: null,
            ttype: a
        },
        {
            input: "<=>",
            tag: "mo",
            output: "\u21D4",
            tex: "iff",
            ttype: c
        },
        {
            input: "AA",
            tag: "mo",
            output: "\u2200",
            tex: "forall",
            ttype: c
        },
        {
            input: "EE",
            tag: "mo",
            output: "\u2203",
            tex: "exists",
            ttype: c
        },
        {
            input: "_|_",
            tag: "mo",
            output: "\u22A5",
            tex: "bot",
            ttype: c
        },
        {
            input: "TT",
            tag: "mo",
            output: "\u22A4",
            tex: "top",
            ttype: c
        },
        {
            input: "|--",
            tag: "mo",
            output: "\u22A2",
            tex: "vdash",
            ttype: c
        },
        {
            input: "|==",
            tag: "mo",
            output: "\u22A8",
            tex: "models",
            ttype: c
        },
        {
            input: "(",
            tag: "mo",
            output: "(",
            tex: null,
            ttype: b
        },
        {
            input: ")",
            tag: "mo",
            output: ")",
            tex: null,
            ttype: h
        },
        {
            input: "[",
            tag: "mo",
            output: "[",
            tex: null,
            ttype: b
        },
        {
            input: "]",
            tag: "mo",
            output: "]",
            tex: null,
            ttype: h
        },
        {
            input: "{",
            tag: "mo",
            output: "{",
            tex: null,
            ttype: b
        },
        {
            input: "}",
            tag: "mo",
            output: "}",
            tex: null,
            ttype: h
        },
        {
            input: "|",
            tag: "mo",
            output: "|",
            tex: null,
            ttype: n
        },
        {
            input: "(:",
            tag: "mo",
            output: "\u2329",
            tex: "langle",
            ttype: b
        },
        {
            input: ":)",
            tag: "mo",
            output: "\u232A",
            tex: "rangle",
            ttype: h
        },
        {
            input: "<<",
            tag: "mo",
            output: "\u2329",
            tex: null,
            ttype: b
        },
        {
            input: ">>",
            tag: "mo",
            output: "\u232A",
            tex: null,
            ttype: h
        },
        {
            input: "{:",
            tag: "mo",
            output: "{:",
            tex: null,
            ttype: b,
            invisible: true
        },
        {
            input: ":}",
            tag: "mo",
            output: ":}",
            tex: null,
            ttype: h,
            invisible: true
        },
        {
            input: "int",
            tag: "mo",
            output: "\u222B",
            tex: null,
            ttype: c
        },
        {
            input: "dx",
            tag: "mi",
            output: "{:d x:}",
            tex: null,
            ttype: V
        },
        {
            input: "dy",
            tag: "mi",
            output: "{:d y:}",
            tex: null,
            ttype: V
        },
        {
            input: "dz",
            tag: "mi",
            output: "{:d z:}",
            tex: null,
            ttype: V
        },
        {
            input: "dt",
            tag: "mi",
            output: "{:d t:}",
            tex: null,
            ttype: V
        },
        {
            input: "oint",
            tag: "mo",
            output: "\u222E",
            tex: null,
            ttype: c
        },
        {
            input: "del",
            tag: "mo",
            output: "\u2202",
            tex: "partial",
            ttype: c
        },
        {
            input: "grad",
            tag: "mo",
            output: "\u2207",
            tex: "nabla",
            ttype: c
        },
        {
            input: "+-",
            tag: "mo",
            output: "\u00B1",
            tex: "pm",
            ttype: c
        },
        {
            input: "O/",
            tag: "mo",
            output: "\u2205",
            tex: "emptyset",
            ttype: c
        },
        {
            input: "oo",
            tag: "mo",
            output: "\u221E",
            tex: "infty",
            ttype: c
        },
        {
            input: "aleph",
            tag: "mo",
            output: "\u2135",
            tex: null,
            ttype: c
        },
        {
            input: "...",
            tag: "mo",
            output: "...",
            tex: "ldots",
            ttype: c
        },
        {
            input: ":.",
            tag: "mo",
            output: "\u2234",
            tex: "therefore",
            ttype: c
        },
        {
            input: "/_",
            tag: "mo",
            output: "\u2220",
            tex: "angle",
            ttype: c
        },
        {
            input: "/_\\",
            tag: "mo",
            output: "\u25B3",
            tex: "triangle",
            ttype: c
        },
        {
            input: "'",
            tag: "mo",
            output: "\u2032",
            tex: "prime",
            ttype: c
        },
        {
            input: "tilde",
            tag: "mover",
            output: "~",
            tex: null,
            ttype: A,
            acc: true
        },
        {
            input: "\\ ",
            tag: "mo",
            output: "\u00A0",
            tex: null,
            ttype: c
        },
        {
            input: "quad",
            tag: "mo",
            output: "\u00A0\u00A0",
            tex: null,
            ttype: c
        },
        {
            input: "qquad",
            tag: "mo",
            output: "\u00A0\u00A0\u00A0\u00A0",
            tex: null,
            ttype: c
        },
        {
            input: "cdots",
            tag: "mo",
            output: "\u22EF",
            tex: null,
            ttype: c
        },
        {
            input: "vdots",
            tag: "mo",
            output: "\u22EE",
            tex: null,
            ttype: c
        },
        {
            input: "ddots",
            tag: "mo",
            output: "\u22F1",
            tex: null,
            ttype: c
        },
        {
            input: "diamond",
            tag: "mo",
            output: "\u22C4",
            tex: null,
            ttype: c
        },
        {
            input: "square",
            tag: "mo",
            output: "\u25A1",
            tex: null,
            ttype: c
        },
        {
            input: "|__",
            tag: "mo",
            output: "\u230A",
            tex: "lfloor",
            ttype: c
        },
        {
            input: "__|",
            tag: "mo",
            output: "\u230B",
            tex: "rfloor",
            ttype: c
        },
        {
            input: "|~",
            tag: "mo",
            output: "\u2308",
            tex: "lceiling",
            ttype: c
        },
        {
            input: "~|",
            tag: "mo",
            output: "\u2309",
            tex: "rceiling",
            ttype: c
        },
        {
            input: "CC",
            tag: "mo",
            output: "\u2102",
            tex: null,
            ttype: c
        },
        {
            input: "NN",
            tag: "mo",
            output: "\u2115",
            tex: null,
            ttype: c
        },
        {
            input: "QQ",
            tag: "mo",
            output: "\u211A",
            tex: null,
            ttype: c
        },
        {
            input: "RR",
            tag: "mo",
            output: "\u211D",
            tex: null,
            ttype: c
        },
        {
            input: "ZZ",
            tag: "mo",
            output: "\u2124",
            tex: null,
            ttype: c
        },
        {
            input: "f",
            tag: "mi",
            output: "f",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "g",
            tag: "mi",
            output: "g",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "lim",
            tag: "mo",
            output: "lim",
            tex: null,
            ttype: L
        },
        {
            input: "Lim",
            tag: "mo",
            output: "Lim",
            tex: null,
            ttype: L
        },
        {
            input: "sin",
            tag: "mo",
            output: "sin",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "cos",
            tag: "mo",
            output: "cos",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "tan",
            tag: "mo",
            output: "tan",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "sinh",
            tag: "mo",
            output: "sinh",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "cosh",
            tag: "mo",
            output: "cosh",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "tanh",
            tag: "mo",
            output: "tanh",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "cot",
            tag: "mo",
            output: "cot",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "sec",
            tag: "mo",
            output: "sec",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "csc",
            tag: "mo",
            output: "csc",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "arcsin",
            tag: "mo",
            output: "arcsin",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "arccos",
            tag: "mo",
            output: "arccos",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "arctan",
            tag: "mo",
            output: "arctan",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "coth",
            tag: "mo",
            output: "coth",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "sech",
            tag: "mo",
            output: "sech",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "csch",
            tag: "mo",
            output: "csch",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "exp",
            tag: "mo",
            output: "exp",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "abs",
            tag: "mo",
            output: "abs",
            tex: null,
            ttype: A,
            rewriteleftright: ["|", "|"]
        },
        {
            input: "norm",
            tag: "mo",
            output: "norm",
            tex: null,
            ttype: A,
            rewriteleftright: ["\u2225", "\u2225"]
        },
        {
            input: "floor",
            tag: "mo",
            output: "floor",
            tex: null,
            ttype: A,
            rewriteleftright: ["\u230A", "\u230B"]
        },
        {
            input: "ceil",
            tag: "mo",
            output: "ceil",
            tex: null,
            ttype: A,
            rewriteleftright: ["\u2308", "\u2309"]
        },
        {
            input: "log",
            tag: "mo",
            output: "log",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "ln",
            tag: "mo",
            output: "ln",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "det",
            tag: "mo",
            output: "det",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "dim",
            tag: "mo",
            output: "dim",
            tex: null,
            ttype: c
        },
        {
            input: "mod",
            tag: "mo",
            output: "mod",
            tex: null,
            ttype: c
        },
        {
            input: "gcd",
            tag: "mo",
            output: "gcd",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "lcm",
            tag: "mo",
            output: "lcm",
            tex: null,
            ttype: A,
            func: true
        },
        {
            input: "lub",
            tag: "mo",
            output: "lub",
            tex: null,
            ttype: c
        },
        {
            input: "glb",
            tag: "mo",
            output: "glb",
            tex: null,
            ttype: c
        },
        {
            input: "min",
            tag: "mo",
            output: "min",
            tex: null,
            ttype: L
        },
        {
            input: "max",
            tag: "mo",
            output: "max",
            tex: null,
            ttype: L
        },
        {
            input: "uarr",
            tag: "mo",
            output: "\u2191",
            tex: "uparrow",
            ttype: c
        },
        {
            input: "darr",
            tag: "mo",
            output: "\u2193",
            tex: "downarrow",
            ttype: c
        },
        {
            input: "rarr",
            tag: "mo",
            output: "\u2192",
            tex: "rightarrow",
            ttype: c
        },
        {
            input: "->",
            tag: "mo",
            output: "\u2192",
            tex: "to",
            ttype: c
        },
        {
            input: ">->",
            tag: "mo",
            output: "\u21A3",
            tex: "rightarrowtail",
            ttype: c
        },
        {
            input: "->>",
            tag: "mo",
            output: "\u21A0",
            tex: "twoheadrightarrow",
            ttype: c
        },
        {
            input: ">->>",
            tag: "mo",
            output: "\u2916",
            tex: "twoheadrightarrowtail",
            ttype: c
        },
        {
            input: "|->",
            tag: "mo",
            output: "\u21A6",
            tex: "mapsto",
            ttype: c
        },
        {
            input: "larr",
            tag: "mo",
            output: "\u2190",
            tex: "leftarrow",
            ttype: c
        },
        {
            input: "harr",
            tag: "mo",
            output: "\u2194",
            tex: "leftrightarrow",
            ttype: c
        },
        {
            input: "rArr",
            tag: "mo",
            output: "\u21D2",
            tex: "Rightarrow",
            ttype: c
        },
        {
            input: "lArr",
            tag: "mo",
            output: "\u21D0",
            tex: "Leftarrow",
            ttype: c
        },
        {
            input: "hArr",
            tag: "mo",
            output: "\u21D4",
            tex: "Leftrightarrow",
            ttype: c
        },
        {
            input: "sqrt",
            tag: "msqrt",
            output: "sqrt",
            tex: null,
            ttype: A
        },
        {
            input: "root",
            tag: "mroot",
            output: "root",
            tex: null,
            ttype: U
        },
        {
            input: "frac",
            tag: "mfrac",
            output: "/",
            tex: null,
            ttype: U
        },
        {
            input: "/",
            tag: "mfrac",
            output: "/",
            tex: null,
            ttype: j
        },
        {
            input: "stackrel",
            tag: "mover",
            output: "stackrel",
            tex: null,
            ttype: U
        },
        {
            input: "_",
            tag: "msub",
            output: "_",
            tex: null,
            ttype: j
        },
        {
            input: "^",
            tag: "msup",
            output: "^",
            tex: null,
            ttype: j
        },
        {
            input: "hat",
            tag: "mover",
            output: "\u005E",
            tex: null,
            ttype: A,
            acc: true
        },
        {
            input: "bar",
            tag: "mover",
            output: "\u00AF",
            tex: "overline",
            ttype: A,
            acc: true
        },
        {
            input: "vec",
            tag: "mover",
            output: "\u2192",
            tex: null,
            ttype: A,
            acc: true
        },
        {
            input: "dot",
            tag: "mover",
            output: ".",
            tex: null,
            ttype: A,
            acc: true
        },
        {
            input: "ddot",
            tag: "mover",
            output: "..",
            tex: null,
            ttype: A,
            acc: true
        },
        {
            input: "ul",
            tag: "munder",
            output: "\u0332",
            tex: "underline",
            ttype: A,
            acc: true
        },
        {
            input: "ubrace",
            tag: "munder",
            output: "\u23DF",
            tex: "underbrace",
            ttype: K,
            acc: true
        },
        {
            input: "obrace",
            tag: "mover",
            output: "\u23DE",
            tex: "overbrace",
            ttype: K,
            acc: true
        },
        {
            input: "text",
            tag: "mtext",
            output: "text",
            tex: null,
            ttype: Y
        },
        {
            input: "mbox",
            tag: "mtext",
            output: "mbox",
            tex: null,
            ttype: Y
        },
        {
            input: "color",
            tag: "mstyle",
            ttype: U
        },
        {
            input: "cancel",
            tag: "menclose",
            output: "cancel",
            tex: null,
            ttype: A
        },
        l,
        {
            input: "bb",
            tag: "mstyle",
            atname: "mathvariant",
            atval: "bold",
            output: "bb",
            tex: null,
            ttype: A
        },
        {
            input: "mathbf",
            tag: "mstyle",
            atname: "mathvariant",
            atval: "bold",
            output: "mathbf",
            tex: null,
            ttype: A
        },
        {
            input: "sf",
            tag: "mstyle",
            atname: "mathvariant",
            atval: "sans-serif",
            output: "sf",
            tex: null,
            ttype: A
        },
        {
            input: "mathsf",
            tag: "mstyle",
            atname: "mathvariant",
            atval: "sans-serif",
            output: "mathsf",
            tex: null,
            ttype: A
        },
        {
            input: "bbb",
            tag: "mstyle",
            atname: "mathvariant",
            atval: "double-struck",
            output: "bbb",
            tex: null,
            ttype: A,
            codes: w
        },
        {
            input: "mathbb",
            tag: "mstyle",
            atname: "mathvariant",
            atval: "double-struck",
            output: "mathbb",
            tex: null,
            ttype: A,
            codes: w
        },
        {
            input: "cc",
            tag: "mstyle",
            atname: "mathvariant",
            atval: "script",
            output: "cc",
            tex: null,
            ttype: A,
            codes: D
        },
        {
            input: "mathcal",
            tag: "mstyle",
            atname: "mathvariant",
            atval: "script",
            output: "mathcal",
            tex: null,
            ttype: A,
            codes: D
        },
        {
            input: "tt",
            tag: "mstyle",
            atname: "mathvariant",
            atval: "monospace",
            output: "tt",
            tex: null,
            ttype: A
        },
        {
            input: "mathtt",
            tag: "mstyle",
            atname: "mathvariant",
            atval: "monospace",
            output: "mathtt",
            tex: null,
            ttype: A
        },
        {
            input: "fr",
            tag: "mstyle",
            atname: "mathvariant",
            atval: "fraktur",
            output: "fr",
            tex: null,
            ttype: A,
            codes: H
        },
        {
            input: "mathfrak",
            tag: "mstyle",
            atname: "mathvariant",
            atval: "fraktur",
            output: "mathfrak",
            tex: null,
            ttype: A,
            codes: H
        }
    ];

    function T( ad, i )
    {
        if (ad.input > i.input) {
            return 1
        } else {
            return -1
        }
    }

    var S = [];

    function o()
    {
        var ae = [], ad;
        for (ad = 0; ad < z.length; ad++) {
            if (z[ad].tex) {
                ae[ae.length] = {
                    input: z[ad].tex,
                    tag: z[ad].tag,
                    output: z[ad].output,
                    ttype: z[ad].ttype,
                    acc: (z[ad].acc || false)
                }
            }
        }
        z = z.concat( ae );
        B()
    }

    function B()
    {
        var ad;
        z.sort( T );
        for (ad = 0; ad < z.length; ad++) {
            S[ad] = z[ad].input
        }
    }

    function I( i, ad )
    {
        z = z.concat( [{input: i, tag: "mo", output: ad, tex: null, ttype: V}] );
        B()
    }

    function q( af, ag )
    {
        var ad;
        if (af.charAt( ag ) == "\\" && af.charAt( ag + 1 ) != "\\" && af.charAt( ag + 1 ) != " ") {
            ad = af.slice( ag + 1 )
        } else {
            ad = af.slice( ag )
        }
        for (var ae = 0; ae < ad.length && ad.charCodeAt( ae ) <= 32; ae = ae + 1) {
        }
        return ad.slice( ae )
    }

    function N( ae, ah, ai )
    {
        if (ai == 0) {
            var ag, ad;
            ai = -1;
            ag = ae.length;
            while (ai + 1 < ag) {
                ad = (ai + ag) >> 1;
                if (ae[ad] < ah) {
                    ai = ad
                } else {
                    ag = ad
                }
            }
            return ag
        } else {
            for (var af = ai; af < ae.length && ae[af] < ah; af++) {
            }
        }
        return af
    }

    function k( aj )
    {
        var ad = 0;
        var ae = 0;
        var ag;
        var am;
        var al;
        var ah = "";
        var ai = true;
        for (var af = 1; af <= aj.length && ai; af++) {
            am = aj.slice( 0, af );
            ae = ad;
            ad = N( S, am, ae );
            if (ad < S.length && aj.slice( 0, S[ad].length ) == S[ad]) {
                ah = S[ad];
                ag = ad;
                af = ah.length
            }
            ai = ad < S.length && aj.slice( 0, S[ad].length ) >= S[ad]
        }
        s = y;
        if (ah != "") {
            y = z[ag].ttype;
            return z[ag]
        }
        y = c;
        ad = 1;
        am = aj.slice( 0, 1 );
        var ak = true;
        while ("0" <= am && am <= "9" && ad <= aj.length) {
            am = aj.slice( ad, ad + 1 );
            ad++
        }
        if (am == d) {
            am = aj.slice( ad, ad + 1 );
            if ("0" <= am && am <= "9") {
                ak = false;
                ad++;
                while ("0" <= am && am <= "9" && ad <= aj.length) {
                    am = aj.slice( ad, ad + 1 );
                    ad++
                }
            }
        }
        if ((ak && ad > 1) || ad > 2) {
            am = aj.slice( 0, ad - 1 );
            al = "mn"
        } else {
            ad = 2;
            am = aj.slice( 0, 1 );
            al = (("A" > am || am > "Z") && ("a" > am || am > "z") ? "mo" : "mi")
        }
        if (am == "-" && s == j) {
            y = j;
            return {input: am, tag: al, output: am, ttype: A, func: true}
        }
        return {input: am, tag: al, output: am, ttype: c}
    }

    function R( ad )
    {
        var i;
        if (!ad.hasChildNodes()) {
            return
        }
        if (ad.firstChild.hasChildNodes() && (ad.nodeName == "mrow" || ad.nodeName == "M:MROW")) {
            i = ad.firstChild.firstChild.nodeValue;
            if (i == "(" || i == "[" || i == "{") {
                ad.removeChild( ad.firstChild )
            }
        }
        if (ad.lastChild.hasChildNodes() && (ad.nodeName == "mrow" || ad.nodeName == "M:MROW")) {
            i = ad.lastChild.firstChild.nodeValue;
            if (i == ")" || i == "]" || i == "}") {
                ad.removeChild( ad.lastChild )
            }
        }
    }

    var F, s, y;

    function G( aj )
    {
        var af, ae, am, ah, al, ai = e.createDocumentFragment();
        aj = q( aj, 0 );
        af = k( aj );
        if (af == null || af.ttype == h && F > 0) {
            return [null, aj]
        }
        if (af.ttype == V) {
            aj = af.output + q( aj, af.input.length );
            af = k( aj )
        }
        switch (af.ttype) {
            case L:
            case c:
                aj = q( aj, af.input.length );
                return [O( af.tag, e.createTextNode( af.output ) ), aj];
            case b:
                F++;
                aj = q( aj, af.input.length );
                am = r( aj, true );
                F--;
                if (typeof af.invisible == "boolean" && af.invisible) {
                    ae = O( "mrow", am[0] )
                } else {
                    ae = O( "mo", e.createTextNode( af.output ) );
                    ae = O( "mrow", ae );
                    ae.appendChild( am[0] )
                }
                return [ae, am[1]];
            case Y:
                if (af != l) {
                    aj = q( aj, af.input.length )
                }
                if (aj.charAt( 0 ) == "{") {
                    ah = aj.indexOf( "}" )
                } else {
                    if (aj.charAt( 0 ) == "(") {
                        ah = aj.indexOf( ")" )
                    } else {
                        if (aj.charAt( 0 ) == "[") {
                            ah = aj.indexOf( "]" )
                        } else {
                            if (af == l) {
                                ah = aj.slice( 1 ).indexOf( '"' ) + 1
                            } else {
                                ah = 0
                            }
                        }
                    }
                }
                if (ah == -1) {
                    ah = aj.length
                }
                al = aj.slice( 1, ah );
                if (al.charAt( 0 ) == " ") {
                    ae = O( "mspace" );
                    ae.setAttribute( "width", "1ex" );
                    ai.appendChild( ae )
                }
                ai.appendChild( O( af.tag, e.createTextNode( al ) ) );
                if (al.charAt( al.length - 1 ) == " ") {
                    ae = O( "mspace" );
                    ae.setAttribute( "width", "1ex" );
                    ai.appendChild( ae )
                }
                aj = q( aj, ah + 1 );
                return [O( "mrow", ai ), aj];
            case K:
            case A:
                aj = q( aj, af.input.length );
                am = G( aj );
                if (am[0] == null) {
                    return [O( af.tag, e.createTextNode( af.output ) ), aj]
                }
                if (typeof af.func == "boolean" && af.func) {
                    al = aj.charAt( 0 );
                    if (al == "^" || al == "_" || al == "/" || al == "|" || al == "," || (af.input.length == 1 && af.input.match( /\w/ ) && al != "(")) {
                        return [O( af.tag, e.createTextNode( af.output ) ), aj]
                    } else {
                        ae = O( "mrow", O( af.tag, e.createTextNode( af.output ) ) );
                        ae.appendChild( am[0] );
                        return [ae, am[1]]
                    }
                }
                R( am[0] );
                if (af.input == "sqrt") {
                    return [O( af.tag, am[0] ), am[1]]
                } else {
                    if (typeof af.rewriteleftright != "undefined") {
                        ae = O( "mrow", O( "mo", e.createTextNode( af.rewriteleftright[0] ) ) );
                        ae.appendChild( am[0] );
                        ae.appendChild( O( "mo", e.createTextNode( af.rewriteleftright[1] ) ) );
                        return [ae, am[1]]
                    } else {
                        if (af.input == "cancel") {
                            ae = O( af.tag, am[0] );
                            ae.setAttribute( "notation", "updiagonalstrike" );
                            return [ae, am[1]]
                        } else {
                            if (typeof af.acc == "boolean" && af.acc) {
                                ae = O( af.tag, am[0] );
                                ae.appendChild( O( "mo", e.createTextNode( af.output ) ) );
                                return [ae, am[1]]
                            } else {
                                if (!m && typeof af.codes != "undefined") {
                                    for (ah = 0; ah < am[0].childNodes.length; ah++) {
                                        if (am[0].childNodes[ah].nodeName == "mi" || am[0].nodeName == "mi") {
                                            al = (am[0].nodeName == "mi" ? am[0].firstChild.nodeValue : am[0].childNodes[ah].firstChild.nodeValue);
                                            var ak = [];
                                            for (var ag = 0; ag < al.length; ag++) {
                                                if (al.charCodeAt( ag ) > 64 && al.charCodeAt( ag ) < 91) {
                                                    ak = ak + af.codes[al.charCodeAt( ag ) - 65]
                                                } else {
                                                    if (al.charCodeAt( ag ) > 96 && al.charCodeAt( ag ) < 123) {
                                                        ak = ak + af.codes[al.charCodeAt( ag ) - 71]
                                                    } else {
                                                        ak = ak + al.charAt( ag )
                                                    }
                                                }
                                            }
                                            if (am[0].nodeName == "mi") {
                                                am[0] = O( "mo" ).appendChild( e.createTextNode( ak ) )
                                            } else {
                                                am[0].replaceChild( O( "mo" ).appendChild( e.createTextNode( ak ) ),
                                                    am[0].childNodes[ah] )
                                            }
                                        }
                                    }
                                }
                                ae = O( af.tag, am[0] );
                                ae.setAttribute( af.atname, af.atval );
                                return [ae, am[1]]
                            }
                        }
                    }
                }
            case U:
                aj = q( aj, af.input.length );
                am = G( aj );
                if (am[0] == null) {
                    return [O( "mo", e.createTextNode( af.input ) ), aj]
                }
                R( am[0] );
                var ad = G( am[1] );
                if (ad[0] == null) {
                    return [O( "mo", e.createTextNode( af.input ) ), aj]
                }
                R( ad[0] );
                if (af.input == "color") {
                    if (aj.charAt( 0 ) == "{") {
                        ah = aj.indexOf( "}" )
                    } else {
                        if (aj.charAt( 0 ) == "(") {
                            ah = aj.indexOf( ")" )
                        } else {
                            if (aj.charAt( 0 ) == "[") {
                                ah = aj.indexOf( "]" )
                            }
                        }
                    }
                    al = aj.slice( 1, ah );
                    ae = O( af.tag, ad[0] );
                    ae.setAttribute( "mathcolor", al );
                    return [ae, ad[1]]
                }
                if (af.input == "root" || af.input == "stackrel") {
                    ai.appendChild( ad[0] )
                }
                ai.appendChild( am[0] );
                if (af.input == "frac") {
                    ai.appendChild( ad[0] )
                }
                return [O( af.tag, ai ), ad[1]];
            case j:
                aj = q( aj, af.input.length );
                return [O( "mo", e.createTextNode( af.output ) ), aj];
            case a:
                aj = q( aj, af.input.length );
                ae = O( "mspace" );
                ae.setAttribute( "width", "1ex" );
                ai.appendChild( ae );
                ai.appendChild( O( af.tag, e.createTextNode( af.output ) ) );
                ae = O( "mspace" );
                ae.setAttribute( "width", "1ex" );
                ai.appendChild( ae );
                return [O( "mrow", ai ), aj];
            case n:
                F++;
                aj = q( aj, af.input.length );
                am = r( aj, false );
                F--;
                al = "";
                if (am[0].lastChild != null) {
                    al = am[0].lastChild.firstChild.nodeValue
                }
                if (al == "|") {
                    ae = O( "mo", e.createTextNode( af.output ) );
                    ae = O( "mrow", ae );
                    ae.appendChild( am[0] );
                    return [ae, am[1]]
                } else {
                    ae = O( "mo", e.createTextNode( "\u2223" ) );
                    ae = O( "mrow", ae );
                    return [ae, aj]
                }
            default:
                aj = q( aj, af.input.length );
                return [O( af.tag, e.createTextNode( af.output ) ), aj]
        }
    }

    function t( ai )
    {
        var ag, aj, ah, af, i, ae;
        ai = q( ai, 0 );
        aj = k( ai );
        i = G( ai );
        af = i[0];
        ai = i[1];
        ag = k( ai );
        if (ag.ttype == j && ag.input != "/") {
            ai = q( ai, ag.input.length );
            i = G( ai );
            if (i[0] == null) {
                i[0] = O( "mo", e.createTextNode( "\u25A1" ) )
            } else {
                R( i[0] )
            }
            ai = i[1];
            ae = (aj.ttype == L || aj.ttype == K);
            if (ag.input == "_") {
                ah = k( ai );
                if (ah.input == "^") {
                    ai = q( ai, ah.input.length );
                    var ad = G( ai );
                    R( ad[0] );
                    ai = ad[1];
                    af = O( (ae ? "munderover" : "msubsup"), af );
                    af.appendChild( i[0] );
                    af.appendChild( ad[0] );
                    af = O( "mrow", af )
                } else {
                    af = O( (ae ? "munder" : "msub"), af );
                    af.appendChild( i[0] )
                }
            } else {
                if (ag.input == "^" && ae) {
                    af = O( "mover", af );
                    af.appendChild( i[0] )
                } else {
                    af = O( ag.tag, af );
                    af.appendChild( i[0] )
                }
            }
            if (typeof aj.func != "undefined" && aj.func) {
                ah = k( ai );
                if (ah.ttype != j && ah.ttype != h) {
                    i = t( ai );
                    af = O( "mrow", af );
                    af.appendChild( i[0] );
                    ai = i[1]
                }
            }
        }
        return [af, ai]
    }

    function r( al, ak )
    {
        var ap, am, ah, at, ai = e.createDocumentFragment();
        do {
            al = q( al, 0 );
            ah = t( al );
            am = ah[0];
            al = ah[1];
            ap = k( al );
            if (ap.ttype == j && ap.input == "/") {
                al = q( al, ap.input.length );
                ah = t( al );
                if (ah[0] == null) {
                    ah[0] = O( "mo", e.createTextNode( "\u25A1" ) )
                } else {
                    R( ah[0] )
                }
                al = ah[1];
                R( am );
                am = O( ap.tag, am );
                am.appendChild( ah[0] );
                ai.appendChild( am );
                ap = k( al )
            } else {
                if (am != undefined) {
                    ai.appendChild( am )
                }
            }
        } while ((ap.ttype != h && (ap.ttype != n || ak) || F == 0) && ap != null && ap.output != "");
        if (ap.ttype == h || ap.ttype == n) {
            var au = ai.childNodes.length;
            if (au > 0 && ai.childNodes[au - 1].nodeName == "mrow") {
                var aw = ai.childNodes[au - 1].lastChild.firstChild.nodeValue;
                if (aw == ")" || aw == "]") {
                    var ae = ai.childNodes[au - 1].firstChild.firstChild.nodeValue;
                    if (ae == "(" && aw == ")" && ap.output != "}" || ae == "[" && aw == "]") {
                        var af = [];
                        var aq = true;
                        var an = ai.childNodes.length;
                        for (at = 0; aq && at < an; at = at + 2) {
                            af[at] = [];
                            am = ai.childNodes[at];
                            if (aq) {
                                aq = am.nodeName == "mrow" && (at == an - 1 || am.nextSibling.nodeName == "mo" && am.nextSibling.firstChild.nodeValue == ",") && am.firstChild.firstChild.nodeValue == ae && am.lastChild.firstChild.nodeValue == aw
                            }
                            if (aq) {
                                for (var ar = 0; ar < am.childNodes.length; ar++) {
                                    if (am.childNodes[ar].firstChild.nodeValue == ",") {
                                        af[at][af[at].length] = ar
                                    }
                                }
                            }
                            if (aq && at > 1) {
                                aq = af[at].length == af[at - 2].length
                            }
                        }
                        aq = aq && (af.length > 1 || af[0].length > 0);
                        if (aq) {
                            var ag, ad, aj, ao, av = e.createDocumentFragment();
                            for (at = 0; at < an; at = at + 2) {
                                ag = e.createDocumentFragment();
                                ad = e.createDocumentFragment();
                                am = ai.firstChild;
                                aj = am.childNodes.length;
                                ao = 0;
                                am.removeChild( am.firstChild );
                                for (ar = 1; ar < aj - 1; ar++) {
                                    if (typeof af[at][ao] != "undefined" && ar == af[at][ao]) {
                                        am.removeChild( am.firstChild );
                                        ag.appendChild( O( "mtd", ad ) );
                                        ao++
                                    } else {
                                        ad.appendChild( am.firstChild )
                                    }
                                }
                                ag.appendChild( O( "mtd", ad ) );
                                if (ai.childNodes.length > 2) {
                                    ai.removeChild( ai.firstChild );
                                    ai.removeChild( ai.firstChild )
                                }
                                av.appendChild( O( "mtr", ag ) )
                            }
                            am = O( "mtable", av );
                            if (typeof ap.invisible == "boolean" && ap.invisible) {
                                am.setAttribute( "columnalign", "left" )
                            }
                            ai.replaceChild( am, ai.firstChild )
                        }
                    }
                }
            }
            al = q( al, ap.input.length );
            if (typeof ap.invisible != "boolean" || !ap.invisible) {
                am = O( "mo", e.createTextNode( ap.output ) );
                ai.appendChild( am )
            }
        }
        return [ai, al]
    }

    function M( ae, ad )
    {
        var af, i;
        F = 0;
        ae = ae.replace( /&nbsp;/g, "" );
        ae = ae.replace( /&gt;/g, ">" );
        ae = ae.replace( /&lt;/g, "<" );
        ae = ae.replace( /(Sin|Cos|Tan|Arcsin|Arccos|Arctan|Sinh|Cosh|Tanh|Cot|Sec|Csc|Log|Ln|Abs)/g, function( ag )
        {
            return ag.toLowerCase()
        } );
        af = r( ae.replace( /^\s+/g, "" ), false )[0];
        i = O( "mstyle", af );
        if (C != "") {
            i.setAttribute( "mathcolor", C )
        }
        if (aa != "") {
            i.setAttribute( "fontfamily", aa )
        }
        if (p) {
            i.setAttribute( "displaystyle", "true" )
        }
        i = O( "math", i );
        if (v) {
            i.setAttribute( "title", ae.replace( /\s+/g, " " ) )
        }
        return i
    }

    v = false;
    aa = "";
    C = "";
    (function()
    {
        for (var ae = 0, ad = z.length; ae < ad; ae++) {
            if (z[ae].codes) {
                delete z[ae].codes
            }
            if (z[ae].func) {
                z[ae].tag = "mi"
            }
        }
    })();
    ac.Augment( {
        AM: {
            Init: function()
            {
                p = ac.config.displaystyle;
                d = (ac.config.decimal || ac.config.decimalsign);
                if (!ac.config.fixphi) {
                    for (var ae = 0, ad = z.length; ae < ad; ae++) {
                        if (z[ae].input === "phi") {
                            z[ae].output = "\u03C6"
                        }
                        if (z[ae].input === "varphi") {
                            z[ae].output = "\u03D5";
                            ae = ad
                        }
                    }
                }
                x();
                o()
            },
            Augment: function( i )
            {
                for (var ad in i) {
                    if (i.hasOwnProperty( ad )) {
                        switch (ad) {
                            case"displaystyle":
                                p = i[ad];
                                break;
                            case"decimal":
                                decimal = i[ad];
                                break;
                            case"parseMath":
                                M = i[ad];
                                break;
                            case"parseExpr":
                                r = i[ad];
                                break;
                            case"parseIexpr":
                                t = i[ad];
                                break;
                            case"parseSexpr":
                                G = i[ad];
                                break;
                            case"removeBrackets":
                                R = i[ad];
                                break;
                            case"getSymbol":
                                k = i[ad];
                                break;
                            case"position":
                                N = i[ad];
                                break;
                            case"removeCharsAndBlanks":
                                q = i[ad];
                                break;
                            case"createMmlNode":
                                O = i[ad];
                                break;
                            case"createElementMathML":
                                P = i[ad];
                                break;
                            case"createElementXHTML":
                                E = i[ad];
                                break;
                            case"initSymbols":
                                o = i[ad];
                                break;
                            case"refreshSymbols":
                                B = i[ad];
                                break;
                            case"compareNames":
                                T = i[ad];
                                break
                        }
                        this[ad] = i[ad]
                    }
                }
            },
            parseMath: M,
            parseExpr: r,
            parseIexpr: t,
            parseSexr: G,
            removeBrackets: R,
            getSymbol: k,
            position: N,
            removeCharsAndBlanks: q,
            createMmlNode: O,
            createElementMathML: P,
            createElementXHTML: E,
            initSymbols: o,
            refreshSymbols: B,
            compareNames: T,
            createDocumentFragment: X,
            document: e,
            define: I,
            newcommand: u,
            symbols: z,
            names: S,
            TOKEN: {
                CONST: c,
                UNARY: A,
                BINARY: U,
                INFIX: j,
                LEFTBRACKET: b,
                RIGHTBRACKET: h,
                SPACE: a,
                UNDEROVER: L,
                DEFINITION: V,
                LEFTRIGHT: n,
                TEXT: Y,
                UNARYUNDEROVER: K
            }
        }
    } );
    var ab = [Q, J];
    ab = null
})( MathJax.InputJax.AsciiMath );
(function( b )
{
    var a;
    b.Augment( {
        sourceMenuTitle: ["AsciiMathInput", "AsciiMath Input"],
        annotationEncoding: "text/x-asciimath",
        prefilterHooks: MathJax.Callback.Hooks( true ),
        postfilterHooks: MathJax.Callback.Hooks( true ),
        Translate: function( c )
        {
            var d, f = MathJax.HTML.getScript( c );
            var g = {math: f, script: c};
            var h = this.prefilterHooks.Execute( g );
            if (h) {
                return h
            }
            f = g.math;
            try {
                d = this.AM.parseMath( f )
            } catch( e ) {
                if (!e.asciimathError) {
                    throw e
                }
                d = this.formatError( e, f )
            }
            g.math = a( d );
            this.postfilterHooks.Execute( g );
            return this.postfilterHooks.Execute( g ) || g.math
        },
        formatError: function( f, e, c )
        {
            var d = f.message.replace( /\n.*/, "" );
            MathJax.Hub.signal.Post( ["AsciiMath Jax - parse error", d, e, c] );
            return a.Error( d )
        },
        Error: function( c )
        {
            throw MathJax.Hub.Insert( Error( c ), {asciimathError: true} )
        },
        Startup: function()
        {
            a = MathJax.ElementJax.mml;
            this.AM.Init()
        }
    } );
    b.loadComplete( "jax.js" )
})( MathJax.InputJax.AsciiMath );
