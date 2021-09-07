<template>
    <div>

        <div class="container">
            <div class="text-center mt-5 mb-5"><h1>Update Post</h1></div>
            <div style="display: flex;justify-content: center">
                <div style="width: 500px">
                    <form   v-on:submit.prevent="onSubmitForm">

                        <div class="mb-5 position-relative">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" v-bind:class="form.errors.title? 'is-invalid': ''" v-model="data.title" >
                            <div class="invalid-tooltip" v-if="form.errors.title">
                                {{form.errors.title}}
                            </div>
                        </div>
                        <div class="mb-5 position-relative">
                            <label for="short_content" class="form-label">Short content</label>
                            <input type="text" class="form-control" id="short_content" v-bind:class="form.errors.short_content? 'is-invalid': ''" placeholder="" v-model="data.short_content">
                            <div class="invalid-tooltip"  v-if="form.errors.short_content">
                                {{form.errors.short_content}}
                            </div>
                        </div>
                        <div class="mb-5 position-relative">
                            <label for="content" class="form-label" >Content</label>
                            <textarea class="form-control" id="content" v-bind:class="form.errors.content? 'is-invalid': ''" rows="3" v-model="data.content" ></textarea>
                            <div class="invalid-tooltip"   v-if="form.errors.content">
                                {{form.errors.content}}
                            </div>
                        </div>
                        <div class="mb-5 position-relative">
                            <label for="cover" class="form-label" >Cover</label>
                            <input type="file" class="form-control" ref="cover" id="cover" v-bind:class="form.errors.cover? 'is-invalid': ''" aria-describedby="cover" v-on:change="handleFileUpload()" accept=".jpeg,.jpg,.png" aria-label="Upload" >
                            <div class="invalid-tooltip"  v-if="form.errors.cover">
                                {{form.errors.cover}}
                            </div>
                        </div>

                        <div class="mt-5 text-center" >
                            <button type="submit" class="btn-lg btn-success" style="margin-right: 5px" >Update</button>
                            <button type="button" class="btn-lg btn-danger" v-on:click="onDelete()" style="margin-right: 5px" >Delete</button>
                        </div>
                    </form>

                    <div class="text-center mt-5"><router-link role="button" to="/"  >Back to home</router-link></div>

                </div>
            </div>
        </div>
        <div class="position-fixed top-0 p-3" style="z-index: 11" >
            <div id="success" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <svg class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#007aff"></rect></svg>
                    <strong class="me-auto">Post notification</strong>
                    <small>now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Post Successfully updated <router-link href="" v-bind:to="'/show/'+this.data.id">show post</router-link>
                </div>
            </div>
        </div>
        <div class="position-fixed top-0 p-3" style="z-index: 11" >
            <div id="error" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <svg class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#fe0000"></rect></svg>
                    <strong class="me-auto">Error notification</strong>
                    <small>now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Oh sorry ! {{errorMessage}}. After 5 minute try again please.
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PostEdit",
        data() {
            return {
                server: 'http://127.0.0.1:8000',
                created: '',
                form:{
                    errors: {
                        title: '',
                        short_content: '',
                        content: '',
                        cover: '',
                    },
                    valid: false
                },
                data: {
                    id: '',
                    title: '',
                    short_content: '',
                    content: '',
                    cover: '',
                },
                errorMessage: ''
            }
        },
        mounted() {
            if (!(new RegExp('^\\d+$').test(this.$route.params.id))){
                this.$router.push('/')
            }else {
                this.$http.get(this.server + '/api/post/' + this.$route.params.id).then((data) => {
                    if (!data.data.data) {
                        this.$router.push('/')
                    }else{
                        this.data = data.data.data;
                        this.data.cover = '';
                    }
                })
            }
        },
        methods: {
            checkForm: function () {
                this.form.errors = {
                    title: '',
                    short_content: '',
                    content: '',
                    cover: '',
                };
                if (!this.data.title) {
                    this.form.errors.title = 'Title required';
                }else{
                    this.form.errors.titles = '';
                }
                if (!this.data.short_content) {
                    this.form.errors.short_content = 'Short content required';
                }else{
                    this.form.errors.short_content = '';
                }
                if (!this.data.content) {
                    this.form.errors.content = 'Content required';
                }else{
                    this.form.errors.content = '';
                }
                this.form.valid = (this.form.errors.title === '' && this.form.errors.short_content === '' && this.form.errors.content === '' );

            },
            onSubmitForm(){
                this.checkForm();

                if ( this.form.valid){
                    let data = new FormData();
                    data.append('title', this.data.title);
                    data.append('short_content', this.data.short_content);
                    data.append('content', this.data.content);

                    if (this.data.cover){
                        data.append('cover', this.data.cover);
                    }

                    this.$http.post(this.server + '/api/post/update/'+this.data.id,data,{
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then((data) => {
                        if (data.status === 200){
                            let success = document.getElementById('success')
                            // eslint-disable-next-line no-undef
                            let toast = new bootstrap.Toast(success);
                            toast.show();
                        }
                    }).catch((err) => {

                        this.errorMessage = err.message;
                        let error = document.getElementById('error')
                        // eslint-disable-next-line no-undef
                        let toast = new bootstrap.Toast(error);
                        toast.show();

                    })
                }
            },
            handleFileUpload(){
                this.data.cover = this.$refs.cover.files[0];
                console.log(this.data.cover);
                this.checkForm();
            },
            onDelete(){
                let yes = window.confirm('Do you really want to delete ? ');
                if (yes){
                    this.$http.get(this.server + '/api/post/delete/' + this.$route.params.id).then(() => {
                        this.$router.push('/')
                    })
                }
            }
        }
    }
</script>
