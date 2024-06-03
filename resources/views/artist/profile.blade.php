@extends('layout.app')
@section('title')
Профиль артиста
@endsection
@section('content')
<div class="container mt-5 pb-5" id="Profile">
    <div class="mt-5 mb-5" :class="message?'alert alert-light':''">
        @{{ message }}
    </div>
    <div class="row mb-5">
        <div class="col-6">
            <div class="row">
                <div class="col-6">
                    <img v-if="user.photo != null" :src="'/public/'+user.photo">
                    <img src="{{ asset('public\icons\user-profile.jpg') }}" v-else alt="">
                </div>
                <div class="col-6">
                    <h5 class="mb-4">Личные данные</h5>
                    <p><span class="bold">Nickname: </span>@{{ user.nickname }}</p>
                    <p><span class="bold">Login: </span>@{{ user.login }}</p>
                    <p><span class="bold">Телефон: </span>@{{ user.phone }}</p>
                    <p><span class="bold">Дата рождения: </span>@{{ user.birthday }}</p>
                </div>
            </div>
        </div>
        <div  class="col-6">
            <button style="color: white; background: linear-gradient(45deg,#3F1573,#561567)" class="btn">редактировать профиль</button>

        </div>
    </div>
    <div class="row" >
        <h5>Мои предпочтения</h5>
        <div class="col-2 d-flex flex-column align-items-center" v-for="f in favs">
            <img style="width: 150px; height:150px;" :src="`/public/${f.genre.img}`">
            <p>@{{ f.genre.title }}</p>
        </div>
    </div>
    <div class="row mb-5">
        <ul class="nav nav-tabs" style="background: none !important; border-bottom: 2px solid white;  border-radius: 0px;">
            <li class="nav-item">
              <a @click="choose_tab('track')" class="nav-link page-nav-link active" id="tracks" aria-current="page" href="#">Мои треки</a>
            </li>
            <li class="nav-item">
              <a @click="choose_tab('album')" class="nav-link page-nav-link text-light" id="albums" href="#">Мои альбомы</a>
            </li>

          </ul>
    </div>
    <div class="tab row tracks mb-5 pb-5" id="track_tab">
        <div class="" v-for="track in tracks">
            <div class="row align-items-center mb-2">
                <div class="col-auto">
                <img style="width: 50px; height: 50px;" :src="'/public/' + track.img">
                </div>
                <div class="col-auto d-flex flex-column" style="flex-grow: 1;">
                    <p class="bold" style="color: white; margin:0;">@{{ track.title }}</p>
                    <p style="color: white; margin:0;" href="#">@{{ track.user.nickname }}</p>

                </div>
                <div class="col-auto">
                    статус: @{{ track.status }}
                </div>
                <div class="col-auto" >

                    <button v-if="track.is_available == 'true'" style=""  data-bs-toggle="modal" :data-bs-target="`#hideModal_${track.id}`" class="btn btn-light">скрыть трек</button>
                    <!-- Modal -->
                    <div class="modal fade" :id="`hideModal_${track.id}`" tabindex="-1" aria-labelledby="hideModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title text-dark fs-5" id="hideModalLabel">Укажите причину скрытия трека</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <form @submit.prevent="hide_track(track.id)" :id="`hide_form_${track.id}`" method="post">
                                <textarea class="form-control mb-3" name="text"></textarea>
                                <button type="submit" data-bs-dismiss="modal" class="btn btn-dark">отправить</button>
                        </form>
                        </div>

                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-auto">
                    <button v-if="track.is_available == 'true'" style="" data-bs-toggle="modal" :data-bs-target="`#deleteModal_${track.id}`" class="btn btn-light">удалить трек</button>
                    <!-- Modal -->
                    <div class="modal fade" :id="`deleteModal_${track.id}`" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title text-dark fs-5" id="deleteModalLabel">Укажите причину удаления трека</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form @submit.prevent="delete_track(track.id)" :id="`delete_form_${track.id}`" method="post">
                                    <textarea class="form-control mb-3" name="text"></textarea>
                                    <button type="submit" data-bs-dismiss="modal" class="btn btn-dark">отправить</button>
                                </form>
                            </div>

                            </div>
                        </div>
                        </div>
                </div>
            </div>

        </div>
    </div>
    <div v-if="user.status = 'артист'" class="row tab albums mb-5 pb-5 d-none" id="album_tab">
        <p>albums</p>
    </div>
</div>
<script>
    window.user = @json($user);
    const app = {
        data(){
            return{
                user:{},
                tracks:{},
                selectedTab:'',
                favs:[],
                message:''
            }

        },
        methods:{
            async get_my_tracks(){
                const response_tracks = await fetch('{{ route('get_my_tracks') }}');
                this.tracks = await response_tracks.json()

                const response_favs = await fetch('{{ route('user_favs') }}')
                this.favs = await response_favs.json()
            },
            choose_tab(tab){
                let links = document.querySelectorAll('.page-nav-link')
                links.forEach((l)=>{
                    l.classList.remove('active')
                    l.classList.add('text-light')
                    l.classList.remove('text-dark')
                })

                let link = document.getElementById(tab+'s');
                link.classList.add('active')
                link.classList.add('text-dark')
                link.classList.remove('text-light')

                let tabs = document.querySelectorAll('.tab')
                tabs.forEach((l)=>{
                    l.classList.add('d-none')
                })

                let content = document.getElementById(tab+'_tab');

                content.classList.add('d-block')
                content.classList.remove('d-none')
            },
            async hide_track(id){
                let form = document.getElementById('hide_form_'+id)
                let form_data = new FormData(form)
                form_data.append('track',id)
                const response_hide = await fetch('{{ route('application_hide_track') }}',{
                    method:'post',
                    headers:{
                        'X-CSRF-TOKEN':'{{ csrf_token() }}'
                    },
                    body:form_data
                })
                if(response_hide.status == 200){
                    this.message = await response_hide.json()
                    this.get_my_tracks()
                }
            },
            async delete_track(id){
                let form = document.getElementById('delete_form_'+id)
                let form_data = new FormData(form)
                form_data.append('track',id)
                const response_delete = await fetch('{{ route('application_delete_track') }}',{
                    method:'post',
                    headers:{
                        'X-CSRF-TOKEN':'{{ csrf_token() }}'
                    },
                    body:form_data
                })
                if(response_delete.status == 200){
                    this.message = await response_delete.json()
                    this.get_my_tracks()
                }
            }
        },

        created(){
            this.user = window.user;
            console.log(this.user)
        },
        mounted(){
            this.get_my_tracks()
        }
    }
    Vue.createApp(app).mount('#Profile')
</script>
<style>
    body{
        background-image: url({{url('public/bg/wave_bottom_bg.png')}});
        background-repeat: no-repeat;
        background-size: cover;
    }
    p,div,h5,span,button{
        color: white;
    }
</style>
@endsection
