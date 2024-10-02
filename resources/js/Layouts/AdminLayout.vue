<template>
    <v-app>
        <v-app-bar app class=" py-1">
            <template v-slot:prepend>
                <v-app-bar-nav-icon @click="toggleDrawer"></v-app-bar-nav-icon>
            </template>
            <v-app-bar-title>
                <v-img contain src="/images/logo/logo.jpg" max-width="229" />
            </v-app-bar-title>
            <v-spacer></v-spacer>
            <template v-slot:append>
                <dark-mode></dark-mode>
                <user-menu></user-menu>
            </template>
        </v-app-bar>

        <v-navigation-drawer v-model="drawer" app color="secondary" location="left" mobile-breakpoint="sm">
            <v-list lines="one">
                <v-list-item v-for="item in items" :key="item.title" variant="plain"
                    class="text-start hoverable-list-item"
                    width="100%"
                    >
                    <Link
                        v-if="item.link !== 'logout'"
                        class="text-decoration-none font-weight-black "
                        :href="route(item.link)"
                        :text="item.title"
                    />
                    <Link
                        v-else
                        method="POST"
                        as="button"
                        class="text-danger font-weight-black"
                        :href="route(item.link)"
                    >
                        {{ item.title }}
                    </Link>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-main position-relative class="position-relative">
            <slot></slot>
        </v-main>
        <notification></notification>
    </v-app>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Link } from "@inertiajs/vue3";
const drawer = ref(false);
const showButtons = ref(false);

const toggleDrawer = () => {
    drawer.value = !drawer.value;
};

const items = ref([
    { title: "Home", link: "home" },
    { title: "Panoramica", link: "admin.dashboard" },
    { title: "Personalizzazione", link: "admin.customization" },
    { title: "Ordini e resi", link: "admin.orders" },
    { title: "Clienti", link: "admin.user" },
    { title: "Ticket", link: "admin.tickets" },
    { title: "Prodotti", link: "admin.products" },
]);

const changeLanguage = (lang) => {
    console.log(`Changed language to: ${lang}`);
};

let lastScrollY = window.scrollY;

const onScroll = () => {
    if (window.scrollY > lastScrollY) {
        showButtons.value = true;
    } else {
        showButtons.value = false;
    }
    lastScrollY = window.scrollY;
};

onMounted(() => {
    window.addEventListener("scroll", onScroll);
});

onUnmounted(() => {
    window.removeEventListener("scroll", onScroll);
});
</script>

<style scoped>
.v-navigation-drawer {
    background-color: #000; /* Colore di sfondo simile alle immagini */
    color: #fff; /* Colore del testo */
}

.language-btn,
.booking-btn {
    position: fixed;
    right: 0;
    transform: translateY(100px);
    transition: transform 0.3s ease;
}

.language-btn {
    top: 38px; /* Adjust position as needed */
}

.booking-btn {
    top: 0; /* Adjust position as needed */
}

.v-menu__content {
    transform: translateY(100px);
    transition: transform 0.3s ease;
}

.v-application--is-scrolling .language-btn,
.v-application--is-scrolling .booking-btn,
.v-application--is-scrolling .v-menu__content {
    transform: translateY(0);
}
</style>
