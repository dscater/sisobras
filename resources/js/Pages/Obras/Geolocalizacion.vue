<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Geolocalización de Obras",
        disabled: false,
        url: "",
        name_url: "",
    },
];
</script>
<script setup>
import BreadBrums from "@/Components/BreadBrums.vue";
import { useApp } from "@/composables/useApp";
import { useMenu } from "@/composables/useMenu";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import { useObras } from "@/composables/obras/useObras";
const { getObras } = useObras();
const { mobile, identificaDispositivo, cambiarUrl } = useMenu();
const { setLoading } = useApp();
onMounted(() => {
    identificaDispositivo();
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const obra_id = ref(null);
const o_obra = ref(null);
const listObras = ref([]);

const cargarListas = async () => {
    listObras.value = await getObras();
};

const changeObra = (value) => {
    if (value) {
        axios.get(route("obras.getObra", value)).then((response) => {
            o_obra.value = response.data.obra;
            cargaMapaGoogle(
                o_obra.value.lat,
                o_obra.value.lng,
                false,
                o_obra.value.nombre
            );
        });
    }
};

const cargaMapaGoogle = async (lat, lng, drag = false, dir = "") => {
    lat = parseFloat(lat);
    lng = parseFloat(lng);
    // Inicializa el mapa
    const { Map, InfoWindow } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
    const map = new Map(document.getElementById("google_map"), {
        zoom: 18,
        center: { lat: lat, lng: lng },
        mapId: mapa_id,
    });
    const infoWindow = new InfoWindow();

    // Crea un marcador en el centro del mapa
    const AME = new AdvancedMarkerElement({
        map,
        position: { lat: lat, lng: lng },
        gmpDraggable: drag,
        // title:""
    });

    // evento click sobre el marcador
    infoWindow.close();
    infoWindow.setContent(dir);
    AME.addListener("click", () => {
        infoWindow.open(AME.map, AME);
    });
};

onMounted(() => {
    cargarListas();
});
</script>
<template>
    <Head title="Obras"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row class="mt-0">
            <v-col cols="12">
                <v-card>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12" md="6" offset-md="3">
                                <v-select
                                    clearable
                                    variant="outlined"
                                    label="Seleccionar Obra*"
                                    :items="listObras"
                                    item-value="id"
                                    item-title="nombre"
                                    required
                                    density="compact"
                                    @update:model-value="changeObra"
                                    v-model="obra_id"
                                ></v-select>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" v-if="obra_id">
                                <div id="google_map"></div>
                            </v-col>
                            <v-col cols="12" v-else>
                                <h4 class="text-center text-caption">Debes seleccionar una Obra</h4>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>
#google_map {
    width: 100%;
    height: 500px;
}
</style>
