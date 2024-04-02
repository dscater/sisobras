<script setup>
// Composables
import { useInstitucion } from "@/composables/institucion/useInstitucion";
import { useMenu } from "@/composables/useMenu";
import { useUser } from "@/composables/useUser";
import { usePage, Link } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
const { oUser } = useUser();
const { drawer, width, rail, mobile, toggleDrawer, cambiarUrl } = useMenu();
const { props } = usePage();
const { oInstitucion } = useInstitucion();

const listNotificacions = ref([]);
const sin_ver = ref(0);
const getNotificacions = () => {
    let tipo_usuario = props.auth.user.tipo;
    if (
        tipo_usuario == "GERENTE GENERAL" ||
        tipo_usuario == "GERENTE REGIONAL"
    ) {
        axios
            .get(route("notificacions.byUser"), {
                params: {
                    sin_ver: sin_ver.value,
                },
            })
            .then((response) => {
                if (
                    response.data.list_notificacions.length !=
                        listNotificacions.value.length ||
                    sin_ver.value != response.data.sin_ver
                ) {
                    listNotificacions.value = response.data.list_notificacions;
                }
                sin_ver.value = response.data.sin_ver;
            });
    }
};
onMounted(() => {
    getNotificacions();
    interval_notificacions = setInterval(getNotificacions, 1500);
});
</script>
<template>
    <v-app-bar class="__navbar">
        <div class="contenedor_navbar">
            <div class="inicio">
                <div
                    class="logo"
                    :style="[
                        mobile
                            ? 'width:80px'
                            : 'min-width: ' + (width - 3) + 'px;',
                    ]"
                >
                    <div class="__img">
                        <img
                            v-if="oInstitucion.url_logo"
                            :src="oInstitucion.url_logo"
                            :style="[
                                mobile ? 'width:60px' : '',
                                rail ? 'width:' + (width - 30) + 'px' : '',
                            ]"
                        />
                        <v-avatar
                            v-else
                            color="grey-darken-3"
                            class="text-center d-block"
                        >
                            <span class="text-h5 d-block text-center">{{
                                oInstitucion.iniciales_nombre
                            }}</span>
                        </v-avatar>
                    </div>
                    <div
                        v-show="!rail && !mobile"
                        class="text-secundario font-weight-bold"
                    >
                        {{ oInstitucion.razon_social }}
                    </div>
                </div>
                <div class="acciones">
                    <v-app-bar-nav-icon
                        @click="toggleDrawer(!drawer)"
                    ></v-app-bar-nav-icon>
                </div>
            </div>
            <div class="user">
                <v-menu
                    v-if="
                        props.auth.user.tipo == 'GERENTE GENERAL' ||
                        props.auth.user.tipo == 'GERENTE REGIONAL'
                    "
                    :width="mobile ? '100%' : '20%'"
                    rounded
                >
                    <template v-slot:activator="{ props }">
                        <v-btn
                            color="white"
                            v-bind="props"
                            stacked
                            class="text-none"
                        >
                            <v-badge
                                v-if="sin_ver > 0"
                                color="error"
                                :content="sin_ver"
                            >
                                <v-icon>mdi-bell-outline</v-icon>
                            </v-badge>
                            <v-icon v-else>mdi-bell-outline</v-icon>
                        </v-btn>
                    </template>
                    <v-list class="pt-0" style="margin-top: -10px">
                        <v-list-item class="bg-blue-darken-3">
                            <p class="text-body-1 text-center w-100">
                                {{ sin_ver }} Notificaciónes Nuevas
                            </p>
                        </v-list-item>
                        <template v-for="(item, index) in listNotificacions">
                            <v-list-item>
                                <template v-slot:prepend>
                                    <v-icon
                                        :class="[
                                            item.visto == 0
                                                ? 'text-orange-darken-4'
                                                : '',
                                        ]"
                                        :icon="
                                            item.visto == 0
                                                ? 'mdi-bell-badge'
                                                : 'mdi-bell'
                                        "
                                    ></v-icon>
                                </template>
                                <template v-slot:title>
                                    <Link
                                        :href="
                                            route('notificacions.show', item.id)
                                        "
                                        class="text-decoration-none text-body-2"
                                        :class="[
                                            item.visto == 0
                                                ? 'text-orange-darken-3'
                                                : '',
                                        ]"
                                        >{{
                                            item.notificacion.descripcion
                                        }}</Link
                                    >
                                </template>
                                <template v-slot:subtitle>
                                    <span class="text-caption">{{
                                        item.notificacion.avance_obra.obra
                                            .nombre
                                    }}</span>
                                </template>
                                <template v-slot:append>
                                    <span class="text-caption"
                                        >{{ item.hace }}.</span
                                    >
                                </template>
                            </v-list-item>
                            <v-divider
                                v-if="index < listNotificacions.length - 1"
                            ></v-divider>
                        </template>
                    </v-list>
                </v-menu>

                <v-menu :width="mobile ? '50%' : '13%'" rounded>
                    <template v-slot:activator="{ props }">
                        <v-btn icon v-bind="props">
                            <v-avatar color="blue-darken-4">
                                <v-img
                                    cover
                                    v-if="oUser.url_foto"
                                    :src="oUser.url_foto"
                                >
                                </v-img>
                                <span v-else class="text-h5">{{
                                    oUser.iniciales_nombre
                                }}</span>
                            </v-avatar>
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-text class="pa-0 pt-4">
                            <div class="mx-auto text-center">
                                <v-avatar color="blue-darken-4">
                                    <v-img
                                        cover
                                        v-if="oUser.url_foto"
                                        :src="oUser.url_foto"
                                    >
                                    </v-img>
                                    <span v-else class="text-h5">{{
                                        oUser.iniciales_nombre
                                    }}</span>
                                </v-avatar>
                                <h3>{{ oUser.full_name }}</h3>
                                <p class="text-caption mt-2">
                                    {{ oUser.tipo }}
                                </p>
                                <v-divider class="my-0 mt-2"></v-divider>
                                <v-btn
                                    block
                                    prepend-icon="mdi-account-circle"
                                    variant="text"
                                    class="text-none"
                                    @click="cambiarUrl(route('profile.edit'))"
                                >
                                    Perfil
                                </v-btn>
                                <v-divider class="my-0"></v-divider>
                                <v-btn
                                    block
                                    prepend-icon="mdi-logout"
                                    variant="text"
                                    class="text-none"
                                    @click="cambiarUrl(route('logout'), 'post')"
                                >
                                    Salir
                                </v-btn>
                                <v-divider class="my-0"></v-divider>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-menu>
            </div>
        </div>
    </v-app-bar>
</template>
