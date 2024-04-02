<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Obras",
        disabled: false,
        url: "",
        name_url: "",
    },
];
</script>
<script setup>
import BreadBrums from "@/Components/BreadBrums.vue";
import { useApp } from "@/composables/useApp";
import { useMenu } from "@/composables/useMenu";
import { Head, usePage } from "@inertiajs/vue3";
import { useObras } from "@/composables/obras/useObras";
import { ref, onMounted } from "vue";
const { mobile, identificaDispositivo, cambiarUrl } = useMenu();
const { setLoading } = useApp();
const { props } = usePage();
onMounted(() => {
    identificaDispositivo();
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const { getObrasApi, deleteObra } = useObras();
const responseObras = ref([]);
const listObras = ref([]);
const itemsPerPage = ref(5);
const headers = ref([
    {
        title: "Id",
        align: "start",
        sortable: false,
    },
    {
        title: "Nombre de la Obra",
        align: "start",
        sortable: false,
    },
    { title: "Gerente Regional", align: "start", sortable: false },
    { title: "Encargado de Obra", align: "start", sortable: false },
    { title: "Fecha Plazo de Entrega", align: "start", sortable: false },
    { title: "Fecha Plazo de Ejecución", align: "start", sortable: false },
    { title: "Descripción", align: "start", sortable: false },
    { title: "Categoría", align: "start", sortable: false },
    { title: "Más", align: "start", sortable: false },
    { title: "Acción", align: "end", sortable: false },
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
        responseObras.value = await getObrasApi(options.value);
        listObras.value = responseObras.value.data;
        totalItems.value = parseInt(responseObras.value.total);
        loading.value = false;
    }, 300);
};
const recargaObras = async () => {
    loading.value = true;
    listObras.value = [];
    options.value.search = search.value;
    responseObras.value = await getObrasApi(options.value);
    listObras.value = responseObras.value.data;
    totalItems.value = parseInt(responseObras.value.total);
    setTimeout(() => {
        loading.value = false;
    }, 300);
};

const editarObra = (item) => {
    cambiarUrl(route("obras.edit", item.id));
};

const eliminarObra = (item) => {
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
            let respuesta = await deleteObra(item.id);
            if (respuesta && respuesta.sw) {
                recargaObras();
            }
        }
    });
};
const verUbicación = async (item) => {};
</script>
<template>
    <Head title="Obras"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row class="mt-0">
            <v-col cols="12" class="d-flex justify-end">
                <v-btn
                    v-if="props.auth.user.permisos.includes('obras.create')"
                    color="primary"
                    prepend-icon="mdi-plus"
                    @click="cambiarUrl(route('obras.create'))"
                >
                    Agregar</v-btn
                >
            </v-col>
        </v-row>
        <v-row class="mt-0">
            <v-col cols="12">
                <v-card flat>
                    <v-card-title>
                        <v-row class="bg-primary d-flex align-center pa-3">
                            <v-col cols="12" sm="6" md="4"> Obras </v-col>
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
                            :items="listObras"
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
                                <template v-if="!mobile">
                                    <tr>
                                        <td>{{ item.id }}</td>
                                        <td>
                                            {{ item.nombre }}
                                        </td>
                                        <td>
                                            {{
                                                item.gerente_regional.full_name
                                            }}
                                        </td>
                                        <td>
                                            {{ item.encargado_obra.full_name }}
                                        </td>
                                        <td>{{ item.fecha_pent_t }}</td>
                                        <td>{{ item.fecha_peje_t }}</td>
                                        <td>{{ item.descripcion }}</td>
                                        <td>{{ item.categoria.nombre }}</td>
                                        <td>
                                            <v-btn
                                                :icon="
                                                    item.mas
                                                        ? 'mdi-chevron-down'
                                                        : 'mdi-chevron-left'
                                                "
                                                @click="item.mas = !item.mas"
                                            ></v-btn>
                                        </td>
                                        <td class="text-right" width="5%">
                                            <!-- <v-btn
                                                color="primary"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="verUbicación(item)"
                                                icon="mdi-map-marker"
                                            ></v-btn> -->
                                            <v-btn
                                                v-if="
                                                    props.auth.user.permisos.includes(
                                                        'obras.edit'
                                                    )
                                                "
                                                color="yellow"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="editarObra(item)"
                                                icon="mdi-pencil"
                                            ></v-btn>
                                            <v-btn
                                                v-if="
                                                    props.auth.user.permisos.includes(
                                                        'obras.destroy'
                                                    )
                                                "
                                                color="error"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="eliminarObra(item)"
                                                icon="mdi-trash-can"
                                            ></v-btn>
                                        </td>
                                    </tr>
                                    <tr v-if="item.mas">
                                        <td
                                            :colspan="headers.length"
                                            class="py-5"
                                        >
                                            <v-row>
                                                <v-col
                                                    cols="3"
                                                    class="text-center"
                                                >
                                                    <v-row>
                                                        <v-col
                                                            cols="12"
                                                            class="pb-0 text-caption font-weight-black"
                                                            >Fecha de
                                                            Registro</v-col
                                                        >
                                                        <v-col cols="12">{{
                                                            item.fecha_registro_t
                                                        }}</v-col>
                                                    </v-row>
                                                </v-col>
                                            </v-row>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
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
                                                    data-label="Nombre de Obra"
                                                >
                                                    {{ item.nombre }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Gerente Regional"
                                                >
                                                    {{
                                                        item.gerente_regional
                                                            .full_name
                                                    }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Encargado de Obra"
                                                >
                                                    {{
                                                        item.encargado_obra
                                                            .full_name
                                                    }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Fecha Plazo de Entrega"
                                                >
                                                    {{ item.fecha_pent_t }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Fecha Plazo de Ejecución"
                                                >
                                                    {{ item.fecha_peje_t }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Descripción"
                                                >
                                                    {{ item.descripcion }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Trabajo realizado"
                                                >
                                                    {{ item.trabajo_realizado }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Fecha de Registro"
                                                >
                                                    {{ item.fecha_registro }}
                                                </li>
                                            </ul>
                                            <v-row>
                                                <v-col
                                                    cols="12"
                                                    class="text-center pa-5"
                                                >
                                                    <!-- <v-btn
                                                        color="primary"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            verUbicación(item)
                                                        "
                                                        icon="mdi-map-marker"
                                                    ></v-btn> -->
                                                    <v-btn
                                                        v-if="
                                                            props.auth.user.permisos.includes(
                                                                'obras.edit'
                                                            )
                                                        "
                                                        color="yellow"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            editarObra(item)
                                                        "
                                                        icon="mdi-pencil"
                                                    ></v-btn>
                                                    <v-btn
                                                        v-if="
                                                            props.auth.user.permisos.includes(
                                                                'obras.destroy'
                                                            )
                                                        "
                                                        color="error"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            eliminarObra(item)
                                                        "
                                                        icon="mdi-trash-can"
                                                    ></v-btn>
                                                </v-col>
                                            </v-row>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                        </v-data-table-server>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
