<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { usePresupuestos } from "@/composables/presupuestos/usePresupuestos";
import { useObras } from "@/composables/obras/useObras";
import { useMaterials } from "@/composables/materials/useMaterials";
import { useOperarios } from "@/composables/operarios/useOperarios";
import { useMaquinarias } from "@/composables/maquinarias/useMaquinarias";
import { useMenu } from "@/composables/useMenu";
import { watch, ref, reactive, computed, onMounted } from "vue";

const { mobile, cambiarUrl } = useMenu();
const {
    oPresupuesto,
    limpiarPresupuesto,
    addMaterial,
    addOperario,
    addMaquinaria,
    deleteMaterial,
    deleteOperario,
    deleteMaquinaria,
    setValuePresupuesto,
    validaTotal,
    getTotales,
} = usePresupuestos();
let form = useForm(oPresupuesto);
const oNuevoMaterial = ref({
    id: 0,
    material_id: null,
    material: null,
    precio: 0,
    cantidad: 0,
    subtotal: 0,
});
const oNuevoOperario = ref({
    id: 0,
    operario_id: null,
    operario: null,
    precio: 0,
    cantidad: 0,
    subtotal: 0,
});
const oNuevoMaquinaria = ref({
    id: 0,
    maquinaria_id: null,
    maquinaria: null,
    precio: 0,
    cantidad: 0,
    subtotal: 0,
});
const total_1 = ref(0);
const total_1_precio = ref(0);
const total_1_cantidad = ref(0);
const total_2 = ref(0);
const total_2_precio = ref(0);
const total_2_cantidad = ref(0);
const total_3 = ref(0);
const total_3_precio = ref(0);
const total_3_cantidad = ref(0);
const oMaterial = ref({
    id: null,
    nombre: "",
    descripcion: "",
    fecha_registro: "",
});
const oOperario = ref({
    id: null,
    nombre: "",
    paterno: "",
    materno: "",
    ci: "",
    ci_exp: "",
    fono: "",
    tipo: "",
});
const oMaquinaria = ref({
    id: null,
    nombre: "",
    descripcion: "",
    fecha_registro: "",
});

const { flash, auth } = usePage().props;
const user = ref(auth.user);
const { getObras } = useObras();
const { getMaterials } = useMaterials();
const { getOperarios } = useOperarios();
const { getMaquinarias } = useMaquinarias();

const listObras = ref([]);
const listMaterials = ref([]);
const listOperarios = ref([]);
const listMaquinarias = ref([]);

const tituloDialog = computed(() => {
    return oPresupuesto.id == 0 ? `Agregar Presupuesto` : `Editar Presupuesto`;
});

const enviarFormulario = () => {
    // form = useForm(oPresupuesto);
    form.total_precio = oPresupuesto.total_precio;
    form.total_cantidad = oPresupuesto.total_cantidad;
    form.total = oPresupuesto.total;
    form.materials = oPresupuesto.materials;
    form.operarios = oPresupuesto.operarios;
    form.maquinarias = oPresupuesto.maquinarias;
    form.eliminados_materials = oPresupuesto.eliminados_materials;
    form.eliminados_operarios = oPresupuesto.eliminados_operarios;
    form.eliminados_maquinarias = oPresupuesto.eliminados_maquinarias;
    console.log(form);
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
            console.log(form.errors);
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

const asignaPrespuesto = (value) => {
    setValuePresupuesto(value);
};

const cargarListas = async () => {
    if (form.id && form.id != "") {
        listObras.value = await getObras({
            order: "desc",
            sin_presupuesto: true,
            id: form.obra_id,
        });
    } else {
        listObras.value = await getObras({
            order: "desc",
            sin_presupuesto: true,
        });
    }
    listMaterials.value = await getMaterials({
        order: "desc",
    });
    listOperarios.value = await getOperarios({
        order: "desc",
    });
    listMaquinarias.value = await getMaquinarias({
        order: "desc",
    });
};

// MATERIALES
const getMaterial = (value) => {
    oMaterial.value = {
        id: null,
        nombre: "",
        descripcion: "",
        fecha_registro: "",
    };
    if (value) {
        let material = listMaterials.value.filter(
            (elem) => elem.id == value
        )[0];
        if (material) {
            oMaterial.value = material;
            oNuevoMaterial.value.material_id = value;
            oNuevoMaterial.value.material = oMaterial.value;
        }
    }
};

const agregarMaterial = () => {
    if (oNuevoMaterial.value.material_id) {
        if (
            oNuevoMaterial.value.precio > 0 &&
            oNuevoMaterial.value.cantidad > 0
        ) {
            oNuevoMaterial.value.subtotal =
                parseFloat(oNuevoMaterial.value.precio) *
                parseFloat(oNuevoMaterial.value.cantidad);
            if (validaTotal(oNuevoMaterial.value.subtotal)) {
                oNuevoMaterial.value.subtotal = parseFloat(
                    oNuevoMaterial.value.subtotal
                ).toFixed(2);
                addMaterial({
                    id: 0,
                    material_id: oNuevoMaterial.value.material_id,
                    material: oNuevoMaterial.value.material,
                    precio: oNuevoMaterial.value.precio,
                    cantidad: oNuevoMaterial.value.cantidad,
                    subtotal: oNuevoMaterial.value.subtotal,
                });
                let totales = getTotales();
                total_1.value = totales["total1"];
                total_1_precio.value = totales["total1_precio"];
                total_1_cantidad.value = totales["total1_cantidad"];
                limpiarMaterial();
            }
        } else {
            Swal.fire({
                icon: "info",
                title: "Atención",
                text: `Debes ingresar un precio y cantidad mayor a 0`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
        }
    } else {
        Swal.fire({
            icon: "info",
            title: "Atención",
            text: `Debes seleccionar un material`,
            confirmButtonColor: "#3085d6",
            confirmButtonText: `Aceptar`,
        });
    }
};

const limpiarMaterial = () => {
    oNuevoMaterial.value.id = "";
    oNuevoMaterial.value.material_id = "";
    oNuevoMaterial.value.material = "";
    oNuevoMaterial.value.precio = 0;
    oNuevoMaterial.value.cantidad = 0;
    oNuevoMaterial.value.subtotal = 0;
};
const eliminarMaterial = (index, id) => {
    deleteMaterial(index, id);
    let totales = getTotales();
    total_1.value = totales["total1"];
    total_1_precio.value = totales["total1_precio"];
    total_1_cantidad.value = totales["total1_cantidad"];
};

// OPERARIOS
const getOperario = (value) => {
    oOperario.value = {
        id: null,
        nombre: "",
        paterno: "",
        materno: "",
        ci: "",
        ci_exp: "",
        fono: "",
        tipo: "",
    };
    if (value) {
        let operario = listOperarios.value.filter(
            (elem) => elem.id == value
        )[0];
        if (operario) {
            oOperario.value = operario;
            oNuevoOperario.value.operario_id = value;
            oNuevoOperario.value.operario = oOperario.value;
        }
    }
};

const agregarOperario = () => {
    if (oNuevoOperario.value.operario_id) {
        if (
            oNuevoOperario.value.precio > 0 &&
            oNuevoOperario.value.cantidad > 0
        ) {
            oNuevoOperario.value.subtotal =
                parseFloat(oNuevoOperario.value.precio) *
                parseFloat(oNuevoOperario.value.cantidad);
            if (validaTotal(oNuevoOperario.value.subtotal)) {
                oNuevoOperario.value.subtotal = parseFloat(
                    oNuevoOperario.value.subtotal
                ).toFixed(2);
                addOperario({
                    id: 0,
                    operario_id: oNuevoOperario.value.operario_id,
                    operario: oNuevoOperario.value.operario,
                    precio: oNuevoOperario.value.precio,
                    cantidad: oNuevoOperario.value.cantidad,
                    subtotal: oNuevoOperario.value.subtotal,
                });
                let totales = getTotales();
                total_2.value = totales["total2"];
                total_2_precio.value = totales["total2_precio"];
                total_2_cantidad.value = totales["total2_cantidad"];
                limpiarOperario();
            }
        } else {
            Swal.fire({
                icon: "info",
                title: "Atención",
                text: `Debes ingresar un precio y cantidad mayor a 0`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
        }
    } else {
        Swal.fire({
            icon: "info",
            title: "Atención",
            text: `Debes seleccionar un Operario/Personal`,
            confirmButtonColor: "#3085d6",
            confirmButtonText: `Aceptar`,
        });
    }
};

const limpiarOperario = () => {
    oNuevoOperario.value.id = "";
    oNuevoOperario.value.operario_id = "";
    oNuevoOperario.value.operario = "";
    oNuevoOperario.value.precio = 0;
    oNuevoOperario.value.cantidad = 0;
    oNuevoOperario.value.subtotal = 0;
};
const eliminarOperario = (index, id) => {
    deleteOperario(index, id);
    let totales = getTotales();
    total_2.value = totales["total2"];
    total_2_precio.value = totales["total2_precio"];
    total_2_cantidad.value = totales["total2_cantidad"];
};

// MAQUINARIAS
const getMaquinaria = (value) => {
    oMaquinaria.value = {
        id: null,
        nombre: "",
        descripcion: "",
    };
    if (value) {
        let maquinaria = listMaquinarias.value.filter(
            (elem) => elem.id == value
        )[0];
        if (maquinaria) {
            oMaquinaria.value = maquinaria;
            oNuevoMaquinaria.value.maquinaria_id = value;
            oNuevoMaquinaria.value.maquinaria = oMaquinaria.value;
        }
    }
};

const agregarMaquinaria = () => {
    if (oNuevoMaquinaria.value.maquinaria_id) {
        if (
            oNuevoMaquinaria.value.precio > 0 &&
            oNuevoMaquinaria.value.cantidad > 0
        ) {
            oNuevoMaquinaria.value.subtotal =
                parseFloat(oNuevoMaquinaria.value.precio) *
                parseFloat(oNuevoMaquinaria.value.cantidad);
            if (validaTotal(oNuevoMaquinaria.value.subtotal)) {
                oNuevoMaquinaria.value.subtotal = parseFloat(
                    oNuevoMaquinaria.value.subtotal
                ).toFixed(2);
                addMaquinaria({
                    id: 0,
                    maquinaria_id: oNuevoMaquinaria.value.maquinaria_id,
                    maquinaria: oNuevoMaquinaria.value.maquinaria,
                    precio: oNuevoMaquinaria.value.precio,
                    cantidad: oNuevoMaquinaria.value.cantidad,
                    subtotal: oNuevoMaquinaria.value.subtotal,
                });
                let totales = getTotales();
                total_3.value = totales["total3"];
                total_3_precio.value = totales["total3_precio"];
                total_3_cantidad.value = totales["total3_cantidad"];
                limpiarMaquinaria();
            }
        } else {
            Swal.fire({
                icon: "info",
                title: "Atención",
                text: `Debes ingresar un precio y cantidad mayor a 0`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
        }
    } else {
        Swal.fire({
            icon: "info",
            title: "Atención",
            text: `Debes seleccionar una Maquinaria`,
            confirmButtonColor: "#3085d6",
            confirmButtonText: `Aceptar`,
        });
    }
};

const limpiarMaquinaria = () => {
    oNuevoMaquinaria.value.id = "";
    oNuevoMaquinaria.value.maquinaria_id = "";
    oNuevoMaquinaria.value.maquinaria = "";
    oNuevoMaquinaria.value.precio = 0;
    oNuevoMaquinaria.value.cantidad = 0;
    oNuevoMaquinaria.value.subtotal = 0;
};
const eliminarMaquinaria = (index, id) => {
    deleteMaquinaria(index, id);
    let totales = getTotales();
    total_3.value = totales["total3"];
    total_3_precio.value = totales["total3_precio"];
    total_3_cantidad.value = totales["total3_cantidad"];
};

onMounted(() => {
    if (form.id && form.id != "") {
        // edit
        let totales = getTotales();
        total_1.value = totales["total1"];
        total_1_precio.value = totales["total1_precio"];
        total_1_cantidad.value = totales["total1_cantidad"];
        total_2.value = totales["total2"];
        total_2_precio.value = totales["total2_precio"];
        total_2_cantidad.value = totales["total2_cantidad"];
        total_3.value = totales["total3"];
        total_3_precio.value = totales["total3_precio"];
        total_3_cantidad.value = totales["total3_cantidad"];
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
                <v-btn icon="mdi-content-save" color="primary"></v-btn>
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
                    color="primary"
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
                <v-card-title class="border-b bg-primary pa-5">
                    <v-icon
                        :icon="form.id == 0 ? 'mdi-plus' : 'mdi-pencil'"
                    ></v-icon>
                    <span class="text-h5" v-html="tituloDialog"></span>
                </v-card-title>
                <v-card-text class="pb-0">
                    <v-container>
                        <form @submit.prevent="enviarFormulario">
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
                                    <v-text-field
                                        type="number"
                                        step="0.01"
                                        :hide-details="
                                            form.errors?.presupuesto
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.presupuesto
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.presupuesto
                                                ? form.errors?.presupuesto
                                                : ''
                                        "
                                        @update:model-value="asignaPrespuesto"
                                        variant="outlined"
                                        label="Presupuesto Asignado*"
                                        rows="1"
                                        auto-grow
                                        density="compact"
                                        v-model="form.presupuesto"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </form>
                        <v-row>
                            <v-col cols="12" class="text-center pb-0">
                                <span class="text-subtitle-1">MATERIALES</span>
                            </v-col>
                            <v-col cols="12" class="pt-0">
                                <v-table>
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Material</th>
                                            <th class="text-center">
                                                Precio Unidad
                                            </th>
                                            <th class="text-center">
                                                Cantidad
                                            </th>
                                            <th class="text-center">
                                                Subtotal
                                            </th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                item, index
                                            ) in oPresupuesto.materials"
                                        >
                                            <td>
                                                {{ index + 1 }}
                                            </td>
                                            <td>{{ item.material.nombre }}</td>
                                            <td class="text-center">
                                                {{ item.precio }}
                                            </td>
                                            <td class="text-center">
                                                {{ item.cantidad }}
                                            </td>
                                            <td class="text-center">
                                                {{ item.subtotal }}
                                            </td>
                                            <td>
                                                <v-btn
                                                    icon="mdi-close"
                                                    density="compact"
                                                    class="bg-red"
                                                    @click="
                                                        eliminarMaterial(
                                                            index,
                                                            item.id
                                                        )
                                                    "
                                                ></v-btn>
                                            </td>
                                        </tr>
                                        <tr class="font-weight-bold border-top">
                                            <td colspan="2" class="text-right">
                                                TOTAL
                                            </td>
                                            <td class="text-center">
                                                {{ total_1_precio }}
                                            </td>
                                            <td class="text-center">
                                                {{ total_1_cantidad }}
                                            </td>
                                            <td class="text-center">
                                                {{ total_1 }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </v-table>
                            </v-col>
                            <v-col cols="12" class="text-center pb-0">
                                <span class="text-subtitle-1"
                                    >OPERARIOS/PERSONAL</span
                                >
                            </v-col>
                            <v-col cols="12" class="pt-0">
                                <v-table>
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Operario/personal</th>
                                            <th class="text-center">
                                                Precio Unidad
                                            </th>
                                            <th class="text-center">
                                                Cantidad
                                            </th>
                                            <th class="text-center">
                                                Subtotal
                                            </th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                item, index
                                            ) in oPresupuesto.operarios"
                                        >
                                            <td>
                                                {{ index + 1 }}
                                            </td>
                                            <td>
                                                {{ item.operario.full_name }}
                                            </td>
                                            <td class="text-center">
                                                {{ item.precio }}
                                            </td>
                                            <td class="text-center">
                                                {{ item.cantidad }}
                                            </td>
                                            <td class="text-center">
                                                {{ item.subtotal }}
                                            </td>
                                            <td>
                                                <v-btn
                                                    icon="mdi-close"
                                                    density="compact"
                                                    class="bg-red"
                                                    @click="
                                                        eliminarOperario(
                                                            index,
                                                            item.id
                                                        )
                                                    "
                                                ></v-btn>
                                            </td>
                                        </tr>
                                        <tr class="font-weight-bold border-top">
                                            <td colspan="2" class="text-right">
                                                TOTAL
                                            </td>
                                            <td class="text-center">
                                                {{ total_2_precio }}
                                            </td>
                                            <td class="text-center">
                                                {{ total_2_cantidad }}
                                            </td>
                                            <td class="text-center">
                                                {{ total_2 }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </v-table>
                            </v-col>
                            <v-col cols="12" class="text-center pb-0">
                                <span class="text-subtitle-1">MAQUINARIAS</span>
                            </v-col>
                            <v-col cols="12" class="pt-0">
                                <v-table>
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Maquinaria</th>
                                            <th class="text-center">
                                                Precio Unidad
                                            </th>
                                            <th class="text-center">
                                                Cantidad
                                            </th>
                                            <th class="text-center">
                                                Subtotal
                                            </th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                item, index
                                            ) in oPresupuesto.maquinarias"
                                        >
                                            <td>
                                                {{ index + 1 }}
                                            </td>
                                            <td>
                                                {{ item.maquinaria.nombre }}
                                            </td>
                                            <td class="text-center">
                                                {{ item.precio }}
                                            </td>
                                            <td class="text-center">
                                                {{ item.cantidad }}
                                            </td>
                                            <td class="text-center">
                                                {{ item.subtotal }}
                                            </td>
                                            <td>
                                                <v-btn
                                                    icon="mdi-close"
                                                    density="compact"
                                                    class="bg-red"
                                                    @click="
                                                        eliminarMaquinaria(
                                                            index,
                                                            item.id
                                                        )
                                                    "
                                                ></v-btn>
                                            </td>
                                        </tr>
                                        <tr class="font-weight-bold border-top">
                                            <td colspan="2" class="text-right">
                                                TOTAL
                                            </td>
                                            <td class="text-center">
                                                {{ total_3_precio }}
                                            </td>
                                            <td class="text-center">
                                                {{ total_3_cantidad }}
                                            </td>
                                            <td class="text-center">
                                                {{ total_3 }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </v-table>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
            </v-card>
            <v-card class="my-3">
                <v-card-text>
                    <v-row>
                        <v-col cols="4" class="text-center">
                            <span class="text-body-1">Precio(s): </span
                            ><v-chip
                                class="font-weight-bold text-body-1"
                                color="primary"
                                variant="flat"
                                >{{ oPresupuesto.total_precio }}</v-chip
                            >
                        </v-col>

                        <v-col cols="4" class="text-center">
                            <span class="text-body-1">Cantidad(es): </span
                            ><v-chip
                                class="font-weight-bold text-body-1"
                                color="primary"
                                variant="flat"
                                >{{ oPresupuesto.total_cantidad }}</v-chip
                            >
                        </v-col>
                        <v-col cols="4" class="text-center">
                            <span class="text-body-1">Total Usado: </span
                            ><v-chip
                                class="font-weight-bold text-body-1"
                                color="primary"
                                variant="flat"
                                >{{ oPresupuesto.total }}</v-chip
                            >
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-col>
        <v-col
            cols="12"
            sm="12"
            md="6"
            xl="6"
            v-if="oPresupuesto.presupuesto && oPresupuesto.presupuesto > 0"
        >
            <v-card>
                <v-card-title class="bg-primary pa-5">
                    <span class="text-h5">Materiales</span>
                </v-card-title>
                <v-card-text class="pb-0">
                    <v-row class="pt-3">
                        <v-col cols="12" sm="12" md="12" xl="6">
                            <v-select
                                clearable
                                variant="outlined"
                                label="Seleccionar Material"
                                :items="listMaterials"
                                item-value="id"
                                item-title="nombre"
                                required
                                density="compact"
                                v-model="oNuevoMaterial.material_id"
                                @update:modelValue="getMaterial"
                            ></v-select>
                        </v-col>
                        <v-col cols="12" sm="12" md="12" xl="6">
                            <v-text-field
                                type="number"
                                step="0.01"
                                variant="outlined"
                                label="Precio Unidad"
                                rows="1"
                                auto-grow
                                density="compact"
                                v-model="oNuevoMaterial.precio"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="12" md="12" xl="6">
                            <v-text-field
                                type="number"
                                step="0.01"
                                variant="outlined"
                                label="Cantidad"
                                rows="1"
                                auto-grow
                                density="compact"
                                v-model="oNuevoMaterial.cantidad"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="12" md="12" xl="6">
                            <v-btn
                                :disabled="
                                    !form.presupuesto &&
                                    form.presupuesto == '' &&
                                    form.presupuesto <= 0
                                "
                                prepend-icon="mdi-plus"
                                color="primary"
                                variant="outlined"
                                class="w-100"
                                @click="agregarMaterial"
                            >
                                AGREGAR</v-btn
                            >
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
            <v-card class="my-3">
                <v-card-title class="bg-primary pa-5">
                    <span class="text-h5">Operarios/Personal</span>
                </v-card-title>
                <v-card-text class="pb-0">
                    <v-row class="pt-3">
                        <v-col cols="12" sm="12" md="12" xl="6">
                            <v-select
                                clearable
                                variant="outlined"
                                label="Seleccionar Operario/Personal"
                                :items="listOperarios"
                                item-value="id"
                                item-title="full_name"
                                required
                                density="compact"
                                v-model="oNuevoOperario.operario_id"
                                @update:modelValue="getOperario"
                            ></v-select>
                        </v-col>
                        <v-col cols="12" sm="12" md="12" xl="6">
                            <v-text-field
                                type="number"
                                step="0.01"
                                variant="outlined"
                                label="Precio Unidad"
                                rows="1"
                                auto-grow
                                density="compact"
                                v-model="oNuevoOperario.precio"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="12" md="12" xl="6">
                            <v-text-field
                                type="number"
                                step="0.01"
                                variant="outlined"
                                label="Cantidad"
                                rows="1"
                                auto-grow
                                density="compact"
                                v-model="oNuevoOperario.cantidad"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="12" md="12" xl="6">
                            <v-btn
                                :disabled="
                                    !form.presupuesto &&
                                    form.presupuesto == '' &&
                                    form.presupuesto <= 0
                                "
                                prepend-icon="mdi-plus"
                                color="primary"
                                variant="outlined"
                                class="w-100"
                                @click="agregarOperario"
                            >
                                AGREGAR</v-btn
                            >
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
            <v-card class="my-3">
                <v-card-title class="bg-primary pa-5">
                    <span class="text-h5">Maquinarias</span>
                </v-card-title>
                <v-card-text class="pb-0">
                    <v-row class="pt-3">
                        <v-col cols="12" sm="12" md="12" xl="6">
                            <v-select
                                clearable
                                variant="outlined"
                                label="Seleccionar Maquinaria"
                                :items="listMaquinarias"
                                item-value="id"
                                item-title="nombre"
                                required
                                density="compact"
                                v-model="oNuevoMaquinaria.maquinaria_id"
                                @update:modelValue="getMaquinaria"
                            ></v-select>
                        </v-col>
                        <v-col cols="12" sm="12" md="12" xl="6">
                            <v-text-field
                                type="number"
                                step="0.01"
                                variant="outlined"
                                label="Precio Unidad"
                                rows="1"
                                auto-grow
                                density="compact"
                                v-model="oNuevoMaquinaria.precio"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="12" md="12" xl="6">
                            <v-text-field
                                type="number"
                                step="0.01"
                                variant="outlined"
                                label="Cantidad"
                                rows="1"
                                auto-grow
                                density="compact"
                                v-model="oNuevoMaquinaria.cantidad"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="12" md="12" xl="6">
                            <v-btn
                                :disabled="
                                    !form.presupuesto &&
                                    form.presupuesto == '' &&
                                    form.presupuesto <= 0
                                "
                                prepend-icon="mdi-plus"
                                color="primary"
                                variant="outlined"
                                class="w-100"
                                @click="agregarMaquinaria"
                            >
                                AGREGAR</v-btn
                            >
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="12" sm="12" md="6" xl="6" v-else>
            <v-card>
                <v-card-text>
                    <v-row>
                        <v-col cols="12" class="pa-10">
                            <span class="text-subtitle-2"
                                >DEBES INGRESAR EL MONTO DE
                                <span class="font-weight-bold"
                                    >PRESUPUESTO ASIGNADO</span
                                ></span
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
