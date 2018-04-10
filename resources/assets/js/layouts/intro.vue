<template>
  <div>
    <div class="container">

        <div class="row">
          <div class="col-12 col-sm-4 text-center pb-4">
            <img src="/images/intro-map.png" class="intro-image">
            <h3 class="heading">{{trans('intro.log_your_catches')}}</h3>
            <p>{{trans('intro.log_your_catches_text')}}</p>
          </div>
          <div class="col-12 col-sm-4 text-center pb-4">
            <img src="/images/intro-images.png" class="intro-image">
            <h3 class="heading">{{trans('intro.upload_images')}}</h3>
            <p>{{trans('intro.upload_images_text')}}</p>
          </div>
          <div class="col-12 col-sm-4 text-center pb-4">
            <img src="/images/intro-fisherman.png" class="intro-image">
            <h3 class="heading">{{trans('intro.free')}}</h3>
            <p>{{trans('intro.free_text')}}</p>
          </div>
        </div>

    </div>
    <footer class="intro-footer bg-dark text-white pt-4">
      <div class="container d-flex flex-column justify-content-center align-items-center text-center">
        <p>{{getConfig('footer_credit')}} © {{ new Date().getFullYear() }}</p>
        <p class="small muted">{{trans('intro.disclaimer')}}</p>
        <p>
          <a href="/"><i class="fa fa-home fa-fw"></i></a>
          <a href="mailto:post@fiskelogg.no"><i class="fa fa-envelope fa-fw"></i></a>
        </p>
      </div>
    </footer>
  </div>

</template>

<script>
export default {

  methods: {
    getConfig(config){
      return helper.getConfig(config);
    }
  }
}
</script>

<style lang="css">
</style>
