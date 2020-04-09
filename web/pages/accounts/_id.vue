<template>
  <div>
    <div class="container" v-if="loading">loading...</div>

    <div class="container" v-show="!loading">
      <account-info
        v-on:toggle-show="toggleShow"
        :account="account"
        :currencySign="currencySign"
      />

      <new-payment
        v-show="show"
        :accountId="id"
        v-on:update-data="updateData"
        v-on:toggle-show="toggleShow"
        v-on:set-loading="setLoading"
      />

      <transactions-list
        :data="transactions"
        :accountId="account.id"
        :currencySign="currencySign"
      />
    </div>
  </div>
</template>

<script lang="ts">
import Vue from "vue";

import Account from "~/types/account";
import Transaction from "~/types/transaction";

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
      id: +id as number
    };
  },

  asyncData({ params: { id }, $axios }) {
    return $axios.get(`accounts/${id}`).then(({ data: account }) => ({
      account
    }));
  },

  mounted() {
    this.$axios.get(`accounts/${this.id}/transactions`).then(({ data }) => {
      this.transactions = data;

      this.setLoading(false);
    });
  },

  computed: {
    currencySign(): string {
      return this.account.currency === "usd" ? "$" : "€";
    }
  },

  methods: {
    toggleShow() {
      this.show = !this.show;
    },
    updateData() {
      this.setLoading(true);

      Promise.all([
        this.$axios.get(`accounts/${this.id}`).then(({ data: account }) => ({
          account
        })),
        this.$axios.get(`accounts/${this.id}/transactions`).then(({ data }) => {
          this.transactions = data;
        })
      ]).finally(() => this.setLoading(false));
    },
    setLoading(value: boolean) {
      this.loading = value;
    }
  }
});
</script>
