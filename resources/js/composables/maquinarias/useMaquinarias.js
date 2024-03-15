import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oMaquinaria = ref({
    id: 0,
    nombre: "",
    descripcion: "",
    fecha_registro: "",
    _method: "POST",
});

export const useMaquinarias = () => {
    const { flash } = usePage().props;
    const getMaquinarias = async (data) => {
        try {
            const response = await axios.get(route("maquinarias.listado"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.maquinarias;
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

    const getMaquinariasApi = async (data) => {
        try {
            const response = await axios.get(
                route("maquinarias.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.maquinarias;
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
    const saveMaquinaria = async (data) => {
        try {
            const response = await axios.post(
                route("maquinarias.store", data),
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

    const deleteMaquinaria = async (id) => {
        try {
            const response = await axios.delete(
                route("maquinarias.destroy", id),
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

    const setMaquinaria = (item = null) => {
        if (item) {
            oMaquinaria.value.id = item.id;
            oMaquinaria.value.nombre = item.nombre;
            oMaquinaria.value.descripcion = item.descripcion;
            oMaquinaria.value.fecha_registro = item.fecha_registro;
            oMaquinaria.value._method = "PUT";
            return oMaquinaria;
        }
        return false;
    };

    const limpiarMaquinaria = () => {
        oMaquinaria.value.id = 0;
        oMaquinaria.value.nombre = "";
        oMaquinaria.value.descripcion = "";
        oMaquinaria.value.fecha_registro = "";
        oMaquinaria.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oMaquinaria,
        getMaquinarias,
        getMaquinariasApi,
        saveMaquinaria,
        deleteMaquinaria,
        setMaquinaria,
        limpiarMaquinaria,
    };
};
