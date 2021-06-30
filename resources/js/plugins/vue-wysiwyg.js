import Vue from 'vue';
import wysiwyg from 'vue-wysiwyg';

//injectamos el plugin wysiwyg
Vue.use(wysiwyg, {
    hideModules: {
        'image': true,
        'table': true,
        'code': true
    }
})