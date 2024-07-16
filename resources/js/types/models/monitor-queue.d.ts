declare type MonitorQueueModel = {
    monitor_queue_id: number;
    website?: WebsiteModel;
    monitor_type?: MonitorTypeModel;
    raw_data: any[];
    created_at: string;
}
