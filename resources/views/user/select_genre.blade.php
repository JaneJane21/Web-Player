@extends('layout.app')
@section('title')
РЕГИСТРАЦИЯ
@endsection
@section('content')
<div class="container" id="Select-Genre">
    <div class="row mt-5">
        <div class="col-auto">
            <h1 style="color:white">Расскажите о Ваших предпочтениях</h1>

        </div>
    </div>
    <div class="row align-items-center">
        <div v-for="g in genres" class="genre-card col-auto d-flex flex-column align-items-center" @click="selectGenre(g.id)" :id="g.id">
            <img style="width: 100px; height:100px;" :src="`/public/${g.img}`">
            <p style="color: white; margin-bottom:0;">@{{ g.title }}</p>
        </div>
        <div class="col-auto">
            <svg style="cursor: pointer" @click="sendData()" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="white" class="bi bi-arrow-right-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm4.5 5.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
            </svg>
        </div>
    </div>
</div>
<style>
    body{
        background-image: url({{url('public/bg/wave_bottom_bg.png')}});
        background-repeat: no-repeat;
        background-size: cover;
    }
    .genre-card{
        /* border: 1px solid white; */
        padding: 10px 15px;
        border-radius: 20px;
        margin: 10px;
    }

    .genre-card-selected{
        border: 1px solid white;
        /* padding: 10px 15px; */
        border-radius: 20px;
        /* margin: 10px; */
    }
</style>
<script>
const app = {
    data(){
        return{
            genres: [],
            selectedGenres: []
        }
    },
    methods:{
        async getGenres(){
            const response_genres = await fetch('{{ route('get_genres') }}')
            this.genres = await response_genres.json()
        },
        selectGenre(id){
            let elem = document.getElementById(id)
            elem.classList.toggle('genre-card-selected')
            if(this.selectedGenres.includes(id)){
                this.selectedGenres.splice(this.selectedGenres.indexOf(id),1)
            }
            else{
                this.selectedGenres.push(id)
            }
        },
        async sendData(){
            const response = await fetch('{{ route('post_genres') }}',{
                method:'post',
                headers:{
                    'X-CSRF-TOKEN':'{{ csrf_token() }}',
                    'Content-Type':'application/json'
                },
                body:JSON.stringify(this.selectedGenres)
            })
            if(response.status == 200){
                window.location = response.url
            }
        }
    },
    mounted(){
        this.getGenres()
    }
}
Vue.createApp(app).mount('#Select-Genre')
</script>
@endsection
