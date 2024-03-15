import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import { onMounted, reactive } from "vue";
import { usePage } from "@inertiajs/vue3";

const oPresupuesto = reactive({
    id: 0,
    obra_id: "",
    presupuesto: "",
    total_precio: 0,
    total_cantidad: 0,
    total: 0,
    fecha_registro: "",
    materials: reactive([]),
    operarios: reactive([]),
    maquinarias: reactive([]),
    eliminados_materials: reactive([]),
    eliminados_operarios: reactive([]),
    eliminados_maquinarias: reactive([]),
    _method: "POST",
});

export const usePresupuestos = () => {
    const { flash } = usePage().props;
    const getPresupuestos = async () => {
        try {
            const response = await axios.get(route("presupuestos.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.presupuestos;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const getPresupuestosApi = async (data) => {
        try {
            const response = await axios.get(
                route("presupuestos.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.presupuestos;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };
    const savePresupuesto = async (data) => {
        try {
            const response = await axios.post(
                route("presupuestos.store", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            return response.data;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const deletePresupuesto = async (id) => {
        try {
            const response = await axios.delete(
                route("presupuestos.destroy", id),
                {
                    headers: { Accept: "application/json" },
                }
            );
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            return response.data;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const setPresupuesto = (item = null) => {
        if (item) {
            oPresupuesto.id = item.id;
            oPresupuesto.obra_id = item.obra_id;
            oPresupuesto.presupuesto = item.presupuesto;
            oPresupuesto.total_precio = item.total_precio;
            oPresupuesto.total_cantidad = item.total_cantidad;
            oPresupuesto.total = item.total;
            oPresupuesto.fecha_registro = item.fecha_registro;
            oPresupuesto.materials = reactive([...item.materials]);
            oPresupuesto.operarios = reactive([...item.operarios]);
            oPresupuesto.maquinarias = reactive([...item.maquinarias]);
            oPresupuesto.eliminados_materials = reactive([]);
            oPresupuesto.eliminados_operarios = reactive([]);
            oPresupuesto.eliminados_maquinarias = reactive([]);
            oPresupuesto._method = "PUT";
            return oPresupuesto;
        }
        return false;
    };

    const limpiarPresupuesto = () => {
        oPresupuesto.id = 0;
        oPresupuesto.obra_id = "";
        oPresupuesto.presupuesto = "";
        oPresupuesto.total_precio = 0;
        oPresupuesto.total_cantidad = 0;
        oPresupuesto.total = 0;
        oPresupuesto.fecha_registro = "";
        oPresupuesto.materials = reactive([]);
        oPresupuesto.operarios = reactive([]);
        oPresupuesto.maquinarias = reactive([]);
        oPresupuesto.eliminados_materials = reactive([]);
        oPresupuesto.eliminados_operarios = reactive([]);
        oPresupuesto.eliminados_maquinarias = reactive([]);
        oPresupuesto._method = "POST";
    };

    const addMaterial = (item = null) => {
        if (item) {
            oPresupuesto.materials.push(item);
            return true;
        }
        return false;
    };

    const deleteMaterial = (index, id = 0) => {
        if (oPresupuesto.materials[index]) {
            if (id != 0) {
                oPresupuesto.eliminados_materials.push(id);
            }
            oPresupuesto.materials.splice(index, 1);
            return true;
        }
        return false;
    };

    const addOperario = (item = null) => {
        if (item) {
            oPresupuesto.operarios.push(item);
            return true;
        }
        return false;
    };

    const deleteOperario = (index, id = 0) => {
        if (oPresupuesto.operarios[index]) {
            if (id != 0) {
                oPresupuesto.eliminados_operarios.push(id);
            }
            oPresupuesto.operarios.splice(index, 1);
            return true;
        }
        return false;
    };

    const addMaquinaria = (item = null) => {
        if (item) {
            oPresupuesto.maquinarias.push(item);
            return true;
        }
        return false;
    };

    const deleteMaquinaria = (index, id = 0) => {
        if (oPresupuesto.maquinarias[index]) {
            if (id != 0) {
                oPresupuesto.eliminados_maquinarias.push(id);
            }
            oPresupuesto.maquinarias.splice(index, 1);
            return true;
        }
        return false;
    };

    const getTotales = () => {
        let total1 = 0;
        let total1_precio = 0;
        let total1_cantidad = 0;
        let total2 = 0;
        let total2_precio = 0;
        let total2_cantidad = 0;
        let total3 = 0;
        let total3_precio = 0;
        let total3_cantidad = 0;

        oPresupuesto.total_precio = 0;
        oPresupuesto.total_cantidad = 0;
        oPresupuesto.total = 0;
        if (oPresupuesto.materials.length > 0) {
            oPresupuesto.materials.forEach((elem) => {
                total1 = total1 + parseFloat(elem.subtotal);
            });
            oPresupuesto.materials.forEach((elem) => {
                total1_precio = total1_precio + parseFloat(elem.precio);
            });
            oPresupuesto.materials.forEach((elem) => {
                total1_cantidad = total1_cantidad + parseFloat(elem.cantidad);
            });
            oPresupuesto.total_precio += total1_precio;
            oPresupuesto.total_cantidad += total1_cantidad;
            oPresupuesto.total += total1;
        }
        if (oPresupuesto.operarios.length > 0) {
            oPresupuesto.operarios.forEach((elem) => {
                total2 = total2 + parseFloat(elem.subtotal);
            });
            oPresupuesto.operarios.forEach((elem) => {
                total2_precio = total2_precio + parseFloat(elem.precio);
            });
            oPresupuesto.operarios.forEach((elem) => {
                total2_cantidad = total2_cantidad + parseFloat(elem.cantidad);
            });
            oPresupuesto.total_precio += total2_precio;
            oPresupuesto.total_cantidad += total2_cantidad;
            oPresupuesto.total += total2;
        }
        if (oPresupuesto.maquinarias.length > 0) {
            oPresupuesto.maquinarias.forEach((elem) => {
                total3 = total3 + parseFloat(elem.subtotal);
            });
            oPresupuesto.maquinarias.forEach((elem) => {
                total3_precio = total3_precio + parseFloat(elem.precio);
            });
            oPresupuesto.maquinarias.forEach((elem) => {
                total3_cantidad = total3_cantidad + parseFloat(elem.cantidad);
            });
            oPresupuesto.total_precio += total3_precio;
            oPresupuesto.total_cantidad += total3_cantidad;
            oPresupuesto.total += total3;
        }

        return {
            total1,
            total1_precio,
            total1_cantidad,
            total2,
            total2_precio,
            total2_cantidad,
            total3,
            total3_precio,
            total3_cantidad,
        };
    };

    const validaTotal = (valor) => {
        let total = parseFloat(oPresupuesto.total) + parseFloat(valor);
        if (total > oPresupuesto.presupuesto) {
            Swal.fire({
                icon: "info",
                title: "Atención",
                text: `El monto no puede ser mayor al Presupuesto Asignado`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            return false;
        }
        return true;
    };

    const setValuePresupuesto = (valor) => {
        oPresupuesto.presupuesto = valor;
    };

    onMounted(() => {});

    return {
        oPresupuesto,
        getPresupuestos,
        getPresupuestosApi,
        savePresupuesto,
        deletePresupuesto,
        setPresupuesto,
        limpiarPresupuesto,
        addMaterial,
        addOperario,
        addMaquinaria,
        deleteMaterial,
        deleteOperario,
        deleteMaquinaria,
        getTotales,
        setValuePresupuesto,
        validaTotal,
    };
};
