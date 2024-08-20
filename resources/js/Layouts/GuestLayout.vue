<template>
    <v-app>
        <v-app-bar app color="white" class="d-flex align-center justify-between">
            <v-img left contain src="/images/logo/logo.jpg" />

            <v-app-bar-nav-icon @click="toggleDrawer"></v-app-bar-nav-icon>
        </v-app-bar>

        <!-- temporary -->
        <v-navigation-drawer
            mobile-breakpoint="sm"
            v-model="drawer"
            app
            color="primary"
            location="right"
            permanent
        >
            <v-list
                lines="one"
                class="d-flex flex-column justify-center align-center"
                height="100%"
            >
                <v-list-item
                    v-for="item in items"
                    :key="item.title"
                    variant="plain"
                    class="text-center hoverable-list-item"
                    width="100%"
                >
                    <Link
                        class="text-decoration-none text-black text-h6"
                        :href="route(item.link)"
                        :text="item.title"
                    />
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-main position-relative class="position-relative">
            <slot></slot>
        </v-main>

        <v-footer color="primary">
            <v-col class="text-center white--text">
                <p>
                    Copyright © {{ currentYear }} Security Fire Srl - Cod. Fisc. e Partita IVA: 01470640382 - Tutti i diritti riservati
                </p>
            </v-col>
        </v-footer>

        <!-- Buttons that appear on scroll -->

        <Link
            :href="route('prenota')"
            v-if="showButtons"
            as="button"
            class="v-btn booking-btn"
            rounded="0"
        >
            <v-btn color="primary" dark> Prenota </v-btn>
        </Link>
        <v-menu
            v-if="showButtons"
            :close-on-content-click="false"
            offset-y
            bottom
        >
            <template v-slot:activator="{ props: activatorProps }">
                <v-btn
                    color="primary"
                    dark
                    v-bind="activatorProps"
                    class="language-btn"
                    rounded="0"
                >
                    IT
                </v-btn>
            </template>
            <v-list>
                <v-list-item
                    v-for="(lang, i) in languages"
                    :key="i"
                    @click="changeLanguage(lang)"
                >
                    <v-list-item-title>{{ lang }}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>

        <!-- Cerchio del mouse -->
        <div
            class="mouse-circle"
            :style="{
                left: mouseX + 'px',
                top: mouseY + 'px',
            }"
        ></div>
    </v-app>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Link } from "@inertiajs/vue3";
const drawer = ref(false);
const currentYear = new Date().getFullYear();
const showButtons = ref(false);
const languages = ["IT", "EN", "FR"];
const productsToSearch = ref([]);

const mouseX = ref(0);
const mouseY = ref(0);

const toggleDrawer = () => {
    drawer.value = !drawer.value;
};

const items = ref([
    { title: "Home", link: "home" },
    { title: "Login", link: "login" },
    // { title: "Menu", link: "menu" },
    // { title: "Chi Siamo", link: "about" },
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

const onMouseMove = (event) => {
    mouseX.value = event.clientX;
    mouseY.value = event.clientY;
};

onMounted(() => {
    window.addEventListener("scroll", onScroll);
    window.addEventListener("mousemove", onMouseMove);
});

onUnmounted(() => {
    window.removeEventListener("scroll", onScroll);
    window.removeEventListener("mousemove", onMouseMove);
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

.mouse-circle {
    position: fixed;
    width: 65px;
    height: 65px;
    background-color: rgba(238, 238, 238, 0.5);
    border-radius: 50%;
    pointer-events: none;
    transform: translate(-50%, -50%);
    transition: transform 0.1s ease;
}

.hoverable-list-item {
    transition: background-color 0.5s ease, color 0.5s ease;
}

.hoverable-list-item:hover {
    background-color: rgb(255, 255, 255);
    color: black;
}
</style>
