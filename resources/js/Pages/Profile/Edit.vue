<script setup>
import BreadBrums from "@/Components/BreadBrums.vue";
import { ref, onMounted } from "vue";
import { useApp } from "@/composables/useApp";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { useUser } from "@/composables/useUser";
const { setLoading } = useApp();
onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Perfil",
        disabled: false,
        url: "",
        name_url: "",
    },
];
const props = defineProps({
    user: {
        type: Object,
    },
});

const url_aux = ref(props.user.url_foto);

const foto = ref(null);
const imagen_cargada = ref(false);

function cargaImagen(e) {
    foto.value = e.target.files[0];
    props.user.url_foto = URL.createObjectURL(foto.value);
    imagen_cargada.value = true;
}

const { getUser } = useUser();

function guardarImagen() {
    router.post(
        route("profile.update_foto"),
        { foto: foto.value, _method: "patch" },
        {
            forceFormData: true,
            onSuccess: () => {
                getUser();
                Swal.fire({
                    icon: "success",
                    title: "Correcto",
                    text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: `Aceptar`,
                });
                imagen_cargada.value = false;
            },
            onError: (err) => {
                Swal.fire({
                    icon: "info",
                    title: "Error",
                    text: `${
                        flash.error
                            ? flash.error
                            : "Tienes errores en el formulario"
                    }`,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: `Aceptar`,
                });
                console.log(err);
            },
        }
    );
}

function cancelarImagen() {
    imagen_cargada.value = false;
    props.user.url_foto = url_aux.value;
}

const form = useForm({
    password_actual: "",
    password: "",
    password_confirmation: "",
    _method: "patch",
});

const { flash } = usePage().props;

const enviaFormulario = () => {
    form.errors = {};
    form.post(route("profile.update"), {
        preserveScroll: true,
        onSuccess: () => {
            form.clearErrors();
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            form.reset();
        },
        onError: (err) => {
            Swal.fire({
                icon: "info",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : "Tienes errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            console.log(err);
        },
    });
};
</script>

<template>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row>
            <v-col cols="12" sm="12" md="4">
                <v-card>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12 text-center">
                                    <v-avatar color="blue-darken-3" size="80">
                                        <v-img
                                            cover
                                            v-if="user.url_foto"
                                            :src="user.url_foto"
                                        ></v-img>
                                        <span v-else class="text-h4">
                                            {{ user.iniciales_nombre }}
                                        </span>
                                    </v-avatar>
                                    <br />
                                    <h4 class="mt-1 mb-3">{{ user.tipo }}</h4>
                                    <label
                                        v-if="!imagen_cargada"
                                        class="btn_principal"
                                        for="file_foto"
                                        ><b>Cambiar foto</b
                                        ><input
                                            type="file"
                                            id="file_foto"
                                            accept="image/png, image/gif, image/jpeg"
                                            hidden
                                            @change="cargaImagen"
                                    /></label>
                                    <v-btn
                                        v-if="imagen_cargada"
                                        color="green"
                                        size="small"
                                        variant="outlined"
                                        class="w-50 mb-1"
                                        prepend-icon="mdi-content-save"
                                        @click="guardarImagen"
                                    >
                                        Guardar cambios
                                    </v-btn>
                                    <br />
                                    <v-btn
                                        v-if="imagen_cargada"
                                        size="small"
                                        variant="outlined"
                                        class="w-50 mb-1"
                                        prepend-icon="mdi-close"
                                        @click="cancelarImagen"
                                    >
                                        Cancelar
                                    </v-btn>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12">
                                    <v-row>
                                        <v-col cols="4" class="text-right"
                                            >Nombre:
                                        </v-col>
                                        <v-col cols="8">{{
                                            user.full_name
                                        }}</v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="4" class="text-right"
                                            >C.I.:
                                        </v-col>
                                        <v-col cols="8">{{
                                            user.full_ci
                                        }}</v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="4" class="text-right"
                                            >Dirección:
                                        </v-col>
                                        <v-col cols="8">{{ user.dir }}</v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="4" class="text-right"
                                            >Correo:
                                        </v-col>
                                        <v-col cols="8">{{
                                            user.correo
                                        }}</v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="4" class="text-right"
                                            >Teléfono/Celular:
                                        </v-col>
                                        <v-col cols="8">{{ user.fono }}</v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="4" class="text-right"
                                            >Fecha Registro:
                                        </v-col>
                                        <v-col cols="8">{{
                                            user.fecha_registro_t
                                        }}</v-col>
                                    </v-row>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" sm="12" md="8">
                <v-card class="pa-3">
                    <v-card-title>Cambiar contraseña </v-card-title>
                    <v-card-item>
                        <form>
                            <v-row class="mt-2">
                                <v-col cols="12">
                                    <v-text-field
                                        :class="
                                            form.errors?.password_actual
                                                ? 'mb-3'
                                                : ''
                                        "
                                        :error="
                                            form.errors?.password_actual
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.password_actual
                                                ? form.errors?.password_actual
                                                : ''
                                        "
                                        density="compact"
                                        placeholder="Contraseña actual"
                                        prepend-inner-icon="mdi-lock-outline"
                                        variant="outlined"
                                        color="blue"
                                        label="Contraseña actual"
                                        autocomplete="false"
                                        v-model="form.password_actual"
                                        type="password"
                                    ></v-text-field>
                                    <v-text-field
                                        :class="
                                            form.errors?.password ? 'mb-3' : ''
                                        "
                                        :error="
                                            form.errors?.password ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.password
                                                ? form.errors?.password
                                                : ''
                                        "
                                        density="compact"
                                        placeholder="Ingresa la nueva contraseña"
                                        prepend-inner-icon="mdi-lock-outline"
                                        variant="outlined"
                                        color="blue"
                                        label="Ingresa la nueva contraseña"
                                        autocomplete="false"
                                        v-model="form.password"
                                        type="password"
                                    ></v-text-field>
                                    <v-text-field
                                        :class="
                                            form.errors?.password_confirmation
                                                ? 'mb-3'
                                                : ''
                                        "
                                        :error="
                                            form.errors?.password_confirmation
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.password_confirmation
                                                ? form.errors
                                                      ?.password_confirmation
                                                : ''
                                        "
                                        density="compact"
                                        placeholder="Repite la nueva contraseña"
                                        prepend-inner-icon="mdi-lock-outline"
                                        variant="outlined"
                                        color="blue"
                                        label="Repite la nueva contraseña"
                                        autocomplete="false"
                                        v-model="form.password_confirmation"
                                        type="password"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </form>
                    </v-card-item>
                    <v-card-actions>
                        <v-btn class="bg-blue" @click="enviaFormulario"
                            >Guardar cambios</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped></style>
