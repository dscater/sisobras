import { defineStore } from "pinia";

export const useMenuStore = defineStore("menu", {
    state: () => ({
        drawer: false,
        mobile: false,
        rail: false,
        width: 270,
        url_actual: "/",
        menu_open: [],
    }),
    actions: {
        toggleDrawer(value) {
            this.drawer = value;
            this.ajustaAncho();
        },
        toggleRail(value) {
            this.rail = value;
            this.ajustaAncho();
        },
        ajustaAncho() {
            if (this.rail) {
                this.width = 75.5;
            } else {
                this.width = 270;
            }
        },
        setMobile(value) {
            this.mobile = value;
        },
        setMenuOpen(value) {
            this.menu_open = value;
        },
    },
});
