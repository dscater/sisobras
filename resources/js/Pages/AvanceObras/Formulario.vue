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
            if (form.id && form.id != "" && form.id != 0) {
                getAvancesObra(form.obra_id, form.id);
            } else {
                array_avances.value = [];
                avance_seleccionado.value = 0;
                o_obra.value = null;
            }
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
    console.log(array_seleccionados.value);
    form["marcados"] = array_seleccionados.value.join(",");
    form["nro_progreso"] = avance_seleccionado.value;

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

const o_obra = ref(null);
const array_avances = ref([]);
const avance_seleccionado = ref(0);
const array_seleccionados = ref([]);
const listAvances = ref([]);
const ultimo_avance = ref(0);
const total_avances = ref(0);
const porcentaje_avance = ref(0);

const changeObra = (value) => {
    getAvancesObra(value);
};

const getAvancesObra = (obra_id, id = "") => {
    o_obra.value = null;
    array_avances.value = [];
    avance_seleccionado.value = 0;
    if (obra_id && obra_id != "") {
        axios
            .get(route("obras.getAvances", obra_id), {
                params: {
                    id: id,
                },
            })
            .then((response) => {
                o_obra.value = response.data.obra;
                array_avances.value = response.data.array_avances;
                total_avances.value = o_obra.value.categoria.nro_avances;
                ultimo_avance.value = response.data.ultimo_avance;
                listAvances.value = getListAvances(
                    o_obra.value.categoria.nro_avances
                );
                if (id != "" && response.data.avance_obra) {
                    response.data.avance_marcados.forEach((elem) => {
                        array_seleccionados.value.push(elem);
                        avance_seleccionado.value = elem;
                    });
                }
                calculaPorcentaje();
            });
    }
};

const calculaPorcentaje = () => {
    porcentaje_avance.value =
        (avance_seleccionado.value * 100) / total_avances.value;
};

const selectAvance = (nro_avance) => {
    if (verificaAvance(nro_avance)) {
        if (nro_avance != avance_seleccionado.value) {
            avance_seleccionado.value = nro_avance;
        }
        array_seleccionados.value = [];
        for (let i = ultimo_avance.value + 1; i <= nro_avance; i++) {
            array_seleccionados.value.push(i);
        }
        calculaPorcentaje();
    }
};

const verificaAvance = (nro_avance) => {
    let string = String(nro_avance);
    if (!array_avances.value.includes(string)) {
        return true;
    }
    return false;
};

const getListAvances = (total) => {
    let array = [];
    for (let i = 1; i <= total; i++) {
        array.push(i);
    }
    return array;
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
    if (form.id && form.id != "" && form.id != 0) {
        getAvancesObra(form.obra_id, form.id);
    }
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
                                        @update:model-value="changeObra"
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
                                <v-col
                                    cols="12"
                                    v-if="o_obra && listAvances.length > 0"
                                >
                                    <p class="text-caption text-center">
                                        Seleccione el avance
                                    </p>
                                    <div class="contenedor_avances">
                                        <div
                                            class="avance"
                                            v-for="(item, index) in listAvances"
                                            :class="[
                                                avance_seleccionado >= item
                                                    ? 'active'
                                                    : '',
                                                verificaAvance(item) == false
                                                    ? 'marcado'
                                                    : '',
                                            ]"
                                            @click="selectAvance(item)"
                                        >
                                            {{ item }}
                                        </div>
                                        <v-progress-linear
                                            v-model="porcentaje_avance"
                                            color="blue-darken-3"
                                            height="25"
                                        >
                                            <template
                                                v-slot:default="{ value }"
                                            >
                                                <strong
                                                    class="text-white">{{
                                                        Math.ceil(value)
                                                    }}%</strong
                                                >
                                            </template>
                                        </v-progress-linear>
                                    </div>
                                </v-col>
                                <v-col cols="12" v-else
                                    ><h4
                                        class="text-caption medium-emphasis text-center"
                                    >
                                        Debes seleccionar una obra
                                    </h4></v-col
                                >
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
    border-radius: 5px;
    box-shadow: 5px 4px 7px 0px black;
    background-color: var(--secundario_hover);
    width: 100%;
    height: auto;
    min-height: 60px;
    display: flex;
    gap: 10px;
    justify-content: space-around;
    flex-wrap: wrap;
    padding: 7px;
}

.contenedor_avances .avance {
    border-radius: 7px;
    border: solid 2px rgb(134, 134, 134);
    cursor: pointer;
    max-width: 60px;
    background-color: rgb(158, 158, 158);
    flex: 1 0 50px;
    height: 45px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-weight: bold;
    transition: all 0.3s;
}

.contenedor_avances .avance.active,
.contenedor_avances .avance.marcado,
.contenedor_avances .avance:hover {
    background-color: var(--principal);
    border-color: rgb(250, 250, 250);
}

.contenedor_avances .avance.marcado {
    cursor: not-allowed;
    border-style: dashed;
    border-color: rgb(21, 255, 0);
}
</style>
