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
      <div class="col-12 col-md-6">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">{{trans('song.your_latest')}}</h4>
            <h6 class="card-subtitle" v-if="!songs.length">{{trans('general.no_result_found')}}</h6>
            <div class="table-responsive" v-if="songs.length">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>{{trans('song.title')}}</th>
                    <th>{{trans('song.artist')}}</th>
                  </tr>
                </thead>
                  <tbody>
                    <tr v-for="song in songs" @click="viewSong(song)">
                      <td v-text="song.title"></td>
                      <td v-text="song.artist"></td>
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
        songs: {}
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
          this.songs = response.songs;
        })
        .catch(error => {
          helper.showDataErrorMsg(error);
        })


    },
    methods: {
      hasRole(role){
        return helper.hasRole(role);
      },
      viewSong(song){
        this.$router.push('/song/'+song.slug);
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
