import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oMaterial = ref({
    id: 0,
    nombre: "",
    descripcion: "",
    fecha_registro: "",
    _method: "POST",
});

export const useMaterials = () => {
    const { flash } = usePage().props;
    const getMaterials = async (data) => {
        try {
            const response = await axios.get(route("materials.listado"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.materials;
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

    const getMaterialsApi = async (data) => {
        try {
            const response = await axios.get(
                route("materials.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.materials;
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
    const saveMaterial = async (data) => {
        try {
            const response = await axios.post(route("materials.store", data), {
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

    const deleteMaterial = async (id) => {
        try {
            const response = await axios.delete(
                route("materials.destroy", id),
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

    const setMaterial = (item = null) => {
        if (item) {
            oMaterial.value.id = item.id;
            oMaterial.value.nombre = item.nombre;
            oMaterial.value.descripcion = item.descripcion;
            oMaterial.value.fecha_registro = item.fecha_registro;
            oMaterial.value._method = "PUT";
            return oMaterial;
        }
        return false;
    };

    const limpiarMaterial = () => {
        oMaterial.value.id = 0;
        oMaterial.value.nombre = "";
        oMaterial.value.descripcion = "";
        oMaterial.value.fecha_registro = "";
        oMaterial.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oMaterial,
        getMaterials,
        getMaterialsApi,
        saveMaterial,
        deleteMaterial,
        setMaterial,
        limpiarMaterial,
    };
};
