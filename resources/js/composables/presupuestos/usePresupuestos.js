import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import { onMounted, reactive } from "vue";
import { usePage } from "@inertiajs/vue3";

const oPresupuesto = reactive({
    id: 0,
    nombre: "",
    gerente_regional_id: "",
    encargado_presupuesto_id: "",
    fecha_pent: "",
    fecha_peje: "",
    descripcion: "",
    lat: "",
    lng: "",
    categoria_id: null,
    fecha_registro: "",
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
            const response = await axios.get(route("presupuestos.paginado", data), {
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
    const savePresupuesto = async (data) => {
        try {
            const response = await axios.post(route("presupuestos.store", data), {
                headers: { Accept: "application/json" },
            });
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
            const response = await axios.delete(route("presupuestos.destroy", id), {
                headers: { Accept: "application/json" },
            });
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
            oPresupuesto.nombre = item.nombre;
            oPresupuesto.gerente_regional_id = item.gerente_regional_id;
            oPresupuesto.encargado_presupuesto_id = item.encargado_presupuesto_id;
            oPresupuesto.fecha_pent = item.fecha_pent;
            oPresupuesto.fecha_peje = item.fecha_peje;
            oPresupuesto.descripcion = item.descripcion;
            oPresupuesto.lat = item.lat;
            oPresupuesto.lng = item.lng;
            oPresupuesto.categoria_id = item.categoria_id;
            oPresupuesto.fecha_registro = item.fecha_registro;
            oPresupuesto._method = "PUT";
            return oPresupuesto;
        }
        return false;
    };

    const limpiarPresupuesto = () => {
        oPresupuesto.id = 0;
        oPresupuesto.nombre = "";
        oPresupuesto.gerente_regional_id = "";
        oPresupuesto.encargado_presupuesto_id = "";
        oPresupuesto.fecha_pent = "";
        oPresupuesto.fecha_peje = "";
        oPresupuesto.descripcion = "";
        oPresupuesto.lat = "";
        oPresupuesto.lng = "";
        oPresupuesto.categoria_id = null;
        oPresupuesto.fecha_registro = "";
        oPresupuesto._method = "POST";
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
    };
};
