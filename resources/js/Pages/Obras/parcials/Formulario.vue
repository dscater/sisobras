<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useObras } from "@/composables/obras/useObras";
import { useUsuarios } from "@/composables/usuarios/useUsuarios";
import { useCategorias } from "@/composables/categorias/useCategorias";
import { useMenu } from "@/composables/useMenu";
import { watch, ref, reactive, computed, onMounted } from "vue";

const { mobile, cambiarUrl } = useMenu();
const { oObra, limpiarObra } = useObras();
let form = useForm(oObra);

const { flash, auth } = usePage().props;
const user = ref(auth.user);
const { getUsuariosByTipo } = useUsuarios();
const { getCategorias } = useCategorias();

const listUsuariosRegional = ref([]);
const listUsuariosEncargado = ref([]);
const listCategorias = ref([]);

const tituloDialog = computed(() => {
    return oObra.id == 0 ? `Agregar Obra` : `Editar Obra`;
});

const enviarFormulario = () => {
    let url =
        form["_method"] == "POST"
            ? route("obras.store")
            : route("obras.update", form.id);

    form.post(url, {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            limpiarObra();
            cambiarUrl(route("obras.index"));
        },
        onError: (err) => {
            Swal.fire({
                icon: "info",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.error
                        ? err.error
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
        },
    });
};

const cargarListas = async () => {
    listUsuariosRegional.value = await getUsuariosByTipo({
        order: "desc",
        tipo: "GERENTE REGIONAL",
    });
    listUsuariosEncargado.value = await getUsuariosByTipo({
        order: "desc",
        tipo: "ENCARGADO DE OBRA",
    });
    listCategorias.value = await getCategorias({
        order: "desc",
    });
};

const cargaMapaGoogle = async (lat, lng, drag = false, dir = "") => {
    form.lat = lat;
    form.lng = lng;
    lat = parseFloat(lat);
    lng = parseFloat(lng);
    // Inicializa el mapa
    const { Map, InfoWindow } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
    const map = new Map(document.getElementById("google_map"), {
        zoom: 18,
        center: { lat: lat, lng: lng },
        mapId: mapa_id,
    });
    const infoWindow = new InfoWindow();

    // Crea un marcador en el centro del mapa
    const AME = new AdvancedMarkerElement({
        map,
        position: { lat: lat, lng: lng },
        gmpDraggable: drag,
        // title:""
    });

    // Escucha el evento de arrastrar del marcador
    AME.addListener("dragend", (event) => {
        const position = AME.position;
        // console.log(position.lat);
        // console.log(position.lng);
        form.lat = "" + position.lat;
        form.lng = "" + position.lng;
        console.log(form.lat);
        console.log(form.lng);
        // infoWindow.close();
        // infoWindow.setContent(
        //     `Pin dropped at: ${position.lat}, ${position.lng}`
        // );
        // infoWindow.open(AME.map, AME);
    });

    // evento click sobre el marcador
    infoWindow.close();
    infoWindow.setContent(form.nombre ? form.nombre : "Ubicación Obra");
    AME.addListener("click", () => {
        infoWindow.open(AME.map, AME);
    });
};

function toggleHighlight(markerView, property) {
    console.log(markerView.content);
    console.log(markerView.zIndex);
    if (markerView.content.classList.contains("highlight")) {
        markerView.content.classList.remove("highlight");
        markerView.zIndex = 1;
    } else {
        markerView.content.classList.add("highlight");
        markerView.zIndex = 1;
    }
}

onMounted(() => {
    if (form.id && form.id != "") {
        cargaMapaGoogle(form.lat, form.lng, true);
    } else {
        cargaMapaGoogle("-16.496059", "-68.133345", true);
    }
    cargarListas();
});
</script>

<template>
    <v-row class="mt-0">
        <v-col cols="12" class="d-flex justify-end">
            <template v-if="mobile">
                <v-btn
                    icon="mdi-arrow-left"
                    class="mr-2"
                    @click="cambiarUrl(route('obras.index'))"
                ></v-btn>
                <v-btn icon="mdi-content-save" color="primary"></v-btn>
            </template>
            <template v-if="!mobile">
                <v-btn
                    prepend-icon="mdi-arrow-left"
                    class="mr-2"
                    @click="cambiarUrl(route('obras.index'))"
                >
                    Volver</v-btn
                >
                <v-btn
                    :prepend-icon="
                        oObra.id != 0 ? 'mdi-sync' : 'mdi-content-save'
                    "
                    color="primary"
                    @click="enviarFormulario"
                >
                    <span
                        v-text="
                            oObra.id != 0 ? 'Actualizar Obra' : 'Guardar Obra'
                        "
                    ></span
                ></v-btn>
            </template>
        </v-col>
    </v-row>
    <v-row>
        <v-col cols="12" sm="12" md="6" xl="6">
            <v-card>
                <v-card-title class="border-b bg-primary pa-5">
                    <v-icon
                        :icon="form.id == 0 ? 'mdi-plus' : 'mdi-pencil'"
                    ></v-icon>
                    <span class="text-h5" v-html="tituloDialog"></span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <form @submit.prevent="enviarFormulario">
                            <v-row>
                                <v-col cols="12" sm="12" md="12" xl="6">
                                    <v-textarea
                                        :hide-details="
                                            form.errors?.nombre ? false : true
                                        "
                                        :error="
                                            form.errors?.nombre ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.nombre
                                                ? form.errors?.nombre
                                                : ''
                                        "
                                        variant="outlined"
                                        label="Nombre de la Obra*"
                                        rows="1"
                                        auto-grow
                                        density="compact"
                                        v-model="form.nombre"
                                    ></v-textarea>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" xl="6">
                                    <v-select
                                        :hide-details="
                                            form.errors?.gerente_regional_id
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.gerente_regional_id
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.gerente_regional_id
                                                ? form.errors
                                                      ?.gerente_regional_id
                                                : ''
                                        "
                                        clearable
                                        variant="outlined"
                                        label="Seleccionar Gerente Regional*"
                                        :items="listUsuariosRegional"
                                        item-value="id"
                                        item-title="full_name"
                                        required
                                        density="compact"
                                        v-model="form.gerente_regional_id"
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" xl="6">
                                    <v-select
                                        :hide-details="
                                            form.errors?.encargado_obra_id
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.encargado_obra_id
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.encargado_obra_id
                                                ? form.errors?.encargado_obra_id
                                                : ''
                                        "
                                        clearable
                                        variant="outlined"
                                        label="Seleccionar Encargado de Obra*"
                                        :items="listUsuariosEncargado"
                                        item-value="id"
                                        item-title="full_name"
                                        required
                                        density="compact"
                                        v-model="form.encargado_obra_id"
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" xl="6">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.fecha_pent
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.fecha_pent
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.fecha_pent
                                                ? form.errors?.fecha_pent
                                                : ''
                                        "
                                        variant="outlined"
                                        type="date"
                                        label="Fecha de Plazo de Entrega*"
                                        required
                                        density="compact"
                                        v-model="form.fecha_pent"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" xl="6">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.fecha_peje
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.fecha_peje
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.fecha_peje
                                                ? form.errors?.fecha_peje
                                                : ''
                                        "
                                        variant="outlined"
                                        type="date"
                                        label="Fecha de Plazo de Ejecución*"
                                        required
                                        density="compact"
                                        v-model="form.fecha_peje"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" xl="6">
                                    <v-textarea
                                        :hide-details="
                                            form.errors?.descripcion
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.descripcion
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.descripcion
                                                ? form.errors?.descripcion
                                                : ''
                                        "
                                        variant="outlined"
                                        label="Descripción"
                                        rows="1"
                                        auto-grow
                                        density="compact"
                                        v-model="form.descripcion"
                                    ></v-textarea>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" xl="6">
                                    <v-select
                                        :hide-details="
                                            form.errors?.categoria_id
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.categoria_id
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.categoria_id
                                                ? form.errors?.categoria_id
                                                : ''
                                        "
                                        clearable
                                        variant="outlined"
                                        label="Seleccionar Categoría*"
                                        :items="listCategorias"
                                        item-value="id"
                                        item-title="nombre"
                                        required
                                        density="compact"
                                        v-model="form.categoria_id"
                                    ></v-select>
                                </v-col>
                            </v-row>
                        </form>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="12" sm="12" md="6" xl="6">
            <v-card>
                <v-card-title class="bg-primary pa-5">
                    <span class="text-h5">Ubicación de la Obra</span>
                </v-card-title>
                <v-card-text>
                    <v-row class="py-3">
                        <v-col cols="12">
                            <span class="text-body-1"
                                >Mueva el marcador
                                <i class="mdi mdi-map-marker text-red"></i> a la
                                ubicación deseada</span
                            >
                        </v-col>
                    </v-row>
                    <div id="google_map"></div>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<style scoped>
#google_map {
    width: 100%;
    height: 500px;
}
</style>
