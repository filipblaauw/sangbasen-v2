<template>
	<aside class="left-sidebar d-print-none">
        <div class="scroll-sidebar">
            <div class="user-profile">
                <div class="profile-img"> <img :src="getAuthUser('avatar')" alt="user" /> </div>
            </div>
            <nav class="sidebar-nav m-t-20">
                <div class="text-center" v-if="getConfig('maintenance_mode')"><span class="badge badge-danger m-b-10">{{trans('configuration.under_maintenance')}}</span></div>
                <div class="text-center" v-if="!getConfig('mode')"><span class="badge badge-danger m-b-10">{{trans('configuration.test_mode')}}</span></div>
                <ul id="sidebarnav">
                    <li><router-link to="/home" exact><i class="fas fa-home fa-fw"></i> <span class="hide-menu">{{trans('general.home')}}</span></router-link></li>
                    <li v-if="hasPermission('list-user') && getConfig('show_user_menu')"><router-link to="/user" exact><i class="fas fa-users fa-fw"></i> <span class="hide-menu">{{trans('user.user')}}</span></router-link></li>
                    <li v-if="hasPermission('access-todo') && getConfig('show_todo_menu')"><router-link to="/todo" exact><i class="far fa-check-circle fa-fw"></i> <span class="hide-menu">{{trans('todo.todo')}}</span></router-link></li>
										<li v-if="hasPermission('access-song') && getConfig('show_song_menu')"><router-link to="/song" exact><i class="fas fa-music"></i> <span class="hide-menu">{{trans('song.song')}}</span></router-link></li>
										<li v-if="hasPermission('access-song')"><router-link to="/help" exact><i class="far fa-question-circle"></i> <span class="hide-menu">Hjelp</span></router-link></li>
										<li v-if="hasPermission('access-message') && getConfig('show_message_menu')"><router-link to="/message" exact><i class="far fa-envelope-open fa-fw"></i> <span class="hide-menu">{{trans('message.message')}}</span></router-link></li>
                    <li v-if="hasPermission('access-configuration') && getConfig('show_configuration_menu')"><router-link to="/configuration" exact><i class="fas fa-cogs fa-fw"></i> <span class="hide-menu">{{trans('configuration.configuration')}}</span></router-link></li>
                    <li v-if="hasPermission('access-configuration') && getConfig('show_backup_menu')"><router-link to="/backup" exact><i class="fas fa-database fa-fw"></i> <span class="hide-menu">{{trans('backup.backup')}}</span></router-link></li>
                    <li v-if="hasPermission('access-configuration') && getConfig('show_email_template_menu')"><router-link to="/email-template" exact><i class="far fa-envelope fa-fw"></i> <span class="hide-menu">{{trans('template.email_template')}}</span></router-link></li>
                    <li v-if="hasPermission('access-configuration') && getConfig('show_email_log_menu')"><router-link to="/email-log" exact><i class="fas fa-envelope fa-fw"></i> <span class="hide-menu">{{trans('mail.email_log')}}</span></router-link></li>
                    <li v-if="hasPermission('access-configuration') && getConfig('show_activity_log_menu')"><router-link to="/activity-log" exact><i class="fas fa-bars fa-fw"></i> <span class="hide-menu">{{trans('activity.activity_log')}}</span></router-link></li>
                </ul>
            </nav>
        </div>
        <div class="sidebar-footer">
            <router-link v-if="hasPermission('access-configuration')" to="/configuration" class="link" v-tooltip="trans('configuration.configuration')"><i class="fas fa-cogs"></i></router-link>
            <router-link to="/profile" class="link" v-tooltip="trans('user.profile')"><i class="fas fa-user"></i></router-link>
            <a href="#" class="link" v-tooltip="trans('auth.logout')" @click.prevent="logout"><i class="fas fa-power-off"></i></a>
        </div>
    </aside>
</template>

<script>
    export default {
        mounted() {
        },
        methods : {
            logout(){
                helper.logout().then(() => {
                    this.$store.dispatch('resetAuthUserDetail');
                    this.$router.push('/login');
                })
            },
            getAuthUser(name){
                return helper.getAuthUser(name);
            },
            hasPermission(permission){
                return helper.hasPermission(permission);
            },
            getConfig(config){
                return helper.getConfig(config);
            }
        },
        computed: {
        }
    }
</script>
