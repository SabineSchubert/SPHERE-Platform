/*
 *  /MathJax/config/AM_HTMLorMML.js
 *
 *  Copyright (c) 2010-2015 The MathJax Consortium
 *
 *  Part of the MathJax library.
 *  See http://www.mathjax.org for details.
 *
 *  Licensed under the Apache License, Version 2.0;
 *  you may not use this file except in compliance with the License.
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 */

MathJax.Hub.Config( {delayJaxRegistration: true} );

MathJax.Ajax.Preloading(
    "[MathJax]/jax/input/AsciiMath/config.js",
    "[MathJax]/jax/output/HTML-CSS/config.js",
    "[MathJax]/jax/output/NativeMML/config.js",
    "[MathJax]/jax/output/CommonHTML/config.js",
    "[MathJax]/config/MMLorHTML.js",
    "[MathJax]/extensions/asciimath2jax.js",
    "[MathJax]/extensions/MathEvents.js",
    "[MathJax]/extensions/MathZoom.js",
    "[MathJax]/extensions/MathMenu.js",
    "[MathJax]/jax/element/mml/jax.js",
    "[MathJax]/extensions/toMathML.js",
    "[MathJax]/jax/input/AsciiMath/jax.js",
    "[MathJax]/jax/output/CommonHTML/jax.js",
    "[MathJax]/extensions/CHTML-preview.js"
);

MathJax.Hub.Config( {"v1.0-compatible": false} );

MathJax.InputJax.AsciiMath = MathJax.InputJax( {
    id: "AsciiMath",
    version: "2.5.0",
    directory: MathJax.InputJax.directory + "/AsciiMath",
    extensionDir: MathJax.InputJax.extensionDir + "/AsciiMath",
    config: {fixphi: true, useMathMLspacing: true, displaystyle: true, decimalsign: "."}
} );
MathJax.InputJax.AsciiMath.Register( "math/asciimath" );
MathJax.InputJax.AsciiMath.loadComplete( "config.js" );
MathJax.OutputJax["HTML-CSS"] = MathJax.OutputJax( {
    id: "HTML-CSS",
    version: "2.5.0",
    directory: MathJax.OutputJax.directory + "/HTML-CSS",
    extensionDir: MathJax.OutputJax.extensionDir + "/HTML-CSS",
    autoloadDir: MathJax.OutputJax.directory + "/HTML-CSS/autoload",
    fontDir: MathJax.OutputJax.directory + "/HTML-CSS/fonts",
    webfontDir: MathJax.OutputJax.fontDir + "/HTML-CSS",
    config: {
        noReflows: true,
        matchFontHeight: true,
        scale: 100,
        minScaleAdjust: 50,
        availableFonts: ["STIX", "TeX"],
        preferredFont: "TeX",
        webFont: "TeX",
        imageFont: "TeX",
        undefinedFamily: "STIXGeneral,'Arial Unicode MS',serif",
        mtextFontInherit: false,
        EqnChunk: (MathJax.Hub.Browser.isMobile ? 10 : 50),
        EqnChunkFactor: 1.5,
        EqnChunkDelay: 100,
        linebreaks: {automatic: false, width: "container"},
        styles: {
            ".MathJax_Display": {"text-align": "center", margin: "1em 0em"},
            ".MathJax .merror": {
                "background-color": "#FFFF88",
                color: "#CC0000",
                border: "1px solid #CC0000",
                padding: "1px 3px",
                "font-style": "normal",
                "font-size": "90%"
            },
            ".MathJax .MJX-monospace": {"font-family": "monospace"},
            ".MathJax .MJX-sans-serif": {"font-family": "sans-serif"},
            "#MathJax_Tooltip": {
                "background-color": "InfoBackground",
                color: "InfoText",
                border: "1px solid black",
                "box-shadow": "2px 2px 5px #AAAAAA",
                "-webkit-box-shadow": "2px 2px 5px #AAAAAA",
                "-moz-box-shadow": "2px 2px 5px #AAAAAA",
                "-khtml-box-shadow": "2px 2px 5px #AAAAAA",
                filter: "progid:DXImageTransform.Microsoft.dropshadow(OffX=2, OffY=2, Color='gray', Positive='true')",
                padding: "3px 4px",
                "z-index": 401
            }
        }
    }
} );
if (MathJax.Hub.Browser.isMSIE && document.documentMode >= 9) {
    delete MathJax.OutputJax["HTML-CSS"].config.styles["#MathJax_Tooltip"].filter
}
if (!MathJax.Hub.config.delayJaxRegistration) {
    MathJax.OutputJax["HTML-CSS"].Register( "jax/mml" )
}
MathJax.Hub.Register.StartupHook( "End Config", [
    function( b, c )
    {
        var a = b.Insert( {
            minBrowserVersion: {Firefox: 3, Opera: 9.52, MSIE: 6, Chrome: 0.3, Safari: 2, Konqueror: 4},
            inlineMathDelimiters: ["$", "$"],
            displayMathDelimiters: ["$$", "$$"],
            multilineDisplay: true,
            minBrowserTranslate: function( f )
            {
                var e = b.getJaxFor( f ), k = ["[Math]"], j;
                var h = document.createElement( "span", {className: "MathJax_Preview"} );
                if (e.inputJax === "TeX") {
                    if (e.root.Get( "displaystyle" )) {
                        j = a.displayMathDelimiters;
                        k = [j[0] + e.originalText + j[1]];
                        if (a.multilineDisplay) {
                            k = k[0].split( /\n/ )
                        }
                    } else {
                        j = a.inlineMathDelimiters;
                        k = [j[0] + e.originalText.replace( /^\s+/, "" ).replace( /\s+$/, "" ) + j[1]]
                    }
                }
                for (var g = 0, d = k.length; g < d; g++) {
                    h.appendChild( document.createTextNode( k[g] ) );
                    if (g < d - 1) {
                        h.appendChild( document.createElement( "br" ) )
                    }
                }
                f.parentNode.insertBefore( h, f )
            }
        }, (b.config["HTML-CSS"] || {}) );
        if (b.Browser.version !== "0.0" && !b.Browser.versionAtLeast( a.minBrowserVersion[b.Browser] || 0 )) {
            c.Translate = a.minBrowserTranslate;
            b.Config( {showProcessingMessages: false} );
            MathJax.Message.Set( ["MathJaxNotSupported", "Your browser does not support MathJax"], null, 4000 );
            b.Startup.signal.Post( "MathJax not supported" )
        }
    }, MathJax.Hub, MathJax.OutputJax["HTML-CSS"]
] );
MathJax.OutputJax["HTML-CSS"].loadComplete( "config.js" );
MathJax.OutputJax.NativeMML = MathJax.OutputJax( {
    id: "NativeMML",
    version: "2.5.0",
    directory: MathJax.OutputJax.directory + "/NativeMML",
    extensionDir: MathJax.OutputJax.extensionDir + "/NativeMML",
    config: {
        matchFontHeight: true,
        scale: 100,
        minScaleAdjust: 50,
        styles: {"div.MathJax_MathML": {"text-align": "center", margin: ".75em 0px"}}
    }
} );
if (!MathJax.Hub.config.delayJaxRegistration) {
    MathJax.OutputJax.NativeMML.Register( "jax/mml" )
}
MathJax.OutputJax.NativeMML.loadComplete( "config.js" );
MathJax.OutputJax.CommonHTML = MathJax.OutputJax( {
    id: "CommonHTML",
    version: "2.5.0",
    directory: MathJax.OutputJax.directory + "/CommonHTML",
    extensionDir: MathJax.OutputJax.extensionDir + "/CommonHTML",
    config: {
        scale: 100,
        minScaleAdjust: 50,
        mtextFontInherit: false,
        linebreaks: {automatic: false, width: "container"}
    }
} );
if (!MathJax.Hub.config.delayJaxRegistration) {
    MathJax.OutputJax.CommonHTML.Register( "jax/mml" )
}
MathJax.OutputJax.CommonHTML.loadComplete( "config.js" );
(function( c, g )
{
    var f = "2.5.0";
    var a = MathJax.Hub.CombineConfig( "MMLorHTML",
        {prefer: {MSIE: "MML", Firefox: "HTML", Opera: "HTML", Chrome: "HTML", Safari: "HTML", other: "HTML"}} );
    var e = {Firefox: 3, Opera: 9.52, MSIE: 6, Chrome: 0.3, Safari: 2, Konqueror: 4};
    var b = (g.version === "0.0" || g.versionAtLeast( e[g] || 0 ));
    var d = (g.isFirefox && g.versionAtLeast( "1.5" )) || (g.isMSIE && g.hasMathPlayer) || (g.isSafari && g.versionAtLeast( "5.0" )) || (g.isOpera && g.versionAtLeast( "9.52" ));
    c.Register.StartupHook( "End Config", function()
    {
        var h = (a.prefer && typeof(a.prefer) === "object" ? a.prefer[MathJax.Hub.Browser] || a.prefer.other || "HTML" : a.prefer);
        if (b || d) {
            if (d && (h === "MML" || !b)) {
                if (MathJax.OutputJax.NativeMML) {
                    MathJax.OutputJax.NativeMML.Register( "jax/mml" )
                } else {
                    c.config.jax.unshift( "output/NativeMML" )
                }
                c.Startup.signal.Post( "NativeMML output selected" )
            } else {
                if (MathJax.OutputJax["HTML-CSS"]) {
                    MathJax.OutputJax["HTML-CSS"].Register( "jax/mml" )
                } else {
                    c.config.jax.unshift( "output/HTML-CSS" )
                }
                c.Startup.signal.Post( "HTML-CSS output selected" )
            }
        } else {
            c.PreProcess.disabled = true;
            c.prepareScripts.disabled = true;
            MathJax.Message.Set( ["MathJaxNotSupported", "Your browser does not support MathJax"], null, 4000 );
            c.Startup.signal.Post( "MathJax not supported" )
        }
    } )
})( MathJax.Hub, MathJax.Hub.Browser );
MathJax.Ajax.loadComplete( "[MathJax]/config/MMLorHTML.js" );
MathJax.Extension.asciimath2jax = {
    version: "2.5.0",
    config: {
        delimiters: [["`", "`"]],
        skipTags: ["script", "noscript", "style", "textarea", "pre", "code", "annotation", "annotation-xml"],
        ignoreClass: "asciimath2jax_ignore",
        processClass: "asciimath2jax_process",
        preview: "AsciiMath"
    },
    PreProcess: function( a )
    {
        if (!this.configured) {
            this.config = MathJax.Hub.CombineConfig( "asciimath2jax", this.config );
            if (this.config.Augment) {
                MathJax.Hub.Insert( this, this.config.Augment )
            }
            this.configured = true
        }
        if (typeof(a) === "string") {
            a = document.getElementById( a )
        }
        if (!a) {
            a = document.body
        }
        if (this.createPatterns()) {
            this.scanElement( a, a.nextSibling )
        }
    },
    createPatterns: function()
    {
        var d = [], c, a, b = this.config;
        this.match = {};
        if (b.delimiters.length === 0) {
            return false
        }
        for (c = 0, a = b.delimiters.length; c < a; c++) {
            d.push( this.patternQuote( b.delimiters[c][0] ) );
            this.match[b.delimiters[c][0]] = {
                mode: "",
                end: b.delimiters[c][1],
                pattern: this.endPattern( b.delimiters[c][1] )
            }
        }
        this.start = new RegExp( d.sort( this.sortLength ).join( "|" ), "g" );
        this.skipTags = new RegExp( "^(" + b.skipTags.join( "|" ) + ")$", "i" );
        var e = [];
        if (MathJax.Hub.config.preRemoveClass) {
            e.push( MathJax.Hub.config.preRemoveClass )
        }
        if (b.ignoreClass) {
            e.push( b.ignoreClass )
        }
        this.ignoreClass = (e.length ? new RegExp( "(^| )(" + e.join( "|" ) + ")( |$)" ) : /^$/);
        this.processClass = new RegExp( "(^| )(" + b.processClass + ")( |$)" );
        return true
    },
    patternQuote: function( a )
    {
        return a.replace( /([\^$(){}+*?\-|\[\]\:\\])/g, "\\$1" )
    },
    endPattern: function( a )
    {
        return new RegExp( this.patternQuote( a ) + "|\\\\.", "g" )
    },
    sortLength: function( d, c )
    {
        if (d.length !== c.length) {
            return c.length - d.length
        }
        return (d == c ? 0 : (d < c ? -1 : 1))
    },
    scanElement: function( c, b, g )
    {
        var a, e, d, f;
        while (c && c != b) {
            if (c.nodeName.toLowerCase() === "#text") {
                if (!g) {
                    c = this.scanText( c )
                }
            } else {
                a = (typeof(c.className) === "undefined" ? "" : c.className);
                e = (typeof(c.tagName) === "undefined" ? "" : c.tagName);
                if (typeof(a) !== "string") {
                    a = String( a )
                }
                f = this.processClass.exec( a );
                if (c.firstChild && !a.match( /(^| )MathJax/ ) && (f || !this.skipTags.exec( e ))) {
                    d = (g || this.ignoreClass.exec( a )) && !f;
                    this.scanElement( c.firstChild, b, d )
                }
            }
            if (c) {
                c = c.nextSibling
            }
        }
    },
    scanText: function( b )
    {
        if (b.nodeValue.replace( /\s+/, "" ) == "") {
            return b
        }
        var a, c;
        this.search = {start: true};
        this.pattern = this.start;
        while (b) {
            this.pattern.lastIndex = 0;
            while (b && b.nodeName.toLowerCase() === "#text" && (a = this.pattern.exec( b.nodeValue ))) {
                if (this.search.start) {
                    b = this.startMatch( a, b )
                } else {
                    b = this.endMatch( a, b )
                }
            }
            if (this.search.matched) {
                b = this.encloseMath( b )
            }
            if (b) {
                do {
                    c = b;
                    b = b.nextSibling
                } while (b && (b.nodeName.toLowerCase() === "br" || b.nodeName.toLowerCase() === "#comment"));
                if (!b || b.nodeName !== "#text") {
                    return c
                }
            }
        }
        return b
    },
    startMatch: function( a, b )
    {
        var c = this.match[a[0]];
        if (c != null) {
            this.search = {
                end: c.end,
                mode: c.mode,
                open: b,
                olen: a[0].length,
                opos: this.pattern.lastIndex - a[0].length
            };
            this.switchPattern( c.pattern )
        }
        return b
    },
    endMatch: function( a, b )
    {
        if (a[0] == this.search.end) {
            this.search.close = b;
            this.search.cpos = this.pattern.lastIndex;
            this.search.clen = (this.search.isBeginEnd ? 0 : a[0].length);
            this.search.matched = true;
            b = this.encloseMath( b );
            this.switchPattern( this.start )
        }
        return b
    },
    switchPattern: function( a )
    {
        a.lastIndex = this.pattern.lastIndex;
        this.pattern = a;
        this.search.start = (a === this.start)
    },
    encloseMath: function( b )
    {
        var a = this.search, f = a.close, e, c;
        if (a.cpos === f.length) {
            f = f.nextSibling
        } else {
            f = f.splitText( a.cpos )
        }
        if (!f) {
            e = f = MathJax.HTML.addText( a.close.parentNode, "" )
        }
        a.close = f;
        c = (a.opos ? a.open.splitText( a.opos ) : a.open);
        while (c.nextSibling && c.nextSibling !== f) {
            if (c.nextSibling.nodeValue !== null) {
                if (c.nextSibling.nodeName === "#comment") {
                    c.nodeValue += c.nextSibling.nodeValue.replace( /^\[CDATA\[((.|\n|\r)*)\]\]$/, "$1" )
                } else {
                    c.nodeValue += c.nextSibling.nodeValue
                }
            } else {
                if (this.msieNewlineBug) {
                    c.nodeValue += (c.nextSibling.nodeName.toLowerCase() === "br" ? "\n" : " ")
                } else {
                    c.nodeValue += " "
                }
            }
            c.parentNode.removeChild( c.nextSibling )
        }
        var d = c.nodeValue.substr( a.olen, c.nodeValue.length - a.olen - a.clen );
        c.parentNode.removeChild( c );
        if (this.config.preview !== "none") {
            this.createPreview( a.mode, d )
        }
        c = this.createMathTag( a.mode, d );
        this.search = {};
        this.pattern.lastIndex = 0;
        if (e) {
            e.parentNode.removeChild( e )
        }
        return c
    },
    insertNode: function( b )
    {
        var a = this.search;
        a.close.parentNode.insertBefore( b, a.close )
    },
    createPreview: function( c, a )
    {
        var b = this.config.preview;
        if (b === "none") {
            return
        }
        if (b === "AsciiMath") {
            b = [this.filterPreview( a )]
        }
        if (b) {
            b = MathJax.HTML.Element( "span", {className: MathJax.Hub.config.preRemoveClass}, b );
            this.insertNode( b )
        }
    },
    createMathTag: function( c, a )
    {
        var b = document.createElement( "script" );
        b.type = "math/asciimath" + c;
        MathJax.HTML.setScript( b, a );
        this.insertNode( b );
        return b
    },
    filterPreview: function( a )
    {
        return a
    },
    msieNewlineBug: (MathJax.Hub.Browser.isMSIE && (document.documentMode || 0) < 9)
};
MathJax.Hub.Register.PreProcessor( ["PreProcess", MathJax.Extension.asciimath2jax] );
MathJax.Ajax.loadComplete( "[MathJax]/extensions/asciimath2jax.js" );
(function( d, h, l, g, m, b, j )
{
    var q = "2.5.0";
    var i = MathJax.Extension;
    var c = i.MathEvents = {version: q};
    var k = d.config.menuSettings;
    var p = {
        hover: 500,
        frame: {x: 3.5, y: 5, bwidth: 1, bcolor: "#A6D", hwidth: "15px", hcolor: "#83A"},
        button: {x: -4, y: -3, wx: -2, src: l.urlRev( b.imageDir + "/MenuArrow-15.png" )},
        fadeinInc: 0.2,
        fadeoutInc: 0.05,
        fadeDelay: 50,
        fadeoutStart: 400,
        fadeoutDelay: 15 * 1000,
        styles: {
            ".MathJax_Hover_Frame": {
                "border-radius": ".25em",
                "-webkit-border-radius": ".25em",
                "-moz-border-radius": ".25em",
                "-khtml-border-radius": ".25em",
                "box-shadow": "0px 0px 15px #83A",
                "-webkit-box-shadow": "0px 0px 15px #83A",
                "-moz-box-shadow": "0px 0px 15px #83A",
                "-khtml-box-shadow": "0px 0px 15px #83A",
                border: "1px solid #A6D ! important",
                display: "inline-block",
                position: "absolute"
            }, ".MathJax_Hover_Arrow": {position: "absolute", width: "15px", height: "11px", cursor: "pointer"}
        }
    };
    var n = c.Event = {
        LEFTBUTTON: 0, RIGHTBUTTON: 2, MENUKEY: "altKey", Mousedown: function( r )
        {
            return n.Handler( r, "Mousedown", this )
        }, Mouseup: function( r )
        {
            return n.Handler( r, "Mouseup", this )
        }, Mousemove: function( r )
        {
            return n.Handler( r, "Mousemove", this )
        }, Mouseover: function( r )
        {
            return n.Handler( r, "Mouseover", this )
        }, Mouseout: function( r )
        {
            return n.Handler( r, "Mouseout", this )
        }, Click: function( r )
        {
            return n.Handler( r, "Click", this )
        }, DblClick: function( r )
        {
            return n.Handler( r, "DblClick", this )
        }, Menu: function( r )
        {
            return n.Handler( r, "ContextMenu", this )
        }, Handler: function( u, s, t )
        {
            if (l.loadingMathMenu) {
                return n.False( u )
            }
            var r = b[t.jaxID];
            if (!u) {
                u = window.event
            }
            u.isContextMenu = (s === "ContextMenu");
            if (r[s]) {
                return r[s]( u, t )
            }
            if (i.MathZoom) {
                return i.MathZoom.HandleEvent( u, s, t )
            }
        }, False: function( r )
        {
            if (!r) {
                r = window.event
            }
            if (r) {
                if (r.preventDefault) {
                    r.preventDefault()
                } else {
                    r.returnValue = false
                }
                if (r.stopPropagation) {
                    r.stopPropagation()
                }
                r.cancelBubble = true
            }
            return false
        }, ContextMenu: function( u, F, x )
        {
            var C = b[F.jaxID], w = C.getJaxFromMath( F );
            var G = (C.config.showMathMenu != null ? C : d).config.showMathMenu;
            if (!G || (k.context !== "MathJax" && !x)) {
                return
            }
            if (c.msieEventBug) {
                u = window.event || u
            }
            n.ClearSelection();
            f.ClearHoverTimer();
            if (w.hover) {
                if (w.hover.remove) {
                    clearTimeout( w.hover.remove );
                    delete w.hover.remove
                }
                w.hover.nofade = true
            }
            var v = MathJax.Menu;
            var H, E;
            if (v) {
                if (v.loadingDomain) {
                    return n.False( u )
                }
                H = m.loadDomain( "MathMenu" );
                if (!H) {
                    v.jax = w;
                    var s = v.menu.Find( "Show Math As" ).menu;
                    s.items[0].name = w.sourceMenuTitle;
                    s.items[0].format = (w.sourceMenuFormat || "MathML");
                    s.items[1].name = j[w.inputJax].sourceMenuTitle;
                    s.items[5].disabled = !j[w.inputJax].annotationEncoding;
                    var B = s.items[2];
                    B.disabled = true;
                    var r = B.menu.items;
                    annotationList = MathJax.Hub.Config.semanticsAnnotations;
                    for (var A = 0, z = r.length; A < z; A++) {
                        var t = r[A].name[1];
                        if (w.root && w.root.getAnnotation( t ) !== null) {
                            B.disabled = false;
                            r[A].hidden = false
                        } else {
                            r[A].hidden = true
                        }
                    }
                    var y = v.menu.Find( "Math Settings", "MathPlayer" );
                    y.hidden = !(w.outputJax === "NativeMML" && d.Browser.hasMathPlayer);
                    return v.menu.Post( u )
                }
                v.loadingDomain = true;
                E = function()
                {
                    delete v.loadingDomain
                }
            } else {
                if (l.loadingMathMenu) {
                    return n.False( u )
                }
                l.loadingMathMenu = true;
                H = l.Require( "[MathJax]/extensions/MathMenu.js" );
                E = function()
                {
                    delete l.loadingMathMenu;
                    if (!MathJax.Menu) {
                        MathJax.Menu = {}
                    }
                }
            }
            var D = {pageX: u.pageX, pageY: u.pageY, clientX: u.clientX, clientY: u.clientY};
            g.Queue( H, E, ["ContextMenu", n, D, F, x] );
            return n.False( u )
        }, AltContextMenu: function( t, s )
        {
            var u = b[s.jaxID];
            var r = (u.config.showMathMenu != null ? u : d).config.showMathMenu;
            if (r) {
                r = (u.config.showMathMenuMSIE != null ? u : d).config.showMathMenuMSIE;
                if (k.context === "MathJax" && !k.mpContext && r) {
                    if (!c.noContextMenuBug || t.button !== n.RIGHTBUTTON) {
                        return
                    }
                } else {
                    if (!t[n.MENUKEY] || t.button !== n.LEFTBUTTON) {
                        return
                    }
                }
                return u.ContextMenu( t, s, true )
            }
        }, ClearSelection: function()
        {
            if (c.safariContextMenuBug) {
                setTimeout( "window.getSelection().empty()", 0 )
            }
            if (document.selection) {
                setTimeout( "document.selection.empty()", 0 )
            }
        }, getBBox: function( t )
        {
            t.appendChild( c.topImg );
            var s = c.topImg.offsetTop, u = t.offsetHeight - s, r = t.offsetWidth;
            t.removeChild( c.topImg );
            return {w: r, h: s, d: u}
        }
    };
    var f = c.Hover = {
        Mouseover: function( t, s )
        {
            if (k.discoverable || k.zoom === "Hover") {
                var v = t.fromElement || t.relatedTarget, u = t.toElement || t.target;
                if (v && u && (v.isMathJax != u.isMathJax || d.getJaxFor( v ) !== d.getJaxFor( u ))) {
                    var r = this.getJaxFromMath( s );
                    if (r.hover) {
                        f.ReHover( r )
                    } else {
                        f.HoverTimer( r, s )
                    }
                    return n.False( t )
                }
            }
        }, Mouseout: function( t, s )
        {
            if (k.discoverable || k.zoom === "Hover") {
                var v = t.fromElement || t.relatedTarget, u = t.toElement || t.target;
                if (v && u && (v.isMathJax != u.isMathJax || d.getJaxFor( v ) !== d.getJaxFor( u ))) {
                    var r = this.getJaxFromMath( s );
                    if (r.hover) {
                        f.UnHover( r )
                    } else {
                        f.ClearHoverTimer()
                    }
                    return n.False( t )
                }
            }
        }, Mousemove: function( t, s )
        {
            if (k.discoverable || k.zoom === "Hover") {
                var r = this.getJaxFromMath( s );
                if (r.hover) {
                    return
                }
                if (f.lastX == t.clientX && f.lastY == t.clientY) {
                    return
                }
                f.lastX = t.clientX;
                f.lastY = t.clientY;
                f.HoverTimer( r, s );
                return n.False( t )
            }
        }, HoverTimer: function( r, s )
        {
            this.ClearHoverTimer();
            this.hoverTimer = setTimeout( g( ["Hover", this, r, s] ), p.hover )
        }, ClearHoverTimer: function()
        {
            if (this.hoverTimer) {
                clearTimeout( this.hoverTimer );
                delete this.hoverTimer
            }
        }, Hover: function( r, v )
        {
            if (i.MathZoom && i.MathZoom.Hover( {}, v )) {
                return
            }
            var u = b[r.outputJax], w = u.getHoverSpan( r, v ), z = u.getHoverBBox( r, w,
                v ), x = (u.config.showMathMenu != null ? u : d).config.showMathMenu;
            var B = p.frame.x, A = p.frame.y, y = p.frame.bwidth;
            if (c.msieBorderWidthBug) {
                y = 0
            }
            r.hover = {opacity: 0, id: r.inputID + "-Hover"};
            var s = h.Element( "span", {
                id: r.hover.id,
                isMathJax: true,
                style: {display: "inline-block", width: 0, height: 0, position: "relative"}
            }, [
                [
                    "span",
                    {
                        className: "MathJax_Hover_Frame",
                        isMathJax: true,
                        style: {
                            display: "inline-block",
                            position: "absolute",
                            top: this.Px( -z.h - A - y - (z.y || 0) ),
                            left: this.Px( -B - y + (z.x || 0) ),
                            width: this.Px( z.w + 2 * B ),
                            height: this.Px( z.h + z.d + 2 * A ),
                            opacity: 0,
                            filter: "alpha(opacity=0)"
                        }
                    }
                ]
            ] );
            var t = h.Element( "span", {
                isMathJax: true,
                id: r.hover.id + "Menu",
                style: {display: "inline-block", "z-index": 1, width: 0, height: 0, position: "relative"}
            }, [
                [
                    "img",
                    {
                        className: "MathJax_Hover_Arrow",
                        isMathJax: true,
                        math: v,
                        src: p.button.src,
                        onclick: this.HoverMenu,
                        jax: u.id,
                        style: {
                            left: this.Px( z.w + B + y + (z.x || 0) + p.button.x ),
                            top: this.Px( -z.h - A - y - (z.y || 0) - p.button.y ),
                            opacity: 0,
                            filter: "alpha(opacity=0)"
                        }
                    }
                ]
            ] );
            if (z.width) {
                s.style.width = t.style.width = z.width;
                s.style.marginRight = t.style.marginRight = "-" + z.width;
                s.firstChild.style.width = z.width;
                t.firstChild.style.left = "";
                t.firstChild.style.right = this.Px( p.button.wx )
            }
            w.parentNode.insertBefore( s, w );
            if (x) {
                w.parentNode.insertBefore( t, w )
            }
            if (w.style) {
                w.style.position = "relative"
            }
            this.ReHover( r )
        }, ReHover: function( r )
        {
            if (r.hover.remove) {
                clearTimeout( r.hover.remove )
            }
            r.hover.remove = setTimeout( g( ["UnHover", this, r] ), p.fadeoutDelay );
            this.HoverFadeTimer( r, p.fadeinInc )
        }, UnHover: function( r )
        {
            if (!r.hover.nofade) {
                this.HoverFadeTimer( r, -p.fadeoutInc, p.fadeoutStart )
            }
        }, HoverFade: function( r )
        {
            delete r.hover.timer;
            r.hover.opacity = Math.max( 0, Math.min( 1, r.hover.opacity + r.hover.inc ) );
            r.hover.opacity = Math.floor( 1000 * r.hover.opacity ) / 1000;
            var t = document.getElementById( r.hover.id ), s = document.getElementById( r.hover.id + "Menu" );
            t.firstChild.style.opacity = r.hover.opacity;
            t.firstChild.style.filter = "alpha(opacity=" + Math.floor( 100 * r.hover.opacity ) + ")";
            if (s) {
                s.firstChild.style.opacity = r.hover.opacity;
                s.firstChild.style.filter = t.style.filter
            }
            if (r.hover.opacity === 1) {
                return
            }
            if (r.hover.opacity > 0) {
                this.HoverFadeTimer( r, r.hover.inc );
                return
            }
            t.parentNode.removeChild( t );
            if (s) {
                s.parentNode.removeChild( s )
            }
            if (r.hover.remove) {
                clearTimeout( r.hover.remove )
            }
            delete r.hover
        }, HoverFadeTimer: function( r, t, s )
        {
            r.hover.inc = t;
            if (!r.hover.timer) {
                r.hover.timer = setTimeout( g( ["HoverFade", this, r] ), (s || p.fadeDelay) )
            }
        }, HoverMenu: function( r )
        {
            if (!r) {
                r = window.event
            }
            return b[this.jax].ContextMenu( r, this.math, true )
        }, ClearHover: function( r )
        {
            if (r.hover.remove) {
                clearTimeout( r.hover.remove )
            }
            if (r.hover.timer) {
                clearTimeout( r.hover.timer )
            }
            f.ClearHoverTimer();
            delete r.hover
        }, Px: function( r )
        {
            if (Math.abs( r ) < 0.006) {
                return "0px"
            }
            return r.toFixed( 2 ).replace( /\.?0+$/, "" ) + "px"
        }, getImages: function()
        {
            if (k.discoverable) {
                var r = new Image();
                r.src = p.button.src
            }
        }
    };
    var a = c.Touch = {
        last: 0, delay: 500, start: function( s )
        {
            var r = new Date().getTime();
            var t = (r - a.last < a.delay && a.up);
            a.last = r;
            a.up = false;
            if (t) {
                a.timeout = setTimeout( a.menu, a.delay, s, this );
                s.preventDefault()
            }
        }, end: function( s )
        {
            var r = new Date().getTime();
            a.up = (r - a.last < a.delay);
            if (a.timeout) {
                clearTimeout( a.timeout );
                delete a.timeout;
                a.last = 0;
                a.up = false;
                s.preventDefault();
                return n.Handler( (s.touches[0] || s.touch), "DblClick", this )
            }
        }, menu: function( s, r )
        {
            delete a.timeout;
            a.last = 0;
            a.up = false;
            return n.Handler( (s.touches[0] || s.touch), "ContextMenu", r )
        }
    };
    if (d.Browser.isMobile) {
        var o = p.styles[".MathJax_Hover_Arrow"];
        o.width = "25px";
        o.height = "18px";
        p.button.x = -6
    }
    d.Browser.Select( {
        MSIE: function( r )
        {
            var t = (document.documentMode || 0);
            var s = r.versionAtLeast( "8.0" );
            c.msieBorderWidthBug = (document.compatMode === "BackCompat");
            c.msieEventBug = r.isIE9;
            c.msieAlignBug = (!s || t < 8);
            if (t < 9) {
                n.LEFTBUTTON = 1
            }
        }, Safari: function( r )
        {
            c.safariContextMenuBug = true
        }, Opera: function( r )
        {
            c.operaPositionBug = true
        }, Konqueror: function( r )
        {
            c.noContextMenuBug = true
        }
    } );
    c.topImg = (c.msieAlignBug ? h.Element( "img",
        {style: {width: 0, height: 0, position: "relative"}, src: "about:blank"} ) : h.Element( "span",
        {style: {width: 0, height: 0, display: "inline-block"}} ));
    if (c.operaPositionBug) {
        c.topImg.style.border = "1px solid"
    }
    c.config = p = d.CombineConfig( "MathEvents", p );
    var e = function()
    {
        var r = p.styles[".MathJax_Hover_Frame"];
        r.border = p.frame.bwidth + "px solid " + p.frame.bcolor + " ! important";
        r["box-shadow"] = r["-webkit-box-shadow"] = r["-moz-box-shadow"] = r["-khtml-box-shadow"] = "0px 0px " + p.frame.hwidth + " " + p.frame.hcolor
    };
    g.Queue( d.Register.StartupHook( "End Config", {} ), [e], ["getImages", f], ["Styles", l, p.styles],
        ["Post", d.Startup.signal, "MathEvents Ready"], ["loadComplete", l, "[MathJax]/extensions/MathEvents.js"] )
})( MathJax.Hub, MathJax.HTML, MathJax.Ajax, MathJax.Callback, MathJax.Localization, MathJax.OutputJax,
    MathJax.InputJax );
(function( a, d, f, c, j )
{
    var k = "2.5.0";
    var i = a.CombineConfig( "MathZoom", {
        styles: {
            "#MathJax_Zoom": {
                position: "absolute",
                "background-color": "#F0F0F0",
                overflow: "auto",
                display: "block",
                "z-index": 301,
                padding: ".5em",
                border: "1px solid black",
                margin: 0,
                "font-weight": "normal",
                "font-style": "normal",
                "text-align": "left",
                "text-indent": 0,
                "text-transform": "none",
                "line-height": "normal",
                "letter-spacing": "normal",
                "word-spacing": "normal",
                "word-wrap": "normal",
                "white-space": "nowrap",
                "float": "none",
                "box-shadow": "5px 5px 15px #AAAAAA",
                "-webkit-box-shadow": "5px 5px 15px #AAAAAA",
                "-moz-box-shadow": "5px 5px 15px #AAAAAA",
                "-khtml-box-shadow": "5px 5px 15px #AAAAAA",
                filter: "progid:DXImageTransform.Microsoft.dropshadow(OffX=2, OffY=2, Color='gray', Positive='true')"
            },
            "#MathJax_ZoomOverlay": {
                position: "absolute",
                left: 0,
                top: 0,
                "z-index": 300,
                display: "inline-block",
                width: "100%",
                height: "100%",
                border: 0,
                padding: 0,
                margin: 0,
                "background-color": "white",
                opacity: 0,
                filter: "alpha(opacity=0)"
            },
            "#MathJax_ZoomFrame": {position: "relative", display: "inline-block", height: 0, width: 0},
            "#MathJax_ZoomEventTrap": {
                position: "absolute",
                left: 0,
                top: 0,
                "z-index": 302,
                display: "inline-block",
                border: 0,
                padding: 0,
                margin: 0,
                "background-color": "white",
                opacity: 0,
                filter: "alpha(opacity=0)"
            }
        }
    } );
    var e, b, g;
    MathJax.Hub.Register.StartupHook( "MathEvents Ready", function()
    {
        g = MathJax.Extension.MathEvents.Event;
        e = MathJax.Extension.MathEvents.Event.False;
        b = MathJax.Extension.MathEvents.Hover
    } );
    var h = MathJax.Extension.MathZoom = {
        version: k,
        settings: a.config.menuSettings,
        scrollSize: 18,
        HandleEvent: function( n, l, m )
        {
            if (h.settings.CTRL && !n.ctrlKey) {
                return true
            }
            if (h.settings.ALT && !n.altKey) {
                return true
            }
            if (h.settings.CMD && !n.metaKey) {
                return true
            }
            if (h.settings.Shift && !n.shiftKey) {
                return true
            }
            if (!h[l]) {
                return true
            }
            return h[l]( n, m )
        },
        Click: function( m, l )
        {
            if (this.settings.zoom === "Click") {
                return this.Zoom( m, l )
            }
        },
        DblClick: function( m, l )
        {
            if (this.settings.zoom === "Double-Click" || this.settings.zoom === "DoubleClick") {
                return this.Zoom( m, l )
            }
        },
        Hover: function( m, l )
        {
            if (this.settings.zoom === "Hover") {
                this.Zoom( m, l );
                return true
            }
            return false
        },
        Zoom: function( o, u )
        {
            this.Remove();
            b.ClearHoverTimer();
            g.ClearSelection();
            var s = MathJax.OutputJax[u.jaxID];
            var p = s.getJaxFromMath( u );
            if (p.hover) {
                b.UnHover( p )
            }
            var q = this.findContainer( u );
            var l = Math.floor( 0.85 * q.clientWidth ), t = Math.max( document.body.clientHeight,
                document.documentElement.clientHeight );
            if (this.getOverflow( q ) !== "visible") {
                t = Math.min( q.clientHeight, t )
            }
            t = Math.floor( 0.85 * t );
            var n = d.Element( "span", {id: "MathJax_ZoomFrame"}, [
                [
                    "span",
                    {
                        id: "MathJax_ZoomOverlay",
                        onmousedown: this.Remove
                    }
                ],
                [
                    "span",
                    {
                        id: "MathJax_Zoom",
                        onclick: this.Remove,
                        style: {visibility: "hidden", fontSize: this.settings.zscale}
                    },
                    [
                        [
                            "span",
                            {style: {display: "inline-block", "white-space": "nowrap"}}
                        ]
                    ]
                ]
            ] );
            var z = n.lastChild, w = z.firstChild, r = n.firstChild;
            u.parentNode.insertBefore( n, u );
            u.parentNode.insertBefore( u, n );
            if (w.addEventListener) {
                w.addEventListener( "mousedown", this.Remove, true )
            }
            var m = z.offsetWidth || z.clientWidth;
            l -= m;
            t -= m;
            z.style.maxWidth = l + "px";
            z.style.maxHeight = t + "px";
            if (this.msieTrapEventBug) {
                var y = d.Element( "span", {id: "MathJax_ZoomEventTrap", onmousedown: this.Remove} );
                n.insertBefore( y, z )
            }
            if (this.msieZIndexBug) {
                var v = d.addElement( document.body, "img", {
                    src: "about:blank",
                    id: "MathJax_ZoomTracker",
                    width: 0,
                    height: 0,
                    style: {width: 0, height: 0, position: "relative"}
                } );
                n.style.position = "relative";
                n.style.zIndex = i.styles["#MathJax_ZoomOverlay"]["z-index"];
                n = v
            }
            var x = s.Zoom( p, w, u, l, t );
            if (this.msiePositionBug) {
                if (this.msieSizeBug) {
                    z.style.height = x.zH + "px";
                    z.style.width = x.zW + "px"
                }
                if (z.offsetHeight > t) {
                    z.style.height = t + "px";
                    z.style.width = (x.zW + this.scrollSize) + "px"
                }
                if (z.offsetWidth > l) {
                    z.style.width = l + "px";
                    z.style.height = (x.zH + this.scrollSize) + "px"
                }
            }
            if (this.operaPositionBug) {
                z.style.width = Math.min( l, x.zW ) + "px"
            }
            if (z.offsetWidth > m && z.offsetWidth - m < l && z.offsetHeight - m < t) {
                z.style.overflow = "visible"
            }
            this.Position( z, x );
            if (this.msieTrapEventBug) {
                y.style.height = z.clientHeight + "px";
                y.style.width = z.clientWidth + "px";
                y.style.left = (parseFloat( z.style.left ) + z.clientLeft) + "px";
                y.style.top = (parseFloat( z.style.top ) + z.clientTop) + "px"
            }
            z.style.visibility = "";
            if (this.settings.zoom === "Hover") {
                r.onmouseover = this.Remove
            }
            if (window.addEventListener) {
                addEventListener( "resize", this.Resize, false )
            } else {
                if (window.attachEvent) {
                    attachEvent( "onresize", this.Resize )
                } else {
                    this.onresize = window.onresize;
                    window.onresize = this.Resize
                }
            }
            a.signal.Post( ["math zoomed", p] );
            return e( o )
        },
        Position: function( p, r )
        {
            p.style.display = "none";
            var q = this.Resize(), m = q.x, s = q.y, l = r.mW;
            p.style.display = "";
            var o = -l - Math.floor( (p.offsetWidth - l) / 2 ), n = r.Y;
            p.style.left = Math.max( o, 10 - m ) + "px";
            p.style.top = Math.max( n, 10 - s ) + "px";
            if (!h.msiePositionBug) {
                h.SetWH()
            }
        },
        Resize: function( m )
        {
            if (h.onresize) {
                h.onresize( m )
            }
            var q = document.getElementById( "MathJax_ZoomFrame" ), l = document.getElementById( "MathJax_ZoomOverlay" );
            var o = h.getXY( q ), n = h.findContainer( q );
            if (h.getOverflow( n ) !== "visible") {
                l.scroll_parent = n;
                var p = h.getXY( n );
                o.x -= p.x;
                o.y -= p.y;
                p = h.getBorder( n );
                o.x -= p.x;
                o.y -= p.y
            }
            l.style.left = (-o.x) + "px";
            l.style.top = (-o.y) + "px";
            if (h.msiePositionBug) {
                setTimeout( h.SetWH, 0 )
            } else {
                h.SetWH()
            }
            return o
        },
        SetWH: function()
        {
            var l = document.getElementById( "MathJax_ZoomOverlay" );
            if (!l) {
                return
            }
            l.style.display = "none";
            var m = l.scroll_parent || document.documentElement || document.body;
            l.style.width = m.scrollWidth + "px";
            l.style.height = Math.max( m.clientHeight, m.scrollHeight ) + "px";
            l.style.display = ""
        },
        findContainer: function( l )
        {
            l = l.parentNode;
            while (l.parentNode && l !== document.body && h.getOverflow( l ) === "visible") {
                l = l.parentNode
            }
            return l
        },
        getOverflow: (window.getComputedStyle ? function( l )
        {
            return getComputedStyle( l ).overflow
        } : function( l )
        {
            return (l.currentStyle || {overflow: "visible"}).overflow
        }),
        getBorder: function( o )
        {
            var m = {thin: 1, medium: 2, thick: 3};
            var n = (window.getComputedStyle ? getComputedStyle( o ) : (o.currentStyle || {
                borderLeftWidth: 0,
                borderTopWidth: 0
            }));
            var l = n.borderLeftWidth, p = n.borderTopWidth;
            if (m[l]) {
                l = m[l]
            } else {
                l = parseInt( l )
            }
            if (m[p]) {
                p = m[p]
            } else {
                p = parseInt( p )
            }
            return {x: l, y: p}
        },
        getXY: function( o )
        {
            var l = 0, n = 0, m;
            m = o;
            while (m.offsetParent) {
                l += m.offsetLeft;
                m = m.offsetParent
            }
            if (h.operaPositionBug) {
                o.style.border = "1px solid"
            }
            m = o;
            while (m.offsetParent) {
                n += m.offsetTop;
                m = m.offsetParent
            }
            if (h.operaPositionBug) {
                o.style.border = ""
            }
            return {x: l, y: n}
        },
        Remove: function( n )
        {
            var p = document.getElementById( "MathJax_ZoomFrame" );
            if (p) {
                var o = MathJax.OutputJax[p.previousSibling.jaxID];
                var l = o.getJaxFromMath( p.previousSibling );
                a.signal.Post( ["math unzoomed", l] );
                p.parentNode.removeChild( p );
                p = document.getElementById( "MathJax_ZoomTracker" );
                if (p) {
                    p.parentNode.removeChild( p )
                }
                if (h.operaRefreshBug) {
                    var m = d.addElement( document.body, "div", {
                        style: {
                            position: "fixed",
                            left: 0,
                            top: 0,
                            width: "100%",
                            height: "100%",
                            backgroundColor: "white",
                            opacity: 0
                        }, id: "MathJax_OperaDiv"
                    } );
                    document.body.removeChild( m )
                }
                if (window.removeEventListener) {
                    removeEventListener( "resize", h.Resize, false )
                } else {
                    if (window.detachEvent) {
                        detachEvent( "onresize", h.Resize )
                    } else {
                        window.onresize = h.onresize;
                        delete h.onresize
                    }
                }
            }
            return e( n )
        }
    };
    a.Browser.Select( {
        MSIE: function( l )
        {
            var n = (document.documentMode || 0);
            var m = (n >= 9);
            h.msiePositionBug = !m;
            h.msieSizeBug = l.versionAtLeast( "7.0" ) && (!document.documentMode || n === 7 || n === 8);
            h.msieZIndexBug = (n <= 7);
            h.msieInlineBlockAlignBug = (n <= 7);
            h.msieTrapEventBug = !window.addEventListener;
            if (document.compatMode === "BackCompat") {
                h.scrollSize = 52
            }
            if (m) {
                delete i.styles["#MathJax_Zoom"].filter
            }
        }, Opera: function( l )
        {
            h.operaPositionBug = true;
            h.operaRefreshBug = true
        }
    } );
    h.topImg = (h.msieInlineBlockAlignBug ? d.Element( "img",
        {style: {width: 0, height: 0, position: "relative"}, src: "about:blank"} ) : d.Element( "span",
        {style: {width: 0, height: 0, display: "inline-block"}} ));
    if (h.operaPositionBug || h.msieTopBug) {
        h.topImg.style.border = "1px solid"
    }
    MathJax.Callback.Queue( ["StartupHook", MathJax.Hub.Register, "Begin Styles", {}], ["Styles", f, i.styles],
        ["Post", a.Startup.signal, "MathZoom Ready"], ["loadComplete", f, "[MathJax]/extensions/MathZoom.js"] )
})( MathJax.Hub, MathJax.HTML, MathJax.Ajax, MathJax.OutputJax["HTML-CSS"], MathJax.OutputJax.NativeMML );
(function( c, g, k, f, b )
{
    var q = "2.5.0";
    var j = MathJax.Callback.Signal( "menu" );
    MathJax.Extension.MathMenu = {version: q, signal: j};
    var o = function( r )
    {
        return MathJax.Localization._.apply( MathJax.Localization,
            [["MathMenu", r]].concat( [].slice.call( arguments, 1 ) ) )
    };
    var n = c.Browser.isPC, l = c.Browser.isMSIE, e = ((document.documentMode || 0) > 8);
    var i = (n ? null : "5px");
    var p = c.CombineConfig( "MathMenu", {
        delay: 150,
        closeImg: k.urlRev( b.imageDir + "/CloseX-31.png" ),
        showRenderer: true,
        showMathPlayer: true,
        showFontMenu: false,
        showContext: false,
        showDiscoverable: false,
        showLocale: true,
        showLocaleURL: false,
        semanticsAnnotations: {
            TeX: ["TeX", "LaTeX", "application/x-tex"],
            StarMath: ["StarMath 5.0"],
            Maple: ["Maple"],
            ContentMathML: ["MathML-Content", "application/mathml-content+xml"],
            OpenMath: ["OpenMath"]
        },
        windowSettings: {
            status: "no",
            toolbar: "no",
            locationbar: "no",
            menubar: "no",
            directories: "no",
            personalbar: "no",
            resizable: "yes",
            scrollbars: "yes",
            width: 400,
            height: 300,
            left: Math.round( (screen.width - 400) / 2 ),
            top: Math.round( (screen.height - 300) / 3 )
        },
        styles: {
            "#MathJax_About": {
                position: "fixed",
                left: "50%",
                width: "auto",
                "text-align": "center",
                border: "3px outset",
                padding: "1em 2em",
                "background-color": "#DDDDDD",
                color: "black",
                cursor: "default",
                "font-family": "message-box",
                "font-size": "120%",
                "font-style": "normal",
                "text-indent": 0,
                "text-transform": "none",
                "line-height": "normal",
                "letter-spacing": "normal",
                "word-spacing": "normal",
                "word-wrap": "normal",
                "white-space": "nowrap",
                "float": "none",
                "z-index": 201,
                "border-radius": "15px",
                "-webkit-border-radius": "15px",
                "-moz-border-radius": "15px",
                "-khtml-border-radius": "15px",
                "box-shadow": "0px 10px 20px #808080",
                "-webkit-box-shadow": "0px 10px 20px #808080",
                "-moz-box-shadow": "0px 10px 20px #808080",
                "-khtml-box-shadow": "0px 10px 20px #808080",
                filter: "progid:DXImageTransform.Microsoft.dropshadow(OffX=2, OffY=2, Color='gray', Positive='true')"
            },
            ".MathJax_Menu": {
                position: "absolute",
                "background-color": "white",
                color: "black",
                width: "auto",
                padding: (n ? "2px" : "5px 0px"),
                border: "1px solid #CCCCCC",
                margin: 0,
                cursor: "default",
                font: "menu",
                "text-align": "left",
                "text-indent": 0,
                "text-transform": "none",
                "line-height": "normal",
                "letter-spacing": "normal",
                "word-spacing": "normal",
                "word-wrap": "normal",
                "white-space": "nowrap",
                "float": "none",
                "z-index": 201,
                "border-radius": i,
                "-webkit-border-radius": i,
                "-moz-border-radius": i,
                "-khtml-border-radius": i,
                "box-shadow": "0px 10px 20px #808080",
                "-webkit-box-shadow": "0px 10px 20px #808080",
                "-moz-box-shadow": "0px 10px 20px #808080",
                "-khtml-box-shadow": "0px 10px 20px #808080",
                filter: "progid:DXImageTransform.Microsoft.dropshadow(OffX=2, OffY=2, Color='gray', Positive='true')"
            },
            ".MathJax_MenuItem": {padding: (n ? "2px 2em" : "1px 2em"), background: "transparent"},
            ".MathJax_MenuArrow": {
                position: "absolute",
                right: ".5em",
                color: "#666666",
                "font-family": (l ? "'Arial unicode MS'" : null)
            },
            ".MathJax_MenuActive .MathJax_MenuArrow": {color: "white"},
            ".MathJax_MenuArrow.RTL": {left: ".5em", right: "auto"},
            ".MathJax_MenuCheck": {
                position: "absolute",
                left: ".7em",
                "font-family": (l ? "'Arial unicode MS'" : null)
            },
            ".MathJax_MenuCheck.RTL": {right: ".7em", left: "auto"},
            ".MathJax_MenuRadioCheck": {position: "absolute", left: (n ? "1em" : ".7em")},
            ".MathJax_MenuRadioCheck.RTL": {right: (n ? "1em" : ".7em"), left: "auto"},
            ".MathJax_MenuLabel": {padding: (n ? "2px 2em 4px 1.33em" : "1px 2em 3px 1.33em"), "font-style": "italic"},
            ".MathJax_MenuRule": {
                "border-top": (n ? "1px solid #CCCCCC" : "1px solid #DDDDDD"),
                margin: (n ? "4px 1px 0px" : "4px 3px")
            },
            ".MathJax_MenuDisabled": {color: "GrayText"},
            ".MathJax_MenuActive": {
                "background-color": (n ? "Highlight" : "#606872"),
                color: (n ? "HighlightText" : "white")
            },
            ".MathJax_Menu_Close": {position: "absolute", width: "31px", height: "31px", top: "-15px", left: "-15px"}
        }
    } );
    var h, d;
    c.Register.StartupHook( "MathEvents Ready", function()
    {
        h = MathJax.Extension.MathEvents.Event.False;
        d = MathJax.Extension.MathEvents.Hover
    } );
    var a = MathJax.Menu = MathJax.Object.Subclass( {
        version: q,
        items: [],
        posted: false,
        title: null,
        margin: 5,
        Init: function( r )
        {
            this.items = [].slice.call( arguments, 0 )
        },
        With: function( r )
        {
            if (r) {
                c.Insert( this, r )
            }
            return this
        },
        Post: function( s, C, A )
        {
            if (!s) {
                s = window.event
            }
            var r = document.getElementById( "MathJax_MenuFrame" );
            if (!r) {
                r = a.Background( this );
                delete m.lastItem;
                delete m.lastMenu;
                delete a.skipUp;
                j.Post( ["post", a.jax] );
                a.isRTL = (MathJax.Localization.fontDirection() === "rtl")
            }
            var t = g.Element( "div", {
                onmouseup: a.Mouseup,
                ondblclick: h,
                ondragstart: h,
                onselectstart: h,
                oncontextmenu: h,
                menuItem: this,
                className: "MathJax_Menu"
            } );
            if (!A) {
                MathJax.Localization.setCSS( t )
            }
            for (var v = 0, u = this.items.length; v < u; v++) {
                this.items[v].Create( t )
            }
            if (a.isMobile) {
                g.addElement( t, "span", {
                    className: "MathJax_Menu_Close",
                    menu: C,
                    ontouchstart: a.Close,
                    ontouchend: h,
                    onmousedown: a.Close,
                    onmouseup: h
                }, [["img", {src: p.closeImg, style: {width: "100%", height: "100%"}}]] )
            }
            r.appendChild( t );
            this.posted = true;
            t.style.width = (t.offsetWidth + 2) + "px";
            var B = s.pageX, z = s.pageY;
            if (!B && !z) {
                B = s.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
                z = s.clientY + document.body.scrollTop + document.documentElement.scrollTop
            }
            if (!C) {
                if (B + t.offsetWidth > document.body.offsetWidth - this.margin) {
                    B = document.body.offsetWidth - t.offsetWidth - this.margin
                }
                if (a.isMobile) {
                    B = Math.max( 5, B - Math.floor( t.offsetWidth / 2 ) );
                    z -= 20
                }
                a.skipUp = s.isContextMenu
            } else {
                var w = "left", D = C.offsetWidth;
                B = (a.isMobile ? 30 : D - 2);
                z = 0;
                while (C && C !== r) {
                    B += C.offsetLeft;
                    z += C.offsetTop;
                    C = C.parentNode
                }
                if (!a.isMobile) {
                    if ((a.isRTL && B - D - t.offsetWidth > this.margin) || (!a.isRTL && B + t.offsetWidth > document.body.offsetWidth - this.margin)) {
                        w = "right";
                        B = Math.max( this.margin, B - D - t.offsetWidth + 6 )
                    }
                }
                if (!n) {
                    t.style["borderRadiusTop" + w] = 0;
                    t.style["WebkitBorderRadiusTop" + w] = 0;
                    t.style["MozBorderRadiusTop" + w] = 0;
                    t.style["KhtmlBorderRadiusTop" + w] = 0
                }
            }
            t.style.left = B + "px";
            t.style.top = z + "px";
            if (document.selection && document.selection.empty) {
                document.selection.empty()
            }
            return h( s )
        },
        Remove: function( r, s )
        {
            j.Post( ["unpost", a.jax] );
            var t = document.getElementById( "MathJax_MenuFrame" );
            if (t) {
                t.parentNode.removeChild( t );
                if (this.msieFixedPositionBug) {
                    detachEvent( "onresize", a.Resize )
                }
            }
            if (a.jax.hover) {
                delete a.jax.hover.nofade;
                d.UnHover( a.jax )
            }
            return h( r )
        },
        Find: function( r )
        {
            return this.FindN( 1, r, [].slice.call( arguments, 1 ) )
        },
        FindId: function( r )
        {
            return this.FindN( 0, r, [].slice.call( arguments, 1 ) )
        },
        FindN: function( v, s, u )
        {
            for (var t = 0, r = this.items.length; t < r; t++) {
                if (this.items[t].name[v] === s) {
                    if (u.length) {
                        if (!this.items[t].menu) {
                            return null
                        }
                        return this.items[t].menu.FindN( v, u[0], u.slice( 1 ) )
                    }
                    return this.items[t]
                }
            }
            return null
        },
        IndexOf: function( r )
        {
            return this.IndexOfN( 1, r )
        },
        IndexOfId: function( r )
        {
            return this.IndexOfN( 0, r )
        },
        IndexOfN: function( u, s )
        {
            for (var t = 0, r = this.items.length; t < r; t++) {
                if (this.items[t].name[u] === s) {
                    return t
                }
            }
            return null
        }
    }, {
        config: p,
        div: null,
        Close: function( r )
        {
            return a.Event( r, this.menu || this.parentNode, (this.menu ? "Touchend" : "Remove") )
        },
        Remove: function( r )
        {
            return a.Event( r, this, "Remove" )
        },
        Mouseover: function( r )
        {
            return a.Event( r, this, "Mouseover" )
        },
        Mouseout: function( r )
        {
            return a.Event( r, this, "Mouseout" )
        },
        Mousedown: function( r )
        {
            return a.Event( r, this, "Mousedown" )
        },
        Mouseup: function( r )
        {
            return a.Event( r, this, "Mouseup" )
        },
        Touchstart: function( r )
        {
            return a.Event( r, this, "Touchstart" )
        },
        Touchend: function( r )
        {
            return a.Event( r, this, "Touchend" )
        },
        Event: function( t, v, r, u )
        {
            if (a.skipMouseover && r === "Mouseover" && !u) {
                return h( t )
            }
            if (a.skipUp) {
                if (r.match( /Mouseup|Touchend/ )) {
                    delete a.skipUp;
                    return h( t )
                }
                if (r === "Touchstart" || (r === "Mousedown" && !a.skipMousedown)) {
                    delete a.skipUp
                }
            }
            if (!t) {
                t = window.event
            }
            var s = v.menuItem;
            if (s && s[r]) {
                return s[r]( t, v )
            }
            return null
        },
        BGSTYLE: {
            position: "absolute",
            left: 0,
            top: 0,
            "z-index": 200,
            width: "100%",
            height: "100%",
            border: 0,
            padding: 0,
            margin: 0
        },
        Background: function( s )
        {
            var t = g.addElement( document.body, "div", {style: this.BGSTYLE, id: "MathJax_MenuFrame"},
                [["div", {style: this.BGSTYLE, menuItem: s, onmousedown: this.Remove}]] );
            var r = t.firstChild;
            if (a.msieBackgroundBug) {
                r.style.backgroundColor = "white";
                r.style.filter = "alpha(opacity=0)"
            }
            if (a.msieFixedPositionBug) {
                t.width = t.height = 0;
                this.Resize();
                attachEvent( "onresize", this.Resize )
            } else {
                r.style.position = "fixed"
            }
            return t
        },
        Resize: function()
        {
            setTimeout( a.SetWH, 0 )
        },
        SetWH: function()
        {
            var r = document.getElementById( "MathJax_MenuFrame" );
            if (r) {
                r = r.firstChild;
                r.style.width = r.style.height = "1px";
                r.style.width = document.body.scrollWidth + "px";
                r.style.height = document.body.scrollHeight + "px"
            }
        },
        saveCookie: function()
        {
            g.Cookie.Set( "menu", this.cookie )
        },
        getCookie: function()
        {
            this.cookie = g.Cookie.Get( "menu" )
        },
        getImages: function()
        {
            if (a.isMobile) {
                var r = new Image();
                r.src = p.closeImg
            }
        }
    } );
    var m = a.ITEM = MathJax.Object.Subclass( {
        name: "", Create: function( s )
        {
            if (!this.hidden) {
                var r = {
                    onmouseover: a.Mouseover,
                    onmouseout: a.Mouseout,
                    onmouseup: a.Mouseup,
                    onmousedown: a.Mousedown,
                    ondragstart: h,
                    onselectstart: h,
                    onselectend: h,
                    ontouchstart: a.Touchstart,
                    ontouchend: a.Touchend,
                    className: "MathJax_MenuItem",
                    menuItem: this
                };
                if (this.disabled) {
                    r.className += " MathJax_MenuDisabled"
                }
                g.addElement( s, "div", r, this.Label( r, s ) )
            }
        }, Name: function()
        {
            return o( this.name[0], this.name[1] )
        }, Mouseover: function( v, x )
        {
            if (!this.disabled) {
                this.Activate( x )
            }
            if (!this.menu || !this.menu.posted) {
                var w = document.getElementById( "MathJax_MenuFrame" ).childNodes, s = x.parentNode.childNodes;
                for (var t = 0, r = s.length; t < r; t++) {
                    var u = s[t].menuItem;
                    if (u && u.menu && u.menu.posted) {
                        u.Deactivate( s[t] )
                    }
                }
                r = w.length - 1;
                while (r >= 0 && x.parentNode.menuItem !== w[r].menuItem) {
                    w[r].menuItem.posted = false;
                    w[r].parentNode.removeChild( w[r] );
                    r--
                }
                if (this.Timer && !a.isMobile) {
                    this.Timer( v, x )
                }
            }
        }, Mouseout: function( r, s )
        {
            if (!this.menu || !this.menu.posted) {
                this.Deactivate( s )
            }
            if (this.timer) {
                clearTimeout( this.timer );
                delete this.timer
            }
        }, Mouseup: function( r, s )
        {
            return this.Remove( r, s )
        }, Touchstart: function( r, s )
        {
            return this.TouchEvent( r, s, "Mousedown" )
        }, Touchend: function( r, s )
        {
            return this.TouchEvent( r, s, "Mouseup" )
        }, TouchEvent: function( s, t, r )
        {
            if (this !== m.lastItem) {
                if (m.lastMenu) {
                    a.Event( s, m.lastMenu, "Mouseout" )
                }
                a.Event( s, t, "Mouseover", true );
                m.lastItem = this;
                m.lastMenu = t
            }
            if (this.nativeTouch) {
                return null
            }
            a.Event( s, t, r );
            return false
        }, Remove: function( r, s )
        {
            s = s.parentNode.menuItem;
            return s.Remove( r, s )
        }, Activate: function( r )
        {
            this.Deactivate( r );
            r.className += " MathJax_MenuActive"
        }, Deactivate: function( r )
        {
            r.className = r.className.replace( / MathJax_MenuActive/, "" )
        }, With: function( r )
        {
            if (r) {
                c.Insert( this, r )
            }
            return this
        }, isRTL: function()
        {
            return a.isRTL
        }, rtlClass: function()
        {
            return (this.isRTL() ? " RTL" : "")
        }
    } );
    a.ITEM.COMMAND = a.ITEM.Subclass( {
        action: function()
        {
        }, Init: function( r, t, s )
        {
            if (!(r instanceof Array)) {
                r = [r, r]
            }
            this.name = r;
            this.action = t;
            this.With( s )
        }, Label: function( r, s )
        {
            return [this.Name()]
        }, Mouseup: function( r, s )
        {
            if (!this.disabled) {
                this.Remove( r, s );
                j.Post( ["command", this] );
                this.action.call( this, r )
            }
            return h( r )
        }
    } );
    a.ITEM.SUBMENU = a.ITEM.Subclass( {
        menu: null,
        marker: (n && !c.Browser.isSafari ? "\u25B6" : "\u25B8"),
        markerRTL: (n && !c.Browser.isSafari ? "\u25B0" : "\u25C2"),
        Init: function( r, t )
        {
            if (!(r instanceof Array)) {
                r = [r, r]
            }
            this.name = r;
            var s = 1;
            if (!(t instanceof a.ITEM)) {
                this.With( t ), s++
            }
            this.menu = a.apply( a, [].slice.call( arguments, s ) )
        },
        Label: function( r, s )
        {
            this.menu.posted = false;
            return [
                this.Name() + " ",
                [
                    "span",
                    {className: "MathJax_MenuArrow" + this.rtlClass()},
                    [this.isRTL() ? this.markerRTL : this.marker]
                ]
            ]
        },
        Timer: function( r, s )
        {
            if (this.timer) {
                clearTimeout( this.timer )
            }
            r = {clientX: r.clientX, clientY: r.clientY};
            this.timer = setTimeout( f( ["Mouseup", this, r, s] ), p.delay )
        },
        Touchend: function( s, u )
        {
            var t = this.menu.posted;
            var r = this.SUPER( arguments ).Touchend.apply( this, arguments );
            if (t) {
                this.Deactivate( u );
                delete m.lastItem;
                delete m.lastMenu
            }
            return r
        },
        Mouseup: function( s, u )
        {
            if (!this.disabled) {
                if (!this.menu.posted) {
                    if (this.timer) {
                        clearTimeout( this.timer );
                        delete this.timer
                    }
                    this.menu.Post( s, u, this.ltr )
                } else {
                    var t = document.getElementById( "MathJax_MenuFrame" ).childNodes, r = t.length - 1;
                    while (r >= 0) {
                        var v = t[r];
                        v.menuItem.posted = false;
                        v.parentNode.removeChild( v );
                        if (v.menuItem === this.menu) {
                            break
                        }
                        r--
                    }
                }
            }
            return h( s )
        }
    } );
    a.ITEM.RADIO = a.ITEM.Subclass( {
        variable: null, marker: (n ? "\u25CF" : "\u2713"), Init: function( s, r, t )
        {
            if (!(s instanceof Array)) {
                s = [s, s]
            }
            this.name = s;
            this.variable = r;
            this.With( t );
            if (this.value == null) {
                this.value = this.name[0]
            }
        }, Label: function( s, t )
        {
            var r = {className: "MathJax_MenuRadioCheck" + this.rtlClass()};
            if (p.settings[this.variable] !== this.value) {
                r = {style: {display: "none"}}
            }
            return [["span", r, [this.marker]], " " + this.Name()]
        }, Mouseup: function( u, v )
        {
            if (!this.disabled) {
                var w = v.parentNode.childNodes;
                for (var s = 0, r = w.length; s < r; s++) {
                    var t = w[s].menuItem;
                    if (t && t.variable === this.variable) {
                        w[s].firstChild.style.display = "none"
                    }
                }
                v.firstChild.display = "";
                p.settings[this.variable] = this.value;
                a.cookie[this.variable] = p.settings[this.variable];
                a.saveCookie();
                j.Post( ["radio button", this] )
            }
            this.Remove( u, v );
            if (this.action && !this.disabled) {
                this.action.call( a, this )
            }
            return h( u )
        }
    } );
    a.ITEM.CHECKBOX = a.ITEM.Subclass( {
        variable: null, marker: "\u2713", Init: function( s, r, t )
        {
            if (!(s instanceof Array)) {
                s = [s, s]
            }
            this.name = s;
            this.variable = r;
            this.With( t )
        }, Label: function( s, t )
        {
            var r = {className: "MathJax_MenuCheck" + this.rtlClass()};
            if (!p.settings[this.variable]) {
                r = {style: {display: "none"}}
            }
            return [["span", r, [this.marker]], " " + this.Name()]
        }, Mouseup: function( r, s )
        {
            if (!this.disabled) {
                s.firstChild.display = (p.settings[this.variable] ? "none" : "");
                p.settings[this.variable] = !p.settings[this.variable];
                a.cookie[this.variable] = p.settings[this.variable];
                a.saveCookie();
                j.Post( ["checkbox", this] )
            }
            this.Remove( r, s );
            if (this.action && !this.disabled) {
                this.action.call( a, this )
            }
            return h( r )
        }
    } );
    a.ITEM.LABEL = a.ITEM.Subclass( {
        Init: function( r, s )
        {
            if (!(r instanceof Array)) {
                r = [r, r]
            }
            this.name = r;
            this.With( s )
        }, Label: function( r, s )
        {
            delete r.onmouseover, delete r.onmouseout;
            delete r.onmousedown;
            r.className += " MathJax_MenuLabel";
            return [this.Name()]
        }
    } );
    a.ITEM.RULE = a.ITEM.Subclass( {
        Label: function( r, s )
        {
            delete r.onmouseover, delete r.onmouseout;
            delete r.onmousedown;
            r.className += " MathJax_MenuRule";
            return null
        }
    } );
    a.About = function()
    {
        var t = b["HTML-CSS"] || {};
        var s = (t.imgFonts ? "image" : (t.fontInUse ? (t.webFonts ? "web" : "local") + " " + t.fontInUse : (b.SVG ? "web SVG" : "generic"))) + " fonts";
        var x = (!t.webFonts || t.imgFonts ? null : t.allowWebFonts.replace( /otf/, "woff or otf" ) + " fonts");
        var r = ["MathJax.js v" + MathJax.fileversion, ["br"]];
        r.push( ["div", {style: {"border-top": "groove 2px", margin: ".25em 0"}}] );
        a.About.GetJax( r, MathJax.InputJax, ["InputJax", "%1 Input Jax v%2"] );
        a.About.GetJax( r, MathJax.OutputJax, ["OutputJax", "%1 Output Jax v%2"] );
        a.About.GetJax( r, MathJax.ElementJax, ["ElementJax", "%1 Element Jax v%2"] );
        r.push( ["div", {style: {"border-top": "groove 2px", margin: ".25em 0"}}] );
        a.About.GetJax( r, MathJax.Extension, ["Extension", "%1 Extension v%2"], true );
        r.push( ["div", {style: {"border-top": "groove 2px", margin: ".25em 0"}}], [
            "center",
            {},
            [c.Browser + " v" + c.Browser.version + (x ? " \u2014 " + o( x.replace( / /g, "" ), x ) : "")]
        ] );
        a.About.div = a.Background( a.About );
        var v = g.addElement( a.About.div, "div", {id: "MathJax_About"}, [
            [
                "b",
                {style: {fontSize: "120%"}},
                ["MathJax"]
            ],
            " v" + MathJax.version,
            ["br"],
            o( s.replace( / /g, "" ), "using " + s ),
            ["br"],
            ["br"],
            [
                "span",
                {
                    style: {
                        display: "inline-block",
                        "text-align": "left",
                        "font-size": "80%",
                        "max-height": "20em",
                        overflow: "auto",
                        "background-color": "#E4E4E4",
                        padding: ".4em .6em",
                        border: "1px inset"
                    }
                },
                r
            ],
            ["br"],
            ["br"],
            [
                "a",
                {href: "http://www.mathjax.org/"},
                ["www.mathjax.org"]
            ],
            [
                "img",
                {
                    src: p.closeImg,
                    style: {width: "21px", height: "21px", position: "absolute", top: ".2em", right: ".2em"},
                    onclick: a.About.Remove
                }
            ]
        ] );
        MathJax.Localization.setCSS( v );
        var w = (document.documentElement || {});
        var u = window.innerHeight || w.clientHeight || w.scrollHeight || 0;
        if (a.prototype.msieAboutBug) {
            v.style.width = "20em";
            v.style.position = "absolute";
            v.style.left = Math.floor( (document.documentElement.scrollWidth - v.offsetWidth) / 2 ) + "px";
            v.style.top = (Math.floor( (u - v.offsetHeight) / 3 ) + document.body.scrollTop) + "px"
        } else {
            v.style.marginLeft = Math.floor( -v.offsetWidth / 2 ) + "px";
            v.style.top = Math.floor( (u - v.offsetHeight) / 3 ) + "px"
        }
    };
    a.About.Remove = function( r )
    {
        if (a.About.div) {
            document.body.removeChild( a.About.div );
            delete a.About.div
        }
    };
    a.About.GetJax = function( s, x, v, u )
    {
        var w = [];
        for (var y in x) {
            if (x.hasOwnProperty( y ) && x[y]) {
                if ((u && x[y].version) || (x[y].isa && x[y].isa( x ))) {
                    w.push( o( v[0], v[1], (x[y].id || y), x[y].version ) )
                }
            }
        }
        w.sort();
        for (var t = 0, r = w.length; t < r; t++) {
            s.push( w[t], ["br"] )
        }
        return s
    };
    a.Help = function()
    {
        k.Require( "[MathJax]/extensions/HelpDialog.js", function()
        {
            MathJax.Extension.Help.Dialog()
        } )
    };
    a.ShowSource = function( v )
    {
        if (!v) {
            v = window.event
        }
        var u = {screenX: v.screenX, screenY: v.screenY};
        if (!a.jax) {
            return
        }
        if (this.format === "MathML") {
            var s = MathJax.ElementJax.mml;
            if (s && typeof(s.mbase.prototype.toMathML) !== "undefined") {
                try {
                    a.ShowSource.Text( a.jax.root.toMathML( "", a.jax ), v )
                } catch( t ) {
                    if (!t.restart) {
                        throw t
                    }
                    f.After( [this, a.ShowSource, u], t.restart )
                }
            } else {
                if (!k.loadingToMathML) {
                    k.loadingToMathML = true;
                    a.ShowSource.Window( v );
                    f.Queue( k.Require( "[MathJax]/extensions/toMathML.js" ), function()
                    {
                        delete k.loadingToMathML;
                        if (!s.mbase.prototype.toMathML) {
                            s.mbase.prototype.toMathML = function()
                            {
                            }
                        }
                    }, [this, a.ShowSource, u] );
                }
            }
        } else {
            if (this.format === "Error") {
                a.ShowSource.Text( a.jax.errorText, v )
            } else {
                if (p.semanticsAnnotations[this.format]) {
                    var r = a.jax.root.getAnnotation( this.format );
                    if (r.data[0]) {
                        a.ShowSource.Text( r.data[0].toString() )
                    }
                } else {
                    if (a.jax.originalText == null) {
                        alert( o( "NoOriginalForm", "No original form available" ) );
                        return
                    }
                    a.ShowSource.Text( a.jax.originalText, v )
                }
            }
        }
    };
    a.ShowSource.Window = function( s )
    {
        if (!a.ShowSource.w) {
            var t = [], r = p.windowSettings;
            for (var u in r) {
                if (r.hasOwnProperty( u )) {
                    t.push( u + "=" + r[u] )
                }
            }
            a.ShowSource.w = window.open( "", "_blank", t.join( "," ) )
        }
        return a.ShowSource.w
    };
    a.ShowSource.Text = function( v, t )
    {
        var r = a.ShowSource.Window( t );
        delete a.ShowSource.w;
        v = v.replace( /^\s*/, "" ).replace( /\s*$/, "" );
        v = v.replace( /&/g, "&amp;" ).replace( /</g, "&lt;" ).replace( />/g, "&gt;" );
        var u = o( "EqSource", "MathJax Equation Source" );
        if (a.isMobile) {
            r.document.open();
            r.document.write( "<html><head><meta name='viewport' content='width=device-width, initial-scale=1.0' /><title>" + u + "</title></head><body style='font-size:85%'>" );
            r.document.write( "<pre>" + v + "</pre>" );
            r.document.write( "<hr><input type='button' value='" + o( "Close",
                "Close" ) + "' onclick='window.close()' />" );
            r.document.write( "</body></html>" );
            r.document.close()
        } else {
            r.document.open();
            r.document.write( "<html><head><title>" + u + "</title></head><body style='font-size:85%'>" );
            r.document.write( "<table><tr><td><pre>" + v + "</pre></td></tr></table>" );
            r.document.write( "</body></html>" );
            r.document.close();
            var s = r.document.body.firstChild;
            setTimeout( function()
            {
                var A = (r.outerHeight - r.innerHeight) || 30, z = (r.outerWidth - r.innerWidth) || 30, w, D;
                z = Math.max( 100, Math.min( Math.floor( 0.5 * screen.width ), s.offsetWidth + z + 25 ) );
                A = Math.max( 40, Math.min( Math.floor( 0.5 * screen.height ), s.offsetHeight + A + 25 ) );
                if (a.prototype.msieHeightBug) {
                    A += 35
                }
                r.resizeTo( z, A );
                var C;
                try {
                    C = t.screenX
                } catch( B ) {
                }
                if (t && C != null) {
                    w = Math.max( 0, Math.min( t.screenX - Math.floor( z / 2 ), screen.width - z - 20 ) );
                    D = Math.max( 0, Math.min( t.screenY - Math.floor( A / 2 ), screen.height - A - 20 ) );
                    r.moveTo( w, D )
                }
            }, 50 )
        }
    };
    a.Scale = function()
    {
        var s = b["HTML-CSS"], r = b.NativeMML, v = b.SVG;
        var u = (s || r || v || {config: {scale: 100}}).config.scale;
        var t = prompt( o( "ScaleMath", "Scale all mathematics (compared to surrounding text) by" ), u + "%" );
        if (t) {
            if (t.match( /^\s*\d+(\.\d*)?\s*%?\s*$/ )) {
                t = parseFloat( t );
                if (t) {
                    if (t !== u) {
                        if (s) {
                            s.config.scale = t
                        }
                        if (r) {
                            r.config.scale = t
                        }
                        if (v) {
                            v.config.scale = t
                        }
                        a.cookie.scale = t;
                        a.saveCookie();
                        c.Rerender()
                    }
                } else {
                    alert( o( "NonZeroScale", "The scale should not be zero" ) )
                }
            } else {
                alert( o( "PercentScale", "The scale should be a percentage (e.g., 120%%)" ) )
            }
        }
    };
    a.Zoom = function()
    {
        if (!MathJax.Extension.MathZoom) {
            k.Require( "[MathJax]/extensions/MathZoom.js" )
        }
    };
    a.Renderer = function()
    {
        var s = c.outputJax["jax/mml"];
        if (s[0] !== p.settings.renderer) {
            var v = c.Browser, u, r = a.Renderer.Messages, t;
            switch (p.settings.renderer) {
                case"NativeMML":
                    if (!p.settings.warnedMML) {
                        if (v.isChrome && v.version.substr( 0, 3 ) !== "24.") {
                            u = r.MML.WebKit
                        } else {
                            if (v.isSafari && !v.versionAtLeast( "5.0" )) {
                                u = r.MML.WebKit
                            } else {
                                if (v.isMSIE) {
                                    if (!v.hasMathPlayer) {
                                        u = r.MML.MSIE
                                    }
                                } else {
                                    u = r.MML[v]
                                }
                            }
                        }
                        t = "warnedMML"
                    }
                    break;
                case"SVG":
                    if (!p.settings.warnedSVG) {
                        if (v.isMSIE && !e) {
                            u = r.SVG.MSIE
                        }
                    }
                    break
            }
            if (u) {
                u = o( u[0], u[1] );
                u += "\n\n";
                u += o( "SwitchAnyway",
                    "Switch the renderer anyway?\n\n(Press OK to switch, CANCEL to continue with the current renderer)" );
                a.cookie.renderer = s[0].id;
                a.saveCookie();
                if (!confirm( u )) {
                    a.cookie.renderer = p.settings.renderer = g.Cookie.Get( "menu" ).renderer;
                    a.saveCookie();
                    return
                }
                if (t) {
                    a.cookie.warned = p.settings.warned = true
                }
                a.cookie.renderer = p.settings.renderer;
                a.saveCookie()
            }
            c.Queue( ["setRenderer", c, p.settings.renderer, "jax/mml"], ["Rerender", c] )
        }
    };
    a.Renderer.Messages = {
        MML: {
            WebKit: [
                "WebkitNativeMMLWarning",
                "Your browser doesn't seem to support MathML natively, so switching to MathML output may cause the mathematics on the page to become unreadable."
            ],
            MSIE: [
                "MSIENativeMMLWarning",
                "Internet Explorer requires the MathPlayer plugin in order to process MathML output."
            ],
            Opera: [
                "OperaNativeMMLWarning",
                "Opera's support for MathML is limited, so switching to MathML output may cause some expressions to render poorly."
            ],
            Safari: [
                "SafariNativeMMLWarning",
                "Your browser's native MathML does not implement all the features used by MathJax, so some expressions may not render properly."
            ],
            Firefox: [
                "FirefoxNativeMMLWarning",
                "Your browser's native MathML does not implement all the features used by MathJax, so some expressions may not render properly."
            ]
        },
        SVG: {
            MSIE: [
                "MSIESVGWarning",
                "SVG is not implemented in Internet Explorer prior to IE9 or when it is emulating IE8 or below. Switching to SVG output will cause the mathematics to not display properly."
            ]
        }
    };
    a.Font = function()
    {
        var r = b["HTML-CSS"];
        if (!r) {
            return
        }
        document.location.reload()
    };
    a.Locale = function()
    {
        MathJax.Localization.setLocale( p.settings.locale );
        MathJax.Hub.Queue( ["Reprocess", MathJax.Hub] )
    };
    a.LoadLocale = function()
    {
        var r = prompt( o( "LoadURL", "Load translation data from this URL:" ) );
        if (r) {
            if (!r.match( /\.js$/ )) {
                alert( o( "BadURL",
                    "The URL should be for a javascript file that defines MathJax translation data.  Javascript file names should end with '.js'" ) )
            }
            k.Require( r, function( s )
            {
                if (s != k.STATUS.OK) {
                    alert( o( "BadData", "Failed to load translation data from %1", r ) )
                }
            } )
        }
    };
    a.MPEvents = function( t )
    {
        var s = p.settings.discoverable, r = a.MPEvents.Messages;
        if (!e) {
            if (p.settings.mpMouse && !confirm( o.apply( o, r.IE8warning ) )) {
                delete a.cookie.mpContext;
                delete p.settings.mpContext;
                delete a.cookie.mpMouse;
                delete p.settings.mpMouse;
                a.saveCookie();
                return
            }
            p.settings.mpContext = p.settings.mpMouse;
            a.cookie.mpContext = a.cookie.mpMouse = p.settings.mpMouse;
            a.saveCookie();
            MathJax.Hub.Queue( ["Rerender", MathJax.Hub] )
        } else {
            if (!s && t.name[1] === "Menu Events" && p.settings.mpContext) {
                alert( o.apply( o, r.IE9warning ) )
            }
        }
    };
    a.MPEvents.Messages = {
        IE8warning: [
            "IE8warning",
            "This will disable the MathJax menu and zoom features, but you can Alt-Click on an expression to obtain the MathJax menu instead.\n\nReally change the MathPlayer settings?"
        ],
        IE9warning: [
            "IE9warning",
            "The MathJax contextual menu will be disabled, but you can Alt-Click on an expression to obtain the MathJax menu instead."
        ]
    };
    c.Browser.Select( {
        MSIE: function( r )
        {
            var s = (document.compatMode === "BackCompat");
            var t = r.versionAtLeast( "8.0" ) && document.documentMode > 7;
            a.Augment( {
                margin: 20,
                msieBackgroundBug: ((document.documentMode || 0) < 9),
                msieFixedPositionBug: (s || !t),
                msieAboutBug: s,
                msieHeightBug: ((document.documentMode || 0) < 9)
            } );
            if (e) {
                delete p.styles["#MathJax_About"].filter;
                delete p.styles[".MathJax_Menu"].filter
            }
        }, Firefox: function( r )
        {
            a.skipMouseover = r.isMobile && r.versionAtLeast( "6.0" );
            a.skipMousedown = r.isMobile
        }
    } );
    a.isMobile = c.Browser.isMobile;
    a.noContextMenu = c.Browser.noContextMenu;
    a.CreateLocaleMenu = function()
    {
        if (!a.menu) {
            return
        }
        var w = a.menu.Find( "Language" ).menu, t = w.items;
        var s = [], y = MathJax.Localization.strings;
        for (var x in y) {
            if (y.hasOwnProperty( x )) {
                s.push( x )
            }
        }
        s = s.sort();
        w.items = [];
        for (var u = 0, r = s.length; u < r; u++) {
            var v = y[s[u]].menuTitle;
            if (v) {
                v += " (" + s[u] + ")"
            } else {
                v = s[u]
            }
            w.items.push( m.RADIO( [s[u], v], "locale", {action: a.Locale} ) )
        }
        w.items.push( t[t.length - 2], t[t.length - 1] )
    };
    a.CreateAnnotationMenu = function()
    {
        if (!a.menu) {
            return
        }
        var t = a.menu.Find( "Show Math As", "Annotation" ).menu;
        var s = p.semanticsAnnotations;
        for (var r in s) {
            if (s.hasOwnProperty( r )) {
                t.items.push( m.COMMAND( [r, r], a.ShowSource, {hidden: true, nativeTouch: true, format: r} ) )
            }
        }
    };
    c.Register.StartupHook( "End Config", function()
    {
        p.settings = c.config.menuSettings;
        if (typeof(p.settings.showRenderer) !== "undefined") {
            p.showRenderer = p.settings.showRenderer
        }
        if (typeof(p.settings.showFontMenu) !== "undefined") {
            p.showFontMenu = p.settings.showFontMenu
        }
        if (typeof(p.settings.showContext) !== "undefined") {
            p.showContext = p.settings.showContext
        }
        a.getCookie();
        a.menu = a( m.SUBMENU( ["Show", "Show Math As"],
            m.COMMAND( ["MathMLcode", "MathML Code"], a.ShowSource, {nativeTouch: true, format: "MathML"} ),
            m.COMMAND( ["Original", "Original Form"], a.ShowSource, {nativeTouch: true} ),
            m.SUBMENU( ["Annotation", "Annotation"], {disabled: true} ), m.RULE(),
            m.CHECKBOX( ["texHints", "Show TeX hints in MathML"], "texHints" ),
            m.CHECKBOX( ["semantics", "Add original form as annotation"], "semantics" ) ), m.RULE(),
            m.SUBMENU( ["Settings", "Math Settings"],
                m.SUBMENU( ["ZoomTrigger", "Zoom Trigger"], m.RADIO( ["Hover", "Hover"], "zoom", {action: a.Zoom} ),
                    m.RADIO( ["Click", "Click"], "zoom", {action: a.Zoom} ),
                    m.RADIO( ["DoubleClick", "Double-Click"], "zoom", {action: a.Zoom} ),
                    m.RADIO( ["NoZoom", "No Zoom"], "zoom", {value: "None"} ), m.RULE(),
                    m.LABEL( ["TriggerRequires", "Trigger Requires:"] ),
                    m.CHECKBOX( (c.Browser.isMac ? ["Option", "Option"] : ["Alt", "Alt"]), "ALT" ),
                    m.CHECKBOX( ["Command", "Command"], "CMD", {hidden: !c.Browser.isMac} ),
                    m.CHECKBOX( ["Control", "Control"], "CTRL", {hidden: c.Browser.isMac} ),
                    m.CHECKBOX( ["Shift", "Shift"], "Shift" ) ),
                m.SUBMENU( ["ZoomFactor", "Zoom Factor"], m.RADIO( "125%", "zscale" ), m.RADIO( "133%", "zscale" ),
                    m.RADIO( "150%", "zscale" ), m.RADIO( "175%", "zscale" ), m.RADIO( "200%", "zscale" ),
                    m.RADIO( "250%", "zscale" ), m.RADIO( "300%", "zscale" ), m.RADIO( "400%", "zscale" ) ), m.RULE(),
                m.SUBMENU( ["Renderer", "Math Renderer"], {hidden: !p.showRenderer},
                    m.RADIO( "HTML-CSS", "renderer", {action: a.Renderer} ),
                    m.RADIO( "Fast HTML", "renderer", {action: a.Renderer, value: "CommonHTML"} ),
                    m.RADIO( "MathML", "renderer", {action: a.Renderer, value: "NativeMML"} ),
                    m.RADIO( "SVG", "renderer", {action: a.Renderer} ), m.RULE(),
                    m.CHECKBOX( "Fast Preview", "CHTMLpreview" ) ), m.SUBMENU( "MathPlayer",
                    {hidden: !c.Browser.isMSIE || !p.showMathPlayer, disabled: !c.Browser.hasMathPlayer},
                    m.LABEL( ["MPHandles", "Let MathPlayer Handle:"] ),
                    m.CHECKBOX( ["MenuEvents", "Menu Events"], "mpContext", {action: a.MPEvents, hidden: !e} ),
                    m.CHECKBOX( ["MouseEvents", "Mouse Events"], "mpMouse", {action: a.MPEvents, hidden: !e} ),
                    m.CHECKBOX( ["MenuAndMouse", "Mouse and Menu Events"], "mpMouse",
                        {action: a.MPEvents, hidden: e} ) ),
                m.SUBMENU( ["FontPrefs", "Font Preference"], {hidden: !p.showFontMenu},
                    m.LABEL( ["ForHTMLCSS", "For HTML-CSS:"] ), m.RADIO( ["Auto", "Auto"], "font", {action: a.Font} ),
                    m.RULE(), m.RADIO( ["TeXLocal", "TeX (local)"], "font", {action: a.Font} ),
                    m.RADIO( ["TeXWeb", "TeX (web)"], "font", {action: a.Font} ),
                    m.RADIO( ["TeXImage", "TeX (image)"], "font", {action: a.Font} ), m.RULE(),
                    m.RADIO( ["STIXLocal", "STIX (local)"], "font", {action: a.Font} ),
                    m.RADIO( ["STIXWeb", "STIX (web)"], "font", {action: a.Font} ), m.RULE(),
                    m.RADIO( ["AsanaMathWeb", "Asana Math (web)"], "font", {action: a.Font} ),
                    m.RADIO( ["GyrePagellaWeb", "Gyre Pagella (web)"], "font", {action: a.Font} ),
                    m.RADIO( ["GyreTermesWeb", "Gyre Termes (web)"], "font", {action: a.Font} ),
                    m.RADIO( ["LatinModernWeb", "Latin Modern (web)"], "font", {action: a.Font} ),
                    m.RADIO( ["NeoEulerWeb", "Neo Euler (web)"], "font", {action: a.Font} ) ),
                m.SUBMENU( ["ContextMenu", "Contextual Menu"], {hidden: !p.showContext},
                    m.RADIO( "MathJax", "context" ), m.RADIO( ["Browser", "Browser"], "context" ) ),
                m.COMMAND( ["Scale", "Scale All Math ..."], a.Scale ),
                m.RULE().With( {hidden: !p.showDiscoverable, name: ["", "discover_rule"]} ),
                m.CHECKBOX( ["Discoverable", "Highlight on Hover"], "discoverable", {hidden: !p.showDiscoverable} ) ),
            m.SUBMENU( ["Locale", "Language"], {hidden: !p.showLocale, ltr: true},
                m.RADIO( "en", "locale", {action: a.Locale} ),
                m.RULE().With( {hidden: !p.showLocaleURL, name: ["", "localURL_rule"]} ),
                m.COMMAND( ["LoadLocale", "Load from URL ..."], a.LoadLocale, {hidden: !p.showLocaleURL} ) ), m.RULE(),
            m.COMMAND( ["About", "About MathJax"], a.About ), m.COMMAND( ["Help", "MathJax Help"], a.Help ) );
        if (a.isMobile) {
            (function()
            {
                var s = p.settings;
                var r = a.menu.Find( "Math Settings", "Zoom Trigger" ).menu;
                r.items[0].disabled = r.items[1].disabled = true;
                if (s.zoom === "Hover" || s.zoom == "Click") {
                    s.zoom = "None"
                }
                r.items = r.items.slice( 0, 4 );
                if (navigator.appVersion.match( /[ (]Android[) ]/ )) {
                    a.ITEM.SUBMENU.Augment( {marker: "\u00BB"} )
                }
            })()
        }
        a.CreateLocaleMenu();
        a.CreateAnnotationMenu()
    } );
    a.showRenderer = function( r )
    {
        a.cookie.showRenderer = p.showRenderer = r;
        a.saveCookie();
        a.menu.Find( "Math Settings", "Math Renderer" ).hidden = !r
    };
    a.showMathPlayer = function( r )
    {
        a.cookie.showMathPlayer = p.showMathPlayer = r;
        a.saveCookie();
        a.menu.Find( "Math Settings", "MathPlayer" ).hidden = !r
    };
    a.showFontMenu = function( r )
    {
        a.cookie.showFontMenu = p.showFontMenu = r;
        a.saveCookie();
        a.menu.Find( "Math Settings", "Font Preference" ).hidden = !r
    };
    a.showContext = function( r )
    {
        a.cookie.showContext = p.showContext = r;
        a.saveCookie();
        a.menu.Find( "Math Settings", "Contextual Menu" ).hidden = !r
    };
    a.showDiscoverable = function( r )
    {
        a.cookie.showDiscoverable = p.showDiscoverable = r;
        a.saveCookie();
        a.menu.Find( "Math Settings", "Highlight on Hover" ).hidden = !r;
        a.menu.Find( "Math Settings", "discover_rule" ).hidden = !r
    };
    a.showLocale = function( r )
    {
        a.cookie.showLocale = p.showLocale = r;
        a.saveCookie();
        a.menu.Find( "Language" ).hidden = !r
    };
    MathJax.Hub.Register.StartupHook( "HTML-CSS Jax Ready", function()
    {
        if (!MathJax.OutputJax["HTML-CSS"].config.imageFont) {
            a.menu.Find( "Math Settings", "Font Preference", "TeX (image)" ).disabled = true
        }
    } );
    f.Queue( c.Register.StartupHook( "End Config", {} ), ["getImages", a], ["Styles", k, p.styles],
        ["Post", c.Startup.signal, "MathMenu Ready"], ["loadComplete", k, "[MathJax]/extensions/MathMenu.js"] )
})( MathJax.Hub, MathJax.HTML, MathJax.Ajax, MathJax.CallBack, MathJax.OutputJax );
MathJax.ElementJax.mml = MathJax.ElementJax( {mimeType: "jax/mml"}, {
    id: "mml",
    version: "2.5.0",
    directory: MathJax.ElementJax.directory + "/mml",
    extensionDir: MathJax.ElementJax.extensionDir + "/mml",
    optableDir: MathJax.ElementJax.directory + "/mml/optable"
} );
MathJax.ElementJax.mml.Augment( {
    Init: function()
    {
        if (arguments.length === 1 && arguments[0].type === "math") {
            this.root = arguments[0]
        } else {
            this.root = MathJax.ElementJax.mml.math.apply( this, arguments )
        }
        if (this.root.attr && this.root.attr.mode) {
            if (!this.root.display && this.root.attr.mode === "display") {
                this.root.display = "block";
                this.root.attrNames.push( "display" )
            }
            delete this.root.attr.mode;
            for (var b = 0, a = this.root.attrNames.length; b < a; b++) {
                if (this.root.attrNames[b] === "mode") {
                    this.root.attrNames.splice( b, 1 );
                    break
                }
            }
        }
    }
}, {
    INHERIT: "_inherit_",
    AUTO: "_auto_",
    SIZE: {INFINITY: "infinity", SMALL: "small", NORMAL: "normal", BIG: "big"},
    COLOR: {TRANSPARENT: "transparent"},
    VARIANT: {
        NORMAL: "normal",
        BOLD: "bold",
        ITALIC: "italic",
        BOLDITALIC: "bold-italic",
        DOUBLESTRUCK: "double-struck",
        FRAKTUR: "fraktur",
        BOLDFRAKTUR: "bold-fraktur",
        SCRIPT: "script",
        BOLDSCRIPT: "bold-script",
        SANSSERIF: "sans-serif",
        BOLDSANSSERIF: "bold-sans-serif",
        SANSSERIFITALIC: "sans-serif-italic",
        SANSSERIFBOLDITALIC: "sans-serif-bold-italic",
        MONOSPACE: "monospace",
        INITIAL: "inital",
        TAILED: "tailed",
        LOOPED: "looped",
        STRETCHED: "stretched",
        CALIGRAPHIC: "-tex-caligraphic",
        OLDSTYLE: "-tex-oldstyle"
    },
    FORM: {PREFIX: "prefix", INFIX: "infix", POSTFIX: "postfix"},
    LINEBREAK: {AUTO: "auto", NEWLINE: "newline", NOBREAK: "nobreak", GOODBREAK: "goodbreak", BADBREAK: "badbreak"},
    LINEBREAKSTYLE: {
        BEFORE: "before",
        AFTER: "after",
        DUPLICATE: "duplicate",
        INFIXLINBREAKSTYLE: "infixlinebreakstyle"
    },
    INDENTALIGN: {LEFT: "left", CENTER: "center", RIGHT: "right", AUTO: "auto", ID: "id", INDENTALIGN: "indentalign"},
    INDENTSHIFT: {INDENTSHIFT: "indentshift"},
    LINETHICKNESS: {THIN: "thin", MEDIUM: "medium", THICK: "thick"},
    NOTATION: {
        LONGDIV: "longdiv",
        ACTUARIAL: "actuarial",
        RADICAL: "radical",
        BOX: "box",
        ROUNDEDBOX: "roundedbox",
        CIRCLE: "circle",
        LEFT: "left",
        RIGHT: "right",
        TOP: "top",
        BOTTOM: "bottom",
        UPDIAGONALSTRIKE: "updiagonalstrike",
        DOWNDIAGONALSTRIKE: "downdiagonalstrike",
        UPDIAGONALARROW: "updiagonalarrow",
        VERTICALSTRIKE: "verticalstrike",
        HORIZONTALSTRIKE: "horizontalstrike",
        PHASORANGLE: "phasorangle",
        MADRUWB: "madruwb"
    },
    ALIGN: {
        TOP: "top",
        BOTTOM: "bottom",
        CENTER: "center",
        BASELINE: "baseline",
        AXIS: "axis",
        LEFT: "left",
        RIGHT: "right"
    },
    LINES: {NONE: "none", SOLID: "solid", DASHED: "dashed"},
    SIDE: {LEFT: "left", RIGHT: "right", LEFTOVERLAP: "leftoverlap", RIGHTOVERLAP: "rightoverlap"},
    WIDTH: {AUTO: "auto", FIT: "fit"},
    ACTIONTYPE: {TOGGLE: "toggle", STATUSLINE: "statusline", TOOLTIP: "tooltip", INPUT: "input"},
    LENGTH: {
        VERYVERYTHINMATHSPACE: "veryverythinmathspace",
        VERYTHINMATHSPACE: "verythinmathspace",
        THINMATHSPACE: "thinmathspace",
        MEDIUMMATHSPACE: "mediummathspace",
        THICKMATHSPACE: "thickmathspace",
        VERYTHICKMATHSPACE: "verythickmathspace",
        VERYVERYTHICKMATHSPACE: "veryverythickmathspace",
        NEGATIVEVERYVERYTHINMATHSPACE: "negativeveryverythinmathspace",
        NEGATIVEVERYTHINMATHSPACE: "negativeverythinmathspace",
        NEGATIVETHINMATHSPACE: "negativethinmathspace",
        NEGATIVEMEDIUMMATHSPACE: "negativemediummathspace",
        NEGATIVETHICKMATHSPACE: "negativethickmathspace",
        NEGATIVEVERYTHICKMATHSPACE: "negativeverythickmathspace",
        NEGATIVEVERYVERYTHICKMATHSPACE: "negativeveryverythickmathspace"
    },
    OVERFLOW: {LINBREAK: "linebreak", SCROLL: "scroll", ELIDE: "elide", TRUNCATE: "truncate", SCALE: "scale"},
    UNIT: {EM: "em", EX: "ex", PX: "px", IN: "in", CM: "cm", MM: "mm", PT: "pt", PC: "pc"},
    TEXCLASS: {ORD: 0, OP: 1, BIN: 2, REL: 3, OPEN: 4, CLOSE: 5, PUNCT: 6, INNER: 7, VCENTER: 8, NONE: -1},
    TEXCLASSNAMES: ["ORD", "OP", "BIN", "REL", "OPEN", "CLOSE", "PUNCT", "INNER", "VCENTER"],
    skipAttributes: {texClass: true, useHeight: true, texprimestyle: true},
    copyAttributes: {
        displaystyle: 1,
        scriptlevel: 1,
        open: 1,
        close: 1,
        form: 1,
        actiontype: 1,
        fontfamily: true,
        fontsize: true,
        fontweight: true,
        fontstyle: true,
        color: true,
        background: true,
        id: true,
        "class": 1,
        href: true,
        style: true
    },
    copyAttributeNames: [
        "displaystyle",
        "scriptlevel",
        "open",
        "close",
        "form",
        "actiontype",
        "fontfamily",
        "fontsize",
        "fontweight",
        "fontstyle",
        "color",
        "background",
        "id",
        "class",
        "href",
        "style"
    ],
    nocopyAttributes: {
        fontfamily: true,
        fontsize: true,
        fontweight: true,
        fontstyle: true,
        color: true,
        background: true,
        id: true,
        "class": true,
        href: true,
        style: true,
        xmlns: true
    },
    Error: function( d, e )
    {
        var c = this.merror( d ), b = MathJax.Localization.fontDirection(), a = MathJax.Localization.fontFamily();
        if (e) {
            c = c.With( e )
        }
        if (b || a) {
            c = this.mstyle( c );
            if (b) {
                c.dir = b
            }
            if (a) {
                c.style.fontFamily = "font-family: " + a
            }
        }
        return c
    }
} );
(function( a )
{
    a.mbase = MathJax.Object.Subclass( {
        type: "base",
        isToken: false,
        defaults: {mathbackground: a.INHERIT, mathcolor: a.INHERIT, dir: a.INHERIT},
        noInherit: {},
        noInheritAttribute: {texClass: true},
        linebreakContainer: false,
        Init: function()
        {
            this.data = [];
            if (this.inferRow && !(arguments.length === 1 && arguments[0].inferred)) {
                this.Append( a.mrow().With( {inferred: true, notParent: true} ) )
            }
            this.Append.apply( this, arguments )
        },
        With: function( e )
        {
            for (var f in e) {
                if (e.hasOwnProperty( f )) {
                    this[f] = e[f]
                }
            }
            return this
        },
        Append: function()
        {
            if (this.inferRow && this.data.length) {
                this.data[0].Append.apply( this.data[0], arguments )
            } else {
                for (var f = 0, e = arguments.length; f < e; f++) {
                    this.SetData( this.data.length, arguments[f] )
                }
            }
        },
        SetData: function( e, f )
        {
            if (f != null) {
                if (!(f instanceof a.mbase)) {
                    f = (this.isToken || this.isChars ? a.chars( f ) : a.mtext( f ))
                }
                f.parent = this;
                f.setInherit( this.inheritFromMe ? this : this.inherit )
            }
            this.data[e] = f
        },
        Parent: function()
        {
            var e = this.parent;
            while (e && e.notParent) {
                e = e.parent
            }
            return e
        },
        Get: function( f, k, l )
        {
            if (!l) {
                if (this[f] != null) {
                    return this[f]
                }
                if (this.attr && this.attr[f] != null) {
                    return this.attr[f]
                }
            }
            var g = this.Parent();
            if (g && g["adjustChild_" + f] != null) {
                return (g["adjustChild_" + f])( this.childPosition(), k )
            }
            var j = this.inherit;
            var e = j;
            while (j) {
                var i = j[f];
                if (i == null && j.attr) {
                    i = j.attr[f]
                }
                if (i != null && j.noInheritAttribute && !j.noInheritAttribute[f]) {
                    var h = j.noInherit[this.type];
                    if (!(h && h[f])) {
                        return i
                    }
                }
                e = j;
                j = j.inherit
            }
            if (!k) {
                if (this.defaults[f] === a.AUTO) {
                    return this.autoDefault( f )
                }
                if (this.defaults[f] !== a.INHERIT && this.defaults[f] != null) {
                    return this.defaults[f]
                }
                if (e) {
                    return e.defaults[f]
                }
            }
            return null
        },
        hasValue: function( e )
        {
            return (this.Get( e, true ) != null)
        },
        getValues: function()
        {
            var f = {};
            for (var g = 0, e = arguments.length; g < e; g++) {
                f[arguments[g]] = this.Get( arguments[g] )
            }
            return f
        },
        adjustChild_scriptlevel: function( f, e )
        {
            return this.Get( "scriptlevel", e )
        },
        adjustChild_displaystyle: function( f, e )
        {
            return this.Get( "displaystyle", e )
        },
        adjustChild_texprimestyle: function( f, e )
        {
            return this.Get( "texprimestyle", e )
        },
        childPosition: function()
        {
            var h = this, g = h.parent;
            while (g.notParent) {
                h = g;
                g = h.parent
            }
            for (var f = 0, e = g.data.length; f < e; f++) {
                if (g.data[f] === h) {
                    return f
                }
            }
            return null
        },
        setInherit: function( g )
        {
            if (g !== this.inherit && this.inherit == null) {
                this.inherit = g;
                for (var f = 0, e = this.data.length; f < e; f++) {
                    if (this.data[f] && this.data[f].setInherit) {
                        this.data[f].setInherit( g )
                    }
                }
            }
        },
        setTeXclass: function( e )
        {
            this.getPrevClass( e );
            return (typeof(this.texClass) !== "undefined" ? this : e)
        },
        getPrevClass: function( e )
        {
            if (e) {
                this.prevClass = e.Get( "texClass" );
                this.prevLevel = e.Get( "scriptlevel" )
            }
        },
        updateTeXclass: function( e )
        {
            if (e) {
                this.prevClass = e.prevClass;
                delete e.prevClass;
                this.prevLevel = e.prevLevel;
                delete e.prevLevel;
                this.texClass = e.Get( "texClass" )
            }
        },
        texSpacing: function()
        {
            var f = (this.prevClass != null ? this.prevClass : a.TEXCLASS.NONE);
            var e = (this.Get( "texClass" ) || a.TEXCLASS.ORD);
            if (f === a.TEXCLASS.NONE || e === a.TEXCLASS.NONE) {
                return ""
            }
            if (f === a.TEXCLASS.VCENTER) {
                f = a.TEXCLASS.ORD
            }
            if (e === a.TEXCLASS.VCENTER) {
                e = a.TEXCLASS.ORD
            }
            var g = this.TEXSPACE[f][e];
            if (this.prevLevel > 0 && this.Get( "scriptlevel" ) > 0 && g >= 0) {
                return ""
            }
            return this.TEXSPACELENGTH[Math.abs( g )]
        },
        TEXSPACELENGTH: ["", a.LENGTH.THINMATHSPACE, a.LENGTH.MEDIUMMATHSPACE, a.LENGTH.THICKMATHSPACE],
        TEXSPACE: [
            [
                0,
                -1,
                2,
                3,
                0,
                0,
                0,
                1
            ],
            [
                -1,
                -1,
                0,
                3,
                0,
                0,
                0,
                1
            ],
            [
                2,
                2,
                0,
                0,
                2,
                0,
                0,
                2
            ],
            [
                3,
                3,
                0,
                0,
                3,
                0,
                0,
                3
            ],
            [
                0,
                0,
                0,
                0,
                0,
                0,
                0,
                0
            ],
            [
                0,
                -1,
                2,
                3,
                0,
                0,
                0,
                1
            ],
            [
                1,
                1,
                0,
                1,
                1,
                1,
                1,
                1
            ],
            [
                1,
                -1,
                2,
                3,
                1,
                0,
                1,
                1
            ]
        ],
        autoDefault: function( e )
        {
            return ""
        },
        isSpacelike: function()
        {
            return false
        },
        isEmbellished: function()
        {
            return false
        },
        Core: function()
        {
            return this
        },
        CoreMO: function()
        {
            return this
        },
        hasNewline: function()
        {
            if (this.isEmbellished()) {
                return this.CoreMO().hasNewline()
            }
            if (this.isToken || this.linebreakContainer) {
                return false
            }
            for (var f = 0, e = this.data.length; f < e; f++) {
                if (this.data[f] && this.data[f].hasNewline()) {
                    return true
                }
            }
            return false
        },
        array: function()
        {
            if (this.inferred) {
                return this.data
            } else {
                return [this]
            }
        },
        toString: function()
        {
            return this.type + "(" + this.data.join( "," ) + ")"
        },
        getAnnotation: function()
        {
            return null
        }
    }, {
        childrenSpacelike: function()
        {
            for (var f = 0, e = this.data.length; f < e; f++) {
                if (!this.data[f].isSpacelike()) {
                    return false
                }
            }
            return true
        }, childEmbellished: function()
        {
            return (this.data[0] && this.data[0].isEmbellished())
        }, childCore: function()
        {
            return this.data[0]
        }, childCoreMO: function()
        {
            return (this.data[0] ? this.data[0].CoreMO() : null)
        }, setChildTeXclass: function( e )
        {
            if (this.data[0]) {
                e = this.data[0].setTeXclass( e );
                this.updateTeXclass( this.data[0] )
            }
            return e
        }, setBaseTeXclasses: function( g )
        {
            this.getPrevClass( g );
            this.texClass = null;
            if (this.data[0]) {
                if (this.isEmbellished() || this.data[0].isa( a.mi )) {
                    g = this.data[0].setTeXclass( g );
                    this.updateTeXclass( this.Core() )
                } else {
                    this.data[0].setTeXclass();
                    g = this
                }
            } else {
                g = this
            }
            for (var f = 1, e = this.data.length; f < e; f++) {
                if (this.data[f]) {
                    this.data[f].setTeXclass()
                }
            }
            return g
        }, setSeparateTeXclasses: function( g )
        {
            this.getPrevClass( g );
            for (var f = 0, e = this.data.length; f < e; f++) {
                if (this.data[f]) {
                    this.data[f].setTeXclass()
                }
            }
            if (this.isEmbellished()) {
                this.updateTeXclass( this.Core() )
            }
            return this
        }
    } );
    a.mi = a.mbase.Subclass( {
        type: "mi",
        isToken: true,
        texClass: a.TEXCLASS.ORD,
        defaults: {
            mathvariant: a.AUTO,
            mathsize: a.INHERIT,
            mathbackground: a.INHERIT,
            mathcolor: a.INHERIT,
            dir: a.INHERIT
        },
        autoDefault: function( f )
        {
            if (f === "mathvariant") {
                var e = (this.data[0] || "").toString();
                return (e.length === 1 || (e.length === 2 && e.charCodeAt( 0 ) >= 55296 && e.charCodeAt( 0 ) < 56320) ? a.VARIANT.ITALIC : a.VARIANT.NORMAL)
            }
            return ""
        },
        setTeXclass: function( f )
        {
            this.getPrevClass( f );
            var e = this.data.join( "" );
            if (e.length > 1 && e.match( /^[a-z][a-z0-9]*$/i ) && this.texClass === a.TEXCLASS.ORD) {
                this.texClass = a.TEXCLASS.OP;
                this.autoOP = true
            }
            return this
        }
    } );
    a.mn = a.mbase.Subclass( {
        type: "mn",
        isToken: true,
        texClass: a.TEXCLASS.ORD,
        defaults: {
            mathvariant: a.INHERIT,
            mathsize: a.INHERIT,
            mathbackground: a.INHERIT,
            mathcolor: a.INHERIT,
            dir: a.INHERIT
        }
    } );
    a.mo = a.mbase.Subclass( {
        type: "mo",
        isToken: true,
        defaults: {
            mathvariant: a.INHERIT,
            mathsize: a.INHERIT,
            mathbackground: a.INHERIT,
            mathcolor: a.INHERIT,
            dir: a.INHERIT,
            form: a.AUTO,
            fence: a.AUTO,
            separator: a.AUTO,
            lspace: a.AUTO,
            rspace: a.AUTO,
            stretchy: a.AUTO,
            symmetric: a.AUTO,
            maxsize: a.AUTO,
            minsize: a.AUTO,
            largeop: a.AUTO,
            movablelimits: a.AUTO,
            accent: a.AUTO,
            linebreak: a.LINEBREAK.AUTO,
            lineleading: a.INHERIT,
            linebreakstyle: a.AUTO,
            linebreakmultchar: a.INHERIT,
            indentalign: a.INHERIT,
            indentshift: a.INHERIT,
            indenttarget: a.INHERIT,
            indentalignfirst: a.INHERIT,
            indentshiftfirst: a.INHERIT,
            indentalignlast: a.INHERIT,
            indentshiftlast: a.INHERIT,
            texClass: a.AUTO
        },
        defaultDef: {
            form: a.FORM.INFIX,
            fence: false,
            separator: false,
            lspace: a.LENGTH.THICKMATHSPACE,
            rspace: a.LENGTH.THICKMATHSPACE,
            stretchy: false,
            symmetric: false,
            maxsize: a.SIZE.INFINITY,
            minsize: "0em",
            largeop: false,
            movablelimits: false,
            accent: false,
            linebreak: a.LINEBREAK.AUTO,
            lineleading: "1ex",
            linebreakstyle: "before",
            indentalign: a.INDENTALIGN.AUTO,
            indentshift: "0",
            indenttarget: "",
            indentalignfirst: a.INDENTALIGN.INDENTALIGN,
            indentshiftfirst: a.INDENTSHIFT.INDENTSHIFT,
            indentalignlast: a.INDENTALIGN.INDENTALIGN,
            indentshiftlast: a.INDENTSHIFT.INDENTSHIFT,
            texClass: a.TEXCLASS.REL
        },
        SPACE_ATTR: {lspace: 1, rspace: 2, form: 4},
        useMMLspacing: 7,
        autoDefault: function( g, n )
        {
            var l = this.def;
            if (!l) {
                if (g === "form") {
                    this.useMMLspacing &= ~this.SPACE_ATTR.form;
                    return this.getForm()
                }
                var k = this.data.join( "" );
                var f = [this.Get( "form" ), a.FORM.INFIX, a.FORM.POSTFIX, a.FORM.PREFIX];
                for (var h = 0, e = f.length; h < e; h++) {
                    var j = this.OPTABLE[f[h]][k];
                    if (j) {
                        l = this.makeDef( j );
                        break
                    }
                }
                if (!l) {
                    l = this.CheckRange( k )
                }
                if (!l && n) {
                    l = {}
                } else {
                    if (!l) {
                        l = MathJax.Hub.Insert( {}, this.defaultDef )
                    }
                    if (this.parent) {
                        this.def = l
                    } else {
                        l = MathJax.Hub.Insert( {}, l )
                    }
                    l.form = f[0]
                }
            }
            this.useMMLspacing &= ~(this.SPACE_ATTR[g] || 0);
            if (l[g] != null) {
                return l[g]
            } else {
                if (!n) {
                    return this.defaultDef[g]
                }
            }
            return ""
        },
        CheckRange: function( j )
        {
            var k = j.charCodeAt( 0 );
            if (k >= 55296 && k < 56320) {
                k = (((k - 55296) << 10) + (j.charCodeAt( 1 ) - 56320)) + 65536
            }
            for (var g = 0, e = this.RANGES.length; g < e && this.RANGES[g][0] <= k; g++) {
                if (k <= this.RANGES[g][1]) {
                    if (this.RANGES[g][3]) {
                        var f = a.optableDir + "/" + this.RANGES[g][3] + ".js";
                        this.RANGES[g][3] = null;
                        MathJax.Hub.RestartAfter( MathJax.Ajax.Require( f ) )
                    }
                    var h = a.TEXCLASSNAMES[this.RANGES[g][2]];
                    h = this.OPTABLE.infix[j] = a.mo.OPTYPES[h === "BIN" ? "BIN3" : h];
                    return this.makeDef( h )
                }
            }
            return null
        },
        makeDef: function( f )
        {
            if (f[2] == null) {
                f[2] = this.defaultDef.texClass
            }
            if (!f[3]) {
                f[3] = {}
            }
            var e = MathJax.Hub.Insert( {}, f[3] );
            e.lspace = this.SPACE[f[0]];
            e.rspace = this.SPACE[f[1]];
            e.texClass = f[2];
            if (e.texClass === a.TEXCLASS.REL && (this.movablelimits || this.data.join( "" ).match( /^[a-z]+$/i ))) {
                e.texClass = a.TEXCLASS.OP
            }
            return e
        },
        getForm: function()
        {
            var e = this, g = this.parent, f = this.Parent();
            while (f && f.isEmbellished()) {
                e = g;
                g = f.parent;
                f = f.Parent()
            }
            if (g && g.type === "mrow" && g.NonSpaceLength() !== 1) {
                if (g.FirstNonSpace() === e) {
                    return a.FORM.PREFIX
                }
                if (g.LastNonSpace() === e) {
                    return a.FORM.POSTFIX
                }
            }
            return a.FORM.INFIX
        },
        isEmbellished: function()
        {
            return true
        },
        hasNewline: function()
        {
            return (this.Get( "linebreak" ) === a.LINEBREAK.NEWLINE)
        },
        CoreParent: function()
        {
            var e = this;
            while (e && e.isEmbellished() && e.CoreMO() === this && !e.isa( a.math )) {
                e = e.Parent()
            }
            return e
        },
        CoreText: function( e )
        {
            if (!e) {
                return ""
            }
            if (e.isEmbellished()) {
                return e.CoreMO().data.join( "" )
            }
            while ((((e.isa( a.mrow ) || e.isa( a.TeXAtom ) || e.isa( a.mstyle ) || e.isa( a.mphantom )) && e.data.length === 1) || e.isa( a.munderover )) && e.data[0]) {
                e = e.data[0]
            }
            if (!e.isToken) {
                return ""
            } else {
                return e.data.join( "" )
            }
        },
        remapChars: {
            "*": "\u2217",
            '"': "\u2033",
            "\u00B0": "\u2218",
            "\u00B2": "2",
            "\u00B3": "3",
            "\u00B4": "\u2032",
            "\u00B9": "1"
        },
        remap: function( f, e )
        {
            f = f.replace( /-/g, "\u2212" );
            if (e) {
                f = f.replace( /'/g, "\u2032" ).replace( /`/g, "\u2035" );
                if (f.length === 1) {
                    f = e[f] || f
                }
            }
            return f
        },
        setTeXclass: function( f )
        {
            var e = this.getValues( "form", "lspace", "rspace", "fence" );
            if (this.useMMLspacing) {
                this.texClass = a.TEXCLASS.NONE;
                return this
            }
            if (e.fence && !this.texClass) {
                if (e.form === a.FORM.PREFIX) {
                    this.texClass = a.TEXCLASS.OPEN
                }
                if (e.form === a.FORM.POSTFIX) {
                    this.texClass = a.TEXCLASS.CLOSE
                }
            }
            this.texClass = this.Get( "texClass" );
            if (this.data.join( "" ) === "\u2061") {
                if (f) {
                    f.texClass = a.TEXCLASS.OP;
                    f.fnOP = true
                }
                this.texClass = this.prevClass = a.TEXCLASS.NONE;
                return f
            }
            return this.adjustTeXclass( f )
        },
        adjustTeXclass: function( e )
        {
            if (this.texClass === a.TEXCLASS.NONE) {
                return e
            }
            if (e) {
                if (e.autoOP && (this.texClass === a.TEXCLASS.BIN || this.texClass === a.TEXCLASS.REL)) {
                    e.texClass = a.TEXCLASS.ORD
                }
                this.prevClass = e.texClass || a.TEXCLASS.ORD;
                this.prevLevel = e.Get( "scriptlevel" )
            } else {
                this.prevClass = a.TEXCLASS.NONE
            }
            if (this.texClass === a.TEXCLASS.BIN && (this.prevClass === a.TEXCLASS.NONE || this.prevClass === a.TEXCLASS.BIN || this.prevClass === a.TEXCLASS.OP || this.prevClass === a.TEXCLASS.REL || this.prevClass === a.TEXCLASS.OPEN || this.prevClass === a.TEXCLASS.PUNCT)) {
                this.texClass = a.TEXCLASS.ORD
            } else {
                if (this.prevClass === a.TEXCLASS.BIN && (this.texClass === a.TEXCLASS.REL || this.texClass === a.TEXCLASS.CLOSE || this.texClass === a.TEXCLASS.PUNCT)) {
                    e.texClass = this.prevClass = a.TEXCLASS.ORD
                }
            }
            return this
        }
    } );
    a.mtext = a.mbase.Subclass( {
        type: "mtext",
        isToken: true,
        isSpacelike: function()
        {
            return true
        },
        texClass: a.TEXCLASS.ORD,
        defaults: {
            mathvariant: a.INHERIT,
            mathsize: a.INHERIT,
            mathbackground: a.INHERIT,
            mathcolor: a.INHERIT,
            dir: a.INHERIT
        }
    } );
    a.mspace = a.mbase.Subclass( {
        type: "mspace",
        isToken: true,
        isSpacelike: function()
        {
            return true
        },
        defaults: {
            mathbackground: a.INHERIT,
            mathcolor: a.INHERIT,
            width: "0em",
            height: "0ex",
            depth: "0ex",
            linebreak: a.LINEBREAK.AUTO
        },
        hasDimAttr: function()
        {
            return (this.hasValue( "width" ) || this.hasValue( "height" ) || this.hasValue( "depth" ))
        },
        hasNewline: function()
        {
            return (!this.hasDimAttr() && this.Get( "linebreak" ) === a.LINEBREAK.NEWLINE)
        }
    } );
    a.ms = a.mbase.Subclass( {
        type: "ms",
        isToken: true,
        texClass: a.TEXCLASS.ORD,
        defaults: {
            mathvariant: a.INHERIT,
            mathsize: a.INHERIT,
            mathbackground: a.INHERIT,
            mathcolor: a.INHERIT,
            dir: a.INHERIT,
            lquote: '"',
            rquote: '"'
        }
    } );
    a.mglyph = a.mbase.Subclass( {
        type: "mglyph",
        isToken: true,
        texClass: a.TEXCLASS.ORD,
        defaults: {
            mathbackground: a.INHERIT,
            mathcolor: a.INHERIT,
            alt: "",
            src: "",
            width: a.AUTO,
            height: a.AUTO,
            valign: "0em"
        }
    } );
    a.mrow = a.mbase.Subclass( {
        type: "mrow",
        isSpacelike: a.mbase.childrenSpacelike,
        inferred: false,
        notParent: false,
        isEmbellished: function()
        {
            var f = false;
            for (var g = 0, e = this.data.length; g < e; g++) {
                if (this.data[g] == null) {
                    continue
                }
                if (this.data[g].isEmbellished()) {
                    if (f) {
                        return false
                    }
                    f = true;
                    this.core = g
                } else {
                    if (!this.data[g].isSpacelike()) {
                        return false
                    }
                }
            }
            return f
        },
        NonSpaceLength: function()
        {
            var g = 0;
            for (var f = 0, e = this.data.length; f < e; f++) {
                if (this.data[f] && !this.data[f].isSpacelike()) {
                    g++
                }
            }
            return g
        },
        FirstNonSpace: function()
        {
            for (var f = 0, e = this.data.length; f < e; f++) {
                if (this.data[f] && !this.data[f].isSpacelike()) {
                    return this.data[f]
                }
            }
            return null
        },
        LastNonSpace: function()
        {
            for (var e = this.data.length - 1; e >= 0; e--) {
                if (this.data[0] && !this.data[e].isSpacelike()) {
                    return this.data[e]
                }
            }
            return null
        },
        Core: function()
        {
            if (!(this.isEmbellished()) || typeof(this.core) === "undefined") {
                return this
            }
            return this.data[this.core]
        },
        CoreMO: function()
        {
            if (!(this.isEmbellished()) || typeof(this.core) === "undefined") {
                return this
            }
            return this.data[this.core].CoreMO()
        },
        toString: function()
        {
            if (this.inferred) {
                return "[" + this.data.join( "," ) + "]"
            }
            return this.SUPER( arguments ).toString.call( this )
        },
        setTeXclass: function( g )
        {
            var f, e = this.data.length;
            if ((this.open || this.close) && (!g || !g.fnOP)) {
                this.getPrevClass( g );
                g = null;
                for (f = 0; f < e; f++) {
                    if (this.data[f]) {
                        g = this.data[f].setTeXclass( g )
                    }
                }
                if (!this.hasOwnProperty( "texClass" )) {
                    this.texClass = a.TEXCLASS.INNER
                }
                return this
            } else {
                for (f = 0; f < e; f++) {
                    if (this.data[f]) {
                        g = this.data[f].setTeXclass( g )
                    }
                }
                if (this.data[0]) {
                    this.updateTeXclass( this.data[0] )
                }
                return g
            }
        },
        getAnnotation: function( e )
        {
            if (this.data.length != 1) {
                return null
            }
            return this.data[0].getAnnotation( e )
        }
    } );
    a.mfrac = a.mbase.Subclass( {
        type: "mfrac",
        num: 0,
        den: 1,
        linebreakContainer: true,
        isEmbellished: a.mbase.childEmbellished,
        Core: a.mbase.childCore,
        CoreMO: a.mbase.childCoreMO,
        defaults: {
            mathbackground: a.INHERIT,
            mathcolor: a.INHERIT,
            linethickness: a.LINETHICKNESS.MEDIUM,
            numalign: a.ALIGN.CENTER,
            denomalign: a.ALIGN.CENTER,
            bevelled: false
        },
        adjustChild_displaystyle: function( e )
        {
            return false
        },
        adjustChild_scriptlevel: function( f )
        {
            var e = this.Get( "scriptlevel" );
            if (!this.Get( "displaystyle" ) || e > 0) {
                e++
            }
            return e
        },
        adjustChild_texprimestyle: function( e )
        {
            if (e == this.den) {
                return true
            }
            return this.Get( "texprimestyle" )
        },
        setTeXclass: a.mbase.setSeparateTeXclasses
    } );
    a.msqrt = a.mbase.Subclass( {
        type: "msqrt",
        inferRow: true,
        linebreakContainer: true,
        texClass: a.TEXCLASS.ORD,
        setTeXclass: a.mbase.setSeparateTeXclasses,
        adjustChild_texprimestyle: function( e )
        {
            return true
        }
    } );
    a.mroot = a.mbase.Subclass( {
        type: "mroot",
        linebreakContainer: true,
        texClass: a.TEXCLASS.ORD,
        adjustChild_displaystyle: function( e )
        {
            if (e === 1) {
                return false
            }
            return this.Get( "displaystyle" )
        },
        adjustChild_scriptlevel: function( f )
        {
            var e = this.Get( "scriptlevel" );
            if (f === 1) {
                e += 2
            }
            return e
        },
        adjustChild_texprimestyle: function( e )
        {
            if (e === 0) {
                return true
            }
            return this.Get( "texprimestyle" )
        },
        setTeXclass: a.mbase.setSeparateTeXclasses
    } );
    a.mstyle = a.mbase.Subclass( {
        type: "mstyle",
        isSpacelike: a.mbase.childrenSpacelike,
        isEmbellished: a.mbase.childEmbellished,
        Core: a.mbase.childCore,
        CoreMO: a.mbase.childCoreMO,
        inferRow: true,
        defaults: {
            scriptlevel: a.INHERIT,
            displaystyle: a.INHERIT,
            scriptsizemultiplier: Math.sqrt( 1 / 2 ),
            scriptminsize: "8pt",
            mathbackground: a.INHERIT,
            mathcolor: a.INHERIT,
            dir: a.INHERIT,
            infixlinebreakstyle: a.LINEBREAKSTYLE.BEFORE,
            decimalseparator: "."
        },
        adjustChild_scriptlevel: function( g )
        {
            var f = this.scriptlevel;
            if (f == null) {
                f = this.Get( "scriptlevel" )
            } else {
                if (String( f ).match( /^ *[-+]/ )) {
                    delete this.scriptlevel;
                    var e = this.Get( "scriptlevel" );
                    this.scriptlevel = f;
                    f = e + parseInt( f )
                }
            }
            return f
        },
        inheritFromMe: true,
        noInherit: {
            mpadded: {width: true, height: true, depth: true, lspace: true, voffset: true},
            mtable: {width: true, height: true, depth: true, align: true}
        },
        setTeXclass: a.mbase.setChildTeXclass
    } );
    a.merror = a.mbase.Subclass( {type: "merror", inferRow: true, linebreakContainer: true, texClass: a.TEXCLASS.ORD} );
    a.mpadded = a.mbase.Subclass( {
        type: "mpadded",
        inferRow: true,
        isSpacelike: a.mbase.childrenSpacelike,
        isEmbellished: a.mbase.childEmbellished,
        Core: a.mbase.childCore,
        CoreMO: a.mbase.childCoreMO,
        defaults: {
            mathbackground: a.INHERIT,
            mathcolor: a.INHERIT,
            width: "",
            height: "",
            depth: "",
            lspace: 0,
            voffset: 0
        },
        setTeXclass: a.mbase.setChildTeXclass
    } );
    a.mphantom = a.mbase.Subclass( {
        type: "mphantom",
        texClass: a.TEXCLASS.ORD,
        inferRow: true,
        isSpacelike: a.mbase.childrenSpacelike,
        isEmbellished: a.mbase.childEmbellished,
        Core: a.mbase.childCore,
        CoreMO: a.mbase.childCoreMO,
        setTeXclass: a.mbase.setChildTeXclass
    } );
    a.mfenced = a.mbase.Subclass( {
        type: "mfenced",
        defaults: {mathbackground: a.INHERIT, mathcolor: a.INHERIT, open: "(", close: ")", separators: ","},
        addFakeNodes: function()
        {
            var f = this.getValues( "open", "close", "separators" );
            f.open = f.open.replace( /[ \t\n\r]/g, "" );
            f.close = f.close.replace( /[ \t\n\r]/g, "" );
            f.separators = f.separators.replace( /[ \t\n\r]/g, "" );
            if (f.open !== "") {
                this.SetData( "open",
                    a.mo( f.open ).With( {fence: true, form: a.FORM.PREFIX, texClass: a.TEXCLASS.OPEN} ) );
                this.data.open.useMMLspacing &= ~this.data.open.SPACE_ATTR.form
            }
            if (f.separators !== "") {
                while (f.separators.length < this.data.length) {
                    f.separators += f.separators.charAt( f.separators.length - 1 )
                }
                for (var g = 1, e = this.data.length; g < e; g++) {
                    if (this.data[g]) {
                        this.SetData( "sep" + g, a.mo( f.separators.charAt( g - 1 ) ).With( {separator: true} ) )
                    }
                }
            }
            if (f.close !== "") {
                this.SetData( "close",
                    a.mo( f.close ).With( {fence: true, form: a.FORM.POSTFIX, texClass: a.TEXCLASS.CLOSE} ) );
                this.data.close.useMMLspacing &= ~this.data.close.SPACE_ATTR.form
            }
        },
        texClass: a.TEXCLASS.OPEN,
        setTeXclass: function( g )
        {
            this.addFakeNodes();
            this.getPrevClass( g );
            if (this.data.open) {
                g = this.data.open.setTeXclass( g )
            }
            if (this.data[0]) {
                g = this.data[0].setTeXclass( g )
            }
            for (var f = 1, e = this.data.length; f < e; f++) {
                if (this.data["sep" + f]) {
                    g = this.data["sep" + f].setTeXclass( g )
                }
                if (this.data[f]) {
                    g = this.data[f].setTeXclass( g )
                }
            }
            if (this.data.close) {
                g = this.data.close.setTeXclass( g )
            }
            this.updateTeXclass( this.data.open );
            this.texClass = a.TEXCLASS.INNER;
            return g
        }
    } );
    a.menclose = a.mbase.Subclass( {
        type: "menclose",
        inferRow: true,
        linebreakContainer: true,
        defaults: {
            mathbackground: a.INHERIT,
            mathcolor: a.INHERIT,
            notation: a.NOTATION.LONGDIV,
            texClass: a.TEXCLASS.ORD
        },
        setTeXclass: a.mbase.setSeparateTeXclasses
    } );
    a.msubsup = a.mbase.Subclass( {
        type: "msubsup",
        base: 0,
        sub: 1,
        sup: 2,
        isEmbellished: a.mbase.childEmbellished,
        Core: a.mbase.childCore,
        CoreMO: a.mbase.childCoreMO,
        defaults: {
            mathbackground: a.INHERIT,
            mathcolor: a.INHERIT,
            subscriptshift: "",
            superscriptshift: "",
            texClass: a.AUTO
        },
        autoDefault: function( e )
        {
            if (e === "texClass") {
                return (this.isEmbellished() ? this.CoreMO().Get( e ) : a.TEXCLASS.ORD)
            }
            return 0
        },
        adjustChild_displaystyle: function( e )
        {
            if (e > 0) {
                return false
            }
            return this.Get( "displaystyle" )
        },
        adjustChild_scriptlevel: function( f )
        {
            var e = this.Get( "scriptlevel" );
            if (f > 0) {
                e++
            }
            return e
        },
        adjustChild_texprimestyle: function( e )
        {
            if (e === this.sub) {
                return true
            }
            return this.Get( "texprimestyle" )
        },
        setTeXclass: a.mbase.setBaseTeXclasses
    } );
    a.msub = a.msubsup.Subclass( {type: "msub"} );
    a.msup = a.msubsup.Subclass( {type: "msup", sub: 2, sup: 1} );
    a.mmultiscripts = a.msubsup.Subclass( {
        type: "mmultiscripts", adjustChild_texprimestyle: function( e )
        {
            if (e % 2 === 1) {
                return true
            }
            return this.Get( "texprimestyle" )
        }
    } );
    a.mprescripts = a.mbase.Subclass( {type: "mprescripts"} );
    a.none = a.mbase.Subclass( {type: "none"} );
    a.munderover = a.mbase.Subclass( {
        type: "munderover",
        base: 0,
        under: 1,
        over: 2,
        sub: 1,
        sup: 2,
        ACCENTS: ["", "accentunder", "accent"],
        linebreakContainer: true,
        isEmbellished: a.mbase.childEmbellished,
        Core: a.mbase.childCore,
        CoreMO: a.mbase.childCoreMO,
        defaults: {
            mathbackground: a.INHERIT,
            mathcolor: a.INHERIT,
            accent: a.AUTO,
            accentunder: a.AUTO,
            align: a.ALIGN.CENTER,
            texClass: a.AUTO,
            subscriptshift: "",
            superscriptshift: ""
        },
        autoDefault: function( e )
        {
            if (e === "texClass") {
                return (this.isEmbellished() ? this.CoreMO().Get( e ) : a.TEXCLASS.ORD)
            }
            if (e === "accent" && this.data[this.over]) {
                return this.data[this.over].CoreMO().Get( "accent" )
            }
            if (e === "accentunder" && this.data[this.under]) {
                return this.data[this.under].CoreMO().Get( "accent" )
            }
            return false
        },
        adjustChild_displaystyle: function( e )
        {
            if (e > 0) {
                return false
            }
            return this.Get( "displaystyle" )
        },
        adjustChild_scriptlevel: function( g )
        {
            var f = this.Get( "scriptlevel" );
            var e = (this.data[this.base] && !this.Get( "displaystyle" ) && this.data[this.base].CoreMO().Get( "movablelimits" ));
            if (g == this.under && (e || !this.Get( "accentunder" ))) {
                f++
            }
            if (g == this.over && (e || !this.Get( "accent" ))) {
                f++
            }
            return f
        },
        adjustChild_texprimestyle: function( e )
        {
            if (e === this.base && this.data[this.over]) {
                return true
            }
            return this.Get( "texprimestyle" )
        },
        setTeXclass: a.mbase.setBaseTeXclasses
    } );
    a.munder = a.munderover.Subclass( {type: "munder"} );
    a.mover = a.munderover.Subclass( {
        type: "mover",
        over: 1,
        under: 2,
        sup: 1,
        sub: 2,
        ACCENTS: ["", "accent", "accentunder"]
    } );
    a.mtable = a.mbase.Subclass( {
        type: "mtable",
        defaults: {
            mathbackground: a.INHERIT,
            mathcolor: a.INHERIT,
            align: a.ALIGN.AXIS,
            rowalign: a.ALIGN.BASELINE,
            columnalign: a.ALIGN.CENTER,
            groupalign: "{left}",
            alignmentscope: true,
            columnwidth: a.WIDTH.AUTO,
            width: a.WIDTH.AUTO,
            rowspacing: "1ex",
            columnspacing: ".8em",
            rowlines: a.LINES.NONE,
            columnlines: a.LINES.NONE,
            frame: a.LINES.NONE,
            framespacing: "0.4em 0.5ex",
            equalrows: false,
            equalcolumns: false,
            displaystyle: false,
            side: a.SIDE.RIGHT,
            minlabelspacing: "0.8em",
            texClass: a.TEXCLASS.ORD,
            useHeight: 1
        },
        adjustChild_displaystyle: function()
        {
            return (this.displaystyle != null ? this.displaystyle : this.defaults.displaystyle)
        },
        inheritFromMe: true,
        noInherit: {
            mover: {align: true},
            munder: {align: true},
            munderover: {align: true},
            mtable: {
                align: true,
                rowalign: true,
                columnalign: true,
                groupalign: true,
                alignmentscope: true,
                columnwidth: true,
                width: true,
                rowspacing: true,
                columnspacing: true,
                rowlines: true,
                columnlines: true,
                frame: true,
                framespacing: true,
                equalrows: true,
                equalcolumns: true,
                displaystyle: true,
                side: true,
                minlabelspacing: true,
                texClass: true,
                useHeight: 1
            }
        },
        linebreakContainer: true,
        Append: function()
        {
            for (var f = 0, e = arguments.length; f < e; f++) {
                if (!((arguments[f] instanceof a.mtr) || (arguments[f] instanceof a.mlabeledtr))) {
                    arguments[f] = a.mtd( arguments[f] )
                }
            }
            this.SUPER( arguments ).Append.apply( this, arguments )
        },
        setTeXclass: a.mbase.setSeparateTeXclasses
    } );
    a.mtr = a.mbase.Subclass( {
        type: "mtr",
        defaults: {
            mathbackground: a.INHERIT,
            mathcolor: a.INHERIT,
            rowalign: a.INHERIT,
            columnalign: a.INHERIT,
            groupalign: a.INHERIT
        },
        inheritFromMe: true,
        noInherit: {
            mrow: {rowalign: true, columnalign: true, groupalign: true},
            mtable: {rowalign: true, columnalign: true, groupalign: true}
        },
        linebreakContainer: true,
        Append: function()
        {
            for (var f = 0, e = arguments.length; f < e; f++) {
                if (!(arguments[f] instanceof a.mtd)) {
                    arguments[f] = a.mtd( arguments[f] )
                }
            }
            this.SUPER( arguments ).Append.apply( this, arguments )
        },
        setTeXclass: a.mbase.setSeparateTeXclasses
    } );
    a.mtd = a.mbase.Subclass( {
        type: "mtd",
        inferRow: true,
        linebreakContainer: true,
        isEmbellished: a.mbase.childEmbellished,
        Core: a.mbase.childCore,
        CoreMO: a.mbase.childCoreMO,
        defaults: {
            mathbackground: a.INHERIT,
            mathcolor: a.INHERIT,
            rowspan: 1,
            columnspan: 1,
            rowalign: a.INHERIT,
            columnalign: a.INHERIT,
            groupalign: a.INHERIT
        },
        setTeXclass: a.mbase.setSeparateTeXclasses
    } );
    a.maligngroup = a.mbase.Subclass( {
        type: "malign",
        isSpacelike: function()
        {
            return true
        },
        defaults: {mathbackground: a.INHERIT, mathcolor: a.INHERIT, groupalign: a.INHERIT},
        inheritFromMe: true,
        noInherit: {mrow: {groupalign: true}, mtable: {groupalign: true}}
    } );
    a.malignmark = a.mbase.Subclass( {
        type: "malignmark",
        defaults: {mathbackground: a.INHERIT, mathcolor: a.INHERIT, edge: a.SIDE.LEFT},
        isSpacelike: function()
        {
            return true
        }
    } );
    a.mlabeledtr = a.mtr.Subclass( {type: "mlabeledtr"} );
    a.maction = a.mbase.Subclass( {
        type: "maction",
        defaults: {mathbackground: a.INHERIT, mathcolor: a.INHERIT, actiontype: a.ACTIONTYPE.TOGGLE, selection: 1},
        selected: function()
        {
            return this.data[this.Get( "selection" ) - 1] || a.NULL
        },
        isEmbellished: function()
        {
            return this.selected().isEmbellished()
        },
        isSpacelike: function()
        {
            return this.selected().isSpacelike()
        },
        Core: function()
        {
            return this.selected().Core()
        },
        CoreMO: function()
        {
            return this.selected().CoreMO()
        },
        setTeXclass: function( e )
        {
            if (this.Get( "actiontype" ) === a.ACTIONTYPE.TOOLTIP && this.data[1]) {
                this.data[1].setTeXclass()
            }
            return this.selected().setTeXclass( e )
        }
    } );
    a.semantics = a.mbase.Subclass( {
        type: "semantics",
        notParent: true,
        isEmbellished: a.mbase.childEmbellished,
        Core: a.mbase.childCore,
        CoreMO: a.mbase.childCoreMO,
        defaults: {definitionURL: null, encoding: null},
        setTeXclass: a.mbase.setChildTeXclass,
        getAnnotation: function( g )
        {
            var l = MathJax.Hub.config.MathMenu.semanticsAnnotations[g];
            if (l) {
                for (var h = 0, e = this.data.length; h < e; h++) {
                    var k = this.data[h].Get( "encoding" );
                    if (k) {
                        for (var f = 0, o = l.length; f < o; f++) {
                            if (l[f] === k) {
                                return this.data[h]
                            }
                        }
                    }
                }
            }
            return null
        }
    } );
    a.annotation = a.mbase.Subclass( {
        type: "annotation",
        isChars: true,
        linebreakContainer: true,
        defaults: {definitionURL: null, encoding: null, cd: "mathmlkeys", name: "", src: null}
    } );
    a["annotation-xml"] = a.mbase.Subclass( {
        type: "annotation-xml",
        linebreakContainer: true,
        defaults: {definitionURL: null, encoding: null, cd: "mathmlkeys", name: "", src: null}
    } );
    a.math = a.mstyle.Subclass( {
        type: "math",
        defaults: {
            mathvariant: a.VARIANT.NORMAL,
            mathsize: a.SIZE.NORMAL,
            mathcolor: "",
            mathbackground: a.COLOR.TRANSPARENT,
            dir: "ltr",
            scriptlevel: 0,
            displaystyle: a.AUTO,
            display: "inline",
            maxwidth: "",
            overflow: a.OVERFLOW.LINEBREAK,
            altimg: "",
            "altimg-width": "",
            "altimg-height": "",
            "altimg-valign": "",
            alttext: "",
            cdgroup: "",
            scriptsizemultiplier: Math.sqrt( 1 / 2 ),
            scriptminsize: "8px",
            infixlinebreakstyle: a.LINEBREAKSTYLE.BEFORE,
            lineleading: "1ex",
            indentshift: "auto",
            indentalign: a.INDENTALIGN.AUTO,
            indentalignfirst: a.INDENTALIGN.INDENTALIGN,
            indentshiftfirst: a.INDENTSHIFT.INDENTSHIFT,
            indentalignlast: a.INDENTALIGN.INDENTALIGN,
            indentshiftlast: a.INDENTSHIFT.INDENTSHIFT,
            decimalseparator: ".",
            texprimestyle: false
        },
        autoDefault: function( e )
        {
            if (e === "displaystyle") {
                return this.Get( "display" ) === "block"
            }
            return ""
        },
        linebreakContainer: true,
        setTeXclass: a.mbase.setChildTeXclass,
        getAnnotation: function( e )
        {
            if (this.data.length != 1) {
                return null
            }
            return this.data[0].getAnnotation( e )
        }
    } );
    a.chars = a.mbase.Subclass( {
        type: "chars", Append: function()
        {
            this.data.push.apply( this.data, arguments )
        }, value: function()
        {
            return this.data.join( "" )
        }, toString: function()
        {
            return this.data.join( "" )
        }
    } );
    a.entity = a.mbase.Subclass( {
        type: "entity", Append: function()
        {
            this.data.push.apply( this.data, arguments )
        }, value: function()
        {
            if (this.data[0].substr( 0, 2 ) === "#x") {
                return parseInt( this.data[0].substr( 2 ), 16 )
            } else {
                if (this.data[0].substr( 0, 1 ) === "#") {
                    return parseInt( this.data[0].substr( 1 ) )
                } else {
                    return 0
                }
            }
        }, toString: function()
        {
            var e = this.value();
            if (e <= 65535) {
                return String.fromCharCode( e )
            }
            e -= 65536;
            return String.fromCharCode( (e >> 10) + 55296 ) + String.fromCharCode( (e & 1023) + 56320 )
        }
    } );
    a.xml = a.mbase.Subclass( {
        type: "xml", Init: function()
        {
            this.div = document.createElement( "div" );
            return this.SUPER( arguments ).Init.apply( this, arguments )
        }, Append: function()
        {
            for (var f = 0, e = arguments.length; f < e; f++) {
                var g = this.Import( arguments[f] );
                this.data.push( g );
                this.div.appendChild( g )
            }
        }, Import: function( j )
        {
            if (document.importNode) {
                return document.importNode( j, true )
            }
            var f, g, e;
            if (j.nodeType === 1) {
                f = document.createElement( j.nodeName );
                for (g = 0, e = j.attributes.length; g < e; g++) {
                    var h = j.attributes[g];
                    if (h.specified && h.nodeValue != null && h.nodeValue != "") {
                        f.setAttribute( h.nodeName, h.nodeValue )
                    }
                    if (h.nodeName === "style") {
                        f.style.cssText = h.nodeValue
                    }
                }
                if (j.className) {
                    f.className = j.className
                }
            } else {
                if (j.nodeType === 3 || j.nodeType === 4) {
                    f = document.createTextNode( j.nodeValue )
                } else {
                    if (j.nodeType === 8) {
                        f = document.createComment( j.nodeValue )
                    } else {
                        return document.createTextNode( "" )
                    }
                }
            }
            for (g = 0, e = j.childNodes.length; g < e; g++) {
                f.appendChild( this.Import( j.childNodes[g] ) )
            }
            return f
        }, value: function()
        {
            return this.div
        }, toString: function()
        {
            return this.div.innerHTML
        }
    } );
    a.TeXAtom = a.mbase.Subclass( {
        type: "texatom",
        inferRow: true,
        notParent: true,
        texClass: a.TEXCLASS.ORD,
        Core: a.mbase.childCore,
        CoreMO: a.mbase.childCoreMO,
        isEmbellished: a.mbase.childEmbellished,
        setTeXclass: function( e )
        {
            this.data[0].setTeXclass();
            return this.adjustTeXclass( e )
        },
        adjustTeXclass: a.mo.prototype.adjustTeXclass
    } );
    a.NULL = a.mbase().With( {type: "null"} );
    var b = a.TEXCLASS;
    var d = {
        ORD: [0, 0, b.ORD],
        ORD11: [1, 1, b.ORD],
        ORD21: [2, 1, b.ORD],
        ORD02: [0, 2, b.ORD],
        ORD55: [5, 5, b.ORD],
        OP: [1, 2, b.OP, {largeop: true, movablelimits: true, symmetric: true}],
        OPFIXED: [1, 2, b.OP, {largeop: true, movablelimits: true}],
        INTEGRAL: [0, 1, b.OP, {largeop: true, symmetric: true}],
        INTEGRAL2: [1, 2, b.OP, {largeop: true, symmetric: true}],
        BIN3: [3, 3, b.BIN],
        BIN4: [4, 4, b.BIN],
        BIN01: [0, 1, b.BIN],
        BIN5: [5, 5, b.BIN],
        TALLBIN: [4, 4, b.BIN, {stretchy: true}],
        BINOP: [4, 4, b.BIN, {largeop: true, movablelimits: true}],
        REL: [5, 5, b.REL],
        REL1: [1, 1, b.REL, {stretchy: true}],
        REL4: [4, 4, b.REL],
        RELSTRETCH: [5, 5, b.REL, {stretchy: true}],
        RELACCENT: [5, 5, b.REL, {accent: true}],
        WIDEREL: [5, 5, b.REL, {accent: true, stretchy: true}],
        OPEN: [0, 0, b.OPEN, {fence: true, stretchy: true, symmetric: true}],
        CLOSE: [0, 0, b.CLOSE, {fence: true, stretchy: true, symmetric: true}],
        INNER: [0, 0, b.INNER],
        PUNCT: [0, 3, b.PUNCT],
        ACCENT: [0, 0, b.ORD, {accent: true}],
        WIDEACCENT: [0, 0, b.ORD, {accent: true, stretchy: true}]
    };
    a.mo.Augment( {
        SPACE: ["0em", "0.1111em", "0.1667em", "0.2222em", "0.2667em", "0.3333em"],
        RANGES: [
            [
                32,
                127,
                b.REL,
                "BasicLatin"
            ],
            [
                160,
                255,
                b.ORD,
                "Latin1Supplement"
            ],
            [
                256,
                383,
                b.ORD
            ],
            [
                384,
                591,
                b.ORD
            ],
            [
                688,
                767,
                b.ORD,
                "SpacingModLetters"
            ],
            [
                768,
                879,
                b.ORD,
                "CombDiacritMarks"
            ],
            [
                880,
                1023,
                b.ORD,
                "GreekAndCoptic"
            ],
            [
                7680,
                7935,
                b.ORD
            ],
            [
                8192,
                8303,
                b.PUNCT,
                "GeneralPunctuation"
            ],
            [
                8304,
                8351,
                b.ORD
            ],
            [
                8352,
                8399,
                b.ORD
            ],
            [
                8400,
                8447,
                b.ORD,
                "CombDiactForSymbols"
            ],
            [
                8448,
                8527,
                b.ORD,
                "LetterlikeSymbols"
            ],
            [
                8528,
                8591,
                b.ORD
            ],
            [
                8592,
                8703,
                b.REL,
                "Arrows"
            ],
            [
                8704,
                8959,
                b.BIN,
                "MathOperators"
            ],
            [
                8960,
                9215,
                b.ORD,
                "MiscTechnical"
            ],
            [
                9312,
                9471,
                b.ORD
            ],
            [
                9472,
                9631,
                b.ORD
            ],
            [
                9632,
                9727,
                b.ORD,
                "GeometricShapes"
            ],
            [
                9984,
                10175,
                b.ORD,
                "Dingbats"
            ],
            [
                10176,
                10223,
                b.ORD,
                "MiscMathSymbolsA"
            ],
            [
                10224,
                10239,
                b.REL,
                "SupplementalArrowsA"
            ],
            [
                10496,
                10623,
                b.REL,
                "SupplementalArrowsB"
            ],
            [
                10624,
                10751,
                b.ORD,
                "MiscMathSymbolsB"
            ],
            [
                10752,
                11007,
                b.BIN,
                "SuppMathOperators"
            ],
            [
                11008,
                11263,
                b.ORD,
                "MiscSymbolsAndArrows"
            ],
            [
                119808,
                120831,
                b.ORD
            ]
        ],
        OPTABLE: {
            prefix: {
                "\u2200": d.ORD21,
                "\u2202": d.ORD21,
                "\u2203": d.ORD21,
                "\u2207": d.ORD21,
                "\u220F": d.OP,
                "\u2210": d.OP,
                "\u2211": d.OP,
                "\u2212": d.BIN01,
                "\u2213": d.BIN01,
                "\u221A": [1, 1, b.ORD, {stretchy: true}],
                "\u2220": d.ORD,
                "\u222B": d.INTEGRAL,
                "\u222E": d.INTEGRAL,
                "\u22C0": d.OP,
                "\u22C1": d.OP,
                "\u22C2": d.OP,
                "\u22C3": d.OP,
                "\u2308": d.OPEN,
                "\u230A": d.OPEN,
                "\u27E8": d.OPEN,
                "\u27EE": d.OPEN,
                "\u2A00": d.OP,
                "\u2A01": d.OP,
                "\u2A02": d.OP,
                "\u2A04": d.OP,
                "\u2A06": d.OP,
                "\u00AC": d.ORD21,
                "\u00B1": d.BIN01,
                "(": d.OPEN,
                "+": d.BIN01,
                "-": d.BIN01,
                "[": d.OPEN,
                "{": d.OPEN,
                "|": d.OPEN
            },
            postfix: {
                "!": [1, 0, b.CLOSE],
                "&": d.ORD,
                "\u2032": d.ORD02,
                "\u203E": d.WIDEACCENT,
                "\u2309": d.CLOSE,
                "\u230B": d.CLOSE,
                "\u23DE": d.WIDEACCENT,
                "\u23DF": d.WIDEACCENT,
                "\u266D": d.ORD02,
                "\u266E": d.ORD02,
                "\u266F": d.ORD02,
                "\u27E9": d.CLOSE,
                "\u27EF": d.CLOSE,
                "\u02C6": d.WIDEACCENT,
                "\u02C7": d.WIDEACCENT,
                "\u02C9": d.WIDEACCENT,
                "\u02CA": d.ACCENT,
                "\u02CB": d.ACCENT,
                "\u02D8": d.ACCENT,
                "\u02D9": d.ACCENT,
                "\u02DC": d.WIDEACCENT,
                "\u0302": d.WIDEACCENT,
                "\u00A8": d.ACCENT,
                "\u00AF": d.WIDEACCENT,
                ")": d.CLOSE,
                "]": d.CLOSE,
                "^": d.WIDEACCENT,
                _: d.WIDEACCENT,
                "`": d.ACCENT,
                "|": d.CLOSE,
                "}": d.CLOSE,
                "~": d.WIDEACCENT
            },
            infix: {
                "": d.ORD,
                "%": [3, 3, b.ORD],
                "\u2022": d.BIN4,
                "\u2026": d.INNER,
                "\u2044": d.TALLBIN,
                "\u2061": d.ORD,
                "\u2062": d.ORD,
                "\u2063": [0, 0, b.ORD, {linebreakstyle: "after", separator: true}],
                "\u2064": d.ORD,
                "\u2190": d.WIDEREL,
                "\u2191": d.RELSTRETCH,
                "\u2192": d.WIDEREL,
                "\u2193": d.RELSTRETCH,
                "\u2194": d.WIDEREL,
                "\u2195": d.RELSTRETCH,
                "\u2196": d.RELSTRETCH,
                "\u2197": d.RELSTRETCH,
                "\u2198": d.RELSTRETCH,
                "\u2199": d.RELSTRETCH,
                "\u21A6": d.WIDEREL,
                "\u21A9": d.WIDEREL,
                "\u21AA": d.WIDEREL,
                "\u21BC": d.WIDEREL,
                "\u21BD": d.WIDEREL,
                "\u21C0": d.WIDEREL,
                "\u21C1": d.WIDEREL,
                "\u21CC": d.WIDEREL,
                "\u21D0": d.WIDEREL,
                "\u21D1": d.RELSTRETCH,
                "\u21D2": d.WIDEREL,
                "\u21D3": d.RELSTRETCH,
                "\u21D4": d.WIDEREL,
                "\u21D5": d.RELSTRETCH,
                "\u2208": d.REL,
                "\u2209": d.REL,
                "\u220B": d.REL,
                "\u2212": d.BIN4,
                "\u2213": d.BIN4,
                "\u2215": d.TALLBIN,
                "\u2216": d.BIN4,
                "\u2217": d.BIN4,
                "\u2218": d.BIN4,
                "\u2219": d.BIN4,
                "\u221D": d.REL,
                "\u2223": d.REL,
                "\u2225": d.REL,
                "\u2227": d.BIN4,
                "\u2228": d.BIN4,
                "\u2229": d.BIN4,
                "\u222A": d.BIN4,
                "\u223C": d.REL,
                "\u2240": d.BIN4,
                "\u2243": d.REL,
                "\u2245": d.REL,
                "\u2248": d.REL,
                "\u224D": d.REL,
                "\u2250": d.REL,
                "\u2260": d.REL,
                "\u2261": d.REL,
                "\u2264": d.REL,
                "\u2265": d.REL,
                "\u226A": d.REL,
                "\u226B": d.REL,
                "\u227A": d.REL,
                "\u227B": d.REL,
                "\u2282": d.REL,
                "\u2283": d.REL,
                "\u2286": d.REL,
                "\u2287": d.REL,
                "\u228E": d.BIN4,
                "\u2291": d.REL,
                "\u2292": d.REL,
                "\u2293": d.BIN4,
                "\u2294": d.BIN4,
                "\u2295": d.BIN4,
                "\u2296": d.BIN4,
                "\u2297": d.BIN4,
                "\u2298": d.BIN4,
                "\u2299": d.BIN4,
                "\u22A2": d.REL,
                "\u22A3": d.REL,
                "\u22A4": d.ORD55,
                "\u22A5": d.REL,
                "\u22A8": d.REL,
                "\u22C4": d.BIN4,
                "\u22C5": d.BIN4,
                "\u22C6": d.BIN4,
                "\u22C8": d.REL,
                "\u22EE": d.ORD55,
                "\u22EF": d.INNER,
                "\u22F1": [5, 5, b.INNER],
                "\u25B3": d.BIN4,
                "\u25B5": d.BIN4,
                "\u25B9": d.BIN4,
                "\u25BD": d.BIN4,
                "\u25BF": d.BIN4,
                "\u25C3": d.BIN4,
                "\u2758": d.REL,
                "\u27F5": d.WIDEREL,
                "\u27F6": d.WIDEREL,
                "\u27F7": d.WIDEREL,
                "\u27F8": d.WIDEREL,
                "\u27F9": d.WIDEREL,
                "\u27FA": d.WIDEREL,
                "\u27FC": d.WIDEREL,
                "\u2A2F": d.BIN4,
                "\u2A3F": d.BIN4,
                "\u2AAF": d.REL,
                "\u2AB0": d.REL,
                "\u00B1": d.BIN4,
                "\u00B7": d.BIN4,
                "\u00D7": d.BIN4,
                "\u00F7": d.BIN4,
                "*": d.BIN3,
                "+": d.BIN4,
                ",": [0, 3, b.PUNCT, {linebreakstyle: "after", separator: true}],
                "-": d.BIN4,
                ".": [3, 3, b.ORD],
                "/": d.ORD11,
                ":": [1, 2, b.REL],
                ";": [0, 3, b.PUNCT, {linebreakstyle: "after", separator: true}],
                "<": d.REL,
                "=": d.REL,
                ">": d.REL,
                "?": [1, 1, b.CLOSE],
                "\\": d.ORD,
                "^": d.ORD11,
                _: d.ORD11,
                "|": [2, 2, b.ORD, {fence: true, stretchy: true, symmetric: true}],
                "#": d.ORD,
                "$": d.ORD,
                "\u002E": [0, 3, b.PUNCT, {separator: true}],
                "\u02B9": d.ORD,
                "\u0300": d.ACCENT,
                "\u0301": d.ACCENT,
                "\u0303": d.WIDEACCENT,
                "\u0304": d.ACCENT,
                "\u0306": d.ACCENT,
                "\u0307": d.ACCENT,
                "\u0308": d.ACCENT,
                "\u030C": d.ACCENT,
                "\u0332": d.WIDEACCENT,
                "\u0338": d.REL4,
                "\u2015": [0, 0, b.ORD, {stretchy: true}],
                "\u2017": [0, 0, b.ORD, {stretchy: true}],
                "\u2020": d.BIN3,
                "\u2021": d.BIN3,
                "\u20D7": d.ACCENT,
                "\u2111": d.ORD,
                "\u2113": d.ORD,
                "\u2118": d.ORD,
                "\u211C": d.ORD,
                "\u2205": d.ORD,
                "\u221E": d.ORD,
                "\u2305": d.BIN3,
                "\u2306": d.BIN3,
                "\u2322": d.REL4,
                "\u2323": d.REL4,
                "\u2329": d.OPEN,
                "\u232A": d.CLOSE,
                "\u23AA": d.ORD,
                "\u23AF": [0, 0, b.ORD, {stretchy: true}],
                "\u23B0": d.OPEN,
                "\u23B1": d.CLOSE,
                "\u2500": d.ORD,
                "\u25EF": d.BIN3,
                "\u2660": d.ORD,
                "\u2661": d.ORD,
                "\u2662": d.ORD,
                "\u2663": d.ORD,
                "\u3008": d.OPEN,
                "\u3009": d.CLOSE,
                "\uFE37": d.WIDEACCENT,
                "\uFE38": d.WIDEACCENT
            }
        }
    }, {OPTYPES: d} );
    var c = a.mo.prototype.OPTABLE;
    c.infix["^"] = d.WIDEREL;
    c.infix._ = d.WIDEREL;
    c.prefix["\u2223"] = d.OPEN;
    c.prefix["\u2225"] = d.OPEN;
    c.postfix["\u2223"] = d.CLOSE;
    c.postfix["\u2225"] = d.CLOSE
})( MathJax.ElementJax.mml );
MathJax.ElementJax.mml.loadComplete( "jax.js" );
MathJax.Hub.Register.LoadHook( "[MathJax]/jax/element/mml/jax.js", function()
{
    var b = "2.5.0";
    var a = MathJax.ElementJax.mml;
    SETTINGS = MathJax.Hub.config.menuSettings;
    a.mbase.Augment( {
        toMathML: function( k )
        {
            var g = (this.inferred && this.parent.inferRow);
            if (k == null) {
                k = ""
            }
            var e = this.type, d = this.toMathMLattributes();
            if (e === "mspace") {
                return k + "<" + e + d + " />"
            }
            var j = [], h = (this.isToken ? "" : k + (g ? "" : "  "));
            for (var f = 0, c = this.data.length; f < c; f++) {
                if (this.data[f]) {
                    j.push( this.data[f].toMathML( h ) )
                } else {
                    if (!this.isToken && !this.isChars) {
                        j.push( h + "<mrow />" )
                    }
                }
            }
            if (this.isToken || this.isChars) {
                return k + "<" + e + d + ">" + j.join( "" ) + "</" + e + ">"
            }
            if (g) {
                return j.join( "\n" )
            }
            if (j.length === 0 || (j.length === 1 && j[0] === "")) {
                return k + "<" + e + d + " />"
            }
            return k + "<" + e + d + ">\n" + j.join( "\n" ) + "\n" + k + "</" + e + ">"
        },
        toMathMLattributes: function()
        {
            var h = (this.type === "mstyle" ? a.math.prototype.defaults : this.defaults);
            var g = (this.attrNames || a.copyAttributeNames), f = a.skipAttributes, k = a.copyAttributes;
            var d = [];
            if (this.type === "math" && (!this.attr || !this.attr.xmlns)) {
                d.push( 'xmlns="http://www.w3.org/1998/Math/MathML"' )
            }
            if (!this.attrNames) {
                for (var j in h) {
                    if (!f[j] && !k[j] && h.hasOwnProperty( j )) {
                        if (this[j] != null && this[j] !== h[j]) {
                            if (this.Get( j, null, 1 ) !== this[j]) {
                                d.push( j + '="' + this.toMathMLattribute( this[j] ) + '"' )
                            }
                        }
                    }
                }
            }
            for (var e = 0, c = g.length; e < c; e++) {
                if (k[g[e]] === 1 && !h.hasOwnProperty( g[e] )) {
                    continue
                }
                value = (this.attr || {})[g[e]];
                if (value == null) {
                    value = this[g[e]]
                }
                if (value != null) {
                    d.push( g[e] + '="' + this.toMathMLquote( value ) + '"' )
                }
            }
            this.toMathMLclass( d );
            if (d.length) {
                return " " + d.join( " " )
            } else {
                return ""
            }
        },
        toMathMLclass: function( c )
        {
            var e = [];
            if (this["class"]) {
                e.push( this["class"] )
            }
            if (this.isa( a.TeXAtom ) && SETTINGS.texHints) {
                var d = ["ORD", "OP", "BIN", "REL", "OPEN", "CLOSE", "PUNCT", "INNER", "VCENTER"][this.texClass];
                if (d) {
                    e.push( "MJX-TeXAtom-" + d )
                }
            }
            if (this.mathvariant && this.toMathMLvariants[this.mathvariant]) {
                e.push( "MJX" + this.mathvariant )
            }
            if (this.variantForm) {
                e.push( "MJX-variant" )
            }
            if (e.length) {
                c.unshift( 'class="' + e.join( " " ) + '"' )
            }
        },
        toMathMLattribute: function( c )
        {
            if (typeof(c) === "string" && c.replace( / /g, "" ).match( /^(([-+])?(\d+(\.\d*)?|\.\d+))mu$/ )) {
                return (RegExp.$2 || "") + ((1 / 18) * RegExp.$3).toFixed( 3 ).replace( /\.?0+$/, "" ) + "em"
            } else {
                if (this.toMathMLvariants[c]) {
                    return this.toMathMLvariants[c]
                }
            }
            return this.toMathMLquote( c )
        },
        toMathMLvariants: {
            "-tex-caligraphic": a.VARIANT.SCRIPT,
            "-tex-caligraphic-bold": a.VARIANT.BOLDSCRIPT,
            "-tex-oldstyle": a.VARIANT.NORMAL,
            "-tex-oldstyle-bold": a.VARIANT.BOLD,
            "-tex-mathit": a.VARIANT.ITALIC
        },
        toMathMLquote: function( f )
        {
            f = String( f ).split( "" );
            for (var g = 0, d = f.length; g < d; g++) {
                var k = f[g].charCodeAt( 0 );
                if (k <= 55295 || 57344 <= k) {
                    if (k > 126 || (k < 32 && k !== 10 && k !== 13 && k !== 9)) {
                        f[g] = "&#x" + k.toString( 16 ).toUpperCase() + ";"
                    } else {
                        var j = {"&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;"}[f[g]];
                        if (j) {
                            f[g] = j
                        }
                    }
                } else {
                    if (g + 1 < d) {
                        var h = f[g + 1].charCodeAt( 0 );
                        var e = (((k - 55296) << 10) + (h - 56320) + 65536);
                        f[g] = "&#x" + e.toString( 16 ).toUpperCase() + ";";
                        f[g + 1] = "";
                        g++
                    } else {
                        f[g] = ""
                    }
                }
            }
            return f.join( "" )
        }
    } );
    a.math.Augment( {
        toMathML: function( c, d )
        {
            var f;
            if (c == null) {
                c = ""
            }
            if (d && d.originalText && SETTINGS.semantics) {
                f = MathJax.InputJax[d.inputJax].annotationEncoding
            }
            var l = (this.data[0] && this.data[0].data.length > 1);
            var o = this.type, j = this.toMathMLattributes();
            var h = [], n = c + (f ? "  " + (l ? "  " : "") : "") + "  ";
            for (var g = 0, e = this.data.length; g < e; g++) {
                if (this.data[g]) {
                    h.push( this.data[g].toMathML( n ) )
                } else {
                    h.push( n + "<mrow />" )
                }
            }
            if (h.length === 0 || (h.length === 1 && h[0] === "")) {
                if (!f) {
                    return "<" + o + j + " />"
                }
                h.push( n + "<mrow />" )
            }
            if (f) {
                if (l) {
                    h.unshift( c + "    <mrow>" );
                    h.push( c + "    </mrow>" )
                }
                h.unshift( c + "  <semantics>" );
                var k = d.originalText.replace( /[&<>]/g, function( i )
                {
                    return {">": "&gt;", "<": "&lt;", "&": "&amp;"}[i]
                } );
                h.push( c + '    <annotation encoding="' + f + '">' + k + "</annotation>" );
                h.push( c + "  </semantics>" )
            }
            return c + "<" + o + j + ">\n" + h.join( "\n" ) + "\n" + c + "</" + o + ">"
        }
    } );
    a.msubsup.Augment( {
        toMathML: function( h )
        {
            var e = this.type;
            if (this.data[this.sup] == null) {
                e = "msub"
            }
            if (this.data[this.sub] == null) {
                e = "msup"
            }
            var d = this.toMathMLattributes();
            delete this.data[0].inferred;
            var g = [];
            for (var f = 0, c = this.data.length; f < c; f++) {
                if (this.data[f]) {
                    g.push( this.data[f].toMathML( h + "  " ) )
                }
            }
            return h + "<" + e + d + ">\n" + g.join( "\n" ) + "\n" + h + "</" + e + ">"
        }
    } );
    a.munderover.Augment( {
        toMathML: function( h )
        {
            var e = this.type;
            if (this.data[this.under] == null) {
                e = "mover"
            }
            if (this.data[this.over] == null) {
                e = "munder"
            }
            var d = this.toMathMLattributes();
            delete this.data[0].inferred;
            var g = [];
            for (var f = 0, c = this.data.length; f < c; f++) {
                if (this.data[f]) {
                    g.push( this.data[f].toMathML( h + "  " ) )
                }
            }
            return h + "<" + e + d + ">\n" + g.join( "\n" ) + "\n" + h + "</" + e + ">"
        }
    } );
    a.TeXAtom.Augment( {
        toMathML: function( d )
        {
            var c = this.toMathMLattributes();
            if (!c && this.data[0].data.length === 1) {
                return d.substr( 2 ) + this.data[0].toMathML( d )
            }
            return d + "<mrow" + c + ">\n" + this.data[0].toMathML( d + "  " ) + "\n" + d + "</mrow>"
        }
    } );
    a.chars.Augment( {
        toMathML: function( c )
        {
            return (c || "") + this.toMathMLquote( this.toString() )
        }
    } );
    a.entity.Augment( {
        toMathML: function( c )
        {
            return (c || "") + "&" + this.data[0] + ";<!-- " + this.toString() + " -->"
        }
    } );
    a.xml.Augment( {
        toMathML: function( c )
        {
            return (c || "") + this.toString()
        }
    } );
    MathJax.Hub.Register.StartupHook( "TeX mathchoice Ready", function()
    {
        a.TeXmathchoice.Augment( {
            toMathML: function( c )
            {
                return this.Core().toMathML( c )
            }
        } )
    } );
    MathJax.Hub.Startup.signal.Post( "toMathML Ready" )
} );
MathJax.Ajax.loadComplete( "[MathJax]/extensions/toMathML.js" );
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
(function( i, b, e, g )
{
    var h;
    var j, a, d;
    var f = "'Times New Roman',Times,STIXGeneral,serif";
    var m = {
        ".MJXc-script": {"font-size": ".8em"},
        ".MJXc-right": {
            "-webkit-transform-origin": "right",
            "-moz-transform-origin": "right",
            "-ms-transform-origin": "right",
            "-o-transform-origin": "right",
            "transform-origin": "right"
        },
        ".MJXc-bold": {"font-weight": "bold"},
        ".MJXc-italic": {"font-style": "italic"},
        ".MJXc-scr": {"font-family": "MathJax_Script," + f},
        ".MJXc-frak": {"font-family": "MathJax_Fraktur," + f},
        ".MJXc-sf": {"font-family": "MathJax_SansSerif," + f},
        ".MJXc-cal": {"font-family": "MathJax_Caligraphic," + f},
        ".MJXc-mono": {"font-family": "MathJax_Typewriter," + f},
        ".MJXc-largeop": {"font-size": "150%"},
        ".MJXc-largeop.MJXc-int": {"vertical-align": "-.2em"},
        ".MJXc-math": {
            display: "inline-block",
            "line-height": "1.2",
            "text-indent": "0",
            "font-family": f,
            "white-space": "nowrap",
            "border-collapse": "collapse"
        },
        ".MJXc-display": {display: "block", "text-align": "center", margin: "1em 0"},
        ".MJXc-math span": {display: "inline-block"},
        ".MJXc-box": {display: "block!important", "text-align": "center"},
        ".MJXc-box:after": {content: '" "'},
        ".MJXc-rule": {display: "block!important", "margin-top": ".1em"},
        ".MJXc-char": {display: "block!important"},
        ".MJXc-mo": {margin: "0 .15em"},
        ".MJXc-mfrac": {margin: "0 .125em", "vertical-align": ".25em"},
        ".MJXc-denom": {display: "inline-table!important", width: "100%"},
        ".MJXc-denom > *": {display: "table-row!important"},
        ".MJXc-surd": {"vertical-align": "top"},
        ".MJXc-surd > *": {display: "block!important"},
        ".MJXc-script-box > * ": {display: "table!important", height: "50%"},
        ".MJXc-script-box > * > *": {display: "table-cell!important", "vertical-align": "top"},
        ".MJXc-script-box > *:last-child > *": {"vertical-align": "bottom"},
        ".MJXc-script-box > * > * > *": {display: "block!important"},
        ".MJXc-mphantom": {visibility: "hidden"},
        ".MJXc-munderover": {display: "inline-table!important"},
        ".MJXc-over": {display: "inline-block!important", "text-align": "center"},
        ".MJXc-over > *": {display: "block!important"},
        ".MJXc-munderover > *": {display: "table-row!important"},
        ".MJXc-mtable": {"vertical-align": ".25em", margin: "0 .125em"},
        ".MJXc-mtable > *": {display: "inline-table!important", "vertical-align": "middle"},
        ".MJXc-mtr": {display: "table-row!important"},
        ".MJXc-mtd": {display: "table-cell!important", "text-align": "center", padding: ".5em 0 0 .5em"},
        ".MJXc-mtr > .MJXc-mtd:first-child": {"padding-left": 0},
        ".MJXc-mtr:first-child > .MJXc-mtd": {"padding-top": 0},
        ".MJXc-mlabeledtr": {display: "table-row!important"},
        ".MJXc-mlabeledtr > .MJXc-mtd:first-child": {"padding-left": 0},
        ".MJXc-mlabeledtr:first-child > .MJXc-mtd": {"padding-top": 0},
        ".MJXc-merror": {
            "background-color": "#FFFF88",
            color: "#CC0000",
            border: "1px solid #CC0000",
            padding: "1px 3px",
            "font-style": "normal",
            "font-size": "90%"
        }
    };
    (function()
    {
        for (var n = 0; n < 10; n++) {
            var o = "scaleX(." + n + ")";
            m[".MJXc-scale" + n] = {
                "-webkit-transform": o,
                "-moz-transform": o,
                "-ms-transform": o,
                "-o-transform": o,
                transform: o
            }
        }
    })();
    var k = 1000000;
    var c = "V", l = "H";
    g.Augment( {
        settings: b.config.menuSettings,
        config: {styles: m},
        hideProcessedMath: false,
        maxStretchyParts: 1000,
        Config: function()
        {
            if (!this.require) {
                this.require = []
            }
            this.SUPER( arguments ).Config.call( this );
            var n = this.settings;
            if (n.scale) {
                this.config.scale = n.scale
            }
            this.require.push( MathJax.OutputJax.extensionDir + "/MathEvents.js" )
        },
        Startup: function()
        {
            j = MathJax.Extension.MathEvents.Event;
            a = MathJax.Extension.MathEvents.Touch;
            d = MathJax.Extension.MathEvents.Hover;
            this.ContextMenu = j.ContextMenu;
            this.Mousedown = j.AltContextMenu;
            this.Mouseover = d.Mouseover;
            this.Mouseout = d.Mouseout;
            this.Mousemove = d.Mousemove;
            var n = e.addElement( document.body, "div", {style: {width: "5in"}} );
            this.pxPerInch = n.offsetWidth / 5;
            n.parentNode.removeChild( n );
            return i.Styles( this.config.styles, ["InitializeCHTML", this] )
        },
        InitializeCHTML: function()
        {
        },
        preTranslate: function( p )
        {
            var s = p.jax[this.id], t, q = s.length, u, r, v, o, n;
            for (t = 0; t < q; t++) {
                u = s[t];
                if (!u.parentNode) {
                    continue
                }
                r = u.previousSibling;
                if (r && String( r.className ).match( /^MathJax_CHTML(_Display)?( MathJax_Processing)?$/ )) {
                    r.parentNode.removeChild( r )
                }
                n = u.MathJax.elementJax;
                if (!n) {
                    continue
                }
                n.CHTML = {display: (n.root.Get( "display" ) === "block")};
                v = o = e.Element( "span", {
                    className: "MathJax_CHTML",
                    id: n.inputID + "-Frame",
                    isMathJax: true,
                    jaxID: this.id,
                    oncontextmenu: j.Menu,
                    onmousedown: j.Mousedown,
                    onmouseover: j.Mouseover,
                    onmouseout: j.Mouseout,
                    onmousemove: j.Mousemove,
                    onclick: j.Click,
                    ondblclick: j.DblClick
                } );
                if (b.Browser.noContextMenu) {
                    v.ontouchstart = a.start;
                    v.ontouchend = a.end
                }
                if (n.CHTML.display) {
                    o = e.Element( "div", {className: "MathJax_CHTML_Display"} );
                    o.appendChild( v )
                }
                o.className += " MathJax_Processing";
                u.parentNode.insertBefore( o, u )
            }
        },
        Translate: function( o, s )
        {
            if (!o.parentNode) {
                return
            }
            var n = o.MathJax.elementJax, r = n.root, p = document.getElementById( n.inputID + "-Frame" ), t = (n.CHTML.display ? p.parentNode : p);
            this.initCHTML( r, p );
            try {
                r.toCommonHTML( p )
            } catch( q ) {
                if (q.restart) {
                    while (p.firstChild) {
                        p.removeChild( p.firstChild )
                    }
                }
                throw q
            }
            t.className = t.className.split( / / )[0];
            if (this.hideProcessedMath) {
                t.className += " MathJax_Processed";
                if (o.MathJax.preview) {
                    n.CHTML.preview = o.MathJax.preview;
                    delete o.MathJax.preview
                }
            }
        },
        postTranslate: function( s )
        {
            var o = s.jax[this.id];
            if (!this.hideProcessedMath) {
                return
            }
            for (var q = 0, n = o.length; q < n; q++) {
                var p = o[q];
                if (p && p.MathJax.elementJax) {
                    p.previousSibling.className = p.previousSibling.className.split( / / )[0];
                    var r = p.MathJax.elementJax.CHTML;
                    if (r.preview) {
                        r.preview.innerHTML = "";
                        p.MathJax.preview = r.preview;
                        delete r.preview
                    }
                }
            }
        },
        getJaxFromMath: function( n )
        {
            if (n.parentNode.className === "MathJax_CHTML_Display") {
                n = n.parentNode
            }
            do {
                n = n.nextSibling
            } while (n && n.nodeName.toLowerCase() !== "script");
            return b.getJaxFor( n )
        },
        getHoverSpan: function( n, o )
        {
            return n.root.CHTMLspanElement()
        },
        getHoverBBox: function( n, o, p )
        {
            return BBOX
        },
        Zoom: function( o, u, s, n, r )
        {
            u.className = "MathJax";
            this.idPostfix = "-zoom";
            o.root.toCHTML( u, u );
            this.idPostfix = "";
            u.style.position = "absolute";
            if (!width) {
                s.style.position = "absolute"
            }
            var t = u.offsetWidth, q = u.offsetHeight, v = s.offsetHeight, p = s.offsetWidth;
            if (p === 0) {
                p = s.parentNode.offsetWidth
            }
            u.style.position = s.style.position = "";
            return {Y: -j.getBBox( u ).h, mW: p, mH: v, zW: t, zH: q}
        },
        initCHTML: function( o, n )
        {
        },
        Remove: function( n )
        {
            var o = document.getElementById( n.inputID + "-Frame" );
            if (o) {
                if (n.CHTML.display) {
                    o = o.parentNode
                }
                o.parentNode.removeChild( o )
            }
            delete n.CHTML
        },
        ID: 0,
        idPostfix: "",
        GetID: function()
        {
            this.ID++;
            return this.ID
        },
        VARIANT: {
            bold: "MJXc-bold",
            italic: "MJXc-italic",
            "bold-italic": "MJXc-bold MJXc-italic",
            script: "MJXc-scr",
            "bold-script": "MJXc-scr MJXc-bold",
            fraktur: "MJXc-frak",
            "bold-fraktur": "MJXc-frak MJXc-bold",
            monospace: "MJXc-mono",
            "sans-serif": "MJXc-sf",
            "-tex-caligraphic": "MJXc-cal"
        },
        MATHSPACE: {
            veryverythinmathspace: 1 / 18,
            verythinmathspace: 2 / 18,
            thinmathspace: 3 / 18,
            mediummathspace: 4 / 18,
            thickmathspace: 5 / 18,
            verythickmathspace: 6 / 18,
            veryverythickmathspace: 7 / 18,
            negativeveryverythinmathspace: -1 / 18,
            negativeverythinmathspace: -2 / 18,
            negativethinmathspace: -3 / 18,
            negativemediummathspace: -4 / 18,
            negativethickmathspace: -5 / 18,
            negativeverythickmathspace: -6 / 18,
            negativeveryverythickmathspace: -7 / 18,
            thin: 0.08,
            medium: 0.1,
            thick: 0.15,
            infinity: k
        },
        TeX: {x_height: 0.430554},
        pxPerInch: 72,
        em: 16,
        DELIMITERS: {
            "(": {dir: c},
            "{": {dir: c, w: 0.58},
            "[": {dir: c},
            "|": {dir: c, w: 0.275},
            ")": {dir: c},
            "}": {dir: c, w: 0.58},
            "]": {dir: c},
            "/": {dir: c},
            "\\": {dir: c},
            "\u2223": {dir: c, w: 0.275},
            "\u2225": {dir: c, w: 0.55},
            "\u230A": {dir: c, w: 0.5},
            "\u230B": {dir: c, w: 0.5},
            "\u2308": {dir: c, w: 0.5},
            "\u2309": {dir: c, w: 0.5},
            "\u27E8": {dir: c, w: 0.5},
            "\u27E9": {dir: c, w: 0.5},
            "\u2191": {dir: c, w: 0.65},
            "\u2193": {dir: c, w: 0.65},
            "\u21D1": {dir: c, w: 0.75},
            "\u21D3": {dir: c, w: 0.75},
            "\u2195": {dir: c, w: 0.65},
            "\u21D5": {dir: c, w: 0.75},
            "\u27EE": {dir: c, w: 0.275},
            "\u27EF": {dir: c, w: 0.275},
            "\u23B0": {dir: c, w: 0.6},
            "\u23B1": {dir: c, w: 0.6}
        },
        REMAPACCENT: {
            "\u20D7": "\u2192",
            "'": "\u02CB",
            "`": "\u02CA",
            ".": "\u02D9",
            "^": "\u02C6",
            "-": "\u02C9",
            "~": "\u02DC",
            "\u00AF": "\u02C9",
            "\u00B0": "\u02DA",
            "\u00B4": "\u02CA",
            "\u0300": "\u02CB",
            "\u0301": "\u02CA",
            "\u0302": "\u02C6",
            "\u0303": "\u02DC",
            "\u0304": "\u02C9",
            "\u0305": "\u02C9",
            "\u0306": "\u02D8",
            "\u0307": "\u02D9",
            "\u0308": "\u00A8",
            "\u030C": "\u02C7"
        },
        REMAPACCENTUNDER: {},
        length2em: function( r, p )
        {
            if (typeof(r) !== "string") {
                r = r.toString()
            }
            if (r === "") {
                return ""
            }
            if (r === h.SIZE.NORMAL) {
                return 1
            }
            if (r === h.SIZE.BIG) {
                return 2
            }
            if (r === h.SIZE.SMALL) {
                return 0.71
            }
            if (this.MATHSPACE[r]) {
                return this.MATHSPACE[r]
            }
            var o = r.match( /^\s*([-+]?(?:\.\d+|\d+(?:\.\d*)?))?(pt|em|ex|mu|px|pc|in|mm|cm|%)?/ );
            var n = parseFloat( o[1] || "1" ), q = o[2];
            if (p == null) {
                p = 1
            }
            if (q === "em") {
                return n
            }
            if (q === "ex") {
                return n * this.TeX.x_height
            }
            if (q === "%") {
                return n / 100 * p
            }
            if (q === "px") {
                return n / this.em
            }
            if (q === "pt") {
                return n / 10
            }
            if (q === "pc") {
                return n * 1.2
            }
            if (q === "in") {
                return n * this.pxPerInch / this.em
            }
            if (q === "cm") {
                return n * this.pxPerInch / this.em / 2.54
            }
            if (q === "mm") {
                return n * this.pxPerInch / this.em / 25.4
            }
            if (q === "mu") {
                return n / 18
            }
            return n * p
        },
        Em: function( n )
        {
            if (Math.abs( n ) < 0.001) {
                return "0em"
            }
            return (n.toFixed( 3 ).replace( /\.?0+$/, "" )) + "em"
        },
        arrayEntry: function( n, o )
        {
            return n[Math.max( 0, Math.min( o, n.length - 1 ) )]
        }
    } );
    MathJax.Hub.Register.StartupHook( "mml Jax Ready", function()
    {
        h = MathJax.ElementJax.mml;
        h.mbase.Augment( {
            toCommonHTML: function( o, n )
            {
                return this.CHTMLdefaultSpan( o, n )
            }, CHTMLdefaultSpan: function( q, o )
            {
                if (!o) {
                    o = {}
                }
                q = this.CHTMLcreateSpan( q );
                this.CHTMLhandleStyle( q );
                this.CHTMLhandleColor( q );
                if (this.isToken) {
                    this.CHTMLhandleToken( q )
                }
                for (var p = 0, n = this.data.length; p < n; p++) {
                    this.CHTMLaddChild( q, p, o )
                }
                return q
            }, CHTMLaddChild: function( p, o, n )
            {
                var q = this.data[o];
                if (q) {
                    if (n.childSpans) {
                        p = e.addElement( p, "span", {className: n.className} )
                    }
                    q.toCommonHTML( p );
                    if (!n.noBBox) {
                        this.CHTML.w += q.CHTML.w + q.CHTML.l + q.CHTML.r;
                        if (q.CHTML.h > this.CHTML.h) {
                            this.CHTML.h = q.CHTML.h
                        }
                        if (q.CHTML.d > this.CHTML.d) {
                            this.CHTML.d = q.CHTML.d
                        }
                        if (q.CHTML.t > this.CHTML.t) {
                            this.CHTML.t = q.CHTML.t
                        }
                        if (q.CHTML.b > this.CHTML.b) {
                            this.CHTML.b = q.CHTML.b
                        }
                    }
                } else {
                    if (n.forceChild) {
                        e.addElement( p, "span" )
                    }
                }
            }, CHTMLstretchChild: function( q, p, s )
            {
                var r = this.data[q];
                if (r && r.CHTMLcanStretch( "Vertical", p, s )) {
                    var t = this.CHTML, o = r.CHTML, n = o.w;
                    r.CHTMLstretchV( p, s );
                    t.w += o.w - n;
                    if (o.h > t.h) {
                        t.h = o.h
                    }
                    if (o.d > t.d) {
                        t.d = o.d
                    }
                }
            }, CHTMLcreateSpan: function( n )
            {
                if (!this.CHTML) {
                    this.CHTML = {}
                }
                this.CHTML = {w: 0, h: 0, d: 0, l: 0, r: 0, t: 0, b: 0};
                if (this.inferred) {
                    return n
                }
                if (this.type === "mo" && this.data.join( "" ) === "\u222B") {
                    g.lastIsInt = true
                } else {
                    if (this.type !== "mspace" || this.width !== "negativethinmathspace") {
                        g.lastIsInt = false
                    }
                }
                if (!this.CHTMLspanID) {
                    this.CHTMLspanID = g.GetID()
                }
                var o = (this.id || "MJXc-Span-" + this.CHTMLspanID);
                return e.addElement( n, "span", {className: "MJXc-" + this.type, id: o} )
            }, CHTMLspanElement: function()
            {
                if (!this.CHTMLspanID) {
                    return null
                }
                return document.getElementById( this.id || "MJXc-Span-" + this.CHTMLspanID )
            }, CHTMLhandleToken: function( o )
            {
                var n = this.getValues( "mathvariant" );
                if (n.mathvariant !== h.VARIANT.NORMAL) {
                    o.className += " " + g.VARIANT[n.mathvariant]
                }
            }, CHTMLhandleStyle: function( n )
            {
                if (this.style) {
                    n.style.cssText = this.style
                }
            }, CHTMLhandleColor: function( n )
            {
                if (this.mathcolor) {
                    n.style.color = this.mathcolor
                }
                if (this.mathbackground) {
                    n.style.backgroundColor = this.mathbackground
                }
            }, CHTMLhandleScriptlevel: function( n )
            {
                var o = this.Get( "scriptlevel" );
                if (o) {
                    n.className += " MJXc-script"
                }
            }, CHTMLhandleText: function( y, A )
            {
                var v, p;
                var z = 0, o = 0, q = 0;
                for (var s = 0, r = A.length; s < r; s++) {
                    p = A.charCodeAt( s );
                    v = A.charAt( s );
                    if (p >= 55296 && p < 56319) {
                        s++;
                        p = (((p - 55296) << 10) + (A.charCodeAt( s ) - 56320)) + 65536
                    }
                    var t = 0.7, u = 0.22, x = 0.5;
                    if (p < 127) {
                        if (v.match( /[A-Za-ehik-or-xz0-9]/ )) {
                            u = 0
                        }
                        if (v.match( /[A-HK-Z]/ )) {
                            x = 0.67
                        } else {
                            if (v.match( /[IJ]/ )) {
                                x = 0.36
                            }
                        }
                        if (v.match( /[acegm-su-z]/ )) {
                            t = 0.45
                        } else {
                            if (v.match( /[ij]/ )) {
                                t = 0.75
                            }
                        }
                        if (v.match( /[ijlt]/ )) {
                            x = 0.28
                        }
                    }
                    if (g.DELIMITERS[v]) {
                        x = g.DELIMITERS[v].w || 0.4
                    }
                    if (t > z) {
                        z = t
                    }
                    if (u > o) {
                        o = u
                    }
                    q += x
                }
                if (!this.CHML) {
                    this.CHTML = {}
                }
                this.CHTML = {h: 0.9, d: 0.3, w: q, l: 0, r: 0, t: z, b: o};
                e.addText( y, A )
            }, CHTMLbboxFor: function( o )
            {
                if (this.data[o] && this.data[o].CHTML) {
                    return this.data[o].CHTML
                }
                return {w: 0, h: 0, d: 0, l: 0, r: 0, t: 0, b: 0}
            }, CHTMLcanStretch: function( q, o, p )
            {
                if (this.isEmbellished()) {
                    var n = this.Core();
                    if (n && n !== this) {
                        return n.CHTMLcanStretch( q, o, p )
                    }
                }
                return false
            }, CHTMLstretchV: function( n, o )
            {
            }, CHTMLstretchH: function( n )
            {
            }, CoreParent: function()
            {
                var n = this;
                while (n && n.isEmbellished() && n.CoreMO() === this && !n.isa( h.math )) {
                    n = n.Parent()
                }
                return n
            }, CoreText: function( n )
            {
                if (!n) {
                    return ""
                }
                if (n.isEmbellished()) {
                    return n.CoreMO().data.join( "" )
                }
                while ((n.isa( h.mrow ) || n.isa( h.TeXAtom ) || n.isa( h.mstyle ) || n.isa( h.mphantom )) && n.data.length === 1 && n.data[0]) {
                    n = n.data[0]
                }
                if (!n.isToken) {
                    return ""
                } else {
                    return n.data.join( "" )
                }
            }
        } );
        h.chars.Augment( {
            toCommonHTML: function( n )
            {
                var o = this.toString().replace( /[\u2061-\u2064]/g, "" );
                this.CHTMLhandleText( n, o )
            }
        } );
        h.entity.Augment( {
            toCommonHTML: function( n )
            {
                var o = this.toString().replace( /[\u2061-\u2064]/g, "" );
                this.CHTMLhandleText( n, o )
            }
        } );
        h.math.Augment( {
            toCommonHTML: function( n )
            {
                n = this.CHTMLdefaultSpan( n );
                if (this.Get( "display" ) === "block") {
                    n.className += " MJXc-display"
                }
                return n
            }
        } );
        h.mo.Augment( {
            toCommonHTML: function( o )
            {
                o = this.CHTMLdefaultSpan( o );
                this.CHTMLadjustAccent( o );
                var n = this.getValues( "lspace", "rspace", "scriptlevel", "displaystyle", "largeop" );
                if (n.scriptlevel === 0) {
                    this.CHTML.l = g.length2em( n.lspace );
                    this.CHTML.r = g.length2em( n.rspace );
                    o.style.marginLeft = g.Em( this.CHTML.l );
                    o.style.marginRight = g.Em( this.CHTML.r )
                } else {
                    this.CHTML.l = 0.15;
                    this.CHTML.r = 0.1
                }
                if (n.displaystyle && n.largeop) {
                    var p = e.Element( "span", {className: "MJXc-largeop"} );
                    p.appendChild( o.firstChild );
                    o.appendChild( p );
                    this.CHTML.h *= 1.2;
                    this.CHTML.d *= 1.2;
                    if (this.data.join( "" ) === "\u222B") {
                        p.className += " MJXc-int"
                    }
                }
                return o
            }, CHTMLadjustAccent: function( p )
            {
                var o = this.CoreParent();
                if (o && o.isa( h.munderover ) && this.CoreText( o.data[o.base] ).length === 1) {
                    var q = o.data[o.over], n = o.data[o.under];
                    var s = this.data.join( "" ), r;
                    if (q && this === q.CoreMO() && o.Get( "accent" )) {
                        r = g.REMAPACCENT[s]
                    } else {
                        if (n && this === n.CoreMO() && o.Get( "accentunder" )) {
                            r = g.REMAPACCENTUNDER[s]
                        }
                    }
                    if (r) {
                        s = p.innerHTML = r
                    }
                    if (s.match( /[\u02C6-\u02DC\u00A8]/ )) {
                        this.CHTML.acc = -0.52
                    } else {
                        if (s === "\u2192") {
                            this.CHTML.acc = -0.15;
                            this.CHTML.vec = true
                        }
                    }
                }
            }, CHTMLcanStretch: function( q, o, p )
            {
                if (!this.Get( "stretchy" )) {
                    return false
                }
                var r = this.data.join( "" );
                if (r.length > 1) {
                    return false
                }
                r = g.DELIMITERS[r];
                var n = (r && r.dir === q.substr( 0, 1 ));
                if (n) {
                    n = (this.CHTML.h !== o || this.CHTML.d !== p || (this.Get( "minsize",
                        true ) || this.Get( "maxsize", true )))
                }
                return n
            }, CHTMLstretchV: function( p, u )
            {
                var o = this.CHTMLspanElement(), t = this.CHTML;
                var n = this.getValues( "symmetric", "maxsize", "minsize" );
                if (n.symmetric) {
                    l = 2 * Math.max( p - 0.25, u + 0.25 )
                } else {
                    l = p + u
                }
                n.maxsize = g.length2em( n.maxsize, t.h + t.d );
                n.minsize = g.length2em( n.minsize, t.h + t.d );
                l = Math.max( n.minsize, Math.min( n.maxsize, l ) );
                var s = l / (t.h + t.d - 0.3);
                var q = e.Element( "span", {style: {"font-size": g.Em( s )}} );
                if (s > 1.25) {
                    var r = Math.ceil( 1.25 / s * 10 );
                    q.className = "MJXc-right MJXc-scale" + r;
                    q.style.marginLeft = g.Em( t.w * (r / 10 - 1) + 0.07 );
                    t.w *= s * r / 10
                }
                q.appendChild( o.firstChild );
                o.appendChild( q );
                if (n.symmetric) {
                    o.style.verticalAlign = g.Em( 0.25 * (1 - s) )
                }
            }
        } );
        h.mspace.Augment( {
            toCommonHTML: function( q )
            {
                q = this.CHTMLdefaultSpan( q );
                var o = this.getValues( "height", "depth", "width" );
                var n = g.length2em( o.width ), p = g.length2em( o.height ), s = g.length2em( o.depth );
                var r = this.CHTML;
                r.w = n;
                r.h = p;
                r.d = s;
                if (n < 0) {
                    if (!g.lastIsInt) {
                        q.style.marginLeft = g.Em( n )
                    }
                    n = 0
                }
                q.style.width = g.Em( n );
                q.style.height = g.Em( p + s );
                if (s) {
                    q.style.verticalAlign = g.Em( -s )
                }
                return q
            }
        } );
        h.mpadded.Augment( {
            toCommonHTML: function( u )
            {
                u = this.CHTMLdefaultSpan( u, {childSpans: true, className: "MJXc-box", forceChild: true} );
                var o = u.firstChild;
                var v = this.getValues( "width", "height", "depth", "lspace", "voffset" );
                var s = this.CHTMLdimen( v.lspace );
                var q = 0, n = 0, t = s.len, r = -s.len, p = 0;
                if (v.width !== "") {
                    s = this.CHTMLdimen( v.width, "w", 0 );
                    if (s.pm) {
                        r += s.len
                    } else {
                        u.style.width = g.Em( s.len )
                    }
                }
                if (v.height !== "") {
                    s = this.CHTMLdimen( v.height, "h", 0 );
                    if (!s.pm) {
                        q += -this.CHTMLbboxFor( 0 ).h
                    }
                    q += s.len
                }
                if (v.depth !== "") {
                    s = this.CHTMLdimen( v.depth, "d", 0 );
                    if (!s.pm) {
                        n += -this.CHTMLbboxFor( 0 ).d;
                        p += -s.len
                    }
                    n += s.len
                }
                if (v.voffset !== "") {
                    s = this.CHTMLdimen( v.voffset );
                    q -= s.len;
                    n += s.len;
                    p += s.len
                }
                if (q) {
                    o.style.marginTop = g.Em( q )
                }
                if (n) {
                    o.style.marginBottom = g.Em( n )
                }
                if (t) {
                    o.style.marginLeft = g.Em( t )
                }
                if (r) {
                    o.style.marginRight = g.Em( r )
                }
                if (p) {
                    u.style.verticalAlign = g.Em( p )
                }
                return u
            }, CHTMLdimen: function( q, r, n )
            {
                if (n == null) {
                    n = -k
                }
                q = String( q );
                var o = q.match( /width|height|depth/ );
                var p = (o ? this.CHTML[o[0].charAt( 0 )] : (r ? this.CHTML[r] : 0));
                return {len: g.length2em( q, p ) || 0, pm: !!q.match( /^[-+]/ )}
            }
        } );
        h.munderover.Augment( {
            toCommonHTML: function( q )
            {
                var n = this.getValues( "displaystyle", "accent", "accentunder", "align" );
                if (!n.displaystyle && this.data[this.base] != null && this.data[this.base].CoreMO().Get( "movablelimits" )) {
                    q = h.msubsup.prototype.toCommonHTML.call( this, q );
                    q.className = q.className.replace( /munderover/, "msubsup" );
                    return q
                }
                q = this.CHTMLdefaultSpan( q, {childSpans: true, className: "", noBBox: true} );
                var p = this.CHTMLbboxFor( this.over ), s = this.CHTMLbboxFor( this.under ), u = this.CHTMLbboxFor( this.base ), o = this.CHTML, r = p.acc;
                if (this.data[this.over]) {
                    q.lastChild.firstChild.style.marginLeft = p.l = q.lastChild.firstChild.style.marginRight = p.r = 0;
                    var t = e.Element( "span", {}, [["span", {className: "MJXc-over"}]] );
                    t.firstChild.appendChild( q.lastChild );
                    if (q.childNodes.length > (this.data[this.under] ? 1 : 0)) {
                        t.firstChild.appendChild( q.firstChild )
                    }
                    this.data[this.over].CHTMLhandleScriptlevel( t.firstChild.firstChild );
                    if (r != null) {
                        if (p.vec) {
                            t.firstChild.firstChild.firstChild.style.fontSize = "60%";
                            p.h *= 0.6;
                            p.d *= 0.6;
                            p.w *= 0.6
                        }
                        r = r - p.d + 0.1;
                        if (u.t != null) {
                            r += u.t - u.h
                        }
                        t.firstChild.firstChild.style.marginBottom = g.Em( r )
                    }
                    if (q.firstChild) {
                        q.insertBefore( t, q.firstChild )
                    } else {
                        q.appendChild( t )
                    }
                }
                if (this.data[this.under]) {
                    q.lastChild.firstChild.style.marginLeft = s.l = q.lastChild.firstChild.marginRight = s.r = 0;
                    this.data[this.under].CHTMLhandleScriptlevel( q.lastChild )
                }
                o.w = Math.max( 0.8 * p.w, 0.8 * s.w, u.w );
                o.h = 0.8 * (p.h + p.d + (r || 0)) + u.h;
                o.d = u.d + 0.8 * (s.h + s.d);
                return q
            }
        } );
        h.msubsup.Augment( {
            toCommonHTML: function( q )
            {
                q = this.CHTMLdefaultSpan( q, {noBBox: true} );
                if (!this.data[this.base]) {
                    if (q.firstChild) {
                        q.insertBefore( e.Element( "span" ), q.firstChild )
                    } else {
                        q.appendChild( e.Element( "span" ) )
                    }
                }
                var s = this.data[this.base], p = this.data[this.sub], n = this.data[this.sup];
                if (!s) {
                    s = {bbox: {h: 0.8, d: 0.2}}
                }
                q.firstChild.style.marginRight = ".05em";
                var o = Math.max( 0.4, s.CHTML.h - 0.4 ), u = Math.max( 0.2, s.CHTML.d + 0.1 );
                var t = this.CHTML;
                if (n && p) {
                    var r = e.Element( "span", {
                        className: "MJXc-script-box",
                        style: {
                            height: g.Em( o + n.CHTML.h * 0.8 + u + p.CHTML.d * 0.8 ),
                            "vertical-align": g.Em( -u - p.CHTML.d * 0.8 )
                        }
                    }, [
                        [
                            "span",
                            {},
                            [
                                [
                                    "span",
                                    {},
                                    [
                                        [
                                            "span",
                                            {style: {"margin-bottom": g.Em( -(n.CHTML.d - 0.05) )}}
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            "span",
                            {},
                            [
                                [
                                    "span",
                                    {},
                                    [
                                        [
                                            "span",
                                            {style: {"margin-top": g.Em( -(n.CHTML.h - 0.05) )}}
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ] );
                    p.CHTMLhandleScriptlevel( r.firstChild );
                    n.CHTMLhandleScriptlevel( r.lastChild );
                    r.firstChild.firstChild.firstChild.appendChild( q.lastChild );
                    r.lastChild.firstChild.firstChild.appendChild( q.lastChild );
                    q.appendChild( r );
                    t.h = Math.max( s.CHTML.h, n.CHTML.h * 0.8 + o );
                    t.d = Math.max( s.CHTML.d, p.CHTML.d * 0.8 + u );
                    t.w = s.CHTML.w + Math.max( n.CHTML.w, p.CHTML.w ) + 0.07
                } else {
                    if (n) {
                        q.lastChild.style.verticalAlign = g.Em( o );
                        n.CHTMLhandleScriptlevel( q.lastChild );
                        t.h = Math.max( s.CHTML.h, n.CHTML.h * 0.8 + o );
                        t.d = Math.max( s.CHTML.d, n.CHTML.d * 0.8 - o );
                        t.w = s.CHTML.w + n.CHTML.w + 0.07
                    } else {
                        if (p) {
                            q.lastChild.style.verticalAlign = g.Em( -u );
                            p.CHTMLhandleScriptlevel( q.lastChild );
                            t.h = Math.max( s.CHTML.h, p.CHTML.h * 0.8 - u );
                            t.d = Math.max( s.CHTML.d, p.CHTML.d * 0.8 + u );
                            t.w = s.CHTML.w + p.CHTML.w + 0.07
                        }
                    }
                }
                return q
            }
        } );
        h.mfrac.Augment( {
            toCommonHTML: function( r )
            {
                r = this.CHTMLdefaultSpan( r,
                    {childSpans: true, className: "MJXc-box", forceChild: true, noBBox: true} );
                var o = this.getValues( "linethickness", "displaystyle" );
                if (!o.displaystyle) {
                    if (this.data[0]) {
                        this.data[0].CHTMLhandleScriptlevel( r.firstChild )
                    }
                    if (this.data[1]) {
                        this.data[1].CHTMLhandleScriptlevel( r.lastChild )
                    }
                }
                var n = e.Element( "span", {className: "MJXc-box", style: {"margin-top": "-.8em"}}, [
                    [
                        "span",
                        {className: "MJXc-denom"},
                        [
                            [
                                "span",
                                {},
                                [
                                    [
                                        "span",
                                        {className: "MJXc-rule"}
                                    ]
                                ]
                            ],
                            ["span"]
                        ]
                    ]
                ] );
                n.firstChild.lastChild.appendChild( r.lastChild );
                r.appendChild( n );
                var s = this.CHTMLbboxFor( 0 ), p = this.CHTMLbboxFor( 1 ), v = this.CHTML;
                v.w = Math.max( s.w, p.w ) * 0.8;
                v.h = s.h + s.d + 0.1 + 0.25;
                v.d = p.h + p.d - 0.25;
                v.l = v.r = 0.125;
                o.linethickness = Math.max( 0, g.length2em( o.linethickness || "0", 0 ) );
                if (o.linethickness) {
                    var u = n.firstChild.firstChild.firstChild;
                    var q = g.Em( o.linethickness );
                    u.style.borderTop = (o.linethickness < 0.15 ? "1px" : q) + " solid";
                    u.style.margin = q + " 0";
                    q = o.linethickness;
                    n.style.marginTop = g.Em( 3 * q - 0.9 );
                    r.style.verticalAlign = g.Em( 1.5 * q + 0.1 );
                    v.h += 1.5 * q - 0.1;
                    v.d += 1.5 * q
                }
                return r
            }
        } );
        h.msqrt.Augment( {
            toCommonHTML: function( n )
            {
                n = this.CHTMLdefaultSpan( n,
                    {childSpans: true, className: "MJXc-box", forceChild: true, noBBox: true} );
                this.CHTMLlayoutRoot( n, n.firstChild );
                return n
            }, CHTMLlayoutRoot: function( u, n )
            {
                var v = this.CHTMLbboxFor( 0 );
                var q = Math.ceil( (v.h + v.d + 0.14) * 100 ), w = g.Em( 14 / q );
                var r = e.Element( "span", {className: "MJXc-surd"},
                    [["span", {style: {"font-size": q + "%", "margin-top": w}}, ["\u221A"]]] );
                var s = e.Element( "span", {className: "MJXc-root"},
                    [["span", {className: "MJXc-rule", style: {"border-top": ".08em solid"}}]] );
                var p = (1.2 / 2.2) * q / 100;
                if (q > 150) {
                    var o = Math.ceil( 150 / q * 10 );
                    r.firstChild.className = "MJXc-right MJXc-scale" + o;
                    r.firstChild.style.marginLeft = g.Em( p * (o / 10 - 1) / q * 100 );
                    p = p * o / 10;
                    s.firstChild.style.borderTopWidth = g.Em( 0.08 / Math.sqrt( o / 10 ) )
                }
                s.appendChild( n );
                u.appendChild( r );
                u.appendChild( s );
                this.CHTML.h = v.h + 0.18;
                this.CHTML.d = v.d;
                this.CHTML.w = v.w + p;
                return u
            }
        } );
        h.mroot.Augment( {
            toCommonHTML: function( q )
            {
                q = this.CHTMLdefaultSpan( q,
                    {childSpans: true, className: "MJXc-box", forceChild: true, noBBox: true} );
                var p = this.CHTMLbboxFor( 1 ), n = q.removeChild( q.lastChild );
                var t = this.CHTMLlayoutRoot( e.Element( "span" ), q.firstChild );
                n.className = "MJXc-script";
                var u = parseInt( t.firstChild.firstChild.style.fontSize );
                var o = 0.55 * (u / 120) + p.d * 0.8, s = -0.6 * (u / 120);
                if (u > 150) {
                    s *= 0.95 * Math.ceil( 150 / u * 10 ) / 10
                }
                n.style.marginRight = g.Em( s );
                n.style.verticalAlign = g.Em( o );
                if (-s > p.w * 0.8) {
                    n.style.marginLeft = g.Em( -s - p.w * 0.8 )
                }
                q.appendChild( n );
                q.appendChild( t );
                this.CHTML.w += Math.max( 0, p.w * 0.8 + s );
                this.CHTML.h = Math.max( this.CHTML.h, p.h * 0.8 + o );
                return q
            }, CHTMLlayoutRoot: h.msqrt.prototype.CHTMLlayoutRoot
        } );
        h.mfenced.Augment( {
            toCommonHTML: function( q )
            {
                q = this.CHTMLcreateSpan( q );
                this.CHTMLhandleStyle( q );
                this.CHTMLhandleColor( q );
                this.addFakeNodes();
                this.CHTMLaddChild( q, "open", {} );
                for (var p = 0, n = this.data.length; p < n; p++) {
                    this.CHTMLaddChild( q, "sep" + p, {} );
                    this.CHTMLaddChild( q, p, {} )
                }
                this.CHTMLaddChild( q, "close", {} );
                var o = this.CHTML.h, r = this.CHTML.d;
                this.CHTMLstretchChild( "open", o, r );
                for (p = 0, n = this.data.length; p < n; p++) {
                    this.CHTMLstretchChild( "sep" + p, o, r );
                    this.CHTMLstretchChild( p, o, r )
                }
                this.CHTMLstretchChild( "close", o, r );
                return q
            }
        } );
        h.mrow.Augment( {
            toCommonHTML: function( q )
            {
                q = this.CHTMLdefaultSpan( q );
                var p = this.CHTML.h, r = this.CHTML.d;
                for (var o = 0, n = this.data.length; o < n; o++) {
                    this.CHTMLstretchChild( o, p, r )
                }
                return q
            }
        } );
        h.mstyle.Augment( {
            toCommonHTML: function( n )
            {
                n = this.CHTMLdefaultSpan( n );
                this.CHTMLhandleScriptlevel( n );
                return n
            }
        } );
        h.TeXAtom.Augment( {
            toCommonHTML: function( n )
            {
                n = this.CHTMLdefaultSpan( n );
                n.className = "MJXc-mrow";
                return n
            }
        } );
        h.mtable.Augment( {
            toCommonHTML: function( E )
            {
                E = this.CHTMLdefaultSpan( E, {noBBox: true} );
                var r = this.getValues( "columnalign", "rowalign", "columnspacing", "rowspacing", "columnwidth",
                    "equalcolumns", "equalrows", "columnlines", "rowlines", "frame", "framespacing", "align", "width" );
                var u = MathJax.Hub.SplitList, F, A, D, z;
                var N = u( r.columnspacing ), w = u( r.rowspacing ), L = u( r.columnalign ), t = u( r.rowalign );
                for (F = 0, A = N.length; F < A; F++) {
                    N[F] = g.length2em( N[F] )
                }
                for (F = 0, A = w.length; F < A; F++) {
                    w[F] = g.length2em( w[F] )
                }
                var K = e.Element( "span" );
                while (E.firstChild) {
                    K.appendChild( E.firstChild )
                }
                E.appendChild( K );
                var y = 0, s = 0;
                for (F = 0, A = this.data.length; F < A; F++) {
                    var v = this.data[F];
                    if (v) {
                        var J = g.arrayEntry( w, F - 1 ), C = g.arrayEntry( t, F );
                        var x = v.CHTML, q = v.CHTMLspanElement();
                        q.style.verticalAlign = C;
                        var B = (v.type === "mlabeledtr" ? 1 : 0);
                        for (D = 0, z = v.data.length; D < z - B; D++) {
                            var p = v.data[D + B];
                            if (p) {
                                var M = g.arrayEntry( N, D - 1 ), G = g.arrayEntry( L, D );
                                var I = p.CHTMLspanElement();
                                if (D) {
                                    x.w += M;
                                    I.style.paddingLeft = g.Em( M )
                                }
                                if (F) {
                                    I.style.paddingTop = g.Em( J )
                                }
                                I.style.textAlign = G
                            }
                        }
                        y += x.h + x.d;
                        if (F) {
                            y += J
                        }
                        if (x.w > s) {
                            s = x.w
                        }
                    }
                }
                var o = this.CHTML;
                o.w = s;
                o.h = y / 2 + 0.25;
                o.d = y / 2 - 0.25;
                o.l = o.r = 0.125;
                return E
            }
        } );
        h.mlabeledtr.Augment( {
            CHTMLdefaultSpan: function( q, o )
            {
                if (!o) {
                    o = {}
                }
                q = this.CHTMLcreateSpan( q );
                this.CHTMLhandleStyle( q );
                this.CHTMLhandleColor( q );
                if (this.isToken) {
                    this.CHTMLhandleToken( q )
                }
                for (var p = 1, n = this.data.length; p < n; p++) {
                    this.CHTMLaddChild( q, p, o )
                }
                return q
            }
        } );
        h.semantics.Augment( {
            toCommonHTML: function( n )
            {
                n = this.CHTMLcreateSpan( n );
                if (this.data[0]) {
                    this.data[0].toCommonHTML( n );
                    MathJax.Hub.Insert( this.data[0].CHTML || {}, this.CHTML )
                }
                return n
            }
        } );
        h.annotation.Augment( {
            toCommonHTML: function( n )
            {
            }
        } );
        h["annotation-xml"].Augment( {
            toCommonHTML: function( n )
            {
            }
        } );
        MathJax.Hub.Register.StartupHook( "onLoad", function()
        {
            setTimeout( MathJax.Callback( ["loadComplete", g, "jax.js"] ), 0 )
        } )
    } );
    MathJax.Hub.Register.StartupHook( "End Cookie", function()
    {
        if (b.config.menuSettings.zoom !== "None") {
            i.Require( "[MathJax]/extensions/MathZoom.js" )
        }
    } )
})( MathJax.Ajax, MathJax.Hub, MathJax.HTML, MathJax.OutputJax.CommonHTML );
(function( a, d )
{
    var b = a.config.menuSettings;
    var c = MathJax.Extension["CHTML-preview"] = {
        version: "2.5.0",
        config: a.CombineConfig( "CHTML-preview", {
            Chunks: {EqnChunk: 10000, EqnChunkFactor: 1, EqnChunkDelay: 0},
            color: "inherit!important",
            updateTime: 30,
            updateDelay: 6,
            messageStyle: "none",
            disabled: false
        } ),
        Config: function()
        {
            a.Config( {"HTML-CSS": this.config.Chunks, SVG: this.config.Chunks} );
            MathJax.Ajax.Styles( {".MathJax_Preview .MJXc-math": {color: this.config.color}} );
            var j, g, h, e, i;
            var f = this.config;
            if (!f.disabled && b.CHTMLpreview == null) {
                a.Config( {menuSettings: {CHTMLpreview: true}} )
            }
            a.Register.MessageHook( "Begin Math Output", function()
            {
                if (!e && b.CHTMLpreview && b.renderer !== "CommonHTML") {
                    j = a.processUpdateTime;
                    g = a.processUpdateDelay;
                    h = a.config.messageStyle;
                    a.processUpdateTime = f.updateTime;
                    a.processUpdateDelay = f.updateDelay;
                    a.Config( {messageStyle: f.messageStyle} );
                    MathJax.Message.Clear( 0, 0 );
                    i = true
                }
            } );
            a.Register.MessageHook( "End Math Output", function()
            {
                if (!e && i) {
                    a.processUpdateTime = j;
                    a.processUpdateDelay = g;
                    a.Config( {messageStyle: h} );
                    e = true
                }
            } )
        },
        Preview: function( e )
        {
            if (!b.CHTMLpreview || b.renderer === "CommonHTML") {
                return
            }
            var f = e.script.MathJax.preview || e.script.previousSibling;
            if (!f || f.className !== MathJax.Hub.config.preRemoveClass) {
                f = d.Element( "span", {className: MathJax.Hub.config.preRemoveClass} );
                e.script.parentNode.insertBefore( f, e.script );
                e.script.MathJax.preview = f
            }
            f.innerHTML = "";
            f.style.color = "inherit";
            return this.postFilter( f, e )
        },
        postFilter: function( g, f )
        {
            if (!f.math.root.toCommonHTML) {
                var e = MathJax.Callback.Queue();
                e.Push( ["Require", MathJax.Ajax, "[MathJax]/jax/output/CommonHTML/config.js"],
                    ["Require", MathJax.Ajax, "[MathJax]/jax/output/CommonHTML/jax.js"] );
                a.RestartAfter( e.Push( {} ) )
            }
            f.math.root.toCommonHTML( g )
        },
        Register: function( e )
        {
            a.Register.StartupHook( e + " Jax Require", function()
            {
                var f = MathJax.InputJax[e];
                f.postfilterHooks.Add( ["Preview", MathJax.Extension["CHTML-preview"]], 50 )
            } )
        }
    };
    c.Register( "TeX" );
    c.Register( "MathML" );
    c.Register( "AsciiMath" );
    a.Register.StartupHook( "End Config", ["Config", c] );
    a.Startup.signal.Post( "CHTML-preview Ready" )
})( MathJax.Hub, MathJax.HTML );
MathJax.Ajax.loadComplete( "[MathJax]/extensions/CHTML-preview.js" );
MathJax.Ajax.loadComplete( "[MathJax]/config/AM_HTMLorMML.js" );
