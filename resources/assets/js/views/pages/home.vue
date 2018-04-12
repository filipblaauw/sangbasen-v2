<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-6 col-8 align-self-center">
        <h3 class="text-themecolor m-b-0 m-t-0">{{trans('general.home')}}</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">{{trans('general.home')}}</li>
        </ol>
      </div>
    </div>
    <div class="row" v-if="hasRole('admin')">
      <div class="col-lg-3 col-md-6">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">{{trans('dashboard.period_registered_user',{period: trans('dashboard.total')})}}</h4>
            <div class="text-right">
              <h2 class="font-light m-b-0"><i class="fas fa-users fa-lg pull-right"></i> <span class="pull-left">{{users}}</span></h2>
            </div>
          </div>
        </div>
      </div>
        <div class="col-lg-3 col-md-6">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">{{trans('dashboard.period_registered_user',{period: trans('dashboard.today')})}}</h4>
              <div class="text-right">
                <h2 class="font-light m-b-0"><i class="fas fa-users fa-lg pull-right"></i> <span class="pull-left">{{today_registered_users}}</span></h2>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">{{trans('dashboard.period_registered_user',{period: trans('dashboard.week')})}}</h4>
              <div class="text-right">
                <h2 class="font-light m-b-0"><i class="fas fa-users fa-lg pull-right"></i> <span class="pull-left">{{weekly_registered_users}}</span></h2>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">{{trans('dashboard.period_registered_user',{period: trans('dashboard.month')})}}</h4>
              <div class="text-right">
                <h2 class="font-light m-b-0"><i class="fas fa-users fa-lg pull-right"></i> <span class="pull-left">{{monthly_registered_users}}</span></h2>
              </div>
            </div>
          </div>
        </div>
    </div>

    <div class="row">
      <div class="col-12 mb-4">

            <gmap-map
              :center="center"
              :zoom="6"
              class="homeMap"
              ref="gmap"
              >
              <button type="button" v-if="zoomedIn" class="btn btn-dark btn-sm" @click="reset"><i class="fas fa-times"></i></button>
              <gmap-info-window
                :options="infoOptions"
                :position="infoWindowPos"
                :opened="infoWinOpen"
                @closeclick="infoWinOpen=false"
                >
                <strong>
                  {{infoContent}}
                </strong>
                <p>
                  {{infoSpecies}}
                  <div v-if="infoPhoto">
                    <img :src="thumbnail(infoPhoto)" alt="">
                  </div>
                </p>

                <a :href="infoLink">{{trans('fish.view_fish')}}</a>
              </gmap-info-window>
              <google-cluster @click="showReset">
                <gmap-marker
                  :key="index"
                  v-for="(m, index) in markers"
                  :position="m.latLng"
                  :clickable="true"
                  :icon="'/images/mapicon.png'"
                  @click="toggleInfoWindow(m,index)"
                ></gmap-marker>
            </google-cluster>
            </gmap-map>



      </div>
    </div>

    <div class="row">
      <div class="col-12 col-md-6">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">{{trans('fish.latest')}}</h4>
            <h6 class="card-subtitle" v-if="!fishs.length">{{trans('general.no_result_found')}}</h6>
            <div class="table-responsive" v-if="fishs.length">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>{{trans('fish.species')}}</th>
                    <th>{{trans('fish.river')}}</th>
                    <th class="table-option">{{trans('fish.date')}}</th>
                  </tr>
                </thead>
                  <tbody>
                    <tr v-for="fish in fishs" @click="viewFish(fish)" class="fish-table-list">
                      <td v-text="fish.species"></td>
                      <td v-text="fish.river"></td>
                      <td class="table-option">{{fish.date | moment}}</td>
                    </tr>
                  </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">{{trans('activity.activity_log')}}</h4>
            <h6 class="card-subtitle" v-if="!activity_logs.length">{{trans('general.no_result_found')}}</h6>
            <div class="table-responsive" v-if="activity_logs.length">
              <table class="table">
                <thead>
                  <tr>
                    <th v-if="hasRole('admin')">{{trans('user.user')}}</th>
                    <th>{{trans('activity.activity')}}</th>
                    <th class="table-option">{{trans('activity.date_time')}}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="activity_log in activity_logs">
                    <td v-if="hasRole('admin')" v-text="activity_log.user.profile.first_name+' '+activity_log.user.profile.last_name"></td>
                    <td v-if="activity_log.sub_module == null">{{trans('activity.'+activity_log.activity,{activity: trans(activity_log.module+'.'+activity_log.module)})}}</td>
                    <td v-else>{{trans('activity.'+activity_log.activity,{activity: trans(activity_log.module+'.'+activity_log.sub_module)})}}</td>
                    <td class="table-option">{{activity_log.created_at | momentDateTime }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
  export default {
    components: {},
    data() {
      return {
        users: 0,
        today_registered_users: 0,
        weekly_registered_users: 0,
        monthly_registered_users: 0,
        activity_logs: {},
        fishs: {},
        mappedfishs: {},
        location: '',
        center: {
          lat: 59.9,
          lng: 5.7
        },
        markers: [],
        infoContent: '',
        infoSpecies: '',
        infoPhoto: '',
        infoLink: '',
        infoWindowPos: {
          lat: 0,
          lng: 0
        },
        infoWinOpen: false,
        currentMidx: null,
        //optional: offset infowindow so it visually sits nicely on top of our marker
        infoOptions: {
          pixelOffset: {
            width: 0,
            height: -35
          }
        },
        zoomedIn: false
      }
    },
    mounted(){
      axios.get('/api/dashboard')
        .then(response => response.data)
        .then(response => {
          this.users = response.users;
          this.today_registered_users = response.today_registered_users;
          this.weekly_registered_users = response.weekly_registered_users;
          this.monthly_registered_users = response.monthly_registered_users;
          this.activity_logs = response.activity_logs;
          this.fishs = response.fishs;
          this.mappedfishs = response.mappedfishs;
          this.markers = response.mappedfishs.map(r => {
            return {
              latLng:
                {lat: parseFloat(r.lat), lng: parseFloat(r.lng)},
              infoText: moment(r.date).format('LL'),
              infoSpecies: r.species + ', ' + r.river,
              infoPhoto: r.photo,
              infoLink: '/fish/'+r.uuid
             }
          });
        })
        .catch(error => {
          helper.showDataErrorMsg(error);
        })


    },
    methods: {
      hasRole(role){
        return helper.hasRole(role);
      },
      viewFish(fish){
        this.$router.push('/fish/'+fish.uuid+'');
      },
      toggleInfoWindow: function(marker, idx) {
        this.infoWindowPos = marker.latLng;
        this.infoContent = marker.infoText;
        this.infoSpecies = marker.infoSpecies;
        this.infoPhoto = marker.infoPhoto;
        this.infoLink = marker.infoLink;
        //check if its the same marker that was selected if yes toggle
        if (this.currentMidx == idx) {
          this.infoWinOpen = !this.infoWinOpen;
        }
        //if different marker set infowindow to open and reset current marker index
        else {
          this.infoWinOpen = true;
          this.currentMidx = idx;
        }
      },
      thumbnail(value) {
				var img = value
				var img = img.replace('/upload/', '/upload/t_media_lib_thumb/')
				return img
			},
      showReset: function() {
        this.zoomedIn = true
      },
      reset: function() {
        var markers = this.markers
        const bounds = new google.maps.LatLngBounds()
        for (let m of markers) {
          bounds.extend(m.latLng)
        }
        this.$refs.gmap.$mapObject.fitBounds(bounds)
        this.zoomedIn = false
      }
    },
    watch: {
      // Fit map to markers
      markers(markers) {
        if (this.markers.length > 2) {
          const bounds = new google.maps.LatLngBounds()
          for (let m of markers) {
            bounds.extend(m.latLng)
          }
          this.$refs.gmap.$mapObject.fitBounds(bounds)
        }
      }
    },

    filters: {
      momentDateTime(date) {
        return helper.formatDateTime(date);
      },
      moment(date) {
        return helper.formatDate(date);
      }
    },
  }
</script>
