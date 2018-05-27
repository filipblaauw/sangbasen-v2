<template>
  <div>
    <div class="row page-titles">
      <div class="col-6 align-self-center">
        <h3 class="text-themecolor m-b-0 m-t-0">{{trans('song.song')}}</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><router-link to="/home">{{trans('general.home')}}</router-link></li>
          <li class="breadcrumb-item" :class="{ active: !filterSongForm.keyword }"><router-link to="/song">{{trans('song.song')}}</router-link></li>
          <li v-if="filterSongForm.keyword" class ="breadcrumb-item active">{{filterSongForm.keyword}}</li>
          <li v-if="currentGenre" class ="breadcrumb-item active">{{currentGenre}}</li>
        </ol>
      </div>
      <div class="col-6 text-right align-self-center">
        <router-link to="/song/add" class="btn btn-success">{{trans('song.add_new_song')}}</router-link>
      </div>
    </div>
    <div class="row">
      <div class="col-12">

        <div class="card">
          <div class="card-body">
            <button class="btn btn-info btn-sm pull-right" v-if="songs.total && !showFilterPanel" @click="showFilterPanel = !showFilterPanel"><i class="fas fa-filter"></i> {{trans('general.filter')}}</button>
            <button class="btn btn-info btn-sm pull-right" v-if="showFilterPanel" @click="showFilterPanel = !showFilterPanel">{{trans('general.hide')}}</button>
            <h4 class="card-title">{{trans('song.song_list')}}</h4>
            <h6 class="card-subtitle" v-if="songs">{{trans('general.total_result_found',{'count' : songs.total})}}</h6>
            <h6 class="card-subtitle" v-else>{{trans('general.no_result_found')}}</h6>
            <div class="row">

              <div class="col-12">
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{this.currentGenre}}</button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="#" @click="resetGenre">Alle</a>
                      <a class="dropdown-item" href="#" v-for="genre in genres" @click="selectedGenre(genre)">{{genre.name}}</a>
                    </div>
                  </div>
                  <input type="text" class="form-control" name="keyword" v-model="filterSongForm.keyword" :placeholder="trans('song.keyword')">

                  <span class="input-group-append" v-if="this.filterSongForm.keyword || this.currentGenre != 'Alle'">
                    <div class="input-group-text bg-transparent clickable" @click="reset">
                      <i class="fas fa-times"></i>
                    </div>
                  </span>
                </div>
                <!--<div class="form-group">
                  <input class="form-control" name="keyword" v-model="filterSongForm.keyword" :placeholder="trans('song.keyword')">
                </div>-->
              </div>
              <!--
              <div class="col-12 col-sm-4">
                <div class="form-group text-right">
                  <input class="form-control" name="keyword" v-model="filterSongForm.keyword" :placeholder="trans('song.genre')">
                </div>
              </div>-->
            </div>
            <div v-if="showFilterPanel">
              <h4 class="card-title">{{trans('general.filter')}}</h4>
              <div class="row">
                <div class="col-12 col-lg-6">
                  <date-range-picker :start-date.sync="filterSongForm.start_date" :end-date.sync="filterSongForm.end_date" :label="trans('general.date_between')"></date-range-picker>
                </div>
                <div class="col-6 col-lg-3">
                  <div class="form-group">
                    <label for="">{{trans('general.sort_by')}}</label>
                    <select name="order" class="form-control" v-model="filterSongForm.sort_by">
                      <option value="title">{{trans('song.title')}}</option>
                      <option value="artist">{{trans('song.artist')}}</option>
                      <option value="genre_id">{{trans('song.genre')}}</option>
                      <option value="updated_at">{{trans('song.created')}}</option>
                      <option value="updated_at">{{trans('song.updated_at')}}</option>
                    </select>
                  </div>
                </div>
                <div class="col-6 col-lg-3">
                  <div class="form-group">
                    <label for="">{{trans('general.order')}}</label>
                    <select name="order" class="form-control" v-model="filterSongForm.order">
                      <option value="asc">{{trans('general.ascending')}}</option>
                      <option value="desc">{{trans('general.descending')}}</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <module-info v-if="!songs.total" module="song" title="module_info_title" description="module_info_description" icon="check-circle">
              <div slot="btn">
                <router-link to="/song/add/" class="btn btn-success btn-lg"><i class="fas fa-plus"></i> {{trans('song.add_new_song')}}</router-link>
              </div>
            </module-info>
          </div>
        </div>

      </div>

    </div>
    <div v-if="songs.total" class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table" id="results">
                <thead>
                  <th>
                    <span class="clickable" @click="sortBy('title')">{{trans('song.title')}}</span>
                    <span class="clickable pull-right" v-if="this.filterSongForm.sort_by === 'title' && this.filterSongForm.order === 'desc'" @click="sortOrder('asc')"><i class="fas fa-caret-down"></i></span>
                    <span class="clickable pull-right" v-if="this.filterSongForm.sort_by === 'title' && this.filterSongForm.order === 'asc'" @click="sortOrder('desc')"><i class="fas fa-caret-up"></i></span>
                  </th>
                  <th>
                    <span class="clickable" @click="sortBy('artist')">{{trans('song.artist')}}</span>
                    <span class="clickable pull-right" v-if="this.filterSongForm.sort_by === 'artist' && this.filterSongForm.order === 'desc'" @click="sortOrder('asc')"><i class="fas fa-caret-down"></i></span>
                    <span class="clickable pull-right" v-if="this.filterSongForm.sort_by === 'artist' && this.filterSongForm.order === 'asc'" @click="sortOrder('desc')"><i class="fas fa-caret-up"></i></span>
                  </th>
                  <th class="d-none d-sm-table-cell">
                    <span class="clickable" @click="sortBy('genre_id')">{{trans('song.genre')}}</span>
                    <span class="clickable pull-right" v-if="this.filterSongForm.sort_by === 'genre_id' && this.filterSongForm.order === 'desc'" @click="sortOrder('asc')"><i class="fas fa-caret-down"></i></span>
                    <span class="clickable pull-right" v-if="this.filterSongForm.sort_by === 'genre_id' && this.filterSongForm.order === 'asc'" @click="sortOrder('desc')"><i class="fas fa-caret-up"></i></span>
                  </th>
                  <th class="text-center d-none d-sm-table-cell">
                    <span class="clickable" @click="sortBy('updated_at')">{{trans('song.updated_at')}}</span>
                    <span class="clickable pull-right" v-if="this.filterSongForm.sort_by === 'updated_at' && this.filterSongForm.order === 'desc'" @click="sortOrder('asc')"><i class="fas fa-caret-down"></i></span>
                    <span class="clickable pull-right" v-if="this.filterSongForm.sort_by === 'updated_at' && this.filterSongForm.order === 'asc'" @click="sortOrder('desc')"><i class="fas fa-caret-up"></i></span>
                  </th>
                  <th></th>
                </thead>
                <tbody>
                  <tr v-for="song in songs.data">
                    <td>
                      <strong>
                        <a :href="'/song/'+song.slug">{{song.title}}</a>
                      </strong>
                    </td>
                    <td>
                      <a href="#" @click="selectedArtist(song.artist)">{{song.artist}}</a>
                    </td>
                    <td class="d-none d-sm-table-cell">
                      <a href="#" @click="selectedGenre(song.genre)">{{song.genre.name}}</a>
                    </td>
                    <td class="text-center d-none d-sm-table-cell">{{song.updated_at | formatShortDateTime }}</td>
                    <td class="text-right nowrap">
                      <i v-if="song.spotify" class="fab fa-spotify text-success pl-1"></i>
                      <i v-if="song.playback" class="fas fa-music pl-1"></i>
                      <i v-if="song.chords" class="far fa-file-alt pl-1"></i>
                      <i v-if="song.image" class="far fa-file-pdf pl-1"></i>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="text-center small text-muted pb-4">
          <i class="fab fa-spotify text-success pl-1"></i> Spotify
          <i class="fas fa-music pl-1"></i> Playback
          <i class="far fa-file-alt pl-1"></i> Chordpro
          <i class="far fa-file-pdf pl-1"></i> PDF
        </div>
      </div>
    </div>

    <pagination-record :page-length.sync="filterSongForm.page_length" :records="songs" @updateRecords="getSongs"></pagination-record>
  </div>
</template>

<script>
  import songForm from './form'
  import switches from 'vue-switches'
  import datepicker from 'vuejs-datepicker'
  import dateRangePicker from '../../components/date-range-picker'
  import _ from 'lodash'

  export default {
    components : { songForm,switches,datepicker,dateRangePicker },
    data() {
      return {
        genres: [],
        currentGenre: 'Alle',
        songs: {
          total: 0,
          data: []
        },
        filterSongForm: {
          keyword: '',
          genre: null,
          sort_by : 'updated_at',
          order: 'desc',
          page_length: helper.getConfig('page_length')
        },
        showFilterPanel: false,
        showCreatePanel: true,
        isOpen: false
      };
    },
    mounted(){
      if(!helper.hasPermission('access-song')){
        console.log('not access')
        helper.notAccessibleMsg();
        this.$router.push('/home');
      }

      if(!helper.featureAvailable('song')){
        console.log('feature not available')
        helper.featureNotAvailableMsg();
        this.$router.push('/home');
      }

      if (this.getQueryVariable('search')) {
        var searchQuery = this.getQueryVariable('search')
        var searchQuery = decodeURI(searchQuery)
        this.filterSongForm.keyword = searchQuery
      }
      if (this.getQueryVariable('genre')) {
        var searchGenre = this.getQueryVariable('genre')
        this.filterSongForm.genre = searchGenre
      }

      this.getSongs();
      this.getGenres();

    },
    methods: {
      sortBy(value) {
        this.filterSongForm.sort_by = value
      },
      sortOrder(value) {
        this.filterSongForm.order = this.filterSongForm.order
        this.filterSongForm.order = value
      },
      getQueryVariable(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i=0;i<vars.length;i++) {
                var pair = vars[i].split("=");
                if(pair[0] == variable){return pair[1];}
        }
        return(false);
      },
      hasPermission(permission){
          return helper.hasPermission(permission);
      },
      getGenres(){
        var vm = this
        axios.get('/api/genre/all')
            .then(response => response.data)
            .then(response => {
              this.genres = response

              // iterate over genres, and set current genre if genre search parameter is set
              if (this.filterSongForm.genre) {
                function findObjectByKey(array, key, value) {
                  for (var i = 0; i < array.length; i++) {
                    if (array[i][key] === value) {
                      return array[i];
                    }
                  }
                  return null;
              }

              var genre = findObjectByKey(this.genres, 'id', parseInt(this.filterSongForm.genre))
              this.currentGenre = genre.name
              }
            })
            .catch(error => {
                helper.showDataErrorMsg(error);
            });

      },

      getSongs(page){
        if (typeof page !== 'number') {
          page = 1;
        }
        let url = helper.getFilterURL(this.filterSongForm);
        axios.get('/api/song?page=' + page + url)
          .then(response => response.data)
          .then(response => this.songs = response)
          .catch(error => {
              helper.showDataErrorMsg(error);
          });
      },
      getFilteredSongs: _.debounce(function (page){
        if (typeof page !== 'number') {
          page = 1;
        }
        let url = helper.getFilterURL(this.filterSongForm);
        axios.get('/api/song?page=' + page + url)
          .then(response => response.data)
          .then(response => this.songs = response)
          .catch(error => {
              helper.showDataErrorMsg(error);
          });
      },500),
      reset() {
        this.filterSongForm.keyword = null
        this.currentGenre = 'Alle'
      },
      selectedGenre(genre) {
        this.filterSongForm.keyword = null
        this.filterSongForm.genre = genre.id
        this.currentGenre = genre.name
      },
      selectedArtist(artist) {
        this.filterSongForm.keyword = artist
      },
      resetGenre() {
        this.filterSongForm.genre = null
        this.currentGenre = 'Alle'
      },
      editSong(song){
        this.$router.push('/song/'+song.slug+'/edit');
      },
      viewSong(song){
        this.$router.push('/song/'+song.slug);
      },
      confirmDelete(song){
          return dialog => this.deleteSong(song);
      },
      deleteSong(song){
        axios.delete('/api/song/'+song.slug)
          .then(response => response.data)
          .then(response => {
              toastr.success(response.message);
              this.getSongs();
          }).catch(error => {
              helper.showDataErrorMsg(error);
          });
      }
    },
    filters: {
      moment(date) {
        return helper.formatDate(date);
      }
    },
    watch: {
      filterSongForm: {
        handler(val){
          this.getFilteredSongs();
        },
        deep: true
      }
    }
  }
</script>
