import { AxiosResponse, AxiosError, AxiosInstance } from "axios";

import Axios from "~/types/axios";

export default function(Vue: Axios, inject?: any): void {
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

      return Promise.reject(error.response)
    }
  );
}

declare module "vue/types/vue" {
  interface Vue {
    $axios: AxiosInstance;
  }
}

declare module "@nuxt/types" {
  interface Context {
    $axios: AxiosInstance;
  }
}
