<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Notificaciones",
        disabled: false,
        url: "",
        name_url: "",
    },
];
</script>
<script setup>
import BreadBrums from "@/Components/BreadBrums.vue";
import { useApp } from "@/composables/useApp";
import { Head, router } from "@inertiajs/vue3";
import { useNotificacions } from "@/composables/notificacions/useNotificacions";
import { ref, onMounted } from "vue";
import { useMenu } from "@/composables/useMenu";
const { mobile, identificaDispositivo } = useMenu();
const { setLoading } = useApp();
onMounted(() => {
    identificaDispositivo();
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const {
    getNotificacionsApi,
    setNotificacion,
    limpiarNotificacion,
    deleteNotificacion,
} = useNotificacions();
const responseNotificacions = ref([]);
const listNotificacions = ref([]);
const itemsPerPage = ref(5);
const headers = ref([
    {
        title: "Obra",
        align: "start",
        sortable: false,
    },
    {
        title: "Descripción",
        align: "start",
        sortable: false,
    },
    {
        title: "Fecha de Registro",
        align: "start",
        sortable: false,
    },
    { title: "Acción", key: "accion", align: "end", sortable: false },
]);

const search = ref("");
const options = ref({
    page: 1,
    itemsPerPage: itemsPerPage,
    sortBy: "",
    sortOrder: "desc",
    search: "",
});

const loading = ref(true);
const totalItems = ref(0);
let setTimeOutLoadData = null;
const loadItems = async ({ page, itemsPerPage, sortBy }) => {
    loading.value = true;
    options.value.page = page;
    if (sortBy.length > 0) {
        options.value.sortBy = sortBy[0].key;
        options.value.sortOrder = sortBy[0].order;
    }
    options.value.search = search.value;

    clearInterval(setTimeOutLoadData);
    setTimeOutLoadData = setTimeout(async () => {
        responseNotificacions.value = await getNotificacionsApi(options.value);
        listNotificacions.value = responseNotificacions.value.data;
        totalItems.value = parseInt(responseNotificacions.value.total);
        loading.value = false;
    }, 300);
};

const verNotificacion = (item) => {
    router.get(route("notificacions.show", item.id));
};
</script>
<template>
    <Head title="Notificaciones"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row class="mt-0">
            <v-col cols="12">
                <v-card flat>
                    <v-card-title>
                        <v-row class="bg-primary d-flex align-center pa-3">
                            <v-col cols="12" sm="6" md="4">
                                Notificaciones
                            </v-col>
                            <v-col cols="12" sm="6" md="4" offset-md="4">
                                <v-text-field
                                    v-model="search"
                                    label="Buscar"
                                    append-inner-icon="mdi-magnify"
                                    variant="underlined"
                                    clearable
                                    hide-details
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-card-title>
                    <v-card-text>
                        <v-data-table-server
                            v-model:items-per-page="itemsPerPage"
                            :headers="!mobile ? headers : []"
                            :class="[mobile ? 'mobile' : '']"
                            :items-length="totalItems"
                            :items="listNotificacions"
                            :loading="loading"
                            :search="search"
                            @update:options="loadItems"
                            height="auto"
                            no-data-text="No se encontrarón registros"
                            loading-text="Cargando..."
                            page-text="{0} - {1} de {2}"
                            items-per-page-text="Registros por página"
                            :items-per-page-options="[
                                { value: 10, title: '10' },
                                { value: 25, title: '25' },
                                { value: 50, title: '50' },
                                { value: 100, title: '100' },
                                {
                                    value: -1,
                                    title: 'Todos',
                                },
                            ]"
                        >
                            <template v-slot:item="{ item }">
                                <tr v-if="!mobile">
                                    <td>
                                        {{
                                            item.notificacion.avance_obra.obra
                                                .nombre
                                        }}
                                    </td>
                                    <td>
                                        {{ item.notificacion.descripcion }}
                                    </td>
                                    <td>
                                        {{ item.notificacion.fecha_registro_t }}
                                    </td>
                                    <td class="text-right">
                                        <v-btn
                                            color="blue-darken-3"
                                            size="small"
                                            class="pa-1 ma-1"
                                            icon="mdi-eye"
                                            @click="verNotificacion(item)"
                                        ></v-btn>
                                    </td>
                                </tr>
                                <tr v-else>
                                    <td>
                                        <ul class="flex-content">
                                            <li
                                                class="flex-item"
                                                data-label="Obra:"
                                            >
                                                {{
                                                    item.notificacion
                                                        .avance_obra.obra.nombre
                                                }}
                                            </li>
                                            <li
                                                class="flex-item"
                                                data-label="Descripción:"
                                            >
                                                {{
                                                    item.notificacion
                                                        .descripcion
                                                }}
                                            </li>
                                            <li
                                                class="flex-item"
                                                data-label="Fecha de Registro:"
                                            >
                                                {{
                                                    item.notificacion
                                                        .fecha_registro_t
                                                }}
                                            </li>
                                        </ul>
                                        <v-row>
                                            <v-col
                                                cols="12"
                                                class="text-center pa-5"
                                            >
                                                <v-btn
                                                    color="blue-darken-3"
                                                    size="small"
                                                    class="pa-1 ma-1"
                                                    icon="mdi-eye"
                                                    @click="
                                                        verNotificacion(item)
                                                    "
                                                ></v-btn>
                                            </v-col>
                                        </v-row>
                                    </td>
                                </tr>
                            </template>
                        </v-data-table-server>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
