<template>
    <div>
        <h2>Articles</h2>
        <form class="mb-3" @submit.prevent="addArticle()">
            <div class="form-group">
                <input type="text" class="form-control" name="title" placeholder="Title" v-model="article.title"/>
            </div>
            <div class="form-group">
                <textarea class="form-control" placeholder="Body" name="body" v-model="article.body"></textarea>
            </div>
            <input type="submit" class="btn btn-light btn-block" value="Save" />
        </form>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item" v-bind:class="[{disabled: !pagination.prev_page_url}]">
                    <a href="#" class="page-link" @click="getArticles(pagination.prev_page_url)">Previous</a>
                </li>
                <li class="page-item disabled">
                    <a href="#" class="page-link text-dark" @click="getArticles(pagination.prev_page_url)">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a>
                </li>
                <li class="page-item" v-bind:class="[{disabled: !pagination.next_page_url}]">
                    <a href="#" class="page-link" @click="getArticles(pagination.next_page_url)">Next</a>
                </li>
            </ul>
        </nav>
        <div class="card card-body mb-2" v-for="article in articles" v-bind:key="article.id">
            <h3>{{ article.title }}</h3>
            <p>{{ article.body }}</p>
            <hr>
            <button @click="deleteArticle(article.id)" class="btn btn-danger mb-2">Delete</button>
            <button @click="editArticle(article)" class="btn btn-warning">Edit</button>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: 'laravelApiVue',
    data(){
        return {
            articles: [],
            article: {
                id: '',
                title: '',
                body: ''
            },
            article_id: '',
            pagination: {},
            edit: false
        };
    },
    created(){
        this.getArticles();
    },
    methods: {
        getArticles(page_url){
            let vm = this;
            page_url = page_url || 'api/articles';
            axios.get(page_url)
                .then(response => {this.articles = response.data.data; vm.makePagination(response.data.meta, response.data.links);})
                //.then(response => (console.log(response.data.data)))
                .catch(error => console.log(error));
        },
        makePagination(meta, links){
            let pagination = {
                current_page: meta.current_page,
                last_page: meta.last_page,
                next_page_url: links.next,
                prev_page_url: links.prev
            };

            this.pagination = pagination;
        },
        deleteArticle(id){
            if(confirm('Are you sure?'))
            {
                axios.delete("api/article/" + id)
                    .then(response => {
                        alert('Article Removed');
                        this.getArticles();
                    })
                    .catch(error => console.log(error));
            }
        },
        addArticle(){
            if(this.edit === false)
            {
                // Add
                const article = {
                    article_id: '',
                    title: this.article.title,
                    body: this.article.body
                };
                axios.post('api/article/store', {article})
                    .then(response => {
                        this.article.title = '';
                        this.article.body = '';
                        this.getArticles();
                    })
                    .catch(error => console.log(error));
            }
            else
            {
                // Update
                const article = {
                    article_id: this.article.article_id,
                    title: this.article.title,
                    body: this.article.body
                };
                //console.log(article);
                axios.put('api/article/update', {article})
                    .then(response => {
                        this.article.title = '';
                        this.article.body = '';
                        this.getArticles();
                        this.edit = false;
                    })
                    .catch(error => console.log(error));
            }
        },
        editArticle(article){
            this.edit = true;
            this.article.id = article.id;
            this.article.article_id = article.id;
            this.article.title = article.title;
            this.article.body = article.body;
            
        }
    }
}
</script>