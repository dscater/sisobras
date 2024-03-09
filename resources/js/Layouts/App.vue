<script setup>
// includes
import { ref } from "vue";
import Footer from "./includes/Footer.vue";
import NavBar from "./includes/NavBar.vue";
import SideBar from "./includes/SideBar.vue";
import { useApp } from "@/composables/useApp";
import { onMounted } from "vue";

const { loading, setLoading } = useApp();
setLoading(true);

onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
</script>
<template>
    <v-app id="inspire">
        <v-overlay
            v-model="loading"
            :persistent="true"
            class="d-flex justify-center align-center bg-loading"
        >
            <v-progress-circular
                indeterminate
                size="64"
                color="blue"
            ></v-progress-circular>
        </v-overlay>
        <NavBar></NavBar>
        <SideBar></SideBar>
        <v-main>
            <slot></slot>
            <!-- it will update, when refresh page by transition -->
        </v-main>
        <Footer></Footer>
    </v-app>
</template>
