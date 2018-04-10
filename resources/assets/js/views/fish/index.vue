<template>
  <div>
    <div class="row page-titles">
      <div class="col-6 align-self-center">
        <h3 class="text-themecolor m-b-0 m-t-0">{{trans('fish.fish')}}</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><router-link to="/home">{{trans('general.home')}}</router-link></li>
          <li class="breadcrumb-item active">{{trans('fish.fish')}}</li>
        </ol>
      </div>
      <div class="col-6 text-right align-self-center">
        <router-link to="/fish/add" class="btn btn-success">{{trans('fish.add_new_fish')}}</router-link>
      </div>
    </div>
    <div class="row">
      <div class="col-12">

        <div class="card">
          <div class="card-body">
            <button class="btn btn-info btn-sm pull-right" v-if="fishs.total && !showFilterPanel" @click="showFilterPanel = !showFilterPanel"><i class="fas fa-filter"></i> {{trans('general.filter')}}</button>
            <button class="btn btn-info btn-sm pull-right" v-if="showFilterPanel" @click="showFilterPanel = !showFilterPanel">{{trans('general.hide')}}</button>
            <h4 class="card-title">{{trans('fish.fish_list')}}</h4>
            <h6 class="card-subtitle" v-if="fishs">{{trans('general.total_result_found',{'count' : fishs.total})}}</h6>
            <h6 class="card-subtitle" v-else>{{trans('general.no_result_found')}}</h6>
            <div class="row">
              <div class="col-12 col-sm-8">
                <div class="form-group">
                  <input class="form-control" name="keyword" v-model="filterFishForm.keyword" :placeholder="trans('fish.keyword')">
                </div>
              </div>
              <div class="col-12 col-sm-4">
                <div class="form-group pt-2 text-right">
                  <switches v-model="filterFishForm.status" theme="bootstrap" color="success"></switches> {{trans('fish.show_released')}}
                </div>
              </div>
            </div>
            <div v-if="showFilterPanel">
              <h4 class="card-title">{{trans('general.filter')}}</h4>
              <div class="row">
                <div class="col-12 col-lg-6">
                  <date-range-picker :start-date.sync="filterFishForm.start_date" :end-date.sync="filterFishForm.end_date" :label="trans('general.date_between')"></date-range-picker>
                </div>
                <div class="col-6 col-lg-3">
                  <div class="form-group">
                    <label for="">{{trans('general.sort_by')}}</label>
                    <select name="order" class="form-control" v-model="filterFishForm.sort_by">
                      <option value="date">{{trans('fish.date')}}</option>
                      <option value="weight">{{trans('fish.weight')}}</option>
                      <option value="species">{{trans('fish.species')}}</option>
                      <option value="river">{{trans('fish.river')}}</option>
                    </select>
                  </div>
                </div>
                <div class="col-6 col-lg-3">
                  <div class="form-group">
                    <label for="">{{trans('general.order')}}</label>
                    <select name="order" class="form-control" v-model="filterFishForm.order">
                      <option value="asc">{{trans('general.ascending')}}</option>
                      <option value="desc">{{trans('general.descending')}}</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <module-info v-if="!fishs.total" module="fish" title="module_info_title" description="module_info_description" icon="check-circle">
              <div slot="btn">
                <router-link to="/fish/add/" class="btn btn-success btn-lg"><i class="fas fa-plus"></i> {{trans('fish.add_new_fish')}}</router-link>
              </div>
            </module-info>
          </div>
        </div>


      </div>
      <div v-for="fish in fishs.data" class="col-6 col-md-4 col-lg-3 mb-4">
        <div class="card text-center h-100">
          <router-link :to="`/fish/${fish.uuid}`">
            <div v-if="fish.photo" class="card-image fish-image" v-bind:style="{ backgroundImage: 'url(' + thumbnail(fish.photo)  + ')' }"></div>
            <div v-if="!fish.photo" class="card-image fish-image" v-bind:style="{ backgroundImage: 'url(https://maps.googleapis.com/maps/api/staticmap?center=' + fish.lat + ','+fish.lng+'&zoom=11&scale=false&size=350x200&maptype=roadmap&format=png&visual_refresh=true&markers=icon:http://blaauw.me/wp-content/uploads/2018/03/mapicon.png%7Cshadow:false%%7Clabel:%7C'+fish.lat+','+fish.lng+'&key=AIzaSyBrtgJsdYVSp0lduOmrZWQQezgFTuYOAS8)' }"></div>
          </router-link>
          <div class="card-body p-2">
            <small class="text-muted">{{fish.date | formatDate }}</small>
            <h3 class="card-title"><span class="species-title" v-bind:class="{ 'released': fish.released == 1 }">{{fish.species}}</span></h3>
            <p class="card-text">
              {{fish.river}}<span v-if="fish.zone">, {{fish.zone}}</span>
              <br>
              {{fish.weight | toKg }}<span v-if="fish.length"> - {{fish.length }} cm</span>
            </p>
          </div>
          <div class="card-footer bg-white">
            <div class="btn-group">
              <button class="btn btn-outline-secondary btn-sm" v-tooltip="trans('fish.view_fish')" @click.prevent="viewFish(fish)"><i class="fas fa-arrow-right"></i></button>
              <button class="btn btn-outline-secondary btn-sm" v-tooltip="trans('fish.edit_fish')" @click.prevent="editFish(fish)"><i class="fas fa-edit"></i></button>
            </div>
          </div>
        </div>
      </div>

    </div>
    <pagination-record :page-length.sync="filterFishForm.page_length" :records="fishs" @updateRecords="getFishes"></pagination-record>
  </div>
</template>

<script>
  import fishForm from './form'
  import switches from 'vue-switches'
  import datepicker from 'vuejs-datepicker'
  import dateRangePicker from '../../components/date-range-picker'
  import _ from 'lodash'

  export default {
    components : { fishForm,switches,datepicker,dateRangePicker },
    data() {
      return {
        fishs: {
          total: 0,
          data: []
        },
        filterFishForm: {
          keyword: '',
          status: false,
          start_date: '',
          end_date: '',
          sort_by : 'date',
          order: 'desc',
          page_length: helper.getConfig('page_length')
        },
        showFilterPanel: false,
        showCreatePanel: true
      };
    },
    mounted(){
      if(!helper.hasPermission('access-fish')){
        helper.notAccessibleMsg();
        this.$router.push('/home');
      }

      if(!helper.featureAvailable('fish')){
        helper.featureNotAvailableMsg();
        this.$router.push('/home');
      }

      this.getFishes();
    },
    methods: {
      thumbnail(value) {
				var img = value
				var img = img.replace('/upload/', '/upload/h_200/')
				return img
			},
      hasPermission(permission){
          return helper.hasPermission(permission);
      },
      getFishes(page){
        if (typeof page !== 'number') {
          page = 1;
        }
        let url = helper.getFilterURL(this.filterFishForm);
        axios.get('/api/fish?page=' + page + url)
          .then(response => response.data)
          .then(response => this.fishs = response)
          .catch(error => {
              helper.showDataErrorMsg(error);
          });
      },
      getFilteredFishes: _.debounce(function (page){
        if (typeof page !== 'number') {
          page = 1;
        }
        let url = helper.getFilterURL(this.filterFishForm);
        axios.get('/api/fish?page=' + page + url)
          .then(response => response.data)
          .then(response => this.fishs = response)
          .catch(error => {
              helper.showDataErrorMsg(error);
          });
      },500),
      editFish(fish){
        this.$router.push('/fish/'+fish.uuid+'/edit');
      },
      viewFish(fish){
        this.$router.push('/fish/'+fish.uuid+'');
      },
      confirmDelete(fish){
          return dialog => this.deleteFish(fish);
      },
      deleteFish(fish){
        axios.delete('/api/fish/'+fish.uuid)
          .then(response => response.data)
          .then(response => {
              toastr.success(response.message);
              this.getFishes();
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
      filterFishForm: {
        handler(val){
          this.getFilteredFishes();
        },
        deep: true
      }
    }
  }
</script>
