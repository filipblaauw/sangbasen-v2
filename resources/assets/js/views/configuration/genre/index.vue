<template>
    <div>
        <div class="row page-titles">
            <div class="col-md-6 col-8 align-self-center">
                <h3 class="m-b-0 m-t-0">{{trans('configuration.configuration')}}</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><router-link to="/home">{{trans('general.home')}}</router-link></li>
                    <li class="breadcrumb-item"><router-link to="/configuration/basic">{{trans('configuration.configuration')}}</router-link></li>
                    <li class="breadcrumb-item active">{{trans('genre.genre')}}</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <configuration-sidebar menu="genre"></configuration-sidebar>
                            <div class="col-10 col-lg-10 col-md-10">

                                <div class="row">
                                    <div class="col-12 col-sm-4 col-md-4">
                                        <h4 class="card-title">{{trans('genre.add_new_genre')}}</h4>
                                        <genre-form @completed="getGenres"></genre-form>

                                    </div>
                                    <div class="col-12 col-sm-8 col-md-8">
                                        <h4 class="card-title">{{trans('genre.genre_list')}}</h4>
                                        <h6 class="card-subtitle" v-if="genres">{{trans('general.total_result_found',{'count' : genres.total})}}</h6>
                                        <h6 class="card-subtitle" v-else>{{trans('general.no_result_found')}}</h6>
                                        <div class="table-responsive" v-if="genres.total">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>{{trans('genre.name')}}</th>
                                                        <th>{{trans('genre.songs_count')}}</th>
                                                        <th class="table-option">{{trans('genre.action')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="genre in genres.data">
                                                        <td v-text="genre.name"></td>
                                                        <td>{{genre.songs.length}}</td>
                                                        <td class="table-option">
                                                            <div class="btn-group">
                                                              <button class="btn btn-info btn-sm" v-tooltip="trans('genre.edit_genre')" @click.prevent="editGenre(genre)"><i class="fas fa-edit"></i></button>
                                                                <button class="btn btn-danger btn-sm" :key="genre.id" v-confirm="{ok: confirmDelete(genre)}" v-tooltip="trans('genre.delete_genre')"><i class="fas fa-trash"></i></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <module-info v-if="!genres.total" module="genre" title="module_info_title" description="module_info_description" icon="key"></module-info>
                                        <pagination-record :page-length.sync="filterGenreForm.page_length" :records="genres" @updateRecords="getGenres" @change.native="getGenres"></pagination-record>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import configurationSidebar from '../system-config-sidebar'
    import genreForm from './form'

    export default {
        components : { configurationSidebar,genreForm },
        data() {
            return {
                genres: {
                    total: 0,
                    data: []
                },
                filterGenreForm: {
                    page_length: helper.getConfig('page_length')
                }
            };
        },
        mounted(){
            if(!helper.hasPermission('access-configuration')){
                helper.notAccessibleMsg();
                this.$router.push('/home');
            }
            this.getGenres();
        },
        methods: {
            getGenres(page){
                if (typeof page !== 'number') {
                    page = 1;
                }
                let url = helper.getFilterURL(this.filterGenreForm);
                axios.get('/api/genre?page=' + page + url)
                    .then(response => response.data)
                    .then(response => this.genres = response
                    )
                    .catch(error => {
                        helper.showDataErrorMsg(error);
                    });
            },
            editGenre(genre){
                this.$router.push('/configuration/genre/'+genre.id+'/edit');
            },
            confirmDelete(genre){
                return dialog => this.deleteGenre(genre);
            },
            deleteGenre(genre){
                axios.delete('/api/genre/'+genre.id)
                    .then(response => response.data)
                    .then(response => {
                        toastr.success(response.message);
                        this.getGenres();
                    }).catch(error => {
                        helper.showDataErrorMsg(error);
                    });
            }
        }
    }
</script>
