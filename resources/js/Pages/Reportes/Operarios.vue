<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Reporte Opearios/Personal",
        disabled: false,
        url: "",
        name_url: "",
    },
];
</script>

<script setup>
import BreadBrums from "@/Components/BreadBrums.vue";
import { useApp } from "@/composables/useApp";
import { computed, onMounted, ref } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import { useObras } from "@/composables/obras/useObras";
const { getObras } = useObras();
const { setLoading } = useApp();

onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const form = ref({
    obra_id: "TODOS",
});

const generando = ref(false);
const txtBtn = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return "Generar Reporte";
});

const listObras = ref([]);

const cargarListas = async () => {
    listObras.value = await getObras({
        order: "desc",
    });

    listObras.value.unshift({
        id: "TODOS",
        nombre: "TODOS",
    });
};

const generarReporte = () => {
    generando.value = true;
    const url = route("reportes.r_operarios", form.value);
    window.open(url, "_blank");
    setTimeout(() => {
        generando.value = false;
    }, 500);
};

onMounted(() => {
    cargarListas();
});
</script>
<template>
    <Head title="Reporte Opearios/Personal"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row>
            <v-col cols="12" sm="12" md="12" xl="8" class="mx-auto">
                <v-card>
                    <v-card-item>
                        <v-container>
                            <form @submit.prevent="generarReporte">
                                <v-row>
                                    <v-col cols="12">
                                        <v-select
                                            :hide-details="
                                                form.errors?.obra_id
                                                    ? false
                                                    : true
                                            "
                                            :error="
                                                form.errors?.obra_id
                                                    ? true
                                                    : false
                                            "
                                            :error-messages="
                                                form.errors?.obra_id
                                                    ? form.errors?.obra_id
                                                    : ''
                                            "
                                            variant="outlined"
                                            density="compact"
                                            required
                                            :items="listObras"
                                            item-value="id"
                                            item-title="nombre"
                                            label="Seleccionar Obra*"
                                            v-model="form.obra_id"
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-btn
                                            color="blue-darken-2"
                                            block
                                            @click="generarReporte"
                                            :disabled="generando"
                                            v-text="txtBtn"
                                        ></v-btn>
                                    </v-col>
                                </v-row>
                            </form>
                        </v-container>
                    </v-card-item>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
