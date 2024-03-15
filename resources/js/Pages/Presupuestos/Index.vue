<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Presupuestos",
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
import { Head } from "@inertiajs/vue3";
import { usePresupuestos } from "@/composables/presupuestos/usePresupuestos";
import { ref, onMounted } from "vue";
const { mobile, identificaDispositivo, cambiarUrl } = useMenu();
const { setLoading } = useApp();
onMounted(() => {
    identificaDispositivo();
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const { getPresupuestosApi, deletePresupuesto } = usePresupuestos();
const responsePresupuestos = ref([]);
const listPresupuestos = ref([]);
const itemsPerPage = ref(5);
const headers = ref([
    {
        title: "Id",
        align: "start",
        sortable: false,
    },
    {
        title: "Obra",
        align: "start",
        sortable: false,
    },
    { title: "Presupuesto", align: "start", sortable: false },
    { title: "Total Precio(s)", align: "start", sortable: false },
    { title: "Total Cantidad(es)", align: "start", sortable: false },
    { title: "Total", align: "start", sortable: false },
    { title: "Fecha de Registro", align: "start", sortable: false },
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
        responsePresupuestos.value = await getPresupuestosApi(options.value);
        listPresupuestos.value = responsePresupuestos.value.data;
        totalItems.value = parseInt(responsePresupuestos.value.total);
        loading.value = false;
    }, 300);
};
const recargaPresupuestos = async () => {
    loading.value = true;
    listPresupuestos.value = [];
    options.value.search = search.value;
    responsePresupuestos.value = await getPresupuestosApi(options.value);
    listPresupuestos.value = responsePresupuestos.value.data;
    totalItems.value = parseInt(responsePresupuestos.value.total);
    setTimeout(() => {
        loading.value = false;
    }, 300);
};

const editarPresupuesto = (item) => {
    cambiarUrl(route("presupuestos.edit", item.id));
};

const eliminarPresupuesto = (item) => {
    Swal.fire({
        title: "¿Quierés eliminar este registro?",
        html: `<strong>ID <i class="mdi mdi-arrow-right"></i> ${item.id}</strong><br>${item.obra.nombre}`,
        showCancelButton: true,
        confirmButtonColor: "#B61431",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        denyButtonText: `No, cancelar`,
    }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            let respuesta = await deletePresupuesto(item.id);
            if (respuesta && respuesta.sw) {
                recargaPresupuestos();
            }
        }
    });
};
</script>
<template>
    <Head title="Presupuestos"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row class="mt-0">
            <v-col cols="12" class="d-flex justify-end">
                <v-btn
                    color="primary"
                    prepend-icon="mdi-plus"
                    @click="cambiarUrl(route('presupuestos.create'))"
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
                            <v-col cols="12" sm="6" md="4">
                                Presupuestos
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
                            :items="listPresupuestos"
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
                                            {{ item.obra.nombre }}
                                        </td>
                                        <td>
                                            {{ item.presupuesto }}
                                        </td>
                                        <td>
                                            {{ item.total_precio }}
                                        </td>
                                        <td>{{ item.total_cantidad }}</td>
                                        <td>{{ item.total }}</td>
                                        <td>{{ item.fecha_registro_t }}</td>
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
                                            <v-btn
                                                color="yellow"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="editarPresupuesto(item)"
                                                icon="mdi-pencil"
                                            ></v-btn>
                                            <v-btn
                                                color="error"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="
                                                    eliminarPresupuesto(item)
                                                "
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
                                                    cols="4"
                                                    class="text-center"
                                                >
                                                    <v-table>
                                                        <thead>
                                                            <tr>
                                                                <th
                                                                    colspan="5"
                                                                    class="text-center"
                                                                >
                                                                    MATERIALES
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th>N°</th>
                                                                <th>
                                                                    Material
                                                                </th>
                                                                <th>
                                                                    Precio
                                                                    Unidad
                                                                </th>
                                                                <th>
                                                                    Cantidad
                                                                </th>
                                                                <th>
                                                                    Subtotal
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr
                                                                v-for="item,index in item.materials"
                                                            >
                                                                <td>{{ index + 1 }}</td>
                                                                <td>{{ item.material.nombre }}</td>
                                                                <td>{{ item.precio }}</td>
                                                                <td>{{ item.cantidad }}</td>
                                                                <td>{{ item.subtotal }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class="text-right">TOTAL</td>
                                                                <td>{{item.total1_precio}}</td>
                                                                <td>{{item.total1_cantidad}}</td>
                                                                <td>{{item.total1}}</td>
                                                            </tr>
                                                        </tbody>
                                                    </v-table>
                                                </v-col>
                                                <v-col
                                                    cols="4"
                                                    class="text-center"
                                                >
                                                    <v-table>
                                                        <thead>
                                                            <tr>
                                                                <th
                                                                    colspan="5"
                                                                    class="text-center"
                                                                >
                                                                    OPERARIOS/PERSONAL
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th>N°</th>
                                                                <th>
                                                                    Operario/Personal
                                                                </th>
                                                                <th>
                                                                    Precio
                                                                    Unidad
                                                                </th>
                                                                <th>
                                                                    Cantidad
                                                                </th>
                                                                <th>
                                                                    Subtotal
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr
                                                                v-for="item,index in item.operarios"
                                                            >
                                                                <td>{{ index + 1 }}</td>
                                                                <td>{{ item.operario.full_name }}</td>
                                                                <td>{{ item.precio }}</td>
                                                                <td>{{ item.cantidad }}</td>
                                                                <td>{{ item.subtotal }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class="text-right">TOTAL</td>
                                                                <td>{{item.total2_precio}}</td>
                                                                <td>{{item.total2_cantidad}}</td>
                                                                <td>{{item.total2}}</td>
                                                            </tr>
                                                        </tbody>
                                                    </v-table>
                                                </v-col>
                                                <v-col
                                                    cols="4"
                                                    class="text-center"
                                                >
                                                    <v-table>
                                                        <thead>
                                                            <tr>
                                                                <th
                                                                    colspan="5"
                                                                    class="text-center"
                                                                >
                                                                    MAQUINARIAS
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th>N°</th>
                                                                <th>
                                                                    Maquinaria
                                                                </th>
                                                                <th>
                                                                    Precio
                                                                    Unidad
                                                                </th>
                                                                <th>
                                                                    Cantidad
                                                                </th>
                                                                <th>
                                                                    Subtotal
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr
                                                                v-for="item,index in item.maquinarias"
                                                            >
                                                                <td>{{ index + 1 }}</td>
                                                                <td>{{ item.maquinaria.nombre }}</td>
                                                                <td>{{ item.precio }}</td>
                                                                <td>{{ item.cantidad }}</td>
                                                                <td>{{ item.subtotal }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class="text-right">TOTAL</td>
                                                                <td>{{item.total3_precio}}</td>
                                                                <td>{{item.total3_cantidad}}</td>
                                                                <td>{{item.total3}}</td>
                                                            </tr>
                                                        </tbody>
                                                    </v-table>
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
                                                    data-label="Nombre de Presupuesto"
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
                                                    data-label="Encargado de Presupuesto"
                                                >
                                                    {{
                                                        item
                                                            .encargado_presupuesto
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
                                                    <v-btn
                                                        color="yellow"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            editarPresupuesto(
                                                                item
                                                            )
                                                        "
                                                        icon="mdi-pencil"
                                                    ></v-btn>
                                                    <v-btn
                                                        color="error"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            eliminarPresupuesto(
                                                                item
                                                            )
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
