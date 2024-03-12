<script setup>
import { useMenu } from "@/composables/useMenu";
import { onMounted, ref } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { useUser } from "@/composables/useUser";
const { oUser } = useUser();

// data
const {
    mobile,
    drawer,
    rail,
    width,
    menu_open,
    setMenuOpen,
    cambiarUrl,
    toggleDrawer,
} = useMenu();

const user_logeado = ref({
    permisos: [],
});

const submenus = {
    vuetify: "Multinivel1",
    vuetify2: "Multinivel1",
};

const route_current = ref("");

router.on("navigate", (event) => {
    route_current.value = route().current();
    if (mobile.value) {
        toggleDrawer(false);
    }
});

const { props: props_page } = usePage();

onMounted(() => {
    let route_actual = route().current();
    // buscar en submenus y abrirlo si uno de sus elementos esta activo
    setMenuOpen([]);
    if (submenus[route_actual]) {
        setMenuOpen([submenus[route_actual]]);
    }

    if (props_page.auth) {
        user_logeado.value = props_page.auth?.user;
    }

    setTimeout(() => {
        scrollActive();
    }, 300);
});

const scrollActive = () => {
    let sidebar = document.querySelector("#sidebar");
    let menu = null;
    let activeChild = null;
    if (sidebar) {
        menu = sidebar.querySelector(".v-navigation-drawer__content");
        activeChild = sidebar.querySelector(".active");
    }
    // Verifica si se encontró un hijo activo
    if (activeChild) {
        let offsetTop = activeChild.offsetTop - sidebar.offsetTop;
        setTimeout(() => {
            menu.scrollTo({
                top: offsetTop,
                behavior: "smooth", // desplazamiento suave
            });
        }, 500);
    }
};
</script>
<template>
    <v-navigation-drawer
        :permanent="!mobile"
        :temporary="mobile"
        v-model="drawer"
        class="border-0 elevation-2 __sidebar"
        :width="width"
        id="sidebar"
    >
        <v-sheet>
            <div
                class="w-100 d-flex flex-column align-center elevation-1 bg-blue pa-2 __info_usuario"
            >
                <v-avatar
                    class="mb-1"
                    color="blue-darken-3"
                    :size="`${!rail ? '64' : '32'}`"
                >
                    <v-img
                        cover
                        v-if="oUser.url_foto"
                        :src="oUser.url_foto"
                    ></v-img>
                    <span v-else class="text-h5">
                        {{ oUser.iniciales_nombre }}
                    </span>
                </v-avatar>
                <div v-show="!rail" class="text-caption font-weight-bold">
                    {{ oUser.tipo }}
                </div>
                <div v-show="!rail" class="text-body">
                    {{ oUser.full_name }}
                </div>
            </div>
        </v-sheet>

        <v-list class="mt-1 px-2" v-model:opened="menu_open">
            <v-list-item class="text-caption">
                <span v-if="rail && !mobile" class="text-center d-block"
                    ><v-icon>mdi-dots-horizontal</v-icon></span
                >
                <span v-else>INICIO</span></v-list-item
            >
            <v-list-item
                prepend-icon="mdi-home-outline"
                :class="[route_current == 'inicio' ? 'active' : '']"
                @click="cambiarUrl(route('inicio'))"
                link
            >
                <v-list-item-title>Inicio</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Inicio</v-tooltip
                >
            </v-list-item>
            <v-list-item
                class="text-caption"
                v-if="oUser.permisos.includes('usuarios.index')"
            >
                <span v-if="rail && !mobile" class="text-center d-block"
                    ><v-icon>mdi-dots-horizontal</v-icon></span
                >
                <span v-else>ADMINISTRACIÓN</span></v-list-item
            >

            <v-list-item
                :class="[
                    route_current == 'obras.index' ||
                    route_current == 'obras.create' ||
                    route_current == 'obras.edit'
                        ? 'active'
                        : '',
                ]"
                v-if="oUser.permisos.includes('obras.index')"
                prepend-icon="mdi-view-list"
                @click="cambiarUrl(route('obras.index'))"
                link
            >
                <v-list-item-title>Obras</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Obras</v-tooltip
                >
            </v-list-item>

            <v-list-item
                :class="[route_current == 'categorias.index' ? 'active' : '']"
                v-if="oUser.permisos.includes('categorias.index')"
                prepend-icon="mdi-clipboard-list"
                @click="cambiarUrl(route('categorias.index'))"
                link
            >
                <v-list-item-title>Categorías</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Categorías</v-tooltip
                >
            </v-list-item>

            <v-list-item
                :class="[route_current == 'maquinarias.index' ? 'active' : '']"
                v-if="oUser.permisos.includes('maquinarias.index')"
                prepend-icon="mdi-tow-truck"
                @click="cambiarUrl(route('maquinarias.index'))"
                link
            >
                <v-list-item-title>Maquinarias</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Maquinarias</v-tooltip
                >
            </v-list-item>

            <v-list-item
                :class="[route_current == 'operarios.index' ? 'active' : '']"
                v-if="oUser.permisos.includes('operarios.index')"
                prepend-icon="mdi-account-group-outline"
                @click="cambiarUrl(route('operarios.index'))"
                link
            >
                <v-list-item-title>Operarios</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Operarios</v-tooltip
                >
            </v-list-item>

            <v-list-item
                :class="[route_current == 'materials.index' ? 'active' : '']"
                v-if="oUser.permisos.includes('materials.index')"
                prepend-icon="mdi-view-list-outline"
                @click="cambiarUrl(route('materials.index'))"
                link
            >
                <v-list-item-title>Materiales</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Materiales</v-tooltip
                >
            </v-list-item>

            <v-list-item
                :class="[route_current == 'usuarios.index' ? 'active' : '']"
                v-if="oUser.permisos.includes('usuarios.index')"
                prepend-icon="mdi-account-group"
                @click="cambiarUrl(route('usuarios.index'))"
                link
            >
                <v-list-item-title>Usuarios</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Usuarios</v-tooltip
                >
            </v-list-item>

            <v-list-item class="text-caption"
                ><span v-if="rail && !mobile" class="text-center d-block"
                    ><v-icon>mdi-dots-horizontal</v-icon></span
                >
                <span v-else>MULTINIVEL</span></v-list-item
            >
            <!-- SUBGROUP -->
            <v-list-group value="Multinivel1">
                <template v-slot:activator="{ props }">
                    <v-list-item
                        v-bind="props"
                        prepend-icon="mdi-list-box"
                        title="Multinivel 1"
                        :class="[
                            route_current == 'vuetify' ||
                            route_current == 'vuetify2'
                                ? 'active'
                                : '',
                        ]"
                    >
                        <v-tooltip
                            v-if="rail && !mobile"
                            color="white"
                            activator="parent"
                            location="end"
                            >Multinivel 1</v-tooltip
                        ></v-list-item
                    >
                </template>
                <v-list-item
                    prepend-icon="mdi-chevron-right"
                    title="Vuetify"
                    :class="[route_current == 'vuetify' ? 'active' : '']"
                    @click="cambiarUrl(route('vuetify'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Vuetify</v-tooltip
                    ></v-list-item
                >
                <v-list-item
                    prepend-icon="mdi-chevron-right"
                    title="Vuetify 2"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Vuetify 2</v-tooltip
                    ></v-list-item
                >
            </v-list-group>
            <v-list-item class="text-caption"
                ><span v-if="rail && !mobile" class="text-center d-block"
                    ><v-icon>mdi-dots-horizontal</v-icon></span
                >
                <span v-else>OTROS</span></v-list-item
            >
            <v-list-item
                v-if="oUser.permisos.includes('institucions.index')"
                prepend-icon="mdi-cog-outline"
                :class="[route_current == 'institucions.index' ? 'active' : '']"
                @click="cambiarUrl(route('institucions.index'))"
                link
            >
                <v-list-item-title>Institución</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Institución</v-tooltip
                >
            </v-list-item>
            <v-list-item
                prepend-icon="mdi-account-circle"
                :class="[route_current == 'profile.edit' ? 'active' : '']"
                @click="cambiarUrl(route('profile.edit'))"
                link
            >
                <v-list-item-title>Perfil</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Perfil</v-tooltip
                >
            </v-list-item>
            <v-list-item
                prepend-icon="mdi-logout"
                @click="cambiarUrl(route('logout'), 'post')"
                link
            >
                <v-list-item-title>Salir</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Salir</v-tooltip
                >
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>
<style scoped></style>
