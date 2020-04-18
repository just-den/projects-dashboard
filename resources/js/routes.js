import NotFound from './components/NotFound'
import Agenda from './components/Agenda'
import Events from './components/Events'
import Finance from './components/Finance'
import Links from './components/Links'
import Settings from './components/Settings'

import Vue from 'vue'
import VueRouter from 'vue-router';	

Vue.use(VueRouter);

const routes = {
    mode: 'history',
    linkActiveClass: 'active',
    routes: [
        {
            path: '*',
            component: NotFound
        },
        {
            path: '/panel/agenda',
            component: Agenda,
        },
        {
            path: '/panel/events',
            component: Events
        },
        {
            path: '/panel/finance',
            component: Finance
        },
        {
            path: '/panel/links',
            component: Links
        },
        {
            path: '/panel/settings',
            component: Settings
        }
    ]
};

const router = new VueRouter(routes)

export default router