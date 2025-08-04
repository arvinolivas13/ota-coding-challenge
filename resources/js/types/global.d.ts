import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { AxiosInstance } from 'axios';
import { route as ziggyRoute } from 'ziggy-js';
import { PageProps as AppPageProps } from './';

declare global {
    interface Window {
        axios: AxiosInstance;
        Echo: any;
        Pusher: any;
    }

    /* eslint-disable no-var */
    var route: typeof ziggyRoute;
}

declare module 'vue' {
    interface ComponentCustomProperties {
        route: typeof ziggyRoute;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}

declare module 'ziggy-js' {
  import type { Config } from 'ziggy-js';

  export const Ziggy: Config;
  export const ZiggyVue: {
    install: (app: any, config: Config) => void;
  };
  export default function route(
    name?: string,
    params?: any,
    absolute?: boolean,
    config?: Config
  ): string;
}