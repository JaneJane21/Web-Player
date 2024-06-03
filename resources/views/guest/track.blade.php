@extends('layout.app')
@section('title')
Track
@endsection
@section('content')
<div class="container mb-5 pb-5" id="Track">
    @if(session()->has('success'))
        <div class="alert alert-light mt-5 mb-5 text-dark">
            {{ session('success') }}
        </div>
    @endif
    <div class="mt-5 mb-5" :class="message?'alert alert-light':''">
        @{{ message }}
    </div>
    <div class="row mt-5 justify-content-around pb-5">
        <div class="col-4">
            <div style="width:400px; height:400px;" class="img-block mb-3">
                <img class="" style="object-fit: cover; width:400px; height:400px;" :src="'/public/'+track.img">
            </div>


                    <p style="margin-bottom:2px; font-size:20px;">@{{ track.user.nickname }}</p>
                    <div class="row align-items-center flex-nowrap justify-content-between">
                        <div class="col-auto">
                            <h1 class="bold" >@{{ track.title }}</h1>
                        </div>
                            @auth
                            <div class="col-auto">
                                <button @click="get_playlists" data-bs-toggle="modal" data-bs-target="#playlistModal" style="font-size: 40px;">+</button>
                                <!-- Modal -->
                                <div class="modal fade" id="playlistModal" tabindex="-1" aria-labelledby="playlistModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h1 style="color: black !important;" class="modal-title fs-5" id="playlistModalLabel">Добавить в плейлист</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="accordion mb-5" id="accordionExample">
                                                <div class="accordion-item">
                                                  <h2 class="accordion-header">
                                                    <button style="color: black !important;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                      добавить в новый плейлист
                                                    </button>
                                                  </h2>
                                                  <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <form :id="`add_to_new_playlist_form_${track.id}`" method="post" @submit.prevent="add_to_new_playlist(track.id)">

                                                            <input type="text" placeholder="название плейлиста"
                                                                class="form-control mb-2" name="title">
                                                            <button type="submit"
                                                                class="btn btn-dark">сохранить</button>
                                                        </form>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                              <form class="mb-4" method="post" :id="`add_to_playlist_form_${track.id}`" @submit.prevent="add_to_playlist(track.id)">

                                                <div class="row mb-3 align-items-center justify-content-between"
                                                    v-for="list in playlists">
                                                    <div class="col-3">
                                                        <label style="color: black;" class="form-check-label"
                                                            :for="list.id">@{{ list.title }}</label>
                                                    </div>
                                                    <div class="col-2">
                                                        <input type="checkbox" class="form-check-input"
                                                            :id="list.id" :value="list.id"
                                                            name="playlists[]">
                                                    </div>

                                                </div>
                                                <button type="submit" class="btn btn-dark">сохранить</button>
                                            </form>


                                        </div>

                                    </div>
                                    </div>
                                </div>
                            </div>
                            @endauth

                            <div class="col-auto">
                                <button @click="changeStatus(track,0)" class="btn btn-link" v-if="!isPlay">
                                    <img src="{{ asset('public\icons\play-circle.svg') }}">
                                </button>
                                <button @click="changeStatus(track,0)" class="btn btn-link" v-else>
                                    <img src="{{ asset('public\icons\pause.svg') }}">
                                </button>
                            </div>


                    </div>
                    <p style="">@{{ track.time }}</p>
                    <p style="">@{{ track.listeners }} прослушиваний</p>



        </div>
        <div class="col-4" >
            <div class="text-box">
                <p v-for="str in track.text">
                    @{{ str }}
                </p>

            </div>

        </div>
    </div>
</div>
<style>
    body{
        background-image: url({{url('public/bg/wave_top_bg.png')}});
        background-repeat: no-repeat;
        background-size: cover;
    }
    .text-box{
        width: 400px;
        max-height: 600px;
        overflow: auto;
        padding-right: 50px;
        scrollbar-color: #505050 #e0e0e000;
        scrollbar-width: thin;
    }
    p, h1, div,button{
        color: white !important;
    }
    button{
        background: none;
        border: none;
    }

    /* .container{
        display: flex;
        align-items: center;
        justify-content: center;
    } */
</style>
<script>
    // if (localStorage.getItem('stopTime')) {
    //     // Извлекаем и парсим данные из локального хранилища
    //     const DataStopTime = JSON.parse(localStorage.getItem('stopTime'));
    //     console.log('DataStopTime '+DataStopTime);
    // }
    const app = {
        data(){
            return{
                track:{},
                player: new Audio(),
                isPlaying:'',
                current:{},
                playlists:[],
                stopTime:0,
                message:'',
                current_key:'',
                isPlay: false,
            }
        },
        methods:{
            async getTrack(){
                // console.log({{ $track }})
                const response_track = await fetch('{{ route('index_one') }}',{
                    method:'post',
                    headers:{
                        'X-CSRF-TOKEN':'{{ csrf_token() }}',
                        'Content-Type':'application/json'
                    },
                    body:JSON.stringify({{ $track }})
                })
                if(response_track.status == 200){
                    this.track = await response_track.json()
                    console.log(this.track.user.nickname)
                    this.track.text = this.track.text.split("\n")
                    this.current = this.track
                    this.player.src = '/public/'+this.current.audio
                    this.player.load()


                    this.player.onloadedmetadata = () => {
                    // Получить длительность аудио
                    console.log('Длительность аудио:', this.player.duration);
                    this.track.time = ((this.player.duration/60).toFixed(2)).split('.').join(':')
                    localStorage.setItem('current_track', JSON.stringify(this.current));
                    };
                }

            },
            changeStatus(song, key) {
                this.current_key = key

                if (this.isPlaying == song.id) {

                    this.pause()
                } else {

                    this.play(song)
                }

            },
            play(song){


                // if(typeof song.audio != undefined){
                //     this.current = song
                //     this.player.src = '/public/'+this.current.audio


                // }
                // this.player.currentTime = this.stopTime
                // this.player.autoplay = true
                // // this.player.play()
                // this.isPlaying = song.id

                // if(this.player.currentTime == this.player.duration){
                //     this.stop_playing()
                // }


                    // ---------------old------------------
                // localStorage.setItem('current_track', JSON.stringify(this.current));
                // this.track_to_bar()

                this.isPlay = true
                    if (song.id == this.current.id) {

                        this.player.currentTime = this.stopTime

                    } else {
                        this.current = song
                        this.player.src = 'public' + this.current.audio
                        this.player.currentTime = 0
                        this.stopTime = 0
                    }
                    localStorage.setItem('current_track', JSON.stringify(song));
                    this.track_to_bar('play',this.stopTime)
                    this.player.volume = 0
                    this.isPlaying = song.id
                // this.player.onended = this.stop_playing()
            },

            // watch_track(){
            //     this.player.addEventListener('ended',()=>{
            //         this.stop_playing()
            //     })
            // },

            // async stop_playing(){
            //     // console.log(this.track)
            //     const response_track = await fetch('{{ route('add_track_listen') }}',{
            //     method:'post',
            //     headers:{
            //         'X-CSRF-TOKEN':'{{ csrf_token() }}',
            //         'Content-Type':'application/json'
            //     },
            //     body:JSON.stringify(this.current.id)
            //     })
            //     console.log('закончилось')
            //     this.pause()
            //     this.stopTime = 0
            //     if(response_track.status == 200){
            //         res = await response_track.json()
            //         this.track.listeners = res[0]
            //         console.log(res)
            //         // this.track.listeners =
            //     }
            // },

            pause(){
                // ------------------OLD----------
                // this.stopTime = this.player.currentTime
                // this.player.pause()
                // this.isPlaying = ''

                this.isPlay = false
                this.stopTime = this.player.currentTime
                this.isPlaying = ''
                this.track_to_bar('pause',this.stopTime)

            },
            async get_playlists(){

                const response_playlists = await fetch('{{ route('getPlaylist') }}');
                this.playlists = await response_playlists.json();


            },
            async add_to_new_playlist(track){
                console.log(this.track)
                let form = document.getElementById('add_to_new_playlist_form_'+track)
                let form_data = new FormData(form)
                form_data.append('id',track)
                const response_track = await fetch('{{ route('song_in_new_playlist') }}',{
                method:'post',
                headers:{
                    'X-CSRF-TOKEN':'{{ csrf_token() }}',
                },
                body: form_data
                })

                if(response_track.status == 200){
                    console.log('успешно')
                    this.message = await response_track.json()
                }
            },
            async add_to_playlist(track){
                console.log('to playlist')
                let form = document.getElementById('add_to_playlist_form_'+track)
                let form_data = new FormData(form)
                form_data.append('id',track)
                const response_track = await fetch('{{ route('song_in_playlist') }}',{
                method:'post',
                headers:{
                    'X-CSRF-TOKEN':'{{ csrf_token() }}',
                },
                body: form_data
                })

                if(response_track.status == 200){
                    console.log('успешно')
                    this.message = await response_track.json()
                }
            },
            track_to_bar(status,time){
                // let current = localStorage.getItem('current_track')
                // current = JSON.parse(current)
                let s = status
                let event = new CustomEvent('custom-update',{detail:{status:s,time:time}});
                window.dispatchEvent(event);
            },
            play_next(){
                // this.current_key = this.current_key+1
                this.stopTime = 0
                let song = this.track
                if(song != undefined){
                    // console.log('if')
                    this.play(song)
                }
                // else{
                //     // console.log('else')
                //     this.current_key = 0
                //     // console.log(this.playlist)
                //     this.play(this.playlist[0])
                // }
                // console.log(song)

            },
            play_prev(){
                this.stopTime = 0
                // this.current_key = this.current_key-1
                let song = this.track
                if(song != undefined){
                    // console.log('if')
                    this.play(song)
                }
                // else{
                //     // console.log('else')
                //     this.current_key = this.playlist.length - 1
                //     this.play(this.playlist[this.current_key])
                // }
                // console.log(song)

            },
            handlePageChange(e) {
                console.log("Пользователь перешел на страницу:", e.detail.path);
                console.log("Время стопа:", e.detail.stopTime);
                // Здесь можно выполнить любые действия в ответ на смену страницы
                }
            ,

        },
        created(){
            // window.addEventListener("page-changed", this.handlePageChange);
            window.addEventListener("page-changed", function(e){
                    console.log("Пользователь перешел на страницу:", e.detail.path);
                    console.log("Время стопа:", e.detail.stopTime);
                });
            this.getTrack()

            window.addEventListener('bar-update', (el) => {
                    this.stopTime = el.detail.time
                    this.player.currentTime = this.stopTime

                    if(el.detail.status == 'play'){
                        // this.player.play()
                        this.isPlay = true
                    }
                    else{
                        this.isPlay = false
                        // this.player.pause()
                    }
                })
                window.addEventListener('next-track', (el) => {
                    this.play_next()
                })
                window.addEventListener('prev-track', (el) => {
                    this.play_prev()
                })

        },
        mounted(){
            // this.watch_track()
            if (localStorage.getItem('stopTime')) {
                // Извлекаем и парсим данные из локального хранилища
                const DataStopTime = JSON.parse(localStorage.getItem('stopTime'));
                console.log('DataStopTime '+DataStopTime);
            }

        },
        beforeUnmount() {
            window.removeEventListener("page-changed", this.handlePageChange);

        }

    }

    Vue.createApp(app).mount('#Track')
</script>
@endsection
