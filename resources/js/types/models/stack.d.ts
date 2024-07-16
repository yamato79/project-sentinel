declare type StackModel = {
    stack_id: number;
    name: string;
    slug: string;
    description: string;
    users_count?: number;
    websites_count?: number;
    created_by_user_id: number;
    created_by_user?: UserModel;
    users?: UserModel[];
    notification_types?: NotificationTypeModel[];
    notification_channels?: NotificationChannelModel[];
}
