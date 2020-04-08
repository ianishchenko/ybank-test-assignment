<template>
  <b-card class="mt-3" header="Payment History">
    <b-table striped hover :items="items"></b-table>
  </b-card>
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
  props: {
    data: {
      type: Array,
      default: []
    },
    currencySign: {
      type: String
    },
    accountId: {
      type: Number
    }
  },

  // TODO: add fields config
  fields: [],

  computed: {
    items() {
      // TODO: add types
      return this.data.map(transaction => {
        const sign: string = this.accountId != transaction.to ? "-" : "";
        const amount: string = `${sign}${this.currencySign}${transaction.amount}`;

        return { ...transaction, amount };
      });
    }
  }
});
</script>
