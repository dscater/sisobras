import { defineStore } from "pinia";

export const useInstitucionStore = defineStore("institucion", {
    state: () => ({
        oInstitucion: {
            nombre: "APLICACIÓN",
            nombre_sistema: "SISTEMA S.A.",
            nit: "11111111",
            historia: "",
            mision: "",
            vision: "",
            objetivo: "",
            nombre_director: "JUAN PEREZ",
            foto_director: "",
            nombre_subdirector: "",
            foto_subdirector: "",
            fono1: "",
            fono2: "",
            correo1: "",
            correo2: "",
            facebook: "",
            youtube: "",
            twitter: "",
            dir: "",
            ubicacion_google: "",
            img_organigrama: "",
            logo: "",
            // appends
            iniciales_nombre: "",
            url_logo: "",
            url_foto_director: "",
            url_foto_subdirector: "",
            url_img_organigrama: "",
            iniciales_director: "",
            iniciales_subdirector: "",
        },
    }),
    actions: {
        setInstiticion(value) {
            this.oInstitucion = value;
        },
    },
});
