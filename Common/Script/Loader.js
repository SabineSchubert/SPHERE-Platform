var Client = (function () {
    'use strict';
    var useDebug = false,
        useDelay = 20,
        useRetry = 10000,
        useConfig = {},
        logDebug = function (Content) {
            if (console && console.log) {
                console.log(Content);
            }
        },
        setModule = function (Module, Depending, cTag) {
            useConfig[Module] = {
                Depending: Depending,
                Source: window.location.pathname.slice(
                    0, window.location.pathname.search('/')
                ) + '/Common/Script/' + Module + '.js' + cTag,
                /**
                 * @return {boolean}
                 */
                Test: function () {
                    return 'undefined' !== typeof jQuery.fn[Module];
                },
                isUsed: false,
                isLoaded: false,
                Retry: 0,
                isReady: function (Callback) {
                    var dependingModule, dependingSize = this.Depending.length - 1;
                    for (dependingSize; 0 <= dependingSize; dependingSize--) {
                        dependingModule = this.Depending[dependingSize];
                        if (useConfig[dependingModule].Test()) {
                            if (!useConfig[dependingModule].isReady()) {
                                loadModule(dependingModule);
                                return false;
                            }
                        } else {
                            loadModule(dependingModule);
                            return false;
                        }
                    }
                    if (this.Test()) {
                        this.isLoaded = true;
                        return true;
                    } else {
                        if ('undefined' !== typeof Callback) {
                            loadModule(Module, Callback);
                        }
                        return false;
                    }
                }
            };
        },
        setSource = function (Module, Source, Test) {
            defineSource(Module, [], Source, Test);
        },
        defineSource = function (Module, Depending, Source, Test) {
            useConfig[Module] = {
                Depending: Depending,
                Source: Source,
                Test: Test,
                isUsed: false,
                isLoaded: false,
                Retry: 0,
                isReady: function (Callback) {
                    var dependingModule, dependingSize = this.Depending.length - 1;
                    for (dependingSize; 0 <= dependingSize; dependingSize--) {
                        dependingModule = this.Depending[dependingSize];
                        if (useConfig[dependingModule].Test()) {
                            if (!useConfig[dependingModule].isReady()) {
                                loadModule(dependingModule);
                                return false;
                            }
                        } else {
                            loadModule(dependingModule);
                            return false;
                        }
                    }
                    if (this.Test()) {
                        this.isLoaded = true;
                        return true;
                    } else {
                        if ('undefined' !== typeof Callback) {
                            loadModule(Module, Callback);
                        }
                        return false;
                    }
                }
            };
        },
        loadScript = function (Source) {
            var htmlElement = document.createElement("script");
            htmlElement.src = Source;
            document.body.appendChild(htmlElement);
        },
        loadModule = function (Module) {
            if (!useConfig[Module].isUsed) {
                loadScript(useConfig[Module].Source);
                useConfig[Module].isUsed = true;
            }
        },
        waitModule = function (Module, Callback) {

            if (!useConfig[Module]) {
                logDebug('!!! Unable to find ' + Module + ' !!!');
                return false;
            }

            if (useConfig[Module].isReady(Callback)) {
                return Callback();
            } else {
                if (useRetry < useConfig[Module].Retry) {
                    logDebug('### Unable to load ' + Module + ' depending on: ###');
                    for (var Index in useConfig[Module].Depending) {
                        if (useConfig[Module].Depending.hasOwnProperty(Index)) {
                            var isUsed = useConfig[useConfig[Module].Depending[Index]].isUsed;
                            var isLoaded = useConfig[useConfig[Module].Depending[Index]].isLoaded;
                            logDebug(
                                ( isUsed && isLoaded
                                        ? 'OK: '
                                        + useConfig[Module].Depending[Index]
                                        : 'FAILED: '
                                        + useConfig[Module].Depending[Index]
                                        + ' > Used: ' + isUsed + ' Loaded: ' + isLoaded
                                )
                            );
                        }
                    }
                    if ('undefined' != typeof jQuery) {
                        jQuery('span.loading-indicator').hide();
                        jQuery('span.loading-error').show();
                    }
                    return false;
                } else {
                    useConfig[Module].Retry++;
                }
                window.setTimeout(function () {
                    waitModule(Module, Callback);
                }, useDelay);
            }
            return null;
        },
        setUse = function (Module, Callback) {
            if ('function' !== typeof Callback) {
                //noinspection AssignmentToFunctionParameterJS
                Callback = function Callback() {
                };
            }
            return waitModule(Module, Callback);
        },
        useIndicator = function () {
            window.setTimeout(function () {
                var isFinished = true;
                for (var Index in useConfig) {
                    if (useConfig.hasOwnProperty(Index)) {
                        if (useConfig[Index].isUsed && !useConfig[Index].isLoaded) {
                            isFinished = false;
                        }
                    }
                }
                if (!isFinished || 'undefined' == typeof jQuery) {
                    useIndicator();
                } else {
                    jQuery('span.loading-indicator').hide();
                    useDebug ? logDebug('Loader JS: Done') : null;
                }
            }, useDelay);
        };

    if (useDebug) {
        useDebug ? logDebug('Loader JS: Start') : null;
    }
    useIndicator();
    return {
        Module: setModule,
        Source: setSource,
        Use: setUse
    };
})();
