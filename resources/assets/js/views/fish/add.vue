<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-6 col-12 align-self-center">
        <h3 class="text-themecolor m-b-0 m-t-0">{{trans('fish.add_new_fish')}}</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><router-link to="/home">{{trans('general.home')}}</router-link></li>
          <li class="breadcrumb-item"><router-link to="/fish">{{trans('fish.fish')}}</router-link></li>
          <li class="breadcrumb-item active">{{trans('fish.add_new_fish')}}</li>
        </ol>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">{{trans('fish.add_new_fish')}}</h4>
            <fish-form></fish-form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import fishForm from './form';

  export default {
    components : { fishForm },
    data() {
      return {
        id:this.$route.params.id
      }
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
    }
  }
</script>
