@extends('layout.app')
@section('title')
Новый трек
@endsection
@section('content')
<div class="container d-flex justify-content-around flex-column align-items-center" id="New">
    <div class="mt-5 mb-5" :class="message?'alert alert-light':''">
        @{{ message }}
    </div>
  <p class="d-inline-flex gap-5 mt-5">
    <a class="btn btn-outline-light" data-bs-toggle="collapse" href="#trackCollapse" role="button" aria-expanded="false" aria-controls="trackCollapse">Добавить трек</a>
    <a class="btn btn-outline-light" data-bs-toggle="collapse" href="#albumCollaplse" role="button" aria-expanded="false" aria-controls="albumCollaplse">Добавить альбом</a>
  </p>
  <div class="row flex-nowrap w-100" style="margin-bottom: 100px; padding-bottom: 100px;">
    <div class="col-6">
      <div class="collapse" id="trackCollapse">
        <div class="card card-body">
          <h5>Расскажите о Вашем треке</h5>
          <form method="POST" enctype="multipart/form-data" @submit.prevent="add_new_track" id="new_track_form">

            <div class="mb-3">
              <label for="title" class="form-label">Название</label>
              <input type="text" class="form-control" id="title" name="title" :class="errors.title?'is_invalid':''">
              <div class="invalid-feedback d-block" v-for="error in errors.title">
                @{{ error }}
              </div>
            </div>
            <div class="mb-3">
              <label for="audio" class="form-label">Аудио-файл</label>
              <input type="file" accept=".mp3, .wav" class="form-control" id="audio" name="audio" :class="errors.audio?'is_invalid':''">
              <div class="invalid-feedback d-block" v-for="error in errors.audio">
                @{{ error }}
              </div>
            </div>
            <div class="mb-3">
              <label for="img" class="form-label">Обложка</label>
              <input type="file" accept=".png, .jpeg, .jpg" class="form-control" id="img" name="img" :class="errors.img?'is_invalid':''">
              <div class="invalid-feedback d-block" v-for="error in errors.img">
                @{{ error }}
              </div>
            </div>
            <div class="mb-3">
              <label for="genre_id" class="form-label">Жанр трека</label>
              <select id="genre_id" multiple class="form-control" name="genres[]" :class="errors.genres?'is_invalid':''">
                <option disabled value=" ">Выберите жанр...</option>
                @foreach ($genres as $g)
                <option value="{{ $g->id }}">{{ $g->title }}</option>
                @endforeach

              </select>
              <div class="invalid-feedback d-block" v-for="error in errors.genres">
                @{{ error }}
              </div>

            </div>
            <div class="mb-3">
              <label for="date_release" class="form-label">Дата релиза</label>
              <input type="date" class="form-control" id="date_release" name="date_release" :class="errors.date_release?'is_invalid':''">
              <div class="invalid-feedback d-block" v-for="error in errors.date_release">
                @{{ error }}
              </div>
            </div>
            <div class="mb-3">
              <label for="text" class="form-label">Текст трека</label>
              <textarea class="form-control" id="text" name="text" :class="errors.text?'is_invalid':''"></textarea>
              <div class="invalid-feedback d-block" v-for="error in errors.text">
                @{{ error }}
              </div>
            </div>
            <div class="form-check form-switch mb-3">
                <input name="is_cens" class="form-check-input" type="checkbox" role="switch" id="is_cens">
                <label class="form-check-label" for="is_cens">Цензурная маркировка</label>
              </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-6">
      <div class="collapse" id="albumCollaplse">
        <div class="card card-body">
            <form method="POST" enctype="multipart/form-data" @submit.prevent="add_new_album" id="new_album_form">

                <div class="mb-3">
                  <label for="title" class="form-label">Название</label>
                  <input type="text" class="form-control" id="title" name="title" :class="errors.title?'is_invalid':''">
                  <div class="invalid-feedback d-block" v-for="error in errors.title">
                    @{{ error }}
                  </div>
                </div>

                <div class="mb-3">
                  <label for="img" class="form-label">Обложка</label>
                  <input type="file" accept=".png, .jpeg, .jpg" class="form-control" id="img" name="img" :class="errors.img?'is_invalid':''">
                  <div class="invalid-feedback d-block" v-for="error in errors.img">
                    @{{ error }}
                  </div>
                </div>
                <div class="mb-3">
                  <label for="genre_id" class="form-label">Жанр трека</label>
                  <select multiple id="genre_id" class="form-control" name="genres[]" :class="errors.genres?'is_invalid':''">
                    <option disabled value=" ">Выберите жанр...</option>
                    @foreach ($genres as $g)
                    <option value="{{ $g->id }}">{{ $g->title }}</option>
                    @endforeach

                  </select>
                  <div class="invalid-feedback d-block" v-for="error in errors.genres">
                    @{{ error }}
                  </div>

                </div>
                <div class="mb-3">
                    <label for="track_id" class="form-label">Треки для альбома</label>
                    <select multiple id="track_id" class="form-control" name="tracks[]" :class="errors.tarcks?'is_invalid':''">
                      <option disabled value=" ">Выберите треки...</option>
                      @foreach ($tracks as $t)
                      <option value="{{ $t->id }}">
                        <div class="row">
                            <div class="col-auto">
                                <img style="width: 50px; height: 50px;" src="{{ '/public/'.$t->img }}">
                            </div>
                            <div class="col-auto d-flex flex-column">
                                <p>{{ $t->title }}</p>
                            </div>
                        </div>
                    </option>
                      @endforeach

                    </select>
                    <div class="invalid-feedback d-block" v-for="error in errors.tracks">
                      @{{ error }}
                    </div>

                  </div>
                <div class="mb-3">
                  <label for="date_release" class="form-label">Дата релиза</label>
                  <input type="date" class="form-control" id="date_release" name="date_release" :class="errors.date_release?'is_invalid':''">
                  <div class="invalid-feedback d-block" v-for="error in errors.date_release">
                    @{{ error }}
                  </div>
                </div>


                <button type="submit" class="btn btn-primary">Сохранить</button>
              </form>
            </div>
      </div>
    </div>
  </div>



</div>
<script>
    const app = {
        data(){
            return{
                errors:[],
                message:'',
            }
        },
        methods:{
            async add_new_track(){

                let form = document.getElementById('new_track_form')
                let form_data = new FormData(form)
                const response_track = await fetch('{{ route('store_track') }}',{
                method:'post',
                headers:{
                    'X-CSRF-TOKEN':'{{ csrf_token() }}',
                },
                body: form_data
                })

                if(response_track.status == 200){
                    this.message = await response_track.json()
                    form.reset
                }
                if(response_track.status == 400){
                    this.errors = await response_track.json()
                }
            },
            async add_new_album(){
                let form = document.getElementById('new_album_form')
                let form_data = new FormData(form)
                const response_track = await fetch('{{ route('store_album') }}',{
                method:'post',
                headers:{
                    'X-CSRF-TOKEN':'{{ csrf_token() }}',
                },
                body: form_data
                })

                if(response_track.status == 200){
                    this.message = await response_track.json()
                    form.reset()
                }
                if(response_track.status == 400){
                    this.errors = await response_track.json()
                }
                },
        }
    }
    Vue.createApp(app).mount('#New')
</script>
<style>
  body{
        background-image: url({{url('public/bg/wave_top_bg.png')}});
        background-repeat: no-repeat;
        background-size: cover;
  }
</style>
@endsection
