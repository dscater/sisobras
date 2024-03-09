<script setup>
import App from "@/Layouts/App.vue";
import { router } from "@inertiajs/vue3";
import { computed } from "vue";
// componentes
import BreadBrums from "@/Components/BreadBrums.vue";

const props = defineProps({
    status: {
        type: Number,
    },
    message: {
        type: String,
    },
});

const title = computed(() => {
    return {
        503: "503: Servicio no disponible",
        500: "500: Error del servidor",
        404: "404: Pagina no encontrada",
        403: "403: Forbidden",
        401: "401: No autorizado",
    }[props.status];
});

const description = computed(() => {
    return {
        503: "Sorry, we are doing some maintenance. Please check back soon.",
        500: "Ops, ocurrió un error en el servidor.",
        404: "Lo siento, no se encontró esta página.",
        403: "Lo siento, usted no tiene acceso a esta página.",
        401: "Lo siento, usted no tiene acceso a esta página.",
    }[props.status];
});

const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Sin autorización",
        disabled: false,
        url: "",
        name_url: "",
    },
];

const redirigirALogin = () => {
    router.get(route("inicio"));
};
</script>
<template>
    <App>
        <v-container>
            <BreadBrums :breadbrums="breadbrums"></BreadBrums>
            <v-row align="center" justify="center">
                <v-col cols="12">
                    <v-card>
                        <v-card-title class="text-h5">
                            {{ title }}
                        </v-card-title>

                        <v-card-text>
                            <p>
                                {{ description }}
                            </p>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="primary" @click="redirigirALogin"
                                >Volver al inicio</v-btn
                            >
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </App>
</template>
