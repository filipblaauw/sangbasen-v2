<template>
  <div>
    <div class="row page-titles d-print-none">
      <div class="col-md-6 col-12 align-self-center">
        <h3 class="text-themecolor m-b-0 m-t-0">{{trans('song.view_song')}}</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><router-link to="/home">{{trans('general.home')}}</router-link></li>
          <li class="breadcrumb-item"><router-link to="/song">{{trans('song.song')}}</router-link></li>
          <li class="breadcrumb-item active">{{trans('song.view_song')}}</li>
        </ol>
      </div>
    </div>

    <div class="row">
      <div class="col-12 col-lg-8">
        <div class="card">
          <div class="card-body" id="song">
            <div class="btn-group mb-4 d-print-none" v-show="chords">
              <button type="button" class="btn btn-sm" :class="{'btn-secondary': isOriginal, 'btn-outline-secondary': !isOriginal }" @click="resetChords">{{trans('song.original')}}</button>
              <button type="button" class="btn btn-sm" :class="{'btn-secondary': isTransposedDown, 'btn-outline-secondary': !isTransposedDown }" @click="transposeDown()">{{trans('song.down')}}</button>
              <button type="button" class="btn btn-sm" :class="{'btn-secondary': isTransposedUp, 'btn-outline-secondary': !isTransposedUp }" @click="transposeUp()">{{trans('song.up')}}</button>
              <button type="button" class="btn btn-sm" :class="{'btn-secondary': isFlat, 'btn-outline-secondary': !isFlat }" @click="flatKey()">&#9837;</button>
              <button type="button" class="btn btn-sm" :class="{'btn-secondary': isSharp, 'btn-outline-secondary': !isSharp }" @click="sharpKey()">&#9839;</button>
            </div>
            <div class="dropdown pull-right d-print-none" v-if="chords || song.playback || song.image">
              <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{trans('song.download')}}
              </button>
              <div class="dropdown-menu dropdown-menu-right">
                <a v-if="chords" class="dropdown-item" href="#" @click="downloadChords">ChordPro</a>
                <a v-if="chords" class="dropdown-item" :href="'onsong://ImportData/'+song.title+'.chordpro?'+encoded">{{trans('song.open_onsong')}}</a>
                <a v-if="song.playback" class="dropdown-item" :href="song.playback" download>{{trans('song.playback')}}</a>
                <a v-if="song.image" class="dropdown-item" :href="song.image" download>PDF</a>
                <a v-if="chords" class="dropdown-item" href="#" @click="generatePdf">{{trans('song.generate_pdf')}}</a>
                <a v-if="chords" class="dropdown-item" href="#" @click="downloadLyrics">{{trans('song.lyrics')}}</a>
                <a v-if="chords" class="dropdown-item" href="javascript:window.print();">{{trans('song.print')}}</a>
              </div>
            </div>
            <h2>{{song.title}}</h2>
            <h5>{{song.artist}}</h5>

            <div class="chordsheet mt-4" v-if="chords" v-html="chords" id="chords"></div>

            <div class="mt-4" v-if="song.image">
              <object :data="song.image" type="application/pdf" class="embedObject" internalinstanceid="5"></object>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-4 d-print-none">
        <div class="card">
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <li class="list-group-item text-center"><h4>{{song.title}}</h4></li>
              <li class="list-group-item">{{trans('song.artist')}}: <span class="float-right"><a :href="'/song/?search='+song.artist">{{song.artist}}</a></span></li>
              <li class="list-group-item">{{trans('song.genre')}}: <span class="float-right"><a :href="'/song/?genre='+song.genre.id">{{song.genre.name}}</a></span></li>
              <li class="list-group-item">{{trans('song.key')}}: <span class="float-right">{{song.key}}</span></li>
              <li class="list-group-item">{{trans('song.time')}}: <span class="float-right">{{song.time}}</span></li>
              <li class="list-group-item" v-if="song.tempo">{{trans('song.tempo')}}: <span class="float-right">{{song.tempo}} BPM</span></li>
              <li class="list-group-item" v-if="song.duration">{{trans('song.duration')}}: <span class="float-right">{{song.duration | secondsToMinutes }}</span></li>
            </ul>
            <iframe
              v-if="song.spotify"
              width="100%"
              height="80px"
              class="mt-4"
              :src="song.spotify"
              frameborder="0"
              allow="encrypted-media"
              allowtransparency="true">
              </iframe>
            <div class="small text-muted mt-4 text-center" v-if="song.playback">
              {{trans('song.playback')}}:
              <audio controls style="width:100%;">
                <source :src="song.playback">
              </audio>
            </div>
            <div class="small text-muted text-center mt-4">
              {{trans('song.created')}}: {{song.created_at | formatDate }} av {{song.user.email}}<br>
              {{trans('song.edited')}}: {{song.updated_at | formatDateTime }}
            </div>
          </div>
        </div>

        <div class="btn-group d-flex justify-content-center" v-if="this.currentUser === song.user_id">
          <button class="btn btn-info" v-tooltip="trans('song.edit_song')" @click.prevent="editSong(song)"><i class="fas fa-edit"></i> {{trans('song.edit_song')}}</button>
          <button class="btn btn-danger" :key="song.slug" v-confirm="{ok: confirmDelete(song)}" v-tooltip="trans('song.delete_song')"><i class="fas fa-trash"></i> {{trans('song.delete_song')}}</button>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import ChordSheetJS from 'chordsheetjs'
import Chord from 'chordjs'
import { Base64 } from 'js-base64'
import html2pdf from 'html2pdf.js'
var _ = require('lodash')

export default {
  data() {
    return {
      song: {
        genre: {
          name: ''
        },
        user: {
          email: ''
        },
        playback: ''
      },
      image: {},
      lyrics: '',
      chords: '',
      chordData: '',
      encoded: '',
      counter: 0,
      modifier: '',
      isTransposedDown: false,
      isTransposedUp: false,
      isFlat: false,
      isSharp: false,
      isOriginal: true,
      currentUser: ''
    }
  },

  created() {
    this.getSong()
  },
  mounted() {
    this.currentUser = helper.isAuthId()
  },
  methods: {
    prepareChordpro(fileName, mimeType) {
      var elHtml = '{title: '+this.song.title + '}\n' + '{artist: '+this.song.artist + '}\n' + '{key: '+this.song.key + '}\n' + '{tempo: '+this.song.tempo + '}\n' + '{duration: '+this.song.duration + '}\n' + '{time: '+this.song.time + '}\n\n' + this.song.chords;
      var link = document.createElement('a');
      mimeType = mimeType || 'text/plain';

      link.setAttribute('download', fileName);
      link.setAttribute('href', 'data:' + mimeType + ';charset=utf-8,' + encodeURIComponent(elHtml));
      link.click();
    },
    prepareLyrics(fileName, mimeType) {
      var elHtml = this.lyrics;
      var link = document.createElement('a');
      mimeType = mimeType || 'text/plain';

      link.setAttribute('download', fileName);
      link.setAttribute('href', 'data:' + mimeType + ';charset=utf-8,' + encodeURIComponent(elHtml));
      link.click();
    },
    generatePdf() {
      var element = document.getElementById('chords');
      html2pdf(element, {
        margin:       2,
        filename:     this.song.title + '.pdf',
        image:        { type: 'jpeg', quality: 1 },
        html2canvas:  { dpi: 192, letterRendering: true },
        jsPDF:        { unit: 'cm', format: 'a4', orientation: 'portrait' }
      });
    },
    downloadChords() {
      var fileName = this.song.title + '.chordpro';
      this.prepareChordpro(fileName, 'text/plain');
    },
    downloadLyrics() {
      var fileName = this.song.title + '.txt';
      this.prepareLyrics(fileName, 'text/plain');
    },
    getSong() {
      axios.get(`/api/song/${this.$route.params.slug}`)
        .then((res) => {
          this.song = res.data
          if (res.data.spotify) {
            this.song.spotify = 'https://open.spotify.com/embed?uri='+res.data.spotify
          }
          if (res.data.chords) {
            this.encoded = Base64.encode('{title: '+this.song.title + '}\n' + '{artist: '+this.song.artist + '}\n' + '{key: '+this.song.key + '}\n' + '{tempo: '+this.song.tempo + '}\n' + '{duration: '+this.song.duration + '}\n' + '{time: '+this.song.time + '}\n\n' + this.song.chords )
            this.chordData = res.data.chords
            const chordSheet = res.data.chords
            const parser = new ChordSheetJS.ChordProParser()
            const song = parser.parse(chordSheet)
            const akkorder = song.lines
            const formatter = new ChordSheetJS.HtmlTableFormatter()
            const disp = formatter.format(song)
            this.chords = disp
            const lyrics = res.data.chords.replace(/\[+([^\][]+)]+/g, "")
            var lyrics = lyrics.replace(/:/g,"")
            this.lyrics = lyrics
          }
          document.title = 'Sangbasen | ' + this.song.title
        })
        .catch(error => {
          helper.showDataErrorMsg(error);
          this.$router.push('/song');
        });

    },
    transposeDown() {
      this.isTransposedDown = true
      this.isTransposedUp = false
      this.isOriginal = false
      this.counter -= 1
      this.transpose()
    },
    transposeUp() {
      this.isTransposedDown = false
      this.isTransposedUp = true
      this.isOriginal = false
      this.counter += 1
      this.transpose()
    },
    flatKey() {
      this.isFlat = true
      this.isSharp = false
      this.modifier = 'b'
      this.transpose()
    },
    sharpKey() {
      this.isFlat = false
      this.isSharp = true
      this.modifier = '#'
      this.transpose()
    },
    transpose(){
      const chordSheet = this.chordData
      const increments = this.counter
      const modifier = this.modifier
      const parser = new ChordSheetJS.ChordProParser()
      const song = parser.parse(chordSheet)
      const akkorder = song.lines

      akkorder.forEach(function(entry, i) {
        entry.items.forEach(function(entry) {
          if (entry.chords != '') {
            var chord = Chord.parse(entry.chords),
                chord2 = chord.transpose(increments);
                chord2 = chord2.useModifier(modifier);
            entry.chords = chord2.toString();
          }
        })
      });

      const formatter = new ChordSheetJS.HtmlTableFormatter()
      const disp = formatter.format(song)
      this.chords = disp

    },
    resetChords() {
      this.isOriginal = true
      this.isTransposedDown = false
      this.isTransposedUp = false
      this.isFlat = false
      this.isSharp = false
      const chordSheet = this.chordData
      const parser = new ChordSheetJS.ChordProParser()
      const song = parser.parse(chordSheet)
      const akkorder = song.lines

      akkorder.forEach(function(entry, i) {
        entry.items.forEach(function(entry) {
          if (entry.chords != '') {
            var chord = Chord.parse(entry.chords),
                chord2 = chord.transpose(0);
            entry.chords = chord2.toString();
          }
        })
      });

      const formatter = new ChordSheetJS.HtmlTableFormatter()
      const disp = formatter.format(song)
      this.chords = disp
    },

    editSong(song){
      this.$router.push('/song/'+song.slug+'/edit');
    },
    confirmDelete(song){
      return dialog => this.deleteSong(song);
    },
    deleteSong(song){
      axios.delete('/api/song/'+song.slug)
        .then(response => response.data)
        .then(response => {
            toastr.success(response.message);
            this.$router.push('/song');
        }).catch(error => {
            helper.showDataErrorMsg(error);
        });
    }
  }

}
</script>
