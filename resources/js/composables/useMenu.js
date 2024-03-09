import { useMenuStore } from "@/stores/menuStore";
import { storeToRefs } from "pinia";
import { useDisplay } from "vuetify";
import { router } from "@inertiajs/vue3";
import { useApp } from "@/composables/useApp";
import { onMounted, onUnmounted } from "vue";

export const useMenu = () => {
    const { setLoading } = useApp();
    const store = useMenuStore();
    const { drawer, mobile, rail, width, menu_open, url_actual } =
        storeToRefs(store);

    const toggleDrawer = (value) => {
        if (!mobile.value) {
            rail.value = !rail.value;
            store.toggleDrawer(true);
        } else {
            store.toggleDrawer(value);
        }
    };
    const toggleRail = (value) => {
        store.toggleRail(value);
    };

    const setMenuOpen = (val) => {
        store.setMenuOpen(val);
    };

    const cambiarUrl = (
        url,
        method = "get",
        info = { data: {}, value: "" }
    ) => {
        setLoading(true);

        if (method == "get") {
            router.get(url, info.data);
        } else {
            router.post(url, info.data);
        }
    };

    const getMobile = () => {
        return useDisplay().mobile.value;
    };

    const identificaDispositivo = () => {
        const value_mobile = getMobile();
        store.setMobile(value_mobile);
        store.toggleRail(false);
        if (value_mobile) {
            store.toggleDrawer(false);
        } else {
            store.toggleDrawer(true);
        }
        store.ajustaAncho();
    };

    const handleResize = () => {
        const x = window.innerWidth;
        const y = window.innerHeight;
        if (x <= 960) {
            store.setMobile(true);
        } else {
            store.setMobile(false);
        }
    };

    onMounted(() => {
        identificaDispositivo();
        window.addEventListener("resize", handleResize);
    });

    onUnmounted(() => {
        window.removeEventListener("resize", handleResize);
    });

    return {
        // propiedaes
        drawer,
        mobile,
        rail,
        width,
        menu_open,
        // metodos
        toggleDrawer,
        toggleRail,
        identificaDispositivo,
        setMenuOpen,
        cambiarUrl,
    };
};
