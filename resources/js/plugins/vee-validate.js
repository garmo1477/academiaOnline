import { extend } from 'vee-validate';
import * as rules from 'vee-validate/dist/rules';
import { messages } from 'vee-validate/dist/locale/es.json';
// validación de formularios con vuejs
// reglas validación
Object.keys(rules).forEach(rule => {
    extend(rule, {
        ...rules[rule], 
        message: messages[rule]
    });
});

//validaciones
extend('wysiwyg-required', {
    ...rules['required'],
    message: 'El campo descripción es requerido'
});

extend('wysiwyg-min', {
    ...rules['min'],
    message: 'El campo descripción ha de tener al menos {length} carácteres'
});

