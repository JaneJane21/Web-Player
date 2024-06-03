@extends('layout.app')
@section('title')
Поиск битов
@endsection
@section('content')
<div class="container mt-5 mb-5 pb-5" id="Search">
    <div class="mt-5 mb-5" :class="message?'alert alert-light':''">
    @{{ message }}
    </div>
    <div class="row mb-5">
        <h5 style="color: white;" class="extra-bold mb-3">ПОИСК</h5>
        <form class="d-flex me-auto" role="search" method="post" action="{{ route('search') }}">
            @method('post')
            @csrf
            <input class="form-control me-2"  v-model="search" style="background: none; border-color: white; color:white;" type="text" aria-label="Search" name="search">
            <button class="btn" type="submit"><img src="{{ asset('public\icons\white_search.svg') }}"></button>
          </form>
    </div>
    <div v-if="tracks.length > 0 || albums.length > 0 || search_playlists.length > 0" class="row">
        <h6 v-if="tracks.length > 0" style="color: white;">ТРЕКИ</h6>
        <div class="" v-for="(track,key) in tracks">

            <div class="row justify-content-between align-items-center mb-3"
                style="cursor: pointer">
                {{-- ТРЕК --}}
                <div class="col-auto" @click.prevent="changeStatus(track, key)" style="flex-grow: 1">
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
        <h6 v-if="albums.length > 0" class="mb-3" style="color: white;">АЛЬБОМЫ</h6>
        <div v-for="album in albums" class="">
            <div class="row align-items-center mb-3">
                <div class="col-auto" style="width: 50px;" v-if="album.img">
                    <img class="img-fluid" :src="'/public/'+album.img">
                </div>
                <div v-else class="col-auto" style="width: 50px; height: 50px; background-color:#505050;" >
                </div>
                <div class="col-auto d-flex flex-column">
                    <p class="bold" style="color: white; margin:0;">@{{ album.title }}</p>

                </div>
            </div>
        </div>

        <h6 v-if="search_playlists.length > 0" class="mb-3" style="color: white;">ПЛЕЙЛИСТЫ</h6>
        <div v-for="list in search_playlists" class="">
            <div class="row align-items-center  mb-3">
                <div class="col-auto" style="width: 50px; height: 50px;" v-if="list.img">
                    <img class="img-fluid" :src="'/public/'+list.img">
                </div>
                <div v-else class="col-auto" style="width: 50px; height: 50px; background-color:#505050;" >
                </div>
                <div class="col-auto d-flex flex-column">
                    <p class="bold" style="color: white; margin:0;">@{{ list.title }}</p>

                </div>
            </div>
        </div>
    </div>
    <div v-else class="" >
        <p style="color: white;">К сожалению, ничего не нашлось</p>
    </div>

</div>
<script>
    window.tracks = @json($tracks);
    window.search = @json($search);
    window.playlists = @json($playlists);
    window.albums = @json($albums);
    const app = {
        data(){
            return{
                tracks:[],
                albums:[],
                search_playlists:[],
                current:{},
                index:0,
                player: new Audio(),
                isPlaying:'',
                playlists:[],
                stopTime:0,
                search:'',
                message:'',
                isPlay: false,
                current_key:'',
            }
        },
        methods:{

            play(song){
                // ------------------OLD--------------
                // if(typeof song.audio != undefined){

                //     if(song.id == this.current.id){

                //         this.player.currentTime = this.stopTime

                //     }
                //     else{
                //         this.current = song
                //         this.player.src = '/public/'+this.current.audio
                //         this.player.currentTime = 0
                //     }


                // }

                // this.player.play()

                // this.isPlaying = song.id

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
            },
            changeStatus(song,key) {
                this.current_key = key

                if (this.isPlaying == song.id) {
                    this.pause()
                } else {
                    this.play(song)
                }

            },
            pause(song){
                this.isPlay = false
                this.stopTime = this.player.currentTime

                // this.player.pause()
                this.isPlaying = ''
                this.track_to_bar('pause',this.stopTime)
            },
            async get_playlists(track){
                const response_playlists = await fetch('{{ route('getPlaylist') }}');
                this.playlists = await response_playlists.json();
            },
            loadTracks(){
                if(this.tracks.length > 0){
                    this.tracks.forEach((song, index) => {
                    let audio = new Audio('/public/'+song.audio);
                    audio.addEventListener('loadedmetadata', () => {
                    // Добавляем длительность к объекту песни
                    this.tracks[index].duration = ((audio.duration/60).toFixed(2)).split('.').join(':')
                    });
                    });
                    this.current = this.tracks[this.index]
                    this.player.src = '/public/'+this.current.audio
                }

            },
            // watch_track(){
            //     this.player.addEventListener('ended',()=>{
            //         this.stop_playing()
            //     })
            //     },

            // async stop_playing(){
            //     console.log(this.track)
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
            //     // if(response_track.status == 200){
            //     //     res = await response_track.json()
            //     //     this.track.listeners = res[0]
            //     //     console.log(res)

            //     // }
            // },
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
                this.current_key = this.current_key+1
                let song = this.tracks[this.current_key]
                if(song != undefined){
                    // console.log('if')
                    this.play(song)
                }
                else{
                    // console.log('else')
                    this.current_key = 0
                    // console.log(this.playlist)
                    this.play(this.tracks[0])
                }
                // console.log(song)

            },
            play_prev(){
                this.current_key = this.current_key-1
                let song = this.tracks[this.current_key]
                if(song != undefined){
                    // console.log('if')
                    this.play(song)
                }
                else{
                    // console.log('else')
                    this.current_key = this.tracks.length - 1
                    this.play(this.tracks[this.current_key])
                }
                // console.log(song)

            }
        },
        created(){
            this.tracks = window.tracks || [];
            this.albums = window.albums || [];
            this.search_playlists = window.playlists || [];
            this.search = window.search || '';
            this.loadTracks()
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
        }
    }
    Vue.createApp(app).mount('#Search')
</script>

<style>
    body{
        background-image: url({{url('public/bg/wave_bottom_bg.png')}});
        background-repeat: no-repeat;
        background-size: cover;
    }
    a{
        text-decoration: none;
    }
    button{
        background: none;
        border: none;
        color: white;
    }
</style>


@endsection
