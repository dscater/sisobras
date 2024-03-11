<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Categorías",
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
import { useCategorias } from "@/composables/categorias/useCategorias";
import { ref, onMounted } from "vue";
import { useMenu } from "@/composables/useMenu";
import Formulario from "./Formulario.vue";
const { mobile, identificaDispositivo } = useMenu();
const { setLoading } = useApp();
onMounted(() => {
    identificaDispositivo();
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const { getCategoriasApi, setCategoria, limpiarCategoria, deleteCategoria } =
    useCategorias();
const responseCategorias = ref([]);
const listCategorias = ref([]);
const itemsPerPage = ref(5);
const headers = ref([
    {
        title: "Id",
        align: "start",
        key: "id",
        sortable: false,
    },
    {
        title: "Nombre de Categoria",
        key: "nombre",
        align: "start",
        sortable: false,
    },
    {
        title: "Nro. de Avances",
        key: "nro_avances",
        align: "start",
        sortable: false,
    },
    {
        title: "Fecha de Registro",
        key: "fecha_registro",
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
        responseCategorias.value = await getCategoriasApi(options.value);
        listCategorias.value = responseCategorias.value.data;
        totalItems.value = parseInt(responseCategorias.value.total);
        loading.value = false;
    }, 300);
};
const recargaCategorias = async () => {
    loading.value = true;
    listCategorias.value = [];
    options.value.search = search.value;
    responseCategorias.value = await getCategoriasApi(options.value);
    listCategorias.value = responseCategorias.value.data;
    totalItems.value = parseInt(responseCategorias.value.total);
    setTimeout(() => {
        loading.value = false;
        open_dialog.value = false;
    }, 300);
};
const accion_dialog = ref(0);
const open_dialog = ref(false);

const agregarRegistro = () => {
    limpiarCategoria();
    accion_dialog.value = 0;
    open_dialog.value = true;
};
const editarCategoria = (item) => {
    setCategoria(item);
    accion_dialog.value = 1;
    open_dialog.value = true;
};
const eliminarCategoria = (item) => {
    Swal.fire({
        title: "¿Quierés eliminar este registro?",
        html: `<strong>${item.nombre}</strong>`,
        showCancelButton: true,
        confirmButtonColor: "#B61431",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        denyButtonText: `No, cancelar`,
    }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            let respuesta = await deleteCategoria(item.id);
            if (respuesta && respuesta.sw) {
                recargaCategorias();
            }
        }
    });
};
</script>
<template>
    <Head title="Categorías"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row class="mt-0">
            <v-col cols="12" class="d-flex justify-end">
                <v-btn
                    color="blue"
                    prepend-icon="mdi-plus"
                    @click="agregarRegistro"
                >
                    Agregar</v-btn
                >
            </v-col>
        </v-row>
        <v-row class="mt-0">
            <v-col cols="12">
                <v-card flat>
                    <v-card-title>
                        <v-row class="bg-blue d-flex align-center pa-3">
                            <v-col cols="12" sm="6" md="4"> Categorías </v-col>
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
                            :items="listCategorias"
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
                                    <td>{{ item.id }}</td>
                                    <td>{{ item.nombre }}</td>
                                    <td>
                                        {{ item.nro_avances }}
                                    </td>
                                    <td>{{ item.fecha_registro_t }}</td>
                                    <td class="text-right">
                                        <v-btn
                                            color="yellow"
                                            size="small"
                                            class="pa-1 ma-1"
                                            @click="editarCategoria(item)"
                                            icon="mdi-pencil"
                                        ></v-btn>
                                        <v-btn
                                            color="error"
                                            size="small"
                                            class="pa-1 ma-1"
                                            @click="eliminarCategoria(item)"
                                            icon="mdi-trash-can"
                                        ></v-btn>
                                    </td>
                                </tr>
                                <tr v-else>
                                    <td>
                                        <ul class="flex-content">
                                            <li
                                                class="flex-item"
                                                data-label="Id"
                                            >
                                                {{ item.id }}
                                            </li>
                                            <li
                                                class="flex-item"
                                                data-label="Nombre de Categoria:"
                                            >
                                                {{ item.nombre }}
                                            </li>
                                            <li
                                                class="flex-item"
                                                data-label="Nro. de Avances:"
                                            >
                                                {{ item.nro_avances }}
                                            </li>
                                            <li
                                                class="flex-item"
                                                data-label="Fecha de Registro:"
                                            >
                                                {{ item.fecha_registro_t }}
                                            </li>
                                        </ul>
                                        <v-row>
                                            <v-col
                                                cols="12"
                                                class="text-center pa-5"
                                            >
                                                <v-btn
                                                    color="yellow"
                                                    size="small"
                                                    class="pa-1 ma-1"
                                                    @click="
                                                        editarCategoria(item)
                                                    "
                                                    icon="mdi-pencil"
                                                ></v-btn>
                                                <v-btn
                                                    color="error"
                                                    size="small"
                                                    class="pa-1 ma-1"
                                                    @click="
                                                        eliminarCategoria(item)
                                                    "
                                                    icon="mdi-trash-can"
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
        <Formulario
            :open_dialog="open_dialog"
            :accion_dialog="accion_dialog"
            @envio-formulario="recargaCategorias"
            @cerrar-dialog="open_dialog = false"
        ></Formulario>
    </v-container>
</template>
