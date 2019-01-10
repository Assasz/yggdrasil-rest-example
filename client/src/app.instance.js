/**
 * Application instance
 *
 * @type {App}
 */
const app = (new App())
    .initNProgress()
    .mount('yjax', new YjaxPlugin({
        host: 'http://localhost/creative-notes/api/web',
        routesProvider: '/yjax/routes'
    }));
