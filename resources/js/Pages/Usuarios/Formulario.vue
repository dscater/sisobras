<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useUsuarios } from "@/composables/usuarios/useUsuarios";
import { watch, ref, computed, defineEmits } from "vue";
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

const { oUsuario, limpiarUsuario } = useUsuarios();
const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
let form = useForm(oUsuario.value);
watch(
    () => props.open_dialog,
    (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
            form = useForm(oUsuario.value);
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

const listTipos = [
    "GERENTE GENERAL",
    "GERENTE REGIONAL",
    "ADMINISTRADOR DE PERSONAL",
    "ENCARGADO DE OBRA",
];
const listExpedido = [
    { value: "LP", label: "La Paz" },
    { value: "CB", label: "Cochabamba" },
    { value: "SC", label: "Santa Cruz" },
    { value: "CH", label: "Chuquisaca" },
    { value: "OR", label: "Oruro" },
    { value: "PT", label: "Potosi" },
    { value: "TJ", label: "Tarija" },
    { value: "PD", label: "Pando" },
    { value: "BN", label: "Beni" },
];

const foto = ref(null);
function cargaArchivo(e, key) {
    form[key] = null;
    form[key] = e.target.files[0];
}

const tituloDialog = computed(() => {
    return accion.value == 0 ? `Agregar Usuario` : `Editar Usuario`;
});

const enviarFormulario = () => {
    let url =
        form["_method"] == "POST"
            ? route("usuarios.store")
            : route("usuarios.update", form.id);

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
            limpiarUsuario();
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
</script>

<template>
    <v-row justify="center">
        <v-dialog v-model="dialog" width="1024" persistent scrollable>
            <v-card>
                <v-card-title class="border-b bg-blue pa-5">
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
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
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
                                        label="Nombre*"
                                        required
                                        density="compact"
                                        v-model="form.nombre"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.paterno ? false : true
                                        "
                                        :error="
                                            form.errors?.paterno ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.paterno
                                                ? form.errors?.paterno
                                                : ''
                                        "
                                        density="compact"
                                        variant="outlined"
                                        label="Apellido Paterno*"
                                        v-model="form.paterno"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.materno ? false : true
                                        "
                                        :error="
                                            form.errors?.materno ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.materno
                                                ? form.errors?.materno
                                                : ''
                                        "
                                        density="compact"
                                        variant="outlined"
                                        label="Apellido Materno"
                                        v-model="form.materno"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.ci ? false : true
                                        "
                                        :error="form.errors?.ci ? true : false"
                                        :error-messages="
                                            form.errors?.ci
                                                ? form.errors?.ci
                                                : ''
                                        "
                                        density="compact"
                                        variant="outlined"
                                        label="C.I.*"
                                        v-model="form.ci"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-select
                                        :hide-details="
                                            form.errors?.ci_exp ? false : true
                                        "
                                        :error="
                                            form.errors?.ci_exp ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.ci_exp
                                                ? form.errors?.ci_exp
                                                : ''
                                        "
                                        density="compact"
                                        variant="outlined"
                                        clearable
                                        :items="listExpedido"
                                        item-value="value"
                                        item-title="label"
                                        label="Expedido*"
                                        v-model="form.ci_exp"
                                        required
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.dir ? false : true
                                        "
                                        :error="form.errors?.dir ? true : false"
                                        :error-messages="
                                            form.errors?.dir
                                                ? form.errors?.dir
                                                : ''
                                        "
                                        density="compact"
                                        variant="outlined"
                                        label="Dirección*"
                                        v-model="form.dir"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.correo ? false : true
                                        "
                                        :error="
                                            form.errors?.correo ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.correo
                                                ? form.errors?.correo
                                                : ''
                                        "
                                        density="compact"
                                        variant="outlined"
                                        label="Correo"
                                        v-model="form.email"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.fono ? false : true
                                        "
                                        :error="
                                            form.errors?.fono ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.fono
                                                ? form.errors?.fono
                                                : ''
                                        "
                                        density="compact"
                                        variant="outlined"
                                        label="Teléfono/Celular*"
                                        v-model="form.fono"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-select
                                        :hide-details="
                                            form.errors?.tipo ? false : true
                                        "
                                        :error="
                                            form.errors?.tipo ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.tipo
                                                ? form.errors?.tipo
                                                : ''
                                        "
                                        density="compact"
                                        variant="outlined"
                                        clearable
                                        :items="listTipos"
                                        label="Tipo de Usuario*"
                                        v-model="form.tipo"
                                        required
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-file-input
                                        :hide-details="
                                            form.errors?.foto ? false : true
                                        "
                                        :error="
                                            form.errors?.foto ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.foto
                                                ? form.errors?.foto
                                                : ''
                                        "
                                        density="compact"
                                        variant="outlined"
                                        accept="image/png, image/jpeg, image/bmp"
                                        placeholder="Foto"
                                        prepend-icon="mdi-camera"
                                        label="Foto"
                                        @change="cargaArchivo($event, 'foto')"
                                        ref="foto"
                                    ></v-file-input>
                                </v-col>
                                <v-col
                                    cols="12"
                                    sm="6"
                                    md="4"
                                    class="text-center d-flex justify-center align-center"
                                >
                                    <div
                                        class="text-body-2 text-medium-emphasis mr-3"
                                    >
                                        Acceso
                                    </div>
                                    <v-switch
                                        hide-details
                                        color="success"
                                        true-value="1"
                                        false-value="0"
                                        v-model="form.acceso"
                                    >
                                        <template v-slot:label>
                                            <v-chip
                                                class="cursor-pointer"
                                                :color="
                                                    form.acceso == 1
                                                        ? 'success'
                                                        : 'error'
                                                "
                                                :prepend-icon="
                                                    form.acceso == 1
                                                        ? 'mdi-check'
                                                        : 'mdi-lock'
                                                "
                                            >
                                                <span
                                                    v-text="
                                                        form.acceso == 1
                                                            ? 'Habilitado'
                                                            : 'Denegado'
                                                    "
                                                ></span>
                                            </v-chip>
                                        </template>
                                    </v-switch>
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
                        class="bg-blue"
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
