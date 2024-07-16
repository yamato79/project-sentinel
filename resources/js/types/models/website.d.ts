declare type WebsiteModel = {
    website_id: number;
    name: string;
    slug: string;
    address: string;
    website_status_id: number;
    website_status?: WebsiteStatusModel;
    monitor_location_ids: number[];
    monitor_locations?: MonitorLocationModel[];
    notification_channels?: NotificationChannelModel[];
    notification_types?: NotificationTypeModel[];
    stacks?: StackModel[];
    is_monitor_active: boolean;
}