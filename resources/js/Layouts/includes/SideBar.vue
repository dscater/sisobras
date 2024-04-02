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
    "reportes.usuarios": "Reportes",
    "reportes.presupuestos": "Reportes",
    "reportes.operarios": "Reportes",
    "reportes.obras": "Reportes",
    "reportes.avance_obras": "Reportes",
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
                class="w-100 d-flex flex-column align-center elevation-1 bg-primary pa-2 __info_usuario"
            >
                <v-avatar
                    class="mb-1"
                    color="blue-darken-4"
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
                v-if="
                    oUser.permisos.includes('usuarios.index') ||
                    oUser.permisos.includes('avance_obras.index') ||
                    oUser.permisos.includes('presupuestos.index') ||
                    oUser.permisos.includes('obras.index') ||
                    oUser.permisos.includes('obras.geolocalizacion') ||
                    oUser.permisos.includes('notificacions.index') ||
                    oUser.permisos.includes('categorias.index') ||
                    oUser.permisos.includes('maquinarias.index') ||
                    oUser.permisos.includes('operarios.index') ||
                    oUser.permisos.includes('materials.index')
                "
            >
                <span v-if="rail && !mobile" class="text-center d-block"
                    ><v-icon>mdi-dots-horizontal</v-icon></span
                >
                <span v-else>ADMINISTRACIÓN</span></v-list-item
            >
            <v-list-item
                :class="[route_current == 'avance_obras.index' ? 'active' : '']"
                v-if="oUser.permisos.includes('avance_obras.index')"
                prepend-icon="mdi-clipboard-check-outline"
                @click="cambiarUrl(route('avance_obras.index'))"
                link
            >
                <v-list-item-title>Avance de Obras</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Avance de Obras</v-tooltip
                >
            </v-list-item>
            <v-list-item
                :class="[
                    route_current == 'presupuestos.index' ||
                    route_current == 'presupuestos.create' ||
                    route_current == 'presupuestos.edit'
                        ? 'active'
                        : '',
                ]"
                v-if="oUser.permisos.includes('presupuestos.index')"
                prepend-icon="mdi-table-check"
                @click="cambiarUrl(route('presupuestos.index'))"
                link
            >
                <v-list-item-title>Presupuestos</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Presupuestos</v-tooltip
                >
            </v-list-item>

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
                :class="[
                    route_current == 'obras.geolocalizacion' ? 'active' : '',
                ]"
                v-if="oUser.permisos.includes('obras.geolocalizacion')"
                prepend-icon="mdi-map-search"
                @click="cambiarUrl(route('obras.geolocalizacion'))"
                link
            >
                <v-list-item-title>Geolocalización de Obras</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Geolocalización de Obras</v-tooltip
                >
            </v-list-item>
            <v-list-item
                :class="[
                    route_current == 'notificacions.index' ||
                    route_current == 'notificacions.show'
                        ? 'active'
                        : '',
                ]"
                v-if="oUser.permisos.includes('notificacions.index')"
                prepend-icon="mdi-bell"
                @click="cambiarUrl(route('notificacions.index'))"
                link
            >
                <v-list-item-title>Notificaciones</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Notificaciones</v-tooltip
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

            <v-list-item
                class="text-caption"
                v-if="
                    oUser.permisos.includes('reportes.usuarios') ||
                    oUser.permisos.includes('reportes.presupuestos') ||
                    oUser.permisos.includes('reportes.operarios') ||
                    oUser.permisos.includes('reportes.obras') ||
                    oUser.permisos.includes('reportes.avance_obras')
                "
                ><span v-if="rail && !mobile" class="text-center d-block"
                    ><v-icon>mdi-dots-horizontal</v-icon></span
                >
                <span v-else>REPORTES</span></v-list-item
            >
            <!-- SUBGROUP -->
            <v-list-group
                value="Reportes"
                v-if="
                    oUser.permisos.includes('reportes.usuarios') ||
                    oUser.permisos.includes('reportes.presupuestos') ||
                    oUser.permisos.includes('reportes.operarios') ||
                    oUser.permisos.includes('reportes.obras') ||
                    oUser.permisos.includes('reportes.avance_obras')
                "
            >
                <template v-slot:activator="{ props }">
                    <v-list-item
                        v-bind="props"
                        prepend-icon="mdi-file-document-multiple"
                        title="Reportes"
                        :class="[
                            route_current == 'reporutes.usuarios' ||
                            route_current == 'reportes.presupuestos' ||
                            route_current == 'reportes.operarios' ||
                            route_current == 'reportes.obras' ||
                            route_current == 'reportes.avance_obras'
                                ? 'active'
                                : '',
                        ]"
                    >
                        <v-tooltip
                            v-if="rail && !mobile"
                            color="white"
                            activator="parent"
                            location="end"
                            >Reportes</v-tooltip
                        ></v-list-item
                    >
                </template>
                <v-list-item
                    v-if="oUser.permisos.includes('reportes.usuarios')"
                    prepend-icon="mdi-chevron-right"
                    title="Usuarios"
                    :class="[
                        route_current == 'reportes.usuarios' ? 'active' : '',
                    ]"
                    @click="cambiarUrl(route('reportes.usuarios'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Usuarios</v-tooltip
                    ></v-list-item
                >
                <v-list-item
                    v-if="oUser.permisos.includes('reportes.presupuestos')"
                    prepend-icon="mdi-chevron-right"
                    title="Presupuestos"
                    :class="[
                        route_current == 'reportes.presupuestos'
                            ? 'active'
                            : '',
                    ]"
                    @click="cambiarUrl(route('reportes.presupuestos'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Presupuestos</v-tooltip
                    ></v-list-item
                >
                <v-list-item
                    v-if="oUser.permisos.includes('reportes.operarios')"
                    prepend-icon="mdi-chevron-right"
                    title="Operarios"
                    :class="[
                        route_current == 'reportes.operarios' ? 'active' : '',
                    ]"
                    @click="cambiarUrl(route('reportes.operarios'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Operarios/Personal</v-tooltip
                    ></v-list-item
                >
                <v-list-item
                    v-if="oUser.permisos.includes('reportes.obras')"
                    prepend-icon="mdi-chevron-right"
                    title="Obras"
                    :class="[route_current == 'reportes.obras' ? 'active' : '']"
                    @click="cambiarUrl(route('reportes.obras'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Obras</v-tooltip
                    ></v-list-item
                >
                <v-list-item
                    v-if="oUser.permisos.includes('reportes.avance_obras')"
                    prepend-icon="mdi-chevron-right"
                    title="Avance de Obras"
                    :class="[
                        route_current == 'reportes.avance_obras'
                            ? 'active'
                            : '',
                    ]"
                    @click="cambiarUrl(route('reportes.avance_obras'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Avance de Obras</v-tooltip
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
