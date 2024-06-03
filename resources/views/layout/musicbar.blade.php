<nav class="musicbar p-3" id="MusicBar">
    <div class="container d-flex flex-column">
        <div class="row mb-3" style="position: relative">
        <div id="progressBarContainer" style="width:100%; height:10px; background-color: #f3f3f3; position: absolute; top: 0; border-radius:10px; padding: 0;">
                <div id="progressBar" style="height:100%; background-color: #532A87; width: 0%;"></div>
            </div>
        </div>


      <div class="row justify-content-start">
        <div class="col-auto" style="width: 70px;">
            <img class="img-fluid" :src="'/public/'+current_track.img">
        </div>
        <div class="col-auto d-flex flex-column">
            <a :href="`{{ route('track') }}/${current_track.id}`" class="bold" style="color: white; margin:0;">@{{ current_track.title }}</a>
            <a style="color: white; margin:0;" href="#">@{{ current_track.user.nickname }}</a>
        </div>
        <div class="col-auto">
            <div class="row align-items-center">
                <div class="col-auto">
                    <button class="prev btn btn-link" @click="prev_to_page">
                        <img src="{{ asset('public\icons\prev.svg') }}">
                    </button>
                </div>
                <div class="col-auto">
                   <button @click="play(current_track)" class="btn btn-link" v-if="!isPlaying">
                        <img src="{{ asset('public\icons\play-circle.svg') }}">
                    </button>
                    <button @click="pause" class="btn btn-link" v-else>
                        <img src="{{ asset('public\icons\pause.svg') }}">
                    </button>
                </div>
                <div class="col-auto">
                    <button class="next btn btn-link" @click="next_to_page">
                        <img src="{{ asset('public\icons\next.svg') }}">
                    </button>
                </div>

            </div>

        </div>
    </div>
    </div>
  </nav>
<style>
  .musicbar{
      z-index:1;
      background-color: #505050;
      border-top-right-radius: 20px;
      border-top-left-radius: 20px;
      position: fixed;
      bottom: 0;
      width: 100%;
      height: 100px;
  }
</style>
<script>

  const bar = {
    data(){
      return{
        track:{},
        player: new Audio(),
        isPlaying:false,
        current_track:{},
        playlists:[],
        stopTime:0,

      }
    },
    methods:{
        loadCurrentSong() {

            // console.log('loadCurrentSong')
            current = localStorage.getItem('current_track');
            // console.log(current)
            if(current){
                // this.track = current
                this.current_track = JSON.parse(current)
                this.play(this.current_track)
            }

        },
        updateProgressBar() {
        const progressBar = document.getElementById('progressBar');
        const percentage = (this.player.currentTime / this.player.duration) * 100;
        progressBar.style.width = `${percentage}%`;
        },
        play(song){
            // console.log('play')
            if(typeof song.audio != undefined){
                // this.current = song
                this.player.src = '/public/'+this.current_track.audio
            }
            if (song.id == this.current_track.id) {
                this.player.currentTime = this.stopTime
            } else {
                this.current_track = song
                this.player.src = 'public' + this.current_track.audio
                this.player.currentTime = 0
            }
            // this.player.currentTime = this.stopTime
            // this.player.autoplay = true
            this.player.play()
            this.isPlaying = true
            this.track_to_page('play',this.stopTime)
            if(this.player.currentTime == this.player.duration){
                this.stop_playing()
            }
            // localStorage.setItem('current_track', JSON.stringify(this.current));
            // this.player.onended = this.stop_playing()
        },
        pause(){
            this.stopTime = this.player.currentTime
            this.player.pause()
            this.isPlaying = false
            this.track_to_page('pause',this.stopTime)
        },
        track_to_page(status,time){
            // let current = localStorage.getItem('current_track')
            // current = JSON.parse(current)
            let s = status
            let event = new CustomEvent('bar-update',{detail:{status:s,time:time}});
            window.dispatchEvent(event);
        },
        next_to_page(){
            this.next_track()
        },
        prev_to_page(){
            this.prev_track()
        },
        next_track(){
            let next = new CustomEvent('next-track');
            window.dispatchEvent(next);
        },
        prev_track(){
            let prev = new CustomEvent('prev-track');
            window.dispatchEvent(prev);
        },
        watch_track(){
        this.player.addEventListener('ended',()=>{
            this.stop_playing()
        })
        },
        async stop_playing(){
        // console.log(this.track)
        const response_track = await fetch('{{ route('add_track_listen') }}',{
        method:'post',
        headers:{
            'X-CSRF-TOKEN':'{{ csrf_token() }}',
            'Content-Type':'application/json'
        },
        body:JSON.stringify(this.current_track.id)
        })
        this.next_to_page()
        // this.pause()
        // this.stopTime = 0
        // if(response_track.status == 200){
        //     res = await response_track.json()
        //     this.track.listeners = res[0]

        // }
    },
    sendPageChangedEvent(){
        console.log('уходим')
        // if(this.player.src){
        //     console.log('if')
        //     this.stopTime = this.player.currentTime
        //     console.log(this.stopTime)
        // }
        // localStorage.setItem('stopTime',this.player.currentTime)
        window.dispatchEvent(new CustomEvent("page-changed",
        { detail: { path: window.location.pathname, stopTime:0} }));

    },
    saveData() {
        if(this.player.currentTime){
            localStorage.setItem('stopTime', JSON.stringify(this.player.currentTime));
        }

    }
    },
    created() {
    //   let current = localStorage.getItem('current_track')
    // console.log('created')

    if (localStorage.getItem('stopTime')) {
        // Извлекаем и парсим данные из локального хранилища
        const DataStopTime = JSON.parse(localStorage.getItem('stopTime'));
        console.log('DataStopTime musicbar '+DataStopTime);
        this.stopTime = DataStopTime
        console.log('this.loadCurrentSong()')
        this.loadCurrentSong()
        

    }
    window.addEventListener('custom-update', (el) => {
    // console.log(el.detail)
    this.stopTime = el.detail.time
    if(el.detail.status == 'play'){
        this.loadCurrentSong();
    }
    else{
        this.pause()
    }

    // console.log(this.track)
    // this.track = e.detail; // переданные данные
    });
    // -------------NEED---------
    this.sendPageChangedEvent()
    // window.dispatchEvent(new CustomEvent("page-changed",
    //     { detail: { path: window.location.pathname, stopTime:this.player.src} }));

  },
//   updated(){
//     console.log('updated')
//     this.loadCurrentSong();

//   },
  mounted(){
    window.addEventListener('beforeunload', this.saveData);

    // if (localStorage.getItem('stopTime')) {
    //     // Извлекаем и парсим данные из локального хранилища
    //     const DataStopTime = JSON.parse(localStorage.getItem('stopTime'));
    //     console.log('DataStopTime musicbar '+DataStopTime);
    //     this.stopTime = DataStopTime
    //     console.log('this.loadCurrentSong()')
    //     this.loadCurrentSong()
    // }
    // console.log('mounted')
    // this.loadCurrentSong();
    // let storage = localStorage.getItem('current_track')
    // if(storage){
    //     this.loadCurrentSong();
    // }
    // window.addEventListener('storage', (event) => {
    //     if (event.key === 'current_track') {
    //       this.loadCurrentSong();
    //     }
    // });
    this.watch_track()
    this.player.addEventListener('timeupdate', this.updateProgressBar);


  },
//   beforeUnmount() {
//     // Удаляем слушатель, если Vue приложение размонтируется
//     window.removeEventListener('beforeunload', this.saveData);
//   }

}

Vue.createApp(bar).mount('#MusicBar')


// , stopTime:bar.data.stopTime


// console.log(bar.config)
// bar.config.globalProperties.$watchEffect(() => {
//         return localStorage.getItem('current_track');
//     }, (newValue) => {
//         bar.data.current_track = newValue || 'Нет текущего трека';
//     });
</script>
