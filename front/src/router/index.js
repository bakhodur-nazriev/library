import {createRouter, createWebHistory} from "vue-router";
import i18n from "../i18n";
import HomeView from "../views/HomeView.vue";
import BooksView from "../views/BooksView.vue";
import AuthorsView from "../views/AuthorsView.vue";
import ContactView from "../views/ContactView.vue";
import LoginView from "../views/auth/LoginView.vue";
import RegistrationView from "../views/auth/RegistrationView.vue";
import NotFoundView from "../views/NotFoundView.vue";
import ForgotPasswordView from "../views/auth/ForgotPasswordView.vue";
import DashboardHomeView from "../views/dashboard/DashboardHomeView.vue";
import DashboardAuthorsView from "../views/dashboard/DashboardAuthorsView.vue";
import DashboardBooksView from "../views/dashboard/DashboardBooksView.vue";
import DashboardUsersView from "../views/dashboard/DashboardUsersView.vue";

const routes = [
    {
        path: '/:lang?/login',
        name: 'login',
        component: LoginView,
        meta: {
            title: i18n.global.t('meta_title.login'),
            requiresAuth: false,
            layout: 'auth'
        }
    },
    {
        path: '/:lang?/registration',
        name: 'registration',
        component: RegistrationView,
        meta: {
            title: i18n.global.t('meta_title.registration'),
            requiresAuth: false,
            layout: 'auth'
        }
    },
    {
        path: '/:lang?/forgot-password',
        name: 'forgot-password',
        component: ForgotPasswordView,
        meta: {
            title: i18n.global.t('meta_title.forgot_password'),
            requiresAuth: false,
            layout: 'auth'
        }
    },
    {
        path: '/:lang?/',
        name: 'home',
        component: HomeView,
        meta: {
            title: i18n.global.t('meta_title.home'),
            requiresAuth: true,
            layout: 'main'
        }
    },
    {
        path: '/:lang?/authors',
        name: 'authors',
        component: AuthorsView,
        meta: {
            title: i18n.global.t('meta_title.home'),
            requiresAuth: true,
            layout: 'main'
        }
    },
    {
        path: '/:lang?/books',
        name: 'books',
        component: BooksView,
        meta: {
            title: i18n.global.t('meta_title.home'),
            requiresAuth: true,
            layout: 'main'
        }
    },
    {
        path: '/:lang?/contact',
        name: 'contact',
        component: ContactView,
        meta: {
            title: i18n.global.t('meta_title.home'),
            requiresAuth: true,
            layout: 'main'
        }
    },
    {
        path: '/:lang?/*',
        name: 'not-found',
        component: NotFoundView,
        meta: {
            title: i18n.global.t('meta_title.not_found'),
            requiresAuth: false,
            layout: 'auth'
        }
    },

    /* Dashboard */
    {
        path: '/:lang?/dashboard',
        children: [
            {
                path: '',
                name: 'dashboard-home',
                component: DashboardHomeView,
                meta: {
                    title: i18n.global.t('meta_title.dashboard.home'),
                    requiresAuth: true,
                    layout: 'dashboard'
                }
            },
            {
                path: 'authors',
                name: 'dashboard-authors',
                component: DashboardAuthorsView,
                meta: {
                    title: i18n.global.t('meta_title.dashboard.authors'),
                    requiresAuth: true,
                    layout: 'dashboard'
                }
            },
            {
                path: 'books',
                name: 'dashboard-books',
                component: DashboardBooksView,
                meta: {
                    title: i18n.global.t('meta_title.dashboard.books'),
                    requiresAuth: true,
                    layout: 'dashboard'
                }
            },
            {
                path: 'users',
                name: 'dashboard-users',
                component: DashboardUsersView,
                meta: {
                    title: i18n.global.t('meta_title.dashboard.users'),
                    requiresAuth: true,
                    layout: 'dashboard'
                }
            },
        ]
    }
];

const router = new createRouter({
    history: createWebHistory(import.meta.env.VITE_BASE_URL),
    routes,
    mode: 'history'
});

export default router;