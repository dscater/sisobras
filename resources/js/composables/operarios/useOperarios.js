import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oOperario = ref({
    id: 0,
    nombre: "",
    paterno: "",
    materno: "",
    ci: "",
    ci_exp: "",
    fono: "",
    tipo: "",
    fecha_registro: "",
    _method: "POST",
});

export const useOperarios = () => {
    const { flash } = usePage().props;
    const getOperarios = async (data) => {
        try {
            const response = await axios.get(route("operarios.listado"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.operarios;
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

    const getOperariosApi = async (data) => {
        try {
            const response = await axios.get(
                route("operarios.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.operarios;
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
    const saveOperario = async (data) => {
        try {
            const response = await axios.post(route("operarios.store", data), {
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
            console.error("Error:", err);
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const deleteOperario = async (id) => {
        try {
            const response = await axios.delete(
                route("operarios.destroy", id),
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
            console.error("Error:", err);
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const setOperario = (item = null) => {
        if (item) {
            oOperario.value.id = item.id;
            oOperario.value.nombre = item.nombre;
            oOperario.value.paterno = item.paterno;
            oOperario.value.materno = item.materno;
            oOperario.value.ci = item.ci;
            oOperario.value.ci_exp = item.ci_exp;
            oOperario.value.fono = item.fono;
            oOperario.value.tipo = item.tipo;
            oOperario.value.fecha_registro = item.fecha_registro;
            oOperario.value._method = "PUT";
            return oOperario;
        }
        return false;
    };

    const limpiarOperario = () => {
        oOperario.value.id = 0;
        oOperario.value.nombre = "";
        oOperario.value.paterno = "";
        oOperario.value.materno = "";
        oOperario.value.ci = "";
        oOperario.value.ci_exp = "";
        oOperario.value.fono = "";
        oOperario.value.tipo = "";
        oOperario.value.fecha_registro = "";
        oOperario.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oOperario,
        getOperarios,
        getOperariosApi,
        saveOperario,
        deleteOperario,
        setOperario,
        limpiarOperario,
    };
};
