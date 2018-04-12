import VueRouter from 'vue-router'
import store from './store'

let appName = 'Fiskelogg.no'
let routes = [
    {
        path: '/',                                       // all the routes which needs authentication + two factor authentication + lock screen
        component: require('./layouts/default-page'),
        meta: { validate: ['auth','two_factor','lock_screen'] },
        children: [
            {
                path: '/',
                component: require('./views/fish/index'),
                meta: {title: appName + ' | ' + i18n.general.home}
            },
            {
                path: '/home',
                component: require('./views/pages/home'),
                meta: {title: appName + ' | ' + i18n.general.home}
            },
            {
                path: '/profile',
                component: require('./views/pages/profile'),
                meta: {title: appName + ' | ' + i18n.user.profile}
            },
            {
                path: '/change-password',
                component: require('./views/pages/change-password'),
                meta: {title: appName + ' | ' + i18n.auth.change_password}
            },
            {
                path: '/blank',
                component: require('./views/pages/blank'),
                meta: {title: appName }
            },
            {
                path: '/configuration',
                component: require('./views/configuration/basic/index'),
                meta: {title: appName + ' | ' + i18n.configuration.configuration}
            },
            {
                path: '/configuration/logo',
                component: require('./views/configuration/logo/index'),
                meta: {title: appName + ' | ' + i18n.configuration.configuration + ' - ' + i18n.general.logo}
            },
            {
                path: '/configuration/mail',
                component: require('./views/configuration/mail/index'),
                meta: {title: appName + ' | ' + i18n.configuration.configuration + ' - ' + i18n.configuration.mail}
            },
            {
                path: '/backup',
                component: require('./views/backup/index'),
                meta: {title: appName + ' | ' + i18n.backup.backup}
            },
            {
                path: '/configuration/basic',
                component: require('./views/configuration/basic/index'),
                meta: {title: appName + ' | ' + i18n.configuration.configuration + ' - ' + i18n.general.basic}
            },
            {
                path: '/configuration/system',
                component: require('./views/configuration/system/index'),
                meta: {title: appName + ' | ' + i18n.configuration.system}
            },
            {
                path: '/configuration/role',
                component: require('./views/configuration/role/index'),
                meta: {title: appName + ' | ' + i18n.configuration.configuration + ' - ' + i18n.role.role}
            },
            {
                path: '/configuration/menu',
                component: require('./views/configuration/menu/index'),
                meta: {title: appName + ' | ' + i18n.configuration.configuration + ' - ' + i18n.general.menu}
            },
            {
                path: '/configuration/authentication',
                component: require('./views/configuration/authentication/index'),
                meta: {title: appName + ' | ' + i18n.configuration.configuration + ' - ' + i18n.auth.authentication}
            },
            {
                path: '/configuration/sms',
                component: require('./views/configuration/sms/index'),
                meta: {title: appName + ' | ' + i18n.configuration.configuration + ' - ' + i18n.sms.sms}
            },
            {
                path: '/configuration/scheduled-job',
                component: require('./views/configuration/scheduled-job/index'),
                meta: {title: appName + ' | ' + i18n.configuration.configuration + ' - ' + i18n.configuration.scheduled_job}
            },
            {
                path: '/configuration/ip-filter',
                component: require('./views/configuration/ip-filter/index'),
                meta: {title: appName + ' | ' + i18n.configuration.configuration + ' - ' + i18n.ip_filter.ip_filter}
            },
            {
                path: '/configuration/ip-filter/:id/edit',
                component: require('./views/configuration/ip-filter/edit'),
                meta: {title: appName + ' | ' + i18n.configuration.configuration + ' - ' + i18n.ip_filter.edit_ip_filter}
            },
            {
                path: '/configuration/permission',
                component: require('./views/configuration/permission/index'),
                meta: {title: appName + ' | ' + i18n.configuration.configuration + ' - ' + i18n.permission.permission}
            },
            {
                path: '/configuration/permission/assign',
                component: require('./views/configuration/permission/assign'),
                meta: {title: appName + ' | ' + i18n.configuration.configuration + ' - ' + i18n.permission.assign_permission}
            },
            {
                path: '/configuration/locale',
                component: require('./views/configuration/locale/index'),
                meta: {title: appName + ' | ' + i18n.configuration.configuration + ' - ' + i18n.locale.locale}
            },
            {
                path: '/configuration/locale/:id/edit',
                component: require('./views/configuration/locale/edit'),
                meta: {title: appName + ' | ' + i18n.configuration.configuration + ' - ' + i18n.locale.edit_locale}
            },
            {
                path: '/configuration/locale/:locale',
                component: require('./views/configuration/locale/view'),
                meta: {title: appName + ' | ' + i18n.configuration.configuration + ' - ' + i18n.locale.locale}
            },
            {
                path: '/configuration/locale/:locale/:module',
                component: require('./views/configuration/locale/view'),
                meta: {title: appName + ' | ' + i18n.configuration.configuration + ' - ' + i18n.locale.module}
            },
            {
                path: '/email-template',
                component: require('./views/email-template/index'),
                meta: {title: appName + ' | ' + i18n.template.template_list}
            },
            {
                path: '/email-template/:id/edit',
                component: require('./views/email-template/edit'),
                meta: {title: appName + ' | ' + i18n.template.edit_template}
            },
            {
                path: '/email-log',
                component: require('./views/email-log/index'),
                meta: {title: appName + ' | ' + i18n.mail.email_log}
            },
            {
                path: '/activity-log',
                component: require('./views/activity-log/index'),
                meta: {title: appName + ' | ' + i18n.activity.activity_log}
            },
            {
                path: '/fish',
                component: require('./views/fish/index'),
                meta: {title: appName + ' | ' + i18n.fish.index}
            },
            {
                path: '/fish/add',
                component: require('./views/fish/add'),
                meta: {title: appName + ' | ' + i18n.fish.add_new_fish}
            },
            {
                path: '/fish/:uuid',
                component: require('./views/fish/view'),
                meta: {title: appName + ' | ' + i18n.fish.view_fish}
            },
            {
                path: '/fish/:uuid/edit',
                component: require('./views/fish/edit'),
                meta: {title: appName + ' | ' + i18n.fish.edit_fish}
            },
            {
                path: '/user',
                component: require('./views/user/index'),
                meta: {title: appName + ' | ' + i18n.user.user}
            },
            {
                path: '/user/:id',
                component: require('./views/user/basic'),
                meta: {title: appName + ' | ' + i18n.user.user + ' - ' + i18n.general.basic}
            },
            {
                path: '/user/:id/basic',
                component: require('./views/user/basic'),
                meta: {title: appName + ' | ' + i18n.user.user + ' - ' + i18n.general.basic}
            },
            {
                path: '/user/:id/contact',
                component: require('./views/user/contact'),
                meta: {title: appName + ' | ' + i18n.user.user + ' - ' + i18n.user.contact}
            },
            {
                path: '/user/:id/avatar',
                component: require('./views/user/avatar'),
                meta: {title: appName + ' | ' + i18n.user.user + ' - ' + i18n.user.avatar}
            },
            {
                path: '/user/:id/social',
                component: require('./views/user/social'),
                meta: {title: appName + ' | ' + i18n.user.user + ' - ' + i18n.user.social}
            },
            {
                path: '/user/:id/password',
                component: require('./views/user/password'),
                meta: {title: appName + ' | ' + i18n.user.user + ' - ' + i18n.user.password}
            },
            {
                path: '/user/:id/email',
                component: require('./views/user/email'),
                meta: {title: appName + ' | ' + i18n.user.user + ' - ' + i18n.user.email}
            },
            {
                path: '/message',
                component: require('./views/message/index'),
                meta: {title: appName + ' | ' + i18n.message.inbox}
            },
            {
                path: '/message/compose',
                component: require('./views/message/index'),
                meta: {title: appName + ' | ' + i18n.message.compose}
            },
            {
                path: '/message/inbox',
                component: require('./views/message/inbox'),
                meta: {title: appName + ' | ' + i18n.message.inbox}
            },
            {
                path: '/message/sent',
                component: require('./views/message/sent'),
                meta: {title: appName + ' | ' + i18n.message.sent_box}
            },
            {
                path: '/message/important',
                component: require('./views/message/important'),
                meta: {title: appName + ' | ' + i18n.message.important}
            },
            {
                path: '/message/trash',
                component: require('./views/message/trash'),
                meta: {title: appName + ' | ' + i18n.message.trash}
            },
            {
                path: '/message/draft',
                component: require('./views/message/draft'),
                meta: {title: appName + ' | ' + i18n.message.draft}
            },
            {
                path: '/message/:uuid/draft',
                component: require('./views/message/edit-draft'),
                meta: {title: appName + ' | ' + i18n.message.edit_draft}
            },
            {
                path: '/message/:uuid',
                component: require('./views/message/view'),
                meta: {title: appName + ' | ' + i18n.message.message}
            },
        ]
    },
    {
        path: '/',
        component: require('./layouts/guest-page'),
        meta: { validate: ['auth'] },
        children: [
            {
                path: '/auth/security',
                component: require('./views/auth/security'),
                meta: {title: appName + ' | ' + i18n.auth.two_factor_code}
            },
            {
                path: '/auth/lock',
                component: require('./views/auth/lock'),
                meta: {title: appName + ' | ' + i18n.auth.lock_screen}
            },
        ]
    },
    {
        path: '/',                      // all the routes which can be access without authentication
        component: require('./layouts/guest-page'),
        meta: { validate: ['guest'] },
        children: [
            {
                path: '/',
                component: require('./views/auth/login'),
                meta: {title: appName + ' | ' + i18n.auth.login}
            },
            {
                path: '/login',
                component: require('./views/auth/login'),
                meta: {title: appName + ' | ' + i18n.auth.login}
            },
            {
                path: '/password',
                component: require('./views/auth/password'),
                meta: {title: appName + ' | ' + i18n.user.reset_password}
            },
            {
                path: '/register',
                component: require('./views/auth/register'),
                meta: {title: appName + ' | ' + i18n.auth.register}
            },
            {
                path: '/auth/:token/activate',
                component: require('./views/auth/activate'),
                meta: {title: appName + ' | ' + i18n.auth.two_factor_code}
            },
            {
                path: '/password/reset/:token',
                component: require('./views/auth/reset'),
                meta: {title: appName + ' | ' + i18n.passwords.reset_password}
            },
            {
                path: '/auth/social',
                component: require('./views/auth/social-auth'),
                meta: {title: appName + ' | ' + i18n.auth.login}
            }
        ]
    },
    {
        path: '/',
        component : require('./layouts/guest-page'),
        children: [
            {
                path: '/terms-and-conditions',
                component: require('./views/pages/terms-and-conditions'),
                meta: {title: appName + ' | ' + i18n.auth.tnc}
            }
        ]
    },
    {
        path: '/',
        component : require('./layouts/error-page'),
        children: [
            {
                path: '/terms-and-conditions',
                component: require('./views/pages/terms-and-conditions'),
                meta: {title: appName + ' | ' + i18n.auth.tnc}
            },
            {
                path: '/ip-restricted',
                component: require('./views/errors/ip-restricted'),
                meta: {title: appName + ' | ' + i18n.general.permission_denied}
            },
            {
                path: '/maintenance',
                component: require('./views/errors/maintenance'),
                meta: {title: appName + ' | ' + i18n.general.permission_denied}
            }
        ]
    },
    {
        path: '*',
        component : require('./layouts/error-page'),
        children: [
            {
                path: '*',
                component: require('./views/errors/page-not-found'),
                meta: {title: appName + ' | ' + i18n.general.page_not_found_heading}
            }
        ]
    }
];

const router = new VueRouter({
    routes,
    linkActiveClass: 'active',
    mode: 'history',
    scrollBehavior (to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { x: 0, y: 0 }
        }
    }
});

router.beforeEach((to, from, next) => {

    // For force logout
    // store.dispatch('resetAuthUserDetail');

    helper.check()
        .then(response => {

            // Initialize toastr notification
            helper.notification();

            // Check for IP Restriction; If restricted IP found, redirect to "/ip-restricted" route
            if(helper.getConfig('ip_filter') && localStorage.getItem('ip_restricted') && to.fullPath != '/ip-restricted')
                return next({ path: '/ip-restricted' })

            // Check for Maintenance mode; If maintenance mode is on, redirect to "/maintenance" route
            if(helper.isAuth() && !helper.hasRole('admin') && helper.getConfig('maintenance_mode') && to.fullPath != '/maintenance')
                return next({ path: '/maintenance' })

            if (to.matched.some(m => m.meta.validate)){
                const m = to.matched.find(m => m.meta.validate);

                // Check for authentication; If no, redirect to "/login" route
                if (m.meta.validate.indexOf('auth') > -1){
                    if(!helper.isAuth()){
                        toastr.error(i18n.auth.auth_required);
                        return next({ path: '/login' })
                    }
                }

                // Check for two factor security; If enabled, redirect to "/auth/security" route after login
                if (m.meta.validate.indexOf('two_factor') > -1){
                    if(helper.getConfig('two_factor_security') && helper.getTwoFactorCode()){
                        return next({ path: '/auth/security' })
                    }
                }

                // Check for screen lock; If enabled, redirect to "/auth/lock" route after screen lock timeout
                if (m.meta.validate.indexOf('lock_screen') > -1){
                    if(helper.getConfig('lock_screen') && helper.isScreenLocked()){
                        return next({ path: '/auth/lock' })
                    }
                }

                // Check for authentication; If authenticated, redirect to "/home" route
                if (m.meta.validate.indexOf('guest') > -1){
                    if(helper.isAuth()){
                        toastr.error(i18n.auth.guest_required);
                        return next({ path: '/fish' })
                    }
                }
            }

            // This goes through the matched routes from last to first, finding the closest route with a title.
            // eg. if we have /some/deep/nested/route and /some, /deep, and /nested have titles, nested's will be chosen.
            const nearestWithTitle = to.matched.slice().reverse().find(r => r.meta && r.meta.title);

            // Find the nearest route element with meta tags.
            const nearestWithMeta = to.matched.slice().reverse().find(r => r.meta && r.meta.metaTags);
            const previousNearestWithMeta = from.matched.slice().reverse().find(r => r.meta && r.meta.metaTags);

            // If a route with a title was found, set the document (page) title to that value.
            if(nearestWithTitle) document.title = nearestWithTitle.meta.title;

            // Remove any stale meta tags from the document using the key attribute we set below.
            Array.from(document.querySelectorAll('[data-vue-router-controlled]')).map(el => el.parentNode.removeChild(el));

            // Skip rendering meta tags if there are none.
            if(!nearestWithMeta) return next();

            // Turn the meta tag definitions into actual elements in the head.
            nearestWithMeta.meta.metaTags.map(tagDef => {
              const tag = document.createElement('meta');

              Object.keys(tagDef).forEach(key => {
                tag.setAttribute(key, tagDef[key]);
              });

              // We use this to track which meta tags we create, so we don't interfere with other ones.
              tag.setAttribute('data-vue-router-controlled', '');

              return tag;
            })
            // Add the meta tags to the document head.
            .forEach(tag => document.head.appendChild(tag));

        return next()
    })
    .catch(error => {
        // Authentication check fail, redirected back to "/login" route
        store.dispatch('resetAuthUserDetail');
        return next({ path: '/login' })
    });
});

export default router;
