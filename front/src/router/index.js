import {createRouter, createWebHistory} from "vue-router";
import i18n from "../i18n";
import HomeView from "../views/HomeView.vue";
import BooksView from "../views/BooksView.vue";
import AuthorsView from "../views/AuthorsView.vue";
import ContactView from "../views/ContactView.vue";

const routes = [
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
];

const router = new createRouter({
    history: createWebHistory(import.meta.env.VITE_BASE_URL),
    routes,
    mode: 'history'
});

export default router;