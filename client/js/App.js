/**
 * Application class
 */
class App {

    /**
     * Initializes properties
     */
    constructor() {
        this.isPjax = false;
        this.actions = {};
        this.plugins = {};
    }

    /**
     * Initializes Pjax
     *
     * @return {App}
     */
    initPjax() {
        $(document).ready(function () {
            $(document).pjax('a:not([data-no-pjax])', '#pjax-container', {
                'timeout': 5000
            });

            $(document).on('submit', 'form[data-pjax]', function (event) {
                $.pjax.submit(event, '#pjax-container');
            });
        });

        this.isPjax = true;

        return this;
    }

    /**
     * Initializes NProgress
     * Ij Pjax is not initialized, Ajax events will be handled instead
     *
     * @return {App}
     */
    initNProgress() {
        if (!this.isPjax) {
            $(document).ready(function () {
                $(document).ajaxStart(function() {
                    NProgress.start();
                });

                $(document).ajaxComplete(function() {
                    NProgress.done();
                });
            });

            return this;
        }

        $(document).ready(function () {
            $(document).on('pjax:send', function () {
                NProgress.start();
            });

            $(document).on('pjax:end', function () {
                NProgress.done();
            });
        });

        return this;
    }

    /**
     * Registers action
     *
     * @param {string} name
     * @param {function} action
     * @return {App}
     */
    register(name, action) {
        this.actions[name] = action;

        return this;
    }

    /**
     * Runs given action
     *
     * @param {string|null} action Name of action - if null, last registered action will be run
     * @return {App}
     */
    run(action = null) {
        if (null === action) {
            let actionsKeys = Object.keys(this.actions);

            action = actionsKeys[actionsKeys.length - 1];
        }

        if (typeof this.actions[action] === 'undefined') {
            console.error(action + ' action doesn\'t exist.');

            return this;
        }

        let self = this;

        $(document).ready(function () {
            self.actions[action]();
        });

        if (this.isPjax) {
            $(document).on('pjax:end', function () {
                self.actions[action]();
            });
        }

        return this;
    }

    /**
     * Mounts plugin
     *
     * @param {string} name
     * @param {object} plugin
     * @return {App}
     */
    mount(name, plugin) {
        this.plugins[name] = plugin;

        return this;
    }

    /**
     * Uses given plugin
     *
     * @param {string} plugin Name of plugin
     * @return {object|null}
     */
    use(plugin) {
        if (typeof this.plugins[plugin] === 'undefined') {
            console.error(plugin + ' plugin doesn\'t exist.');

            return null;
        }

        return this.plugins[plugin];
    }
}
