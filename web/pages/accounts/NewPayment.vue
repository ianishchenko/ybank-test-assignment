<template>
  <b-card class="mt-3" header="New Payment">
    <b-form v-on:submit.prevent="onSubmit">
      <b-form-group id="input-group-1" label="To:" label-for="input-1">
        <b-form-input
          id="input-1"
          size="sm"
          v-model="payment.to"
          type="number"
          required
          placeholder="Destination ID"
        ></b-form-input>
      </b-form-group>

      <b-form-group id="input-group-2" label="Amount:" label-for="input-2">
        <b-input-group prepend="$" size="sm">
          <b-form-input
            id="input-2"
            v-model="payment.amount"
            type="number"
            required
            placeholder="Amount"
          ></b-form-input>
        </b-input-group>
      </b-form-group>

      <b-form-group id="input-group-3" label="Details:" label-for="input-3">
        <b-form-input
          id="input-3"
          size="sm"
          v-model="payment.details"
          required
          placeholder="Payment details"
        ></b-form-input>
      </b-form-group>

      <b-button type="submit" size="sm" variant="primary">Submit</b-button>
    </b-form>
  </b-card>
</template>

<script lang="ts">
import Vue from "vue";

// TODO: add validations
export default Vue.extend({
  data() {
    return { payment: {} };
  },

  methods: {
    onSubmit() {
      this.$emit("set-loading", true);

      this.$axios.post(
        `accounts/${this.$route.params.id}/transactions`,
        this.payment
      ).then(() => {
        // dont need to set loading to false. it will be done in "update-data" event to prevent twinkle of loader
        this.payment = {};
        this.$emit("toggle-show");
        this.$emit("update-data");
      }, error => {
        this.$emit("set-loading", false);
        // errors handling if it needs in future
      });
    }
  }
});
</script>
