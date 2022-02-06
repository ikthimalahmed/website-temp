import { createRouter, createWebHashHistory } from 'vue-router';

const routes = [
    {
        path: '/my-calendar',
        name:'calendar',
        component: () => import('./components/calendar/MyCalendar.vue'),
    },

    {
        path: '/attendees-list',
        name:'mrbs-attendees-list',
        component: () => import('./components/mrbs-attendees/AttendeesList.vue'),
    },

    {
        path: '/room-list',
        name:'room-list',
        component: () => import('./components/room/roomList.vue'),
    },
    {
        path: '/booking-list',
        name:'booking-list',
        component: () => import('./components/bookings/BookingList.vue'),
    },
];

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});

export default router;
