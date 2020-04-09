import { AxiosStatic } from "axios";

export default interface Axios {
  $axios: AxiosStatic;
  redirect: (url: string) => void;
}


