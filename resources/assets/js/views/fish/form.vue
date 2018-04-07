<template>
    <form @submit.prevent="proceed" @keydown="fishForm.errors.clear($event.target.name)">
      <div class="row">
        <div class="col-12 col-md-6">
          <gmap-autocomplete
			    	class="form-control mb-2"
						:placeholder="trans('fish.searchplace')"
			      @place_changed="setPlace">
			    </gmap-autocomplete>
          <br>
			    <gmap-map
			      :center="center"
			      :zoom="10"
			      class="map mb-2"
			      :clickable="true"
			      @click="setMarker"
			      >
			      <gmap-marker
			        v-if="location"
			        :position="location"
			        :draggable="true",
			        @dragend="setMarker"
              :icon="'/images/mapicon.png'"
			      ></gmap-marker>
			    </gmap-map>
          <small class="text-muted">{{trans('fish.setmarker')}}</small>
          <input type="text" class="form-control" v-model="fishForm.lat" hidden>
          <input type="text" class="form-control" v-model="fishForm.lng" hidden>
					<show-error :form-name="fishForm" prop-name="lat"></show-error>

          <div class="form-group mt-2">
              <label for="">{{trans('fish.date')}}</label>
              <datepicker language="nb-no" :monday-first="true" v-model="fishForm.date" :bootstrapStyling="true" @selected="fishForm.errors.clear('date')" :placeholder="trans('fish.date')"></datepicker>
              <show-error :form-name="fishForm" prop-name="date"></show-error>
          </div>

          <div class="form-group">
				    <label for="">{{trans('fish.time')}}</label>
						<select class="custom-select" v-model="fishForm.time">
						  <option value="1" :selected="fishForm.time == 1">{{trans('fish.time_morning')}}</option>
							<option value="2" :selected="fishForm.time == 2">{{trans('fish.time_beforenoon')}}</option>
							<option value="3" :selected="fishForm.time == 3">{{trans('fish.time_afternoon')}}</option>
							<option value="4" :selected="fishForm.time == 4">{{trans('fish.time_evening')}}</option>
							<option value="5" :selected="fishForm.time == 5">{{trans('fish.time_night')}}</option>
						</select>
						<show-error :form-name="fishForm" prop-name="time"></show-error>
					</div>

          <div class="form-group">
            <label for="">{{trans('fish.species')}}</label>
            <species :suggestions="suggestions" v-model="fishForm.species"></species>
            <show-error :form-name="fishForm" prop-name="species"></show-error>
          </div>

          <div class="form-group">
            <label for="">{{trans('fish.river')}}</label>
            <input class="form-control" type="text" v-model="fishForm.river" name="river" :placeholder="trans('fish.river')">
            <show-error :form-name="fishForm" prop-name="river"></show-error>
          </div>

          <div class="form-group">
            <label for="">{{trans('fish.zone')}}</label>
            <input class="form-control" type="text" v-model="fishForm.zone" name="zone" :placeholder="trans('fish.zone')">
            <show-error :form-name="fishForm" prop-name="zone"></show-error>
          </div>

          <div class="form-group">
            <label for="">{{trans('fish.weight')}}</label>
            <div class="input-group">
              <input class="form-control" type="number" v-model="fishForm.weight" name="weight" :placeholder="trans('fish.weight')">
              <div class="input-group-append">
                <div class="input-group-text">gram</div>
              </div>
            </div>
            <show-error :form-name="fishForm" prop-name="weight"></show-error>
          </div>

          <div class="form-group">
            <label for="">{{trans('fish.length')}}</label>
            <div class="input-group">
              <input class="form-control" type="number" v-model="fishForm.length" name="length" :placeholder="trans('fish.length')">
              <div class="input-group-append">
                <div class="input-group-text">cm</div>
              </div>
            </div>
            <show-error :form-name="fishForm" prop-name="length"></show-error>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="card text-center bg-light">
            <div class="card-body">
              <h5 class="card-title">Bilde</h5>
              <p v-if="!fishForm.photo" class="card-text">Last opp et bilde av fangsten</p>
              <label class="btn btn-primary">
                <span v-if="!fishForm.photo">Last opp bilde</span>
                <span v-else>Erstatt gjeldende bilde</span>
                <input id="file-upload" type="file" @change="onFileChange" hidden>
              </label>
              <div v-if="isUploading" class="d-flex justify-content-center mt-3">
                <moon-loader :loading="isUploading" color="#007bff" size="40px"></moon-loader>
              </div>
            </div>
            <img v-if="fishForm.photo" :src="scaledImage(fishForm.photo)" class="card-img-bottom">
          </div>
          <input type="text" class="form-control" v-model="fishForm.photo" hidden>

          <div class="form-group">
            <label for="">{{trans('fish.bait')}}</label>
            <input class="form-control" type="text" v-model="fishForm.bait" name="bait" :placeholder="trans('fish.bait')">
            <show-error :form-name="fishForm" prop-name="bait"></show-error>
          </div>

          <div class="form-group">
            <label for="">{{trans('fish.line')}}</label>
            <input class="form-control" type="text" v-model="fishForm.line" name="line" :placeholder="trans('fish.line')">
            <show-error :form-name="fishForm" prop-name="line"></show-error>
          </div>

          <div class="form-group">
				    <label for="">{{trans('fish.temperature')}}</label>
						<select class="custom-select" v-model="fishForm.waterTemp">
						  <option value="1" :selected="fishForm.waterTemp == 1">0-5 {{trans('fish.degrees')}}</option>
							<option value="2" :selected="fishForm.waterTemp == 2">5-10 {{trans('fish.degrees')}}</option>
							<option value="3" :selected="fishForm.waterTemp == 3">10-15 {{trans('fish.degrees')}}</option>
							<option value="4" :selected="fishForm.waterTemp == 4">15-20 {{trans('fish.degrees')}}</option>
							<option value="5" :selected="fishForm.waterTemp == 5">20-25 {{trans('fish.degrees')}}</option>
						</select>
						<show-error :form-name="fishForm" prop-name="waterTemp"></show-error>
					</div>

          <div class="form-group">
				    <label for="">{{trans('fish.waterlevel')}}</label>
						<select class="custom-select" v-model="fishForm.waterLevel">
						  <option value="1" :selected="fishForm.waterLevel == 1">{{trans('fish.waterlevel_low')}}</option>
							<option value="2" :selected="fishForm.waterLevel == 2">{{trans('fish.waterlevel_sinking')}}</option>
							<option value="3" :selected="fishForm.waterLevel == 3">{{trans('fish.waterlevel_normal')}}</option>
							<option value="4" :selected="fishForm.waterLevel == 4">{{trans('fish.waterlevel_rising')}}</option>
							<option value="5" :selected="fishForm.waterLevel == 5">{{trans('fish.waterlevel_high')}}</option>
              <option value="6" :selected="fishForm.waterLevel == 6">{{trans('fish.waterlevel_flood')}}</option>
						</select>
						<show-error :form-name="fishForm" prop-name="waterLevel"></show-error>
					</div>

          <div class="form-row">
						<div class="col">
							<div class="form-group">
						    <label>{{trans('fish.sex')}}</label>
								<div class="custom-control custom-radio">
								  <input type="radio" id="sex1" name="sex" class="custom-control-input" value="1" v-model="fishForm.sex" :checked="fishForm.sex == 1">
								  <label class="custom-control-label" for="sex1">{{trans('fish.sex_male')}}</label>
								</div>
								<div class="custom-control custom-radio">
								  <input type="radio" id="sex2" name="sex" class="custom-control-input" value="2" v-model="fishForm.sex" :checked="fishForm.sex == 2">
								  <label class="custom-control-label" for="sex2">{{trans('fish.sex_female')}}</label>
								</div>
								<div class="custom-control custom-radio">
								  <input type="radio" id="sex3" name="sex" class="custom-control-input" value="3" v-model="fishForm.sex" :checked="fishForm.sex == 3">
								  <label class="custom-control-label" for="sex3">{{trans('fish.unknown')}}</label>
								</div>
                <show-error :form-name="fishForm" prop-name="sex"></show-error>
							</div>

							<div class="form-group">
						    <label>{{trans('fish.farmed')}}</label>
								<div class="custom-control custom-radio">
								  <input type="radio" id="wild1" name="wild" class="custom-control-input" value="1" v-model="fishForm.farmed" :checked="fishForm.farmed == 1">
								  <label class="custom-control-label" for="wild1">{{trans('fish.farmed_wild')}}</label>
								</div>
								<div class="custom-control custom-radio">
								  <input type="radio" id="wild2" name="wild" class="custom-control-input" value="2" v-model="fishForm.farmed" :checked="fishForm.farmed == 2">
								  <label class="custom-control-label" for="wild2">{{trans('fish.farmed_farmed')}}</label>
								</div>
								<div class="custom-control custom-radio">
								  <input type="radio" id="wild3" name="wild" class="custom-control-input" value="3" v-model="fishForm.farmed" :checked="fishForm.farmed == 3">
								  <label class="custom-control-label" for="wild3">{{trans('fish.unknown')}}</label>
								</div>
                <show-error :form-name="fishForm" prop-name="farmed"></show-error>
							</div>
						</div>

						<div class="col">
							<div class="form-group">
						    <label>{{trans('fish.lice')}}</label>
								<div class="custom-control custom-radio">
								  <input type="radio" id="lice1" name="lice" class="custom-control-input" value="1" v-model="fishForm.lice" :checked="fishForm.lice == 1">
								  <label class="custom-control-label" for="lice1">{{trans('fish.lice_none')}}</label>
								</div>
								<div class="custom-control custom-radio">
								  <input type="radio" id="lice2" name="lice" class="custom-control-input" value="2" v-model="fishForm.lice" :checked="fishForm.lice == 2">
								  <label class="custom-control-label" for="lice2">{{trans('fish.lice_few')}}</label>
								</div>
								<div class="custom-control custom-radio">
								  <input type="radio" id="lice3" name="lice" class="custom-control-input" value="3" v-model="fishForm.lice" :checked="fishForm.lice == 3">
								  <label class="custom-control-label" for="lice3">{{trans('fish.lice_moderate')}}</label>
								</div>
								<div class="custom-control custom-radio">
								  <input type="radio" id="lice4" name="lice" class="custom-control-input" value="4" v-model="fishForm.lice" :checked="fishForm.lice == 4">
								  <label class="custom-control-label" for="lice4">{{trans('fish.lice_many')}}</label>
								</div>
                <show-error :form-name="fishForm" prop-name="lice"></show-error>
							</div>

							<div class="form-group">
						    <label>{{trans('fish.released')}}</label>
								<div class="custom-control custom-radio">
								  <input type="radio" id="released1" name="released" class="custom-control-input" value="1" v-model="fishForm.released" :checked="fishForm.released == 1">
								  <label class="custom-control-label" for="released1">{{trans('fish.released')}}</label>
								</div>
								<div class="custom-control custom-radio">
								  <input type="radio" id="released2" name="released" class="custom-control-input" value="2" v-model="fishForm.released" :checked="fishForm.released == 2">
								  <label class="custom-control-label" for="released2">{{trans('fish.notreleased')}}</label>
								</div>
                <show-error :form-name="fishForm" prop-name="released"></show-error>
							</div>
						</div>
					</div>
        </div>

        <div class="col-12">
          <div class="form-group">
            <label for="">{{trans('fish.description')}}</label>
            <autosize-textarea v-model="fishForm.description" rows="2" name="description" :placeholder="trans('fish.description')"></autosize-textarea>
            <show-error :form-name="fishForm" prop-name="description"></show-error>
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-info waves-effect waves-light pull-right">
        <span v-if="uuid">{{trans('general.update')}}</span>
        <span v-else>{{trans('general.save')}}</span>
      </button>
      <router-link to="/fish" class="btn btn-danger waves-effect waves-light pull-right m-r-10" v-show="uuid">{{trans('general.cancel')}}</router-link>
    </form>
</template>


<script>
  import datepicker from 'vuejs-datepicker'
  import autosizeTextarea from '../../components/autosize-textarea'
  import MoonLoader from 'vue-spinner/src/MoonLoader.vue'
  import Species from '../../components/species'

  export default {
    components: {
      datepicker,
      autosizeTextarea,
      MoonLoader,
      Species
    },
    data() {
      return {
        suggestions: [
          { species: i18n.fish.species_salmon },
          { species: i18n.fish.species_trout },
          { species: i18n.fish.species_searuntrout },
          { species: i18n.fish.species_char },
          { species: i18n.fish.species_searunchar },
          { species: i18n.fish.species_grayling },
          { species: i18n.fish.species_pike },
          { species: i18n.fish.species_whitefish },
          { species: i18n.fish.species_perch },
          { species: i18n.fish.species_rainbowtrout },
          { species: i18n.fish.species_seabass },
          { species: i18n.fish.species_mackarell },
          { species: i18n.fish.species_coalfish },
          { species: i18n.fish.species_pollack },
          { species: i18n.fish.species_cod },
          { species: i18n.fish.species_zander },
          { species: i18n.fish.species_asp },
          { species: i18n.fish.species_brooktrout }
        ],
        isUploading: false,
        location: '',
	      // default to Montreal to keep it simple
	      // change this to whatever makes sense
	      center: { lat: 58.771028, lng: 5.8488836 },
	      markers: [],
	      currentPlace: null,
        fishForm: new Form({
          date: '',
          time: '',
          species: '',
          river: '',
          zone: '',
          lat: '',
          lng: '',
          weight: '',
          length: '',
          bait: '',
          line: '',
          waterTemp: '',
          waterLevel: '',
          sex: '',
          farmed: '',
          lice: '',
          released: '',
          photo: '',
          description: '',
        })
      };
    },
    props: ['uuid'],
    mounted() {
      this.center = { lat: 58.771028, lng: 5.8488836 }
		  this.location = ''
      if(this.uuid) {
        this.getFish();
      }
      else {
        this.geolocate();
      }
    },
    methods: {
      proceed(){
        if(this.uuid)
            this.updateFish();
        else
            this.storeFish();
      },
      setPlace(place) {
	      this.currentPlace = place;
	      const center = {
	        lat: this.currentPlace.geometry.location.lat(),
	        lng: this.currentPlace.geometry.location.lng()
	      };
	      this.center = center;
	    },
			setMarker(event) {
				this.location = {lat: event.latLng.lat(), lng: event.latLng.lng()}
				this.fishForm.lat = event.latLng.lat()
				this.fishForm.lng = event.latLng.lng()
			},
	    geolocate: function() {
	      navigator.geolocation.getCurrentPosition(position => {
	        this.center = {
	          lat: position.coords.latitude,
	          lng: position.coords.longitude
	        };
          console.log('geolocated')
	      });
	    },
      scaledImage(value) {
				var img = value
				var img = img.replace('/upload/', '/upload/c_scale,w_750/')
				return img
			},
      storeFish(){
        this.fishForm.date = moment(this.fishForm.date).format('YYYY-MM-DD');
        this.fishForm.post('/api/fish')
          .then(response => {
            toastr.success(response.message);
            this.$emit('completed');
            this.$router.push('/fish');
          })
          .catch(error => {
            helper.showErrorMsg(error);
          });
      },
      getFish(){
        axios.get('/api/fish/'+this.uuid)
          .then(response => response.data)
          .then(response => {
            this.fishForm.lat = response.lat;
            this.fishForm.lng = response.lng;
            this.fishForm.date = response.date;
            this.fishForm.time = response.time;
            this.fishForm.species = response.species;
            this.fishForm.river = response.river;
            this.fishForm.zone = response.zone;
            this.fishForm.weight = response.weight;
            this.fishForm.length = response.length;
            this.fishForm.bait = response.bait;
            this.fishForm.line = response.line;
            this.fishForm.waterTemp = response.waterTemp;
            this.fishForm.waterLevel = response.waterLevel;
            this.fishForm.sex = response.sex;
            this.fishForm.farmed = response.farmed;
            this.fishForm.lice = response.lice;
            this.fishForm.released = response.released;
            this.fishForm.photo = response.photo;
            this.fishForm.description = response.description;
            this.center.lat = parseFloat(response.lat);
						this.center.lng = parseFloat(response.lng);
						this.location = {lat: parseFloat(response.lat), lng: parseFloat(response.lng)};

          })
          .catch(error => {
            helper.showDataErrorMsg(error);
            this.$router.push('/fish');
          });
      },
      updateFish(){
        this.fishForm.date = moment(this.fishForm.date).format('YYYY-MM-DD');
        this.fishForm.patch('/api/fish/'+this.uuid)
          .then(response => {
            toastr.success(response.message);
            this.$router.push('/fish/'+this.uuid);
          })
          .catch(error => {
            helper.showErrorMsg(error);
          });
      },
      onFileChange(e) {
				var self = this;
				self.isUploading = true;
	      var file = e.target.files[0];

				var formData = new FormData();
				formData.append('file', file);
				formData.append('upload_preset', 'spn50z5f');

				axios({
					url: 'https://api.cloudinary.com/v1_1/catchesimages/upload',
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					data: formData
				}).then(function(res) {
					self.isUploading = false;
					self.fishForm.photo = res.data.secure_url;
				}).catch(function(err) {
					console.error(err)
				})

	    }
    }
  }
</script>
