/**
 * Yjax plugin
 *
 * Performs requests on remote
 */
class YjaxPlugin {

    /**
     * Initializes plugin
     *
     * @param {object} options
     * @param {string} options.host           Hostname of remote, if left empty, Yjax will try to resolve it automatically
     * @param {string} options.routesProvider Remote action to load routes from, if left empty, routes will not be loaded in this stage
     */
    constructor({ host, routesProvider } = {}) {
        if (null !== host) {
            this.host = host;
        } else {
            this.host = window.location.protocol + '//' + window.location.host;

            if ('http://localhost' === this.host) {
                let pathArray = window.location.pathname.split('/');

                if (pathArray.length > 3) {
                    this.host = [this.host, pathArray[1], pathArray[2]].join('/');
                }
            }
        }

        if (null !== routesProvider) {
            this.loadRoutes(routesProvider);
        }

        this.onError();
    }

    /**
     * Loads routes from remote
     *
     * @param {string} action Remote action to load routes from
     * @return {YjaxPlugin}
     */
    loadRoutes(action) {
        let self = this;

        $.ajax({
            url: self.host + action,
            dataType: 'json',
            async: false,
            headers: {'X-YJAX': true},
            success: function (routes) {
                self.routes = routes;
            },
            error: function () {
                console.error('Unable to load routes from remote.');
                self.routes = {};
            }
        });

        return this;
    }

    /**
     * Calls remote GET action
     *
     * @param {string}    action  Alias of remote action like Controller:action
     * @param {array}     params  Remote action parameters
     * @param {?function} success On success callback
     * @param {?function} error   On error callback
     * @param {?object}   options Set of ajax options
     * @return {boolean}          Returns false is action route doesn't exist
     */
    get (action, params = [], success = null, error = null, options = null) {
        if (typeof this.routes[action] === 'undefined') {
            console.error('Route for ' + action + ' doesn\'t exist.');

            return false;
        }

        let queryString = (params.length > 0) ? '/' + params.join('/') : '',
            url = this.routes[action] + queryString,
            request = {
                url: url,
                dataType: 'json',
                headers: {'X-YJAX': true},
                success: (typeof success === 'function') ? success : function () {},
                error: (typeof error === 'function') ? error : function (event, jqXHR, ajaxSettings, thrownError) {
                    console.error('GET request failed on remote ' + action + '.');
                    $(document).trigger('yjax:error', [event, jqXHR, ajaxSettings, thrownError]);
                },
            };

        $.each(options, function (key, value) {
            request[key] = value;
        });

        $.ajax(request);

        return true;
    }

    /**
     * Calls remote POST action
     *
     * @param {string}    action  Alias of remote action like Controller:action
     * @param {object}    data    Data to send
     * @param {array}     params  Remote action parameters
     * @param {?function} success On success callback
     * @param {?function} error   On error callback
     * @param {?object}   options Set of ajax options
     * @return {boolean}          Returns false is action route doesn't exist
     */
    post (action, data, params = [], success = null, error = null, options = null) {
        if (typeof this.routes[action] === 'undefined') {
            console.error('Route for ' + action + ' doesn\'t exist.');

            return false;
        }

        let queryString = (params.length > 0) ? '/' + params.join('/') : '',
            url = this.routes[action] + queryString,
            request = {
                url: url,
                method: 'POST',
                data: JSON.stringify(data),
                dataType: 'json',
                contentType: 'application/json',
                headers: {'X-YJAX': true},
                success: (typeof success === 'function') ? success : function () {},
                error: (typeof error === 'function') ? error : function (event, jqXHR, ajaxSettings, thrownError) {
                    console.error('POST request failed on remote ' + action + '.');
                    $(document).trigger('yjax:error', [event, jqXHR, ajaxSettings, thrownError]);
                },
            };

        $.each(options, function (key, value) {
            request[key] = value;
        });

        $.ajax(request);

        return true;
    }

    /**
     * Calls remote PUT action
     *
     * @param {string}    action  Alias of remote action like Controller:action
     * @param {object}    data    Data to send
     * @param {array}     params  Remote action parameters
     * @param {?function} success On success callback
     * @param {?function} error   On error callback
     * @param {?object}   options Set of ajax options
     * @return {boolean}          Returns false is action route doesn't exist
     */
    put (action, data, params = [], success = null, error = null, options = null) {
        if (typeof this.routes[action] === 'undefined') {
            console.error('Route for ' + action + ' doesn\'t exist.');

            return false;
        }

        let queryString = (params.length > 0) ? '/' + params.join('/') : '',
            url = this.routes[action] + queryString,
            request = {
                url: url,
                method: 'PUT',
                data: JSON.stringify(data),
                dataType: 'json',
                contentType: 'application/json',
                headers: {'X-YJAX': true},
                success: (typeof success === 'function') ? success : function () {},
                error: (typeof error === 'function') ? error : function (event, jqXHR, ajaxSettings, thrownError) {
                    console.error('PUT request failed on remote ' + action + '.');
                    $(document).trigger('yjax:error', [event, jqXHR, ajaxSettings, thrownError]);
                },
            };

        $.each(options, function (key, value) {
            request[key] = value;
        });

        $.ajax(request);

        return true;
    }

    /**
     * Calls remote DELETE action
     *
     * @param {string}    action  Alias of remote action like Controller:action
     * @param {array}     params  Remote action parameters
     * @param {?function} success On success callback
     * @param {?function} error   On error callback
     * @param {?object}   options Set of ajax options
     * @return {boolean}          Returns false is action route doesn't exist
     */
    delete (action, params = [], success = null, error = null, options = null) {
        if (typeof this.routes[action] === 'undefined') {
            console.error('Route for ' + action + ' doesn\'t exist.');

            return false;
        }

        let queryString = (params.length > 0) ? '/' + params.join('/') : '',
            url = this.routes[action] + queryString,
            request = {
                url: url,
                method: 'DELETE',
                dataType: 'json',
                headers: {'X-YJAX': true},
                success: (typeof success === 'function') ? success : function () {},
                error: (typeof error === 'function') ? error : function (event, jqXHR, ajaxSettings, thrownError) {
                    console.error('DELETE request failed on remote ' + action + '.');
                    $(document).trigger('yjax:error', [event, jqXHR, ajaxSettings, thrownError]);
                },
            };

        $.each(options, function (key, value) {
            request[key] = value;
        });

        $.ajax(request);

        return true;
    }

    /**
     * Registers on error callback
     * Default callback works pretty well with Whoops JsonResponseHandler
     *
     * @param {?function} callback Sets default callback if null
     */
    onError (callback = null) {
        $(document).on('yjax:error', (typeof callback === 'function') ? callback : function (event, jqXHR) {
            if (typeof jqXHR.responseText === "object") {
                let response = JSON.parse(jqXHR.responseText);

                if (typeof response.error !== 'undefined') {
                    console.error(response.error.message);

                    return;
                }
            }

            console.error(jqXHR.status + ' HTTP response.');
        });
    }
}
