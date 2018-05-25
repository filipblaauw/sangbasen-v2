<template>
    <form @submit.prevent="proceed" @keydown="songForm.errors.clear($event.target.name)">
      <div class="row">
        <div class="col-12 col-md-4">

          <div class="form-group">
            <label for="">{{trans('song.title')}}</label>
            <input class="form-control" type="text" v-model="songForm.title" name="title" :placeholder="trans('song.title')">
            <show-error :form-name="songForm" prop-name="title"></show-error>
          </div>

          <div class="form-group">
            <label for="">{{trans('song.artist')}}</label>
            <input class="form-control" type="text" v-model="songForm.artist" name="artist" :placeholder="trans('song.artist')">
            <show-error :form-name="songForm" prop-name="artist"></show-error>
          </div>

          <div class="form-group">
            <label for="">{{trans('song.genre')}}</label>
            <select class="form-control" v-model="songForm.genre_id">
              <option v-for="genre in genres" v-bind:value="genre.id">
                {{ genre.name }}
              </option>
            </select>
            <show-error :form-name="songForm" prop-name="genre_id"></show-error>
          </div>

          <div class="form-group">
            <label for="">{{trans('song.spotify')}}</label>
            <input class="form-control" type="spotify" v-model="songForm.spotify" name="spotify" :placeholder="trans('song.spotify')">
            <show-error :form-name="songForm" prop-name="spotify"></show-error>
          </div>

          <div class="form-group">
            <label for="">{{trans('song.tempo')}}</label>
            <div class="input-group">
              <input class="form-control" type="number" min="0" v-model="songForm.tempo" name="tempo" :placeholder="trans('song.tempo')">
              <div class="input-group-append">
                <div class="input-group-text" @click="tapTempo" style="cursor: pointer;">TAP</div>
              </div>
            </div>
            <div v-if="this.count > 1" class="small text-muted mt-2">
              Taps: {{t_tap}} <span class="float-right">{{trans('song.bpm_average')}} {{t_avg}} BPM</span>
            </div>

            <show-error :form-name="songForm" prop-name="tempo"></show-error>
          </div>

          <div class="form-group">
            <label for="customRange1">{{trans('song.duration')}}</label>
            <span class="badge badge-primary pull-right" style="font-size: 1rem; width: 60px;">{{songForm.duration | secondsToMinutes }}</span>
            <div class="input-group">

              <input type="range" class="custom-range" v-model="songForm.duration" min="0" max="600" name="duration">

            </div>
            <show-error :form-name="songForm" prop-name="duration"></show-error>
          </div>

          <div class="form-group">
				    <label for="">{{trans('song.time')}}</label>
						<select class="custom-select" v-model="songForm.time">
						  <option value="4/4" :selected="songForm.time == '4/4'">4/4 </option>
							<option value="3/4" :selected="songForm.time == '3/4'">3/4 </option>
							<option value="6/8" :selected="songForm.time == '6/8'">6/8 </option>
							<option value="2/2" :selected="songForm.time == '2/2'">2/2 </option>
							<option value="2/4" :selected="songForm.time == '2/4'">2/4 </option>
              <option value="9/8" :selected="songForm.time == '9/8'">9/8 </option>
              <option value="12/8" :selected="songForm.time == '12/8'">12/8 </option>
						</select>
						<show-error :form-name="songForm" prop-name="time"></show-error>
					</div>

          <div class="form-group">
				    <label for="">{{trans('song.key')}}</label>
						<select class="custom-select" v-model="songForm.key">
              <option value="A" :selected="songForm.time == 'A'">A </option>
              <option value="Bb" :selected="songForm.time == 'Bb'">Bb </option>
							<option value="B" :selected="songForm.time == 'B'">B </option>
							<option value="C" :selected="songForm.time == 'C'">C </option>
							<option value="Db" :selected="songForm.time == 'Db'">Db </option>
              <option value="D" :selected="songForm.time == 'D'">D </option>
              <option value="Eb" :selected="songForm.time == 'Eb'">Eb </option>
              <option value="E" :selected="songForm.time == 'E'">E </option>
              <option value="F" :selected="songForm.time == 'F'">F </option>
              <option value="Gb" :selected="songForm.time == 'Gb'">Gb </option>
              <option value="G" :selected="songForm.time == 'G'">G </option>
              <option value="Ab" :selected="songForm.time == 'Ab'">Ab </option>
						</select>
						<show-error :form-name="songForm" prop-name="key"></show-error>
					</div>

          <div class="form-group">
            <label for="">{{trans('song.author')}}</label>
            <input type="text" class="form-control" v-model="songForm.author">
            <show-error :form-name="songForm" prop-name="author"></show-error>
          </div>

          <div class="card text-center bg-light mb-1">
            <div class="card-body">
              <div v-if="!songForm.image" class="card-text">
                {{trans('song.upload_pdf')}}
              </div>
              <div v-if="songForm.image" class="card-text">
                {{trans('song.pdf_uploaded')}}
              </div>
              <label class="btn btn-primary">
                <span v-if="!songForm.image">{{trans('general.choose_image')}}</span>
                <span v-else>{{trans('song.replace_file')}}</span>
                <input id="file-upload" type="file" @change="uploadImage" hidden>
              </label>
              <div v-if="isImageUploading" class="progress" style="height: 20px;">
                <div id="progressbar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
          <div class="text-center" v-if="songForm.image">
            <img :src="scaledImage(songForm.image)">
          </div>
          <input type="text" class="form-control" v-model="songForm.image" hidden>


          <div class="card text-center bg-light mt-2">
            <div class="card-body">
              <div v-if="!songForm.playback" class="card-text">
                {{trans('song.upload_playback')}}
                <p class="small">{{trans('song.playback_maxsize')}}</p>
              </div>
              <div v-if="songForm.playback" class="card-text">
                {{trans('song.playback_uploaded')}}
                  <audio controls style="width:100%;">
                    <source :src="songForm.playback">
                  </audio>
              </div>
              <label class="btn btn-primary">
                <span v-if="!songForm.playback">{{trans('general.choose_image')}}</span>
                <span v-else>{{trans('song.replace_file')}}</span>
                <input id="file-upload" type="file" @change="uploadAudio" hidden>
              </label>
              <pulse-loader :loading="isPlaybackUploading"></pulse-loader>
            </div>

          </div>
          <input type="text" class="form-control" v-model="songForm.playback" hidden>
        </div>

        <div class="col-12 col-md-8">
          <div class="form-group">
            <button v-if="chords" type="button" class="btn btn-sm btn-secondary float-right" data-toggle="modal" data-target="#exampleModal">
              {{trans('song.preview')}}
            </button>
            <label for="">{{trans('song.chords')}}</label>
            <textarea class="form-control" v-model="songForm.chords" rows="40" name="chords" :placeholder="this.placeholder" style="resize: vertical;" @input="updateChords"></textarea>
            <show-error :form-name="songForm" prop-name="chords"></show-error>
          </div>
          <div class="form-group">
            <label for="">{{trans('song.com')}}</label>
            <textarea class="form-control" v-model="songForm.comments" rows="5" name="comments" :placeholder="trans('song.com_placeholder')" style="resize: vertical;"></textarea>
            <show-error :form-name="songForm" prop-name="comments"></show-error>
          </div>
        </div>

      </div>

      <button type="submit" class="btn btn-info waves-effect waves-light pull-right">
        <span v-if="slug">{{trans('general.update')}}</span>
        <span v-else>{{trans('general.save')}}</span>
      </button>
      <router-link to="/song" class="btn btn-danger waves-effect waves-light pull-right m-r-10" v-show="slug">{{trans('general.cancel')}}</router-link>

      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">{{trans('song.preview_song')}}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="chordsheet mt-4" v-if="chords" v-html="chords" id="chords"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('general.close')}}</button>
            </div>
          </div>
        </div>
      </div>
    </form>
</template>


<script>
  import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
  import ChordSheetJS from 'chordsheetjs'
  import Chord from 'chordjs'
  export default {
    components: {
      PulseLoader
    },
    data() {
      return {
        isImageUploading: false,
        isPlaybackUploading: false,
        placeholder: 'Eks:\n\nVerse 1:\nAm[G]azing Gr[G7]ace, how [C]sweet the so[G]und,\nThat s[G]aved a wr[G7]etch like [D]me.',
        genres: [],
        songForm: new Form({
          title: '',
          artist: '',
          genre_id: '',
          spotify: '',
          playback: '',
          image: '',
          key: '',
          time: '',
          tempo: '',
          duration: 0,
          author: '',
          chords: '',
          comments: '',
        }),
        chords: '',
        count: 0,
        msecsFirst: 0,
        msecsPrevious: 0,
        t_tap: 0,
        t_whole: 0,
        t_avg: 0
      };
    },
    props: ['slug'],
    mounted() {
      this.getGenres();
      if(this.slug) {
        this.getSong();
      }
      this.songForm.author = this.getAuthUser('full_name')
    },
    methods: {
      proceed(){
        if(this.slug)
            this.updateSong();
        else
            this.storeSong();
      },
      updateChords(value) {
        this.chords = this.songForm.chords
        const chordSheet = this.chords
        const parser = new ChordSheetJS.ChordProParser()
        const song = parser.parse(chordSheet)
        const akkorder = song.lines
        const formatter = new ChordSheetJS.HtmlTableFormatter()
        const disp = formatter.format(song)
        this.chords = disp
      },
      getAuthUser(name){
          return helper.getAuthUser(name);
      },
      tapTempo() {
        var timeSeconds = new Date;
        var msecs = timeSeconds.getTime();

        if ((msecs - this.msecsPrevious) > 1000 * 2) {
          this.count = 0
        }
        if (this.count == 0) {
          this.t_avg = "First Beat"
          this.t_tap = "First Beat"
          this.msecsFirst = msecs
          this.count = 1
        } else {
          var bpmAvg = 60000 * this.count / (msecs - this.msecsFirst)
          this.t_avg = Math.round(bpmAvg * 100) / 100;
          this.t_whole = Math.round(bpmAvg)
          this.songForm.tempo = Math.round(bpmAvg)
          this.count++
          this.t_tap = this.count
        }
        this.msecsPrevious = msecs
        return true;
      },
      getGenres(){
        axios.get('/api/genre/all')
            .then(response => response.data)
            .then(response => this.genres = response)
            .catch(error => {
                helper.showDataErrorMsg(error);
            });
      },
      scaledImage(value) {
				var img = value
        if (img.indexOf('amazonaws') !== -1) {
          return 'https://i.pinimg.com/originals/20/59/4e/20594ec17be5f2b66bbda9f3d2087da9.png'
        } else {
          var img = img.replace('/upload/', '/upload/t_media_lib_thumb/')
          var img = img.replace('.pdf', '.jpg')
  				return img
        }

			},
      storeSong(){
        this.songForm.post('/api/song')
          .then(response => {
            toastr.success(response.message);
            this.$emit('completed');
            this.$router.push('/song');
          })
          .catch(error => {
            helper.showErrorMsg(error);
          });
      },
      getSong(){
        axios.get('/api/song/'+this.slug)
          .then(response => response.data)
          .then(response => {
            this.songForm.title = response.title
            this.songForm.artist = response.artist
            this.songForm.spotify = response.spotify
            this.songForm.genre_id = response.genre_id
            this.songForm.playback = response.playback
            this.songForm.image = response.image
            this.songForm.key = response.key
            this.songForm.time = response.time
            this.songForm.tempo = response.tempo
            this.songForm.duration = response.duration
            this.songForm.author = response.author
            this.songForm.chords = response.chords
            this.songForm.comments = response.comments
            this.chords = this.songForm.chords
            const chordSheet = this.chords
            const parser = new ChordSheetJS.ChordProParser()
            const song = parser.parse(chordSheet)
            const akkorder = song.lines
            const formatter = new ChordSheetJS.HtmlTableFormatter()
            const disp = formatter.format(song)
            this.chords = disp
          })
          .catch(error => {
            helper.showDataErrorMsg(error);
            this.$router.push('/song');
          });
      },
      updateSong(){
        this.songForm.patch('/api/song/'+this.slug)
          .then(response => {
            toastr.success(response.message);
            this.$router.push('/song/');
          })
          .catch(error => {
            helper.showErrorMsg(error);
          });
      },
      uploadImage(e) {
				var self = this;
				self.isImageUploading = true;
	      var file = e.target.files[0];

				var formData = new FormData();
				formData.append('file', file);
				formData.append('upload_preset', 'ei9qgh67');
				axios({
					url: 'https://api.cloudinary.com/v1_1/catchesimages/upload',
          transformRequest: [(data, headers) => {
              delete headers.common.Authorization
              return data
          }],
					method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          onUploadProgress: function(progressEvent) {
            var progress = Math.round((progressEvent.loaded * 100.0) / progressEvent.total);
            document.getElementById('progressbar').style.width = progress + "%";
          },
					data: formData
				}).then(function(res) {
					self.isImageUploading = false;
          console.log(res)
					self.songForm.image = res.data.secure_url;
          toastr.success('PDF lastet opp!');
				}).catch(function(err) {
					console.error(err)
				})
	    },
      uploadAudio(e) {
				var self = this;
        self.isPlaybackUploading = true;
	      var file = e.target.files[0];

				var data = new FormData();
				data.append('file', file);

        axios.post('/api/upload/audio',data)
          .then(response => response.data)
          .then(response => {
            self.isPlaybackUploading = false
            toastr.success('Playback lastet opp!')
            self.songForm.playback = response.file_url
          }).catch(error => {
            console.log(error.response)
            //toastr.error(error.response.data.errors.file[0]);
            self.isPlaybackUploading = false
          });
	    }
    }
  }
</script>
