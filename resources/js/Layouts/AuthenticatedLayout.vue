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
            </template>
        </v-app-bar>

        <v-navigation-drawer v-model="drawer" app color="secondary">
            <v-list density="compact">
                <v-list-item v-for="item in items" :key="item.title" color="white">
                    <Link
                        v-if="item.link !== 'logout'"
                        class="text-decoration-none text-black"
                        :href="route(item.link)"
                        :text="item.title"
                    />
                    <Link
                        v-else
                        method="POST"
                        as="button"
                        class="text-black"
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
    { title: "User", link: "home" },
    // { title: "Calendario", link: "dashboard" },
    // { title: "Menu", link: "dashboard.menu" },
    // { title: "Newsletter", link: "dashboard.newsletter" },
    // // { title: "Contatti", link: "" },
    { title: "Logout", link: "logout" },
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
