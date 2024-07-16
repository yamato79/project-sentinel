declare type NotificationChannelDriverModel = {
    notification_channel_driver_id: number;
    name: string;
    slug: string;
    description: string;
    fields: {
        label: string;
        name: string;
        required: boolean,
        type: string;
        placeholder: string;
        container_class: string;
    }[]
    is_active: boolean;
}
