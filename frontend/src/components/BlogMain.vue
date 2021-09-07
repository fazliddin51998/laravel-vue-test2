<template>
    <div>
        <section class="py-5 text-center container">
            <div class="row py-lg-5">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <h1 class="fw-light">CRUD example</h1>
                    <p class="lead text-muted">Something short and leading about the collection below—its contents, the creator, etc. Make it short and sweet, but not too short so folks don’t simply skip over it entirely.</p>
                    <p>
                        <router-link to="/create" class="btn btn-success my-2">Create post</router-link>
                    </p>
                </div>
            </div>
        </section>
        <div class="album py-5 bg-light"   v-if="posts">
            <div class="container">

                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <div class="col" v-for="post in posts" :key="post.id">
                        <div class="card shadow-sm">
                            <img class="bd-placeholder-img card-img-top" width="100%" height="225" v-bind:src="server + post.cover_url" alt="sava">
                            <div class="card-body">
                                <h5 class="card-text">{{post.title}}</h5>
                                <p class="card-text">{{post.short_content}}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <router-link  v-bind:to="'/show/' + post.id" type="button" class="btn btn-sm btn-outline-secondary">View</router-link>
                                        <router-link v-bind:to="'/edit/' + post.id" type="button" class="btn btn-sm btn-outline-secondary">Edit</router-link>
                                    </div>
                                    <small class="text-muted">{{new Date(post.created_at).toString().replace('GMT+0500 (Uzbekistan Standard Time)','')}}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="album py-5 bg-light text-center" v-if="!posts"><h3>There is no post yet</h3></div>
    </div>
</template>

<script>


export default {
        name: 'BlogMain',
        data() {
            return {
                posts: '',
                server: 'http://127.0.0.1:8000'
            }
        },
        mounted() {
            this.$http.get(this.server +'/api/post/').then((data) => {
                this.posts = data.data.data;
            })
        }
    }
</script>

<style>

    .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }

    @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
    }

</style>
