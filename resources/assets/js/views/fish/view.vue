<template>
  <div>
      <div class="row page-titles">
        <div class="col-md-6 col-12 align-self-center">
          <h3 class="text-themecolor m-b-0 m-t-0">{{trans('fish.view_fish')}}</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><router-link to="/home">{{trans('general.home')}}</router-link></li>
            <li class="breadcrumb-item"><router-link to="/fish">{{trans('fish.fish')}}</router-link></li>
            <li class="breadcrumb-item active">{{trans('fish.view_fish')}}</li>
          </ol>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-lg-8">
          <div class="card">
            <img v-if="fish.photo" :src="scaledImage(fish.photo)" class="card-img-top">
            <div class="card-body">
              <h2 class="card-title text-center">{{fish.species}}</h2>

              <ul class="list-group list-group-flush">
    	          <li class="list-group-item">{{trans('fish.river')}}: <span class="pull-right">{{fish.river}}</span></li>
    	          <li v-if="fish.zone" class="list-group-item">{{trans('fish.zone')}}: <span class="pull-right">{{fish.zone}}</span></li>
    	          <li class="list-group-item">{{trans('fish.weight')}}: <span class="pull-right">{{fish.weight | toKg }}</span></li>
    	          <li v-if="fish.length" class="list-group-item">Lengde: <span class="pull-right">{{fish.length}} cm</span></li>
    	          <li v-if="fish.bait" class="list-group-item">{{trans('fish.bait')}}: <span class="pull-right">{{fish.bait}}</span></li>
    						<li v-if="fish.line" class="list-group-item">Flueline: <span class="pull-right">{{fish.line}}</span></li>
    						<li class="list-group-item">{{trans('fish.waterlevel')}}: <span class="pull-right">{{fish.waterLevel | waterLevel }}</span></li>
    						<li v-if="fish.waterTemp" class="list-group-item">{{trans('fish.temperature')}}: <span class="pull-right">{{fish.waterTemp}} &deg;C</span></li>
    						<li class="list-group-item">{{trans('fish.lice')}}: <span class="pull-right">{{fish.lice | lice }}</span></li>
    						<li class="list-group-item">{{trans('fish.sex')}}: <span class="pull-right">{{fish.sex | sex }}</span></li>
    						<li class="list-group-item">{{trans('fish.released')}}: <span class="pull-right">{{fish.released | released }}</span></li>
    	        </ul>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-4">
          <div class="card text-center">
            <gmap-map
              :center="center"
              :zoom="12"
              class="mapcard card-img-top"
              >
              <gmap-marker
                :position="center"
                :icon="'/images/mapicon.png'"
              ></gmap-marker>
            </gmap-map>
            <div class="card-body">
              <h4 class="card-title">{{trans('fish.description')}}</h4>
              <p v-if="fish.description" class="card-text">{{fish.description}}</p>
              <small class="text-muted">{{trans('fish.created')}}: {{fish.created_at | formatDateTime }}</small><br>
              <small class="text-muted">{{trans('fish.edited')}}: {{fish.updated_at | formatDateTime }}</small><br>
            </div>
          </div>
          <div class="btn-group d-flex justify-content-center">
            <button class="btn btn-info" v-tooltip="trans('fish.edit_fish')" @click.prevent="editFish(fish)"><i class="fas fa-edit"></i> {{trans('fish.edit_fish')}}</button>
            <button class="btn btn-danger" :key="fish.id" v-confirm="{ok: confirmDelete(fish)}" v-tooltip="trans('fish.delete_fish')"><i class="fas fa-trash"></i> {{trans('fish.delete_fish')}}</button>
          </div>
        </div>

      </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      fish: {},
      location: '',
      center: {
        lat: null,
        lng: null
      }
    }
  },

  created() {
    this.center = { lat: 0, lng: 0 }
    this.getFish()
  },
  methods: {
    getFish() {
      axios.get(`/api/fish/${this.$route.params.uuid}`)
        .then((res) => {
          this.fish = res.data
          this.center.lat = parseFloat(res.data.lat)
          this.center.lng = parseFloat(res.data.lng)
        })
        .catch(error => {
          helper.showDataErrorMsg(error);
          this.$router.push('/fish');
        });
    },
    scaledImage(value) {
      var img = value
      var img = img.replace('/upload/', '/upload/c_scale,w_750/')
      return img
    },
    editFish(fish){
      this.$router.push('/fish/'+fish.uuid+'/edit');
    },
    confirmDelete(fish){
      return dialog => this.deleteFish(fish);
    },
    deleteFish(fish){
      axios.delete('/api/fish/'+fish.uuid)
        .then(response => response.data)
        .then(response => {
            toastr.success(response.message);
            this.$router.push('/fish');
        }).catch(error => {
            helper.showDataErrorMsg(error);
        });
    }
  },
  filters: {
    waterLevel: function (value) {
      if (value == 1) {
        return i18n.fish.waterlevel_low
      } if (value == 2) {
        return i18n.fish.waterlevel_sinking
      } if (value == 3) {
        return i18n.fish.waterlevel_normal
      } if (value == 4) {
        return i18n.fish.waterlevel_rising
      } if (value == 5) {
        return i18n.fish.waterlevel_high
      } else {
        return i18n.fish.waterlevel_flood
      }
    },
    lice: function (value) {
      if (value == 1) {
        return i18n.fish.lice_none
      } if (value == 2) {
        return i18n.fish.lice_few
      } if (value == 3) {
        return i18n.fish.lice_moderate
      } else {
        return i18n.fish.lice_many
      }
    },
    sex: function (value) {
      if (value == 1) {
        return i18n.fish.sex_male
      } if (value == 2) {
        return i18n.fish.sex_female
      } else {
        return i18n.fish.unknown
      }
    },
    released: function (value) {
      if (value == 1) {
        return i18n.fish.released
      } else {
        return i18n.fish.notreleased
      }
    }
  }

}
</script>
