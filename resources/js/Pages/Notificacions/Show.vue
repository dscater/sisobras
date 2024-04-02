<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Notificacion",
        disabled: false,
        url: route("notificacions.index"),
        name_url: "notificacions.index",
    },
    {
        title: "Nuevo",
        disabled: false,
        url: "",
        name_url: "",
    },
];
</script>
<script setup>
import BreadBrums from "@/Components/BreadBrums.vue";
import { useApp } from "@/composables/useApp";
import { Head } from "@inertiajs/vue3";
import { onMounted } from "vue";
import { useNotificacions } from "@/composables/notificacions/useNotificacions";
import { useMenu } from "@/composables/useMenu";
const { mobile, identificaDispositivo, cambiarUrl } = useMenu();
const { setLoading } = useApp();
const props = defineProps({
    notificacion_user: {
        type: Object,
    },
});

onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
</script>
<template>
    <Head title="Notificacions"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>

        <v-row class="mt-0">
            <v-col cols="12" class="d-flex justify-end">
                <v-btn
                    prepend-icon="mdi-arrow-left"
                    class="mr-2"
                    @click="cambiarUrl(route('notificacions.index'))"
                >
                    Volver</v-btn
                >
            </v-col>
        </v-row>
        <v-row class="mt-0">
            <v-col cols="12" sm="12" md="12">
                <v-card>
                    <v-card-title>
                        <h4 class="text-center text-body-1">
                            Ver notificación
                        </h4>
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="3" class="text-right font-weight-bold"
                                >Obra:</v-col
                            >
                            <v-col cols="9" class="text-uppercase">{{
                                props.notificacion_user.notificacion.avance_obra
                                    .obra.nombre
                            }}</v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="3" class="text-right font-weight-bold"
                                >Registrado:</v-col
                            >
                            <v-col cols="9" class="text-uppercase">
                                {{
                                    props.notificacion_user.notificacion
                                        .fecha_registro_t
                                }}
                                <span class="text-caption"
                                    >({{ props.notificacion_user.hace }})</span
                                >
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="3" class="text-right font-weight-bold"
                                >Descripción:</v-col
                            >
                            <v-col cols="9" class="text-uppercase">{{
                                props.notificacion_user.notificacion.descripcion
                            }}</v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="3" class="text-right font-weight-bold"
                                >Avances registrados:</v-col
                            >
                            <v-col cols="9" class="text-uppercase">{{
                                props.notificacion_user.notificacion.avance_obra
                                    .marcados
                            }}</v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="3" class="text-right font-weight-bold"
                                >Porcentaje Progreso:</v-col
                            >
                            <v-col cols="9" class="text-uppercase"
                                >{{
                                    props.notificacion_user.notificacion
                                        .avance_obra.porcentaje
                                }}%</v-col
                            >
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
