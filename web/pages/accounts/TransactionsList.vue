<template>
  <b-card class="mt-3" header="Payment History">
    <b-table striped hover :items="items" :fields="fields"></b-table>
  </b-card>
</template>

<script lang="ts">
import Vue, { PropOptions } from "vue";

import Transaction from "~/types/transaction";
import { usdToEuro } from "~/helpers/currency";

export default Vue.extend({
  props: {
    data: {
      type: Array,
      default: []
    } as PropOptions<Array<Transaction<number>>>,
    currencySign: {
      type: String,
      required: true
    } as PropOptions<String>,
    accountId: {
      type: Number,
      required: true
    } as PropOptions<Number>,
    needConvertMoney: {
      type: Boolean,
      default: false
    } as PropOptions<boolean>
  },

  data() {
    return {
      fields: [
        { key: "id", label: "ID#" },
        "from",
        "to",
        "amount",
        "details",
        {
          key: "created_at",
          label: "Date",
          formatter: (date: string): string => new Date(date).toLocaleString()
        }
      ]
    };
  },

  computed: {
    items(): Array<Transaction<string>> {
      return this.data.map(transaction => {
        const sign: string = this.accountId !== transaction.to ? "-" : "";
        const balance = this.needConvertMoney
          ? usdToEuro(transaction.amount, transaction.current_currency)
          : transaction.amount;

        const amount: string = `${sign}${this.currencySign}${balance}`;

        return { ...transaction, amount };
      });
    }
  }
});
</script>
