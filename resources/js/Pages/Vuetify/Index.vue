<script setup>
import BreadBrums from "@/Components/BreadBrums.vue";
import { useApp } from "@/composables/useApp";
import { onMounted } from "vue";
const { setLoading } = useApp();
onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Vuetify",
        disabled: false,
        url: route("vuetify"),
        name_url: "vuetify",
    },
];
</script>
<template>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
    </v-container>
</template>
