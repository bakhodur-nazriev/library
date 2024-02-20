import {createRouter, createWebHistory} from "vue-router";
import i18n from "../i18n";
import HomeView from "../views/HomeView.vue";
import BooksView from "../views/BooksView.vue";
import AuthorsView from "../views/AuthorsView.vue";
import ContactView from "../views/ContactView.vue";
import LoginView from "../views/auth/LoginView.vue";
import RegistrationView from "../views/auth/RegistrationView.vue";
import NotFoundView from "../views/NotFoundView.vue";

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
];

const router = new createRouter({
    history: createWebHistory(import.meta.env.VITE_BASE_URL),
    routes,
    mode: 'history'
});

export default router;