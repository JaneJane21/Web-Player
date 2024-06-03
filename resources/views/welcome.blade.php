@extends('layout.app')
@section('title')
    МИБИТ
@endsection
@section('content')
    <div class="container mt-5 mb-5 pb-5" id="Welcome">
        @if (session()->has('success'))
            <div class="alert alert-light mt-5 mb-5">
                {{ session('success') }}
            </div>
        @endif
        <div class="mt-5 mb-5" :class="message?'alert alert-light':''">
            @{{ message }}
        </div>
        <div class="row news">
            <h5 style="color: white;" class="extra-bold mb-3">Новинки</h5>
            <div class="" v-for="(track,key) in playlist">

                <div class="row justify-content-between align-items-center mb-3"
                    style="cursor: pointer">
                    {{-- ТРЕК --}}
                    <div class="col-auto" @click.prevent="changeStatus(track,key)" style="flex-grow: 1">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <img v-if="this.current_key === key && this.isPlay" src="{{ asset('public\icons\min-pause.svg') }}">
                                <img v-else src="{{ asset('public\icons\play.svg') }}">

                            </div>
                            <div class="col-auto">
                                <img style="width: 50px; height: 50px;" :src="'/public/' + track.img">
                            </div>
                            <div class="col-auto d-flex flex-column">
                                <a onclick="event.stopPropagation()" :href="`{{ route('track') }}/${track.id}`"
                                    class="bold" style="color: white; margin:0;">@{{ track.title }}</a>
                                <a style="color: white; margin:0;" href="#">@{{ track.user.nickname }}</a>

                            </div>

                        </div>
                    </div>
                    <div class="col-1">
                        <p style="color: white; margin-bottom:0;">@{{ track.duration }}</p>
                    </div>
                    {{-- ДОБАВЛЕНИЕ В ПЛЕЙЛИСТ --}}
                    <div class="col-auto">
                        <div class="row">
                            @auth
                                <div class="col-auto">
                                    <button @click="get_playlists(track)" data-bs-toggle="modal"
                                        :data-bs-target="`#playlistModal_${track.id}`" style="font-size: 40px;">+</button>
                                    <!-- Modal -->
                                    <div class="modal fade" :id="`playlistModal_${track.id}`" tabindex="-1"
                                        aria-labelledby="playlistModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 style="color: black !important;" class="modal-title fs-5"
                                                        id="playlistModalLabel">Добавить в плейлист @{{ track.title }}</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="accordion mb-5" id="accordionExample">
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header">
                                                                <button style="color: black !important;"
                                                                    class="accordion-button collapsed" type="button"
                                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                                    aria-expanded="true" aria-controls="collapseOne">
                                                                    добавить в новый плейлист
                                                                </button>
                                                            </h2>
                                                            <div id="collapseOne" class="accordion-collapse collapse"
                                                                data-bs-parent="#accordionExample">
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
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div>
    <style>
        body {
            background-image: url('public\\bg\\welcome_gradient.png');
            background-repeat: no-repeat;
            background-size: cover;
        }

        a {
            text-decoration: none;
        }

        button {
            background: none;
            border: none;
            color: white;
        }
    </style>
    <script>
        if (localStorage.getItem('stopTime')) {
        // Извлекаем и парсим данные из локального хранилища
        const DataStopTime = JSON.parse(localStorage.getItem('stopTime'));
        console.log(DataStopTime);
        }
        const app = {
            data() {
                return {
                    playlist: [],
                    current: {},
                    current_key:'',
                    index: 0,
                    player: new Audio(),
                    isPlaying: '',
                    playlists: [],
                    stopTime: 0,
                    message:'',
                    isPlay: false,

                }
            },
            methods: {
                async getTracks() {
                    const response_tracks = await fetch('{{ route('get_all_tracks') }}');
                    this.playlist = await response_tracks.json()



                    this.playlist.forEach((song, index) => {
                    let audio = new Audio('/public/'+song.audio);
                    audio.addEventListener('loadedmetadata', () => {
                    // Добавляем длительность к объекту песни
                    this.playlist[index].duration = ((audio.duration/60).toFixed(2)).split('.').join(':')
                    });
                    });


                    this.current = this.playlist[this.index]
                    this.player.src = 'public' + this.current.audio


                },
                changeStatus(song, key) {
                    this.current_key = key

                    if (this.isPlaying == song.id) {

                        this.pause()
                    } else {

                        this.play(song)
                    }

                },
                play(song) {
                    // console.log('localStorage.setItem(, JSON.stringify(song))')
                    // console.log(song)
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

                    // if (typeof song.audio != undefined) {

                    //     if (song.id == this.current.id) {

                    //         this.player.currentTime = this.stopTime

                    //     } else {
                    //         this.current = song
                    //         this.player.src = 'public' + this.current.audio
                    //         this.player.currentTime = 0
                    //     }


                    // }

                    // this.player.play()
                    this.player.volume = 0

                    this.isPlaying = song.id
                },
                pause(song) {
                    this.isPlay = false
                    this.stopTime = this.player.currentTime

                    // this.player.pause()
                    this.isPlaying = ''
                    this.track_to_bar('pause',this.stopTime)
                },
                async get_playlists(track) {
                    const response_playlists = await fetch('{{ route('getPlaylist') }}');
                    this.playlists = await response_playlists.json();
                },
                // watch_track(){
                // this.player.addEventListener('ended',()=>{
                //     this.stop_playing()
                // })
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

                //     this.pause()
                //     this.stopTime = 0
                //     // if(response_track.status == 200){
                //     //     res = await response_track.json()
                //     //     this.track.listeners = res[0]

                //     // }
                // },
                async add_to_new_playlist(track){
                // console.log(this.track)
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
                    // console.log('успешно')
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
                    // console.log('успешно')
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
                this.current_key = this.current_key+1
                let song = this.playlist[this.current_key]
                if(song != undefined){
                    // console.log('if')
                    this.play(song)
                }
                else{
                    // console.log('else')
                    this.current_key = 0
                    // console.log(this.playlist)
                    this.play(this.playlist[0])
                }
                // console.log(song)

            },
            play_prev(){
                this.current_key = this.current_key-1
                let song = this.playlist[this.current_key]
                if(song != undefined){
                    // console.log('if')
                    this.play(song)
                }
                else{
                    // console.log('else')
                    this.current_key = this.playlist.length - 1
                    this.play(this.playlist[this.current_key])
                }
                // console.log(song)

            },
            handlePageChange(e) {
                console.log("Пользователь перешел на страницу:", e.detail.path);
                console.log("Время стопа:", e.detail.stopTime);
                // Здесь можно выполнить любые действия в ответ на смену страницы
                }
            },
            created() {
                // window.addEventListener("page-changed", this.handlePageChange);
                window.addEventListener("page-changed", function(e){
                    console.log("Пользователь перешел на страницу:", e.detail.path);
                    console.log("Время стопа:", e.detail.stopTime);
                });

                this.getTracks()
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
                // console.log(this.current_key)
                // console.log('остановили на '+DataStopTime)
            },
            beforeUnmount() {
                window.removeEventListener("page-changed", this.handlePageChange);
            }
        }
        Vue.createApp(app).mount('#Welcome')
    </script>
@endsection
