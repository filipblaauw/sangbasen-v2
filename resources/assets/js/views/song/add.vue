<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-6 col-12 align-self-center">
        <h3 class="text-themecolor m-b-0 m-t-0">{{trans('song.add_new_song')}}</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><router-link to="/home">{{trans('general.home')}}</router-link></li>
          <li class="breadcrumb-item"><router-link to="/song">{{trans('song.song')}}</router-link></li>
          <li class="breadcrumb-item active">{{trans('song.add_new_song')}}</li>
        </ol>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">{{trans('song.add_new_song')}}</h4>
            <song-form></song-form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import songForm from './form';

  export default {
    components : { songForm },
    data() {
      return {
        slug:this.$route.params.slug
      }
    },
    mounted(){
      if(!helper.hasPermission('access-song')){
        helper.notAccessibleMsg();
        this.$router.push('/home');
      }

      if(!helper.featureAvailable('song')){
        helper.featureNotAvailableMsg();
        this.$router.push('/home');
      }
    }
  }
</script>
