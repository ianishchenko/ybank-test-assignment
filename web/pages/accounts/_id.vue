<template>
  <div>
    <div class="container" v-if="loading">loading...</div>

    <div class="container" v-show="!loading">
      <account-info
        v-on:toggle-show="toggleShow"
        :account="account"
        :currencySign="currencySign"
        :needConvertMoney="needConvertMoney"
        :rate="rate"
      />

      <new-payment
        v-show="show"
        :accountId="id"
        :currencySign="currencySign"
        v-on:update-data="updateData"
        v-on:toggle-show="toggleShow"
        v-on:set-loading="setLoading"
      />

      <transactions-list
        :data="transactions"
        :accountId="id"
        :currencySign="currencySign"
        :needConvertMoney="needConvertMoney"
      />
    </div>
  </div>
</template>

<script lang="ts">
import Vue from "vue";

import Account from "~/types/account";
import Transaction from "~/types/transaction";
import Currency from "~/types/currency";
import { Currency as CurrencyEnum } from "~/enums/currency";

import AccountInfo from "./AccountInfo.vue";
import NewPayment from "./NewPayment.vue";
import TransactionsList from "./TransactionsList.vue";

export default Vue.extend({
  components: { AccountInfo, NewPayment, TransactionsList },

  data() {
    const {
      $route: {
        params: { id }
      }
    } = this;

    return {
      account: {} as Account,
      transactions: [] as Array<Transaction>,
      show: false as boolean,
      loading: true as boolean,
      id: +id as number,
      currencies: { rates: {} } as Currency
    };
  },

  asyncData({ params: { id }, $axios }) {
    return $axios.get(`accounts/${id}`).then(({ data: account }) => ({
      account
    }));
  },

  mounted() {
    Promise.all([
      this.$axios.get(`accounts/${this.id}/transactions`).then(({ data }) => {
        this.transactions = data;
      }),
      this.$axios.get(`currencies`).then(({ data }) => {
        this.currencies = data;
      })
    ]).finally(() => this.setLoading(false));
  },

  // TODO: need to add logic if new currency type is added
  computed: {
    currencySign(): string {
      return this.account.currency === CurrencyEnum.USD ? "$" : "€";
    },
    needConvertMoney(): boolean {
      return CurrencyEnum.EUR === this.account.currency;
    },
    rate(): number {
      return this.currencies.rates.EUR;
    }
  },

  methods: {
    toggleShow() {
      this.show = !this.show;
    },
    updateData() {
      this.setLoading(true);

      Promise.all([
        this.$axios.get(`accounts/${this.id}`).then(({ data }) => {
          this.account = data;
        }),
        this.$axios.get(`accounts/${this.id}/transactions`).then(({ data }) => {
          this.transactions = data;
        }),
        this.$axios.get(`currencies`).then(({ data }) => {
          this.currencies = data;
        })
      ]).finally(() => this.setLoading(false));
    },
    setLoading(value: boolean) {
      this.loading = value;
    }
  }
});
</script>
