<template>
  <b-card class="mt-3" header="New Payment">
    <p v-if="errors.length">
      <b>Please correct the following error(s):</b>
      <b-list-group flush>
        <b-list-group-item
          variant="danger"
          v-for="error in errors"
          v-bind:key="error"
        >
          {{ error }}
        </b-list-group-item>
      </b-list-group>
    </p>
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
import Vue, { PropOptions } from "vue";

import Payment from "~/types/payment";
import Error from "~/types/error";

export default Vue.extend({
  props: {
    accountId: {
      type: Number,
      required: true
    } as PropOptions<number>
  },

  data() {
    return { payment: {} as Payment, errors: [] as Array<string> };
  },

  methods: {
    onSubmit() {
      const errors = this.getErrors();

      if (!errors.length) {
        this.$emit("set-loading", true);

        this.$axios
          .post(`accounts/${this.accountId}/transactions`, this.payment)
          .then(
            () => {
              // dont need to set loading to false. it will be done in "update-data" event to prevent twinkle of loader
              this.payment = {};
              this.$emit("toggle-show");
              this.$emit("update-data");
            },
            ({ data: { errors } }) => {
              this.$emit("set-loading", false);

              this.errors = errors.map((error: Error) => error.message);
            }
          );
      } else {
        this.errors = errors;
      }
    },
    getErrors() {
      const { to, amount, details } = this.payment;
      const errors = [];

      if (!to) {
        errors.push("You should specify recipient");
      } else if (+to === this.accountId) {
        errors.push("You cannot specify yourself");
      }

      if (!amount) {
        errors.push("You should specify amount");
      } else if (Number.isNaN(amount)) {
        errors.push("You should enter valid number value");
      } else if (amount <= 0) {
        errors.push("Amount should be greater than 0");
      }

      if (!details) {
        errors.push("You should specify details");
      }

      return errors;
    }
  }
});
</script>
