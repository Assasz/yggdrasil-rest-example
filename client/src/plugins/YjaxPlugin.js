/**
 * Yjax plugin
 *
 * Performs requests on remote
 */
class YjaxPlugin {

    /**
     * Initializes plugin
     *
     * @param {?string} host Hostname of remote - if null, Yjax will try to resolve it automatically
     */
    constructor(host = null) {
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

        this.loadRoutes()
            .onError();
    }

    /**
     * Loads routes from remote
     *
     * @return {YjaxPlugin}
     */
    loadRoutes() {
        let self = this;

        $.ajax({
            url: self.host + '/api/yjax/routes',
            dataType: 'json',
            async: false,
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
     * @param {string}    action  Alias of remote action like [API:]Controller:action
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
                success: (typeof success === 'function') ? success : function () {},
                error: (typeof error === 'function') ? error : function () {
                    console.error('GET request failed on remote ' + action + '.');
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
     * @param {string}    action  Alias of remote action like [API:]Controller:action
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
                success: (typeof success === 'function') ? success : function () {},
                error: (typeof error === 'function') ? error : function () {
                    console.error('POST request failed on remote ' + action + '.');
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
     * @param {string}    action  Alias of remote action like [API:]Controller:action
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
                success: (typeof success === 'function') ? success : function () {},
                error: (typeof error === 'function') ? error : function () {
                    console.error('PUT request failed on remote ' + action + '.');
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
     * @param {string}    action  Alias of remote action like [API:]Controller:action
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
                success: (typeof success === 'function') ? success : function () {},
                error: (typeof error === 'function') ? error : function () {
                    console.error('DELETE request failed on remote ' + action + '.');
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
        $(document).ajaxError((typeof callback === 'function') ? callback : function (event, jqXHR) {
            if (jqXHR.getResponseHeader("Content-Type").indexOf('json') && 500 === jqXHR.status) {
                console.error(JSON.parse(jqXHR.responseText).error.message);

                return;
            }

            console.error(jqXHR.status + ' HTTP response.');
        });
    }
}
