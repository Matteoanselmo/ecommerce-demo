<template>
  <v-data-table-server
    :items-per-page="itemsPerPage"
    :headers="headers"
    :items="items"
    :items-length="totalItems"
    :loading="loading"
    :search="search"
    item-value="name"
  >
    <template v-slot:tfoot>
      <tr>
        <td>
          <v-text-field v-model="name" class="ma-2" density="compact" placeholder="Search name..." hide-details></v-text-field>
        </td>
      </tr>
    </template>
  </v-data-table-server>
</template>
<script setup>
// TO-DO riportare tutto su pinia

import { ref, watch } from 'vue'
      const props = defineProps({
      items: {
          type: Array,
          required: true,
      },
      headers: {
        type: Array,
          required: true,
      },
      totalItems: {
        type: Number,
        default: 5
      },
      itemsPerPage: {
        default: 5,
        type: Number
      },
      loading: {
        type: Boolean,
        default: true
    }
    });
    const name = ref('')
    const search = ref('')
</script>
