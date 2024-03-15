import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import { onMounted, reactive } from "vue";
import { usePage } from "@inertiajs/vue3";

const oObra = reactive({
    id: 0,
    nombre: "",
    gerente_regional_id: "",
    encargado_obra_id: "",
    fecha_pent: "",
    fecha_peje: "",
    descripcion: "",
    lat: "",
    lng: "",
    categoria_id: null,
    fecha_registro: "",
    _method: "POST",
});

export const useObras = () => {
    const { flash } = usePage().props;
    const getObras = async (data) => {
        try {
            const response = await axios.get(route("obras.listado"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.obras;
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

    const getObrasApi = async (data) => {
        try {
            const response = await axios.get(route("obras.paginado", data), {
                headers: { Accept: "application/json" },
            });
            return response.data.obras;
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
    const saveObra = async (data) => {
        try {
            const response = await axios.post(route("obras.store", data), {
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

    const deleteObra = async (id) => {
        try {
            const response = await axios.delete(route("obras.destroy", id), {
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

    const setObra = (item = null) => {
        if (item) {
            oObra.id = item.id;
            oObra.nombre = item.nombre;
            oObra.gerente_regional_id = item.gerente_regional_id;
            oObra.encargado_obra_id = item.encargado_obra_id;
            oObra.fecha_pent = item.fecha_pent;
            oObra.fecha_peje = item.fecha_peje;
            oObra.descripcion = item.descripcion;
            oObra.lat = item.lat;
            oObra.lng = item.lng;
            oObra.categoria_id = item.categoria_id;
            oObra.fecha_registro = item.fecha_registro;
            oObra._method = "PUT";
            return oObra;
        }
        return false;
    };

    const limpiarObra = () => {
        oObra.id = 0;
        oObra.nombre = "";
        oObra.gerente_regional_id = "";
        oObra.encargado_obra_id = "";
        oObra.fecha_pent = "";
        oObra.fecha_peje = "";
        oObra.descripcion = "";
        oObra.lat = "";
        oObra.lng = "";
        oObra.categoria_id = null;
        oObra.fecha_registro = "";
        oObra._method = "POST";
    };

    onMounted(() => {});

    return {
        oObra,
        getObras,
        getObrasApi,
        saveObra,
        deleteObra,
        setObra,
        limpiarObra,
    };
};
