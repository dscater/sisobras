<script setup>
import { useMenu } from "@/composables/useMenu";

const { cambiarUrl } = useMenu();

const props = defineProps({
    breadbrums: {
        type: Array,
        default: [
            {
                title: "Inicio",
                disabled: false,
                url: route("inicio"),
                name_url: "inicio",
            },
        ],
    },
});
</script>
<template>
    <v-row>
        <v-col cols="12">
            <v-breadcrumbs :items="breadbrums" class="pb-0">
                <template v-slot:title="{ item }">
                    <span
                        @click="cambiarUrl(item.url)"
                        class="cursor-pointer"
                        v-if="
                            !item.disabled && route().current() != item.name_url
                        "
                    >
                        {{ item.title }}
                    </span>
                    <span v-else>
                        {{ item.title }}
                    </span>
                </template>
                <template v-slot:divider>
                    <v-icon icon="mdi-chevron-right"></v-icon> </template
            ></v-breadcrumbs>
        </v-col>
    </v-row>
</template>
