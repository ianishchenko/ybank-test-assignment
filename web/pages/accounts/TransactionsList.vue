<template>
  <b-card class="mt-3" header="Payment History">
    <b-table striped hover :items="items"></b-table>
  </b-card>
</template>

<script lang="ts">
import Vue, { PropOptions } from "vue";

import Transaction from "~/types/transaction";

export default Vue.extend({
  props: {
    data: {
      type: Array,
      default: []
    } as PropOptions<Array<Transaction>>,
    currencySign: {
      type: String,
      required: true
    } as PropOptions<String>,
    accountId: {
      type: Number,
      required: true
    } as PropOptions<Number>
  },

  computed: {
    items(): Array<Transaction> {
      return this.data.map(transaction => {
        const sign: string = this.accountId != transaction.to ? "-" : "";
        const amount: string = `${sign}${this.currencySign}${transaction.amount}`;

        return { ...transaction, amount };
      });
    }
  }
});
</script>
