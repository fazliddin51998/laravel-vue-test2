<template>
    <div>
        <section class="py-5 text-center container cover-img" v-bind:style="{background: '#cccccc url(\'http://127.0.0.1:8000'+ post.cover_url}"   >
            <div class="row py-lg-5">
                <div class="col-lg-6 col-md-8 mx-auto" style="height: 500px;background: #222222; opacity: 0.8; padding: 30px">
                    <h1 class="fw-light">{{post.title}}</h1>
                    <p class="lead text-muted">{{post.short_content}}</p>
                    <p class="lead text-muted"><router-link v-bind:to="'/edit/'+post.id">Edit post</router-link></p>
                    <p class="lead text-muted"><router-link v-bind:to="'/'">All post</router-link></p>

                </div>
            </div>
        </section>
        <div class="album py-5 bg-light">
            <div class="container">
                <div class="d-flex align-items-center flex-column text-center">
                    <h2>{{post.title}}</h2>
                    <div>{{created}}</div>
                </div>
                <hr>
                <div>
                    {{post.content}}
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: "PostView",
        data() {
            return {
                post: '',
                server: 'http://127.0.0.1:8000',
                created: '',
            }
        },

        mounted() {

            if (!(new RegExp('^\\d+$').test(this.$route.params.id))){
                this.$router.push('/')
            }else{
                this.$http.get(this.server +'/api/post/'+ this.$route.params.id ).then((data) => {
                    if (! data.data.data){
                        this.$router.push('/')
                    }else{
                        this.post = data.data.data;
                        this.created = new Date(this.post.created_at).toString().replace('GMT+0500 (Uzbekistan Standard Time)','')
                    }
                }).catch((error) => {
                    if (error){
                        this.$router.push('/')
                    }
                })
            }
        }
    }
</script>
<style>
    .cover-img{
        background-size: 100% 100% !important;
        background-repeat: no-repeat !important;
        color: white !important;
    }
</style>
