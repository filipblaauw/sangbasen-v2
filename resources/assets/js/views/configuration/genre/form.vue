<template>
    <form @submit.prevent="proceed" @keydown="genreForm.errors.clear($event.target.name)">
        <div class="form-group">
            <label for="">{{trans('genre.name')}}</label>
            <input class="form-control" type="text" value="" v-model="genreForm.name" name="name" :placeholder="trans('genre.name')">
            <show-error :form-name="genreForm" prop-name="name"></show-error>
        </div>
        <button type="submit" class="btn btn-info waves-effect waves-light pull-right">
            <span v-if="id">{{trans('general.update')}}</span>
            <span v-else>{{trans('general.save')}}</span>
        </button>
        <router-link to="/configuration/genre" class="btn btn-danger waves-effect waves-light pull-right m-r-10" v-show="id">{{trans('general.cancel')}}</router-link>
    </form>
</template>


<script>
    export default {
        data() {
            return {
                genreForm: new Form({
                    name : ''
                })
            };
        },
        props: ['id'],
        mounted() {
            if(this.id)
                this.getGenre();
        },
        methods: {
          proceed(){
              if(this.id)
                  this.updateGenre();
              else
                  this.storeGenre();
          },
          storeGenre(){
              this.genreForm.post('/api/genre')
                  .then(response => {
                      toastr.success(response.message);
                      this.$emit('completed')
                  })
                  .catch(error => {
                      helper.showErrorMsg(error);
                  });
          },
          getGenre(){
              axios.get('/api/genre/'+this.id)
                  .then(response => response.data)
                  .then(response => {
                      this.genreForm.name = response.name;
                  })
                  .catch(error => {
                      helper.showDataErrorMsg(error);
                      this.$router.push('/configuration/genre');
                  });
          },
          updateGenre(){
              this.genreForm.patch('/api/genre/'+this.id)
                  .then(response => {
                      toastr.success(response.message);
                      this.$router.push('/configuration/genre');
                  })
                  .catch(error => {
                      helper.showErrorMsg(error);
                  });
          }
        }
    }
</script>
