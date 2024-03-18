<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useAvanceObras } from "@/composables/avance_obras/useAvanceObras";
import { useObras } from "@/composables/obras/useObras";
import { watch, ref, computed, defineEmits, onMounted } from "vue";
const props = defineProps({
    open_dialog: {
        type: Boolean,
        default: false,
    },
    accion_dialog: {
        type: Number,
        default: 0,
    },
});

const { oAvanceObra, limpiarAvanceObra } = useAvanceObras();
const { getObras } = useObras();
const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
const listObras = ref([]);
let form = useForm(oAvanceObra.value);

const cargarListas = async () => {
    listObras.value = await getObras();
};

watch(
    () => props.open_dialog,
    (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
            form = useForm(oAvanceObra.value);
        }
    }
);
watch(
    () => props.accion_dialog,
    (newValue) => {
        accion.value = newValue;
    }
);

const { flash } = usePage().props;

const tituloDialog = computed(() => {
    return accion.value == 0
        ? `Agregar Avance de Obra`
        : `Editar Avance de Obra`;
});

const enviarFormulario = () => {
    let url =
        form["_method"] == "POST"
            ? route("avance_obras.store")
            : route("avance_obras.update", form.id);

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
            limpiarAvanceObra();
            emits("envio-formulario");
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

const emits = defineEmits(["cerrar-dialog", "envio-formulario"]);

watch(dialog, (newVal) => {
    if (!newVal) {
        emits("cerrar-dialog");
    }
});

const cerrarDialog = () => {
    dialog.value = false;
};

onMounted(() => {
    cargarListas();
});
</script>

<template>
    <v-row justify="center">
        <v-dialog v-model="dialog" width="1024" persistent scrollable>
            <v-card>
                <v-card-title class="border-b bg-primary pa-5">
                    <v-icon
                        icon="mdi-close"
                        class="float-right"
                        @click="cerrarDialog"
                    ></v-icon>

                    <v-icon
                        :icon="accion == 0 ? 'mdi-plus' : 'mdi-pencil'"
                    ></v-icon>
                    <span class="text-h5" v-html="tituloDialog"></span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <form>
                            <v-row>
                                <v-col cols="12" sm="12" md="12" xl="6">
                                    <v-select
                                        :hide-details="
                                            form.errors?.obra_id ? false : true
                                        "
                                        :error="
                                            form.errors?.obra_id ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.obra_id
                                                ? form.errors?.obra_id
                                                : ''
                                        "
                                        clearable
                                        variant="outlined"
                                        label="Seleccionar Obra*"
                                        :items="listObras"
                                        item-value="id"
                                        item-title="nombre"
                                        required
                                        density="compact"
                                        v-model="form.obra_id"
                                    ></v-select>
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
                                        label="Descripción*"
                                        rows="1"
                                        auto-grow
                                        density="compact"
                                        v-model="form.descripcion"
                                    ></v-textarea>
                                </v-col>
                                <v-col cols="12" sm="12" md="12" xl="6">
                                    <v-textarea
                                        :hide-details="
                                            form.errors?.observacion
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.observacion
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.observacion
                                                ? form.errors?.observacion
                                                : ''
                                        "
                                        variant="outlined"
                                        label="Observación"
                                        rows="1"
                                        auto-grow
                                        density="compact"
                                        v-model="form.observacion"
                                    ></v-textarea>
                                </v-col>
                                <v-col cols="12">
                                    <div class="contenedor_avances">
                                        <div class="avance">1</div>
                                        <div class="avance">2</div>
                                        <div class="avance">3</div>
                                        <div class="avance">4</div>
                                        <div class="avance">5</div>
                                        <div class="avance">6</div>
                                        <div class="avance">7</div>
                                        <div class="avance">8</div>
                                        <div class="avance">9</div>
                                        <div class="avance">10</div>
                                    </div>
                                </v-col>
                            </v-row>
                        </form>
                    </v-container>
                </v-card-text>
                <v-card-actions class="border-t">
                    <v-spacer></v-spacer>
                    <v-btn
                        color="grey-darken-4"
                        variant="text"
                        @click="cerrarDialog"
                    >
                        Cancelar
                    </v-btn>
                    <v-btn
                        class="bg-primary"
                        prepend-icon="mdi-content-save"
                        @click="enviarFormulario"
                    >
                        Guardar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<style scoped>
.contenedor_avances {
    background-color: antiquewhite;
    width: 100%;
    height: 40px;
    display: flex;
    gap: 20px;
    justify-content: space-around;
    flex-wrap: wrap;
}

.contenedor_avances .avance {
    cursor: pointer;
    max-width: 60px;
    background-color: blue;
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-weight: bold;
}
</style>
