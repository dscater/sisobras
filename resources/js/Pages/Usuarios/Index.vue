<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Usuarios",
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
import { useUsuarios } from "@/composables/usuarios/useUsuarios";
import { ref, onMounted } from "vue";
import { useMenu } from "@/composables/useMenu";
import Formulario from "./Formulario.vue";
import FormPassword from "./FormPassword.vue";
const { mobile, identificaDispositivo } = useMenu();
const { setLoading } = useApp();
onMounted(() => {
    identificaDispositivo();
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const { getUsuariosApi, setUsuario, limpiarUsuario, deleteUsuario } =
    useUsuarios();
const responseUsuarios = ref([]);
const listUsuarios = ref([]);
const itemsPerPage = ref(5);
const headers = ref([
    {
        title: "Id",
        align: "start",
        key: "id",
        sortable: false,
    },
    { title: "Usuario", key: "usuario", align: "start", sortable: false },
    { title: "Nombre", key: "full_name", align: "start", sortable: false },
    { title: "C.I.", key: "full_ci", align: "start", sortable: false },
    { title: "Dirección", key: "dir", align: "start", sortable: false },
    { title: "Correo", key: "email", align: "start", sortable: false },
    { title: "Teléfono/Celular", key: "fono", align: "start", sortable: false },
    { title: "Foto", key: "foto", align: "start", sortable: false },
    { title: "Tipo", key: "tipo", align: "start", sortable: false },
    { title: "Acceso", key: "acceso", align: "start", sortable: false },
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
        responseUsuarios.value = await getUsuariosApi(options.value);
        listUsuarios.value = responseUsuarios.value.data;
        totalItems.value = parseInt(responseUsuarios.value.total);
        loading.value = false;
    }, 300);
};
const recargaUsuarios = async () => {
    loading.value = true;
    listUsuarios.value = [];
    options.value.search = search.value;
    responseUsuarios.value = await getUsuariosApi(options.value);
    listUsuarios.value = responseUsuarios.value.data;
    totalItems.value = parseInt(responseUsuarios.value.total);
    setTimeout(() => {
        loading.value = false;
        open_dialog.value = false;
    }, 300);
};
const accion_dialog = ref(0);
const open_dialog = ref(false);
const accion_dialog_pass = ref(0);
const open_dialog_pass = ref(false);

const agregarRegistro = () => {
    limpiarUsuario();
    accion_dialog.value = 0;
    open_dialog.value = true;
};
const editarUsuario = (item) => {
    setUsuario(item);
    accion_dialog.value = 1;
    open_dialog.value = true;
};
const updatePassword = (item) => {
    setUsuario(item);
    accion_dialog_pass.value = 1;
    open_dialog_pass.value = true;
};
const eliminarUsuario = (item) => {
    Swal.fire({
        title: "¿Quierés eliminar este registro?",
        html: `<strong>${item.full_name}</strong>`,
        showCancelButton: true,
        confirmButtonColor: "#B61431",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        denyButtonText: `No, cancelar`,
    }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            let respuesta = await deleteUsuario(item.id);
            if (respuesta && respuesta.sw) {
                recargaUsuarios();
            }
        }
    });
};
</script>
<template>
    <Head title="Usuarios"></Head>
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
                        <v-row
                            class="bg-blue d-flex align-center pa-3"
                        >
                            <v-col cols="12" sm="6" md="4"> Usuarios </v-col>
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
                            :items="listUsuarios"
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
                                    <td>{{ item.usuario }}</td>
                                    <td class="text-xs-right">
                                        {{ item.full_name }}
                                    </td>
                                    <td>{{ item.full_ci }}</td>
                                    <td>{{ item.dir }}</td>
                                    <td>{{ item.email }}</td>
                                    <td>{{ item.fono }}</td>
                                    <td>
                                        <v-avatar color="blue">
                                            <v-img
                                                v-if="item.url_foto"
                                                :src="item.url_foto"
                                                cover
                                                :lazy-src="item.url_foto"
                                            ></v-img>
                                            <span v-else>{{
                                                item.iniciales_nombre
                                            }}</span>
                                        </v-avatar>
                                    </td>
                                    <td class="text-xs-right">
                                        {{ item.tipo }}
                                    </td>
                                    <td>
                                        <v-chip
                                            :color="
                                                item.acceso == 1
                                                    ? 'success'
                                                    : 'error'
                                            "
                                            :prepend-icon="
                                                item.acceso == 1
                                                    ? 'mdi-check'
                                                    : 'mdi-lock'
                                            "
                                        >
                                            <span
                                                v-text="
                                                    item.acceso == 1
                                                        ? 'Habilitado'
                                                        : 'Denegado'
                                                "
                                            ></span>
                                        </v-chip>
                                    </td>
                                    <td class="text-right">
                                        <v-btn
                                            color="blue"
                                            size="small"
                                            class="pa-1 ma-1"
                                            @click="updatePassword(item)"
                                            icon="mdi-key-variant"
                                        ></v-btn>
                                        <v-btn
                                            color="yellow"
                                            size="small"
                                            class="pa-1 ma-1"
                                            @click="editarUsuario(item)"
                                            icon="mdi-pencil"
                                        ></v-btn>
                                        <v-btn
                                            color="error"
                                            size="small"
                                            class="pa-1 ma-1"
                                            @click="eliminarUsuario(item)"
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
                                                data-label="Usuario"
                                            >
                                                {{ item.usuario }}
                                            </li>
                                            <li
                                                class="flex-item"
                                                data-label="Nombre"
                                            >
                                                {{ item.full_name }}
                                            </li>
                                            <li
                                                class="flex-item"
                                                data-label="C.I:"
                                            >
                                                {{ item.full_ci }}
                                            </li>
                                            <li
                                                class="flex-item"
                                                data-label="Dirección"
                                            >
                                                {{ item.dir }}
                                            </li>
                                            <li
                                                class="flex-item"
                                                data-label="Correo"
                                            >
                                                {{ item.email }}
                                            </li>
                                            <li
                                                class="flex-item"
                                                data-label="Teléfono/Celular"
                                            >
                                                {{ item.fono }}
                                            </li>
                                            <li
                                                class="flex-item"
                                                data-label="Foto"
                                            >
                                                <v-avatar color="blue">
                                                    <v-img
                                                        v-if="item.url_foto"
                                                        :src="item.url_foto"
                                                        cover
                                                        :lazy-src="
                                                            item.url_foto
                                                        "
                                                    ></v-img>
                                                    <span v-else>{{
                                                        item.iniciales_nombre
                                                    }}</span>
                                                </v-avatar>
                                            </li>
                                            <li
                                                class="flex-item"
                                                data-label="Tipo"
                                            >
                                                {{ item.tipo }}
                                            </li>
                                            <li
                                                class="flex-item"
                                                data-label="Acceso"
                                            >
                                                <v-chip
                                                    :color="
                                                        item.acceso == 1
                                                            ? 'success'
                                                            : 'error'
                                                    "
                                                    :prepend-icon="
                                                        item.acceso == 1
                                                            ? 'mdi-check'
                                                            : 'mdi-lock'
                                                    "
                                                >
                                                    <span
                                                        v-text="
                                                            item.acceso == 1
                                                                ? 'Habilitado'
                                                                : 'Denegado'
                                                        "
                                                    ></span>
                                                </v-chip>
                                            </li>
                                        </ul>
                                        <v-row>
                                            <v-col
                                                cols="12"
                                                class="text-center pa-5"
                                            >
                                                <v-btn
                                                    color="blue"
                                                    size="small"
                                                    class="pa-1 ma-1"
                                                    @click="
                                                        updatePassword(item)
                                                    "
                                                    icon="mdi-key-variant"
                                                ></v-btn>
                                                <v-btn
                                                    color="yellow"
                                                    size="small"
                                                    class="pa-1 ma-1"
                                                    @click="editarUsuario(item)"
                                                    icon="mdi-pencil"
                                                ></v-btn>
                                                <v-btn
                                                    color="error"
                                                    size="small"
                                                    class="pa-1 ma-1"
                                                    @click="
                                                        eliminarUsuario(item)
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
            @envio-formulario="recargaUsuarios"
            @cerrar-dialog="open_dialog = false"
        ></Formulario>
        <FormPassword
            :open_dialog="open_dialog_pass"
            :accion_dialog="accion_dialog_pass"
            @envio-formulario="open_dialog_pass = false"
            @cerrar-dialog="open_dialog_pass = false"
        ></FormPassword>
    </v-container>
</template>
