<template>
  <b-card :header="`Welcome, ${account.name}`" class="mt-3">
    <b-card-text>
      <div>
        Account: <code>{{ account.id }}</code>
      </div>
      <div>
        Balance:
        <code>{{ balance }}</code>
      </div>
    </b-card-text>
    <b-button size="sm" variant="success" @click="toggleShow"
      >New payment</b-button
    >

    <b-button class="float-right" variant="danger" size="sm" nuxt-link to="/"
      >Logout</b-button
    >
  </b-card>
</template>

<script lang="ts">
import Vue, { PropOptions } from "vue";

import Account from "~/types/account";
import { usdToEuro } from "~/helpers/currency";

export default Vue.extend({
  props: {
    account: {
      type: Object,
      required: true
    } as PropOptions<Account>,
    currencySign: {
      type: String,
      required: true
    } as PropOptions<String>,
    needConvertMoney: {
      type: Boolean,
      default: false
    } as PropOptions<boolean>,
    rate: {
      type: Number,
      required: true,
      default: 0
    } as PropOptions<number>
  },

  computed: {
    balance(): string {
      let balance:number = this.account.balance;

      if (this.needConvertMoney) {
        balance = usdToEuro(balance, this.rate);
      }

      return `${this.currencySign}${balance}`;
    }
  },

  methods: {
    toggleShow() {
      this.$emit("toggle-show");
    }
  }
});
</script>
