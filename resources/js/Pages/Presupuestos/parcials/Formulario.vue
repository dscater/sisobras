<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { usePresupuestos } from "@/composables/presupuestos/usePresupuestos";
import { useUsuarios } from "@/composables/usuarios/useUsuarios";
import { useCategorias } from "@/composables/categorias/useCategorias";
import { useMenu } from "@/composables/useMenu";
import { watch, ref, reactive, computed, onMounted } from "vue";

const { mobile, cambiarUrl } = useMenu();
const { oPresupuesto, limpiarPresupuesto } = usePresupuestos();
let form = useForm(oPresupuesto);

const { flash, auth } = usePage().props;
const user = ref(auth.user);
const { getUsuariosByTipo } = useUsuarios();
const { getCategorias } = useCategorias();

const listObras = ref([]);
const listUsuariosEncargado = ref([]);
const listCategorias = ref([]);

const tituloDialog = computed(() => {
    return oPresupuesto.id == 0 ? `Agregar Presupuesto` : `Editar Presupuesto`;
});

const enviarFormulario = () => {
    let url =
        form["_method"] == "POST"
            ? route("presupuestos.store")
            : route("presupuestos.update", form.id);

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
            limpiarPresupuesto();
            cambiarUrl(route("presupuestos.index"));
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
    listObras.value = await getUsuariosByTipo({
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

onMounted(() => {
    if (form.id && form.id != "") {
        // edit
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
                    @click="cambiarUrl(route('presupuestos.index'))"
                ></v-btn>
                <v-btn icon="mdi-content-save" color="blue"></v-btn>
            </template>
            <template v-if="!mobile">
                <v-btn
                    prepend-icon="mdi-arrow-left"
                    class="mr-2"
                    @click="cambiarUrl(route('presupuestos.index'))"
                >
                    Volver</v-btn
                >
                <v-btn
                    :prepend-icon="
                        oPresupuesto.id != 0 ? 'mdi-sync' : 'mdi-content-save'
                    "
                    color="blue"
                    @click="enviarFormulario"
                >
                    <span
                        v-text="
                            oPresupuesto.id != 0
                                ? 'Actualizar Presupuesto'
                                : 'Guardar Presupuesto'
                        "
                    ></span
                ></v-btn>
            </template>
        </v-col>
    </v-row>
    <v-row>
        <v-col cols="12" sm="12" md="6" xl="6">
            <v-card>
                <v-card-title class="border-b bg-blue pa-5">
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
                                                ? form.errors
                                                      ?.obra_id
                                                : ''
                                        "
                                        clearable
                                        variant="outlined"
                                        label="Seleccionar Obra*"
                                        :items="listObras"
                                        item-value="id"
                                        item-title="full_name"
                                        required
                                        density="compact"
                                        v-model="form.obra_id"
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" xl="6">
                                    <v-textarea
                                        :hide-details="
                                            form.errors?.presupuesto ? false : true
                                        "
                                        :error="
                                            form.errors?.presupuesto ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.presupuesto
                                                ? form.errors?.presupuesto
                                                : ''
                                        "
                                        variant="outlined"
                                        label="Nombre de la Presupuesto*"
                                        rows="1"
                                        auto-grow
                                        density="compact"
                                        v-model="form.presupuesto"
                                    ></v-textarea>
                                </v-col>
                            </v-row>
                        </form>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="12" sm="12" md="6" xl="6">
            <v-card>
                <v-card-title class="bg-blue pa-5">
                    <span class="text-h5">Ubicación de la Presupuesto</span>
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
