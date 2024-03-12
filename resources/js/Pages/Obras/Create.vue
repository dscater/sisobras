<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Obra",
        disabled: false,
        url: route("obras.index"),
        name_url: "obras.index",
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
import { useMenu } from "@/composables/useMenu";
import { useObras } from "@/composables/obras/useObras";
import Formulario from "./parcials/Formulario.vue";
const { mobile, identificaDispositivo } = useMenu();
const { setLoading } = useApp();
const { oObra, limpiarObra } = useObras();
limpiarObra();
onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
</script>
<template>
    <Head title="Obras"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row class="mt-0">
            <v-col cols="12" sm="12" md="12">
                <Formulario></Formulario>
            </v-col>
        </v-row>
    </v-container>
</template>
