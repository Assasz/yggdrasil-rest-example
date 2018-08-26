/**
 * Application instance
 *
 * @type {App}
 */
const app = (new App())
    .initNProgress()
    .mount('yjax', new YjaxPlugin('http://localhost/creative-notes/api/web'));
