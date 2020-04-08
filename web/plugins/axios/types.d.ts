import { AxiosStatic } from "axios";

export default interface AxiosInterface {
  $axios: AxiosStatic;
  redirect: (url: string) => void;
}


