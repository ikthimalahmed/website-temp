<template>
	<div :class="containerClass" @click="onWrapperClick">
        <Toast/>
		<AppTopBar @menu-toggle="onMenuToggle" />

        <transition name="layout-sidebar">
            <div :class="sidebarClass" @click="onSidebarClick" v-show="isSidebarVisible()">
                <AppProfile />
                <AppMenu :model="menu" @menuitem-click="onMenuItemClick" />
            </div>
        </transition>

		<div class="layout-main">
			<router-view />
		</div>

		<!-- <AppConfig :layoutMode="layoutMode" :layoutColorMode="layoutColorMode" @layout-change="onLayoutChange" @layout-color-change="onLayoutColorChange"/> -->

		<AppFooter />
	</div>
</template>

<script>
import AppTopBar from './AppTopbar.vue';
import AppProfile from './AppProfile.vue';
import AppMenu from './AppMenu.vue';
// import AppConfig from './AppConfig.vue';
import AppFooter from './AppFooter.vue';

export default {
    data() {
        return {
            layoutMode: 'static',
            layoutColorMode: 'light',
            staticMenuInactive: false,
            overlayMenuActive: false,
            mobileMenuActive: false,
            menu : [
                {label: 'Booking Calender', icon: 'pi pi-fw pi-calendar', to: '/my-calendar'},
                {label: 'Contact', icon: 'pi pi-fw pi-users', to: '/attendees-list'},
                {label: 'Bookings', icon: 'pi pi-book', to: '/booking-list'},
                {label: 'Rooms', icon: 'pi pi-home', to: '/room-list'},
            ]
        }
    },
    components: {
         AppTopBar,
         AppProfile,
         AppMenu,
        //  AppConfig,
         AppFooter,
    },
    watch: {
        $route() {
            this.menuActive = false;
            this.$toast.removeAllGroups();
        }
    },
    methods: {
        onWrapperClick() {
            if (!this.menuClick) {
                this.overlayMenuActive = false;
                this.mobileMenuActive = false;
            }

            this.menuClick = false;
        },
        onMenuToggle() {
            this.menuClick = true;

            if (this.isDesktop()) {
                if (this.layoutMode === 'overlay') {
					if(this.mobileMenuActive === true) {
						this.overlayMenuActive = true;
					}

                    this.overlayMenuActive = !this.overlayMenuActive;
					this.mobileMenuActive = false;
                }
                else if (this.layoutMode === 'static') {
                    this.staticMenuInactive = !this.staticMenuInactive;
                }
            }
            else {
                this.mobileMenuActive = !this.mobileMenuActive;
            }

            event.preventDefault();
        },
        onSidebarClick() {
            this.menuClick = true;
        },
        onMenuItemClick(event) {
            if (event.item && !event.item.items) {
                this.overlayMenuActive = false;
                this.mobileMenuActive = false;
            }
        },
		onLayoutChange(layoutMode) {
			this.layoutMode = layoutMode;
		},
		onLayoutColorChange(layoutColorMode) {
			this.layoutColorMode = layoutColorMode;
		},
        addClass(element, className) {
            if (element.classList)
                element.classList.add(className);
            else
                element.className += ' ' + className;
        },
        removeClass(element, className) {
            if (element.classList)
                element.classList.remove(className);
            else
                element.className = element.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
        },
        isDesktop() {
            return window.innerWidth > 1024;
        },
        isSidebarVisible() {
            if (this.isDesktop()) {
                if (this.layoutMode === 'static')
                    return !this.staticMenuInactive;
                else if (this.layoutMode === 'overlay')
                    return this.overlayMenuActive;
                else
                    return true;
            }
            else {
                return true;
            }
        },
    },
    computed: {
        containerClass() {
            return ['layout-wrapper', {
                'layout-overlay': this.layoutMode === 'overlay',
                'layout-static': this.layoutMode === 'static',
                'layout-static-sidebar-inactive': this.staticMenuInactive && this.layoutMode === 'static',
                'layout-overlay-sidebar-active': this.overlayMenuActive && this.layoutMode === 'overlay',
                'layout-mobile-sidebar-active': this.mobileMenuActive,
				'p-input-filled': this.$appState.inputStyle === 'filled',
				'p-ripple-disabled': this.$primevue.ripple === false
            }];
        },
        sidebarClass() {
            return ['layout-sidebar', {
                'layout-sidebar-dark': this.layoutColorMode === 'dark',
                'layout-sidebar-light': this.layoutColorMode === 'light'
            }];
        },
        logo() {
            return (this.layoutColorMode === 'dark') ? "assets/layout/images/logo-white.svg" : "assets/layout/images/logo.svg";
        }
    },
    beforeUpdate() {
        if (this.mobileMenuActive)
            this.addClass(document.body, 'body-overflow-hidden');
        else
            this.removeClass(document.body, 'body-overflow-hidden');
    },
    
}
</script>

<style >

.table{width:100%;max-width:100%;margin-bottom:1rem;background-color:transparent;  }
.table th{ font-weight: 800; font-size: 10px !important;}
.table td,.table th{padding:.75rem;vertical-align:middle;border-bottom:1px solid #eeeeee; font-size: 15px !important;}
.table thead th{vertical-align:middle;border-bottom:2px solid #dee2e6}
.table-hover tbody tr:hover{background-color:rgba(0,0,0,.075)}
.table tr:nth-child(even){
    background: #f9f9f9;
}

</style>
