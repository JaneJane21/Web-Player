@extends('layout.app')
@section('title')
Профиль пользователя
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
        <div class="col-3 d-flex flex-column">
            <button style="color: white; background: linear-gradient(45deg,#3F1573,#561567)" class="btn mb-4">редактировать профиль</button>
            <button style="color: white; background: linear-gradient(45deg,#561567,#3F1573)" class="btn">хочу стать артистом</button>
        </div>
    </div>
    <div class="row" style="margin-bottom: 100px;">
        <h5>Мои предпочтения</h5>
        <div class="col-2 d-flex flex-column align-items-center" v-for="f in favs">
            <img style="width: 150px; height:150px;" :src="`/public/${f.genre.img}`">
            <p>@{{ f.genre.title }}</p>
        </div>
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

            async get_my_favs(){
                const response_favs = await fetch('{{ route('user_favs') }}')
                this.favs = await response_favs.json()
                console.log(this.favs)
            }
            // async delete_track(id){
            //     let form = document.getElementById('delete_form_'+id)
            //     let form_data = new FormData(form)
            //     form_data.append('track',id)
            //     const response_delete = await fetch('{{ route('application_delete_track') }}',{
            //         method:'post',
            //         headers:{
            //             'X-CSRF-TOKEN':'{{ csrf_token() }}'
            //         },
            //         body:form_data
            //     })
            //     if(response_delete.status == 200){
            //         this.message = await response_delete.json()
            //         this.get_my_tracks()
            //     }
            // }
        },

        created(){
            this.user = window.user;
            console.log(this.user)
        },
        mounted(){
            this.get_my_favs()
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
