import Vue from "vue";
import { AxiosResponse, AxiosError, AxiosInstance } from "axios";

import AxiosInterface from "./types";

export default function(Vue: AxiosInterface, inject?: any): void {
  const { $axios, redirect } = Vue;

  $axios.defaults.baseURL = process.env.API_URL;
  $axios.interceptors.response.use(
    (response: AxiosResponse) => {
      const {
        data: { data = null, message = "" } = {},
        status,
        ...other
      } = response;

      // form data from response in right way
      return { ...other, data, status, message };
    },
    (error: AxiosError) => {
      const status: number | undefined =
        error.response && error.response.status;

      // if server is down
      if (!status) {
        redirect(`${500}`);
      }

      if (status && [404, 500].includes(status)) {
        redirect(`/${status}`);
      }

      return error.response;
    }
  );
}

declare module "vue/types/vue" {
  interface Vue {
    $axios: AxiosInstance;
  }
}
