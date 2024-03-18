import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oAvanceObra = ref({
    id: 0,
    nombre: "",
    nro_avances: "",
    fecha_registro: "",
    _method: "POST",
});

export const useAvanceObras = () => {
    const { flash } = usePage().props;
    const getAvanceObras = async () => {
        try {
            const response = await axios.get(route("avance_obras.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.avance_obras;
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

    const getAvanceObrasApi = async (data) => {
        try {
            const response = await axios.get(
                route("avance_obras.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.avance_obras;
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
    const saveAvanceObra = async (data) => {
        try {
            const response = await axios.post(route("avance_obras.store", data), {
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
            console.error("Error:", err);
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const deleteAvanceObra = async (id) => {
        try {
            const response = await axios.delete(
                route("avance_obras.destroy", id),
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

    const setAvanceObra = (item = null) => {
        if (item) {
            oAvanceObra.value.id = item.id;
            oAvanceObra.value.nombre = item.nombre;
            oAvanceObra.value.nro_avances = item.nro_avances;
            oAvanceObra.value.fecha_registro = item.fecha_registro;
            oAvanceObra.value._method = "PUT";
            return oAvanceObra;
        }
        return false;
    };

    const limpiarAvanceObra = () => {
        oAvanceObra.value.id = 0;
        oAvanceObra.value.nombre = "";
        oAvanceObra.value.nro_avances = "";
        oAvanceObra.value.fecha_registro = "";
        oAvanceObra.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oAvanceObra,
        getAvanceObras,
        getAvanceObrasApi,
        saveAvanceObra,
        deleteAvanceObra,
        setAvanceObra,
        limpiarAvanceObra,
    };
};
