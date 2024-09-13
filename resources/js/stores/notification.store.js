import { defineStore } from 'pinia';

export const useNotificationStore = defineStore('notification', {
    state: () => ({
        show: false,
        message: '',
        color: 'success',
        timeout: 3000
    }),
    actions: {
        notify(text, notificationColor = 'success') {
            this.message = text;
            this.color = notificationColor;
            this.show = true;
            // Nasconde automaticamente la notifica dopo 3 secondi
        }
    },
    persist: false,
})
