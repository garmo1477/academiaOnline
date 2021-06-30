<template>
    <div>
        <vue-snotify />
        <ValidationObserver v-slot="{ invalid }">
            <form @submit.prevent="onSubmit">
                
                <ValidationProvider rules="required|min:3" v-slot="{ errors }">
                    <div class="form-group">
                        <label for="título">Título del debate</label>
                        <input
                            name="título"
                            id="título"
                            class="form-control"
                            v-model="form.title"
                        />
                        <span>{{ errors[0] }}</span>
                    </div>
                </ValidationProvider>

                  <ValidationProvider
                    rules="wysiwyg-required|wysiwyg-min:20"
                    v-slot="{ errors }"
                >
                    <div class="form-group">
                        <wysiwyg
                            id="content"
                            v-model="form.content"
                            placeholder="Escribe aquí tus dudas"
                        />
                        <span>{{ errors[0] }}</span>
                    </div>
                </ValidationProvider>
                <hr/>
                <button type="submit" class="btn btn-dark btn-block" :disabled="invalid">
                    Crear Debate
                </button>
            </form>
        </ValidationObserver> 
        <loading 
            :show="processing"
            label="Procesando formulario...">
            
        </loading>
    </div>
</template>

<script>

    import { ValidationProvider, ValidationObserver } from 'vee-validate';
    import loading from 'vue-full-loading';
    export default {
        name: 'TopicsForm',
        components: {
            ValidationProvider,
            ValidationObserver,
            loading
        },
        props: {
            course: {
                type: String,
                required: true
            }
        },
        data () {
            return{
                processing: false,
                form: {
                    title: null,
                    content: null
                }
            }
        },
        methods: {
            async onSubmit(){
                const self = this;
                const data = self.form;
                self.processing = true;
                try {
                    await window.axios({method: 'POST', url: `/courses/${this.$props.course}/topics`, data});
                    self.$snotify.success('Debate creado con éxito!','Tu debate ha sido publicado', {
                        width: 500,
                        timeout: 5000,
                        showProgressBar: false,
                        closeOnClick: false,
                        pauseOnHover: true
                    });

                    self.$learningBus.$emit('topics:new');
                    // una vez haya funcionado todo vaciamos el formulario
                    Object.assign(self.$data, self.$options.data());

                }catch(e){
                    if(e.response.data.hasOwnProperty('errors')){
                        for(let error in e.response.data.errors){
                            self.$snotify.error('Error de validación', e.response.data.errors[error][0], {
                                width: 500,
                                timeout: 5000,
                                showProgressBar: false,
                                closeOnClick: false,
                                pauseOnHover: true
                            });
                        }
                    }else{
                        self.$snotify.error('Error','Ha ocurrido un error en el servidor', {
                                width: 500,
                                timeout: 5000,
                                showProgressBar: false,
                                closeOnClick: false,
                                pauseOnHover: true
                            });
                    }
                }finally{
                    self.processing = false;
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import "~vue-wysiwyg/dist/vueWysiwyg.css";
</style>