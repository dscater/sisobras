<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Reporte Tabla de Posiciones",
        disabled: false,
        url: "",
        name_url: "",
    },
];
</script>

<script setup>
import BreadBrums from "@/Components/BreadBrums.vue";
import { useApp } from "@/composables/useApp";
import { computed, onMounted, ref } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import Highcharts from "highcharts";
import exporting from "highcharts/modules/exporting";
import { useObras } from "@/composables/obras/useObras";
const { getObras } = useObras();
exporting(Highcharts);
Highcharts.setOptions({
    lang: {
        downloadPNG: "Descargar PNG",
        downloadJPEG: "Descargar JPEG",
        downloadPDF: "Descargar PDF",
        downloadSVG: "Descargar SVG",
        printChart: "Imprimir gráfico",
        contextButtonTitle: "Menú de exportación",
        viewFullscreen: "Pantalla completa",
        exitFullscreen: "Salir de pantalla completa",
    },
});

const { setLoading } = useApp();

const cargarListas = async () => {
    listObras.value = await getObras({
        order: "desc",
    });

    listObras.value.unshift({
        id: "TODOS",
        nombre: "TODOS",
    });
};

onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
    cargarListas();
});

const form = ref({
    obra_id: "TODOS",
});

const listObras = ref([]);

const generando = ref(false);
const txtBtn = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return `Generar Reporte Gráfico <i class="mdi mdi-chart-bar"></i>`;
});
const txtBtn2 = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return `Generar Reporte Pdf <i class="mdi mdi-file-pdf-box"></i>`;
});

const formulario = ref(null);

const generarReporte = async () => {
    const { valid } = await formulario.value.validate();
    if (valid) {
        generando.value = true;

        axios
            .get(route("reportes.g_avance_obras"), { params: form.value })
            .then((response) => {
                // Create the chart
                Highcharts.chart("container", {
                    chart: {
                        type: "column",
                    },
                    title: {
                        align: "center",
                        text: "Avance de Obras",
                    },
                    subtitle: {
                        align: "left",
                        text: "",
                    },
                    accessibility: {
                        announceNewData: {
                            enabled: true,
                        },
                    },
                    xAxis: {
                        type: "category",
                    },
                    yAxis: {
                        title: {
                            text: "% Progreso",
                        },
                    },
                    legend: {
                        enabled: false,
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: "{point.y:.0f}%",
                            },
                        },
                    },

                    tooltip: {
                        headerFormat:
                            '<span style="font-size:11px">{series.name}</span><br>',
                        pointFormat:
                            '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> %<br/>',
                    },

                    series: [
                        {
                            name: "Progreso",
                            colorByPoint: true,
                            data: response.data.data,
                        },
                    ],
                });
                generando.value = false;
            });
    }
};

const generarReportePdf = async () => {
    const { valid } = await formulario.value.validate();
    if (valid) {
        generando.value = true;
        const url = route("reportes.r_avance_obras", form.value);
        window.open(url, "_blank");
        setTimeout(() => {
            generando.value = false;
        }, 500);
    }
};
onMounted(() => {});
</script>
<template>
    <Head title="Reporte Tabla de Posiciones"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row>
            <v-col cols="12" sm="12" md="12" xl="8" class="mx-auto">
                <v-card>
                    <v-card-item>
                        <v-container>
                            <v-form
                                @submit.prevent="generarReporte"
                                ref="formulario"
                            >
                                <v-row>
                                    <v-col cols="12">
                                        <v-select
                                            :hide-details="true"
                                            variant="outlined"
                                            density="compact"
                                            required
                                            :items="listObras"
                                            item-value="value"
                                            item-title="label"
                                            label="Tipo*"
                                            v-model="form.obra_id"
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-btn
                                            color="blue-darken-2"
                                            block
                                            @click="generarReporte"
                                            :disabled="generando"
                                            v-html="txtBtn"
                                        ></v-btn>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-btn
                                            color="blue-darken-2"
                                            block
                                            @click="generarReportePdf"
                                            :disabled="generando"
                                            v-html="txtBtn2"
                                        ></v-btn>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </v-container>
                    </v-card-item>
                </v-card>
            </v-col>
            <v-col cols="12">
                <div id="container"></div>
            </v-col>
        </v-row>
    </v-container>
</template>
