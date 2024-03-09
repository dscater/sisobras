<script setup>
// Composables
import { useInstitucion } from "@/composables/institucion/useInstitucion";
import { useMenu } from "@/composables/useMenu";
import { useUser } from "@/composables/useUser";
const { oUser } = useUser();
const { drawer, width, rail, mobile, toggleDrawer, cambiarUrl } = useMenu();

const user = {
    initials: "JD",
    fullName: "John Doe",
    email: "john.doe@doe.com",
};

const { oInstitucion } = useInstitucion();
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
                    <div v-show="!rail && !mobile">
                        {{ oInstitucion.nombre }}
                    </div>
                </div>
                <div class="acciones">
                    <v-app-bar-nav-icon
                        @click="toggleDrawer(!drawer)"
                    ></v-app-bar-nav-icon>
                </div>
            </div>
            <div class="user">
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
