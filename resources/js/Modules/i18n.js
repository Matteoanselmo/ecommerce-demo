import { createI18n } from 'vue-i18n';

// Importa i messaggi di traduzione per le lingue supportate
const messages = {
    en: {
        welcome: 'Welcome',
        // Altre traduzioni...
    },
    it: {
        welcome: 'Benvenuto',
        // Altre traduzioni...
    },
    // Altre lingue...
};

const i18n = createI18n({
    legacy: false,
    locale: 'it', // Imposta la lingua predefinita
    fallbackLocale: 'en', // Lingua di fallback
    messages,
});

export default i18n;
