declare type MonitorLocationModel = {
    monitor_location_id: number;
    name: string;
    slug: string;
    description: string;
    agent_url: string;
    is_active: boolean;
    websites?: WebsiteModel[];
}
