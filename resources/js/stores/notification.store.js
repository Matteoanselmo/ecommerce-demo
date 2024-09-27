import { defineStore } from 'pinia';

export const useNotificationStore = defineStore('notification', {
    state: () => ({
        show: false,
        message: '',
        color: '',
        timeout: 3000
    }),
    actions: {
        notify(text, color) {
            this.message = text;
            this.color = color;
            this.show = true;
            // Nasconde automaticamente la notifica dopo 3 secondi
        }
    },
    persist: false,
})
