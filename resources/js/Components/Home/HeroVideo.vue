<script setup>
</script>

<template>
    <v-container fluid class="px-0" data-aos="fade-up" data-aos-duration="800">
        <v-row>
            <v-col cols="12" class="px-0">
                <div class="video-hero">
                <!-- Video di sfondo -->
                <video autoplay muted loop playsinline class="video-background">
                    <source src="/images/background/video.mp4" type="video/mp4" />
                    Il tuo browser non supporta i video.
                </video>

                <!-- Sovrapposizione per contrasto -->
                <div class="video-overlay"></div>

                <!-- Contenuto sovrapposto -->
                <div class="video-content">
                    <h1 class="video-title">Benvenuti nel nostro mondo</h1>
                    <p class="video-subtitle">
                        Scopri la qualità e l'innovazione che ci contraddistinguono.
                    </p>
                </div>
            </div>
            </v-col>
        </v-row>
    </v-container>

</template>

<style scoped>
.video-hero {
    position: relative;
    height: 100vh;
    width: 100%;
    overflow: hidden;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    color: white;
}

.video-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 1;
}

.video-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 2;
}

.video-content {
    text-align: center;
    z-index: 1;
    padding: 20px;
}

.video-title {
    font-size: 3rem;
    font-weight: bold;
    margin-bottom: 1rem;
}

.video-subtitle {
    font-size: 1.25rem;
    margin-bottom: 2rem;
}
</style>
