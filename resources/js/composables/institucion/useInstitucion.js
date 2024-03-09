import { useInstitucionStore } from "@/stores/institucion/institucionStore";
import axios from "axios";
import { storeToRefs } from "pinia";
import { onMounted } from "vue";

export const useInstitucion = () => {
    const store_institucion = useInstitucionStore();
    const { oInstitucion } = storeToRefs(store_institucion);

    const getInstitucion = async (id) => {
        try {
            const response = await axios.get(
                route("institucions.getInstitucion", id),
                {
                    headers: { Accept: "application/json" },
                }
            );
            store_institucion.setInstiticion(response.data.institucion);
            return response.data.institucion;
        } catch (error) {
            console.error("Error al obtener la institución:", error);
            throw error; // Puedes manejar el error según tus necesidades
        }
    };

    onMounted(() => {
        getInstitucion();
    });

    return {
        // propiedades
        oInstitucion,
        // metodos
        getInstitucion,
    };
};
