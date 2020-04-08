<template>
  <b-card :header="`Welcome, ${account.name}`" class="mt-3">
    <b-card-text>
      <div>
        Account: <code>{{ account.id }}</code>
      </div>
      <div>
        Balance:
        <code>{{ currencySign }}{{ account.balance }}</code>
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
import Vue from "vue";

export default Vue.extend({
  props: {
    account: {
      type: Object
    },
    currencySign: String
  },

  data() {
    return { show: false };
  },

  methods: {
    toggleShow() {
      this.$emit("toggle-show");
    }
  }
});
</script>
