import { Config } from "ziggy-js";

export interface User {
[x: string]: any;
    user_id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
    ziggy: Config & { location: string };
    query: {
        [key: string]: any
    }
};
