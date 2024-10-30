import 'vuetify/styles';
import "@mdi/font/css/materialdesignicons.css";
import { createVuetify } from 'vuetify';
import { aliases, mdi } from 'vuetify/iconsets/mdi';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import { createVueI18nAdapter } from 'vuetify/locale/adapters/vue-i18n'
import i18n from './Modules/i18n';
import { en, it, fr } from 'vuetify/locale'


const vuetify = createVuetify({
    locale: {
        locale: i18n.global.locale,
        fallbackLocale: 'en',
        messages: { it, en },
    },
    theme: {
        defaultTheme: 'myCustomTheme',
        themes: {
            myCustomTheme: {
                dark: false,
                colors: {
                    primary: '#00238C',
                    secondary: '#2f4eaa', // Sostituisci con il colore che desideri
                    tertiary: '#CF3825',
                    accent: '#CF3825', // Sostituisci con il colore che desideri
                    error: '#FF5252', // Sostituisci con il colore che desideri
                    info: '#2196F3', // Sostituisci con il colore che desideri
                    success: '#4CAF50', // Sostituisci con il colore che desideri
                    warning: '#FFC107', // Sostituisci con il colore che desideri
                    danger: '#F44336',
                    background: '#F5F5F5', // Colore di sfondo personalizzato
                },
            },
            myCustomThemeDark: {
                dark: true,
                colors: {
                    primary: '#2f4eaa', // Colore principale scurito
                    secondary: '#aab8e0', // Colore secondario scurito
                    tertiary: '#B71C1C',
                    accent: '#B71C1C',
                    error: '#D32F2F',
                    info: '#1976D2',
                    success: '#388E3C',
                    warning: '#F57C00',
                    danger: '#D32F2F',
                    background: '#333333', // Grigio molto scuro per sfondo
                },
            },
        },
    },
    components,
    directives,
    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
            mdi,
        },
    },
});

export default vuetify;
