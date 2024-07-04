CREATE OR REPLACE VIEW v_website_domain_nameservers_history AS
SELECT
	website_id,
	raw_data->>'app_location' as app_location,
	raw_data->'data'->'nameservers' as nameservers,
	created_at
FROM
	monitor_queue
WHERE
	-- Domain Nameservers Monitor (App\Models\MonitorType::DNS_NAMESERVERS)
	monitor_type_id = 6
	AND raw_data->>'status' = 'success'
;