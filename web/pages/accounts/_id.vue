<template>
  <div>
    <div class="container" v-if="loading">loading...</div>

    <div class="container" v-if="!loading">
      <account-info
        v-on:toggle-show="toggleShow"
        :account="account"
        :currencySign="currencySign"
      />

      <new-payment
        v-show="show"
        v-on:update-data="updateData"
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

import AccountInfo from "~/pages/accounts/AccountInfo";
import NewPayment from "~/pages/accounts/NewPayment";
import TransactionsList from "~/pages/accounts/TransactionsList";

// TODO: add types
export default Vue.extend({
  components: { AccountInfo, NewPayment, TransactionsList },
  data() {
    return {
      account: null,
      transactions: null,
      show: false,
      loading: true
    };
  },

  asyncData({ params: { id }, $axios }) {
    return $axios.get(`accounts/${id}`).then(({ data: account }) => ({
      account
    }));
  },

  mounted() {
    const {
      $route: {
        params: { id }
      }
    } = this;

    this.$axios.get(`accounts/${id}/transactions`).then(({ data }) => {
      this.transactions = data;

      if (this.account && this.transactions) {
        this.setLoading(false);
      }
    });
  },

  computed: {
    currencySign() {
      return this.account.currency === "usd" ? "$" : "€";
    }
  },

  methods: {
    toggleShow() {
      this.show = !this.show;
    },
    updateData() {
      // TODO: add update data logic
    },
    setLoading(value: boolean) {
      this.loading = value;
    }
  }
});
</script>
